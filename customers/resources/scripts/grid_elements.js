/**
 * Grid Prompt Events
 * 
 */
	$(document).ready(function(){
	
		
		/**
		 * Delete Grid Prompt
		 * 
		 */
			$('.icon-event').click(function() {
				
				link 		= $(this).attr('id');
				
				alert_text  = $(this).attr('title');
				
					$('#dialog_icon').attr('title','Confirmacion');
					
					$('#dialog_icon').html(alert_text);
					
					$('#dialog_icon').dialog(
							
							{ autoOpen:false},
							
							{ show: { effect: 'drop', direction: 'up' }},
							
							{ resizable: false},
							
							{ buttons: { 
								
								'Aceptar': function() { 
								
								document.location.href=link;
							}
			
							},
														
						});
						
						if($('#dialog_icon').dialog('isOpen')==false){
							$('#dialog_icon').dialog('open');
						}else{
							$('#dialog_icon').dialog('close');
						}
			});
			/**
			 * Grid Check
			 */
			$('#grid input:checkbox').click(function(){
				
				
				
					checked = [];
					
					$(this+':checked').each(function(){
						checked.push($(this).attr('name'));
					});
					
					
					$('#ui-id-1').html('Seleccione una accion');
					$('#dialog').attr('title','Seleccione una accion');
							
							$('#dialog').html($('#combo_ajax').html());
							
							$('#dialog').dialog(
									
									{autoOpen:false},
									
									{ show: { effect: 'drop', direction: 'up' }},
									
									{ resizable: true},
									
									{ buttons: { 
										
										 'Aceptar': function() { 
									
										 if($(this).children('#accept_option').val()!='0'){
											
											 	$.ajax({
														type: 'POST',
														url: document.location.href,
														data:{
															action : $(this).children('#accept_option').val(),
															id	   : checked,
														},
														
														datatype:'text',
														
														success:function(data){
															   // 	document.location.reload();
														},
											 
											 	}); 
											 	
										  }else{
											  $(this).dialog('option', 'title', '<span style="color:red;">Debe seleccionar una Acción</span>');
										  }
									}
									},
					});
					/**
					 * Dialog event
					 */
					if($('form input:checkbox:checked').length > 0 && $('#combo_ajax').html()!='Accion:<select name="accept_option" id="accept_option"></select>'){
						if($('#dialog').dialog('isOpen')==false){
							$('#dialog').dialog('open');
						}
					}else{
						if($('#dialog').dialog('isOpen')){
							$('#dialog').dialog('destroy'); 
						}
					}
				});
			
			
			$('.commenthistory').click(function(){
				$('#ui-id-1').html('Historial de anotaciones');
				$('#dialog').attr('title','Historial de anotaciones');
				$.ajax(
						{type: 'POST',
						 url: document.location.href,
						 async: true,
						 data:'checkcomment='+$(this).attr('id'),
						 success:function(data){
							 $('#dialog').html(data);
						 },
					});
				
				$('#dialog').dialog(
					{autoOpen:true},
					{ show: { effect: 'drop', direction: 'up' }},
					{ resizable: true},
					{buttons:{}});
			});
			
			$('.makecomment').click(function(){
				var id = $(this).attr('id');
				$('#ui-id-1').html('Agregar anotación');
				$('#dialog').attr('title','Agregar anotación');
				$('#dialog').html('<textarea name="comment" id="comment" style="width:256px;height:140px;"></textarea>');
				$('#dialog').dialog(
					{autoOpen:true},
					{ show: { effect: 'drop', direction: 'up' }},
					{ resizable: true},
					{buttons:{
							'Aceptar': function() { 
								var datastring = 'comment='+$(this).children('#comment').val()+'&commid='+id;
									$.ajax({
										type: 'POST',
										url: document.location.href,
										async: true,
										data:datastring,
										success:function(data){
											$('#dialog').dialog('destroy'); 
										},
							  	}); 
						  
					}}});
			});
	});
	
	function batchform(ini, end){
		
		$('#dialog').attr('title','Asignar lote de códigos');
		$('#dialog').html(	'<label style="width:90px; float:left; margin-top:7px; display:block;">Inicial: </label><input name="ini" id="ini" value="'+ini+'"></input></br>'+
							'<label style="width:90px; float:left; margin-top:7px; display:block;">Final: </label><input name="end" id="end" value="'+end+'"></input></br>'+
							'<label style="width:90px; float:left; margin-top:7px; display:block;">Asignado a: </label><input name="assignto" id="assignto"</input>');
		$('#dialog').dialog(
			{autoOpen: true},
			{show: { effect: 'drop', direction: 'up' }},
			{resizable: false},
			{buttons: 
				{'Aceptar': function() 
					{if(ini>$('#ini').val()||end<$('#end').val()||$('#assignto').val()==''){
						if(ini>$('#ini').val()){$('#dialog').dialog('option', 'title', '<span style="color:red;">Valor inicial inválido</span>');}
						if(end<$('#end').val()){$('#dialog').dialog('option', 'title', '<span style="color:red;">Valor final inválido</span>');}
						if($('#assignto').val()==''){$('#dialog').dialog('option', 'title', '<span style="color:red;">Debe asignar el lote</span>');}
					}else{
						var datastring = 'action=assign&init='+$('#ini').val()+'&end='+$('#end').val()+'&assignto='+$('#assignto').val();
						$.ajax({
							type: 'POST',
							url: document.location.href,
							async: true,
							data:datastring,
							success:function(data){
								document.location.reload();
							}
						});
					}
				}
			},
		});
		
		
	}
