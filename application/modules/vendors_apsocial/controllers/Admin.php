<?php 
 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
set_time_limit(0);
 date_default_timezone_set('Asia/Kolkata');
class Admin extends MX_Controller {

    function __construct() {
        parent::__construct();
		if($this->uri->segment(2) !="login") { 
					 Modules::run("security/is_admin");		 
					if ($this->session->userdata("is_loggedin") != TRUE || $this->session->userdata("user_id") == "" ) {
							redirect("admin/login");
							die;
					}
					//print_a($this->session->userdata("user_role"),1);
					 			
					if($this->session->userdata("user_role") != "subadmin")
					{
						redirect("admin/login");
							die;
					}
		}
		 
		 
	}

    function index() {
   
		
		 $condition = '';
		if($this->session->userdata("is_dco") == 1)
		 {
			  $condition = " and district_id='".$this->session->userdata("district_id")."' ";
		 }			 
		 
        $data['schools_rs'] = $this->db->query("select * from schools where is_school='1'  ".$condition);
		 
        $data["module"] = "vendors_apsocial"; 
        $data["view_file"] = "schools_form";
        echo Modules::run("template/admin", $data);
	}
	
	function confirm_vendor($school_id) {
   
		if($this->session->userdata("is_dco") == 1)
		 {
			  $district_id = $this->session->userdata("district_id");
			  $schools_rs  = $this->db->query("select * from schools where school_id=? and district_id=?",array($school_id,$district_id));
			  if($schools_rs->num_rows()==0){
				die("<h1>Access Denied</h1>");
			  }
				  
		 }		 
		$data['school_id'] = $school_id;
		$data['school_info'] = $this->db->query("select * from schools  where school_id=?",array($school_id))->row();
		  
        $data['vendors'] = $this->db->query("select * from tw_vendors where school_id=?",array($school_id));
		 
        $data["module"] = "vendors_apsocial"; 
        $data["view_file"] = "vendor_list_admin";
        echo Modules::run("template/admin", $data);
	} 
	function confirm_vendor_by_dco($school_id,$vendor_annapurna_id) {
   
		if($this->session->userdata("is_dco") == 1)
		 {
			  $district_id = $this->session->userdata("district_id");
			  $schools_rs  = $this->db->query("select * from schools where school_id=? and district_id=?",array($school_id,$district_id));
			  if($schools_rs->num_rows()==0){
				die("<h1>Access Denied</h1>");
			  }
				  
		 }		 
		$this->db->query("update tw_vendors set dco_confirmed='1' , dco_confirmed_date=now() where school_id=? and vendor_annapurna_id=?",array($school_id,$vendor_annapurna_id));
		 $this->session->set_flashdata('message', '<div class="alert alert-success">Supplier Confirmed successfully.</div>');
		
		 redirect("vendors_apsocial/admin/confirm_vendor/".$school_id);
	} 
	function delete_vendor($school_id,$vendor_annapurna_id) {
			die("<h1>Access Denied</h1>"); 
		/*
		//restrict date to delete 
		$max_date = '2019-11-30';
		$date_rs = $this->db->query("select CURRENT_DATE() <= ? as allowed",array($max_date));
		$allowed = $date_rs->row()->allowed;
		if($allowed==0)
		{
			die("<h1>Access Denied to Delete as vendor may  already mapped to purchases and consumptions.</h1>"); 
		}
   
		if($this->session->userdata("is_dco") == 1 )
		 { 
				die("<h1>Access Denied</h1>"); 
		 }	
		if($this->session->userdata("school_code") != "10100" )
		 { 
				die("<h1>Access Denied</h1>"); 
		 }	
		 
		 $this->db->query("delete from tw_vendors where school_id=? and vendor_annapurna_id=?",array($school_id,$vendor_annapurna_id));
		 $this->db->query("delete from tw_vendors_items_map where school_id=? and vendor_annapurna_id=?",array($school_id,$vendor_annapurna_id));
		 $this->session->set_flashdata('message', '<div class="alert alert-success">Supplier deleted successfully.</div>');
		
		 redirect("vendors_apsocial/admin/confirm_vendor/".$school_id);*/
	} 
	function updatestatus($status,$vendor_annapurna_id,$school_id) {
		
		if($status=="0")
		{
			$msg  = "Vendor Deactivated ";
		}
		else  if($status=="1")
		{
			$msg  = "Vendor Activated ";
		}else{
			
			die("<h1>Error in Deletion</h1> ");
		}
		 $this->db->query("update tw_vendors set status=? where   vendor_annapurna_id=?",array($status ,$vendor_annapurna_id));
		 $this->session->set_flashdata('message', '<div class="alert alert-success">'.$msg.'</div>');
		
		 redirect("vendors_apsocial/admin/confirm_vendor/".$school_id);
	}
	
	function generate_pdf($school_id)
	{
		  $this->load->library('pdf');
		  
		if($this->session->userdata("is_dco") == 1)
		 {
			  $district_id = $this->session->userdata("district_id");
			  $schools_rs  = $this->db->query("select * from schools where school_id=? and district_id=?",array($school_id,$district_id));
			  if($schools_rs->num_rows()==0){
				die("<h1>Access Denied</h1>");
			  }
				  
		 }		 
		
		$data['school_id'] = $school_id;
		$data['school_info'] = $school_info = $this->db->query("select * from schools  where school_id=?",array($school_id))->row();
		  
        $data['vendors'] = $this->db->query("select * from tw_vendors where school_id=?",array($school_id));
		
		/*
		The A4 sheet that is printed or downloaded should be of fixed height and width.
		as written in the view "dummmy_pdf_view"
		*/
	   $html =$this->load->view('vendor_list_admin_pdf', $data , true);
	   $this->dompdf->loadHtml($html);
	   $this->dompdf->setPaper('A4', 'potrait');
	   $this->dompdf->render();
		//$this->dompdf->stream($school_info->school_code."_".$school_info->name.'_suppliers.pdf', array('Attachement'=> 0));
		$pdf_filename = $school_info->school_code."_".rand(1000,99999999).".pdf";
	    $pdfroot = "assets/vendors_pdfs/".$pdf_filename;
	    $pdf_string =   $this->dompdf->output();
	    file_put_contents($pdfroot, $pdf_string );
	   

	   //$this->invoice_pdf_download($customer_id , $month , $year);
		unset($this->dompdf);
		
		$dco_id= $this->session->userdata("user_id");
		$confirmed_date= date("Y-m-d H:i:s");
		//update confirmation table
		$rs= $this->db->query("select * from tw_vendors_confirmation where school_id=?",array($school_id));
		if($rs->num_rows()==0)
		{
			$this->db->query("insert into tw_vendors_confirmation set school_id=?,is_dco_confirmed='1',dco_id=?,confirmed_date=?,pdf_path=?", 
			array($school_id,$dco_id,$confirmed_date,$pdf_filename));
		}
		else{
			$this->db->query("update  tw_vendors_confirmation set is_dco_confirmed='1',dco_id=?,confirmed_date=?,pdf_path=? where school_id=?", 
			array($dco_id,$confirmed_date,$pdf_filename,$school_id));
			
		}
		$this->session->set_flashdata('message', '<div class="alert alert-success">Suppliers confirmed successfully, PDF is ready to download.</div>');
		redirect("vendors_apsocial/admin/");
	}
  function entryform($account_type='',$vendor_annapurna_id=0)
	{
		$allowed_account_types  = array("firm","individual");
		if(!in_array($account_type,$allowed_account_types))
		{
			die("<h1>Invalid Account Type</h1>");
		}
		$vend_rs = $this->db->query("select * from tw_vendors where vendor_annapurna_id=?",array($vendor_annapurna_id));
		
		$school_id = $vend_rs->row()->school_id;
		
		$school_rs = $this->db->query("select * from schools where school_id=?",array($school_id));
		$school_name = $school_rs->row()->school_code." - ".$school_rs->row()->name;
		
		if($this->session->userdata("is_dco") == 1)
		 {
			  $district_id = $this->session->userdata("district_id");
			  $schools_rs  = $this->db->query("select * from schools where school_id=? and district_id=?",array($school_id,$district_id));
			  if($schools_rs->num_rows()==0){
				die("<h1>Access Denied</h1>");
			  }
				  
		 }		
		 
		 
		$condition = '';
		if($account_type=="individual")
		{
			$condition = " and LOWER(item_name) != 'rice'";
		}
		$already_mapped_rs  = $this->db->query("select *  from tw_vendors_items_map where school_id=? and vendor_annapurna_id=?",array($school_id,$vendor_annapurna_id));
		//echo $this->db->last_query();die;
		$mapped_items = array();
		foreach($already_mapped_rs->result() as $mapped)
		{
			$mapped_items[] = $mapped->item_id;
		}
		
		$data["mapped_items"] = $mapped_items; 
		
		$data["school_name"] = $school_name; 
		$data["school_id"] = $school_id; 
		$data["vendor_info"] = $this->db->query("select * from tw_vendors where vendor_annapurna_id=?",array($vendor_annapurna_id))->row();
		$data["account_type"] = $account_type; 
		$data["vendor_annapurna_id"] = $vendor_annapurna_id; 
		$data["items"] = $this->db->query("select * from  items where status='1' $condition and item_id not in(select item_id from central_items_list   )");
		$data["module"] = "vendors_apsocial"; 
        $data["view_file"] = "vendor_form_edit";
        echo Modules::run("template/admin", $data);
         
	}
	 
	public function ajax_update_vendor($account_type='',$vendor_annapurna_id)
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
        $config['max_size'] = 20000;
        $config['max_width'] = 2000;
        $config['max_height'] = 2000;
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
		
		
		$school_id	=	$this->db->query("select * from tw_vendors where vendor_annapurna_id=?",array($vendor_annapurna_id))->row()->school_id;
		 $required_fields = array('vendor_type','vendor_name',    'vendor_bank','vendor_bank_branch','vendor_bank_ifsc','vendor_account_number');
		 
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
				$check_already_exists_rs = $this->db->query("select * from tw_vendors where school_id=? and vendor_bank_ifsc=? and vendor_account_number=? and vendor_annapurna_id !=?",array($school_id,$vendor_bank_ifsc,$vendor_account_number,$vendor_annapurna_id));
				
				
				/*
				if($check_already_exists_rs->num_rows()>0)
				{
					$result_flags['success'] = false;
					$result_flags['msg'] = '<div class="alert alert-danger">IFSC CODE And Account Number already exists </div>'; ;
					echo json_encode($result_flags);die;
				}*/
			  
				 
					$sc_rs = $this->db->query("select * from schools where school_id=?",array($school_id));
					$sc_info = $sc_rs->row();
					 
					
			 
					//$ins_data['school_id'] = $school_id;
					$ins_data['vendor_type'] = trim($this->input->post('vendor_type'));
					$ins_data['vendor_name'] = trim($this->input->post('vendor_name')); 
					$ins_data['pan_number'] = trim($this->input->post('pan_number')); 
					$ins_data['gst_number'] = trim($this->input->post('gst_number')); 
					
					$ins_data['title'] = trim($this->input->post('title')); 
					$ins_data['sur_name'] = trim($this->input->post('sur_name')); 
					$ins_data['city'] = trim($this->input->post('city')); 
					$ins_data['postal_code'] = trim($this->input->post('postal_code')); 
					$ins_data['beneficiary_code'] = trim($this->input->post('beneficiary_code')); 
					
					 
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
					if($bank_cover_image !=""){
						 $ins_data['bank_passbook_cover'] = $bank_cover_image;
					}
					$ins_data['account_type'] = $account_type;
					//print_a($ins_data,1);
					
					$this->db->where("vendor_annapurna_id",$vendor_annapurna_id);
					$this->db->update("tw_vendors",$ins_data);
					//echo $this->db->last_query();die;
					  //Delete already mapped_items 
					  $this->db->query("delete from tw_vendors_items_map  where  vendor_annapurna_id=?",array($vendor_annapurna_id));
					
					//map items to added to vendor 
					foreach($items as $key=>$item_id){
							$this->db->query("insert into tw_vendors_items_map  set  school_id=? , vendor_annapurna_id=?, item_id=?",array($school_id,$vendor_annapurna_id,$item_id));
						}
					 //echo $this->db->last_query();die;
					$result_flags['success'] = true;
					$result_flags['msg'] = '<div class="alert alert-success">Supplier updated Successfully </div>'; ;
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
 
}
