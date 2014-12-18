<?php
/**
 * Emulate PEAR
 *
 * @author William
 * @package Core
 */

	/**
	 * OS Windows
	 */
	define('OS_WINDOWS', false);

/**
 * PEAR
 *
 * @author William
 * @package Core
 */	
class PEAR {
	
	/**
	 * Constructor
	 */
	public function PEAR(){
		
		return true;
		
	}
	
	/**
	 * Is Error
	 *
	 * @param integer $err
	 * @return boolean
	 */
	public function isError($err){
		
		if($err < 0){
			return true;
		}
		
		return false;
	
	}
	
	/**
	 * Raise Error
	 *
	 * @param string $err_description
	 * @param integer $err_number
	 * @return boolean
	 */	
	public function raiseError($err_description, $err_number){
		
		trigger_error($err_number . ': ' . $err_description);
		
		return false;
		
	}
	
}

?>
