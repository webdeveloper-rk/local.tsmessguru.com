 <?php
	 if ($this->session->userdata("is_loggedin") == TRUE && $this->session->userdata("user_id") != "" && $this->session->userdata("user_role") == "admin") {
		 ?>
                        <li class="">
                            <a href="<?php echo site_url();?>admin/school">
                                <i class="fa fa-school"></i> <span>Dashboard</span>
                            </a>
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
												<li><a href="<?php echo site_url();?>cms/manage/resetpassword" style="margin-left: 10px;"><i class="fa fa-circle-o text-aqua"></i> Reset Password</a></li>
												
												 
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
												<li><a href="<?php echo site_url();?>cms/manage/items" style="margin-left: 10px;"><i class="fa fa-circle-o text-aqua"></i>List items</a></li>
												 
											  </ul>
						</li> 
						 
					 		 
                      <li class="treeview">
											  <a href="#">
												<i class="fa fa-institution"></i>
												<span>Update Attendance</span>
												<span class="pull-right-container">
												  <i class="fa fa-angle-left pull-right"></i>
												</span>
											  </a>
											  <ul class="treeview-menu">
												<li><a href="<?php echo site_url();?>cms/manage/schoolattendence" style="margin-left: 10px;"><i class="fa fa-circle-o text-aqua"></i>Attendance</a></li>
												<li><a href="<?php echo site_url();?>cms/manage/updateattendence" style="margin-left: 10px;"><i class="fa fa-circle-o text-yellow"></i>Update Attendance</a></li>
												
												<li><a href="<?php echo site_url();?>cms/manage/updateguestattendence" style="margin-left: 10px;"><i class="fa fa-circle-o text-aqua"></i>Update Guest Attendance  </a></li>
												<li><a href="<?php echo site_url();?>cms/manage/updatefuelcharges" style="margin-left: 10px;"><i class="fa fa-circle-o text-aqua"></i>Update Fuel Charges</a></li>
												
												 
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
						 
						
                      
                      <!--
						 
						
							
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
	 if ($this->session->userdata("is_loggedin") == TRUE && $this->session->userdata("user_id") != "" && $this->session->userdata("user_role") == "school" &&   $this->session->userdata("operator_type") != "CT") {
		 ?>
		 
		 
		 <li  >
				  <a href="<?php echo site_url();?>admin/school/schoolreporttoday">
					<i class="fa fa-calendar-plus-o"></i>
					<span>Dashboard</span>
					 
				  </a>
											 
			</li> 
	 
			 <li  >
				  <a href="<?php echo site_url();?>admin/school/attendencelist">
					<i class="fa fa-users"></i>
					<span>Attendance</span>
					 
				  </a>
											 
			</li>
	 
			
			<!-- <li  >
				  <a href="<?php echo site_url();?>admin/school/today_report">
					<i class="fa fa-briefcase"></i>
					<span>Today Report</span>
					 
				  </a>
											 
			</li>
			-->
			
			<!-- <li  >
				  <a href="<?php echo site_url();?>admin/school/itemprices">
					<i class="fa fa-briefcase"></i>
					<span>Item Prices </span>
					 
				  </a>
											 
			</li>-->
			<?php 
			//echo $this->session->userdata("school_code");die;
			if(in_array($this->session->userdata("school_code"),$new_schools)) { 
			
			?>
			 <li  >
				  <a href="<?php echo site_url();?>admin/school/openingbalance">
					<i class="fa fa-briefcase"></i>
					<span>Opening Balance</span>
					 
				  </a>
											 
			</li>
			<?php } ?>
			<li class="treeview">
														  <a href="#">
															<i class="fa fa-laptop"></i>
															<span>STOCK RECORD </span>
															<span class="pull-right-container">
															  <i class="fa fa-angle-left pull-right"></i>
															</span>
														  </a>
								  <ul class="treeview-menu" style="display: none;">
									<li  >
												<a href="<?php echo site_url();?>admin/school/purchase_entry">
											<i class="fa fa-inr"></i><span>Purchase Entry</span></a>
									</li>
									<li class="treeview">
											  <a href="#">
												<i class="fa  fa-hourglass-end"></i>
												<span>Consumption Entry</span>
												 
											  </a>
											  <ul class="treeview"> 
												<li style="padding:6px"><a href="<?php echo site_url();?>admin/school/consumption_entry/1"><i   class="fa fa-circle-o text-aqua"></i>&nbsp;&nbsp;&nbsp; Break fast <span class='red'>[ 08 AM - 12 PM ]</span></a></li>
												<li style="padding:6px"><a href="<?php echo site_url();?>admin/school/consumption_entry/2" ><i   class="fa fa-circle-o text-aqua"></i>&nbsp;&nbsp;&nbsp;Lunch<span class='red'>[ 08 AM - 12 PM ]</span></a></li>
												<li style="padding:6px"> <a href="<?php echo site_url();?>admin/school/consumption_entry/3"  ><i   class="fa fa-circle-o text-aqua"></i>&nbsp;&nbsp;&nbsp;Snacks<span class='red'>[ 01 PM - 04:30 PM ]</span></a></li>
												<li style="padding:6px"><a href="<?php echo site_url();?>admin/school/consumption_entry/4"  ><i     class="fa fa-circle-o text-aqua"></i>&nbsp;&nbsp;&nbsp;Supper<span class='red'>[ 01 PM - 04:30 PM ]</span></a></li>
												 
											  </ul>
						</li>
									
								  </ul>
        </li>
			
			<li class="treeview">
														  <a href="#">
															<i class="fa fa-laptop"></i>
															<span>UPLOAD</span>
															<span class="pull-right-container">
															  <i class="fa fa-angle-left pull-right"></i>
															</span>
														  </a>
								  <ul class="treeview-menu" style="display: none;">
									  <li  >
				  <a href="<?php echo site_url();?>gallery/manage/purchasegallery">
					<i class="fa fa-inr"></i>
					<span>Purchase Bills</span>
					 
				  </a>
											 
			</li>
								  </ul>
        </li>
		<li class="treeview">
														  <a href="#">
															<i class="fa fa-laptop"></i>
															<span>REPORTS</span>
															<span class="pull-right-container">
															  <i class="fa fa-angle-left pull-right"></i>
															</span>
														  </a>
								  <ul class="treeview-menu" style="display: none;">
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
														<li><a href="<?php echo site_url();?>admin/school/reports"  ><i class="fa fa-briefcase"></i>Itemwise Report</a></li>
												<li><a href="<?php echo site_url();?>admin/school/customreports"  ><i class="fa fa-briefcase"></i>Consolidated report</a></li> 
											 
								  </ul>
        </li>
			
			
				
			
		 
						
						 
						
		 
		 
	 <?php } ?>
	 
	
		 

		 
		 
	    <?php
	 if ($this->session->userdata("is_loggedin") == TRUE && $this->session->userdata("user_id") != "" && $this->session->userdata("user_role") == "school" && $this->session->userdata("operator_type") =="CT")
	 {
	 ?>
	 <li class="treeview  ">
														  <a href="#">
															<i class="fa  fa-bookmark"></i>
															<span>Authorizations</span>
															<span class="pull-right-container">
															  <i class="fa fa-angle-left pull-right"></i>
															</span>
														  </a>
	  <ul class="treeview-menu"> 
												<li  ><a href="<?php echo site_url();?>admin/school/consumption_entry/1"><i   class="fa fa-circle-o text-aqua"></i>&nbsp;&nbsp;&nbsp; Break fast <span class='red'>[12:00 PM - 12:30 PM ]</span></a></li>
												<li  ><a href="<?php echo site_url();?>admin/school/consumption_entry/2" ><i   class="fa fa-circle-o text-aqua"></i>&nbsp;&nbsp;&nbsp;Lunch<span class='red'>[12:00 PM - 12:30 PM ]</span></a></li>
												<li > <a href="<?php echo site_url();?>admin/school/consumption_entry/3"  ><i   class="fa fa-circle-o text-aqua"></i>&nbsp;&nbsp;&nbsp;Snacks<span class='red'>[04:30 PM - 05:00 PM ]</span></a></li>
												<li ><a href="<?php echo site_url();?>admin/school/consumption_entry/4"  ><i     class="fa fa-circle-o text-aqua"></i>&nbsp;&nbsp;&nbsp;Supper<span class='red'>[04:30 PM - 05:00 PM ]</span></a></li>
												 
											  </ul>
											  </li>
	 <?php 
	 
	 }
		 ?>
	  
	 
	 <?php
	 if ($this->session->userdata("is_loggedin") == TRUE && $this->session->userdata("user_id") != "" && $this->session->userdata("user_role") == "subadmin") {
		 ?>
	  
			
		
			<li  >
				  <a href="<?php echo site_url();?>admin/subadmin/schoolreporttoday">
					<i class="fa fa-area-chart"></i>
					<span>Dashboard</span>
					 
				  </a>
											 
			</li>
			<li  >
				  <a href="<?php echo site_url();?>admin/subadmin/schoolattendence">
					<i class="fa fa-area-chart"></i>
					<span>School Attendance </span>
					 
				  </a>
											 

			</li>
				 <?php
	 if ($this->session->userdata("is_loggedin") == TRUE && $this->session->userdata("user_id")==2) {
		 ?>
			<li class="treeview">
														  <a href="#">
															<i class="fa fa-laptop"></i>
															<span>Purchase/Consumption</span>
															<span class="pull-right-container">
															  <i class="fa fa-angle-left pull-right"></i>
															</span>
														  </a>
								  <ul class="treeview-menu" style="display: none;">
									<li><a href="<?php echo site_url();?>admin/subadmin/data_entry_bulk_selection"><i class="fa fa-circle-o"></i> Monthly</a></li>
									<li><a href="<?php echo site_url();?>admin/subadmin/data_entry_school_selection"><i class="fa fa-circle-o"></i> Day Entries</a></li>
									<li><a href="<?php echo site_url();?>admin/subadmin/entriestoday"><i class="fa fa-circle-o"></i> Missed School entries</a></li>
									<li><a href="<?php echo site_url();?>admin/subadmin/itembalancesform"><i class="fa fa-circle-o"></i> Item Balances</a></li>
									 
								  </ul>
        </li>
			<li  >
				  <a href="<?php echo site_url();?>admin/general/resetpassword">
					<i class="fa fa-area-chart"></i>
					<span>Reset school password</span>
					 
				  </a>
											 

			</li>
				<li  >
				  <a href="<?php echo site_url();?>admin/subadmin/mitems">
					<i class="fa fa-area-chart"></i>
					<span>Manage Items</span>
					 
				  </a>
											 

			</li>
			 <li class="treeview">
														  <a href="#">
															<i class="fa fa-laptop"></i>
															<span>UPLOADS</span>
															<span class="pull-right-container">
															  <i class="fa fa-angle-left pull-right"></i>
															</span>
														  </a>
								  <ul class="treeview-menu" style="display: none;">
									<li><a href="<?php echo site_url();?>admin/subadmin/purchasebills"><i class="fa fa-circle-o"></i> Purchase Bills</a></li>
									<li><a href="<?php echo site_url();?>admin/subadmin/sessionpics"><i class="fa fa-circle-o"></i> DIET PICS</a></li>
									 
								  </ul>
        </li>
			 
			
			 <li class="treeview">
														  <a href="#">
															<i class="fa fa-laptop"></i>
															<span>REPORTS</span>
															<span class="pull-right-container">
															  <i class="fa fa-angle-left pull-right"></i>
															</span>
														  </a>
								  <ul class="treeview-menu" style="display: none;">
									<li><a href="<?php echo site_url();?>admin/subadmin/today_consumed_balancenew"><i class="fa fa-circle-o"></i> Daily Consumptions</a></li>
																								 <?php
															 if ($this->session->userdata("is_loggedin") == TRUE && $this->session->userdata("user_id")==2) {
																 ?><li  >
																	
																		  <a href="<?php echo site_url();?>admin/subadmin/reports/district">
																			<i class="fa fa-circle-o"></i>
																			<span>District Reports</span>
																			 
																		  </a>	</li>
															 <?php } ?>		
															 
																	<li  >
																				<a href="<?php echo site_url();?>admin/subadmin/reports/school">
																				<i class="fa fa-circle-o"></i>
																				<span>School Reports</span>

																				</a>

																	</li> 
																	<li  >
																			<a href="<?php echo site_url();?>admin/subadmin/attendencereports_months">
																			<i class="fa fa-circle-o"></i>
																			<span>DIET Expenditure   </span>

																			</a>

																	</li>
																	<li  >
																			<a href="<?php echo site_url();?>admin/subadmin/authorisations_entries">
																			<i class="fa fa-circle-o"></i>
																			<span>Caretaker Authorisations</span>

																			</a>

																	</li>
									 
								  </ul>
        </li>
			 
			 
			
			
		 
	
	 
	
		 
		 
			
			 					 

			</li>
			<?php } ?>
			
	 
		
			
			 
			
	 <?php }  
 
	 ?>
	 	 <?php
		if ($this->session->userdata("is_loggedin") == TRUE && $this->session->userdata("is_dco")==true  || ($this->session->userdata("user_id") > 2  && $this->session->userdata("user_role") == "subadmin")) {
																 ?>
																 <li class="treeview">
														  <a href="#">
															<i class="fa fa-laptop"></i>
															<span>REPORTS</span>
															<span class="pull-right-container">
															  <i class="fa fa-angle-left pull-right"></i>
															</span>
														  </a>
								  <ul class="treeview-menu" style="display: none;">
									
									<li  >
																				<a href="<?php echo site_url();?>admin/subadmin/reports/school">
																				<i class="fa fa-circle-o"></i>
																				<span>School Reports</span>

																				</a>
									</li>
									
									
									<li><a href="<?php echo site_url();?>admin/subadmin/today_consumed_balancenew">
													<i class="fa fa-circle-o"></i> Daily Consumptions</a>
									</li>
									<li  >
											<a href="<?php echo site_url();?>admin/subadmin/attendencereports_months">
											<i class="fa fa-circle-o"></i>
											<span>DIET Expenditure   </span>

											</a>

									</li>
	
											<li><a href="<?php echo site_url();?>admin/subadmin/purchasebills"><i class="fa fa-circle-o"></i> Purchase Bills</a></li>
											<!--<li><a href="<?php echo site_url();?>admin/subadmin/sessionpics"><i class="fa fa-circle-o"></i> DIET PICS</a></li>-->
											<li><a href="<?php echo site_url();?>admin/subadmin/itembalancesform"><i class="fa fa-circle-o"></i> Item Balances</a></li>
											

																	</li> 
																	<!--<li  >
																			<a href="<?php echo site_url();?>admin/subadmin/authorisations_entries">
																			<i class="fa fa-circle-o"></i>
																			<span>Caretaker Authorisations</span>

																			</a>

																	</li>
																	-->
									 
								  </ul>
        </li>
			 
			
															 <?php } ?>