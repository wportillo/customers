<?php
		$page_title       = 'Recargar Balance';
		
		$layout           = true;
		
		$error			  = false;
		
		$page	  		  = $apache_index->get_uri_position(1);
		
		$i_reseller		  = $session_vars['i_reseller'];
		
		$Customer 		  = new Customer();
		
		/**
		 * Load template
		 */
			$tpl = new HTML_Template_Sigma(TEMPLATES_PATH, TEMPLATES_CACHE_PATH);
			
			$tpl->loadTemplatefile($page.'.tpl.html'); 
	
			$tpl->touchBlock($page);
	
			$Elements = new Elements(false, $tpl);
	
			$form_val['sent_form']	 =	_post('sent_form');
		
			if($i_reseller!='0'){
				
				$tpl->setVariable('title','Recargar Balance con tarjeta de credito Definida');
				
				$rows=false;
				
			}else{
				
				header('location:/home');
			}
			
			$elements = array(
					array('value'=>'0',
						 'dropvalues'=> 	
						    array('20'  =>'$20',
								  '40'  =>'$40',
								  '60'  =>'$60',
								  '80'  =>'$80',
								  '100' =>'$100',
								  '200' =>'$200',
								  '600' =>'$400',
								  '700' =>'$600',
								  '800' =>'$800',
								  '1000'=>'$1000'
						     ),
							'type'		=>'dropmenu',
							'label'		=>'*Monto a Recargar',
							'name'		=>'amount'
					),
			);
	
			/**
			 * Charge from values
			 */
				foreach($elements as $value){
		
					 $key = $value['name'];
					
					 if(isset($key)){
								
									$form_val[$key] 	= _post($key,$value['value']);
									
									switch($key){
										default:
											$data_mysql[$key]	= $form_val[$key];
										break;
									}
								}
						}
					
					if($form_val['sent_form']!=''){
						
						$message = $Customer->rechargereseller($data_mysql['amount'],$session_vars);
						
						if($message){	
	
							/**
							 * Show Notification event
							 */
							$Elements->notification($message->error->es,2);
								
							/**
							 * Autocomplete send values
							 */
							
							foreach($form_val as $key=>$val){
								switch($key){
									default:
										$rows[$key] = $form_val[$key];
										break;
								}
							}
							
						}else{
							

							$_SESSION['label_notification']['label'] = $page_title.' creado correctamente';
							
							$_SESSION['label_notification']['type'] = 1;
							
							header('location:/home');
						}
					}

					$Elements->LoadElements($elements, $rows);
		
					$module_content = $tpl->get();
				/*
				 * Display File
				 */
			
				require_once('display.inc.php');
?>