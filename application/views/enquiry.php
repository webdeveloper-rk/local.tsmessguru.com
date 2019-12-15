<?php $this->load->view('header');?>

 <div  class="body_bg">



    <table width="100%">

	   

  <tr>

    <td align="center" valign="top" background="images/main_body_bg.jpg"><table width="100%" border="0" cellspacing="0" cellpadding="0">

      <tr>

        <td align="center" class="welcome">Enquiry Us</td>

      </tr>

      <tr>

        <td align="center" valign="top"><table border="0" cellspacing="0" cellpadding="0">

            <tr>

              <td valign="top" class="bodytext"><div style="color:#FF0000;width:200px;margin:auto;"><?php echo validation_errors(); ?></div>

                  <div style="color:#0000FF;width:400px;margin:auto;font-size:15px;font-weight:bold"><?php echo $this->session->flashdata("message"); ?></div>

                <?php echo form_open('enquiry'); ?>

                  <table border="0" align="center" cellpadding="0" cellspacing="0">

                    <tr>

                      <td align="right" valign="middle" class="coursestext">Name :</td>

                      <td align="left" valign="middle">&nbsp;</td>

                      <td height="40" align="left" valign="middle"><input name="name" type="text" placeholder="Name"  style="background-color:#fff; border:#ccc solid 1px; color:#999999; width:300px; height:28px; padding-left:5px;" value="<?php echo set_value('name'); ?>"/></td>

                    </tr>

                    <tr>

                      <td align="right" valign="middle" class="coursestext">Mobile No : </td>

                      <td align="left" valign="middle">&nbsp;</td>

                      <td height="40" align="left" valign="middle"><input name="mobile" type="text"   placeholder="Mobile Number"    style="background-color:#fff; border:#ccc solid 1px; color:#999999; width:300px;  height:28px; padding-left:5px;" value="<?php echo set_value('mobile'); ?>" /></td>

                    </tr>

                    <tr>

                      <td align="right" valign="middle" class="coursestext">Email Id : </td>

                      <td align="left" valign="middle">&nbsp;</td>

                      <td height="40" align="left" valign="middle"><input name="email" type="text"    placeholder="Email address"  style="background-color:#fff; border:#ccc solid 1px; color:#999999; width:300px;  height:28px; padding-left:5px;" value="<?php echo set_value('email'); ?>" /></td>

                    </tr>

                    <tr>

                      <td align="right" valign="top" class="coursestext">Messeage : </td>

                      <td align="left" valign="middle">&nbsp;</td>

                      <td height="40" align="left" valign="middle"><textarea name="message"  placeholder="Message"   style="background-color:#fff; font-size:14px; font-family:Arial, Helvetica, sans-serif; border:#ccc solid 1px; color:#999999; width:300px;  padding-left:5px;" rows="7"><?php echo set_value('message'); ?></textarea></td>

                    </tr>

                    <tr>

                      <td align="right" valign="bottom">&nbsp;</td>

                      <td align="left" valign="bottom">&nbsp;</td>

                      <td height="30" align="left" valign="bottom"><input type="submit" name="submit" value="Submit" /></td>

                    </tr>

                    <tr>

                      <td align="right" valign="middle">&nbsp;</td>

                      <td align="left" valign="middle">&nbsp;</td>

                      <td align="left" valign="middle">&nbsp;</td>

                    </tr>

                  </table>

                </form></td>

              <td valign="top" class="bodytext"><img src="<?php echo site_url();?>assets/images/contact.png" width="251" height="250" /></td>

            </tr>

            <tr>

              <td height="100" colspan="2">&nbsp;</td>

            </tr>

        </table></td>

      </tr>

    </table></td>

  </tr>

	</table>

   

<?php $this->load->view('footer');?>