<?php 
 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
set_time_limit(0);
 date_default_timezone_set('Asia/Kolkata');
class Tw_attendence extends MX_Controller {

    function __construct() {
        parent::__construct();
		if($this->uri->segment(2) !="login") { 
					 Modules::run("security/is_admin");		 
					if ($this->session->userdata("is_loggedin") != TRUE || $this->session->userdata("user_id") == "" ) {
							redirect("admin/login");
							die;
					}
					 			
					if($this->session->userdata("user_role") != "school")
					{
						redirect("admin/login");
							die;
					}
		}
		$this->load->helper('url'); 
		$this->load->library('ci_jwt'); 
		$this->load->model('purchase_model'); 
		$this->load->model('common/common_model'); 
		$this->load->config('config'); 
		 
		 
	}
	function index()
	{
		 
		$data["today_purchases"] = $this->purchase_model->get_balance_entries_today($this->session->userdata("school_id"),date('Y-m-d'));
		$drs = $this->db->query("select * from  items where status='1' and 	allowed_to_edit='1'");         
        $data["rset"] = $drs;
		
		
		$data["module"] = "tw_attendence";
        $data["view_file"] = "purchase_entry";
        echo Modules::run("template/admin", $data);
         
	}
	/*
	
	*/
	function purchase_entryform($item_id=null)
	{
		
		injection_check();				 
				
		$item_id = $this->ci_jwt->jwt_web_decode($item_id);	
		
		$school_id	=	$this->session->userdata("school_id");
	 
		
		$this->form_validation->set_rules('quantity', 'Quantity', 'required|numeric|greater_than_equal_to[0]');              
		$this->form_validation->set_rules('price', 'Price', 'required|numeric|greater_than_equal_to[0]'); 
		$this->form_validation->set_rules('billno', 'Bill Number', 'numeric|greater_than[0]'); 
		
		 
		
		$date			=	date('Y-m-d');
		$school_id	=	$this->session->userdata("school_id");
		 $stock_entry_table = $this->common_model->get_stock_entry_table($date);
			 
		  $sql = "select item_id from $stock_entry_table where entry_date=? and school_id=? and item_id=? and purchase_quantity= '0.00'	";
		$rs = $this->db->query($sql,array($date ,$school_id,$item_id));
		  $locked = $rs->num_rows();
		//if 0 not allowed else allowed
		if($locked==0)
		{
			$allow_to_modify = false;
		}
		else{
			$allow_to_modify = true;
		}
		
	 
		if($this->session->userdata("operator_type")=="CT" && $this->config->item("ct_update_enabled") == true) 
		{
			$sql = "select (CURRENT_TIME between ? and ?)  as in_time";
			$in_time = $this->db->query($sql,array( $this->config->item("ct_update_start_time") ,$this->config->item("ct_update_end_time") ))->row()->in_time; 
			//echo $this->db->last_query();
			if($in_time==1)
			{
				$allow_to_modify = true;
			}
		}
	  $stock_entry_table = $this->common_model->get_stock_entry_table($date);
		 $school_id	=	$this->session->userdata("school_id");
		 $sql = "select * from $stock_entry_table where entry_date=? and school_id=? and item_id=?  	";
		$rs = $this->db->query($sql,array($date,  $school_id,$item_id));
		   
		$data["today_purchases"] = $purchased_item_row = $rs->row();
		if($this->config->item("site_name")=="twhostels"){
				$data["item_price"] = $this->common_model->get_item_fixed_price($item_id,$school_id);
			}
		
		if($this->form_validation->run() == true && $allow_to_modify  == true && $this->input->post('action')=="submit")
		 {
 
			$qty 		= 	floatval($this->input->post('quantity') );
			$school_price	=	floatval($this->input->post('price'));
			if($this->config->item("site_name")=="twhostels"){
				 $is_excempted = $this->common_model->fixed_rate_item_excemption($item_id,$school_id);
				  if($is_excempted == false) { 
				  			$school_price	=	$this->common_model->get_item_fixed_price($item_id,$school_id);
				  }
			}
			
			$date			=	date('Y-m-d');				 
			$purchase_biil_no = $this->input->post('billno');
			$entry_id = $purchased_item_row->entry_id;		
			$stock_entry_table = $this->common_model->get_stock_entry_table($date);
			$update_data = array('purchase_quantity'=> $qty,'purchase_price'=>$school_price,'purchase_biil_no'=>$purchase_biil_no);
			$this->db->where('entry_id', $entry_id);
			$this->db->update($stock_entry_table, $update_data); 
			 
			//update closing Balance	
			$qry = "update $stock_entry_table set closing_quantity=(opening_quantity+purchase_quantity) - (session_1_qty+session_2_qty+session_3_qty+session_4_qty) where entry_id=?";
			$rs = $this->db->query($qry,array($entry_id)); 
			$this->session->set_flashdata('message', '<div class="alert alert-success">Updated Successfully.</div>'); 
			redirect('purchase_entry'); 
		 }
		 
		
		
		 
        $data["allow_to_modify"] = $allow_to_modify; 
        $data["item_id"] = $item_id;
        $data["item_details"] = $rs = $this->db->query("select * from items where item_id=?",array($item_id))->row();
        $data["module"] = "tw_attendence"; 
        $data["view_file"] = "purchase_form";
        echo Modules::run("template/admin", $data);
         
	}
	 
	
}
