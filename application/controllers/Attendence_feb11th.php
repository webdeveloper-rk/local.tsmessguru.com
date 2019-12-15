<?php
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
		libxml_disable_entity_loader(false); 
		
		 $this->load->library('webservices_lib');
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
		//echo $date,"-",$db_date,"---<br>"; 
		$school_codes = array();
		$school_rs  =  $this->db->query("select * from schools");
		foreach($school_rs->result() as $row){
			$school_codes[$row->sam_school_id] = $row->school_id;
		}
		
		$attendene_report = array();
		
		
		
		$obj = new HasService();
		$requestObj = new getStateWiseAttendance($date);
		$responseObj = $obj->getStateWiseAttendance($requestObj);
		$responseReturned = $responseObj->getReturn();
		
		// echo "<pre>";print_r($responseReturned);
		$count = 0 ;
		$classes_list = array();
		foreach($responseReturned as $school_att_obj)
		{ 
			$class_name = $school_att_obj->getClassname();
			
			$class_name = $this->replace($class_name);
			
			$classes_list[] = $class_name ;
			$attendene_report[$school_att_obj->getSchoolid()][$class_name] = $school_att_obj->getPresencecount();				 
			
			if(!isset($attendene_report[$school_att_obj->getSchoolid()]['total']))
				$attendene_report[$school_att_obj->getSchoolid()]['total'] = 0;
			
			$total_count  = $attendene_report[$school_att_obj->getSchoolid()]['total'];
			$attendene_report[$school_att_obj->getSchoolid()]['total'] = $total_count + $school_att_obj->getPresencecount(); 
		}		
		$queries_list = array();
		$already_entered = array();
	 
		$already_entered_rs = $this->db->query("select school_id from school_attendence where entry_date='$db_date'");
		foreach($already_entered_rs->result() as $exis_row){
			$already_entered[] =  $exis_row->school_id;
		}
		print_a(array_unique($classes_list));		
		echo "<pre>";print_r($attendene_report ); //;die;
		 foreach($attendene_report as $sam_id=>$details)
		 {
			 
			$class5 = isset($details['class5'])?$details['class5']:0; 
			$class6 = isset($details['class6'])?$details['class6']:0; 
			$class7 = isset($details['class7'])?$details['class7']:0; 
			$class8 = isset($details['class8'])?$details['class8']:0; 
			$class9 = isset($details['class9'])?$details['class9']:0; 
			$class10 = isset($details['class10'])?$details['class10']:0; 


			$mpc1year = isset($details['mpc1year'])?$details['mpc1year']:0; 
			$mpc2year = isset($details['mpc2year'])?$details['mpc2year']:0; 
			$bipc1year = isset($details['bipc1year'])?$details['bipc1year']:0; 
			$bipc2year = isset($details['bipc2year'])?$details['bipc2year']:0; 

			$cec1year = isset($details['cec1year'])?$details['cec1year']:0; 
			$cec2year = isset($details['cec2year'])?$details['cec2year']:0; 
			$hec1year = isset($details['hec1year'])?$details['hec1year']:0; 
			$hec2year = isset($details['hec2year'])?$details['hec2year']:0; 
			$mec1year = isset($details['mec1year'])?$details['mec1year']:0; 
			$mec2year = isset($details['mec2year'])?$details['mec2year']:0; 
			 
			 
			 
			 $total = $details['total'];
			 
			 if(isset($school_codes[$sam_id])){
				 $school_id = $school_codes[$sam_id];
				 if( in_array($school_id,$already_entered)){
							$qryts = "update school_attendence set
																present_count='$total', 
																class_5_count='$class5',
																class_6_count='$class6',
																class_7_count='$class7',
																class_8_count='$class8',
																class_9_count='$class9',
																class_10_count='$class10',
																inter_mpc_1='$mpc1year',
																inter_mpc_2='$mpc2year',
																inter_bipc_1='$bipc1year',
																inter_bipc_2='$bipc2year',

																inter_cec_1='$cec1year',
																inter_cec_2='$cec2year',
																inter_hec_1='$hec1year',
																inter_hec_2='$hec2year',
																inter_mec_1='$mec1year',
																inter_mec_2='$mec2year'
															where school_id='$school_id' and entry_date='$db_date' ";
				 }
				 else{
					 //insert 
					 $qryts  = "insert into school_attendence set 
																	school_id='$school_id',
																				entry_date='$db_date',
																				present_count='$total', 
																				class_5_count='$class5',
																				class_6_count='$class6',
																				class_7_count='$class7',
																				class_8_count='$class8',
																				class_9_count='$class9',
																				class_10_count='$class10',
																				inter_mpc_1='$mpc1year',
																				inter_mpc_2='$mpc2year',
																				inter_bipc_1='$bipc1year',
																				inter_bipc_2='$bipc2year',

																				inter_cec_1='$cec1year',
																				inter_cec_2='$cec2year',
																				inter_hec_1='$hec1year',
																				inter_hec_2='$hec2year',
																				inter_mec_1='$mec1year',
																				inter_mec_2='$mec2year'
																";
						
				 }
				 $this->db->query($qryts);
						 $queries_list[] = $qryts;
				
			 }
			 else
			 {
				 echo "--",$sam_id,"<br>";
			 }
			 
		 }
		 //execute if queries exists
		 if(count($queries_list)>0)
		 {
				//echo implode(";<br>",$queries_list);
				//$queries = implode(";",$queries_list);
				//echo $queries;
				//$this->db->query($queries);
		 }
		echo "Done";		 
	} 
	function replace($str)
	{
	
		$str = str_replace("II YEAR","2year",$str);
		$str = str_replace("I YEAR","1year",$str);
		$str = str_replace("BI.P.C","bipc",$str);
		$str = str_replace(" ","",$str);
		$str = str_replace(".","",$str);
		$str = str_replace("-","",$str);		
	 
		$str = strtolower($str);
		 
		
		return  $str;
	}
}