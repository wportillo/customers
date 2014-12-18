$(document).ready(function(){
		hide_product();
		$('.fancybox').fancybox({width  : 680,height : 720});
		
		mobile_detection();
		//Sidebar Accordion Menu:
		
		$("#main-nav li ul").hide(); // Hide all sub menus
		$("#main-nav li a.current").parent().find("ul").slideToggle("slow"); // Slide down the current menu item's sub menu
		
		$("#main-nav li a.nav-top-item").click( // When a top menu item is clicked...
			function () {
				$(this).parent().siblings().find("ul").slideUp("normal"); // Slide up all sub menus except the one clicked
				$(this).next().slideToggle("normal"); // Slide down the clicked sub menu
				return false;
			}
		);
		
		$("#main-nav li a.no-submenu").click( // When a menu item with no sub menu is clicked...
			function () {
				window.location.href=(this.href); // Just open the link instead of a sub menu
				return false;
			}
		); 

    // Sidebar Accordion Menu Hover Effect:
		
		$("#main-nav li .nav-top-item").hover(
			function () {
				$(this).stop().animate({ paddingRight: "25px" }, 200);
			}, 
			function () {
				$(this).stop().animate({ paddingRight: "15px" });
			}
		);
		
		get_stat_sidebar();
    
		//Minimize Content Box
		
		$(".content-box-header h3").css({ "cursor":"s-resize" }); // Give the h3 in Content Box Header a different cursor
		$(".closed-box .content-box-content").hide(); // Hide the content of the header if it has the class "closed"
		$(".closed-box .content-box-tabs").hide(); // Hide the tabs in the header if it has the class "closed"
		
		$(".content-box-header h3").click( // When the h3 is clicked...
			function () {
			  $(this).parent().next().toggle(); // Toggle the Content Box
			  $(this).parent().parent().toggleClass("closed-box"); // Toggle the class "closed-box" on the content box
			  $(this).parent().find(".content-box-tabs").toggle(); // Toggle the tabs
			}
		);

    // Content box tabs:
		
		$('.content-box .content-box-content div.tab-content').hide(); // Hide the content divs
		$('ul.content-box-tabs li a.default-tab').addClass('current'); // Add the class "current" to the default tab
		$('.content-box-content div.default-tab').show(); // Show the div with class "default-tab"
		
		$('.content-box ul.content-box-tabs li a').click( // When a tab is clicked...
			function() { 
				$(this).parent().siblings().find("a").removeClass('current'); // Remove "current" class from all tabs
				$(this).addClass('current'); // Add class "current" to clicked tab
				var currentTab = $(this).attr('href'); // Set variable "currentTab" to the value of href of clicked tab
				$(currentTab).siblings().hide(); // Hide all content divs
				$(currentTab).show(); // Show the content div with the id equal to the id of clicked tab
				return false; 
			}
		);

   
		

    // Alternating table rows:
		
		$('tbody tr:even').addClass("alt-row"); // Add class "alt-row" to even table rows

    // Check all checkboxes when the one in a table head is checked:
		
		$('.check-all').click(
			function(){
				$(this).parent().parent().parent().parent().find("input[type='checkbox']").attr('checked', $(this).is(':checked'));   
			}
		);
		$('.check-all-permissions').click(
			function(){
				
				var id = $(this).attr('id');
				
				$(this).parent().parent().parent().parent().find("input[id='"+id+"']").attr('checked', $(this).is(':checked'));   
			}
		);
		$('.delete-permissions').click(
			function(){
				
				var id = $(this).attr('id');
				
				$(this).parent().parent().parent().parent().find("input[id='"+id+"'][class='view-permissions'],input[id='"+id+"'][class='check-all-permissions']").attr('checked', 'checked');
			
			}
		);
		$('.edit-permissions').click(
			function(){
				var id = $(this).attr('id');
				
				$(this).parent().parent().parent().parent().find("input[id='"+id+"'][class='view-permissions'],input[id='"+id+"'][class='check-all-permissions']").attr('checked', 'checked');
			}
		);
		$('.view-permissions').click(
			function(){
				
				var id = $(this).attr('id');

				if($(this).attr('checked')=='checked'){
					$(this).parent().parent().parent().parent().find("input[id='"+id+"'][class='check-all-permissions']").attr('checked','checked');
				}else{
					$(this).parent().parent().parent().parent().find("input[id='"+id+"']").removeAttr("checked");
				}
			}
		);
		

    // Initialise Facebox Modal window:
		
	//	$('a[rel*=modal]').facebox(); // Applies modal window to any link with attribute rel="modal"

    // Initialise jQuery WYSIWYG:
		
		//$(".wysiwyg").wysiwyg(); // Applies WYSIWYG editor to any textarea with the class "wysiwyg"

});
//Close button:
function closes(){
	$(".close").parent().fadeTo(400, 0, function () { // Links with the class "close" will close parent
		$(this).slideUp(400);
	});
	return false;
} 
function isArray(testObject) {
    return testObject && !(testObject.propertyIsEnumerable('length')) && typeof testObject === 'object' && typeof testObject.length === 'number';
}
function hidesidebar(){
	
	if($('#sidebar').is(':visible')){
	    
		$('#sidebar').fadeOut('fast');
		
	    $('#main-content').css('margin-left','20px');
	    
	    $('#sidebar_button').css('left','0px');
	    
	    $.cookie('sidebar_stat','close');
	}else{
	   
	    $('#sidebar').fadeIn('fast');
	    
	    $('#main-content').css('margin-left','260px');
	    
	    $('#sidebar_button').css('left','234px');
	    
	    $.cookie('sidebar_stat','open');
	}
}
function get_stat_sidebar(){
	
	if($.cookie('sidebar_stat')=='close'){
	    
		$('#sidebar').hide();

	    $('#main-content').css('margin-left','20px');
	    
	    $('#sidebar_button').css('left','0px');
	}
}
function mobile_detection(){
    
	if(navigator.platform == 'iPad' || navigator.platform == 'iPhone' || navigator.platform == 'iPod' || navigator.platform == 'Android'){
		
		$('#sidebar').fadeOut('fast');
		
	    $('#main-content').css('margin-left','20px');
	    
	    $('#sidebar_button').css('left','0px');
	    
	    $.cookie('sidebar_stat','close');
    }	
}
function iSubmitEnter(oEvento, oFormulario){
    var iAscii;

    if (oEvento.keyCode)
        iAscii = oEvento.keyCode;
    else if (oEvento.which)
        iAscii = oEvento.which;
    else
        return false;

    if (iAscii == 13) oFormulario.submit();

    return true;
}
function hide_product(){

    var type = $('#type');
    
    switch(type.val()){
    	case '0':
        $('#financing_fees').parent().hide();
        $('#financing').parent().hide();
        $('#shipping').parent().hide();
        $('#shipping_international').parent().hide();
        $('#sessions').parent().show();
        $('#residual').parent().show();
        $('#cycle').parent().show();
        $('#commissions').parent().show();
        $('#price').parent().show();
        $('#monthly').parent().show();
      break;
      case 'Service':
         $('#financing_fees').parent().hide();
         $('#financing').parent().hide();
         $('#shipping').parent().hide();
         $('#shipping_international').parent().hide();
         $('#sessions').parent().show();
         $('#residual').parent().show();
         $('#cycle').parent().show();
         $('#commissions').parent().show();
         $('#price').parent().show();
         $('#monthly').parent().show();
       break;
        case 'Article':
        
         $('#sessions').parent().hide();
         $('#residual').parent().hide();
         $('#cycle').parent().hide();
         $('#commissions').parent().hide();
         $('#price').parent().hide();
         $('#monthly').parent().hide();
         $('#financing_fees').parent().show();
         $('#financing').parent().show();
         $('#shipping').parent().show();
         $('#shipping_international').parent().show();
     
        break;

    }
}
