<?php  
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
set_time_limit(0);
 date_default_timezone_set('Asia/Kolkata');
class Captcha extends MX_Controller {

    function __construct() {
        parent::__construct();
		 
		 
	}
	function index() {
		 
							//creates a image handle
					$img = imagecreate( 250, 60 );
					 
					//choose a bg color, u can play with the rgb values
					$background = imagecolorallocate( $img,232, 0, 135 );
					 
					//chooses the text color
					$text_colour = imagecolorallocate( $img, 255, 255, 255 );
					 
					//sets the thickness/bolness of the line
					imagesetthickness ( $img, 3 );
					 
					//draws a line  params are (imgres,x1,y1,x2,y2,color)
					imageline( $img, 20, 130, 165, 130, $text_colour );
					 
					//pulls the value passed in the URL
					$text = $this->generateRandomString(rand(5,7));
					 
					// place the font file in the same dir level as the php file
					$font = 'fonts/arial.ttf';
					 
					//this function sets the font size, places to the co-ords
					imagettftext($img, 25, 0, 11, 30, $text_colour, $font, $text);
					//places another text with smaller size
					imagettftext($img, 16, 0, 10, 160, $text_colour, $font, 'Small Text');
					 
					//alerts the browser abt the type of content i.e. png image
					header( 'Content-type: image/png' );
					//now creates the image
					imagepng( $img );
					 
					//destroys used resources
					@imagecolordeallocate( $text_color );
					@imagecolordeallocate( $background );
					imagedestroy( $img );
							
	}

    
	private function generateRandomString($length = 8) {
    $characters = '123456789ABCDEFGHJKMNPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    $randomString_extraspace = '';
    for ($i = 0; $i < $length; $i++) {
		$char_choosen = $characters[rand(0, $charactersLength - 1)];
        $randomString .= $char_choosen;
        $randomString_extraspace .= " ". $char_choosen;
    }
	$this->session->set_userdata(array('user_captcha'=>$randomString));
	
    return $randomString_extraspace;
}
}
