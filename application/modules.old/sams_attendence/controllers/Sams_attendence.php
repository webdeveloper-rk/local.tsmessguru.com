<?php 
 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
set_time_limit(0);
 date_default_timezone_set('Asia/Kolkata');
class Sams_attendence extends MX_Controller {

    function __construct() {
        parent::__construct();
		if($this->uri->segment(2) !="login") { 
					 Modules::run("security/is_admin");		 
					
		}
		$this->load->helper('url');  
		 $this->load->config("config.php");
		 $this->load->model('sams_model');
		  $this->load->library('webservices_lib');
	}

   	public function index()
	{
		$data['output_text'] = '';
		$ip_address = $this->input->ip_address();
		$updated_by = '';
		 
			if ($this->session->userdata("is_loggedin") != TRUE || $this->session->userdata("user_id") == "" ) {
				redirect("admin/login");
				die;
			}

			if($this->session->userdata("user_role") != "subadmin")
			{
				redirect("admin/login");
				die;
			}
				  
		$this->form_validation->set_rules('day', 'Day', 'required|numeric');              
		$this->form_validation->set_rules('month', 'Month', 'required|numeric');              
		$this->form_validation->set_rules('year', 'Year', 'required|numeric'); 
	 
		 
		if($this->form_validation->run() == true  )
		{
			
			$day = $this->input->post('day');
			$month = $this->input->post('month');
			$year = $this->input->post('year');
			$date_choosen = $year."-".$month."-".$day;
			$date_indian = $day."/".$month."/".$year;
			$webservice_date =$month."-".$day."-".$year;
			$url_accessed = $this->check_url_access();
			
			if($url_accessed==true)
			{
				$start_time  = $this->db->query("select CURRENT_TIMESTAMP as timenow")->row()->timenow;
				$webservice_url_connected = true;
				//check valid date 
				$datecheck_sql = "select isnull(date('$date_choosen')) as is_not_valid" ;
				$is_not_valid= $this->db->query($datecheck_sql)->row()->is_not_valid;
				if($is_not_valid==1)
				{
					$this->session->set_flashdata('message', '<div class="alert alert-danger">'.$date_indian.' is Invalid date.</div>');
					redirect('sams_attendence');
					die;
				}
				//Check if date is future date 
				$future_date_check_sql = "select  date('$date_choosen') > CURRENT_DATE()  as is_future_date " ;
				$is_future_date= $this->db->query($future_date_check_sql)->row()->is_future_date;
				if($is_future_date==1)
				{
					$this->session->set_flashdata('message', '<div class="alert alert-danger">'.$date_indian.' is Future date.Future dates is not allowed to update the attendence.</div>');
					redirect('sams_attendence');
					die;
				}
				
				//Conditions checking over , Update it to database 
				
				$this->insert_dates($date_choosen); 
				
				$start_time  = $this->db->query("select CURRENT_TIMESTAMP as timenow")->row()->timenow;
				$attendene_report = $this->get_sams_data($webservice_date );				
				$response = $this->sams_model->update_attendence($attendene_report,$date_choosen,$start_time); 
				$data['output_text'] = $response;
					 
			}
			else{
				//url not accessed and already logged in database. no need to do any thing	
				$data['output_text'] = '<h1 style="color:#FF0000;">Webservice URL Not connected</h1>';
			}
		}
		 
		
		 
		
		
		$data["module"] = "sams_attendence";
        $data["view_file"] = "attendence_update_form";
        echo Modules::run("template/admin", $data);
	}
	
	public function system_update() 
	{
		$date_choosen = date('Y-m-d');
		$webservice_date = date('m-d-Y');
		$ip_address = $this->input->ip_address();
		$updated_by = 'System';
		
		$this->insert_dates($date_choosen); 
		if(in_array($ip_address ,$this->config->item('system_ip')))
		{
		
			$check_url_accesed = $this->check_url_access();
			if($check_url_accesed == true)
			{
				$start_time  = $this->db->query("select CURRENT_TIMESTAMP as timenow")->row()->timenow;
				$attendene_report = $this->get_sams_data($webservice_date );				
				$response = $this->sams_model->update_attendence($attendene_report,$date_choosen,$start_time); 
				echo $response;
			}
		
		}
		
	}
	private function check_url_access()
	{
		$webservice_url = $this->config->item('webservice_url');   
		    
			$file_headers = @get_headers($webservice_url);
			if(!$file_headers || $file_headers[0] == 'HTTP/1.1 404 Not Found') {
					$webservice_url_connected = false;
					
					$att_data = array('start_time'=>$start_time, 
										'log_ip'=>$this->input->ip_address() ,
										'webservice_url_connected'=>false,
										'entry_date'=>$db_date,
										'log_text'=>"<h1>Webservice URL Not Connected</h1>"
										);
					
					$this->db->set('end_time', 'NOW()', FALSE);
					$this->db->insert('attendence_log',$att_data);	
				   echo "<h1>Webservice URL Not Connected</h1> " ;
				   return false;
			} else{
					return true;
			}
	}
	
	 private function get_sams_data($date )
	 {
		 
		
		$webservice_url_connected = true;
		$class_actual_names = array(); 
		
		 $cat1_list = array('class3','class4','class5','class6','class7');
		 $cat2_list = array('class8','class9','class10');
		 $cat3_list = array('mpc1year','mpc2year','bipc1year','bipc2year','cec1year','cec2year','hec1year','hec2year','ct1year','ct2year','aandt1year','aandt1year','aandt2year','mec1year','mec2year','mlt1year','mlt2year','cga1year','cga2year','emcetiitmpc1year','emcetiitmpc2year','emcetbipc1year','emcetbipc2year','iitmpc2year','iitmpc1year');
		 
		 
		$school_codes = array();
		$school_rs  =  $this->db->query("select * from schools where is_school=1");
		foreach($school_rs->result() as $row){
			$school_codes[$row->sam_school_id] = $row->school_id;
		}
		
		$attendene_report = array();
		$webservice_url = $this->config->item('webservice_url');
		$obj = new HasService(array(),$webservice_url);
		$requestObj = new getStateWiseAttendance($date);
		$responseObj = $obj->getStateWiseAttendance($requestObj);
		$responseReturned = $responseObj->getReturn();
		
		  
		$count = 0 ;
		$classes_list = array();
		$school_list = array();
		$dst_list = array();
		
		 
		foreach($responseReturned as $school_att_obj)
		{ 
			$class_name = $school_att_obj->getClassname();
			$class_actual_names[$this->replace($class_name)] = $class_name;
			$class_name = $this->replace($class_name);
			$classes_list[$class_name] =  $school_att_obj->getClassname();
			$dst_list[] =$school_att_obj->getDistrict_name();
			$school_list[$school_att_obj->getSchoolid()] =  array('district'=>$school_att_obj->getDistrict_name(),'sams_school_id'=>$school_att_obj->getSchoolid(),'school_code'=>$this->getschoolcodereplace($school_att_obj->getSchoolname()), 'school_name'=>$this->getschoolcodereplacename2($school_att_obj->getSchoolname()),);
			$attendene_report[$school_att_obj->getSchoolid()][$class_name] = $school_att_obj->getPresencecount();				 
			
			
			$school_id  = $school_att_obj->getSchoolid();
			
			if(!isset($attendene_report[$school_id]['total']))
				$attendene_report[$school_id]['total'] = 0;
			
			if(!isset($attendene_report[$school_id]['cat1_count']))
				$attendene_report[$school_id]['cat1_count'] = 0;
			
			if(!isset($attendene_report[$school_id]['cat2_count']))
				$attendene_report[$school_id]['cat2_count'] = 0;
			
			if(!isset($attendene_report[$school_id]['cat3_count']))
				$attendene_report[$school_id]['cat3_count'] = 0;
			
			$total_count  = $attendene_report[$school_id]['total'];
			$attendene_report[$school_id]['total'] = $total_count + $school_att_obj->getPresencecount(); 
			
			
			if(in_array($class_name, $cat1_list))
			{
					
				$cat1_total_count  = $attendene_report[$school_id]['cat1_count'];
				$attendene_report[$school_id]['cat1_count'] = $cat1_total_count + $school_att_obj->getPresencecount(); 
			}
			else if(in_array($class_name, $cat2_list))
			{
					
				$cat2_total_count  = $attendene_report[$school_id]['cat2_count'];
				$attendene_report[$school_id]['cat2_count'] = $cat2_total_count + $school_att_obj->getPresencecount(); 
			}
			else if(in_array($class_name, $cat3_list))
			{
					
				$cat3_total_count  = $attendene_report[$school_id]['cat3_count'];
				$attendene_report[$school_id]['cat3_count'] = $cat3_total_count + $school_att_obj->getPresencecount(); 
			}
			else {
				//echo $class_name,"<br>";
				$cat3_total_count  = $attendene_report[$school_id]['cat3_count'];
				$attendene_report[$school_id]['cat3_count'] = $cat3_total_count + $school_att_obj->getPresencecount(); 
			}
			
			$attendene_report[$school_id][$class_name] =  $school_att_obj->getPresencecount();  
		}
		$attendene_report['class_names'] =  $class_actual_names;
		return $attendene_report;
		
	 }
	 
	 
	 /***************************************************************/
	private  function replace($str)
	{
		//return  $str;
		$str = str_replace("II YEAR","2year",$str);
		$str = str_replace("I YEAR","1year",$str);
		$str = str_replace("BI.P.C","bipc",$str);
		$str = str_replace(" ","",$str);
		$str = str_replace(".","",$str);
		$str = str_replace("-","",$str);		
	 
		$str = strtolower($str);
		 
		
		return  $str;
	}
	private function getschoolcodereplace($str)
	{
		$ex = explode("-",$str);
		return trim($ex[0]);
	}
	private function getschoolcodereplacename2($str)
	{
		$ex = explode("-",$str);
		$nstr  = str_replace($ex[0],"",$str);
		return substr($nstr,1);
		
	}
	
	private function insert_dates($db_date)
	{
		$this->db->query("insert into school_attendence(school_id ,  entry_date)
								select school_id ,'$db_date' as entry_date from schools where school_id not in
												(select school_id  from school_attendence where entry_date='$db_date') ");
	}
	
	

	
}
