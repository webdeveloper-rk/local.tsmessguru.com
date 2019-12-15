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
 
 
		  <div class="box box-danger">
                <div class="box-header with-border">
                  <h3 class="box-title"><b><?php echo $school_name;?> </b>- <?php echo $year;?> Diet Pictures [ <?php echo $edate;?>]  </h3>
					<a href='javascript:window.history.back();' class='btn btn-info  '>Goback</a>
                  <div class="box-tools pull-right">
                    <span class="label label-danger"><?php echo $pics_rset->num_rows();?>  Pics uploaded</span>
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                    
                    
                  </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body no-padding">
                  
                    <?php foreach($pics_rset->result() as $row) { 
						$url =  $pic_main_path.$row->food_pic;;
						
						//hard codeed 
						 //$url = "https://annapurna.in.net/foodgallery/".$row->food_pic;
					?>
					<div   class='purchase_box'>
                     <a data-fancybox="gallery"  class='fancybox' title = "<?php echo  $row->fooditem_title;  ?>" href="<?php echo $url;?>"> <img title = "<?php echo  $row->fooditem_title;  ?>"src="<?php echo $url;?>" alt="Purchase Bills" width="200px" height="200px">  </a>
                     
                      <span class="users-list-date" style='color:#FFFFFF;text-transform:capitalize'><?php echo  $row->fooditem_title;  ?></span>  
					  <span class="users-list-date" style='color:#FFFFFF;'><?php echo date("d-F-Y h:i:s A",strtotime($row->uploaded_date));?></span>
					   <span class="users-list-date" style='color:#FFFFFF;text-transform:uppercase'><?php echo $row->location_addr;?>
					   <br><a style="color:#FFFF;" target="blank" href='https://www.google.com/maps/?q=<?php echo $row->location_latitude;?>,<?php echo $row->location_langitude;?>'>Google Location</a>
					  </span>
                    </div>
					<?php } ?>
                     
                   <?php if($pics_rset->num_rows()==0) { ?>
				  <div   style="width:100%;height:50px;padding:10px;font-weight:bold;">
                      No Pics Uploaded
                    </div>
				  <?php } ?>
				  
				  
                  <!-- /.users-list -->
                </div>
                <!-- /.box-body -->
                 
                <!-- /.box-footer -->
              </div> 