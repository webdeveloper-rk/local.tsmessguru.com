 <?php
	 if ($this->session->userdata("is_loggedin") == TRUE && $this->session->userdata("user_id") != "" && $this->session->userdata("user_role") == "subadmin") {
		 ?>
	  
			
		
			<li  >
				  <a href="<?php echo site_url();?>school_day_report">
					<i class="fa fa-area-chart"></i>
					<span>Dashboard</span>
					 
				  </a>
											 
			</li>
			<li  >
				  <a href="<?php echo site_url();?>manage/documents">
					<i class="fa fa-area-chart"></i>
					<span>Documents</span>
					 
				  </a>
											 
			</li>
			
				 <?php
				 $subadmin_roles = array('10100');
	 if ($this->session->userdata("is_loggedin") == TRUE && in_array($this->session->userdata("school_code"), $subadmin_roles)) {
		 ?>
		 
		 <li class="treeview">
														  <a href="#">
															<i class="fa fa-laptop"></i>
															<span>Update</span>
															<span class="pull-right-container">
															  <i class="fa  pull-right">></i>
															</span>
														  </a>
								  <ul class="treeview-menu" style="display: none;">
									 <li class="treeview" style="padding-left:10px;">
														  <a href="#">
															<i class="fa fa-laptop"></i>
															<span>Purchase/Consumption</span>
															<span class="pull-right-container">
															  <i class="fa fa-angle-leftf pull-right">></i>
															</span>
														  </a>
								  <ul class="treeview-menu" style="display: none;">
									 
									<li><a href="<?php echo site_url();?>purchase_consumption"><i class="fa fa-circle-o"></i> Day Entries</a></li>
									
									<li><a href="<?php echo site_url();?>item_balances"><i class="fa fa-circle-o"></i> Item Balances</a></li>
									 
								  </ul>
        </li>
		 
			
									<li  class='cleft10px'>
										  <a href="<?php echo site_url("attendence/admin");?>">
											<i class="fa fa-area-chart"></i>
											<span>School Attendance</span>
											 
										  </a> 
									</li>
									
									<li  class='cleft10px'>
										  <a href="<?php echo site_url();?>manage/items">
											<i class="fa fa-area-chart"></i>
											<span>Manage Items</span>
											 
										  </a> 
									</li>
									<li  class='cleft10px'>
										  <a href="<?php echo site_url();?>manage/fixed_rates">
											<i class="fa fa-area-chart"></i>
											<span>Fuel Charges</span>
											 
										  </a>
																	 

									</li>
									<li  class='cleft10px'>
										  <a href="<?php echo site_url();?>general/admin/resetpassword">
											<i class="fa fa-area-chart"></i>
											<span>Reset school/CT passwords</span>
											 
										  </a>
																	 

									</li>
									<li  class='cleft10px'>
										  <a href="<?php echo site_url();?>general/admin/diet_resetpassword">
											<i class="fa fa-area-chart"></i>
											<span>Reset DIET APP password</span>
											 
										  </a>
																	 

									</li>
									 
								  </ul>
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
									  <a href="<?php echo site_url();?>special_entries">
											<i class="fa fa-circle-o"></i>
										<span>Specials Approvals</span> 
									  </a> 
									</li>
										<li  >
																			<a href="<?php echo site_url();?>authorisations_entries">
																			<i class="fa fa-circle-o"></i>
																			<span>Caretaker Authorisations</span>

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
																	
																		<li  >
																			<a href="<?php echo site_url();?>consumption_savings">
																			<i class="fa fa-circle-o"></i>
																			<span>Consumption Savings</span>

																			</a>

																	</li>
																	
																	
																	<li  >
																			<a href="<?php echo site_url();?>report_day_consumed">
																			<i class="fa fa-circle-o"></i>
																			<span>School Day Consumed</span>

																			</a>

																	</li>
																	
																	
																	
																	
																	<li  >
																			<a href="<?php echo site_url();?>report_monthly_consumed">
																			<i class="fa fa-circle-o"></i>
																			<span>School Monthly Consumed</span>

																			</a>

																	</li>
																	
																	
																	
		 
																	
																	
																	<li  >
																			<a href="<?php echo site_url();?>school_item_balances">
																			<i class="fa fa-circle-o"></i>
																			<span>School Itembalance Reports</span>

																			</a>

																	</li>
																	
																	<li  >
																			<a href="<?php echo site_url();?>spenders/amountwise">
																			<i class="fa fa-circle-o"></i>
																			<span>Spenders::Amountwise Reports</span>

																			</a>

																	</li>
																	
																	<li  >
																			<a href="<?php echo site_url();?>spenders/itemwise">
																			<i class="fa fa-circle-o"></i>
																			<span>Spenders::Itemwise Reports</span>

																			</a>

																	</li>
																	<li  >
																			<a href="<?php echo site_url();?>missed_entries">
																			<i class="fa fa-circle-o"></i>
																			<span>Missed Schools Reports</span>

																			</a>

																	</li>
																	<li  >
																			<a href="<?php echo site_url();?>missed_entries/itemwise">
																			<i class="fa fa-circle-o"></i>
																			<span>Menu Missed Schools</span>

																			</a>

																	</li>
																	
																	<li  >
																			<a href="<?php echo site_url();?>missed_entries/usedschools">
																			<i class="fa fa-circle-o"></i>
																			<span>Menu Track</span>

																			</a>

																	</li>
																	
																	 
									 
								  </ul>
        </li>
			 
			 
			
			
		 
	
	 
	
		 
		 
			
			 					 

			</li>
			<?php } ?>
			
	 
		
			
			 
			
	 <?php }  
 
	 ?>