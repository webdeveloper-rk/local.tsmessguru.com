<?php echo $this->session->flashdata('message');?>
<h3>consumption entry - <?php echo $current_session->name;?></h3>
<?php
	/*if($data_entry_allowed==false)
	{
		echo $data_entry_text;
		 
	}  */
?>

<div class="box">
            <div class="box-header">
              
			 <br>
              <h3 class="box-title"><?php echo date('D');?> - <?php echo date('d-m-Y');?> </h3>			  			  <?php 			  $ses_p = $this->school_model->get_authorise($session_id,$this->session->userdata("school_id"));			   //print_r($ses_p);			 // echo intval($authorised);			  if(strtolower($ses_p['status']) == "authorised" )			  {					echo "<span class='callout callout-success lead'>Authorised</span>";			  }			  else{				 echo "<span  ><b>Not Authorised</b></span> &nbsp;&nbsp;&nbsp;  ";					if($this->session->userdata("operator_type")!="CT"){ echo "Caretaker can authorise this session.";}								 					if($this->session->userdata("operator_type")=="CT" && $ses_p['code']==2){				 ?>						<a href='<?php echo site_url('admin/school/authorise_today/'.$session_id);?>' class='btn btn-success'>Click here to Authorise</a>					<?php }			  }			  ?>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
               
			  
			  <table id="example1" class="table table-bordered table-striped dataTable" role="grid" aria-describedby="example1_info">
                <thead>
                <tr role="row">
				<th class="sorting_asc" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-sort="ascending" 
				aria-label="Rendering engine: activate to sort column descending" style="width: 126px;">Item Name</th>
				 
				
				<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" 
				aria-label="Engine version: activate to sort column ascending" style="width: 106px;">Quantity</th>
				<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" 
				aria-label="Engine version: activate to sort column ascending" style="width: 106px;">Rate</th>
				<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" 
				aria-label="Engine version: activate to sort column ascending" style="width: 106px;">Total Amount</th>
				<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" 
				aria-label="Engine version: activate to sort column ascending" style="width: 106px;">Avilable Quantity</th>
				<?php 	 if($data_entry_allowed==true) 	{?>
				<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" 
				aria-label="CSS grade: activate to sort column ascending" style="width: 75px;">Action</th>
	<?php } ?>
				</tr>
                </thead>
                <tbody>
                 <?php 
				//print_r($consumption_qty_price_list);
				 foreach($rset->result() as $item) { ?>                <tr role="row" class="odd">
                  <td class="sorting_1">
				  <a href='<?php echo site_url();?>admin/school/consumption_entryform/<?php echo $item->item_id;?>/<?php echo $this->uri->segment(4);?>'>
				  <?php echo $item->telugu_name."-".$item->item_name;?></td>
				  </a>
                  <td>
             
				  <?php  //echo $qty_field;
				  $quantity = 0.00;
				    $qty_var = "session_".$session_id."_qty";
				  $price_var = "session_".$session_id."_price";
				  
				  $old_qty_var = "session_".$session_id."_old_qty";
				  $old_price_var = "session_".$session_id."_old_price";
				  
				  $new_qty_var = "session_".$session_id."_new_qty";
				  $new_price_var = "session_".$session_id."_new_price";
				  
				  
					  if(isset($today_consumes[$item->item_id]->$qty_var)) { $quantity  = $today_consumes[$item->item_id]->$qty_var;} else {  $quantity  =  "0.00";} ;;
						echo  $quantity ;
					if($today_consumes[$item->item_id]->$old_qty_var>0)
					{
						echo "<br>(old Stock : ". $today_consumes[$item->item_id]->$old_qty_var. " <br> New Stock : ".$today_consumes[$item->item_id]->$new_qty_var.")";
					}						
						
				?>
					
				 </td>
				     <td><?php 
				 if(isset($today_consumes[$item->item_id]->$price_var)) { $price  = $today_consumes[$item->item_id]->$price_var;} else {  $price  =  "0.00";} ;;
						echo  number_format($price,2) ;
					if($today_consumes[$item->item_id]->$old_price_var>0)
					{
						echo "<br>(old Stock : ". $today_consumes[$item->item_id]->$old_price_var. " <br> New Stock : ".$today_consumes[$item->item_id]->$new_price_var.")";
					}		
				 
				 
				 
					?></td>
                  
				  <td><?php //echo  number_format( ($quantity * $sch_price),2),"--"; 
					 if(isset($today_consumes[$item->item_id]->$price_var)){
						 
						$old_total =  $today_consumes[$item->item_id]->$old_qty_var * $today_consumes[$item->item_id]->$old_price_var;
						$new_total =  $today_consumes[$item->item_id]->$new_qty_var * $today_consumes[$item->item_id]->$new_price_var;
						 
						  echo number_format( $old_total + $new_total ,2);
					 }
					 else
					 {
						 echo "0.00";
					 }
				  ?></td>
				  <td><?php if(isset($closing_quantites[$item->item_id])){echo $closing_quantites[$item->item_id];} else { echo "0.000";}?></td>
				 <?php 	 if($data_entry_allowed==true) 	{?>
                  <td><a href='<?php echo site_url();?>admin/school/consumption_entryform/<?php echo $item->item_id;?>/<?php echo $this->uri->segment(4);?>'>Update</a></td>
				 <?php } ?>
                </tr>
				 <?php } ?>
				
                </tbody>
                
              </table>
			  
            </div>
            <!-- /.box-body -->
          </div>
		  <script>
  $(function () {
  //  $("#example1").DataTable();
    $('#example1').DataTable({
		"pageLength": 300,
      "paging": true,
      "lengthChange": false,
      "searching": true,
      "ordering": true,
      "info": true,
	    "order": [[ 3, "desc" ]],
      "autoWidth": false
    });
  });
</script>
	 