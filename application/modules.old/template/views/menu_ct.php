 
		 
	    <?php
	 if ($this->session->userdata("is_loggedin") == TRUE && $this->session->userdata("user_id") != "" && $this->session->userdata("user_role") == "school" && $this->session->userdata("operator_type") =="CT")
	 {
	 ?>
<li  >
												<a href="<?php echo site_url();?>purchase_entry">
											<i class="fa fa-inr"></i><span>Purchase Entry</span></a>
									</li>
	 <li class="treeview  ">
														  <a href="#">
															<i class="fa  fa-bookmark"></i>
															<span>Authorizations</span>
															<span class="pull-right-container">
															  <i class="fa fa-angle-leftcc pull-right">></i>
															</span>
														  </a>
	  <ul class="treeview-menu"> 
												<li style="padding:6px"><a href="<?php echo site_url();?>consumption_entry/view/1"><i   class="fa fa-circle-o text-aqua"></i>&nbsp;&nbsp;&nbsp; Break fast <span class='red'>[ 08 AM - 12 PM ]</span></a></li>
												<li style="padding:6px"><a href="<?php echo site_url();?>consumption_entry/view/2" ><i   class="fa fa-circle-o text-aqua"></i>&nbsp;&nbsp;&nbsp;Lunch<span class='red'>[ 08 AM - 12 PM ]</span></a></li>
												<li style="padding:6px"> <a href="<?php echo site_url();?>consumption_entry/view/3"  ><i   class="fa fa-circle-o text-aqua"></i>&nbsp;&nbsp;&nbsp;Snacks<span class='red'>[ 01 PM - 04:30 PM ]</span></a></li>
												<li style="padding:6px"><a href="<?php echo site_url();?>consumption_entry/view/4"  ><i     class="fa fa-circle-o text-aqua"></i>&nbsp;&nbsp;&nbsp;Supper<span class='red'>[ 01 PM - 04:30 PM ]</span></a></li>
												  
											  </ul>
											  </li>
	 <?php 
	 
	 }
		 ?>
	  
	 