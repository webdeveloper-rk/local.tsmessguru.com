<?php 
 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
set_time_limit(0);
 date_default_timezone_set('Asia/Kolkata');
class Missed_monthly_cron extends MX_Controller {

    function __construct() {
        parent::__construct(); 
	}

    function index() {
		 
		 $month_date  = date('m-Y');
		 
		 $sql = "delete from missed_monthly WHERE DATE_FORMAT(entry_date,'%m-%Y') = ?"; 
		$this->db->query($sql,array($month_date));
		 
		 $sql = "insert into missed_monthly( school_id ,entry_date, total_purchase,  total_qty)
				SELECT `school_id`,entry_date,sum(purchase_quantity) as total_purchase,sum(session_1_qty+session_2_qty+session_3_qty+session_4_qty) as total_qty
			FROM `balance_sheet` WHERE DATE_FORMAT(entry_date,'%m-%Y') = ? group by school_id ,entry_date 
			having   sum(purchase_quantity) =0 and  sum(session_1_qty+session_2_qty+session_3_qty+session_4_qty) = 0 ";
			
		$this->db->query($sql,array($month_date));
		echo "Done for ".$month_date . " on ".date('d-M-Y H:i:s A');
	}
}