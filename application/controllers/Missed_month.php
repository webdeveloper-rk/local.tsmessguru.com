<?php
set_time_limit (0);
defined('BASEPATH') OR exit('No direct script access allowed');

class Missed_month extends CI_Controller {

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
		$month = "06";
		$year = "2018";
		
		if(isset($_GET['month']))
		{
			$month = $_GET['month'];
		}
		if(isset($_GET['month']))
		{
			$month = $_GET['year'];
		}
		
		 $sql = "select * from schools where is_school='1' and school_code not like '%85000%'";
		 $rs = $this->db->query($sql);
		 $school_ids  = array();
		 foreach($rs->result() as $row)
		 {
			$school_ids[] = $row->school_id;
			 
		 }
		 
		
		 $sql = "select *,DATE_FORMAT(entry_date,'%d/%m/%Y')  as entry_date  from school_day";
		 $rs = $this->db->query($sql);
		 $list_days  = array();
		 foreach($rs->result() as $row)
		 {
			 if(in_array($row->school_id,$school_ids)){
					$list_days[$row->school_id]['days'][$row->entry_date] = 0;
			 }
			 
		 }
		
		     $ysql = "SELECT bs.school_id,DATE_FORMAT(entry_date,'%d/%m/%Y')  as entry_date ,school_code,name , sum(`session_1_qty`+`session_2_qty`+`session_3_qty`+`session_4_qty`) as used_qty FROM `balance_sheet` bs  inner join schools sc on sc.school_id = bs.school_id 
										WHERE entry_date between '$year-$month-01' and  last_day('$year-$month-01') and sc.is_school=1  and sc.school_code not like '%85000%'group by school_id,entry_date order by school_id asc";
										
			$srs = $this->db->query($ysql);
		 
		 foreach($srs->result() as $row)
		 {
			 if($row->used_qty==0)
				 $qty = 0;
			 else 
				 $qty = 1;
			 
			$list_days[$row->school_id]['school']   = $row->school_code." - ".$row->name;
			 
						$list_days[$row->school_id]['days'][$row->entry_date] = $row->used_qty;
			 
		 }
		 foreach($list_days as $school_id=>$obj)
		 {
		 
		  if(!in_array($school_id,$school_ids)){
					continue;
			 }
		 
			$used_count = 0;
			$not_used_count = 0;
			foreach($obj['days'] as $datep=>$day_count )
			{
				//print_a($day_count,1);
				if($day_count == 0)
					$not_used_count++;
				else
					$used_count++;
			}
			$list_days[$school_id]['used_count'] = $used_count;
			$list_days[$school_id]['not_used_count'] = $not_used_count;
		 }
		 
		 
		// print_a($list_days,1);
		 $months = array("01"=>"January","02"=>"February","03"=>"March","04"=>"April","05"=>"May",
									"06"=>"June","07"=>"July","08"=>"August","09"=>"September","10"=>"October","11"=>"November","12"=>"December");

		  
		  $data['report_date'] =  $months[$month]."-".$year;
		  $data['days_list'] = $list_days;
		  $this->load->view("missed_month",$data);
	}
	
	
}