<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends MX_Controller {
	
	public function __construct() {
        parent::__construct();
		
		if($this->session->userdata("user_role") != "subadmin")
					{
						 
							redirect("");
						 
					}
					$this->load->model('dpc_model');
					
    }
	public function index()
	{
		$condition = '';
		if($this->session->userdata("is_dco")==true)
		{
			$district_id = $this->session->userdata("district_id");
			$condition =  " and sc.district_id= '$district_id'";
		}
		$financial_year_id = $this->db->query("SELECT * FROM `dpcrates_years` where status='active' order by financial_year_id desc")->row()->financial_year_id;
		 
		$sql ="select sc.school_id,sc.name,sc.school_code,
							dpa.school_submitted,
							dpa.school_submited_date,
							dpa.dpc_approved 
							from schools sc inner join dpcrates_approvals dpa on dpa.school_id = sc.school_id 
							where   sc.is_school=1 $condition and financial_year_id=? order by sc.school_code asc";
		
		$rset = $this->db->query($sql,array($financial_year_id));         
      
			//echo $this->db->last_query();die;
		$data["allow_to_modify"] = true;
		 

		
		 
		$data["rset"] = $rset; 
		$data["module"] = "dpcrates"; 
		$data["view_file"] = "schools_list";

        echo  Modules::run("template/admin", $data);
	}
	public function school($school_id)
	{
		$this->dpc_model->check_role_allowed($school_id);
		
		$financial_year_id = $this->db->query("SELECT * FROM `dpcrates_years` where status='active' order by financial_year_id desc")->row()->financial_year_id;
		 
		$sql ="select dp.*,concat(telugu_name,'-',item_name) as item_name  from  dpc_rates dp inner join items it on it.item_id= dp.item_id 
					  where school_id=? and financial_year_id=? order by it.item_name asc";
		//echo $sql;die;
		$drs = $this->db->query($sql,array($school_id,$financial_year_id));         
      
		$data["school_submited"] = $this->dpc_model->get_dpc_submitted($school_id); 
		$data["dpc_approved"] = $this->dpc_model->get_dpc_approved_status($school_id);
		$data["allow_to_modify"] = true;
		 
		
		 
		$data["school_id"] = $school_id;     
		$data["school_name"] = $this->db->query("select * from schools where school_id=?",array($school_id))->row()->name;     
		$data["rset"] = $drs; 
		$data["module"] = "dpcrates"; 
		$data["view_file"] = "admin_school_items_list";

        echo  Modules::run("template/admin", $data);
	}
	
	
	function dpc_entryform($school_id,$item_id=null)
	{
			$this->dpc_model->check_role_allowed($school_id);
		$financial_year_id = $this->db->query("SELECT * FROM `dpcrates_years` where status='active' order by financial_year_id desc")->row()->financial_year_id;
		 
		           
		$this->form_validation->set_rules('price', 'Price', 'required|greater_than[0]'); 
		$allow_to_modify  =  true;
		  
		if($this->form_validation->run() == true && $allow_to_modify  == true && $this->input->post('action')=="submit")
		 {
			 
			 $item_id	=	$item_id; 
			 $school_price	=	$this->input->post('price'); 
			 $this->db->query("update dpc_rates set amount=? where financial_year_id=? and school_id=? and item_id=?",
								array($school_price,$financial_year_id,$school_id,$item_id));
			  // echo $this->db->last_query();die;
			 $this->session->set_flashdata('message', '<div class="alert alert-success">Succesfully updated</div>');	 
			 redirect('dpcrates/admin/school/'.$school_id);
				 
		 }
		
		 
        $data["allow_to_modify"] = $allow_to_modify;
			$data["school_name"] = $this->db->query("select * from schools where school_id=?",array($school_id))->row()->name;     
			
        $data["item_id"] = $item_id;
        $data["item_details"] = $this->db->query("select * from items where item_id=?",array($item_id))->row();
        $data["dpc_details"] =			 $this->db->query("select * from dpc_rates   where financial_year_id=? and school_id=? and item_id=?",
								array($financial_year_id,$school_id,$item_id))->row();
		 
        $data["module"] = "dpcrates"; 
        $data["view_file"] = "admin_school_dpc_entry";
        echo Modules::run("template/admin", $data);
         
	}
	function approve($school_id)
	{
			 	$this->dpc_model->check_role_allowed($school_id);
			 $this->dpc_model->mark_school_as_approved($school_id);
			 $this->session->set_flashdata('message', '<div class="alert alert-success">Succesfully Approved</div>');	 
			 redirect('dpcrates/admin/school/'.$school_id);
	}
	function reject($school_id)
	{
			 $this->dpc_model->check_role_allowed($school_id);
			 $this->dpc_model->mark_school_as_rejected($school_id);
			 $this->session->set_flashdata('message', '<div class="alert alert-success">Succesfully Rejected</div>');	 
			 redirect('dpcrates/admin/school/'.$school_id);
	}
	/*
	*/
	 
}
