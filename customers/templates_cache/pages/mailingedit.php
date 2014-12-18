<?php
		$moduleroll = array('roll'=>'mailing','permissions'=>'edit');
	
		$page_title       = 'Correo';
		
		$layout           = true;
		
		$error			  = false;
		
		$page	  		  = $apache_index->get_uri_position(1);
		
		$i_email			  = $apache_index->get_uri_position(2);
		
		/**
		 * Load template
		 */
			$tpl = new HTML_Template_Sigma(TEMPLATES_PATH, TEMPLATES_CACHE_PATH);
			
			$tpl->loadTemplatefile($page.'.tpl.html'); 
	
			$tpl->touchBlock($page);
	
			$Elements = new Elements(new Mailling(), $tpl);
	
			$form_val['sent_form']	 =	_post('sent_form');
		
			if($i_email!=''){
				
				$rows=$Elements->db->get($i_email);
				
				$tpl->setVariable('title','Editar Correo');
				
			}else{

				$tpl->setVariable('title','Agregar Correo');	

				$rows=false;
			}

			$elements = array(
					array('value'=>'0','name'=>'trash'),
					array('value'=>'','type'=>'input','label'=>'*Nombre ','size'=>'3','name'=>'name'),
					array('value'=>'','type'=>'input','label'=>'*Email','size'=>'3','name'=>'email','inputtype'=>'email'),
					array('value'=>'0','type'=>'radio','label'=>'Ventas','name'=>'sales'),
					array('value'=>'0','type'=>'radio','label'=>'Consultas','name'=>'questions'),
					array('value'=>'0','type'=>'radio','label'=>'Activo','name'=>'active'),
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
						
						try {
	
							if($form_val['name']==''){
	
								throw new Exception('Debe ingresar Nombre compledo del usuario a registrar');
							}
	
							if(!Check::email($form_val['email'])){
								
								throw new Exception('Debe ingresar un email correcto.');
							}
						
							if($i_email!=''){
									
									$Elements->db->update($data_mysql, $i_email);
								
									$_SESSION['label_notification']['label'] = $page_title.' editado correctamente';
								
									$_SESSION['label_notification']['type'] = 1;
									
									header('location:/mailing');
	
								}else{
								
									$Elements->db->debug=true;
									
									$Elements->db->add($data_mysql);
								
									$_SESSION['label_notification']['label'] = $page_title.' cargado correctamente';
								
									$_SESSION['label_notification']['type'] = 1;
								
									header('location:/mailing');
							}
								
						}catch (Exception $e){
							
							/**
							 * Show Notification event
							 */
								$Elements->notification($e->getMessage(),2);
							
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
						}
					}

					$Elements->LoadElements($elements, $rows);
		
					$module_content = $tpl->get();
			
				/*
				 * Display File
				 */
				require_once('display.inc.php');
?>