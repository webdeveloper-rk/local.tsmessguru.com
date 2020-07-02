<?php 
 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
set_time_limit(0);
 date_default_timezone_set('Asia/Kolkata');
class Twhostels_consumption extends MX_Controller {

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
		$this->load->library('ci_jwt'); 
		$this->load->model('consumption_model'); 
		$this->load->model('common/common_model'); 
		$this->load->config('config'); 
		 
	}

    function index() {
        redirect("twhostels_consumption/view");
    }
 
	function  view()
	{
		$session_id= 1;//all are into one session
		$message = '';

		$session_id_column_qty = 'session_1_qty' ;
		$session_id_column_price  ='session_1_price'; 
		
		
		$condate =  $this->today_date();
		$conschool_id = $this->session->userdata("school_id"); 
		$stock_entry_table = $this->common_model->get_stock_entry_table($condate);
		
		$qry = "select * from $stock_entry_table where school_id=? and entry_date=?";		
		$data['rset']= $this->db->query($qry,array($conschool_id,$condate)); 
		
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
		 
        $data["module"] = "twhostels_consumption"; 
        $data["view_file"] = "consumption_list";
        echo Modules::run("template/admin", $data);
	}
	
		/******************************************************
		
		
		
		/******************************************************/
		
	 function twhostels_consumptionform($item_id=null,$session_id=null)
	{
		
		$session_id= intval($session_id);
		$item_id =  intval( $item_id);
		
		
		 $stock_entry_table = $this->common_model->get_stock_entry_table($this->today_date());
		$allowed_sessions = array(1,2,3,4);
		if(!in_array($session_id,$allowed_sessions))
		{
			 $this->session->set_flashdata('message', '<div class="alert alert-danger">Invalid Session.</div>');
				redirect('twhostels_consumption'); 
		}
		
		
		$data["item_details"] = $this->consumption_model->get_itemdetails($item_id);
		if(count($data["item_details"])==0)
		{
			$this->session->set_flashdata('message', '<div class="alert alert-danger">Invalid Item.</div>');
				redirect('twhostels_consumption'); 
		}
	 
		 
		
		$this->load->library('encryption');
		$this->form_validation->set_rules('quantity', 'Quantity', 'required|numeric|greater_than[0]');              
		$this->form_validation->set_rules('price', 'Price', 'required|numeric|greater_than[0]'); 
		$this->form_validation->set_rules('combined_stock', 'Combined Stock', 'required|numeric|in_list[0,1]'); 
				
			 if(null !==  ($this->input->post('combined_stock')) && $this->input->post('combined_stock') ==1) {
						$this->form_validation->set_rules('old_quantity', 'Old Quantity', 'required|numeric|greater_than[0]');              
						$this->form_validation->set_rules('old_price', 'Old Price', 'required|numeric|greater_than[0]'); 
			 }
				
		
	
		if($data["item_details"]->status=="0")
		{
			echo "<h1 style='padding:30px;'>Item:".strtoupper($data["item_details"]->item_name)." is not avilable to use.</h1>";
			die;
		}			
				
		//echo $session_id;
		$school_id	=	intval($this->session->userdata("school_id")); 
		$date =	 $this->today_date();
	
		$message = '';
		$eligibility = $this->consumption_model->check_entries_allowed($session_id); 
		$sesdata = $this->consumption_model->get_food_sessions($session_id); 
		
		 $data['authorised']= $this->consumption_model->check_allowed_authorise($session_id,$this->session->userdata("school_id"));
		
		
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
		 
		 
	 
		
		if(ip_allowed_to_edit($this->input->ip_address())){
			$eligibility = true ;
			$locked_check =  false;
		}
		if($this->config->item("site_name")=="apreis"){
			$eligibility = true ;
			$locked_check =  false;
		}	
		
		//Tempararly solution 
		/*$eligibility = true ;
		$locked_check =  false;
		*/
		if($this->form_validation->run() == true && $eligibility == true  && $locked_check == false && $this->input->post('action')=="submit")
		 {
			   
			 $entry_id_e =  $this->ci_jwt->jwt_web_decode( $this->input->post('consumptionprime')); 
			 $school_id	=	intval($this->session->userdata("school_id"));
			 
			 $qty 		= 	floatval($this->input->post('quantity')); 
			 $price 		= $price_new = 	floatval($this->input->post('price')); 
			 $old_qty 		= 0;
			  $old_price 		= 0;
			 if($this->input->post('combined_stock') ==1) {
						$old_qty 		= 	floatval($this->input->post('old_quantity')); 
						$old_price 		= 	floatval($this->input->post('old_price')); 
			 }
			 $total_quantity = $qty  +  $old_qty ;
			 
			   
			   
			 
			 $entries_data  = $this->consumption_model->get_entry_set($entry_id_e,$session_id);
			 //print_a($entries_data);die;
			 $total_price = ( $qty *  $price ) + ( $old_qty *  $old_price ) ;
			 $used_total 	= $this->db->query("select sum(
												(session_1_qty * session_1_price)+
												(session_2_qty * session_2_price)+
												(session_3_qty * session_3_price)+
												(session_4_qty * session_4_price) 
							)as used_total from $stock_entry_table where school_id=? and entry_date=? and item_id !=?",
							array($school_id,$date,$item_id))->row()->used_total;
			 			
			 $today_used_total =  $used_total + $total_price;
			 if($today_used_total > $this->config->item("day_max_limit"))
			 {
				$this->session->set_flashdata('message', '<div class="alert alert-danger">Daily limit exceeding. please check with admin. </div>');
				redirect('twhostels_consumption/twhostels_consumptionform/'.$item_id.'/'.$session_id); 
			 }
			 
			 if( $total_quantity > $entries_data->balcount   )
			 {
				 $this->session->set_flashdata('message', '<div class="alert alert-danger">Insufficient Stock quantity. please add purchase entry to add consumption </div>');
				redirect('twhostels_consumption/twhostels_consumptionform/'.$item_id.'/'.$session_id); 
			 }
			 //Calculate avg price 
			
			 if( $old_qty >0)
			 {
				 
					$total_price = ( $qty *  $price ) + ( $old_qty *  $old_price ) ;
					$kg_avg_price = $total_price/$total_quantity;
					$price = $kg_avg_price;
			 }
			 
			 if($this->config->item("site_name")=="twhostels")
			 {
				  $is_excempted = $this->common_model->fixed_rate_item_excemption($item_id,$school_id);
				  if($is_excempted == false) { 
							  $qty 		= 	floatval($this->input->post('quantity')); 
							 $price = $this->common_model->get_item_fixed_price($item_id,$school_id);
							 $total_price = ( $qty *  $price )  ;
								$kg_avg_price =  $price;
								 
							 $_POST['price'] = $price;
				  }
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
				redirect('twhostels_consumption/twhostels_consumptionform/'.$item_id.'/'.$session_id); 
						
				}
			
			
			
			
			$this->session->set_flashdata('message', '<div class="alert alert-success">Succesfully updated</div>');
			redirect('twhostels_consumption/view/'.$session_id); 
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
        
        $data["current_session"] = $this->consumption_model->get_food_sessions($session_id);
        $data["module"] = "twhostels_consumption";
        $data['data_entry_allowed']=$eligibility; 
		$data['data_entry_text']=$message;  
       $data['todat_date_text']= date('d-M-Y',strtotime($date));
		if($this->config->item("site_name")=="twhostels")
		{
				$data['item_price']=$this->common_model->get_item_fixed_price($item_id,$school_id);
		}
        $data["view_file"] = "consumption_form";
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
		
		return $date;
	
	}
	  
	public   function recalculate_item($item_id=null,$session_id=null)
	{
		$school_id = $this->session->userdata("school_id");
		$entry_date = '2018-04-01';
		$this->common_model->update_entries($school_id,$item_id,$entry_date);
		redirect('twhostels_consumption/twhostels_consumptionform/'.$item_id.'/'.$session_id);
	}
	 
	 
	
}
