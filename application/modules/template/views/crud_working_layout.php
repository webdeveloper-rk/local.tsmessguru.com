<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Annapurna | Dashboard</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="<?php echo site_url();?>bootstrap/css/bootstrap.min.css">
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

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
</head>
<body class="hold-transition skin-purple-light sidebar-mini">
<div class="wrapper">

  <header class="main-header">
    <!-- Logo -->
    <a href="<?php echo site_url();?>admin" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><b>Annapurna</b></span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><b>Annapurna</b></span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>

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
                  <small>Member since Aug. 2016</small>
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
                  <a href="<?php echo site_url("admin/logout");?>" class="btn btn-default btn-flat">Sign out</a>
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
                        <li class="">
                            <a href="<?php echo site_url();?>admin/dashboard">
                                <i class="fa fa-dashboard"></i> <span>Dashboard</span>
                            </a>
                        </li>
						<li class="treeview">
											  <a href="#">
												<i class="glyphicon glyphicon-map-marker"></i>
												<span>Manage Areas</span>
												<span class="pull-right-container">
												  <i class="fa fa-angle-left pull-right"></i>
												</span>
											  </a>
											  <ul class="treeview-menu">
												
												<li><a href="icons.html" style="margin-left: 10px;"><i class="fa fa-circle-o text-yellow"></i> Add Area</a></li>
												<li><a href="general.html" style="margin-left: 10px;"><i class="fa fa-circle-o text-aqua"></i> List Areas</a></li>
												 
											  </ul>
						</li>
                      <li class="treeview">
											  <a href="#">
												<i class="fa fa-institution"></i>
												<span>Manage Schools</span>
												<span class="pull-right-container">
												  <i class="fa fa-angle-left pull-right"></i>
												</span>
											  </a>
											  <ul class="treeview-menu">
												
												<li><a href="<?php echo site_url();?>cms/manage/schools/modals/add" style="margin-left: 10px;"><i class="fa fa-circle-o text-yellow"></i> Add School</a></li>
												<li><a href="<?php echo site_url();?>cms/manage/schools" style="margin-left: 10px;"><i class="fa fa-circle-o text-aqua"></i> List Schools</a></li>
												 
											  </ul>
						</li>
						 <li class="treeview">
											  <a href="#">
												<i class="fa fa-balance-scale"></i>
												<span>Provisional items</span>
												<span class="pull-right-container">
												  <i class="fa fa-angle-left pull-right"></i>
												</span>
											  </a>
											  <ul class="treeview-menu">
												
												<li><a href="icons.html" style="margin-left: 10px;"><i class="fa fa-circle-o text-yellow"></i> Add New</a></li>
												<li><a href="general.html" style="margin-left: 10px;"><i class="fa fa-circle-o text-aqua"></i>List Provisional items</a></li>
												 
											  </ul>
						</li>
						 <li class="treeview">
											  <a href="#">
												<i class="glyphicon glyphicon-apple"></i>
												<span>Non Provisional items</span>
												<span class="pull-right-container">
												  <i class="fa fa-angle-left pull-right"></i>
												</span>
											  </a>
											  <ul class="treeview-menu">
												
												<li><a href="icons.html" style="margin-left: 10px;"><i class="fa fa-circle-o text-yellow"></i> Add New</a></li>
												<li><a href="general.html" style="margin-left: 10px;"><i class="fa fa-circle-o text-aqua"></i>List NON-Provisional items</a></li>
												 
											  </ul>
						</li>
						
						 <li class="treeview">
											  <a href="#">
												<i class="fa fa-truck"></i>
												<span>Tendor vendors</span>
												<span class="pull-right-container">
												  <i class="fa fa-angle-left pull-right"></i>
												</span>
											  </a>
											  <ul class="treeview-menu"> 
												<li><a href="general.html" style="margin-left: 10px;"><i class="fa fa-circle-o text-aqua"></i>List Tendor vendors</a></li>
												 
											  </ul>
						</li>
						 <li class="treeview">
											  <a href="#">
												<i class="fa fa-area-chart"></i>
												<span>Reports</span>
												<span class="pull-right-container">
												  <i class="fa fa-angle-left pull-right"></i>
												</span>
											  </a>
											  <ul class="treeview-menu"> 
												<li><a href="general.html" style="margin-left: 10px;"><i class="fa fa-circle-o text-aqua"></i>Report 1</a></li>
												<li><a href="general.html" style="margin-left: 10px;"><i class="fa fa-circle-o text-yellow"></i>Report 2</a></li>
												<li><a href="general.html" style="margin-left: 10px;"><i class="fa fa-circle-o text-aqua"></i>Report 3</a></li>
												<li><a href="general.html" style="margin-left: 10px;"><i class="fa fa-circle-o text-yellow"></i>Report 4</a></li>
												<li><a href="general.html" style="margin-left: 10px;"><i class="fa fa-circle-o text-aqua"></i>Report 5</a></li>
												<li><a href="general.html" style="margin-left: 10px;"><i class="fa fa-circle-o text-yellow"></i>Report 6</a></li>
												<li><a href="general.html" style="margin-left: 10px;"><i class="fa fa-circle-o text-aqua"></i>Report 7</a></li>
												<li><a href="general.html" style="margin-left: 10px;"><i class="fa fa-circle-o text-yellow"></i>Report 8</a></li>
												 
											  </ul>
						</li>
						
						
                      
                      <!--
						<li class="">
                            <a href="<?php echo site_url();?>manage/pages">
                                <i class="fa fa-floppy-o  "></i> 
                                <span>Main Pages</span>
                            </a>
                        </li> 
						<li class="">
                            <a href="<?php echo site_url();?>manage/aqpages">
                                <i class="fa fa-floppy-o  "></i> 
                                <span>Other Cms pages</span>
                            </a>
                        </li> 
						
						<li class="">
                            <a href="<?php echo site_url();?>manage/albums">
                                <i class="fa fa-picture-o"></i> 
                                <span>Product Albums</span>
                            </a>
                        </li> 
						
							
						<li class="">
                            <a href="<?php echo site_url();?>manage/videoalbums">
                                <i class="fa fa-picture-o"></i> 
                                <span>Video Albums</span>
                            </a>
                        </li> 
							<li class="">
                            <a href="<?php echo site_url();?>manage/videos">
                                <i class="fa fa-picture-o"></i> 
                                <span>Videos</span>
                            </a>
                        </li> 
						 
						 
							<li class="">
                            <a href="<?php echo site_url();?>manage/scroller">
                                 <i class="fa  fa-flickr"></i> 
                                <span>Scroller Images</span>
                            </a>
                        </li> 
						 
							<li class="">
                            <a href="<?php echo site_url();?>manage/sliders">
                                 <i class="fa  fa-flickr"></i> 
                                <span>Slideshows</span>
                            </a>
                        </li> -->
						  <li class="">
                            <a href="<?php echo site_url();?>admin/manage/settings">
                                <i class="fa  fa-tag "></i> 
                                <span>Settings</span>
                            </a>
                        </li> 
						
                           <li class="">
                            <a href="<?php echo site_url();?>admin/changepassword">
                                <i class="fa fa-lock "></i> 
                                <span>Change Password</span>
                            </a>
                        </li> 
						   <li class="">
                            <a href="<?php echo site_url();?>admin/logout">
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

<section id="introduction"><aside class="right-side" style="margin-left:30px"> 
  <?php  $this->load->view($module . "/" . $view_file); ?></aside>
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

</body>
</html>
