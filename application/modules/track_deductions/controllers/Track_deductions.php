<?php 
 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
set_time_limit(0);
 date_default_timezone_set('Asia/Kolkata');
class Track_deductions extends MX_Controller {

    function __construct() {
        parent::__construct();
		if($this->uri->segment(2) !="login") { 
					 Modules::run("security/is_admin");		 
					if ($this->session->userdata("is_loggedin") != TRUE || $this->session->userdata("user_id") == "" ) {
							redirect("admin/login");
							die;
					}
					 	//print_a($this->session->all_userdata(),1);	
						
						$roles = array('subadmin','secretary');
					if(!in_array($this->session->userdata("user_role"),$roles))
					{
						redirect("admin/login");
							die;
					}
					$is_collector = $this->db->query("select is_collector from users where school_id=?",array($this->session->userdata("school_id")))->row()->is_collector;
					if($is_collector==1)
					{
						redirect("admin");
					}
		}
		$this->load->helper('url');
	 
	}

    function index() {
			$this->listschools();
		}
		
	function listschools()
	{
		
		if($this->input->post('school_date')!="")
		 {
			   $date = date('Ymd',strtotime($this->input->post('school_date'))); 
		 }else {
		 
				$date = date('Ymd');
		 }
			
		  $report_date = date('Y-m-d',strtotime($date));
		 
		 $condition_dco =  '';
		 if($this->session->userdata("is_dco") == 1) 
		{
						$condition_dco = " and  sc.district_id = '".intval($this->session->userdata("district_id"))."'   ";
		}
			
		  $sql = "select sc.school_id,sc.school_code,sc.name,dc.item_id,it.item_name ,dc.amount,min_20,one_forth_total,dpc_approved  from schools sc inner  join 
		  ( SELECT `school_id`,item_id ,amount ,entry_date,min_20,one_forth_total,dpc_approved from  dietpic_cheker  WHERE `entry_date` = ?   ) dc on dc.school_id= sc.school_id
				inner join items it on dc.item_id=it.item_id   $condition_dco
		  order by sc.school_id,it.item_id";
		$rs  = $this->db->query($sql,array($report_date));
		 //if($this->input->ip_address()=="103.49.53.146")
			//echo $this->db->last_query();
		$report_date_formated = date('d-m-Y',strtotime($report_date));
		
		$data["report_date"] = $report_date_formated;
		$data["rset"] = $rs;
		//$data['total_schools'] = $this->db->query("select count(*) as total_schools from dietpic_cheker  WHERE `entry_date` = ? group by school_id",array($report_date))->row()->total_schools;
		//$data['total_amt'] = $this->db->query("select sum(amount) as total_amt from dietpic_cheker  WHERE `entry_date` = ?     ",array($report_date))->row()->total_amt;
		
		$data["module"] = "track_deductions"; 
		$data["view_file"] = "track_deductions";
		echo Modules::run("template/admin", $data);
		
	}
	 
}
 