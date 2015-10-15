<?php 
/*
* Options panel screen for Red Flag Parking.
* Setting dates to start and stop Red Flag Parking.
* The Red Flag Parking system is based on WP Options API.
*/
class RedFlagParkingOptionPanel
{
	/**
	 * The id of this widget.
	 *
	 */
	const wid = 'red_flag_parking';

	/**
	* Start up
	*
	*/
	public function __construct()
	{
		
		add_action( 'admin_menu', array( $this, 'add_red_flag_page' ),10 );
		
		add_action( 'admin_init', array( $this, 'register_red_flag_settings' ),11 );	
		
	}
	
	/**
	* Add menu page
	*
	*/
	public function add_red_flag_page()
	{		
			
		add_menu_page( 
			'Red Flag Parking Settings', 
			'Red Flag Parking', 
			'manage_categories', 
			'red-flag-parking-setting-admin',
			array( $this, 'red_flag_parking_settings_page' ), 
			'dashicons-flag', 
			'2.99'
		);		
	}
	
	/**
	* Register settings and sets some default values.
	* Remove Role capabilities.
	*
	*/
	public function register_red_flag_settings()
	{
		// Register settings.
		register_setting( 		
			'red-flag-dashboard-group',			
			'red_flag_parking'			
		);
				
		// Register default options...
		self::update_red_flag_widget_options(
			self::wid,                                  //The  widget id
			array(                                      //Associative array of options & default values
						'reset' => 0,
						'start_datetime' => '',
						'end_datetime' => '',
						'starting_year' => '',
						'starting_month' => '',
						'starting_day' => '',
						'starting_hour' => '',
						'starting_minute' => '',
						'starting_meridiem' => '',
						'ending_year' => '',
						'ending_month' => '',
						'ending_day' => '',
						'ending_hour' => '',
						'ending_minute' => '',
						'ending_meridiem' => '',
						'set_by_user' => '',
						'alert_message' => 'Red Flag Parking Restrictions, Tickets and Tow in Effect Today',
						'active' => 0,
						'days remaining' => 0
				),
			true                                        //Add only (will not update existing options)
		);
		
		// get after option update
		$role = get_role( 'editor' );	
		$role->remove_cap( 'manage_network_options' );
	}

	public function red_flag_parking_settings_page()
	{
			
		// define validation variables and set them empty values.
		$startingMonthErr = $startingDayErr = $startingYearErr = "";
		$startingMeridiemErr = $startingHourErr = $startingMinuteErr = "";
		
		$endingMonthErr = $endingDayErr = $endingYearErr = "";
		$endingMeridiemErr = $endingHourErr = $endingMinuteErr = "";
		
		// Error array.		
		$Err = array();			
	
		
		//get saved data	
		if ( !$widget_options = get_site_option( self::wid ) )
		{
		   
		   $widget_options = array();
		   
		}
		
		// Request HTTP POST && Form submitted.
		if ( $_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['red_flag_parking']) ) 
		{
			
				$widget_options['alert_message'] = $_POST['red_flag_parking']['alert_message'];
				
				
				// create the starting time for Red Flag.
				$start_datetime = createDateTime(
								$_POST['red_flag_parking']['starting_month'],
								$_POST['red_flag_parking']['starting_day'],
								$_POST['red_flag_parking']['starting_year'],
								$_POST['red_flag_parking']['starting_hour'],
								$_POST['red_flag_parking']['starting_minute'],
								$_POST['red_flag_parking']['starting_meridiem']
							);
				$widget_options['start_datetime'] = $start_datetime;
				
				// create the ending time for Red Flag.
				 $end_datetime = createDateTime(
								(isset($_POST['red_flag_parking']['ending_month']))? $_POST['red_flag_parking']['ending_month'] : date('n'),
								(isset($_POST['red_flag_parking']['ending_day']))? $_POST['red_flag_parking']['ending_day'] : date('d'),
								(isset($_POST['red_flag_parking']['ending_year']))? $_POST['red_flag_parking']['ending_year'] : date('Y')+2,
								(isset($_POST['red_flag_parking']['ending_hour']))? $_POST['red_flag_parking']['ending_hour'] : date('H'),
								(isset($_POST['red_flag_parking']['ending_minute']))? $_POST['red_flag_parking']['ending_minute'] : date('i'),
								(isset($_POST['red_flag_parking']['ending_meridiem']))? $_POST['red_flag_parking']['ending_meridiem'] : date('AM')
							);
				$widget_options['end_datetime'] = $end_datetime; 
				
					
			$widget_options['ending_month'] = $_POST['red_flag_parking']['ending_month'];			
			$widget_options['ending_day'] = $_POST['red_flag_parking']['ending_day'];
			$widget_options['ending_year'] = $_POST['red_flag_parking']['ending_year'];
			$widget_options['ending_hour'] = $_POST['red_flag_parking']['ending_hour'];
			$widget_options['ending_meridiem'] = $_POST['red_flag_parking']['ending_meridiem'];
			
			//no validation required always 00.
			$widget_options['ending_minute'] = $_POST['red_flag_parking']['ending_minute'];
				
				// days remaining for red flag.
				$widget_options['days remaining'] = dateDiff($start_datetime, $end_datetime);
				
				
				// validate start date is less than end date.
				if($start_datetime > $end_datetime)
				{	
					
					$Err['datetimeErr'] = "Starting date is greater than Ending date.";
					
				}
				
				// validate End date not in past.
				if(date('Y-m-d H:i:s') >= $end_datetime)
				{
					
					//$Err['endTimeErr'] = "Ending date is less than today's date.";
					
				}
				
				// validate start month is not empty.					
				if(empty($_POST['red_flag_parking']['starting_month']))
				{
					
					$Err['startingMonthErr'] = "Starting month is required.";
					
				}
				else{
					
					$widget_options['starting_month'] = $_POST['red_flag_parking']['starting_month'];
					
				}
				
				// validate start day is not empty.
				if(empty($_POST['red_flag_parking']['starting_day']))
				{
					
					$Err['startingDayErr'] = "Starting day is required.";
					
				}
				else{
					
					$widget_options['starting_day'] = $_POST['red_flag_parking']['starting_day'];
							
				}
				
				// validate start year is not empty.
				if(empty($_POST['red_flag_parking']['starting_year']))
				{
					
					$Err['startingYearErr'] = "Starting year is required.";
					
				}
				else {
				
					$widget_options['starting_year'] = $_POST['red_flag_parking']['starting_year'];
					
				}
				
				// validate start hour is not empty.
				if(empty($_POST['red_flag_parking']['starting_hour']))
				{
					
					$Err['startingHourErr'] = "Starting hour is required.";
					
				}
				else{
					
					$widget_options['starting_hour'] = $_POST['red_flag_parking']['starting_hour'];
				
				}
				
				// no validation required since always 00.
				$widget_options['starting_minute'] = $_POST['red_flag_parking']['starting_minute'];
				
				
				// validate start meridiem is not empty.
				if(empty($_POST['red_flag_parking']['starting_meridiem']))
				{
					
					$Err['startingMeridiemErr'] = "Starting meridiem is required.";
					
				}
				else{
					
					$widget_options['starting_meridiem'] = $_POST['red_flag_parking']['starting_meridiem'];
					
				}				
				
				
				/* uncomment this section if end date is required.
				// validate end month is not empty.
				if(empty($_POST['red_flag_parking']['ending_month']))
				{
					
					$Err['endingMonthErr'] = "Ending month is required.";
					
				}
				else{
					
					$widget_options['ending_month'] = $_POST['red_flag_parking']['ending_month'];
				
				}
				
				// validate end day is not empty.
				if(empty($_POST['red_flag_parking']['ending_day']))
				{
					
					$Err['endingDayErr'] = "Ending day is required.";
					
				}
				else{
					
					$widget_options['ending_day'] = $_POST['red_flag_parking']['ending_day'];
					
				}
				
				// validate end year is not empty.
				if(empty($_POST['red_flag_parking']['ending_year']))
				{
					
					$Err['endingYearErr'] = "Ending year is required.";
					
				}
				else{
					
					$widget_options['ending_year'] = $_POST['red_flag_parking']['ending_year'];
				
				}
				
				// validate end hour is not empty.
				if(empty($_POST['red_flag_parking']['ending_hour']))
				{
					
					$Err['endingHourErr'] = "Ending hour is required.";
					
				}
				else {
					
					$widget_options['ending_hour'] = $_POST['red_flag_parking']['ending_hour'];
				
				}
				
				
				//no validation required always 00.
				$widget_options['ending_minute'] = $_POST['red_flag_parking']['ending_minute'];
				
				
				// validate end meridiem is not empty.
				if(empty($_POST['red_flag_parking']['ending_meridiem']))
				{
					
					$Err['endingMeridiemErr'] = "Ending meridiem is required.";
					
				}
				else{
					
					$widget_options['ending_meridiem'] = $_POST['red_flag_parking']['ending_meridiem'];
				
				}
				*/
				 
				
				
				
				// validate reset is zero or value of 1.
				if(empty($_POST['red_flag_parking']['reset']))
				{
					
					$widget_options['reset'] = 0;
					
				}
				else{
					
					
					$widget_options['reset'] = $_POST['red_flag_parking']['reset']; 
				}
			
			
			
			//  and not reset then add the current user and active.
			if( empty($Err) )  
			{		
				
				
				// validate end month is set.
				if(empty($_POST['red_flag_parking']['ending_month']))
				{
					
					$widget_options['ending_month'] = date('n');
					
				}
				
				
				// validate end day is set.
				if(empty($_POST['red_flag_parking']['ending_day']))
				{
					$widget_options['ending_day'] = date('d');
					
					
				}
				
				
				// validate end year is not empty.
				if(empty($_POST['red_flag_parking']['ending_year']))
				{
					
					$widget_options['ending_year'] = date('Y')+2;					
					
				}
				
				
				// validate end hour is not empty.
				if(empty($_POST['red_flag_parking']['ending_hour']))
				{
					
					$widget_options['ending_hour'] = 8;
					
				}				
				
				// validate end meridiem is not empty.
				if(empty($_POST['red_flag_parking']['ending_meridiem']))
				{
						
					$widget_options['ending_meridiem'] = 'AM';
					
				}
				
							
				
				
				$current_user = wp_get_current_user();	
				
				$widget_options['set_by_user'] = $current_user->user_login;
				
				$widget_options['active'] = 1;
				
				$widget_options['start_conversion'] = 1;
				
				$start = $widget_options['start_conversion'];
				
				
			}
									
			// delete options.
			if($widget_options['reset'] == 1)
			{
				// used to reset 
				self::delete_red_flag_widget_options( 'red_flag_widget_options' );
				
				//Red Flag ending time was reached. reset. reset. reset.			
		
				//Register default settings...
				self::update_red_flag_widget_options(
					self::wid,                                  //The  widget id
					array(                                      //Associative array of options & default values
						'reset' => 0,
						'start_datetime' => '',
						'end_datetime' => '',
						'starting_year' => '',
						'starting_month' => '',
						'starting_day' => '',
						'starting_hour' => '',
						'starting_minute' => '',
						'starting_meridiem' => '',
						'ending_year' => '',
						'ending_month' => '',
						'ending_day' => '',
						'ending_hour' => '',
						'ending_minute' => '',
						'ending_meridiem' => '',
						'set_by_user' => '',
						'alert_message' => 'Red Flag Parking Restrictions, Tickets and Tow in Effect Today',
						'active' => 0,
						'days remaining' => 0,
						'start_conversion' => 0
					),
					true                                        //Add only (will not update existing options)
				);
		
			}
			else {
				
				//save update        
				self::update_red_flag_widget_options( self::wid, $widget_options );
			
			}
			
		}
		
		
	
		// get widget options from the network database table.
		$get_widget_options = self::get_red_flag_widget_options('red_flag_parking');
		$alert_message = $get_widget_options['alert_message'];		
		$get_starting_month = $get_widget_options['starting_month'];
		$get_starting_day = $get_widget_options['starting_day'];
		$get_starting_year = $get_widget_options['starting_year'];
		$get_starting_hour = $get_widget_options['starting_hour'];
		$get_starting_minute = $get_widget_options['starting_minute'];
		$get_starting_meridiem = $get_widget_options['starting_meridiem'];
		$get_ending_month = $get_widget_options['ending_month'];
		$get_ending_day = $get_widget_options['ending_day'];
		$get_ending_year = $get_widget_options['ending_year'];
		$get_ending_hour = $get_widget_options['ending_hour'];
		$get_ending_minute = $get_widget_options['ending_minute'];
		$get_ending_meridiem = $get_widget_options['ending_meridiem'];
		$get_set_by_user = $get_widget_options['set_by_user'];	
		$start = $get_widget_options['start_conversion'];		
		//echo "<pre>". print_r($get_widget_options, 1)."</pre>";	
		
		// set message to display on admin page.
			if( isset($get_widget_options['active']) && $get_widget_options['active'] == 1 ) 
			{
				
				
				// check to see if started.
				if(strtotime($get_widget_options['start_datetime']) < time() ) 
				{
			
					$active = '<div id="active"> The Red Flag Parking is ACTIVE.</div>';
					
				} 
				else {
					
					// default when start time is in advance.
					$active = '<div id="scheduled-active">Red Flag Parking  starting on ' . monthName($get_starting_month). 
					' ' . $get_starting_day . ', ' . $get_starting_year . 
					' at '. $get_starting_hour . ':' . $get_starting_minute . ' ' . $get_starting_meridiem.
					'</div>';
				
				}		
			} 
			else{
				
				$active = '<div id="not-active"> The Red Flag Parking is NOT ACTIVE.</div>';
			}
		
		
		
		/* $end_time = $get_widget_options['end_datetime'];	
		$end = strtotime($end_time);
		echo "Is active? " . isset($get_widget_options['active']);
		echo "<br> end time ".date('H:i:s',$end);	
		echo "<br> server time. ". date('H:i:s');	 */
		
		// include the form.
		require_once 'options-panel-form.php';		
	}
	
	/**
     * Gets one specific option for the specified widget.
     * @param $widget_id
     * @param $option
     * @param null $default
     *
     * @return string
     */
    public static function get_red_flag_widget_option( $widget_id, $option, $default=NULL ) {

        $opts = self::get_dashboard_widget_options($widget_id);

        //If widget opts dont exist, return false
        if ( ! $opts )
            return false;

        //Otherwise fetch the option or use default
        if ( isset( $opts[$option] ) && ! empty($opts[$option]) )
            return $opts[$option];
        else
            return ( isset($default) ) ? $default : false;

    }

	
    /**
     * Gets the options for a widget of the specified name.
     *
     * @param string $widget_id Optional. If provided, will only get options for the specified widget.
     * @return array An associative array containing the widget's options and values. False if no opts.
     */
    public function get_red_flag_widget_options( $widget_id='' )
    {
        // Add these capabilities before option update
		$role = get_role( 'editor' );
		$role->add_cap( 'manage_network_options' );
		
		
		//Fetch ALL dashboard widget options from the db...
        $opts = get_site_option( 'red_flag_widget_options' );

        //If no widget is specified, return everything
        if ( empty( $widget_id ) )
            return $opts;

        //If we request a widget and it exists, return it
        if ( isset( $opts[$widget_id] ) )
            return $opts[$widget_id];

        //Something went wrong...
        return false;
    }
  

    /**
     * Saves an array of options for a single dashboard widget to the database.
     * Can also be used to define default values for a widget.
     *
     * @param string $widget_id The name of the widget being updated
     * @param array $args An associative array of options being saved.
     * @param bool $add_only If true, options will not be added if widget options already exist
     */
    public function update_red_flag_widget_options( $widget_id , $args=array(), $add_only=false )
    {
		// Add these capabilities before option update
		$role = get_role( 'editor' );
		$role->add_cap( 'manage_network_options' );	
		
		//Fetch ALL dashboard widget options from the db...
        $opts = get_site_option( 'red_flag_widget_options' );

        //Get just our widget's options, or set empty array
        $w_opts = ( isset( $opts[$widget_id] ) ) ? $opts[$widget_id] : array();

        if ( $add_only ) {
            //Flesh out any missing options (existing ones overwrite new ones)
            $opts[$widget_id] = array_merge($args,$w_opts);
        }
        else {
            //Merge new options with existing ones, and add it back to the widgets array
            $opts[$widget_id] = array_merge($w_opts,$args);
        }

        //Save the entire widgets array back to the db
        return update_site_option('red_flag_widget_options', $opts);
    }
	
	/**
	* deletes all network options in the database.
	*
	* @param string $widget_id The name of the widget being deleted.
	*/
	public function delete_red_flag_widget_options($widget_id)
	{
		// Add these capabilities before option update
		$role = get_role( 'editor' );
		$role->add_cap( 'manage_network_options' );				
		
		return delete_site_option( $widget_id );
		
	}
}
 // admin pages are only allowed to access the Red Flag Option Panel.
 if( is_admin() )
 {
    $red_flag_parking_settings_page = new RedFlagParkingOptionPanel(); 
 }
 
