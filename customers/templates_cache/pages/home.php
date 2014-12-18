<?php
		require_once('main.inc.php');
	
		$layout           = true;

		$page_title		  = 'Inicio';
	
		
	/**
	 * Load template
	 */
		$tpl = new HTML_Template_Sigma(TEMPLATES_PATH, TEMPLATES_CACHE_PATH);
		
		$tpl->loadTemplatefile('home.tpl.html'); 
		
		$tpl->touchBlock('home');
		
		$Sysmenu = new Sysmenu($tpl);

		$pages=array(
				'customers'	    	=> array('title'=>'Gestionar Clientes','icon'=>'/resources/icons_home/clientes.png','url'=>'customers'),
				'roles'	    		=> array('title'=>'Gestionar <br/> Roles','icon'=>'/resources/icons_home/roles.png','url'=>'roles'),
			//	'users'				=> array('title'=>'Gestionar Usuarios','icon'=>'/resources/icons_home/usuarios.jpg','url'=>'users'),
				'mailing'			=> array('title'=>'Lista de <br/> Correos','icon'=>'/resources/icons_home/lista_de_correos.png','url'=>'mailing'),
				'promocodes'		=> array('title'=>'Promo <br/> Codes','icon'=>'/resources/icons_home/promo_codes.png','url'=>'promocodes'),
				'categories'		=> array('title'=>'Gestionar Categorias','icon'=>'/resources/icons_home/gestionar_categorias.png','url'=>'categories'),
				'contents'		    => array('title'=>'Gestionar Contenidos','icon'=>'/resources/icons_home/gestionar_contenido.png','url'=>'contents'),
				'resellers'    		=> array('title'=>'Gestionar Resellers','icon'=>'/resources/icons_home/icon_reseller_2.png','url'=>'resellers'),
				'storeresellers'    => array('title'=>'Tiendas Resellers','icon'=>'/resources/icons_home/icon_reseller_tienda.png','url'=>'storeresellers'),
				'products'    		=> array('title'=>'Gestionar Productos','icon'=>'/resources/icons_home/productos.png','url'=>'products'),
				'commissions'    	=> array('title'=>'Gestionar Comisiones','icon'=>'/resources/icons_home/comissions.png','url'=>'commissions'),
				//'sales'    			=> array('title'=>'Vender <br/> Cuenta','icon'=>'/resources/icons_home/sales.png','url'=>'sales'),
				//'freeregistry'    	=> array('title'=>'Crear <br/>  Gratis','icon'=>'/resources/icons_home/sales.png','url'=>'freeregistry'),
		);
		
		
		$Sysmenu->home($pages);
	 
		$module_content = $tpl->get();
		
		/*
		 * Display File
		 */
			require_once('display.inc.php');
?>