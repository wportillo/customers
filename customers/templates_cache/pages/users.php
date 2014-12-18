<?php
	 		$moduleroll = array('roll'=>'users','permissions'=>'show');
	 		
			$page	  		    = $apache_index->get_uri_position(1);
				
			$sent_form			= _request('sent_form','0');
		/*
		 * Page configurations
		 */
			$page_title       = 'Usuarios';
			
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
		
			
			$Users   						= new Users();
			
			$Users->table_fields= array(
					'crm_user`.`trash',
					'crm_user`.`i_user',
					'crm_user`.`user',
					'crm_user`.`fullname',
					'crm_user`.`email',
					'crm_roll`.`name',
					'crm_user`.`expiration_date',
					'crm_user`.`active',
					'crm_user`.`i_roll'
			);
	
			$Users->debug				= false;
		
			$Elements 	   				= new Elements($Users,$tpl);
			
			$Elements->rolname			= $moduleroll['roll'];
			
			$Elements->page				= $page;	

			$Crm_store = new Crm_store();
				
			$Crm_store->table_fields= array(
					'crm_store`.`name',
					'crm_store`.`i_reseller',
					'crm_store`.`i_store',
			);
				

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
			 				
			 		 array('value'=>'','type'=>'input','label'=>'Usuario','size'=>'3','name'=>'user'),
			 				
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
			   		array('value'=>'0',
			   				 'type'   =>'dropmenurol',
			   				'label'	  =>'Rol',
			   				'table'     => new Roll(),
			   				'where'	  =>'trash!=1',
			   				'drop_value'=>'i_roll',
			   				'drop_label'=>'name',
			   				'name'	  =>'i_roll',
			   				'i_roll'  => $session_vars['i_roll']
			   		),
			   		($session_vars['i_reseller']=='0') ?
			   		     array('value'=>'0',
			   				'type' 	    =>'dropmenudb',
			   				'label'	    =>'Reseller',
			   				'table'     => new Crm_reseller(),
			   				'where'	    =>'trash!=1',
			   				'drop_value'=>'i_reseller',
			   				'drop_label'=>'name',
			   				'name'	    =>'i_reseller'
			   		) : array('value'=>$session_vars['i_reseller'],'name'=>'i_reseller'),
			   		
			   		($session_vars['i_store']=='0') ?
			   		
			   		array('value'=>'0',
			   				'type' 	    =>'dropmenudb',
			   				'label'	    =>'*Tienda',
			   				'table'     => $Crm_store,
			   				'where'	    => ($session_vars['i_reseller']!='0')? '`crm_store`.`trash`!= 1 AND `crm_store`.`i_reseller`='.$session_vars['i_reseller'] :'`crm_store`.`trash`!= 1',
			   				'drop_value'=>'i_store',
			   				'drop_label'=>'name',
			   				'name'	    =>'i_store'
			   		) : array('value'=>$session_vars['i_store'],'name'=>'i_store')
				);
			 
				foreach($filter as $value){
				
					$key = $value['name'];
					
					$form_val[$key] 	= _request($key,$value['value']);
	
					if($sent_form!='0'){

							if(isset($_REQUEST[$key])){
								
								$_SESSION[$key.'_users']  	   = $form_val[$key];
							}
					}
					
					$session[$key]				   =	_session($key.'_users',$value['value']);
				}
			
				if($session['quantity']!='0'){

					$max	=	$session['quantity'];	
				
				}
			
				if($session['trash']!='0'){
					$where.=' AND `crm_user`.`trash`='.db::quote($session['trash']);
				}
				
				if($session['active']!='0'){
					if($session['active']=='A'){
						$where.=' AND `crm_user`.`active`=1';
					}else{
						$where.=' AND `crm_user`.`active`=0';
					}
				}
			
				if($session['i_reseller']!='0'){
					$where.=' AND `crm_user`.`i_reseller` ='.db::quote($session['i_reseller']);
				}
				
				if($session['i_roll']!='0'){
					$where.=' AND `crm_user`.`i_roll` ='.db::quote($session['i_roll']);
				}
				
				if($session['i_store']!='0'){
					$where.=' AND `crm_user`.`i_store` ='.db::quote($session['i_store']);
				}
					
				if($session['name']!=''){
					$where.=' AND  `crm_user`.`fullname` like '.db::quote('%'.$session['name'].'%');
				}
				
				if($session['user']!=''){
					$where.=' AND  `crm_user`.`user` like '.db::quote('%'.$session['user'].'%');
				}
				
				if($session['email']!=''){
					$where.=' AND  `crm_user`.`email` like '.db::quote('%'.$session['email'].'%');
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

							unset($_SESSION[$value['name'].'_users']);

							header('location:/users');
						}
					break;
				}

				if($Elements->security->getperm($moduleroll['roll'],'edit')){
					$tpl->touchBlock('adduser');	
				}
				
				$Elements->where = $where;
				
				if($session['table']!='' && $session['order']!=''){
					$order.=  $session['table'].' '.$session['order'];
				}
				
				$pag   = $Elements->generate_pagination($session['pagenumber'],$max,$form_val['go_to']);

				$grid = $Elements->db->get_list($max,$pag,$where,$order);
			
				while($row=db::fetch_assoc($grid)){
					
					$tpl->setCurrentBlock('list');
				
						$tpl->setVariable('user_id',$row['i_user']);
						
						$tpl->setVariable('user',$row['user']);
						
						$tpl->setVariable('fullname',$row['fullname']);
						
						$tpl->setVariable('email',$row['email']);
						
						$tpl->setVariable('rol_id',$row['name']);
	
						if(!date_null($row['expiration_date'])){
							$tpl->setVariable('expiration_date',mysql_date_to_spanish($row['expiration_date']));
						}else{
							$tpl->setVariable('expiration_date','not asigned');
						}
						
							$Elements->icondelete('delete',$row['i_user'],$row['trash']);
							
							$Elements->iconedit('edit',$row['i_user'],'user');
							
							$Elements->iconactive('active_action',$row['i_user'],$row['active']);
							
							$Elements->iconrestore('restore',$row['i_user'],$row['trash']);
							
							$Elements->log_icon('log', $row['i_user'], 'accesslog');
							
					$tpl->parse('list');
				}
				
			/*
			 * Generate dialog box combobox  
			 */
				$Elements->ajax_combobox('combo');
			
				$Elements->LoadElements($filter, $session);
		
				$order_elements = array(
						'user'      		=>	'Usuario',
						'fullname'  		=>	'Nombre',
						'email'				=>  'Email',
						'name'				=>  'Rol',
						'expiration_date'	=>  'Expiracion',
						'active'			=>  'Activo',
				);
					
				$Elements->Orderby($page,$order_elements, $session['order'],$session['table']);
		
				
				$module_content = $tpl->get();
			 /*
			  * Display File
			  */
			  require_once('display.inc.php');