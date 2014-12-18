<?php
/**
 * Check Class
 *
 * @author Masilicondrio
 * @package Core
 */

/**
 * Check Class
 *
 * @package Core
 */
class Check{
	
	/**
	* Checks if a U.S. phone number input is correctly formatted:
	* 	XXXXXXXXX			(ten digits)
	*
	* @author Kallikanzaros
	* @param string $value
	* @param integer $min
	* @return boolean
	*/
	public static function validPhoneNumber($value, $min = 10){
	
	
		if(preg_match('/[^0-9]/', $value)){
			return false;
		}else {
			if(strlen($value) != $min){
				return false;
			}else {
				
				return true;
				
			}
		}
	
	}
	
	/**
	* Checks if a ZIP Code input is correctly formatted:
	* 	XXXXX			(five digits) OR
	* 	XXXXX-XXXX		(five digits, hyphen and four more digits)
	* 
	* @author Kallikanzaros
	* @param string $value
	* @param integer $min
	* @return boolean
	*/
	public static function validZipCode($value, $min = 5){
		if(preg_match('/(^\d{5}(-\d{4})?$)|(^[ABCEGHJKLMNPRSTVXYabceghjklmnprstvxy]{1}\d{1}[A-Za-z]{1} *\d{1}[A-Za-z]{1}\d{1}$)/', $value)){
			return true;
		}else{
			return false;
		}
	
	}
		
	/**
	 * Check Number
	 *
	 * @param string $value
	 * @param integer $min
	 * @return boolean
	 */
	public static function number($value, $min = 0){
		
		if(!preg_match('/^[+-]?[0-9]*\.?[0-9]+$/', $value)){
			return false;
		}else {
			if(strlen($value) < $min){ 
				return false; 
			}else {
				return true;
			}
		}	
				
	}
	
	/**
	 * Check Lenght
	 *
	 * @param string $value
	 * @param integer $min
	 * @param integer $max
	 * @return boolean
	 */
	public static function lenght($value, $min = 0, $max = 0){
		
		if(strlen($value) < $min){ 
			return false; 
		}
		
		if($max != 0 && strlen($value) > $max){ 
			return false; 
		}

		return true;
		
	}		
	
	/**
	 * Check Email
	 *
	 * @param string $email
	 * @return boolean
	 */
	public static function email($email){

		if(preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)+$/", $email)){
			
			if(function_exists('checkdnsrr')){
				
				list($alias, $domain) = explode("@", $email);
				
				if(checkdnsrr($domain, "MX")){							
					return true;			   
				}else {			 
					return false;   
				}

			}else {
				return true;
			}

		}else{
			return false;  					  
		} 

	}
	/**
	 * Check Mac address
	 * 
	 * @param string $mac_address
	 * 
	 * return boolean
	 * 
	 */
	public static function mac($mac_address){
		if(preg_match('/^[a-f0-9]{2}:[a-f0-9]{2}:[a-f0-9]{2}:[a-f0-9]{2}:[a-f0-9]{2}:[a-f0-9]{2}$/', $mac_address)){
	 	   return true;
		}else{
		   return false;
		}
	}
}

?>