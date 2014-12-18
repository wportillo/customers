<?php
		$moduleroll = array('roll'=>'contents','permissions'=>'edit');
	
		$page_title       = 'Categoria';
		
		$layout           = true;
		
		$error			  = false;
		
		$page	  		  = $apache_index->get_uri_position(1);
		
		$i_category		  = $apache_index->get_uri_position(2);
		
		$File			  = new File();
		
		/**
		 * Load template
		 */
			$tpl = new HTML_Template_Sigma(TEMPLATES_PATH, TEMPLATES_CACHE_PATH);
			
			$tpl->loadTemplatefile($page.'.tpl.html'); 
	
			$tpl->touchBlock($page);
	
			$Elements = new Elements(new Category(), $tpl);
	
			$form_val['sent_form']	 =	_post('sent_form');
		
			if($i_category!=''){
				
				$rows=$Elements->db->get($i_category);
				
				$tpl->setVariable('title','Editar Categoria');
				

				/**
				 * If relation users
				 */
					$Content = new Contents();
				
					$Content->primary_key='i_category';
				
					$content = $Content->get($i_category);
				
				
			}else{

				$tpl->setVariable('title','Agregar Categoria');	

				$rows=false;
			}

			$elements = array(
					array('value'=>'0','name'=>'trash'),
					array('value'=>'','type'=>'input','label'=>'*Nombre','size'=>'3','name'=>'name'),
					array('value'=>'','type'=>'textarea','label'=>'*Descripcion','width'=>'15','height'=>'15','name'=>'description'),
					(!$content) ? array('value'=>'0','type'=>'radio','label'=>'Activo','name'=>'active'):null,
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
	
								throw new Exception('Debe ingresar Nombre del canal');
							}
							if($form_val['description']==''){
							
								throw new Exception('Debe ingresar descripcion del canal');
							}
															
							if($i_category!=''){
								
								
									$Elements->db->update($data_mysql, $i_category);
								
									$_SESSION['label_notification']['label'] = $page_title.' editado correctamente';
								
									$_SESSION['label_notification']['type'] = 1;
									
									header('location:/categories');
	
								}else{
									
									$Elements->db->add($data_mysql);
								
									$_SESSION['label_notification']['label'] = $page_title.' cargado correctamente';
								
									$_SESSION['label_notification']['type'] = 1;
								
									header('location:/categories');
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