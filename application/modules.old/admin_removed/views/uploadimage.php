<script src="<?php echo site_url(); ?>assets/admin/js/jquery-1.10.2.min.js" type="text/javascript"></script>
<script src="<?php echo site_url(); ?>assets/admin/js/jquery.form.js" type="text/javascript"></script>
<link href="<?php echo site_url(); ?>assets/admin/css/facebox.css" media="screen" rel="stylesheet" type="text/css"/>
<script src="<?php echo site_url(); ?>assets/admin/js/jquery.cookie.js" type="text/javascript"></script>
<script src="<?php echo site_url(); ?>assets/admin/js/facebox.js" type="text/javascript"></script>
<script type="text/javascript">
    // prepare the form when the DOM is ready
    $(document).ready(function() {
        // bind form using ajaxForm
        $('#image_upload_form').ajaxForm({
            // dataType identifies the expected content type of the server response
            dataType:  'json',
            // success identifies the function to invoke when the server response
            // has been received
            success:   uploadJson
        });
    });
    function uploadJson(data) {
        // 'data' is the json object returned from the server
        if(data.success) {
            $("#image_notifier").attr("class","success_msg");
            $("#image_notifier").html(data.message);                        
            setTimeout("popup_close()", 10);
        } else {
            $("#image_notifier").attr("class","fail_msg");
            $("#image_notifier").html(data.message);
        }
    }
    function popup_close() {
        jQuery.facebox.close();
        window.location.reload();
        return false;
    }
</script>
        <!-- Middle Column Start -->
       <div class="col-lg-12"> 

    <div class="side-heading">Uploading image...</div>

    <div class="col-md-12" style="padding:0px;">
        <div class="register-area">
              <form class="form-horizontal" id="image_upload_form" action="<?php echo current_url(); ?>" method="post" name="image_upload_form" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="defaultInput" class="col-sm-4 control-label" >Upload Image:</label>
                        <div class="col-sm-7">
                            <input type="file"  name="image"   > 
                        </div>
                    </div>
                    
                    <p style="text-align:center;">
                        <input type="submit" name="form_submit" value="Upload" class="btn btn-warning btn-lg">
                    </p>
                    <div id="image_notifier"></div>
                </form>
        </div>

    </div> </div>
