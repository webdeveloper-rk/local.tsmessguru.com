 
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
              <h3 class="box-title"><b><?php echo $current_session->name;?></b> -   Consumption entry for <b><?php echo $item_details->item_name." - ".$item_details->telugu_name; ?></b> date of  <b><?php echo $todat_date_text;?></b></h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
			
			 
			
			 
 

  <input type="hidden"  name="consumptionprime"    value="<?php echo  $entry_id  ;?>">
              <div class="box-body">
			   <div class="form-group">
                  <label for="exampleInputEmail1">Avilable Quantity</label>
						<span class="bold " style="color:#0000FF;font-weight:bold;font-size:18px"><?php echo $item_used->closing_quantity;?></span>&nbsp;&nbsp;KG
						      </div>
			 
					 
			 
                <div class="form-group">
                  <label for="exampleInputEmail1">   Usage</label>
                 <?php   //print_a($today_item_used); 
				 //echo $item_used->$qty; ?>
				 
				  <style>
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
						.a2 th {
						  background-color: #FA6135;
						  color: white;
						}
						</style>
				<table class='a2'><thead><tr><th>Quantity</th><th>Price</th><th>Total</th></tr></thead><tbody>
											<?php 
											switch($session_id)
											{
												case 1: 
															$sess_old_qty  = $today_item_used->session_1_old_qty ;
															$sess_old_price  = $today_item_used->session_1_old_price ; 
															$sess_new_qty  = $today_item_used->session_1_new_qty ;
															$sess_new_price  = $today_item_used->session_1_new_price ;
															break;
												case 2: 
															$sess_old_qty  = $today_item_used->session_2_old_qty ;
															$sess_old_price  = $today_item_used->session_2_old_price ; 
															$sess_new_qty  = $today_item_used->session_2_new_qty ;
															$sess_new_price  = $today_item_used->session_2_new_price ;
															break;
												case 3: 
															$sess_old_qty  = $today_item_used->session_3_old_qty ;
															$sess_old_price  = $today_item_used->session_3_old_price ; 
															$sess_new_qty  = $today_item_used->session_3_new_qty ;
															$sess_new_price  = $today_item_used->session_3_new_price ;
															break;
												case 4: 
															$sess_old_qty  = $today_item_used->session_4_old_qty ;
															$sess_old_price  = $today_item_used->session_4_old_price ; 
															$sess_new_qty  = $today_item_used->session_4_new_qty ;
															$sess_new_price  = $today_item_used->session_4_new_price ;
															break;
											}
												
											if( $sess_old_qty>0 ) { ?><tr><td><?php echo   $sess_old_qty;?></td><td><?php echo  $sess_old_price;?></td>
											<td><?php echo $sess_old_qty * $sess_old_price; ?></td>
											</tr><?php } ?>
											<tr><td><?php echo  $sess_new_qty ;?></td><td> <?php echo  $sess_new_price;?></td>
											<td><?php echo $sess_new_qty * $sess_new_price;;?></td></tr>
											
											<tr><td colspan="2" style="text-align:right;font-weight:bold;">Grand Total</td> 
											<td><?php 
											echo 		($sess_new_qty* $sess_new_price ) + ($sess_old_qty * $sess_old_price) ; ?>
											 </td></tr>
											
											
											</tbody></table>
				 
                </div>
				 
               
              </div>
			  
			  <div id="response"></div>
              <!-- /.box-body -->
 
 
            
			
          </div>
		   
			 
		  