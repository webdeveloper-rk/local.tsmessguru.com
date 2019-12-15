<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Admin extends MX_Controller {

    function __construct() {
        parent::__construct();
		
		if($this->uri->segment(2) !="login") { 
					 Modules::run("security/is_admin");		 
					if ($this->session->userdata("is_loggedin") != TRUE || $this->session->userdata("user_id") == "" ) {
							redirect("admin/login");
							die;
					}
					 
		}
		 			
					if($this->session->userdata("user_role") != "subadmin")
					{
						redirect('dashboard');
							die;
					}
					
          $this->load->model('general_model');
         $this->general_model->set_table("users");
    }

    function index() {
        redirect("dashboard");
    }
	 
 
	function resetpassword() {
       //    Modules::run("security/is_admin");
         $this->load->model('general_model');
		 
		   
					 if ($this->input->post("action")=="updatepassword") {
							$data= $this->general_model->reset_password();
					}
		 
        $data["module"] = "general";
        $data["view_file"] = "reset_password";
        echo  Modules::run("template/admin", $data);
    }
	public 	function diet_resetpassword() {
       //    Modules::run("security/is_admin");
         $this->load->model('general_model');
         if ($this->input->post("action")=="updatepassword") {
				$data= $this->general_model->diet_reset_password();
        }
        $data["module"] = "general";
        $data["view_file"] = "diet_reset_password";
        echo  Modules::run("template/admin", $data);
    }

}
