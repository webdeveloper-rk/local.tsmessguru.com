<?php
class Consumption_model extends CI_Model {

    var $table;

    function __construct() {
        parent::__construct();
    }
	 
	 function check_entries_allowed($session_id=1)
	{
		$session_id = intval($session_id);
		 
		$allowed = false;
		$current_hour  = date('H');
		$week_day = date('w');
		$today_date  = date('d-m-Y');
		  
			if($this->config->item("saturday_exemption")==1 && $week_day==6)
			{
					return true;
			}
			if($this->config->item("sunday_exemption")==1 && $week_day==0)
			{
				return true;
			}
			if($this->config->item("holiday_exemption")==1)
			{
				$holiday_list = $this->session->userdata('holidays');
				if(in_array($today_date,$holiday_list))
				{
					return true;
				}				
			}

		$qry = "select * from food_sessions where (session_id=? and status='active') and  date_format(now(),'%H:%i:%s')  between start_hour and end_hour "; 
		
		if( $this->session->userdata("operator_type")=="CT")
		{
			$qry = "select * from food_sessions where (session_id=? and status='active')  and   now() between ct_start_hour and ct_end_hour "; 
		}
 
		$rs = $this->db->query($qry,array($session_id));
		$allowed = false;
		if($rs->num_rows()==0)
				$allowed = false;
		else
				$allowed = true;
			
		return $allowed;	
	}

	
	
	
	
		function get_food_sessions($session_id=null)
	{
		 
		$qry = "select * from food_sessions where (session_id=? and status='active') "; 
		$rs = $this->db->query($qry,array($session_id));
		if($rs->num_rows()==0)
		{
			return null;
		}
		else{
			$data = $rs->row();
			return $data;
		}
	}

	
	
		function check_allowed_authorise($session_id,$school_id)
	{
			
			
			$sql = "SELECT * FROM  food_sessions  WHERE session_id=?  and  date_format(now(),'%H:%i:%s') BETWEEN start_hour and end_hour";
			$crs = $this->db->query($sql,array($session_id));
			if($crs->num_rows()==0)
			{
				return false;
			}
			else{
				$var = "session_".$session_id."_status";
				$today = date('Y-m-d');
				
				  	$tsql =  "SELECT * FROM `caretaker_confirmation` WHERE school_id=?   and entry_date=?";
				$trs = $this->db->query($tsql,array($school_id,$today));
				if($trs->num_rows()>0)
				{
					$sdata = $trs->row();
					//echo $sdata->$var;
					if($sdata->$var == "authorised")
					{
						//echo $sdata->$var;
						return false ;
					}
					else {
						return true;
					}
				}
				else{
						return false;
				}
			}
	}
	function get_itemdetails($item_id=null)
	{
		$qry = "select *  from items where item_id=? and status=1";
		$rs = $this->db->query($qry,array($item_id));
		$data = $rs->row();
		return $data;
	}
	
	
	
	function get_authorise($session_id,$school_id)
	{
			$array_codes = array("1"=>"Timedout","2"=>"INTIME");
			
			$sql = "SELECT * FROM  food_sessions  WHERE session_id=?  and  date_format(now(),'%H:%i:%s') BETWEEN ct_start_hour and ct_end_hour";
			 
			$crs = $this->db->query($sql,array($session_id));
			if($crs->num_rows()==0)
			{
				return array("code"=>1,"message"=>"Timed out");
			}
			else{
				$var = "session_".$session_id."_status";
				$today = date('Y-m-d');
				
				      $tsql =  "SELECT * FROM `caretaker_confirmation` WHERE school_id=?  and entry_date=?";
				$trs = $this->db->query($tsql,array($school_id,$today));
				if($trs->num_rows()>0)
				{
					$sdata = $trs->row(); 
					return array("code"=>2,"message"=>"IN Time",'status'=>$sdata->$var);
				 
				}
				 return array("code"=>3,"message"=>"Missed Entry");
			}
			return '';
	}
		function check_consumption_locked($school_id,$item_id,$session_id)
	{
		//print_a($this->session->all_userdata());
		
		if($this->session->userdata("operator_type")=="CT")
			{
					return true;
			}
			
				if($session_id<1 && $session_id>4)
				{
					//Invalid Session return false session only valid between 1 and 4 
					return false;
				}
				$field_name= "session_".$session_id."_qty";
				$today_date = date('Y-m-d');
				 $stock_entry_table = $this->common_model->get_stock_entry_table($today_date);
			  	$qry = "select * from  $stock_entry_table where $field_name='0.00'  and  school_id=? and item_id=? and entry_date=? "; 
				
				$rs = $this->db->query($qry,array($school_id,$item_id,$today_date));
				if($rs->num_rows()==0){
						return true;//allow to edit 
				}
				else{
					return false;		// not allow to edit 
				}		
	}
		function get_entry_set($entry_id=0,$session_id='0')
	{
		//Bypass checkming quantity
		//return true;
		$session_id = intval($session_id);
		$session_column_qty = '';
			if($session_id==1)
			{
				$session_column_qty = 'session_1_qty' ;
				$session_column_price  ='session_1_price';
			}
			else if($session_id==2)
			{
				$session_column_qty = 'session_2_qty' ;
				$session_column_price  ='session_2_price';
			}
			else if($session_id==3)
			{
				$session_column_qty = 'session_3_qty' ;
				$session_column_price  ='session_3_price';
			}
			else if($session_id==4)
			{
				$session_column_qty = 'session_4_qty' ;
				$session_column_price  ='session_4_price';
			}
			else{
				die;
			}
			$sessions = array('session_1_qty','session_2_qty','session_3_qty','session_4_qty');
			unset($sessions[array_search($session_column_qty,$sessions)]);
			
			$session_merged = implode("+",$sessions); 
			
			 $stock_entry_table = $this->common_model->get_stock_entry_table(date('Y-m-d'));
			
			$qry2 = "select *, (opening_quantity+ 	purchase_quantity) as today_quantity,
							 (opening_quantity+ 	purchase_quantity) - ($session_merged) as balcount 
								  from  $stock_entry_table     where  entry_id=?";  
			$today_rs = $this->db->query($qry2 ,array($entry_id));
			$qty_allowed = 0;
			if($today_rs->num_rows()==0)
			{  
				die("Invalid Request");
			}				
			else{
				return  $today_rs->row();
			}
		
		
	}
	function update_consume_entry_byid($arguments=array(),$date=NULL)
	{
				//print_a($arguments,1);
				if($date == NULL)
						$date = date('Y-m-d');
		
			 $post_array = $arguments['post_array'];
			 
			 //print_a($post_array);
			 
			 foreach( $post_array as $pkey=>$pvalue)
			 {
				$post_array[$pkey] = floatval($pvalue);

			}
			 $session_id = intval($arguments['session_id']);
			 $entry_id =    $arguments['entry_id'];
			 $closing_qty = floatval($arguments['closing_qty']);
			 
			 $qty = floatval($arguments['tqty']);
			 $price  = floatval($arguments['tprice']);
			 
			 
			 $entry_details = $this->db->query("select * from balance_sheet where entry_id=?",array($entry_id))->row();
			 $school_id = $entry_details->school_id;
			 $item_id= $entry_details->item_id;
			 $entry_date =  $entry_details->entry_date; 
			 $price_data = $this->get_price_details($school_id,$item_id,$entry_date);
			
			//over write if item price is locked 
			if($price_data['lock'] == true){
					 $price  =  $price_data['price'] ; 
					 $post_array['old_price']  =  $price_data['price'] ; 
					 $post_array['price']  =  $price_data['price'] ; 
			}
			 
			$session_column = '';
			$session_column_price = '';
			$old_new_entries = '';
			if($session_id==1)
			{
				$session_column_qty = 'session_1_qty' ;
				$session_column_price  ='session_1_price';
				$old_new_entries =  "  session_1_old_qty = '".$post_array['old_quantity']."' , session_1_old_price='".$post_array['old_price']."' , session_1_new_qty='".$post_array['quantity']."' , session_1_new_price = '".$post_array['price']."' , ";
			}
			else if($session_id==2)
			{
				$session_column_qty = 'session_2_qty' ;
				$session_column_price  ='session_2_price';
				$old_new_entries =  "  session_2_old_qty = '".$post_array['old_quantity']."' , session_2_old_price='".$post_array['old_price']."' , session_2_new_qty='".$post_array['quantity']."' , session_2_new_price = '".$post_array['price']."' , ";
			}
			else if($session_id==3)
			{
				$session_column_qty = 'session_3_qty' ;
				$session_column_price  ='session_3_price';
				$old_new_entries =  "  session_3_old_qty = '".$post_array['old_quantity']."' , session_3_old_price='".$post_array['old_price']."' , session_3_new_qty='".$post_array['quantity']."' , session_3_new_price = '".$post_array['price']."' , ";
			}
			else if($session_id==4)
			{
				$session_column_qty = 'session_4_qty' ;
				$session_column_price  ='session_4_price';
				$old_new_entries =  "  session_4_old_qty = '".$post_array['old_quantity']."' , session_4_old_price='".$post_array['old_price']."' , session_4_new_qty='".$post_array['quantity']."' , session_4_new_price = '".$post_array['price']."' , ";
			}
			else
			{
				die("<h1>invalid session</h1>");
			}
				
				if($post_array['combined_stock']!="1")
				{
					$old_new_entries =  "  session_".$session_id."_old_qty = '0' , session_".$session_id."_old_price='0' ,  session_".$session_id."_new_qty='".$post_array['quantity']."' , session_".$session_id."_new_price = '".$post_array['price']."' , ";
				}
			 
				$stock_entry_table = $this->common_model->get_stock_entry_table($date);
				$t_query = "update   $stock_entry_table set  
														$old_new_entries
														$session_column_qty = ? ,
														$session_column_price =? ,
														closing_quantity =  ?  
														where  entry_id= ?
														";
					 
				//echo $t_query;die;
				$this->db->query($t_query,array($qty,$price,$closing_qty,$entry_id)); 
		return true;
	}
	
	function get_price_details($school_id,$item_id,$entry_date)
	{
		$ouput_arr = array('lock'=>false,'price'=>0.00);
		if(  $this->config->item("dpc_rates_enabled")==true) {
			
			
			//central stock ::  lock price and return price 
			$icrs = $this->db->query("select * from item_per_head where item_id=?",array($item_id)); 
			if($icrs->num_rows()>0){
				
				 
				$it_row = $this->db->query("select * from item_prices where item_id=?",array($item_id)); 
				$rdata = $it_row->row(); 
				$new_price  = $oldstock_price  = $rdata->amount; 
				$ouput_arr = array('lock'=>true,'price'=>$new_price);
				return $ouput_arr;
			}
			//School wise item prices 
			else{
				
					$is_greater = $this->db->query("select ? >= ? as is_greater",array($entry_date,$this->config->item("dpc_rates_start_date")))->row()->is_greater;
					
					if($this->input->ip_address()=="175.101.67.204"){
							//echo $this->db->last_query();
					}
					if($is_greater ==1)
					{
						//exclude krishna district vegetables 
							$exclude_district_ids  = array(6,13);//6 krishna 13 west godavari district ids
							$district_id  = $this->db->query("select district_id from schools where school_id=?",array($school_id))->row()->district_id;
							if(in_array($district_id  , $exclude_district_ids )){
										$it_row = $this->db->query("select * from 	krishna_veg_excludes where   item_id=?",array( $item_id)); 
										if($it_row->num_rows()>0){
													 $ouput_arr = array('lock'=>false,'price'=>0.00);//dont lock item 
													return $ouput_arr;	
										}
							}
						//else return dpc rates 
						$financial_year_id = $this->db->query("select * from 	dpcrates_years where status='active'")->row()->financial_year_id; 
						 
						$it_row = $this->db->query("select * from 	dpc_rates where school_id= ? and item_id=? and financial_year_id=?",array($school_id,$item_id,$financial_year_id )); 
						//echo $this->db->last_query();
						if($it_row->num_rows()>0){
									$rdata = $it_row->row(); 
									$new_price  = $oldstock_price  = $rdata->amount; 
									$ouput_arr = array('lock'=>true,'price'=>$new_price);//lock item
									return $ouput_arr;	
						}
					
					}	
			}
		}					
		return $ouput_arr ;
	
	}
	
	 
}