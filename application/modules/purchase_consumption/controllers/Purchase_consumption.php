<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
set_time_limit(0); 
 date_default_timezone_set('Asia/Kolkata');
class Purchase_consumption extends MX_Controller {

    function __construct() {
        parent::__construct();
		if($this->uri->segment(2) !="login") { 
					 Modules::run("security/is_admin");		 
					
		}
			$this->load->helper('url');  
			$this->load->config("config.php");
			$this->load->library("ci_jwt");
			$this->load->model("common/common_model");
			//print_a($this->session->all_userdata(),1);
			if ($this->session->userdata("is_loggedin") != TRUE || $this->session->userdata("user_id") == "" ) {
				redirect("admin/login");
				die;
			}
		
			if($this->session->userdata("user_role") != "subadmin")
			{
				redirect("admin/login");
				die;
			}
			/*if($this->session->userdata("school_code") != "10100")
			{
				redirect("admin/login");
				die;
			}*/
}

   	public function index(){
		
		 $this->form_validation->set_rules('school_id', 'School', 'required|numeric|greater_than[0]');              
		$this->form_validation->set_rules('item_id', 'Item', 'required|numeric|greater_than[0]'); 
		$this->form_validation->set_rules('todate', 'Date', 'required');  
		 
		if($this->form_validation->run() == true )
		{
			  $todate = date('Y-m-d',strtotime($this->input->post('todate')));
			  $school_id = intval($this->input->post('school_id'));
			  $item_id = intval($this->input->post('item_id'));
			  $dataarray = array('school_id'=>$school_id,'item_id'=>$item_id,'date'=>$todate);
			  
			  //check record exists for particular date if not redirect to form 
			  $sql = "select * from balance_sheet where school_id=? and item_id=? and entry_date=?";
			  $rs = $this->db->query($sql,array($school_id,$item_id,$todate));
			   
			  if($rs->num_rows()==0)
			  {
					$this->createNonExistRecord($school_id,$item_id, $todate);
					$encoded_data  = $this->ci_jwt->jwt_web_encode($dataarray );
					redirect('purchase_consumption/update/'.$encoded_data );
			  } 
			  $encoded_data  = $this->ci_jwt->jwt_web_encode($dataarray );
			  redirect('purchase_consumption/update/'.$encoded_data );
			  die;
			  
		} 
		$drs = $this->db->query("SELECT s.*,d.name as dname,s.name as sname FROM  schools  s inner join districts d on s.`district_id`=d.district_id");         
        $data["schools"] = $drs; 
		$itemsrs = $this->db->query("SELECT * FROM  items where  status='1'");         
        $data["items"] = $itemsrs; 
        $data["module"] = "purchase_consumption";        
        $data["view_file"] = "purchasebills_selection_form";
        echo Modules::run("template/admin", $data);
	 }

	function update($encoded_data=null)
	{
		


		$decoded_data  = $this->ci_jwt->jwt_web_decode($encoded_data );
		 $school_id = $decoded_data->school_id;
		 $item_id= $decoded_data->item_id;
		 $date= $decoded_data->date;
		 
		  //check record exists for particular date if not redirect to form 
			  $sql = "select * from balance_sheet where school_id=? and item_id=? and entry_date=?";
			  $rs = $this->db->query($sql,array($school_id,$item_id,$date));
			  if($rs->num_rows()==0)
			  {
					$this->session->set_flashdata('message', '<div class="alert alert-danger">Record not Found to update. please choose other dates.</div>');
					redirect("purchase_consumption");
			  }
			  $qp_data = $rs->row();
			$entry_id = $qp_data->entry_id;
		 
		 
		 
		 $this->form_validation->set_rules('pqty', 'Purchase Quantity', 'required|numeric');              
		$this->form_validation->set_rules('pprice', 'Purchase Price', 'required|numeric'); 
		
		 
		$this->form_validation->set_rules('bf_qty', 'Breakfast Quantity', 'required|numeric');  
		$this->form_validation->set_rules('bf_price', 'Breakfast Price', 'required|numeric');  
		
		$this->form_validation->set_rules('lu_qty', 'Lunch Quantity', 'required|numeric');  
		$this->form_validation->set_rules('lu_price', 'Lunch Price', 'required|numeric');  
		
		$this->form_validation->set_rules('sn_qty', 'Snacks Quantity', 'required|numeric');  
		$this->form_validation->set_rules('sn_price', 'Snacks Price', 'required|numeric');  
		
		$this->form_validation->set_rules('di_qty', 'Dinner Quantity', 'required|numeric');  
		$this->form_validation->set_rules('di_price', 'Dinner Price', 'required|numeric');  
		 
		if($this->form_validation->run() == true )
		{
				$inputs_array['school_id'] =  $school_id;
				$inputs_array['item_id'] =   $item_id;
				$inputs_array['entry_date'] = $date;

				$inputs_array['pqty'] = $this->input->post('pqty');
				$inputs_array['pprice'] = $this->input->post('pprice');

				$inputs_array['bf_qty'] = $this->input->post('bf_qty');
				$inputs_array['bf_price'] = $this->input->post('bf_price');

				$inputs_array['lu_qty'] = $this->input->post('lu_qty');
				$inputs_array['lu_price'] = $this->input->post('lu_price');


				$inputs_array['sn_qty'] = $this->input->post('sn_qty');
				$inputs_array['sn_price'] = $this->input->post('sn_price');

				$inputs_array['di_qty'] = $this->input->post('di_qty');
				$inputs_array['di_price'] = $this->input->post('di_price');
		
		
			 
		  $updatable_entries= $this->common_model->get_updatable_entries($inputs_array);
		  
		  if($updatable_entries['negative_reached'] == true)
		  {
				send_json_result([
                'success' =>  FALSE ,
                'message' => '<div class="alert alert-danger">Updation failed as closing balance reaching negative value on '.$updatable_entries['negative_date'].". please check the below transactions table</div>",
				'html_table'=>$this->generate_html_table($updatable_entries['entries_list'])
            ] );  
		  }
		  
		//  print_a($updatable_entries,1);
		  
		 $update_data = array( 'purchase_quantity'=>floatval($this->input->post('pqty')),
							'purchase_price'=>floatval($this->input->post('pprice')),

							'session_1_old_qty'=>0,
							'session_1_old_price'=>0,
							'session_1_new_qty'=>floatval($this->input->post('bf_qty')),
							'session_1_new_price'=>floatval($this->input->post('bf_price')),						
							'session_1_qty'=>floatval($this->input->post('bf_qty')),
							'session_1_price'=>floatval($this->input->post('bf_price')),
							
							'session_2_old_qty'=>0,
							'session_2_old_price'=>0,
							'session_2_new_qty'=>floatval($this->input->post('lu_qty')),
							'session_2_new_price'=>floatval($this->input->post('lu_price')),
							'session_2_qty'=>floatval($this->input->post('lu_qty')),
							'session_2_price'=>floatval($this->input->post('lu_price')),

							'session_3_old_qty'=>0,
							'session_3_old_price'=>0,
							'session_3_new_qty'=>floatval($this->input->post('sn_qty')),
							'session_3_new_price'=>floatval($this->input->post('sn_price')),
							'session_3_qty'=>floatval($this->input->post('sn_qty')),
							'session_3_price'=>floatval($this->input->post('sn_price')),

							'session_4_old_qty'=>0,
							'session_4_old_price'=>0,
							'session_4_new_qty'=>floatval($this->input->post('di_qty')),
							'session_4_new_price'=>floatval($this->input->post('di_price')),
							'session_4_qty'=>floatval($this->input->post('di_qty')),
							'session_4_price'=>floatval($this->input->post('di_price'))
							);
							
		  
			 
			$this->db->where('entry_id',$entry_id);
			$this->db->update('balance_sheet', $update_data);  
			
			
			$pur_rs = $this->db->query("select * from purchases where school_id=? and item_id=? and purchase_date=?",array($school_id,$item_id, $date));
			if($pur_rs->num_rows()==0)
			{
					$this->db->query("insert into purchases set school_id=? , item_id=? , purchase_date=?,quantity=?,purchase_price=?",array($school_id,$item_id,$date, floatval($this->input->post('pqty')),floatval($this->input->post('pprice'))));
			}
			else{
					$prow_id = $pur_rs->row()->pid;
					$this->db->query("update purchases set quantity=?,purchase_price=? where pid=? ",array( floatval($this->input->post('pqty')),floatval($this->input->post('pprice')),$prow_id));
					
			}
			
			$this->common_model->update_entries($school_id,$item_id,$date);
			$this->session->set_flashdata('message', '<div class="alert alert-success">Updated Successfully.</div>');
			/*redirect('purchase_consumption');*/
			
			send_json_result([
                'success' =>  TRUE ,
                'message' => '<div class="alert alert-success">Updated Successfully</div>'  
            ] );  
			
		}
		
		//print_a($data["today_consumes"]);
		$drs = $this->db->query("SELECT s.*,d.name as dname,s.name as sname FROM  schools  s inner join districts d on s.`district_id`=d.district_id and s.school_id=?",array($school_id));         
        $data["school_info"] = $drs->row();
        
		$data["date_selected"] = date('d-M-Y',strtotime($date));
		$data["date"] = $date ;
		$data["school_id"] = $school_id ;
		$data["item_id"] = $item_id ;
		$data["item_details"] = $this->db->query("select * from items where item_id=?",array($item_id))->row();
		 	
		 
		$form_data = array('pqty'=>0,'pprice'=>'0','bf_qty'=>'0','bf_price'=>'0','lu_qty'=>'0','lu_price'=>'0','sn_qty'=>'0','sn_price'=>'0','di_qty'=>'0','di_price'=>'0');
		 
				
				//print_a($qp_data)				;
	 
		$form_data['opening_quantity'] = $qp_data->opening_quantity ;	
		$form_data['pqty'] = $qp_data->purchase_quantity;	
		$form_data['pprice'] = $qp_data->purchase_price;	
		$form_data['bf_qty'] = $qp_data->session_1_qty;	
		$form_data['bf_price'] = $qp_data->session_1_price;	
		$form_data['lu_qty'] = $qp_data->session_2_qty;	
		$form_data['lu_price'] = $qp_data->session_2_price;	
		$form_data['sn_qty'] = $qp_data->session_3_qty;	
		$form_data['sn_price'] = $qp_data->session_3_price;	
		$form_data['di_qty'] = $qp_data->session_4_qty;	
		$form_data['di_price'] = $qp_data->session_4_price;	 
 
        $data["form_data"] = $form_data;        
        $data["module"] = "purchase_consumption";        
        $data["view_file"] = "edit_form";
        echo Modules::run("template/admin", $data);
	}
	 
	  function createNonExistRecord($school_id,$item_id, $todate)
	 {
			$stock_entry_table = $this->common_model->get_stock_entry_table($todate);
			$sql =  "select max(entry_date) as edate from  $stock_entry_table where school_id=? and item_id=? and entry_date< ?";
			$rs = $this->db->query($sql,array($school_id,$item_id,$todate));
			 
			if($rs->num_rows()==0) {
					$this->session->set_flashdata('message', '<div class="alert alert-danger">No entries Found in $stock_entry_table. please Contact Administrator.</div>');
					redirect("purchase_consumption_bulk");
			}
			$bsdata = $rs->row();
			$sql =  "select * from  $stock_entry_table where school_id=? and item_id=? and entry_date=? ";
			$bsd_rs = $this->db->query($sql,array($school_id,$item_id,$bsdata->edate));
			$bsd_data = $bsd_rs->row();
			$closing_quantity = $bsd_data->closing_quantity;
			if($closing_quantity=="" || $closing_quantity == NULL)
					$closing_quantity = 0.00;
			$ins_data = array('school_id'=>$school_id,'item_id'=>$item_id,'entry_date'=>$todate,'opening_quantity'=>$closing_quantity ,'closing_quantity'=>$closing_quantity );
			$this->db->insert($stock_entry_table ,$ins_data); 
			//echo $this->db->last_query();die;
	 }
	 private function generate_html_table($dataarray=array())
	 {
		$table_html = "<table class='table'><thead><tr><th>Date</th><th>Opening Quantity</th><th>Purchase Quantity</th><th>Total</th><th>Consumed Quantity</th><th>Closing Quantity </th></tr></thead><tbody>";
		foreach($dataarray as $data)
		{
			$total_used = $data['session_1_qty'] + $data['session_2_qty'] + $data['session_3_qty'] + $data['session_4_qty'] ;
			$avilable_qty = $data['opening_quantity'] + $data['purchase_quantity'];
			
			$table_html  .= "<tr><td>".$data['entry_date_dp']."</td><td>".$data['opening_quantity']."</td><td>".$data['purchase_quantity']."</td><td>".$avilable_qty."</td><td>".$total_used."</td><td>".number_format($data['closing_quantity'],3)."</td></tr>";
		
		}
		$table_html .= "</tbody></table>";
		return $table_html;
	 }
	 
	
}
