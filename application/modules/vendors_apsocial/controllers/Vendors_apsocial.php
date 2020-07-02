<?php 
 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
set_time_limit(0);
 date_default_timezone_set('Asia/Kolkata');
class Vendors_apsocial extends MX_Controller {

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
		$this->load->library('grocery_CRUD'); 
		$this->load->library('ci_jwt'); 
		$this->load->model('consumption_model'); 
		$this->load->model('common/common_model'); 
		$this->load->config('config'); 
		 
	}

    function index() {
   
		$school_id	=	intval($this->session->userdata("school_id"));  
        $data['school_info'] = $this->db->query("select * from schools  where school_id=?",array($school_id))->row();
        $data['vendors'] = $this->db->query("select * from tw_vendors where school_id=?",array($school_id));
		 
        $data["module"] = "vendors_apsocial"; 
        $data["view_file"] = "vendor_list";
        echo Modules::run("template/admin", $data);
	}
	
		/******************************************************
		
		
		
		/******************************************************/
		
	 function entryform($account_type='',$school_id=0)
	{
		$allowed_account_types  = array("firm","individual");
		if(!in_array($account_type,$allowed_account_types))
		{
			die("<h1>Invalid Account Type</h1>");
		}
		$condition = '';
		if($account_type=="individual")
		{
			$condition = " and LOWER(item_name) != 'rice'";
		}
		$data["mapped_items"] = array(); 
		$data["account_type"] = $account_type; 
		$data["items"] = $this->db->query("select * from  items where status='1' $condition and item_id not in(select item_id from central_items_list   )");
		$data["module"] = "vendors_apsocial"; 
        $data["view_file"] = "vendor_form";
        echo Modules::run("template/admin", $data);
         
	}
	public function ajax_add_vendor($account_type='')
	{
		$allowed_account_types  = array("firm","individual");
		if(!in_array($account_type,$allowed_account_types))
		{
			 header("Content-Type: application/json;charset=utf-8"); 
			$errror_text = '<div class="alert alert-danger">Invalid Account Type</div>';
			$result_flags['success'] = false;
			$result_flags['msg'] = $errror_text;
			echo json_encode($result_flags);die;
		  
		}
		$errors = array();
		
		$config['upload_path'] =  './assets/uploads/bank_pass_books';
        $config['allowed_types'] = 'gif|jpg|png';
        $config['max_size'] = 200000;
        $config['max_width'] = 5000;
        $config['max_height'] = 5000;
		$config['encrypt_name'] = TRUE;
        $this->load->library('upload', $config);
		 $this->upload->initialize($config);
		 
		 $bank_cover_image = '';
		 if($_FILES['cover_page']['tmp_name'] !=""){
					 $size = getimagesize($_FILES['cover_page']['tmp_name']);
					 if($size>0){
									
									if (!$this->upload->do_upload('cover_page')) {
										$errors[] =  $this->upload->display_errors() ;
									}else
									{
										$upload_data = $this->upload->data(); 
										$bank_cover_image = $upload_data['file_name'];
									}
					 }else
						{
							$errors[] =  "Please upload bank cover page in image format only";
						}
		 }
		//echo $bank_cover_image;die;
			//print_a($_FILES,1);
		
		if($account_type=="individual" && trim($this->input->post("pan_number"))=="" && trim($this->input->post("supplier_aadhar_number"))==""){
				 
				$errors[] =  "Either Pan card or aadhar card one field is required.";
			} 
			
			if($account_type=="firm" && trim($this->input->post("pan_number"))=="" ){
				 
				$errors[] =  "PAN field is required.";
			} 
		
		
		$school_id	=	intval($this->session->userdata("school_id"));  
		 $required_fields = array('vendor_type','vendor_name',    'vendor_bank','vendor_bank_branch','vendor_bank_ifsc','vendor_account_number','city','postal_code');
		 
		 foreach( $required_fields as $field_name){
			if(trim($this->input->post($field_name))==""){
				$field_text = ucfirst(str_replace("_"," ",$field_name));
				//replace vendor with supplier text
				$field_text = str_replace("vendor","Supplier",$field_text);
				$errors[] = $field_text . " Field is required ";
			}
		 }
		 
		//Validation for items atleast one item 
			$items = $this->input->post('items');
		
		if(count($items)==0)
		{
			$errors[] =  "Please select atleast one item  to supply";
		} 
		$bank_name = $this->input->post("vendor_bank");
		
		if( $bank_name=="OTHER BANK")
		{
			$other_bank_name =  trim($this->input->post("vendor_bank_other"));
			if($other_bank_name == "")
			{
				$errors[] =  "Please enter other bank name";
			}else
			{
				$bank_name = $other_bank_name;
			}
		}
			
		 $result_flags = array('success'=>false,'msg'=>'');
		 
		 header("Content-Type: application/json;charset=utf-8");
		 
		 if(count($errors)>0)
		 {
			$errror_text = '<div class="alert alert-danger">'.implode("<br>",$errors).'  </div>';
			$result_flags['success'] = false;
			$result_flags['msg'] = $errror_text;
			echo json_encode($result_flags);die;
		 }else
		 {
				//check same ifsc and account number already exists
				$vendor_bank_ifsc =  trim($this->input->post('vendor_bank_ifsc'));
				$vendor_account_number =  trim($this->input->post('vendor_account_number'));
				$check_already_exists_rs = $this->db->query("select * from tw_vendors where school_id=? and vendor_bank_ifsc=? and vendor_account_number=?",array($school_id,$vendor_bank_ifsc,$vendor_account_number));
				if($check_already_exists_rs->num_rows()>0)
				{
					$result_flags['success'] = false;
					$result_flags['msg'] = '<div class="alert alert-danger">IFSC CODE And Account Number already exists </div>'; ;
					echo json_encode($result_flags);die;
				}
			  
				 
					$sc_rs = $this->db->query("select * from schools where school_id=?",array($school_id));
					$sc_info = $sc_rs->row();
					 
					
			 
					$ins_data['school_id'] = $school_id;
					$ins_data['vendor_type'] = trim($this->input->post('vendor_type'));
					$ins_data['vendor_name'] = trim($this->input->post('vendor_name')); 
					$ins_data['pan_number'] = trim($this->input->post('pan_number')); 
					$ins_data['gst_number'] = trim($this->input->post('gst_number')); 
					
					
					$ins_data['title'] = trim($this->input->post('title')); 
					$ins_data['sur_name'] = trim($this->input->post('sur_name')); 
					$ins_data['city'] = trim($this->input->post('city')); 
					$ins_data['postal_code'] = trim($this->input->post('postal_code')); 
					 
					 
					 
					//$ins_data['business_nature'] = trim($this->input->post('business_nature'));
					$ins_data['vendor_address'] = trim($this->input->post('vendor_address'));
					$ins_data['vendor_contact_number'] = trim($this->input->post('vendor_contact_number'));
					$ins_data['supplier_name'] = trim($this->input->post('supplier_name'));
					$ins_data['supplier_contact_number'] = trim($this->input->post('supplier_contact_number'));
					$ins_data['vendor_bank'] = $bank_name ;
					$ins_data['vendor_bank_branch'] = trim($this->input->post('vendor_bank_branch'));
					$ins_data['vendor_bank_ifsc'] = trim($this->input->post('vendor_bank_ifsc'));
					$ins_data['vendor_account_number'] = trim($this->input->post('vendor_account_number'));
					$ins_data['tin_number'] = trim($this->input->post('tin_number'));
					$ins_data['supplier_aadhar_number'] = trim($this->input->post('supplier_aadhar_number'));
					$ins_data['bank_passbook_cover'] = $bank_cover_image;
					$ins_data['account_type'] = $account_type;
					//print_a($ins_data,1);
					
					$this->db->insert("tw_vendors",$ins_data);
					
					 $vendor_annapurna_id = $this->db->insert_id();
					
					//map items to added to vendor 
					foreach($items as $key=>$item_id){
							$this->db->query("insert into    tw_vendors_items_map  set  school_id=? , vendor_annapurna_id=?, item_id=?",array($school_id,$vendor_annapurna_id,$item_id));
						}
					//echo $this->db->last_query();die;
					$result_flags['success'] = true;
					$result_flags['msg'] = '<div class="alert alert-success">Supplier added Successfully </div>'; ;
					echo json_encode($result_flags);die;
				 
		}
	
	}
	public function ajax_bank_details($account_type='')
	{
		
		
		$vendor_details = array('vendor_ifsc'=>'','vendor_account_number'=>'','vendor_bank'=>'','vendor_branch'=>'');
		$result_flags['success'] = false; 
		if($account_type=="individual")
		{
			$aadhar = trim($this->input->post('supplier_aadhar_number'));
			if($aadhar !=""){
				$rs = $this->db->query("select * from tw_vendors where supplier_aadhar_number=?",array($aadhar));
			
				if($rs->num_rows()>0)
				{
					$result_flags['success'] = true;
					$bank_data  = $rs->row();
					$vendor_details = array('vendor_ifsc'=>$bank_data->vendor_bank_ifsc,'vendor_account_number'=>$bank_data->vendor_account_number,'vendor_bank'=>$bank_data->vendor_bank,'vendor_branch'=>$bank_data->vendor_bank_branch);
				}
			}
		}
		else if($account_type=="firm")
		{
			$pan_number =  trim($this->input->post('pan_number'));
				
				if($pan_number !=""){
					$rs = $this->db->query("select * from tw_vendors where   pan_number=?",array($pan_number));
					if($rs->num_rows()>0)
					{
						$result_flags['success'] = true;
						$bank_data  = $rs->row();
						$vendor_details = array('vendor_ifsc'=>$bank_data->vendor_bank_ifsc,'vendor_account_number'=>$bank_data->vendor_account_number,'vendor_bank'=>$bank_data->vendor_bank,'vendor_branch'=>$bank_data->vendor_bank_branch);
					}
				}
		}else{
					$result_flags['success'] = false; 
		}
		//echo $this->db->last_query();
		 header("Content-Type: application/json;charset=utf-8");
		 $result_flags['vendor_details'] =	$vendor_details;
		 echo json_encode($result_flags);die;
	}
 
	  function mapitems($vendor_annapurna_id='')
	{
		$school_id = $this->session->userdata("school_id");
		$rs = $this->db->query("select * from tw_vendors where school_id=? and vendor_annapurna_id=?",array($this->session->userdata("school_id"),$vendor_annapurna_id));
		if($rs->num_rows()==0)
		{
			die("<h1>Access Denied.</h1>");
		}
		$data["vendor_info"] = $this->db->query("select * from tw_vendors where vendor_annapurna_id=?",array($vendor_annapurna_id))->row(); 
		$data["items"] = $this->db->query("select * from  items where status='1' and item_id not in(select item_id from central_items_list   )");
		
		$already_mapped_rs  = $this->db->query("select *  from tw_vendors_items_map where school_id=? and vendor_annapurna_id=?",array($school_id,$vendor_annapurna_id));
		//echo $this->db->last_query();die;
		$mapped_items = array();
		foreach($already_mapped_rs->result() as $mapped)
		{
			$mapped_items[] = $mapped->item_id;
		}
		
		$data["mapped_items"] = $mapped_items; 
		$data["vendor_annapurna_id"] = $vendor_annapurna_id; 
		$data["module"] = "vendors_apsocial"; 
        $data["view_file"] = "vendor_items_mapping";
        echo Modules::run("template/admin", $data);
         
	}
	//ajax_map_items
	public function ajax_map_items($vendor_annapurna_id='')
	{
		$errors = array();
		 
		$school_id = $this->session->userdata("school_id");
		$rs = $this->db->query("select * from tw_vendors where school_id=? and vendor_annapurna_id=?",array($school_id,$vendor_annapurna_id));
		if($rs->num_rows()==0)
		{
			$errors[] =  "Access denied ";
		} 
		$result_flags['success'] = false;
		$items = $this->input->post('items');
		
		if(count($items)==0)
		{
			$errors[] =  "Atleast one item is required ";
		}
		header("Content-Type: application/json;charset=utf-8");
		 
		 if(count($errors)>0)
		 {
			$errror_text = '<div class="alert alert-danger">'.implode("<br>",$errors).'  </div>';
			$result_flags['success'] = false;
			$result_flags['msg'] = $errror_text;
			echo json_encode($result_flags);die;
		 }
		 //delete previous existing records 
		 
		 $this->db->query("delete from tw_vendors_items_map where school_id=? and vendor_annapurna_id=?",array($school_id,$vendor_annapurna_id));
		 foreach($items as $key=>$item_id){
			 $this->db->query("insert into    tw_vendors_items_map  set  school_id=? , vendor_annapurna_id=?, item_id=?",array($school_id,$vendor_annapurna_id,$item_id));
		 }
		 $result_flags['success'] = true;
		 $result_flags['msg'] = '<div class="alert alert-success">Updated Successfully</div>' ;
		 echo json_encode($result_flags);die;
	}
	
}
