<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
set_time_limit(0);
 date_default_timezone_set('Asia/Kolkata');
class Assign_ids extends MX_Controller {

    function __construct() {
        parent::__construct();
		 $this->load->model('common_model');
		
	}

    function index() {
			$irs  =$this->db->query("select * from school_item_purchase_status where status='open' limit 0 ,1");
			foreach($irs->result() as $irow)
			{
				$item_id = $irow->item_id;
				$siid = $irow->siid;
				$this->db->trans_start();
				$this->db->query("update  school_item_purchase_status set status='under_process' wher siid=?",array($siid));
				$srs = $this->db->query("select * from schools where is_school='1' and school_code !='85000'");
				foreach($srs->result() as $srow)
				{
					$school_id= $srow->school_id;
					$school_items = $this->db->query("select * from bs_jan_2019 where school_id=? and item_id=? and purchase_id=0",array($school_id,$item_id));
					foreach($srs->result() as $srow)
					{
						$entry_id = $srow->entry_id;
						$used_qty = $srow->session_1_qty $srow->+session_2_qty+ $srow->session_3_qty+ $srow->session_4_qty;
						$avilablity = $this->assign_purchase_id($school_id,$item_id);
						if($avilablity['avilable_qty'] >= $used_qty){
							
							$this->db->query("update  bs_jan_2019  set purchase_id=? where entry_id=?",array($avilablity['purchase_id'],$entry_id));
						}else{
							
							//spilt consumption 
							$avilable_qty = $used_qty - $avilablity['avilable_qty'];
							
							//set avilable quantity for existing quantity used 
							$this->db->query("update  bs_jan_2019  set purchase_id=?,session_1_qty=0,session_2_qty=0,session_3_qty=0,session_4_qty=? where entry_id=?",array($avilablity['purchase_id'],$avilable_qty,$entry_id));
							
							//create new consumption entry with avilble qty 
							
							
							//assign new purchase id 
						}
					}
					
					
				}
				$this->db->query("update  school_item_purchase_status set status='completed' wher siid=?",array($siid));
				$this->db->trans_complete();
				echo "Comleted ",$item_id,"-",$school_id,"\n";
			}
	}
	function assign_purchase_id($school_id,$item_id)
	{
			$status_rs  = $this->db->query("select * from purchase_entries where school_id=? and item_id=?  and status='open'",array($school_id,$item_id));
			
			if($status_rs->num_rows()==0)
			{
				$status_rs2  = $this->db->query("select * from purchase_entries where school_id=? and item_id=? and status='not_opened'  ",array($school_id,$item_id));
				
				$purchase_id = $status_rs2 ->row()->purchase_id;
				 $this->db->query("update  purchase_entries  set status='open' where school_id=? and item_id=? and status='not_opened'  ",array($school_id,$item_id));
				 
				 $status_rs  = $this->db->query("select * from purchase_entries where school_id=? and item_id=?  and status='open'",array($school_id,$item_id));
				
				
			}
			if($status_rs->num_rows()>0)
			{	
					$ssrow = $status_rs->row();
					$purchase_id = $ssrow->purchase_id;
					$purchase_qty = $ssrow->purchase_qty;
					
					$school_items = $this->db->query("select sum(session_1_qty+session_2_qty+session_3_qty+session_4_qty) as total_used  from bs_jan_2019 where school_id=? and item_id=? and purchase_id=?",array($school_id,$item_id,$purchase_id));
					
					$total_used = $school_items->row()->total_used;
					$remaining_qty = $purchase_qty - $total_used;
					
					return array("purchase_id"=>$purchase_id,"avilable_qty"=>$remaining_qty );
					
			} 
	}
	
}
