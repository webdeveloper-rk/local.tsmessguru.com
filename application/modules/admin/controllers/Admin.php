<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Admin extends MX_Controller {

    function __construct() {
        parent::__construct();
		
		if($this->uri->segment(2) !="login") { 
					 Modules::run("security/is_admin");		 
					if ($this->session->userdata("is_loggedin") != TRUE || $this->session->userdata("user_id") == "" ) {
							redirect("admin/login");
							die;
					}else  if ($this->session->userdata("school_code") == "10000")
					{
						redirect("menu_permissions");
							die;
					}
					else if ($this->session->userdata("is_loggedin") == TRUE)
					{
						$role_id = $this->session->userdata("role_id");
						$redirect_rs = $this->db->query("select * from roles where role_id=?",array($role_id));
						 //echo $redirect_rs->num_rows();die;
						if($redirect_rs->num_rows()==0)
						{
							redirect("admin/login");
							die;
						}
						else
						{
							$rdata = $redirect_rs->row();
							//echo $rdata->dashboard_link;die;
							redirect($rdata->dashboard_link);
						}
					
					}
				 
					
					
					 
		}
					
          $this->load->model('admin_model');
         $this->admin_model->set_table("users");
    }

   

    function index() {

        redirect("admin/dashboard");

    }



    function login() {

	
		//redirect(site_url());
        //If Form Submitted --

        if ($_POST) {
				if($this->config->item("site_name")!="tstribal") { 
					$password  = $this->cryptoJsAesDecrypt($this->input->post("password"));
				}
				else{
					$password  =  $this->input->post("password") ;
				}
				
            $conditions = array(

                "school_code" => $this->input->post("email", TRUE),

                "password" => md5($password ),  
                "status" => "A"

            );
			 
			//check captcha code here 
			$captcha_code  = strtoupper(trim($this->input->post("captcha")));
			//replace spaces
			$captcha_code = str_replace(" ","",$captcha_code);
			$session_code = strtoupper(trim($this->session->userdata("user_captcha")));
				if($this->config->item("site_name")!="tstribal")
				{
					if($captcha_code != $session_code)
					{
						$this->userlib->show_ajax_output("", "Invalid Captcha Code");
					}
					else
					{
						$this->session->set_userdata(array("user_captcha"=>rand(100000,9999999)));
					}
				}
			
			/*if(ip_allowed_to_edit($this->input->ip_address())){
							  $conditions = array(  "school_code" => $this->input->post("email", TRUE),  "status" => "A" );
			}*/
			
            $result =  Modules::run('login/check', $conditions);

            if ($result == TRUE) {
					
					$school_code = $this->input->post("email", TRUE);
					$is_collector = $this->db->query("select * from users where school_code=?",array($school_code))->row()->is_collector; 
					$role_id = $this->db->query("select * from user_roles where school_code=?",array($school_code))->row()->role_id; 
					$menu_roles_rs  = $this->db->query("select mr.menu_id as mid,m.*  from menu_roles mr inner join menus m on m.menu_id=mr.menu_id where role_id=? order by menu_order_id asc",array(intval($role_id )));
					
					if($is_collector==1)
					{
					
						$menu_roles_rs  = $this->db->query("select mr.menu_id as mid,m.*  from menu_roles mr inner join menus m on m.menu_id=mr.menu_id where role_id=? and is_collector_report=1 order by menu_order_id asc",array(intval($role_id )));
					}
					else{
							$menu_roles_rs  = $this->db->query("select mr.menu_id as mid,m.*  from menu_roles mr inner join menus m on m.menu_id=mr.menu_id where role_id=? order by menu_order_id asc",array(intval($role_id )));
					}
					$menus_list = array();
					foreach($menu_roles_rs->result() as $mrow)
					{
						$menus_list[$mrow->menu_parent_id][] = $mrow;
					}
					
					/*$main_menus_rs  = $this->db->query("select mr.menu_id as mid,m.*  from menu_roles mr inner join menus m on m.menu_id=mr.menu_id
											where role_id=? and menu_parent_id=0 order by ",array(intval($role_id )));
					$main_menus_list = array();
					foreach($main_menus_rs->result() as $mrow)
					{
						$main_menus_list[$mrow->menu_parent_id][] = $mrow;
					}*/
					
					$this->session->set_userdata(array("role_id"=>$role_id,'menus_list'=>$menus_list));
					
                $this->userlib->show_ajax_output(TRUE, "Redirecting to Dashboard");

            } else {

                $this->userlib->show_ajax_output("", "Invalid Login Details");

            }

        }
		else{
			if($this->config->item("society_name")=="TSMESSGURU")
			{
				$this->load->view("login");
				return;
			}else { 
				redirect(site_url());
			}
		}

        // Modules::run("security/is_admin_loggedin");

        $this->load->view("login");

    }

    

    function logout() {

        $variables = array("user_id"=>"","user_name"=>"","user_email"=>"","user_role"=>"blahahhahahahha","is_loggedin"=>false);

        $this->session->set_userdata($variables);

        $this->session->unset_userdata($variables);

        $msg = $this->userlib->gen_msg_output(TRUE,"You are successfully logged out");

        $this->session->set_flashdata('notice', $msg);

        redirect("admin/login");

    }

     function changepassword() {

       //    Modules::run("security/is_admin");

         $this->load->model('admin_model');

         if ($this->input->post("action")=="updatepassword") {

        $data= $this->admin_model->change_password();

        }

        $data["module"] = "admin";

        $data["view_file"] = "changepassword_view";

        echo  Modules::run("template/admin", $data);

    }

     function profile() {

          Modules::run("security/is_admin");

        $userid = $this->session->userdata("user_id");

        $data['user_details'] = $this->admin_model->single(array('uid' => $userid));

         if ($this->input->post("profile_form_submit")) {

            $posted_data = array('name'=>$this->input->post('adminname'),

                                  'email'=>$this->input->post('adminemail')

                                                   );



            if ($this->admin_model->update($posted_data,array('uid' => $userid))) {

                $this->userlib->show_ajax_output(TRUE, "Updated Successfully");

            }

        }

        $data["module"] = "admin";

        $data["view_file"] = "profile_view";

        echo Modules::run("template/admin", $data);

    }

	
/**
* Decrypt data from a CryptoJS json encoding string
*
* @param mixed $passphrase
* @param mixed $jsonString
* @return mixed
*/
function cryptoJsAesDecrypt(  $jsonString){
	$this->load->config("hash_config");
	$passphrase = $this->config->item("hash_code");
    $jsondata = json_decode($jsonString, true);
    try {
        $salt = hex2bin($jsondata["s"]);
        $iv  = hex2bin($jsondata["iv"]);
    } catch(Exception $e) { return null; }
    $ct = base64_decode($jsondata["ct"]);
    $concatedPassphrase = $passphrase.$salt;
    $md5 = array();
    $md5[0] = md5($concatedPassphrase, true);
    $result = $md5[0];
    for ($i = 1; $i < 3; $i++) {
        $md5[$i] = md5($md5[$i - 1].$concatedPassphrase, true);
        $result .= $md5[$i];
    }
    $key = substr($result, 0, 32);
    $data = openssl_decrypt($ct, 'aes-256-cbc', $key, true, $iv);
    return json_decode($data, true);
}

}

