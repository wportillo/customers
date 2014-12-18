<?php
	 		$moduleroll = array('roll'=>'resellers','permissions'=>'show');
	 		
			$page	  		    = $apache_index->get_uri_position(1);
				
			$sent_form			= _request('sent_form','0');
			
			/*
			 * Page configurations
			 */
				$page_title       = 'Resellers';
				
				$layout           = true;
				
				$max			  = 10;
				
				$order			  = '';
				
				$where 			  = '1=1';
				
			/*
			 * Levantar template
			 */
				$tpl = new HTML_Template_Sigma(TEMPLATES_PATH, TEMPLATES_CACHE_PATH);
				$tpl->loadTemplatefile($page.'.tpl.html'); 
				$tpl->touchBlock($page);
	
			/*
			 * New Search Button
			 */
				$tpl->setVariable('page_title',$page_title);
			
				
				$Resellers   				= new Crm_reseller();
				
				$Users						= new Users();
				
				$Store						= new Crm_store();
				
				$Resellers->debug			= false;
			
				$Elements 	   				= new Elements($Resellers,$tpl);
				
				$Elements->rolname			= $moduleroll['roll'];
				
				$Elements->page				= $page;	

	

				$label_notification	= _session('label_notification','');	
				
				if($label_notification!=''){
					
					$Elements->notification($label_notification['label'],$label_notification['type']);
					
					unset($_SESSION['label_notification']);
				}
			
			
			   $filter=array(
			 		
			   		 array('value'=>'0','name'=>'go_to'),
			 			
			 		 array('value'=>'0','name'=>'action'),
			 			 
			 		 array('value'=>'','name'=>'order'),
			 		
			 		 array('value'=>'','name'=>'table'),
			 				
			 		 array('value'=>'0','name'=>'id'),
			 			 
			 		 array('value'=>'0','name'=>'sent_form'),
			 			
			 		 array('value'=>'0','name'=>'pagenumber'),
			 			
			   		 array('value'=>'','type'=>'input','label'=>'Nombre','size'=>'3','name'=>'name'),
			   		
			   		 array('value'=>'','type'=>'input','label'=>'Email','size'=>'3','name'=>'email'),
			   		
			 		 array('value'=>'0',
			 		 	   'dropvalues'=> array('1'=>'Eliminados'),
			 		 	   'type' =>'dropmenu',
			 		 	   'label'=>'Papelera',
			 		 	   'name' =>'trash'),
			   		 array('value'=>'0',
			   				'dropvalues'=> array('A'=>'Activo','I'=>'Inactivo'),
			   				'type' =>'dropmenu',
			   				'label'=>'Estatus',
			   				'name' =>'active'),
			   		 array(
			 		 	  'value'=>'0',
			 		 	  'dropvalues'	=> array('50'=>'50','100'=>'100','150'=>'150','200'=>'200','250'=>'250'),
			 		 	  'type'		=>'dropmenu',
			 		 	  'label'		=>'Cantidad por pag',
			 		 	  'onchange'	=>'javascript:document.grilla.submit();',
			 		 	  'name'		=>'quantity'
			 		  ),
			   		);
			 
				foreach($filter as $value){
				
					$key = $value['name'];
					
					$form_val[$key] 	= _request($key,$value['value']);
	
					if($sent_form!='0'){
					
							if(isset($_REQUEST[$key])){
								
								$_SESSION[$key.'_reseller']  	   = $form_val[$key];
							}
					}
					
					$session[$key]				   =	_session($key.'_reseller',$value['value']);
				}
			
				if($session['quantity']!='0'){

					$max	=	$session['quantity'];	
				
				}
				
				if($session['trash']!='0'){
					$where.=' AND trash='.db::quote($session['trash']);
				}

				if($session['active']!='0'){
					if($session['active']=='A'){
						$where.=' AND active =1';
					}else{
						$where.=' AND active =0';
					} 
				}
				
				if($session['name']!=''){
					$where.=' AND name LIKE '.db::quote('%'.$session['name'].'%');
				}
				
				if($session['email']!=''){
					$where.=' AND email LIKE '.db::quote('%'.$session['email'].'%');
				}
		
				switch($form_val['action']){
					case 'active':
						$Elements->active($form_val['id']);
					break;
					case 'desactive':
						$Elements->desactive($form_val['id']);
					break;
					case 'delete':
						$Elements->delete($form_val['id']);
					break;
					case 'restore':
						$Elements->restore($form_val['id']);
					break;
					case 'reset':
						foreach ($filter as $value){

							unset($_SESSION[$value['name'].'_reseller']);

							header('location:/resellers');
						}
					break;
				}
				
				if($Elements->security->getperm($moduleroll['roll'],'edit')){
					$tpl->touchBlock('addreseller');
				}
				
				$Elements->where = $where;
				
				if($session['table']!='' && $session['order']!=''){
					$order.=  $session['table'].' '.$session['order'];
				}
				
				
				$pag   = $Elements->generate_pagination($session['pagenumber'],$max,$form_val['go_to']);

				$grid = $Elements->db->get_list($max,$pag,$where,$order);
			
				while($row=db::fetch_assoc($grid)){
					
					/*
					 * count users
					 */
						$Users->primary_key='i_reseller';
						
						$users = $Users->get($row['i_reseller']);
						
						
						$Store->primary_key='i_reseller';
						
						$store = $Store->get($row['i_reseller']);
						
					$tpl->setCurrentBlock('list');
				
						$tpl->setVariable('name',$row['name']);
						
						$tpl->setVariable('contact',$row['contact']);
						
						$tpl->setVariable('email',$row['email']);
						
						$tpl->setVariable('phone',$row['phone']);
						
						$tpl->setVariable('address',$row['address']);						
						
						$tpl->setVariable('id_reseller',$row['i_reseller']);
						
						if(!$users){
							
							if(!$store){
								$Elements->icondelete('delete',$row['i_reseller'],$row['trash']);
							}
						}
						
						$Elements->iconedit('edit',$row['i_reseller'],'reseller');
						
						$Elements->iconactiveshow('active_action',$row['active']);
						
						$Elements->iconrestore('restore',$row['i_reseller'],$row['trash']);
						
					$tpl->parse('list');
				}
				
			/*
			 * Generate dialog box combobox  
			 */
				$Elements->ajax_combobox('combo');
			
				$Elements->LoadElements($filter, $session);
		
				$order_elements = array(
						'name'				=>  'Nombre',
						'contact'			=>  'Contacto',
						'email'				=>  'Email',
						'phone'				=>  'Telefono',
						'address'			=>  'Direccion',
						'active'			=>  'Activo',
				);
					
				$Elements->Orderby($page,$order_elements, $session['order'],$session['table']);
		
				$module_content = $tpl->get();
			 /*
			  * Display File
			  */
			  require_once('display.inc.php');