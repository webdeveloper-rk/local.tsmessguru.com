<?php 
<h3><?php echo $school_info->school_code." - " .$school_info->name;?> - Supplier List</h3>
 <style>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
<div class="box">
          
            <!-- /.box-header --><br><br>
            <div class="box-body">
               <?php if($vendors->num_rows()==0){
			  <span class='noprint'><b><h4>Total Supplier  : <?php echo $vendors->num_rows();?></h4></span>
			  <table id="example1" class="table vendors table-bordered">
	 