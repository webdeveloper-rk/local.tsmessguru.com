<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Manage extends MX_Controller {

	 
	
	 function __construct() {
        parent::__construct();
		if($this->uri->segment(2) !="login") { 
					 Modules::run("security/is_admin");		 
					
		}
		if ($this->session->userdata("is_loggedin") != TRUE || $this->session->userdata("user_id") == "" ) {
							redirect("admin/login");
							die;
					}
					 			
					 
	 
		$this->load->library('Image_CRUD');
	}
	
	function send_output($output = null)
	{
		$this->load->view('example.php',$output);	
	}
	
	function index()
	{
		//$this->send_output((object)array('output' => '' , 'js_files' => array() , 'css_files' => array()));
		//$this-> projectgallery();
		 
	}	
	
 

	function purchasegallery()
	{
		  
		  
	$image_crud = new image_CRUD();
	
		$image_crud->set_primary_key_field('id');
		$image_crud->set_url_field('url');
		$image_crud->set_title_field('title');
		$image_crud->set_table('purchase_bills_old'); 
		$image_crud->set_ordering_field('dateposted');
		$image_crud->unset_upload();
		$image_crud->unset_delete();
		//$image_crud->unset_upload();
		$image_crud->set_image_path('assets/uploads/gallery'); 
		
		//$image_crud->set_relation_field(12);
		$output = $image_crud->render(); 
		 //$this->_example_output($output);
			$data["module"] = "gallery";
			$data["view_file"] = "manage";
			$output->title = " Purchase  Bills OLD";
			
			//echo "<pre>";			print_r($output);die;
			$data["output"] = $output;
			echo Modules::run("template/admin", $data);
			 
	}
	//admingallery
	function admingallery($school_id=0)
	{
		  if($this->session->userdata("user_role") == "school")
					{
						redirect("admin/school/today_report");
					}
					//echo $this->uri->segment(4);die;
					
		$schol_rs = $this->db->query("select * from schools where school_id='".$school_id."'");		
		$rsc_row =	$schol_rs->row();	
		  
		$image_crud = new image_CRUD();
	
		$image_crud->set_primary_key_field('id');
		$image_crud->set_url_field('url');
		$image_crud->set_title_field('title');
		$image_crud->set_table('purchase_bills_old'); 
		$image_crud->set_ordering_field('dateposted');
		$image_crud->unset_delete();
		$image_crud->unset_upload();
		$image_crud->set_image_path('assets/uploads/gallery'); 
		
		//$image_crud->set_relation_field(12);
		$output = $image_crud->render(); 
		 //$this->_example_output($output);
			$data["module"] = "gallery";
			$data["view_file"] = "manage";
			$output->title = $rsc_row->name. " Purchase  Bills";
			
			//echo "<pre>";			print_r($output);die;
			$data["output"] = $output;
			echo Modules::run("template/admin", $data);
			 
	}
	
	//
	function sessiongallery($school_id=0,$datechoosen='')
	{
		  if($this->session->userdata("user_role") == "school")
					{
						redirect("admin/school/today_report");
					}
					//echo $this->uri->segment(4);die;
					
		
		 
		 if($datechoosen==null)
				$datechoosen = date('Ymd');
			
		  $datechoosen = date('Y-m-d',strtotime($datechoosen));
		  
		//  echo "select * from food_pics where school_id='$school_id' and date_format('%Y-%m-%d',uploaded_date)='$datechoosen'";
		 
		 $rs = $this->db->query("select * from food_pics where school_id='$school_id' and food_session_id='1' and  uploaded_date like  '".$datechoosen."%'");
		 $data['pics_data1'] = $rs;	
		 $rs = $this->db->query("select * from food_pics where school_id='$school_id' and food_session_id='2' and  uploaded_date like  '".$datechoosen."%'");
		 $data['pics_data2'] = $rs;		

		 $rs = $this->db->query("select * from food_pics where school_id='$school_id' and food_session_id='3' and  uploaded_date like  '".$datechoosen."%'");
		 $data['pics_data3'] = $rs;	

		 $rs = $this->db->query("select * from food_pics where school_id='$school_id' and food_session_id='4' and  uploaded_date like  '".$datechoosen."%'");
		 $data['pics_data4'] = $rs;
		 
		  $rs = $this->db->query("select * from schools where school_id='$school_id'");
		 $data['school_info'] = $rs->row();
		 
		 $data['date_choosen'] = date('d-m-Y',strtotime($datechoosen));
		 $data["module"] = "gallery";
		 $data["view_file"] = "showpic_gallery";
			//$output->title = $rsc_row->name. " Purchase  Bills";
			
			//echo "<pre>";			print_r($output);die;
			$data["output"] = $output;
			echo Modules::run("template/admin", $data);
			 
	}
	 
}