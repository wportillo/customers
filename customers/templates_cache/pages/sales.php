<?php
		$moduleroll = array('roll'=>'sales','permissions'=>'edit');
	
		$page_title       = 'Contentido';
		
		$layout           = true;
		
		$error			  = false;
		
		$page	  		  = $apache_index->get_uri_position(1);
		
		$i_channel		  = $apache_index->get_uri_position(2);
		
		$File			  = new File();
		
		/**
		 * Load template
		 */
			$tpl = new HTML_Template_Sigma(TEMPLATES_PATH, TEMPLATES_CACHE_PATH);
			
			$tpl->loadTemplatefile($page.'.tpl.html'); 
	
			$tpl->touchBlock($page);
	
			$Elements = new Elements(new Contents(), $tpl);
	
			$form_val['sent_form']	 =	_post('sent_form');
		
			if($i_channel!=''){
				
				$rows=$Elements->db->get($i_channel);
				
				$tpl->setVariable('title','Editar Canal');
				
			}else{

				$tpl->setVariable('title','Agregar Canal');	

				$rows=false;
			}

			$a='trash
			imagebox
			imagesite';
					
			$elements = array(
					array('value'=>'0','name'=>'trash'),
					array('value'=>'','type'=>'input','label'=>'*Nombre','size'=>'3','name'=>'name'),
					array('value'=>'','type'=>'textarea','label'=>'*Descripcion','width'=>'15','height'=>'15','name'=>'description'),
					array('value'=>'','type'=>'input','label'=>'Contraseña','size'=>3,'name'=>'password','inputtype'=>'password'),
					array('value'=>'0',
							'type' 	  	=>'dropmenudb',
							'label'	  	=>'*Categoria',
							'table'     => new Category(),
							'where'	  	=>'trash!=1',
							'drop_value'=>'i_category',
							'drop_label'=>'name',
							'name'	  	=>'i_category',
				    ),
					array('value'=>'0',
							'dropvalues'=> array('web'=>'Web','device'=>'Dispositivos','all'=>'Todos'),
							'type' 		=>'dropmenu',
							'label'		=>'*Permisos',
							'name' 		=>'permissions'),
					array('value'=>'0',
							'dropvalues'=> array('1'=>'Si'),
							'type' 		=>'dropmenu',
							'label'		=>'Player Proveedor',
							'name' 		=>'provider_player'),
					array('value'=>'0',
							'dropvalues'=> array('primary'=>'Primario','secondary'=>'Secundario'),
							'type' 		=>'dropmenu',
							'label'		=>'*Cdn',
							'name' 		=>'cdn_status'
					),
					array('value'=>'','type'=>'textarea','label'=>'*HLS primario','width'=>'15','height'=>'15','name'=>'primary_hls'),
					array('value'=>'','type'=>'textarea','label'=>'*RTMP primario','width'=>'15','height'=>'15','name'=>'primary_rtmp'),
					array('value'=>'','type'=>'textarea','label'=>'*RTSP primario','width'=>'15','height'=>'15','name'=>'primary_rtsp'),
					array('value'=>'','type'=>'textarea','label'=>'*Player primario','width'=>'15','height'=>'15','name'=>'primary_player'),
					array('value'=>'','type'=>'textarea','label'=>'*HLS secundario','width'=>'15','height'=>'15','name'=>'secondary_hls'),
					array('value'=>'','type'=>'textarea','label'=>'*RTMP secundario','width'=>'15','height'=>'15','name'=>'secondary_rtmp'),
					array('value'=>'','type'=>'textarea','label'=>'*RTSP secundario','width'=>'15','height'=>'15','name'=>'secondary_rtsp'),
					array('value'=>'','type'=>'textarea','label'=>'*Player Secundario','width'=>'15','height'=>'15','name'=>'secondary_player'),
					array('value'=>'0',
							'dropvalues'=> array('1'=>'Desactivado'),
							'type' 		=>'dropmenu',
							'label'		=>'*Problemas tecnicos',
							'name' 		=>'status'
					),
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
	
								throw new Exception('Debe ingresar Nombre del canal');
							}
							
							if($form_val['imagesite']==''){
							
								throw new Exception('Debe ingresar el logo que sera visible en el sitio web');
							}
							
							if($form_val['imagebox']==''){
									
								throw new Exception('Debe ingresar el logo que sera visible en los dispositivos moviles');
							}
							
							if($form_val['description']==''){
							
								throw new Exception('Debe ingresar descripcion del canal');
							}
							
							if($form_val['i_category']=='0'){
									
								throw new Exception('Debe seleccionar una categoria');
							}
							
							if($form_val['permissions']=='0'){
									
								throw new Exception('Debe seleccionar los permisos para este canal');
							}
							
							if($form_val['cdn_status']=='0'){
									
								throw new Exception('Debe seleccionar el cdn desde donde transmiten los canales');
							}
								
							
							if($form_val['cdn_status']=='primary'){
								
								if($form_val['primary_hls']==''){
										
									throw new Exception('Debe ingresar la url de HLS primario');
								}

								if($form_val['primary_rtmp']==''){
								
									throw new Exception('Debe ingresar la url de RTMP primario');
								}
								
								if($form_val['primary_rtsp']==''){
								
									throw new Exception('Debe ingresar la url de RTSP primario');
								}
								
								if($form_val['provider_player']=='1'){
									
									if($form_val['primary_player']==''){
											
										throw new Exception('Debe ingresar el player primario del proveedor');
									
									}
								}
								
							}else{
								

								if($form_val['secondary_hls']==''){
								
									throw new Exception('Debe ingresar la url de HLS secundario');
								}
								
								if($form_val['secondary_rtmp']==''){
								
									throw new Exception('Debe ingresar la url de RTMP secundario');
								}
								
								if($form_val['secondary_rtsp']==''){
								
									throw new Exception('Debe ingresar la url de RTSP secundario');
								}
							
								
								if($form_val['provider_player']=='1'){
									
									if($form_val['secondary_player']==''){
									
										throw new Exception('Debe ingresar el player secundario del proveedor');
									}
								}
								
								
							}
				
							
							if($i_channel!=''){
								
								
									$File->move($data_mysql['imagesite']);	
								
									$File->move($data_mysql['imagebox']);
									
									$Elements->db->update($data_mysql, $i_channel);
								
									$_SESSION['label_notification']['label'] = $page_title.' editado correctamente';
								
									$_SESSION['label_notification']['type'] = 1;
									
									header('location:/contents');
	
								}else{
								
									$File->move($data_mysql['imagesite']);
									
									$File->move($data_mysql['imagebox']);
										
									$Elements->db->add($data_mysql);
								
									$_SESSION['label_notification']['label'] = $page_title.' cargado correctamente';
								
									$_SESSION['label_notification']['type'] = 1;
								
									header('location:/contents');
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