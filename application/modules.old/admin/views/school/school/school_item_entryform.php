<div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Price entry for <b><?php echo $item_details->item_name." - ".$item_details->telugu_name; ?> per KG</b>  </b></h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
         <?php   $attributes = array('class' => 'email', 'id' => 'myform');
echo form_open('admin/school/schoolitem_entryform/'.$item_id, $attributes); 	

$errors = validation_errors();
if($errors !=""){
	
?>
 <div class="validation_errors"><?php echo validation_errors(); ?>  </div>
<?php }
if(isset($item_prices[$item_details->item_id])){
	$price = $item_prices[$item_details->item_id];
}
else
	$price = '0.00';
 ?>
              <div class="box-body">
                
                
                <div class="form-group">
                  <label for="exampleInputPassword1">Price</label>
                  <input type="text"  name="price"  class="form-control"    required    id="exampleInputPassword1" placeholder="Price" value="<?php echo $price ;?>">
                  <input type="hidden"  name="action"    value="submit">
                </div>
               
              </div>
              <!-- /.box-body -->

              <div class="box-footer">
                <button type="submit" class="btn btn-primary">Submit</button>
              </div>
           <?php form_close(); ?>
          </div>