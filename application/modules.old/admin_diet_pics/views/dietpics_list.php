<style>
.tab-pane
{
	min-height: 300px;
}


</style>
<div>

<h2><?php echo $school_name;?>  - <?php echo $edate;?>  Photos </h2>
 <div><a href="javascript:window.history.back();" class="btn btn-info">Back</a></div>
</div>
<?php 

$t = $this->db->query("select * from schools where school_id=?",array($this->session->userdata("school_id")))->row();
//print_a($t);
//echo $is_collector;
$is_collector = $t->is_collector;
if($is_collector== 1 )
{
	$this->load->view("dietpics_list_tab");
}else { ?>
 
<div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
          
              <li class=""><a href="#tab_4"  id="htab4" data-toggle="tab" >Daywise work sheet - submitting by DCO</a></li>
			       <li class=""><a href="#tab_3" data-toggle="tab" >Dashboard noted items - Certified by  DCO</a></li>
			      
				  <li class=""><a href="#tab_2"  data-toggle="tab" > Diet Pics - Deep Observation by the DCO</a></li>
             <li class=""><a href="#tab_1" data-toggle="tab" >Dashboard- Deep Observation by the DCO</a></li>
              
             
              <li class="pull-right"><a href="#" class="text-muted"><i class="fa fa-gear"></i></a></li>
            </ul>
            <div class="tab-content">
			 
              <div class="tab-pane" id="tab_1">
						<?php  $this->load->view("consumed_list_tab");?>	
              </div>
              <!-- /.tab-pane -->
              <div class="tab-pane" id="tab_2">
                <?php $this->load->view("dietpics_list_tab");?>
				
              </div>
              <!-- /.tab-pane -->
              <div class="tab-pane  " id="tab_3">
			  <div id="ajax_notes">
							<?php  $this->load->view("noted_list_tab");?>
							</div>
              </div>
			  
			   <div class="tab-pane  " id="tab_4">
							<?php $this->load->view("entry_form");?>
              </div>
		 
              <!-- /.tab-pane -->
            </div>
            <!-- /.tab-content -->
          </div>
		  <script>
		  $(document).ready(function(){
				$("#htab4").trigger("click");
				//alert("hey");
				 
});
		  </script>
		  
<?php } ?>
		  