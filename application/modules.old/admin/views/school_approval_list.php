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

</style>


<?php echo $this->session->flashdata('message');?>

<h3><?php echo $report_date;?> DPC Approval List</h3>
 

<div class="box">
            <div class="box-header">
             
            </div>
            <!-- /.box-header -->
            <div class="box-body">
               
			  
			  <table id="example1" class="table table-bordered table-striped dataTable" role="grid" aria-describedby="example1_info">
                <thead>
                <tr role="row">
				<th class="sorting_asc" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-sort="ascending" 
				aria-label="Rendering engine: activate to sort column descending" style="width: 126px;">School Name</th>
				 
				 <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" 
				aria-label="Engine version: activate to sort column ascending" style="width: 106px;">Submitted On </th> 
				
				<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" 
				aria-label="Platform(s): activate to sort column ascending" style="width: 139px;">School  Status </th>
				<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Platform(s): activate to sort column ascending" style="width: 139px;">DCO Status </th>
				<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Platform(s): activate to sort column ascending" style="width: 139px;">DCO Approved Date </th>
				<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" 
				aria-label="Platform(s): activate to sort column ascending" style="width: 139px;">Action</th>
				 
				
				</tr>
                </thead>
                <tbody>
                 <?php 
				 
				 foreach($schools_list->result() as $school_data) {  ?>                <tr role="row" class="odd">
                  <td class="sorting_1"> 
				 <b> <?php echo $school_names[$school_data->school_id];?></b></td>
                 
                  <td><?php echo  $school_data->school_submited_date; ?> </td> 
                  <td><?php    if($school_data->school_submitted==1) { echo "Submitted";}else { echo "Not Submitted";}   ; ?> </td> 
                  <td><?php    if($school_data->dpc_approved==1) { echo "Approved";}else { echo "Not Approved";}   ; ?> </td> 
                  <td><?php echo  $school_data->dpc_approved_time; ?> </td> 
                  <td><a href='<?php echo site_url();?>admin/tsdco/viewschoolrates/<?php echo md5($school_data->school_id); ?>'>View</a></td> 
				
                  
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
      "searching": false,
      "ordering": true,
      "info": true,
	  
      "autoWidth": true
    });
  });
</script>
	 