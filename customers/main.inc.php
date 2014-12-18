<?php
/**
 * Main
 *
 * @author william
 * @package tvmia
 */
	ob_start();
	
	$base_memory = round(memory_get_usage() / 1024);
	
	$time_start = microtime(true);
	
	/**
	 *  Error reporting
	 */
	error_reporting(E_ALL);
	
	ini_set('display_errors',true);
	
	ini_set('memory_limit','500M');
	
	ini_set('precision', 17);
	
	/**
	 * Default Timezone
	 * 
	 */
    date_default_timezone_set('America/New_York');
	/**
	 * Base Path 
	 */
	define('BASE_PATH', realpath(dirname(realpath(__FILE__))));
	/**
	 * Includes Path
	 */
	define('INCLUDE_PATH', BASE_PATH . '/includes/');
	/**
	 * SERVER NAME
	 */
	define('SERVER_NAME',$_SERVER['SERVER_NAME']);
	/**
	 * SERVER NAME LOCAL
	 */
	define('SERVER_NAME_LOCAL','customers');
	/**
	 * Lang Path
	 */
	define('LANG_PATH', INCLUDE_PATH. 'define_lang/');
	/**
	 * Encrypt Key
	 */
	define('ENCRYPT_KEY','jp3326001');
	/**
	 * Templates Path
	 */
	define('TEMPLATES_PATH', BASE_PATH . '/templates/');
	/**
	 * Templates Class Path
	 */
	define('TEMPLATES_CLASS_PATH', BASE_PATH . '/templates/class_elements/');
	/**
	 * Templates Cache Path
	 */
	define('TEMPLATES_CACHE_PATH', BASE_PATH . '/templates_cache/');
	/**
	 * Templates Class Cache Path
	 */
	define('TEMPLATES_CLASS_CACHE_PATH', BASE_PATH . '/templates_cache/class_elements/');
	/**
	 * Classes Path
	 */
	define('CLASSES_PATH', BASE_PATH. '/includes/classes/');
	/**
	 * Config
	 */
	require_once(INCLUDE_PATH . 'config.inc.php');
	/**
	 * General Functions
	 */
	require_once(INCLUDE_PATH . 'functions.inc.php');
	/**
	 * Sigma Templates
	 */
	require_once(INCLUDE_PATH . 'sigma.inc.php');
	/**
	 * DB Class
	 */
	require_once(INCLUDE_PATH . 'db.class.php');
	/**
	 * RS Class
	 */
	require_once(INCLUDE_PATH . 'rs.class.php');	
	/**
	 * Rs Extend Elements
	 */
	require_once(CLASSES_PATH . 'clase.php');
	/**
	 * Session
	 */
	require_once(INCLUDE_PATH . 'session.class.php');
	/**
	 * Security Class
	 */
	require_once(INCLUDE_PATH . 'security.class.php');
	/**
	 * Generate Gird
	 */
	require_once(INCLUDE_PATH . 'grid.class.php');
	/**
	 * Generate Elements
	 */
	require_once(INCLUDE_PATH . 'elements.class.php');
	/**
	 * Sysmenu
	 */
	require_once(INCLUDE_PATH . 'sysmenu.class.php');
	/**
	 * Check
	 */
	require_once(INCLUDE_PATH . 'check.class.php');
	/**
	 * Credit Card Validator
	 */
	require_once(INCLUDE_PATH . 'credit_validator.class.php');
	/**
	 * Customer Class
	 */
	require_once(INCLUDE_PATH . 'customer.class.php');

	/**
	 * Email Library
	 */
	require_once(INCLUDE_PATH . 'phpmailer/class.phpmailer.php');
	/**
	 * Files Upload
	 */
	require_once(INCLUDE_PATH . 'files.upload.class.php');
	/**
	 * Thimg class
	 */
	require_once(INCLUDE_PATH . 'thimg.class.php');
	/**
	 * Sms Interface
	 */
	require_once(INCLUDE_PATH . 'smsinterface.class.php');
	
	/**
	 * Db Connection
	 * 
	 */
	
	if(DEBUG){
		ini_set('display_errors', true);
	}else {
		ini_set('display_errors', false);
		$old_error_handler = set_error_handler('userErrorHandler');	
	}	
	
	if(!db::connectdb()){
		trigger_error(mysql_error());
		die('Database server error');
	}

	/**
	 * Module Rewrite apache
	 */
		$apache_index		= new Rewrite();
	
	/**
	 * Session save Db
	 */
		if(SESSION){
			
			$Sessions_Val = new SessionManager();
			
		}else{	
			session_start();
		}
		
		$Security = new Security();
		
		$session_vars = array(
				'i_reseller' 	=>	_session(array('key'=>'user','val'=>'i_reseller'),'0'),
				'i_store'		=>  _session(array('key'=>'user','val'=>'i_store'),'0'),
				'i_roll'		=>	_session(array('key'=>'user','val'=>'i_roll'),'0'),
				'i_user'		=>	_session(array('key'=>'user','val'=>'i_user'),'0'),
		);
?>