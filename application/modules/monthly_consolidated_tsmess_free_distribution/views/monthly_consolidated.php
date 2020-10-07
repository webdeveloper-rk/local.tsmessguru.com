 
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
<h3>Item distribution Report </h3>
<?php 
$errors = validation_errors();
if($errors !=""){
?>
 <div class="validation_errors"><?php echo validation_errors(); ?>  </div>
<?php } ?>
<form method="post" action="">
<div class="box box-primary">
           
            <div class="box-body">
              <!-- Date -->
             
              <!-- /.form group -->

              <!-- Date range -->
              <div class="box-footer">
               <!--  
                <input type="submit" class="btn btn-info pull-right" value="Get Report" name="submit"> 
				 <br><br>-->
               <a  href="#" class="btn btn-info" onclick="javascript:window.print();">Print</a>               <a  href="<?php echo site_url("monthly_consolidated_tsmess_free_distribution");?>?download=download" class="btn btn-info pull-right" >Download Report</a>
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
  <div class='table-responsive'>
  <table class="table table-bordered table-striped  "  >
	 
	<tr class='bold'><td align="center">Item Distribution Report </span></td></tr>
	<tr>
		<td>
			<table id="example1"  class="table table-bordered table-striped dataTable" role="grid" aria-describedby="example1_info"> 
				 
				<tr class='bold'>
					<td>SLNO</td>
					<td>School Name</td>
					<td>School Code</td>					 					<td>District</td>					 					 <td>Free Distribution Amount</td> 					 <td></td> 					 					 
				</tr>
				<?php $i=1; 
				$total_amount = 0;								 foreach( $free_distributions_rs->result() as $school_data){ 
				 
				 //print_a($school_data,0);				 	$total_amount = 	$total_amount + $school_data->total_distributed;
								?>
							<tr >
							 
								<td><?php echo $i;?></td>
								<td><?php echo $school_data->name;?></td>
								<td><?php echo $school_data->school_code ;?></td> 								<td><?php echo $school_data->district_name ;?></td>								 								<td><?php echo number_format($school_data->total_distributed,2);?></td>								<td> <a  href="<?php echo site_url("monthly_consolidated_tsmess_free_distribution/schoolview/".$school_data->school_id);?>" class="btn btn-info noprint no-print" >View Distribution items</a></td>								 					 
							</tr>
				<?php $i++; } ?>
				 
			</table>
		
		
		
		
		
		</td></tr>
		

</table></div> 