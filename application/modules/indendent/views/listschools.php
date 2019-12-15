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

<h3>Schools - <?php echo $open_date;?> </h3>
 

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
				<!--<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" 
				aria-label="Browser: activate to sort column ascending" style="width: 159px;">Current Balance</th>-->
				 
				
				
				 
				 <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" 
				aria-label="CSS grade: activate to sort column ascending" style="width: 75px;">Action</th>
				 
				</tr>
                </thead>
                <tbody>
                 <?php 
				 
				 foreach($rset->result() as $school) { ?>                <tr role="row" class="odd">
                  <td class="sorting_1">
				  <?php if( $allowed_to_update==true){ ?>
				 <a href='<?php echo site_url();?>opening_balance/opening_balance_admin/listitems/<?php echo $school->school_id;?>'> 
				  <?php echo $school->school_code."-".$school->name;?><a/><?php } else { echo  $school->school_code."-".$school->name;}?>
				  
				  
				  </td>
                  
				
                  
				  
                 <td>  <a href='<?php echo site_url();?>opening_balance/opening_balance_admin/listitems/<?php echo $school->school_id;?>'>Open</a> </td><?php }  ?>
                </tr>
				 
				
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
	 