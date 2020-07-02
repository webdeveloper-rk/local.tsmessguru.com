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

<h3>Opening Balance entries  </h3>
 

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
				
				<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" 
				aria-label="Platform(s): activate to sort column ascending" style="width: 139px;">Rate</th>
				
				
				 <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" 
				aria-label="Engine version: activate to sort column ascending" style="width: 106px;">Total</th>
				<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" 
				aria-label="CSS grade: activate to sort column ascending" style="width: 75px;">Action</th></tr>
                </thead>
                <tbody>
                 <?php 
				 
				 foreach($rset->result() as $item) { ?>                <tr role="row" class="odd">
                  <td class="sorting_1">
				 <a href='<?php echo site_url();?>admin/school/openingbalance_entryform/<?php echo $item->item_id;?>'> 
				  <?php echo $item->telugu_name."-".$item->item_name;?><a/></td>
                  <!--<td>Curebnt balance</td>
                  <td><?php echo $item_prices[$item->item_id]; ?> </td>-->
				
                   <td>
					<?php 
					$sqty = 0;
					  if(isset($intial_balances[$item->item_id]->purchase_quantity)) { $sqty = $intial_balances[$item->item_id]->purchase_quantity;} else { $sqty = "0.00";} ;;
						echo $sqty ;
					  ?>
				   </td> 
				   	<td> <?php 
					$school_price=0;
					  if(isset($intial_balances[$item->item_id]->purchase_price)) { $school_price =  $intial_balances[$item->item_id]->purchase_price;} 
					  else { 
						$school_price =  '0.00';
					  }
					  echo $school_price;
					?></td>
                   <td><?php 
						echo number_format(( $school_price*$sqty),2);
					?></td> 
                  <td> <a href='<?php echo site_url();?>admin/school/openingbalance_entryform/<?php echo $item->item_id;?>'>Update</a> </td>
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
      "autoWidth": true
    });
  });
</script>
	 