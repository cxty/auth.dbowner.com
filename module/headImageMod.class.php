<?php
class headImageMod{
	public function head(){
		$len = $_GET['len'];
	
		if($_GET['type'] != 1){
			$path = __PUBLIC__.'/images/mod-avatar-custom.png';
		}else{
			$path = __PUBLIC__.'/images/mod-avatar-custom.png';
		}
		
		$src_img = $path;
		$base = '/cache/images/default_bb.png';
		$dst_img = dirname(dirname(__FILE__)).$base;
		
		include(dirname(dirname(__FILE__)).'/include/lib/CutPicture.class.php');
		
		$CutPicture = new CutPicture();
		
		$picture = $CutPicture->img2thumb($src_img,$dst_img,$len,$len);
		
		echo __ROOT__.$base;
		
		//echo '<img src="'.$path.'" width="'.$width.'px" height="'.$len.'px"  />';
	}
}