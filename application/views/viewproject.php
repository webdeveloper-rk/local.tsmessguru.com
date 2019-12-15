<?php $this->load->view('header');?>
  <link rel="stylesheet" href="http://code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
  <!--<script src="http://code.jquery.com/jquery-1.10.2.js"></script>-->
   <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
  <script src="http://code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
    <script src="<?php echo site_url();?>assets/js/jquery.colorbox.js"></script>
	  <link rel="stylesheet" href="<?php echo site_url();?>assets/css/lightbox.min.css">
    <table>
	   <tr>
    <td align="center" background="<?php echo site_url();?>assets/images/main_body_bg.jpg"><table width="990" border="0" cellspacing="0" cellpadding="0">
      
      <tr>
        <td align="center" class="body_bg"><table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top">&nbsp;</td>
          </tr>
          <tr>
            <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td width="71%" align="center" valign="middle" bgcolor="#FFFFFF" class="contactus_heading2"><?php echo $project->title;?></td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td height="200" align="center" valign="top"><table width="99%" border="0" cellspacing="0" cellpadding="0">
              
              
              <tr>
                <td align="center" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                  
                  <tr>
                    <td width="69%" align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td><img src="<?php echo site_url();?>assets/uploads/<?php echo $project->project_pic;?>" width="100%" height="320" /></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                    </table></td>
                    <td width="31%" align="right" valign="top"><table border="0" cellspacing="0" cellpadding="0">
                          
                          <tr>
                            <td align="center" height="5"></td>
                          </tr>
                          <tr>
                            <td align="center" class="contactus_heading">PROJECT VIDEO</td>
                          </tr>
                          <tr>
                            <td align="center"><iframe width="300" height="230" src="https://www.youtube.com/embed/<?php echo $project->video_link;?>" frameborder="0" allowfullscreen></iframe></td>
                          </tr>
                          <tr>
                            <td align="center"><a href="<?php echo site_url();?>assets/uploads/files/<?php echo $project->pdf_link; ?>"><img src="<?php echo site_url();?>assets/images/pdf.png" width="300" height="44" /></a></td>
                          </tr>
                          
                          
                    </table></td>
                  </tr>
                </table></td>
              </tr>
              
              <tr>
                <td><div id="accordion">
	<h3 >Location Highlights</h3>
	<div class="bodytext2"><?php echo $project->location_highlights;?></div>
	<h3>Local Advantages</h3>
		<div class="bodytext2"><?php echo $project->local_advantages;?></div>
	<h3>Fine Features</h3>
		<div class="bodytext2"><?php echo $project->fine_features;?></div>
</div></td>
              </tr>
              <tr>
                <td align="center">&nbsp;</td>
              </tr>
              <tr>
                <td align="center" class="contactus_heading2">PROJECT GALLERY</td>
              </tr>
              <tr>
                <td align="center">
                <?php 
				foreach($project_gallery->result() as $row){				
				?>
                -<a class="fancybox-buttons" data-fancybox-group="button" href="<?php echo site_url();?>assets/uploads/projectgallery/<?php echo $row->url ?>" >
  <img src="<?php echo site_url();?>assets/uploads/projectgallery/<?php echo $row->url ?>" alt="" width="225" height="150"   class="imgborderadius"></a>
  
  
  
				<?php } ?>
 


  </td>
              </tr>
              <tr>
                <td>&nbsp;</td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
    </table></td>
  </tr>
  
	</table>
	
<!--
   <script src="<?php echo site_url();?>assets/js/lightbox-plus-jquery.min.js"></script>
   -->
   
     
  <script>
  $(function() {
    $( "#accordion" ).accordion();
  });
  </script>
 
	<?php $this->load->view('footer');?>