<?php
		$moduleroll = array('roll'=>'freeregistry','permissions'=>'edit');
	
		$page_title       = 'Crear Cliente Gratis';
		
		$layout           = true;
		
		$error			  = false;
		
		$page	  		  = $apache_index->get_uri_position(1);
		

		
		
		/**
		 * Load template
		 */
			$tpl = new HTML_Template_Sigma(TEMPLATES_PATH, TEMPLATES_CACHE_PATH);
			
			$tpl->loadTemplatefile($page.'.tpl.html'); 
	
			$tpl->touchBlock($page);
	
			$Customer = new Customer();
			
			$Elements = new Elements(false, $tpl);
	
			$form_val['sent_form']	 =	_post('sent_form');
		
			$tpl->setVariable('title','Crear Cliente Gratis');	

			$rows=false;
			
			$elements = array(
					array('value'=>'','type'=>'input','label'=>'*Nombre','size'=>'3','name'=>'name'),
					array('value'=>'','type'=>'input','label'=>'*Apellido','size'=>'3','name'=>'surname'),
					array('value'=>'','type'=>'input','label'=>'*Email','size'=>'3','name'=>'email','inputtype'=>'email'),
					array('value'=>'','type'=>'input','label'=>'*Repetir Email','size'=>'3','name'=>'email_repeat','inputtype'=>'email'),
					array('value'=>'','type'=>'input','label'=>'*Cod pais','size'=>'1','name'=>'areacode'),
					array('value'=>'','type'=>'input','label'=>'*Telefono','size'=>'1','name'=>'phone'),
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
						
						$message = $Customer->addfree($data_mysql,$session_vars);
						
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