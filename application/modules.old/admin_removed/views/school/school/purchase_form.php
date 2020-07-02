<div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Purchase entry for <b><?php echo $item_details->item_name." - ".$item_details->telugu_name; ?></b> date of  <b><?php echo date('d-m-Y');?></b></h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
         <?php   $attributes = array('class' => 'email', 'id' => 'myform');
echo form_open('admin/school/purchase_entryform/'.$item_id, $attributes); 	
$errors = validation_errors();
if($errors !=""){
?>
 <div class="validation_errors"><?php echo $errors; ?>  </div>
<?php } ?>
              <div class="box-body">
                <div class="form-group">
                  <label for="exampleInputEmail1">Quantity</label>
                  <?php if($allow_to_modify==true){ ?>
				  <input type="text" name="quantity" value="<?php echo $today_purchases['qty'] ;?>" class="form-control" required  id="exampleInputEmail1" placeholder="Enter Quantity">
				  <?php } else{ echo  $today_purchases['qty'];} ?>
                </div>
                <div class="form-group">
                  <label for="exampleInputPassword1">Price</label>
                   <?php if($allow_to_modify){ ?>
				  <input type="text"  name="price"  class="form-control"     id="exampleInputPassword1" required placeholder="Price" value="<?php echo $today_purchases['price'];?>">
				  
                  <input type="hidden"  name="action"    value="submit">
				  <?php } else{ echo  $today_purchases['price'];} ?>
                </div>
                <div class="form-group">
                  <label for="exampleInputPassword1">Bill Number</label>
                   <?php if($allow_to_modify){ ?>
				  <input type="text"  name="billno"  class="form-control"     id="exampleInputPassword1"   placeholder="Enter Bill Number" value="<?php echo $today_purchases['purchase_biil_no'];?>">
				  
                  <input type="hidden"  name="action"    value="submit">
				  <?php } else{ echo  $today_purchases['price'];} ?>
                </div>
               
              </div>
              <!-- /.box-body -->
			 <?php if($allow_to_modify){ ?>
			 <div class='notification-alert'>Note: please check the values carefully before submit, once submitted values can't be modified.</div>
              <div class="box-footer">
                <button type="submit" class="btn btn-primary">Submit</button>
              </div>
			 <?php } ?>
           <?php form_close(); ?>
          </div>