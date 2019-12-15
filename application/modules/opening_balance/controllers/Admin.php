<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Admin extends MX_Controller {

    function __construct() {
        parent::__construct(); 
         $this->load->config("config.php");
         $this->load->model("admin/school_model");
		 if($this->session->userdata("user_role") != "subadmin")
		{
				//redirect("admin/general/logout"); 
		}
    }

   

    function index() {

		
        $this->listschools();

    }
	function listschools()
	{
		$school_id = $this->session->userdata('school_id');
		$opening_balance_start_date = $this->config->item('opening_balance_start_date');
		$opening_balance_end_date = $this->config->item('opening_balance_end_date');
		
		$sql = "select * from schools order by school_code and is_school='1'";
		$rs = $this->db->query($sql);
		
		$data['rset'] = $rs;
		$data['open_date'] =  date('d-m-Y',strtotime($opening_balance_start_date));
		$data['allowed_to_update'] = $this->allowed_to_update(); 
		$data["module"] = "opening_balance"; 
        $data["view_file"] = "listschools"; 
        echo Modules::run("template/admin", $data);
	}
	function listitems($school_id='0')
	{
		
		 
		$opening_balance_start_date = $this->config->item('opening_balance_start_date');
		$opening_balance_end_date = $this->config->item('opening_balance_end_date');
		
		$sql = "select * from balance_sheet bs inner join items it on bs.item_id= it.item_id and it.status='1' and bs.school_id='$school_id' where entry_date='".$opening_balance_start_date."'";
		$rs = $this->db->query($sql);
		
		$data['school_id'] = $school_id;
		$data['school_name'] =$this->db->query("select concat(school_code,' - ',name) as name from schools where school_id='$school_id'")->row()->name;
		$data['rset'] = $rs;
		$data['open_date'] =  date('d-m-Y',strtotime($opening_balance_start_date));
		$data['allowed_to_update'] = $this->allowed_to_update(); 
		$data["module"] = "opening_balance"; 
        $data["view_file"] = "openingbalance_admin"; 
        echo Modules::run("template/admin", $data);
	}


	function update($school_id,$item_id='0'){

		$data['allowed_to_update'] =  $allowed_to_update = $this->allowed_to_update(); $this->allowed_to_update(); 
		 
		$opening_balance_start_date = $this->config->item('opening_balance_start_date');
		$opening_balance_end_date = $this->config->item('opening_balance_end_date');
		$item_id = $item_id;
		
		
		$bs_data= $this->db->query("select * from balance_sheet where school_id='$school_id' and entry_date='$opening_balance_start_date' and item_id='$item_id'")->row();
		$item_data= $this->db->query("select * from items where item_id='$item_id'")->row();  
		
		if($allowed_to_update==true)
		{
				$this->form_validation->set_rules('quantity', 'Quantity', 'required|numeric');
                $this->form_validation->set_rules('price', 'Price', 'required|numeric');

                if ($this->form_validation->run() == FALSE)
                {
                        //nothing to do 
                }
				else {
					//update entries 
					$opening_quantity = $this->input->post('quantity');
					$opening_price = $this->input->post('price');
					$entry_id = $bs_data->entry_id;
					$update_sql = "update balance_sheet set opening_quantity='$opening_quantity' , opening_price='$opening_price'  where entry_id='$entry_id'";
					$this->db->query($update_sql);
					$this->school_model->update_entries($school_id,$item_id,$opening_balance_start_date);
					
					//echo $update_sql;die;
					$this->session->set_flashdata('message', '<div class="alert alert-success">Succesfully updated</div>');
					redirect('opening_balance/opening_balance_admin/listitems/'.$school_id);
					
				}
		}
		
		
		$data['bs_data'] =  $bs_data;
		$data['item_data'] =  $item_data;
		$data['open_date'] =  date('d-m-Y',strtotime($opening_balance_start_date));
		$data['school_name'] =$this->db->query("select concat(school_code,' - ',name) as name from schools where school_id='$school_id'")->row()->name;
		
        $data["module"] = "opening_balance";
        $data["view_file"] = "openingbalance_entryform_admin";
        echo Modules::run("template/admin", $data);

    }
	private function allowed_to_update()
	{
		return true;
	}



}

