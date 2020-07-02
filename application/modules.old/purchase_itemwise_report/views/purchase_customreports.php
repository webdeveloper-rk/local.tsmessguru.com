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
<h3>PURCHASE Consolidated Report </h3>
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
            <div class="box-body">			<!-- CHOOSE -->												   <div class="form-group">                <label>TYPE OF ITEMS:</label>                <div class="input-group date">                  					<?php 					$item_types = array('provisional','nonprovisional','vegetables','fruits','eggs','chicken','mutton','bakery items','milk');?>					                  <select name="item_type">				  <?php foreach($item_types as $item)				  {					 ?><option <?php if($item==$this->input->post('item_type') ) { echo " selected  " ;} ?> value="<?php echo $item;?>"><?php echo ucfirst($item);?></option><?php  				  }?>				  </select>                </div>                <!-- /.input group -->              </div>			
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
	<tr class='bold'><td align="center"><?php echo $this->config->item('society_name'); ?>  <?php echo $this->session->userdata("user_name"); ?></td></tr>
	<tr class='bold'><td align="center"><?php echo ucfirst($item_type); ?> ITEMS - PURCHASE STATEMENT FOR THE dates between <span class='red'><b><?php echo $from_date . "</b></span> and  <span class='red'><b>". $to_date;?></b></span></td></tr>
	<tr class='bold'><td align="center">Note: Qty will be measures in Kg / Litre / Unit / Dozens </b></span></td></tr>
	<tr>
		<td>
			<table id="example1"  class="table table-bordered table-striped dataTable" role="grid" aria-describedby="example1_info"> 
				 
				<tr class='bold'>
					<td>SLNO</td>
					<td>Item</td>
				 
				 
					<!--- PURCHASE COLUMNS -->
					<td>Purchase  Qty</td >
					
					<!---- Total columns -->
					<td>Total</td>
					 
					 
					 
				</tr>
				<?php $i=1; 
				$total_amt = 0;
				foreach($items as $item_id=>$printitem){ 
				
				$total_amt = $total_amt + $printitem['purchase_amount'];
				 
								?>
							<tr >
								<td><?php echo $i;?></td>
								<td><?php echo  $itemnames[$item_id];?></td>
								<!--- Opening balance COLUMNS -->
								 
							  
								<!--- PURCHASE COLUMNS -->
								<td><?php echo number_format($printitem['purchase_qty'],3,'.', '');?></td>								 
					
								<!---- Total columns -->
								<td><?php echo number_format($printitem['purchase_amount'],3,'.', '');?></td>
					
							 
								
								 
							</tr>
				<?php $i++; } ?>
				<tr class="bold"><td colspan="3" align="right">Total: </td><td>&nbsp;&nbsp;&nbsp;Rs&nbsp;&nbsp;<?php echo  number_format($total_amt,2); ?> </td></tr>
			</table>
		
		
		
		
		
		</td></tr>
		

</table> 
	 
  <?php } ?>