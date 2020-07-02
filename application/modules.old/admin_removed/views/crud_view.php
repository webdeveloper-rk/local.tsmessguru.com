<!-- DATA TABLES -->
<link href="<?php echo site_url();?>assets/admin/css/datatables/dataTables.bootstrap.css" rel="stylesheet" type="text/css" />
<script src="<?php echo site_url(); ?>assets/js_common/custom.js" type="text/javascript"></script>
<aside class="right-side">                
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <?php echo $page_heading;?>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo site_url("admin/dashboard");?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <?php echo $breadcrumb; ?>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
<!--                        <h3 class="box-title"></h3> -->
                    </div><!-- /.box-header -->
                    <div class="box-body table-responsive">
                        <?php echo $crud_output;?>
                    </div><!-- /.box-body -->
                </div><!-- /.box -->
            </div>
        </div>

    </section><!-- /.content -->
</aside>
<script type="text/javascript">
    $(document).ready(function () {
        $("#cname_input_box input").blur(function () {
            var field_title = $(this).val();
            $.ajax({
            type: "POST",
            url: "<?php echo site_url("common/gen_url");?>",
            data: {"title": field_title},
            cache: false,
            success: function(data){
                    $("#url_input_box input").val(data);
                }
            });
        })
    });
</script>