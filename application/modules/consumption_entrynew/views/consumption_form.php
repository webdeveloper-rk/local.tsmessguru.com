<script src="<?php echo site_url();?>js/bootbox.min.js"></script>
<?php
 //echo "<pre>"; print_r($item_used); echo "</pre>";
 
$min_item_value = $item_details->min_price;
$max_item_value = $item_details->max_price;

$price_read_only = false;
 if($this->config->item("site_name")=="twhostels")
 {
	$price_read_only = true;
	 $is_excempted = $this->common_model->fixed_rate_item_excemption($item_id,$school_id);
	 if($is_excempted == true)
		 $price_read_only = false;
 }
 
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

.box.box-primary {
    border-top-color: #3c8dbc;
    padding: 10px;
}
</style>
<?php echo $this->session->flashdata('message');?>
	<!--<h3><span style='color:red'>Time extended for snacks and supper till 7 PM for March 8th 2017 only.</span></h3>-->
<div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title"><b><?php echo $current_session->name;?></b> - Consumption entry for <b><?php echo $item_details->item_name." - ".$item_details->telugu_name; ?></b> date of  <b><?php echo $todat_date_text;?></b></h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
			
			<?php 
			//check special item or not and approved or not 
			 //print_a($item_details);
			// print_a($this->session->all_userdata());
			if($item_details->item_special=="special")
			{
				$chk_date = date('Y-m-d');
				$sch_id = $this->session->userdata('school_id');
				$sql_chk = "select * from item_approvals where 
										school_id='".$sch_id."' and 
										item_id='".$item_details->item_id."' and 
										entry_date='$chk_date' and status='Approved'";
				//echo $sql_chk;
				$rs_chk = $this->db->query($sql_chk);
				if($rs_chk->num_rows()==0)
				{
					 echo "<h1>This item has not been Approved to use on ".date('d-m-Y')."</h1>";
					 return;
					
				}					
										
			}
			
			if($data_entry_allowed == true) {
				
			
			?>
         <?php   $attributes = array('class' => 'email', 'id' => 'myform');
echo form_open('', $attributes); 	
 
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

  <input type="hidden"  name="consumptionprime"    value="<?php echo  $entry_id  ;?>">
              <div class="box-body">
			   <div class="form-group">
                  <label for="exampleInputEmail1">Avilable Quantity</label>
						<span class="bold " style="color:#0000FF;font-weight:bold;font-size:18px"><?php echo $item_used->closing_quantity;?></span>&nbsp;&nbsp;KG
						      </div>
			 
					 
			 
                <div class="form-group">
                  <label for="exampleInputEmail1"> Quantity</label>
                  <?php if($locked_check==false) { 
					
				  ?>
				  <input type="text" name="quantity"  style="width:40%;" value="<?php echo $item_used->$qty ;?>" class="form-control"  required  id="quantity_id" placeholder="Enter Quantity">
				  <!--<input type="button" value="Get Price Details" onclick="update_pricedetails()">-->
				 <div class="box-body table-responsive"> <br>
				 <h3> గమనిక  : ఈ క్రింది కొనుగోలు పట్టిక ద్వారా   price ఆటోమేటిక్ గా తీసుకోబడుతుంది గమనించగలరు . </h3>
				  <br>
					<?php 
					$total_amount = 0;
					$text = "<style>
						table {
						  border-collapse: collapse;
						  width: 100%;
						}

						th, td {
						  text-align: left;
						  padding: 8px;
						}

						tr:nth-child(even){background-color: #f2f2f2}

						th {
						  background-color: #4CAF50;
						  color: white;
						}
						</style>
				<table class='table-dark'><thead><tr><th>Purchase Date</th><th>Purchased Quantity</th><th>Remaing Quantity to use</th><th>Price</th> </tr></thead>";
					foreach($purchase_data as $obj)
					{
						if($obj['remaing_to_use']<=0)
								continue;
						//print_a( $obj,1);
						$amount = $obj['remaing_to_use'] * $obj['purchase_price'];
						$text .= "<tr><td>".$obj['purchase_date']."</td><td>".$obj['purchase_quantity']."</td><td>".$obj['remaing_to_use']."</td><td>".$obj['purchase_price']."</td> </tr>";
						$total_amount = $total_amount + $amount;
					}
					//$text .= "<tr><td colspan='3' align='right' style='text-align:right;'><b>Total Amount</b></td><td><b>".$total_amount."</b></td></tr>";
					$text .="</table>";
					echo $text;
					
					?>
					</div>
				  <?php 
							  } else {echo $item_used->$qty;}?>
                </div>
				 
               
              </div>
			  
			  <div id="response" style="display:none;"></div>
              <!-- /.box-body -->
 <?php if($locked_check==false) { ?>
  <div class='notification-alert'>Note: please check the values carefully before submit, once submitted values can't be modified.</div>
              <div class="box-footer">
			  <input type="hidden"  name="action"    value="submit">
			
                <button type="submit" class="btn btn-primary"  >Submit</button>
              </div>
 <?php } ?>
           <?php form_close(); ?>
			<?php } 
			else{
				echo $data_entry_text;
			}
			?>
			
          </div>
		   
			 
		  <script>
		  function toggle(flag)
		  {
			 
		  }
		  
		  
		  		$(document).ready( function () {  $(function(){
    $("#myform").submit(function(e) {       
      e.preventDefault();
		flag = validatemyfrom();
		//alert(flag);
		
		if(flag){
			 frm = document.getElementById("myform");
			  $("#response").html("");
				update_pricedetails();
			 //alert($("#response").html());
			
			 var message = ' You are submitting the consumption entry for the following item  <br> <b><h3><?php echo $item_details->item_name;?> : ' + frm.quantity.value+ '</h3></b> <br>  ';
				  
					//message =  message + "Quantity : <b><h4>"+ frm.quantity.value + "</h4></b> Kgs  <br> <br> ";
					
				  total_quantity  = parseFloat(frm.quantity.value)  ; 
				  
				  message =  message + $("#response").html();
				  
				  message = message + " </b><br><br><span  ><h5 style='color:#ff0000;font-weight:bold;'> Are you sure to Submit ?</h5></span>";
				 
				  
				  bootbox.confirm({ 
									size: "big",
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
			   if(isNaN(frm.quantity.value))
				  {
					  alert("Please enter valid  stock quantity");
					  frm.quantity.focus();
					  return false;
				  }
			     
				   if(parseFloat(frm.quantity.value)==0)
				  {
					  alert("Please enter valid   stock quantity");
					  frm.quantity.focus();
					  return false;
				  }
				   
				  return true; 
		  }
		  function update_pricedetails()
		  {
			frm = document.getElementById("myform"); 
			if(validatemyfrom(frm))
			{
				
			 
            $.ajax({
                url: '<?php echo site_url("consumption_entrynew/ajax_pricelist/".$item_id);?>',
                dataType: 'text',
				async:false,
                type: 'post',
                contentType: 'application/x-www-form-urlencoded',
                data: $("#myform").serialize(),
                success: function( data, textStatus, jQxhr ){
					//console.log(data);
                    $('#response').html( data );
                },
                error: function( jqXhr, textStatus, errorThrown ){
                    console.log( errorThrown );
                }
            });

           
         
			//alert("get Ajax data");
			//	return false;
			}
		  
		  }
		  </script>
		  