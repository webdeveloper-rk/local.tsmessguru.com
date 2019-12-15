<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once "classes/awss3/vendor/autoload.php";use  Aws\S3\S3Client;use  League\Flysystem\AwsS3v3\AwsS3Adapter;use  League\Flysystem\Filesystem;
class Api extends MX_Controller {
 function __construct()    {        // Construct the parent class        parent::__construct();		$this->load->library("ci_jwt"); 		$this->load->config("purchase_bills/config");		$this->load->config("dietpics_config");        // Configure limits on our controller methods    }
 
	public function index()
	{			$this->login();	}	  public function login()    {        $school_code = trim($this->input->post('school_code'));		 $password =  trim($this->input->post('password'));				if($school_code == "" || $password =="")		{			send_json_result([                'status' =>  FALSE ,                'message' => 'Please fill all the fields '            ] ); // NOT_FOUND (404) being the HTTP response code		}				 		$rset = $this->db->get_where('student_logins', array('school_code' => $school_code,'password'=>md5($password),'status'=>'1'));		$users_found = $rset->num_rows();				  //$sql = $this->db->last_query();				if($users_found > 0 )		{			$user_info  = $rset->result(); 						$users['status'] = 'TRUE';			$users['message'] = "Successfully logged in";			$users['uid'] = $user_info[0]->uid;			$users['school_code'] = $user_info[0]->school_code;			$users['school_id'] = $user_info[0]->school_id; 			$users['user_name'] = $user_info[0]->name;			$users['role'] = $user_info[0]->utype;			 		}		 		 if ($users_found )        {						$token_generated = $this->ci_jwt->jwt_encode($users);			$response = array();			$response['status'] = TRUE; 						$response['token'] =  $token_generated; 			$response['message'] = "Logged in Successfully.";						            send_json_result($response ); // OK (200) being the HTTP response code        }        else        {            send_json_result([                'status' =>  FALSE ,                'message' => 'Invalid login details'            ] );          }				    }		public function upload()	{			$food_session  = $this->input->post("food_session");									 			switch($food_session )			{				case 'breakfast':								$food_session_id = 1;								break;				case 'lunch':								$food_session_id = 2;								break;				case 'snacks':								$food_session_id = 3;								break;				case 'dinner':								$food_session_id = 4;								break;				default:								$food_session_id = "";								break;			}						$this->check_allowed_to_upload($food_session_id);						$fooditem_title= $this->input->post("fooditem_title"); 			$location_latitude= $this->input->post("location_latitude");			$location_langitude= $this->input->post("location_langitude");			$location_addr = $this->input->post("location_addr"); 			$item_type  = $this->input->post("item_type"); 						$allowed_item_types= array("special","general");			if(!in_array($item_type,$allowed_item_types))			{				$item_type  = "general";			}						if($food_session_id  == "" || $fooditem_title =="" || $location_latitude =="" || $location_langitude == "" || $location_addr == "")			{				$data = array('status'=>false,'message' => 'Please fill the all required fields.' ); 		 				send_json_result($data );  			}			$extention = strtolower(pathinfo($_FILES['userfile']['name'],PATHINFO_EXTENSION ));			if(!in_array($extention,$this->config->item('allowed_types')))			{					 					$data = array('status'=>false,'message' => 'Invalid Picture type. Only JPG, PNG types allowed' ); 		 					send_json_result($data ); 			}			else if(!getimagesize($_FILES['userfile']['tmp_name'])){						 						$data = array('status'=>false,'message' => 'Looks like not a image. Only JPG, PNG types allowed.' ); 		 						send_json_result($data ); 					}			else			{				 			$token = $this->input->get_request_header('token', TRUE);			$token = isset($token ) ? $token : $this->input->get_request_header('Token', TRUE);;			$token_generated = $this->ci_jwt->jwt_decode($token);			//print_r($token_generated);die;			$uid = $token_generated->uid;			$school_id =  $token_generated->school_id;			$school_code = $token_generated->school_code;			//$mobile_number = $token_generated->mobile_number;						$limit_per_session = 30;			$picsql = "select * from food_pics_local where school_id=? and food_session_id=? and date_format(uploaded_date,'%Y-%m-%d') =CURRENT_DATE";			$picrs = $this->db->query($picsql,array($school_id,$food_session_id));			if($picrs->num_rows()>=$limit_per_session)			{				$data = array('status'=>false,'message' => ' Picture not uploaded, For every session only '.$limit_per_session. ' Pictures Allowed to upload.' ); 		 				send_json_result($data );  			}			$diet_pics_folder = $this->config->item("folder_path");						 			$month_year  =  strtolower(date('F_Y'));			$file_name = $food_session."_".date('d_F_Y_h_i_A')."_".uniqid().".jpg";						$uploaded_file_name = $this->upload_image_local($file_name,$school_code);			if ( $uploaded_file_name =='' ) {					$data = array('status'=>false,'message' => 'Failed to upload picture'); 			}			else {				 					$data = array('status'=>true,'message' => 'uploaded successfully.','filename' =>basename($uploaded_file_name),								'filepath'=> site_url().$uploaded_file_name ); 										$insert_data = array();					$insert_data['school_id']	=	$school_id;					$insert_data['school_code']	=	$school_code;					$insert_data['food_session_id']	=	$food_session_id;					$insert_data['fooditem_title']	=	$fooditem_title;					$insert_data['food_pic']	=	   $uploaded_file_name;										$insert_data['location_latitude']	=	$location_latitude;					$insert_data['location_langitude']	=	$location_langitude;					$insert_data['location_addr']		=	$location_addr;					$insert_data['uploaded_by']			=	$uid; 					$insert_data['item_type']			=	$item_type; 										$this->db->insert("food_pics_local",$insert_data);					//echo $this->db->last_query();					 			} 			send_json_result($data );  
	}	} 	public   function getlist()	{						$token = $this->input->get_request_header('token', TRUE);			$token = isset($token ) ? $token : $this->input->get_request_header('Token', TRUE);;			$token_generated = $this->ci_jwt->jwt_decode($token);			//print_r($token_generated);die;			$uid = $token_generated->uid;			$school_id = $token_generated->school_id;			$school_code = $token_generated->school_code;			//$mobile_number = $token_generated->mobile_number;						$posted_date = date("Y-m-d",strtotime($this->input->post('date')));			  if($posted_date == "" || $posted_date == "1970-01-01"){					 					$posted_date  = date('Y-m-d');			}				//echo $posted_date;			$is_before_date = $this->db->query("select ?<=? as is_before_date",array($posted_date,$this->config->item("food_pics_spaces_start_date")))->row()->is_before_date;			 			/*if($is_before_date)			{				$list = $this->get_local_pics_list($posted_date,$school_id);			}			else 			{				$list = $this->get_spaces_pics_list($posted_date,$school_id);			}*/							$list = $this->get_local_pics_list($posted_date,$school_id);				$data = array('status'=>true,'message' => 'Fetched','records'=>$list); 				send_json_result($data );  	}					/************************************************************************************					***************************************************************************************/	  function updatepassword()    {			$token = $this->input->get_request_header('token', TRUE);			$token = isset($token ) ? $token : $this->input->get_request_header('Token', TRUE);;		             $new_password = $this->input->post('new_password');		 $confirm_password =  $this->input->post('confirm_password');		if($new_password != $confirm_password ||$new_password =='' )		{			 send_json_result([                'status' => FALSE,                'message' => "Passwords didn't  matched. please try again."            ]);		}		else{			   			   $token_generated = $this->ci_jwt->jwt_decode($token); 			   // print_r($token_generated);die;			  $data = array('password' => md5($new_password));			  			  			  if(!isset($token_generated->uid))					$uid = 0;				else 					$uid = $token_generated->uid;			  $this->db->where('uid',  $uid);			  $this->db->update('student_logins', $data); 			//echo  $this->db->last_query();			  $response = array();			$response['status'] = TRUE; 									$response['message'] = "Password Updated Successfully.";						            send_json_result($response); // OK (200) being the HTTP response code								}				 				 		    }	public function check_access()	{						$token = $this->input->get_request_header('token', TRUE);			$token = isset($token ) ? $token : $this->input->get_request_header('Token', TRUE);;												$token_generated = $this->ci_jwt->jwt_decode($token);						$rs = $this->db->query("SELECT session_id														FROM food_pics_sessions														WHERE  DATE_FORMAT( NOW( ) ,  '%H:%i' ) 														BETWEEN start_hour														AND end_hour");						$session_timings = array("1"=>"breakfast","2"=>"lunch","3"=>"snacks","4"=>'dinner');									$session_flags = array("breakfast"=>false,"lunch"=>false,"snacks"=>false,'dinner'=>false);			foreach($rs->result() as $row)			{				$session_flags[$session_timings[$row->session_id]] = true;			}												$rs = $this->db->query("SELECT session_id,date_format(start_hour,'%l:%i %p') as start_hour,date_format(end_hour,'%l:%i %p') as end_hour FROM food_pics_sessions");						$session_msgs = array();			$session_timings_text = array();			foreach($rs->result() as $row)			{				$session_msgs[$session_timings[$row->session_id]] = ucfirst($session_timings[$row->session_id])." Entries allowed from ".$row->start_hour. " to  ".$row->end_hour." only.";;				$session_timings_text[$session_timings[$row->session_id]] = ucfirst($session_timings[$row->session_id])." Timings :  ".$row->start_hour. " to  ".$row->end_hour." .";;			}												$response['app_version'] = $this->config->item('app_version'); 									$response['force_update'] = $this->config->item('force_update'); 							$response['status'] = TRUE; 									$response['message'] = "";			$response['session_timings_text'] = $session_timings_text ;			$response['session_msgs'] = $session_msgs;			$response['sessions'] = $session_flags;						$response['item_names'] = $this->specials_list();			 send_json_result($response); 				}
	 /*********************************************************	 	 get speical Items List	 	 *********************************************************/	 	 	 public function  specials_list()	{						$token = $this->input->get_request_header('token', TRUE);			$token = isset($token ) ? $token : $this->input->get_request_header('Token', TRUE);;			$token_generated = $this->ci_jwt->jwt_decode($token);			//print_r($token_generated);die;			$uid = $token_generated->uid;			$school_id = $token_generated->school_id;			$school_code = $token_generated->school_code; 			$today_date = date('Y-m-d');									$list = array();			//Add General Items 			$trs= $this->db->query("select item_name from app_general_items where status='1'");				foreach($trs->result() as $trow)				{					$list[] =  $trow->item_name ;				}									//Add special Items   			$rs = $this->db->query("select telugu_name,item_name , it.item_id from items it inner join item_approvals ita 					on it.item_id = ita.item_id and ita.school_id=? and ita.entry_date=? ",array($school_id,$today_date));				foreach($rs->result() as $row)				{					$list[] =  $row->item_name ;				}				 return $list;	}			
	  private function upload_image_to_spaces($file_name,$school_code)	 {		 $file_name = strtolower($file_name);		 $uploaded_url  = '';				$client = S3Client::factory([				'credentials' => [									'key' =>  $this->config->item('key'),									'secret' =>  $this->config->item('secret')									],				'region' => $this->config->item('region'), // Region you selected on time of space creation				'endpoint' => $this->config->item('space_url'),				'version' => 'latest',				'scheme'  => 'http'				]);				$adapter = new AwsS3Adapter($client,$this->config->item('space_name'));				$filesystem = new Filesystem($adapter);				$extention = strtolower(pathinfo($_FILES['userfile']['name'],PATHINFO_EXTENSION ));				$month_folder = strtolower(date('F_Y'));								$diet_pics_folder = $this->config->item('diet_pics_folder');				$current_month_folder = $diet_pics_folder.$month_folder."/";				if(!$filesystem->has($current_month_folder))				{					$filesystem->createDir($current_month_folder);				}				$school_current_month_folder = $current_month_folder.$school_code."/";				if(!$filesystem->has($school_current_month_folder))				{					$filesystem->createDir($school_current_month_folder);				}				 							$file_path  =   $school_current_month_folder.$file_name ;				//echo $file_path;die;				$flag = $filesystem->put($file_path, file_get_contents($_FILES['userfile']['tmp_name']),['visibility' => 'public']   );				if($flag)				{					$uploaded_url =  $file_name;				}				 				return $uploaded_url ;	 }	 	 	 private function upload_image_local($file_name,$school_code)	 {		 $file_name = strtolower($file_name);		  				$extention = strtolower(pathinfo($_FILES['userfile']['name'],PATHINFO_EXTENSION ));				$month_folder = strtolower(date('F_Y'));								$diet_pics_folder = $this->config->item('folder_path');				$current_month_folder = $diet_pics_folder.$month_folder."/$school_code/";				 if (!file_exists($current_month_folder)) {							mkdir($current_month_folder, 0777, true);					}				 							$uploaded_url  =   $current_month_folder.$file_name ;				 				move_uploaded_file($_FILES['userfile']['tmp_name'], $uploaded_url );				 				return $uploaded_url ;	 }	 private function get_local_pics_list($posted_date,$school_id)	 {				 				$sql= "select *,date_format(uploaded_date,'%Y-%m-%d') as rdate from food_pics_local	where school_id=?  and date_format(uploaded_date,'%Y-%m-%d')=? order by uploaded_date desc";				$rs = $this->db->query($sql,array($school_id,$posted_date));				$list = array();								$list['breakfast'] = array();				$list['lunch'] = array();				$list['snacks'] = array();				$list['dinner'] = array(); 												$sessions = array(1=>"breakfast",2=>"lunch",3=>"snacks",4=>"dinner");				foreach($rs->result() as $row)				{					$session_name = $sessions[$row->food_session_id];					$list[$session_name][] = array('title'=>$row->fooditem_title, 'pic'=>site_url(). $row->food_pic);				}				return $list;	 }	 	  private function get_spaces_pics_list($posted_date,$school_id)	 {			 				$sql= "select *,date_format(uploaded_date,'%Y-%m-%d') as rdate from food_pics_spaces where school_id=? and date_format(uploaded_date,'%Y-%m-%d')=? order by uploaded_date desc";				$rs = $this->db->query($sql,array($school_id,$posted_date));				$list = array();								$list['breakfast'] = array();				$list['lunch'] = array();				$list['snacks'] = array();				$list['dinner'] = array(); 				$diet_pic_folder = $this->config->item("diet_pics_end_url");				$septest = $this->db->query("select (?<'2018-09-03') as bsep ",array($posted_date))->row()->bsep;				if($septest ==1)				{					$diet_pic_folder = $this->config->item("diet_pics_end_url_before_sep");				}				//echo $this->db->last_query();				//echo $diet_pic_folder ;die;				$sessions = array(1=>"breakfast",2=>"lunch",3=>"snacks",4=>"dinner");				foreach($rs->result() as $row)				{					$session_name = $sessions[$row->food_session_id];					$list[$session_name][] = array('title'=>$row->fooditem_title, 'pic'=>$diet_pic_folder.$row->food_pic);				}				return $list;	 }	 private function check_allowed_to_upload($food_session_id){		 		 $rs = $this->db->query("SELECT session_id														FROM food_pics_sessions														WHERE  session_id=? and  DATE_FORMAT( NOW( ) ,  '%H:%i' ) 														BETWEEN start_hour														AND end_hour",array($food_session_id));						 if( $rs->num_rows()==0){						$response['status'] = FALSE; 												$response['message'] = "Session Time over or not in time";						send_json_result($response); 			 }	 }	 	 /*********************************************************	 	 get speical Items List	 	 *********************************************************/	 	 	 public function  get_items_list()	{						/*$token = $this->input->get_request_header('token', TRUE);			$token = isset($token ) ? $token : $this->input->get_request_header('Token', TRUE);;			$token_generated = $this->ci_jwt->jwt_decode($token);			//print_r($token_generated);die;			$uid = $token_generated->uid;			$school_id = $token_generated->school_id;			$school_code = $token_generated->school_code; 			$today_date = date('Y-m-d');			*/						$list = array();			//Add General Items 			$trs= $this->db->query("select item_name,item_type  from app_items where status='1'");				foreach($trs->result() as $trow)				{					$list[$trow->item_type][] =  $trow->item_name ;				}			$response['status'] = TRUE; 									$response['message'] = "fetched data successfully";			$response['special'] = $list['special'];			$response['general'] = $list['general']; 						send_json_result($response); 	}			public function itembalance($school_code='',$item_id=0)	{		$trs= $this->db->query("select item_name,closing_quantity   from  items it 									inner join balance_sheet bs on bs.item_id= it.item_id  and entry_date=CURRENT_DATE									inner join schools sc on sc.school_id=bs.school_id and sc.school_code=? and bs.item_id=?									",array($school_code,$item_id));		 	if($trs->num_rows()==0)			{				$response['status'] = false; 										$response['message'] = "No Records Found";			}			else{				$response['status'] = true; 										$response['message'] = "Fetched data successfully";				$item_data = $trs->row();				$response['item_name'] = $item_data->item_name;				$response['balance'] = $item_data->closing_quantity ;  			}				send_json_result($response); 				}	
}
