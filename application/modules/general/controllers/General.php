<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class General extends MX_Controller {

    function __construct() {
        parent::__construct();
		
		if($this->uri->segment(2) !="login") { 
					 Modules::run("security/is_admin");		 
					if ($this->session->userdata("is_loggedin") != TRUE || $this->session->userdata("user_id") == "" ) {
							redirect("admin/login");
							die;
					}
					 
		}
					
          $this->load->model('general_model');
         $this->general_model->set_table("users");
    }

    function index() {
        redirect("admin/dashboard");
    }
	function logout() {
        $variables = array("user_id"=>"","user_name"=>"","user_email"=>"","user_role"=>"","is_loggedin"=>false);
        $this->session->set_userdata($variables);
        $this->session->unset_userdata($variables);
		$this->session->sess_destroy();
        $msg = $this->userlib->gen_msg_output(TRUE,"You are successfully logged out");
        $this->session->set_flashdata('notice', $msg);
        redirect(site_url());
    }
     function changepassword() {
       //    Modules::run("security/is_admin");
         $this->load->model('general_model');
         if ($this->input->post("action")=="updatepassword") {
        $data= $this->general_model->change_password();
        }
        $data["module"] = "general";
        $data["view_file"] = "changepassword_view";
        echo  Modules::run("template/admin", $data);
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
