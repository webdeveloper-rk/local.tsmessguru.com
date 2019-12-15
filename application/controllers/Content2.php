<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Content2 extends CI_Controller {

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
		
				 
					
					
					
					
		 
	
		//$this->viewpage('aboutus');
	}
	public function viewpage($page)
	{
		 
		$cms_rs = $this->db->query("select * from  cms_achivement where url='$page' ");
		
		if ($cms_rs->num_rows() > 0)
		{		
			$content['pagecontent'] =  $cms_rs->row();
			/*if($page=="contactus")
			{
				$this->load->view('contactus',$content);
			}
			else{			*/
				$this->load->view('content',$content);
			//}
		}
		else
		{
			$this->load->view('pagenotfound');
		}
			
	}
	 
}
