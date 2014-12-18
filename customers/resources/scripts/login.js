$(document).ready(function(){
	

			$('.icon-event').click(function() {
					
					$('#dialog').dialog(
							
							{ autoOpen:false},
							
							{ show: { effect: 'drop', direction: 'up' }},
							
							{ resizable: false},
							
							{ buttons: { 
								
								'Aceptar': function() { 
								
									if($('#forgot').val()==''){
										  $(this).dialog('option', 'title', '<span style="color:red;font-size:12px;">Ingrese su correo electrónico</span>');
									}else{
										
										$.ajax({
											type: 'POST',
											url: '/forget',
											async: true,
											data:{
												user : $('#forget').val()
											},
											
											success:function(data){
											
												message = $.parseJSON(data);
												
												if(message.error){
													 $('#dialog').dialog('option', 'title', '<span style="color:red;font-size:12px;">'+message.message+'</span>');
												}else{
													 $('#dialog').dialog('option', 'title', '<span style="color:green;font-size:12px;">'+message.message+'</span>');
														
												}
											}
										}); 
									}
							}
							},
														
						});
					
						if($('#dialog').dialog('isOpen')==false){
							$('#dialog').dialog('open');
						}else{
							$('#dialog').dialog('destroy');
						}
			});

});