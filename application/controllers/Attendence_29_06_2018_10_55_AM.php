<?php
set_time_limit (0);
defined('BASEPATH') OR exit('No direct script access allowed');

class Attendence extends CI_Controller {

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
		$start_time  = time();
		libxml_disable_entity_loader(false); 
		
		 $this->load->library('webservices_lib');
		 $date = $_GET['date'];
		 
		 $cat1_list = array('class3','class4','class5','class6','class7');
		 $cat2_list = array('class8','class9','class10');
		 $cat3_list = array('mpc1year','mpc2year','bipc1year','bipc2year','cec1year','cec2year','hec1year','hec2year','ct1year','ct2year','aandt1year','aandt1year','aandt2year','mec1year','mec2year','mlt1year','mlt2year','cga1year','cga2year','emcetiitmpc1year','emcetiitmpc2year','emcetbipc1year','emcetbipc2year','iitmpc2year','iitmpc1year');
		 
		 
		 
		 $all_cat_list = array_merge($cat1_list,$cat2_list,$cat3_list);
		 $cat1_total = 0;
		 $cat2_total = 0;
		 $cat3_total = 0;
		 
		 if(isset( $_GET['date'])){
			$date = $_GET['date'];
		 }
		if($date=="")
		{
			$date = date('m-d-Y');
		} 
		$date_split = explode("-",$date);
		
		
		$db_date  = $date_split[2]."-".$date_split[0]."-".$date_split[1];
		//echo $date,"-",$db_date,"---<br>"; 
		$school_codes = array();
		$school_rs  =  $this->db->query("select * from schools");
		foreach($school_rs->result() as $row){
			$school_codes[$row->sam_school_id] = $row->school_id;
		}
		
		$attendene_report = array();
	 
		$obj = new HasService(array(),'http://202.65.156.34:9090/tgtwrs/services/HasService?wsdl');
		$requestObj = new getStateWiseAttendance($date);
		$responseObj = $obj->getStateWiseAttendance($requestObj);
		$responseReturned = $responseObj->getReturn();
		
		 // echo "<pre>";echo count($responseReturned);print_r($responseReturned);die;
		$count = 0 ;
		$classes_list = array();
		$school_list = array();
		$dst_list = array();
		
		foreach($responseReturned as $school_att_obj)
		{ 
			$class_name = $school_att_obj->getClassname();
			 
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
	 
		$already_entered_rs = $this->db->query("select school_id from school_attendence where entry_date='$db_date'");
		foreach($already_entered_rs->result() as $exis_row){
			$already_entered[] =  $exis_row->school_id;
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
		
		//echo count($attendene_report),"--";		//print_a($attendene_report);
		$akeys = array_keys($attendene_report);
		echo $list_sids = implode(",",$akeys);
		
		foreach($attendene_report as $sam_id=>$details)
		 {
				 $total = $details['total'];
				 $cat1_attendence = $details['cat1_count'];
				 $cat2_attendence = $details['cat2_count'];
				 $cat3_attendence = $details['cat3_count'];
				 
			 
			 if(isset($school_codes[$sam_id])){
				 $school_id = $school_codes[$sam_id];
				 if( in_array($school_id,$already_entered)){
							$qryts = "update school_attendence set
																	present_count='$total', 
																	cat1_attendence ='$cat1_attendence',
																	cat2_attendence ='$cat2_attendence',
																	cat3_attendence ='$cat3_attendence' 																
															where school_id='$school_id' and entry_date='$db_date' ";
				 }
				 else{
					 //insert 
					 $qryts  = "insert into school_attendence set 
																	school_id='$school_id',
																	entry_date='$db_date',
																	present_count='$total',  
																	cat1_attendence ='$cat1_attendence',
																	cat2_attendence ='$cat2_attendence',
																	cat3_attendence ='$cat3_attendence' 
																";
						
				 }
			//	 echo "<br>",$qryts;
				 $this->db->query($qryts);
				 //print_a($details);
				 //insert into attendence_list
				 $this->db->query("delete  from school_attendence_list where school_id='$school_id'  and entry_date='$db_date'  ");
				 foreach($details as $key=>$value)
				 {
					if(in_array($key,$all_cat_list)){		
						
						  
							  $this->db->query("insert into school_attendence_list set class_code='$key',class_title='".$classes_list[$key]."',
																						school_id='$school_id' ,
																						attendence_count='$value' ,  
																						entry_date='$db_date'  
																						");
						  
					}
				 }
		 
		 }
		 
		
		
		
		} 
		
		$end_time = time();
		echo $total_time = $start_time - $end_time;
		echo "-Done";
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
	
	
}