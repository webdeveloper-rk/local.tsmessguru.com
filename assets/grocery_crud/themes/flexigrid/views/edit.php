<?php

	$this->set_css($this->default_theme_path.'/flexigrid/css/flexigrid.css');

    $this->set_js_lib($this->default_javascript_path.'/jquery_plugins/jquery.form.min.js');
	$this->set_js_config($this->default_theme_path.'/flexigrid/js/flexigrid-edit.js');

	$this->set_js_lib($this->default_javascript_path.'/jquery_plugins/jquery.noty.js');
	$this->set_js_lib($this->default_javascript_path.'/jquery_plugins/config/jquery.noty.config.js');
?>
<?php echo form_open( $update_url, 'method="post" id="crudForm"  enctype="multipart/form-data"'); ?>
<div class="flexigrid crud-form" style='width: 100%;' data-unique-hash="<?php echo $unique_hash; ?>">
<div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title"><?php echo $this->l('form_edit'); ?> <?php echo $subject?></h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form class="form-horizontal">
              <div class="box-body">
               

<?php
		$counter = 0;
			foreach($fields as $field)
			{
				$even_odd = $counter % 2 == 0 ? 'odd' : 'even';
				$counter++;
		?>
			   <div class="form-group">
                  <label   class="col-sm-2 control-label"><?php echo $input_fields[$field->field_name]->display_as?><?php echo ($input_fields[$field->field_name]->required)? "<span class='required'>*</span> " : ""?> :</label>

                  <div class="col-sm-10">
                   <?php echo $input_fields[$field->field_name]->input?>
                  </div>
                </div>
				<div class='clear'></div><br>
               <?php }?>
		<?php if(!empty($hidden_fields)){?>
		<!-- Start of hidden inputs -->
			<?php
				foreach($hidden_fields as $hidden_field){
					echo $hidden_field->input;
				}
			?>
		<!-- End of hidden inputs -->
		<?php }?>
		<?php if ($is_ajax) { ?><input type="hidden" name="is_ajax" value="true" /><?php }?>
		<div id='report-error' class='report-div error'></div>
		<div id='report-success' class='report-div success'></div>
                
              </div>
              <!-- /.box-body -->
              <div class="box-footer">
			  <?php 	if(!$this->unset_back_to_list) { ?>
		 <div class='form-button-box'>
			<input type='button' value='<?php echo $this->l('form_update_and_go_back'); ?>' id="save-and-go-back-button" class="btn btn-info pull-right"/></div>
	 
		 <div class='form-button-box'>
			<input type='button' value='<?php echo $this->l('form_cancel'); ?>'class="btn btn-info pull-right" id="cancel-button" /></div>
		 
<?php 	} ?>
		 
			<div class='small-loading' id='FormLoading'><?php echo $this->l('form_update_loading'); ?></div>
		 
		
		
                
              </div><div class='clear'></div>
              <!-- /.box-footer -->
             
</div> 
	
 <?php echo form_close(); ?>
<script>
	var validation_url = '<?php echo $validation_url?>';
	var list_url = '<?php echo $list_url?>';

	var message_alert_edit_form = "<?php echo $this->l('alert_edit_form')?>";
	var message_update_error = "<?php echo $this->l('update_error')?>";
</script>