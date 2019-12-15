<?php 
 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
set_time_limit(0);
 date_default_timezone_set('Asia/Kolkata');
class Menu_permissions extends MX_Controller {

    function __construct() {
        parent::__construct();
		if($this->uri->segment(2) !="login") { 
					 Modules::run("security/is_admin");		 
					if ($this->session->userdata("is_loggedin") != TRUE || $this->session->userdata("user_id") == "" ) {
							redirect("admin/login");
							die;
					}
					 			
					if($this->session->userdata("school_code") != "10000")
					{
						redirect("admin/login");
							die;
					}
		}
		$this->load->helper('url');
 
		$this->load->library('ci_jwt'); 
		$this->load->config('config'); 
		 
		 
	}
	function index()
	{
		 $drs = $this->db->query("select * from  roles where status='1' ");         
        $data["rset"] = $drs;
		
		
		$data["module"] = "menu_permissions";
        $data["view_file"] = "menupermissions_list";
        echo Modules::run("template/admin", $data);
         
	}
	/*
	
	*/
	function update($encoded_role_id=null)
	{
		
		 		
		$role_id = $this->ci_jwt->jwt_web_decode($encoded_role_id);	
				$permission_ids = $this->input->post("permission_ids");
		// print_a($permission_ids,0);
		//$this->form_validation->set_rules('permission_ids[]', 'Permissions', 'required');  
		
		if(count( $permission_ids)> 0   )
		 {
			$permission_ids = $this->input->post("permission_ids");
			 
			$this->db->query("delete from menu_roles where role_id=?",$role_id);
			foreach($permission_ids as $pid)
			{
				$dataarray = array("menu_id"=>$pid ,"role_id"=>$role_id);
				$this->db->insert("menu_roles",$dataarray);
			}
			 
			$this->session->set_flashdata('message', '<div class="alert alert-success">Updated Successfully.</div>'); 
			redirect('menu_permissions/update/'.$encoded_role_id); 
		 }
		 
		
		$menus_rs = $this->db->query("select * from menus where status=1 order by menu_for asc ");
		$menu_list = array();
		foreach($menus_rs->result() as $menu_row)
		{
			$menu_list[$menu_row->menu_parent_id][] = $menu_row;
		}
		$main_menu= $menu_list[0];//seperate main menu headings and menus 
		unset($menu_list[0]);
         
        $data["role_title"] = $rs = $this->db->query("select * from roles where status=1 and role_id=?",array($role_id))->row()->role_title;
        $data["main_menu"] =  $main_menu;
        $data["menus_sub_list"] = $menu_list;
		
		
		 $menus_selected  = $this->db->query("select * from menu_roles where  role_id=?",array($role_id)) ;
		 $selected_menus_list = array();
		 foreach( $menus_selected->result() as $mrow)
		 {
			$selected_menus_list[] = $mrow->menu_id;
		 }
		$data['selected_menus_list']=$selected_menus_list ;	
				
        $data["module"] = "menu_permissions"; 
        $data["view_file"] = "menu_permissions_form";
        echo Modules::run("template/admin", $data);
         
	}

}
