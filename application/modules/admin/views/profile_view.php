<aside class="right-side">
<section class="content">
    <div class="row">

        <div class="col-md-offset-3 col-sm-5">

            <div class="box">
                <!--                        <h3 class="box-title"></h3> -->
                <div class="form-box" id="login-box" style="margin-top:18px">
                    <div class="header" style="background-color: #3C8DBC">Profile</div>
                    <form role="form" class="form-horizontal" id="changepassword_form" action="<?php echo current_url(); ?>" method="post">

                        <label for="exampleInputEmail1"><h5>Name  <sup>*</sup></h5></label>
                        <input type="text" required id="exampleInputEmail1" class="form-control" name="adminname" value="<?php echo $user_details->name ?>">


                        <label for="exampleInputEmail1"><h5>Email <sup>*</sup></h5> </label>
                        <input type="text"  id="exampleInputEmail1" class="form-control" name="adminemail" required value="<?php echo $user_details->email ?>">
                        <br/>

                        <button class="btn btn-primary btn-lg" type="submit" name="profile_form_submit" value="submit">Edit Profile</button><br/><br/>


                    </form>
                    <div id="changepwdnotifier"></div>
                    
                </div>
                <div class="box-body table-responsive">

                </div><!-- /.box-body -->
            </div><!-- /.box -->
        </div>
    </div>
</section>
</aside>


<!-- jQuery 1.10.2 -->
<script src="<?php echo site_url(); ?>assets/admin/js/jquery-1.10.2.min.js"></script>
<script src="<?php echo site_url(); ?>assets/admin/js/jquery.form.js"></script>
<!-- Bootstrap -->

<script type="text/javascript">
    $(document).ready(function() {
        $('#changepassword_form').ajaxForm({dataType: 'json', success: processJson});
        $("#inputEmail").focus();
    });
    function processJson(data) {
        if (data.success) {
            $("#changepwdnotifier").html(data.message);
            setTimeout(function() {
                window.location = "<?php echo site_url('admin'); ?>";
            }, 2000);
        } else {
            $("#changepwdnotifier").html(data.message);
        }
    }
</script>
