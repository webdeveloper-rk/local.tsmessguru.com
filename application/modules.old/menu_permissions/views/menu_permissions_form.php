<script src="<?php echo site_url();?>js/bootbox.min.js"></script> 
<style>
.top_menu
{
font-weight: normal;
margin-right:20px;
}

</style>         
<div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Menu Permissions for <b><?php echo $role_title;?></b></h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
         <?php   $attributes = array('class' => 'email', 'id' => 'myform');
echo form_open('', $attributes); 	
$errors = validation_errors();
if($errors !=""){
?>
 <div class="validation_errors"><?php echo $errors; ?>  </div>
<?php } ?>
              <div class="box-body">
			  <div><a href="<?php echo site_url("menu_permissions");?>" class="btn btn-primary pull-right">Back</a></div>
			  <div><?php echo $this->session->flashdata('message');?></div>
                <div class="form-group">
	<table>
				<?php foreach($main_menu as $menu_item) { ?>
						<tr>
						<td style="padding:10px;" valign="top">
						<input type="checkbox"  <?php if(in_array($menu_item->menu_id,$selected_menus_list)) { echo " checked=checked "; } ?> name="permission_ids[]" value="<?php echo $menu_item->menu_id;?>" id="lbl_<?php echo $menu_item->menu_id;?>"></td>
							
							<td style="padding:10px;">
									<label class='top_menu' for="lbl_<?php echo $menu_item->menu_id;?>"> <?php echo $menu_item->menu_title;?>- [<?php echo ucfirst($menu_item->menu_for);?>] - <?php echo $menu_item->menu_id;?>
										</label>
												<?php 
												if(isset($menus_sub_list[$menu_item->menu_id])){ ?>
																<table>
																	<?php foreach($menus_sub_list[$menu_item->menu_id] as $menu_item2) { ?>
																				<tr><!--Level 2 Menu -->
																					<td style="padding:10px;" valign="top">
																					
										 <input type="checkbox" <?php if(in_array($menu_item2->menu_id,$selected_menus_list)) { echo " checked=checked "; } ?> name="permission_ids[]" value="<?php echo $menu_item2->menu_id;?>" id="lbl_<?php echo $menu_item2->menu_id;?>"></td>
								<td style="padding:10px;" valign="top">
									<label class='top_menu' for="lbl_<?php echo $menu_item2->menu_id;?>"> <?php echo $menu_item2->menu_title;?>- [<?php echo ucfirst($menu_item2->menu_for);?>]  <?php echo $menu_item2->menu_id;?>
										</label>
											
											
											
											<!-- LEVEL 3  -->
											
											<?php 
												if(isset($menus_sub_list[$menu_item2->menu_id])){ ?>
																<table>
																	<?php foreach($menus_sub_list[$menu_item2->menu_id] as $menu_item3) { ?>
																				<tr><!--Level 2 Menu -->
																					<td style="padding:10px;" valign="top">
																					
						 <input type="checkbox"  <?php if(in_array($menu_item3->menu_id,$selected_menus_list)) { echo " checked=checked "; } ?>  name="permission_ids[]" value="<?php echo $menu_item3->menu_id;?>" id="lbl_<?php echo $menu_item3->menu_id;?>"></td>
								<td style="padding:10px;" valign="top">
									<label class='top_menu' for="lbl_<?php echo $menu_item3->menu_id;?>"> <?php echo $menu_item3->menu_title;?>- [<?php echo ucfirst($menu_item3->menu_for);?>] 
										</label>
											
																					</td>
																				</tr>
																	<?php } ?>
																		</table>
																		
												<?php } ?>
											
											<!-- END LEVEL 3 -->
																					</td>
																				</tr>
																	<?php } ?>
																		</table>
																		
												<?php } ?>
								</td><!--Level 1 -->
						</tr>
				<?php }?>
		</table>
				 
				    <div class="box-footer">
                <button type="submit" class="btn btn-primary">Submit</button>
              </div>
			  </form>
				 </div>
				 </div>
				 
				 </div>