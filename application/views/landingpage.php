<?php $this->load->view('home-header');?>
<style>
.links{
    border: 2px solid #a1a1a1;
    padding: 10px 40px;
    background: #006BA8;
	color:#FFF;
    width: 300px;
    border-radius: 25px;
	padding:10px; 
}
.links:hover{
     width: 300px;
    background: #FFC700;
	color:#FFF;
    width: 300px;
    border-radius: 25px;
	padding:12px; 
}
.tag{
	width:500px;
}
</style>
 

<div class="container-fluid" style="background-color:white;">
<div style="height:100px">&nbsp;</div>
<table width="500px" height="150px" align="center">
<tr>
	<td><a class="links" href='<?php echo site_url('photogallery/residency');?>'><span>Residency</span></a></td>
</tr>
<tr>
	<td><div class="tag"><a  class="links"  href='<?php echo site_url('photogallery/functionhall');?>'><span class="tag">Function Hall</span></a></td>
</tr>
	
</table>
<div style="height:200px"></div>
<?php $this->load->view('footer');?>