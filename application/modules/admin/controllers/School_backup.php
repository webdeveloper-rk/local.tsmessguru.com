<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

 date_default_timezone_set('Asia/Kolkata');
class School extends MX_Controller {

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
		}
		$this->load->helper('url');
		$this->load->library('grocery_CRUD');
		$this->load->model('school_model');
	}

    function index() {
          $data["module"] = "admin";
        $data["view_file"] = "school_dashboard";
        echo Modules::run("template/admin", $data);
    }
	function underconstruction()
	{
		  $data['users_count']=0;//
        $data['banks_count']=0;
        $data['plans_count']=0;
        $data['payments_count']=0;
        
		 
        $data["module"] = "admin";
        $data["view_file"] = "underconstruction";
        echo Modules::run("template/admin", $data);
		
	}
	function purchase_entry()
	{
		$quotation_prices = $this->school_model->get_items_price($this->session->userdata("district_id"));
		$data["item_prices"] = $quotation_prices;
		//print_a($data["item_prices"] );
		$data["today_purchases"] = $this->school_model->get_purchase_entries($this->session->userdata("school_id"),date('Y-m-d'));
		 
		
		//echo "<pre>";print_r($data["item_prices"]);echo "</pre>";		
		$drs = $this->db->query("select * from  items where status='1'");  
        $data["module"] = "admin";
        $data["rset"] = $drs;
		
		
		
        $data["view_file"] = "school/purchase_entry";
        echo Modules::run("template/admin", $data);
         
	}
	function purchase_entryform($item_id=null)
	{
		$school_id	=	$this->session->userdata("school_id");
		$this->school_model->initiate_item($school_id,$item_id);
		 if($this->input->post('action')=="submit")
		 {
			 $school_id	=	$this->session->userdata("school_id");
			 $item_id	=	$item_id;
			 $qty 		= 	$this->input->post('quantity');
			 $school_price	=	$this->input->post('price');
			 $date			=	date('Y-m-d');
			 
			$result = $this->school_model->insert_purchase_entry($school_id,$item_id,$qty,$school_price,$date);
			$this->session->set_flashdata('message', '<div class="alert alert-success">Succesfully updated</div>');
			redirect('admin/school/purchase_entry');
				 
		 }
		
		$drs = $this->db->query("select * from  items where status='1'");  		
		$data['price']=$this->school_model->get_item_price($this->session->userdata("district_id"), $item_id); 

		$data["today_purchases"] = $this->school_model->get_purchase_entries($this->session->userdata("school_id"),date('Y-m-d'),$item_id);
		
		 
        $data["item_id"] = $item_id;
        $data["item_details"] = $this->school_model->get_itemdetails($item_id);
        $data["module"] = "admin";
        $data["rset"] = $drs;
        $data["view_file"] = "school/purchase_form";
        echo Modules::run("template/admin", $data);
         
	}
 
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
		
		$data['qty'] = $session_column_qty ;
		$data['price'] = $session_column_price;
		
		
		$drs = $this->db->query("select * from  items where status='1'"); 
		$data['current_session'] = $sesdata;
		$data['data_entry_allowed']=$eligibility; 
		$data['data_entry_text']=$message;  
		
		$data['item_prices']=$this->school_model->get_items_price($this->session->userdata("district_id") ); 
		
		$data["today_consumes"] = $this->school_model->get_consumption_entries($this->session->userdata("school_id"),date('Y-m-d'),$session);
		$data['users_count']=0; 
        $data['banks_count']=0;
        $data['plans_count']=0;
        $data['payments_count']=0;
        
		 
        $data["module"] = "admin";
        $data["rset"] = $drs;
        $data["view_file"] = "school/consumption_entry";
        echo Modules::run("template/admin", $data);
	}
	
	function consumption_entryform($item_id=null,$session_id=null)
	{
		//echo $session_id;
		$school_id	=	$this->session->userdata("school_id");
		$this->school_model->initiate_item($school_id,$item_id);
	
	
		$message = '';
		$eligibility = $this->school_model->check_entries_allowed($session_id); 
		$sesdata = $this->school_model->get_food_sessions($session_id); 
		if(!$eligibility)
		{
			$message =  "<h2 style='color:#FF0000'>Entries locked</h2><br><h3>You have to enter data between ".$sesdata->start_hour_text. " to ".$sesdata->end_hour_text. " only</h3>";			
		}
		
		if($eligibility == true && $this->input->post('action')=="submit")
		 {
			 $school_id	=	$this->session->userdata("school_id");
			 $item_id	=	$item_id;
			 $qty 		= 	$this->input->post('quantity'); 
			 $price 		= 	$this->input->post('price'); 
			 $date			=	date('Y-m-d');
			 
			 $check_qty = $this->school_model->check_quantity($school_id,$item_id,$date,$qty);
			 if( $check_qty==false)
			 {
				 $this->session->set_flashdata('message', '<div class="alert alert-danger">Insufficient Stock quantity. please add purchase entry to add consumption </div>');
				redirect('admin/school/consumption_entryform/'.$item_id.'/'.$session_id); 
			 }
			 
			$result = $this->school_model->insert_consume_entry($school_id,$item_id,$qty,$price,$date,$session_id);
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
		$data["today_consumes"] = $this->school_model->get_consumption_entries($this->session->userdata("school_id"),date('Y-m-d'),$session_id,$item_id);
		$data['item_prices']=$this->school_model->get_items_price($this->session->userdata("district_id") ); 
        $data["item_id"] = $item_id;
        $data["session_id"] = $session_id;
        $data["item_details"] = $this->school_model->get_itemdetails($item_id);
        $data["current_session"] = $this->school_model->get_food_sessions($session_id);
        $data["module"] = "admin";
        $data['data_entry_allowed']=$eligibility; 
		$data['data_entry_text']=$message;  
       
        $data["view_file"] = "school/consumption_form";
        echo Modules::run("template/admin", $data);
         
	}

	
	
	function report($report_type=1)
	{
		  $data['users_count']=0;//
        $data['banks_count']=0;
        $data['plans_count']=0;
        $data['payments_count']=0;
        
		 
        $data["module"] = "admin";
        $data["view_file"] = "school/report_entry";
        echo Modules::run("template/admin", $data);
	}
	function check_salt($str)
{

   
      $this->form_validation->set_message('check_salt', 'text dont match captcha');

      return false;
   
}
 function attendence(){
 
		if($this->input->post('action')=="submit")
		 {
			$attendence_date = $this->input->post('date');
			$attendence_count = $this->input->post('attendence');
			$school_id = $this->session->userdata("school_id");
			
			  $sql = "select * from  school_attendence where school_id='$school_id' and entry_date='$attendence_date'";
			$rs = $this->db->query($sql);
			if($rs->num_rows()==0)
			{
				$sql = "insert into   school_attendence set school_id='$school_id' , entry_date='$attendence_date',present_count='$attendence_count'";
				$rs = $this->db->query($sql);
				$this->session->set_flashdata('message', '<div class="alert alert-success">Succesfully updated</div>');
				redirect('admin/school/attendencelist');
			}
			else
			{
				$this->session->set_flashdata('message', '<div class="alert alert-danger">Attendence already posted for date of '.$attendence_date.'. please update attendence if required.</div>');
				//redirect('admin/school/attendencelist');
			} 
		 }
		
		 
        $data["module"] = "admin";
        
        $data["view_file"] = "school/attendence_form";
        echo Modules::run("template/admin", $data);
 
 
 }
	
	public function _example_output($output = null)
	{
		  $data["module"] = "admin";
        $data["view_file"] = "example";
        echo Modules::run("template/admin", $data);
		//$this->load->view('example.php',$output);
	}
	function date_formatdisplay($value, $row)
		{
			 return date('d-M-Y',strtotime($value));
		}
     function attendencelist(){
		
		try{
			$crud = new grocery_CRUD($this);

			$crud->set_theme('flexigrid'); 
			$crud->set_table('school_attendence');
			$crud->where('school_id',$this->session->userdata("school_id"));
			$crud->set_subject('Attendence');
			
			 $crud->callback_column('entry_date',array($this,'date_formatdisplay'));
			 $crud->callback_edit_field('entry_date',array($this,'date_formatdisplay'));
			$crud->unset_add(); 
            $crud->unset_delete();
			$crud->columns(array('entry_date','present_count'));
			$crud->edit_fields(array('entry_date','present_count'));
			$crud->required_fields(array('present_count'));
			 
			$crud->field_type('entry_date', 'readonly');
			 
			 

			$output = $crud->render();
			$data["module"] = "cms";
			$data["view_file"] = "cms";
			$output->title = "Attendence entries";
			$data["crud"] = $output;
			echo Modules::run("template/admin", $data);
			

		}catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
	}
	
	function openingbalance()
	{
		$quotation_prices = $this->school_model->get_items_price($this->session->userdata("district_id"));
		$data["item_prices"] = $quotation_prices;
		
		$data["closing_balances"] = $this->school_model->get_openingbalance_entries($this->session->userdata("school_id"));
		 
		
		//echo "<pre>";print_r($data["item_prices"]);echo "</pre>";		
		$drs = $this->db->query("select * from  items where status='1'");  
        $data["module"] = "admin";
        $data["rset"] = $drs;
		
		
		
        $data["view_file"] = "school/openingbalance";
        echo Modules::run("template/admin", $data);
         
	}
	function openingbalance_entryform($item_id=null)
	{
				
		 if($this->input->post('action')=="submit")
		 {
			 $school_id	=	$this->session->userdata("school_id");
			 $item_id	=	$item_id;
			 $qty 		= 	$this->input->post('quantity');
			 $school_price	=	$this->input->post('price');
			 $date			=	date('Y-m-d');
			 
			$result = $this->school_model->insert_closingbalance_entry($school_id,$item_id,$qty,$school_price,$date);
			$this->session->set_flashdata('message', '<div class="alert alert-success">Succesfully updated</div>');
			redirect('admin/school/openingbalance');
				 
		 }
		
		$drs = $this->db->query("select * from  items where status='1'");  		
		$data['price']=$this->school_model->get_item_price($this->session->userdata("district_id"), $item_id); 

		$data["closing_balances"] = $this->school_model->get_closing_entries($this->session->userdata("school_id"),$item_id);
		
		 
        $data["item_id"] = $item_id;
        $data["item_details"] = $this->school_model->get_itemdetails($item_id);
        $data["module"] = "admin";
        $data["rset"] = $drs;
        $data["view_file"] = "school/openingbalance_entryform";
        echo Modules::run("template/admin", $data);
         
	}

}
