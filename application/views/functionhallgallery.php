<?php $this->load->view('header');?>
		
		




 <div  class="body_bg">







    <table width="100%" border="0" cellpadding="0" cellspacing="0">
      
      <tr>
        <td align="center" background="<?php echo site_url();?>assets/images/main_body_bg.jpg"><table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td align="center" class="welcome"><?php echo $album_title;?> - Photo Gallery</td>
          </tr>
          <tr>
            <td align="center" valign="top" ><table border="0" cellpadding="0" cellspacing="0">
                <tr>
                  <td align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td align="center"><table width="85%" border="0" cellspacing="0" cellpadding="0">
                            
                            
							<tr>
							  <td colspan="2"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                  <td valign="top" bgcolor="#FFFFFF"><table width="100%" border="0" cellpadding="0" cellspacing="0" class="bodyshde1">
                                    <tr>
                                      <td class="leftmenu">Facilities</td>
                                    </tr>
                                    <tr>
                                      <td valign="top"><div class="leftColumn">
                                          <?php foreach($albums->result() as $row) {   ?>
                                          <a href="<?php echo site_url('photogallery/functionhall/'.$row->album_id);?>"><?php echo  $row->album_title; ?></a>
                                          <?php } ?>
                                      </div></td>
                                    </tr>
                                  </table></td>
                                  <td align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                    <tr>
                                      <td bgcolor="#FFFFFF" class="bodytext" style="font-size:14px; line-height:23px;"><?php echo  $album_desc; ?></td>
                                    </tr>
                                    <tr>
                                      <td bgcolor="#FFFFFF"  style="font-size:14px; line-height:23px; padding:5px;"><?php    foreach($photos->result() as $row) {    ?>
						      <a class="fancybox-buttons" data-fancybox-group="button" href="<?php echo site_url();?>assets/uploads/gallery/<?php echo $row->url;?>" ><img src="<?php echo site_url();?>assets/uploads/gallery/<?php echo $row->url;?>" alt="" width="250" height="150" / class="imgborderadius" /></a><a class="fancybox-buttons" data-fancybox-group="button" href="<?php echo site_url();?>assets/uploads/gallery/<?php echo $row->url;?>" ></a><?php } ?>    </td>
                                    </tr>
                                  </table></td>
                                </tr>
                              </table>						      </td>
						    </tr>
							
                            
                        </table></td>
                      </tr>
                  </table></td>
                </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
	</table>
    </div>
    
    
<?php $this->load->view('footer');?>