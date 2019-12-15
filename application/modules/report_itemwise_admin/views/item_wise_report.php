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
<h3>Item Report<?php ?></h3>
<?php 
$errors = validation_errors();
if($errors !=""){
?>
 <div class="validation_errors"><?php echo validation_errors(); ?>  </div>
<?php } ?>
<form method="post" action="">
<div class="box box-primary">
            <div class="box-header">
              <h3 class="box-title">Choose school,item ,month and year to get the report</h3>
            </div>
            <div class="box-body">
			 <div class="form-group">
                <label>Select School:</label>

                <div  >
                   
               <?php echo school_selection($this->input->post('school_id'));?>
                </div>
                <!-- /.input group -->
              </div>
			
			 <!-- Date -->
              <div class="form-group">
                <label>Select Item:</label>

                <div class="input-group date">
                  <div class="input-group-addon">
                    <i class="fa fa-bars"></i>
                  </div>
                <select name="item_id" required class='form-control' >
				<?php
					foreach($rset->result() as $item)
					{
						echo "<option value='".$item->item_id."'>".$item->item_name." - ".$item->telugu_name."-".$item->item_id."</option>";
					}
				?>
				</select>
                </div>
                <!-- /.input group -->
              </div>
			  
			  
              <!-- Date -->
              <div class="form-group">
                 <table><tr>
					<td style="margin-left:15px;">
				 <b>Choose Month & Year : </b>
					<select name="month_year" id="month_year"   required class='form-control'  >
					<option value="">Select Month</option>
					<?php 
					$cyear = date('Y');
					$start_year = 2017;
					$month_year_selected  = $this->input->post("month_year");
					for($year=$cyear;$year>=$start_year;$year--)
					{ 
								$months = array("01"=>"January","02"=>"February","03"=>"March","04"=>"April","05"=>"May",
									"06"=>"June","07"=>"July","08"=>"August","09"=>"September","10"=>"October","11"=>"November","12"=>"December");
					
									foreach($months as $key_month =>$month_name) { 
										$selected = '';
										$ym = $year."-".$key_month;
											if($ym == $month_year_selected){ $selected =  " selected =selected ";}
										echo '<option value="'.$ym.'"'  . $selected .'>'.$month_name."-".$year.'</option>';
											  }
					}
					  ?>
					  </select>
					  </td><td>  </td></tr></table>
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
  <?php if($from_date!='' && $to_date !=""){ ?>
  <table class="table table-bordered table-striped  "  >
	<tr class='bold'><td align="center">APSWRSCHOOL,<?php echo $school_name; ?></td></tr>
	<tr class='bold'><td align="center">DIET EXPENDITURE STATEMENT FOR THE dates between <span class='red'><b><?php echo $from_date . "</b></span> and  <span class='red'><b>". $to_date;?></b></span></td></tr>
	<tr>
		<td>
			<table id="example1"  class="table table-bordered table-striped dataTable" role="grid" aria-describedby="example1_info"> 
				 
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
					 <td>Consumption Amount</td>
					 
				</tr>
				<?php $i=1; 
				$total_amount = 0;
				foreach($items as $item_id=>$printitem){ 
				
				$total_amount = $total_amount + $printitem['consumed_total'];
				
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
							  
								<!--- PURCHASE COLUMNS -->
								<td><?php echo number_format($printitem['purchase_qty'],3,'.', '');?></td>								 
					
								<!---- Total columns -->
								<td><?php echo number_format($printitem['total_qty'],3,'.', '');?></td>
					
								<!-- Consumption Columns -->
								<td><?php echo number_format($printitem['consumed_quantity'],3,'.', '');?></td>					
								
								<!-- Closing Balance -->
								<td><?php echo number_format($printitem['closing_quantity'],3,'.', '');?></td>
								<td><?php echo number_format($printitem['consumed_total'],2,'.', '');?></td>
								
								 
							</tr>
				<?php $i++; } ?>
				<tr class="bold"><td colspan="7" align="right">Total</td><td><?php echo  number_format($total_amount,2); ?></td></tr>
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