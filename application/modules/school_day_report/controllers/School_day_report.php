<?php 
 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
set_time_limit(0);
 date_default_timezone_set('Asia/Kolkata');
class School_day_report extends MX_Controller {

    function __construct() {
        parent::__construct();
		if($this->uri->segment(2) !="login") { 
					 Modules::run("security/is_admin");		 
					
		}
		 if ($this->session->userdata("is_loggedin") != TRUE || $this->session->userdata("user_id") == "" ) {
							redirect("admin/login");
							die;
					}
					$allowed_roles = array('school','subadmin','dco','collector','report_viewer','secretary');		
					if(!in_array($this->session->userdata("user_role"),$allowed_roles))
					{
						redirect("admin/login");
							die;
					} 
		//check_permission('admin_dashboard');
		
		$this->load->helper('url');  
		$this->load->config("config.php");  
		$this->load->library("ci_jwt");  
	}
	function index()
	{
		redirect("school_day_report/view");
	}
   function view($date_encoded= null)
   {
	   
		$data["school_date"] 		= '';
		$data["formated_date"] 	='';
		$data["result_display"] 	= false;
				 
		 if($date_encoded==null)
		 {
			$date = date('Y-m-d');
		 }
		 else{
				injection_check();				 
				
				$inputs = $this->ci_jwt->jwt_web_decode($date_encoded);		
				//print_a($inputs );				
				$data["school_date"] 		=  $inputs->school_date ;
				$data["school_id"] 		=  $inputs->school_id ;
				$data["formated_date"] 	= $inputs->formated_date;
				$data["report_date"] 	= date('d-m-Y',strtotime($inputs->formated_date));
				$data["result_display"] 	= true;
				 
				 
				
				if($this->session->userdata("user_role")=="school")
				{
					$school_id = intval($this->session->userdata("school_id"));		
				}
				else
				{
					$school_id = $inputs->school_id ;	
				}

				
				$sql = "SELECT it.item_name,it.telugu_name,bs.*,
									(
										(session_1_qty*session_1_price) +
										(session_2_qty*session_2_price)+ 
										(session_3_qty*session_3_price) + 
										(session_4_qty*session_4_price)
									) as today_consumed,
									session_1_price,
									session_2_price,
									session_3_price,
									session_4_price

									FROM `balance_sheet` bs inner join items  it on bs.item_id=it.item_id WHERE `school_id`=? and 
									`entry_date`=? order by closing_quantity desc";
				$rset  = $this->db->query($sql,array($school_id,$inputs->formated_date));
				//echo $this->db->last_query();
				$data["rset"] 	= $rset;
				$data["school_name"] 	= $this->db->query("select concat(school_code,'-',name) as name from schools where school_id=?",array($school_id))->row()->name;
		
		
		 }
		 
		 
		$this->form_validation->set_rules('school_date', 'Date ', 'required');      
			if($this->session->userdata("user_role")!="school")
			{ 
				$this->form_validation->set_rules('school_id', 'School ', 'required|numeric');    
			}		
		  
		if($this->form_validation->run() == true )
		{
			if(!chk_date_format($this->input->post('school_date')))
			{
				$this->session->set_flashdata('message', '<div class="alert alert-danger">Invalid Date format. ex: mm/dd/YYYY</div>');
				redirect('school_day_report/view');
			}
			else
			{
				if($this->session->userdata("user_role")=="school")
				{
					$school_id = intval($this->session->userdata("school_id"));		
				}
				else
				{
					$school_id =   intval($this->input->post('school_id'));
				}
				
				$posted_date = $this->input->post('school_date');
				
				$formated_date = date('Y-m-d',strtotime($posted_date));
				$inputs = array('school_date'=>$posted_date,'formated_date'=>$formated_date,'school_id'=>$school_id);
				$encoded_input = $this->ci_jwt->jwt_web_encode($inputs);
				redirect("school_day_report/view/".$encoded_input);				
			}
		}
		 
		 
       
         
        $data["module"] = "school_day_report";
        $data["view_file"] = "report_form";
        echo Modules::run("template/admin", $data);
   }
   
   function display_menu()
   {
	   $rs = $this->db->query("select * from circulars_mailbox where school_id=? and status='0'  ",array($this->session->userdata("school_id")));
		 if($rs->num_rows()>0)
		 {
			redirect("school_day_report/display_circulars");
		 }
		 
		$data["module"] = "school_day_report";
        $data["view_file"] = "menu_pic";
        echo Modules::run("template/admin", $data);
   }
	function display_circulars()
   {
	   $rs = $this->db->query("select c.circular_id,c.title,c.section,cm.mailbox_id,date_format(cm.circular_time,'%d-%M-%Y %h:%i:%s %p') as datetime,cm.status from circulars_mailbox cm inner join circulars c on c.circular_id= cm.circular_id  where cm.school_id=?  order by cm.mailbox_id desc  ",array($this->session->userdata("school_id")));
		$data['rs'] = $rs;
		$data["module"] = "school_day_report";
        $data["view_file"] = "circulars_list";
        echo Modules::run("template/admin", $data);
   }
   function read_circular($circular_id)
   {
	   $this->db->query("update  circulars_mailbox  set status='1'   where  school_id=?  and  circular_id=?",array($this->session->userdata("school_id"),$circular_id));
	   $rs = $this->db->query("select c.title,c.document_path,c.section,cm.mailbox_id,date_format(cm.circular_time,'%d-%M-%Y %h:%i:%s %p') as datetime,cm.status from circulars_mailbox cm inner join circulars c on c.circular_id= cm.circular_id  where cm.school_id=?  and cm.circular_id=? order by cm.mailbox_id desc  ",array($this->session->userdata("school_id"),$circular_id));
	   
		$data['row'] = $rs->row();
		$data["module"] = "school_day_report";
        $data["view_file"] = "circular_view";
        echo Modules::run("template/admin", $data);
   }
	

	
}
