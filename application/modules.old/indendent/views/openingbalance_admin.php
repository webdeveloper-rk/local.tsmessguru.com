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

</style>


<?php echo $this->session->flashdata('message');?>

<h3>Opening Balance entries - <?php echo $open_date;?> [<?php echo $school_name;?>]</h3>
 

<div class="box">
            <div class="box-header">
             
            </div>
            <!-- /.box-header -->
            <div class="box-body">
               
			  
			  <table id="example1" class="table table-bordered table-striped dataTable" role="grid" aria-describedby="example1_info">
                <thead>
                <tr role="row">
				<th class="sorting_asc" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-sort="ascending" 
				aria-label="Rendering engine: activate to sort column descending" style="width: 126px;">Item Name</th>
				<!--<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" 
				aria-label="Browser: activate to sort column ascending" style="width: 159px;">Current Balance</th>-->
				 
				
				
				 <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" 
				aria-label="Engine version: activate to sort column ascending" style="width: 106px;">Qty</th> 
				
				<!--<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" 
				aria-label="Platform(s): activate to sort column ascending" style="width: 139px;">Rate</th>
				
				
				 <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" 
				aria-label="Engine version: activate to sort column ascending" style="width: 106px;">Total</th>-->
				 <?php if( $allowed_to_update==true){ ?> <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" 
				aria-label="CSS grade: activate to sort column ascending" style="width: 75px;">Action</th>
				 <?php } ?>
				</tr>
                </thead>
                <tbody>
                 <?php 
				 
				 foreach($rset->result() as $item) { ?>                <tr role="row" class="odd">
                  <td class="sorting_1">
				  <?php if( $allowed_to_update==true){ ?>
				 <a href='<?php echo site_url();?>opening_balance/opening_balance_admin/update/<?php echo $school_id;?>/<?php echo $item->item_id;?>'> 
				  <?php echo $item->telugu_name."-".$item->item_name;?><a/><?php } else { echo $item->telugu_name."-".$item->item_name;}?>
				  
				  
				  </td>
                  
				
                   <td>
					<?php 
					 echo $item->opening_quantity;
					  ?>
				   </td> 
				  <!-- 	<td> <?php 
					echo $item->opening_price;
					?></td>
                   <td><?php 
						echo number_format(( $item->opening_quantity*$item->opening_price),2);
					?></td> -->
                 <?php if( $allowed_to_update==true){ ?> <td>  <a href='<?php echo site_url();?>opening_balance/update/<?php echo $item->item_id;?>'>Update</a> </td><?php }  ?>
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
	   "order": [[ 1, "desc" ]],
      "autoWidth": true
    });
  });
</script>
	 