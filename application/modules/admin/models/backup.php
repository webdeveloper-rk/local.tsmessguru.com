<?php 
function get_item_price($district_id=null,$item_id=null)
	{
		  $qry = "select price from quotation_prices where item_id='$item_id' and quotation_id in
			(select max(quotation_id) as quotation_id  from quotations where 	district_id='$district_id'  and status='active'  )";
			
		$rs = $this->db->query($qry);
		if($rs->num_rows()==0)
		{
			$price = 0;
			return number_format($price , 2, '.', '');
		}
		else{
			$data= $rs->row();
			return  number_format($data->price , 2, '.', ''); 
		}
	}
	function get_items_price($district_id=null)
	{
		$qry = "select item_id,price from quotation_prices where quotation_id in(select max(quotation_id) as quotation_id  from quotations where 	district_id='$district_id'  and status='active'  )";
		$rs = $this->db->query($qry);
		$list = array(); 
		foreach($rs->result() as $row)
		{
			$list[$row->item_id] = $row->price;
		}
		return $list;
	}
	function get_itemdetails($item_id=null)
	{
		$qry = "select *  from items where item_id='$item_id'";
		$rs = $this->db->query($qry);
		$data = $rs->row();
		return $data;
	}
	function insert_purchase_entry($school_id=null,$item_id=null,$qty=null,$school_price=null,$date=null)
	{
			if($date==null)
			{
				$date = date('Y-m-d');
			}
			$today_rs = $this->db->query("select  entry_id from   balance_sheet where  school_id='$school_id' and 
							item_id='$item_id' and  entry_date='$date'");
			 
			$datenow = date('Y-m-d H:i:s');
			
			//if   purchase entry already exists for todat delete the record and and new record
			if($today_rs->num_rows()>0)
			{
				  $qry = "update balance_sheet  set purchase_quantity = '$qty',	purchase_price='$school_price'
														where school_id = '$school_id'
														and item_id = '$item_id' 
														and  	entry_date= '$date'";
				$this->db->query($qry );
			}
			else {
					
					
					$closing_entries= $this->get_opening_balance($school_id,$item_id,$date);
					
				 		 
					  $qry ="insert into balance_sheet set 
														school_id = '$school_id',
														item_id = '$item_id',
														entry_date= '$date',
														purchase_quantity = '$qty',
														purchase_price = '$school_price', 
														opening_quantity = '".$closing_entries['closing_qty']."', 
														opening_price = '".$closing_entries['closing_price']."', 
														created_time= '$datenow' ";
					$this->db->query($qry);
			}
			//echo $qry;			die;
			$this->update_closing_entries($school_id,$item_id,$date);
		return true;
	}
	function get_purchase_entries($school_id=null,$date=null,$item_id=null)
	{
		if($date==null)
			{
				$date = date('Y-m-d');
			}
		$item_condition = '';	
		if($item_id !=null)
			$item_condition = " and item_id='$item_id' " ;
		
		  $qry = "select * from  balance_sheet where school_id='$school_id' and  entry_date='$date' $item_condition "; 
		$rs = $this->db->query($qry);
		$list = array(); 
		foreach($rs->result() as $row)
		{
			$list[$row->item_id] = $row;
		}
		//print_a($list);
		return $list;
	}
	function get_consumption_entries($school_id=null,$date=null,$session_id=null,$item_id =null)
	{
		if($date==null)
			{
				$date = date('Y-m-d');
			}
		$item_condition = '';	
		if($item_id !=null)
			$item_condition = " and item_id='$item_id' " ;
		
		$qry = "select * from balance_sheet where school_id='$school_id'  and  entry_date='$date' $item_condition "; 
		$rs = $this->db->query($qry);
		$list = array(); 
		foreach($rs->result() as $row)
		{
			$list[$row->item_id] = $row;
		}
		//print_a( $list);
		return $list;
	}
	function get_food_sessions($session_id=null)
	{
		 
		$qry = "select * from food_sessions where (session_id='$session_id' and status='active') "; 
		$rs = $this->db->query($qry);
		if($rs->num_rows()==0)
		{
			return null;
		}
		else{
			$data = $rs->row();
			return $data;
		}
	}
	function check_entries_allowed($session_id=null)
	{
		$allowed = false;
		$current_hour  = date('H');
		  $qry = "select * from food_sessions where (session_id='$session_id' and status='active')  and   $current_hour between start_hour and end_hour "; 
		$rs = $this->db->query($qry);
		if($rs->num_rows()==0)
				return false;
		else
				return true;
	}
	function insert_consume_entry($school_id=null,$item_id=null,$qty=null,$price=null,$date=null,$session_id=null)
	{
			if($date==null)
			{
				$date = date('Y-m-d');
			}
			$today_rs = $this->db->query("select  entry_id from  balance_sheet    where school_id = '$school_id'
														and item_id = '$item_id' and  	entry_date= '$date'");
			  
			
			$datenow = date('Y-m-d H:i:s');
			
			//if    already exists for todat update the record and and new record
			$session_column = '';
			$session_column_price = '';
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
				
			if($today_rs->num_rows()>0)
			{
				$this->db->query("update   balance_sheet set  
														$session_column_qty = '$qty' ,
														$session_column_price ='$price' 
														where school_id = '$school_id'
															and item_id = '$item_id'   
															and  	entry_date= '$date'
														");
				 
			}
			else{
				$closing_entries= $this->get_opening_balance($school_id,$item_id,$date);
					$qry ="insert into balance_sheet set 
														$session_column_qty = '$qty' ,
														$session_column_price ='$price' ,
														school_id = '$school_id',
														item_id = '$item_id', 
															opening_quantity = '".$closing_entries['closing_qty']."', 
														opening_price = '".$closing_entries['closing_price']."', 
														entry_date= '$date',
														created_time= '$datenow' ";
					$this->db->query($qry);
			}
			$this->update_closing_entries($school_id,$item_id,$date);
		return true;
	}
	function get_openingbalance_entries($school_id=null,$item_id=null)
	{
		 
		$item_condition = '';	
		if($item_id !=null)
			$item_condition = " and item_id='$item_id' " ;
		
		$qry = "select * from  balance_sheet where school_id='$school_id' and record_type='closing_balance' and entry_date='2016-06-30' $item_condition "; 
		$rs = $this->db->query($qry);
		$list = array(); 
		foreach($rs->result() as $row)
		{
			$list[$row->item_id] = $row;
		}
		return $list;
	}
	function insert_closingbalance_entry($school_id=null,$item_id=null,$qty=null,$school_price=null)
	{
			 
			$today_rs = $this->db->query("select * from  balance_sheet where  school_id='$school_id' and 
							item_id='$item_id'  and record_type='closing_balance' and entry_date='2016-06-30' ");
			
			$total_price = $qty * $school_price;
			$school_total = number_format($total_price , 2, '.', ''); 
			
			
		 
			 
			
			$datenow = date('Y-m-d H:i:s');
			
			//if   purchase entry already exists for todat delete the record and and new record
			if($today_rs->num_rows()>0)
			{
				$this->db->query("update balance_sheet set closing_quantity='$qty',closing_price='$school_price'
										where school_id = '$school_id' and item_id = '$item_id' 
												and record_type='closing_balance' 
												and entry_date='2016-06-30' ");
			}else{
				
				$closing_entries= $this->get_opening_balance($school_id,$item_id,$date);
					$qry ="insert into balance_sheet set 
														school_id = '$school_id',
														item_id = '$item_id',
														closing_quantity = '$qty',
														closing_price = '$school_price',
														 record_type='closing_balance' ,
														 entry_date='2016-06-30',														 
														created_time= '$datenow' ";
					$this->db->query($qry);
			}
			$this->update_closing_entries($school_id,$item_id,$date);
		return true;
	}
	function get_closing_entries($school_id=null,$item_id=null)
	{
		 
		$item_condition = '';	
		if($item_id !=null)
			$item_condition = " and item_id='$item_id' " ;
		
		$qry = "select * from balance_sheet where school_id='$school_id'   $item_condition  and record_type='closing_balance'"; 
		$rs = $this->db->query($qry);
		$list = array(); 
		foreach($rs->result() as $row)
		{
			$list[$row->item_id] = $row;
		}
		return $list;
	}
	 
	 
	function get_opening_balance($school_id,$item_id,$date=null)
	{
		$qry = "select max(entry_date) as edate,item_id,closing_quantity,closing_price, TRUNCATE((closing_quantity*closing_price),2) as closing_balance
					from balance_sheet where entry_date<'$date	' and item_id='$item_id' and school_id='$school_id'"; 
		
		$rs = $this->db->query($qry);
		$closing_array = array('closing_qty'=>0,'closing_price'=>0,'closing_balance'=>0);		
		if($rs->num_rows()>0)
		{
			$cdata = $rs->row();
			$closing_array['closing_qty']	= 	$cdata->closing_quantity;
			$closing_array['closing_price']	= 	$cdata->closing_price;
			$closing_array['closing_balance']	= 	$cdata->closing_balance;	
		}
		return $closing_array ;
	}
	function update_closing_entries($school_id,$item_id,$date=null){
		
		$qry = "update balance_sheet set 
					closing_price=((opening_quantity * opening_price))+ (purchase_quantity*purchase_price) - ( (session_1_qty*session_1_price)+(session_2_qty*session_2_price)+(session_3_qty*session_3_price)+(session_4_qty*session_4_price)) , closing_quantity=(opening_quantity+purchase_quantity) - (session_1_qty+session_2_qty+session_3_qty+session_4_qty)
					where school_id='$school_id' and item_id='$item_id' and entry_date='$date'";
		$rs = $this->db->query($qry);
	}
	function initiate_item($school_id,$item_id=null)                                                                                                                                                         
	{
		 
			$date = date('Y-m-d');			
			$today_rs = $this->db->query("select  entry_id from   balance_sheet where  school_id='$school_id' and 
							item_id='$item_id' and  entry_date='$date'");
			 
			$datenow = date('Y-m-d H:i:s');
			//if   purchase entry already exists for todat delete the record and and new record
			if($today_rs->num_rows()==0)
			{
			 
					$closing_entries= $this->get_opening_balance($school_id,$item_id,$date);			
				 		 
					  $qry ="insert into balance_sheet set 
														school_id = '$school_id',
														item_id = '$item_id',
														entry_date= '$date',
														opening_quantity = '".$closing_entries['closing_qty']."', 
														opening_price = '".$closing_entries['closing_price']."', 
														created_time= '$datenow' ";
					$this->db->query($qry);
					$this->update_closing_entries($school_id,$item_id,$date);
			}
	}
	function check_quantity($school_id,$item_id,$date,$consumption_qty)
	{
			$today_rs = $this->db->query("select  closing_quantity from   balance_sheet where  school_id='$school_id' and 
							item_id='$item_id' and  entry_date='$date' and closing_quantity >='$consumption_qty'");
			if($today_rs->num_rows()>0)
			{
				return true;
			}				
			else{
				return false;
			}
		
		
	}
	function get_item_purchase_price($school_id,$item_id,$date,$type=null)
	{
		$price = '0.00';

		$today_rs = $this->db->query("select  purchase_price from balance_sheet where  school_id='$school_id' and 
		item_id='$item_id' and  entry_date='$date' ");
		if($today_rs->num_rows()==0)
		{
			$price = $this->get_item_price($this->session->userdata("district_id"),$item_id);								
		}
		else{
			$data = $today_rs->row();
			return $data->purchase_price;
		}

		
	}