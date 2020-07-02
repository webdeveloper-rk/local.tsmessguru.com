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

</style><?php  echo $this->session->flashdata('message');?>   
<?php 
// echo "<pre>";print_r($crud);echo "</pre>";die;
foreach($crud->css_files as $file): ?>
	<link type="text/css" rel="stylesheet" href="<?php echo $file; ?>" />
<?php endforeach; ?>
<?php foreach($crud->js_files as $file): ?>
	<script src="<?php echo $file; ?>"></script>
<?php endforeach; ?>
<div style="margin-top:10px"></div>
<div style="width:94%;margin:auto">

<div><h2><?php echo $crud->title;?></h2></div>
<?php if(isset($extra_content)){ echo $extra_content;}?>
<?php echo $crud->output; ?></div>

