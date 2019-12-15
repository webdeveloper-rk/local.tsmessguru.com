<?php 
$months = array("1"=>"January",
					"2"=>"February",
					"3"=>"March",
					"4"=>"April",
					"5"=>"May",
					"6"=>"June",
					"7"=>"July",
					"8"=>"August",
					"9"=>"September",
					"10"=>"October",
					"11"=>"November",
					"12"=>"December");
$from_date = '';
$to_date = '';
if($this->input->post('fromdate')!=null)
	$from_date = $this->input->post('fromdate');
if($this->input->post('todate')!=null)
	$to_date = $this->input->post('todate');

?>
<style>
.bold 
{
	font-weight:bold;
}
.red
{
	color:#FF0000;
}

.mytable, td, th {
    border: 1px solid black;
}

.mytable {
    border-collapse: collapse;
    width: 100%;
}

th {
    height: 20px;
}
.tblTitle{
    
}
</style>
<h3>Purchase and consumption BULK entries</h3>
<form method="post" action="<?php echo site_url();?>admin/subadmin/bulkdataentry/<?php echo $school_id;?>/<?php echo $month_id;?>" onsubmit="return validateform(this)">
<div class="box box-primary">
            <div class="box-header">
              <h3 class="box-title">Select date  Quantity and prices</h3>
            </div>
            <div class="box-body"> 
			 <label>&nbsp;&nbsp;School:&nbsp;&nbsp;  <span style="color:#0000FF"><b><?php echo $school_info->sname;?></b></span></label>
                 
			</div>	
 <div class="box-body"> 
			 <label>&nbsp;&nbsp;Item Name:&nbsp;&nbsp;  <span style="color:#0000FF"><b><?php echo $school_info->sname;?></b></span></label>
                 
			</div>					
              <!-- /.form group -->

			   <!-- Date -->
              <div class="box-body"> 
                   <label>&nbsp;&nbsp;Month:&nbsp;&nbsp; &nbsp;&nbsp; <span style="color:#0000FF"><b><?php echo $months[$month_id]."-".date('Y');;?></b></span></label>
                  <br>
				  <span style="color:#FF0000;font-weight:bold;">Note: Quantites must be in KG's Only</span>
                 
              </div>
              <!-- /.form group -->
			  <table class="mytable">
			 <!-- <tr class="tblTitle bold">
				<td>&nbsp;</td>
				<td>&nbsp;Purchase Qty</td>
				<td>&nbsp;Purchase Price</td>
				<td>&nbsp;Breakfast Qty</td>
				<td>&nbsp;Breakfast Price</td>
				<td>&nbsp;Lunch Qty</td>
				<td>&nbsp;Lunch Price</td>
				<td>&nbsp;Snacks Qty</td>
				<td>&nbsp;Snacks Price</td>
				<td>&nbsp;Dinner Qty</td>
				<td>&nbsp;Dinner Price</td>
			</tr>-->
	<?php 
	//print_a($items_list);
		foreach($items_list->result() as $row){
			?>
			<tr style="padding-bottomn:10px;">
				<td>&nbsp;<b><span style="color:#0000FF"><?php echo  $row->item_name. "- ". $row->telugu_name;?></span></b></td>
				<td>&nbsp;<span class='bold'>Purchase Qty</span><br><input type="text" style="width:100px;" name="pqty[<?php echo $row->item_id;?>]"   value=''></td>
				<td>&nbsp;<span class='bold'>Purchase Price</span><br><input type="text"  style="width:100px;" name="pprice[<?php echo $row->item_id;?>]"   value=''></td>
				
				<td>&nbsp;<span class='bold'>Breakfast Qty</span><br><input type="text" style="width:100px;" name="bf_qty[<?php echo $row->item_id;?>]"   value=''></td>
				<td>&nbsp;<span class='bold'>Breakfast Price</span><br><input type="text"  style="width:100px;" name="bf_price[<?php echo $row->item_id;?>]"   value=''></td>
				
				<td>&nbsp;<span class='bold'>Lunch Qty</span><br><input type="text" style="width:100px;" name="lu_qty[<?php echo $row->item_id;?>]"   value=''></td>
				<td>&nbsp;<span class='bold'>Lunch Price</span><br><input type="text"  style="width:100px;" name="lu_price[<?php echo $row->item_id;?>]"  value=''></td>
				
				<td>&nbsp;<span class='bold'>Snacks Qty</span><br><input type="text" style="width:100px;" name="sn_qty[<?php echo $row->item_id;?>]"   value=''></td>
				<td>&nbsp;<span class='bold'>Snacks Price</span><br><input type="text"  style="width:100px;" name="sn_price[<?php echo $row->item_id;?>]"   value=''></td>
				
				<td>&nbsp;<span class='bold'>Dinner Qty</span><br><input type="text" style="width:100px;" name="di_qty[<?php echo $row->item_id;?>]"  value=''></td>
				<td>&nbsp;<span class='bold'>Dinner Price</span><br><input type="text"  style="width:100px;" name="di_price[<?php echo $row->item_id;?>]"  value=''></td>
				
			</tr>
	<?php 	}
	?>
               
			  
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
   <script>
  function validateform(frm)
  {
	  if(frm.pqty.value().trim()=="")
	  {
			alert("Please enter Purchase quantity. if no purchases enter 0 value");
			frm.pqty.focus();
			return false;
	  }
	  
	  
	  return true;
  }
</script>
	 
  <?php } ?>