 
<style>
  .bold{
	font-weight:bold
}
</style>
<section class="content">
<div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Update School Attendance   </h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
             
			<form   role="form" class="form-horizontal"   action=""  method="post"  >
              <div class="box-body">
				  <div id="changepwdnotifier"></div>
 				            
			  <div class="form-group" id="div_school_code"  >
				 
				 <label for="inputEmail3" class="col-sm-2 control-label">School Code</label>

                  <div class="col-sm-10">
				 
                   <!-- <input type="text" class="form-control" value="<?php echo $school_code;?>" id="school_code" placeholder="Enter school Code" name="school_code"  required>  
				  -->
				  <select name="school_code" id="school_code" required >
				  <option value=''>Select School </option>				  <option value='all'>ALL Schools</option>
				    <?php 					 $school_posted  = $this->input->post('school_code') ;
				  $school_rs = $this->db->query("SELECT * FROM schools where is_school=1 ");	
					 

					foreach($school_rs->result() as $row)
					{							$selected_text = '';							if( $school_posted == $row->school_code)							{								//$selected_text = " selected  ";							}
						echo "<option value='".$row->school_code."'  >".$row->school_code."-" .$row->name."</option>" ;
					}
			 ?>
                    </select>
				  
				  
				 
                  </div>
				  
                </div>
				 
				 
           	  <div class="form-group"  >				 				 <label for="inputEmail3" class="col-sm-2 control-label">Choose Date</label>                  <div class="col-sm-10">				                     <input type="text" class="datepicker"  value="<?php echo $this->input->post('attendence_date');?>" id="attendence_date" placeholder="Choose Date" name="attendence_date"  required>                    </div>				                  </div>				 
              </div>
              <!-- /.box-body -->
              <div class="box-footer">
                
                <button type="submit" class="btn btn-info pull-right">Update Attendance</button>
              </div>
              <!-- /.box-footer -->
            </form>						<?php if($frame_load==true){ ?>				<iframe width="100%" height="100%" src="<?php echo $frame_url; ?>"></iframe>			<?php } ?>
			 
 
 
				
			    
			 
          </div>
 
</section>

<!-- jQuery 1.10.2 -->
 
     
<script type="text/javascript">
 function toggle_schoolcode(tval) {	if(tval == "single")	{		$("#div_school_code").show();	}	else{		$("#div_school_code").hide();	} }
    function validate(form) {
       
	   if(form.school_code.value.trim()=="")
	   {
		   alert("Please enter school_code");
		   form.school_code.focus();
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

