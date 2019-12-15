<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
set_time_limit(0);
 date_default_timezone_set('Asia/Kolkata');
class Loadmissed_data extends CI_Controller {

    function __construct() {
        parent::__construct();
		 
		 
	}

    function index() {
		 $sql = "SELECT school_id,item_id FROM schools,items";
		$rs= $this->db->query($sql);
		$i = 0;
		$entry_date = '2018-07-04';
		foreach($rs->result() as $school_row)
		{
				 $bs_rs = $this->db->query("select * from balance_sheet where item_id='".$school_row->item_id."' and school_id='".$school_row->school_id."'");
						if( $bs_rs->num_rows()==0){				
							$this->db->query("insert into balance_sheet set entry_date='$entry_date' ,
										item_id='".$school_row->item_id."' ,school_id='".$school_row->school_id."',
										purchase_quantity='0',	purchase_price='0' ,
											closing_quantity='0',	closing_price='0',
												record_type='missed_item_inserted'
										");
										
										$i++;
						}
						 
		} 
		echo "updated records ";
echo 		$i ;
	echo  "<br>";
        echo "Done";
    }
 
		 
}
