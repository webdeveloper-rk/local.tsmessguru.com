<?php
$this->load->helper('cookie');
$this->input->set_cookie('fburl', site_url(), "86500");
?>
<script src="<?php echo site_url(); ?>assets/admin/js/jquery.form.js" type="text/javascript"></script>
<link href="<?php echo site_url(); ?>assets/admin/css/facebox.css" media="screen" rel="stylesheet" type="text/css"/>
<script src="<?php echo site_url(); ?>assets/admin/js/jquery.cookie.js" type="text/javascript"></script>
<script src="<?php echo site_url(); ?>assets/admin/js/facebox.js" type="text/javascript"></script>
<script src="<?php echo site_url(); ?>assets/admin/js/jquery.livequery.min.js" type="text/javascript"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('#settings_form').ajaxForm({dataType: 'json', success: settingsJson});
    });
    function settingsJson(data) {
        if (data.success) {
            $("#notifier").html(data.message);
            setTimeout(function() {
                window.location = "<?php echo site_url('admin/settings'); ?>";
            }, 2000);
        } else {
            $("#notifier").html(data.message);
        }
    }
</script>
<script type="text/javascript">
    function open_facebox(fburl) {
        jQuery.facebox({ajax: fburl})
    }
    function closepopup() {
        $.facebox.close();
        return false;
    }
</script>  
<!-- DATA TABLES -->
<aside class="right-side"> 
    <div class="bc-area">          
        <ul class="breadcrumb">
            <li><a href="<?php echo site_url("admin/dashboard"); ?>">Home</a></li>
            <li class="active">Ads</li>
        </ul>
        <div class="clearfix"></div>
    </div>  
    <div id="edit-area">
      <div id="article" class="span9"><h2>&nbsp;&nbsp;&nbsp;Edit Site Settings</h2></div>
        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box">
                        <div class="box-header">
                            <!--                        <h3 class="box-title"></h3> -->
                        </div><!-- /.box-header -->
                        <div class="box-body table-responsive">
                             
                                <form id="wqdasdas" class="form-horizontal"  name="main_form" method="post" action="<?php echo site_url('admin/import/import_data'); ?>">
                                   
								    <label class="exampleInputEmail1" for="prod_name"><h5>Type<sup>*</sup></h5></label>
                                    <select required name="import_type"  class="form-control">
									<option value="" >Select Type</option>
									<option value="general" >General</option>
									<option value="salaries" >Salaries</option>
                                    </select>
									
									
                                    <label class="exampleInputEmail1" for="head_number"><h5>Head Number <sup>*</sup></h5></label>
                                    <input type="text" required name="head_number" id="head_number"  class="form-control" > 
                                    
                                  
                                    <label class="exampleInputEmail1" for="prod_name"><h5>Cheque Date <sup>*</sup></h5></label>
                                    <input type="text" required name="cheque_date" id="cheque_date"  class="form-control" >   <br/>
                                    
                                    <label class="exampleInputEmail1" for="prod_name"><h5>Cheque Number <sup>*</sup></h5></label>
                                    <input type="text" required name="cheque_number" id="cheque_number" class="form-control" >   <br/>
                                    
                                      
                                       <label class="exampleInputEmail1" for="prod_name"><h5>Excel File <sup>*</sup></h5></label>
                                    <input type="file" required name="excel_file" id="excel_file" class="form-control" > 
                                    
                                     
                                    
                                   <br/>
                                    <button class="btn btn-success" type="submit" name="form_submit" value="Edit Details">Import Data</button>
                                       <div id="notifier" ></div>


                                </form>

                        </div><!-- /.box-body -->
                    </div><!-- /.box -->
                </div>
            </div>

        </section>

        
        

   
        <div class="clearfix"></div>
    </div>
</aside>    
<script type="text/javascript">
$(document).ready(function () {
    $(".statusinput").click(function () {
        var conf_res = confirm("Are you sure to change the status?");
        if (conf_res) {
            var inp_id = $(this).attr("id");
            var words = inp_id.split("_");
            var status = words[0];
            var id = words[1];
            $.ajax({
            type: "POST",
             url: "<?php echo site_url("admin/users/change_status");?>",
            data: {"rid": id, "status": status},
            cache: false,
            success: function(data) {
               $("#notifier").html(data).addClass('alert alert-success');
                 setTimeout(function() { window.location = "<?php echo site_url('admin/dashboard');?>"; }, 2000);
            }
        });
        }
    })
});
</script>


