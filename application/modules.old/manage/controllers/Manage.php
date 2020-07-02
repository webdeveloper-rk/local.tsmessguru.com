<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Manage extends MX_Controller {

	public function __construct()
	{
		parent::__construct();

		$this->load->database();
		$this->load->helper('url');

		$this->load->library('grocery_CRUD');
		 
		if($this->uri->segment(2) !="login") { 
					 Modules::run("security/is_admin");		 
					if ($this->session->userdata("is_loggedin") != TRUE || $this->session->userdata("user_id") == "" ) {
							redirect("admin/login");
							die;
					}
					 
					if ($this->session->userdata("user_role") != "subadmin" ) {
							redirect("dashboard");
							die;
					}
					
		}
					
            
		 
	}

	public function _example_output($output = null)
	{
		$this->load->view('example.php',$output);
	}

	function index()
	{
		redirect("manage/items");
	}
	public function items()
	{
		try{
			$crud = new grocery_CRUD();

			$crud->set_theme('flexigrid');
			$crud->set_theme('datatables');
			$crud->set_table('items');
			$crud->set_subject('Items');
			//$crud->unset_add();
            $crud->unset_delete();
			$crud->columns(array('telugu_name','item_name','item_code','item_type','min_price','max_price'));
			$crud->add_fields(array('telugu_name','item_name','item_code','item_type','min_price','max_price','status'));
			$crud->edit_fields(array('telugu_name','item_name','item_code','item_type','min_price','max_price','status'));
			$crud->required_fields(array('telugu_name','item_name','item_type'));
			$crud->callback_after_insert(array($this, 'update_balancesheet_entries'));
			$crud->unique_fields(array('telugu_name','item_name' ));
			 
			 

			$output = $crud->render();

			//$this->_example_output($output);
			$data["module"] = "manage";
			$data["view_file"] = "cms";
			$output->title = "Manage Items";
			$data["crud"] = $output;
			echo Modules::run("template/admin", $data);
			

		}catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
	}
	
	public function atdos()
	{
		try{
			$crud = new grocery_CRUD();
 
			$crud->set_theme('datatables');
			$crud->set_table('users');
			$crud->set_subject('Atdos');
			$crud->where ('is_atdo', 1);
			$crud->display_as('school_code', 'Atdo code');
			 
			 
            $crud->unset_edit();
            $crud->unset_delete();
			 $crud->columns(array('school_code','name','district_id','email'));
			 
			 $crud->callback_after_insert(array($this, 'updatepassword_roles'));
			 
			$crud->add_action('Assign Schools', '', 'assignschools/update','ui-icon-plus');
			
			$crud->required_fields(['school_code','name','email','password','district_id']);
			$crud->field_type('school_id','hidden','0');
			$crud->field_type('rpass','hidden','123456');
			$crud->field_type('is_atdo','hidden','1');
			$crud->field_type('is_dco','hidden','1');
			$crud->field_type('utype','hidden','subadmin');   
			$crud->field_type('contact_no','hidden','00000');   
			$crud->field_type('activation_code','hidden','');   
			$crud->field_type('registered_time','hidden','');   
			$crud->field_type('activated_time','hidden','');   
			$crud->field_type('registered_ip','hidden',$this->input->ip_address());   
			$crud->field_type('terms_acceptance','hidden','true');   
			$crud->field_type('operator_type','hidden','DEO');   
			$crud->field_type('status','hidden','A');   
			$crud->field_type('is_collector','hidden','0');   
			$crud->field_type('old_school_code','hidden',' ');   
			$crud->field_type('ddo_code','hidden','');   
			
			
			$crud->set_relation('district_id','districts','name');
			$crud->display_as('school_code','Atdo Code');
			$crud->display_as('district_id','District  Name');
			$crud->unique_fields(array('school_code','email'));
			
			
			$output = $crud->render();

			//$this->_example_output($output);
			$data["module"] = "manage";
			$data["view_file"] = "cms";
			$output->title = "Manage Atdos";
			$data["crud"] = $output;
			echo Modules::run("template/admin", $data);
			

		}catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
	}
	
	
	
	 public function items_per_head()
	{
		try{
			$crud = new grocery_CRUD();

			$crud->set_theme('flexigrid');
			$crud->set_theme('datatables');
			$crud->set_table('item_per_head');
			$crud->set_subject('Items Per head indent');
			$crud->unset_add();
            $crud->unset_delete();
			$crud->columns(array('item_id','grams_per_head' ));
			$crud->field_type('item_id','readonly');
			$crud->field_type('type_of_item','readonly'); 
			$crud->required_fields(array('grams_per_head' )); 
			 $crud->set_relation('item_id','items','{telugu_name}-{item_name}');
			 $crud->display_as('grams_per_head', 'KG Per head(Please convert grams into Kgs format)');
			 
			 

			$output = $crud->render();

			//$this->_example_output($output);
			$data["module"] = "manage";
			$data["view_file"] = "cms";
			$output->title = "Manage Indent Items grams per head on avg";
			$data["crud"] = $output;
			echo Modules::run("template/admin", $data);
			

		}catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
	}
	  public function  updatepassword_roles($post_array,$primary_key)
	{
			 
			 $uid = $primary_key;
			 $update_sql  = "update users set password=md5(password),ddo_code=school_code where uid=?";
			 $this->db->query( $update_sql,array($uid)); 
			 
			 $school_code = $this->db->query("select * from users where uid=?",array($uid))->row()->school_code;
			 
			 $ins_data = array('uid'=> $uid,'school_code'=>$school_code,'role_id'=>11,'start_date'=>date('y-m-d h:i:s'),'end_date'=>'2099-01-01');
			 $this->db->insert("user_roles", $ins_data );
			 return true;
	}
	public function  update_balancesheet_entries($post_array,$primary_key)
	{
			 
			 $item_id = $primary_key;
			 $update_balancesheet_sql  = "insert into balance_sheet(school_id,item_id,entry_date) select school_id,'$item_id' as item_id,CURRENT_DATE as entry_date from schools  where is_school=1";
			 $this->db->query( $update_balancesheet_sql,array($item_id));
			// $this->db->last_query();die;
			 return true;
	}
	
		  function fixed_rates(){
		
		try{
			$crud = new grocery_CRUD($this);
			 
			$crud->set_theme('flexigrid'); 
			$crud->set_table('fixed_rates');
		 
		 
			$crud->set_subject(' Fuel Charges');
			$crud->columns(array('school_code','school_id','item_name','amount' ));
			 
					 
			 $crud->unset_add(); 
            $crud->unset_delete();
			 
			
			$crud->edit_fields(array('school_code','school_id','item_name','amount' ));
			 

			$crud->set_relation('school_id','schools','name');
			$crud->field_type('school_id', 'readonly');
			$crud->field_type('school_code', 'readonly');
			$crud->field_type('item_name', 'readonly');
			$crud->display_as('school_id', 'School Name');
			 

			$output = $crud->render(); 
			$data["module"] = "cms";
			$data["extra_content"] = "";
			$data["view_file"] = "cms";
			$output->title = "Manage Fuel Charges ";
			$data["crud"] = $output;
			echo Modules::run("template/admin", $data);
			

		}catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
	}
		 
	/**************************************************************



*******************************************************************/

	  function  schools(){
		  
		  if($this->session->userdata("is_dco") == 1)
		{
			redirect('admin/subadmin/schoolreporttoday');
		}
		
		try{
			$crud = new grocery_CRUD($this);
			 
			$crud->set_theme('flexigrid'); 
			$crud->set_table('schools'); 
			$crud->set_subject('Schools');  
			$crud->where('is_school',1);  
			
			$crud->unset_add();
			$crud->unset_delete();
			$crud->columns(array('school_code','name','region_id','district_id','amount_category','school_type'));
			$crud->set_relation('district_id','districts','name');
			$crud->set_relation('region_id','regions','region_name');
			
			$crud->add_fields(array('school_code','name','district_id','amount_category','school_type'));
			$crud->edit_fields(array('school_code','name','region_id','district_id','amount_category','school_type'));
			
			

			$output = $crud->render(); 
			$data["module"] = "manage";
			$data["extra_content"] = "";
			$data["view_file"] = "cms";
			$output->title = "Manage Schools";
			$data["crud"] = $output;
			echo Modules::run("template/admin", $data);
			

		}catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
	}
	/*********************************************************************************************************
	
	
	
	*********************************************************************************************************/

			  function fuel_dates(){
		
		try{
			$crud = new grocery_CRUD($this);
			 
			$crud->set_theme('datatables'); 
			$crud->set_table('fuel_entry_dates');
		 
		 
			$crud->set_subject(' Fuel Charges Dates');
			$crud->columns(array('school_code','school_id' ,'start_date' ));
			 
					 
			 $crud->unset_add(); 
            $crud->unset_delete();
			 
			
			$crud->edit_fields(array('school_code','school_id','start_date'  ));
			 

			$crud->set_relation('school_id','schools','name');
			$crud->field_type('school_id', 'readonly');
			$crud->field_type('school_code', 'readonly'); 
			$crud->display_as('school_id', 'School Name');
			 

			$output = $crud->render(); 
			$data["module"] = "cms";
			$data["extra_content"] = "";
			$data["view_file"] = "cms";
			$output->title = "  Fuel Charges Dates ";
			$data["crud"] = $output;
			echo Modules::run("template/admin", $data);
			

		}catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
	}
	
	 
}