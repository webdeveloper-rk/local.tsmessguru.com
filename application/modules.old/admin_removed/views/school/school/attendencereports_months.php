<?php 
$months = array("01"=>"January","02"=>"February","03"=>"March","04"=>"April","05"=>"May",
									"06"=>"June","07"=>"July","08"=>"August","09"=>"September","10"=>"October","11"=>"November","12"=>"December");	

if(isset($attendencereport)){
$extra_data = $attendencereport['extra'];
unset($attendencereport['extra']);
}
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
<h3>Attendance & Consumption Report </h3>
<?php 
$errors = validation_errors();
if($errors !=""){
?>
 <div class="validation_errors"><?php echo validation_errors(); ?>  </div>
<?php } ?>
<form method="post" action="">
<div class="box box-primary">
            <div class="box-header">
              <h3 class="box-title">Choose month and year to get the report</h3>
            </div>
            <div class="box-body">
              <!-- Date -->
              <div class="form-group">
                <label>Choose Month:</label>

                <div class="input-group date">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                 <select name="month" id="month" >
					<option value="">Select Month</option>
					<?php 
					$months = array("01"=>"January","02"=>"February","03"=>"March","04"=>"April","05"=>"May",
									"06"=>"June","07"=>"July","08"=>"August","09"=>"September","10"=>"October","11"=>"November","12"=>"December");
					foreach($months as $key_month =>$month_name) { 
					$selected = '';
					if($key_month == $sel_month){ $selected =  " selected =selected ";}
					echo '<option value="'.$key_month .'" '. $selected .'>'.$month_name.'</option>';
					  } ?>
					  </select>
                 
                </div>
                <!-- /.input group -->
              </div>
			       <label>Choose Year  :</label>

                <div class="input-group date">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                   
					 <select name="year" id="year" >
					<option value="">Select Year</option>
					<?php 
					for($i=2017;$i<=date('Y');$i++)
					{ 
							$selected = '';
							if( $i ==  $sel_year){ 
												$selected =  " selected =selected ";
											}
								echo '<option value="'.$i .'" '. $selected .'>'.$i.'</option>';
					}
					?>
					  </select> 
                 
                </div>
                <!-- /.input group -->
              </div>
			  
			 
              <!-- /.form group -->

              <!-- Date range -->
              <div class="box-footer">
                 
                <input type="submit" class="btn btn-info pull-right" value="Get Report" name="submit"> 
				 <br><br>
               <input type="submit" class="btn btn-info pull-right" value="Download Report" name="submit">  
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
 <script>
  $( function() {
			$( ".datepicker" ).datepicker({ 
			startDate: '09-01-2016',
			endDate: '+0d'});
  } );
  </script>
  <?php if(isset($sel_year)){ ?>
  <table class="table table-bordered table-striped  "  >
	 
	<tr class='bold'><td align="center">Attendance and Consumption Report  of  <span class='red'><b><?php echo $months[$sel_month]?> - <?php echo $sel_year;?> </span></td></tr>
	<tr>
		<td>
			<table id="example1"  class="table table-bordered table-striped dataTable" role="grid" aria-describedby="example1_info"> 
				 
				<tr class='bold'>
					<td>SLNO</td>
					<td>School Name</td>
					<td>School Code</td>
					 
					<td>Cat 1 Attendance (5,6,7)</td>
					<td>Cat 1 Per Day </td>
					<td>Amount </td>
					<td>Cat 2 Attendance  (8,9,10)</td>
						<td>Cat 2 Per Day </td>
						<td>Amount </td>
					<td>Cat 3 Attendance(Intermediate)</td>
						<td>Cat 3 Per Day </td>
						<td>Amount </td>
					<td>Total Attendance</td>					
 
					 <td>Allowed Amount</td>
					 <td>Consumption Amount</td>
					 <td>Remaining Amount</td>
					
					 
				</tr>
				<?php $i=1; 
				$total_amount = 0;
				foreach($attendencereport as $school_code=>$school_data){ 
				 
								?>
							<tr >
							 
								<td><?php echo $i;?></td>
								<td><?php echo $school_data['name'];?></td>
								<td><?php echo $school_code;?></td>

								<td><?php echo $school_data['grp1_att'];?></td>
								<td><?php echo $school_data['group_1_per_day'];?></td>
								<td><?php echo $school_data['cat1_amount'];?></td>
								
								
								<td><?php echo $school_data['grp2_att'];?></td>
								<td><?php echo $school_data['group_2_per_day'];?></td>
								<td><?php echo $school_data['cat2_amount'];?></td>
								
								
								<td><?php echo $school_data['grp3_att'];?></td>
								<td><?php echo $school_data['group_3_per_day'];?></td>
								<td><?php echo $school_data['cat3_amount'];?></td>
					
					
					
					<td><?php echo $school_data['attendence'];?></td>
					 				
					<td><?php echo  $school_data['allowed_amt'];?></td>
					<td><?php echo  $school_data['consumed_amt'];?></td>
					<td><?php echo  $school_data['remaining_amt'];?></td>
					
					 
								 
							</tr>
				<?php $i++; } ?>
				 
			</table>
		
		
		
		
		
		</td></tr>
		

</table>
 <script>
  $(function () {
  //  $("#example1").DataTable();
    $('#example1').DataTable({
		"pageLength": 300,
      "paging": true,
      "lengthChange": false,
      "searching": true,
      "ordering": true,
      "info": true,
	   "order": [[ 4, "desc" ]],
      "autoWidth": true
    });
  });
</script>
	 
  <?php } ?>