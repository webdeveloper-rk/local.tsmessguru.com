<?php 
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
<h3>Bill Collection Consolidated Report </h3>
<?php 
$errors = validation_errors();
if($errors !=""){
?>
 <div class="validation_errors"><?php echo validation_errors(); ?>  </div>
<?php } ?>
<form method="post" action="">
<div class="box box-primary">
            <div class="box-header">
              <h3 class="box-title">Choose dates to get the report</h3>
            </div>
            <div class="box-body">			<!-- CHOOSE -->						<?php if($this->session->userdata("user_role") == "subadmin"){			?>   <div class="form-group">                <label>Choose :</label>                <div class="input-group date">                  					<?php echo school_selection($this->input->post('school_id'));?>                </div>                <!-- /.input group -->              </div>			  		<?php } ?>						   			
              <!-- Date -->
              <div class="form-group">
                <label>From Date:</label>

                <div class="input-group date">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                  <input type="text" name="fromdate"  value="<?php echo $from_date;?>"  required  class="form-control pull-right datepicker"  >
                </div>
                <!-- /.input group -->
              </div>
			  
			   <!-- Date -->
              <div class="form-group">
                <label>To Date:</label>

                <div class="input-group date">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                  <input type="text" name="todate" value="<?php echo $to_date;?>"  required  class="form-control pull-right datepicker"   >
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
	<tr class='bold'><td align="center"><?php echo $this->config->item('society_name'); ?>  <?php echo $sname; ?></td></tr>
	<tr class='bold'><td align="center"> BILL Collection STATEMENT FOR THE dates between <span class='red'><b><?php echo $from_date . "</b></span> and  <span class='red'><b>". $to_date;?></b></span></td></tr>
	 
	<tr>
		<td>
			<table id="example1"  class="table table-bordered table-striped dataTable" role="grid" aria-describedby="example1_info"> 
				 
				<tr class='bold'>
					<td>SLNO</td>
					<td>Date</td>
				 
				 
					<!--- PURCHASE COLUMNS -->
					<td>Number Of Guest</td >
					
					<!---- Total columns -->
					<td>Total</td>
					 
					 
					 
				</tr>
				<?php $i=1; 
				$total_amt = 0;
				foreach($rs->result() as $rowitem){ 
				
				$total_amt = $total_amt + $rowitem->amount;
				 
								?>
							<tr >
								<td><?php echo $i;?></td>
								<td><?php echo  date('d-m-Y',strtotime($rowitem->entry_date));?></td>								<td><?php echo  $rowitem->no_of_guests;?></td>
								<!--- Opening balance COLUMNS -->
								 
							  
								<!--- PURCHASE COLUMNS -->
								<td><?php echo number_format($rowitem->amount,2,'.', '');?></td>								 
					
								 								 								 
					
							 
								
								 
							</tr>
				<?php $i++; } ?>
				<tr class="bold"><td colspan="3" align="right">Total: </td><td>&nbsp;&nbsp;&nbsp;Rs&nbsp;&nbsp;<?php echo  number_format($total_amt,2); ?> </td></tr>
			</table>
		
		
		
		
		
		</td></tr>
		

</table> 
	 
  <?php } ?>