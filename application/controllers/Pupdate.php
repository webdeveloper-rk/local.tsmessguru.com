<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pupdate extends CI_Controller {

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
		 
		 $rs = $this->db->query("select * from item_updates where status='inprogress' limit 0,1000");
		 
		 foreach($rs->result() as $row){
			 $entry_date = '2016-08-31';
			 $school_id = $row->school_id;
			 $item_id = $row->item_id;
			 $temp_table ="entries".$school_id.$item_id.rand(10,10000);
			$this->school_model->update_entries($school_id,$item_id,$entry_date);
			
			
		 }
		 echo "Completed";
	}
	 
	 
}
