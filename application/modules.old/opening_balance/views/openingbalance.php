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

<h3>Opening Balance entries<!-- as on - <?php echo $open_date;?> --></h3>
 

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
				aria-label="Platform(s): activate to sort column ascending" style="width: 139px;">Date </th>
				
				 <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" 
				aria-label="Engine version: activate to sort column ascending" style="width: 106px;">Qty</th> 
				
				
				
				
				 
				  <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" 
				aria-label="CSS grade: activate to sort column ascending" style="width: 75px;">Action</th>
				  
				</tr>
                </thead>
                <tbody>
                 <?php 
				 
				 foreach($rset->result() as $item) { // print_a($item,1);?>                <tr role="row" class="odd">
                  <td class="sorting_1">
				  <?php if(  $item->allowed_to_update==true){ ?>
				 <a href='<?php echo site_url();?>opening_balance/update/<?php echo $item->item_id;?>'> 
				  <?php echo $item->telugu_name."-".$item->item_name;?><a/><?php } else { echo $item->telugu_name."-".$item->item_name;}?>
				  
				  
				  </td>
                   
                   <td> <?php  echo $item->display_ob_date;   ?> </td> 
				   	  <td> <?php  echo $item->qty;   ?> </td> 
                 
                  <td> <?php if( $item->allowed_to_update==true){ ?> <a href='<?php echo site_url();?>opening_balance/update/<?php echo $item->item_id;?>'>Update</a> <?php }  ?></td>
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
		"pageLength": 1300,
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
	 
	 
	<!--  <link rel="stylesheet" href="http://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  
<div id="dialog" title="Attention dear staff">
  <h2 style='color:#FF0000;'>This report is opening quantity  for all items  as on june 4th 2018, not today. </h2>
</div>
 <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
  $( function() {
    $( "#dialog" ).dialog();
  } );
  </script>-->
	 