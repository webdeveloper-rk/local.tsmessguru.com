<?php
defined('BASEPATH') OR exit('No direct script access allowed'); 

class Removeold_records extends CI_Controller {

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
	 public function __construct()
    {
        parent::__construct(); 
    }
	public function index()
	{
		$del_date = $this->db->query("select DATE_SUB(CURRENT_DATE(), INTERVAL 30 DAY) as del_date")->row()->del_date;
		
		$rs = $this->db->query("select day(?) as day_num,day(last_day(?)) as last_day  ",array($del_date,$del_date));
		$day_row  = $rs->row();
		$day_num =	$day_row->day_num;
		$last_day =	$day_row->last_day;
		
		if(in_array($day_num,array(1,15,$last_day)))//skip the 1st and 15th day  and last day of every month 
		{
			echo " Delete Skipped for the $day_num day :: for the  $del_date on ",date("d-m-Y H:i:s");
			die;
		}
	 
		
		
		 $this->db->query("delete from balance_sheet where  (purchase_quantity = 0 and session_1_qty + session_2_qty+session_3_qty+session_4_qty=0)  and entry_date =? ",array($del_date));
							echo $this->db->affected_rows() ," Rows Deleted for the date of $del_date on ",date("d-m-Y H:i:s");
		 
	 
	}
	 
}
