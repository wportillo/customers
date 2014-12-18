<?php
			$moduleroll = array('roll'=>'customers','permissions'=>'delete');
			
			/**
			 * Db Class
			 */
			
				$where 	     = _session('where_customer','');			
		
				$Customers   = new Customer_info();
	
				$Customers->debug=false;
		
				/*
				 * Codificacion csv
				 */
				$csv='<meta http-equiv="Content-type" content="text/html; charset=utf-8" />';		
				
				$csv_begin='<tr>';
				
				$csv_end = '</tr>';
				
				function insert_td($valor){
					return '<td WIDTH=110 HEIGHT=15 style="font-size:11px;">'.trim(strip_tags($valor)).'</td>';
				}
				
				function insert_td_style($valor,$stylo,$collspan=1){
						
					if($collspan==1){
						return '<td WIDTH=110 HEIGHT=15 style="font-size:11px;'.$stylo.'">'.trim($valor).'</td>';
					}else{
						return '<td WIDTH=110 HEIGHT=15 style="font-size:11px;'.$stylo.'" colspan="'.$collspan.'" ALIGN=CENTER>'.trim($valor).'</td>';
					}
				
				}
				
				
				
			/*
			 * Title
			 */
				$csv.='<table align="center">';
				
				$csv.=
				$csv_begin.
					insert_td_style('Listado de clientes','font-size:16px;',9).
				$csv_end;
				$csv.=
				$csv_begin.
					insert_td_style('',9).
				$csv_end;
				$csv.=
				$csv_begin.
					insert_td('Nombre').
					insert_td('Email').
					insert_td('Creado').
					insert_td('Telefono').
					insert_td('Producto').
					insert_td('Activo').
				$csv_end;
				
				
				$grilla=$Customers->get_list('','',$where,'');
				
				while($row=db::fetch_assoc($grilla)){	
					
						$csv.=
							$csv_begin.
								insert_td($row['name'].' '.$row['surname']).
								insert_td($row['email']).
								insert_td(mysql_date_to_spanish($row['creation_date'])).
								insert_td('+'.$row['areacode'].$row['phone']).
								insert_td($row['key_product']).
								insert_td(($row['active']==1)?'Activo': 'Inactivo').
							$csv_end;
				}
						
			$csv.='</table>';
			
			echo $csv;
			
			header('Content-type: application/vnd.ms-excel');
			
			header('Content-Disposition:filename='.basename('customers_'.date('d_m_Y')).'.xls;');
			
			
?>