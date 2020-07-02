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
<h3>Consolidated Report </h3>
<?php 			 			 			 $errors = validation_errors();if($errors !=""){?> <div class="validation_errors" style="padding:10px;"><?php echo $errors; ?>  </div><?php } ?>			 <?php echo $this->session->flashdata('message');?>
<form method="post" action="">
<div class="box box-primary">
            <div class="box-header">
              <h3 class="box-title">Choose dates to get the report</h3>
            </div>
            <div class="box-body">
              <!-- Date -->
              <div class="form-group">
                <label>Choose Year and Month:</label>

                <div class="input-group date">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div><select name="year" required style="height:30px;width:130px">						<option value="">Select   year</option>						<?php 						  						$end_year = date('Y');						$start_year = 2017;						$year_selected = $this->input->post('year'); 						for($year= $end_year;$year>$start_year;$year--)						{							 $selected_text ='';							if($year_selected == $year) 								$selected_text = ' selected ';							?>						<option <?php echo $selected_text ;?>   value="<?php echo $year;?>"><?php echo $year;?></option>						<?php } ?>					</select><select name="month" required style="height:30px;width:130px">						<option value="">Select Month</option>						<?php 						   $month_selected = $this->input->post('month'); 						 	  $months = array("01"=>"January","02"=>"February","03"=>"March","04"=>"April","05"=>"May",									"06"=>"June","07"=>"July","08"=>"August","09"=>"September","10"=>"October","11"=>"November","12"=>"December");													for($lmonth=1;$lmonth<13;$lmonth++)						{							$selected_text ='';							$month_name = $months[$lmonth];							if($lmonth < 10)									$lmonth = "0".$lmonth;															 							if($month_selected == $lmonth){								$selected_text = ' selected ';							}														?>						<option <?php echo $selected_text ;?>   value="<?php echo $lmonth;?>"><?php echo $months[$lmonth];?></option>						<?php } ?>					</select>
                </div>
                <!-- /.input group -->
              </div>
			   <div class="form-group">                <label>From &  To Date:</label>                <div class="input-group date">                  <div class="input-group-addon">                    <i class="fa fa-calendar"></i>                  </div>                  <select name="from_date" required style="height:30px;width:130px">						<option value="">Select From Day</option>						<?php 						  												for($day=1;$day<32;$day++)						{							 $selected_text = '';							if($day < 10)									$day_text = "0".$day;								else									$day_text =  $day;								$selected_text = '';							if($this->input->post("from_date") == $day){								$selected_text = ' selected ';							}														?>						<option <?php echo $selected_text ;?>   value="<?php echo $day;?>"><?php echo $day_text;?></option>						<?php } ?>					</select><select name="to_date" required style="height:30px;width:130px">						<option value="">Select To Day</option>						<?php 						  												for($day=1;$day<32;$day++)						{							 $selected_text = '';							if($day < 10)									$day_text = "0".$day;								else									$day_text =  $day;								$selected_text = '';							if($this->input->post("to_date") == $day){								$selected_text = ' selected ';							}														?>						<option <?php echo $selected_text ;?>   value="<?php echo $day;?>"><?php echo $day_text;?></option>						<?php } ?>					</select>                </div>                <!-- /.input group -->              </div>
			   <!-- Date -->
             			  			  			    <div class="form-group">                                <div  >						                   <label> <input type="checkbox" name="exclude"   <?php if($exclude == "exclude") { echo " checked ";} ?> value="exclude"       >&nbsp;&nbsp; 				  Exclude Unused items				</label>                                 </div>                <!-- /.input group -->              </div>
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
  <?php if($display_result ){ ?>
  <table class="table table-bordered table-striped  "  >
	<tr class='bold'><td align="center"><?php echo $this->config->item('society_name'); ?>,<?php echo $this->session->userdata("user_name"); ?></td></tr>
	<tr class='bold'><td align="center">DIET EXPENDITURE STATEMENT FOR THE dates between <span class='red'><b><?php echo date('d-M-Y',strtotime($from_date)) . "</b></span> and  <span class='red'><b>". date('d-M-Y',strtotime($to_date));?></b></span></td></tr>
	<tr class='bold'><td align="center">Note: Qty will be measures in Kg / Litre / Unit / Dozens </b></span></td></tr>
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
				$total_amount = 0;
				foreach($items as $item_id=>$printitem){ 
				if($exclude=="exclude" && $printitem['consumed_quantity']==0)						continue;
				$total_amount = $total_amount + $printitem['consumed_total'];
	 
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
				<tr class="bold"><td colspan="7" align="right">Total: </td><td>&nbsp;&nbsp;&nbsp;Rs&nbsp;&nbsp;<?php echo  number_format($total_amount,2); ?> </td></tr>
			</table>
		
		
		
		
		
		</td></tr>
		

</table>
 
	 
  <?php   }?>