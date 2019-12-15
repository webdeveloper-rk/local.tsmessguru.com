<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
set_time_limit(0);
 date_default_timezone_set('Asia/Kolkata');
class Loaddata extends MX_Controller {

    function __construct() {
        parent::__construct();
		 
		$this->load->helper('url');
		$this->load->library('grocery_CRUD');
		$this->load->model('school_model');
		 $this->load->library('excel');
	}

    function index() {
		echo "Data already loaded";
		die;
		$sql = "select * from schools ";
		$rs= $this->db->query($sql);
		foreach($rs->result() as $school_row)
		{
					$item_sql = "select * from items where status='1'";
					$item_rs= $this->db->query($item_sql);
					foreach($item_rs->result() as $item_row)
					{
						$school_item = "select * from balance_sheet where entry_date='2016-08-31' and item_id='".$item_row->item_id."' and school_id='".$school_row->school_id."'";
				
						$school_rs= $this->db->query($school_item);
						if($school_rs->num_rows()==0)
						{
							$this->db->query("insert into balance_sheet set entry_date='2016-08-31' ,
										item_id='".$item_row->item_id."' ,school_id='".$school_row->school_id."',
										purchase_quantity='0',	purchase_price='0' ,
											closing_quantity='0',	closing_price='0',
												record_type='closing_balance'
										");
						}
					}
		}
		
        echo "Done";
    }
 
		 
}
