<?php
		/**
		 * Main File
		 */
			require_once('main.inc.php');
		
			$Product_info  = new Product_info();
			
			$Product_info  -> primary_key='key_product';
			
			$Customer_info = new Customer_info();
			
			$rscustomer = $Customer_info->get_list(false,false,'active=1 AND test=0');
		
			while($row = db::fetch_assoc($rscustomer)){
				
				$product = $Product_info->get($row['key_product']);
				
				/*
				 * Free 
				 */
					if($product['amount'] == 0 && $product['subscription'] == 0){

						if(myexpiredate($row['valid'])< 0){

							$data['active']='0';
							
							$Customer_info->update($data, $row['i_customer']);
						}
					
					}else{
						
						if(myexpiredate($row['next_payment'])< 0){
							
							$data['active']='0';
							
							$Customer_info->update($data, $row['i_customer']);
						}
				 	}
			}
?>