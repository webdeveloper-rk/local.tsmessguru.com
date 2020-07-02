<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
set_time_limit(0);
 date_default_timezone_set('Asia/Kolkata');
class Schoolfeb11 extends MX_Controller {

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
		 $this->load->library('excel');
		// $this->load->library('image_CRUD');
	}

    function index() {
			$data["module"] = "admin";
			$data["view_file"] = "school_dashboard";
			echo Modules::run("template/admin", $data);
    }
	 function schoolreporttoday() {
		 
		 if($this->input->post('school_code')!="")
		 {
			 $school_code = $this->input->post('school_code');
			 $school_date = date('Ymd',strtotime($this->input->post('school_date')));
			 
			 $srs = $this->db->query("select * from users where school_code='$school_code'");
			 $school_data = $srs->row();
			 $school_id = $school_data->school_id;
			 redirect('admin/school/today_report/'. $school_id."/". $school_date);
			 die;
		 }
		 
		 $school_code = "";
		 $school_id = $this->session->userdata("school_id");
		 $scdata  = $this->db->query("select * from users where school_id=' $school_id'");
		 $scd =  $scdata->row();
        $data["school_code"] = $scd->school_code ;
        $data["module"] = "admin";
        $data["view_file"] = "subadmin_reportform";
        echo Modules::run("template/admin", $data);
    }
	function today_report($school_id=0,$date=null)
	{
		if($date==null)
				$date = date('Ymd');
			
		$report_date = date('Y-m-d',strtotime($date));
		
		if($school_id==0)
				$school_id = $this->session->userdata("school_id");
			
		$sql = "SELECT it.item_name,it.telugu_name,bs.* FROM `balance_sheet` bs inner join items  it on bs.item_id=it.item_id WHERE `school_id`='$school_id' and `entry_date`='$report_date'";
		$rs  = $this->db->query($sql);
		
		$report_date_formated = date('d-m-Y',strtotime($date));
		
		$data["report_date"] = $report_date_formated;
		$data["rset"] = $rs;
		
		
		$school_name_rs = $this->db->query("select * from users where school_id='$school_id'");
		$school_data  = $school_name_rs->row();
		
		
		$data["school_name"] = $school_data->name;
		$data["module"] = "admin";
		$data["module"] = "admin";
		$data["view_file"] = "school_today_report";
		echo Modules::run("template/admin", $data);
		
	}
		function todaybalance_old($month=null ,$year=null )
	{
		
		if($month == null)
				$month = date('m');
		if($year == null)
				$year = date('Y');
			
		
		$school_id  = $this->session->userdata("school_id");
		$sql = "select * from schools where school_id='".$school_id ."'";
		$rs  = $this->db->query($sql);
		$school_data = $rs->row();
		$per_head_per_day = $school_data->daily_amount;
		
		
		/*$sql = "SELECT DAY(LAST_DAY(curdate())) as days";
		$srs  = $this->db->query($sql);
		$pdata = $srs->row();
		$number_of_days  = $pdata->days;
		
		$per_head_per_day = $student_amount/$number_of_days;
		*/
		
		$ssql = "SELECT  sum(`present_count`) as pcount from school_attendence where school_id='$school_id' and month(`entry_date`) = $month  and year(`entry_date`) = $year";
		$dsrs  = $this->db->query($ssql);
		$psdata = $dsrs->row();
		$pcount  = $psdata->pcount;
		
		$allowed_amount = $pcount * $per_head_per_day ;
		
		
		$asql = "SELECT  sum( 
								(`session_1_qty`*`session_1_price`) + 
								(`session_2_qty`*`session_2_price`) + 
								(`session_3_qty`*`session_3_price`) + 
								(`session_4_qty`*`session_4_price`)  
							) as consumed_amount 
				from  balance_sheet where school_id='$school_id' and  month(`entry_date`) = $month  and year(`entry_date`) = $year";
		$asrs  = $this->db->query($asql);
		$asdata = $asrs->row();
		$consumed_amount  = $asdata->consumed_amount;
		
		$balance = $allowed_amount - $consumed_amount;
		
		$data['month'] = $month;
		$data['year'] = $year;
		$data['balance'] = $balance;
		$data['allowed_amount'] = $allowed_amount;
		$data['consumed_amount'] = $consumed_amount;
		
		$data["module"] = "admin";
        $data["view_file"] = "school/balance";
        echo Modules::run("template/admin", $data);
		
	}
	
	function todaybalance($month=null ,$year=null )
	{
		
		if($month == null)
				$month = date('m');
		if($year == null)
				$year = date('Y');
			
		
		
		$school_id  = $this->session->userdata("school_id");
		/*
			Get Amounts				
		*/ 
		$price_sql = "select * from group_prices";
		$price_rs = $this->db->query($price_sql);
		$student_prices = array();
		foreach($price_rs->result() as $stu_price){
			$student_prices[$stu_price->group_code] = $stu_price->amount;
		}
		//print_a($student_prices);
		$days_sql = "SELECT DAY( LAST_DAY( '$year-$month-01' ) ) as days";
		$days_row  = $this->db->query($days_sql)->row();
		$days_count = $days_row->days ;
		$data['days_count'] = $days_count;
		
		/*********************/
		$group_1_per_day= $student_prices['gp_5_7']/$days_count;
		$group_2_per_day= $student_prices['gp_8_10']/$days_count;
		$group_3_per_day= $student_prices['gp_inter']/$days_count;
		
		
		$data['group_1_per_day'] = $group_1_per_day;
		$data['group_2_per_day'] = $group_2_per_day;
		$data['group_3_per_day'] = $group_3_per_day;
		
		/*//////////////////////////*/
		/******get attendence ******/
			
		$attendece  = 0;
		$group_1_attendence = 0;
		$group_2_attendence = 0;
		$group_3_attendence = 0;
		$group_1_price = 0;
		$group_2_price = 0;
		$group_3_price = 0;
		
		$school_date = '$year-$month-01';
		$days_sql = "SELECT DAY( LAST_DAY( '$school_date' ) ) as days";
		$days_row  = $this->db->query($days_sql)->row();
		  $days_count = $days_row->days ;
		//print_a($student_prices);
		  $atters_sql = "select 
								sum(class_5_count+class_6_count+class_7_count) as grp1_count,
								sum(class_8_count+class_9_count+class_10_count) as grp2_count,
								sum(inter_mpc_1 +inter_mpc_2 +	inter_bipc_1 +	inter_bipc_2 +inter_cec_1 + inter_cec_2 + inter_hec_1 + inter_hec_2   + inter_mec_1 + inter_mec_2) as grp3_count 
								from school_attendence where school_id='$school_id'   and month(`entry_date`) = $month  and year(`entry_date`) = $year ";
		$atters = $this->db->query($atters_sql);
		if($atters->num_rows()>0)
		{
			$attedata = $atters->row();
			
			$group_1_attendence =  $attedata->grp1_count  ;
			$group_2_attendence =  $attedata->grp2_count;
			$group_3_attendence  =  $attedata->grp3_count;
			
			$data['group_1_attendence'] =  $group_1_attendence;
			$data['group_2_attendence'] =  $group_2_attendence;
			$data['group_3_attendence']  =  $group_3_attendence;
			
			 
			$group_1_price =  $group_1_attendence * $group_1_per_day;
			$group_2_price =  $group_2_attendence * $group_2_per_day;
			$group_3_price =  $group_3_attendence * $group_3_per_day;
			
			$data['group_1_price'] =  $group_1_price;
			$data['group_2_price'] =  $group_2_price;
			$data['group_3_price'] =  $group_3_price;
			
			
			$attendece = $group_1_attendence + $group_2_attendence + $group_3_attendence;
			$data['attendence'] = $attendece;
		}
		  $allowed_amount  = $group_1_price + $group_2_price + $group_3_price;
		
		
		$total_attendence = $attendece ;
		
		
		  $asql = "SELECT  sum( 
								(`session_1_qty`*`session_1_price`) + 
								(`session_2_qty`*`session_2_price`) + 
								(`session_3_qty`*`session_3_price`) + 
								(`session_4_qty`*`session_4_price`)  
							) as consumed_amount 
				from  balance_sheet where school_id='$school_id' and  month(`entry_date`) = $month  and year(`entry_date`) = $year";
		$asrs  = $this->db->query($asql);
		$asdata = $asrs->row();
		$consumed_amount  = $asdata->consumed_amount;
		
		$balance = $allowed_amount - $consumed_amount;
		
		$data['month'] = $month;
		$data['year'] = $year;
		$data['balance'] = $balance;
		$data['allowed_amount'] = $allowed_amount;
		$data['consumed_amount'] = $consumed_amount;
		$data['attendece'] =  $attendece;
		
		//$data['allowed_amount'] = 0;
		// $data['balance'] = 0;
		
		$data["module"] = "admin";
        $data["view_file"] = "school/balance";
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
	 
	function attendence(){
 
 
                $this->form_validation->set_rules('date', 'From Date', 'required');              
                $this->form_validation->set_rules('attendence', 'Attendence', 'required'); 
                
                         
 
		if($this->form_validation->run() == true && $this->input->post('action')=="submit")
		 {
			 
			 
			
			$adate = date('d-M-Y',strtotime($this->input->post('date')));
			$attendence_date = date('Y-m-d',strtotime($this->input->post('date')));
			$attendence_count = $this->input->post('attendence');
			$school_id = $this->session->userdata("school_id");
			
			//check the date is less than today
			$sql = "SELECT date('$attendence_date') <= CURRENT_DATE() as flag";
			$rs = $this->db->query($sql);
			$date_data = $rs->row();
			if($date_data->flag==0)
			{
						$this->session->set_flashdata('message', '<div class="alert alert-danger">Date should not exceed current date.</div>');
						//redirect('admin/school/attendencelist');
			}
			else
			{
		

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
						$this->session->set_flashdata('message', '<div class="alert alert-danger">Attendence already posted for date of '.$adate.'. please update attendence if required.</div>');
					} 
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
			$crud->order_by('entry_date','desc');
			$crud->set_subject('Attendence');
			
			 $crud->callback_column('entry_date',array($this,'date_formatdisplay'));
			 $crud->callback_edit_field('entry_date',array($this,'date_formatdisplay'));
			$crud->unset_add(); 
			$crud->unset_edit(); 
            $crud->unset_delete();
			$crud->columns(array('entry_date','present_count'));
			$crud->edit_fields(array('entry_date','present_count'));
			$crud->required_fields(array('present_count'));
			 
			$crud->field_type('entry_date', 'readonly');
			 
			 

			$output = $crud->render();
			$data["module"] = "cms";
			$data["extra_content"] = "";
			$data["view_file"] = "cms";
			$output->title = "Attendence entries";
			$data["crud"] = $output;
			echo Modules::run("template/admin", $data);
			

		}catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
	}
	function itemprices()
	{
		 
		 
		
		$data["item_prices"] = $this->school_model->get_school_itemprices($this->session->userdata("school_id"));
		$drs = $this->db->query("select * from  items where status='1'"); 
        $data["rset"] = $drs;
		
		
		 $data["module"] = "admin";
        $data["view_file"] = "school/itemprices";
        echo Modules::run("template/admin", $data);
         
	}
	function schoolitem_entryform($item_id=null)
	{
		
		       
		$this->form_validation->set_rules('price', 'Price', 'required'); 

		$school_id	=	$this->session->userdata("school_id");		
		 if($this->form_validation->run() == true && $this->input->post('action')=="submit")
		 {
			
			 $item_id	=	$item_id;
			  
			 $school_price	=	$this->input->post('price');
			 
			 $this->db->query("delete from school_item_prices where school_id='$school_id' and item_id='$item_id'");
			 $this->db->query("insert into school_item_prices set  school_id='$school_id' , item_id='$item_id' , 	price_per_kg='$school_price'");
			 
			$this->session->set_flashdata('message', '<div class="alert alert-success">Succesfully updated</div>');
			redirect('admin/school/itemprices');
				 
		 }
		
		$drs = $this->db->query("select * from  items where status='1'"); 
        $data["rset"] = $drs;
		
		$data["item_id"] = $item_id;
        $data["item_details"] = $this->school_model->get_itemdetails($item_id);
		
		$data["item_prices"] = $this->school_model->get_school_itemprices($this->session->userdata("school_id"));
		
        $data["module"] = "admin";        
        $data["view_file"] = "school/school_item_entryform.php";
        echo Modules::run("template/admin", $data);
         
	}
	function openingbalance()
	{
		$quotation_prices = $this->school_model->get_items_price($this->session->userdata("district_id"));
		$data["item_prices"] = $quotation_prices;
		
		$data["intial_balances"] = $this->school_model->get_initial_balances($this->session->userdata("school_id"));
		$drs = $this->db->query("select * from  items where status='1'"); 
        $data["rset"] = $drs;
		
		
		
		$data["module"] = "admin";
        $data["view_file"] = "school/openingbalance";
        echo Modules::run("template/admin", $data);
         
	}
	
	function openingbalance_entryform($item_id=null)
	{
		
		$this->form_validation->set_rules('quantity', 'Qunatity', 'required');              
		$this->form_validation->set_rules('price', 'Price', 'required'); 

		$school_id	=	$this->session->userdata("school_id");	
		
		/* check openinig balance entry avilable or not */
		
		
			$rs = $this->db->query("select * from  balance_sheet where  school_id='$school_id' and 
							item_id='$item_id'  and record_type='closing_balance' and entry_date='2016-08-31' ");
	
			if($rs->num_rows()==0)
			{
				 
					$qry ="insert into balance_sheet set 
														school_id = '$school_id',
														item_id = '$item_id',
														purchase_quantity = '0',
														purchase_price = '0',
														closing_quantity = '0.00',
														closing_price = '0',
														 record_type='closing_balance' ,
														 entry_date='2016-08-31',														 
														created_time= now() ";
					$this->db->query($qry);
			}
		
		
		/********************************************/
		
		
		$sql = "select item_id from balance_sheet where entry_date='2016-08-31'  and record_type='closing_balance' and school_id='$school_id' and item_id='$item_id' and 	closing_quantity='0.00' and curdate()<'2017-03-05'";
		$allowed_rs = $this->db->query($sql);
		$allowed_to_modify = $allowed_rs->num_rows();
		
		
		  
		if(ip_allowed_to_edit($this->input->ip_address())){
			$allowed_to_modify  = true;
		}
		
		 if($allowed_to_modify && $this->form_validation->run() == true && $this->input->post('action')=="submit")
		 {
			
			 $item_id	=	$item_id;
			 $qty 		= 	$this->input->post('quantity');
			 $school_price	=	$this->input->post('price');
			 $date			=	date('Y-m-d');
			 
			$result = $this->school_model->set_initial_balance_entry($school_id,$item_id,$qty,$school_price);
			$this->session->set_flashdata('message', '<div class="alert alert-success">Succesfully updated</div>');
			redirect('admin/school/openingbalance');
				 
		 }
		
		$drs = $this->db->query("select * from  items where status='1'"); 
        $data["rset"] = $drs;
		
		$data["allowed_to_modify"] =  $allowed_to_modify;
		$data["item_id"] = $item_id;
        $data["item_details"] = $this->school_model->get_itemdetails($item_id);
		
		$data['initial_amounts'] = $this->school_model->get_initial_balances_form($school_id,$item_id);
		//print_a($data['initial_amounts']);	
		
        $data["module"] = "admin";        
        $data["view_file"] = "school/openingbalance_entryform";
        echo Modules::run("template/admin", $data);
         
	}
	function purchase_entry()
	{
		$data["item_prices"] = $this->school_model->get_items_price($this->session->userdata("district_id"));		
		$data["today_purchases"] = $this->school_model->get_balance_entries_today($this->session->userdata("school_id"),date('Y-m-d'));
		$drs = $this->db->query("select * from  items where status='1'");         
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
		
		
		$drs = $this->db->query("select * from  items where status='1'"); 
		$data['current_session'] = $sesdata;
		$data['data_entry_allowed']=$eligibility; 
		$data['data_entry_text']=$message;  
		
		$data['item_prices'] = $this->school_model->get_items_price($this->session->userdata("district_id") ); 
		
		$data["today_consumes"] = $this->school_model->get_balance_entries_today($this->session->userdata("school_id"),date('Y-m-d'));
		$data["consumption_prices"] = $this->school_model->get_consumption_price_today($this->session->userdata("school_id"),$session);
		//print_a($data["today_consumes"]);
		$drs = $this->db->query("select * from  items where status='1'");         
        $data["rset"] = $drs;
		
		
		
		$condate = date('Y-m-d');
		$conschool_id = $this->session->userdata("school_id");
		 
		//$conrs = $this->db->query("select * from daily_item_consumtions where school_id='$conschool_id' and entry_date='$condate'");
		$con_qty_list = array();
		/*foreach($conrs->result() as $row)
		{
			if($row->old_stock_qty > 0 )
			{
				  
				$con_qty_list[$row->session_id][$row->item_id]['qty'] = "Old stock :".$row->old_stock_qty . "<br>New Stock : ".$row->new_stock_qty;
				$con_qty_list[$row->session_id][$row->item_id]['price'] = "Old stock price:".$row->old_stock_price . "<br>New Stock price: ".$row->new_stock_price	;
			}
			 
		}
		*/
		$data['consumption_qty_price_list'] = $con_qty_list ;
		
		$data["module"] = "admin";
        $data["view_file"] = "school/purchase_entry";
        
		
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
		if ($this->db->trans_status() === FALSE)
		{
				// generate an error... or use the log_message() function to log your error
				$this->session->set_flashdata('message', '<div class="alert alert-danger">Failed to Update entries. please try again.</div>');
				redirect('admin/school/consumption_entryform/'.$item_id.'/'.$session_id); 
		}

		
		$date			=	date('Y-m-d');
	
		$message = '';
		$eligibility = $this->school_model->check_entries_allowed($session_id); 
		$sesdata = $this->school_model->get_food_sessions($session_id); 
		if(!$eligibility)
		{
			$message =  "<h2 style='color:#FF0000'>Entries locked</h2><br><h3>You have to enter data between ".$sesdata->start_hour_text. " to ".$sesdata->end_hour_text. " only</h3>";			
		}
		$locked_check = $this->school_model->check_consumption_locked($school_id,$item_id,$session_id);
		
		
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
			
			/*
	 
			$select_entry = "select school_id from daily_item_consumtions where school_id='$school_id,' and item_id='$item_id' and session_id='$session_id'  and entry_date='$date'";
			$count_rs = $this->db->query($select_entry);
			if($count_rs->num_rows()==0) {
			$sql = "insert into daily_item_consumtions set  school_id='$school_id,' ,
										item_id='$item_id' ,
										entry_date='$date',
										session_id='$session_id',
										old_stock_qty ='$old_qty',
										old_stock_price	='$old_price',
										new_stock_qty='$qty',
										new_stock_price='$price_new '";
			}
			else{
						$sql = "update daily_item_consumtions set  school_id='$school_id,' ,
										item_id='$item_id' ,
										entry_date='$date',
										session_id='$session_id',
										old_stock_qty ='$old_qty',
										old_stock_price	='$old_price',
										new_stock_qty='$qty',
										new_stock_price='$price_new '
										where school_id='$school_id' and item_id='$item_id' and session_id='$session_id'  and entry_date='$date'
										
										";
				
			}
			$this->db->query($sql);
			*/
			
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
        $data["locked_check"] = $locked_check;
        $data['closing_quantity'] = $this->school_model->get_closing_quantity($school_id,$item_id,$date);
        $data["date"] = $date;
        $data["school_id"] = $school_id;
        $data["session_id"] = $session_id;
        $data["item_details"] = $this->school_model->get_itemdetails($item_id);
        $data["current_session"] = $this->school_model->get_food_sessions($session_id);
        $data["module"] = "admin";
        $data['data_entry_allowed']=$eligibility; 
		$data['data_entry_text']=$message;  
       
        $data["view_file"] = "school/consumption_form";
        echo Modules::run("template/admin", $data);
         
	}
	
	/*
	
	*/
	function update_school_info()
	{
		$rs = $this->db->query("select * from schools ");
		foreach($rs->result() as $row)
		{
			$list = $this->school_model->get_items_price($row->district_id);
			print_a($list);
			foreach($list as $item_id=>$price)
			{
				//print_a($item,1);
				$school_id= $row->school_id;
				 
				$qty = 10;
				$school_price =$price;
				$datenow = date('Y-m-d H:i:s');
				$qry ="insert into balance_sheet set 
														school_id = '$school_id',
														item_id = '$item_id',
														closing_quantity = '$qty',
														closing_price = '0',
														 record_type='closing_balance' ,
														 entry_date='2016-08-31',														 
														created_time= '$datenow' ";
					$this->db->query($qry);
			}
		}
		echo "Done";
	}
	/*
	
	*/
	function update_sheets()
	{
		$qry = "select * from balance_sheet where record_type is NULL ";
		$rs = $this->db->query($qry);
		foreach($rs->result() as $row)
		{
			//print_a($row);
			$opening_details = $this->school_model->get_opening_balance($row->school_id,$row->item_id,$row->entry_date);
			print_a($opening_details);
			  $up_qry = "update balance_sheet set opening_quantity='".$opening_details['closing_qty']."',opening_price='0' where 	entry_id ='".$row->entry_id."' ";			
			$this->db->query($up_qry);
			//die;
			
			$this->school_model->update_closing_entries($row->school_id,$row->item_id,$row->entry_date);
		}
		
		echo "Done;";
	}
	
	function reports()
	{
		$this->form_validation->set_rules('item_id', 'Iuem', 'required');              
		$this->form_validation->set_rules('fromdate', 'From Date', 'required');              
		$this->form_validation->set_rules('todate', 'To Date', 'required'); 
		
		if($this->form_validation->run() == true && $this->input->post('fromdate') !="" && $this->input->post('todate') !="")
		{
				//print_a($_POST,1);
				$item_id = $this->input->post('item_id');
				$from_date = date('Y-m-d',strtotime($this->input->post('fromdate')));
				$to_date = date('Y-m-d',strtotime($this->input->post('todate')));
				$submit = $this->input->post('submit');
				 
				if($submit == "Get Report")
					$type = "display";
				else
					$type = "download";
				redirect('admin/school/itemreport/'.$item_id ."/$from_date/$to_date/$type");
		}
		
		$data["item_prices"] = $this->school_model->get_items_price($this->session->userdata("district_id"));		
		$data["today_purchases"] = $this->school_model->get_balance_entries_today($this->session->userdata("school_id"),date('Y-m-d'));
		$drs = $this->db->query("select * from  items where status='1' order by priority asc");         
        $data["rset"] = $drs;
		
		
		$data["module"] = "admin";
        $data["view_file"] = "school/item_wise_report";
        echo Modules::run("template/admin", $data);
         
	}
	function customreportsnew()
	{
		
		$item_names   = array();
		$data = array();
		if($this->input->post('fromdate') !="")
		{
				//print_a($_POST,1);
				$from_date = date('Y-m-d',strtotime($this->input->post('fromdate')));
				$to_date = date('Y-m-d',strtotime($this->input->post('todate')));	
				$school_id = $this->session->userdata("school_id");
				$table_temp = "temptable".uniqid();
				$sql = "create temporary table  $table_temp select opening_tbl_item_id as item_id,opening_tbl_entry_date,closing_tbl_entry_date from (


					((select item_id as opening_tbl_item_id,max(entry_date) opening_tbl_entry_date  from balance_sheet where entry_date <'$from_date' and school_id='$school_id' 
								group by item_id
							) as opening_tbl  
							
							inner join 
						
							(
								select  item_id as  closing_tbl_item_id,max(entry_date) closing_tbl_entry_date  from balance_sheet where entry_date <='$to_date' and school_id='$school_id' 
									group by item_id
							) as closing_tbl on opening_tbl.opening_tbl_item_id = closing_tbl.closing_tbl_item_id
							
					) 
					) ";
					$this->db->query($sql);
					//item_id opening_tbl_entry_date closing_tbl_entry_date
					$sql = "select  
						from balance_sheet bs inner join  $table_temp opening on  bs.item_id = opening.item_id  and bs.entry_date =opening.opening_tbl_entry_date
						inner join $table_temp closing on  bs.item_id = closing.item_id  and bs.entry_date =closing.closing_tbl_entry_date
						inner join $table_temp closing on  bs.item_id = closing.item_id  and bs.entry_date =closing.closing_tbl_entry_date
					
					";
				
			
				
				

		}
		
		
		
		$data["item_prices"] = $this->school_model->get_items_price($this->session->userdata("district_id"));		
		$data["today_purchases"] = $this->school_model->get_balance_entries_today($this->session->userdata("school_id"),date('Y-m-d'));
		$drs = $this->db->query("select * from  items where status='1'");         
        $data["rset"] = $drs;
        $data["itemnames"] = $item_names;
		
		
		$data["module"] = "admin";
        $data["view_file"] = "school/customreports";
        echo Modules::run("template/admin", $data);
         
	}
	
	
	function customreports()
	{
		$this->form_validation->set_rules('fromdate', 'From Date', 'required');              
		$this->form_validation->set_rules('todate', 'To Date', 'required'); 
		$item_names   = array();
		$data = array();
		if($this->form_validation->run() == true && $this->input->post('fromdate') !="" && $this->input->post('todate') !="")
		{
				
				
				
				//print_a($_POST,1);
				$from_date = date('Y-m-d',strtotime($this->input->post('fromdate')));
				$to_date = date('Y-m-d',strtotime($this->input->post('todate')));
				
				
				$opening_qty = 0;
				$opening_price = 0;
				$items = array();
				
				//get all items between dates 
				  $sql = "SELECT distinct bs.item_id,item_name   FROM `balance_sheet`  bs inner join items on items.item_id=bs.item_id  
				WHERE bs.school_id='".$this->session->userdata("school_id")."' and  bs.entry_date between '".$from_date ."' and '".$to_date ."'";
				$rs  = $this->db->query($sql);
				$item_ids  = array();
				
				foreach($rs->result() as $row)
				{
					$item_ids[] = $row->item_id;
					$item_names[$row->item_id] = $row->item_name;
				}
				//print_a($item_ids);
				//get the opening balances of from date 
				  /*
				  calculate opening balances
				  */
				  foreach($item_ids as $opening_item_id){
								
								
								$tsql = "select *,truncate((opening_quantity*opening_price),2) as opening_total from balance_sheet 
								where school_id='".$this->session->userdata("school_id")."' and 
								entry_date BETWEEN '".$from_date ."'  and '".$to_date ."'  and 
								item_id='".$opening_item_id."' order by entry_date asc limit 0,1";
								$trs  = $this->db->query($tsql);
								if($trs->num_rows()>0){
									$sdata = $trs->row();
										$items[$opening_item_id]['opening_quantity'] = $sdata->opening_quantity;
										$items[$opening_item_id]['opening_price'] = $sdata->opening_price;
										$items[$opening_item_id]['opening_total'] = $sdata->opening_total;
								}
								else{
										$tsql = "select *,truncate((opening_quantity*opening_price),2) as opening_total from balance_sheet where
													school_id='".$this->session->userdata("school_id")."'
												and entry_date <='".$from_date ."' and item_id='".$opening_item_id."' 
												order by entry_date desc limit 0,1";
									$trs  = $this->db->query($tsql);
										if($trs->num_rows()>0){
											$sdata = $trs->row();
												$items[$opening_item_id]['opening_quantity'] = $sdata->opening_quantity;
												$items[$opening_item_id]['opening_price'] = $sdata->opening_price;
												$items[$opening_item_id]['opening_total'] = $sdata->opening_total;
										}
									
								}
		
					}
					/*
					End of opeinig balances
					*/
					/*
					calculate Closing balances
					*/
					
					foreach($item_ids as $opening_item_id){
								
								 
								$tsql = "select *,truncate((closing_quantity*closing_price),2) as closing_total from balance_sheet 
								where school_id='".$this->session->userdata("school_id")."' and 
								entry_date BETWEEN '".$from_date ."'  and '".$to_date ."'  and 
								item_id='".$opening_item_id."' order by entry_date desc limit 0,1";
								$trs  = $this->db->query($tsql);
								if($trs->num_rows()>0){
									$sdata = $trs->row();
										$items[$opening_item_id]['closing_quantity'] = $sdata->closing_quantity;
										$items[$opening_item_id]['closing_price'] = $sdata->closing_price;
										$items[$opening_item_id]['closing_total'] = $sdata->closing_total;
								}
								else{
										$tsql =  "select *,(closing_quantity*closing_price) as closing_total from balance_sheet 
								where school_id='".$this->session->userdata("school_id")."' and 
								entry_date <='".$from_date ."'  and item_id='".$opening_item_id."' 
								order by entry_date desc limit 0,1";
									$trs  = $this->db->query($tsql);
										if($trs->num_rows()>0){
											$sdata = $trs->row();
												$items[$opening_item_id]['closing_quantity'] = $sdata->closing_quantity;
												$items[$opening_item_id]['closing_price'] = $sdata->closing_price;
												$items[$opening_item_id]['closing_total'] = $sdata->closing_total;
										}
									
								}
		
					}
					
								
					/* end of closing balances */
					
				/* calculate consumption data 
				*/				
				$sql = "select item_id,sum(( session_1_qty+session_2_qty+session_3_qty+session_4_qty)) as consumed_qty ,
						truncate(sum(
							( 	(session_1_qty*session_1_price) + 
								(session_2_qty*session_2_price) + 
								(session_3_qty*session_3_price) + 
								(session_4_qty*session_4_price) )
							),2) as consumed_total
						from balance_sheet
					where school_id='".$this->session->userdata("school_id")."' and 
					entry_date between '$from_date' and '$to_date' group by item_id";
				 $rs  = $this->db->query($sql);
				foreach($rs->result() as $row)
				{
					$items[$row->item_id]['consumed_quantity'] = $row->consumed_qty;
					$items[$row->item_id]['consumed_total'] = $row->consumed_total;
					 
				}	
		
		/*
		calculate purchase data 
		*/
		
		$sql ="select item_id,sum(purchase_quantity) as purchase_qty,	purchase_price,
		
							truncate(sum((purchase_quantity*purchase_price)),2) purchase_total from balance_sheet 
							where school_id='".$this->session->userdata("school_id")."'and entry_date between '$from_date' and '$to_date' group by item_id";				 
		
		
		$rs  = $this->db->query($sql);
				foreach($rs->result() as $row)
				{
					$items[$row->item_id]['purchase_price'] = $row->purchase_price;
					$items[$row->item_id]['purchase_qty'] = $row->purchase_qty;
					$items[$row->item_id]['purchase_total'] = $row->purchase_total;
					 
				}					
				
				 /* end purchase data */
				 
				 //rearrange items array , check all keys exists or not if not add trhem
				 
				  $items_dup = $items;
				 foreach($items_dup as $item_id=>$itemobj)
				 {
					 
					 $cols =  array(
								'opening_quantity',
								'opening_price',
								'opening_total',
								'purchase_price',
								'closing_quantity',
								'closing_price',
								'closing_total',
								'consumed_quantity',
								'consumed_total',
								'purchase_qty',
								'purchase_total',
								'total_qty',
								'total_price');
								foreach($cols as $column){
										 if(!array_key_exists( $column,$itemobj))
												$items[$item_id][ $column] = '0';
								}
						
						
				 }
				 /* calaculate Total from opeinig balance and purchase balance */
				 $items_dup = $items;
				 foreach($items_dup as $item_id=>$itemobj)
				 {
					// echo $item_id;
					 
				//print_a($item_id,1);
						$items[$item_id]['total_qty'] =    ($itemobj['purchase_qty'] +  $itemobj['opening_quantity']);
						$items[$item_id]['total_price'] =   ($itemobj['purchase_total'] +  $itemobj['opening_total']);
				 }
				 
		
				$data['rfrom_date'] = $from_date;
				$data['rto_date'] = $to_date;
				$data['items'] = $items;
				
				if($this->input->post('submit')=="Download Report")
				{
					$filedata['fromdate'] = date('d-M-Y',strtotime($this->input->post('fromdate')));
					$filedata['todate'] =  date('d-M-Y',strtotime($this->input->post('todate')));
					$filedata['sname'] = $this->session->userdata("user_name");
					$this->consolidated_report($items,$filedata,$item_names);
					die;
				}

		}
		
		
		
		$data["item_prices"] = $this->school_model->get_items_price($this->session->userdata("district_id"));		
		$data["today_purchases"] = $this->school_model->get_balance_entries_today($this->session->userdata("school_id"),date('Y-m-d'));
		$drs = $this->db->query("select * from  items where status='1'");         
        $data["rset"] = $drs;
        $data["itemnames"] = $item_names;
		
		
		$data["module"] = "admin";
        $data["view_file"] = "school/customreports";
        echo Modules::run("template/admin", $data);
         
	}
	
	
	
	function itemreport($item_id ,$from_date,$to_date,$type='display')
	{
			$data["from_date_dp"] = date('d-M-Y',strtotime($from_date));
			$data["to_date_dp"] =date('d-M-Y',strtotime($to_date));
		$columns = array();
	 
			   $sqry =  " select bs.item_id,items.item_name,session_1_qty,session_2_qty,session_3_qty,session_4_qty,
								DATE_FORMAT(entry_date,'%d-%M-%Y') as entry_date_dp, 
								opening_quantity,purchase_quantity,purchase_price,
								(opening_quantity+ purchase_quantity) as total_qty,
								(session_1_qty+	session_2_qty+	session_3_qty+ session_4_qty) as consumed_qty,
									closing_quantity,
								((session_1_qty*session_1_price) +(session_2_qty*session_2_price)+(session_3_qty*session_3_price)+(session_4_qty*session_4_price)) as consumed_total 
						from balance_sheet bs inner join items on  bs.item_id= items.item_id where
						school_id='".$this->session->userdata("school_id")."' and entry_date between '$from_date' and '$to_date'  and bs.item_id='$item_id' and entry_date>'2016-08-31' order by entry_date desc";
				 //echo $sqry;
				 $daily_item_details = $this->db->query( $sqry);
		$item_data = $this->school_model->get_itemdetails($item_id);
		
		if($type=="download"){
			$extra_params = array('from_date_dp'=>$data["from_date_dp"], 'to_date_dp'=>$data["to_date_dp"]);
				$this->download_item_report($daily_item_details,$item_data,$extra_params);
		}else {
			//print_a($list );
			$data['daily_item_details'] = $daily_item_details;
	
			$data["module"] = "admin";
			$data["from_date"] = $from_date;
			$data["to_date"] = $to_date;
			$data["item_id"] = $item_id;
			$data["item_details"] = $this->school_model->get_itemdetails($item_id);
			$data["view_file"] = "school/item_report";
			echo Modules::run("template/admin", $data);
		}
         
	}
	function get_item_consumption_details($item_id=null)
	{
		  $sqry =  " select entry_id,session_1_qty,session_2_qty,session_3_qty,session_4_qty,
								DATE_FORMAT(entry_date,'%d-%m-%Y') as entry_date_dp, 
								opening_quantity,purchase_quantity,purchase_price,
								(opening_quantity+ purchase_quantity) as total_qty,
								(session_1_qty+	session_2_qty+	session_3_qty+ session_4_qty) as consumed_qty,
								((session_1_qty*session_1_price) +(session_2_qty*session_2_price)+(session_3_qty*session_3_price)+(session_4_qty*session_4_price)) as consumed_total 
						from balance_sheet where school_id='".$this->session->userdata("school_id")."' and item_id='$item_id' order by entry_date desc";
				 //echo $sqry;
				 $rset = $this->db->query( $sqry);
				 
				  return $rset;
	}
	
	
	public function consolidated_report($rows,$metadata,$item_names)
    {
          
                $this->excel->setActiveSheetIndex(0);
                //name the worksheet
                $this->excel->getActiveSheet()->setTitle('Report');
                //set cell A1 content with some text
                $this->excel->getActiveSheet()->setCellValue('A1', $this->config->item('society_name').$metadata['sname']);
				 //merge cell A1 until Q1
                $this->excel->getActiveSheet()->mergeCells('A1:H1');
                $this->excel->getActiveSheet()->setCellValue('A2', 'DIET EXPENDITURE STATEMENT FOR dates between '.$metadata['fromdate']. " - ".$metadata['todate']);
				//merge cell A2 until Q2
                $this->excel->getActiveSheet()->mergeCells('A2:H2');
				
				$this->excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                //make the font become bold
                $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
                $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(16);
                $this->excel->getActiveSheet()->getStyle('A1')->getFill()->getStartColor()->setARGB('#333');
				
				$this->excel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                //make the font become bold
                $this->excel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);
                $this->excel->getActiveSheet()->getStyle('A2')->getFont()->setSize(12);
                $this->excel->getActiveSheet()->getStyle('A2')->getFill()->getStartColor()->setARGB('#333');
				
				
				
				 
				
				$this->excel->getActiveSheet()->setCellValue('A3', 'SLNO');
				$this->excel->getActiveSheet()->setCellValue('B3', 'Item');				
				$this->excel->getActiveSheet()->setCellValue('C3', 'Opening Balance Qty');
				$this->excel->getActiveSheet()->setCellValue('D3', 'Purchase Qty');
				$this->excel->getActiveSheet()->setCellValue('E3', 'Total Qty');				
				$this->excel->getActiveSheet()->setCellValue('F3', 'Consumption Qty');				
				$this->excel->getActiveSheet()->setCellValue('G3', 'Closing Qty');				
				$this->excel->getActiveSheet()->setCellValue('H3', 'Consumption Amount');
			 
														 
				
                 $this->excel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                //make the font become bold
                $this->excel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);
				$this->excel->getActiveSheet()->getStyle('A3:O3')->getFont()->setBold(true);
				
                $this->excel->getActiveSheet()->getStyle('A2')->getFont()->setSize(12);
                $this->excel->getActiveSheet()->getStyle('A2')->getFill()->getStartColor()->setARGB('#333');
                
                $i = 4;
				$sno=1;
				$consumption_amount_total = 0;
				foreach($rows as $item_idd =>$rowitem)
				{
					//echo $item_idd;
				//print_a($rowitem);
				 
						$consumption_amount_total = $consumption_amount_total + 	$rowitem['consumed_total'];	
					$this->excel->getActiveSheet()->setCellValue('A'.$i,  $sno);
					$this->excel->getActiveSheet()->setCellValue('B'.$i, $item_names[$item_idd]);
					$this->excel->getActiveSheet()->setCellValue('C'.$i, $rowitem['opening_quantity']);
					$this->excel->getActiveSheet()->setCellValue('D'.$i, $rowitem['purchase_qty']);
					$this->excel->getActiveSheet()->setCellValue('E'.$i, $rowitem['total_qty']);
					$this->excel->getActiveSheet()->setCellValue('F'.$i, $rowitem['consumed_quantity']);
					
					$this->excel->getActiveSheet()->setCellValue('G'.$i, $rowitem['closing_quantity']);
					$this->excel->getActiveSheet()->setCellValue('H'.$i,  number_format($rowitem['consumed_total'],2));
					 
					$i++;$sno++;
				}
	 
					$this->excel->getActiveSheet()->setCellValue('G'.$i, "Total Consumption  Amount");
					$this->excel->getActiveSheet()->setCellValue('H'.$i,  number_format($consumption_amount_total,2));
				$this->excel->getActiveSheet()->getStyle('A'.$i.':Z'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                //make the font become bold
                $this->excel->getActiveSheet()->getStyle('A'.$i.':Z'.$i)->getFont()->setBold(true);
                
              
                $filename='report_'.date('d-m-Y').'.xls'; //save our workbook as this file name
                header('Content-Type: application/vnd.ms-excel'); //mime type
                header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
                header('Cache-Control: max-age=0'); //no cache
 
                //save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
                //if you want to save it as .XLSX Excel 2007 format
                $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');  
                //force user to download the Excel file without writing it to server's HD
                $objWriter->save('php://output');
                 
    }
	
	public function download_item_report($list,$item_data,$extra_params)
    {
         // print_a($extra_params,1);
                $this->excel->setActiveSheetIndex(0);
                //name the worksheet
                $this->excel->getActiveSheet()->setTitle($item_data->item_name."-".'Report');
                //set cell A1 content with some text
                $this->excel->getActiveSheet()->setCellValue('A1', $this->config->item('society_name').$this->session->userdata("user_name") );
				 //merge cell A1 until Q1
                $this->excel->getActiveSheet()->mergeCells('A1:I1');
                $this->excel->getActiveSheet()->setCellValue('A2', 'DIET EXPENDITURE STATEMENT FOR '.$item_data->item_name  ." - Dates between ".$extra_params['from_date_dp']." to ".$extra_params['to_date_dp'] );
				//merge cell A2 until Q2
                $this->excel->getActiveSheet()->mergeCells('A2:I2');
				
				$this->excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                //make the font become bold
                $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
                $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(16);
                $this->excel->getActiveSheet()->getStyle('A1')->getFill()->getStartColor()->setARGB('#333');
				
				$this->excel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                //make the font become bold
                $this->excel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);
                $this->excel->getActiveSheet()->getStyle('A2')->getFont()->setSize(12);
                $this->excel->getActiveSheet()->getStyle('A2')->getFill()->getStartColor()->setARGB('#333');
				
				
															
				$this->excel->getActiveSheet()->setCellValue('A3', 'SLNO');
				$this->excel->getActiveSheet()->setCellValue('B3', 'Date');
				
				$this->excel->getActiveSheet()->setCellValue('C3', 'Opening Qty');
				$this->excel->getActiveSheet()->setCellValue('D3', 'Purchase Qty');
				
				$this->excel->getActiveSheet()->setCellValue('E3', 'Purchase Price');				
				$this->excel->getActiveSheet()->setCellValue('F3', 'Total Qty');		
				
				$this->excel->getActiveSheet()->setCellValue('G3', 'Consumption Qty');				
				$this->excel->getActiveSheet()->setCellValue('H3', 'Closing Qty');
				
				
				$this->excel->getActiveSheet()->setCellValue('I3', 'Total Consumed Price');				
				 
				
														 
				
                 $this->excel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                //make the font become bold
                $this->excel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);
				$this->excel->getActiveSheet()->getStyle('A3:O3')->getFont()->setBold(true);
				
                $this->excel->getActiveSheet()->getStyle('A2')->getFont()->setSize(12);
                $this->excel->getActiveSheet()->getStyle('A2')->getFill()->getStartColor()->setARGB('#333');
                
                $i = 4;
				$sno=1;
				$total_amount_consumed = 0;
				$total_qty_consumed = 0;
				foreach($list->result() as $item_idd =>$rowitem)
				{
					$total_amount_consumed = $total_amount_consumed + $rowitem->consumed_total;
					$total_qty_consumed =  $total_qty_consumed + $rowitem->consumed_qty;
				 
						//print_a($rowitem,1);
						//Date	Opening Qty	Purchase Qty	Purchase Price	Total Qty	Consumption Qty	Closing Qty	Total Consumed Price
						 
						   
					$this->excel->getActiveSheet()->setCellValue('A'.$i,  $sno);
					$this->excel->getActiveSheet()->setCellValue('B'.$i, $rowitem->entry_date_dp);
					$this->excel->getActiveSheet()->setCellValue('C'.$i, $rowitem->opening_quantity);
					$this->excel->getActiveSheet()->setCellValue('D'.$i, $rowitem->purchase_quantity);
					$this->excel->getActiveSheet()->setCellValue('E'.$i, $rowitem->purchase_price);
					$this->excel->getActiveSheet()->setCellValue('F'.$i, $rowitem->total_qty);
					
					$this->excel->getActiveSheet()->setCellValue('G'.$i, $rowitem->consumed_qty);
					$this->excel->getActiveSheet()->setCellValue('H'.$i, $rowitem->closing_quantity);
					$this->excel->getActiveSheet()->setCellValue('I'.$i, $rowitem->consumed_total); 
					$i++;$sno++;
				}
				
					$this->excel->getActiveSheet()->setCellValue('F'.$i, 'Total Consumed Qty');
					
					$this->excel->getActiveSheet()->setCellValue('G'.$i, number_format($total_qty_consumed,3));
					$this->excel->getActiveSheet()->setCellValue('H'.$i, 'Total Consumed Amount');
					$this->excel->getActiveSheet()->setCellValue('I'.$i, number_format($total_amount_consumed ,2)); 
					$this->excel->getActiveSheet()->getStyle(('A'.$i.':O'.$i))->getFont()->setBold(true);
					$this->excel->getActiveSheet()->getStyle('A'.$i.':O'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                
              
                $filename= $item_data->item_name.'-report_'.date('d-m-Y').'.xls'; //save our workbook as this file name
                header('Content-Type: application/vnd.ms-excel'); //mime type
                header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
                header('Cache-Control: max-age=0'); //no cache
 
                //save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
                //if you want to save it as .XLSX Excel 2007 format
                $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');  
                //force user to download the Excel file without writing it to server's HD
                $objWriter->save('php://output');
                 
    }
	 

	 function schoolprice() {
       //    Modules::run("security/is_admin");
         $this->load->model('admin_model');
         if ($this->input->post("action")=="updateprice") {
				 $strength = $this->input->post("strength");
				 $price = $this->input->post("price");
				 $school_id = $this->session->userdata("school_id");
				 
				 $ip_condition = " and amount_updated='0' ";
				 if(ip_allowed_to_edit($this->input->ip_address())){
							 $ip_condition = '';
					}
				 $this->db->query("update  schools set strength='$strength',daily_amount='$price',amount_updated='1' where school_id='$school_id' $ip_condition  ");
				 
				 $this->session->set_flashdata('message', '<div class="alert alert-success">Updated Successfully.</div>');
        }
		$school_id = $this->session->userdata("school_id");
		$rs =  $this->db->query("select * from  schools  where school_id='$school_id'");
		$data['school_data'] = $rs->row();
        $data["module"] = "admin";
        $data["view_file"] = "school_price_update";
        echo  Modules::run("template/admin", $data);
    }
	function today_consumed_balance($school_id=null)
	{
		if($this->session->userdata("user_role")=="school")
		{
			$school_id = $this->session->userdata("school_id");
		}
		
		$rs =  $this->db->query("select * from  schools  where school_id='$school_id'");
		$data['school_data'] = $rs->row();
        $data["module"] = "admin";
        $data["view_file"] = "school_price_update";
        echo  Modules::run("template/admin", $data);
	}
	   function today_consumed_balancenew() {
		 
		$today_allowed_Amount = '0.00';
		$today_consumed_Amount = '0.00';
		$today_remaining_Amount = '0.00';
		$data['result_flag']			  =  0;
		if($this->input->post('school_code')!="")
		 {
			 $school_code = $this->input->post('school_code');
			 $school_date = date('Y-m-d',strtotime($this->input->post('school_date')));
			 
			 $srs = $this->db->query("select * from users where school_code='$school_code'");
			 $school_data = $srs->row();
			 $data['school_info'] =  $school_data;
			 $school_id = $school_data->school_id;		
			$data['result_flag']			  =  1;
		 }
		 
		if($this->session->userdata("user_role")=="school")
		{
			$school_id = $this->session->userdata("school_id");
			$srs = $this->db->query("select * from schools where school_id='$school_id'");
			$sch_data = $srs->row();
			 $data['school_info'] =  $sch_data;
			$school_code = $sch_data->school_code;	
			
		}
		$school_date = date('Y-m-d',strtotime($this->input->post('school_date')));
		if($this->input->post('school_date')=="")
		{
			$school_date = date('Y-m-d');
		}
		/* calculate balances */
		
		$trs_sql  = "select sum(round(((session_1_qty*session_1_price) +(session_2_qty*session_2_price)+(session_3_qty*session_3_price)+(session_4_qty*session_4_price)),2)) as consumed_total
					from balance_sheet where school_id='$school_id' and entry_date='$school_date'";
		
		/*if($_GET['test']==1)
			echo $trs_sql ;
		*/
		$trs = $this->db->query($trs_sql);
					
		if($trs->num_rows()>0)
		{
			$tdata = $trs->row();
			$today_consumed_Amount = $tdata->consumed_total;
		}
		/**********Calculate attendence ***************/
		
		$daily_amount  = 0.00;
		
		/*
			Get Amounts				
		*/ 
		$price_sql = "select * from group_prices";
		$price_rs = $this->db->query($price_sql);
		$student_prices = array();
		foreach($price_rs->result() as $stu_price){
			$student_prices[$stu_price->group_code] = $stu_price->amount;
		}


		
		

		/******get attendence ******/
			
		$attendece  = 0;
		$group_1_attendence = 0;
		$group_2_attendence = 0;
		$group_3_attendence = 0;
		$group_1_price = 0;
		$group_2_price = 0;
		$group_3_price = 0;
		
		$group_1_perday_price = 0;
		$group_2_perday_price = 0;
		$group_3_perday_price = 0;
		
		
		$days_sql = "SELECT DAY( LAST_DAY( '$school_date' ) ) as days";
		$days_row  = $this->db->query($days_sql)->row();
		  $days_count = $days_row->days ;
		//print_a($student_prices);
		
		$group_1_perday_price = ($student_prices['gp_5_7']/$days_count);
		$group_2_perday_price =($student_prices['gp_8_10']/$days_count);
		$group_3_perday_price = ($student_prices['gp_inter']/$days_count);
		
		$atters_sql = "select * from school_attendence where school_id='$school_id' and entry_date='$school_date'";
		$atters = $this->db->query($atters_sql);
		if($atters->num_rows()>0)
		{
			$attedata = $atters->row();
			
			$group_1_attendence =  $attedata->class_5_count  + $attedata->class_6_count  + $attedata->class_7_count ;
			$group_2_attendence =  $attedata->class_8_count  + $attedata->class_9_count  + $attedata->class_10_count ;
			$group_3_attendence =  $attedata->inter_mpc_1  + $attedata->inter_mpc_2  + $attedata->inter_bipc_1  + $attedata->inter_bipc_2 + $attedata->inter_cec_1 + $attedata->inter_cec_2	 + $attedata->inter_hec_1 + $attedata->inter_hec_2 + $attedata->inter_mec_1 +  $attedata->inter_mec_2;
			 
			$group_1_price =  $group_1_attendence * ($student_prices['gp_5_7']/$days_count);
			$group_2_price =  $group_2_attendence * ($student_prices['gp_8_10']/$days_count);
			$group_3_price =  $group_3_attendence *  ($student_prices['gp_inter']/$days_count);
			
			
			
			$attendece =  $attedata->present_count;
			$attendece2 = $group_1_attendence + $group_2_attendence + $group_3_attendence;
		}
		$data['student_prices'] = $student_prices;
		$data['days_count'] = $days_count;
		$data['group_1_attendence'] = $group_1_attendence;
		$data['group_2_attendence']= $group_2_attendence ;
		$data['group_3_attendence'] = $group_3_attendence ;
		$data['group_1_price'] = number_format($group_1_price,2);
		$data['group_2_price'] = number_format($group_2_price,2);
		$data['group_3_price'] = number_format($group_3_price,2);
		
		$data['group_1_perday_price'] = number_format($group_1_perday_price,4);
		$data['group_2_perday_price'] = number_format($group_2_perday_price,4);
		$data['group_3_perday_price'] = number_format($group_3_perday_price,4);
		 
		//echo $attendece2 ;
	//	echo $group_1_attendence ,"--",$group_2_attendence ,"---",$group_3_attendence ,"---"  ;
		//echo "<br>",$group_1_price ,"--",$group_2_price ,"---",$group_3_price ,"---"  ;
		$today_allowed_Amount = $group_1_price + $group_2_price + $group_3_price;
		/**************************************/
		
        $data["reportdate"] =  date('d-M-Y',strtotime($this->input->post('school_date')));
        $data["school_name"] = $today_allowed_Amount;
        $data["per_stundent"] = $daily_amount;
        $data["attendence"] = $attendece;
		
        $data["today_allowed_Amount"] = number_format($today_allowed_Amount,2);
        $data["today_consumed_Amount"] = $today_consumed_Amount;
        $data["today_remaining_Amount"] = number_format($today_allowed_Amount -  $today_consumed_Amount,2);
		
        $data["school_code"] = $school_code;
        $data["module"] = "admin";
        $data["view_file"] = "school_consumed";
        echo Modules::run("template/admin", $data);
    }
	function recalculate_item($item_id=null,$session_id=null)
	{
		$school_id = $this->session->userdata("school_id");
		$entry_date = '2016-08-31';
		$this->school_model->update_entries($school_id,$item_id,$entry_date);
		redirect('admin/school/consumption_entryform/'.$item_id.'/'.$session_id);
	}
	function purchase_bills()
	{
		$image_crud = new image_CRUD();
		
		$image_crud->set_primary_key_field('id');
		$image_crud->set_url_field('url');
		$image_crud->set_table('example_1')
			->set_image_path('assets/uploads');
			
		$output = $image_crud->render();
		
		$this->_example_output($output);
	}


}
