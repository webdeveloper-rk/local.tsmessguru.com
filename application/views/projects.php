<?php $this->load->view('header');?>

    <table width="100%">
	  <tr>
    <td align="center" background="<?php echo site_url();?>images/main_body_bg.jpg"><table width="990" border="0" cellspacing="0" cellpadding="0">
      
      <tr>
        <td align="center" class="body_bg"><table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td width="71%" align="left" valign="middle" class="welcome"><?php echo $title;?></td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td height="200" align="center" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td align="center"><table width="98%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <?php 
					$i = 1;
					foreach($projects->result() as $row) { ?>
					
					<td valign="top"><table width="170" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td align="center"><a href="<?php echo site_url('viewproject/'.$row->project_id);?>"><img src="<?php echo site_url();?>assets/uploads/<?php echo $row->project_pic;?>" width="278" height="200" / class="img_border" /></a></td>
                      </tr>
                      <tr>
                        <td align="center" bgcolor="#007FEA" class="slidemenuh1" style="padding:5px;"><?php echo $row->title;?></td>
                      </tr>
                    </table></td>
                    <?php 
					if($i%3==0){echo "</tr><tr>";}
					$i++;
					} ?>
                    
                  </tr>
                </table></td>
              </tr>
              
              <tr>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td height="200">&nbsp;</td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
    </table></td>
  </tr>
	</table>
<?php $this->load->view('footer');?>