<?php 
 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
set_time_limit(0);
 date_default_timezone_set('Asia/Kolkata');
class Dashboard extends MX_Controller {

    function __construct() {
        parent::__construct();
		if($this->uri->segment(2) !="login") { 
					 Modules::run("security/is_admin");		 
					
		}
		$this->load->helper('url');  
		$this->load->config("config.php");  
		$this->load->library("ci_jwt");  
	}
	function index()
	{
		redirect("school_day_report");
		$data["module"] = "dashboard";
		$data["view_file"] = "dashboard";
		echo Modules::run("template/admin", $data);
	}
  
	

	
}
