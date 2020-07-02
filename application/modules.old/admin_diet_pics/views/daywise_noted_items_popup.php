  <link rel="stylesheet" href="<?php echo site_url();?>bootstrap/css/bootstrap.min.css?id=906789">  <!-- Font Awesome -->  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">  <!-- Ionicons -->  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">  <!-- Theme style -->  <link rel="stylesheet" href="<?php echo site_url();?>dist/css/AdminLTE.min.css">  <!-- AdminLTE Skins. Choose a skin from the css/skins       folder instead of downloading all of them to reduce the load. -->  <link rel="stylesheet" href="<?php echo site_url();?>dist/css/skins/_all-skins.min.css">  <!-- iCheck -->  <link rel="stylesheet" href="<?php echo site_url();?>plugins/iCheck/flat/blue.css">  <!-- Morris chart -->  <link rel="stylesheet" href="<?php echo site_url();?>plugins/morris/morris.css">  <!-- jvectormap -->  <link rel="stylesheet" href="<?php echo site_url();?>plugins/jvectormap/jquery-jvectormap-1.2.2.css">  <!-- Date Picker -->  <link rel="stylesheet" href="<?php echo site_url();?>plugins/datepicker/datepicker3.css">  <!-- Daterange picker -->  <link rel="stylesheet" href="<?php echo site_url();?>plugins/daterangepicker/daterangepicker.css">  <!-- bootstrap wysihtml5 - text editor -->  <link rel="stylesheet" href="<?php echo site_url();?>plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css"><style>
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
				aria-label="Platform(s): activate to sort column ascending" style="width: 139px;">Total Quantity</th><th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" 				aria-label="Platform(s): activate to sort column ascending" style="width: 139px;">Avg Price</th>
				 	<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" 				aria-label="Platform(s): activate to sort column ascending" style="width: 139px;">Total Amount</th>													 
				</tr>
                </thead>
                <tbody>
                 <?php 
				 
				 foreach($rset->result() as $school) {  ?>               				 <tr role="row " class="odd ">
                   
				    <td> <?php echo $school->telugu_name."-".  $school->item_name; ?>  
				   </td> 
				    <td>
					<?php echo number_format( $school->total_qty,2 ,'.', '') ;   ?>  
				   </td> 					<td>					<?php echo number_format( $school->total_price/$school->total_qty,2 ,'.', '') ; ?>  				   </td> 
				      <td>					<?php echo number_format($school->total_price,2, '.', '') ; ?>  				   </td> 
                </tr>
				 <?php } ?>
				
                </tbody>
                
              </table>
			  
            </div>
            <!-- /.box-body -->
          </div>
		  </div>
	 