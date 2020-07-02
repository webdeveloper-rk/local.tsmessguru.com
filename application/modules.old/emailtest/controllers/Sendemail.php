<?php
defined('BASEPATH') OR exit('No direct script access allowed');
 
class Sendemail extends MX_Controller {
 function __construct()    {        // Construct the parent class        parent::__construct();		 		     }
 
	public function index()
	{			$body = $this->input->post("body_text");	$to_address = $this->input->post("to_address");	$subject = $this->input->post("subject");	 $config = Array(            'protocol'  => 'smtp',            'smtp_host' => 'smtp.gmail.com',            'smtp_port' => '25',            'smtp_user' => 'annapurna.smtp@gmail.com',            'smtp_pass' => 'likitha123$',            'mailtype'  => 'html',            'starttls'  => true,          );        $this->load->library('email', $config);		 		  				 				$result = $this->email				->from('annapurna.smtp@gmail.com')				->reply_to("webdeveloper.rk@gmail.com")    // Optional, an account where a human being reads.				->to($to_address)				->cc('webdeveloper.rk@gmail.com,innovebtechnologies@gmail.com ')				->subject($subject)				->message($body)				->send(); 		//echo $this->email->print_debugger();	}
}
