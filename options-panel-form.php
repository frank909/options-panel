<?php
/*
* option panel form
*/
$days = 31;
$months = 12;
$years = 1;
$hours = 12;
$minutes = 59;
?>

<style type="text/css">
	
	.red-flag-options-page {
		margin-top:10px;
		padding: 10px;
		width:90%;
		background: #ffffff;
		border:3px solid #ccc;
		border-radius: 10px;
	}
	.red-flag-main {		
		width:40%;
	}
	.red-flag-sidebar {		
		float:right;
		width: 40%;
		padding: 20px;
		padding-right: 10%;		
	}	
	
	
	
	fieldset {		
		width: 100%;
		margin: 25px 0 25px 12px;
		padding: 10px 0;
		border-bottom:3px solid #ccc;
	}
	fieldset.ending-group {
		display:block;
		margin: 25px 0 25px 12px;
	}
	fieldset legend{		
		font-weight:bold;		
	}	
	label {
		padding-left:10px;
	}
	label:first-child {
		padding-left:0;
	}
	#submit {
		margin: 0 20%;
	}	
	.red-flag-parking-date-group {
		padding: 10px 0;
	}
	
	#active, 
	#not-active,
	#scheduled-active {		
		padding:10px;
		background-color: #FF6666;
		color: #ffffff;
		font-size: 18px;
		border:1px solid #ccc;
		border-radius: 3px;
	}
	#not-active {		
		background-color: #339933;
		color: #ffffff;		
	}
	#scheduled-active {		
		background-color: #ffba00;
		color: #000000;		
	}
	.author {
		padding-left: 10px;
	}
	@media screen and (max-width: 1080px){
	#redFlagParking select{
			
			width: 50%;
		}
		#redFlagParking label { 
			float:left;
			
			width: 50%;
		}
		#redFlagParking .red-flag-parking-date-group {
			float:left;
		}
		#redFlagParking .red-flag-parking-date-group select,
		#redFlagParking .red-flag-parking-date-group label,
		{
			float:none;
			clear:none;
		}
	}
	@media screen and (min-width: 400px) and (max-width: 780px){		
	
				
		#redFlagParking input[type="text"] {
			width: 100%;
		}
		
		
		.red-flag-main {		
			width: 97%;
		}
		.red-flag-sidebar {		
			float:none;
			width: 97%;
		}
		
		#submit {
			margin: 0 40%;
		}	
	}
	
</style>
<div class="red-flag-options-page">
<h1>Red Flag Parking Settings</h1>
<div><?php echo $active; ?></div>
<?php if(!empty($get_set_by_user)) : ?>
	<p class="author">Set by <?php echo $get_set_by_user; ?>.</p>
<?php endif; ?>
	
<div class="red-flag-sidebar">
	<?php if( $start == 1 ): ?>
		
		<img src="<?php echo get_stylesheet_directory_uri(); ?>/flags/Red-Flag-Condition---Restricted-Parking.jpg" title="Restricted Parking, Tickets and Tow" alt="Restricted Parking, Tickets and Tow" />
		
	<?php else: ?>
		
		<img src="<?php echo get_stylesheet_directory_uri(); ?>/flags/Red-Flag-Condition---Normal-Parking.jpg" title="Normal Parking" alt="Normal Parking" />
	
	<?php endif; ?>
		
	<div id="all_errors">	
		<?php echo ( !empty( $Err['datetimeErr'] ))? '<div class="error">'.$Err['datetimeErr'].'</div>'  : ''; ?>
		<?php echo ( !empty( $Err['endTimeErr'] ))? '<div class="error">'.$Err['endTimeErr'].'</div>'  : ''; ?>		
		<?php echo ( !empty( $Err['startingMonthErr'] ))? '<div class="error">'.$Err['startingMonthErr'].'</div>'  : ''; ?>	
		<?php echo ( !empty( $Err['startingDayErr'] ))? '<div class="error">'.$Err['startingDayErr'].'</div>'  : ''; ?>	
		<?php echo ( !empty( $Err['startingYearErr'] ))? '<div class="error">'.$Err['startingYearErr'].'</div>'  : ''; ?>	
		<?php echo ( !empty( $Err['startingHourErr'] ))? '<div class="error">'.$Err['startingHourErr'].'</div>'  : '';  ?>
		<?php echo ( !empty( $Err['startingMinuteErr']))? '<div class="error">'.$Err['startingMinuteErr'].'</div>'  : '';  ?>
		<?php echo ( !empty( $Err['startingMeridiemErr'] ))? '<div class="error">'.$Err['startingMeridiemErr'].'</div>'  : ''; ?>
		<?php echo ( !empty( $Err['endingMonthErr'] ))? '<div class="error">'.$Err['endingMonthErr'].'</div>'  : '';  ?>
		<?php echo ( !empty( $Err['endingDayErr'] ))? '<div class="error">'.$Err['endingDayErr'].'</div>'  : '';  ?>
		<?php echo ( !empty( $Err['endingYearErr'] ))? '<div class="error">'.$Err['endingYearErr'].'</div>'  : '';  ?>
		<?php echo ( !empty( $Err['endingHourErr'] ))? '<div class="error">'.$Err['endingHourErr'].'</div>'  : '';  ?>
		<?php echo ( !empty( $Err['endingMinuteErr'] ))? '<div class="error">'.$Err['endingMinuteErr'].'</div>'  : '';  ?>
		<?php echo ( !empty( $Err['endingMeridiemErr'] ))? '<div class="error">'.$Err['endingMeridiemErr'].'</div>'  : '';  ?>
	</div>
	
</div>	

<div class="red-flag-main">
<form method="post" id="redFlagParking">
	
	<fieldset>
	<legend>HOME PAGE ALERT (required): </legend>
	<div>
		<label for="red-flag-alert">Text: </label>
		<input type="text" id="red-flag-alert" name="red_flag_parking[alert_message]" maxlength="128" size="40" value="<?php echo $alert_message;  ?>" />
	</div>
	</fieldset>
	
	<fieldset class="starting-group">	
		<legend>Starting Date (required):</legend>				
		
		<div class="red-flag-parking-date-group" id="Starting_Group">	
			
			<label for="Starting_Month">Month:</label>	
			<select name="red_flag_parking[starting_month]" id="Starting_Month">
				<option value=""> </option>		
				<?php for( $i = 1; $i <= $months; $i++ ): ?>			
				<option value="<?php echo $i; ?>" <?php selected( $get_starting_month, $i ); ?>><?php echo monthName($i); ?></option>
				<?php endfor; ?>
			</select>
			
			<label for="Starting_Day">Day:</label>	
			<select name="red_flag_parking[starting_day]" id="Starting_Day">
				<option value=""> </option>
				<?php for( $i = 1; $i <= $days; $i++ ):	?>
				<option value="<?php echo $i; ?>" <?php selected( $get_starting_day, $i ); ?>><?php echo $i; ?></option>
				<?php endfor; ?>	
			</select>
		
		
			<label for="Starting_Year">Year:</label>	
			<select name="red_flag_parking[starting_year]" id="Starting_Year">';
				<option value=""> </option>	
				<?php for( $y = 0; $y <= $years; $y++ ): ?>		
				<option value="<?php echo(date("Y")+$y); ?>" <?php echo selected( $get_starting_year, (date("Y")+$y) ); ?>><?php echo (date("Y")+$y); ?></option>
				<?php endfor; ?>
			</select>	
			
		</div>
		
		<div class="red-flag-parking-date-group">
			<label for="Starting_Hour">Hour:</label>
			<select name="red_flag_parking[starting_hour]" id="Starting_Hour">		
				<option value=""> </option>
				<?php for( $h = 1; $h <= 12; $h++):	?>			
				<option value="<?php echo $h; ?>" <?php selected( $get_starting_hour, $h )?>><?php echo $h; ?></option>
				<?php endfor; ?>			
			</select>&nbsp; : &nbsp;	
		
			<select name="red_flag_parking[starting_minute]" id="Starting_Minute">
				<?php for( $m = 0; $m < 60; $m++ ): ?>
				<option value="<?php echo $m; ?>" <?php echo selected( $get_starting_minute, $m )?>><?php echo sprintf( '%02d',$m);?></option>
				<?php endfor; ?>
			</select>&nbsp; : &nbsp;		
		
			<select name="red_flag_parking[starting_meridiem]" id="Starting_Meridiem">
				<option value=""> </option>
				<option value="AM" <?php  selected( $get_starting_meridiem, 'AM' ); ?>>AM</option>
				<option value="PM" <?php  selected( $get_starting_meridiem, 'PM' ); ?>>PM</option>
			</select>
		</div>
		
	</fieldset>
		
	<fieldset class="ending-group">	
		<legend>Ending Date:</legend>
					
		<div class="red-flag-parking-date-group">	
			<label for="Ending_Month">Month:</label>		
			<select name="red_flag_parking[ending_month]" id="Ending_Month">		
				<option value=""></option>
				<?php for($i = 1; $i <= 12; $i++ ): ?>
					<option value="<?php echo $i; ?>" <?php selected( $get_ending_month, $i ); ?>><?php echo monthName($i); ?></option>		
				<?php endfor; ?>		
			</select>
			
			<label for="Ending_Day">Day:</label>		
			<select name="red_flag_parking[ending_day]" id="Ending_Day">';
				<option value=""></option>		
				<?php for( $i = 1; $i <= 31; $i++ ): ?>
				<option value="<?php echo $i ?>" <?php selected( $get_ending_day, $i ); ?>><?php echo $i; ?></option>
				<?php endfor; ?>		
			</select>
			
			<label for="End_Year">Year:</label>		
			<select name="red_flag_parking[ending_year]" id="Ending_Year">
				<option value=""> </option>		
				<?php for( $i = 0; $i <= 2; $i++ ): ?>			
				<option value="<?php echo(date("Y")+$i); ?>" <?php selected( $get_ending_year, (date("Y")+$i) ); ?>><?php echo(date("Y")+$i); ?></option>
				<?php endfor; ?>	
			</select>	
		</div>
		
		<div class="red-flag-parking-date-group">		
			<label for="End_Hour">Hour:</label>		
			<select name="red_flag_parking[ending_hour]" id="Ending_Hour">		
				<option value=""> </option>			
				<?php for( $i = 1; $i <= 12; $i++ ): ?>
					<option value="<?php echo $i; ?>" <?php selected( $get_ending_hour, $i )?>><?php echo $i; ?></option>
				<?php endfor; ?>
			</select>&nbsp; : &nbsp;	
				
			<select name="red_flag_parking[ending_minute]" id="Ending_Minute">						
				<?php for( $i = 0; $i < 60; $i++): ?>						
					<option value="<?php echo $i; ?>" <?php echo selected( $get_ending_minute, $i ); ?>><?php echo sprintf( '%02d',$i); ?></option>
				<?php endfor; ?>
			</select>&nbsp; : &nbsp;	
			
			
			<select name="red_flag_parking[ending_meridiem]" id="Ending_Meridiem">
				<option value=""> </option>
				<option value="AM" <?php  selected( $get_ending_meridiem, 'AM' ); ?>>AM</option>
				<option value="PM" <?php  selected( $get_ending_meridiem, 'PM' ); ?>>PM</option>
			</select>		
		</div>		
	</fieldset>		
			
	<fieldset>		
		<legend>Reset To Default:</legend>
		<div>
			<input type="checkbox" name="red_flag_parking[reset]" id="reset" value="1" /> Master Reset (Reset all options)
		</div>	
	</fieldset>	
	<?php submit_button(); ?>
</form>
<script type="text/javascript">
(function($){

	//console.log('testing #1');	
	
	$("#redFlagParking").submit(function()
	{
		//console.log('testing #2');	
		
		// get all the inputs into an array.
		var $inputs = $('#redFlagParking :input');

		// get an associative array of just the values.
		var values = {};
		
		$inputs.each(function() {
			values[this.name] = $(this).val();
		});
		
		// clear all errors
		$("#all_errors").html('');	
		
		// testing serialized inputs
		console.log(values);
		
		if($("#reset").is(":checked"))
		{
			$("#all_errors").html('RESET!');
			return true;
		}
		else {
			if(values['red_flag_parking[starting_month]'] == '')
			{
				$("#all_errors").append("<div class='error'>Starting month is required.</div>");
				
			}
			
			if(values['red_flag_parking[starting_day]'] == '')
			{
				$("#all_errors").append("<div class='error'>Starting day is required.</div>");
				
			}
			
			if(values['red_flag_parking[starting_year]'] == '')
			{
				$("#all_errors").append("<div class='error'>Starting year is required.</div>");	
				
			}
			
			if(values['red_flag_parking[starting_hour]'] == '')
			{			
				$("#all_errors").append("<div class='error'>Starting hour is required.</div>");
				
			}

			if(values['red_flag_parking[starting_meridiem]'] == '')
			{			
				$("#all_errors").append("<div class='error'>Starting meridiem is required.</div>");
				
			}
			/* 		
			if(values['red_flag_parking[ending_month]'] == '')
			{	
				$("#all_errors").append("<div class='error'>Ending month is required.</div>");	
				
			}
			
			if(values['red_flag_parking[ending_day]'] == '')
			{						
				$("#all_errors").append("<div class='error'>Ending day is required.</div>");
				
			}
			
			if(values['red_flag_parking[ending_year]'] == '')
			{	
				$("#all_errors").append("<div class='error'>Ending year is required.</div>");
				
			}
			
			if(values['red_flag_parking[ending_hour]'] == '')
			{		
				$("#all_errors").append("<div class='error'>Ending hour is required.</div>");
				
			}
			
			if(values['red_flag_parking[ending_meridiem]'] == '')
			{			
				$("#all_errors").append("<div class='error'>Ending meridiem is required.</div>");
				
			} */
			
			if($("#all_errors").is(':empty')) 
			{
				return true;
			}
			else {
				
				return false;
			}
		}		
		
	});	
	
})(jQuery);

</script>

</div>
</div>


