<?php $this->load->view('header');?>
		
		



 <table width="990" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td align="center" class="bodyshde"><table width="100%" border="0" cellpadding="0" cellspacing="0">
              <tr>
                <td align="center"><div id="fadeshow1" style="z-index:2;"></div></td>
              </tr>
              <tr>
                <td align="center" valign="top"><img src="<?php echo base_url(); ?>public/images/shade1.jpg" width="970" height="26" /></td>
              </tr>
              <tr>
                <td align="left"  class="page_links2">Products</td>
              </tr>
              <tr>
                <td height="500" align="left" valign="top" class="normaltext1"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td align="left" valign="top">&nbsp;</td>
                    <td align="right" valign="top">&nbsp;</td>
                  </tr>
                  <tr>
                    <td width="23%" align="left" valign="top"><table width="700" border="0" cellspacing="0" cellpadding="0">
                        
                        <tr>
                          <td>
                          <?php
						  
						  if(1) {
						   
						   foreach($photos->result() as $row) 
						  {
						  ?>
                          <a class="fancybox-buttons" data-fancybox-group="button"  href="<?php echo site_url();?>assets/uploads/gallery/<?php echo $row->url;?>">
                          <img src="<?php echo site_url();?>assets/uploads/gallery/<?php echo $row->url;?>"  alt="" width="220" height="130" / class="imgborderadius" />
						    
						  </a>
                          <?php } } else { ?><div style="color:#F00;font-size:20px;"><?php echo "No Results Found"; } ?></div>                         </td>
                        </tr>
                    </table></td>
                    <td width="77%" align="right" valign="top"><table width="250" border="0" cellspacing="2" cellpadding="2">
                        <tr>
                          <td align="left" valign="top"><ul class="slidedoormenu">
                          
						   <?php foreach($albums->result() as $row) {    
						  ?>
                              <li><a href="<?php echo site_url('products/gallery/'.$row->album_id);?>"><?php echo  $row->album_title; ?></a></li>
                              <?php } ?>
                              
                          </ul></td>
                        </tr>
                    </table></td>
                  </tr>
                </table></td>
              </tr>
              <tr>
                <td align="center" bgcolor="#255D83"><table width="98%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td height="50" align="center" class="whitecolor">&copy; 2015-2016  Milesh Marines &amp; Exports Pvt Ltd | ALL RIGHTS RESERVED </td>
                    </tr>
                </table></td>
              </tr>
              
    </table></td>
  </tr>
  <tr>
    <td align="center">&nbsp;</td>
  </tr>
</table>
