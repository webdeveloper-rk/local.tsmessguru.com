<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dpcrates extends MX_Controller {
	
	public function __construct() {
        parent::__construct();
    }
	public function index()
	{
		if($this->session->userdata("user_role") == "school")
		{ 
			redirect("dpcrates/school"); 
		}
		if($this->session->userdata("user_role") == "subadmin")
		{ 
			redirect("dpcrates/admin"); 
		}
	}
	 
}
