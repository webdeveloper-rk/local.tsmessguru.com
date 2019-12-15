<style>
  .bold{
	font-weight:bold
}
</style>
<section class="content">
<div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">School Daily Consumption Report </h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
             <?php 			 			 			 $errors = validation_errors();if($errors !=""){?> <div class="validation_errors" style="padding:10px;"><?php echo $errors; ?>  </div><?php } ?>			 <?php echo $this->session->flashdata('message');?>
			<form   role="form" class="form-horizontal"   action=""  method="post" onsubmit="return validate(this)">
              <div class="box-body">
				  <div id="changepwdnotifier"></div>

			  <div class="form-group">
				 <?php  if(in_array($this->session->userdata("user_role"),array("subadmin","secretary"))){ ?>
				 <label for="inputEmail3" class="col-sm-2 control-label">School Code</label>

                  <div class="col-sm-10">
				  
				  	  <select name="school_code" id="school_code" required >
				  <option value=''>Select School </option>
				    <?php 
					 			$uid  = $this->session->userdata("user_id");
					$district_id  = $this->session->userdata("district_id");
					if($this->session->userdata("is_dco")==0)
					{
										$school_rs = $this->db->query("SELECT s.name as sname,d.name as dname ,school_code ,d.district_id FROM schools s inner join districts d on d.district_id = s.district_id and s.is_school='1'   and s.school_code not like '%85000%' order by school_code asc ");	
					}
					else
					{
											//$school_rs = $this->db->query("select * from schools where district_id='$district_id' and name not like 'coll%' ");
											$school_rs = $this->db->query("SELECT s.name as sname,d.name as dname ,school_code ,d.district_id FROM schools s inner join districts d on d.district_id = s.district_id and    s.is_school='1' and s.school_code not like '%85000%' and d.district_id='$district_id' order by school_code asc ");	
					}
					$selected_school_code = $this->input->post("school_code");
					$selected_text = '';
					foreach($school_rs->result() as $row)
					{
						if($selected_school_code == $row->school_code)
							$selected_text = ' selected ';
						echo "<option value='".$row->school_code."' $selected_text >".$row->school_code."-" .$row->sname." - ".$row->dname."</option>" ;
						$selected_text = '';
					}
			 ?>
                    </select>
				  
				 
                  </div>
				  <?php } ?>
                </div>
				 <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Date</label>

                  <div class="col-sm-10">
                    <input type="text" class="datepicker  form-control"   value="<?php echo $input_date;?>" id="school_date" placeholder="Select Date" name="school_date" >
                  </div>
                </div>
				 
           
              </div>
              <!-- /.box-body -->
              <div class="box-footer">
                
                <button type="submit" class="btn btn-info pull-right">Get Report</button>
              </div>
              <!-- /.box-footer -->
            </form>
			<?php if($result_flag  ){ ?>
  <div class="form-group">   
                  <label for="inputEmail3" class="col-sm-2 control-label">Date</label>

                  <div  >
                    <?php echo $reportdate;?>
                  </div>
                </div>

  <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">School Name</label>

                  <div  ><?php echo $school_info->school_code." - ".$school_info->name;?>
                  </div>
                </div>
				
			    <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Attendance</label>

                  <div  >
                     
					 <table class="table table-bordered table-striped dataTable no-footer ">
					 <tr ><td class='bold'>&nbsp;</td><td class='bold'>Category 1(5,6,7) </td><td class='bold'>Category 2(8,9,10) </td><td class='bold'>Category 3(Intermediate) </td><td class='bold'>Total</td></tr>
					 
					 <tr  ><td class='bold'>Per Day Price</td><td><?php echo $student_prices['gp_5_7']; ?> / <?php echo $days_count;?> = <?php echo $group_1_perday_price;?></td><td><?php echo $student_prices['gp_8_10']; ?> / <?php echo $days_count;?> = <?php echo $group_2_perday_price;?></td><td><?php echo $student_prices['gp_inter']; ?> / <?php echo $days_count;?> = <?php echo $group_3_perday_price;?></td><td></td></tr>
					<tr ><td class='bold'>Attendance</td><td><?php echo $group_1_attendence; ?> </td><td><?php echo $group_2_attendence; ?>  </td><td><?php echo $group_3_attendence; ?> </td><td class='bold'><?php echo $attendence;?></td></tr>
					 <tr ><td class='bold'>Price</td><td><?php echo $group_1_price; ?> </td><td><?php echo $group_2_price; ?>  </td><td><?php echo $group_3_price; ?> </td><td class='bold'><?php echo  $today_allowed_Amount;?></td></tr>
					 </table>
                  </div>
                </div>
			 
				 <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Allowed Amount</label>

                  <div  >
                    <?php  echo $today_allowed_Amount;?>
                  </div>
                </div>
				 <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Consumed Amount</label>

                  <div  >
                    <?php  echo  ($today_consumed_Amount);?>
                  </div>
                </div>
				 <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Remaining Amount</label>

                  <div  >
                   <?php  echo $today_remaining_Amount;?><br><br>
                  </div>
                </div>
				<?php } ?>
          </div>
 
</section>

<!-- jQuery 1.10.2 -->
 
     
<script type="text/javascript">
 
    function validate(form) {
      
	    
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
			startDate: '01-01-2017',
			endDate: '+0d'});
  } );
  </script>

