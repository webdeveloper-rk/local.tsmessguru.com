<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Userlib {

    var $message = "";

    /**
     * Constructor
     *
     * Get instance for Database Lib
     *
     * @access	public
     */
    function Userlib() {
        $this->CI = & get_instance();

        log_message('debug', "User Class Initialized");
    }
        function get_row($table, $cond_arr = array()) {
        //Query
        if (count($cond_arr) > 0) {
            $this->CI->db->where($cond_arr);
        }
        $query = $this->CI->db->get($table)->row();
        return $query;
    } 


    function record_detail($table, $cond_arr = array()) {
        $details = array();
        //Table Columns Initialization
        $fields = $this->CI->db->list_fields($table);
        //Query
        if (count($cond_arr) == 0) {
            $cond_arr = array('status' => 'A');
        }
        $this->CI->db->where($cond_arr);
        $query = $this->CI->db->get($table);
        $res = $query->row();
        //Results
        if ($query->num_rows() > 0) {
            foreach ($fields as $field) {
                $details[$field] = $res->$field;
            }
        }
        return $details;
    }

    function get_title($table, $title_col, $cond_arr) {
        $cond;
        foreach ($cond_arr as $col => $val) {
            $cond[] = " $col = $val";
        }
        if ($cond) {
            $cond = implode(" AND ", $cond);
            $cond = "WHERE " . $cond;
        }
        $sql = "SELECT $title_col FROM $table $cond";
        $res = $this->CI->db->query($sql)->row()->$title_col;
        return $res;
    }

    function make_short($string, $maxLength) {
        $count = strlen($string);
        if ($count > $maxLength) {
            $stringCut = substr($string, 0, $maxLength);
            $new_str = substr($stringCut, 0, strrpos($stringCut, ' '));
            $new_str .= "...";
        } else {
            $new_str = $string;
        }
        return $new_str;
    }

    function get_titles($table, $cond = array()) {
        if (count($cond) > 0) {
            $this->CI->db->where($cond);
        }
        $res = $this->CI->db->get($table);
        return $res->result();
    }

    # This function makes any text into a url frienly

    function clean_url($text) {
        $text = strtolower(trim($text));
        $code_entities_match = array(' ', '--', '&quot;', '!', '@', '#', '$', '%', '^', '&', '*', '(', ')', '_', '+', '{', '}', '|', ':', '"', '<', '>', '?', '[', ']', '\\', ';', "'", ',', '.', '/', '*', '+', '~', '`', '=');
        $code_entities_replace = array('-', '-', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '');
        $text = str_replace($code_entities_match, $code_entities_replace, $text);
        $text = str_replace("--", "-", $text);
        return $text;
    }
    
    function clean_string($text) {
        $text = strtolower(trim($text));
        $code_entities_match = array('&quot;', '!', '@', '#', '$', '%', '^', '&', '*', '(', ')', '_', '+', '{', '}', '|', ':', '"', '<', '>', '?', '[', ']', '\\', ';', "'", ',', '.', '/', '*', '+', '~', '`', '=');
        $text = str_replace($code_entities_match, "", $text);
        return $text;
    }
    
    function show_ajax_output($success = '', $msg = '', $extra_param = "") {
        $block = $this->gen_msg_output($success, $msg);
        die(json_encode(array("success" => $success, "message" => $block, "extra_param" => $extra_param)));
    }
    
    function gen_msg_output($success = '', $msg = '') {
        if ($success == '') {
            //ERROR DISPLAY
            $class = 'danger';
            $title = "Warning";
        } else {
            //SUCCESS DISPLAY
            $class = 'success';
            $title = "Success";
        }
       $block = '<div class="alert alert-'.$class.'" style="margin-left: 0;margin-top: 10px;">
                    <strong>'.$title.'!</strong>&emsp;
                    '.$msg.'...
                </div>';
        return $block;
    }
    
    function gen_admin_breadcrumb($links = array()) {
        //BreadCrumb Initialization
        $bc_str = '<ul class="breadcrumb">';
        $links_count = count($links);
        if ( $links_count > 0 ) {
            $bc_str .= '<li><a href="'.  site_url(ADMIN_DIR).'">Dashboard</a> <span class="divider">/</span></li>';
            foreach ($links as $title => $link) {
                if ($links_count != 1)
                    $bc_str .= '<li><a href="'.  site_url(ADMIN_DIR.$link).'">'.$title.'</a> <span class="divider">/</span></li>';
                else
                    $bc_str .= '<li class="active">'.$title.'</li>';
                $links_count--;
            }
        } else {
            $bc_str .= '<li class="active">Dashboard</li>';
        }
        $bc_str .= '</ul><div class="clearfix"></div>';
        return $bc_str;
    }
    
    function gen_breadcrumb($links = array()) {
        //BreadCrumb Initialization
        $bc_str = '<ul class="breadcrumb">';
        $links_count = count($links);
        if ( $links_count > 0 ) {
            $bc_str .= '<li><a href="'.  site_url().'">Home</a> <span class="divider">/</span></li>';
            foreach ($links as $title => $link) {
                if ($links_count != 1) {
                    if ($link != "")
                        $bc_str .= '<li><a href="'.  site_url($link).'">'.$title.'</a> <span class="divider">/</span></li>';
                    else
                        $bc_str .= '<li>'.$title.' <span class="divider">/</span></li>';
                } else
                    $bc_str .= '<li class="active">'.$title.'</li>';
                $links_count--;
            }
        } else {
            $bc_str .= '<li class="active">Home</li>';
        }
        $bc_str .= '</ul><div class="clearfix"></div>';
        return $bc_str;
    }
    
    function gen_url($controller, $method, $id, $title) {
        $url = $this->clean_url($title);
        $url = site_url("$controller/$method/$url/$id");
        return $url;
    }
    
    function get_page_advts($controller, $method="") {
        $sql = "select a.ad_id, a.block_id  from ad_display a, ad_pages b where b.controller = '$controller' and b.method = '$method' and a.page_id = b.page_id and b.status = 'A'";
        $ads_res = $this->CI->db->query($sql);
//        print_r($this->CI->db->last_query());
        foreach ($ads_res->result() as $ad) {
            $ad_dtails = $this->record_detail("ad_details", array("ad_id"=>$ad->ad_id));
            //print_r($ad_dtails);
            $block_dtails = $this->record_detail("ad_blocks", array("block_id"=>$ad->block_id));
            if ($ad_dtails["status"] == "A" && $block_dtails["status"] == "A")
            $ads[$ad_dtails["ad_id"]] = array(
                "block"         => $block_dtails["block_title"],
                "image"         => $ad_dtails["image"],
                "link"          => $ad_dtails["ad_url"],
                "block_width"   => $block_dtails["width"],
                "block_height"   => $block_dtails["height"],
            );
        }
        
        foreach ($ads as $id => $ad) {
            if ($ad["block_width"] != 0) {
                $max_width = "max-width: " . $ad["block_width"] . "px;";
            } else {
                $max_width = "";
            }
            if ($ad["block_height"] != 0) {
                $max_height = "max-height: " . $ad["block_height"] . "px;";
            } else {
                $max_height = "";
            }
            $adblocks[$ad["block"]][$id] = '<div class="dyad_block" style="' . $max_width . $max_height . '"><a href="' . $ad["link"] . '" target="_blank"><img src="' . site_url() . "uploads/ads/" . $ad["image"] . '"  style="' . $max_width . ';' . $max_height . '" /></a></div>';
        }
        return $adblocks;
    }
            
}

// END User Class 
?>
