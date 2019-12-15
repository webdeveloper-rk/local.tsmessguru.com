<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Unused_items extends CI_Controller {

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
			 $rs = $this->db->query("select * from unused_items where updated='no' limit 0,2");
			 foreach($rs->result() as $row)
			 {
				$item_id = $row->item_id;
				$bs_rs = $this->db->query("select sum(purchase_quantity) as total_purchase from balance_sheet where item_id=?",array($item_id));
				$sum_purchased = $bs_rs->row()->total_purchase;
				
				$this->db->query("update unused_items set  quantity_purchased=?,updated='yes' where item_id=?",array($sum_purchased,$item_id));
				 
			 }
			 echo "Done";
			 ///unused_items
	}
	public function deleteitems()
	{
			 $rs = $this->db->query("SELECT * FROM `unused_items` WHERE quantity_purchased = 0");
			 foreach($rs->result() as $row)
			 {
				$item_id = $row->item_id;
				//$bs_rs = $this->db->query("select sum(purchase_quantity) as total_purchase from balance_sheet where item_id=?",array($item_id));
				//$sum_purchased = $bs_rs->row()->total_purchase;
				//if($sum_purchased ==0){
					$this->db->query("delete from balance_sheet where item_id=? and opening_quantity=0 and entry_date= CURRENT_DATE() ",array($item_id)); 
				//}
				 
			 }
			 echo "Done";
			 ///unused_items
	}
	 
}
