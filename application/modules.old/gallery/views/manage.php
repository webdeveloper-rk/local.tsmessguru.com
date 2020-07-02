 <aside class="right-side">  
<?php 
foreach($output->css_files as $file): ?>
	<link type="text/css" rel="stylesheet" href="<?php echo $file; ?>" />
<?php endforeach; ?>
<?php foreach($output->js_files as $file): ?>
	<script src="<?php echo $file; ?>"></script>
<?php endforeach; ?>
 
 
 
	<div style="padding-left:20px;padding-top:20px">
		 
						<div style='height:20px;'>&nbsp;</div>  
						<div style=''><h2><?php echo $output->title;?></h2></div>  
						<div>
							<?php echo $output->output; ?>
						</div> 
	
	
	</div>
	</aside>