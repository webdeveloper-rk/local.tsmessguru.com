<?php if(!isset($school_code)){$school_code='';}?>
<style>
  .bold{
	font-weight:bold
}
</style>
<section class="content">
<div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">School BillCollection List </h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
             
			<form   role="form" class="form-horizontal"   action=""  method="post" onsubmit="return validate(this)">
              <div class="box-body">
				  <div id="changepwdnotifier"></div>

			  <div class="form-group">
				 
				 <label for="inputEmail3" class="col-sm-2 control-label">School Code</label>

                  <div class="col-sm-10">
				 
                   <!-- <input type="text" class="form-control" value="<?php echo $school_code;?>" id="school_code" placeholder="Enter school Code" name="school_code"  required>  
				  -->
				  <select name="school_code" id="school_code" required >
				  <option value=''>Select School </option>
				    <?php 
					 			$uid  = $this->session->userdata("user_id");
					$district_id  = $this->session->userdata("district_id");
					if($uid ==2)
					{
										$school_rs = $this->db->query("SELECT s.name as sname,d.name as dname ,school_code ,d.district_id FROM schools s inner join districts d on d.district_id = s.district_id and s.is_school='1'");	
					}
					else
					{
											//$school_rs = $this->db->query("select * from schools where district_id='$district_id' and name not like 'coll%' ");
											$school_rs = $this->db->query("SELECT s.name as sname,d.name as dname ,school_code ,d.district_id FROM schools s inner join districts d on d.district_id = s.district_id and s.is_school='1' and d.district_id='$district_id'");	
					}

					foreach($school_rs->result() as $row)
					{
						echo "<option value='".$row->school_code."'>".$row->school_code."-" .$row->sname." - ".$row->dname."</option>" ;
					}
			 ?>
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
			startDate: '01-01-2017',
			endDate: '+0d'});
  } );
  </script>

