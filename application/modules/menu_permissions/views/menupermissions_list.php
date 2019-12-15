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

<h3>Roles - Menu Permissions</h3>
 

<div class="box">
            <div class="box-header">
             
            </div>
            <!-- /.box-header -->
            <div class="box-body">
               
			  
			  <table id="example1" class="table table-bordered table-striped dataTable" role="grid" aria-describedby="example1_info">
                <thead>
                <tr role="row">
				<th class="sorting_asc" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-sort="ascending" 
				aria-label="Rendering engine: activate to sort column descending" style="width: 126px;">Role Name</th>
		 
				<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" 
				aria-label="CSS grade: activate to sort column ascending" style="width: 75px;">Action</th></tr>
                </thead>
                <tbody>
                 <?php 
				 
				 foreach($rset->result() as $role) {
						$enc_role_id =  $this->ci_jwt->jwt_web_encode($role->role_id);	
				 ?>                <tr role="row" class="odd">
                  <td class="sorting_1"><a href='<?php echo site_url();?>menu_permissions/update/<?php echo $enc_role_id;?>'>
				  <?php echo $role->role_title;?></a></td>
             
                  <td><a href='<?php echo site_url();?>menu_permissions/update/<?php echo $enc_role_id;?>'>Permissions</a></td>
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
	 