<?php 
 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
set_time_limit(0);
 date_default_timezone_set('Asia/Kolkata');
class Usedschools extends MX_Controller {

    function __construct() {
        parent::__construct();
		if($this->uri->segment(2) !="login") { 
					 Modules::run("security/is_admin");		 
					if ($this->session->userdata("is_loggedin") != TRUE || $this->session->userdata("user_id") == "" ) {
							redirect("admin/login");
							die;
					}
					 			
					$roles = array('subadmin','secretary');
					if(!in_array($this->session->userdata("user_role"),$roles))
					{
						redirect("admin/login");
							die;
					}
		}
		$this->load->helper('url');
	  $this->load->model('common/common_model');
	}

    function index() {
			$this->listschools();
		}
		
	function listschools()
	{
		$data['result'] = 0;
		
		
		if($this->input->post('school_date')!="")
		 {
					$date = date('Ymd',strtotime($this->input->post('school_date')));
					$item_id =intval($this->input->post('item_id')) ;  
					$report_date = date('Y-m-d',strtotime($this->input->post('school_date')));
					$condition_dco =  '';
					if($this->session->userdata("is_dco") == 1) 
					{
							$condition_dco = " and  sc.district_id = '".htmlentities($this->session->userdata("district_id"))."'   ";
					}
						$balance_sheet_table = $this->common_model->get_stock_entry_table($report_date);

						
					  $sql = "select sc.school_id,sc.school_code,sc.name ,sc.is_school,t1.item_id,t1.total_purchase,t1.total_qty from schools sc left join 
					  ( SELECT  school_id ,item_id, sum(purchase_quantity) as total_purchase,sum(session_1_qty+session_2_qty+session_3_qty+session_4_qty) as total_qty
						FROM  $balance_sheet_table  WHERE  entry_date =? and item_id=? group by school_id ,item_id
						having(sum(purchase_quantity))>0 or sum(session_1_qty+session_2_qty+session_3_qty+session_4_qty)>0 ) as t1 on sc.school_id = t1.school_id 
						where sc.is_school=1   and t1.total_qty is NOT NULL and sc.school_code not like '%85000%' $condition_dco  order by sc.school_code 
					  ";
					$rs  = $this->db->query($sql,array($report_date,$item_id));
					// echo $this->db->last_query();

					$report_date_formated = date('d-m-Y',strtotime($report_date));
					
					 
					 $data["report_date"] = $report_date_formated;
					$data["rset"] = $rs;
					$data["item_name"] = $this->db->query("select * from items where status=1 and item_id=?   ",array($item_id))->row()->item_name;;
			$data['result'] = 1;		
		 }
		
		$data["item_list"] = $this->db->query("select * from items where status='1' order by item_name");
		
		
		$data["module"] = "missed_entries"; 
		$data["view_file"] = "usedschools";
		echo Modules::run("template/admin", $data);
		
	}
}