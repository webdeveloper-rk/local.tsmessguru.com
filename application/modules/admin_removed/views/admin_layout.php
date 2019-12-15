<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>TSMESS | school</title>
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
  
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
	
	 <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

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
    background-color: #605ca8;
}
.box-footer input
{
	margin-left:10px;
}
.red{
	padding-left:5px;
	color:#FF0000;
}
  </style>
</head>
<body class="hold-transition skin-purple sidebar-mini">
<div class="wrapper">

  <header class="main-header">
    <!-- Logo -->
    <a href="<?php echo site_url();?>admin" class="logo" style="display:none">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><b>AP</b></span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><b>TSMESS</b></span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>
		<span style="font-weight:15px;color: #FFF;font-size: 30px;font-weight: bold;margin: auto;margin-left: 50px;"> TSMESS </span>
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
                  <small>Member since June. 2017</small>
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
                  <a href="<?php echo site_url("admin/general/logout");?>" class="btn btn-default btn-flat">Sign out</a>
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
	 <?php
	 if ($this->session->userdata("is_loggedin") == TRUE && $this->session->userdata("user_id") != "" && $this->session->userdata("user_role") == "admin") {
		 ?>
                        <li class="">
                            <a href="<?php echo site_url();?>admin/school">
                                <i class="fa fa-school"></i> <span>Dashboard</span>
                            </a>
                        </li>
						<!--<li class="treeview">
											  <a href="#">
												<i class="glyphicon glyphicon-map-marker"></i>
												<span>Manage Areas</span>
												<span class="pull-right-container">
												  <i class="fa fa-angle-left pull-right"></i>
												</span>
											  </a>
											  <ul class="treeview-menu">
												
												 
												<li><a href="<?php echo site_url();?>cms/manage/areas" style="margin-left: 10px;"><i class="fa fa-circle-o text-aqua"></i> List Areas</a></li>
												 
											  </ul>
						</li>-->
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
												<li><a href="<?php echo site_url();?>cms/manage/schools_price" style="margin-left: 10px;"><i class="fa fa-circle-o text-aqua"></i> Schools Prices</a></li>
												 
											  </ul>
						</li>
						 <li class="treeview">
											  <a href="#">
												<i class="fa fa-balance-scale"></i>
												<span>Items</span>
												<span class="pull-right-container">
												  <i class="fa fa-angle-left pull-right"></i>
												</span>
											  </a>
											  <ul class="treeview-menu">
												
												<li><a href="<?php echo site_url();?>cms/manage/items/modals/add" style="margin-left: 10px;"><i class="fa fa-circle-o text-yellow"></i> Add New</a></li>
												<li><a href="<?php echo site_url();?>cms/manage/items" style="margin-left: 10px;"><i class="fa fa-circle-o text-aqua"></i>List Provisional items</a></li>
												 
											  </ul>
						</li> 
						 <li class="treeview">
											  <a href="#">
												<i class="fa fa-truck"></i>
												<span>vendors</span>
												<span class="pull-right-container">
												  <i class="fa fa-angle-left pull-right"></i>
												</span>
											  </a>
											  <ul class="treeview-menu"> 
												<li><a href="<?php echo site_url();?>cms/manage/vendors/modals/add" style="margin-left: 10px;"><i class="fa fa-circle-o text-yellow"></i> Add New</a></li>
												<li><a href="<?php echo site_url();?>cms/manage/vendors" style="margin-left: 10px;"><i class="fa fa-circle-o text-aqua"></i>List vendors</a></li>
												 
											  </ul>
						</li>
						 <li class="treeview">
											  <a href="#">
												<i class="fa fa-balance-scale"></i>
												<span>Active Quotations</span>
												<span class="pull-right-container">
												  <i class="fa fa-angle-left pull-right"></i>
												</span>
											  </a>
											  <ul class="treeview-menu">
												
												<li><a href="<?php echo site_url();?>cms/manage/quotations/modals/add" style="margin-left: 10px;"><i class="fa fa-circle-o text-yellow"></i> Add New</a></li>
												<li><a href="<?php echo site_url();?>cms/manage/quotations" style="margin-left: 10px;"><i class="fa fa-circle-o text-aqua"></i>List All</a></li>
												 
											  </ul>
						</li> 
						<li class="treeview">
											  <a href="#">
												<i class="fa  fa-inr"></i>
												<span>Quotations Prices</span>
												<span class="pull-right-container">
												  <i class="fa fa-angle-left pull-right"></i>
												</span>
											  </a>
											  <ul class="treeview-menu">
												
												<?php
												$quotations_list = $this->db->query("select * from   quotations where status='active'");
												foreach($quotations_list->result() as $quotation){?>
											
												<li><a href="<?php echo site_url();?>cms/manage/quotation_prices/<?php echo $quotation->quotation_id; ?>" style="margin-left: 10px;"><i class="fa fa-circle-o text-aqua"></i><?php echo $quotation->title;?></a></li>
												<?php } ?>	 
											  </ul>
						</li>
						
						
						
						<!-- <li class="treeview">
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
						
						-->
						 <li class="treeview">
											  <a href="#">
												<i class="fa fa-area-chart"></i>
												<span>Reports</span>
												<span class="pull-right-container">
												  <i class="fa fa-angle-left pull-right"></i>
												</span>
											  </a>
											  <ul class="treeview-menu"> 
												<li><a href="<?php echo site_url();?>admin/school/underconstruction" style="margin-left: 10px;"><i class="fa fa-circle-o text-aqua"></i>Report 1</a></li>
												<li><a href="<?php echo site_url();?>admin/school/underconstruction" style="margin-left: 10px;"><i class="fa fa-circle-o text-yellow"></i>Report 2</a></li>
												<li><a href="<?php echo site_url();?>admin/school/underconstruction" style="margin-left: 10px;"><i class="fa fa-circle-o text-aqua"></i>Report 3</a></li>
												<li><a href="<?php echo site_url();?>admin/school/underconstruction" style="margin-left: 10px;"><i class="fa fa-circle-o text-yellow"></i>Report 4</a></li>
												<li><a href="<?php echo site_url();?>admin/school/underconstruction" style="margin-left: 10px;"><i class="fa fa-circle-o text-aqua"></i>Report 5</a></li>
												<li><a href="<?php echo site_url();?>admin/school/underconstruction" style="margin-left: 10px;"><i class="fa fa-circle-o text-yellow"></i>Report 6</a></li>
												<li><a href="<?php echo site_url();?>admin/school/underconstruction" style="margin-left: 10px;"><i class="fa fa-circle-o text-aqua"></i>Report 7</a></li>
												<li><a href="<?php echo site_url();?>admin/school/underconstruction" style="margin-left: 10px;"><i class="fa fa-circle-o text-yellow"></i>Report 8</a></li>
												 
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
						  <?php } //admin menus section closed 
						  ?>
						<?php
	 if ($this->session->userdata("is_loggedin") == TRUE && $this->session->userdata("user_id") != "" && $this->session->userdata("user_role") == "school") {
		 ?>
		 
		 
		 <li  >
				  <a href="<?php echo site_url();?>admin/school/schoolreporttoday">
					<i class="fa fa-calendar-plus-o"></i>
					<span>Dashboard</span>
					 
				  </a>
											 
			</li>
		 <!--<li  >
				  <a href="<?php echo site_url();?>admin/school/schoolprice">
					<i class="fa fa-briefcase"></i>
					<span>School Price</span>
					 
				  </a>
											 
			</li>
		 -->
	 
			 <li  >
				  <a href="<?php echo site_url();?>admin/school/attendencelist">
					<i class="fa fa-users"></i>
					<span>Attendance List</span>
					 
				  </a>
											 
			</li>
			 <li  >
				  <a href="<?php echo site_url();?>admin/school/today_report">
					<i class="fa fa-briefcase"></i>
					<span>Item Prices</span>
					 
				  </a>
											 
			</li>
			
			 <li  >
				  <a href="<?php echo site_url();?>admin/school/today_report">
					<i class="fa fa-briefcase"></i>
					<span>Today Report</span>
					 
				  </a>
											 
			</li>
			
			
			<!-- <li  >
				  <a href="<?php echo site_url();?>admin/school/itemprices">
					<i class="fa fa-briefcase"></i>
					<span>Item Prices </span>
					 
				  </a>
											 
			</li>-->
			 <li  >
				  <a href="<?php echo site_url();?>admin/school/openingbalance">
					<i class="fa fa-briefcase"></i>
					<span>Opening Balance</span>
					 
				  </a>
											 
			</li>
			 <li  >
				  <a href="<?php echo site_url();?>admin/school/today_consumed_balancenew">
					<i class="fa fa-briefcase"></i>
					<span>Daily Consumed</span>
					 
				  </a>
											 
			</li>
			 <li  >
				  <a href="<?php echo site_url();?>admin/school/todaybalance">
					<i class="fa fa-briefcase"></i>
					<span>Monthly Consumed</span>
					 
				  </a>
											 
			</li>
				<li  >
				  <a href="<?php echo site_url();?>gallery/manage/purchasegallery">
					<i class="fa fa-inr"></i>
					<span>Purchase Bills</span>
					 
				  </a>
											 
			</li>
			<li  >
				  <a href="<?php echo site_url();?>admin/school/purchase_entry">
					<i class="fa fa-inr"></i>
					<span>Purchase Entry</span>
					 
				  </a>
											 
			</li>
						<li class="treeview">
											  <a href="#">
												<i class="fa  fa-hourglass-end"></i>
												<span>Consumption Entry</span>
												<span class="pull-right-container">
												  <i class="fa fa-angle-left pull-right"></i>
												</span>
											  </a>
											  <ul class="treeview-menu"> 
												<li><a href="<?php echo site_url();?>admin/school/consumption_entry/1" style="margin-left: 10px;"><i class="fa fa-circle-o text-aqua"></i>Break fast <span class='red'>[ 08 AM - 12 PM ]</span></a></li>
												<li><a href="<?php echo site_url();?>admin/school/consumption_entry/2" style="margin-left: 10px;"><i class="fa fa-circle-o text-yellow"></i>Lunch<span class='red'>[ 08 AM - 12 PM ]</span></a></li>
												<li><a href="<?php echo site_url();?>admin/school/consumption_entry/3" style="margin-left: 10px;"><i class="fa fa-circle-o text-aqua"></i>Snacks<span class='red'>[ 01 PM - 05 PM ]</span></a></li>
												<li><a href="<?php echo site_url();?>admin/school/consumption_entry/4" style="margin-left: 10px;"><i class="fa fa-circle-o text-aqua"></i>Supper<span class='red'>[ 01 PM - 05 PM ]</span></a></li>
												 
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
												<li><a href="<?php echo site_url();?>admin/school/reports" style="margin-left: 10px;"><i class="fa fa-circle-o text-aqua"></i>Itemwise Report</a></li>
												<li><a href="<?php echo site_url();?>admin/school/customreports" style="margin-left: 10px;"><i class="fa fa-circle-o text-aqua"></i>Consolidated report</a></li> 
											  </ul>
						</li>
						
		 
		 
	 <?php } ?>
	 
	 <?php
	 if ($this->session->userdata("is_loggedin") == TRUE && $this->session->userdata("user_id") != "" && $this->session->userdata("user_role") == "subadmin") {
		 ?>
	<!--	 <li  >
				  <a href="<?php echo site_url();?>admin/school/attendence">
					<i class="fa fa-institution"></i>
					<span>Schools</span>
					 
				  </a>
											 
			</li>
			<li  >
				  <a href="<?php echo site_url();?>admin/school/attendence">
					<i class="fa fa-calendar-plus-o"></i>
					<span>Items</span>
					 
				  </a>
											 
			</li>-->
			
			 <?php
	 if ($this->session->userdata("is_loggedin") == TRUE && $this->session->userdata("user_id")<10) {
		 ?>
			<li  >
				  <a href="<?php echo site_url();?>admin/subadmin/schoolreporttoday">
					<i class="fa fa-area-chart"></i>
					<span>Dashboard</span>
					 
				  </a>
											 
			</li>
			
			<li  >
				  <a href="<?php echo site_url();?>admin/subadmin/data_entry_bulk_selection">
					<i class="fa fa-area-chart"></i>
					<span>BULK Purchase/consumptions</span>
					 
				  </a>
											 
			</li>
			<li  >
				  <a href="<?php echo site_url();?>admin/subadmin/data_entry_school_selection">
					<i class="fa fa-area-chart"></i>
					<span>Purchase/consumption entries</span>
					 
				  </a>
											 
			</li>
			
			<li  >
				  <a href="<?php echo site_url();?>admin/subadmin/entriestoday">
					<i class="fa fa-area-chart"></i>
					<span>Missed entries</span>
					 
				  </a>
											 

			</li>
	
			<li class="treeview">
											  <a href="#">
												<i class="fa fa-area-chart"></i>
												<span>Item Prices</span>
												<span class="pull-down-container">
												  <i class="fa fa-angle-left pull-right"></i>
												</span>
											  </a>
											  <ul class="treeview-menu"> 
												<?php 
													$trb = $this->db->query("select * from districts");
													foreach($trb->result() as $mitem) { ?>
												
												
												<li><a href="<?php echo site_url();?>admin/subadmin/districts_prices/<?php echo $mitem->district_id;?>" style="margin-left: 10px;"><i class="fa fa-circle-o text-aqua"></i><?php echo $mitem->name;?></a></li>
													<?php } ?>
											  </ul>
						</li>
			<li  >
				  <a href="<?php echo site_url();?>admin/subadmin/itembalancesformdates">
					<i class="fa fa-area-chart"></i>
					<span>Item Balances By Date</span>
					 
				  </a>
											 

			</li>
			
			
			
			
				<li  >
				  <a href="<?php echo site_url();?>admin/subadmin/itembalancesform">
					<i class="fa fa-area-chart"></i>
					<span>Item Balances</span>
					 
				  </a>
											 

			</li>
			<!--	<li  >
				  <a href="<?php echo site_url();?>admin/subadmin/schoolbalancesform">
					<i class="fa fa-area-chart"></i>
					<span>School Balances</span>
					 
				  </a>
				-->							 

			</li>
			<?php } ?>
			<li  >
				  <a href="<?php echo site_url();?>admin/subadmin/schoolattendence">
					<i class="fa fa-area-chart"></i>
					<span>School Attendance </span>
					 
				  </a>
											 

			</li>
			
			
			 
			<li  >
				  <a href="<?php echo site_url();?>admin/subadmin/purchasebills">
					<i class="fa fa-area-chart"></i>
					<span>Purchase Bills</span>
					 
				  </a>
											 
			</li>
			 	
			<li  >
				  <a href="<?php echo site_url();?>admin/subadmin/today_consumed_balancenew">
					<i class="fa fa-area-chart"></i>
					<span>Daily Consumptions	</span>
					 
				  </a>
											 
			</li>
			 <?php
	 if ($this->session->userdata("is_loggedin") == TRUE && $this->session->userdata("user_id")<10) {
		 ?>
			<li  >
				  <a href="<?php echo site_url();?>admin/subadmin/reports/district">
					<i class="fa fa-area-chart"></i>
					<span>District Reports</span>
					 
				  </a>
	 <?php } ?>					 
			</li>
			<li  >
				  <a href="<?php echo site_url();?>admin/subadmin/reports/school">
					<i class="fa fa-area-chart"></i>
					<span>School Reports</span>
					 
				  </a>
											 
			</li> 
			<li  >
				  <a href="<?php echo site_url();?>admin/subadmin/attendencereports_months">
					<i class="fa fa-area-chart"></i>
					<span>Atten & Consum Reports  </span>
					 
				  </a>
											 
			</li>
			<?php 
			 if(site_url()=="http://TSMESS.in.net/"){
			?>
			<li  >
				  <a href="<?php echo site_url();?>admin/subadmin/attendencereports">
					<i class="fa fa-area-chart"></i>
					<span>Atten & Consum Reports Old </span>
					 
				  </a>
											 
			</li>
			 <?php  } ?>
			
	 <?php } ?>
                           <li class="">
                            <a href="<?php echo site_url();?>admin/general/changepassword" >
                                <i class="fa fa-lock "></i> 
                              <span>Change Password</span>
                            </a>
                        </li> 
						   <li class="">
                            <a href="<?php echo site_url();?>admin/general/logout">
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
<script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
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
</body>
</html>
