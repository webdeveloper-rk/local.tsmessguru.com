<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Import extends MX_Controller {

    function __construct() {
        parent::__construct();
		
		if($this->uri->segment(2) !="login") { 
					 Modules::run("security/is_admin");		 
					/*if ($this->session->userdata("is_loggedin") != TRUE || $this->session->userdata("user_id") == "" ) {
							redirect("admin/login");
							die;
					}
					if($this->session->userdata("user_role") != "headoffice" )
					{
						redirect("admin/school/customreports");
					}*/
					
					 
		}
					
          
    }

   

    function index() {

         $data["module"] = "admin";
        $data["view_file"] = "import_form";
        echo Modules::run("template/admin", $data);

    }
	function import_data()
	{
		print_a($_POST);
	}



    function confirm($import_id=0) {
		
		
		
		}





}

