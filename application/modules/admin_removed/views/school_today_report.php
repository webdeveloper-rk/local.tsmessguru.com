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
.daily table{
	border-collapse: collapse;
	 border: 1px;
}
.daily   td{
	padding:5px;
}
@media print
{    
   .noprint
    {
        display: none !important;
    }
}


</style>


<?php echo $this->session->flashdata('message');?>

<h3><?php echo $report_date;?> Balance report [ <?php echo $school_name;?> ] </h3>
 

<div class="box">
            <div class="box-header">
             
            </div>
            <!-- /.box-header -->
            <div class="box-body">
               
			   <a href='#' class='btn btn-info pull-right noprint' onclick='javascript:window.print();'>Print</a>
			  
			  <table id="example1" class="table table-bordered table-striped dataTable" role="grid" aria-describedby="example1_info">
                <thead>
                <tr role="row">
				<th class="sorting_asc" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-sort="ascending" 
				aria-label="Rendering engine: activate to sort column descending" style="width: 126px;">Item Name</th>
				<!--<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" 
				aria-label="Browser: activate to sort column ascending" style="width: 159px;">Current Balance</th>-->
				 
				
				
				 <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" 
				aria-label="Engine version: activate to sort column ascending" style="width: 106px;">Opening Balance</th> 
				
				<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" 
				aria-label="Platform(s): activate to sort column ascending" style="width: 139px;">Purchase Balance</th>
				<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" 
				aria-label="Platform(s): activate to sort column ascending" style="width: 139px;">Total Qty</th>
				 <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" 
				aria-label="Engine version: activate to sort column ascending" style="width: 106px;">Consumed Qty</th>
				
				 <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" 
				aria-label="Engine version: activate to sort column ascending" style="width: 106px;">Total Consumed Qty</th>
				
				 
				<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" 
				aria-label="CSS grade: activate to sort column ascending" style="width: 75px;">Closing Balance Qty</th>
				  <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" 
				aria-label="Engine version: activate to sort column ascending" style="width: 106px;">Total Consumed Amount</th>
				</tr>
                </thead>
                <tbody>
                 <?php 
				 
				 foreach($rset->result() as $item) {  
				 
						$tqty =  $item->session_1_qty + $item->session_2_qty  + $item->session_3_qty  + $item->session_4_qty;
						if($tqty > 0) { 
				 ?>                <tr role="row" class="odd">
                  <td class="sorting_1"> 
				 <b> <?php echo $item->telugu_name."-".$item->item_name;?></b></td>
                  <!--<td>Curebnt balance</td>
                  <td><?php echo $item->item_name; ?> </td>-->
				
                   <td>
					 <?php echo $item->opening_quantity; ?> 
				   </td> 
				    <td>
					 <?php echo $item->purchase_quantity; ?> 
				   </td> 
				    <td>
					 <?php echo  ($item->opening_quantity+$item->purchase_quantity); ?> 
				   </td> 
				   	<td> <table  class='daily' border="1px">
						<tr><td>Breakfast</td><td> <?php echo $item->session_1_qty; ?> </td></tr>
						<tr><td>Lunch</td><td> <?php echo $item->session_2_qty; ?> </td></tr>
						<tr><td>Snacks</td><td> <?php echo $item->session_3_qty; ?> </td></tr>
						<tr><td>Dinner/Supper</td><td> <?php echo $item->session_4_qty; ?> </td></tr>
						</table>
					</td>
                   <td><?php 
						echo number_format((  $item->session_1_qty + $item->session_2_qty  + $item->session_3_qty  + $item->session_4_qty ),3);
					?></td> 
                  
                
                  <td> <?php echo $item->closing_quantity; ?> </td>  
				  <td> <?php echo number_format(  $item->today_consumed,2); ?> </td>
                </tr>
				 <?php 
				 
						}
				 } ?>
				
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
      "searching": false,
      "ordering": false,
      "info": true,
	  
      "autoWidth": true
    });
  });
</script>
	 