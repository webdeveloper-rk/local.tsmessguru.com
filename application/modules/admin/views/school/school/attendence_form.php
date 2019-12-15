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

</style><?php  echo $this->session->flashdata('message');

$errors = validation_errors();
if($errors !=""){
?>
 <div class="validation_errors"><?php echo validation_errors(); ?>  </div>
<?php } ?>
<div> <br><br><a class="btn btn-primary" href='<?php echo site_url('admin/school/attendencelist');?>'>Attendance list<a/><br><br></div>
<div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title"><b>Attendance entry</b></h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
			 <?php   $attributes = array('class' => 'email', 'id' => 'myform');
echo form_open('admin/school/attendence', $attributes); 

$from_date = date('m-d-Y'); 
	?>
			 
              <div class="box-body">
                 
				    <!-- Date -->
              <div class="form-group">
                <label>From Date:</label>

                <div class="input-group date">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                  <input type="text" name="date"   required   value=""   class="form-control pull-right datepicker"  >
                </div>
                <!-- /.input group -->
              </div>
			  
			   <!-- Date -->
				 
				 
                 <div class="form-group">
                  <label for="exampleInputEmail1">Attendance Present</label>
                  <input type="text" name="attendence" value="" class="form-control"  required     id="exampleInputEmail1" placeholder="Enter number of presented students">
                </div>
               
              </div>
              <!-- /.box-body -->

              <div class="box-footer">
			  <input type="hidden"  name="action"    value="submit">
                <button type="submit" class="btn btn-primary">Submit</button>
              </div>
           <?php form_close(); ?>
			 
			
          </div>
 <script>
  $( function() {
			$( ".datepicker" ).datepicker({ 
			startDate: '09-01-2016',
			endDate: '+0d'});
  } );
  </script>