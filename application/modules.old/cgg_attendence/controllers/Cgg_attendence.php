<?php 
 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
set_time_limit(0);
 date_default_timezone_set('Asia/Kolkata');
class Cgg_attendence extends MX_Controller {

    function __construct() {
        parent::__construct();
		 $this->load->config("config");
		 $this->load->config("email");
 
	}

   	public function index()
	{
		$date = $_GET['date'];
		if($date=="")
				$date = date('Y-m-d');
			
		$this->db->query("insert into school_attendence(school_id,entry_date,school_code)  select school_id,? entry_date,school_code from schools where is_school=1 and  school_id not in (select school_id   from school_attendence where entry_date=?)",array($date,$date));		
		 
		 
		 $attlist_rs = $this->db->query(" select *   from school_attendence where entry_date=? ",array($date));	
		$attendence_ids = array();		
		foreach($attlist_rs->result() as $attrow)
		{
			$attendence_ids[$attrow->school_id] = $attrow->attendence_id;
		}
		 
		 $start_time  = $this->db->query("select CURRENT_TIMESTAMP as timenow")->row()->timenow;
		 
		 $data_captured = $this->CallAPI($date);
		 $school_attendence = array();
		 
		 //initiate school attendence array with all schools 
		 $scrs= $this->db->query("select * from schools where is_school='1'");
		 foreach($scrs->result() as $scrow)
			{
				 $school_attendence[$scrow->school_code]['school_name']  =$scrow->name;
				 $school_attendence[$scrow->school_code]['school_id']  =$scrow->school_id;
				 $school_attendence[$scrow->school_code]['cat_1_total']  = 0;
				 $school_attendence[$scrow->school_code]['cat_2_total']  = 0;
				 $school_attendence[$scrow->school_code]['cat_3_total']  = 0;
				 $school_attendence[$scrow->school_code]['total']  = 0;
				 $school_attendence[$scrow->school_code]['classes']  = array();
			}
		 
		 
		 $total = 0 ;
		 $cat_1_classes = array(1,2,3,4,5,6,7);
		 $cat_2_classes = array(8,9,10);
		 $cat_3_classes = array(11);
		 foreach($data_captured as $key=>$attObj)
		 {
			$total =  intval($school_attendence[$attObj->hostelId]['total']);
			$total =$total + $attObj->attendanceCount;
			$school_attendence[$attObj->hostelId]['total'] = $total ;
			$school_attendence[$attObj->hostelId]['classes'][$attObj->presClass] = $attObj->attendanceCount ; 
			if(in_array($attObj->presClass,$cat_1_classes)){
							$cat_1_total =  intval($school_attendence[$attObj->hostelId]['cat_1_total']);
							$school_attendence[$attObj->hostelId]['cat_1_total']= $cat_1_total + $attObj->attendanceCount;
			}else if(in_array($attObj->presClass,$cat_2_classes)){
							$cat_2_total =  intval($school_attendence[$attObj->hostelId]['cat_2_total']);
							$school_attendence[$attObj->hostelId]['cat_2_total']= $cat_2_total + $attObj->attendanceCount;
			}else if( $attObj->presClass>10){
							$cat_3_total =  intval($school_attendence[$attObj->hostelId]['cat_3_total']);
							$school_attendence[$attObj->hostelId]['cat_3_total']= $cat_3_total + $attObj->attendanceCount;
			}
			
			
		 }
		 //print_a($school_attendence,1);die;
		 //update it to data base 
		 $mail_content = "<div>Hi , <br><br>Attendence Date : <table> <tr><td> ";
		 foreach($school_attendence as $school_code=>$attObj)
		 {
			 
				$total  = 	intval($attObj['total']);
				$classes  = $attObj['classes'];
				$school_id  = intval($attObj['school_id']);
				$cat_1_total  = intval($attObj['cat_1_total']);
				$cat_2_total  = intval($attObj['cat_2_total']);
				$cat_3_total  = intval($attObj['cat_3_total']);
				$update_data = array('school_id'=>$school_id,'school_code'=>$school_code,'entry_date'=>$date,'present_count'=>$total,'cat1_attendence'=>$cat_1_total,'cat2_attendence'=>$cat_2_total,'cat3_attendence'=>$cat_3_total,'classwise_data'=>serialize($classes));
			 
				$this->db->where('attendence_id',  $attendence_ids[$school_id]); 
				$this->db->update('school_attendence', 	$update_data); 
				// echo $this->db->last_query(),"<br>"; 
				
		 }
		 //update present count again 
		 $this->db->query("update school_attendence set present_count =cat1_attendence+cat2_attendence+cat3_attendence+cat1_guest_attendence+cat2_guest_attendence+cat3_guest_attendence  where entry_date=?",array($date));
		 
		 
		 $end_time = $this->db->query("select CURRENT_TIMESTAMP as timenow")->row()->timenow;
		 $timings = array('start_time'=>$start_time,'end_time'=>$end_time ) ;
		 
		 
		 $indian_date = $this->db->query("SELECT  DATE_FORMAT(?,'%d/%m/%Y') AS niceDate",array($date))->row()->niceDate;
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
					<th style=' $th_style'>Total</th>					
					<th style=' $th_style'>Classwise</th>	
					<th style=' $th_style'>School Code</th>
				 
				</tr> ";
				$i =1;
		foreach($school_attendence as $school_code=>$attObj)
		 {
			$classwise_data =  $attObj['classes'] ;
			$class_text = "";
			foreach($classwise_data as $class_no=>$class_count)
			{
				$class_text .= "Class $class_no:  $class_count <br>";
				
				
			}
			if($i%2==0)
					$trbgcolr='#FFDCAD';
			else 
					$trbgcolr = '#D1FDBA';
			 $html_text .= "<tr style='background-color:$trbgcolr'>
					<td style=' $td_style' valign='top' >".$i."</td>
					<td style=' $td_style' valign='top'>".$school_code."</td>
					<td style=' $td_style' valign='top'>".$attObj['school_name']."</td>
					<td style=' $td_style' valign='top'>". $indian_date."</td>
					<td style=' $td_style' valign='top'>".$attObj['cat_1_total']."</td>
					<td style=' $td_style' valign='top'>".$attObj['cat_2_total']."</td>
					<td style=' $td_style' valign='top'>".$attObj['cat_3_total']."</td>
					<td style=' $td_style' valign='top'>".$attObj['total']."</td>
					<td style=' $td_style' valign='top'>".$class_text ."</td>	
					<td style=' $td_style' valign='top'>".$school_code."</td>						
					 
				</tr>";
				$i++;
		 }
		   $html_text .= "</table>";
		  
		  $data =array(
		  'entry_date'=>$date,
		  'start_time'=>$timings['start_time'],
		  'end_time'=>$timings['end_time'],
		  'webservice_url_connected'=>'1',
		  'log_text'=>$html_text,
		  'log_ip'=>$this->input->ip_address(),
		  'user_id'=>'---',
		  'raw_xml'=>$raw_xml 
		  
		  
		  );
		  
		  $this->db->insert('attendence_log', $data);
		  $insert_id = $this->db->insert_id();
		  $inserted_dates = $this->db->query("select 
													DATE_FORMAT(entry_date, '%d/%M/%Y') as entry_date,
													DATE_FORMAT(start_time, '%d/%M/%Y %H:%i:%s') as start_time,
													DATE_FORMAT(end_time, '%d/%M/%Y %H:%i:%s') as end_time,
													UNIX_TIMESTAMP(end_time) - UNIX_TIMESTAMP(start_time) as difftime 
															from attendence_log where attendence_log_id=?",array($insert_id))->row();
		  
		  
		  
		   $timings_text = "<table style='font-family: Verdana, Geneva, sans-serif;font-size: 11px;'><tr  ><tr><th colspan='2' align='left'>Attendence Capture</th></tr>
					<tr><td width='50%'>Attendence Date</td><td>   ".$inserted_dates->entry_date."</td></tr> 
					<tr><td>Start Time</td><td>   ".$inserted_dates->start_time."</td></tr> 
					<tr><td>End Time</td><td>   ".$inserted_dates->end_time."</td></tr> 
					<tr><td>Duration </td><td>   ".$inserted_dates->difftime." Seconds</td></tr> 
					<tr><td>IP Address</td><td>   ".$this->input->ip_address()."</td></tr> 
					 
				</table>";
		$body = $timings_text.$html_text;		
		$cc_array = array("innovebtechnologies@gmail.com");
		$to_address = array("webdeveloper.rk@gmail.com");
			 
		$this->send_email("TWHOSTELS Attendence Captured for ". $indian_date,$body,$to_address ,$cc_array );
			 
		   echo    $timings_text.$html_text;
		 //echo "School_count ",count($school_attendence);
		//  print_a($school_attendence);				 
	}
	
	public function CallAPI($date)
	{
		//echo 'Curl: ', function_exists('curl_version') ? 'Enabled' : 'Disabled'; 
		
			$method ="POST";
		$url =  $this->config->item("webservice_url");
		$username =  $this->config->item("webservice_username");
		$password =  $this->config->item("webservice_password");
		$header_params = array(
								'attendancedate: '.$date,
								'department:'.$username,
								'wspassword:'.$password
								);
		
		$curl = curl_init();
	
		switch ($method)
		{
			case "POST":
				curl_setopt($curl, CURLOPT_POST, 1);

				 
				 	curl_setopt($curl, CURLOPT_POSTFIELDS, array('a123'=>'12345'));
				break;
			case "PUT":
				curl_setopt($curl, CURLOPT_PUT, 1);
				break;
			default:
				if ($data)
					$url = sprintf("%s?%s", $url, http_build_query($data));
		}

		curl_setopt($curl, CURLOPT_HTTPHEADER,$header_params);

		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

		$result = curl_exec($curl); 
		//echo $result ;die;
		curl_close($curl); 
		return json_decode($result);
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