<?php  $hour_1 = date('H');  $secretary_roles = array('secretary','10100');	 if (  in_array($this->session->userdata("school_code"), $secretary_roles)) {		$ehour = 6;	 }	 else {		 $ehour = 13;	 }if($hour_1 < $ehour ){	//echo "<h1>This Report Disabled untill 6:00 PM";	//return;die;	 }
$from_date = '';
$to_date = '';
if($this->input->post('fromdate')!=null)
	$from_date = $this->input->post('fromdate');
if($this->input->post('todate')!=null)
	$to_date = $this->input->post('todate');

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
<h3> Surplus/deficit of diet funds Report </h3>
<?php 
$errors = validation_errors();
if($errors !=""){
?>
 <div class="validation_errors"><?php echo validation_errors(); ?>  </div>
<?php }echo $this->session->flashdata('message'); ?>
<form method="post" action="" onsubmit="return days_validate()">
<div class="box box-primary">
            <div class="box-header">
              <h3 class="box-title">Choose dates to get the report</h3>
            </div>
            <div class="box-body">
              <!-- Date -->
              <div class="form-group">
                <label>Choose From  Date :</label>

                <div class="input-group date">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                  <input type="text" name="fromdate" id="fromdate"  value="<?php echo $from_date;?>"  required  class="form-control pull-right datepicker"  >
                 
                </div>
                <!-- /.input group -->
              </div>
			       <label>Choose To Date  :</label>

                <div class="input-group date">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                  <input type="text" name="todate"  id="todate"  value="<?php echo $to_date;?>"  required  class="form-control pull-right datepicker"  >
                 
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
  <?php if($from_date!=''){ ?>
  <table class="table table-bordered table-striped  "  >
	 
	<tr class='bold'><td align="center"> Surplus/deficit of diet funds Report between Dates of  <span class='red'><b><?php echo $rdate?> and <?php echo $tdate?> </span></td></tr>
	<tr>
		<td>
			<table id="example1"  class="table table-bordered table-striped dataTable" role="grid" aria-describedby="example1_info"> 
				 
				<tr class='bold'>
					<td>SLNO</td>
					<td>School Name</td>
					<td>School Code</td>
					 
					 
					<td>Attendence</td>					
					 
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
				 
					 
					<td><?php echo $school_data['attendence'];?></td>
					 			
					<td><?php echo  $school_data['allowed_amt'];?></td>
					<td><?php echo  $school_data['consumed_amt'];?></td>
					<td><?php echo  $school_data['remaining_amt'];?></td>
					
					 
								 
							</tr>
				<?php $i++; } ?>
				 
			</table>
		
		
		
		
		
		</td></tr>
		

</table>
 
	 
  <?php } ?>