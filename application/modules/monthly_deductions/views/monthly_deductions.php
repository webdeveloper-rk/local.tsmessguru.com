<?php 
$months = array("01"=>"January","02"=>"February","03"=>"March","04"=>"April","05"=>"May",
									"06"=>"June","07"=>"July","08"=>"August","09"=>"September","10"=>"October","11"=>"November","12"=>"December");	 
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
<h3>Mothly Deduction Report </h3>
<?php 
$errors = validation_errors();
if($errors !=""){
?>
 <div class="validation_errors"><?php echo validation_errors(); ?>  </div>
<?php } ?>
<form method="post" action="">
<div class="box box-primary">
            <div class="box-header">
              <h3 class="box-title">Choose month and year to get the report</h3>
            </div>
            <div class="box-body">
              <!-- Date -->
              <div class="form-group">
                <label>Choose Month:</label>

                <div class="input-group date">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                 <select name="month" id="month"  style="height:30px;width:100px;" >
					<option value="">Select Month</option>
					<?php 					$sel_month = $this->input->post('month');
					$months = array("01"=>"January","02"=>"February","03"=>"March","04"=>"April","05"=>"May",
									"06"=>"June","07"=>"July","08"=>"August","09"=>"September","10"=>"October","11"=>"November","12"=>"December");
					foreach($months as $key_month =>$month_name) { 
					$selected = '';
					if($key_month == $sel_month){ $selected =  " selected =selected ";}
					echo '<option value="'.$key_month .'" '. $selected .'>'.$month_name.'</option>';
					  } ?>
					  </select>
                 
                </div>
                <!-- /.input group -->
              </div>
			       <label>Choose Year  :</label>

                <div class="input-group date">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                   
					 <select name="year" id="year" style="height:30px;width:100px;" >
					<option value="">Select Year</option>
					<?php 					$sel_year = $this->input->post('year');
					for($i=2017;$i<=date('Y');$i++)
					{ 
							$selected = '';
							if( $i ==  $sel_year){ 
												$selected =  " selected =selected ";
											}
								echo '<option value="'.$i .'" '. $selected .'>'.$i.'</option>';
					}
					?>
					  </select> 					  					
                 
                </div>                 <div class="input-group date">                   <br>				   <?php $checked_text = ' checked ';				   $checked_box_val = $this->input->post('only_deducted');				   if(  $checked_box_val==0)					    $checked_text = '   ';				   ?>					  <label> <input type="checkbox"  <?php echo  $checked_text;?> name="only_deducted" value="1">&nbsp;&nbsp;&nbsp;Only Deducted</label>                   
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
  
  <?php  if($display_result==true){ ?><div class='table-responsive'>
  <table id="example1" class="table table-bordered table-striped dataTable" role="grid" aria-describedby="example1_info">
	 
	<tr class='bold'><td align="center">Mothly Deductions Report  of  <span class='red'><b><?php echo $months[$sel_month];?> - <?php echo $sel_year ;?> </span></td></tr>
	<tr>
		<td>				<div>					Total Amount Deducted : <b><?php echo number_format($monthly_deducted,2);?></b><br>				</div>
			<table id="example1"  class="table table-bordered table-striped dataTable" role="grid" aria-describedby="example1_info"> 
				 
				<tr class='bold'>
					<td aria-controls="example1">SLNO</td>
					
					<td aria-controls="example1">School Code</td>					 
					 <td aria-controls="example1">School Name</td>					 <td aria-controls="example1">Deduction Amount</td>					  
					
					 
				</tr>
				<?php $i=1; 
				$total_amount = 0;
				foreach($deduction_rs->result() as   $school_data){ 												if($school_data->school_code == "85000" || $school_data->school_code == "" || $school_data->deduction_amount ==0 )					continue;				$encode_array = array("school_id"=>$school_data->school_id,"month"=>$sel_month,"year"=>$sel_year); 				$encode_id =  $this->ci_jwt->jwt_web_encode($encode_array);									 //print_a($school_data,1);?>
							<tr > 
							<td><?php echo $i;?></td> 
							<td><?php echo $school_data->school_code;?></td>							<td><?php echo $school_data->name;?></td> 							 							<td><?php  $total_amount = $total_amount + $school_data->deduction_amount;							echo  number_format( $school_data->deduction_amount,2) ;?></td> 							<td> 													 <a href="<?php echo site_url("monthly_deductions/viewdeductions_popup/".$encode_id);?>" data-toggle="modal" data-target="#myModal" data-remote="false" class="btn btn-default">								View Deductions</a></td> 
							</tr>
				<?php $i++; } ?>					
				 <tr><td colspan="2"></td>				 <td><b>Total :</b></td>				 <td><b><?php echo  number_format( $total_amount,2);?></b></td>				 <td></td>				 </tr>
			</table>
		
		
		
		
		
		</td></tr>
		

</table></div>
  
	 
  <?php } ?>    	  <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">  <div class="modal-dialog">    <div class="modal-content">      <div class="modal-header">        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>        <h4 class="modal-title" id="myModalLabel" style="font-weight:bold;">Mothly Deductions Report </h4>      </div>      <div class="modal-body" style="height:500px;overflow:auto;">        ...      </div>      <div class="modal-footer">        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>       </div>    </div>  </div></div><script>// Fill modal with content from link href$("#myModal").on("show.bs.modal", function(e) {    var link = $(e.relatedTarget);    $(this).find(".modal-body").load(link.attr("href"));});</script>		  