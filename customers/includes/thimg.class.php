<?php
/**
 * Thimg
 *
 * @author William
 * @package Core
 */

/**
 * Thimg Class
 *
 * @package Core
 */
class thimg{
	
	/**
	 * File Types
	 *
	 * @var array
	 */
	public $file_types = array('jpg', 'jpeg', 'png', 'gif');
	
	/**
	 * Errors
	 *
	 * @var array
	 */
	public $errors = array();
	
	/**
	 * GD Image resource
	 *
	 * @var image_resource
	 */
	private $img;
	
	/**
	 * Image Source
	 *
	 * @var string
	 */
	private $src;

	/**
	 * Image Width
	 *
	 * @var integer
	 */
	private $width;
	
	/**
	 * Image Height
	 *
	 * @var unknown_type
	 */
	private $height;
	/**
	 * Image Prop
	 *
	 * @var boolean
	 */
	  public $prop=true;
	
	
	/**
	 * Thimg Constructor
	 *
	 * @param string $src
	 * @param integer $width
	 * @param integer $height
	 * @return boolean
	 */
	public function __construct($src, $width, $height){
		
		$width  = (int) $width;
		
		$height = (int) $height;
		
		if(!file_exists($src)){
			$this->errors[] ='File Not Found';
			return false;
		}
		
		if(!is_readable($src)){
			$this->errors[] = 'File Not Read';
			return false;
		}
		
		if($width < 5 or $height < 5){
			$this->errors[] = 'Invalid image size';
			return false;
		}
		$this->src    = $src;
		
		$this->width  = (int) $width;
		
		$this->height = (int) $height;
		
		$ext = $this->get_extension($src);
		
		if(!in_array($ext, $this->file_types)){
			$this->errors[] ='File Not suported';
			return false;
		}
		
		switch($ext){
			case 'jpg':
				$this->img = imagecreatefromjpeg($src);
				break;
				
			case 'jpeg':
				$this->img = imagecreatefromjpeg($src);
				break;
				
			case 'gif':
				$this->img = imagecreatefromgif($src);
				break;
				
			case 'png':
				$this->img = imagecreatefrompng($src);
				break;
			
		}
		
		if(!$this->img){
			$this->errors[] = 'Error Creating thumb';
			return false;
		}
								
	}
	
	/**
	 * Generate Thumbnail
	 *
	 * @param string $filename
	 * @return image
	 */
	public function generate($filename = ''){
		
		if($this->errors){
			return false;
		}
		
		$image_size    = getimagesize($this->src);
		
		$actual_width  = $image_size[0];
		
		$actual_height = $image_size[1];
		
		if($this->prop){

			 	$width_prop = $this->width / $actual_width;
			 	$height_prop = $this->height / $actual_height;
			 		
			 	if($width_prop > $height_prop){
			 		$new_height = $actual_height * $height_prop;
			 		$new_width = $actual_width * $height_prop;
			 	}else{
			 		$new_height = $actual_height * $width_prop;
			 		$new_width = $actual_width * $width_prop;
			 	}
			 
		}else{
			$new_width=$this->width;
			
			$new_height=$this->height;
		}	
			
		$img_ret = imagecreatetruecolor($new_width,$new_height); 
					
		imagecopyresampled($img_ret, $this->img, 0, 0, 0, 0, $new_width, $new_height, $actual_width, $actual_height);	
		
		if($filename == ''){
			header('Content-Type: image/jpeg');
		}
		
		return imagejpeg($img_ret, $filename, 95);
		
	}
	
	/**
	 * Get Extension
	 *
	 * @param string $src
	 * @return string
	 */
	private function get_extension($src){
		
		$ext = strtolower(array_pop(explode('.', basename($src))));
		return $ext;
		
	}
	
}
?>