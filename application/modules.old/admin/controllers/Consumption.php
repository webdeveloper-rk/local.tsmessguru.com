<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
set_time_limit(0);
 date_default_timezone_set('Asia/Kolkata');
class Consumption extends MX_Controller {

    function __construct() {
        parent::__construct();
		if($this->uri->segment(2) !="login") { 
					 Modules::run("security/is_admin");		 
					if ($this->session->userdata("is_loggedin") != TRUE || $this->session->userdata("user_id") == "" ) {
							redirect("admin/login");
							die;
					}
					if($this->session->userdata("user_role") == "admin")
					{
						redirect("admin/admin");
					}					
					if($this->session->userdata("user_role") != "school")
					{
						redirect("admin/login");
							die;
					}
					if($this->session->userdata("upassword")=="caretaker" && $this->session->userdata("operator_type") =="CT")
					{
						$this->session->set_flashdata('message', '<div class="alert alert-danger">Please update your password to continue.</div>');
						redirect('admin/general/changepassword');
					}
		}
		//print_a($this->session->all_userdata());
		$this->load->helper('url');
		$this->load->library('grocery_CRUD');
		$this->load->model('school_model');
		 $this->load->library('excel');
		// $this->load->library('image_CRUD');
	}

    function index() {
			 
    }
	public function _example_output($output = null)
	{
		  $data["module"] = "admin";
        $data["view_file"] = "example";
        echo Modules::run("template/admin", $data);
		//$this->load->view('example.php',$output);
	}
 	function purchase_entry()
	{
		$data["item_prices"] = $this->school_model->get_items_price($this->session->userdata("district_id"));		
		$data["today_purchases"] = $this->school_model->get_balance_entries_today($this->session->userdata("school_id"),date('Y-m-d'));
		$drs = $this->db->query("select * from  items where status='1' and 	allowed_to_edit='1'");         
        $data["rset"] = $drs;
		
		
		$data["module"] = "admin";
        $data["view_file"] = "school/purchase_entry";
        echo Modules::run("template/admin", $data);
         
	}
	/*
	
	*/
	function purchase_entryform($item_id=null)
	{
		$school_id	=	$this->session->userdata("school_id");
		$this->school_model->initiate_item($school_id,$item_id);
		
		$this->form_validation->set_rules('quantity', 'Quantity', 'required|greater_than[0]');              
		$this->form_validation->set_rules('price', 'Price', 'required|greater_than[0]'); 
		
		if(ip_allowed_to_edit($this->input->ip_address())){
			$this->form_validation->set_rules('quantity', 'Quantity', 'required');              
			$this->form_validation->set_rules('price', 'Price', 'required'); 
		}
		
		$date			=	date('Y-m-d');
		$school_id	=	$this->session->userdata("school_id");
		$item_id	=	$item_id;
			 
		  $sql = "select item_id from balance_sheet where entry_date='$date' and school_id='$school_id' and item_id='$item_id' and purchase_quantity= '0.00'	";
		$rs = $this->db->query($sql);
		  $locked = $rs->num_rows();
		//if 0 not allowed else allowed
		if($locked==0)
		{
			$allow_to_modify = false;
		}
		else{
			$allow_to_modify = true;
		}
		
		
		if(ip_allowed_to_edit($this->input->ip_address())){
			$allow_to_modify  = true;
		}
		//remove be,low line
		//$allow_to_modify = true;
		
		if($this->form_validation->run() == true && $allow_to_modify  == true && $this->input->post('action')=="submit")
		 {
			 $school_id	=	$this->session->userdata("school_id");
			 $item_id	=	$item_id;
			 $qty 		= 	$this->input->post('quantity');
			 $school_price	=	$this->input->post('price');
			 $date			=	date('Y-m-d');
			 
			 
			$this->db->trans_start();
			$result = $this->school_model->insert_purchase_entry($school_id,$item_id,$qty,$school_price,$date);
			$this->session->set_flashdata('message', '<div class="alert alert-success">Succesfully updated</div>');
			$this->db->trans_complete();
			if ($this->db->trans_status() === FALSE)
			{
					
					$subject = $this->session->userdata("school_id") . " - " . $this->session->userdata("school_code") ." - Failed to update Purchase entry";
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
					$this->session->set_flashdata('message', '<div class="alert alert-danger">Failed to Update entries. please try again.</div>');
					redirect('admin/school/purchase_entry');
			} 
			redirect('admin/school/purchase_entry');
				 
		 }
		
		$data["today_purchases"] = $this->school_model->get_purchase_item_form($this->session->userdata("school_id"),date('Y-m-d'),$item_id);
		
		 
        $data["allow_to_modify"] = $allow_to_modify;
        $data["item_id"] = $item_id;
        $data["item_details"] = $this->school_model->get_itemdetails($item_id);
        $data["module"] = "admin"; 
        $data["view_file"] = "school/purchase_form";
        echo Modules::run("template/admin", $data);
         
	}
	/*
	*/
	function consumption_entry($session=1)
	{
		$message = '';
		$eligibility = $this->school_model->check_entries_allowed($session); 
		$sesdata = $this->school_model->get_food_sessions($session); 
		 
		if(!$eligibility)
		{
			$message =  "<h2 style='color:#FF0000'>Entries locked</h2><br><h3>You have to enter data between ".$sesdata->start_hour_text. " to ".$sesdata->end_hour_text. " only</h3>";			
		}
		
		$session_column = '';
			$session_column_price = '';
			if($session==1)
			{
				$session_column_qty = 'session_1_qty' ;
				$session_column_price  ='session_1_price';
			}
			else if($session==2)
			{
				$session_column_qty = 'session_2_qty' ;
				$session_column_price  ='session_2_price';
			}
			else if($session==3)
			{
				$session_column_qty = 'session_3_qty' ;
				$session_column_price  ='session_3_price';
			}
			else if($session==4)
			{
				$session_column_qty = 'session_4_qty' ;
				$session_column_price  ='session_4_price';
			}
		
		$data['session_id'] = $session ;
		$data['qty_field'] = $session_column_qty ;
		$data['price_field'] = $session_column_price;
		
		
		$drs = $this->db->query("select * from  items where status='1' and allowed_to_edit='1'"); 
		$data['current_session'] = $sesdata;
		$data['data_entry_allowed']=$eligibility; 
		$data['data_entry_text']=$message;  
		
		$data['item_prices'] = $this->school_model->get_items_price($this->session->userdata("district_id") ); 
		
		$data["today_consumes"] = $this->school_model->get_balance_entries_today($this->session->userdata("school_id"),date('Y-m-d'));
		$data["consumption_prices"] = $this->school_model->get_consumption_price_today($this->session->userdata("school_id"),$session);
		//print_a($data["today_consumes"]);
		$drs = $this->db->query("select * from  items where status='1' and allowed_to_edit='1'");         
        $data["rset"] = $drs;
		
		
		
		$condate = date('Y-m-d');
		$conschool_id = $this->session->userdata("school_id");
		 
		//$conrs = $this->db->query("select * from daily_item_consumtions where school_id='$conschool_id' and entry_date='$condate'");
		$con_qty_list = array();
		 
		$data['consumption_qty_price_list'] = $con_qty_list ;
		
		$data["module"] = "admin";
        $data["view_file"] = "school/purchase_entry";
        
		
		$data['authorised']= $this->school_model->check_allowed_authorise($session,$this->session->userdata("school_id"));
		$data['users_count']=0; 
        $data['banks_count']=0;
        $data['plans_count']=0;
        $data['payments_count']=0;
        
		 
        $data["module"] = "admin";
        $data["rset"] = $drs;
        $data["closing_quantites"] = $this->school_model->get_school_closing_quantities($this->session->userdata("school_id"),date('Y-m-d'));
        $data["view_file"] = "school/consumption_entry";
        echo Modules::run("template/admin", $data);
	}
	
	function consumption_entryform($item_id=null,$session_id=null)
	{
		
		$this->form_validation->set_rules('quantity', 'Quantity', 'required|greater_than[0]');              
		$this->form_validation->set_rules('price', 'Price', 'required|greater_than[0]'); 
				
		//echo $session_id;
		$school_id	=	$this->session->userdata("school_id");
		
		
		$this->db->trans_start();
		$this->school_model->initiate_item($school_id,$item_id);
		$this->db->trans_complete();
		if ($this->db->trans_status(\) === FALSE)
		{
				// generate an error... or use the log_message() function to log your error
				$this->session->set_flashdata('message', '<div class="alert alert-danger">Failed to Update entries. please try again.</div>');
				redirect('admin/school/consumption_entryform/'.$item_id.'/'.$session_id); 
		}

		
		$date =	date('Y-m-d');
	
		$message = '';
		$eligibility = $this->school_model->check_entries_allowed($session_id); 
		$sesdata = $this->school_model->get_food_sessions($session_id); 
		
		 $data['authorised']= $this->school_model->check_allowed_authorise($session_id,$this->session->userdata("school_id"));
		
		
		if(!$eligibility)
		{
				$message =  "<h2 style='color:#FF0000'>Entries locked</h2><br><h3>You have to enter data between ".$sesdata->start_hour_text. " to ".$sesdata->end_hour_text. " only</h3>";			
				$locked_check = true;
			 
		}
 
			if( $this->session->userdata("operator_type") == "CT"  )
			{
			  $auth_data = $this->school_model->get_authorise($session_id,$school_id);
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
				  	$locked_check = $this->school_model->check_consumption_locked($school_id,$item_id,$session_id);
			}
	 
		
		if(ip_allowed_to_edit($this->input->ip_address())){
			$eligibility = true ;
			$locked_check =  false;
		}
	 
		if($this->form_validation->run() == true && $eligibility == true  && $locked_check == false && $this->input->post('action')=="submit")
		 {
			 $school_id	=	$this->session->userdata("school_id");
			 $item_id	=	$item_id;
			 $qty 		= 	floatval($this->input->post('quantity')); 
			 $price 		= $price_new = 	floatval($this->input->post('price')); 
			 $old_qty 		= 0;
			  $old_price 		= 0;
			 if($this->input->post('combined_stock') ==1) {
								 $old_qty 		= 	floatval($this->input->post('old_quantity')); 
								 $old_price 		= 	floatval($this->input->post('old_price')); 
			 }
			 $total_quantity = $qty  +  $old_qty ;
			 
			 $check_qty = $this->school_model->check_quantity($school_id,$item_id,$date,$total_quantity,$session_id);
			 if( $check_qty==false)
			 {
				 $this->session->set_flashdata('message', '<div class="alert alert-danger">Insufficient Stock quantity. please add purchase entry to add consumption </div>');
				redirect('admin/school/consumption_entryform/'.$item_id.'/'.$session_id); 
			 }
			 //Calculate avg price 
			
			 if( $old_qty >0)
			 {
					$total_price = ( $qty *  $price ) + ( $old_qty *  $old_price ) ;
					$kg_avg_price = $total_price/$total_quantity;
					$price = $kg_avg_price;
			 }
			//echo "starting...";
			//die;
			sleep(1);
			$this->db->trans_start();
			
			$result = $this->school_model->insert_consume_entry($school_id,$item_id,$total_quantity,$price,$date,$session_id,$_POST);
			
 
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
				redirect('admin/school/consumption_entryform/'.$item_id.'/'.$session_id); 
						
				}
			
			
			
			
			$this->session->set_flashdata('message', '<div class="alert alert-success">Succesfully updated</div>');
			redirect('admin/school/consumption_entry/'.$session_id); 
		 } 
		//echo $session_id;
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
			  $data['qty'] = $session_column_qty;
			  $data['price'] = $session_column_price;
				$data["today_consumes"] = $this->school_model->get_balance_entries_today($this->session->userdata("school_id"),date('Y-m-d'),$item_id);
													   //get_balance_entries_today($school_id=null,$date=null,$item_id=null)
			 //print_a($data["today_consumes"]);
		
		$data['item_prices']=$this->school_model->get_items_price($this->session->userdata("district_id") ); 
        $data["item_id"] = $item_id;
        $data["locked_check"] =  $locked_check;
        $data['closing_quantity'] = $this->school_model->get_closing_quantity($school_id,$item_id,$date);
        $data["date"] = $date;
        $data["school_id"] = $school_id;
        $data["session_id"] = $session_id;
        $data["item_details"] = $this->school_model->get_itemdetails($item_id);
        $data["current_session"] = $this->school_model->get_food_sessions($session_id);
        $data["module"] = "admin";
        $data['data_entry_allowed']=$eligibility; 
		$data['data_entry_text']=$message;  
		$data['data_entry_text']=$message;  
       
        $data["view_file"] = "school/consumption_form";
        echo Modules::run("template/admin", $data);
         
	}
	
	 

}
