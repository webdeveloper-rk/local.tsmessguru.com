<?php
class Purchase_model extends CI_Model {

    var $table;

    function __construct() {
        parent::__construct();
    }
	 
	
	
		function get_balance_entries_today($school_id=null,$date=null,$item_id=null)
	{
		if($date==null)
			{
				$date = date('Y-m-d');
			}
		$item_condition = '';	
		if($item_id !=null)
			$item_condition = " and item_id='$item_id' " ;
		 $stock_entry_table = $this->common_model->get_stock_entry_table($date);
		  $qry = "select * from   $stock_entry_table where school_id='$school_id' and  entry_date='$date' $item_condition "; 
		$rs = $this->db->query($qry);
		$list = array(); 
		foreach($rs->result() as $row)
		{
			$list[$row->item_id] = $row;
		}
		//print_a($list);
		return $list;
	}
	 
	 function get_purchase_item_form($school_id,$date,$item_id)
	{
		$data_arr = array('qty'=>0,'price'=>0);
		$qry = "select * from  balance_sheet where school_id='$school_id' and  entry_date='$date' and item_id='$item_id' "; 
		$rs = $this->db->query($qry);
		if($rs->num_rows()>0)
		{
			$data = $rs->row();
			$data_arr = array('qty'=>$data->purchase_quantity,'price'=>$data->purchase_price,'purchase_biil_no'=>$data->purchase_biil_no);
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
	
	function get_itemdetails($item_id=null)
	{
		$qry = "select *  from items where item_id='$item_id'";
		$rs = $this->db->query($qry);
		$data = $rs->row();
		return $data;
	}
	
		function update_purchase_entry($school_id=null,$item_id=null,$qty=null,$school_price=null,$date=null)
	{
			 
			$date = date('Y-m-d');
			 
			$today_rs = $this->db->query("select  entry_id from   balance_sheet where  school_id=? and 
							item_id=? and  entry_date=?",array('$school_id','$item_id','$date'));
			 
			$datenow = date('Y-m-d H:i:s');
			
			//if   purchase entry already exists for today update the record 
			if($today_rs->num_rows()>0)
			{
				$purchase_biil_no = $this->input->post('billno');
				  $qry = "update balance_sheet  set purchase_quantity = '$qty',	purchase_price='$school_price',purchase_biil_no='$purchase_biil_no'
														where school_id = '$school_id'
														and item_id = '$item_id' 
														and  	entry_date= '$date'";
				$this->db->query($qry );
				//echo $qry;die;
			}
			else {
					
					
					$closing_entries= $this->get_opening_balance($school_id,$item_id,$date);
					
				 		 $purchase_biil_no = $this->input->post('billno');
					  $qry ="insert into balance_sheet set 
														school_id = '$school_id',
														item_id = '$item_id',
														entry_date= '$date',
														purchase_quantity = '$qty',
														purchase_price = '$school_price', 
														purchase_biil_no='$purchase_biil_no',
														opening_quantity = '".$closing_entries['closing_qty']."', 
														opening_price = '0', 
														created_time= '$datenow' ";
					$this->db->query($qry);
			}
			//echo $qry;			die;
			$this->update_closing_entries($school_id,$item_id,$date);
		return true;
	}
	
}