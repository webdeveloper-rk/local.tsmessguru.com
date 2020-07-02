<?php

class Settings extends MX_Controller {

    function __construct() {
        parent::__construct();
        Modules::run("security/is_admin");
		
        $this->load->model("admin_model");
        $this->admin_model->set_table("site_settings");
    }

    public function index() {
        $this->listing();
    }

    function listing($offset = 0) {
        //Uncomment below line to check authentication if defined
        //
        //Default values to be set
        $order_by = "id asc";
        $conditions = array();

        $meta_title = "";
        $meta_description = "";
        $meta_keywords = "";
        $module = "admin";
        $view_name = "settings";
        $template_method = "admin";
        /*         * *************** DEFAULT CODE ************************************** */
        $data['records'] = $this->admin_model->custom_query("select * from site_settings where id=1");
//        //If Records not exist
//        if (($data["records"]->num_rows() == 0)&&($offset!=0)) {
//            redirect(site_url()."admin/settings");
//        }
//    
       
        $data["meta_keywords"] = $meta_keywords;
        $data["module"] = $module;
        $data["view_file"] = $view_name;
        echo Modules::run("template/$template_method", $data);
    }

    function edit() {
        if ($this->input->post("form_submit") != "") {
            $posted_data = $this->set_form_inputs();
            $where = array('id' => 1);
            if ($this->admin_model->update($posted_data, $where)) {
                $this->userlib->show_ajax_output(TRUE, "Updated Successfully");
            } else {
                $this->userlib->show_ajax_output("", "Error occured while updating");
            }
        }
        $records = $this->admin_model->custom_query("select * from site_settings");
        $data['details'] = $records->row();
        $data["module"] = "admin";
        $data["view_file"] = "edit_settings";
        echo Modules::run("template/admin", $data);
    }

    function set_form_inputs() {
        //Database_Column_Name => Form_Input_Name
        $dbcol_input = array(
            "site_title" => "site_title",
            "contact_email" => "contact_email",
            "contact_no1" => "contact_no1",
            "contact_no2" => "contact_no2",
            "news" => "news",
            "address" => "address",
            "homepage_video" => "homepage_video",
               
        );
        foreach ($dbcol_input as $dbcol => $input) {
            $data[$dbcol] = $this->input->post($input);
        }
        return $data;
    }

    function upload_image() {
         $imgcol='site_logo';
        if ($this->input->post("form_submit") != '') {
            $this->file_path = realpath(APPPATH . '../assets/img_common');
            $this->file_path_url = site_url() . 'assets/img_common/';
            $config = array(
               'allowed_types' => 'jpg|jpeg|png|gif|JPG|JPEG|PNG|GIF',
                'upload_path' => $this->file_path,
                'max_size' => 1024,
                'encrypt_name' => FALSE
            );
            $this->load->library('upload', $config);

            if (!$this->upload->do_upload('image')) {
                $upload_error = $this->upload->display_errors();
                 $this->userlib->show_ajax_output('',  strip_tags($upload_error));
            } else {
                $data = array('upload_data' => $this->upload->data());
                $image = $data['upload_data']['file_name'];
                //Deleting Old Picture Record
                $old_pic_name = $this->userlib->get_title('site_settings', 'site_logo', array('id' => 1));
                if ($old_pic_name) {
                    if (file_exists("assets/img_common/" . $old_pic_name)) {
                        $delete_file = unlink($this->file_path . "/" . $old_pic_name); //Deleting Old pic
                    }
                }
                $image_name=explode(".",$image);
               $filenew= rename('assets/img_common/'.$image, 'assets/img_common/logo.'.$image_name[1]);
                //New Pic Saving to Database
                $image_path="assets/img_common/logo.".$image_name[1];
                $this->db->set($imgcol, $image_path);
                $this->db->where('id', '1');
                $sucess = $this->db->update("site_settings");
                if ($sucess) {
                    $this->userlib->show_ajax_output(TRUE, "Updated Successfully");
                } else {
                    $this->userlib->show_ajax_output('', "Error While Updating ");
                }
            }
        }
     
        $this->load->view("uploadimage");
    }

}
