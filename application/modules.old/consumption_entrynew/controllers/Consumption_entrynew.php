<?php 
 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
set_time_limit(0);
 date_default_timezone_set('Asia/Kolkata');
class Consumption_entrynew  extends MX_Controller {

    function __construct() {
        parent::__construct();
        
		if($this->uri->segment(2) !="login") { 
					 Modules::run("security/is_admin");		 
					if ($this->session->userdata("is_loggedin") != TRUE || $this->session->userdata("user_id") == "" ) {
							redirect("admin/login");
							die;
					}
					 			
					if($this->session->userdata("user_role") != "school")
					{
						redirect("admin/login");
							die;
					}
		}
		$this->load->helper('url');
		$this->load->library('grocery_CRUD'); 
		$this->load->library('ci_jwt'); 
		$this->load->model('consumption_model'); 
		$this->load->model('common/common_model'); 
		$this->load->config('config'); 
		 
	}

    function index() {
        redirect("consumption_entrynew/view/1");
    }
 
	function  view($session_id=1)
	{
		
		$this->is_excempted("view",$session_id );
		
		$session_id= intval($session_id);
		$allowed_sessions = array(1,2,3,4);
		if(!in_array($session_id,$allowed_sessions))
		{
			 $this->session->set_flashdata('message', '<div class="alert alert-danger">Invalid Session.</div>');
				redirect('consumption_entrynew'); 
		}
		$message = '';
		$eligibility = $this->consumption_model->check_entries_allowed($session_id);
		//bypass eligibility edit as true for apreisannapurna.com
		if($this->config->item("site_name")=="apreis"){
			$eligibility = true;
		}	
		$sesdata = $this->consumption_model->get_food_sessions($session_id); 
		 
		if(!$eligibility)
		{
			$message =  "<h2 style='color:#FF0000'>Entries locked</h2><br><h3>You have to enter data between ".$sesdata->start_hour_text. " to ".$sesdata->end_hour_text. " only</h3>";			
		}
		
		$session_id_column = '';
			$session_id_column_price = '';
			if($session_id==1)
			{
				$session_id_column_qty = 'session_1_qty' ;
				$session_id_column_price  ='session_1_price';
			}
			else if($session_id==2)
			{
				$session_id_column_qty = 'session_2_qty' ;
				$session_id_column_price  ='session_2_price';
			}
			else if($session_id==3)
			{
				$session_id_column_qty = 'session_3_qty' ;
				$session_id_column_price  ='session_3_price';
			}
			else if($session_id==4)
			{
				$session_id_column_qty = 'session_4_qty' ;
				$session_id_column_price  ='session_4_price';
			}
			else
			{
				die("Invalid Request.");				
			}
		
		$data['session_id'] = $session_id ;
		$data['qty_field'] = $session_id_column_qty ;
		$data['price_field'] = $session_id_column_price;
		
		
		 
		$data['current_session'] = $sesdata;
		$data['data_entry_allowed']=$eligibility; 
		$data['data_entry_text']=$message;  
		
		 
		 
		
		
		
		$condate =  $this->today_date();
		//echo $condate;die;
		$conschool_id = $this->session->userdata("school_id");
	 
		
		$data['authorised']= $this->consumption_model->check_allowed_authorise($session_id,$this->session->userdata("school_id"));
		$stock_entry_table = $this->common_model->get_stock_entry_table($condate);
		
		$qry = "select * from $stock_entry_table where school_id=? and entry_date=?";		
		$data['rset']= $this->db->query($qry,array($conschool_id,$condate)); 
		//echo $this->db->last_query();
		$items_rs = $this->db->query("select * from items where status=1");
		$items = array();
		$not_allowed_items  = array();
		foreach($items_rs->result() as $item_obj)
		{
			$items[$item_obj->item_id] = $item_obj->telugu_name. " - " .$item_obj->item_name; 
			 
		}
		
		  $data['todat_date_text']= date('d-M-Y',strtotime($condate));
		$data['itemnames']= $items; 
		$data['allowed_items']= array_keys($items); 
		$data['users_count'] =0; 
        $data['banks_count'] =0;
        $data['plans_count'] =0;
        $data['payments_count'] = 0; 
		 
        $data["module"] = "consumption_entrynew"; 
        $data["view_file"] = "consumption_list";
        echo Modules::run("template/admin", $data);
	}
	
		/******************************************************
		
		
		
		/******************************************************/
		
	 function consumption_entryform($item_id=null,$session_id=null)
	{
	
		$this->is_excempted("consumption_entryform",$session_id,$item_id );
	
		$session_id= intval($session_id);
		$item_id =  intval( $item_id);
		$school_id =  intval( $this->session->userdata("school_id"));
		//echo $session_id;
		$school_id	=	intval($this->session->userdata("school_id")); 
		$date =	$entry_date = $this->today_date();
		//echo $date;die;
		
		 $stock_entry_table = $this->common_model->get_stock_entry_table($this->today_date());
		$allowed_sessions = array(1,2,3,4);
		if(!in_array($session_id,$allowed_sessions))
		{
			 $this->session->set_flashdata('message', '<div class="alert alert-danger">Invalid Session.</div>');
				redirect('consumption_entrynew'); 
		}
		$sesdata = $this->consumption_model->get_food_sessions($session_id); 
		$data['current_session'] = $sesdata;
		
		$data["item_details"] = $this->consumption_model->get_itemdetails($item_id);
		if(count($data["item_details"])==0)
		{
			$this->session->set_flashdata('message', '<div class="alert alert-danger">Invalid Item.</div>');
				redirect('consumption_entrynew'); 
		}
		 
		 
		$this->load->library('encryption');
		$this->form_validation->set_rules('quantity', 'Quantity', 'required|numeric|greater_than[0]');    
		 
			 
				
		
	
		if($data["item_details"]->status=="0")
		{
			echo "<h1 style='padding:30px;'>Item:".strtoupper($data["item_details"]->item_name)." is not avilable to use.</h1>";
			die;
		}			
				
		
	
		$message = '';
		$eligibility = $this->consumption_model->check_entries_allowed($session_id); 
		$sesdata = $this->consumption_model->get_food_sessions($session_id); 
		
		 $data['authorised']= $this->consumption_model->check_allowed_authorise($session_id,$this->session->userdata("school_id"));
		 //bypass for apreisannapurna.com
		 if($this->config->item("site_name")=="apreis")
		 {
				$eligibility = true;
				 $locked_check = false; 
		 }
		
		if(!$eligibility)
		{
				$message =  "<h2 style='color:#FF0000'>Entries locked</h2><br><h3>You have to enter data between ".$sesdata->start_hour_text. " to ".$sesdata->end_hour_text. " only</h3>";			
				$locked_check = true;
			 
		}
 
			if( $this->session->userdata("operator_type") == "CT"  )
			{
			  $auth_data = $this->consumption_model->get_authorise($session_id,$school_id);
			  //print_r($auth_data );
				if($auth_data['code']==2)
				{
					$eligibility = true;
					if($auth_data['status']=="authorised"){
						$locked_check = true;
					}
					else{
						$locked_check = false;
					}
				}
				else
				{
					$eligibility = true;
					$locked_check = true;
					
				}
			}
			else
			{
				  	$locked_check = $this->consumption_model->check_consumption_locked($school_id,$item_id,$session_id);
			}
		if($this->config->item("site_name")=="apreis")
		 {
				$eligibility = true;
				 $locked_check = false; 
		 }
	  
		if($this->form_validation->run() == true && $eligibility == true  && $locked_check == false && $this->input->post('action')=="submit")
		 {
			   
			 $entry_id_e =  $this->ci_jwt->jwt_web_decode( $this->input->post('consumptionprime')); 
			 $school_id	=	intval($this->session->userdata("school_id"));
			  $old_qty 		= 0;
			  $old_price 		= 0;
			   $price 		= $price_new = 0;
			 
			 $qty 		= 	$total_quantity  = floatval($this->input->post('quantity')); 
			 $entries_data  = $this->consumption_model->get_entry_set($entry_id_e,$session_id);
			 if( $total_quantity > $entries_data->balcount   )
			 {
				 $this->session->set_flashdata('message', '<div class="alert alert-danger">Insufficient Stock quantity. please add purchase entry to add consumption </div>');
				redirect('consumption_entrynew/consumption_entryform/'.$item_id.'/'.$session_id); 
			 }
			 
			 
			 $possible_entries  = $this->get_price_details($item_id,false,$qty);
			 if(count( $possible_entries)==0)
			 {
			 	 $this->session->set_flashdata('message', '<div class="alert alert-danger">Price details not found. please contact administrator.</div>');
				redirect('consumption_entrynew/consumption_entryform/'.$item_id.'/'.$session_id); 
			 }
			  $old_quantities= 0;
			  $old_amount = 0;
				// print_a($possible_entries ); 
			 if(count($possible_entries)==1)
			 {
						$old_qty 		= 	0; 
						$old_price 		= 	0; 
						$_POST['quantity'] = $possible_entries[0]['qty'];
						$_POST['price'] = $possible_entries[0]['price'];
						
						$_POST['old_quantity'] = 0;
						$_POST['old_price'] = 0;
			 }
			 if(count($possible_entries)>1)
			 {
				
			   for($i=0;$i<count($possible_entries)-1;$i++)
			   {
					$old_quantities = $old_quantities + $possible_entries[$i]['qty'];
					$old_amount = $old_amount  + ($possible_entries[$i]['qty'] * $possible_entries[$i]['price']);
			   }
			   $avg_old_price=  $old_amount/$old_quantities;
				$_POST['old_quantity'] = $old_quantities;
				$_POST['old_price'] = $avg_old_price;
				
				$last_record = count($possible_entries);
				$_POST['quantity'] = $possible_entries[$last_record-1]['qty'];
				$_POST['price'] = $possible_entries[$last_record-1]['price'];
						
			   
			   
			 }
			 
			 $total_price = ($_POST['quantity'] * $_POST['price'])   + ($_POST['old_quantity']*$_POST['old_price']);
			  if($total_price==0)
			 {
			 	 $this->session->set_flashdata('message', '<div class="alert alert-danger">Price details not found. please contact administrator.</div>');
				redirect('consumption_entrynew/consumption_entryform/'.$item_id.'/'.$session_id); 
			 }
			 $qty 		=  floatval($_POST['quantity'] ); 
			 $price 		= $price_new = 	floatval($_POST['price'] ); 
			 $old_qty 		= 	floatval($_POST['old_quantity']); 
			 $old_qty 		= 	floatval($_POST['old_quantity']); 
			 $old_price 	= 	floatval($_POST['old_price']); 

 
			 
			 
			 
			 
			 //Calculate avg price 
			
			 if( $old_qty >0)
			 {
				 
					$total_price = ( $qty *  $price ) + ( $old_qty *  $old_price ) ;
					$kg_avg_price = $total_price/$total_quantity;
					$price = $kg_avg_price;
			 }
			 //- sum(session_".$session_id."_qty *session_".$session_id."_price ) as used_total 
			  $used_total 	= $this->db->query("select sum(
												(session_1_qty * session_1_price)+
												(session_2_qty * session_2_price)+
												(session_3_qty * session_3_price)+
												(session_4_qty * session_4_price) 
							) as used_total  from $stock_entry_table where school_id=? and entry_date=? ",
							array($school_id,$date))->row()->used_total;
			 			
						
				 $exclude_current_item_total 	= $this->db->query("select sum(session_".$session_id."_qty *session_".$session_id."_price ) as exclude_current_item_total
						from $stock_entry_table where school_id=? and entry_date=? and item_id=? ",
							array($school_id,$date,$item_id))->row()->exclude_current_item_total;		
				
			$used_total 	= $used_total - $exclude_current_item_total;
			
			 $today_used_total =  $used_total + $total_price;
			 if($today_used_total > $this->config->item("day_max_limit"))
			 {
				$this->session->set_flashdata('message', '<div class="alert alert-danger">Daily limit exceeding. please check with admin. </div>');
				redirect('consumption_entrynew/consumption_entryform/'.$item_id.'/'.$session_id); 
			 }
			//echo "starting...";
			//die;
			$arguments = array();
			$arguments['closing_qty'] = $entries_data->balcount -  $total_quantity;
			$arguments['post_array']  = $_POST;
			$arguments['session_id']  = $session_id;
			$arguments['entry_id']  = 	$entry_id_e;
			$arguments['tqty']  = 	$total_quantity ;
			$arguments['tprice']  = 	$price;
			
			// print_a($entries_data ); die;
			sleep(1);
			$this->db->trans_start();			
			$result = $this->consumption_model->update_consume_entry_byid($arguments,$date);
			$this->db->trans_complete();
			
			if ($this->db->trans_status() === FALSE)
				{
							//Send Email to Administrator
				$subject = $this->session->userdata("school_id") . " - " . $this->session->userdata("school_code") ." - Failed to update consumption entry";
				$body = "<br>School : ".$this->session->userdata("school_id") . " - " . $this->session->userdata("school_code") ;
				$body .= "<br>Item id: ".$item_id . "<br> Session : ".$session_id;
				$body .=  " <br> Time : ".date('d-M-Y H:i:s');
				$post_msg = '';
				foreach($_POST as $key=>$val){
					$post_msg .= "<br> $key = $val";
				}
				$body .= "<br><br>".$post_msg;
				$body .= "<br><br>Thanks,<br>Team";
				$result = $this->email
				->from('annapurna.smtp@gmail.com')
				->reply_to('annapurna.smtp@gmail.com')    // Optional, an account where a human being reads.
				->to('webdeveloper.rk@gmail.com')
				->subject($subject)
				->message($body)
				->send();
				// generate an error... or use the log_message() function to log your error
				$this->session->set_flashdata('message', '<div class="alert alert-danger">Failed to Update consumption entries. please try again.</div>');
				redirect('consumption_entrynew/consumption_entryform/'.$item_id.'/'.$session_id); 
						
				}
			
			
			
			
			$this->session->set_flashdata('message', '<div class="alert alert-success">Succesfully updated</div>');
			redirect('consumption_entrynew/view/'.$session_id); 
		 } 
		//echo $session_id;
		$session_id_column = '';
			$session_id_column_price = '';
			if($session_id==1)
			{
				$session_id_column_qty = 'session_1_qty' ;
				$session_id_column_price  ='session_1_price';
				$session_id_column_old_qty = 'session_1_old_qty' ;
				$session_id_column_old_price  ='session_1_old_price';
			}
			else if($session_id==2)
			{
				$session_id_column_qty = 'session_2_qty' ;
				$session_id_column_price  ='session_2_price';
				
				$session_id_column_old_qty = 'session_2_old_qty' ;
				$session_id_column_old_price  ='session_2_old_price';
			}
			else if($session_id==3)
			{
				$session_id_column_qty = 'session_3_qty' ;
				$session_id_column_price  ='session_3_price';
				
					
				$session_id_column_old_qty = 'session_3_old_qty' ;
				$session_id_column_old_price  ='session_3_old_price';
			}
			else if($session_id==4)
			{
				$session_id_column_qty = 'session_4_qty' ;
				$session_id_column_price  ='session_4_price';
				
					
				$session_id_column_old_qty = 'session_4_old_qty' ;
				$session_id_column_old_price  ='session_4_old_price';
			}
			  $data['qty'] = $session_id_column_qty;
			  $data['old_qty'] = $session_id_column_old_qty;
			  $data['price'] = $session_id_column_price;
			  $data['old_price'] = $session_id_column_old_price;
				 
		 
        $data["item_used"] = $this->db->query("select * from $stock_entry_table where item_id=? and school_id=? and entry_date=? ",array($item_id,$school_id,$date))->row();
		
		//print_statement($this->db->last_query());
	 
        $data["item_id"] = $item_id;
		
        $data["entry_id"] = $this->ci_jwt->jwt_web_encode( $data["item_used"]->entry_id);// $this->encryption->encrypt(   $data["item_used"]->entry_id);  
		
        $data["locked_check"] =  $locked_check;
        
        $data["date"] = $date;
        $data["school_id"] = $school_id;
        $data["session_id"] = $session_id;
        
        
        $data["module"] = "consumption_entrynew";
        $data['data_entry_allowed']=$eligibility; 
		$data['data_entry_text']=$message;  
       $data['todat_date_text']= date('d-M-Y',strtotime($date));
		 
		 
		if($locked_check == true){
				$data["today_item_used"] = $this->db->query("select * from balance_sheet where school_id=? and entry_date=? and item_id=?",array($school_id,$date,$item_id))->row();
				$data["view_file"] = "consumption_form_locked";
		}else{
			 
			 $data['purchase_data'] = $this->get_price_details($item_id,true,0);
		$data["view_file"] = "consumption_form";
		}			
		  
        echo Modules::run("template/admin", $data);
         
	}
	private function today_date()
	{
	
		switch($this->config->item("site_name"))
		{
			case 'apreis':
							//echo $this->config->item("day_start_time");die;
							$is_yester_day = $this->db->query("select CURRENT_TIME < ? as is_yester_day",$this->config->item("day_start_time"))->row()->is_yester_day;
							if($is_yester_day ==1)
									$date = date("Y-m-d",strtotime("yesterday"));
							else 
									$date = date('Y-m-d');
							break;
			default :
							$date = date('Y-m-d');
							break;
		}
		//echo $date;die;
		return $date;
	
	}
	  
	public   function recalculate_item($item_id=null,$session_id=null)
	{
		$school_id = $this->session->userdata("school_id");
		$entry_date = '2018-04-01';
		$this->common_model->update_entries($school_id,$item_id,$entry_date);
		redirect('consumption_entrynew/consumption_entryform/'.$item_id.'/'.$session_id);
	}
	
	private function get_price_details($item_id,$return_avilable_list  = false,$qty)
	{
		$con_entries  = array();
		$entry_date  = $this->today_date();
		 
		$school_id = $this->session->userdata("school_id");
		  $today_cb = $cbalance =  $this->db->query("select * from balance_sheet where item_id=? and entry_date=? and school_id=? ",array($item_id,$entry_date,$school_id ))->row()->closing_quantity;
		//  echo $today_cb ;
		  if($today_cb < $qty )
		  {
			  echo "<h1>Insuffucient Balance</h1>";
			  die;
		  }
		 
		// echo $today_cb,"<br>";
		$rs= $this->db->query("select *,date_format(purchase_date,'%d-%M-%Y') as entry_date,purchase_date entry_date_org from purchases where item_id=? and quantity>0 and school_id=? order by entry_date_org desc    ",array($item_id,$school_id ));
		//  echo $this->db->last_query(),"<br>";
		$purchase_entries = array();
		foreach($rs->result() as $row)
		{
			$ctemp  =  $cbalance;
			$cbalance = $cbalance - $row->quantity;
			if($cbalance<0)
			{
				$allowed_to_use  = $ctemp;
			}
			else {
				
				$allowed_to_use  =  $row->quantity;
			}
			if($allowed_to_use>0){
				$purchase_entries[] = array('purchase_quantity'=>$row->quantity,'remaing_to_use'=>$allowed_to_use,'purchase_price'=>$row->purchase_price,'purchase_date'=>$row->entry_date);
			}
			 if($cbalance<0)
			{
				 break;
			}
			 
		}
		//print_a($purchase_entries,1); 
		$usable_list  = array_reverse($purchase_entries);
		if($return_avilable_list == true)
		{
			//print_a($usable_list);
			return $usable_list; 
		}
		//print_a($usable_list);
		$uentries= array();
		$uqty = $qty;
		foreach($usable_list as $key=>$ulist)
		{
			 if($uqty <=0)
			 		break;
				// print_a($ulist,1);
			$remaing_to_use = $ulist['remaing_to_use'];
			if($remaing_to_use <= $uqty)
			{ 
				$uentries[]  =  array('purchase_qty'=> $ulist['purchase_quantity'],'remaing_to_use'=>$ulist['remaing_to_use'],'qty'=>$remaing_to_use,'price'=>$ulist['purchase_price'],'purchase_date'=>$ulist['purchase_date']); 
			}
			else{
				$uentries[]  = array('purchase_qty'=>$ulist['purchase_quantity'],'remaing_to_use'=>$ulist['remaing_to_use'],'qty'=>$uqty,'price'=>$ulist['purchase_price'],'purchase_date'=>$ulist['purchase_date']);   ;
				 
			}
			$uqty  = $uqty - $remaing_to_use;
		}
		return $uentries;
	}
	public function ajax_pricelist($item_id)
	{
		//sleep(20);testing purpose
		$amount = $total_amount = 0;
		$qty = $this->input->post("quantity");
		$data = $this->get_price_details($item_id,false,$qty);
		$text = "<style>
						table {
						  border-collapse: collapse;
						  width: 100%;
						}

						th, td {
						  text-align: left;
						  padding: 8px;
						}

						tr:nth-child(even){background-color: #f2f2f2}

						th {
						  background-color: #4CAF50;
						  color: white;
						}
						</style><div class='box-body table-responsive'>
				<table class='table-dark'><thead><tr><th>Purchase Date</th><th>Purchased Qty</th><th>Remaining Qty</th><th>Using Qty</th><th>Price</th><th>Total </th></tr></thead>";
		foreach($data as $obj)
		{
			// print_a($obj);
			$amount = $obj['qty'] * $obj['price'];
			$text .= "<tr><td>".$obj['purchase_date']."</td><td>".$obj['purchase_qty']."</td><td>".$obj['remaing_to_use']."</td><td>".$obj['qty']."</td><td>".$obj['price']."</td><td>".$amount."</td></tr>";
			$total_amount = $total_amount + $amount;
		}
		$text .= "<tr><td colspan='5' align='right' style='text-align:right;'><b>Total Amount</b></td><td><b>".$total_amount."</b></td></tr>";
		$text .="</table></div>";
		echo $text;
		die;
	}
	private function is_excempted($method,$session_id,$item_id='')
	{
		$school_id = $this->session->userdata("school_id");
		$school_code =  $this->db->query("select * from schools where school_id=?",array($school_id))->row()->school_code;
		$rs = $this->db->query("select * from consumption_entry_excemptions where school_code=?",array($school_code));
		if($rs->num_rows()>0)
		{
			if($method=="view")
			{
				redirect("consumption_entry/view/".$session_id);
			}elseif($method=="consumption_entryform")
			{
			  redirect("consumption_entry/consumption_entryform/".$item_id."/".$session_id);
			}
			 
		}
	}
	 
	 
	
}
