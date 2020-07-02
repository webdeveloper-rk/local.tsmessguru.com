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

<h3><?php echo $report_date;?> -Specials Approvals [ <?php echo $school_name;?> ] </h3>
 

<div class="box">
            <div class="box-header">
             
            </div>
            <!-- /.box-header -->
            <div class="box-body">
               
			  <!-- <a href='#' class='btn btn-info pull-right noprint' onclick='javascript:window.print();'>Print</a>-->
			  
			  <table id="example1" class="table table-bordered table-striped dataTable" role="grid" aria-describedby="example1_info">
                <thead>
                <tr role="row">
					<th class="sorting_asc" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-sort="ascending" 
				aria-label="Rendering engine: activate to sort column descending" style="width: 126px;">SNO</th>
				<th class="sorting_asc" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-sort="ascending" 
				aria-label="Rendering engine: activate to sort column descending" style="width: 126px;">Item Name</th>
				<!--<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" 
				aria-label="Browser: activate to sort column ascending" style="width: 159px;">Current Balance</th>-->
				 
				
				
				 <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" 
				aria-label="Engine version: activate to sort column ascending" style="width: 106px;">Date</th> 
				
				<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" 
				aria-label="Platform(s): activate to sort column ascending" style="width: 139px;">Requested Time</th>
				<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" 
				aria-label="Platform(s): activate to sort column ascending" style="width: 139px;">No of Students</th>
				<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" 
				aria-label="Platform(s): activate to sort column ascending" style="width: 139px;">DPC</th>
				 <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" 
				aria-label="Platform(s): activate to sort column ascending" style="width: 139px;">Action</th>
				
				</tr>
                </thead>
                <tbody>
                 <?php 
				 $sno = 1;
				 foreach($rset->result() as $item) {  
				 $encoded_item_id =  $this->ci_jwt->jwt_web_encode($item->item_id);	
						 
				 ?>                <tr role="row" class="odd">
				  <td class="sorting_1"> 
				 <b> <?php echo $sno;?></b></td>
                  <td class="sorting_2"> 
				 <b> <?php 
				 if($item->item_status!=""){
				 
				 echo $item->telugu_name."-".$item->item_name;
				 }else {
				 
				 ?>
				 <a href="<?php echo site_url('specials/approval_entryform/'.$encoded_item_id );?>" ><?php    echo $item->telugu_name."-".$item->item_name;;?></a>
				 <?php } ?>
				 </b></td>
                  
                  <td><?php echo $report_date; ?> </td> 
				
                   <td>
					 <?php 
						if($item->requested_time=="")
							$timerequested ="Not Avilable";
						else 
							$timerequested =$item->requested_time; 
					 echo $timerequested ?> 
				   </td> 
				    <td>
					 <?php
						echo	  $item->strength ;
						 ?> 
				   </td> 
				    <td>
					 <?php
							$status = ucfirst($item->dpc_approved);
							 
					 echo $status; ?> 
				   </td> 
				    <td>
					  <?php
							 $status = ucfirst($item->item_status);
							if($status=="")
							{
								?><a href="<?php echo site_url('specials/approval_entryform/'.$encoded_item_id );?>" class="btn btn-info  noprint" >Send for Approval</a><?php
							}else {
							 
							switch($status)
								{
									case 'Approved':
													echo "Permitted";
													break;
									case 'Not Approved':
														echo "Not Permitted";
									
												break;
								}
								
							}?> 
				   </td> 
				     
                 
                </tr>
				 <?php 
				 
					$sno++;	 
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
      "searching": true,
      "ordering": false,
      "info": true,
	  
      "autoWidth": true
    });
  });
</script>
	 