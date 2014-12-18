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
				'users'				=> array('title'=>'Gestionar Usuarios','icon'=>'/resources/icons_home/usuarios.jpg','url'=>'users'),
				'mailing'			=> array('title'=>'Lista de <br/> Correos','icon'=>'/resources/icons_home/lista_de_correos.png','url'=>'mailing'),
				'promocodes'		=> array('title'=>'Promo <br/> Codes','icon'=>'/resources/icons_home/promo_codes.png','url'=>'promocodes'),
				'categories'		=> array('title'=>'Gestionar Categorias','icon'=>'/resources/icons_home/gestionar_categorias.png','url'=>'categories'),
				'contents'		    => array('title'=>'Gestionar Contenidos','icon'=>'/resources/icons_home/gestionar_contenido.png','url'=>'contents'),
				'resellers'    		=> array('title'=>'Gestionar Resellers','icon'=>'/resources/icons_home/icon_reseller_2.png','url'=>'resellers'),
				'storeresellers'    => array('title'=>'Tiendas Resellers','icon'=>'/resources/icons_home/icon_reseller_tienda.png','url'=>'storeresellers'),
				'products'    		=> array('title'=>'Gestionar Productos','icon'=>'/resources/icons_home/productos.png','url'=>'products'),
				'commissions'    	=> array('title'=>'Gestionar Comisiones','icon'=>'/resources/icons_home/comissions.png','url'=>'commissions'),
				'sales'    			=> array('title'=>'Vender <br/> Cuenta','icon'=>'/resources/icons_home/sales.png','url'=>'sales'),
				'freeregistry'    	=> array('title'=>'Crear <br/>  Gratis','icon'=>'/resources/icons_home/sales.png','url'=>'freeregistry'),
		);
		
		$Sysmenu->home($pages);
	 
		/*
		 * Stats
		 */
		
			$Customer_info = new Customer_info();
			
			$Customer_info->debug=false;
			
			$Crm_payment  = new Customer_payment_history();
			
			if($session_vars['i_reseller']=='0'){

				$premiumaccount = $Customer_info->count('`customer_product_info`.`key_product`!='.db::quote('freeorion'));
				
				$premiumactive  = $Customer_info->count('`customer_product_info`.`key_product`!='.db::quote('freeorion').' AND active=1');
				
				$premiuminactive  = $Customer_info->count('`customer_product_info`.`key_product`!='.db::quote('freeorion').' AND active=0');
				
				$freeaccount = $Customer_info->count('`customer_product_info`.`key_product`='.db::quote('freeorion'));
				
				$freeactive  = $Customer_info->count('`customer_product_info`.`key_product`='.db::quote('freeorion').' AND active=1');
				
				$freeinactive  = $Customer_info->count('`customer_product_info`.`key_product`='.db::quote('freeorion').' AND active=0');
				
				$tpl->setCurrentBlock('adminblock');
				
					$tpl->setVariable('premium_account',$premiumaccount);
				
					$tpl->setVariable('premium_active',$premiuminactive);
						
					$tpl->setVariable('premium_inactive',$premiuminactive);

					$tpl->setVariable('free_account',$freeaccount);
					
					$tpl->setVariable('free_active',$freeactive);
					
					$tpl->setVariable('free_inactive',$freeinactive);
				
				$tpl->parse('adminblock');
				
				
				$new_customer_count_month = $Customer_info->count('`customer_product_info`.`key_product`!='.db::quote('freeorion').' AND `customer_date_info`.`creation_date`
				
				BETWEEN DATE( DATE_SUB( NOW() , INTERVAL 31 DAY ) )
		
				AND DATE_ADD(NOW(), INTERVAL 1 DAY)');
				
				$new_customer_free_count_month = $Customer_info->count('`customer_product_info`.`key_product`='.db::quote('freeorion').' AND `customer_date_info`.`creation_date`
				
				BETWEEN DATE( DATE_SUB( NOW() , INTERVAL 31 DAY ) )
				
				AND DATE_ADD(NOW(), INTERVAL 1 DAY)');
				
				
				$free_inactive = $Customer_info->count('`customer_product_info`.`key_product`='.db::quote('freeorion').'AND `customer_info`.`active`=0 AND `customer_date_info`.`creation_date`
				
				BETWEEN DATE( DATE_SUB( NOW() , INTERVAL 31 DAY ) )
				
				AND DATE_ADD(NOW(), INTERVAL 1 DAY)');
				
				
				$free_active = $Customer_info->count('`customer_product_info`.`key_product`='.db::quote('freeorion').' AND `customer_date_info`.`creation_date`
				
				BETWEEN DATE( DATE_SUB( NOW() , INTERVAL 31 DAY ) )
				
				AND DATE_ADD(NOW(), INTERVAL 1 DAY)');
				
				
				$premium_inactive = $Customer_info->count('`customer_product_info`.`key_product`!='.db::quote('freeorion').'AND `customer_info`.`active`=0 AND `customer_date_info`.`creation_date`
				
				BETWEEN DATE( DATE_SUB( NOW() , INTERVAL 31 DAY ) )
				
				AND DATE_ADD(NOW(), INTERVAL 1 DAY)');
				
				
				$premium_active = $Customer_info->count('`customer_product_info`.`key_product`!='.db::quote('freeorion').'AND `customer_info`.`active`=1 AND `customer_date_info`.`creation_date`
				
				BETWEEN DATE( DATE_SUB( NOW() , INTERVAL 31 DAY ) )
				
				AND DATE_ADD(NOW(), INTERVAL 1 DAY)');
				
				$rssales_month = db::query('SELECT SUM( amount ) AS amount FROM customer_payment_history WHERE operation="new" AND transdate BETWEEN DATE( DATE_SUB( NOW( ) , INTERVAL 31 DAY ) ) AND DATE_ADD(NOW(), INTERVAL 1 DAY)');
					
				$rowsales_month = db::fetch_assoc($rssales_month);
				
				$rsrecharge_month = db::query('SELECT SUM( amount ) AS amount FROM customer_payment_history WHERE operation="recharge" AND transdate BETWEEN DATE( DATE_SUB( NOW( ) , INTERVAL 31 DAY ) ) AND DATE_ADD(NOW(), INTERVAL 1 DAY)');
					
				$rsrecharge_month = db::fetch_assoc($rsrecharge_month);
				
				$transcount = $Crm_payment->count('transdate BETWEEN DATE( DATE_SUB( NOW( ) , INTERVAL 31 DAY ) ) AND DATE_ADD(NOW(), INTERVAL 1 DAY)');
				
				
				if($rowsales_month['amount']==null){
					$rowsales_month['amount']='0.00';
				}
				if($rsrecharge_month['amount']==null){
					$rsrecharge_month['amount']='0.00';
				}
				
				$tpl->setCurrentBlock('monthblock');
					
					$tpl->setVariable('customer_sales',$rowsales_month['amount']);
						
					$tpl->setVariable('customer_recharge',$rsrecharge_month['amount']);
						
					$tpl->setVariable('total_sales',truncateFloat($rowsales_month['amount']+$rsrecharge_month['amount'],2));
					
					$tpl->setVariable('new_customer',$new_customer_count_month);
					
					$tpl->setVariable('free_inactive',$free_inactive);
						
					$tpl->setVariable('free_active',$free_active);
						
					$tpl->setVariable('premium_inactive',$premium_inactive);
					
					$tpl->setVariable('premium_active',$premium_active);
						
					$tpl->setVariable('transcount',$transcount);
						
					$tpl->setVariable('new_customer_free',$new_customer_free_count_month);
					
				$tpl->parse('monthblock');
			
			}else{
				
				$Crm_reseller = new Crm_reseller();
				
				$reseller = $Crm_reseller->get($session_vars['i_reseller']);
				
				$Customer_info->debug=false;
				
				$new_customer_count_day = $Customer_info->count('`customer_product_info`.`key_product`!='.db::quote('freeorion').' AND `customer_info`.`i_reseller`='.db::quote($session_vars['i_reseller']).' AND `customer_date_info`.`creation_date` 
				
				BETWEEN DATE( DATE_SUB( NOW() , INTERVAL 1 DAY ) )
					
				AND DATE_ADD(NOW(), INTERVAL 1 DAY)');
						
				$new_customer_free_count_day = $Customer_info->count('`customer_product_info`.`key_product`='.db::quote('freeorion').' AND `customer_info`.`i_reseller`='.db::quote($session_vars['i_reseller']).' AND `customer_date_info`.`creation_date`
			
				BETWEEN DATE( DATE_SUB( NOW() , INTERVAL 1 DAY ) )
			
				AND DATE_ADD(NOW(), INTERVAL 1 DAY)');
						
				$rssales_day = db::query('SELECT SUM( amount ) AS amount FROM customer_payment_history WHERE i_reseller = '.db::quote($session_vars['i_reseller']).' AND operation="new" AND transdate BETWEEN DATE( DATE_SUB( NOW( ) , INTERVAL 1 DAY ) ) AND DATE_ADD(NOW(), INTERVAL 1 DAY)');
					
				$rowsales_day = db::fetch_assoc($rssales_day);
				
				$rsrecharge_day = db::query('SELECT SUM( amount ) AS amount FROM customer_payment_history WHERE i_reseller = '.db::quote($session_vars['i_reseller']).' AND operation="recharge" AND transdate BETWEEN DATE( DATE_SUB( NOW( ) , INTERVAL 1 DAY ) ) AND DATE_ADD(NOW(), INTERVAL 1 DAY)');
					
				$rsrecharge_day = db::fetch_assoc($rsrecharge_day);
				
				$transcount = $Crm_payment->count('i_reseller='.db::quote($session_vars['i_reseller']).' AND transdate BETWEEN DATE( DATE_SUB( NOW( ) , INTERVAL 1 DAY ) ) AND DATE_ADD(NOW(), INTERVAL 1 DAY)');
				
				$recharge_commission_day = truncateFloat($rsrecharge_day['amount']*$reseller['commission']/100,2);
				
				$sales_commission_day    = truncateFloat($rowsales_day['amount']*$reseller['create_commission']/100,2);
				
				if($rowsales_day['amount']==null){
					$rowsales_day['amount']='0.00';
				}
				if($rsrecharge_day['amount']==null){
					$rsrecharge_day['amount']='0.00';
				}
				
				$tpl->setCurrentBlock('dayblock');
					
					
					$tpl->setVariable('customer_sales',$rowsales_day['amount']);
					
					$tpl->setVariable('customer_recharge',$rsrecharge_day['amount']);
				
					$tpl->setVariable('total_sales',truncateFloat($rowsales_day['amount']+$rsrecharge_day['amount'],2));		
					
					$tpl->setVariable('transcount',$transcount);
					
					$tpl->setVariable('commission_sales',$sales_commission_day);
					
					$tpl->setVariable('commission_recharge',$recharge_commission_day);
						
					$tpl->setVariable('total_commission',truncateFloat($recharge_commission_day+$sales_commission_day,2));
					
				$tpl->parse('dayblock');
				

				$new_customer_count_month = $Customer_info->count('`customer_product_info`.`key_product`!='.db::quote('freeorion').' AND `customer_info`.`i_reseller`='.db::quote($session_vars['i_reseller']).' AND `customer_date_info`.`creation_date`
				
				BETWEEN DATE( DATE_SUB( NOW() , INTERVAL 31 DAY ) )
			
				AND DATE_ADD(NOW(), INTERVAL 1 DAY)');
				
				$new_customer_free_count_month = $Customer_info->count('`customer_product_info`.`key_product`='.db::quote('freeorion').' AND `customer_info`.`i_reseller`='.db::quote($session_vars['i_reseller']).' AND `customer_date_info`.`creation_date`
		
				BETWEEN DATE( DATE_SUB( NOW() , INTERVAL 31 DAY ) )
		
				AND DATE_ADD(NOW(), INTERVAL 1 DAY)');
				
				
				$free_inactive = $Customer_info->count('`customer_product_info`.`key_product`='.db::quote('freeorion').'AND `customer_info`.`active`=0 AND `customer_info`.`i_reseller`='.db::quote($session_vars['i_reseller']).' AND `customer_date_info`.`creation_date`
				
				BETWEEN DATE( DATE_SUB( NOW() , INTERVAL 31 DAY ) )
				
				AND DATE_ADD(NOW(), INTERVAL 1 DAY)');
				
				
				$free_active = $Customer_info->count('`customer_product_info`.`key_product`='.db::quote('freeorion').'AND `customer_info`.`active`=1 AND `customer_info`.`i_reseller`='.db::quote($session_vars['i_reseller']).' AND `customer_date_info`.`creation_date`
				
				BETWEEN DATE( DATE_SUB( NOW() , INTERVAL 31 DAY ) )
				
				AND DATE_ADD(NOW(), INTERVAL 1 DAY)');
				
				
				$premium_inactive = $Customer_info->count('`customer_product_info`.`key_product`!='.db::quote('freeorion').'AND `customer_info`.`active`=0 AND `customer_info`.`i_reseller`='.db::quote($session_vars['i_reseller']).' AND `customer_date_info`.`creation_date`
				
				BETWEEN DATE( DATE_SUB( NOW() , INTERVAL 31 DAY ) )
				
				AND DATE_ADD(NOW(), INTERVAL 1 DAY)');
				
				
				$premium_active = $Customer_info->count('`customer_product_info`.`key_product`!='.db::quote('freeorion').'AND `customer_info`.`active`=1 AND `customer_info`.`i_reseller`='.db::quote($session_vars['i_reseller']).' AND `customer_date_info`.`creation_date`
				
				BETWEEN DATE( DATE_SUB( NOW() , INTERVAL 31 DAY ) )
				
				AND DATE_ADD(NOW(), INTERVAL 1 DAY)');
				
				$rssales_month = db::query('SELECT SUM( amount ) AS amount FROM customer_payment_history WHERE i_reseller = '.db::quote($session_vars['i_reseller']).' AND operation="new" AND transdate BETWEEN DATE( DATE_SUB( NOW( ) , INTERVAL 31 DAY ) ) AND DATE_ADD(NOW(), INTERVAL 1 DAY)');
					
				$rowsales_month = db::fetch_assoc($rssales_month);
				
				$rsrecharge_month = db::query('SELECT SUM( amount ) AS amount FROM customer_payment_history WHERE i_reseller = '.db::quote($session_vars['i_reseller']).' AND operation="recharge" AND transdate BETWEEN DATE( DATE_SUB( NOW( ) , INTERVAL 31 DAY ) ) AND DATE_ADD(NOW(), INTERVAL 1 DAY)');
					
				$rsrecharge_month = db::fetch_assoc($rsrecharge_month);
				
				$transcount = $Crm_payment->count('i_reseller='.db::quote($session_vars['i_reseller']).' AND transdate BETWEEN DATE( DATE_SUB( NOW( ) , INTERVAL 31 DAY ) ) AND DATE_ADD(NOW(), INTERVAL 1 DAY)');
				
				$recharge_commission = truncateFloat($rsrecharge_month['amount']*$reseller['commission']/100,2);
				
				$sales_commission    = truncateFloat($rowsales_month['amount']*$reseller['create_commission']/100,2);
				
				if($rowsales_month['amount']==null){
					$rowsales_month['amount']='0.00';
				}
				if($rsrecharge_month['amount']==null){
					$rsrecharge_month['amount']='0.00';
				}
				
				$tpl->setCurrentBlock('monthblock');
					
					$tpl->setVariable('customer_sales',$rowsales_month['amount']);
					
					$tpl->setVariable('customer_recharge',$rsrecharge_month['amount']);
					
					$tpl->setVariable('total_sales',truncateFloat($rowsales_month['amount']+$rsrecharge_month['amount'],2));
					
					
					$tpl->setCurrentBlock('commission');
						$tpl->setVariable('commission_sales',$sales_commission);
						
						$tpl->setVariable('commission_recharge',$recharge_commission);
					
						$tpl->setVariable('total_commission',truncateFloat($recharge_commission+$sales_commission,2));
					
					$tpl->parse('commission');
					
					$tpl->setVariable('new_customer',$new_customer_count_month);
				
					$tpl->setVariable('free_inactive',$free_inactive);
					
					$tpl->setVariable('free_active',$free_active);
					
					$tpl->setVariable('premium_inactive',$premium_inactive);
						
					$tpl->setVariable('premium_active',$premium_active);
					
					$tpl->setVariable('transcount',$transcount);
					
					$tpl->setVariable('new_customer_free',$new_customer_free_count_month);
					
				$tpl->parse('monthblock');
				
				
			}
			
			$module_content = $tpl->get();
			
		/*
		 * Display File
		 */
			require_once('display.inc.php');
?>