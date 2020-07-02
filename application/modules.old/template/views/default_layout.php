<?php 
$data = $this->session->all_userdata();
//echo "<pre>";print_r($data);echo "</pre>";
?>
<!DOCTYPE html>
<html>
	<head>

		<!-- Basic -->
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">	

		<title>BPOSERVICELTD.COM</title>	

		<meta name="keywords" content="BPOSERVICELTD" />
		<meta name="description" content="BPOSERVICELTD">
		<meta name="author" content="BPOSERVICELTD">

		<!-- Favicon -->
		<link rel="shortcut icon" href="img/favicon.ico" type="image/x-icon" />
		<link rel="apple-touch-icon" href="img/apple-touch-icon.png">

		<!-- Mobile Metas -->
		<meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">

		<!-- Web Fonts  -->
		<link href="https://fonts.googleapis.com/<?php echo site_url();?>css?family=Open+Sans:300,400,600,700,800%7CShadows+Into+Light" rel="stylesheet" type="text/<?php echo site_url();?>css">

		<!-- <?php echo site_url();?>vendor <?php echo site_url();?>css -->
		<link rel="stylesheet" href="<?php echo site_url();?>vendor/bootstrap/css/bootstrap.min.css">
		<link rel="stylesheet" href="<?php echo site_url();?>vendor/font-awesome/css/font-awesome.min.css">
		<link rel="stylesheet" href="<?php echo site_url();?>vendor/simple-line-icons/css/simple-line-icons.min.css">
		<link rel="stylesheet" href="<?php echo site_url();?>vendor/owl.carousel/assets/owl.carousel.min.css">
		<link rel="stylesheet" href="<?php echo site_url();?>vendor/owl.carousel/assets/owl.theme.default.min.css">
		<link rel="stylesheet" href="<?php echo site_url();?>vendor/magnific-popup/magnific-popup.min.css">

		<!-- Theme <?php echo site_url();?>css -->
		<link rel="stylesheet" href="<?php echo site_url();?>css/theme.css">
		<link rel="stylesheet" href="<?php echo site_url();?>css/theme-elements.css">
		<link rel="stylesheet" href="<?php echo site_url();?>css/theme-blog.css">
		<link rel="stylesheet" href="<?php echo site_url();?>css/theme-shop.css">
		<link rel="stylesheet" href="<?php echo site_url();?>css/theme-animate.css">

		<!-- Skin <?php echo site_url();?>css -->
		<link rel="stylesheet" href="<?php echo site_url();?>css/skins/default.css">

		<!-- Theme Custom <?php echo site_url();?>css -->
		<link rel="stylesheet" href="<?php echo site_url();?>css/custom.css">

		<!-- Head Libs -->
		<script src="<?php echo site_url();?>vendor/modernizr/modernizr.min.js"></script>

	</head>
	<body>

		<div class="body">
			<header id="header" data-plugin-options='{"stickyEnabled": true, "stickyEnableOnBoxed": true, "stickyEnableOnMobile": true, "stickyStartAt": 57, "stickySetTop": "-57px", "stickyChangeLogo": true}'>
				<div class="header-body">
					<div class="header-container container">
						<div class="header-row">
							<div class="header-column">
								<div class="header-logo">
									<a href="<?php echo site_url();?>">
										<img alt="BPO SERVICE LTD" width="256" height="71" data-sticky-width="82" data-sticky-height="40" data-sticky-top="33" src="<?php echo site_url();?>img/logo.png">									</a>								</div>
						  </div>
							<div class="header-column">
								
								<div class="header-row">
									<div class="header-nav">
										<button class="btn header-btn-collapse-nav" data-toggle="collapse" data-target=".header-nav-main">
											<i class="fa fa-bars"></i>
										</button>
										
										<div class="header-nav-main header-nav-main-effect-1 header-nav-main-sub-effect-1 collapse">
											<nav>
												<ul class="nav nav-pills" id="mainNav">
													<li class="active"><a  href="<?php echo site_url('wallet');?>">Wallet Balance</a></li>                        
													<li class="dropdown"><a  href="<?php echo site_url('transactions');?>">Transactions</a></li>
													<li class="dropdown"><a  href="<?php echo site_url('settings');?>">Settings</a></li>
													<li class="dropdown"><a  href="<?php echo site_url('payments');?>">Payment Request</a></li>
													<li class="dropdown"><a  href="<?php echo site_url('home/logout');?>">Logout</a></li>
												</ul>
											</nav>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</header>

			<div role="main" class="main">

				<section class="page-header">
					<div class="container">						
						<div class="row">
							
							<div class="col-md-12"> 
								<h1 style="font-size:24px;">Welcome To <?php echo $this->session->userdata('fullname');?></h1> 
							</div>                                                       	
						</div> </div>
                        </section>
                        
                        	
                
                
				
				

				

				<div class="container">
						<?php echo $view_name;?>
					 <div><?php $this->load->view($view_name);?> </div>

				</div>

			</div>

			

			<footer id="footer">
				
				<div class="footer-copyright" style="height:auto;">
					<div class="container">
						<div class="row">
							<div class="col-md-1">
								<a href="index.html" class="logo">
									<img alt="Porto Website Template" class="img-responsive" src="<?php echo site_url();?>img/logo.png">
								</a>
							</div>
							<div class="col-md-7">
								<p>© Copyright 2016. All Rights Reserved.</p>
							</div>
							<div class="col-md-4">
								<nav id="sub-menu">
									<ul>
										<li><a href="#">Type & Earn</a></li>
										<li><a href="#">My Wallet</a></li>
										<li><a href="#">Contact</a></li>
									</ul>
								</nav>
							</div>
						</div>
					</div>
				</div>
			</footer>
		</div>

		<!-- <?php echo site_url();?>vendor -->
		<script src="<?php echo site_url();?>vendor/jquery/jquery.min.js"></script>
		<script src="<?php echo site_url();?>vendor/jquery.appear/jquery.appear.min.js"></script>
		<script src="<?php echo site_url();?>vendor/jquery.easing/jquery.easing.min.js"></script>
		<script src="<?php echo site_url();?>vendor/jquery-cookie/jquery-cookie.min.js"></script>
		<script src="<?php echo site_url();?>vendor/bootstrap/js/bootstrap.min.js"></script>
		<script src="<?php echo site_url();?>vendor/common/common.min.js"></script>
		<script src="<?php echo site_url();?>vendor/jquery.validation/jquery.validation.min.js"></script>
		<script src="<?php echo site_url();?>vendor/jquery.stellar/jquery.stellar.min.js"></script>
		<script src="<?php echo site_url();?>vendor/jquery.easy-pie-chart/jquery.easy-pie-chart.min.js"></script>
		<script src="<?php echo site_url();?>vendor/jquery.gmap/jquery.gmap.min.js"></script>
		<script src="<?php echo site_url();?>vendor/jquery.lazyload/jquery.lazyload.min.js"></script>
		<script src="<?php echo site_url();?>vendor/isotope/jquery.isotope.min.js"></script>
		<script src="<?php echo site_url();?>vendor/owl.carousel/owl.carousel.min.js"></script>
		<script src="<?php echo site_url();?>vendor/magnific-popup/jquery.magnific-popup.min.js"></script>
		<script src="<?php echo site_url();?>vendor/vide/vide.min.js"></script>
		
		<!-- Theme Base, Components and Settings -->
		<script src="<?php echo site_url();?>js/theme.js"></script>

		<!-- Current Page <?php echo site_url();?>vendor and Views -->
		<script src="<?php echo site_url();?>js/views/view.contact.js"></script>
		
		<!-- Theme Custom -->
		<script src="<?php echo site_url();?>js/custom.js"></script>
		
		<!-- Theme Initialization Files -->
		<script src="<?php echo site_url();?>js/theme.init.js"></script>

		 

		 
	</body>
</html>
