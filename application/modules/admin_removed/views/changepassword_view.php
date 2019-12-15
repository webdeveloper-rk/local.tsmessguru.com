<section class="content">
<div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Change Password</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
             
			<form   role="form" class="form-horizontal" id="changepassword_form" action="<?php echo current_url(); ?>" id="changepassword_form" method="post">
              <div class="box-body">
				  <div id="changepwdnotifier"></div>

			  <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Old password</label>

                  <div class="col-sm-10">
                    <input type="password" class="form-control" id="oldpwd" placeholder="Enter Old password" name="oldpwd" required>  
                    <input type="hidden"   name="action" value="updatepassword" required>  
                  </div>
                </div>
				 <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">New password</label>

                  <div class="col-sm-10">
                    <input type="password" class="form-control" id="newpwd" placeholder="Enter New password" name="newpwd" required>
                  </div>
                </div>
				 <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Confirm password</label>

                  <div class="col-sm-10">
                    <input type="password" class="form-control" id="cpwd" placeholder="Confirm password" name="cpwd" required>
                  </div>
                </div>
               
           
              </div>
              <!-- /.box-body -->
              <div class="box-footer">
                
                <button type="submit" class="btn btn-info pull-right">Change Password</button>
              </div>
              <!-- /.box-footer -->
            </form>
          </div>
 
</section>

<!-- jQuery 1.10.2 -->
<script src="<?php echo site_url(); ?>assets/admin/js/jquery-1.10.2.min.js"></script>
<script src="<?php echo site_url(); ?>assets/admin/js/jquery.form.js"></script>
     
<script type="text/javascript">
    $(document).ready(function() {
        $('#changepassword_form').ajaxForm({dataType: 'json', success: processJson});
        $("#oldpwd").focus();
    });
    function processJson(data) {
        if (data.success) {
            $("#changepwdnotifier").html(data.message);
            /*setTimeout(function() {
                window.location = "<?php echo site_url('admin'); ?>";
            }, 2000);*/
			 
			 document.getElementById("changepassword_form").reset();
        } else {
            $("#changepwdnotifier").html(data.message);
        }
    }
</script>
