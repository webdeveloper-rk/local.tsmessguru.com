<?php
require "classes/autoload.php";
class Webservices_lib {
    var $CI;
    public function __construct($params = array())
    {
        $this->CI =& get_instance();

        $this->CI->load->helper('url');
        $this->CI->config->item('base_url');
        $this->CI->load->database();
        
    }
	public function getattendence(){
       
	    return   "Kiran";
	}
}

?>