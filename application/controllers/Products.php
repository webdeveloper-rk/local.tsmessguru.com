<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Products extends CI_Controller {

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
		echo "hi";
die;		
		 redirect('Products/gallery');
	}
	public function gallery($album_id)
	{
		
				
			 
			 
		$sql = "select * from  albums where category='products' and status='published'";
		 $album_rs = $album_copy = $this->db->query($sql);
		 $content['albums'] =  $album_rs ;
		 
		 $content['photos'] = array();
		 if($album_id =="")
		 {
				$album_first_row =  $album_copy->row();
				$album_id = $album_first_row->album_id; 
		 }
		 $content['album_id'] =  $album_id;
		  $sql = "select * from albums  where category='products' and status='published' and album_id='$album_id'";
		 $album_rs =   $this->db->query($sql);
		  $album_data = $album_rs->row();
		  $content['album_title'] =  $album_data->album_title;
		  $content['album_desc'] =  $album_data->album_desc; 
		 
		 
			$photo_sql = "select * from album_photos where album_id='$album_id'  ";
			$photos = $this->db->query( $photo_sql);
			$content['photos'] =  $photos;
		 
		 $this->load->view("product_gallery",$content);
	}
	public function videos($album_id='')
	{
		 $sql = "select * from video_albums where  1";
		 $album_rs = $album_copy = $this->db->query($sql);
		 $content['albums'] =  $album_rs ; 
		 
		 if($album_id == '') {
				 $sql = "select * from video_albums ";
				$album_rs =   $this->db->query($sql);
				$album_data = $album_rs->row();
				$album_id =  $album_data->va_id ;
		 }
		 
		$sql = "select * from video_albums  where va_id='$album_id'";
		$album_rs =   $this->db->query($sql);
		$album_data = $album_rs->row();
		$content['album_title'] =  $album_data->title;
		  
		 
			$video_sql = "select * from videos where va_id='$album_id'  ";
			$videos = $this->db->query( $video_sql);
			$content['videos'] =  $videos;
		 
		 $this->load->view("video_gallery",$content);
	}
	function getalbum_title( $album_id)
	{
		 $sql = "select * from albums where album_id='$album_id' ";
		 $album_rs = $album_copy = $this->db->query($sql);
		 $album_info=  $album_rs->row();
		 return  $album_info->album_title;
		
	}
	 
	 
}
