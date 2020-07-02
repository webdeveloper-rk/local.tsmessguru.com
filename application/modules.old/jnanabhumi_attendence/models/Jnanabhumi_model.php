<?php

class Jnanabhumi_model extends CI_Model {

    

    function __construct() {
        parent::__construct();
    }
	
	
	function update_attendence($attendene_report,$db_date,$start_time)
	{
	
				$class_actual_names = $attendene_report['class_names'];
				unset($attendene_report['class_names']);
		$already_entered = array();
	 
		$already_entered_rs = $this->db->query("select school_id,attendence_id from school_attendence where entry_date='$db_date'");
		foreach($already_entered_rs->result() as $exis_row){
			$already_entered[$exis_row->school_id] =  $exis_row->attendence_id;
		}
		
		$school_codes = array();
		$school_rs  =  $this->db->query("select * from schools where is_school=1");
		foreach($school_rs->result() as $row){
			$school_codes[$row->sam_school_id] = $row->school_id;
		}
		 
		
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
																	cat1_attendence ='$cat1_attendence',
																	cat2_attendence ='$cat2_attendence',
																	cat3_attendence ='$cat3_attendence' 																
															where  attendence_id='$attendence_id' ";
				  
				 $this->db->query($qryts);
				 
		 
				} 
		} //foreach close 
		//update present count 
		$qryts = "update school_attendence set  
						present_count = `cat1_attendence`+`cat2_attendence`+`cat3_attendence`+
										`cat1_guest_attendence`+ `cat2_guest_attendence`+`cat3_guest_attendence`															
															where  entry_date='$db_date' ";
				  
		$this->db->query($qryts);
		
		$end_time = $this->db->query("select CURRENT_TIMESTAMP as timenow")->row()->timenow;
		$timings = array('start_time'=>$start_time,'end_time'=>$end_time);
		
		return $this->update_log($attendene_report,$db_date,$timings,$class_actual_names);
	}
	 
	
	function update_log($attendene_report=array(),$entry_date,$timings = array(),$class_actual_names)
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
		  
		  $updated_by = '';
		if(in_array($this->input->ip_address() ,$this->config->item('system_ip')))
		{
		 $updated_by = 'System';
		}
		else
		{
			 $updated_by = "Administrator - ". $this->session->userdata('user_name');
		}
		  
		  $data =array(
		  'entry_date'=>$entry_date,
		  'start_time'=>$timings['start_time'],
		  'end_time'=>$timings['end_time'],
		  'webservice_url_connected'=>'1',
		  'log_text'=>$html_text,
		  'log_ip'=>$this->input->ip_address(),
		  'user_id'=>$updated_by,
		  'raw_xml'=>'' 
		  
		  
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
					<tr><td>Run By</td><td>   $updated_by</td></tr> 
				</table>";
		  
		  return    $style_sheet.$timings_text.$html_text;
		  
	}
	
}
?>