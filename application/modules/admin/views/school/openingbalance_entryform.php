<div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Opening Balance entry for <b><?php echo $item_details->item_name." - ".$item_details->telugu_name; ?></b>  </b></h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
         <?php   $attributes = array('class' => 'email', 'id' => 'myform');
echo form_open('admin/school/openingbalance_entryform/'.$item_id, $attributes); 	

$errors = validation_errors();
if($errors !=""){
?>
 <div class="validation_errors"><?php echo validation_errors(); ?>  </div>
<?php } ?>
              <div class="box-body">
                <div class="form-group">
                  <label for="exampleInputEmail1">Opening balance Quantity</label>
                  <?php if($allowed_to_modify>0){?>
				  <input type="text" name="quantity" value="<?php echo $initial_amounts['qty']; ;?>" class="form-control"  required   id="exampleInputEmail1" placeholder="Enter Quantity">
				  <?php } else {    echo $initial_amounts['qty'];}
				  ?>
                </div>
                <div class="form-group">
                  <label for="exampleInputPassword1">Price</label>
               <?php if($allowed_to_modify>0){?>
			   <input type="text"  name="price"  class="form-control"    required    id="exampleInputPassword1" placeholder="Price" value="<?php echo $initial_amounts['price'];?>">
                  <input type="hidden"  name="action"    value="submit">
				   <?php } else {    echo $initial_amounts['price']; } ?> 
				   
                </div>
               
              </div>
              <!-- /.box-body -->
  <?php if($allowed_to_modify>0){?>
  <div class='notification-alert'>Note: please check the values carefully before submit, once submitted values can't be modified.</div>
              <div class="box-footer">
			  	
                <button type="submit" class="btn btn-primary">Submit</button>
			
  </div><?php } ?>
           <?php form_close(); ?>
          </div>