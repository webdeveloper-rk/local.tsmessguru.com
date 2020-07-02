<?php
set_time_limit(0);
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Itembalances extends MX_Controller {

    function __construct() {
        parent::__construct();
	  
		 
    }

   

    function index() {
		
		$sql = "select * from schools";
		$rs = $this->db->query($sql);
		foreach($rs->result() as $row)
		{
			echo "<br>----",$school_id = $row->school_id;
			$sql = "select * from items";
			$itemrs = $this->db->query($sql);
			foreach($itemrs->result() as $item_row)
			{
				echo "---item----",$item_id = $item_row->item_id; 
				$date = date('Y-m-d');		
						  
							  $qry = "select  entry_id from   balance_sheet where  school_id='$school_id' and 
											item_id='$item_id' and  entry_date='$date'";
							$today_rs = $this->db->query($qry );
							 
							$datenow = date('Y-m-d H:i:s');
							//if  entry not exists for todat  add new record
							if($today_rs->num_rows()==0)
							{
							 
									//$closing_entries= $this->get_intial_balance($school_id,$item_id,$date);			
									 $cqry = "select  max(entry_date) as entry_date  from   balance_sheet where  school_id='$school_id' and 
											item_id='$item_id' and  entry_date<='$date'";
									 $c_rs = $this->db->query($cqry );
									  $cdata = $c_rs->row();
									  $tqry = "select  closing_quantity   from   balance_sheet where  school_id='$school_id' and 
											item_id='$item_id' and  entry_date='".$cdata->entry_date."'";
									 $t_rs = $this->db->query($tqry );
									 $tdata = $t_rs->row();
										 
									  $qry ="insert into balance_sheet set 
																		school_id = '$school_id',
																		item_id = '$item_id',
																		entry_date= '$date',
																		opening_quantity = '".$tdata->closing_quantity."', 
																		opening_price = '0', 
																		created_time= '$datenow' ";
									$this->db->query($qry);
									  /******************************************************************************************/
									  
									  
									  
											$qry = "update balance_sheet set 
														closing_price=0,
														closing_quantity=(opening_quantity+purchase_quantity) - (session_1_qty+session_2_qty+session_3_qty+session_4_qty)
														where school_id='$school_id' and item_id='$item_id' and entry_date='$date'";
											$rs = $this->db->query($qry); 
							}
			}
		}
        

    }

 


}

