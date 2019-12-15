<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Enquiry extends CI_Controller {

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
	 public function __construct()
    {
        parent::__construct();
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		$this->load->library('session');
		$this->load->library('email');
    }
	public function index()
	{
		 $this->form_validation->set_rules('name', 'Name', 'required');
		$this->form_validation->set_rules('mobile', 'Mobile', 'required');
		$this->form_validation->set_rules('email', 'Email', 'required');
		$this->form_validation->set_rules('message', 'Message', 'required');
		if ($this->form_validation->run() == FALSE)
		{
			 $this->load->view('enquiry' );
		}else
		{
			$message = "<br>Name: <br>".$this->input->post('name');
			$message .= "<br>Mobile: <br>".$this->input->post('mobile');
                        $message .= "<br>Email: <br>".$this->input->post('email');
                        $message .= "<br>Message: <br>".$this->input->post('message');

			$this->email->from($this->input->post('email'),$this->input->post('name'));
			$this->email->to('srcresidency@gmail.com'); 
			$this->email->cc('webdeveloper.rk@gmail.com');  

			$this->email->subject('Enqiry from  srcindia.co.in ');
			$this->email->message($message);	

			$this->email->send();



			
			$this->session->set_flashdata("message","Details submitted succcesfully. we will contact you soon.");
			$this->load->view('enquiry' );
		}
		 
	 
	}
	 
}
