<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Photogallery extends CI_Controller {

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
		 
		 redirect('photogallery/functionhall');
	}
	public function functionhall($album_id)
	{
		 $sql = "select * from  albums where category='Function Hall' and status='published'";
		 $album_rs = $album_copy = $this->db->query($sql);
		 $content['albums'] =  $album_rs ;
		 
		 $content['photos'] = array();
		 if($album_id =="")
		 {
				$album_first_row =  $album_copy->row();
				$album_id = $album_first_row->album_id; 
		 }
		 $content['album_id'] =  $album_id;
		  $sql = "select * from albums  where category='Function Hall' and status='published' and album_id='$album_id'";
		 $album_rs =   $this->db->query($sql);
		  $album_data = $album_rs->row();
		  $content['album_title'] =  $album_data->album_title;
		  $content['album_desc'] =  $album_data->album_desc; 
		 
		 
			$photo_sql = "select * from album_photos where album_id='$album_id'  ";
			$photos = $this->db->query( $photo_sql);
			$content['photos'] =  $photos;
		 
		 $this->load->view("functionhallgallery",$content);
	}
	public function residency($album_id)
	{
		 $sql = "select * from albums where category='Residency' and status='published'";
		 $album_rs = $album_copy = $this->db->query($sql);
		 $content['albums'] =  $album_rs ;
		 
		 $content['photos'] = array();
		 if($album_id =="")
		 {
				$album_first_row =  $album_copy->row();
				$album_id = $album_first_row->album_id; 
		 }
		 $content['album_id'] =  $album_id;
		
		 
		 $sql = "select * from albums where category='Residency' and status='published' and album_id='$album_id'";
		 $album_rs =   $this->db->query($sql);
		  $album_data = $album_rs->row();
		  $content['album_title'] =  $album_data->album_title;
		  $content['album_desc'] =  $album_data->album_desc;
		 
			$photo_sql = "select * from album_photos where album_id='$album_id'  ";
			$photos = $this->db->query( $photo_sql);
			$content['photos'] =  $photos;
		 
		 $this->load->view("residencygallery",$content);
	}
	function getalbum_title( $album_id)
	{
		 $sql = "select * from albums where album_id='$album_id' ";
		 $album_rs = $album_copy = $this->db->query($sql);
		 $album_info=  $album_rs->row();
		 return  $album_info->album_title;
		
	}
	 
	 
}
