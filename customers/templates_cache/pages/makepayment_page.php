<?php
		require_once('main.inc.php');
	
		$page_title       =  'Pago';
		
		$layout           =  true;
		
		$Form_elements    =  new Form_elements();
		
		$error		 	  =	 false;
		
		$info			  =  $apache_index->get_uri_position(3);
		
		$pagina	  		  =  $apache_index->get_uri_position(1);
		
		$i_customer	  	  =  $apache_index->get_uri_position(2);

		$Create_customer  =  new TVmia_Customer();
		
		$Create_customer  -> debug=false;
		
		$Product 		  =  new Products();
		
		$Product		  -> debug=false;
		
		if($i_customer==''){
			header('location:/deny');
		}
		
	/**
	 * Get Store User & Reseller Id
	 */	
		$id_reseller	=	_session('id_reseller');
		
		$id_store		=	_session('id_store');
		
		$id_user		=	_session('user_id');
		
		$id_rol			=   _session('rol_id');
		
	/**
	 * Load template
	 */
		$tpl = new HTML_Template_Sigma(TEMPLATES_PATH, TEMPLATES_CACHE_PATH);
		
		$tpl->loadTemplatefile($pagina.'.tpl.html'); 
		
		$Form_elements->object_template=$tpl;
	
		
		$tpl->touchBlock($pagina);
		 
			/**
			 * Sent form load value
		 	 */
		
				$form_val['sent_form']				=		_post('sent_form');
					
					$rows=false;
					
					$Create_customer->i_customer=$i_customer;
					
					/*
					 * Get Customer Information
					 */
						$client_information=$Create_customer->Get_Customer_Personal_information();
						
						$tpl->setVariable('title',$client_information['nombre_cliente'].' '.$client_information['apellido_cliente'].' Pago pendiente de $'.$client_information['balance']);
				
				if($form_val['sent_form']!=''){
				
					$customer=$Create_customer->Recharge_Customer_Balance();
						
					if(!isset($customer['i_customer'])){
						$error[] = $customer['error'];
					}
					
					if($error!=false){
						
							$Form_elements->notification($error[0],2);
					}else{
						
				
							$_SESSION['label_notification']['label'] = $page_title.' cargado correctamente';
							
							$_SESSION['label_notification']['type'] = 1;
							
							header('location:/customers');
					}	
				}
				$module_content = $tpl->get();
			
				/*
				 * Display File
				 */
				require_once('display.inc.php');
?>