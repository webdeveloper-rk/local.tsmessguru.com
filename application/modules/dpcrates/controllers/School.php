<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class School extends MX_Controller {
	
	public function __construct() {
        parent::__construct();
		
		if($this->session->userdata("user_role") != "school")
					{
						 
							redirect("");
						 
					}
					$this->load->model('dpc_model');
					
    }
	public function index()
	{
		
		
		$financial_year_id = $this->db->query("SELECT * FROM `dpcrates_years` where status='active' order by financial_year_id desc")->row()->financial_year_id;
		$school_id = $this->session->userdata("school_id");
		$sql ="select dp.*,concat(telugu_name,'-',item_name) as item_name  from  dpc_rates dp inner join items it on it.item_id= dp.item_id 
					  where school_id=? and financial_year_id=? order by it.item_name asc";
		//echo $sql;die;
		$drs = $this->db->query($sql,array($school_id,$financial_year_id));         
      
		$data["school_submited"] = $this->dpc_model->get_dpc_submitted($school_id); 
		$data["dpc_approved"] = $this->dpc_model->get_dpc_approved_status($school_id);
		$data["allow_to_modify"] = true;
		if($data["school_submited"] == true && $data["dpc_approved"]==0 ||$data["dpc_approved"]==1 )
		{
			$data["allow_to_modify"] = false;
		}
		else if($data["school_submited"] == true && $data["dpc_approved"]==-1)
		{
			$data["allow_to_modify"] = true;
		}

		
		 
		$data["rset"] = $drs; 
		$data["module"] = "dpcrates"; 
		$data["view_file"] = "school_items_list";

        echo  Modules::run("template/admin", $data);
	}
	
	
	function dpc_entryform($item_id=null)
	{
		$financial_year_id = $this->db->query("SELECT * FROM `dpcrates_years` where status='active' order by financial_year_id desc")->row()->financial_year_id;
		$school_id = $this->session->userdata("school_id");
		           
		$this->form_validation->set_rules('price', 'Price', 'required|greater_than[0]'); 
		$allow_to_modify  =  true;
		  
		if($this->form_validation->run() == true && $allow_to_modify  == true && $this->input->post('action')=="submit")
		 {
			 $school_id	=	$this->session->userdata("school_id");
			 $item_id	=	$item_id; 
			 $school_price	=	$this->input->post('price'); 
			 $this->db->query("update dpc_rates set amount=? where financial_year_id=? and school_id=? and item_id=?",
								array($school_price,$financial_year_id,$school_id,$item_id));
			  // echo $this->db->last_query();die;
			 $this->session->set_flashdata('message', '<div class="alert alert-success">Succesfully updated</div>');	 
			 redirect('dpcrates/school');
				 
		 }
		
		 
        $data["allow_to_modify"] = $allow_to_modify;
        $data["item_id"] = $item_id;
        $data["item_details"] = $this->db->query("select * from items where item_id=?",array($item_id))->row();
        $data["dpc_details"] =			 $this->db->query("select * from dpc_rates   where financial_year_id=? and school_id=? and item_id=?",
								array($financial_year_id,$school_id,$item_id))->row();
		 
        $data["module"] = "dpcrates"; 
        $data["view_file"] = "school_dpc_entry";
        echo Modules::run("template/admin", $data);
         
	}
	function submit()
	{
			$school_id	=	$this->session->userdata("school_id");
			 
			 $this->dpc_model->mark_school_as_submited($school_id);
			 $this->session->set_flashdata('message', '<div class="alert alert-success">Succesfully submitted</div>');	 
			 redirect('dpcrates/school');
	}
	/*
	*/
	 
}
