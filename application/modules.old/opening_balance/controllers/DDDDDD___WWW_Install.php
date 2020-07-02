<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Install extends MX_Controller {

    function __construct() {
        parent::__construct(); 
         $this->load->config("config.php");
         $this->load->model("admin/school_model");
		 
		$this->load->model("common/common_model");  
    }

   

    function index() {
 
		$opening_balance_start_date = $this->config->item('opening_balance_start_date');
		$opening_balance_end_date = $this->config->item('opening_balance_end_date');
		$opening_academic_year = $this->config->item('opening_academic_year');
	 
		$create_sql = "CREATE TABLE IF NOT EXISTS `school_opening_balance` (
													  `sid` int(11) NOT NULL AUTO_INCREMENT,
													  `academic_year` varchar(255) NOT NULL,
													  `school_id` int(11) NOT NULL,
													  `item_id` int(11) NOT NULL,
													  `qty` decimal(10,3) NOT NULL DEFAULT '0.000',
													  `price` decimal(10,2) NOT NULL DEFAULT '0.00',
													  `opening_balance_date` date NOT NULL,
													  `editable_end_date` date NOT NULL,
													  `status` enum('1','0') NOT NULL DEFAULT '1',
													  PRIMARY KEY (`sid`),
													  KEY `school_id` (`school_id`),
													  KEY `item_id` (`item_id`),
													  KEY `academic_year` (`academic_year`)
													) ENGINE=MyISAM DEFAULT CHARSET=latin1; ";
		$this->db->query($create_sql);	
		
		$insert_sql  = "insert into school_opening_balance(school_id,item_id,academic_year,opening_balance_date,editable_end_date) 
		select school_id,item_id ,?,?,? from schools,items where is_school=1 and items.status=1 and concat(school_id,'_',item_id ) not in 
		(select concat(school_id,'_',item_id ) from school_opening_balance where academic_year = ? and status=1 )";
		$this->db->query($insert_sql,array($opening_academic_year,$opening_balance_start_date,$opening_balance_end_date,$opening_academic_year)) ;
		echo "<br>Inserted Rows : ",$this->db->affected_rows();
		
		  $stock_entry_table = $this->common_model->get_stock_entry_table($opening_balance_start_date);
		  
		$update_sql  = "update  school_opening_balance  sob    inner join  $stock_entry_table bs on
									sob.item_id = bs.item_id and bs.school_id=sob.school_id 
								set sob.qty = bs.opening_quantity where bs.entry_date=?  and sob.academic_year=? 
									 ";
		$this->db->query($update_sql,array($opening_balance_start_date,$opening_academic_year )) ;
		//echo $this->db->last_query();
		
		echo "<br>Effected Rows : ",$this->db->affected_rows();
		 echo "<br>Done";
		
		
		
	}


}

