<script src="<?php echo site_url();?>js/bootbox.min.js"></script>
 
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

.box.box-primary {
    border-top-color: #3c8dbc;
    padding: 10px;
}
</style>
<?php echo $this->session->flashdata('message');?>
	<!--<h3><span style='color:red'>Time extended for snacks and supper till 7 PM for March 8th 2017 only.</span></h3>-->
<div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title"><b><?php echo $vendor_info->vendor_name;?> - Mapping Items </b></h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
 
  
  <div class="box-body">
  
   <?php   $attributes = array('class' => 'email', 'id' => 'myform','action'=>site_url().'vendors_apsocial/ajax_map_items/'.$vendor_annapurna_id);
echo form_open_multipart('', $attributes); ?>
     
	<div class="form-group" id="oldstockform" >
						  <div class="form-group">
							<h3><?php echo $vendor_info->vendor_name;?> - Mapping Items </h3>
						</div>
					 
                
                </div> 
				
				<?php foreach($items->result() as $row_item){ ?>
				 
						  <div class="form-group">
						  <?php
							$checked  = '';
							if(in_array( $row_item->item_id,$mapped_items)){ $checked  = " checked  ";} 
						  ?>
						   
						  <input type="checkbox" name="items[]" value="<?php echo $row_item->item_id;?>"  <?php echo $checked;?>    id="items_<?php echo   $row_item->item_id;?>"  >
						  <label for="items_<?php echo   $row_item->item_id;?>"> <?php echo   $row_item->item_name. " - ".  $row_item->telugu_name;   ?> </label>
						</div>
				<?php } ?>
                
                
				 
                
                </div>
 
   
              <div class="box-footer">
			  <input type="hidden"  name="action"    value="submit">
			<div class='error_div'></div>
                <button type="submit" class="btn btn-primary">Submit</button>
              </div>

           <?php form_close(); ?>
			 
			
          </div>
		   
			 
		  <script>
		   
		  
		  		$(document).ready( function () {   
    $("#myform").submit(function(e) {       
      e.preventDefault();
		 
			 frm = document.getElementById("myform");
			$(".error_div").html("");
			 var message = '  ';
				   
				 
											 

										  $.ajax({
																			   type: "POST",
																			    processData: false,
																				contentType: false,
																			   url: '<?php echo site_url();?>vendors_apsocial/ajax_map_items/<?php echo $vendor_annapurna_id;?>',
																			   data: new FormData(document.getElementById("myform")),//$("#myform").serialize(), // serializes the form's elements.
																			   success: function(data)
																			   {
																				   if(data.success == true)
																				   {
																					  $(".error_div").html(data.msg);
																					   window.location.href="<?php echo site_url();?>vendors_apsocial"
																				   }else
																				   {
																						$(".error_div").html(data.msg);
																				   }
																			   }
																			 }); 
					}); 
    }); 
		  </script>
		   
		  
		  