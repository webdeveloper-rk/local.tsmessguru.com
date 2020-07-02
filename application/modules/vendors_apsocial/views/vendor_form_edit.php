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
              
            </div>
            <!-- /.box-header -->
            <!-- form start -->
 
  
  <div class="box-body">
  
   <?php   $attributes = array('class' => 'email', 'id' => 'myform','action'=>site_url().'vendors_apsocial/admin/ajax_update_vendor/'.$account_type."/".$vendor_annapurna_id);
echo form_open_multipart('', $attributes); ?>
    <?php if($account_type=="individual") { ?>
	<div class="form-group" id="oldstockform" >
						  <div class="form-group">
							<h3><?php echo $school_name;?> - Individual Registration details</h3>
						</div>
					 
                
                </div>
	<?php }?>
	
		<?php if($account_type=="firm") { ?>
	<div class="form-group" id="oldstockform" >
						  <div class="form-group">
							<h3><?php echo $school_name;?> - Firm Registration details</h3>
						</div>
					 
                
                </div>
	<?php }?>
	 <?php if($account_type=="individual") { ?>
			<input type="hidden" name="account_type" value="<?php echo $account_type;?>">
				<div class="form-group" id="oldstockform" >
						  <div class="form-group">
						  <label for="exampleInputEmail1">Supplier Aadhar  number *</label>
						   
						  <input type="text" name="supplier_aadhar_number" required  value="<?php echo strtoupper($vendor_info->supplier_aadhar_number);?>" class="form-control" onblur="load_bank_details()"    id="supplier_aadhar_number" placeholder="Enter supplier aadhar number">
						  
						</div>
					 
                
                </div>
				
				  <div class="form-group" id="oldstockform" >
						  <div class="form-group">
						  <label for="exampleInputEmail1">PAN number</label>
						   
						  <input type="text" name="pan_number" value="<?php echo strtoupper($vendor_info->pan_number);?>" class="form-control"      id="pan_number" placeholder="Enter PAN card number">
						  
						</div> 
                </div>
	<?php } ?>
	<?php if($account_type=="firm") { ?>
	<div class="form-group" id="oldstockform" >
						  <div class="form-group">
						  <label for="exampleInputEmail1">TIN/TAN number</label>
						   
						  <input type="text" name="tin_number" value="<?php echo strtoupper($vendor_info->tin_number);?>" class="form-control"    id="tin_number" placeholder="Enter TIN/TAN number">
						  
						</div>
					 
                
                </div>
				 <div class="form-group" id="oldstockform" >
						  <div class="form-group">
						  <label for="exampleInputEmail1">PAN number *</label>
						   
						  <input type="text" name="pan_number" value="<?php echo strtoupper($vendor_info->pan_number);?>" class="form-control"  onblur="load_bank_details()" required    id="pan_number" placeholder="Enter PAN card number">
						  
						</div> 
                </div>
				<div class="form-group" id="oldstockform" >
						  <div class="form-group">
						  <label for="exampleInputEmail1">GST number</label>
						   
						  <input type="text" name="gst_number" value="<?php echo strtoupper($vendor_info->gst_number);?>" class="form-control "     id="gst_number" placeholder="Enter GST number">
						  
						</div>
					 
                
                </div>
				
	<?php } ?>
				
				<?php if($account_type=="individual") { ?>
	 
						  <div class="form-group">
						  <label for="exampleInputEmail1">Title *</label>
						   
						   <select required name="title" id="title" class="form-control alpha" >
								<option value="">Please select</option>
								<option value="Mr" <?php if(strtoupper($vendor_info->title)=="MR"){ echo " selected ";} ?>>Mr</option>
								<option value="M/s" <?php if(strtoupper($vendor_info->title)=="M/S"){ echo " selected ";} ?>>M/s</option>
						   </select>
						</div>
						 <div class="form-group">
						  <label for="exampleInputEmail1">SURNAME *</label>
						    <input type="text" name="sur_name" value="<?php echo strtoupper($vendor_info->sur_name);?>" class="form-control alphaonly"  required  id="sur_name" placeholder="Enter SURNAME">
						  
						   
						</div>
				<?php } ?>
				
				<div class="form-group" id="oldstockform" >
						  <div class="form-group">
						  <label for="exampleInputEmail1"> Supplier name *</label>
						   <input type="hidden" name='vendor_type' value='local'> 
						  <input type="text" name="vendor_name" value="<?php echo strtoupper($vendor_info->vendor_name);?>" class="form-control alphaonly"  required  id="vendor_name" placeholder="Enter Supplier name">
						  
						</div>
					 
                
                </div>
					<div class="form-group" id="oldstockform" >
						  <div class="form-group">
						  <label for="exampleInputEmail1"> City *</label>
						   	  <input type="text"  name="city" value="<?php echo strtoupper($vendor_info->city);?>" class="form-control alphaonly"  required  id="city" placeholder="Enter City">
						  
						</div>
					 
                
                </div>
				
				<div class="form-group" id="oldstockform" >
						  <div class="form-group">
						  <label for="exampleInputEmail1"> Postal Code *</label>
						  
						  <input type="text" name="postal_code" maxlength="6" value="<?php echo strtoupper($vendor_info->postal_code);?>" class="form-control  number"  required  id="postal_code" placeholder="Enter Postal code">
						  
						</div>
					 
                
                </div>		
				
				<div class="form-group" id="oldstockform" >
						  <div class="form-group">
						  <label for="exampleInputEmail1">Supplier address</label><br>
						   
						  <textarea name="vendor_address" cols="60" rows="4"  ><?php echo strtoupper($vendor_info->vendor_address);?></textarea>
						</div>
					 
                
                </div>
				<div class="form-group" id="oldstockform" >
						  <div class="form-group">
						  <label for="exampleInputEmail1">Supplier contact number</label>
						   
						  <input type="text" name="vendor_contact_number" value="<?php echo strtoupper($vendor_info->vendor_contact_number);?>"  maxlength="10" class="form-control number"     id="vendor_contact_number" placeholder="Enter vendor contact number">
						  
						</div>
					 
                
                </div>
				 
				 
				<div class="form-group" id="oldstockform" >
						  <div class="form-group">
						  <label for="exampleInputEmail1">Supplier bank name*</label>
						   
						  <!--<input type="text" name="vendor_bank" value="" class="form-control"  required  id="vendor_bank" placeholder="Enter Supplier bank">-->
						   <select name="vendor_bank" required  class="  form-control" id="vendor_bank" onchange="show_other_bank(this.value)">
							<option value = "">Please select Supplier bank name *  </option>
						 <?php 
						  $banks_list = array("ALLAHABAD BANK","ANDHRA BANK","BANK OF BARODA","BANK OF INDIA","BANK OF MAHARASHTRA","CANARA BANK","CENTRAL BANK OF INDIA","CORPORATION BANK","IDBI BANK LTD.","Dena Bank","INDBANK MERCHANT BANKING SERVICES LTD.","INDIAN BANK","INDIAN OVERSEAS BANK","Vijaya Bank","ORIENTAL BANK OF COMMERCE","PNB GILTS LTD.","PUNJAB & SIND BANK","PUNJAB NATIONAL BANK","STATE BANK OF INDIA","SYNDICATE BANK","UCO BANK","UNION BANK OF INDIA","UNITED BANK OF INDIA","OTHER BANK");
						  
						  foreach($banks_list as $bank_name)
						  {
						  ?><option value = "<?php echo $bank_name;?>" <?php if($bank_name == $vendor_info->vendor_bank) { echo " selected ";} ?> ><?php echo $bank_name;?></option>
						  <?php } ?>
						 </select> 
						</div>
					 
                
                </div>
				<div class="form-group" id="other_bank" style="display:<?php if($vendor_info->bank_name != "OTHER BANK") { echo "none";} ?>" >
						  <div class="form-group">
						  <label for="exampleInputEmail1">Other Bank name * </label>
						   
						  <input type="text" name="vendor_bank_other" value="<?php echo strtoupper($vendor_info->bank_name);?>" class="form-control alphaonly"     id="vendor_bank_other" placeholder="Supplier bank name  "> 
						
						</div> 
                </div>
				<div class="form-group" id="oldstockform" >
						  <div class="form-group">
						  <label for="exampleInputEmail1">Supplier bank branch *</label>
						   
						  <input type="text" name="vendor_bank_branch" value="<?php echo strtoupper($vendor_info->vendor_bank_branch);?>" class="form-control alphaonly"  required  id="vendor_bank_branch" placeholder="Supplier bank branch  "> 
						
						</div> 
                </div>
                 <div class="form-group" id="oldstockform" >
						  <div class="form-group">
						  <label for="exampleInputEmail1">Supplier bank ifsc * </label>
						   
						  <input type="text" name="vendor_bank_ifsc" value="<?php echo strtoupper($vendor_info->vendor_bank_ifsc);?>" class="form-control"  required  id="vendor_bank_ifsc" placeholder="Enter Supplier bank ifsc">
						  
						</div>
					 
                
                </div>
                 <div class="form-group" id="oldstockform" >
						  <div class="form-group">
						  <label for="exampleInputEmail1">Bank account number of supplier * </label>
						   
						  <input type="text" name="vendor_account_number" value="<?php echo strtoupper($vendor_info->vendor_account_number);?>" class="form-control number"  required  id="vendor_account_number" placeholder="Enter Supplier account number">
						  
						</div>
					 
                
                </div>
				
				 <div class="form-group" id="oldstockform" >
						  <div class="form-group">
						  <label for="exampleInputEmail1">Beneficiary Code   </label>
						   
						  <input type="text" name="beneficiary_code" value="<?php echo strtoupper($vendor_info->beneficiary_code);?>" class="form-control "  maxlength=12 id="beneficiary_code" placeholder="Enter beneficiary code">
						  
						</div>
					 
                
                </div>
                
                  <div class="form-group" id="oldstockform" >
						  <div class="form-group">
						  <label for="exampleInputEmail1">SUPPLIER BANK PASSBOOK FIRST PAGE   </label>
						   
						  <input type="file" name="cover_page"    id="cover_page" placeholder="Upload cover page">
						   <div>Maximuim Size should not exceed 20 MB , image maximum dimensions  are 3000 X 3000.</div>
						</div>
					 
                
                </div>
				<div class="box box-solid">
             				 <div class="form-group" id="oldstockform" style="background-color:#ccc;padding:10px;line-height: 80%;"><h4 style="color:#FF0000;font-weight:bold;">పై సప్లయర్ సప్లై చేయు వస్తువులు ఈ క్రింద ఇచ్చిన క్యాటగిరీ లో ఉన్న ఐటమ్స్ పేరు ఎదురుగా టిక్ మార్క్ చేయండి .</h4></div>
 
            <!-- /.box-header -->
            <div class="box-body">
              <div class="box-group" id="accordion">
                <!-- we are adding the .panel class so bootstrap.js collapse plugin detects it -->
                <?php $accordin_id = 1;
				 
			  
			  if($account_type=="individual"){
					$vendor_cats = array("VEGETABLES","MENU FRUIT","SPECIAL FRUITS","LOCAL PROVISIONS","SWEET & HOT","BISCUITS","GROSERY ITEMS","OTHERS");
				}
				else {
					$vendor_cats = array("VEGETABLES","MENU FRUIT","SPECIAL FRUITS","LOCAL PROVISIONS","SWEET & HOT","BISCUITS","GROSERY ITEMS","OTHERS","FUEL CHARGES");
				}
			  if($account_type=="firm")  {$vendor_cats[] = "CIVIL SUPPLIERS"; }
				 
				foreach($vendor_cats as $category) {
					$accordin_id++;
				?>
				 
				
				<div class="panel box box-primary">
                  <div class="box-header with-border">
                    <h4 class="box-title">
                      <a data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo $accordin_id;?>" aria-expanded="false" class="collapsed">
                        <?php echo $category;?>
                      </a>
                    </h4>
                  </div>
                  <div id="collapse<?php echo $accordin_id;?>" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
                    <div class="box-body">
                     <?php 
					 $items_list = $this->db->query("select * from items where TRIM(vendor_category)=?",array(trim($category)));
					 foreach($items_list->result() as $row_item){ ?>
				 
						  <div class="form-group">
						  <?php
							$checked  = '';
							if(in_array( $row_item->item_id,$mapped_items)){ $checked  = " checked  ";} 
						  ?>
						   
						  <input type="checkbox" name="items[]" value="<?php echo $row_item->item_id;?>"  <?php echo $checked;?>    id="items_<?php echo   $row_item->item_id;?>"  >
						  <label for="items_<?php echo   $row_item->item_id;?>"> <?php echo   ucfirst($row_item->item_name. " - ".  $row_item->telugu_name);   ?> </label>
						</div>
				<?php } ?>
                    </div>
                  </div>
                </div>
                <?php } ?>
                
              </div>
            </div>
            <!-- /.box-body -->
          </div>
				 
   
              <div class="box-footer">
			  <input type="hidden"  name="action"    value="submit">
			<div class='error_div'></div>
                <button type="submit" class="btn btn-primary">Submit</button>
              </div>

           <?php form_close(); ?>
			 
			
          </div>
		   
			 
		  <script>
		   $('input[type=text]').keyup (function () {
				this.value =  this.value.toUpperCase();
})

//textarea

		   $('textarea').keyup (function () {
				this.value =  this.value.toUpperCase();
})

	$( "input[class*='number']" ).keyup(function(e)
         {
				  if (/\D/g.test(this.value))
				  {
					// Filter non-digits from input value.
					this.value = this.value.replace(/\D/g, '');
				  }
		});	  
		  		$(document).ready( function () {  $(function(){
    $("#myform").submit(function(e) {       
      e.preventDefault();
		flag =1;
		//alert(flag);
		
		 
		
		if(flag){
			 frm = document.getElementById("myform");
			$(".error_div").html("");
			 var message = '  ';
				   
				   <?php if($account_type=="firm") { ?>
							message = '<h5>You are in submitting the   FIRM Vendor Details </h5>  <br>  ';
							message =  message + "Tin number : <b>"+ frm.tin_number.value + "</b>   <br>  ";
							message =  message + "GST number : <b>"+ frm.gst_number.value + "</b>   <br>  ";
					
				   <?php } ?>
				   <?php if($account_type=="individual") { ?>
							message = '<h5>You are in submitting the   INDIVIDUAL Vendor Details </h5>  <br>  '; 
							message =  message + "PAN Number : <b>"+ frm.pan_number.value + "</b>   <br>  ";
							message =  message + "Aadhar number : <b>"+ frm.supplier_aadhar_number.value + "</b>   <br>  ";
							message =  message + "Title : <b>"+ frm.title.value + "</b>   <br>  ";
							message =  message + "SURNAME : <b>"+ frm.sur_name.value + "</b>   <br>  ";
					
				   <?php } ?>
				   
					
					message =  message + "Supplier name : <b>"+ frm.vendor_name.value + "</b>   <br>  ";
					message =  message + "City : <b>"+ frm.city.value + "</b>   <br>  ";
					message =  message + "Postal Code : <b>"+ frm.postal_code.value + "</b>   <br>  ";
					
					 
					message =  message + "Supplier contact number : <b>"+ frm.vendor_contact_number.value + "</b>   <br>  ";
					 message =  message + "Supplier bank : <b>"+ frm.vendor_bank.value + "</b>   <br>  ";
					message =  message + "Supplier bank branch : <b>"+ frm.vendor_bank_branch.value + "</b>   <br>  ";
					message =  message + "Supplier bank ifsc : <b>"+ frm.vendor_bank_ifsc.value + "</b>   <br>  ";
					message =  message + "Supplier account number : <b>"+ frm.vendor_account_number.value + "</b>   <br>  ";
					
					 //var formData = new FormData(document.getElementById("myform"));
					 
					 //alert(formData);
				   
				  message = message + " </b><br><br><span  ><h5 style='color:#ff0000;font-weight:bold;'> Are you sure to Submit ?</h5></span>";
				 
				  
				  bootbox.confirm({ 
									size: "small",
									message: message, 
									callback: function(result){  
									if(result){
										
											 

										  $.ajax({
																			   type: "POST",
																			    processData: false,
																				contentType: false,
																				cache: false,
																			   url: '<?php echo site_url();?>vendors_apsocial/admin/ajax_update_vendor/<?php echo $account_type;?>/<?php echo $vendor_annapurna_id;?>',
																			   data: new FormData(document.getElementById("myform")),//$("#myform").serialize(), // serializes the form's elements.
																			   success: function(data)
																			   {
																				   if(data.success == true)
																				   {
																					  $(".error_div").html(data.msg);
																					   window.location.href="<?php echo site_url();?>vendors_apsocial/admin/confirm_vendor/<?php echo $school_id;?>"
																				   }else
																				   {
																						$(".error_div").html(data.msg);
																				   }
																			   }
																			 });
										
										
									}
									}
					});
					
			
			
			
			
		}
		
		
		
    });
});
		});
		
		
		function load_bank_details()
		{
				  $.ajax({
																			   type: "POST",
																			    processData: false,
																				contentType: false,
																			   url: '<?php echo site_url();?>vendors_apsocial/ajax_bank_details/<?php echo $account_type;?>',
																			   data: new FormData(document.getElementById("myform")),//$("#myform").serialize(), // serializes the form's elements.
																			   success: function(data)
																			   {
																				   if(data.success == true)
																				   {
																					  
																					  $("#vendor_bank").val(data.vendor_details.vendor_bank);
																					  $("#vendor_bank_branch").val(data.vendor_details.vendor_branch);
																					  $("#vendor_bank_ifsc").val(data.vendor_details.vendor_ifsc);
																					  $("#vendor_account_number").val(data.vendor_details.vendor_account_number);
																					  
																					  $('#vendor_bank').prop('readonly', true);
																					  $('#vendor_bank_branch').prop('readonly', true);
																					  $('#vendor_bank_ifsc').prop('readonly', true);
																					  $('#vendor_account_number').prop('readonly', true);
																					   
																				   }else
																				   {
																						//nothing to do 
																					$("#vendor_bank").val("");
																					  $("#vendor_bank_branch").val("");
																					  $("#vendor_bank_ifsc").val("");
																					  $("#vendor_account_number").val("");
																					  
																					  $('#vendor_bank').prop('readonly', false);
																					  $('#vendor_bank_branch').prop('readonly', false);
																					  $('#vendor_bank_ifsc').prop('readonly', false);
																					  $('#vendor_account_number').prop('readonly', false);
																					   
																				   }
																			   }
																			 });
																			 
																		
		}
		  
	function show_other_bank(selected_branch)
	{
		//console.log(selected_branch);
		if( selected_branch=="OTHER BANK")
		{
			$("#other_bank").show();
		}else{
			$("#other_bank").hide();
		}
	}
	
	
	$(".alphaonly").keypress(function(event){
        var inputValue = event.which;
        // allow letters and whitespaces only.
        if(!(inputValue >= 65 && inputValue <= 120) && (inputValue != 32 && inputValue != 0)) { 
            event.preventDefault(); 
        }
    });
		  </script>
		   
		  
		  