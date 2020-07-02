<div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">DPC rate for <b><?php echo $item_details->item_name." - ".$item_details->telugu_name; ?></b></b></h3>
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
                
                <div class="form-group">
                  <label for="exampleInputPassword1">Price</label>
                    
				  <input type="text"  name="price"  class="form-control"     id="exampleInputPassword1" required placeholder="Price" value="<?php echo $dpc_details->amount;?>">
				  <input type="hidden" name='action' value='submit'>
                  
                </div>
                   
              <div class="box-footer">
                <button type="submit" class="btn btn-primary">Submit</button>
              </div>
               
              </div>
              <!-- /.box-body -->
			  
           <?php form_close(); ?>
          </div>