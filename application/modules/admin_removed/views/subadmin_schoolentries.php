<section class="content">
<div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Schools Missed Entries </h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
             
			<form   role="form" class="form-horizontal"   action="<?php echo site_url();?>admin/subadmin/entriestoday"  method="post" onsubmit="return validate(this)">
              <div class="box-body">
				  <div id="changepwdnotifier"></div>

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

