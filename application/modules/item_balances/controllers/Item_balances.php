<?php 
 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
set_time_limit(0);
 date_default_timezone_set('Asia/Kolkata');
class Item_balances extends MX_Controller {

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
		$this->load->helper('url');  
		$this->load->config("config.php");  
		$this->load->library("ci_jwt");  
	}
	function index()
	{
		 $this->form_validation->set_rules('items_id', 'Item', 'required|numeric|greater_than[0]');  
		 
		 if($this->form_validation->run() == true  )
		 {
			$items_id = intval($this->input->post('items_id')); 
			 redirect('item_balances/viewlist/'.$items_id );
		 }
		 
		$items_sql = "select * from items ";
		$items_rs =  $this->db->query($items_sql);
		 $data["items"] = $items_rs;
		 $data["module"] = "item_balances";
        $data["view_file"] = "iteml_balances_form";
        echo Modules::run("template/admin", $data);
	}
		function viewlist($item_id=0)
		{
			$item_id= intval($item_id);
			if($item_id==0)
					redirect("item_balances");
			  $schools_sql  = "select * from schools ";
			  $schools_rs =  $this->db->query($schools_sql);
			  $school_names  = array();
			  $district_ids = array();
			  $school_codes = array();
			  foreach($schools_rs->result() as $school_data)
			  {
				   $school_names[ $school_data->school_id] =  $school_data->name;
				   $school_codes[ $school_data->school_id] =  $school_data->school_code;
				   $district_ids[ $school_data->school_id] =  $school_data->district_id;
			  }
			  
			 // print_a($district_ids,1);
			 
			  $districts_sql  = "select * from districts ";
			  $districts_rs =  $this->db->query($districts_sql);
			  
			  $district_names = array(); 
			  foreach($districts_rs->result() as $districts_data)
			  {
				  
				   $district_names[ $districts_data->district_id] =  $districts_data->name;
			  }
			 // print_a($district_names,1);
			  
			  $max_sql = "select max(entry_id) as entry_id from balance_sheet  	where  item_id=? group by school_id  ";
			  $max_rs =  $this->db->query($max_sql,array($item_id));
			  $max_item_ids = array(0);
			  foreach($max_rs->result() as $maxdata)
			  {
				   $max_item_ids[] = $maxdata->entry_id;
			  }
			  $max_ids_implode = implode(",",$max_item_ids);
			  
			   $sql  = "select  school_id,item_id, closing_quantity    FROM balance_sheet WHERE  entry_id in ($max_ids_implode )  order by closing_quantity desc ";

			$rs  = $this->db->query($sql);
			
			$bs_data_rows = array();
			  foreach($rs->result() as $bs_data)
			  {
				   $bs_data_rows[  $school_codes[ $bs_data->school_id]  ] =  $bs_data;
				   
			  }
			 // print_a($bs_data_rows);
			
			$data["district_ids"] = $district_ids;
			$data["district_names"] = $district_names;
			$data["bs_data_rows"] = $bs_data_rows;
			$data["school_codes"] = $school_codes;
			$data["school_names"] = $school_names;
			$data["rset"] = $rs;
			 
			$data["item_info"] = $this->db->query("select * from items where item_id=?",array($item_id))->row();
			 
			 
			$data["module"] = "item_balances";
			$data["view_file"] = "school_balances";
			echo Modules::run("template/admin", $data);
		}
	  
   

	
}
