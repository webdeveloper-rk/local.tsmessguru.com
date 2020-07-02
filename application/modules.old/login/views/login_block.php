<script src="<?php echo site_url(); ?>assets/js_jqvalidation/jquery.validate.js"></script>
<script src="<?php echo site_url(); ?>assets/js_jqvalidation/jquery.validation.functions.js"></script>
 <link href="<?php echo site_url(); ?>assets/css_styles/jquery.validate.css" rel="stylesheet" type="text/css">
  <script src="<?php echo site_url(); ?>assets/js_common/jquery.form.js" type="text/javascript"></script>
  <script type="text/javascript">
    /* <![CDATA[ */
    jQuery(function() {
//        jQuery("#loginemail1").validate({
//            expression: "if (VAL.match(/^[^\\W][a-zA-Z0-9\\_\\-\\.]+([a-zA-Z0-9\\_\\-\\.]+)*\\@[a-zA-Z0-9_]+(\\.[a-zA-Z0-9_]+)*\\.[a-zA-Z]{2,4}$/)) return true; else return false;",
//            message: "Please enter a valid Email ID"
//        });
          jQuery("#loginpassword1").validate({
            expression: "if (VAL.length > 5 && VAL) return true; else return false;",
            message: "Required, min 6 chars length"
        });
      
    });
    /* ]]> */
</script>
<script type="text/javascript">
      $(document).ready(function() {
     $('#loading_image').hide();
});
            $(document).ready(function() {
                $('#login_form1').ajaxForm({dataType: 'json',
                             beforeSend : function(){
              $('#loading_image').show();
               $(".login_button").attr('disabled', '');
            },  
              complete : function(){
             $('#loading_image').hide();
            $(".login_button").removeAttr('disabled');
            }, 
            success: processJson
                    });
                });
            function processJson(data) {
                if (data.success) {
                    $("#login_notifier").html(data.message);
                    setTimeout(function() {
                        window.location = "<?php echo site_url('dashboard');?>"; }, 2000);
                } else {
                    $("#login_notifier").html(data.message);
                }
            }
</script>

   <?php  $controller = $this->uri->segment(1);  
if ($controller != "") {
    $method = $this->uri->segment(2);
    if ($method == "") {
        if (in_array($controller, array("aboutus", "register", "login")))
            $method = "listing";
        else
            $method = "index";
    }
} else {
    $controller = "home";
    $method = "index";
}
$ads = $this->userlib->get_page_advts($controller, $method);


?>

<div id="left-col-8" class="grid_8">
                <!-- Text Content -->
                <div id="form-block">
                    <h3>User Login</h3>
                    <br>
                    <br>
                    <form name="rform" method="post" action="<?php echo site_url("login/login_check");?>" id="login_form1">
                        <fieldset>
                            <label>Email ID  | Login ID </label>
                            <input type="text" id="loginemail1" name="email" placeholder="Please Enter Your Login ID Or Your Email" />
                        </fieldset>
                        <fieldset>
                            <label>Password : </label>
                            <input type="password" name="password"  id="loginpassword1" placeholder="Please Enter Your Password" />
                        </fieldset>
                        <fieldset >
                            <label>&nbsp;</label>
                            <button type="submit" class="login_button submit-button" name="form_submit">Login</button> 
                             <p  style="display:inline">  <img src="<?php echo site_url(); ?>assets/img_common/ajax-loader.gif" alt="loading" id="loading_image"></p>
                            <strong>
                                <a href="<?php echo site_url("login/forgot_password");?>" >Forgot Password</a> 
                            </strong>
                        </fieldset>
                         <div id="login_notifier"><?php echo $this->session->flashdata("msg");?></div>
                    </form>
                    <br>
                </div>
                <div class="clear"></div>
                <br><br>
                <div class="text-center">
                  <?php
        //if ($google_footerad == '') {
            if (count($ads["footer_ad"]) > 0) {
                foreach ($ads["footer_ad"] as $adblok) {
                    echo $adblok;
                }
            }
      /*  } else {
            print_r($google_footerad);
        }*/
        ?>
                </div>
            </div>