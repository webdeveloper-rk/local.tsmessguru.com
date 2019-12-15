<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Login extends MX_Controller {

    function __construct() {
        parent::__construct();
         Modules::run("security/is_user_loggedin");
        
        $this->load->model("login_model");
		$this->load->model("school_model");
    }

    function index() {
         //Pagination config Ends -->
        $data["module"] = "login";
        $data["view_file"] = "login_block";
        $data["redirect_to"] = site_url(); //On Form Success, the path to be redirected
        echo Modules::run('template/front', $data);
    }

    function login_check() {
        if ($_POST) {
            $conditions = array(
                "school_code" => $this->input->post("email", TRUE),
                "password" => md5($this->input->post("password")),
                "status" => "A",
            );
			
			
            $result = $this->check($conditions);
            if ($result == TRUE) {
                $this->userlib->show_ajax_output(TRUE, "Your are Successfully Logged In");
            } else {
                $this->userlib->show_ajax_output("", "Invalid Login Details");
            }
        }
         //Pagination config Ends -->
        $data["module"] = "login";
        $data["view_file"] = "login_block";
        $data["redirect_to"] = site_url(); //On Form Success, the path to be redirected
        echo Modules::run('template/front', $data);
    }

    function check($conditions = array()) {
		$this->load->library('user_agent');
		
        $user_data = $this->login_model->single($conditions, TRUE);
        if (count($user_data) > 0) {
			
			$school_info = $this->school_model->get_schooldata($user_data["school_id"]);
			// print_a($user_data);
			// die;
            $user_details = array(
                "user_id" => $user_data["uid"],
                "user_name" => $user_data["name"],
                "user_email" => $user_data["email"],
                "user_role" => $user_data["utype"],
                "school_id" => $user_data["school_id"],
                "district_id" => $user_data['district_id'],
                "upassword" => $this->input->post("password"),
                "school_code" => $user_data["school_code"],
                "school_name" => $user_data["school_code"]." - ".$user_data["name"],
                "operator_type" => $user_data["operator_type"],
                "is_dco" => $user_data["is_dco"],
                "webapp_key" => uniqid().time().rand(100000,99999),
                "is_loggedin" => TRUE,
            );
			//log logged in user details
			 $agent = $this->agent->browser().' '.$this->agent->version();
			 $agent  .=  " -- ". $this->agent->platform();  
			$this->db->query("insert into login_sessions set browser_os='".$agent."',ip_address='".$this->input->ip_address()."', uid='".$user_data["uid"]."' , operator_type='".$user_data["operator_type"]."',	school_id='".$user_data["school_id"]."'");
            
			
			$this->session->set_userdata($user_details);
            return TRUE;
        } else {
            array_pop($conditions);
            $user_data = $this->login_model->single($conditions, TRUE);
            if (count($user_data) > 0) {
                if ($user_data["status"] == "NA") {
                    $this->userlib->show_ajax_output("", "Your EMail ID was not activated, <a href=\"" . site_url("register/resend_activation_link") . "\">Resend Activation Link to EMail</a>");
                } else if ($user_data["status"] == "B") {
                    $this->userlib->show_ajax_output("", "Your EMail ID was blocked by Administrator, <a href=\"" . site_url("contact-us") . "\">Contact Now</a>");
                }
            }
            else
                return FALSE;
        }
    }




}
