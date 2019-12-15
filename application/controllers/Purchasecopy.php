<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Purchasecopy extends CI_Controller {

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
		 die("Access denied");
	 }
 public function update($mycode='')
	{
		//echo $mycode;
		 //die("Access denied");
		if($mycode !="iwantkiran")
		{
			die("Access denied");
		}
		$this->db->trans_start();
		 $this->db->query("TRUNCATE purchases"); 
		 echo "Truncated<br>";
		if ( $this->db->table_exists('refer_balance_sheet'))
		{
				$rs = $this->db->query("SELECT * FROM `refer_balance_sheet` where is_current='0'");
				foreach($rs->result() as $row)
				{
					$bsheet = $row->table_name;
					$this->db->query("insert into purchases(school_id,item_id, purchase_date, quantity,purchase_price) select school_id,item_id,entry_date as purchase_date,purchase_quantity as quantity,purchase_price from $bsheet where purchase_quantity>0" );
					echo "<br>",$this->db->last_query();
					
				}
		
		}
		
		$this->db->query("insert into purchases(school_id,item_id, purchase_date, quantity,purchase_price) select school_id,item_id,entry_date as purchase_date,purchase_quantity as quantity,purchase_price from balance_sheet where purchase_quantity>0");
		$this->db->trans_complete();
		if ($this->db->trans_status() === FALSE)
		{
				// generate an error... or use the log_message() function to log your error
				echo " Error Occured in Update entries. please try again.  ";
				die;
				 
		}
		echo "Completed";
		
         
	}
	 
	
	
	//old reports link
	
}
