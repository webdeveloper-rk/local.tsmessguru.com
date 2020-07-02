<div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Purchase bill upload</b>  </b></h3>
			  <a href='<?php echo site_url('purchase_bills');?>' class="btn btn-primary pull-right">View Bills</a>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
         <?php   $attributes = array('class' => 'email', 'id' => 'myform');
echo form_open_multipart(''); 	

$errors = validation_errors();
if($errors !=""){
?>
 <div class="validation_errors"><?php echo validation_errors(); ?>  </div>
<?php } 
	
	
	
?><div style='padding:10px;'><?php   echo $this->session->flashdata('message'); ?></div>
              <div class="box-body">
                <div class="form-group">
                  <label for="exampleInputEmail1">Bill Type *</label>
                  
				 <select class='form-control' name='bill_type'  required>
						<option value=''>Select Bill Type</option>
						<?php foreach($bill_types_rs->result() as $row){?>
						<option value='<?php echo $row->purchase_list_item_id;?>'><?php echo $row->item_name;?></option><?php } ?>
				  </select>
                </div>
                
                 <div class="form-group">
                  <label for="exampleInputEmail1">Bill Image *</label>
                  
				 <input type="file" name='purchase_image'   accept="image/x-png,image/jpg,image/jpeg">
                </div>
              </div>
              <!-- /.box-body -->
 

              <div class="box-footer">
			  	
                <button type="submit" class="btn btn-primary">Submit</button>
			
  </div> 
           <?php form_close(); ?>
          </div>