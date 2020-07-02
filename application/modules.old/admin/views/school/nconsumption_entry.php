<?php echo $this->session->flashdata('message'); 
<h3>consumption entry - <?php echo $current_session->name;?></h3>
 

<div class="box">
            <div class="box-header">
              
			 <br>
              <h3 class="box-title"><?php echo date('D');?> - <?php echo date('d-m-Y');?> </h3>
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
				<?php 	 
				<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" 
				aria-label="CSS grade: activate to sort column ascending" style="width: 75px;">Action</th>
	<?php //} ?>
				</tr>
                </thead>
                <tbody>
                 <?php 
			 
				 foreach($rset->result() as $item) { 
                  <td class="sorting_1">
				  <a href='<?php echo site_url();?>admin/school/consumption_entryform/<?php echo $item->item_id;?>/<?php echo $this->uri->segment(4);?>'>
				  <?php echo $itemnames[$item->item_id];?>  </a></td>
				
                  <td>
             
				  
					
				 </td>
				     <td> <?php echo  $item->$price_field;?> </td>
 
				  <td><?php echo $item->$qty_field *  $item->$price_field; ?></td>
				  <td>  <?php echo  $item->closing_quantity;?></td>
				<td><?php 
                  <a href='<?php echo site_url();?>admin/school/consumption_entryform/<?php echo $item->item_id;?>/<?php echo $this->uri->segment(4);?>'><?php if($this->session->userdata("operator_type")=="DEO"){ echo "UPDATE";} elseif($this->session->userdata("operator_type")=="CT") { echo "EDIT";}else { echo "Update";} ?></a>
				 <?php } ?>   </td>
                </tr>
				 <?php } ?>
				
                </tbody>
                
              </table>
			  <?php 
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
	 