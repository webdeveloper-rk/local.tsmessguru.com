<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Opening_balance extends MX_Controller {

    function __construct() {
        parent::__construct(); 
         $this->load->config("config.php");
         $this->load->model("admin/school_model");
		 if($this->session->userdata("user_role") != "school")
		{
				redirect("admin/general/logout"); 
		}
    }

   

    function index() {

		
        $this->listitems();

    }
	function listitems()
	{
		$school_id = $this->session->userdata('school_id');
		/*$opening_balance_start_date = $this->config->item('opening_balance_start_date');
		$opening_balance_end_date = $this->config->item('opening_balance_end_date');*/
		
		
		$sql = "select *,date_format(opening_balance_date,'%d-%M-%Y') as display_ob_date , editable_end_date > CURRENT_DATE() as allowed_to_update from school_opening_balance bs inner join items it on bs.item_id= it.item_id and it.status='1' and bs.school_id=? and bs.status='1' ";
		$rs = $this->db->query($sql,array($school_id ));
		
		$data['rset'] = $rs;
		$data['open_date'] =  date('d-m-Y',strtotime($opening_balance_start_date));
		$data['allowed_to_update'] = $this->allowed_to_update(); 
		$data["module"] = "opening_balance"; 
        $data["view_file"] = "openingbalance"; 
        echo Modules::run("template/admin", $data);
	}


	function update($item_id='0'){

		$data['allowed_to_update'] =  $allowed_to_update = $this->allowed_to_update(); $this->allowed_to_update(); 
		$school_id = $this->session->userdata('school_id');
		$opening_balance_start_date = $this->config->item('opening_balance_start_date');
		$opening_balance_end_date = $this->config->item('opening_balance_end_date');
		 
		
		
		$bs_data= $this->db->query("select * from balance_sheet where school_id=? and entry_date=? and item_id=?",array($school_id,$opening_balance_start_date,$item_id))->row();
		$item_data= $this->db->query("select * from items where item_id=?",array($item_id))->row();  
		
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
					$update_sql = "update balance_sheet set opening_quantity=? , opening_price=?  where entry_id= ?";
					$this->db->query($update_sql,array($opening_quantity,$opening_price,$entry_id));
					$this->school_model->update_entries($school_id,$item_id,$opening_balance_start_date);
					
					//echo $update_sql;die;
					$this->session->set_flashdata('message', '<div class="alert alert-success">Succesfully updated</div>');
					redirect('opening_balance/');
					
				}
		}
		
		
		$data['bs_data'] =  $bs_data;
		$data['item_data'] =  $item_data;
		$data['open_date'] =  date('d-m-Y',strtotime($opening_balance_start_date));
		
        $data["module"] = "opening_balance";
        $data["view_file"] = "openingbalance_entryform";
        echo Modules::run("template/admin", $data);

    }
	private function allowed_to_update()
	{
		$school_id = $this->session->userdata('school_id');
		$opening_balance_start_date = $this->config->item('opening_balance_start_date');
		$opening_balance_end_date = $this->config->item('opening_balance_end_date'); 
		
		if($school_id==218)//30729 school code 
				$opening_balance_end_date ='2019-01-03';
		
		$sql = "SELECT CURRENT_DATE <= ? as allowed";
		$rs = $this->db->query($sql,array($opening_balance_end_date));
		$allowed = $rs->row()->allowed;
		if($allowed > 0 )
		{
			return true;
		}
		else {
			return false;
		}
	}



}

