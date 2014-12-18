<?php
/**
 * Configuration File
 *
 * @author william
 * @package Core
 */

if(SERVER_NAME=='customers'){
		
		/**
		 * Site Debug (PHP Errors)
		 */
		define('DEBUG', true);
		
		/**
		 * Database Debug (SQL)
		 */
		define('DEBUG_DB', false);
		
		/**
		 * Show Load (Memory & Time)
		 */
		define('DEBUG_LOAD', false);
		/**
		 * MySQL Host
		 */
		define('DB_HOST', 'localhost');
		
		/**
		 * MySQL Username
		 */
		define('DB_USER', 'root');
	
		/**
		 * MySQL Password
		 */
		define('DB_PASS', '1234');
		
		/**
		 * MySQL Database
		 */
		define('DB_NAME', 'jsonteleorion');
		/**
		 * BASE NAME
		 */
		define('BASE','http://customers/');
		/**
		 * BASE IMG
		 */
		define('BASE_IMG','tvmia_developer');
		/**
		 * Save Session Db 
		 */
		define('SESSION',true);
		/**
		 * Session Name Cookie
		 */
		define ('SESSION_NAME','customers');
	    /**
		 * Define Cookie Name
		 */
	   	 ini_set('session.name',SESSION_NAME);
	   	 /**
	   	  * JSON HOST
	   	  */
	   	  define ('JSON_HOST','jsonteleorion/api');
	}else{
		/**
		 * Site Debug (PHP Errors)
		 */
		define('DEBUG', false);
		
		/**
		 * Database Debug (SQL)
		 */
		define('DEBUG_DB', false);
		
		/**
		 * Show Load (Memory & Time)
		 */
		define('DEBUG_LOAD', false);
		/**
		 * MySQL Host
		 */
		define('DB_HOST', 'localhost');
		
		/**
		 * MySQL Username
		 */
		define('DB_USER', 'root');
	
		/**
		 * MySQL Password
		 */
		define('DB_PASS', 'jp3326042');
		
		/**
		 * MySQL Database
		 */
		define('DB_NAME', 'jsonteleorion');
		/**
		 * BASE NAME
		 */
		define('BASE','http://reseller.teleorion.com/');
		/**
		 * BASE IMG
		 */
		define('BASE_IMG','tvmia_developer');
		/**
		 * Save Session Db
		 */
		define('SESSION',true);
		
		/**
		 * Session Name Cookie
		 */
		define ('SESSION_NAME','customers');
		/**
		 * Define Cookie Name
		 */
		ini_set('session.name',SESSION_NAME);
		/**
		 * JSON HOST
		 */
		 define ('JSON_HOST','json.teleorion.com');
	}
	
	/**
	 * SMS User & Pass
	 */
		define('SMS_USER', 'TVmia002');
		define('SMS_PASS', 'jp3326001');
	/**
	 * transactions login
	 */
		define('HOST_MERCHANT','75.144.126.210');
		
		define('PORT_MERCHANT','31419');
		
		define('PROCESSOR_ID','NOVA');
		
		define('MERCHANT_NUMBER','0008021258150000');
	/**
	 * Email Config
	 */
		define('HOST_EMAIL','smtp.gmail.com');
		
		define('PORT_EMAIL','465');
		
		define('USER_EMAIL','soporte@tvmia.com');
		
		define('PASSWORD_EMAIL','tvmia123456');
	/**
	 * Paypal Config
	 */
		define('SERVER_PAYPAL','https://www.paypal.com/cgi-bin/webscr');
		
		define('USER_PAYPAL','bucci@tvmia.com');
		
		define('RETURN_IPN_PAYPAL','http://customers.tvmia.com/payalipn');

		define('RETURN_URI_PAYPAL','http://customers.tvmia.com/completed');
		
		/**
		 * Session
		 */
		define('SESSION_DELIM', '|');
?>