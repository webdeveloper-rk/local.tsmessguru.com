<?php
defined('BASEPATH') OR exit('No direct script access allowed');
set_time_limit(0);

class Email extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
		 $this->load->library('email');
		 $subject="SMS TEST";
		 $message  = "Failed to upload";
		 $body = $this->email->full_html($subject, $message);

		$result = $this->email
				->from('annapurna.smtp@gmail.com')
				->reply_to('annapurna.smtp@gmail.com')    // Optional, an account where a human being reads.
				->to('webdeveloper.rk@gmail.com')
				->subject($subject)
				->message($body)
				->send();

		var_dump($result);
		echo '<br />';
		echo $this->email->print_debugger();
 
		  
	}
	 
	 
}
