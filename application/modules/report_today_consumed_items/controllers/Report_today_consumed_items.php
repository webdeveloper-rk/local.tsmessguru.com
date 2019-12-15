<?php 
 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
set_time_limit(0);
 date_default_timezone_set('Asia/Kolkata');
class Report_today_consumed_items extends MX_Controller {

    function __construct() {
        parent::__construct();
		if($this->uri->segment(2) !="login") { 
					 Modules::run("security/is_admin");		 
					
		}
		if ($this->session->userdata("is_loggedin") != TRUE || $this->session->userdata("user_id") == "" ) {
							redirect("admin/login");
							die;
					}
					 			
					if($this->session->userdata("user_role") != "school")
					{
						redirect("admin/login");
							die;
					}
					
		$this->load->helper('url');  
		$this->load->model("common/common_model");  
	 
	}
	function index()
	{
  
		 $today = date('Y-m-d');
		  $stock_entry_table = $this->common_model->get_stock_entry_table($today);
				 
				//get all items between dates 
				    $sql = "SELECT *,
							 ( session_1_qty+session_2_qty+session_3_qty+session_4_qty)  as consumed_qty ,
							 	
								(
									(session_1_qty*session_1_price) + 
									(session_2_qty*session_2_price) + 
									(session_3_qty*session_3_price) + 
									(session_4_qty*session_4_price) 
								)	   as consumed_total,
							(purchase_quantity*purchase_price) purchase_total
							

				  FROM  $stock_entry_table  
				WHERE  school_id=? and  entry_date =?  order by consumed_qty desc ";
				$rs  = $this->db->query($sql,array($this->session->userdata("school_id"),$today));
				 $data["rset"] = $rs;
				 
				 
		 $drs = $this->db->query("select * from  items  ");         
        $item_names = array();
		foreach($drs->result() as $row)
		{
			$item_names[$row->item_id] = $row->telugu_name ." - ".$row->item_name;
		}
        $data["itemnames"] = $item_names;
		
		
		$data["today_date"] = date('d-M-Y');
		$data["module"] = "report_today_consumed_items";
        $data["view_file"] = "today_consumed_items";
        echo Modules::run("template/admin", $data);
         
	}

	 

}
