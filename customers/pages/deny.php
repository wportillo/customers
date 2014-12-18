<?php
		require_once('main.inc.php');
	
		$page_title       = 'Deny';
		
		$layout           = true;
		
		$error		 	  =	false;
	
		$pagina	  		  = $apache_index->get_uri_position(1);
	
		/**
		 * Load template
		 */
			$tpl = new HTML_Template_Sigma(TEMPLATES_PATH, TEMPLATES_CACHE_PATH);
			
			$tpl->loadTemplatefile($pagina.'.tpl.html'); 
			
	
			$tpl->touchBlock($pagina);
		 

				
			$module_content = $tpl->get();
			
			/*
			 * Display File
			 */
			require_once('display.inc.php');
?>