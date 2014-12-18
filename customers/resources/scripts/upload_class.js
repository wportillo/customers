/*
 * Generate Generate Gallery
 */
function Generate_Gallery(constructor_object){

					if(typeof(constructor_object.gallery_id)!='undefined'){
						this.gallery_id	   =  constructor_object.gallery_id;
					}else{
						this.gallery_id='upload_image';
					};
		
					if(typeof(constructor_object.table_id)!='undefined'){
						this.table_id	   =  constructor_object.table_id;
					};
					this.pattern 	   = 	 { 
											   filename  : /^[A-Za-z0-9_.-]{3,60}$/,

											   extension : /^(jpg|png|jpeg|gif|pdf|doc|docx|xls|xlsx|ppt|pptx|csv|zip|html|xml)$/,
											 };
					
					if(typeof(constructor_object.file_action)!='undefined'){
						this.file_action   =  constructor_object.file_action;
					}else{
						this.file_action   =  'files_operation';
					};
					
					if(typeof(constructor_object.parent_id)!='undefined'){
						this.parent_id	   = constructor_object.parent_id;
					}else{
						this.parent_id	   = 'galery';
					};
					
					this.file_db	   = constructor_object.file_db;

					this.jsonfile	   = function(){	

						if(this.file_db != null){
							return	this.jsondecode(this.file_db);
						}else{
							return new Array(); 
						}
					};
					this.urlencode	= function(str){
						
						 str = (str + '').toString();

						 return encodeURIComponent(str).replace(/!/g, '%21').replace(/'/g, '%27').replace(/\(t/g, '%28');
					};
					this.file_direction	= constructor_object.file_direction;
						
					this.delete_thumb  = function(id){

						delete_arr_thumb = this.jsonfile();
						
						var button_delete = $('#'+this.gallery_id+'_delete');

						delete_arr_thumb.splice(jQuery.inArray(id,delete_arr_thumb),1);
						
						this.file_db = this.jsonencode(delete_arr_thumb);

						this.load_thumbs();
						
						button_delete.hide();
						
						Objdef=this;
						
						jQuery.ajax({
						          url	  	: this.file_action,
						          type	  	: 'POST',
						          data	  	: { image_id  : id ,
						        	  			parent_id : Objdef.parent_id,
						        	  			gallery_id: this.table_id,
						        	  			action:'delete' 
						        	  		  },
						          dataType	: 'json',
						});
	
					};
					this.save_bd	= function(){
					
						jQuery.ajax({
						          url	  	: this.file_action,
						          type	  	: 'POST',
						          data	  	: { 
						        	  		    image_galery : this.file_db,
						        	  			parent_id    : this.parent_id,
						        	  			gallery_id	 : this.table_id
						          			  },
						          dataType	: 'json',
						});
					};
					this.show_action_button = function(){
						
						var thumb_array = new Array();
						
						var button_delete = $('#'+this.gallery_id+'_delete');
						
						$('#'+this.gallery_id+'_galery'+' input:checked').each(function(){
							thumb_array.push($(this).val());	
						});
						
						if(thumb_array.length==0){
							button_delete.hide();
						}else{
							button_delete.show();
						}
					};
					
					this.delete_selected_thumb = function(){
						
						var objself = this;
						
						$('#'+this.gallery_id+'_galery'+' input:checked').each(function(){
							objself.delete_thumb($(this).val());
						});
						
					};
					
					this.jsonencode =  function(javascript_array){
						return	window.JSON.stringify(javascript_array);
					};
					
					this.jsondecode = function(json_string){
						return	jQuery.parseJSON(json_string);
					};
					
					this.load_new_file = function(file){

									add_arr_thumb = this.jsonfile();

									add_arr_thumb.push(file);

									this.file_db = this.jsonencode(add_arr_thumb);

									this.load_thumbs();
									
									this.save_bd();
					};

					this.setcookievalue = function(array){
						$.cookie(this.gallery_id+'_file',this.jsonencode(array));
					};
					
					this.generate_upload_html=function(){
						
						html='<input class="button" type="button" id="'+this.gallery_id+'" value="Cargar Archivo" title="Haga click aqui para cargar imagenes"/>';
						
						html+='<input class="button" type="button" id="'+this.gallery_id+'_delete" value="Borrar Archivo"  title="Haga click aqui para borrar las imagenes" onclick="'+this.parent_id+'.delete_selected_thumb();"/>';
					
						html+='<div class="galery" id="'+this.gallery_id+'_galery"></div>';
			
						$('#'+this.parent_id+'_parent').html(html);
					};
					
					this.check = function(filename_var,extension_var){
						
							errors=false;

							if( (typeof(extension_var) != 'undefined' ) && ( ! this.pattern.extension.test(extension_var))){
									errors = ['Solo se permiten imagenes .jpg .png .jpeg .gif'];
							}
							
							if(!(this.pattern.filename.test(filename_var))){
									errors = ['Verifique que el archivo no contenga caracteres especiales o espacios en blanco (!@#$%^&*()'];
							}
							if(jQuery.inArray(filename_var, this.jsonfile())!='-1'){
								errors = ['Este nombre de archivo ya fue cargado'];
							}
							
							if(errors!=false){
								alert(errors[0]);
							}else{
								return true;
							}
						
					};
					this.getfilename=function(filename){
						return filename.split('.').pop();
					};
					this.generate_thumb	= function(image){
						
						html='<div class="thumb_galery" title="'+image+'">';
						
						
						switch(this.getfilename(image)){
							case 'pdf':
								html+='<a  href="downloadfile/?filename='+this.urlencode(this.file_direction+image)+'" target="_blank" style="width:127px; display:block;text-align: center;"><img src="resources/hd_icons/icon_pdf.png" height="100"/></a>';
							break;
							case 'html':
								html+='<a  href="downloadfile/?filename='+this.urlencode(this.file_direction+image)+'" target="_blank" style="width:127px; display:block;text-align: center;"><img src="resources/hd_icons/icon_html.png" height="100"/></a>';
							break;
							case 'xml':
								html+='<a  href="downloadfile/?filename='+this.urlencode(this.file_direction+image)+'" target="_blank" style="width:127px; display:block;text-align: center;"><img src="resources/hd_icons/icon_xml.png" height="100"/></a>';
							break;
							case 'zip':
								html+='<a  href="downloadfile/?filename='+this.urlencode(this.file_direction+image)+'" target="_blank" style="width:127px; display:block;text-align: center;"><img src="resources/hd_icons/icon_zip.png" height="100"/></a>';
							break;
							case 'csv':
								html+='<a  href="downloadfile/?filename='+this.urlencode(this.file_direction+image)+'" target="_blank" style="width:127px; display:block;text-align: center;"><img src="resources/hd_icons/icon_csv.png" height="100"/></a>';
							break;
							case 'ppt':
							case 'pptx':
								html+='<a  href="downloadfile/?filename='+this.urlencode(this.file_direction+image)+'" target="_blank" style="width:127px; display:block;text-align: center;"><img src="resources/hd_icons/icon_ppt.png" height="100"/></a>';
							break;
							case 'doc':
							case 'docx':
								html+='<a  href="downloadfile/?filename='+this.urlencode(this.file_direction+image)+'" target="_blank" style="width:127px; display:block;text-align: center;"><img src="resources/hd_icons/icon_doc.png" height="100"/></a>';
							break;
							case 'xls':
							case 'xlsx':
								html+='<a  href="downloadfile/?filename='+this.urlencode(this.file_direction+image)+'" target="_blank" style="width:127px; display:block;text-align: center;"><img src="resources/hd_icons/icon_xls.png" height="100"/></a>';
							break;
							default:
								html+='<a  href="thumb/?height=640&width=480&filename='+this.urlencode(this.file_direction+image)+'"  rel="lightbox" style="width:127px; display:block;text-align: center;" ><img src="thumb/?height=100&width=100&prop=false&filename='+this.urlencode(this.file_direction+image)+'" id="'+image+'" height="100"/></a>';
							break;
						}
				
						
						html+='<input type="checkbox" value="'+image+'" onclick="'+this.parent_id+'.show_action_button();">';
						
						html+='<a href="javascript:'+this.parent_id+'.delete_thumb(\''+image+'\');"><img src="/resources/images/icons/cross.png" alt="delete" style=" float: right;margin-left: 61px;margin-top: 2px;"/></a>';
						
						html+='</div>';

						return html;
					};
					
					this.load_thumbs = function(){
						
						var objself = this;
						
						var galery_thumb = new String();
					
						jQuery.each(this.jsonfile(),function(index,value){ 
							galery_thumb += objself.generate_thumb(value);		
			         	});
										
						$('#'+this.gallery_id+'_galery').html(galery_thumb);
						
						$('a[rel=lightbox]').lightBox(); 
					};
				 	
					this.sortable = function(){
				 	
						Objef = this;
					
						$('#'+this.parent_id+'_galery').sortable({
  						   	
							   revert: true,
  						   
  						   	   opacity: 0.6, 
							   
							   cursor: 'move', 
							   
							   connectWith: '#'+Objef.parent_id+'_galery',
								   
							   update: function(event,ui) {
								  
								   var array_sortable = new Array();
								   
								   $('#'+Objef.gallery_id+'_galery').children().each(function(index){
									   
										array_sortable.push({image: $(this).attr('title'),pos:index});
									});
							     
								jQuery.ajax({
								          url	  	:   Objef.file_action,
								          type	  	:  'POST',
								          data	  	:  { 
								        	  			 sortable: Objef.jsonencode(array_sortable),
								        	  			
								        	  			 action :'sortable'
								          			   },
								          dataType	:  'json',
								});
							   },
 						});
				 	};
				 	
				 	this.ajax_upload = function(){
					 		
				 			this.generate_upload_html();
							
				 			this.sortable();
				 			
					 		this.load_thumbs();
					 		
					 		var objself = this;
				 	
							button 	      = $('#'+this.gallery_id);
							
							button_delete = $('#'+this.gallery_id+'_delete');
							
							button_delete.hide();
							
								new AjaxUpload('#'+this.gallery_id ,{
										
										action	 : objself.file_action,
										
										name     : 'user_file_'+ this.parent_id,
						
										data	 : {gallery_id:objself.table_id},
										
										onSubmit : function(file , ext){
											
									    if (objself.check(file,ext)!=true){
										
												return false;
										}else{
											
											$('#'+objself.gallery_id).val('Cargando...');
		
											this.disable();
										}
								 },
									 	onComplete: function(file, response){ 
									 		
									 		this.enable();
			
									 		objself.load_new_file(file);
			
									 		$('#'+objself.gallery_id).val('Cargar archivo');
									 		
								}	
							});
				 	};
	};