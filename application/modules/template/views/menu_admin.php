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
												  <i class="fa fa-angle-leftcc pull-right">></i>
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
												  <i class="fa fa-angle-leftcc pull-right">></i>
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
												  <i class="fa fa-angle-leftcc pull-right">></i>
												</span>
											  </a>
											  <ul class="treeview-menu">
												<li><a href="<?php echo site_url();?>cms/manage/schoolattendence" style="margin-left: 10px;"><i class="fa fa-circle-o text-aqua"></i>Attendance</a></li>
												<li><a href="<?php echo site_url();?>cms/manage/updateattendence" style="margin-left: 10px;"><i class="fa fa-circle-o text-yellow"></i>Update Attendance</a></li>
												
												<li><a href="<?php echo site_url();?>cms/manage/updateguestattendence" style="margin-left: 10px;"><i class="fa fa-circle-o text-aqua"></i>Update Guest Attendance  </a></li>
												<li><a href="<?php echo site_url();?>cms/manage/updatefuelcharges" style="margin-left: 10px;"><i class="fa fa-circle-o text-aqua"></i>Update Fuel Charges</a></li>
												
												 
											  </ul>
						</li>
						
						  <?php } //admin menus section closed 
						  ?>