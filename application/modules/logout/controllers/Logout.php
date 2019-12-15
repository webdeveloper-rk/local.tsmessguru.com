<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Logout extends MX_Controller {
     public function index() {
        $this->load->library('HybridAuthLib');
        $this->hybridauthlib->logoutAllProviders();
        $this->session->sess_destroy();
        $msg = $this->userlib->gen_msg_output(TRUE, "You are successfully logged out");
        $this->session->set_flashdata("notice", $msg);
        redirect(site_url());
    }
}
?>
