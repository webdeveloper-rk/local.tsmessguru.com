<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>:: MILESH MARINE EXPORTS Pvt. Ltd ::</title>




 <!-- Add jQuery library -->
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/lib/jquery-1.10.1.min.js"></script>
<link href="<?php echo base_url(); ?>public/masra.css" rel="stylesheet" type="text/css" />




<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>public/ddsmoothmenu.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>public/ddsmoothmenu-v.css" />
 
<script type="text/javascript" src="<?php echo base_url(); ?>public/ddsmoothmenu.js">

/***********************************************
* Smooth Navigational Menu- (c) Dynamic Drive DHTML code library (www.dynamicdrive.com)
* This notice MUST stay intact for legal use
* Visit Dynamic Drive at http://www.dynamicdrive.com/ for full source code
***********************************************/

</script>
<style>
#primary_nav_wrap
{
	margin-top:15px
}

#primary_nav_wrap ul
{
	list-style:none;
	position:relative;
	float:left;
	margin:0;
	padding:0
}

#primary_nav_wrap ul a
{
	display:block;
	#color:#333;
	text-decoration:none;
	#font-weight:700;
	#font-size:12px;
	line-height:32px;
	padding:0 15px;
	#font-family:"HelveticaNeue","Helvetica Neue",Helvetica,Arial,sans-serif
}

#primary_nav_wrap ul li
{
	position:relative;
	float:left;
	margin:0;
	padding:0
}

#primary_nav_wrap ul li.current-menu-item
{
	background:#ddd
}

#primary_nav_wrap ul li:hover
{
	#background:#f6f6f6
}

#primary_nav_wrap ul ul
{
	display:none;
	position:absolute;
	top:100%;
	left:0;
	#background:#fff;
	padding:0
}

#primary_nav_wrap ul ul li
{
	float:none;
	width:200px
}

#primary_nav_wrap ul ul a
{
	line-height:120%;
	padding:10px 15px
}

#primary_nav_wrap ul ul ul
{
	top:0;
	left:100%
}

#primary_nav_wrap ul li:hover > ul
{
	display:block
}
</style>

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




 

<script type="text/javascript" src="<?php echo base_url(); ?>public/fadeslideshow.js">

/***********************************************
* Ultimate Fade In Slideshow v2.0- (c) Dynamic Drive DHTML code library (www.dynamicdrive.com)
* This notice MUST stay intact for legal use
* Visit Dynamic Drive at http://www.dynamicdrive.com/ for this script and 100s more
***********************************************/

</script>
<?php
$slides_rs = $this->db->query("select * from  sliders ");
 
					?>
<script type="text/javascript">

var mygallery=new fadeSlideShow({
	wrapperid: "fadeshow1", //ID of blank DIV on page to house Slideshow
	dimensions: [970, 320], //width/height of gallery in pixels. Should reflect dimensions of largest image
	imagearray: [
				 <?php
				 foreach($slides_rs->result()  as $ban)
				 {
				 ?>
		["<?php echo base_url(); ?>/assets/uploads/files/<?php echo $ban->image; ?>", "<?php echo $ban->url; ?>", "", "<?php echo $ban->title; ?>"],
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





	<!-- Add mousewheel plugin (this is optional) -->
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/lib/jquery.mousewheel-3.0.6.pack.js"></script>

	<!-- Add fancyBox main JS and CSS files -->
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/source/jquery.fancybox.js?v=2.1.5"></script>
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/source/jquery.fancybox.css?v=2.1.5" media="screen" />

	<!-- Add Button helper (this is optional) -->
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/source/helpers/jquery.fancybox-buttons.css?v=1.0.5" />
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/source/helpers/jquery.fancybox-buttons.js?v=1.0.5"></script>

	<!-- Add Thumbnail helper (this is optional) -->
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/source/helpers/jquery.fancybox-thumbs.css?v=1.0.7" />
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/source/helpers/jquery.fancybox-thumbs.js?v=1.0.7"></script>

	<!-- Add Media helper (this is optional) -->
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/source/helpers/jquery.fancybox-media.js?v=1.0.6"></script>

	<script type="text/javascript">
		$(document).ready(function() {
			/*
			 *  Simple image gallery. Uses default settings
			 */

			$('.fancybox').fancybox();

			/*
			 *  Different effects
			 */

			// Change title type, overlay closing speed
			$(".fancybox-effects-a").fancybox({
				helpers: {
					title : {
						type : 'outside'
					},
					overlay : {
						speedOut : 0
					}
				}
			});

			// Disable opening and closing animations, change title type
			$(".fancybox-effects-b").fancybox({
				openEffect  : 'none',
				closeEffect	: 'none',

				helpers : {
					title : {
						type : 'over'
					}
				}
			});

			// Set custom style, close if clicked, change title type and overlay color
			$(".fancybox-effects-c").fancybox({
				wrapCSS    : 'fancybox-custom',
				closeClick : true,

				openEffect : 'none',

				helpers : {
					title : {
						type : 'inside'
					},
					overlay : {
						css : {
							'background' : 'rgba(238,238,238,0.85)'
						}
					}
				}
			});

			// Remove padding, set opening and closing animations, close if clicked and disable overlay
			$(".fancybox-effects-d").fancybox({
				padding: 0,

				openEffect : 'elastic',
				openSpeed  : 150,

				closeEffect : 'elastic',
				closeSpeed  : 150,

				closeClick : true,

				helpers : {
					overlay : null
				}
			});

			/*
			 *  Button helper. Disable animations, hide close button, change title type and content
			 */

			$('.fancybox-buttons').fancybox({
				openEffect  : 'none',
				closeEffect : 'none',

				prevEffect : 'none',
				nextEffect : 'none',

				closeBtn  : false,

				helpers : {
					title : {
						type : 'inside'
					},
					buttons	: {}
				},

				afterLoad : function() {
					this.title = 'Image ' + (this.index + 1) + ' of ' + this.group.length + (this.title ? ' - ' + this.title : '');
				}
			});


			/*
			 *  Thumbnail helper. Disable animations, hide close button, arrows and slide to next gallery item if clicked
			 */

			$('.fancybox-thumbs').fancybox({
				prevEffect : 'none',
				nextEffect : 'none',

				closeBtn  : false,
				arrows    : false,
				nextClick : true,

				helpers : {
					thumbs : {
						width  : 50,
						height : 50
					}
				}
			});

			/*
			 *  Media helper. Group items, disable animations, hide arrows, enable media and button helpers.
			*/
			$('.fancybox-media')
				.attr('rel', 'media-gallery')
				.fancybox({
					openEffect : 'none',
					closeEffect : 'none',
					prevEffect : 'none',
					nextEffect : 'none',

					arrows : false,
					helpers : {
						media : {},
						buttons : {}
					}
				});

			/*
			 *  Open manually
			 */

			$("#fancybox-manual-a").click(function() {
				$.fancybox.open('1_b.jpg');
			});

			$("#fancybox-manual-b").click(function() {
				$.fancybox.open({
					href : 'iframe.html',
					type : 'iframe',
					padding : 5
				});
			});

			$("#fancybox-manual-c").click(function() {
				$.fancybox.open([
					{
						href : '1_b.jpg',
						title : 'My title'
					}, {
						href : '2_b.jpg',
						title : '2nd title'
					}, {
						href : '3_b.jpg'
					}
				], {
					helpers : {
						thumbs : {
							width: 75,
							height: 50
						}
					}
				});
			});


		});
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
            <td width="64%" height="100" align="left"><a href="<?php echo base_url(); ?>"><img src="<?php echo base_url(); ?>public/images/logo text.png" width="463" height="93" border="0" /></a></td>
            <td width="36%" align="right"><img src="<?php echo base_url(); ?>public/images/requestfor_appointment.jpg" width="190" height="65" /></td>
          </tr>
        </table></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td align="center"><table width="990" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <?php   $this_page=$this->uri->segment(2); ?>
        <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td >
			 <!-- <nav id="primary_nav_wrap222">-->
			  <div id="smoothmenu1" class="ddsmoothmenu">
                   <ul>
                    <li><a href="<?php echo base_url(); ?>" <?php if($this_page=="home"  )echo 'class="selected"'; ?>>Home</a></li>
                    <li><a href="<?php echo base_url(); ?>content/aboutus" <?php if($this_page=="about_us"  )echo 'class="selected"'; ?>>About Us</a></li>
                    <li><a href="<?php echo base_url(); ?>" <?php if($this_page=="our_vision"  )echo 'class="selected"'; ?>>Achivements </a>
							<ul>
							<?php 
							$menurs = $this->db->query("select * from  cms_achivement where 	category='achivements'");
							foreach($menurs->result() as $mrow){
							?>
							<li><a href="<?php echo base_url(); ?>content2/<?php echo $mrow->url; ?>" <?php if($this_page=="our_vision"  )echo 'class="selected"'; ?>><?php echo $mrow->title;?> </a>
							<?php } ?>
							</ul>
							
					</li>
                     <li><a href="<?php echo base_url(); ?>" <?php if($this_page=="our_mission"  )echo 'class="selected"'; ?>>Quality </a>
					<ul>
							<?php 
							$menurs = $this->db->query("select * from  cms_achivement where 	category='quality'");
							foreach($menurs->result() as $mrow){
							?>
							<li><a href="<?php echo base_url(); ?>content2/<?php echo $mrow->url; ?>" <?php if($this_page=="our_vision"  )echo 'class="selected"'; ?>><?php echo $mrow->title;?> </a>
							<?php } ?>
							</ul>

					 </li>
                    
                    
                    <li><a href="<?php echo base_url(); ?>content/careers" <?php if($this_page=="donate"  )echo 'class="selected"'; ?>>Careers</a></li>
                    <li><a href="<?php echo base_url(); ?>products" <?php if($this_page=="photos")echo 'class="selected"'; ?>>Products</a></li>
                    
                    <li><a href="<?php echo base_url(); ?>products/videos" <?php if($this_page=="videos" )echo 'class="selected"'; ?>>Videos</a></li>
    
     <li><a href="<?php echo base_url(); ?>content/assistance" <?php if($this_page=="aims_objectives"  )echo 'class="selected"'; ?>>Assistance</a></li>                
                    <li><a href="<?php echo base_url(); ?>content/contactus" <?php if($this_page=="contactus"  )echo 'class="selected"'; ?>>Contact Us</a></li>
                  </ul>
                <br style="clear: left" />
              </div>
			  <!--</nav>-->
			  </td>
            </tr>
			<tr><td style="padding:5px;background-color:#FFF"><div id="fadeshow1" style="z-index:2;"></div></td></tr>
        </table></td>
      </tr>
    </table></td>
  </tr>
</table>

