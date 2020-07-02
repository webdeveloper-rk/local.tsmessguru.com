<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Admin | Dashboard</title>
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
        <!-- bootstrap 3.0.2 -->
        <link href="<?php echo site_url();?>assets/admin/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <!-- font Awesome -->
        <link href="<?php echo site_url();?>assets/admin/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
        <!-- Ionicons -->
        <link href="<?php echo site_url();?>assets/admin/css/ionicons.min.css" rel="stylesheet" type="text/css" />
        <!-- bootstrap wysihtml5 - text editor -->
        <link href="<?php echo site_url();?>assets/admin/css/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css" rel="stylesheet" type="text/css" />
        <!-- Theme style -->
        <link href="<?php echo site_url();?>assets/admin/dist/css/AdminLTE.css" rel="stylesheet" type="text/css" />
       

 <link href="<?php echo site_url();?>assets/admin/dist/css/skins/_all-skins.min.css" rel="stylesheet" type="text/css" />
        <!-- jQuery 2.0.2 -->
        <script src="<?php echo site_url();?>assets/js_common/jquery-1.10.2.min.js"></script>

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
          <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
        <![endif]-->
    </head>
   <!-- <body class="skin-blue">-->
    <body class="skin-purple-light">
        <!-- header logo: style can be found in header.less -->
        <header class="header">
            <a href="<?php echo site_url("admin");?>" class="logo">
                <!-- Add the class icon to your logo image or logo icon to add the margining -->
               My Website-Admin Panel
            </a>
            <!-- Header Navbar: style can be found in header.less -->
            <nav class="navbar navbar-static-top" role="navigation">
                <!-- Sidebar toggle button-->
                <a href="#" class="navbar-btn sidebar-toggle" data-toggle="offcanvas" role="button">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </a>
                <div class="navbar-right">
                    <ul class="nav navbar-nav">
                        <!-- User Account: style can be found in dropdown.less -->
                        <li class="dropdown user user-menu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <i class="glyphicon glyphicon-user"></i>
                                <span><?php echo $this->session->userdata("user_name");?> <i class="caret"></i></span>
                            </a>
                            <ul class="dropdown-menu">
                                <!-- User image -->
                                <li class="user-header bg-light-blue">
                                    <img src="<?php echo site_url();?>assets/admin/img/avatar5.png" class="img-circle" alt="User Image" />
                                    <p>
                                        <?php echo $this->session->userdata("user_name");?> - Site Administrator
                                        <small>Member since November. 2014</small>
                                    </p>
                                </li>
                                <!-- Menu Body -->
                                <li class="user-body">
                                    <div class="col-xs-12 text-center">
                                        <a href="<?php echo site_url("admin/changepassword");?>" class="change_password">Change Password</a>
                                    </div>
                                </li>
                                <!-- Menu Footer-->
                                <li class="user-footer">
                                    <div class="pull-left">
                                        <a href="<?php echo site_url("admin/profile");?>" class="profile btn btn-default btn-flat">Profile</a>
                                    </div>
                                    <div class="pull-right">
                                        <a href="<?php echo site_url("admin/logout");?>" class="btn btn-default btn-flat">Sign out</a>
                                    </div>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>
        <div class="wrapper row-offcanvas row-offcanvas-left">
            <!-- Left side column. contains the logo and sidebar -->
            <aside class="left-side sidebar-offcanvas">
                <!-- sidebar: style can be found in sidebar.less -->
                <section class="sidebar">
                    <!-- Sidebar user panel -->
                    <div class="user-panel">
                        <div class="pull-left image">
                            <img src="<?php echo site_url();?>assets/admin/img/avatar5.png" class="img-circle" alt="User Image" />
                        </div>
                        <div class="pull-left info">
                            <p>Hello, <?php echo $this->session->userdata("user_name");?></p>

                            <a href="#"><i class="fa fa-circle text-primary"></i> Online</a>
                        </div>
                    </div>
                    
                    <!-- sidebar menu: : style can be found in sidebar.less -->
                     <ul class="sidebar-menu">
                        <li class="">
                            <a href="http://localhost/annapurna/admin/dashboard">
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
											  <ul class="treeview-menu" style="display: none;">
												
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
											  <ul class="treeview-menu" style="display: none;">
												
												<li><a href="&lt;?php echo site_url();?&gt;manage/schools/modals/add" style="margin-left: 10px;"><i class="fa fa-circle-o text-yellow"></i> Add School</a></li>
												<li><a href="&lt;?php echo site_url();?&gt;manage/schools" style="margin-left: 10px;"><i class="fa fa-circle-o text-aqua"></i> List Schools</a></li>
												 
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
											  <ul class="treeview-menu" style="display: none;">
												
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
											  <ul class="treeview-menu" style="display: none;">
												
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
											  <ul class="treeview-menu" style="display: none;"> 
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
											  <ul class="treeview-menu" style="display: none;"> 
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
                            <a href="&lt;?php echo site_url();?&gt;manage/settings">
                                <i class="fa  fa-tag "></i> 
                                <span>Settings</span>
                            </a>
                        </li> 
						
                           <li class="">
                            <a href="http://localhost/annapurna/admin/changepassword">
                                <i class="fa fa-lock "></i> 
                                <span>Change Password</span>
                            </a>
                        </li> 
						   <li class="">
                            <a href="http://localhost/annapurna/admin/logout">
                                <i class="fa  fa-arrow-left  "></i> 
                                <span>Logout</span>
                            </a>
                        </li> 
                    </ul>
                        <li class="<?php echo ($this->uri->segment(2) == "dashboard")?"active":"";?>">
                            <a href="<?php echo site_url("admin/dashboard");?>">
                                <i class="fa fa-dashboard"></i> <span>Dashboard</span>
                            </a>
                        </li>
						<li class="treeview">
											  <a href="#">
												<i class="fa fa-laptop"></i>
												<span>Manage Areas</span>
												<span class="pull-right-container">
												  <i class="fa fa-angle-left pull-right"></i>
												</span>
											  </a>
											  <ul class="treeview-menu">
												
												<li><a href="icons.html"><i class="fa fa-circle-o text-yellow"></i> Add Area</a></li>
												<li><a href="general.html"><i class="fa fa-circle-o text-aqua"></i> List Areas</a></li>
												 
											  </ul>
						</li>
                      <li class="treeview">
											  <a href="#">
												<i class="fa fa-laptop"></i>
												<span>Manage Schools</span>
												<span class="pull-right-container">
												  <i class="fa fa-angle-left pull-right"></i>
												</span>
											  </a>
											  <ul class="treeview-menu">
												
												<li><a href="<?php echo site_url("cms/manage/schools/modals/add");?>"><i class="fa fa-circle-o text-yellow"></i> Add School</a></li>
												<li><a href="<?php echo site_url("cms/manage/schools");?>"><i class="fa fa-circle-o text-aqua"></i> List Schools</a></li>
												 
											  </ul>
						</li>
						 <li class="treeview">
											  <a href="#">
												<i class="fa fa-laptop"></i>
												<span>Provisional items</span>
												<span class="pull-right-container">
												  <i class="fa fa-angle-left pull-right"></i>
												</span>
											  </a>
											  <ul class="treeview-menu">
												
												<li><a href="icons.html"><i class="fa fa-circle-o text-yellow"></i> Add New</a></li>
												<li><a href="general.html"><i class="fa fa-circle-o text-aqua"></i>List Provisional items</a></li>
												 
											  </ul>
						</li>
						 <li class="treeview">
											  <a href="#">
												<i class="fa fa-laptop"></i>
												<span>Non Provisional items</span>
												<span class="pull-right-container">
												  <i class="fa fa-angle-left pull-right"></i>
												</span>
											  </a>
											  <ul class="treeview-menu">
												
												<li><a href="icons.html"><i class="fa fa-circle-o text-yellow"></i> Add New</a></li>
												<li><a href="general.html"><i class="fa fa-circle-o text-aqua"></i>List NON-Provisional items</a></li>
												 
											  </ul>
						</li>
						
						 <li class="treeview">
											  <a href="#">
												<i class="fa fa-laptop"></i>
												<span>Vendors</span>
												<span class="pull-right-container">
												  <i class="fa fa-angle-left pull-right"></i>
												</span>
											  </a>
											  <ul class="treeview-menu"> 
												<li><a href="general.html"><i class="fa fa-circle-o text-aqua"></i>List Vendors</a></li>
												 
											  </ul>
						</li>
						 <li class="treeview">
											  <a href="#">
												<i class="fa fa-laptop"></i>
												<span>Reports</span>
												<span class="pull-right-container">
												  <i class="fa fa-angle-left pull-right"></i>
												</span>
											  </a>
											  <ul class="treeview-menu"> 
												<li><a href="general.html"><i class="fa fa-circle-o text-aqua"></i>Report 1</a></li>
												<li><a href="general.html"><i class="fa fa-circle-o text-yellow"></i>Report 2</a></li>
												<li><a href="general.html"><i class="fa fa-circle-o text-aqua"></i>Report 3</a></li>
												<li><a href="general.html"><i class="fa fa-circle-o text-yellow"></i>Report 4</a></li>
												<li><a href="general.html"><i class="fa fa-circle-o text-aqua"></i>Report 5</a></li>
												<li><a href="general.html"><i class="fa fa-circle-o text-yellow"></i>Report 6</a></li>
												<li><a href="general.html"><i class="fa fa-circle-o text-aqua"></i>Report 7</a></li>
												<li><a href="general.html"><i class="fa fa-circle-o text-yellow"></i>Report 8</a></li>
												 
											  </ul>
						</li>
						
						
                      
                      <!--
						<li class="<?php echo ($this->uri->segment(3) == "pages")?"active":"";?>">
                            <a href="<?php echo site_url("cms/manage/pages");?>">
                                <i class="fa fa-floppy-o  "></i> 
                                <span>Main Pages</span>
                            </a>
                        </li> 
						<li class="<?php echo ($this->uri->segment(3) == "aqpages")?"active":"";?>">
                            <a href="<?php echo site_url("cms/manage/aqpages");?>">
                                <i class="fa fa-floppy-o  "></i> 
                                <span>Other Cms pages</span>
                            </a>
                        </li> 
						
						<li class="<?php echo ($this->uri->segment(3) == "albums")?"active":"";?>">
                            <a href="<?php echo site_url("cms/manage/albums");?>">
                                <i class="fa fa-picture-o"></i> 
                                <span>Product Albums</span>
                            </a>
                        </li> 
						
							
						<li class="<?php echo ($this->uri->segment(3) == "videoalbums")?"active":"";?>">
                            <a href="<?php echo site_url("cms/manage/videoalbums");?>">
                                <i class="fa fa-picture-o"></i> 
                                <span>Video Albums</span>
                            </a>
                        </li> 
							<li class="<?php echo ($this->uri->segment(3) == "videos")?"active":"";?>">
                            <a href="<?php echo site_url("cms/manage/videos");?>">
                                <i class="fa fa-picture-o"></i> 
                                <span>Videos</span>
                            </a>
                        </li> 
						 
						 
							<li class="<?php echo ($this->uri->segment(3) == "scroller")?"active":"";?>">
                            <a href="<?php echo site_url("cms/manage/scroller");?>">
                                 <i class="fa  fa-flickr"></i> 
                                <span>Scroller Images</span>
                            </a>
                        </li> 
						 
							<li class="<?php echo ($this->uri->segment(3) == "sliders")?"active":"";?>">
                            <a href="<?php echo site_url("cms/manage/sliders");?>">
                                 <i class="fa  fa-flickr"></i> 
                                <span>Slideshows</span>
                            </a>
                        </li> -->
						  <li class="<?php echo ($this->uri->segment(3) == "settings")?"active":"";?>">
                            <a href="<?php echo site_url("cms/manage/settings");?>">
                                <i class="fa  fa-tag "></i> 
                                <span>Settings</span>
                            </a>
                        </li> 
						
                           <li class="<?php echo ($this->uri->segment(2) == "changepassword")?"active":"";?>">
                            <a href="<?php echo site_url("admin/changepassword");?>">
                                <i class="fa fa-lock "></i> 
                                <span>Change Password</span>
                            </a>
                        </li> 
						   <li class="<?php echo ($this->uri->segment(2) == "logout")?"active":"";?>">
                            <a href="<?php echo site_url("admin/logout");?>">
                                <i class="fa  fa-arrow-left  "></i> 
                                <span>Logout</span>
                            </a>
                        </li> 
                    </ul>
                </section>
                <!-- /.sidebar -->
            </aside>

            <!-- Right side column. Contains the navbar and content of the page -->
            <?php $this->load->view($module . "/" . $view_file); ?>
            <!-- /.right-side -->
        </div><!-- ./wrapper -->
       
        <!-- add new calendar event modal -->

        <!-- jQuery UI 1.10.3 -->
        <script src="<?php echo site_url();?>assets/admin/js/jquery-ui-1.10.3.min.js" type="text/javascript"></script>
        <!-- Bootstrap -->
        <script src="<?php echo site_url();?>assets/admin/js/bootstrap.min.js" type="text/javascript"></script>
        <!-- Sparkline -->
        <script src="<?php echo site_url();?>assets/admin/js/plugins/sparkline/jquery.sparkline.min.js" type="text/javascript"></script>
        <!-- iCheck -->
        <script src="<?php echo site_url();?>assets/admin/js/plugins/iCheck/icheck.min.js" type="text/javascript"></script>

<!--         AdminLTE App -->
        <script src="<?php echo site_url();?>assets/admin/js/AdminLTE/app.js" type="text/javascript"></script>
        
<!--         AdminLTE dashboard demo (This is only for demo purposes) -->
        <script src="<?php echo site_url();?>assets/admin/js/AdminLTE/dashboard.js" type="text/javascript"></script>        

    </body>
</html>