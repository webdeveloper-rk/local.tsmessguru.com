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
 <div style='padding:10px;'><?php   echo $this->session->flashdata('message'); ?></div>
               
		  
		  
		  
		 
		  
		  <div class="box box-danger">
                <div class="box-header with-border">
                  <h3 class="box-title"><?php echo $school_info->school_code."-".$school_info->name;?> - <?php echo $date_choosen;?> Purchase Bills OLD</h3>

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
							//$url =  "https://annapurna.in.net/assets/uploads/gallery/".$row->url; 
					?>
					<div   class='purchase_box'>
                     <a data-fancybox="gallery" href="<?php echo $url;?>"> <img src="<?php echo $url;?>" alt="Purchase Bills" width="200px" height="200px">  </a>
                      <a class="users-list-name" href="#"><?php echo $row->item_name;?></a>
                      <span class="users-list-date" style='color:#FFFFFF;'><?php echo $row->uploaded_time;?></span>
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
		  
		   