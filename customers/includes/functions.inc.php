<?php
/**
 * General Functions
 * @author William
 * @package tvmia
 */

	/**
	 * Xml Generator
	 * @param Boolean $header
	 * @return void
	 */
	function xml_generator($header=true){
		if($header!=false){
			header('Content-type: text/xml');
		}
	}
	/**
	 * funcion para convertir un numero a decimal con X digitos
	 * @param String $number
	 * @param Int $digitos cantidad de digitos a mostrar
	 * @return Float
	 */
	function truncateFloat($number, $digitos)
	{
		$raiz = 10;
		$multiplicador = pow ($raiz,$digitos);
		$resultado = ((int)($number * $multiplicador)) / $multiplicador;
		return number_format($resultado, $digitos);
	}
	function jsonconnect($array_data){

		$data_string = json_encode($array_data);
		$ch = curl_init(JSON_HOST);
		curl_setopt($ch, CURLOPT_POST,true);
		curl_setopt($ch, CURLOPT_POSTFIELDS,"formdata={$data_string}");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	
		$return = curl_exec($ch);
	
		return $return;
	}
	
	/**
	 * Get POST var
	 *
	 * @param string $name
	 * @param string $default
	 * @return mixed
	 */
	function _post($name, $default = ''){
		
		if(!isset($_POST[$name])){
			return $default;
		}else {
			return $_POST[$name];			
		}
		
	}
	
	/**
	 * Php Serialize session
	 *
	 * @param array data
	 */
	function serialize_session($data){
			
		session_start();
			
		foreach($data as $key=>$value){
			$_SESSION[$key] = $value;
		}
			
		$session = session_encode();
			
		session_destroy();
			
		return $session;
	}
	
	/**
	 * Php Unserialize session
	 *
	 * @param array data
	 */
	function unserialize_session($session_data, $start_index=0, &$dict=null) {
	
		isset($dict) or $dict = array();
			
		$name_end = strpos($session_data, SESSION_DELIM, $start_index);
			
		if ($name_end !== FALSE) {
			$name = substr($session_data, $start_index, $name_end - $start_index);
			$rest = substr($session_data, $name_end + 1);
	
			$value = unserialize($rest); // PHP will unserialize up to "|" delimiter.
			$dict[$name] = $value;
	
			return unserialize_session($session_data, $name_end + 1 + strlen(serialize($value)), $dict);
		}
			
		return $dict;
	}
	/**
	 * Get Cookie var
	 *
	 * @param string $name
	 * @param string $default
	 * @return mixed
	 */
	function _cookie($name, $default = ''){
		
		if(!isset($_COOKIE[$name])){
			return $default;
		}else {
			return $_COOKIE[$name];			
		}
		
	}
	/**
	 * Get GET var
	 *
	 * @param string $name
	 * @param string $default
	 * @return mixed
	 */	
	function _get($name, $default = ''){
		
		if(!isset($_GET[$name])){
			return $default;
		}else {
			return $_GET[$name];
		}
	}
	
	/**
	 * Get REQUEST var
	 *
	 * @param string $name
	 * @param string $default
	 * @return mixed
	 */	
	function _request($name, $default = ''){

		if(!isset($_REQUEST[$name])){
			return $default;
		}else {
			return $_REQUEST[$name];
		}
		
	}

	/**
	 * Get SESSION var
	 *
	 * @param mixed $name
	 * @param string $default
	 * @return mixed
	 */
	function _session($name, $default = ''){
		
		if(is_array($name)){
			
			$key = $name['key'];
			
			$val = $name['val'];
			
			if(!isset($_SESSION[$key][$val])){
				return $default;
			}else {
				return $_SESSION[$key][$val];
			}
		
		}else{
			if(!isset($_SESSION[$name])){
				return $default;
			}else {
				return $_SESSION[$name];
			}
		}
	}
	
	/**
	 * Encrypt password
	 * 
	 * @param String $String
	 * @param String $key
	 * 
	 * return String encrypted
	 */
	function encrypt($string, $key) {
	   $result = '';
	   for($i=0; $i<strlen($string); $i++) {
	      $char = substr($string, $i, 1);
	      $keychar = substr($key, ($i % strlen($key))-1, 1);
	      $char = chr(ord($char)+ord($keychar));
	      $result.=$char;
	   }
	   return base64_encode($result);
	}
	/**
	 * Decrypt password
	 * @param String $String
	 * @param String $key
	 * return String encrypted
	 */
	function decrypt($string, $key) {
	   $result = '';
	   $string = base64_decode($string);
	   for($i=0; $i<strlen($string); $i++) {
	      $char = substr($string, $i, 1);
	      $keychar = substr($key, ($i % strlen($key))-1, 1);
	      $char = chr(ord($char)-ord($keychar));
	      $result.=$char;
	   }
	   return $result;
	}
	/**
	 * Check Date null
	 * 
	 * @param date
	 * 
	 * @return boolean
	 * 
	 */
	function date_null($date){
		if($date=='0000-00-00 00:00:00'){
			return true;
		}else{
			return false;
		}
	}
	/**
	 * Generate Mysql Date
	 * 
	 * @return mixed date
	 */
	function mysql_date(){
		return date('y-m-d H:i:s');
	}
	/**
	 * Add days to Mysql Date
	 * 
	 * @param date $date
	 * @param integer $dd
	 * 
	 * return date
	 */
	function add_day_mysqldate($date, $dd=0){
		
		$date2= str_replace(':', '-', $date);
		
		$date3= str_replace(' ', '-', $date2);
		
		
		$arr_date = explode('-',$date3);
		
		$date_result = date('Y-m-d H:i:s', mktime($arr_date[3],$arr_date[4],$arr_date[5], $arr_date[1], $arr_date[2]+$dd, $arr_date[0]));
		 
		return $date_result;
	}
	/**
	 * MySQL Date to Timestamp
	 *
	 * @param string $date
	 * @return integer
	 */
	function mysql_date_to_timestamp($date){
		return strtotime($date);
	}
	/**
	 * Search in array
	 *
	 * @param array $pattern
	 * @param string $search_string
	 * @return integer $key
	 */
	function search_in_array($pattern,$search_string){
		
		array_unshift($pattern,'0');
			
		return	array_search($search_string,$pattern);
	}
	/**
	 * Spanish Date to mysql date
	 *
	 * @param  date $date
	 * @return date	$mysqldate
	 */
	function spanish_date_to_mysql($date){
		$arr_fecha = explode('/',$date);
		return @date('Y-m-d', mktime('00','00','00', $arr_fecha[1], $arr_fecha[0], $arr_fecha[2]));
	}
	/**
	 * Mysql date to spanish date
	 *
	 * @param  date $mysqldate
	 * @return date	$date
	 */
	function mysql_date_to_spanish($date){
		$arr_fecha = explode('-',$date);
		return @date('j/n/Y', mktime("00","00","00", $arr_fecha[1], $arr_fecha[2], $arr_fecha[0]));
	}
	/**
	 * Mysql date to spanish date
	 *
	 * @param  date $mysqldate
	 * @return date	$date
	 */
	function mysql_timestamp_to_spanish($date){
		
		$arr_fecha = explode('-',substr($date,0,-8));
		
		$arr_time = explode(':',substr(trim($date),-8));
		
		return @date('h:i:s j/n/Y', mktime($arr_time[0],$arr_time[1],$arr_time[2], $arr_fecha[1], $arr_fecha[2], $arr_fecha[0]));
	}
	/**
	 * String to Mac Address
	 * 
	 * @param string $mac_address
	 * 
	 * return string $mac_address
	 */
	function string_to_mac($mac_address){
		return wordwrap($mac_address,2,':',true);
	}
	/**
	 * Get Document Root
	 *
	 * return string $document_root
	 */
	function get_document_root(){
	
		$document_root = explode('/', $_SERVER['DOCUMENT_ROOT']);
	
		array_pop($document_root);
	
		return implode('/',$document_root);
	
	}
	/**
	 * Get real ip
	 *
	 * return string $get_real_ip
	 */
		function get_real_ip(){
		
		   if( getenv('HTTP_X_FORWARDED_FOR') != '' )
		 	{
		      $client_ip =
		         ( !empty($_SERVER['REMOTE_ADDR']) ) ?
		            $_SERVER['REMOTE_ADDR']
		            :
		            ( ( !empty($_ENV['REMOTE_ADDR']) ) ?
		               $_ENV['REMOTE_ADDR']
		               :
		               "unknown" );
		
		   
		
		      $entries = preg_split('/[, ]/', getenv('HTTP_X_FORWARDED_FOR'));
		
		      reset($entries);
		      while (list(, $entry) = each($entries))
		      {
		         $entry = trim($entry);
		         if ( preg_match("/^([0-9]+\\.[0-9]+\\.[0-9]+\\.[0-9]+)/", $entry, $ip_list) )
		         {
		           
		            $private_ip = array(
		                  '/^0\\./',
		                  '/^127\\.0\\.0\\.1/',
		                  '/^192\\.168\\..*/',
		                  '/^172\\.((1[6-9])|(2[0-9])|(3[0-1]))\\..*/',
		                  '/^10\\..*/');
		
		            $found_ip = preg_replace($private_ip, $client_ip, $ip_list[1]);
		
		            if ($client_ip != $found_ip)
		            {
		               $client_ip = $found_ip;
		               break;
		            }
		         }
		      }
		   }
		   else
		   {
		      $client_ip =
		         ( !empty($_SERVER['REMOTE_ADDR']) ) ?
		            $_SERVER['REMOTE_ADDR']
		            :
		            ( ( !empty($_ENV['REMOTE_ADDR']) ) ?
		               $_ENV['REMOTE_ADDR']
		               :
		               "unknown" );
		   }
		
		   return $client_ip;
		
		}
		
		/**
		 * Get File size
		 *
		 * @param string $filename
		 * @param integer $decimal
		 * @return array
		 */
		function get_file_size($filename , $decimal = 2 ) {
			
			$file = filesize($filename);
			
			$type = array(' Bytes',' KB',' MB',' GB',' TB');
			
			return round($file/pow(1024,($i = floor(log($file, 1024)))),$decimal ).$type[$i];
		}
		
		/**
		 * OBject To Array
		 *
		 * @param object $d
		 * @return array
		 */
		function objectToArray($d) {
			if (is_object($d)) {
				$d = get_object_vars($d);
			}
		
			if (is_array($d)) {
				return array_map(__FUNCTION__, $d);
			}else {
				return $d;
			}
		}
		
		/**
		 * Return Rand Number
		 */
		function rand_number(){
			return rand(9009,13456);
		}
		
		/**
		 * Array To OBject
		 *
		 * @param array $d
		 * @return object
		 */
		function arrayToObject($d) {
			if (is_array($d)) {
				return (object) array_map(__FUNCTION__, $d);
			}else{
				return $d;
			}
		}
		
		/**
		 * Mysql expire Date
		 */
		function myexpiredate($date){
			
			$seconds =  strtotime($date)-strtotime('now');
			
			$expire_date = intval($seconds/60/60/24);
			
			return $expire_date;
		} 
		
		/**
		 * Generate Activation hash
		 */
		function activacion_hash(){
			return sha1(mt_rand().time().mt_rand().$_SERVER['REMOTE_ADDR']);
		}
?>