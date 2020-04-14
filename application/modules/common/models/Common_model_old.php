<?php

class Common_model extends CI_Model {

    var $table;

    function __construct() {
        parent::__construct();
    }

    function get_item_price($item_id,$district_id,$date)
	{
		$item_id = intval($item_id);
		$district_id= intval($district_id);
		$sql = "select price from district_prices where district_id=? and item_id=? and ? between start_date  and end_date";
		$rs = $this->db->query($sql,array($district_id,$item_id,$date));
		
		//echo $this->db->last_query();die;
		if($rs->num_rows()>0)
		{
			$data = $rs->row();
			return $data->price;
		}
		else
		{
			return 0;
		}
	}
	function get_monthly_prices($month,$year)
	{
		$month = intval($month);
		$year = intval($year);
		if($month<1 || $month >12)
		{
			$month = "01";
		}
		if($month<10)
		{
			$month = "0$month";
		}
		if($year>date('Y') )
		{
			$year = date('Y');
		}
		if($year<2017 )
		{
			$year = 2017;
		}
		
		$choosen_date = "$year-$month-01"; 
		$days_count  = $this->db->query("SELECT DAY(LAST_DAY(?)) as tdays",array($choosen_date))->row()->tdays ; 
		
		 // echo $this->db->last_query();die;
		$price_sql = "select * from group_prices  where     ? between start_date and end_date";
		$price_rs = $this->db->query($price_sql,array($choosen_date));
		// echo $this->db->last_query();die;
		$rates = array();
		foreach($price_rs->result() as $stu_price){
			$rates[$stu_price->category][$stu_price->group_code]['amount_per_month'] = $stu_price->amount;
			$rates[$stu_price->category][$stu_price->group_code]['number_of_days'] = $days_count;
			$rates[$stu_price->category][$stu_price->group_code]['per_day'] = $stu_price->amount/$days_count;
		}
		
		return $rates;		
	}
	function get_school_amount_category($school_id=null)
	{
		$school_id = intval($school_id);
		$sch_data = $this->db->query("select amount_category from schools where school_id=?",array($school_id))->row(); 
		return $sch_data->amount_category;
	}
	function get_month_days($date)
	{
		return  $this->db->query("SELECT DAY(LAST_DAY(?)) as tdays",array($date))->row()->tdays ; 
	}
}
?>