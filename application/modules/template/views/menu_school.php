<?php
	 if ($this->session->userdata("is_loggedin") == TRUE && $this->session->userdata("user_id") != "" && $this->session->userdata("user_role") == "school" &&   $this->session->userdata("operator_type") != "CT") {
		 ?>
		 
		 
			 <li  >
				  <a href="<?php echo site_url();?>opening_balance">
					<i class="fa fa-briefcase"></i>
					<span>Opening Balance</span>
					 
				  </a>
											 
			</li>
			
			<li  >
				  <a href="<?php echo site_url();?>school_day_report">
					<i class="fa fa-calendar-plus-o"></i>
					<span>Dashboard</span>
					 
				  </a>
											 
			</li>
	 
			 <li  >
				  <a href="<?php echo site_url();?>attendence">
					<i class="fa fa-users"></i>
					<span>Attendance</span>
					 
				  </a>
											 
			</li>
			
			 <li class="treeview">
														  <a href="#">
															<i class="fa fa-laptop"></i>
															<span>Specials </span>
															<span class="pull-right-container">
															  <i class="fa fa-angle-leftcc pull-right">></i>
															</span>
														  </a>
								  <ul class="treeview-menu" style="display: none;">
									<li  >
												<a href="<?php echo site_url();?>specials/today_approvals">
											<i class="fa fa-circle-o text-aqua"></i><span>Tommorow Specials</span></a>
									</li>
									<li  >
												<a href="<?php echo site_url();?>specials/approvals_list">
											<i class="fa fa-circle-o text-aqua"></i><span>Specials List</span></a>
									</li>
									
								  </ul>
        </li>
	 
			
			 
		  
			 
			<li class="treeview">
														  <a href="#">
															<i class="fa fa-laptop"></i>
															<span>STOCK RECORD </span>
															<span class="pull-right-container">
															  <i class="fa fa-angle-leftcc pull-right">></i>
															</span>
														  </a>
								  <ul class="treeview-menu" style="display: none;">
									<li  >
												<a href="<?php echo site_url();?>purchase_entry">
											<i class="fa fa-inr"></i><span>Purchase Entry</span></a>
									</li>
									<li class="treeview">
											  <a href="#">
												<i class="fa  fa-hourglass-end"></i>
												<span>Consumption Entry</span>
												 
											  </a>
											  <ul class="treeview"> 
												<li style="padding:6px"><a href="<?php echo site_url();?>consumption_entrynew/view/1"><i   class="fa fa-circle-o text-aqua"></i>&nbsp;&nbsp;&nbsp; Break fast <span class='red'>[ 08 AM - 12 PM ]</span></a></li>
												<li style="padding:6px"><a href="<?php echo site_url();?>consumption_entrynew/view/2" ><i   class="fa fa-circle-o text-aqua"></i>&nbsp;&nbsp;&nbsp;Lunch<span class='red'>[ 08 AM - 12 PM ]</span></a></li>
												<li style="padding:6px"> <a href="<?php echo site_url();?>consumption_entrynew/view/3"  ><i   class="fa fa-circle-o text-aqua"></i>&nbsp;&nbsp;&nbsp;Snacks<span class='red'>[ 01 PM - 04:30 PM ]</span></a></li>
												<li style="padding:6px"><a href="<?php echo site_url();?>consumption_entrynew/view/4"  ><i     class="fa fa-circle-o text-aqua"></i>&nbsp;&nbsp;&nbsp;Supper<span class='red'>[ 01 PM - 04:30 PM ]</span></a></li>
												 
											  </ul>
						</li>
									
								  </ul>
        </li>
			
			<li class="treeview">
														  <a href="#">
															<i class="fa fa-laptop"></i>
															<span>UPLOAD</span>
															<span class="pull-right-container">
															  <i class="fa fa-angle-leftcc pull-right">></i>
															</span>
														  </a>
								  <ul class="treeview-menu" style="display: none;">
								
			 <li  >
				  <a href="<?php echo site_url();?>purchase_bills">
					<i class="fa fa-inr"></i>
					<span>Purchase Bills  </span>
					 
				  </a>
											 
			</li>
			
				  <li  >
				  <a href="<?php echo site_url();?>purchase_bills/oldbills">
					<i class="fa fa-inr"></i>
					<span>Purchase Bills Old</span>
					 
				  </a>
											 
			</li>
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
															<a href="<?php echo site_url();?>school_day_report">
																<i class="fa fa-briefcase"></i>
																<span>Daywise Consumed Items</span>

															</a>

														</li>
														<li  >
															<a href="<?php echo site_url();?>report_day_consumed">
																<i class="fa fa-briefcase"></i>
																<span>Daily Consumed</span>

															</a>

														</li>
														<li  >
														<a href="<?php echo site_url();?>report_monthly_consumed">
														<i class="fa fa-briefcase"></i>
														<span>Monthly Consumed</span>

														</a>

														</li>	
														<li><a href="<?php echo site_url();?>report_itemwise"  ><i class="fa fa-briefcase"></i>Itemwise Report</a></li>
												<li><a href="<?php echo site_url();?>report_today_consumed_items"  ><i class="fa fa-briefcase"></i>Today Consolidated report</a></li> 
												<li><a href="<?php echo site_url();?>consolidated_between_dates"  ><i class="fa fa-briefcase"></i>Consolidated report</a></li> 
												<li><a href="<?php echo site_url();?>report_purchase_entries"  ><i class="fa fa-briefcase"></i>Purchase report</a></li> 
											 
								  </ul>
        </li>
			
			
				
			
		 
						
						 
						
		 
		 
	 <?php } ?>
	 