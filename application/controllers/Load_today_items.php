<?php
set_time_limit (0);
defined('BASEPATH') OR exit('No direct script access allowed');

class Load_today_items  extends CI_Controller {
 
	public function index()	{				$today_date  = date('Y-m-d');		if($today_date  ==  '2017-11-28')		{			$today_date =  '2017-11-29';		}		echo $today_date."\n";;		 $cron_rs  = $this->db->query("select * from cronjobs where cron_code='load_item_balances' and entry_date='$today_date' ");		 if($cron_rs->num_rows()==0)		 {			 								 //Clear 				 $this->db->query("truncate  item_today_balances");				 				 //insert all items 				 $this->db->query("insert into item_today_balances(	entry_id,school_id,district_id,	item_id)																			SELECT MAX( entry_id ) AS entry_id, school_id,district_id, item_id																			FROM balance_sheet																			GROUP BY school_id, item_id ");				//update closing_quantities																			 $this->db->query("update item_today_balances tb inner join balance_sheet bs on bs.entry_id = tb.entry_id set											tb.last_entry_date = bs.entry_date ,tb.opening_quantity = bs.closing_quantity");															 $this->db->trans_start();									$this->db->query("insert into cronjobs set cron_code='load_item_balances' , entry_date='$today_date',cron_title='Loading Daily Balances' ");				 				$this->db->query("insert into balance_sheet (school_id,entry_date,item_id,opening_quantity,closing_quantity,district_id) 			select school_id, '$today_date' as entry_date,item_id,opening_quantity, opening_quantity as closing_quantity,district_id from item_today_balances ");													echo "\n Rows effected : ".$this->db->affected_rows()." \n "; 				 $this->db->trans_complete();					if ($this->db->trans_status() === FALSE)					{						echo "Failed to execute cron load_item_balances " .date('d-m-Y H:i:s');					}					else					{						echo "successfully executed cron load_item_balances " .date('d-m-Y H:i:s');					}				 }		 else		 {			 echo "Cron already executed ".date('d-m-Y H:i:s');;		 }		 		 					}	 
}