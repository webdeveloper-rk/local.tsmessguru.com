<?php echo $this->session->flashdata('message'); $ses_p = $this->school_model->get_authorise($session_id,$this->session->userdata("school_id"));?>
<h3>consumption entry - <?php echo $current_session->name;?></h3>
 

<div class="box">
            <div class="box-header">
              
			 <br>
              <h3 class="box-title"><?php echo date('D');?> - <?php echo date('d-m-Y');?> </h3>			  <br><br>			   
            </div>
            <!-- /.box-header -->
            <div class="box-body">
               
			  
			  <table id="example1" class="table table-bordered table-striped dataTable" role="grid" aria-describedby="example1_info">
                <thead>
                <tr role="row">
				<th class="sorting_asc" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-sort="ascending"  aria-label="Rendering engine: activate to sort column descending" style="width: 126px;">Item Name</th>
				  <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1"  aria-label="Engine version: activate to sort column ascending" style="width: 106px;">Quantity</th>
				<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1"  aria-label="Engine version: activate to sort column ascending" style="width: 106px;">Rate</th>							 
				<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" 
				aria-label="Engine version: activate to sort column ascending" style="width: 106px;">Total Amount</th>
				<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" 
				aria-label="Engine version: activate to sort column ascending" style="width: 106px;">Avilable Quantity</th>				 								 
				<?php 	 				$ses_p = $this->school_model->get_authorise($session_id,$this->session->userdata("school_id"));				// if($data_entry_allowed==true || ($this->session->userdata("operator_type")=="CT" && $ses_p['code']==2) ) 	{ ?>
				<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" 
				aria-label="CSS grade: activate to sort column ascending" style="width: 75px;">Action</th>
	<?php //} ?>
				</tr>
                </thead>
                <tbody>
                 <?php 
			 
				 foreach($rset->result() as $item) { 				 				 if(!in_array($item->item_id,$allowed_items))						continue;									//echo "<pre>";print_r($item);echo "</pre>";die; 				 ?> 				 <tr role="row" class="odd">
                  <td class="sorting_1">
				  <a href='<?php echo site_url();?>admin/school/consumption_entryform/<?php echo $item->item_id;?>/<?php echo $this->uri->segment(4);?>'>
				  <?php echo $itemnames[$item->item_id];?>  </a></td>
				
                  <td>
             			<?php echo $item->$qty_field;?> 
				  
					
				 </td>
				     <td> <?php echo  $item->$price_field;?> </td>
 
				  <td><?php echo $item->$qty_field *  $item->$price_field; ?></td>
				  <td>  <?php echo  $item->closing_quantity;?></td>						 												 
				<td><?php 								 if($data_entry_allowed==true || ($this->session->userdata("operator_type")=="CT" && $ses_p['code']==2) ) 	{?> 
                  <a href='<?php echo site_url();?>admin/school/consumption_entryform/<?php echo $item->item_id;?>/<?php echo $this->uri->segment(4);?>'><?php if($this->session->userdata("operator_type")=="DEO"){ echo "UPDATE";} elseif($this->session->userdata("operator_type")=="CT") { echo "EDIT";}else { echo "Update";} ?></a>				  								 
				 <?php } ?>   </td>				 
                </tr>
				 <?php } ?>					<?php 					if($display_count ==0)					{						//echo "<tr><td colspan='3'>No Records Found.</td></tr>";					}					?>
				
                </tbody>
                
              </table> 
			  <?php 			  $ses_p = $this->school_model->get_authorise($session_id,$this->session->userdata("school_id"));			   //print_r($ses_p);			 // echo intval($authorised);			  if(strtolower($ses_p['status']) == "authorised" )			  {					echo "<span class='callout callout-success lead'>Authorised</span>";			  }			  else{				 echo "<span  ><b>Not Authorised</b></span> &nbsp;&nbsp;&nbsp;  ";					if($this->session->userdata("operator_type")!="CT"){						//echo "Caretaker can authorise this session.";						}								 					if($this->session->userdata("operator_type")=="CT" && $ses_p['code']==2){				 ?>						<a href='<?php echo site_url('admin/school/authorise_today/'.$session_id);?>' class='btn btn-success'>Click here to Confirm</a>					<?php }			  }			  ?>
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
	 