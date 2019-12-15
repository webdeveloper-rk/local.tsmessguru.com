<!DOCTYPE html>
<html class="bg-black">
    <head>
        <meta charset="UTF-8">
        <title>Admin | Log in</title>
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
        <!-- bootstrap 3.0.2 -->
        <link href="<?php echo site_url();?>assets/admin/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <!-- font Awesome -->
        <link href="<?php echo site_url();?>assets/admin/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
        <!-- Theme style -->
        <link href="<?php echo site_url();?>assets/admin/css/AdminLTE.css" rel="stylesheet" type="text/css" />

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
          <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
        <![endif]-->
        <style type="text/css">
            .form-box .header, .bg-olive {
                background-color: #3C8DBC !important;
            }
        </style>
    </head>
    <body class="bg-black">

        <div class="form-box" id="login-box">
            <div class="header">Login</div>
            <form id="main_form" action="<?php echo current_url();?>" method="post">
                <div class="body bg-gray">
                    <div class="form-group">
                        <input type="email" required name="email" id="inputEmail" class="form-control" placeholder="Email ID"/>
                    </div>
                    <div class="form-group">
                        <input type="password" required name="password" class="form-control" placeholder="Password"/>
                    </div>          
                    
                </div>
                <div class="footer">                                                               
                    <button type="submit" class="btn bg-olive btn-block">Sign me in</button>  
                </div>
                <div id="notifier"><?php echo $this->session->flashdata("notice");?></div>
            </form>

           
        </div>


        <!-- jQuery 1.10.2 -->
        <script src="<?php echo site_url();?>assets/admin/js/jquery-1.10.2.min.js"></script>
        <script src="<?php echo site_url();?>assets/admin/js/jquery.form.js"></script>
        <!-- Bootstrap -->
        <script src="<?php echo site_url();?>assets/admin/js/bootstrap.min.js" type="text/javascript"></script>        
        <script type="text/javascript">
            $(document).ready(function() {
                $('#main_form').ajaxForm({dataType: 'json', success: processJson});
                $("#inputEmail").focus();
            });
            function processJson(data) {
                if (data.success) {
                    $("#notifier").html(data.message);
                    setTimeout(function() {
                        window.location = "<?php echo site_url('admin'); ?>";
                    }, 2000);
                } else {
                    $("#notifier").html(data.message);
                }
            }
        </script>

    </body>
</html>