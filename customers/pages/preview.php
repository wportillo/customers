<?php
		$moduleroll = array('roll'=>'contents','permissions'=>'show');

		$layout           = false;
		
		$page			  = $apache_index->get_uri_position(1);

		$i_channel		  = $apache_index->get_uri_position(2);
		
		/*
		 * Load template
		 */
			$tpl = new HTML_Template_Sigma(TEMPLATES_PATH, TEMPLATES_CACHE_PATH);
		
			$tpl->loadTemplatefile($page.'.tpl.html'); 
	
			$tpl->touchBlock($page);
		
			$Content	= new Contents();
		
			if($i_channel!=''){
				
				$channel = $Content->get($i_channel);
				
				$tpl->setVariable('channel',json_encode($channel));
			}

			
			$module_content = $tpl->get();
			
	   /*
		* Display File
		*/
			require_once('display.inc.php');
?>
