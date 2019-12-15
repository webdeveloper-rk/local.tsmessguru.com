<?php
class Common_model extends CI_Model {

    

    function __construct() {
        parent::__construct();
    }
	 function get_updatable_entries($inputs_array=array())
	 {
		$school_id = $inputs_array['school_id'];
		$item_id = $inputs_array['item_id'];
		$entry_date = $inputs_array['entry_date'];
		$purchase_qty = $inputs_array['pqty'];
		$purchase_price = $inputs_array['pprice'];
		
		$bf_qty = $inputs_array['bf_qty'];
		$bf_price = $inputs_array['bf_price'];
		
		$lu_qty = $inputs_array['lu_qty'];
		$lu_price = $inputs_array['lu_price'];
		
		
		$sn_qty = $inputs_array['sn_qty'];
		$sn_price = $inputs_array['sn_price'];
		
		$di_qty = $inputs_array['di_qty'];
		$di_price = $inputs_array['di_price'];
		
		
		$checkrecord_rs = $this->db->query("select *,date_format(entry_date,'%d-%M-%Y') as entry_date_dp  from balance_sheet where school_id=? and item_id=? and entry_date  = ?  ",array($school_id,$item_id,$entry_date));
		if($checkrecord_rs->num_rows()==0)
		{
			echo "<h1>Record not exists Please contact administrator</h1>";
			die;
		}
		
		$return_data  = array();
		$negative_reached = false;
		$negative_date = NULL;
		$opening_balance = NULL;
		$entries_list  = array();
		$entries_rs = $this->db->query("select *,date_format(entry_date,'%d-%M-%Y') as entry_date_dp  from balance_sheet where school_id=? and item_id=? and entry_date >= ? order by entry_date asc ",array($school_id,$item_id,$entry_date));
		foreach($entries_rs->result() as $erow)
		{
			 
			if($erow->entry_date == $entry_date)
			{
				 $opening_balance =  $entries_list[$erow->entry_date]['opening_quantity'] =	$erow->opening_quantity;
				$entries_list[$erow->entry_date]['purchase_quantity']  = $purchase_qty ;
				$entries_list[$erow->entry_date]['purchase_price']  =	 $purchase_price ;
				 
				
				$entries_list[$erow->entry_date]['session_1_qty'] 		 = $bf_qty ;
				$entries_list[$erow->entry_date]['session_1_price']  = $bf_price;
				
				
				$entries_list[$erow->entry_date]['session_2_qty'] 		 = $lu_qty ;
				$entries_list[$erow->entry_date]['session_2_price']  = $lu_price;
				
				$entries_list[$erow->entry_date]['session_3_qty'] 		 = $sn_qty ;
				$entries_list[$erow->entry_date]['session_3_price']  = $sn_price;
				
				$entries_list[$erow->entry_date]['session_4_qty'] 		 = $di_qty ;
				$entries_list[$erow->entry_date]['session_4_price']  = $di_price;
				 
			}
			else
			{
				$entries_list[$erow->entry_date]['opening_quantity'] = $opening_balance ;
				$entries_list[$erow->entry_date]['purchase_quantity']  = $erow->purchase_quantity ;
				$entries_list[$erow->entry_date]['purchase_price']  =	 $erow->purchase_price ;
				 
				
				$entries_list[$erow->entry_date]['session_1_qty'] 		 = $erow->session_1_qty ;
				$entries_list[$erow->entry_date]['session_1_price']  = $erow->session_1_price ;
				
				
				$entries_list[$erow->entry_date]['session_2_qty'] 		 = $erow->session_2_qty ;
				$entries_list[$erow->entry_date]['session_2_price']  = $erow->session_2_price ;
				
				$entries_list[$erow->entry_date]['session_3_qty'] 		 = $erow->session_3_qty ;
				$entries_list[$erow->entry_date]['session_3_price']  = $erow->session_3_price ;
				
				$entries_list[$erow->entry_date]['session_4_qty'] 		 = $erow->session_4_qty ;
				$entries_list[$erow->entry_date]['session_4_price']  = $erow->session_4_price   ;
			} 
			 $entries_list[$erow->entry_date]['entry_date_dp']  = $erow->entry_date_dp   ;
			 
			 $total_qty  =  $entries_list[$erow->entry_date]['opening_quantity'] + $entries_list[$erow->entry_date]['purchase_quantity'];
			 $used_qty = $entries_list[$erow->entry_date]['session_1_qty'] + $entries_list[$erow->entry_date]['session_2_qty'] +$entries_list[$erow->entry_date]['session_3_qty'] +$entries_list[$erow->entry_date]['session_4_qty'] ;
			 $closing_quantity =  $total_qty - $used_qty;
			 $entries_list[$erow->entry_date]['closing_quantity']  = $closing_quantity   ;
			 
			 if($closing_quantity<0){			 
				$negative_reached = true;
				if($negative_date == NULL){
					$negative_date = $erow->entry_date_dp ;
				}
			 }
			 $opening_balance  = $closing_quantity; 
		}
		$return_data['negative_date'] = $negative_date;
		$return_data['negative_reached'] = $negative_reached;
		$return_data['entries_list'] = $entries_list;
		return $return_data;
		
	 
	 }
	 
	 function update_entries($school_id,$item_id,$entry_date,$is_temp='')
	{
		$this->db->trans_start();
		$temp_table ="entries".$school_id.$item_id.rand(10,10000);
		 
		if($is_temp !="")
		{
			$rs = $this->db->query("call item_balances_temp($school_id,$item_id,'$entry_date','$temp_table')");
		}else{			
		 
			$rs = $this->db->query("call item_balances($school_id,$item_id,'$entry_date','$temp_table')");
		}
		
		// echo $this->db->last_query(),"<br>";
		
		$arr_list = array();
		foreach($rs->result() as $row)
		{
		 $arr_list[] =  "update balance_sheet set opening_quantity='".$row->opening_balance."' , opening_price='0',
					closing_quantity='".$row->closing_balance."',closing_price='0'
						where   entry_id='".$row->entry_id."' ";
		}
		mysqli_next_result($this->db->conn_id);
		$queries_list = implode(";",$arr_list);
		
		 // echo $queries_list;die;
		//die;
		//$this->db->query($queries_list);
		$this->update_table($arr_list);
		
		$this->db->trans_complete();
		if ($this->db->trans_status() === FALSE)
		{
				// generate an error... or use the log_message() function to log your error
				echo "<script>alert('Error Occured in Update entries. please try again.');window.location.href='".site_url('')."';</script>'";
				die;
				 
		}
		 
	}
	function update_table($list)
	{
	 	foreach($list as $item=>$tsql){
			$this->db->query($tsql);
		}
	}
	function get_month_days($report_date)
	{
		$days = $this->db->query("select day(last_day(?)) as days ",array($report_date))->row()->days;
		return $days;
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
		$days_count  = $this->db->query("SELECT DAY(LAST_DAY(?)) as days",array($choosen_date))->row()->days ; 
		
		 
		$price_sql = "select * from group_prices  where     ? between start_date and end_date";
		$price_rs = $this->db->query($price_sql,array($choosen_date));
		//echo $this->db->last_query();
		$rates = array();
		foreach($price_rs->result() as $stu_price){
			$rates[$stu_price->category][$stu_price->group_code]['amount_per_month'] = $stu_price->amount;
			$rates[$stu_price->category][$stu_price->group_code]['number_of_days'] = $days_count;
			$rates[$stu_price->category][$stu_price->group_code]['per_day'] = $stu_price->amount/$days_count;
		}
		
		return $rates;		
	}
	function get_school_amount_category($school_id)
	{
		$sch_data = $this->db->query("select amount_category from schools where school_id=?",array($school_id))->row(); 
		return $sch_data->amount_category;
	}
	function get_consumption_max_limit($school_id=null)
	{
		$school_id = intval($school_id);
		$sc_row = $this->db->query("select school_id,strength from schools where school_id=? ",array($school_id))->row();
		$strength = $sc_row->strength;
		$max_row = $this->db->query("select per_head_max_amount from day_limit ")->row();
		$per_head_max_amount = $max_row->per_head_max_amount;
		return $strength * $per_head_max_amount; 
	}
	function get_diet_pic_path($entry_date)
	{
		$diet_rs = $this->db->query("select * from diet_pic_paths where ? between start_date and end_date ",array($entry_date));
		 
		 if($diet_rs->num_rows()==0)
		{
			return NULL;
		}
		else{
			$path_data = $diet_rs->row();
			return $path_data;
		} 
	 
	}
	function get_stock_entry_table($date )
	{
		$rs = $this->db->query("select table_name from refer_balance_sheet where ? between start_date and end_date ",array($date));
		if($rs->num_rows()==0){
			return "balance_sheet";
		}else{
			$row = $rs->row();
			return $row->table_name;
		}
		
		 return 'balance_sheet';
	}
	 
	  function get_item_fixed_price($item_id,$school_id)
	{
		
		$site_name = $this->config->item("site_name");
		switch($site_name)
		{
			case 'twhostels':
			
								$item_rs = $this->db->query("select amount from fixed_rates where item_id=?",array($item_id));
								//echo $this->db->last_query();die;
								if($item_rs->num_rows()==0)
								{
									return 0;
								}else{
									$it_data = $item_rs->row();
									return $it_data->amount;
								}
			break;
			default:
						return 0;
		
		}
	}
	function fixed_rate_item_excemption($item_id,$school_id)
	{
		
		$site_name = $this->config->item("site_name");
		switch($site_name)
		{
			case 'twhostels':
			
								$item_rs = $this->db->query("select item_exemption from fixed_rates where item_id=? and item_exemption=1",array($item_id));
								//echo $this->db->last_query();die;
								if($item_rs->num_rows()==0)
								{
									return false;
								}else{
									return true;
								}
			break;
			default:
						return false;
		
		}
	}
	
	 
}