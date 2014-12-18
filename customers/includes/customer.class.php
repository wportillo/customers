<?php 

/**
 * Customer Method
 *  @author william
 */
	Class Customer{
		
		public  function addfree($data,$session_vars){
		
			$data = array(
						
					'object'  		=>'Actions',
					'method'	    =>'addfree',
					'lang'	 		=>'es',
					'name'			=> $data['name'],
					'surname'		=> $data['surname'],
					'email'			=> $data['email'],
					'email_repeat'	=> $data['email_repeat'],
					'country'		=> 'United States',
					'areacode'		=> $data['areacode'],
					'i_user'		=> $session_vars['i_user'],
					'i_reseller'	=> $session_vars['i_reseller'],
					'i_store'		=> $session_vars['i_store'],
					'phone'			=> $data['phone'],
			);
		
			$message =  json_decode(jsonconnect($data));
			
			if($message->error){
				return  $message;
			}
		}
		
		public function rechargereseller($amount,$session_vars){
			
			$data = array(
					'object'  		 =>'Actions',
					'method'	     =>'rechargereseller',
					'lang'	 		 =>'es',
					'i_reseller'	 => $session_vars['i_reseller'],
					'amount'		 => $amount,
			);
			
			$jconnect = jsonconnect($data);
			
			$message =  json_decode($jconnect);
				
			if($message->error){
				return  $message;
			}
		}
		
		public function changeplan($data,$session_vars){

			$data = array(
					'object'  		 =>'Memberactions',
					'method'	     =>'changeplan',
					'lang'	 		 =>'es',
					'i_product'		 => $data['key_product'],
					'automatic_debit'=> '1',
					'payment_method' => $data['payment_method'],
					'i_reseller'	 => $session_vars['i_reseller'],
					'i_store'		 => $session_vars['i_store'],
					'i_customer'	 => $data['i_customer'],
					'cvv'		 	 => $data['cvv'],
					'number'		 => $data['number'],
					'month'		 	 => $data['month'],
					'year'		 	 => $data['year'],
					'c_address'		 => $data['c_address'],
					'c_zip'		 	 => $data['c_zip'],
					'c_name'		 => $data['c_name'],
					'c_surname'		 => $data['c_surname'],
			);
			
			$jconnect = jsonconnect($data);

			$message =  json_decode($jconnect);
			
			if($message->error){
				return  $message;
			}
		}
		
		public function makepayment($data,$session_vars){

			$data = array(
					'object'  		 =>'Memberactions',
					'method'	     =>'makecustomerpayment',
					'lang'	 		 =>'es',
					'payment_method' => $data['payment_method'],
					'i_reseller'	 => $session_vars['i_reseller'],
					'i_store'		 => $session_vars['i_store'],
					'i_customer'	 => $data['i_customer'],
					'cvv'		 	 => $data['cvv'],
					'number'		 => $data['number'],
					'month'		 	 => $data['month'],
					'year'		 	 => $data['year'],
					'c_address'		 => $data['c_address'],
					'c_zip'		 	 => $data['c_zip'],
					'c_name'		 => $data['c_name'],
					'c_surname'		 => $data['c_surname'],
			);
				
			$jconnect = jsonconnect($data);
			
			$message =  json_decode($jconnect);
				
			if($message->error){
				return  $message;
			}
		}
		
		public  function addpremium($data,$session_vars){
		
			$data = array(
					'object'  		 =>'Actions',
					'method'	     =>'addpremium',
					'lang'	 		 =>'es',
					'number'		 => $data['number'],
					'cvv'		 	 => $data['cvv'],
					'month'		 	 => $data['month'],
					'year'		 	 => $data['year'],
					'c_address'		 => $data['c_address'],
					'c_zip'		 	 => $data['c_zip'],
					'payment_method' => $data['payment_method'],
					'c_name'		 => $data['name'],
					'c_surname'		 => $data['surname'],
					'key_product'	 => $data['key_product'],
					'name'			 => $data['name'],
					'surname'		 => $data['surname'],
					'email'			 => $data['email'],
					'email_repeat'	 => $data['email_repeat'],
					'password'		 => $data['password'],
					'password_repeat'=> $data['password_repeat'],
					'country'		 => $data['country'],
					'areacode'		 => $data['areacode'],
					'i_user'		 => $session_vars['i_user'],
					'i_reseller'	 => $session_vars['i_reseller'],
					'i_store'		 => $session_vars['i_store'],
					'phone'			 => $data['phone'],
			);
		
			$jconnect = jsonconnect($data);
			
			$message =  json_decode($jconnect);
			
			if($message->error){
				return  $message;
			}
		}
	}
?>