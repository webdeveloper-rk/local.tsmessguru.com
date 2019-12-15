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
.bold{
	font-weight:bold;
}

</style>


<?php echo $this->session->flashdata('message');?>

<h3><?php echo $item_details->telugu_name ." - ";echo $item_details->item_name;?> - PURCHASE  Report</h3>
 

<div class="box">
            <div class="box-header">
             <h4>Report from <b><?php echo $from_date_dp; ?> </b> from <b><?php echo $to_date_dp;?></b> </h4>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
               <div><A class="btn btn-info pull-right" href='<?php echo site_url('purchase_itemwise_report/purchaseitemreport/'.$item_id."/".$from_date."/".$to_date."/download");?>'>Download Report</a></div>
			  <A class="btn btn-info pull-right" href='<?php echo site_url('purchase_itemwise_report');?>'>Go Back</a></div>
			  <table id="example1" class="table table-bordered table-striped dataTable" role="grid" aria-describedby="example1_info">
                <thead>
                <tr role="row">
				<th class="sorting_asc" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-sort="ascending" 
				aria-label="Rendering engine: activate to sort column descending" style="width: 126px;">Date</th>
				 
				 
				 
				 <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" 
				aria-label="Engine version: activate to sort column ascending" style="width: 106px;">Purchase Qty</th> 
				 <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" 
				aria-label="Engine version: activate to sort column ascending" style="width: 106px;">Purchase Price</th>
				  
			  
 
				 
				 
				<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" 
				aria-label="Engine version: activate to sort column ascending" style="width: 106px;">Total </th>
		
				 
</tr>
                </thead>
                <tbody>
                 <?php 
				 
				 $total_purchase = 0;
				 foreach($daily_item_details->result() as $item) {  
				  
				  $total_purchase  =  $total_purchase +  $item->purchase_quantity;
				  
				 ?>
				 <tr  >
				<td class="sorting_asc" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-sort="ascending" 
				aria-label="Rendering engine: activate to sort column descending" style="widtd: 126px;"><?php echo $item->entry_date_dp;?></td>
				 
				 
				 <td class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" 
				aria-label="Engine version: activate to sort column ascending" style="widtd: 106px;"><?php echo $item->purchase_quantity;?></td> 
				 <td class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" 
				aria-label="Engine version: activate to sort column ascending" style="widtd: 106px;"><?php echo $item->purchase_price;?></td>
				 
				<td class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" 
				aria-label="Engine version: activate to sort column ascending" style="widtd: 106px;"><?php echo number_format($item->purchase_quantity,3);?></td>
		
				 
</tr>
				 <?php 
				  } 
				  ?>
				   <tr class="bold" >
				 
				 <td class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="3" 
				aria-label="Engine version: activate to sort column ascending" style="widtd: 106px;" align="right">&nbsp;Total Purchased Quantity</td> 
			 
				<td class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" 
				aria-label="Engine version: activate to sort column ascending" style="widtd: 106px;"><?php echo  number_format(  $total_purchase,3);;?></td> 
				 
		
				 
				
                </tbody>
                
              </table>
			  
            </div>
            <!-- /.box-body -->
          </div>
		  <script>
  $(function () {
  //  $("#example1").DataTable();
    $('#example11').DataTable({
		"pageLength": 3000,
      "paging": true,
      "lengthChange": false,
      "searching": true,
      "ordering": true,
      "info": true,
	   
      "autoWidth": true
    });
  });
</script>
	 