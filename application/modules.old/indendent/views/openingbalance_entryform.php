<div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Opening Balance entry for <b><?php echo $item_data->item_name." - ".$item_data->telugu_name; ?></b>  </b></h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
         <?php   $attributes = array('class' => 'email', 'id' => 'myform');
echo form_open('', $attributes); 	

$errors = validation_errors();
if($errors !=""){
?>
 <div class="validation_errors"><?php echo validation_errors(); ?>  </div>
<?php } ?>
              <div class="box-body">
                <div class="form-group">
                  <label for="exampleInputEmail1">Opening balance Quantity</label>
                  <?php if($allowed_to_update>0){?>
				  <input type="text" name="quantity" value="<?php echo $bs_data->opening_quantity; ?>" class="form-control"  required   id="exampleInputEmail1" placeholder="Enter Quantity">
				  <?php } else {    echo $bs_data->opening_quantity;}
				  ?>
                </div>
                <div class="form-group">
                   <label for="exampleInputPassword1">Price</label> 
               <?php if($allowed_to_update>0){?>
			   <input type="text"  readonly name="price"  class="form-control"    required    id="exampleInputPassword1" placeholder="Price" value="<?php echo $item_price;?>">
                  <input type="hidden"   name="action"    value="submit">
				   <?php } else {    echo $bs_data->opening_price;; } ?> 
				   
                </div>
               
              </div>
              <!-- /.box-body -->
  <?php if($allowed_to_update>0){?>

              <div class="box-footer">
			  	
                <button type="submit" class="btn btn-primary">Submit</button>
			
  </div><?php } ?>
           <?php form_close(); ?>
          </div>