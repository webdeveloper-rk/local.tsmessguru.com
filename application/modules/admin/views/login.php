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
<style>
.form-box {
    width: 500px;
    margin: 90px auto 0 auto;
}

</style>
    </head>

    <body class="bg-black">



        <div class="form-box" id="login-box">

            <div class="header">Login </div>

            <form id="main_form" action="<?php echo current_url();?>" method="post">

                <div class="body bg-gray">
 <div class="form-group">
					 
                      <img src="<?php echo site_url();?>images/login.png">
					 

                    </div>
                    <div class="form-group">

                        <input type="text" required name="email" id="inputEmail" class="form-control" placeholder="school Code"/>

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
	<?php $school_id = $this->session->userdata('school_id');
		$opening_balance_start_date = $this->config->item('opening_balance_start_date');
		$opening_balance_end_date = $this->config->item('opening_balance_end_date'); 
		
		$sql = "SELECT CURRENT_DATE <= '$opening_balance_end_date' as allowed";
		$rs = $this->db->query($sql);
		$allowed = $rs->row()->allowed;
		if($allowed > 0 )
		{
			?>
<div id="dialog-message" title="Attention dear staff">
  <p style="float:left; margin:0 7px 50px 0;color:#FF0000;font-weight:bold;">
    
    <!--If you have any issues with <i>Avilable Quantity</i> please use <i><span style='color:#00FF00;'>Click here to Recalculate</span></i> option which is avilable under "Avilable Quantity" in Consumption Entry Screen-->

	<h2>Opening Balance entries opened for updation from jun 4th 2018 to jun 5th 2018 midnight . <br><br>Opening balances will be locked on jun 6th 2018.
<br><br>	Please update opening balance for required items. </h2>
	
  </p>
  
</div>
		<?php } ?>



        <!-- jQuery 1.10.2 -->

        <script src="<?php echo site_url();?>assets/admin/js/jquery-1.10.2.min.js"></script>

        <script src="<?php echo site_url();?>assets/admin/js/jquery.form.js"></script>

        <!-- Bootstrap -->

        <script src="<?php echo site_url();?>assets/admin/js/bootstrap.min.js" type="text/javascript"></script>        

		
		 <link rel="stylesheet" href="http://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css"> 
 
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
		
		<script>
  $( function() {
    $( "#dialog-message" ).dialog({
      modal: true,
      buttons: {
        Ok: function() {
          $( this ).dialog( "close" );
        }
      }
    });
  } );
  </script>
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