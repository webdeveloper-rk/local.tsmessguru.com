<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Projects extends CI_Controller {

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
		 
		$this->listprojects('current');
	}
	public function listprojects($project_type)
	{
		if(in_array($project_type,array('upcoming','current','completed')))
		{		
			$list =  array('upcoming'=>'Upcoming Projects','current'=>'Current Projects','completed'=>'Completed Projects');
			 $sql = "select * from realestate_projects where project_type='".$list[$project_type]."'";
			 $rs = $this->db->query($sql);
			 $content['projects'] = $rs;
			 $content['title'] = $list[$project_type];
			 $this->load->view('projects',$content);
		}
		else
		{
			$this->load->view('pagenotfound');
		}
			
	}
	public function viewproject($project_id=0)
	{
		$sql = "select * from realestate_projects where project_id='".$project_id."'";
		$rs = $this->db->query($sql);
		if($rs->num_rows()>0){	
			$content['project'] = $rs->row(); 
			//Project gallery project_gallery
			$galsql = "select * from project_gallery where project_id='".$project_id."'";
			$galrs = $this->db->query($galsql);
			$content['project_gallery'] = $galrs;
			 $this->load->view('viewproject',$content);
		}
		else
		{
			$this->load->view('pagenotfound');
		}
	}
	 
}
