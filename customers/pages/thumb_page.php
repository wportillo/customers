<?php
	require_once('main.inc.php');

	/**
	 *  Thumb Generator
	 * 
	 */
	    
		$pagina    = $apache_index->get_uri_position(1);
		
		$filename  = _get('filename');
		
		$width	   = _get('width');
		
		$height	   =  _get('height');
		
		$prop	   =  _get('prop');
		
		/**
		 * Get Uri image
		 * 
		 * @return image
		 */
		
		
		
		
		
		$thumb  = new thimg(urldecode($filename),$width, $height);

	
		if($prop!=''){
			$thumb->prop=false;		
		}
		
		if($filename!=''){
			if(!$thumb->errors){
				$thumb->generate();
			}else{
				print_r($thumb->errors);
			}
		}
?>