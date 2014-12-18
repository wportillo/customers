<?php
	require_once('main.inc.php');
	$page	  		    = $apache_index->get_uri_position(1);
	$page_number	    = $apache_index->get_uri_position(2);
	$instruction 	    = $apache_index->get_uri_position(3);
	$instruction_value  = $apache_index->get_uri_position(4);
	
	$page_title       = 'Comisiones';
	$layout           = true;
	$max			  = 50;
	$order			  = '';
	$where 			  = 'i_commission!=0 AND trash = 0 ';
	
	$session_reseller = _session('id_reseller');
	
	$tpl = new HTML_Template_Sigma(TEMPLATES_PATH, TEMPLATES_CACHE_PATH);
	$tpl->loadTemplatefile($page.'.tpl.html');
	$tpl->touchBlock($page);
	$tpl->setVariable('page_title',$page_title);
	
	$commissions = new Commissions();
	//$commissions->debug = true;
	$tempcustomer = new Customer();
	//$tempcustomer ->debug = true;
	print_r(_session('selected_commissions'));
	$gridElements 	   				= new Grid_elements();
	$gridElements->db_object		= $commissions;
	$gridElements->page				= $page;
	$gridElements->rol_name			= $page_security_attr['rol_name'];
	$gridElements->security			= $Security;
	$gridElements->object_template	= $tpl;
	
	$formElements    =  new Form_elements();
	$formElements->object_template=$tpl;
	
	$label_notification	= _session('label_notification','');
	if($label_notification!=''){
		$gridElements->notification($label_notification['label'],$label_notification['type']);
		unset($_SESSION['label_notification']);
	}
	
	$pr=new Products();
	
	$postElements=array(
		'product'	  	=>		array('0','dropmenu_bd','Producto',array('drop_value'=>'i_product','drop_name'=>'name_product'),new Products(),'trash!=1 and active=1',false,false,'get_product();',_session('id_rol')),
		'i_reseller'	=>		array('0','dropmenu_bd','Agente reseller',array('drop_value'=>'id_reseller','drop_name'=>'name_reseller'),new Business_Reseller(),(_session('id_reseller')==0)?'id_reseller!=0':'id_reseller='._session('id_reseller'),false,false,false),
		'i_client'		=>		array('','input','ID TVmia','3'),
		'i_telefonica'	=>		array('','input','ID Telefónica','3'),
		'desde'			=>		array('','calendar','Desde'),
		'hasta'			=>		array('','calendar','Hasta'),
		'ir_a'			=>		array('','',''),
		'payment'		=>		array('0','dropmenu','Balance',array('sp'=>'Sin procesar','pending'=>'Pendiente','ok'=>'Pagado'),false,false),
			
		'checkcomment'	=>		array('','',''),
		'commid'		=>		array('','',''),
		'comment'		=>		array('','',''),
		'action'		=>		array('','',''),
		'selected'	  	=>		array('','',''),
		'cantidad'		=>		array('0','dropmenu','Cantidad por pag',array('50'=>'50','100'=>'100','150'=>'150','200'=>'200','250'=>'250'),false,'javascript:document.grilla.submit();',false)
	);
	
	$form_val['send_form'] 			=  _post('send_form');
	if($instruction=='reset'){
		foreach ($postElements as $value=>$atrrib){
			unset($_SESSION[$value.'_commissions']);
			header('location:/commissions');
		}
	}
	foreach($postElements as $value=>$default_value){
		$form_val[$value] = _post($value,$default_value[0]);
		if($form_val['send_form']!=''){
			$_SESSION[$value.'_commissions']   = $form_val[$value];
		}
		$session[$value]				   =	_session($value.'_commissions',$default_value[0]);
	}
	if(_post('add')==1){
		if(!in_array(_post('addtosession'), $_SESSION['selected_commissions'])){
			$_SESSION['selected_commissions'][]=_post('addtosession');
		}else{
			if(($key = array_search(_post('addtosession'), $_SESSION['selected_commissions'])) !== false) {
				unset($_SESSION['selected_commissions'][$key]);
			}
		}
	}
	
	
	
	if($gridElements->check_rol_security_delete()){
		if($instruction=='delete'){
			$commissions->Delete($instruction_value);
			header('location:/commissions');
		}
	}
	if($form_val['send_form']!='' && $form_val['action']!='0'){
		if($form_val['comment']!='' && $gridElements->check_rol_security_delete()){
			$result = $gridElements->commentCommissions($form_val['selected'], $form_val['comment']);
		}
		switch($form_val['action']){
			case'delete':
				if($gridElements->check_rol_security_delete()){
					$gridElements->Delete($form_val['selected']);
				}
			break;
			case'generate':
				if($gridElements->check_rol_security_delete()){
					//$result = $gridElements->generateInvoice($form_val['selected']);
					$result = $gridElements->generateInvoice($_SESSION['selected_commissions']);
					if($result!='OK'){
						$_SESSION['label_notification']['label'] = $result;
						$_SESSION['label_notification']['type'] = 2;
					}
				}
			break;
			
		}
	}
	
	foreach($postElements as $value=>$default_value){
		$form_val[$value] = _post($value,$default_value[0]);
		if($form_val['send_form']!=''){
			$_SESSION[$value.'_commissions']   = $form_val[$value];
		}
		$session[$value]				   =	_session($value.'_commissions',$default_value[0]);
	}
	
	if($form_val['checkcomment']!=''){
		$comm_id = explode('-', $form_val['checkcomment']);
		$comment = $commissions->get($comm_id[1]);
		unset($_SESSION['checkcomment_commissions']);
		echo '<div><p>'.$comment['comment'].'</p></div>';
		exit();
	}
	
	if($form_val['commid']!=''){
		$singlecomm = explode('-', $form_val['commid']);
		$result = $gridElements->commentCommissions($singlecomm[1], $form_val['comment']);
		exit();
	}
	
	$orderByElements=array(
		'generation_date'	=>	'Fecha de generación',
		'product'			=>	'Producto',
		'ammount'			=>	'Cantidad',
		'i_invoice'			=>	'Invoice asociado',
		'i_telefonica'		=>	'ID telefónica',
		'payment'			=>	'Estado',
		'i_reseller'		=>	'Reseller',
		'i_client'			=>	'ID TVmia'		
	);
	
	if($instruction!='' && $instruction_value!=''){
		$order= $instruction.' '.$instruction_value;
	}
	
	if($session['cantidad']!='0'){
		$max=$session['cantidad'];
	}
	
	if($session['desde']!='' && $session['hasta']!=''){
		if($session['desde']!=$session['hasta']){
			$where.=' AND `generation_date` BETWEEN '.db::quote(spanish_date_to_mysql($session['desde']).' 00:00:00').' AND '.db::quote(spanish_date_to_mysql($session['hasta']).' 11:59:59 ');
		}else{
			$where.=' AND `generation_date` LIKE '.db::quote(spanish_date_to_mysql($session['desde']).'%');
		}
	}
	
	if($session['product']!='0'){
		$where.="AND `product`='".$session['product']."'";
	}
	if($session['payment']!='0'){
		$pay = 0;
		switch($session['payment']){
			case 'sp':
				$pay = 0;
			break;
			case 'pending':
				$pay = 1;
			break;
			case 'ok':
				$pay = 2;
			break;
		}
		$where.="AND `payment`=".$pay;
	}
	if($session['i_reseller']!='0'){
		$where.="AND `i_reseller`=".$session['i_reseller'];
	}
	if($session['i_client']!=''){
		$where.="AND `i_client`=".$session['i_client'];
	}
	if($session['i_telefonica']!=''){
		$rowcust=$tempcustomer->get_list('','','`id_telefonica`='.$session['i_telefonica'],'');
		$row=db::fetch_assoc($rowcust);
		$where.="AND `i_client`=".$row['id_cliente'];
	}
	
	if(_session('id_reseller')!=0){
		$tpl->hideBlock('isresellerheader');
	}
	$gridElements->where=$where;
	$pag = $gridElements->generate_pagination($page_number,$max,$form_val['ir_a'],$instruction,$instruction_value);
	$grilla=$commissions->get_list($max,$pag,$where,$order);
	while($row=db::fetch_assoc($grilla)){
		$rowcustomer = $tempcustomer->get($row['i_client']);
		if($rowcustomer['id_reseller']==_session('id_reseller')||_session('id_reseller')==0){
			$tpl->setCurrentBlock('list');
			if(_session('selected_commissions')){
				if(in_array($row['i_commission'], $_SESSION['selected_commissions'])){
					$tpl->setVariable('ischecked', 'checked="1"');
				}else{
					$tpl->setVariable('ischecked', '');
				}
			}
			
			$tpl->setVariable('i_client', '<a href="/customer/'.$row['i_client'].'" >'.$row['i_client'].'</a>');
			$rowcust=$tempcustomer->get($row['i_client']);
			$tpl->setVariable('i_telefonica',$rowcust['id_telefonica']);
			if($rowcust['estatus_canales']&&$rowcust['estatus_adultos']&&$rowcust['estatus_videos']){
				$tpl->setVariable('status', '<img src="'.BASE.'resources/images/icons/activo.png"></img>');
			}else{
				$tpl->setVariable('status', '<img src="'.BASE.'resources/images/icons/desactivo.png"></img>');
			}
			$tpl->setVariable('id',$row['i_commission']);
			$tempreseller = new Business_Reseller();
			$rowreseller = $tempreseller->get($row['i_reseller']);
			$tpl->setVariable('name_reseller', $rowreseller['name_reseller']);
			
			$tpl->setVariable('product', $row['description']);
			$tpl->setVariable('ammount', $row['ammount']);
			$tpl->setVariable('generation_date', $row['generation_date']);
			switch($row['payment']){
				case 2:
					$tpl->setVariable('payment', 'Pagado');
				break;
				case 1:
					$tpl->setVariable('payment', 'Pendiente');
				break;
				case 0;
					$tpl->setVariable('payment', 'Sin procesar');
				break;
			}
			$tpl->setVariable('i_invoice', ($row['i_invoice']!=0)?'<a href="/invoice/'.$row['i_invoice'].'/pdf" target="_blank">invoice# :'.$row['i_invoice'].'</a>':'Sin asociar');
			if($row['comment']!=''){
				$tpl->setVariable('comments', '<img title="Ver historial" class="commenthistory" id="comment-'.$row['i_commission'].'" style="float:right;" src="'.BASE.'resources/icons/comment.gif"></img>');
			}
			
			if(_session('id_reseller')!=0){
				$tpl->hideBlock('isreseller');
			}
			$tpl->parse('list');
		}
	}
	$tpl->setVariable('new_search','/'.$page.'/pag-1/reset');
	$gridElements->generate_invoice_combobox('combo');
	$gridElements->Generate_Form($postElements, $session);
	$gridElements->Generate_OrderBy($page, $page_number,$orderByElements,$instruction,$instruction_value);
	
	$module_content = $tpl->get();
	require_once('display.inc.php');
?>