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
<h3>Purchase and consumption entries Form</h3>
<form method="post" action="<?php echo site_url();?>admin/subadmin/dataentry/<?php echo $school_id;?>/<?php echo $item_id;?>/<?php echo $date;?>" onsubmit="return validateform(this)">
<input type="text" name="entry_id" value="<?php echo $form_data['entry_id'];?>">

<div class="box box-primary">
            <div class="box-header">
              <h3 class="box-title">Enter item  Quantity and prices</h3>
            </div>
            <div class="box-body"> 
			 <label>&nbsp;&nbsp;School:&nbsp;&nbsp;  <span style="color:#ff0000"><b><?php echo $school_info->sname;?></b></span></label>
                 
			</div>				  
              <!-- /.form group -->

			   <!-- Date -->
              <div class="box-body"> 
                   <label>&nbsp;&nbsp;Date:&nbsp;&nbsp; &nbsp;&nbsp; <span style="color:#ff0000"><b><?php echo $date_selected;?></b></span></label>
                  
                 
              </div>
              <!-- /.form group -->
 <div class="box-body"> 
			 <label>&nbsp;&nbsp;Item:&nbsp;&nbsp;  <span style="color:#ff0000">&nbsp;&nbsp;&nbsp;<b><?php echo $item_details->item_name." - " .$item_details->telugu_name;?></b></span></label>
                 
			</div>	
              <div class="box-body">
				  <div id="changepwdnotifier"></div>
<div class="form-group" style="padding:10px;">
                  <label for="inputEmail3" class="col-sm-2 control-label"></label>

                  <div class="col-sm-10">
                   <label for="inputEmail3" class="col-sm-2 control-label"><b>Quantity</b></label>
					 <label for="inputEmail3" class="col-sm-2 control-label"><b>Price per 1 KG</b></label>
                  </div>
                </div>
			   <div class="form-group" style="padding:10px;">
                  <label for="inputEmail3" class="col-sm-2 control-label">Purchase</label>

                  <div class="col-sm-10">
                    <input type="text"  value="<?php echo $form_data['pqty'];?>" id="pqty" placeholder="Enter Purchase quantity" name="pqty" required="" style="width:200px;">
					<input type="text"  value="<?php echo $form_data['pprice'];?>"  id="pprice" placeholder="Enter Purchase price per kg" name="pprice" required="" style="width:200px;">  
                  </div>
                </div>
				
				<div class="form-group" style="padding:10px;">
                  <label for="inputEmail3" class="col-sm-2 control-label">Breakfast</label>

                  <div class="col-sm-10">
                    <input type="text" value="<?php echo $form_data['bf_qty'];?>"  id="pqty" placeholder="Enter Breakfast quantity" name="bf_qty" required="" style="width:200px;">
					<input type="text" value="<?php echo $form_data['bf_price'];?>"  id="pprice" placeholder="Enter Breakfast price per kg" name="bf_price" required="" style="width:200px;">  
                  </div>
                </div>

				<div class="form-group" style="padding:10px;">
                  <label for="inputEmail3" class="col-sm-2 control-label">Lunch</label>

                  <div class="col-sm-10">
                    <input type="text"  value="<?php echo $form_data['lu_qty'];?>" id="pqty" placeholder="Enter Lunch quantity" name="lu_qty" required="" style="width:200px;">
					<input type="text"  value="<?php echo $form_data['lu_price'];?>"  id="pprice" placeholder="Enter Lunch price per kg" name="lu_price" required="" style="width:200px;">  
                  </div>
                </div>		
				<div class="form-group" style="padding:10px;">
                  <label for="inputEmail3" class="col-sm-2 control-label">Snacks</label>

                  <div class="col-sm-10">
                    <input type="text" value="<?php echo $form_data['sn_qty'];?>" id="pqty" placeholder="Enter Snacks quantity" name="sn_qty" required="" style="width:200px;">
					<input type="text" value="<?php echo $form_data['sn_price'];?>"  id="pprice" placeholder="Enter Snacks price per kg" name="sn_price" required="" style="width:200px;">  
                  </div>
                </div>		

				<div class="form-group" style="padding:10px;">
                  <label for="inputEmail3" class="col-sm-2 control-label">Dinner</label>

                  <div class="col-sm-10">
                    <input type="text" value="<?php echo $form_data['di_qty'];?>"  id="pqty" placeholder="Enter Dinner quantity" name="di_qty" required="" style="width:200px;">
					<input type="text" value="<?php echo $form_data['di_price'];?>"  id="pprice" placeholder="Enter Dinner price per kg" name="di_price" required="" style="width:200px;">  
                  </div>
                </div>						
				 
               
           
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