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
<h3>Items  Distribution</h3>
 
 <?php 
$errors = validation_errors();
if($errors !=""){
?>
 <div class="validation_errors"><?php echo validation_errors(); ?>  </div>
<?php 
echo $this->session->flashdata("message");

} ?>
 
 
  <a href="<?php echo site_url("monthly_consolidated_tsmess_free_distribution");?>"  class="btn btn-info pull-right noprint no-print" >Go Back</a>
  <a href="javascript:void(0)" onclick="window.print()" class="btn btn-info pull-right noprint no-print" >Print</a>
  <table class="table table-bordered table-striped  "  >
	 
	<tr class='bold'><td align="center"><b><?php echo $school_info->school_code."- " .$school_info->name."-".$school_info->district_name;?> - Item Distribution</b></span></td></tr>
	<tr>
		<td>
			<table id="example1"  class="table table-bordered table-striped dataTable" role="grid" aria-describedby="example1_info"> 
				<tr class='bold'>
					 
					<td  >SNO</td>
					<td  >Item name</td>
					<td  >Supply Date</td>
					<td  >Issued To</td>
					<td  >Issued To Name</td>
					<td  >District</td>
					<td  >Quantity</td>
					<td  >Rate</td>
					<td  >Total </td>
				</tr>
				 
				<?php $i=1;
				$sub_total =0;
				$total = 0;
				
				foreach($items->result() as $printitem){ 
				 
				 $sub_total = $printitem->quantity*$printitem->price;
				 $total = $total + $sub_total;
								?>
							<tr >
								<td><?php echo $i;?></td>
								<td><b><?php echo  $printitem->item_name;?></b></td>
								<td><?php echo  $printitem->entry_date_dp;?></td>
								<td><?php echo  $printitem->to_whom;?></td>
								<td><?php echo  $printitem->person_name;?></td>
								<td><?php echo  $printitem->district_name;?></td>
								<td><?php echo  $printitem->quantity;?></td>
								<td><?php echo  $printitem->price;?></td>
								<td><?php echo round($sub_total,2);?></td>
								 
								 
							</tr>
				<?php $i++; } ?>
				<tr >
								 
								<td colspan="8" align="right"><b>Total Amount : </b></td>
								<td align="left"><b>&nbsp;&nbsp;&nbsp;<?php echo number_format($total,2);?></b></td>
								 
								 
							</tr>
			</table>
		
		
		
		
		
		</td></tr>
		

</table>
  
 