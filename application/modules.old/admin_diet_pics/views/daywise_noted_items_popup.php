
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
.rowred td{
	color:#FF0000;
	font-weight:bold;
}
</style>

<div style="margin:30px">
<?php echo $this->session->flashdata('message');?>

<h3><?php echo $sname;?> - items list  on <?php echo $rdate;?>  </h3>
<b> Total items count : <?php echo $rset->num_rows();?></b>  
 

<div class="box">
            <div class="box-header">
             
            </div>
            <!-- /.box-header -->
            <div class="box-body">
               
			  
			  <table id="example1" class="table table-bordered table-striped dataTable" role="grid" aria-describedby="example1_info" style="width:500px">
                <thead>
                <tr role="row">
				 
				<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" 
				aria-label="Platform(s): activate to sort column ascending" style="width: 139px;">Item Name</th>
				<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" 
				aria-label="Platform(s): activate to sort column ascending" style="width: 139px;">Total Quantity</th>
				 	<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" 
				</tr>
                </thead>
                <tbody>
                 <?php 
				 
				 foreach($rset->result() as $school) {  ?>               
                   
				    <td> <?php echo $school->telugu_name."-".  $school->item_name; ?>  
				   </td> 
				    <td>
					<?php echo number_format( $school->total_qty,2 ,'.', '') ;   ?>  
				   </td> 
				      <td>
                </tr>
				 <?php } ?>
				
                </tbody>
                
              </table>
			  
            </div>
            <!-- /.box-body -->
          </div>
		  </div>
	 