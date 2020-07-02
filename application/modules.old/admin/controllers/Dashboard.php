<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Dashboard extends MX_Controller {

    function __construct() {
        parent::__construct();
        //Authentication Check
		 Modules::run("security/is_admin");
         if ($this->session->userdata("is_loggedin") != TRUE || $this->session->userdata("user_id") == "" || $this->session->userdata("user_role") != "admin") {
            redirect("admin/login");
        }
        $this->load->model("admin_model");
    }
    
    function index() {
//        //Preparing View
        $data['users_count']=0;//
        $data['banks_count']=0;
        $data['plans_count']=0;
        $data['payments_count']=0;
        
		 
        $data["module"] = "admin";
        $data["view_file"] = "dashboard";
		if($this->session->userdata("operator_type") =="CT")
		{
			$data["view_file"] = "caretaker_dashboard";
		}
        echo Modules::run("template/admin", $data);
    }
	function underconstruction()
	{
		  $data['users_count']=0;//
        $data['banks_count']=0;
        $data['plans_count']=0;
        $data['payments_count']=0;
        
		 
        $data["module"] = "admin";
        $data["view_file"] = "underconstruction";
        echo Modules::run("template/admin", $data);
		
	}

}
