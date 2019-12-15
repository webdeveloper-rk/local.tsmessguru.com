<script src="<?php echo site_url();?>js/bootbox.min.js"></script>
<?php
$item_obj = $today_consumes[$item_id];
 
$ses_var = "session_".$session_id."_";

$old_quantity_var = $ses_var ."old_qty";
$old_price_var = $ses_var ."old_price";


$new_quantity_var = $ses_var ."new_qty";
$new_price_var = $ses_var ."new_price";

$old_qty = $item_obj->$old_quantity_var;
$old_price = $item_obj->$old_price_var;

$new_qty = $item_obj->$new_quantity_var;
$new_price = $item_obj->$new_price_var; 



$min_item_value = 1;
$max_item_value = 1000;
//echo $item_id;
$rsitem = $this->db->query("select * from items where item_id='$item_id'");
$item_min_max= $rsitem->row();
//print_a($item_min_max);
$min_item_value = $item_min_max->min_price;
$max_item_value = $item_min_max->max_price;

 $entry_id = $today_consumes[$item_id]->entry_id;
?>
<style>
.alert-success
{
	 color: #FFF;
    background-color: #4CAF50;
    border-color: #ebccd1;
}
.alert {
    padding: 15px;
    margin-bottom: 20px;
    border: 1px solid transparent;
    border-radius: 4px;
}
.new{
	display:none;
}
.recal
{
	font-weight:bold;
	color:#2C8C02;
}

</style>
<?php echo $this->session->flashdata('message');?>
	<!--<h3><span style='color:red'>Time extended for snacks and supper till 7 PM for March 8th 2017 only.</span></h3>-->
<div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title"><b><?php echo $current_session->name;?></b> - Consumption entry for <b><?php echo $item_details->item_name." - ".$item_details->telugu_name; ?></b> date of  <b><?php echo date('d-m-Y');?></b></h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
			
			<?php 
			if($data_entry_allowed == true) {
				
			
			?>
         <?php   $attributes = array('class' => 'email', 'id' => 'myform');
echo form_open('admin/school/consumption_entryform/'.$item_id."/".$session_id, $attributes); 	
 
if($this->input->post('quantity') !="")
{
	 $qty = $this->input->post('quantity');
}
	
if($this->input->post('price') !="")
{
	 $price = $this->input->post('price');
}
$errors = validation_errors();
if($errors !=""){
?>
 <div class="validation_errors"><?php echo $errors; ?>  </div>
<?php } ?>

  <input type="hidden"  name="entry_id"    value="<?php echo  base64_encode($entry_id) ;?>">
              <div class="box-body">
			   <div class="form-group">
                  <label for="exampleInputEmail1">Avilable Quantity</label>
						<span class="bold " style="color:#0000FF;font-weight:bold;font-size:18px"><?php echo $closing_quantity;?></span>&nbsp;&nbsp;KG
						 
						<br> if you have any issues with Avilable Quantity please <A class='recal' href='<?php echo site_url('admin/school/recalculate_item/'.$item_id."/".$session_id); ?>'>Click here to Recalculate </a>
                </div>
				<div class="form-group">
					<?php if($locked_check==false) { ?>
				  <label for="exampleInputEmail1" style="color:#0000FF">Is Combined Stock ?</label>
                   <label ><input type="radio" name="combined_stock" value="0"  <?php if($old_qty==0) { echo 'checked=checked'; } ?> onchange="toggle(this.value)"> No </label>
				    <label ><input type="radio" name="combined_stock" value="1" <?php  if($old_qty>0) { echo 'checked=checked'; } ?> onchange="toggle(this.value)"> Yes </label>
                </div>
					<?php } ?>
				<div class="form-group" id="oldstockform" style="display: <?php  if($old_qty==0) { echo 'none'; } ?> ">
						  <div class="form-group">
						  <label for="exampleInputEmail1"><span class='new1'>Old Stock </span>Quantity</label>
						  	<?php if($locked_check==false) { ?>
						  <input type="text" name="old_quantity" value="<?php echo $old_qty ;?>" class="form-control"  required  id="old_quantity" placeholder="Enter Quantity">
						  <?php } else {echo $old_qty;}?>
						</div>
						 <div class="form-group">
						  <label for="exampleInputEmail1"><span class='new1'>old Stock </span>Price</label>
						 
							<?php if($locked_check==false) { ?>
								
						 <input type="text" name="old_price" value="<?php echo  $old_price;?>" class="form-control"  required  id="old_price" placeholder="Enter Price">
							<?php } else {echo $old_price;}?>
						</div>
                
                </div>
                <div class="form-group">
                  <label for="exampleInputEmail1"><span class='new'>New Stock </span>Quantity</label>
                  <?php if($locked_check==false) { ?>
				  <input type="text" name="quantity" value="<?php echo $new_qty ;?>" class="form-control"  required  id="exampleInputEmail1" placeholder="Enter Quantity">
				  <?php } else {echo $qty;}?>
                </div>
				 <div class="form-group">
                  <label for="exampleInputEmail1"><span class='new'>New Stock </span>Price</label>
				   <?php if($locked_check==false) { ?>
                  <input type="text" name="price" value="<?php echo $new_price ;?>" class="form-control"  required  id="exampleInputprice" placeholder="Enter Price">
				    <?php } else {echo $price;}?>
                </div>
                
               
              </div>
              <!-- /.box-body -->
 <?php if($locked_check==false) { ?>
  <div class='notification-alert'>Note: please check the values carefully before submit, once submitted values can't be modified.</div>
              <div class="box-footer">
			  <input type="hidden"  name="action"    value="submit">
			
                <button type="submit" class="btn btn-primary">Submit</button>
              </div>
 <?php } ?>
           <?php form_close(); ?>
			<?php } 
			else{
				echo $data_entry_text;
			}
			?>
			
          </div>
		    <input type="hidden"  name="entry_id"    value="<?php echo  base64_encode($entry_id) ;?>">
			 <input type="hidden"  name="pentry_id"    value="<?php echo  $entry_id ;?>">
  
		  <script>
		  function toggle(flag)
		  {
			if(flag==1){
					$("#oldstockform").show();
					$(".new").show();
			}
			else{
					$("#oldstockform").hide();
					$(".new").hide();
			}
		  }
		  
		  
		  		$(document).ready( function () {  $(function(){
    $("#myform").submit(function(e) {       
      e.preventDefault();
		flag = validatemyfrom();
		//alert(flag);
		
		if(flag){
			 frm = document.getElementById("myform");
			
			 var message = '<h5>You are in submitting the consumption entry for the following item </h5><br>Item : <b><?php echo $item_details->item_name;?></b> <br>  ';
				  if(combined_value==1)
					{
							message =  message + "Old Quantity : <b>"+ frm.old_quantity.value + "</b> Kgs <br>Old quantity Price : <b>Rs "+frm.old_price.value +"</b>  per Kg<br>";
					}
					message =  message + "Quantity : <b>"+ frm.quantity.value + "</b> Kgs  <br> Price : <b>Rs "+frm.price.value +"</b> per Kg<br>";
					
				  total_quantity  = parseFloat(frm.quantity.value)  + parseFloat(frm.old_quantity.value);
				  
				  if(combined_value==1)
					{
						total_price = parseFloat(frm.quantity.value * frm.price.value) + parseFloat(frm.old_quantity.value * frm.old_price.value)
					}
					else {
						total_price = parseFloat(frm.quantity.value * frm.price.value) ;
					}
				 
				  message = message + "<br> Total Price : <b>Rs "+ total_price.toFixed(2);
				  message = message + " </b><br><br><span  ><h5 style='color:#ff0000;font-weight:bold;'> Are you sure to Submit ?</h5></span>";
				 
				  
				  bootbox.confirm({ 
									size: "small",
									message: message, 
									callback: function(result){  
									if(result){
										
											var dialog = bootbox.dialog({
											message: '<p class="text-center"><img src="<?php echo site_url();?>images/progress.gif"></p>',
											closeButton: false
											});
											// do something in the background
											//dialog.modal('hide');			
										sec_wait = Math.floor(Math.random() * 2) + 1  ;
											//alert(sec_wait);
											secs = sec_wait * 1000;
										setTimeout(function(){
											
											
																frm.submit();
																}, secs);
										
										
									}
									}
					});
					
			
			
			
			
		}
		
		
		
    });
});
		});
		  
		  
		  
		  function validatemyfrom(frm)
		  {
			  frm = document.getElementById("myform");
			  combined_value  = frm.combined_stock.value;
			  if(combined_value==1)
			  {
				  if(isNaN(frm.old_quantity.value))
				  {
					  alert("Please enter valid Old  stock quantity");
					  frm.old_quantity.focus();
					  return false;
				  }
			    if(isNaN(frm.old_quantity.value))
				  {
					  alert("Please enter valid   Old stock quantity");
					  frm.old_quantity.focus();
					  return false;
				  }
				  if(parseFloat(frm.old_quantity.value)==0)
				  {
					  alert("Please enter valid old stock quantity");
					  frm.old_quantity.focus();
					  return false;
				  }
				  if(parseFloat(frm.old_price.value)==0)
				  {
					  alert("Please enter valid old stock Price");
					  frm.old_price.focus();
					  return false;
				  }
				  
				  
			  }
			//  alert(frm.quantity.value);
			   if(isNaN(frm.quantity.value))
				  {
					  alert("Please enter valid   stock quantity");
					  frm.old_quantity.focus();
					  return false;
				  }
			    if(isNaN(frm.quantity.value))
				  {
					  alert("Please enter valid   stock quantity");
					  frm.quantity.focus();
					  return false;
				  }
				   if(parseFloat(frm.quantity.value)==0)
				  {
					  alert("Please enter valid   stock quantity");
					  frm.quantity.focus();
					  return false;
				  }
				  if(parseFloat(frm.price.value)==0)
				  {
					  alert("Please enter valid   stock Price");
					  frm.price.focus();
					  return false;
				  }
				  
				  
				   <?php if(isset( $min_item_value)){ ?>
							if(parseFloat(frm.price.value)<<?php echo $min_item_value;?>)
								{
									//alert("Price should not less than  <?php echo  $min_item_value;?>");
									alert("Please check the price value  ");
									frm.price.focus();
									return false;
								}
				  <?php  } ?>
				  
				  <?php if(isset( $max_item_value)){?>
							if(parseFloat(frm.price.value)><?php echo $max_item_value;?>)
								{
									//alert("Price should not exceed <?php echo  $max_item_value;?> RS");
									alert("Please check the price value  ");
									frm.price.focus();
									return false;
								}
				  <?php } ?>
				  
				 
				  
				  
				  return true;
				  
				  
				  
				  
		  }
		  </script>
		  
		  