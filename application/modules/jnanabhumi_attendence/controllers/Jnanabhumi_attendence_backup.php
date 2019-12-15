<?php 
 require 'jnanabhumi/autoload.php';
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
set_time_limit(0);
 date_default_timezone_set('Asia/Kolkata');
class jnanabhumi_attendence extends MX_Controller {

    function __construct() {
        parent::__construct();
		 
		$this->load->helper('url');  
		 $this->load->config("config.php"); 
		  $this->load->library('webservices_lib');
		  $this->load->model('jnanabhumi_model');
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
			$dates_array= array('year'=>$year,'month'=>$month,'day'=>$day);
			$date_choosen = $year."-".$month."-".$day;
			$date_indian = $day."/".$month."/".$year;
			$webservice_date =$month."-".$day."-".$year;
			$url_accessed = $this->check_url_access();
			
			if($url_accessed==true)
			{
				$start_time  = $this->db->query("select CURRENT_TIMESTAMP as timenow")->row()->timenow;
				$webservice_url_connected = true;
				//check valid date 
				$datecheck_sql = "select isnull(date(?)) as is_not_valid" ;
				$is_not_valid= $this->db->query($datecheck_sql,array($date_choosen))->row()->is_not_valid;
				if($is_not_valid==1)
				{
					$this->session->set_flashdata('message', '<div class="alert alert-danger">'.$date_indian.' is Invalid date.</div>');
					redirect('jnanabhumi_attendence');
					die;
				}
				//Check if date is future date 
				$future_date_check_sql = "select  date('$date_choosen') > CURRENT_DATE()  as is_future_date " ;
				$is_future_date= $this->db->query($future_date_check_sql)->row()->is_future_date;
				if($is_future_date==1)
				{
					$this->session->set_flashdata('message', '<div class="alert alert-danger">'.$date_indian.' is Future date.Future dates is not allowed to update the attendence.</div>');
					redirect('jnanabhumi_attendence');
					die;
				}
				
				//Check if date is less than jan 9th 2019
				$future_date_check_sql = "select  date('$date_choosen')< '2019-01-09'    as is_less_date " ;
				$is_less_date= $this->db->query($future_date_check_sql)->row()->is_less_date;
				if($is_less_date==1)
				{
					$this->session->set_flashdata('message', '<div class="alert alert-danger">Before jan 9th 2019  dates  are  not allowed to update the jnanabhumi attendence.</div>');
					redirect('jnanabhumi_attendence');
					die;
				}
				
				
				//Conditions checking over , Update it to database 
				
				$this->insert_dates($date_choosen); 
				
				$start_time  = $this->db->query("select CURRENT_TIMESTAMP as timenow")->row()->timenow;
				$attendene_report = $this->get_jnanabhumi_data($dates_array);
				$this->update_school_attendence($attendene_report,$date_choosen);
				$html_table =  $this->generate_html_table($attendene_report,$date_indian);	
				$this->send_email("AP SOCIAL - JNANABHUMI ATTENDENCE CAPTURED FOR ".$date_indian,$html_table,array('webdeveloper.rk@gmail.com','annapurna.swr@gmail.com','vamshiyfs@gmail.com','innovebtechnologies@gmail.com'));
				$this->session->set_flashdata('message', '<div class="alert alert-success">'.$date_indian.' ATTENDENCE CAPTURED Successfully.</div>');
					redirect('jnanabhumi_attendence');
					 
			}
			else{
				//url not accessed and already logged in database. no need to do any thing	
				$data['output_text'] = '<h1 style="color:#FF0000;">Webservice URL Not connected</h1>';
			}
		}
		 
		
		 
		
		
		$data["module"] = "jnanabhumi_attendence";
        $data["view_file"] = "attendence_update_form";
        echo Modules::run("template/admin", $data);
	}
	
	public function system_update() 
	{
		$date_choosen = date('Y-m-d');
		$webservice_date = date('d-m-Y');
		 $date_indian = date('d-m-Y');
		$ip_address = $this->input->ip_address();
		$updated_by = 'System';
		
		$this->insert_dates($date_choosen); 
		//if(in_array($ip_address ,$this->config->item('system_ip')))
		{
		
			$check_url_accesed = $this->check_url_access();
			//if($check_url_accesed == true)
			{
				$start_time  = $this->db->query("select CURRENT_TIMESTAMP as timenow")->row()->timenow;
				$attendene_report = $this->get_jnanabhumi_data(array('year'=>date('Y'),'month'=>date('m'),'day'=>date('d')) );	
				$this->update_school_attendence($attendene_report,$date_choosen);
				$html_table =  $this->generate_html_table($attendene_report,$date_indian);	
				$this->send_email("AP SOCIAL - JNANABHUMI ATTENDENCE CAPTURED FOR ".$date_indian,$html_table,array('webdeveloper.rk@gmail.com','annapurna.swr@gmail.com','vamshiyfs@gmail.com','innovebtechnologies@gmail.com'));
				
				 
				echo $html_table;
			}
		
		}
		
	}
	private function check_url_access()
	{
		$webservice_url = $this->config->item('webservice_url');   
		    
			$file_headers = @get_headers($webservice_url);
			if(!$file_headers || $file_headers[0] == 'HTTP/1.1 404 Not Found') {
					$webservice_url_connected = false;
					
					$att_data = array('start_time'=>date('d-m-Y H:i:s'), 
										'log_ip'=>$this->input->ip_address() ,
										'webservice_url_connected'=>false,
										'entry_date'=>date('d-m-Y'),
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
	
 
	
	private function insert_dates($db_date)
	{
		$this->db->query("insert into school_attendence(school_id ,  entry_date)
								select school_id ,'$db_date' as entry_date from schools where school_id not in
												(select school_id  from school_attendence where entry_date='$db_date') ");
	}
	public function insert_attendence_today()
	{
		$this->insert_dates(array('year'=>date('y'),'month'=>date('m'),'day'=>date('d')));
	}
	
	

	private function get_jnanabhumi_data($dates_array )
	 {
		$attendene_report = array();		 
		$year = $dates_array['year'];
		$month = $dates_array['month'];
		$day = $dates_array['day']; 
		$date_to_update = $day."-".$month."-".$year; 
		$db_date_to_update = $year."-".$month."-".$day; 
		$schools_info = array();
		$schools_rs = $this->db->query("select sa.attendence_id,sc.school_id,sc.school_code,sc.u_dise_code,sc.name from schools sc inner join school_attendence sa on sa.school_id= sc.school_id where sc.school_code not like '%85000%' and  sa.entry_date = ?",array($db_date_to_update));
		foreach($schools_rs->result() as $school_data)
		{
			 
			$attendene_report[$school_data->u_dise_code]['date'] ='';
			$attendene_report[$school_data->u_dise_code]['school_code'] =$school_data->school_code;
			$attendene_report[$school_data->u_dise_code]['category_1'] =0;
			$attendene_report[$school_data->u_dise_code]['category_2'] =0;
			$attendene_report[$school_data->u_dise_code]['category_3'] =0;
			$attendene_report[$school_data->u_dise_code]['category_1_guest'] =0;
			$attendene_report[$school_data->u_dise_code]['category_2_guest'] =0;
			$attendene_report[$school_data->u_dise_code]['category_3_guest'] =0;
			$attendene_report[$school_data->u_dise_code]['u_dise_code'] =$school_data->u_dise_code;
			$attendene_report[$school_data->u_dise_code]['school_id'] = $school_data->school_id;
			$attendene_report[$school_data->u_dise_code]['school_name'] = $school_data->name;
			$attendene_report[$school_data->u_dise_code]['attendence_id'] = $school_data->attendence_id;
             
          
			
		}
		
		$webservice_url = $this->config->item('webservice_url');
		$obj = new HostelsAttendanceWebServiceService(array(),$webservice_url);
		
			if (is_soap_fault($obj)) {
					trigger_error("SOAP Fault: (faultcode: {$result->faultcode}, faultstring: {$result->faultstring})", E_USER_ERROR);
			}

		//echo $date_to_update;die;
		$requestObj = new getAnnapurnaAttList();
		$requestObj->setArg0($date_to_update); 
		$requestObj->setArg1($this->config->item('dept_code')); 
		$requestObj->setArg2($this->config->item('username')); 
		$requestObj->setArg3($this->config->item('password'));  
		$getAnnapurnaAttListResponse  = $obj->getAnnapurnaAttList($requestObj ); 
		$responseReturned = $getAnnapurnaAttListResponse->getReturn(); 
		$replace_content  ='<?xml version="1.0" encoding="UTF-8"?>';
		$responseReturned = str_replace($replace_content,"",$responseReturned ); 
		$responseReturned = "<data>".$responseReturned ."</data>";
		  
		$xml = simplexml_load_string($responseReturned );
		$json  = json_encode($xml);
		$data_parsed = json_decode($json, true);
		$school_attendence =  $data_parsed['school'];
		
		foreach($school_attendence as  $att_obj)
		{
			
			$attendene_report[$att_obj['school_code']]['category_1'] 		= $att_obj['category_1'];
			$attendene_report[$att_obj['school_code']]['category_2']  		= $att_obj['category_2'];
			$attendene_report[$att_obj['school_code']]['category_3'] 		= $att_obj['category_3'];
			 $attendene_report[$att_obj['school_code']]['category_1_guest'] 		= $att_obj['category_1_guest'];
			$attendene_report[$att_obj['school_code']]['category_2_guest']  		= $att_obj['category_2_guest'];
			$attendene_report[$att_obj['school_code']]['category_3_guest'] 		= $att_obj['category_3_guest'];
			 
		}
		 
		
		 return $attendene_report;
		  
		 
		
	 }
	 private function update_school_attendence($school_attendence,$date )
	 {
		 
		foreach($school_attendence as  $att_obj)
		{
			 
				$this->db->query("update school_attendence set cat1_attendence=?, cat2_attendence=? ,cat3_attendence=? ,cat1_guest_attendence=?,cat2_guest_attendence=?, cat3_guest_attendence=? where attendence_id=?   ",
						array($att_obj['category_1'],$att_obj['category_2'],$att_obj['category_3'],$att_obj['category_1_guest'],$att_obj['category_2_guest'],$att_obj['category_3_guest'],$att_obj['attendence_id']));
						// echo $this->db->last_query(); die;
			 
		}
		$totqry = "update school_attendence set  
						present_count =(cat1_attendence+cat2_attendence+cat3_attendence+cat1_guest_attendence+cat2_guest_attendence+cat3_guest_attendence)
			where  	entry_date='$date' ";
			
			$this->db->query($totqry);
			 //echo $this->db->last_query(); die;
			
		 
	 }
	 
	 private function generate_html_table($attendene_report,$date_indian){
			 $th_style= 'padding-top: 11px;padding-bottom: 11px;background-color: #4CAF50;color: white;border: 1px solid #ddd;text-align: left;padding: 8px;';
		 $td_style= 'padding:10px;';
		 $bg_color_1= 'background-color: #f2f2f2;';
		 $bg_color_2= 'background-color: #f2f2f2;';
		 
		
		 $html_text =  "<table style='font-family: Verdana, Geneva, sans-serif;font-size: 11px;'><tr class='sticky'>
					<th style=' $th_style'>SNO</th>
					<th style=' $th_style'>School Code</th>
					<th style=' $th_style'>School Name</th>
					<th style=' $th_style'>Date</th>
					
					<th style=' $th_style'>Category 1</th>
					<th style=' $th_style'>Category 2</th>
					<th style=' $th_style'>Category 3</th>
					<th style=' $th_style'>Category 1 Guest</th>
					<th style=' $th_style'>Category 2 Guest</th>
					<th style=' $th_style'>Category 3 Guest</th>
					<th style=' $th_style'>Total</th> 
					<th style=' $th_style'>School U DISE CODE</th>
					<th style=' $th_style'>School Code</th>
				 
				</tr> ";
				$i =1;
		foreach($attendene_report as $school_code=>$attObj)
		 {
			 
			
			$total = $attObj['category_1'] + $attObj['category_2'] +$attObj['category_3'] +$attObj['category_1_guest'] + $attObj['category_2_guest']+$attObj['category_3_guest'];
			if($i%2==0)
					$trbgcolr='#FFDCAD';
			else 
					$trbgcolr = '#D1FDBA';
			 $html_text .= "<tr style='background-color:$trbgcolr'>
					<td style=' $td_style' valign='top' >".$i."</td>
					<td style=' $td_style' valign='top'>".$attObj['school_code']."</td>
					<td style=' $td_style' valign='top'>".$attObj['school_name']."</td>
					<td style=' $td_style' valign='top'>". $date_indian."</td>
					<td style=' $td_style' valign='top'>".$attObj['category_1']."</td>
					<td style=' $td_style' valign='top'>".$attObj['category_2']."</td>
					<td style=' $td_style' valign='top'>".$attObj['category_3']."</td>
					<td style=' $td_style' valign='top'>".$attObj['category_1_guest']."</td>
					<td style=' $td_style' valign='top'>".$attObj['category_2_guest']."</td>
					<td style=' $td_style' valign='top'>".$attObj['category_3_guest']."</td>
					<td style=' $td_style' valign='top'>".$total."</td>
					<td style=' $td_style' valign='top'>".$attObj['u_dise_code'] ."</td>	
					<td style=' $td_style' valign='top'>".$attObj['school_code']."--".$school_code."</td>						
					 
				</tr>";
				$i++;
		 }
		   $html_text .= "</table> <p >Generated Time :".date('d-M-Y h:i:s A')."</p>";
		   
		   
		     $timings_text = "<table style='font-family: Verdana, Geneva, sans-serif;font-size: 11px;'><tr  ><tr><th colspan='2' align='left'>Attendence Capture</th></tr>
					<tr><td width='50%'>Attendence Date</td><td style='color:#FF0000;'> <b>   ".$date_indian."</b></td></tr> 
					<tr><td> Captured Time</td><td>   ".date('d-M-Y h:i:s A')."</td></tr>  
					<tr><td> Total Schools CAPTURED</td><td>   ".count($attendene_report)."</td></tr>  
					<tr><td>IP Address</td><td>   ".$this->input->ip_address()."</td></tr> 
					 
				</table>";
				
				$html_text =  $timings_text.$html_text;
		   return  $html_text ;
		  
	 }
		 private function send_email($subject,$body,$to_address=array(),$cc_array=array())
	{
		$url = "https://annapurna.in.net/sendemail";
		 $to_address_list =  '';
		 if(is_array($to_address))
			 $to_address_list = implode(",",$to_address);
		 else
			  $to_address_list =$to_address;
		  
			$curl = curl_init();
			curl_setopt($curl, CURLOPT_POST, 1); 
			curl_setopt($curl, CURLOPT_POSTFIELDS, array('body_text'=>$body,'to_address'=>$to_address_list,'subject'=>$subject));
			curl_setopt($curl, CURLOPT_URL, $url);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

			$result = curl_exec($curl); 
			// echo $result ;die;
			curl_close($curl); 
		  
		  
				 
	}
}
