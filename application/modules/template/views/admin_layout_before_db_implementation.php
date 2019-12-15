<?php 
  $site_name  =  $this->config->item('site_name'); 
  $menus_rs = $this->db->query("select * from ");
 ?>
<style>
@media print
{    
   form
    {
        display: none !important;
    }
}
 
</style>

<style>
.alert-success
{
	 color: #FFF;
    background-color: #4CAF50;
    border-color: #ebccd1;
}
.alert {
    padding: 15px;
    margin-bottom: 20px;
    border: 1px solid transparent;
    border-radius: 4px;
}
 
@media print
{    
   .noprint
    {
        display: none !important;
    }
	.dataTables_paginate
	{
        display: none !important;
    }
}


</style>
 <?php  //echo '<pre>'; print_r($this->session->all_userdata()); 
$school_id = $this->session->userdata("school_id");		
$school_name = $this->session->userdata("school_name");
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?php echo $this->config->item("society_name");?> | <?php echo $school_name;?></title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="<?php echo site_url();?>bootstrap/css/bootstrap.min.css?id=906789">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo site_url();?>dist/css/AdminLTE.min.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="<?php echo site_url();?>dist/css/skins/_all-skins.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="<?php echo site_url();?>plugins/iCheck/flat/blue.css">
  <!-- Morris chart -->
  <link rel="stylesheet" href="<?php echo site_url();?>plugins/morris/morris.css">
  <!-- jvectormap -->
  <link rel="stylesheet" href="<?php echo site_url();?>plugins/jvectormap/jquery-jvectormap-1.2.2.css">
  <!-- Date Picker -->
  <link rel="stylesheet" href="<?php echo site_url();?>plugins/datepicker/datepicker3.css">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="<?php echo site_url();?>plugins/daterangepicker/daterangepicker.css">
  <!-- bootstrap wysihtml5 - text editor -->
  <link rel="stylesheet" href="<?php echo site_url();?>plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
  
    
	
	 <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
	 
	 <link  href="<?php echo site_url();?>assets/fancybox/jquery.fancybox.css" rel="stylesheet">


  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
  <style>
  .validation_errors
  {
	   
    background-color: #E33439;
    width: auto;
    border: 1px solid green;
    padding: 25px;
    margin: 25px;
    color:#FFFFFF;
	border-radius: 10px;
 
  }
.responsive .logo { display: none !important; }
.main-header {
    background-color: #FFCD41;
}
.box-footer input
{
	margin-left:10px;
}
.red{
	padding-left:5px;
	color:#FF0000;
}
.cleft10px{
	padding-left:10px
}
  </style>
</head>
<body class="hold-transition skin-yellow sidebar-mini">
<div class="wrapper">

  <header class="main-header">
    <!-- Logo -->
    <a href="<?php echo site_url();?>admin" class="logo" style="display:none">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><b><?php echo $this->config->item("society_name");?></b></span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><b><?php echo $this->config->item("society_name");?></b></span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>
		<span style="font-weight:15px;color: #FFF;font-size: 30px;font-weight: bold;margin: auto;margin-left: 50px;"> <?php echo $this->config->item("society_name");?> </span>
      <div class="navbar-custom-menu">
	   
        <ul class="nav navbar-nav">
        
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <img src="<?php echo site_url();?>dist/img/user2-160x160.jpg" class="user-image" alt="User Image">
              <span class="hidden-xs"><?php echo $this->session->userdata("user_name");?></span>
            </a>
            <ul class="dropdown-menu">
              <!-- User image -->
              <li class="user-header">
                <img src="<?php echo site_url();?>dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">

                <p>
                  <?php echo $this->session->userdata("user_name");?> 
                  <!--<small>Member since June. 2017</small>-->
                </p>
              </li>
              <!-- Menu Body -->
              <li class="user-body">
                
                <!-- /.row -->
              </li>
              <!-- Menu Footer-->
              <li class="user-footer">
              <!--  <div class="pull-left">
                  <a href="#" class="btn btn-default btn-flat">Profile</a>
                </div>-->
                <div class="pull-right">
                  <a href="<?php echo site_url("general/logout");?>" class="btn btn-default btn-flat">Sign out</a>
                </div>
              </li>
            </ul>
          </li>
          <!-- Control Sidebar Toggle Button -->
        
        </ul>
      </div>
    </nav>
  </header>
  <!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
      <div class="user-panel">
        <div class="pull-left image">
          <img src="<?php echo site_url();?>dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">
        </div>
        <div class="pull-left info">
          <p> <?php echo $this->session->userdata("user_name");?> </p>
          <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
        </div>
      </div>
      
      <!-- sidebar menu: : style can be found in sidebar.less -->
 
     <ul class="sidebar-menu">
	<?php include "menu_admin.php";?>
	<?php include "menu_school.php";?>
	<?php include "menu_ct.php";?>
	<?php include "menu_subadmin.php";?>
	
						
	
		 

		
	
	 								 <?php
		if ($this->session->userdata("is_loggedin") == TRUE && $this->session->userdata("is_dco")==true  || ($this->session->userdata("user_id") > 2  &&  $this->session->userdata("user_role") == "subadmin" && $school_code !="subadmin2")) {
																 ?>
																  <li class="treeview">
														  <a href="#">
															<i class="fa fa-laptop"></i>
															<span>UPLOADS</span>
															<span class="pull-right-container">
															  <i class="fa fa-angle-leftcc pull-right">></i>
															</span>
														  </a>
								  <ul class="treeview-menu" style="display: none;">
									<li><a href="<?php echo site_url();?>report_purchase_bills/spaces_bills"><i class="fa fa-circle-o"></i> Purchase Bills  </a></li>
									<li><a href="<?php echo site_url();?>report_purchase_bills"><i class="fa fa-circle-o"></i> Purchase Bills Old</a></li>
									<li><a href="<?php echo site_url();?>admin_diet_pics"><i class="fa fa-circle-o"></i> DIET PICS</a></li>
									
									 
								  </ul>
        </li>
																 <li class="treeview">
														  <a href="#">
															<i class="fa fa-laptop"></i>
															<span>REPORTS</span>
															<span class="pull-right-container">
															  <i class="fa fa-angle-leftcc pull-right">></i>
															</span>
														  </a>
								  <ul class="treeview-menu" style="display: none;">
									
									 
									
									<li  >
																			<a href="<?php echo site_url();?>authorisations_entries">
																			<i class="fa fa-circle-o"></i>
																			<span>Caretaker Authorisations</span>

																			</a>

																	</li>
																	
																	<li  >
																			<a href="<?php echo site_url();?>consumption_savings">
																			<i class="fa fa-circle-o"></i>
																			<span>Consumption Savings</span>

																			</a>

																	</li>
																	
																	<li  >
																			<a href="<?php echo site_url();?>report_item_purchases">
																			<i class="fa fa-circle-o"></i>
																			<span>Purchase Item Reports</span>

																			</a>

																	</li>
																	<li  >
																			<a href="<?php echo site_url();?>report_item_consumptions">
																			<i class="fa fa-circle-o"></i>
																			<span>Consumption Item Reports</span>

																			</a>

																	</li>
																	
																<li  >
																			<a href="<?php echo site_url();?>itewise_consumption_dailyentries">
																			<i class="fa fa-circle-o"></i>
																			<span>Consumption daywise Items</span>

																			</a>

																	</li>
									
									
									<li><a href="<?php echo site_url();?>report_item_consumptions"><i class="fa fa-circle-o"></i> Daily Consumptions</a></li>
										<li  >
																			<a href="<?php echo site_url();?>monthly_consolidated">
																			<i class="fa fa-circle-o"></i>
																			<span>DIET Expenditure   </span>

																			</a>

																	</li>
									<li  >
											<a href="<?php echo site_url();?>monthly_consolidated_dates">
											<i class="fa fa-circle-o"></i>
											<span>DIET Expenditure  Dates </span>

											</a>

									</li> 
									<li  >
																			<a href="<?php echo site_url();?>school_item_balances">
																			<i class="fa fa-circle-o"></i>
																			<span>School Itembalance Reports</span>

																			</a>

																	</li>
	
											 
											<li><a href="<?php echo site_url();?>item_balances"><i class="fa fa-circle-o"></i> Item Balances</a></li>
											<li  >
																			<a href="<?php echo site_url();?>missed_entries">
																			<i class="fa fa-circle-o"></i>
																			<span>Missed Schools Reports</span>

																			</a>

																	</li>
												<li>
																			<a href="<?php echo site_url();?>missed_entries/itemwise">
																			<i class="fa fa-circle-o"></i>
																			<span>Menu Missed Schools</span>

																			</a>

																	</li>
													<li>
																			<a href="<?php echo site_url();?>missed_entries/usedschools">
																			<i class="fa fa-circle-o"></i>
																			<span>Menu Track</span>

																			</a>

																	</li>

																	</li> 
																	 
									 
								  </ul>
        </li>
			 
			
															 <?php } ?>
															 
															 
						 <?php
				 $secretary_roles = array('secretary','dsapswreis');
	 if ($this->session->userdata("is_loggedin") == TRUE && in_array($this->session->userdata("school_code"), $secretary_roles)) {
		 ?>     
<li  >
				  <a href="<?php echo site_url();?>school_day_report">
					<i class="fa fa-area-chart"></i>
					<span>Dashboard</span>
					 
				  </a>
											 
			</li>
						
				<li  >
																			<a href="<?php echo site_url();?>report_item_purchases">
																			<i class="fa fa-circle-o"></i>
																			<span>Purchase Item Reports</span>

																			</a>

																	</li>
			<li><a href="<?php echo site_url();?>report_item_consumptions"><i class="fa fa-circle-o"></i> Daily Consumptions</a></li>
			
			 
																	
							<li  >
																			<a href="<?php echo site_url();?>report_item_consumptions">
																			<i class="fa fa-circle-o"></i>
																			<span>Consumption Item Reports</span>

																			</a>

																	</li>
						<li  >
																			<a href="<?php echo site_url();?>school_item_balances">
																			<i class="fa fa-circle-o"></i>
																			<span>School Itembalance Reports</span>

																			</a>

																	</li>
																	
																	 <li>
																			<a href="<?php echo site_url();?>missed_entries">
																			<i class="fa fa-circle-o"></i>
																			<span>Missed Schools Reports</span>

																			</a>

																	</li>
												<li>
																			<a href="<?php echo site_url();?>missed_entries/itemwise">
																			<i class="fa fa-circle-o"></i>
																			<span>Menu Missed Schools</span>

																			</a>

																	</li>
													<li>
																			<a href="<?php echo site_url();?>missed_entries/usedschools">
																			<i class="fa fa-circle-o"></i>
																			<span>Menu Track</span>

																			</a>

																	</li>
																	 <li class="treeview">
														  <a href="#">
															<i class="fa fa-laptop"></i>
															<span>UPLOADS</span>
															<span class="pull-right-container">
															  <i class="fa fa-angle-leftd pull-right">></i>
															</span>
														  </a>
								  <ul class="treeview-menu" style="display: none;">
									<li><a href="<?php echo site_url();?>report_purchase_bills/spaces_bills"><i class="fa fa-circle-o"></i> Purchase Bills  </a></li>
									<li><a href="<?php echo site_url();?>report_purchase_bills"><i class="fa fa-circle-o"></i> Purchase Bills Old</a></li>
									<li><a href="<?php echo site_url();?>admin_diet_pics"><i class="fa fa-circle-o"></i> DIET PICS</a></li>
									 
								  </ul>
        </li>
			 


	 <?php } ?>
	 
	  <?php
				 $secretary_roles = array('subadmin2');
	 if ($this->session->userdata("is_loggedin") == TRUE && in_array($this->session->userdata("school_code"), $secretary_roles)) {
		 ?>     
 
						
			<li><a href="<?php echo site_url();?>admin_diet_pics"><i class="fa fa-circle-o"></i> DIET PICS</a></li>
																	
						 
					<li  >
																			<a href="<?php echo site_url();?>itewise_consumption_dailyentries">
																			<i class="fa fa-circle-o"></i>
																			<span>Consumption daywise Items</span>

																			</a>

																	</li>

	 <?php } ?>
	 
	 
		 <li class="">
                            <a href="<?php echo site_url();?>general/changepassword" >
                                <i class="fa fa-lock "></i> 
                                <span>Change Password</span>
                            </a>
                        </li> 
	 <?php  ?>
						
						
						
						   <li class="">
                            <a href="<?php echo site_url();?>general/logout">
                                <i class="fa  fa-arrow-left  "></i> 
                                <span>Logout</span>
                            </a>
                        </li> 
                    </ul>
					
    </section>
    <!-- /.sidebar -->
  </aside>














  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
        

        <!-- Main content -->
        <div class="content body">
<!-- jQuery 2.2.3 -->
<!--<script src="<?php echo site_url();?>plugins/jQuery/jquery-2.2.3.min.js"></script>-->
<script src="<?php echo site_url();?>assets/grocery_crud/js/jquery-1.11.1.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<!--<script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>-->
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<section id="introduction">
  <?php $this->load->view($module . "/" . $view_file); ?>
</section><!-- /#introduction -->

   
    </div>
	</div>
  </aside>
  <!-- /.control-sidebar -->
  <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->


<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button);
</script>
<!-- Bootstrap 3.3.6 -->
<script src="<?php echo site_url();?>bootstrap/js/bootstrap.min.js"></script>

<!-- Morris.js charts -->
<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
<script src="<?php echo site_url();?>plugins/morris/morris.min.js"></script>
-->

<!-- Sparkline -->
<script src="<?php echo site_url();?>plugins/sparkline/jquery.sparkline.min.js"></script>
<!-- jvectormap -->
<script src="<?php echo site_url();?>plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
<script src="<?php echo site_url();?>plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
<!-- jQuery Knob Chart -->
<script src="<?php echo site_url();?>plugins/knob/jquery.knob.js"></script>
<!-- daterangepicker -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js"></script>
<script src="<?php echo site_url();?>plugins/daterangepicker/daterangepicker.js"></script>
<!-- datepicker -->
<script src="<?php echo site_url();?>plugins/datepicker/bootstrap-datepicker.js"></script>
<!-- Bootstrap WYSIHTML5 -->
<script src="<?php echo site_url();?>plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>

<script src="<?php echo site_url();?>plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo site_url();?>plugins/datatables/dataTables.bootstrap.min.js"></script>

<!-- Slimscroll -->
<script src="<?php echo site_url();?>plugins/slimScroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="<?php echo site_url();?>plugins/fastclick/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="<?php echo site_url();?>dist/js/app.min.js"></script>
<!-- AdminLTE school demo (This is only for demo purposes) -->
<script src="<?php echo site_url();?>dist/js/pages/school.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="<?php echo site_url();?>dist/js/demo.js"></script>

<script src="<?php echo site_url();?>assets/fancybox/jquery.fancybox.js"></script>
</body>
</html>
