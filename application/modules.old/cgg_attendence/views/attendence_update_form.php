<?php 
$months = array("01"=>"January","02"=>"February","03"=>"March","04"=>"April","05"=>"May",
									"06"=>"June","07"=>"July","08"=>"August","09"=>"September","10"=>"October","11"=>"November","12"=>"December");	 
	?>
<style>
.bold td
{
	font-weight:bold;
}
.red
{
	color:#FF0000;
}
</style>
<h3>SAMS Attendance Update  </h3>
<?php 
$errors = validation_errors();
if($errors !=""){
?>
 <div class="validation_errors"><?php echo validation_errors(); ?>  </div>
<?php }  echo $this->session->flashdata('message');?>
<form method="post" action="">
<div class="box box-primary">
            <div class="box-header">
              <h3 class="box-title">Choose date to get and update the  Attendence</h3>
            </div>
            <div class="box-body">
              <!-- Date -->
              <div class="form-group">
                <label>Choose Date:</label>

                <div class="input-group date">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>				   <select name="day" id="day" required  style=" height: 34px;">					<option value="">Select Day</option>					<?php  $selected = '';					for($i=1;$i<=31;$i++){						if($i<10)							$key_day = "0".$i;						else							$key_day =  $i;												if($key_day == $this->input->post('day')){ $selected =  " selected =selected ";}												echo '<option value="'.$key_day .'" '. $selected .'>'.$key_day.'</option>';						$selected = '';					}					?>					</select>
                 <select name="month" id="month" required style=" height: 34px;">
					<option value="">Select Month</option>
					<?php 					$sel_month = $this->input->post('month');
					$months = array("01"=>"January","02"=>"February","03"=>"March","04"=>"April","05"=>"May",
									"06"=>"June","07"=>"July","08"=>"August","09"=>"September","10"=>"October","11"=>"November","12"=>"December");
					foreach($months as $key_month =>$month_name) { 
					$selected = '';
					if($key_month == $sel_month){ $selected =  " selected =selected ";}
					echo '<option value="'.$key_month .'" '. $selected .'>'.$month_name.'</option>';
					  } ?>
					  </select>
                  <select name="year" id="year" required style=" height: 34px;">					<option value="">Select Year</option>					<?php  $selected = '';					for($key_year=2018;$key_year<=date('Y');$key_year++){												if($key_year == $this->input->post('year')){ $selected =  " selected =selected ";}						echo '<option value="'.$key_year .'" '. $selected .'>'.$key_year.'</option>';						$selected = '';					}					?>					</select>
                </div>
                <!-- /.input group -->
              </div>
			       
  
			  
			 
              <!-- /.form group --><div><label style='color:#FF0000;font-size:16px;'>Note :   This Report is will fetch data from  SAMS and it will OVERWRITE the attendence for the selected date.   </label>                 </div>

              <!-- Date range -->
              <div class="box-footer">					  
               <input type="submit" class="btn btn-info pull-right" value="Update Report" name="submit">  
              </div>
              <!-- /.form group -->

              <!-- Date and time range -->
              
              <!-- /.form group -->

              <!-- Date and time range -->
              
              <!-- /.form group -->

            </div>
            <!-- /.box-body -->
          </div>
		  </form>
 
   <?php      if(isset($output_text))   {	   echo $output_text;   }      ?>