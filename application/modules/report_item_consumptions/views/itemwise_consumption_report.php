<section class="content">
<div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">  Itemwise  Consumption Reports  </h3>
			  
            </div>
            <!-- /.box-header -->
            <!-- form start -->
             <?php 

$errors = validation_errors();
if($errors !=""){
?>
 <div class="validation_errors"><?php echo validation_errors(); ?>  </div>
<?php } ?>

<?php echo $this->session->flashdata("message");?>
			<form   role="form" class="form-horizontal"   action=""  method="post"  onsubmit="return validate(this)">
              <div class="box-body">
				  <div id="changepwdnotifier"></div>
				   <?php if($this->session->userdata("is_dco")!="1"){?>		
	 <div class="form-group" id="fg_report" style="">
                  <label for="inputEmail3" class="col-sm-2 control-label">Report Type:</label>

                  <div class="col-sm-10">
                   <label><input type="radio" onchange="showElement(this.value)" required <?php if($type=="state") { echo " checked ";}?>  name="type" id="rtype" value="state">&nbsp;&nbsp;State</label>&nbsp;&nbsp;&nbsp;&nbsp;
                   <label><input type="radio"  onchange="showElement(this.value)"  required  <?php if($type=="district") { echo " checked ";}?>  name="type"  id="rtype"  value="district">&nbsp;&nbsp;District</label>&nbsp;&nbsp;&nbsp;&nbsp;
                   <label><input type="radio"  onchange="showElement(this.value)"  required  <?php if($type=="school") { echo " checked ";}?>  name="type"  id="rtype"  value="school">&nbsp;&nbsp;School</label>&nbsp;&nbsp;&nbsp;&nbsp;
                   
                    
                  </div>
                </div>
					 
				  			 <div class="form-group" id="fg_district" style="display:<?php if($type=="district") { echo "block;";}else { echo "none;";}?> ">
                  <label for="inputEmail3" class="col-sm-2 control-label">District Name:</label>

                  <div class="col-sm-10">
                  <select name="district_id" id="district_id" >
						<option value="">Select District</option>
						<?php 
						$dis_selected = $this->input->post('district_id');
						foreach($districts_list->result() as $districtObj)
						{
							$selected_text = '';
							if($dis_selected == $districtObj->district_id){
								$selected_text = ' selected ';
							}
						 ?>	
						<option <?php echo $selected_text ;?>  value="<?php echo $districtObj->district_id;?>"><?php echo $districtObj->name;?></option>
						<?php } ?>
					</select>
                    
                  </div>
                </div>
					 
					 
				  			 <div class="form-group"   id="fg_school" style="display:<?php if($type=="school") { echo "block;";}else { echo "none;";}?> ">
                  <label for="inputEmail3" class="col-sm-2 control-label">School Name:</label>

                  <div class="col-sm-10">
				  <?php 
				 
				    $sid_selected = $this->input->post('school_id');?>
                  <select name="school_id"  id="school_id"  >
						<option value="">Select School</option>
						<?php 
						$sid_selected = $this->input->post('school_id');
						foreach($schools_list->result() as $schoolObj)
						{
							$selected_text = '';
							if($sid_selected == $schoolObj->school_id){
								$selected_text = ' selected ';
							}
						 ?>	
						<option  <?php echo $selected_text ;?>   value="<?php echo $schoolObj->school_id;?>"><?php echo $schoolObj->school_code;?>-<?php echo $schoolObj->name;?></option>
						<?php } ?>
					</select>
                    
                  </div>
                </div>
				  
				   <?php } else{
					?>

						<input type="hidden" name="type" value="district">
						<input type="hidden" name="district_id" value="<?php echo $this->session->userdata("district_id");?>">
					
					<?php 					
				  }
				  ?>
						 <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Month & Year:</label>

                  <div class="col-sm-10">
                   	<select name="month" required>
						<option value="">Select Month</option>
						<?php 
						   $month_selected = $this->input->post('month'); 
						 	  $months = array("01"=>"January","02"=>"February","03"=>"March","04"=>"April","05"=>"May",
									"06"=>"June","07"=>"July","08"=>"August","09"=>"September","10"=>"October","11"=>"November","12"=>"December");	
						
						for($lmonth=1;$lmonth<13;$lmonth++)
						{
							$month_name = $months[$lmonth];
							if($lmonth < 10)
									$lmonth = "0".$lmonth;
								
								$selected_text = '';
							if($month_selected == $lmonth){
								$selected_text = ' selected ';
							}
							
							?>
						<option <?php echo $selected_text ;?>   value="<?php echo $lmonth;?>"><?php echo $months[$lmonth];?></option>
						<?php } ?>
					</select>
                   	<select name="year"  required>
						<option value="">Select Year</option>
						<?php 
						 $year_selected = $this->input->post('year'); 
						$sdyear = 2017;
						$pyear = date('Y');
						
						for($lyear=$sdyear;$lyear<=$pyear;$lyear++)
						{
								$selected_text = '';
							if($year_selected == $lyear){
								$selected_text = ' selected ';
							}
							?>
						<option  <?php echo $selected_text ;?>    value="<?php echo $lyear;?>"><?php echo $lyear;?></option>
						<?php } ?>
					</select>
                  </div>
                </div>
				 
           						 <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Item:</label>

                  <div class="col-sm-10">
                   <select name="item_id" required>
						<option value="">Select Item</option>
						<?php 
						 $item_id_selected = $this->input->post('item_id'); 
						foreach($item_list->result() as $itemObj)
						{
								$selected_text = '';
							if($item_id_selected == $itemObj->item_id){
								$selected_text = ' selected ';
							}
						 ?>	
						<option  <?php echo $selected_text ;?>   value="<?php echo $itemObj->item_id;?>"><?php echo $itemObj->item_name."-".$itemObj->telugu_name;?></option>
						<?php } ?>
					</select>
                    
                  </div>
                </div>
              </div>
              <!-- /.box-body -->
              <div class="box-footer">
                
                <button type="submit" name="submit"  value="getreport" class="btn btn-info pull-right">Get Report</button> 
			 
                <button type="submit"  name="submit"  value="download"  class="btn btn-info pull-right">Download</button>
              </div>
              <!-- /.box-footer -->
            </form>
          </div>
		  
		  <?php if($display_result==true){ ?>
		  
		  <div class="box">
            <div class="box-header">
             <h4><b><?php echo $rdata['item_name'];?></b> Consumption Report of <b>
			 <?php echo  $rdata['month_name'];?> <span style="color:#FF0000;"><?php if($rdata['report_for']!="") { echo "-". $rdata['report_for'];} ?></span></b></b> </h4>
            </div>
            <!-- /.box-header -->
            
			  <table id="example1" class="table table-bordered table-striped dataTable" role="grid" aria-describedby="example1_info">
                <thead>
                <tr role="row">
				<th class="sorting_asc" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-sort="ascending" 
				aria-label="Rendering engine: activate to sort column descending" style="width: 126px;">Sno</th>
				
				<th class="sorting_asc" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-sort="ascending" 
				aria-label="Rendering engine: activate to sort column descending" style="width: 126px;">Date</th>
			 
				 
				 <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" 
				aria-label="Engine version: activate to sort column ascending" style="width: 106px;">Consumption Qty</th> 
				 
		
				 
</tr>
                </thead>
                <tbody>
                 <?php 
				 $sno = 1;
				 $total_consumed = 0;
				 
				 $rset = $rdata['rset'];
				 foreach($rset->result() as $item) {
 				 
				  $total_consumed  =  $total_consumed  + $item->consumed_quantity;
				  
				  
				 ?>
				 <tr  >
				 <td class="sorting_asc" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-sort="ascending" 
				aria-label="Rendering engine: activate to sort column descending" style="widtd: 126px;"><?php echo $sno;?></td>
				<td class="sorting_asc" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-sort="ascending" 
				aria-label="Rendering engine: activate to sort column descending" style="widtd: 126px;"><?php echo $item->entry_date;?></td>
				 
				<td class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" 
				aria-label="Platform(s): activate to sort column ascending" style="widtd: 139px;"><?php echo $item->consumed_quantity;?></td>
				 
				 
</tr>
				 <?php $sno++;
				  } 
				  ?>
				   <tr class="bold" >
				  <td>&nbsp;</td>
				  <td class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" 
				aria-label="Engine version: activate to sort column ascending" style="widtd: 106px;"><b>Total Purchased Amount</b></td> 
				 
				<td class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" 
				aria-label="Engine version: activate to sort column ascending" style="widtd: 106px;"><b><?php echo number_format($total_consumed,3);?></b></td>
		
				 
				
                </tbody>
                
              </table>
			  
            </div>
		  <?php } ?>
            <!-- /.box-body -->
          </div>
		  <script>
  $(function () {
  //  $("#example1").DataTable();
    $('#example11').DataTable({
		"pageLength": 3000,
      "paging": true,
      "lengthChange": false,
      "searching": true,
      "ordering": true,
      "info": true,
	   
      "autoWidth": true
    });
  });
</script>
	 
 
</section>

<!-- jQuery 1.10.2 -->
 
     
<script type="text/javascript">
 
    function validate(form) {
       
	   if(form.school_code.value.trim()=="")
	   {
		   alert("Please enter school_code");
		   form.school_code.focus();
		   return false;
	   }
	    if(form.school_date.value.trim()=="")
	   {
		   alert("Please select date");
		   form.school_date.focus();
		   return false;
	   }
    }
</script>
 <script>
  $( function() {
			$( ".datepicker" ).datepicker({ 
			startDate: '09-01-2016',
			endDate: '+0d'});
  } );
  function showElement(sval)
  {
	//  console.log(sval);
	  switch(sval)
	  {
		  case 'district':
							$("#fg_school").hide();
							$("#fg_district").show();
					break;
		  case 'school':
							$("#fg_school").show();
							$("#fg_district").hide();
					break;
		default: 
						$("#fg_school").hide();
							$("#fg_district").hide();
					break;
	  }
  }
  function validate(frm)
  {
	  
	  rtype = $('input[name=type]:checked').val();
	  
	  if(rtype=="school")
	  {
		  if($("#school_id").val()==""){
				  alert("Please select School ");
				  $("#school_id").focus();
				  return false ;
		  }
	  }
	   if(rtype=="district")
	  {
		   if($("#district_id").val()==""){
						  alert("Please select district ");
						  $("#district_id").focus();
						  return false ;
		   }
	  }
  }
  </script>

