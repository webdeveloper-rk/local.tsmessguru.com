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
					if ($this->session->userdata("is_loggedin") != TRUE || $this->session->userdata("user_id") == "" || $this->session->userdata("user_role") != "admin") {
							redirect("admin/login");
							die;
					}
		}
					
            
		 
	}

	public function _example_output($output = null)
	{
		$this->load->view('example.php',$output);
	}

	 
	public function index()
	{
		$this->pages();
	}
	public function notfound()
	{
		//$this->load->view('notfound');
		echo "No page found";
		die;
	}
	public function vendors()
	{
		try{
			$crud = new grocery_CRUD();

			$crud->set_theme('flexigrid');
			 //$crud->set_theme('datatables');
			$crud->set_table('vendors');
			$crud->set_subject('Vendors');
			//$crud->unset_add();
           // $crud->unset_delete();
			$crud->columns(array( 'name','vendor_code','address'));  
			 

			$output = $crud->render();

			//$this->_example_output($output);
			$data["module"] = "cms";
			$data["view_file"] = "cms";
			$output->title = "Manage Vendors";
			$data["crud"] = $output;
			echo Modules::run("template/admin", $data);
			

		}catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
	}
	public function areas()
	{
		try{
			$crud = new grocery_CRUD();

			$crud->set_theme('flexigrid');
			 //$crud->set_theme('datatables');
			$crud->set_table('mandels');
			$crud->set_subject('Areas');
			$crud->unset_add();
            $crud->unset_delete();
			$crud->columns(array( 'district_id','name')); 
			$crud->set_relation('district_id','districts','name');
			 

			$output = $crud->render();

			//$this->_example_output($output);
			$data["module"] = "cms";
			$data["view_file"] = "cms";
			$output->title = "Manage Areas";
			$data["crud"] = $output;
			echo Modules::run("template/admin", $data);
			

		}catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
	}
	public function items()
	{
		try{
			$crud = new grocery_CRUD();

			$crud->set_theme('flexigrid');
			 //$crud->set_theme('datatables');
			$crud->set_table('items');
			$crud->set_subject('Items');
			//$crud->unset_add();
            $crud->unset_delete();
			$crud->columns(array('telugu_name','item_name','item_type'));
			$crud->required_fields(array('telugu_name','item_name','item_type'));
			 
			 

			$output = $crud->render();

			//$this->_example_output($output);
			$data["module"] = "cms";
			$data["view_file"] = "cms";
			$output->title = "Manage Items";
			$data["crud"] = $output;
			echo Modules::run("template/admin", $data);
			

		}catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
	}
	public function  quotation_prices()
	{
		$quotation_id = $this->uri->segment(4);
		
		$rs = $this->db->query("SELECT * FROM  items where item_id not in (select item_id from   quotation_prices  WHERE  quotation_id ='$quotation_id')");
		foreach($rs->result() as $row)
		{
			 
			$this->db->query("insert into quotation_prices set  quotation_id ='$quotation_id', item_id='".$row->item_id."'");
		}
		
		
		
		try{
			$crud = new grocery_CRUD();

			$crud->set_theme('flexigrid');
			 //$crud->set_theme('datatables');
			$crud->set_table('quotation_prices');
		 	$crud->where('quotation_id',$quotation_id);
			$crud->unset_add(); 
			$crud->unset_delete(); 
			
			$crud->set_subject('quotation prices'); 
			$crud->columns(array('item_id','quantity','weight_type','price'));
			
			$crud->edit_fields(array('item_id','quantity','weight_type','price'));
			$crud->required_fields(array('quantity','weight_type','price'));
			 
			//$crud->set_relation('quotation_id','quotations','title',array('status' => 'active' ));
			//$crud->display_as('quotation_id','quotation_id2');
			
			$crud->field_type('quotation_id', 'readonly');
			$crud->field_type('item_id', 'readonly');
			$crud->callback_column('quotation_id',array($this,'_callback_quotation_title'));
			//$crud->callback_edit_field('quotation_id',array($this,'_callback_quotation_title'));
			
			  
			 $crud->set_relation('item_id','items','{item_name} - {telugu_name} ',array('status' => '1'));
			 

			$output = $crud->render();

			//$this->_example_output($output);
			$data["module"] = "cms";
			$data["view_file"] = "cms";
			$output->title =  $this->get_quotation_title($quotation_id);
			$data["crud"] = $output;
			echo Modules::run("template/admin", $data);
			

		}catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
	}
	public function get_quotation_title($value)
	{
		$rss = $this->db->query("select * from  quotations where quotation_id='$value'");
				$data = $rss->row();
				//print_r($data);
				return $data->title;
	}
	public function _callback_quotation_title($value, $row)
		{
				$rss = $this->db->query("select * from  quotations where quotation_id='$value'");
				$data = $rss->row();
				//print_r($data);
				return $data->title;
		}
	public function quotations()
	{
		try{
			$crud = new grocery_CRUD();

			$crud->set_theme('flexigrid');
			 //$crud->set_theme('datatables');
			$crud->set_table('quotations');
			$crud->set_subject('Quotations');
			
			
			$crud->set_relation('district_id','districts','name');
			$crud->set_relation('vendor_id','vendors','name');
			$crud->columns('title','vendor_id','district_id','start_date','end_date','status'); 
			$crud->add_fields('title','vendor_id','district_id','start_date','end_date','status'); 
			$crud->edit_fields('title','vendor_id','district_id','start_date','end_date','status'); 
			
			
			$crud->required_fields('title','vendor_id','district_id','start_date','end_date','status'); 
			 
			 $crud->unset_add_fields('created_date');
			 $crud->unset_edit_fields('updated_date');
			 

			$output = $crud->render();

			//$this->_example_output($output);
			$data["module"] = "cms";
			$data["view_file"] = "cms";
			$output->title = "Manage Quotations";
			$data["crud"] = $output;
			echo Modules::run("template/admin", $data);
			

		}catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
	}
	public function pages()
	{
		try{
			$crud = new grocery_CRUD();

			$crud->set_theme('flexigrid');
			 //$crud->set_theme('datatables');
			$crud->set_table('cms');
			$crud->set_subject('Pages');
			$crud->unset_add();
            $crud->unset_delete();
			$crud->columns(array('title','description'));
			 
			 //$crud->unset_texteditor('url','meta_description','meta_keywords','meta_tags','status'); 
			 $crud->unset_add_fields('url', 'meta_description','meta_title','meta_keywords','meta_tags','status');
			 $crud->unset_edit_fields('url', 'meta_title','meta_description','meta_keywords','meta_tags','status');
			$crud->columns('title', 'staus');

			$output = $crud->render();

			//$this->_example_output($output);
			$data["module"] = "cms";
			$data["view_file"] = "cms";
			$output->title = "Manage Pages";
			$data["crud"] = $output;
			echo Modules::run("template/admin", $data);
			

		}catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
	}
	public function aqpages()
	{
		try{
			$crud = new grocery_CRUD();

			$crud->set_theme('flexigrid');
			 //$crud->set_theme('datatables');
			$crud->set_table('cms_achivement');
			$crud->set_subject('Achivements/Quality/Services');
			$crud->unique_fields('url');
			//$crud->unset_add();
            //$crud->unset_delete();
			$crud->columns(array('title','description'));
			 
			 //$crud->unset_texteditor('url','meta_description','meta_keywords','meta_tags','status'); 
			 $crud->unset_add_fields( 'meta_description','meta_title','meta_keywords','meta_tags','status');
			 $crud->unset_edit_fields( 'meta_title','meta_description','meta_keywords','meta_tags','status');
			$crud->columns('title', 'url');

			$output = $crud->render();

			//$this->_example_output($output);
			$data["module"] = "cms";
			$data["view_file"] = "cms";
			$output->title = "Achivements/Quality/Services Pages";
			$data["crud"] = $output;
			echo Modules::run("template/admin", $data);
			

		}catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
	}
	public function schools_price()
	{
		try{
			$crud = new grocery_CRUD(); 
			//$crud->set_theme('datatables');
			//$crud->unset_jquery();
			$crud->set_table('school_prices');
			$crud->set_subject('School Prices'); 
			$crud->columns(array('school_id','per_month_price','year'));
			$crud->required_fields('per_month_price','year','status');
			
			
			$crud->set_relation('school_id','schools','name');
			
			 
			$output = $crud->render();

			//$this->_example_output($output);
			$data["module"] = "cms";
			$data["view_file"] = "cms";
			$output->title = "School Prices";
			$data["crud"] = $output;
			echo Modules::run("template/admin", $data);
			

		}catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
	}
	
	public function schools()
	{
		try{
			$crud = new grocery_CRUD(); 
			//$crud->set_theme('datatables');
			//$crud->unset_jquery();
			$crud->set_table('schools');
			$crud->set_subject('School'); 
			$crud->columns(array('name','district_id','address','mandel','village','strength','monthly_amount'));
			$crud->required_fields('name','district_id','address', 'village','strength','monthly_amount');
			
			
			$crud->set_relation('district_id','districts','name');
			
			
			$crud->add_action('Manage users', site_url().'assets/users.png', 'cms/manage/users');
			
			
			 
			 $crud->unset_texteditor('address','meta_description','meta_keywords','meta_tags','status'); 
			 $crud->unset_add_fields( 'meta_description','meta_title','meta_keywords','meta_tags','status');
			 $crud->unset_edit_fields( 'meta_title','meta_description','meta_keywords','meta_tags','status'); 

			$output = $crud->render();

			//$this->_example_output($output);
			$data["module"] = "cms";
			$data["view_file"] = "cms";
			$output->title = "Schools information";
			$data["crud"] = $output;
			echo Modules::run("template/admin", $data);
			

		}catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
	}
	function users($school_id=0)
	{
		$rs = $this->db->query("select * from  schools where school_id='$school_id'");
		$data = $rs->row();
		 
		 
		  $schol_name = $data->name."--".$data->mandel."--".$data->village;
		 
		
	try{
			$crud = new grocery_CRUD(); 
			$crud->set_theme('flexigrid');
			$crud->set_table('users');
			$crud->where('school_id',$school_id);
			$crud->set_subject('Users'); 
			$crud->columns(array('school_code','name','email','password','contact_no'));
			$crud->add_fields(array('school_code','name','email','password','contact_no','school_id'));
			$crud->edit_fields(array('school_code','name','email','password','contact_no','school_id'));
			
			$crud->change_field_type('school_id', 'hidden',$school_id);
			$crud->unique_fields('school_code');
			$crud->callback_before_insert(array($this,'encrypt_password_callback'));
			$crud->callback_before_update(array($this,'encrypt_password_updatecallback'));
			
			$crud->required_fields(array('name','email','password','contact_no'));
			
			$output = $crud->render();
			$data = array();
			$data["module"] = "cms";
			$data["view_file"] = "cms";
			$output->title = $schol_name. " - Manage users";
			$data["crud"] = $output;
			echo Modules::run("template/admin", $data);
			

		}catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
	}
	//encrypt_password_updatecallback
	function encrypt_password_updatecallback($post_array, $primary_key = null)
	{
		$rsp = $this->db->query("select * from users where email='".$post_array['email']."' and password='".$post_array['password']."'");
		$num = $rsp->num_rows();
		if($num ==0){
			$post_array['password'] = md5($post_array['password']);
		}
		return $post_array;
	}
	
	function encrypt_password_callback($post_array, $primary_key = null)
	{
		$post_array['password'] = md5($post_array['password']);
		return $post_array;
	}
	public function settings()
	{
		try{
			$crud = new grocery_CRUD();

			$crud->set_theme('flexigrid');
			 //$crud->set_theme('datatables');
			$crud->set_table('site_values');
			$crud->set_subject('Settings');
			$crud->unique_fields('name');
			$crud->unset_add();
            $crud->unset_delete();
			$crud->columns(array('name','varible_value'));
			$crud->display_as('varible_value','Value');
			 
			 
			// $crud->unset_add_fields( 'name','value');
			$crud->unset_edit_fields( 'code');
			$crud->columns('name', 'varible_value');

			$output = $crud->render();

			//$this->_example_output($output);
			$data["module"] = "cms";
			$data["view_file"] = "cms";
			$output->title = "Settings";
			$data["crud"] = $output;
			echo Modules::run("template/admin", $data);
			

		}catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
	}
	public function  videoalbums() 
	{
		try{
			$crud = new grocery_CRUD();

			$crud->set_theme('flexigrid');
			 //$crud->set_theme('datatables');
			$crud->set_table('video_albums');
			$crud->set_subject('Video albums');
			 $crud->unset_texteditor('description','sms_text'); 
			$crud->columns('title');
			 $crud->required_fields('title'); 
 
			$this->config->set_item('grocery_crud_file_upload_allow_file_types', 'gif|jpeg|jpg|png');			
			$output = $crud->render();

			//$this->_example_output($output);
			$data["module"] = "cms";
			$data["view_file"] = "cms";
			$output->title = "Manage Video albums";
			$data["crud"] = $output;
			echo Modules::run("template/admin", $data);
			

		}catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
	}
	public function  videos() 
	{
		try{
			$crud = new grocery_CRUD();

			$crud->set_theme('flexigrid'); 
			$crud->set_table('videos');
			$crud->set_subject('Videos');
			$crud->set_relation('va_id','video_albums','title');
			$crud->columns('title','youtube_url');
			$crud->required_fields('title','youtube_url','va_id');
			
			$crud->display_as('va_id','Album');
			
 
			$this->config->set_item('grocery_crud_file_upload_allow_file_types', 'gif|jpeg|jpg|png');			
			$output = $crud->render();

			//$this->_example_output($output);
			$data["module"] = "cms";
			$data["view_file"] = "cms";
			$output->title = "Manage Videos";
			$data["crud"] = $output;
			echo Modules::run("template/admin", $data);
			

		}catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
	}
	
	public function  sliders() 
	{
		try{
			$crud = new grocery_CRUD();

			$crud->set_theme('flexigrid');
			 //$crud->set_theme('datatables');
			$crud->set_table('sliders');
			$crud->set_subject('sliders');
			 $crud->unset_texteditor('description','sms_text'); 
			$crud->columns('title','url','image','description');
			 $crud->required_fields('title','url','image');
			$crud->set_field_upload('image','assets/uploads/files');
 
			$this->config->set_item('grocery_crud_file_upload_allow_file_types', 'gif|jpeg|jpg|png');			
			$output = $crud->render();

			//$this->_example_output($output);
			$data["module"] = "cms";
			$data["view_file"] = "cms";
			$output->title = "Manage sliders";
			$data["crud"] = $output;
			echo Modules::run("template/admin", $data);
			

		}catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
	}
	public function  tariffs()
	{
		try{
			$crud = new grocery_CRUD();

			$crud->set_theme('flexigrid');
			 //$crud->set_theme('datatables');
			$crud->set_table('tariffs');
			$crud->set_subject('Tariffs');
		 
			$crud->required_fields( 'title' );
			 $crud->unset_add_fields('id');
			 $crud->unset_edit_fields('id');
		 
			$output = $crud->render();

			//$this->_example_output($output);
			$data["module"] = "cms";
			$data["view_file"] = "cms";
			$output->title = "Manage Tariffs";
			$data["crud"] = $output;
			echo Modules::run("template/admin", $data);
			

		}catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
	}
	public function  scroller()
	{
		try{
			$crud = new grocery_CRUD();

			$crud->set_theme('flexigrid');
			 //$crud->set_theme('datatables');
			$crud->set_table('scroller');
			$crud->set_subject('Scroller Images');
			 
			$crud->set_field_upload('image','assets/uploads/files');
			$crud->required_fields( 'title','image');
			
			$this->config->set_item('grocery_crud_file_upload_allow_file_types', 'gif|jpeg|jpg|png');			
			$output = $crud->render();

			//$this->_example_output($output);
			$data["module"] = "cms";
			$data["view_file"] = "cms";
			$output->title = "Manage Scroller Images";
			$data["crud"] = $output;
			echo Modules::run("template/admin", $data);
			

		}catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
	}
	//albums
	public function  albums()
	{
		try{
			$crud = new grocery_CRUD();

			$crud->set_theme('flexigrid');
			 //$crud->set_theme('datatables');
			$crud->set_table('albums');
			$crud->set_subject('Product Albums');
			
			
			$crud->unset_add_fields( 'category','album_desc','status');
			 $crud->unset_edit_fields( 'category','album_desc','status');
			 
			$crud->columns('category','album_title','status');
			$crud->required_fields('category','album_title','status');
			// $crud->unique_fields('album_title');
			$crud->add_action('Gallery', site_url().'assets/gallery.png', 'gallery/manage/projectgallery'); 
			
			$output = $crud->render();

			//$this->_example_output($output);
			$data["module"] = "cms";
			$data["view_file"] = "cms";
			$output->title = "Manage Product Albums";
			$data["crud"] = $output;
			echo Modules::run("template/admin", $data);
			

		}catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
	}
	//albums
	public function  galleries()
	{
		try{
			$crud = new grocery_CRUD();

			$crud->set_relation('album_id','photogallery_albums','album_title');			
			 
			
			$crud->set_theme('flexigrid');
			$crud->set_table('photogallery_photos');
			$crud->set_subject('Gallery Photos');
			 
			$crud->columns('album_id','title','image','status');
			$crud->required_fields('album_id','title','image','status');
			$crud->set_field_upload('image','assets/uploads/files');
			
			$output = $crud->render();

			//$this->_example_output($output);
			$data["module"] = "cms";
			$data["view_file"] = "cms";
			$output->title = "Manage Gallery Photos";
			$data["crud"] = $output;
			echo Modules::run("template/admin", $data);
			

		}catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
	}
	public function  projects()
	{
		try{
			$crud = new grocery_CRUD(); 
			$crud->set_table('realestate_projects');
			$crud->set_subject('Projects');
			 
			$crud->columns('title', 'project_pic', 'video_link','project_type');
			$crud->required_fields('title','location_highlights','local_advantages','fine_features','project_pic',  'project_type');
			$crud->set_field_upload('project_pic','assets/uploads');
			$crud->set_field_upload('pdf_link','assets/uploads/files');
			
			 
			
			$crud->add_action('Gallery', site_url().'assets/gallery.png', 'gallery/manage/projectgallery'); 
			$output = $crud->render();

			//$this->_example_output($output);
			$data["module"] = "cms";
			$data["view_file"] = "cms";
			$output->title = "Manage Projects";
			$data["crud"] = $output;
			echo Modules::run("template/admin", $data);
			

		}catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
	}
	function showImage($value) {  
		$image_url = site_url('assets/uploads/'.$value);
			return "<img src='".$image_url ."' width=100>";
	}
	
	  function updateattendence() {
		 $school_id = 0;
		 $school_code = '';
		 
		$data['frame_load'] = false;
		
		if($this->input->post('school_code')!="")
		{
			 
			$school_code = $this->input->post('school_code'); 
			$attendence_date = $this->input->post('attendence_date'); 
			
			$attendence_split = explode("/",	$attendence_date);
			
			//m-d-Y
			
			$year = $attendence_split[2]; 
			$month = $attendence_split[0]; 
			$day = $attendence_split[1]; 
			
			$choosen_date = $month."-".$day."-".$year;			
			
			
			
			if($school_code=="all")
			{
					$url = site_url()."attendence/setattendence/all/?date=".$choosen_date;
			}
			else
			{
					$url = site_url()."attendence/setattendence/single/".$school_code."/?date=".$choosen_date;
			}
			  $data['frame_url'] = $url;
			$data['frame_load'] = true;
		}
		 
		 $data["module"] = "cms";
        $data["view_file"] = "admin_school_attendence";
        echo Modules::run("template/admin", $data);
		
	  }
	  
	   function schoolattendence() {
		 $school_id = 0;
		 $school_code = '';
		 
		$today_allowed_Amount = '0.00';
		$today_consumed_Amount = '0.00';
		$today_remaining_Amount = '0.00';
		$data['result_flag']			  =  0;
		if($this->input->post('school_code')!="")
		 {
			  $school_code = $this->input->post('school_code');
			  
			 
			 $srs = $this->db->query("select * from users where school_code='$school_code'") ;
			  $school_data = $srs->row();
		
			 $school_id = $school_data->school_id;		
			 redirect('cms/manage/attendencelist/'.$school_id );
		 }
		 
		 $data["module"] = "cms";
        $data["view_file"] = "school_attendence";
        echo Modules::run("template/admin", $data);
		
	  }
	  	  function attendencelist($school_id ){
		
		try{
			$crud = new grocery_CRUD($this);
			 
			$crud->set_theme('flexigrid'); 
			$crud->set_table('school_attendence');
			$crud->where('school_id',$school_id );
			$crud->order_by('entry_date','desc');
			$crud->set_subject('Attendance');
			
			 
			$crud->unset_add(); 
			$crud->unset_edit(); 
			$crud->unset_delete();
			
			$crud->columns(array('entry_date','present_count','cat1_attendence','cat1_guest_attendence','cat2_attendence','cat2_guest_attendence','cat3_attendence','cat3_guest_attendence'));
			
			$crud->display_as('cat1_guest_attendence','Category 1 Guest Attendance')
				->display_as('cat2_guest_attendence','Category 2 Guest Attendance')
				->display_as('cat3_guest_attendence','Category 3 Guest Attendance');
			$crud->display_as('cat1_attendence','Category 1 Attendance')
				->display_as('cat2_attendence','Category 2  Attendance')
				->display_as('cat3_attendence','Category 3 Attendance');
			$crud->display_as('present_count','Total');
			
			 
			 
			//$crud->field_type('entry_date', 'readonly');
			// $crud->field_type('cat1_attendence', 'readonly' );
			// $crud->field_type('cat2_attendence', 'readonly' );
			// $crud->field_type('cat3_attendence', 'readonly' );
			 

			  $srs = $this->db->query("select * from schools  where school_id='$school_id'") ;
			  $school_data = $srs->row();
		
			 $school_name = $school_data->name;		
			 
			 
			 
			$output = $crud->render(); 
			$data["module"] = "cms";
			$data["extra_content"] = "";
			$data["view_file"] = "cms";
			$output->title = "Attendance entries of  $school_name ";
			$data["crud"] = $output;
			echo Modules::run("template/admin", $data);
			

		}catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
	}
	
	 	  function updateguestattendence() {
		 $school_id = 0;
		 $school_code = '';
		 
		$data['frame_load'] = false;
		
		if($this->input->post('attendence_date')!="")
		{
			 
		 
			$attendence_date = $this->input->post('attendence_date'); 
			
			$attendence_split = explode("/",	$attendence_date);
			
			//m-d-Y
			
			$year = $attendence_split[2]; 
			$month = $attendence_split[0]; 
			$day = $attendence_split[1]; 
			
			$choosen_date =$year. "-". $month."-".$day;//YMD Format
			
			$url = site_url()."guestattendence/?date=".$choosen_date;
			
			$data['frame_url'] = $url;
			$data['frame_load'] = true;
		}
		 
		 $data["module"] = "cms";
        $data["view_file"] = "adminguest_school_attendence";
        echo Modules::run("template/admin", $data);
		
	  } 
	  
	  
	 	  function updatefuelcharges() {
		 $school_id = 0;
		 $school_code = '';
		 
		$data['frame_load'] = false;
		
		if($this->input->post('attendence_date')!="")
		{
			 
		 
			$attendence_date = $this->input->post('attendence_date'); 
			
			$attendence_split = explode("/",	$attendence_date);
			
			//m-d-Y
			
			$year = $attendence_split[2]; 
			$month = $attendence_split[0]; 
			$day = $attendence_split[1]; 
			
			$choosen_date =$year. "-". $month."-".$day;//YMD Format
			
			$url = site_url()."fixeditems/fuelcharges/?date=".$choosen_date;
			
			$data['frame_url'] = $url;
			$data['frame_load'] = true;
		}
		 
		 $data["module"] = "cms";
        $data["view_file"] = "fuelcharges";
        echo Modules::run("template/admin", $data);
		
	  } 
	  
	  function resetpassword() {
       //    Modules::run("security/is_admin");
         $this->load->model('admin_model');
         if ($this->input->post("action")=="updatepassword") {
				$data= $this->admin_model->reset_password();
        }
        $data["module"] = "admin";
        $data["view_file"] = "reset_password";
        echo  Modules::run("template/admin", $data);
    }
 
	  
	
	 
}