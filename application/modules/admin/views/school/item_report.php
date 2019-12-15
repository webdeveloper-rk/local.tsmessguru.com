<?php
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
.bold{
	font-weight:bold;
}

</style>


<?php echo $this->session->flashdata('message');?>

<h3><?php echo $item_details->telugu_name ." - ";echo $item_details->item_name;?>  Report</h3>
 

<div class="box">
            <div class="box-header">
             <h4>Report from <b><?php echo $from_date_dp; ?> </b> from <b><?php echo $to_date_dp;?></b> </h4>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
               <div><A class="btn btn-info pull-right" href='<?php echo site_url('admin/school/itemreport/'.$item_id."/".$from_date."/".$to_date."/download");?>'>Download Report</a></div>
			  <A class="btn btn-info pull-right" href='<?php echo site_url('admin/school/reports');?>'>Go Back</a></div>
			  <table id="example1" class="table table-bordered table-striped dataTable" role="grid" aria-describedby="example1_info">
                <thead>
                <tr role="row">
				<th class="sorting_asc" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-sort="ascending" 
				aria-label="Rendering engine: activate to sort column descending" style="width: 126px;">Date</th>
				 
				<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" 
				aria-label="Platform(s): activate to sort column ascending" style="width: 139px;">Opening Qty</th>
				 
				 <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" 
				aria-label="Engine version: activate to sort column ascending" style="width: 106px;">Purchase Qty</th> 
				 <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" 
				aria-label="Engine version: activate to sort column ascending" style="width: 106px;">Purchase Price</th>
				 <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" 
				aria-label="Engine version: activate to sort column ascending" style="width: 106px;">Total Qty</th> 
			 
				<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" 
				aria-label="Engine version: activate to sort column ascending" style="width: 106px;">Consumption Qty</th> 
 
				<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" 
				aria-label="Engine version: activate to sort column ascending" style="width: 106px;">Closing Qty</th> 
				 
				<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" 
				aria-label="Engine version: activate to sort column ascending" style="width: 106px;">Total Consumed Price</th>
		
				 
</tr>
                </thead>
                <tbody>
                 <?php 
				 $total_amount = 0;
				 $total_consumed = 0;
				 foreach($daily_item_details->result() as $item) {  
				  $total_amount  =  $total_amount  + $item->consumed_total;
				  $total_consumed  =  $total_consumed  + $item->consumed_qty;
				  
				 ?>
				 <tr  >
				<td class="sorting_asc" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-sort="ascending" 
				aria-label="Rendering engine: activate to sort column descending" style="widtd: 126px;"><?php echo $item->entry_date_dp;?></td>
				 
				<td class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" 
				aria-label="Platform(s): activate to sort column ascending" style="widtd: 139px;"><?php echo $item->opening_quantity;?></td>
				 
				 <td class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" 
				aria-label="Engine version: activate to sort column ascending" style="widtd: 106px;"><?php echo $item->purchase_quantity;?></td> 
				 <td class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" 
				aria-label="Engine version: activate to sort column ascending" style="widtd: 106px;"><?php echo $item->purchase_price;?></td>
				 <td class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" 
				aria-label="Engine version: activate to sort column ascending" style="widtd: 106px;"><?php echo $item->total_qty;?></td> 
			 
				<td class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" 
				aria-label="Engine version: activate to sort column ascending" style="widtd: 106px;"><?php echo $item->consumed_qty;?></td> 
				  <td class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" 
				aria-label="Engine version: activate to sort column ascending" style="widtd: 106px;"><?php echo $item->closing_quantity;?></td> 
				 
				<td class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" 
				aria-label="Engine version: activate to sort column ascending" style="widtd: 106px;"><?php echo number_format($item->consumed_total,2);?></td>
		
				 
</tr>
				 <?php 
				  } 
				  ?>
				   <tr class="bold" >
				<td class="sorting_asc" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-sort="ascending" 
				aria-label="Rendering engine: activate to sort column descending" style="widtd: 126px;">&nbsp;</td>
				 
				<td class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" 
				aria-label="Platform(s): activate to sort column ascending" style="widtd: 139px;">&nbsp;</td>
				 
				 <td class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" 
				aria-label="Engine version: activate to sort column ascending" style="widtd: 106px;">&nbsp;</td> 
				 <td class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" 
				aria-label="Engine version: activate to sort column ascending" style="widtd: 106px;">&nbsp;</td>
				 <td class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" 
				aria-label="Engine version: activate to sort column ascending" style="widtd: 106px;">&nbsp;Total Consumed Quantity</td> 
			 
				<td class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" 
				aria-label="Engine version: activate to sort column ascending" style="widtd: 106px;"><?php echo  number_format($total_consumed,3);;?></td> 
				  <td class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" 
				aria-label="Engine version: activate to sort column ascending" style="widtd: 106px;">Total Consumed Amount</td> 
				 
				<td class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" 
				aria-label="Engine version: activate to sort column ascending" style="widtd: 106px;"><?php echo number_format($total_amount,2);?></td>
		
				 
				
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
	 