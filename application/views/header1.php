<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>:: MILESH MARINE EXPORTS Pvt. Ltd ::</title>





<link href="<?php echo base_url(); ?>public/masra.css" rel="stylesheet" type="text/css" />




<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>public/ddsmoothmenu.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>public/ddsmoothmenu-v.css" />

<script type="text/javascript" src="<?php echo base_url(); ?>public/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>public/ddsmoothmenu.js">

/***********************************************
* Smooth Navigational Menu- (c) Dynamic Drive DHTML code library (www.dynamicdrive.com)
* This notice MUST stay intact for legal use
* Visit Dynamic Drive at http://www.dynamicdrive.com/ for full source code
***********************************************/

</script>

<script type="text/javascript">

ddsmoothmenu.init({
	mainmenuid: "smoothmenu1", //menu DIV id
	orientation: 'h', //Horizontal or vertical menu: Set to "h" or "v"
	classname: 'ddsmoothmenu', //class added to menu's outer DIV
	//customtheme: ["#1c5a80", "#18374a"],
	contentsource: "markup" //"markup" or ["container_id", "path_to_menu_file"]
})

ddsmoothmenu.init({
	mainmenuid: "smoothmenu2", //Menu DIV id
	orientation: 'v', //Horizontal or vertical menu: Set to "h" or "v"
	classname: 'ddsmoothmenu-v', //class added to menu's outer DIV
	//customtheme: ["#804000", "#482400"],
	contentsource: "markup" //"markup" or ["container_id", "path_to_menu_file"]
})

</script>




<script type="text/javascript" src="<?php echo base_url(); ?>public/jquery.min.js"></script>

<script type="text/javascript" src="<?php echo base_url(); ?>public/fadeslideshow.js">

/***********************************************
* Ultimate Fade In Slideshow v2.0- (c) Dynamic Drive DHTML code library (www.dynamicdrive.com)
* This notice MUST stay intact for legal use
* Visit Dynamic Drive at http://www.dynamicdrive.com/ for this script and 100s more
***********************************************/

</script>

<script type="text/javascript">

var mygallery=new fadeSlideShow({
	wrapperid: "fadeshow1", //ID of blank DIV on page to house Slideshow
	dimensions: [970, 320], //width/height of gallery in pixels. Should reflect dimensions of largest image
	imagearray: [
				 <?php
				 foreach($view_banner as $ban)
				 {
				 ?>
		["<?php echo base_url(); ?>public/banner/<?php echo $ban->image; ?>", "<?php echo $ban->link; ?>", "", "<?php echo $ban->name; ?>"],
		<?php } ?>
		 //<--no trailing comma after very last image element!
	],
	displaymode: {type:'auto', pause:2500, cycles:0, wraparound:false},
	persist: false, //remember last viewed slide and recall within same session?
	fadeduration: 500, //transition duration (milliseconds)
	descreveal: "ondemand",
	togglerid: ""
})



</script>







<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>public/featuredcontentglider.css" />

<script type="text/javascript" src="<?php echo base_url(); ?>public/featuredcontentglider.js">

/***********************************************
* Featured Content Glider script- (c) Dynamic Drive DHTML code library (www.dynamicdrive.com)
* Visit http://www.dynamicDrive.com for hundreds of DHTML scripts
* This notice must stay intact for legal use
***********************************************/

</script>



<script type="text/javascript" src="<?php echo base_url(); ?>public/crawler.js"></script>
</head>

<body>
<!-- AddThis Pro BEGIN -->
<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-539999092f719469"></script>
<!-- AddThis Pro END -->
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td height="5" align="center" bgcolor="#255D83"></td>
  </tr>
  <tr>
    <td height="120" align="center" valign="middle" bgcolor="#FFFFFF"><table width="990" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="64%" height="100" align="left"><img src="<?php echo base_url(); ?>public/images/logo text.png" width="463" height="93" border="0" /></td>
            <td width="36%" align="right"><img src="<?php echo base_url(); ?>public/images/requestfor_appointment.jpg" width="190" height="65" /></td>
          </tr>
        </table></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td align="center"><table width="990" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <?php $this_page=$this->uri->segment(1); ?>
        <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td ><div id="smoothmenu1" class="ddsmoothmenu">
                   <ul>
                    <li><a href="<?php echo base_url(); ?>home" <?php if($this_page=="home"  )echo 'class="selected"'; ?>>Home</a></li>
                    <li><a href="<?php echo base_url(); ?>about_us" <?php if($this_page=="about_us"  )echo 'class="selected"'; ?>>About Us</a></li>
                    <li><a href="<?php echo base_url(); ?>our_vision" <?php if($this_page=="our_vision"  )echo 'class="selected"'; ?>>Achivements </a> </li>
                     <li><a href="<?php echo base_url(); ?>our_mission" <?php if($this_page=="our_mission"  )echo 'class="selected"'; ?>>Quality </a> </li>
                    
                    
                    <li><a href="<?php echo base_url(); ?>donate" <?php if($this_page=="donate"  )echo 'class="selected"'; ?>>Careers</a></li>
                    <li><a href="<?php echo base_url(); ?>photos" <?php if($this_page=="photos")echo 'class="selected"'; ?>>Products</a></li>
                    
                    <li><a href="<?php echo base_url(); ?>videos" <?php if($this_page=="videos" )echo 'class="selected"'; ?>>Videos</a></li>
    
     <li><a href="<?php echo base_url(); ?>aims_objectives" <?php if($this_page=="aims_objectives"  )echo 'class="selected"'; ?>>Assistance</a></li>                
                    <li><a href="<?php echo base_url(); ?>contactus" <?php if($this_page=="contactus"  )echo 'class="selected"'; ?>>Contact Us</a></li>
                  </ul>
                <br style="clear: left" />
              </div></td>
            </tr>
        </table></td>
      </tr>
    </table></td>
  </tr>
</table>

