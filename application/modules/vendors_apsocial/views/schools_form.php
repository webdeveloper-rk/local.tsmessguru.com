<script src="<?php echo site_url();?>js/bootbox.min.js"></script>
 
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
.new{
	display:none;
}
.recal
{
	font-weight:bold;
	color:#2C8C02;
}

.box.box-primary {
    border-top-color: #3c8dbc;
    padding: 10px;
}
</style>
<?php echo $this->session->flashdata('message');?>
	 
<div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title"><b>Vendors :: Schools</b></h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
 <?php 
echo $this->session->flashdata("message");
?>
  
  <div class="box-body">
 
    
				 <table class="responsive table">
						   <thead>
							  <tr>
								<td>School Name</td>
							    <td>Action</td> 
							    <td>Download</td> 
							</tr>
						   </thead>
						   <tbody>
						  
							<?php foreach($schools_rs->result() as $row) { ?>
							<tr><td><?php echo  $row->school_code." - ".  $row->name;  ?></td><td><a href="<?php echo site_url();?>vendors_apsocial/admin/confirm_vendor/<?php echo  $row->school_id;?>">View</td>
							<td>
								<?php $drs =  $this->db->query("select * from tw_vendors_confirmation where  school_id=? and is_dco_confirmed='1'  ",array($row->school_id));
								if($drs->num_rows()==0){ echo "Report Not Generated";}else{
										$pdf_path = $drs->row()->pdf_path;
									?>  
									<a href='<?php echo site_url();?>assets/vendors_pdfs/<?php echo $pdf_path;?>' class='btn btn-info ' target="_blank" >Download</a> 
			 <?php 
								}?>
							</td>
							</tr>
						 
							<?php } ?>
						  
					 </tbody>
					 </table>
                
                </div>
				 
                 
 
   
              <div class="box-footer">
			  <input type="hidden"  name="action"    value="submit">
			<div class='error_div'></div>
                <button type="submit" class="btn btn-primary">Submit</button>
              </div>

            
			 
			
          </div>
		   
		 
		  