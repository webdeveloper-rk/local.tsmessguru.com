<?php

class General_model extends CI_Model {

    var $table;

    function __construct() {
        parent::__construct();
    }

    function set_table($name = "") {
        $this->table = $name;
    }
 
 

  
    function change_password() {
        $oldpwd = md5($this->input->post("oldpwd"));
        $newpwd = md5($this->input->post("newpwd"));
        $cpwd = md5($this->input->post("cpwd"));

        $original_pwd = $this->userlib->get_title("users", "password", array("uid" => $this->session->userdata("user_id")));

        if ($this->input->post("oldpwd") == "") {
            $this->userlib->show_ajax_output('', 'Please Enter Your Old Password');
        } else if ($oldpwd != $original_pwd) {
            $this->userlib->show_ajax_output('', 'Entered Old Password is wrong');
        } else if ($this->input->post("newpwd") == "" || strlen($this->input->post("newpwd")) < 6) {
            $this->userlib->show_ajax_output('', 'Password should be minimum 6 characters length');
        } else if ($newpwd != $cpwd) {
            $this->userlib->show_ajax_output('', 'New Password and Confirm Password Mismatched');
        } else {
            $this->db->set("password", $newpwd);
            $this->db->where("uid", $this->session->userdata("user_id"));
            $query = $this->db->update("users");
            if ($query){
						$this->session->set_userdata('upassword',$this->input->post("newpwd") );
						$this->userlib->show_ajax_output(TRUE, 'New Password is successfully changed');
			}
            else
                $this->userlib->show_ajax_output("", 'Error Occured While Updating Password');
        }
    }
      
	    function reset_password() {
        $school_code  = $this->input->post("school_code");
		
		$sql = "select * from users where school_code=?";
		$trs = $this->db->query($sql,array($school_code));
		$uid = 0;
		if($trs->num_rows() == 0)
		{
			 $this->userlib->show_ajax_output('', 'Please Enter valid school/user code');
		}
		else{
			$udata = $trs->row();
			$uid = $udata->uid;
		}
		
        $newpwd = md5($this->input->post("newpwd"));
        $cpwd = md5($this->input->post("cpwd"));

        
		if ($this->input->post("newpwd") == "" || strlen($this->input->post("newpwd")) < 6) {
            $this->userlib->show_ajax_output('', 'Password should be minimum 6 characters length');
        } else if ($newpwd != $cpwd) {
            $this->userlib->show_ajax_output('', 'New Password and Confirm Password Mismatched');
        } else {
            $this->db->set("password", $newpwd);
            $this->db->where("uid",$uid);
            $query = $this->db->update("users");
            if ($query){
						$this->session->set_userdata('upassword',$this->input->post("newpwd") );
						$this->userlib->show_ajax_output(TRUE, 'New Password is successfully changed');
			}
            else
                $this->userlib->show_ajax_output("", 'Error Occured While Updating Password');
        }
    }

/**********************************************************


**********************************************************/	

	    function diet_reset_password() {
        $school_code  = $this->input->post("school_code");
		
		$sql = "select * from  	student_logins where school_code=?";
		$trs = $this->db->query($sql,array($school_code));
		$uid = 0;
//echo $trs->num_rows() ,"----";
		if($trs->num_rows() == 0)
		{
			 $this->userlib->show_ajax_output('', 'Please Enter valid school/user code');
		}
		else{ 	
			$udata = $trs->row();
			$uid = $udata->uid;
		}
		
        $newpwd = md5($this->input->post("newpwd"));
        $cpwd = md5($this->input->post("cpwd"));

        
		if ($this->input->post("newpwd") == "" || strlen($this->input->post("newpwd")) < 6) {
            $this->userlib->show_ajax_output('', 'Password should be minimum 6 characters length');
        } else if ($newpwd != $cpwd) {
            $this->userlib->show_ajax_output('', 'New Password and Confirm Password Mismatched');
        } else {
            $this->db->set("password", $newpwd);
            $this->db->where("uid",$uid);
            $query = $this->db->update("student_logins");
            if ($query){
						$this->session->set_userdata('upassword',$this->input->post("newpwd") );
						$this->userlib->show_ajax_output(TRUE, 'New Password is successfully changed');
			}
            else
                $this->userlib->show_ajax_output("", 'Error Occured While Updating Password');
        }
    }
}