<section class="content">
<div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">School Consumption Report</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
             
			<form   role="form" class="form-horizontal"   action=""  method="post" onsubmit="return validate(this)">
              <div class="box-body">
				  <div id="changepwdnotifier"></div>

			  <div class="form-group">
				 <?php  				 $admin_roles = array('subadmin','secretary');				 if(in_array($this->session->userdata("user_role"),$admin_roles)){ ?>
				 <label for="inputEmail3" class="col-sm-2 control-label">School Code</label>

                  <div class="col-sm-10">
				 
                    <!--<input type="text" class="form-control" id="school_code" placeholder="Enter school Code" name="school_code"  required>  -->   <?php 					 			$uid  = $this->session->userdata("user_id");					$district_id  = $this->session->userdata("district_id");					$admin_roles = array('subadmin','secretary');				 if( $this->session->userdata("is_dco")!=1 ) 					{										$school_rs = $this->db->query("SELECT s.name as sname,d.name as dname ,school_code ,d.district_id FROM schools s inner join districts d on d.district_id = s.district_id and s.name not like 'coll%'");						}					else					{							$school_rs = $this->db->query("SELECT s.name as sname,d.name as dname ,school_code ,d.district_id FROM schools s inner join districts d on d.district_id = s.district_id and s.name not like 'coll%' and d.district_id='$district_id'");						}					?>
				   <select name="school_code" id="school_code" required >
				  <option value=''>Select School </option>
				  <?php
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
				  <?php } else  if($this->session->userdata("user_role")=="school") {?>
				   <input type="hidden" value="<?php echo $school_code;?>" class="form-control" id="school_code" placeholder="Enter school Code" name="school_code"  required> 
				  <?php } ?>
                </div>
				 <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Date</label>

                  <div class="col-sm-10">
                    <input type="text" class="datepicker form-control" id="school_date" placeholder="Select Date" name="school_date" required>
                  </div>
                </div>
				 
           
              </div>
              <!-- /.box-body -->
              <div class="box-footer">
                
                <button type="submit" class="btn btn-info pull-right">Get Report</button>
              </div>
              <!-- /.box-footer -->
            </form>
          </div>
 
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
  </script>

