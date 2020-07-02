<?php
if(!isset($frame_load))
{
	$frame_load = false;
}
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Attendence</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="<?php echo site_url();?>bootstrap/css/bootstrap.min.css?id=906789">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo site_url();?>dist/css/AdminLTE.min.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="<?php echo site_url();?>dist/css/skins/_all-skins.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="<?php echo site_url();?>plugins/iCheck/flat/blue.css">
  <!-- Morris chart -->
  <link rel="stylesheet" href="<?php echo site_url();?>plugins/morris/morris.css">
  <!-- jvectormap -->
  <link rel="stylesheet" href="<?php echo site_url();?>plugins/jvectormap/jquery-jvectormap-1.2.2.css">
  <!-- Date Picker -->
  <link rel="stylesheet" href="<?php echo site_url();?>plugins/datepicker/datepicker3.css">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="<?php echo site_url();?>plugins/daterangepicker/daterangepicker.css">
  <!-- bootstrap wysihtml5 - text editor -->
  <link rel="stylesheet" href="<?php echo site_url();?>plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
  
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
	
	 <link rel="stylesheet" href="http://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
  <style>
  .validation_errors
  {
	   
    background-color: #E33439;
    width: auto;
    border: 1px solid green;
    padding: 25px;
    margin: 25px;
    color:#FFFFFF;
	border-radius: 10px;
 
  }
.responsive .logo { display: none !important; }
.main-header {
    background-color: #FFCD41;
}
.box-footer input
{
	margin-left:10px;
}
.red{
	padding-left:5px;
	color:#FF0000;
}
  </style>
</head>
<body class="hold-transition skin-yellow sidebar-mini">
 
<style>
  .bold{
	font-weight:bold
}
</style>
<section class="content">
<div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Update School Attendance   </h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
             
			<form   role="form" class="form-horizontal"   action=""  method="post"  >
              <div class="box-body">
				  <div id="changepwdnotifier"></div>
 				            
			  <div class="form-group" id="div_school_code"  >
				 
				 
                  <div class="col-sm-10">
				 
                   <!-- <input type="text" class="form-control" value="<?php echo $school_code;?>" id="school_code" placeholder="Enter school Code" name="school_code"  required>  
				  
				  <select name="school_code" id="school_code" required >
				  <option value=''>Select School </option>				  <option value='all'>ALL Schools</option>
				    
			 ?>

                    </select>-->
				  
				  					<input type='hidden' name='school_code' value='all'>
				 
                  </div>
				  
                </div>
				 
				 
           	  <div class="form-group"  >				 				 <label for="inputEmail3" class="col-sm-2 control-label">Choose Date</label>                  <div class="col-sm-10">				                     <input type="text" class="datepicker"  value="<?php echo $this->input->post('attendence_date');?>" id="attendence_date" placeholder="Choose Date" name="attendence_date"  required>                    </div>				                  </div>				 
              </div>
              <!-- /.box-body -->
              <div class="box-footer">
                
                <button type="submit" class="btn btn-info pull-right">Update Attendance</button>
              </div>
              <!-- /.box-footer -->
            </form>						<?php if($frame_load==true){ ?>				<iframe width="100%" height="1000px" src="<?php echo $frame_url; ?>"></iframe>			<?php } ?>
			 
 
 
				
			    
			 
          </div>
 
</section>
<!-- jQuery 2.2.3 -->
<!--<script src="http://localhost/annapurna/plugins/jQuery/jquery-2.2.3.min.js"></script>-->
<script src="<?php echo site_url();?>assets/grocery_crud/js/jquery-1.11.1.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>

<!-- jQuery 1.10.2 -->
  
 <script>
  $( function() {
			$( ".datepicker" ).datepicker({ 
			startDate: '01-01-2017',
			dateFormat: 'dd/mm/yy',
			endDate: '+0d'});
  } );
  </script>

