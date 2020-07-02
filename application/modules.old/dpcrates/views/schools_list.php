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
<?php $bg_colors = array(0=>"#FFCA33",1=>"#1BCD49","-1"=>"#FF5733");?>
<h3>School DPC RATES Approvals</h3>
  
<div class="box">
            <div class="box-header">
					 
            </div>
            <!-- /.box-header -->
            <div class="box-body">
               
			 
			  <table id="example1" class="table table-bordered table-striped dataTable" role="grid" aria-describedby="example1_info">
                <thead>
                <tr role="row">
				
				<th class="sorting_asc" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-sort="ascending" 
				aria-label="Rendering engine: activate to sort column descending" style="width: 126px;">School code</th>
				
				
				<th class="sorting_asc" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-sort="ascending" 
				aria-label="Rendering engine: activate to sort column descending" style="width: 126px;">School Name</th>
				 
				<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" 
				aria-label="Platform(s): activate to sort column ascending" style="width: 139px;">Submitted time</th>
				<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" 
				aria-label="Platform(s): activate to sort column ascending" style="width: 139px;">Status</th>
				 
				<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" 
				aria-label="CSS grade: activate to sort column ascending" style="width: 75px;">Action</th>
				 
				</tr>
                </thead>
                <tbody>
                 <?php 
				 $school_submitted_list = array('1'=>'Submited','0'=>"Not Submited");
				 $dpc_approved_list = array('1'=>'Approved','0'=>"Pending",'-1'=>'Rejected');
				 foreach($rset->result() as $item) { ?>                <tr role="row" class="odd">
                  <td class="sorting_1"><?php echo $item->school_code;?>
				  </td>
                  
              <td>
					<?php echo $item->name;?>
				   </td>   <td>
					<?php echo $school_submitted_list[$item->school_submitted];?>
				   </td> 
				   <td>
					<?php if($item->school_submitted==1) { echo $dpc_approved_list[$item->dpc_approved]; } ;?>
				   </td> 
                  
                   
                  <td><a href='<?php echo site_url();?>dpcrates/admin/school/<?php echo $item->school_id;?>'>View Dpc Rates</a></td><?php   ?>
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
       "order": [[ 1, "desc" ]],
      "info": true,
	    
      "autoWidth": true
    });
  });
</script>
	 