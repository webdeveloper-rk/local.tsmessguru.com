<style>
.purchase_box  {
    border-radius:  3px;
    background: #33AFFF;
	padding :10px;
	margin:5px;
	width:220px;
	float:left; 
   
}
</style>
<div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Old Purchase bills</b>  </b></h3>
			  
            </div>
            <!-- /.box-header -->
            <!-- form start -->
         <?php   $attributes = array('class' => 'email', 'id' => 'myform');
echo form_open_multipart(''); 	

$errors = validation_errors();
if($errors !=""){
?>
 <div class="validation_errors"><?php echo validation_errors(); ?>  </div>
<?php } 
	
	
	
?><div style='padding:10px;'><?php   echo $this->session->flashdata('message'); ?></div>
              <div class="box-body">
			  
			   <div class="form-group">
                  <label for="exampleInputEmail1">Choose Month & Year *</label>
                  <br>
				 <select  name='month'  required style="width:200px;">
						<option value=''>Select Month</option>
						<?php foreach( $months_list as $mkey=>$mname){
							$selected_text = "  ";
								if($month == $mkey)
									$selected_text = " selected ";
						?>
						<option value='<?php echo $mkey;?>' <?php echo $selected_text;?>><?php echo $mname;?></option>
						<?php } ?>
						 
				  </select>
				   <select  ' name='year'  required style="width:200px;">
						<option value=''>Select Year</option>
						 <?php for($iyear=2017;$iyear<=date('Y');$iyear++){

								$selected_text = "  ";
								if($year == $iyear)
									$selected_text = " selected ";						 ?>
						<option value='<?php echo $iyear;?>'  <?php echo $selected_text;?>><?php echo $iyear;?></option>
						<?php } ?>
				  </select>
                </div>
               
                
              </div>
              <!-- /.box-body -->
 

              <div class="box-footer">
			  	
                <button type="submit" class="btn btn-primary">Submit</button>
			
  </div> 
           <?php form_close(); ?>
          </div>
		  
		  
		  
		  <?php if($display_result) { 
		  
		  ?>
		  
		  <div class="box box-danger">
                <div class="box-header with-border">
                  <h3 class="box-title"><?php echo $months_list[$month];?> - <?php echo $year;?> Purchase Bills </h3>

                  <div class="box-tools pull-right">
                    <span class="label label-danger"><?php echo $bills_rset->num_rows();?>  Bills uploaded</span>
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                    
                    
                  </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body no-padding">
                  
                    <?php foreach($bills_rset->result() as $row) { 
						$url = site_url()."assets/uploads/gallery/".$row->url; 
						//$url = "https://annapurna.in.net/assets/uploads/gallery/".$row->url; 
					?>
					<div   class='purchase_box'>
                     <a data-fancybox="gallery" href="<?php echo $url;?>"> <img src="<?php echo $url;?>" alt="Purchase Bills" width="200px" height="200px">  </a>
                      <a class="users-list-name" href="#"><?php echo $row->item_name;?></a>
                      <span class="users-list-date" style='color:#FFFFFF;'><?php echo $row->dateposted;?></span>
                    </div>
					<?php } ?>
                     
                   <?php if($bills_rset->num_rows()==0) { ?>
				  <div   style="width:100%;height:50px;padding:10px;font-weight:bold;">
                      No Bills Uploaded
                    </div>
				  <?php } ?>
				  
				  
                  <!-- /.users-list -->
                </div>
                <!-- /.box-body -->
                 
                <!-- /.box-footer -->
              </div>
		  
		  <?php } ?>