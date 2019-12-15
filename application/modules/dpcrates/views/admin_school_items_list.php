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
<h3><?php echo $school_name;?> -  DPC RATES</h3>
 <table width="200px">
 <tr style='height:50px'>
	<td>Submitted status :</td>
	<td>
	<span style="color:#FFFFFF;font-weight:bold;background-color:<?php echo $bg_colors[$school_submited];?>;padding:10px;">
	<?php if($school_submited==true){ echo "Submitted";}else{ echo "Not submitted";}?> </span></td>
 </tr>
 <?php if($school_submited==true ) {

 ?>
<tr style='height:50px'>
	<td>Approved status : <?php //echo  $dpc_approved;?></td>
	<td> <span style="color:#FFFFFF;font-weight:bold;background-color:<?php echo $bg_colors[$dpc_approved];?>;padding:10px;">
<?php 

		if( $dpc_approved==0){ echo "Pending";}
		if( $dpc_approved==1){ echo "Approved";}
		if( $dpc_approved==-1){ echo "Rejected";}
		 
			?> </span>
 	</td>
 </tr>
 <?php } ?>
	
</table>

<div class="box">
            <div class="box-header">
					 <div>
					 <div>
					 
					 
					<?php if($dpc_approved == 0 ) { ?>
					  
						 <a href='<?php echo site_url('dpcrates/admin/reject/'.$school_id)?>' onclick='return confirm("Are you sure to Reject?")' 
									class='btn btn-primary '>Reject</a>
					  &nbsp;
								 <a href='<?php echo site_url('dpcrates/admin/approve/'.$school_id)?>' onclick='return confirm("Are you sure to Approve ?")' 
									class='btn btn-primary pull-right'>Approve</a>	
					<?php } ?>
									</div>
					  
									
									</div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
               
			 
			  <table id="example1" class="table table-bordered table-striped dataTable" role="grid" aria-describedby="example1_info">
                <thead>
                <tr role="row">
				<th class="sorting_asc" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-sort="ascending" 
				aria-label="Rendering engine: activate to sort column descending" style="width: 126px;">Item Name</th>
				 
				<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" 
				aria-label="Platform(s): activate to sort column ascending" style="width: 139px;">Rate</th>
				<?php if($allow_to_modify==true) { ?>
				<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" 
				aria-label="CSS grade: activate to sort column ascending" style="width: 75px;">Action</th>
				<?php } ?>
				</tr>
                </thead>
                <tbody>
                 <?php 
				 
				 foreach($rset->result() as $item) { ?>                <tr role="row" class="odd">
                  <td class="sorting_1">
				  <?php if($allow_to_modify==true) { ?><a href='<?php echo site_url();?>dpcrates/admin/dpc_entryform/<?php echo $school_id;?>/<?php echo $item->item_id;?>'>
				  <?php echo $item->item_name;?></a>
				  <?php } else { echo $item->item_name;} ?>
				  </td>
                  
              <td>
					<?php echo $item->amount;?>
				   </td> 
				 
                  
                  <?php if($allow_to_modify==true) { ?>
                  <td><a href='<?php echo site_url();?>dpcrates/admin/dpc_entryform/<?php echo $school_id;?>/<?php echo $item->item_id;?>'>Update</a></td><?php } ?>
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
	 