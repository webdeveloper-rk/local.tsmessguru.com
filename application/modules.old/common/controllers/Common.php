<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
set_time_limit(0);
 date_default_timezone_set('Asia/Kolkata');
class Common extends MX_Controller {

    function __construct() {
        parent::__construct();
		 $this->load->model('common_model');
		
	}

    function index() {
		echo $sql = "select * from ";
    }
	function test() {
		echo "hi";
    }
	
	function recalculate_item($school_code,$item_id=null)
	{
		 $school_id = $this->db->query("select * from schools where school_code=?",array($school_code))->row()->school_id;
		$entry_date = '2018-04-01';
		$this->common_model->update_entries($school_id,$item_id,$entry_date,'temp_procedure');
		echo "Done";
	}
	
	function update_local_central()
	{
		 /*
		  TRUNCATE purchase_entries;
insert into purchase_entries(school_id,item_id , purchase_qty, entry_date,  comment )
select school_id,item_id ,  
opening_quantity  as purchase_qty,
entry_date,'Jan 1st 2019 opening balance considered as purchase entry '  as comment from balance_sheet where entry_date='2019-01-01'  and opening_quantity> 0 and item_id in(select item_id from item_per_head) group by school_id,item_id ;



insert into purchase_entries(school_id,item_id , purchase_qty, entry_date,  comment )
select school_id,item_id ,  
purchase_quantity  as purchase_qty,
entry_date,'from jan 1st'  as comment from balance_sheet where entry_date between '2019-01-01'  and last_day('2019-01-01')  and purchase_quantity> 0 and item_id in(select item_id from item_per_head) group by school_id,item_id ;



 
 */
 
		 $items_rs	= $this->db->query("select * from item_per_head ");
		 
	}
	
	function get_purchase_id($school_id,$item_id)
	{
	
	}
	function assign_purchase_id($school_id,$item_id)
	{
	
	}
	function divide_local_central_purchases()
	{
		 
		 $items_rs	= $this->db->query("select * from temp_school_item_dates  where central_purchased_date!='NULL'");
		 
		 
		 foreach($items_rs->result() as $row)
		 {
			$school_id = $row->school_id;
			$item_id = $row->item_id;
			$item_id = $row->item_id;
			$central_purchased_date = $row->central_purchased_date;
			$this->db->query("update purchase_entries set purchase_type='central' where  school_id=? and item_id= ? and entry_date>=?",array($school_id,$item_id,$central_purchased_date));
			//echo $this->db->last_query();die;
		 }
		 echo "Done ";
	
	}
	
}
