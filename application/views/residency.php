<?php $this->load->view('header');?>
		
		



 <div  class="body_bg">







    <table width="100%" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td align="center" bgcolor="#FFFFFF">&nbsp;</td>
      </tr>
      <tr>
        <td align="center" class="welcome"><?php echo $album_title;?> </td>
      </tr>
      <tr>
        <td align="center" background="<?php echo site_url();?>assets/images/main_body_bg.jpg"><table width="100%" border="0" cellspacing="0" cellpadding="0">
          
          <tr>
            <td align="center" valign="top" ><table border="0" cellpadding="0" cellspacing="0">
                <tr>
                  <td align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                              <td class="welcome">&nbsp;</td>
                            </tr>
                          
                            <tr>
                              <td align="left" valign="top">
							  
							  
							  
							  <?php    foreach($photos->result() as $row) {   ?>
                                  <a class="fancybox-buttons" data-fancybox-group="button" href="<?php echo site_url();?>assets/uploads/files/<?php echo $row->image;?>" > <img src="<?php echo site_url();?>assets/uploads/files/<?php echo $row->image;?>" alt="" width="200" height="150" / class="imgborderadius" /></a>
                                  <?php } ?>                              </td>
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
        </table></td>
      </tr>
	</table>
</div>
    
<?php $this->load->view('footer');?>