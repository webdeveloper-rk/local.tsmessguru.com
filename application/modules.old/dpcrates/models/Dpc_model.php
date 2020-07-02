<?php
class Dpc_model extends CI_Model {

    var $table;

    function __construct() {
        parent::__construct();
    }
	
	function get_dpc_submitted($school_id=0)
	{
		 
		$financial_year_id = $this->db->query("SELECT * FROM `dpcrates_years` where status='active' order by financial_year_id desc")->row()->financial_year_id;
		$submited_rs = $this->db->query("SELECT * FROM `dpcrates_approvals` where financial_year_id=? and school_id=? and school_submitted=1",
										array($financial_year_id,$school_id));
		if($submited_rs->num_rows()==1)
		{
			return true ;
		}	
		else{
			return false;
		}
		 
	}
	
	function get_dpc_approved_status($school_id=0)
	{
		$financial_year_id = $this->db->query("SELECT * FROM `dpcrates_years` where status='active' order by financial_year_id desc")->row()->financial_year_id;
		$submited_rs = $this->db->query("SELECT * FROM `dpcrates_approvals` where financial_year_id=? and school_id=? ",
										array($financial_year_id,$school_id));
		 
			$row = $submited_rs->row();
			 
			return $row->dpc_approved;
			 
		 
	}
	
	function mark_school_as_submited($school_id=0)
	{
		$financial_year_id = $this->db->query("SELECT * FROM `dpcrates_years` where status='active' order by financial_year_id desc")->row()->financial_year_id;
		$submited_rs = $this->db->query("update  `dpcrates_approvals` set school_submitted=1 ,school_submited_date=now(),dpc_approved='0'
											where financial_year_id=? and school_id=?  ",
										array($financial_year_id,$school_id));
		 return true;
	}
	
	
	function mark_school_as_approved($school_id=0)
	{
		 
		$financial_year_id = $this->db->query("SELECT * FROM `dpcrates_years` where status='active' order by financial_year_id desc")->row()->financial_year_id;
		$submited_rs = $this->db->query("update  `dpcrates_approvals` set dpc_approved='1' 
											where financial_year_id=? and school_id=?",
										array($financial_year_id,$school_id));
		 return true;
	
	}
	function mark_school_as_rejected($school_id=0)
	{
		$financial_year_id = $this->db->query("SELECT * FROM `dpcrates_years` where status='active' order by financial_year_id desc")->row()->financial_year_id;
		$submited_rs = $this->db->query("update  `dpcrates_approvals` set dpc_approved='-1'
											where financial_year_id=? and school_id=?",
										array($financial_year_id,$school_id));
		 return true;
	}
	function check_role_allowed($school_id=0)
	{
		if($this->session->userdata("is_dco")==true)
		{
			$district_id = $this->session->userdata("district_id");
			 
			$rrs = $this->db->query("select * from schools where school_id='$school_id' and district_id= '$district_id' ");
			 if($rrs->num_rows()==0){
				 redirect('dpcrates/admin');
			 }
		}
	}
}