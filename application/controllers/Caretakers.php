<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Caretakers extends CI_Controller {

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
		 $date=date('Y-m-d');
		 $sql ="insert into caretaker_confirmation(school_id,entry_date) SELECT school_id,CURRENT_DATE as entry_date  FROM schools where concat(school_id,'_',CURRENT_DATE) not in (select concat(school_id,'_',CURRENT_DATE) from caretaker_confirmation where entry_date=CURRENT_DATE) ";
		 $this->db->query($sql);
		 echo "Done";
	}
	 
}
