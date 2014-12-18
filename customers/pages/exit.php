<?php
	require_once('main.inc.php');
	
	/**
	 * Destroy Session
	 */
	  
	  session_destroy();
	  
	  header('location:/login');
?>
