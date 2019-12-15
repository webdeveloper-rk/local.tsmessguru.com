<?php 
 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
set_time_limit(0);
 date_default_timezone_set('Asia/Kolkata');
class Attendence extends MX_Controller {

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
	 
	}

    function index() {
            $school_id = $this->session->userdata("school_id");
			$uri_seg = $this->uri->uri_to_assoc();
			if(isset($uri_seg['edit']))//check whethere user is updating self school record or not , if not redirect to attendence page.
			{  
				$edit_attendence_id = $uri_seg['edit'];
				$rs = $this->db->query("select entry_date as cal_date from school_attendence where school_id=? and attendence_id=?",array($school_id,$edit_attendence_id) );
				if($rs->num_rows()==0)
				{
					redirect("attendence");
				} 
			}
			   
			
			$this->db->query("insert into school_attendence(school_id,entry_date) select ? as school_id,cal_date as entry_date from calender where cal_date between '2018-12-01' and CURRENT_DATE and cal_date not in (select entry_date as cal_date from school_attendence where school_id=?)",array($school_id,$school_id));
			 
					try{
						$crud = new grocery_CRUD($this);

						$crud->set_theme('flexigrid'); 
						$crud->set_table('school_attendence');
						$crud->where('school_id',$this->session->userdata("school_id"));
						$crud->order_by('entry_date','desc');
						$crud->set_subject('Attendence');
						
						 $crud->callback_column('entry_date',array($this,'date_formatdisplay'));
						 $crud->callback_edit_field('entry_date',array($this,'date_formatdisplay'));
						
						
						
						$check_editable = $this->is_editable(); 
						if($check_editable['edit_flag']  == false)
						{
							$crud->unset_edit(); 
						}
						else
						{
							$edit_cols = array('entry_date');
							$edit_cols =array_merge($edit_cols,$check_editable['cols']);
							$validate_cols =$check_editable['cols'];
							$crud->callback_after_update(array($this, 'update_attendence_total_count')); 
						}
						 
						$crud->unset_add(); 
						$crud->unset_read();
						$crud->unset_delete();
						$crud->columns(array('entry_date','present_count'));
						$crud->edit_fields($edit_cols);
						$crud->required_fields($validate_cols);
						 
						$crud->field_type('entry_date', 'readonly');
						
						 //$crud->callback_view_field('entry_date',array($this,'date_formatdisplay'));
						
						$crud->columns(array('entry_date','present_count','cat1_attendence','cat1_guest_attendence','cat2_attendence','cat2_guest_attendence','cat3_attendence','cat3_guest_attendence'));
						
						$crud->display_as('cat1_guest_attendence','Category 1 Guest Attendance')
							->display_as('cat2_guest_attendence','Category 2 Guest Attendance')
							->display_as('cat3_guest_attendence','Category 3 Guest Attendance');
						$crud->display_as('cat1_attendence','Category 1 Attendance')
							->display_as('cat2_attendence','Category 2  Attendance')
							->display_as('cat3_attendence','Category 3 Attendance');
						$crud->display_as('present_count','Total Attendance');
						 
						 
						$school_name = $this->db->query("select concat(school_code,'-',name) as name from schools where school_id=?",array($this->session->userdata("school_id")))->row()->name;
						$output = $crud->render();
						$data["module"] = "cms";
						$data["extra_content"] = "";
						$data["extra_content"] = "<p>Category 1 : up to 7th class<br>Category 2 : 8,9,10th classes <br>Category 3 : Intermediate and above</p>";
						$data["view_file"] = "cms";
						$output->title = $school_name ." - Attendance entries";
						$data["crud"] = $output;
						echo Modules::run("template/admin", $data);
						

					}catch(Exception $e){
						show_error($e->getMessage().' --- '.$e->getTraceAsString());
					}
				 
	
    }
 
	public function update_attendence_total_count($post_array,$primary_key)
	{
			 
			 $update_attendence_sql= "update  school_attendence set present_count=(cat1_attendence+cat2_attendence+cat3_attendence+cat1_guest_attendence	+cat2_guest_attendence	+cat3_guest_attendence	) where attendence_id=?";
			 $this->db->query( $update_attendence_sql,array($primary_key)); 
			 return true;
	}
	public function date_formatdisplay($value, $row)
	{
		return "<h4 style='color:#0000FF;font-weight:bold;'>".date('d-M-Y',strtotime($value))."</h4>";
	}
	private function is_editable()
	{
		$edit_cols = array(); 
		$allowed_to_edit   = false;
		switch($this->config->item("site_name"))
		{
				case 'twhostels':
								$edit_cols = array('cat1_attendence','cat2_attendence','cat3_attendence','cat1_guest_attendence','cat2_guest_attendence','cat3_guest_attendence'); 
								$allowed_to_edit = true; 
				break;

				default :
								$allowed_to_edit = false;
				break;
		}
		return array('edit_flag'=>$allowed_to_edit,'cols'=>$edit_cols);

	}
}
