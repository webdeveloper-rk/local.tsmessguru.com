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

<h3>School   : <?php echo $school_info->name;?></h3>
  <a href="<?php echo site_url('admin/subadmin/itembalancesform');?>"><b>Go back</b></a>
 

<div class="box">
            <div class="box-header">
             
            </div>
            <!-- /.box-header -->
            <div class="box-body">
               
			  
			  <table id="example1" class="table table-bordered table-striped dataTable" role="grid" aria-describedby="example1_info">
                <thead>
                <tr role="row">
				<th class="sorting_asc" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-sort="ascending" 
				aria-label="Rendering engine: activate to sort column descending" style="width: 126px;">SNO</th>
			 
				
				 <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" 
				aria-label="Engine version: activate to sort column ascending" style="width: 106px;">Item Name</th> 
				
				<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" 
				aria-label="Platform(s): activate to sort column ascending" style="width: 139px;">Closing Balance</th>
				 
				</tr>
                </thead>
                <tbody>
                 <?php 
				 $no =1 ;
				 foreach($rset->result() as $itemdetails) { 

						// print_a($itemdetails);	
			 ?>                <tr role="row" class="odd">
                  <td class="sorting_1"> 
				 <b> <?php echo  $no;// $itemdetails->item_id;?></b></td>
                  
				
                   <td>
					 <?php echo $items_names[$itemdetails->item_id]; ?> 
				   </td> 
				    <td>
					 <?php echo $itemdetails->closing_quantity; ?> 
				   </td> 
				     
                </tr>
				 <?php 
				  $no++;
				 } ?>
				
                </tbody>
                
              </table>
			  
            </div>
            <!-- /.box-body -->
          </div>
		  
	 