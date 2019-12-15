<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Movdata extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
			echo "Hi";
			$sql = "SELECT s.district_id,code , school_code,school_id from schools s inner join districts d on s.district_id = d.district_id ";
			$rs = $this->db->query($sql);
			$district_schools = array();
			if($rs->num_rows()>0)
			{
				foreach($rs->result() as $row)
				{
					$district_schools[$row->school_id] =  array("did"=>$row->district_id,"code"=>$row->code);
				}
			}
		// print_a($district_schools);
		 $sql = "select * from balance_sheet"
		 $rs = $this->db->query($sql);
			$district_schools = array();
			if($rs->num_rows()>0)
			{
				foreach($rs->result() as $row)
				{
					$update_sql = "insert into "
				}
			}
		 
	}
}
