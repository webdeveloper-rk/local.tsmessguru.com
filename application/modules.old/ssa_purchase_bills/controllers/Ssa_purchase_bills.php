<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
set_time_limit(0);
require_once "classes/awss3/vendor/autoload.php";
use  Aws\S3\S3Client;
use  League\Flysystem\AwsS3v3\AwsS3Adapter;
use  League\Flysystem\Filesystem;


 date_default_timezone_set('Asia/Kolkata');
class Ssa_purchase_bills extends MX_Controller {

    function __construct() {
        parent::__construct();
		if($this->uri->segment(2) !="login") { 
					 Modules::run("security/is_admin");		 
					
		}
			$this->load->helper('url');  
			$this->load->config("config.php");

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

   	public function index(){
		
		
		$months_list = array("01"=>"January","02"=>"February","03"=>"March","04"=>"April","05"=>"May",
									"06"=>"June","07"=>"July","08"=>"August","09"=>"September","10"=>"October","11"=>"November","12"=>"December");	
		 $list_types_rset = $this->db->query("select * from purchase_list_items where status='1'");
		$list_type_numbers=array(0);
		foreach($list_types_rset->result() as $row){
			$list_type_numbers[] = $row->purchase_list_item_id;
		}
		
	$this->form_validation->set_rules('month', 'Month ', 'required|numeric|greater_than[0]|less_than_equal_to[12]');              
		$this->form_validation->set_rules('year', 'Year ', 'required|numeric|greater_than_equal_to[2017]|less_than_equal_to['.date('Y') .']');  

	 
		$this->form_validation->set_rules('bill_type', 'Bill Type ', 'required|numeric|in_list['.implode(',',$list_type_numbers).']');  
		 $display_result = 0;
		 $display_result = 1;
		 if($this->form_validation->run() == true  )
		{ 
			$month = intval($this->input->post('month'));
			$year = intval($this->input->post('year'));
			$bill_type = intval($this->input->post('bill_type'));
			 
		}
		else
		{
			$month = intval(date('m'));
			$year = date('Y');
			$bill_type = 0;
		}
		if($month <10)
				$month ="0".$month;
		
		$condition = '';
		$bill_type = intval($bill_type);
		if($bill_type !=0)
		{
			$condition = " and pb.purchase_list_item_id='$bill_type' " ;
		}
		 
		 $month_start_date = "$year-$month-01";
		 $bills_rset = $this->db->query("select pb.*,pbli.item_name,date_format(uploaded_time,'%d-%M-%Y %r') as uploaded_time from purchase_bills  pb inner join purchase_list_items  pbli on pb.purchase_list_item_id  = pbli.purchase_list_item_id where school_id=? and date(upload_date) between ? and last_day(?)  $condition order by uploaded_time desc",array( $this->session->userdata("school_id"),$month_start_date,$month_start_date));
		// echo $this->db->last_query();
		 
		$data["months_list"] = $months_list;
		$data["month"] = $month;
		$data["year"] = $year;
		$data["bill_type"] = $bill_type;
		$data["bills_rset"] = $bills_rset;
		$data["display_result"] = $display_result;
		$data["bill_types_rs"] = $list_types_rset;
		$data["module"] = "ssa_purchase_bills";
        $data["view_file"] = "purchasebills_list";
        echo Modules::run("template/admin", $data);
	}
   	public function form()
	{
		$list_types_rset = $this->db->query("select * from purchase_list_items where status='1'");
		$list_type_numbers=array();
		foreach($list_types_rset->result() as $row){
			$list_type_numbers[] = $row->purchase_list_item_id;
		}
		
		$this->form_validation->set_rules('bill_type', 'Bill Type ', 'required|numeric|in_list['.implode(',',$list_type_numbers).']');    
		$this->form_validation->set_rules('bill_month', 'Bill Year and month ', 'required');    
		
		if (empty($_FILES['purchase_image']['name']))
		{
				$this->form_validation->set_rules('purchase_image', 'Purchase Bill', 'required');
		}
		 
		if($this->form_validation->run() == true  )
		{ 
			$bill_type = intval($this->input->post('bill_type')); 
			 $upload_date =   $this->input->post('bill_month'); 
			$extention = strtolower(pathinfo($_FILES['purchase_image']['name'],PATHINFO_EXTENSION ));
			if(!in_array($extention,$this->config->item('allowed_types')))
			{
					$this->session->set_flashdata('message', '<div class="alert alert-danger">Invalid Purchase bill type. Only JPG, PNG types allowed.</div>');
					redirect('ssa_purchase_bills/form');
			}
			else if(!getimagesize($_FILES['purchase_image']['tmp_name'])){
						$this->session->set_flashdata('message', '<div class="alert alert-danger">Looks like not a image. Only JPG, PNG types allowed.</div>');
						redirect('ssa_purchase_bills/form');
					}
			else
			{
				 
					//start upload images 
					$uploaded_file = $this->upload_image();
					if($uploaded_file !='')
					{
							//insert entry to database 
							 $this->db->set('uploaded_time', 'NOW()', FALSE);
							$insert_data = array('school_id'=>$this->session->userdata("school_id"),
													'purchase_list_item_id'=>$bill_type,
													'upload_date'=>$upload_date,													
													'uploaded_to'=>'spaces',													
													'pic_path'=>$uploaded_file,													
													'uploaded_by'=>$this->session->userdata("user_id"),
													'ip_address'=>$this->input->ip_address()
												);
							$this->db->insert('purchase_bills',$insert_data);
								//print_a($_POST);echo $this->db->last_query();die;							
							
							$url = $this->config->item('end_url').$uploaded_file; 
							$this->session->set_flashdata('message', '<div class="alert alert-success">Uploaded Successfully.</div>');
							redirect('ssa_purchase_bills/form');
					}
					else
					{
						
						$this->session->set_flashdata('message', '<div class="alert alert-danger">Failed to upload bill. please try again.</div>');
						redirect('ssa_purchase_bills/form');
					}
			}
			
		}
		  
		
		$data["bill_types_rs"] = $list_types_rset;
		$data["module"] = "ssa_purchase_bills";
        $data["view_file"] = "purchasebills_form";
        echo Modules::run("template/admin", $data);
	}
	
	 private function upload_image()
	 {
		 $uploaded_url  = '';
				$client = S3Client::factory([
				'credentials' => [
									'key' =>  $this->config->item('key'),
									'secret' =>  $this->config->item('secret')
									],

				'region' => $this->config->item('region'), // Region you selected on time of space creation
				'endpoint' => $this->config->item('space_url'),
				'version' => 'latest',
				'scheme'  => 'http'
				]);

				$adapter = new AwsS3Adapter($client,$this->config->item('space_name'));
				$filesystem = new Filesystem($adapter);

				$extention = strtolower(pathinfo($_FILES['purchase_image']['name'],PATHINFO_EXTENSION ));
				$file_name  =    uniqid().'.'.$extention;
				$file_path  =   $this->config->item('purchase_bills_folder').$file_name ;
				$flag = $filesystem->put($file_path, file_get_contents($_FILES['purchase_image']['tmp_name']),['visibility' => 'public']   );
				if($flag)
				{
					$uploaded_url =  $file_name;
				}
				 
				return $uploaded_url ;
	 }

	 public function oldbills(){
		
		$months_list = array("01"=>"January","02"=>"February","03"=>"March","04"=>"April","05"=>"May",
									"06"=>"June","07"=>"July","08"=>"August","09"=>"September","10"=>"October","11"=>"November","12"=>"December");	
		 
		
		 $display_result = 0;
		 $display_result = 1;
		$this->form_validation->set_rules('month', 'Month ', 'required|numeric|greater_than[0]|less_than_equal_to[12]');              
		$this->form_validation->set_rules('year', 'Year ', 'required|numeric|greater_than_equal_to[2017]|less_than_equal_to['.date('Y') .']');  

		 if($this->form_validation->run() == true  )
		{ 
			$month = intval($this->input->post('month'));
			$year = intval($this->input->post('year'));	
			 
		}
		else
		{
			$month = intval(date('m'));
			$year = date('Y');
			$bill_type = 0;
		}
		if($month <10)
				$month ="0".$month;
		
		$condition = '';
		$bill_type = intval($bill_type);
		if($bill_type !=0)
		{
			$condition = " and pb.purchase_list_item_id='$bill_type' " ;
		}
		 
		 $month_start_date = "$year-$month-01";
		 $bills_rset = $this->db->query("select   *,date_format(dateposted,'%d-%m-%Y') as dateposted from purchase_bills_old  where school_id=? and date(dateposted) between ? and last_day(?)  $condition order by dateposted desc",array( $this->session->userdata("school_id"),$month_start_date,$month_start_date));
		 
		 
		$data["months_list"] = $months_list;
		$data["month"] = $month;
		$data["year"] = $year;
		$data["bill_type"] = $bill_type;
		$data["bills_rset"] = $bills_rset;
		$data["display_result"] = $display_result;
		$data["bill_types_rs"] = $list_types_rset;
		$data["module"] = "ssa_purchase_bills";
        $data["view_file"] = "old_purchasebills_list";
        echo Modules::run("template/admin", $data);
	}
}
