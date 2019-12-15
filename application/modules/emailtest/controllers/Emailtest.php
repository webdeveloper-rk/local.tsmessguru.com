<?php
defined('BASEPATH') OR exit('No direct script access allowed');
 
class Emailtest extends MX_Controller {
 function __construct()    {        // Construct the parent class        parent::__construct();		 		     }
 
	public function index()
	{	 $config = Array(            'protocol'  => 'smtp',            'smtp_host' => 'smtp.gmail.com',            'smtp_port' => '25',            'smtp_user' => 'annapurna.smtp@gmail.com',            'smtp_pass' => 'likitha123$',            'mailtype'  => 'html',            'starttls'  => true,          );        $this->load->library('email', $config);		 		  				$body  = "<br><br>Email generated at ".date('d-M-Y h:i:s A') ."<br><br>".site_url()."<br>************";				$result = $this->email				->from('annapurna.smtp@gmail.com')				->reply_to("webdeveloper.rk@gmail.com")    // Optional, an account where a human being reads.				->to('webdeveloper.rk@gmail.com')				->cc('webdeveloper.rk@gmail.com,innovebtechnologies@gmail.com ')				->subject($this->config->item("society_name")." - test subject ")				->message($body)				->send(); 				echo $this->email->print_debugger();	}
}
