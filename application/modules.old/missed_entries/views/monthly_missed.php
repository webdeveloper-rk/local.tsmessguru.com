
 

<style>
.alert-success
{
	 color: #FFF;
    background-color: #4CAF50;
    border-color: #ebccd1;
}
.alert {
    padding: 15px;
    margin-bottom: 20px;
    border: 1px solid transparent;
    border-radius: 4px;
}
.daily table{
	border-collapse: collapse;
	 border: 1px;
}
.daily   td{
	padding:5px;
}

</style>
<section class="content no-print">
<div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title no-print">Schools Monthly Missed Entries </h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
             
			<form   role="form" class="form-horizontal"   action=""  method="post" onsubmit="return validate(this)">
              <table>
			  <tr><td>Choose Month </td>
			  <td style="height:30px;padding:20px;">

                 <select name="month" id="month"  required style="height:30px;width:100px;" >

					<option value="">Select Month</option>

					<?php 
					$sel_month = $this->input->post('month');

					$months = array("01"=>"January","02"=>"February","03"=>"March","04"=>"April","05"=>"May",

									"06"=>"June","07"=>"July","08"=>"August","09"=>"September","10"=>"October","11"=>"November","12"=>"December");

					foreach($months as $key_month =>$month_name) { 

					$selected = '';

					if($key_month == $sel_month){ $selected =  " selected =selected ";}

					echo '<option value="'.$key_month .'" '. $selected .'>'.$month_name.'</option>';

					  } ?>

					  </select>

                 

               </td></tr>
			   <tr style="height:30px;padding:20px;">
			   <td>Choose Year  :</td>
        <td style="height:30px;padding:20px;">  <select name="year" id="year" required style="height:30px;width:100px;" >

					<option value="">Select Year</option>

					<?php 
					$sel_year = $this->input->post('year');

					for($i=2018;$i<=date('Y');$i++)

					{ 

							$selected = '';

							if( $i ==  $sel_year){ 

												$selected =  " selected =selected ";

											}

								echo '<option value="'.$i .'" '. $selected .'>'.$i.'</option>';

					}

					?>

					  </select> 
				</tr>
				<tr>
				<td colspan="2" style="height:30px;padding:20px;"> <button type="submit" class="btn btn-info pull-right">Get Report</button></td></tr>
</table>
</form>
</div>				
 
</section> 
<a href="javascript:window.print();" class="btn btn-info pull-right no-print">Print</a>
<?php echo $this->session->flashdata('message');?>

<h3>Schools Monthly Missed Entries [ <?php echo $report_date;?> ] </h3>
 

<div class="box">
            <div class="box-header">
             
            </div>
            <!-- /.box-header -->
            <div class="box-body">
               
			  
			  <table id="example1" class="table table-bordered table-striped dataTable" role="grid" aria-describedby="example1_info">
                <thead>
                <tr role="row">
			
				 
				 
				 <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" 
				aria-label="Engine version: activate to sort column ascending" style="width: 106px;">Sno</th> 
				
				
				 <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" 
				aria-label="Engine version: activate to sort column ascending" style="width: 106px;">School Code</th> 
				
				 	<th class="sorting_asc" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-sort="ascending" 
				aria-label="Rendering engine: activate to sort column descending" style="width: 126px;">School Name</th>
				 <th class="sorting_asc" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-sort="ascending" 
				aria-label="Rendering engine: activate to sort column descending" style="width: 126px;">No of Days Missed</th>
				 
				</tr>
                </thead>
                <tbody>
                 <?php 
				 $i =1;
				 foreach($rset->result() as $school) {  ?>                <tr role="row" class="odd">
               
				 <td>
					 <?php echo $i; ?> 
				   </td> 
                   <td>
					 <?php echo $school->school_code; ?> 
				   </td> 
				       <td class="sorting_1"> 
				 <b> <?php echo $school->name;?></b></td>
				<td> <b> <?php echo $school->missed_days;?></b></td>
                  
				     
                </tr>
				 <?php $i++;} ?>
				
                </tbody>
                
              </table>
			  
            </div>
            <!-- /.box-body -->
          </div>
		  
	 
