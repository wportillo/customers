<?php 
/**
 * Start Apache Module Rewrite
 */
	    require_once ('includes/apache_mod_rewrite.class.php');
	
	   	$apache_index = new Rewrite();
	
		$apache_index->page_default='home.php';
	
		$apache_index->include_php();
?>