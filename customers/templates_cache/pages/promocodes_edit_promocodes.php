<?php
	require_once('main.inc.php');
	$page				= $apache_index->get_uri_position(1);
	$page_title			= 'Promo Codes';
	$layout				= true;
	$where				= '';
	
	$tpl = new HTML_Template_Sigma(TEMPLATES_PATH, TEMPLATES_CACHE_PATH);
	$tpl->loadTemplatefile($page.'.tpl.html'); 
	$tpl->touchBlock($page);
	
	$pc 				= new Promo_Codes();
	//$pc->debug 			= true;
	$promo_rows			= $pc->get_list('','',$where,'`id_code` desc');
	
	if(_post('action')=='assign'){
		$ini = _post('init');
		$end = _post('end');
		$to = _post('assignto');
		$data['batch'] = $to;
		$pc->batch_update($data, $ini, $end);
	}
	
	$prev				= false;
	$initial			= false;
	$used_codes			= 0;
	$webplan			= 0;
	$boxplan			= 0;
	$tvplan				= 0;
	while($row = db::fetch_assoc($promo_rows)){
		if($initial==0){
			$prev = $row;
			$initial = $row['id_code'];
		}
		if($row['canjeado']==1){
			$used_codes++;
			$customer = new Customer();
			$customer_row = $customer->get($row['id_cliente']);
			switch($customer_row['producto']){
				case 'TVMIA00041':
				case 'Tvmia00041':
					$webplan++;
				break;
				case 'Tvmia00200':
				case 'TVMIA00200':
					$tvplan++;
				break;
				case 'Tvmia00500':
				case 'TVMIA00500':
					$boxplan++;
				break;
			}
		}
		if($prev['batch']!=$row['batch'] || $prev['id_code']-1>$row['id_code']){
			$tpl->setCurrentBlock('list');
				$tpl->setVariable('batch', $prev['id_code'].' - '.$initial);
				if($prev['batch']==''){
					$tpl->setVariable('assign', '<a onclick="batchform('.$prev['id_code'].','.$initial.');" id="'.$prev['id_code'].'_'.$initial.'" class="assign-promo" title="Asignar">Asignar</a>');
				}else{
					$tpl->setVariable('assign', $prev['batch']);
				}
				$tpl->setVariable('date', $prev['creation_date']);
				$tpl->setVariable('used', $used_codes);
				$tpl->setVariable('plan41', $webplan);
				$tpl->setVariable('plan200', $tvplan);
				$tpl->setVariable('plan500', $boxplan);
			$tpl->parseCurrentBlock();
			$used_codes = 0;
			$webplan			= 0;
			$boxplan			= 0;
			$tvplan				= 0;
			$initial = $row['id_code'];
		}
		$prev = $row;
	}
	$tpl->setCurrentBlock('list');
		$tpl->setVariable('batch', $prev['id_code'].' - '.$initial);
		$tpl->setVariable('assign', $prev['batch']);
		$tpl->setVariable('date', $prev['creation_date']);
		$tpl->setVariable('used', $used_codes);
		$tpl->setVariable('plan41', $webplan);
		$tpl->setVariable('plan200', $tvplan);
		$tpl->setVariable('plan500', $boxplan);
	$tpl->parseCurrentBlock();
	
	
	
	$module_content = $tpl->get();
	require_once('display.inc.php');
?>