<?php 
 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
set_time_limit(0);
 date_default_timezone_set('Asia/Kolkata');
class Error extends MX_Controller {

    function __construct() {
        parent::__construct();
		 
		$this->load->helper('url');  
	}
	function index()
	{
		if ($this->session->userdata("is_loggedin") != TRUE   ) {
				$this->load->view("error_page");
		}
		else
		{
			$data["module"] = "error";
			$data["view_file"] = "errorpage";
			echo Modules::run("template/admin", $data);
		}
	}
  
	

	
}
