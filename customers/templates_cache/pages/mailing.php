<?php
	 		$moduleroll = array('roll'=>'mailing','permissions'=>'show');
	 		
			$page	  		    = $apache_index->get_uri_position(1);
				
			$sent_form			= _request('sent_form','0');
		/*
		 * Page configurations
		 */
			$page_title       = 'Gestionar Correos';
			
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
		
			$Mailling   				= new Mailling();
			
			$Mailling->debug			= false;
		
			$Elements 	   				= new Elements($Mailling,$tpl);
			
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
			 		
			   		 array('value'=>'0','name'=>'field'),
			   		
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
								
								$_SESSION[$key.'_mailling']  	   = $form_val[$key];
							}
					}
					
					$session[$key]				   =	_session($key.'_mailling',$value['value']);
				}
			
				if($session['quantity']!='0'){

					$max	=	$session['quantity'];	
				
				}
			
				if($session['trash']!='0'){
					$where.=' AND trash='.db::quote($session['trash']);
				}
				
				if($session['active']!='0'){
					if($session['active']=='A'){
						$where.=' AND active=1';
					}else{
						$where.=' AND active=0';
					}
				}
			
				if($session['name']!=''){
					$where.=' AND  name like '.db::quote('%'.$session['name'].'%');
				}
				
				if($session['email']!=''){
					$where.=' AND  email like '.db::quote('%'.$session['email'].'%');
				}

				switch($form_val['action']){
					case 'active':

						if($form_val['field']!=''){

							$Elements->active($form_val['id'],$form_val['field']);
						
						}else{

							$Elements->active($form_val['id']);
						
						}
						
					break;
					case 'desactive':
						
						if($form_val['field']!=''){

							$Elements->desactive($form_val['id'],$form_val['field']);
						
						}else{

							$Elements->desactive($form_val['id']);
						
						}
						
					break;
					case 'delete':
						$Elements->delete($form_val['id']);
					break;
					case 'restore':
						$Elements->restore($form_val['id']);
					break;
					case 'reset':
						foreach ($filter as $value){

							unset($_SESSION[$value['name'].'_mailling']);

							header('location:/mailing');
						}
					break;
				}

				if($Elements->security->getperm($moduleroll['roll'],'edit')){
					$tpl->touchBlock('addemail');	
				}
				
				$Elements->where = $where;
				
				if($session['table']!='' && $session['order']!=''){
					$order.=  $session['table'].' '.$session['order'];
				}
				
				$pag   = $Elements->generate_pagination($session['pagenumber'],$max,$form_val['go_to']);

				$grid = $Elements->db->get_list($max,$pag,$where,$order);
			
				while($row=db::fetch_assoc($grid)){
					
					$tpl->setCurrentBlock('list');
				
							$tpl->setVariable('name',$row['name']);
						
							$tpl->setVariable('email',$row['email']);
						
							$Elements->icondelete('delete',$row['i_email'],$row['trash']);
							
							$Elements->iconedit('edit',$row['i_email'],'mailingedit');
							
							$Elements->iconactive('active',$row['i_email'],$row['active']);
							
							$Elements->iconactive('questions',$row['i_email'],$row['questions'],'questions');

							$Elements->iconactive('sales',$row['i_email'],$row['sales'],'sales');
								
							$Elements->iconrestore('restore',$row['i_email'],$row['trash']);
							
					$tpl->parse('list');
				}
				
			/*
			 * Generate dialog box combobox  
			 */
				$Elements->ajax_combobox('combo');
			
				$Elements->LoadElements($filter, $session);
		
				$order_elements = array(
						'name'      		=>	'Nombre',
						'email'  			=>	'Email',
						'sales'				=>  'Ventas',
						'question'			=>  'Consultas',
						'active'			=>  'Activo',
				);
					
				$Elements->Orderby($page,$order_elements, $session['order'],$session['table']);
		
				
				$module_content = $tpl->get();
			 /*
			  * Display File
			  */
			  require_once('display.inc.php');