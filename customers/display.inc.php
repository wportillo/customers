<?php

/**
 * Display
 *
 * @author willy
 * @package tvmia
 */
	ob_start();
	
	require_once ('main.inc.php');

	if($layout){
		
		$main_tpl = new HTML_Template_Sigma(TEMPLATES_PATH, TEMPLATES_CACHE_PATH);

		$Sysmenu = new Sysmenu($main_tpl);
		
		$main_tpl->loadTemplateFile('layout.tpl.html');
		
		$main_tpl->setVariable('base',BASE);
		
		$main_tpl->setVariable('page_title', $page_title);
		
		$main_tpl->setVariable('user',$Security->data['fullname']);
		
		$main_tpl->setVariable('user_id',$Security->data['i_user']);
		
		$pages=array(
	
			'roles'			=>	     array('title'=>'Gestionar Roles','url'=>'roles'),
			'users'			=>	  	 array('title'=>'Gestionar Usuarios','url'=>'users'),
			'mailing'		=>		 array('title'=>'Gestionar Correos','url'=>'mailing'),
			'categories'	=>		 array('title'=>'Gestionar Categorias','url'=>'categories'),
			'contents'		=>		 array('title'=>'Gestionar Canales','url'=>'contents'),
			'products'		=>		 array('title'=>'Gestionar Productos','url'=>'products'),
			'resellers'		=>		 array('title'=>'Gestionar Resellers','url'=>'resellers','perm'=>'edit'),
			'storeresellers'=>	 	 array('title'=>'Gestionar Tiendas','url'=>'storeresellers'),
			'commissions'	=>	 	 array('title'=>'Gestionar Comisiones','url'=>'commissions'),
			'customers'		=>		 array('title'=>'Gestionar Clientes','url'=>'customers'),
			'sales'			=>		 array('title'=>'Crear Premium','url'=>'sales'),
			'freeregistry'	=>		 array('title'=>'Crear Gratis','url'=>'freeregistry'),
		);
		
		if($session_vars['i_reseller']!='0' && $session_vars['i_store']=='0'){

				$balance = $Security->getbalance($session_vars['i_reseller'], 'reseller');
			
				$main_tpl->setCurrentBlock('balance');
					
					$main_tpl->setVariable('balance_amount',$balance['balance']);
					
					$main_tpl->touchBlock('reseller');
					
					if($balance['type']=='credit'){

						$main_tpl->touchBlock('credit');
					}
					
				$main_tpl->parse('balance');
		}
		
		if($session_vars['i_reseller']!='0' && $session_vars['i_store']!='0'){
		
				$balance = $Security->getbalance($session_vars['i_store'], 'store');
		
				$main_tpl->setCurrentBlock('balance');
					$main_tpl->setVariable('balance_amount',$balance['balance']);
				$main_tpl->parse('balance');
		}
	
		$Sysmenu->sidebar($pages);
		
		$Sysmenu->menubar($pages);
		
		$main_tpl->setCurrentBlock('module_content');
			$main_tpl->setVariable('module_content', $module_content);
		$main_tpl->parse('module_content');
			
		$main_tpl->show();
				
	}else {
		echo $module_content;	
	}
					
	ob_flush();
	ob_end_clean();		
	
	if(DEBUG_LOAD){
		$time_end = microtime(true);
		$time = $time_end - $time_start;		
		echo '<br /><center><div class="load_stats">Script load:<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Base Memory: ' . $base_memory . 'KB<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Memory: ' . round(memory_get_usage() / 1024) . " KB<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Memory Peak: " . round(memory_get_peak_usage() / 1024) . " KB<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Time: " . round($time, 2) . " seconds</div></center>";
	}
	
?>