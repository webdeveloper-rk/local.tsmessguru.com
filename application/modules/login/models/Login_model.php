<?php

class Login_model extends CI_Model {

    var $table = "users";

    function __construct() {
        parent::__construct();
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
	//	echo "---", $this->db->last_query();
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
     function reset_user($md5_email, $activation_code) {
        $sql = "SELECT * FROM users WHERE md5(email) = '$md5_email'AND status = 'A' AND activation_code= '$activation_code'";
        $res = $this->db->query($sql);
       
        return $res;
    }
      function get_where_custom($col, $value) {
        $this->db->where($col, $value);
        $query = $this->db->get('users');
        return $query;
    }

}