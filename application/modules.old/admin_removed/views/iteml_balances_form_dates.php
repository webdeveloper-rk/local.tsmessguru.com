<?php if(!isset($school_code)){$school_code='';}?>
<style>
  .bold{
	font-weight:bold
}
</style>
<section class="content">
<div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Item Balance Report </h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
             
			<form   role="form" class="form-horizontal"   action=""  method="post" onsubmit="return validate(this)">
              <div class="box-body">
				  <div id="changepwdnotifier"></div>

			  <div class="form-group">
				 
				 <label for="inputEmail3" class="col-sm-2 control-label">Choose Item</label>

                  <div class="col-sm-10">
				 
                    <select name="items_id" id="items_id" class="form-control"  >
                      <?php foreach($items->result() as $item) {
						echo "<option value='".$item->item_id."'>".$item->item_name."-".$item->telugu_name."</option>";
					}

						?>
                    </select>
				  
				 
                  </div>
				  
                </div>
				 
				   <div class="form-group">
				 
				 <label for="inputEmail3" class="col-sm-2 control-label">From date</label>

                  <div class="col-sm-10">
							<input type="text" name="from_date" id="from_date"  required value="" class="datepicker">
                    </select>
				  
				 
                  </div>
				  
                </div>
				
				  <div class="form-group">
				 
				 <label for="inputEmail3" class="col-sm-2 control-label">To Date</label>

                  <div class="col-sm-10">
				 <input type="text" name="to_date" id="to_sate" required  value="" class="datepicker">

						 
                    </select>
				  
				 
                  </div>
				  
                </div>
           
              </div>
              <!-- /.box-body -->
              <div class="box-footer">
                
                <button type="submit" class="btn btn-info pull-right">Get Report</button>
              </div>
              <!-- /.box-footer -->
            </form>
			 
 
 
				
			    
			 
          </div>
 
</section>

<!-- jQuery 1.10.2 -->
 
     
<script type="text/javascript">
 
    function validate(form) {
       
	   if(form.school_code.value.trim()=="")
	   {
		   alert("Please enter school_code");
		   form.school_code.focus();
		   return false;
	   }
	 
    }
</script>
 <script>
  $( function() {
			$( ".datepicker" ).datepicker({ 
			format: "dd/mm/yyyy",
			startDate: '01-01-2017',
			endDate: '+0d'});
  } );
  </script>

