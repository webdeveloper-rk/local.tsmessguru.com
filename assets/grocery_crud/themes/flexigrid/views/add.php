<?php

	$this->set_css($this->default_theme_path.'/flexigrid/css/flexigrid.css');
	$this->set_js_lib($this->default_theme_path.'/flexigrid/js/jquery.form.js');
    $this->set_js_lib($this->default_javascript_path.'/jquery_plugins/jquery.form.min.js');
	$this->set_js_config($this->default_theme_path.'/flexigrid/js/flexigrid-add.js');

	$this->set_js_lib($this->default_javascript_path.'/jquery_plugins/jquery.noty.js');
	$this->set_js_lib($this->default_javascript_path.'/jquery_plugins/config/jquery.noty.config.js');
?>
<div class="flexigrid crud-form" style='width: 100%;' data-unique-hash="<?php echo $unique_hash; ?>">

<div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title"><?php echo $this->l('form_add'); ?> <?php echo $subject?></h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
             
			<?php echo form_open( $insert_url, 'method="post" id="crudForm"  class="form-horizontal" enctype="multipart/form-data"'); ?>
              <div class="box-body">
			  
			  <?php
			$counter = 0;
				foreach($fields as $field)
				{
					$even_odd = $counter % 2 == 0 ? 'odd' : 'even';
					$counter++;
			?>
                <div class="form-group">
                  <label for="<?php echo $field->field_name;?>" class="col-sm-2 control-label"><?php echo $input_fields[$field->field_name]->display_as; ?><?php echo ($input_fields[$field->field_name]->required)? "<span class='required'>*</span> " : ""; ?> :</label>

                  <div class="col-sm-10">
                    <?php echo $input_fields[$field->field_name]->input?>
                  </div>
                </div>
				
				<?php }?>
			<!-- Start of hidden inputs -->
				<?php
					foreach($hidden_fields as $hidden_field){
						echo $hidden_field->input;
					}
				?>
			<!-- End of hidden inputs -->
			<?php if ($is_ajax) { ?><input type="hidden" name="is_ajax" value="true" /><?php }?>

			<div id='report-error' class='report-div error'></div>
			<div id='report-success' class='report-div success'></div>
               
			 <div style="width:70%;margin:auto;">
					<!--<div class='form-button-box'>
						<input type='submit' value='<?php echo $this->l('form_save'); ?>' id="save-and-go-back-button"  class="btn btn-info pull-right"/>
					</div>-->
		<?php 	if(!$this->unset_back_to_list) { ?>
					<div class='form-button-box'>
						<input type='submit' value='<?php echo $this->l('form_save_and_go_back'); ?>' id="save-and-go-back-button"  class="btn btn-info pull-right"/>
					</div>
					<div class='form-button-box'>
						<input type='submit' value='<?php echo $this->l('form_cancel'); ?>'class="btn btn-info pull-right" id="cancel-button" />
					</div>
		<?php 	} ?>
		</div>
			<div class='form-button-box'>
				<div class='small-loading' id='FormLoading'><?php echo $this->l('form_insert_loading'); ?></div>
			</div>
			<div class='clear'></div>
                
              
			  <?php echo form_close(); ?>
              <!-- /.box-body -->
             
              <!-- /.box-footer -->
             
          </div>
 
 
</div>
</div>
<script>
	var validation_url = '<?php echo $validation_url?>';
	var list_url = '<?php echo $list_url?>';

	var message_alert_add_form = "<?php echo $this->l('alert_add_form')?>";
	var message_insert_error = "<?php echo $this->l('insert_error')?>";
</script>