<?php

class Admin_model extends CI_Model {

    var $table;

    function __construct() {
        parent::__construct();
    }

    function set_table($name = "") {
        $this->table = $name;
    }

    //This function is to return the results of the query
    function multiple($conditions = array(), $order_by = "", $offset = 0, $limit = "") {
        //If Conditions Mentioned in array format
        if (count($conditions) > 0) {
            $this->db->where($conditions);
        }
        //If Order by Mentioned
        if ($order_by) {
            $this->db->order_by($order_by);
        }
        //If limit is set
        if ($limit) {
            $this->db->limit($limit, $offset);
        }
        $result = $this->db->get($this->table);
        return $result;
    }

    //This Function is to return single record values, if thrid param is set to TRUE, it return with column name values
    function single($conditions = array(), $with_cols = FALSE) {
        //If Conditions Mentioned in array format
        if (count($conditions) > 0) {
            $this->db->where($conditions);
        }
        $result = $this->db->get($this->table);
        if ($result->num_rows() > 0) {
            if ($with_cols == TRUE) {
                $data = $result->row_array();
                return $data;
            } else {
                return $result->row();
            }
        } else {
            return NULL;
        }
    }

    //This function is to return result with db column names
    function get_column_values($row) {
        $data = array();
        $table_cols = $this->db->list_fields($this->table);
        foreach ($table_cols as $col) {
            $data[$col] = $row->$col;
        }
        return $data;
    }

    function insert($data, $insert_id = FALSE) {
        if (count($data) > 0) {
            $this->db->set($data);
            $query = $this->db->insert($this->table);
            if ($query) {
                if ($insert_id == TRUE)
                    return $this->db->insert_id();
                else
                    return TRUE;
            }
            else
                return FALSE;
        }
        else
            return FALSE;
    }

    function update($data = array(), $where = array(), $affected_rows = FALSE) {
        if (count($data) > 0) {
            $this->db->set($data);
            if (count($where) > 0) {
                $this->db->where($where);
            }
            $query = $this->db->update($this->table);
            if ($query) {
                if ($affected_rows == TRUE)
                    return $this->db->affected_rows();
                else
                    return TRUE;
            }
            else
                return FALSE;
        }
        else
            return FALSE;
    }

    function delete($where = array(), $affected_rows = FALSE) {
        if (count($where) > 0) {
            $this->db->where($where);
            $query = $this->db->delete($this->table);
            if ($query) {
                if ($affected_rows == TRUE)
                    return $this->db->affected_rows();
                else
                    return TRUE;
            }
            else
                return FALSE;
        }
        else
            return FALSE;
    }

    function count_where($conditions) {
        $this->db->where($conditions);
        $query = $this->db->get($this->table);
        $num_rows = $query->num_rows();
        return $num_rows;
    }

    function count_all() {
        $query = $this->db->get($this->table);
        $num_rows = $query->num_rows();
        return $num_rows;
    }

    function get_max() {
        $this->db->select_max('id');
        $query = $this->db->get($this->table);
        $row = $query->row();
        $id = $row->id;
        return $id;
    }

    function custom_query($mysql_query) {
        $query = $this->db->query($mysql_query);
        return $query;
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
        function single_ads($conditions = array(), $with_cols = FALSE) {
        //If Conditions Mentioned in array format
        if (count($conditions) > 0) {
            $this->db->where($conditions);
        }
        $result = $this->db->get('ad_blocks');
        if ($result->num_rows() > 0) {
            if ($with_cols == TRUE) {
                $data = $result->row_array();
                return $data;
            } else {
                return $result->row();
            }
        } else {
            return NULL;
        }
    }
      function save_ad_blocks() {
       $page_ids = $this->input->post('page_id');
        $ad_ids = $this->input->post('ad_id'); 
        $this->db->where("page_id", $page_ids);
        $this->db->delete("ad_display");
          if (count($ad_ids) >= 1) {
        foreach ($ad_ids as $val) {
                $ids = explode("-", $val);
                $ins_data = array(
                    "page_id" => $page_ids,
                    "ad_id" => $ids[0],
                    "block_id" => $ids[1],
                );
                $this->db->set($ins_data); 
                $this->db->insert("ad_display"); 
             }
          }
          $this->userlib->show_ajax_output(TRUE, "This Advertisement Successfully Updated to the specific pages ");
       
    }
	
	    function reset_password() {
        $school_code  = $this->input->post("school_code");
		
		$sql = "select * from users where school_code='". $school_code."'";
		$trs = $this->db->query($sql);
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
		
		$sql = "select * from  	student_logins where school_code='". $school_code."'";
		$trs = $this->db->query($sql);
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
            $query = $this->db->update("student_logins 	");
            if ($query){
						$this->session->set_userdata('upassword',$this->input->post("newpwd") );
						$this->userlib->show_ajax_output(TRUE, 'New Password is successfully changed');
			}
            else
                $this->userlib->show_ajax_output("", 'Error Occured While Updating Password');
        }
    }
}