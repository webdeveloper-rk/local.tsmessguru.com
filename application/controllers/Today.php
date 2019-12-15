<?php
set_time_limit (0);
defined('BASEPATH') OR exit('No direct script access allowed');

class Today  extends CI_Controller {
 
	public function index()	{		echo date('d-m-Y H:i:s');		$today_date  = date('Y-m-d');		/*if($today_date  ==  '2017-11-28')		{			$today_date =  '2017-11-29';		}		*/		 echo "Should be equal to 56488 <br>";		 $cron_rs  = $this->db->query("select * from balance_sheet where  entry_date='$today_date' ");		 echo " \n <br> Number of entries inserted  : ";		echo $cron_rs->num_rows();	}	 
}