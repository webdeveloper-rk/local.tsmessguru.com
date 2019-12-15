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
<h3>Purchase and consumption entries</h3>
<form method="post" action="">
 <?php 
$errors = validation_errors();
if($errors !=""){
?>
 <div class="validation_errors"><?php echo validation_errors(); ?>  </div>
<?php } ?>
<div class="box box-primary">
            <div class="box-header">
			<?php echo $this->session->flashdata('message');?>
              <h3 class="box-title">Choose school and Date</h3>
            </div>
            <div class="box-body">
              <!-- Date -->
              <div class="form-group">
                <label>School:</label>

                <div class="input-group date">
                  <div class="input-group-addon">
                    <i class="fa fa-institution"></i>
                  </div>
                   <select required  name="school_id" style="height:32px" class="form-control pull-right " >
					<option value="">Choose school</option>
				<?php  foreach($schools->result() as $row){
					echo "<option value='".$row->school_id."'>".$row->school_code."-".$row->sname."</option>";
			 } ?>
 				   </select>
                </div>
                <!-- /.input group -->
              </div>
			  
			 

			  
			    <div class="form-group">
                <label>Item:</label>

                <div class="input-group date">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar-plus-o"></i>
                  </div>
                   <select required name="item_id" style="height:32px" class="form-control pull-right " >
					<option value="">Choose Item</option>
				<?php  foreach($items->result() as $row){
					echo "<option value='".$row->item_id."'>".$row->item_name."-".$row->telugu_name."</option>";
			 } ?>
 				   </select>
                </div>
                <!-- /.input group -->
				
				  <!-- Date -->
              <div class="form-group">
                <label>Date:</label>

                <div class="input-group date">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                  <input type="text" required name="todate" value="<?php echo $to_date;?>"    class="form-control pull-right datepicker"    >
                </div>
                <!-- /.input group -->
              </div>
              <!-- /.form group -->
				
				
              </div>
			  
			  
              <!-- Date range -->
              <div class="box-footer">
                 
                <input type="submit" class="btn btn-info pull-right" value="Submit" name="submit"> 
				 
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
	<tr class='bold'><td align="center">APSWRSCHOOL,<?php echo $this->session->userdata("user_name"); ?></td></tr>
	<tr class='bold'><td align="center">DIET EXPENDITURE STATEMENT FOR THE dates between <span class='red'><b><?php echo $from_date . "</b></span> and  <span class='red'><b>". $to_date;?></b></span></td></tr>
	<tr>
		<td>
			<table id="example1"  class="table table-bordered table-striped dataTable" role="grid" aria-describedby="example1_info"> 
				<tr class='bold'>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td colspan="3">Opening Balance</td>
					<td colspan="2">Purchase Balance</td>
					<td colspan="2">Total</td>
					<td colspan="2">Consumption</td>
					<td colspan="2">CLOSING BALANCE</td>
				</tr>
				<tr class='bold'>
					<td>SLNO</td>
					<td>Item</td>
					<!--- Opening balance COLUMNS -->
					<td>Qty</td>
					<td>Rate</td>
					<td>Amount</td>
					<!--- PURCHASE COLUMNS -->
					<td>Qty</td >
					<td>Amount</td>
					<!---- Total columns -->
					<td>Qty</td>
					<td>Amount</td>
					<!-- Consumption Columns -->
					<td>Qty</td>					
					<td>Amount</td>
					<!-- Closing Balance -->
					<td>Qty</td>
					<td>Amount</td>
					 
				</tr>
				<?php $i=1; 
				foreach($items as $item_id=>$printitem){ 
				//print_a($printitem);
				/*opening_quantity,
								opening_price,
								opening_total,
								closing_quantity,
								closing_price,
								closing_total,
								consumed_quantity,
								consumed_total,
								purchase_qty,
								purchase_total,
								total_qty,
								total_price*/
								?>
							<tr >
								<td><?php echo $i;?></td>
								<td><?php echo  $itemnames[$item_id];?></td>
								<!--- Opening balance COLUMNS -->
								<td><?php echo number_format($printitem['opening_quantity'],3,'.', '');?></td>
								<td><?php echo number_format($printitem['opening_price'],2,'.', '');?></td>
								<td><?php echo number_format($printitem['opening_total'],2,'.', '');?></td>
								<!--- PURCHASE COLUMNS -->
								<td><?php echo number_format($printitem['purchase_qty'],3,'.', '');?></td>								 
								<td><?php echo number_format($printitem['purchase_total'],2,'.', '');?></td>
								<!---- Total columns -->
								<td><?php echo number_format($printitem['total_qty'],3,'.', '');?></td>
								<td><?php echo number_format($printitem['total_price'],2,'.', '');?></td>
								<!-- Consumption Columns -->
								<td><?php echo number_format($printitem['consumed_quantity'],3,'.', '');?></td>					
								<td><?php echo number_format($printitem['consumed_total'],2,'.', '');?></td>
								<!-- Closing Balance -->
								<td><?php echo number_format($printitem['closing_quantity'],3,'.', '');?></td>
								<td><?php echo number_format($printitem['closing_total'],2,'.', '');?></td>
								 
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