<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
set_time_limit(0);
 date_default_timezone_set('Asia/Kolkata');
class Itemupdate extends MX_Controller {

    function __construct() {
        parent::__construct();
		 
		$this->load->helper('url');
		$this->load->library('grocery_CRUD');
		$this->load->model('school_model');
		 $this->load->library('excel');
	}

    function index() {
		  $rs = $this->db->query("select * from item_updates where status='inprogress' limit 0,1000");
		 
		 foreach($rs->result() as $row){
			 $entry_date = '2016-08-31';
			 $school_id = $row->school_id;
			 $item_id = $row->item_id;
			 $temp_table ="entries".$school_id.$item_id.rand(10,10000);
			$this->school_model->update_entries($school_id,$item_id,$entry_date);
			$this->db->query("update item_updates set  status='completed' where school_id='$school_id' and item_id='$item_id'");
		 } 
		 echo "Completed 0-- ";
		
        echo "Done";
    }
 
		 
}
