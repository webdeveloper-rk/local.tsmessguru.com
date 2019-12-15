<?php

class Template extends MX_Controller {

    function __construct() {
        parent::__construct();
    }

    function admin($controller_data = array()) {
        //Site Settings from Database
        $site_settings = $this->default_settings();
       /*foreach ($site_settings as $column => $value) {
            if ($column == "site_logo" && $value != "")
                $data[$column] = site_url() . $value;
            else
                $data[$column] = $value;
        }*/
        //Loading Passed Controller Data
        foreach ($controller_data as $var => $value) {
            $data[$var] = $value;
        }
        $this->load->view("admin_layout", $data);
    }

    function front($controller_data = array()) {
        //Site Settings from Database
        $site_settings = $this->default_settings();
   
        $this->load->view("default_layout", $data);
    }

    function default_css_files() {
        $css_files[] = site_url() . "assets/css_styles/global.css";
        $css_files[] = site_url() . "assets/css_framework/css/bootstrap.min.css";
        $css_files[] = site_url() . "assets/css_framework/css/bootstrap-responsive.min.css";
        $css_files[] = site_url() . "assets/css_styles/site.css";
        return $css_files;
    }

    function default_js_files() {
        $js_files[] = site_url() . "assets/js_common/jquery-1.10.2.min.js";
        $js_files[] = site_url() . "assets/css_framework/js/bootstrap.min.js";
        $js_files[] = site_url() . "assets/js_common/jquery.1.8.3.min.js";
        return $js_files;
    }

    function admin_css_files() {
        $css_files[] = site_url() . "assets/admin/css/bootstrap.min.css";
        $css_files[] = site_url() . "assets/admin/css/font-awesome.min.css";
        $css_files[] = site_url() . "assets/admin/css/ionicons.min.css";
        $css_files[] = site_url() . "assets/admin/css/morris/morris.css";
        $css_files[] = site_url() . "assets/admin/css/jvectormap/jquery-jvectormap-1.2.2.css";
        $css_files[] = site_url() . "assets/admin/css/fullcalendar/fullcalendar.css";
        $css_files[] = site_url() . "assets/admin/css/daterangepicker/daterangepicker-bs3.css";
        $css_files[] = site_url() . "assets/admin/css/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css";
        $css_files[] = site_url() . "assets/admin/css/AdminLTE.css";
        $css_files[] = site_url() . "assets/admin/";
        return $css_files;
    }

    function admin_js_files() {
        $js_files[] = site_url() . "assets/js_common/jquery-1.10.2.min.js";
        $js_files[] = site_url() . "assets/css_framework/js/bootstrap.min.js";
        $js_files[] = site_url() . "assets/js_common/jquery.1.8.3.min.js";
        return $js_files;
    }

    function default_settings() {
        $this->db->where("id", 1);
        $result = $this->db->get("site_settings")->row_array();
        unset($result["id"]);
        return $result;
    }

    function email($template, $message_content) {
        return $this->load->view("email_tpl/$template", $message_content, TRUE);
    }

    function share_locations() {
        $share_cityid = $this->input->post("share_cityid");
		$share_area = $this->input->post("area");
		$session_city_id = $this->session->userdata("location_city");
		 
		  if($session_city_id != NULL && $session_city_id != $share_cityid){
			$share_area = "0";//All areas if city changed
		} 
		
	
        $share_details = array(
            "location_city" => $share_cityid,
            "location_area" => $share_area,
        );
        $this->session->set_userdata($share_details);
		
		 
		
    }

}
