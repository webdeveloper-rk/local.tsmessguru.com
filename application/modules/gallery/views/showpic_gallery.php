 <link rel="stylesheet" href="<?php echo site_url();?>dist/css/lightbox.min.css">
 <aside class="right-side">  
 
    <h2><?php echo $school_info->name;?> -<?php echo $date_choosen;?> </h2>
	
	<?php $foodsessions = array("1"=>"Breakfast","2"=>"Lunch","3"=>"Snacks","4"=>"Dinner");
	foreach($foodsessions as $key=>$val)
	{
		?>
    <h3><?php echo $val;?></h3>
    <div>
	<?php 
	if($key ==  1 )
	{
		$picsdatan = $pics_data1;
	}
	if($key == "2")
	{
		$picsdatan = $pics_data2;
	}
	if($key == "3")
	{
		$picsdatan = $pics_data3;
	}
	if($key == "4")
	{
		$picsdatan = $pics_data4;
	}
	$rowscount = $picsdatan->num_rows();
	foreach($picsdatan->result() as $picrow) { ?>
	 
      <a class="example-image-link" href="<?php echo site_url();?>foodgallery/<?php echo $picrow->food_pic; ?>" data-lightbox="example-set" data-title="<?php echo $foodsessions[$picrow->food_session_id]. " - " . $picrow->fooditem_title; ?>"  title="<?php echo $foodsessions[$picrow->food_session_id]. " - " . $picrow->fooditem_title; ?>"><img class="example-image" width="200" height="200" src="<?php echo site_url();?>foodgallery/<?php echo $picrow->food_pic; ?>" alt=""/></a>
      
	<?php } if($rowscount==0) { echo "<h4>No records Found</h4>";}  ?>
	 </div>
	<?php } ?>
  </section>

   

  <script src="<?php echo site_url();?>dist/js/lightbox-plus-jquery.min.js"></script>
	 </aside>