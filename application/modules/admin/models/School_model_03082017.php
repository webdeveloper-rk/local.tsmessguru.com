<?php
class School_model extends CI_Model {

    var $table;

    function __construct() {
        parent::__construct();
    }
	function get_schooldata($school_id=null)
	{
		$rs = $this->db->query("select * from schools where school_id='".$school_id."'");
		$school_data  = $rs->row();
		return $school_data;
	}
	function get_quotation_id($district_id=null)
	{
			$rs = $this->db->query("select   quotation_id    from quotations where 	
			district_id='$district_id'  and status='active' order by quotation_id desc limit 0,1");
			$data= $rs->row();
			return $data->quotation_id;
			
	}
	function get_school_itemprices($school_id=null){
		$qry = "select * from school_item_prices where school_id = '$school_id'";
		$rs = $this->db->query($qry);
		$list = array(); 
		foreach($rs->result() as $row)
		{
			$list[$row->item_id] = $row->price_per_kg;
		}
		return $list;
	}
	function get_items_price($district_id=null)
	{
		$tsql = "select quotation_id  from quotations where 	district_id='$district_id'  and status='active' order by quotation_id desc limit 0,1";
		$ttrs = $this->db->query($tsql);
		$data = $ttrs->row();
		
		$quotation_id = $data->quotation_id;
		$qry = "select item_id,price from quotation_prices where quotation_id = '$quotation_id'";
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
	function set_initial_balance_entry($school_id,$item_id,$qty,$school_price)
	{
	
			$datenow = date('Y-m-d H:i:s');
			$total_price = $qty * $school_price;
			$school_total = number_format($total_price , 2, '.', ''); 
			
			$entry_date = '2016-08-31';
			$entry_date_need_to_update = '2016-08-01';
			
			$rs = $this->db->query("select * from  balance_sheet where  school_id='$school_id' and 
							item_id='$item_id'  and record_type='closing_balance' and entry_date='2016-08-31' ");
	
			if($rs->num_rows()>0)
			{
				 $qry = "update balance_sheet set 
										purchase_quantity='$qty',purchase_price='$school_price',
										closing_quantity='$qty',closing_price='$school_price'
										where school_id = '$school_id' and item_id = '$item_id' 
												and record_type='closing_balance' 
												and entry_date='$entry_date' ";
				$this->db->query($qry);
			}else{
					$qry ="insert into balance_sheet set 
														school_id = '$school_id',
														item_id = '$item_id',
														purchase_quantity = '$qty',
														purchase_price = '$school_price',
														closing_quantity = '$qty',
														closing_price = '$school_price',
														 record_type='closing_balance' ,
														 entry_date='$entry_date',														 
														created_time= '$datenow' ";
					$this->db->query($qry);
			}
			$this->update_entries($school_id,$item_id,$entry_date_need_to_update);
			//echo $qry;
			// die;
			
			
		return true;	
	}
	function get_initial_balances($school_id,$item_id=null)
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
	function get_initial_balances_form($school_id,$item_id)
	{
	
		$data_arr = array('qty'=>0,'price'=>0);		
		     $qry = "select * from balance_sheet where school_id='$school_id'   and item_id='$item_id'  and record_type='closing_balance'"; 
		$rs = $this->db->query($qry);
		if($rs->num_rows()>0)
		{
			$data = $rs->row();
			$data_arr = array('qty'=>$data->closing_quantity,'price'=>$data->purchase_price);	
		}
		else{
			 //get district id based on school_id
			 $school = $this->get_schooldata($school_id);
			 $district_id = $school->district_id;
			 $prices = $this->get_items_price($district_id);
			// $distprice = $prices[$item_id];
			 
			$data_arr = array('qty'=>'0.00','price'=>'0.00');	
		}
		return $data_arr;
	}
	/*
	
	*/
	function get_intial_balance($school_id,$item_id,$date=null)
	{
		$qry = "select   entry_date  as edate,item_id,closing_quantity,closing_price, TRUNCATE((closing_quantity*closing_price),2) 
					as closing_balance
					from balance_sheet where entry_date<'$date	' and item_id='$item_id' and school_id='$school_id' order by entry_id desc limit 0,1"; 
		
		$rs = $this->db->query($qry);
		$closing_array = array('closing_qty'=>0,'closing_price'=>0,'closing_balance'=>0);		
		if($rs->num_rows()>0)
		{
			$cdata = $rs->row();
			$closing_array['closing_qty']	= 	$cdata->closing_quantity;
			$closing_array['closing_price']	= 	$cdata->closing_price;
			$closing_array['closing_balance']	= 	$cdata->closing_balance;	
		}
		//print_a($closing_array);
		$price = $closing_array['closing_price'];
			if($price==0)
			{
				$itemcosts = $this->get_items_price($this->session->userdata("district_id"));
				$price = $itemcosts[$item_id];
			}
			$closing_array['closing_price']	= 	$price ;
		return $closing_array ;
	}
	/*
	
	*/
	
	function initiate_item($school_id,$item_id=null,$date=null)                                                                                                                                                         
	{
		 if($date==null){
				$date = date('Y-m-d');		
		 }
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
					$this->update_closing_entries($school_id,$item_id,$date);
			}
	}
	/*
	
	*/
	function get_balance_entries_today($school_id=null,$date=null,$item_id=null)
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
	/*
	
	*/
	function get_consumption_price_today($school_id=null,$session_id=null)
	{
		if($date==null)
			{
				$date = date('Y-m-d');
			}
		//$qry = "select now()";
		//echo   $qry = "select item_id,sum(  (old_stock_qty* 	old_stock_price) + 	(new_stock_qty*new_stock_price	))    as total from  daily_item_consumtions where school_id='$school_id' and 	session_id='$session_id' and  entry_date='$date'  group by item_id "; 
		//$rs = $this->db->query($qry);
		$list = array(); 
		/*if($rs->num_rows()>0){
			foreach($rs->result() as $row)
			{
				$list[$row->item_id] = $row->total;
			} 
		}*/
		 if($_GET['test']==1){
			 echo $qry;
			 print_a($list);
		 }
		return $list;
	}
	
	function update_closing_entries($school_id,$item_id,$date=null){
		
		
		
		
		$qry = "update balance_sheet set 
					closing_price=0,
					closing_quantity=(opening_quantity+purchase_quantity) - (session_1_qty+session_2_qty+session_3_qty+session_4_qty)
					where school_id='$school_id' and item_id='$item_id' and entry_date='$date'";
		$rs = $this->db->query($qry);
	}
	/*
	*/
	function get_purchase_item_form($school_id,$date,$item_id)
	{
		$data_arr = array('qty'=>0,'price'=>0);
		$qry = "select * from  balance_sheet where school_id='$school_id' and  entry_date='$date' and item_id='$item_id' "; 
		$rs = $this->db->query($qry);
		if($rs->num_rows()>0)
		{
			$data = $rs->row();
			$data_arr = array('qty'=>$data->purchase_quantity,'price'=>$data->purchase_price);
		}
		if($data_arr['price']==0)
		{
			//get district id based on school_id
			 $school = $this->get_schooldata($school_id);
			 $district_id = $school->district_id;
			 //$prices = $this->get_items_price($district_id);
			 //$distprice = $prices[$item_id];
			 //$data_arr['price'] =  $distprice;
		}
		
		return $data_arr;
	}
	/*
	
	*/
	function insert_purchase_entry($school_id=null,$item_id=null,$qty=null,$school_price=null,$date=null)
	{
			if($date==null)
			{
				$date = date('Y-m-d');
			}
			$today_rs = $this->db->query("select  entry_id from   balance_sheet where  school_id='$school_id' and 
							item_id='$item_id' and  entry_date='$date'");
			 
			$datenow = date('Y-m-d H:i:s');
			
			//if   purchase entry already exists for today update the record 
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
														opening_price = '0', 
														created_time= '$datenow' ";
					$this->db->query($qry);
			}
			//echo $qry;			die;
			$this->update_closing_entries($school_id,$item_id,$date);
		return true;
	}
	/*
	
	
	*/
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
	/*
	*/
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
	/*
	
	*/
	function get_school_closing_quantities($school_id,$date)
	{
	  	$sql = "select bs.item_id,bs.closing_quantity as 	closing_quantity,bs.entry_date from balance_sheet bs inner join 
					(
						select  
						item_id,max(entry_date) as entry_date  from  balance_sheet   where 
						school_id = '$school_id'   and  entry_date <='$date' group by item_id
					) as bsheet 
					on bs.item_id = bsheet.item_id and bs.entry_date = bsheet.entry_date and bs.school_id='$school_id'  group by bs.item_id";
		$rs  = $this->db->query($sql);				
		$list = array();
		foreach($rs->result() as $row)
		{
			$list[$row->item_id] = $row->closing_quantity;
		}
		return $list;
	}
	function get_closing_quantity($school_id,$item_id,$date)
	{
		   $set_rs = $this->db->query("select item_id,max(entry_date) as entry_date  from  balance_sheet  where  school_id = '$school_id'
														and item_id = '$item_id' and  entry_date <='$date'");  
			 
			$qty_allowed = 0;
			if($set_rs->num_rows()>0)
			{
				
				$check_date_data = $set_rs->row();
				$tdat = $this->db->query("select  closing_quantity   from  balance_sheet  where  school_id = '$school_id'
														and item_id = '$item_id' and  entry_date ='".$check_date_data->entry_date."'");
				
				$sdata = $tdat->row();
				$qty_allowed = $sdata->closing_quantity;		
			}
			/*else{
				//$tdat = $today_rs->row();
				//$qty_allowed = $tdat->closing_quantity;	
			}*/
			return $qty_allowed;
	}
	
	/*
	
	*/
	function check_quantity($school_id,$item_id,$date,$consumption_qty,$session_id)
	{
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
			$sessions = array('session_1_qty','session_2_qty','session_3_qty','session_4_qty');
			unset($sessions[array_search($session_column_qty,$sessions)]);
			
			$session_merged = implode("+",$sessions);
			
			
														
			
			
			
			$qry2 = "select  	(opening_quantity+ 	purchase_quantity) - ($session_merged) as balcount from  balance_sheet    where school_id = '$school_id'
														and item_id = '$item_id' and  	entry_date= '$date'";  
			$today_rs = $this->db->query($qry2 );
			$qty_allowed = 0;
			if($today_rs->num_rows()==0)
			{
				$set_rs = $this->db->query("select item_id,max(entry_date) as entry_date  from  balance_sheet  where  school_id = '$school_id'
														and item_id = '$item_id' and  entry_date <='$date'");
				$check_date_data = $set_rs->row();
				$tdat = $this->db->query("select 	closing_quantity  from  balance_sheet  where  school_id = '$school_id'
														and item_id = '$item_id' and  entry_date ='".$check_date_data->entry_date."'");
				
				$sdat = $tdat->row();
				$qty_allowed = $sdat->closing_quantity;		
			}
			else{
				$tdat = $today_rs->row();
				$qty_allowed = $tdat->balcount;
			}
			 
			if($consumption_qty <= $qty_allowed)
			{
				return true;
			}				
			else{
				return false;
			}
		
		
	}
	
	/*
	
	
	*/
	function insert_consume_entry($school_id=null,$item_id=null,$qty=null,$price=null,$date=null,$session_id=null,$post_array)
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
				
				if($post_array['combined_stock']!="1")
				{
					$old_new_entries =  "  session_".$session_id."_old_qty = '0' , session_".$session_id."_old_price='0' ,  session_".$session_id."_new_qty='".$post_array['quantity']."' , session_".$session_id."_new_price = '".$post_array['price']."' , ";
				}
				
			if($today_rs->num_rows()>0)
			{
				$this->db->query("update   balance_sheet set  
														$old_new_entries
														$session_column_qty = '$qty' ,
														$session_column_price ='$price' 
														where school_id = '$school_id'
															and item_id = '$item_id'   
															and  	entry_date= '$date'
														");
				 
			}
			else{
				//die;
				$closing_entries= $this->get_opening_balance($school_id,$item_id,$date);
				//print_a($closing_entries,1);die;
					$qry ="insert into balance_sheet set 
														$old_new_entries 
														$session_column_qty = '$qty' ,
														$session_column_price ='$price' ,
														school_id = '$school_id',
														item_id = '$item_id', 
															opening_quantity = '".$closing_entries['closing_qty']."', 
														opening_price = '0', 
														entry_date= '$date',
														created_time= '$datenow' ";
					$this->db->query($qry);
			}
			$this->update_closing_entries($school_id,$item_id,$date);
		return true;
	}
	function get_opening_balance($school_id,$item_id,$date=null)
	{
		  $qry = "select  (entry_date) as edate,item_id,closing_quantity,closing_price, TRUNCATE((closing_quantity*closing_price),2) as closing_balance
					from balance_sheet where entry_date<'$date	' and item_id='$item_id' and school_id='$school_id' order by entry_date desc limit 0,1"; 
		
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
	/*
	update all school entries balances
	*/
	function update_entries($school_id,$item_id,$entry_date)
	{
		$this->db->trans_start();
		$temp_table ="entries".$school_id.$item_id.rand(10,10000);
		//echo "call item_balances($school_id,$item_id,'$entry_date','$temp_table')";
		//die;
		$rs = $this->db->query("call item_balances($school_id,$item_id,'$entry_date','$temp_table')");
		
		 
		
		$arr_list = array();
		foreach($rs->result() as $row)
		{
		 $arr_list[] =  "update balance_sheet set opening_quantity='".$row->opening_balance."' , opening_price='0',
					closing_quantity='".$row->closing_balance."',closing_price='0'
						where entry_date>='$entry_date	' and item_id='$item_id' and school_id='$school_id' and entry_id='".$row->entry_id."' ";
		}
		mysqli_next_result($this->db->conn_id);
		$queries_list = implode(";",$arr_list);
		//die;
		//$this->db->query($queries_list);
		$this->update_table($arr_list);
		
		$this->db->trans_complete();
		if ($this->db->trans_status() === FALSE)
		{
				// generate an error... or use the log_message() function to log your error
				echo "<script>alert('Error Occured in Update entries. please try again.');window.location.href='".site_url('admin/school/schoolreporttoday')."';</script>'";
				die;
				 
		}
		 
	}
	function update_table($list)
	{
	 	foreach($list as $item=>$tsql){
			$this->db->query($tsql);
		}
	}
	function check_consumption_locked($school_id,$item_id,$session_id)
	{
				if($session_id<1 && $session_id>4)
				{
					//Invalid Session return false session only valid between 1 and 4 
					return false;
				}
				$field_name= "session_".$session_id."_qty";
				$today_date = date('Y-m-d');
				
			  	$qry = "select * from  balance_sheet where $field_name='0.00'  and  school_id='$school_id' and item_id='$item_id' and entry_date='$today_date' "; 
				
				$rs = $this->db->query($qry);
				if($rs->num_rows()==0){
						return true;//allow to edit 
				}
				else{
					return false;		// not allow to edit 
				}		
	}
	
	
}