<section class="content">
<div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">School Strength & Amount</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
             
			<form   role="form" class="form-horizontal" id="updateform" action=""  method="post" onsubmit="return validatefrm(this)">
              <div class="box-body">
				  <div id=""  ><?php echo $this->session->flashdata('message');;?></div>

			  <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">School Strength</label>

                  <div class="col-sm-10">
                    
					<?php if( $school_data->amount_updated==0 || ip_allowed_to_edit($this->input->ip_address())){?>
					<input type="text" class="form-control" id="strength" placeholder="Enter School Strength" value="<?php echo $school_data->strength; ?>" name="strength" required>  
                    <?php } else { echo $school_data->strength;}  ?>
					
					<input type="hidden"   name="action" value="updateprice" required>  
                  </div>
                </div>
				 <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Daily amount per stundent</label>
<div class="col-sm-10">
<?php if( $school_data->amount_updated==0|| ip_allowed_to_edit($this->input->ip_address())){?>
                    <input type="text" class="form-control" id="price" placeholder="Enter Amount" name="price"  value="<?php echo $school_data->daily_amount; ?>" required>  
                      <?php } else { echo $school_data->daily_amount;}  ?>
                  </div>
                </div>
				 
               
           
              </div>
              <!-- /.box-body -->
			  <?php if( $school_data->amount_updated==0 || ip_allowed_to_edit($this->input->ip_address())){?>
              <div class="box-footer">
                
                <button type="submit" class="btn btn-info pull-right">Update</button>
              </div>
			  <?php } ?>
              <!-- /.box-footer -->
            </form>
          </div>
 
</section>

<!-- jQuery 1.10.2 -->
<script src="<?php echo site_url(); ?>assets/admin/js/jquery-1.10.2.min.js"></script>
<script src="<?php echo site_url(); ?>assets/admin/js/jquery.form.js"></script>
 <script>
 function validatefrm(frm)
 {
	if(frm.strength.value.trim()=="")
	{
		alert("Please enter school strength");
		frm.strength.focus();
		return false;
	}
	if(frm.price.value.trim()=="")
	{
		alert("Please enter Amount");
		frm.price.focus();
		return false;
	}	
	if(isNaN(frm.strength.value.trim()))
	{
		alert("Please enter valid school strength");
		frm.strength.focus();
		return false;
	}
	if(isNaN(frm.price.value.trim()))
	{
		alert("Please enter valid Amount");
		frm.price.focus();
		return false;
	}	
 }
 
 </script>
     