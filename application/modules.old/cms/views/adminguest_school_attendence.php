 
<style>
  .bold{
	font-weight:bold
}
</style>
<section class="content">
<div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Update School Guest Attendance   </h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
             
			<form   role="form" class="form-horizontal"   action=""  method="post"  >
              <div class="box-body">
				  <div id="changepwdnotifier"></div>
 				             
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

