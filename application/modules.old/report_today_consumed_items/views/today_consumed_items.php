 
<style>
.bold td
{
	font-weight:bold;
}
.red
{
	color:#FF0000;
}.zeroclass{	display:none;}@media   {	.tlink{	display:none;}}
</style>
<h3>Today consumed Items and balances Report </h3>
<?php 
$errors = validation_errors();
if($errors !=""){
?>
 <div class="validation_errors"><?php echo validation_errors(); ?>  </div>
<?php } ?>
 
  <?php if($today_date!=''){ ?><a href='#' onclick="javascript:window.print()" class='btn btn-info  pull-right noprint'>Print</a>
  <table class="table table-bordered table-striped  "  >
	<tr class='bold'><td align="center"><?php echo $this->config->item('society_name'); ?>,<?php echo $this->session->userdata("user_name"); ?></td></tr>
	<tr class='bold'><td align="center">DIET EXPENDITURE STATEMENT FOR THE date of <span class='red'><b><?php echo $today_date . "</b></span> " ?></b></span></td></tr>
	<tr class='bold noprint'><td align="center"> <a href="#" id="tlink"  onclick='togglezeros()'>show/hide Zero Consumed Quantities</a> </b></span></td></tr>
	<tr>
		<td>
			<table id="example1"  class="table table-bordered table-striped  " role="grid" aria-describedby="example1_info"> 
				 
				<tr class='bold'>
					<td>SLNO</td>
					<td>Item</td>
					<!--- Opening balance COLUMNS -->
					<td>Opening Balance Qty</td>
				 
					<!--- PURCHASE COLUMNS -->
					<td>Purchase  Qty</td >
					
					<!---- Total columns -->
					<td>Total Qty</td>
					 
					<!-- Consumption Columns -->
					<td>Consumption Qty</td>					
					
					<!-- Closing Balance -->
					<td>Closing Qty</td>
					 <td>Consumption Amount(Rs)</td>
					 
				</tr>
				<?php $i=1; 							 
				$overall_total_amount = 0;				 
				foreach($rset->result()  as $printitem){ 								  					/**********************										*************/					$opening_quantity = $printitem->opening_quantity;					$purchase_quantity =  $printitem->purchase_quantity;										$total_qty  = $opening_quantity + $purchase_quantity;										$consumed_qty = $printitem->consumed_qty;					$consumed_total = $printitem->consumed_total; 					$closing_quantity = $printitem->closing_quantity; 					$consumed_total = $printitem->consumed_total; 										$overall_total_amount = $overall_total_amount + $consumed_total;
								?>
							<tr   class='<?php if($consumed_qty==0) { echo " zeroclass ";}?>'>
								<td><?php echo $i;?></td>
								<td><?php echo  $itemnames[$printitem->item_id];?></td>
								<!--- Opening balance COLUMNS -->
								<td><?php echo number_format($opening_quantity  ,3,'.', '');?></td>
							  
								<!--- PURCHASE COLUMNS -->
								<td><?php echo number_format($purchase_quantity ,3,'.', '');?></td>								 
					
								<!---- Total columns -->
								<td><?php echo number_format($total_qty,3,'.', '');?></td>
					
								<!-- Consumption Columns -->
								<td><?php echo number_format($consumed_qty ,3,'.', '');?></td>					
								
								<!-- Closing Balance -->
								<td><?php echo number_format($closing_quantity,3,'.', '');?></td>
								<td><?php echo number_format($consumed_total,2,'.', '');?></td>
								
								 
							</tr>
				<?php $i++; } ?>
				<tr class="bold"><td colspan="7" align="right">Total: </td><td>&nbsp;&nbsp;&nbsp;Rs&nbsp;&nbsp;<?php echo  number_format($overall_total_amount,2); ?> </td></tr>
			</table>
		
		
		
		
		
		</td></tr>
		

</table>
 
	 
  <?php    }?><script>function togglezeros(){	//zeroclass	$( ".zeroclass" ).toggle();}</script>