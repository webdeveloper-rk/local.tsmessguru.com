<?php $this->load->view('header');?>
<style>
.videos li{
	display:inline;
	padding:10px;
}

</style>		
		



 <table width="990" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td align="center" class="bodyshde"><table width="100%" border="0" cellpadding="0" cellspacing="0">
              <tr>
                <td align="center"><div id="fadeshow1" style="z-index:2;"></div></td>
              </tr>
              <tr>
                <td align="center" valign="top"><img src="<?php echo base_url(); ?>public/images/shade1.jpg" width="970" height="26" /></td>
              </tr>
              <tr>
                <td align="left"  class="page_links2"><?php echo $album_title;?> - Videos </td>
              </tr>
              <tr>
                <td height="500" align="left" valign="top" class="normaltext1"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td align="left" valign="top">&nbsp;</td>
                    <td align="right" valign="top">&nbsp;</td>
                  </tr>
                  <tr>
                    <td width="23%" align="left" valign="top"><table width="700" border="0" cellspacing="0" cellpadding="0">
                        
                        <tr>
                          <td>
                         
  <ul class="videos clearfix">
                         <?php
						 $c = $videos->num_rows();
						 if($c>0) {
						 foreach($videos->result() as $rec)
						 {
						  $a=$rec->youtube_url;
						  $embed=preg_replace('/.+(\?|&)v=([a-zA-Z0-9-_]+).*/', 'http://youtube.com/watch?v=$2', $a);

						  $st1=explode('v=',$embed);
						  $st2=explode('&amp;',$st1[1]);
						  
						 
						  $vidID = $st2[0];
						  $vidID= explode('&',$vidID);
						 ?>
					<li><a href="<?php echo $rec->youtube_url; ?>" rel="prettyPhoto" title="<?php echo $rec->name; ?>"><img src="http://img.youtube.com/vi/<?php echo $vidID[0];?>/0.jpg" alt="" width="250" height="250" /></a></li>
					<?php } } else { ?><div style="color:#F00;font-size:20px;"><?php echo "No Results Found"; } ?></div>
                    
                   
					
				</ul>             

						 </td>
                        </tr>
                    </table></td>
                    <td width="77%" align="right" valign="top"><table width="250" border="0" cellspacing="2" cellpadding="2">
                        <tr>
                          <td align="left" valign="top"><ul class="slidedoormenu">
                          
						   <?php foreach($albums->result() as $row) {    
						  ?>
                              <li><a href="<?php echo site_url('products/videos/'.$row->va_id);?>"><?php echo  $row->title; ?></a></li>
                              <?php } ?>
                              
                          </ul></td>
                        </tr>
                    </table></td>
                  </tr>
                </table></td>
              </tr>
              <tr>
                <td align="center" bgcolor="#255D83"><table width="98%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td height="50" align="center" class="whitecolor">&copy; 2015-2016  Milesh Marines &amp; Exports Pvt Ltd | ALL RIGHTS RESERVED </td>
                    </tr>
                </table></td>
              </tr>
              
    </table></td>
  </tr>
  <tr>
    <td align="center">&nbsp;</td>
  </tr>
</table>
<script type="text/javascript" charset="utf-8">
			$(document).ready(function(){
				$("area[rel^='prettyPhoto']").prettyPhoto();
				
				$(".gallery:first a[rel^='prettyPhoto']").prettyPhoto({animation_speed:'normal',theme:'light_square',slideshow:3000, autoplay_slideshow: false});
				$(".gallery:gt(0) a[rel^='prettyPhoto']").prettyPhoto({animation_speed:'fast',slideshow:10000, hideflash: true});
		
				$("#custom_content a[rel^='prettyPhoto']:first").prettyPhoto({
					custom_markup: '<div id="map_canvas" style="width:260px; height:265px"></div>',
					changepicturecallback: function(){ initialize(); }
				});

				$("#custom_content a[rel^='prettyPhoto']:last").prettyPhoto({
					custom_markup: '<div id="bsap_1259344" class="bsarocks bsap_d49a0984d0f377271ccbf01a33f2b6d6"></div><div id="bsap_1237859" class="bsarocks bsap_d49a0984d0f377271ccbf01a33f2b6d6" style="height:260px"></div><div id="bsap_1251710" class="bsarocks bsap_d49a0984d0f377271ccbf01a33f2b6d6"></div>',
					changepicturecallback: function(){ _bsap.exec(); }
				});
			});
			</script>
