<?php
set_time_limit (0);
defined('BASEPATH') OR exit('No direct script access allowed');

class Attendence2 extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
		
		
		
		 
		$start_time  = $this->db->query("select CURRENT_TIMESTAMP as timenow")->row()->timenow;
		$webservice_url_connected = true;
		$class_actual_names = array();//kk
		libxml_disable_entity_loader(false); 
		
		 $this->load->library('webservices_lib');
		  
		 $cat1_list = array('class1','class2','class3','class4','class5','class6','class7');
		 $cat2_list = array('class8','class9','class10');
		 $cat3_list = array('mpc1year','mpc2year','bipc1year','bipc2year','cec1year','cec2year','hec1year','hec2year','ct1year','ct2year','aandt1year','aandt1year','aandt2year','mec1year','mec2year','mlt1year','mlt2year','cga1year','cga2year','emcetiitmpc1year','emcetiitmpc2year','emcetbipc1year','emcetbipc2year','iitmpc2year','iitmpc1year');
		 
		 
		 
		 $all_cat_list = array_merge($cat1_list,$cat2_list,$cat3_list);
		 $cat1_total = 0;
		 $cat2_total = 0;
		 $cat3_total = 0;
		 $date = '';
		 if(isset( $_GET['date'])){
			$date = $_GET['date'];
		 }
		if($date=="")
		{
			$date = date('m-d-Y');
		} 
		$date_split = explode("-",$date);
		
		
		$db_date  = $date_split[2]."-".$date_split[0]."-".$date_split[1];
		 
		 
		 $this->db->query("insert into school_attendence(school_id ,  entry_date)
								select school_id ,'$db_date' as entry_date from schools where school_id not in
												(select school_id  from school_attendence where entry_date='$db_date') ");
												
												
		$school_codes = array();
		$school_rs  =  $this->db->query("select * from schools");
		foreach($school_rs->result() as $row){
			$school_codes[$row->sam_school_id] = $row->school_id;
		}
		
		$attendene_report = array();
	 
		$webservice_url = 'http://202.65.156.34:9090/tgtwrs/services/HasService?wsdl'; //kk
		$obj = new HasService(array(),$webservice_url);
		
		    
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
				   return ;
			} 
			
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
		$queries_list = array();
		//print_a($classes_list);die;
		
		$already_entered = array();
	 
		$already_entered_rs = $this->db->query("select school_id,attendence_id from school_attendence where entry_date='$db_date'");
		foreach($already_entered_rs->result() as $exis_row){
			$already_entered[$exis_row->school_id] =  $exis_row->attendence_id;
		}
		////////////
		//school_attendence_list
		$attendence_list = array();
	 
		$attendence_list_rs = $this->db->query("select school_id from school_attendence_list where entry_date='$db_date' ");
		foreach($attendence_list_rs->result() as $exis_row){
			$attendence_list[] =  $exis_row->school_id;
		}
		//////////////////////////////////////////////////////////
		////////////////////////////////////////////////////////////////
		 
		 //echo count($attendene_report),"--";		 print_a($attendene_report,1);
		$akeys = array_keys($attendene_report);
		//echo $list_sids = implode(",",$akeys);
		
		foreach($attendene_report as $sam_id=>$details)
		 {
				 $total = $details['total'];
				 $cat1_attendence = $details['cat1_count'];
				 $cat2_attendence = $details['cat2_count'];
				 $cat3_attendence = $details['cat3_count'];
				 
			 
			 if(isset($school_codes[$sam_id])){
				 $school_id = $school_codes[$sam_id];
				 $attendence_id = $already_entered[$school_id];
				 
				$qryts = "update school_attendence set
																	present_count='$total', 
																	cat1_attendence ='$cat1_attendence',
																	cat2_attendence ='$cat2_attendence',
																	cat3_attendence ='$cat3_attendence' 																
															where  attendence_id='$attendence_id' ";
				  
				 $this->db->query($qryts);
				 
		 
		 } 
		} //foreach close 
		
		$raw_xml  = '';
		$end_time = $this->db->query("select CURRENT_TIMESTAMP as timenow")->row()->timenow;
		$this->generate_attendence_html_table($attendene_report,$db_date,
												$timings = array('start_time'=>$start_time,'end_time'=>$end_time ) ,
												$class_actual_names,
												$raw_xml 
												);
		 
	}
	function replace($str)
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
	function getschoolcodereplace($str)
	{
		$ex = explode("-",$str);
		return trim($ex[0]);
	}
	function getschoolcodereplacename2($str)
	{
		$ex = explode("-",$str);
		$nstr  = str_replace($ex[0],"",$str);
		return substr($nstr,1);
		
	}
	function generate_attendence_html_table($attendene_report=array(),$entry_date,$timings = array(),$class_actual_names,$raw_xml ='')
	{
		 $indian_date = $this->db->query("SELECT  DATE_FORMAT('$entry_date','%d/%m/%Y') AS niceDate")->row()->niceDate;
		 $style_sheet = "
		 
		 <style>
					.atttable th {
									padding-top: 11px;
									padding-bottom: 11px;
									background-color: #4CAF50;
									color: white;
									border: 1px solid #ddd;
									text-align: left;
									padding: 8px;
					}
					.atttable tr:nth-child(even) {
									background-color: #f2f2f2;
					}

					.atttable { 	
									font-family: Verdana, Geneva, sans-serif;
									font-size: 11px;
					}
					.atttable td {
									padding:10px;
					}

					 
		</style> 
		 ";
		
		 $html_text = $style_sheet . "<table class='atttable '><tr class='sticky'>
					<th>SNO</th>
					<th>School Code</th>
					<th>School Name</th>
					<th>Date</th>
					
					<th>Category 1</th>
					<th>Category 2</th>
					<th>Category 3</th>
					<th>Total</th>
					<th>Classwise</th>	
					<th>School Code</th>					
					<th>Sam Id</th>
				</tr> ";
				
				 $i = 1;
		//foreach($attendene_report as $sam_id=>$details)
		$schools_data = $this->db->query("select * from schools where is_school=1 and school_code !='85000' order by school_code asc ");
		 $school_info = array();
		 foreach($schools_data->result() as $schobj)
		 {
			 $sam_id = $schobj->sam_school_id;
			
				
				$school_code = $schobj->school_code;
				$school_name = $schobj->name; 

			 
				$individual_text = '';
				$total = 0;				
				$cat1_attendence = 0;
				$cat2_attendence = 0;
				$cat3_attendence = 0;
				 
				 if(isset($attendene_report[$sam_id]))
				 {
					$details = $attendene_report[$sam_id];		
					$total = intval($details['total']);					
					$cat1_attendence = intval($details['cat1_count']);
					$cat2_attendence = intval($details['cat2_count']);
					$cat3_attendence = intval($details['cat3_count']);
					
					 unset($details['total']);
					 unset($details['cat1_count']);
					 unset($details['cat2_count']);
					 unset($details['cat3_count']);
				 
				 }else{
					 //$individual_text = "SAM ID : ".$sam_id ." Not Found in Webservice Result.";
				 }
				 
				 
				 
				
				 
				 if(count($details)>0) { 
						 foreach($details as $class_code=>$present_count)
						 {
							 $individual_text  .=  $class_actual_names[$class_code] . " : ". $present_count."<br>";
						 }
				 }
				 
				 $html_text .= "<tr>
					<td>".$i."</td>
					<td>".$school_code."</td>
					<td>".$school_name."</td>
					<td>". $indian_date."</td>
					<td>".$cat1_attendence."</td>
					<td>".$cat2_attendence."</td>
					<td>".$cat3_attendence."</td>
					<td>".$total."</td>
					<td>".$individual_text."</td>					
					<td>".$school_code."</td>					
					<td>".$sam_id."</td>
				</tr>";
				 
			 $i++;
		 }
		  $html_text .= "</table>";
		  
		  $data =array(
		  'entry_date'=>$entry_date,
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
													DATE_FORMAT(entry_date, '%m/%d/%Y') as entry_date,
													DATE_FORMAT(start_time, '%m/%d/%Y %H:%i:%s') as start_time,
													DATE_FORMAT(end_time, '%m/%d/%Y %H:%i:%s') as end_time,
													UNIX_TIMESTAMP(end_time) - UNIX_TIMESTAMP(start_time) as difftime 
															from attendence_log where attendence_log_id='$insert_id'")->row();
		  
		  
		  
		   $timings_text = "<table class='atttable '><tr class='sticky'><tr><th colspan='2'>Attendence Capture</th></tr>
					<tr><td>Attendence Date</td><td>   ".$inserted_dates->entry_date."</td></tr> 
					<tr><td>Start Time</td><td>   ".$inserted_dates->start_time."</td></tr> 
					<tr><td>End Time</td><td>   ".$inserted_dates->end_time."</td></tr> 
					<tr><td>Duration </td><td>   ".$inserted_dates->difftime." Seconds</td></tr> 
					<tr><td>IP Address</td><td>   ".$this->input->ip_address()."</td></tr> 
					<tr><td>Run By</td><td>   ---</td></tr> 
				</table>";
		  
		  echo   $style_sheet.$timings_text.$html_text;
		  
	}
	
	
}