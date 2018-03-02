/**
 * Create By Putra
 * public function
 */
//url current protocol
function urlprotocol(){
	return document.location.protocol + "//" + document.location.hostname + "/";	 
}

$(function(){		
	//use sort by list produk grid
	sortby_grid();	
	//counter qty cart item
	$(".counter").on('click',function(){
		var click = $(this);
		var oldValue = $('[name=quantity]').val();
		if (click.attr('id') == 'plus'){
			var newValue = parseFloat(oldValue) + 1;
		}else{
			// Don't allow decrementing below zero
			if (oldValue > 0){
				var newValue = parseFloat(oldValue) - 1;
			}else{
				newValue=0;
			}
		}
		$('[name=quantity]').val(newValue);		
		
	});
	
	search_form();
	load_form_ajax();
	//autoload notif alert option
	 var x = $('.success').html();	
	 var y = $('.warning').html();
	 var z = $('.danger').html();
    if(x !=''){      	
   	 show_alert('.success','success',x);
    }
    if(y !=''){     	
   	 show_alert('.warning','warning',y);
    }
    if(z !=''){       	
   	 show_alert('.danger','danger',z);
    }    
	
    //date opicker auto load
    $('.date-picker').datepicker({
		autoclose: true,
		todayHighlight: true
	})
	
	//tab menu active memberarea
	var url = window.location;
	   var found = false;
	   //load array href value if href == url
	   $("#mytab a").each(function(){
	      var href = $(this).attr("href");	     
	      if(href==url){    	    	
	         $(this).parent().addClass("active");
	         found = true;
	      }
	   });	   
	   //jika url false
	   if (!found){
		   var spl = window.location.pathname.split('/');
	    	  var lastparam = spl.pop(-1);	    	  
	    	  if (lastparam == 'edit'){
	    		  $("#myaccount").addClass("active");//edit kontak & addresss
	    	  }else{
	    		  $("#myorder").addClass("active");//used if order details
	    	  }
	   }
})
function msp_token(){
	return $("meta[name=msp_token]").attr('content');
}

function ajaxform(url,div,form){	
	loading(true);				
	var formData = new FormData(form);
	$.ajax ({
		url		: url,
		type	: 'post',
		mimeType : 'multipart/form-data',
		data	: formData,
		contentType: false,
		processData: false,
		cache	: false,
		async:false,
		timeout : 600000,			
		success	: function (param)
		{			
			var json = eval('('+param+')');
			if(json.csrf_token !== undefined)
			$('meta[name=msp_token]').attr("content", json.csrf_token);
			$('.msp_token').attr("value", json.csrf_token);
			//sukkses
			if (json.error == 0)
			{				
				if (json.konfirm_order == true){
					$("#"+div).toggle();
					$("#"+div).html(json.finish_page);
					$("#"+div).show();
					$('html,body').scrollTop(0);
					loading(false);	
				}else if (json.paypal == true){
					window.location.href=json.redirect;					
				}else if (json.add_cart == true){
					$("#"+div).html(json.content);
					$("#list-cart").html(json.list_cart);
					show_alert('.success','success',json.msg);
					loading(false);	
				}					
			}
			else{
				if (json.error == true){
					//show dialog popup				
					show_alert('.warning','warning',json.msg);
				}else if (json.error_loop == true){
					//proses looping notif
					var msg = json.msg;
					for(var i=0; i < msg.length; i++){
						//show dialog popup
						alert(msg[i]);
												
					}		
				}
				loading(false);	
			}			
			//clearconsole();							
            return json.data;//back to reload data 
		},		
		error : function(jqXHR, status, errorThrown){			
			if (jqXHR.status == 403){
				alert("CSRF session is expired Or CSRF not working, try process again!");
			}else{
				alert(status+': '+errorThrown);
			}
		}
	});	
	
}
function load_form_ajax(){
	//save form submit required
	$("#form-ajax").on('submit',function(e){			
		e.preventDefault();				
		var spl = $(this).attr('action').split("#");			
		var url = spl[0];
		var div = spl[1];								
		ajaxform(url,div,this);
	})
}

function show_alert(div,status,msg){	
	$(div).removeClass("alert alert-block alert-danger");
	$(div).removeClass("alert alert-block alert-success");
	$(div).removeClass("alert alert-block alert-warning");
	$(div).addClass("alert alert-block alert-"+status);
	if(status == 'warning')
		var span = ' <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><strong>WARNING!</strong><br>';
	else if (status == 'success')
		var span = ' <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><strong>CONGRATULATIONS!</strong><br>';
	else if (status == 'danger')
		var span = ' <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><strong>ERROR!</strong><br>';
    //$(div).html("<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>"+"<b>"+msg+"</b>").show(500);
    $(div).html(span+msg).slideDown();//slideDown(500);
// 	$(div).removeClass('hide');
    //$(div).slideUp();
	setTimeout(function(){ $(div).slideUp(500); },20000);
}

function clear_field(){
	$('.clear').prop("selected", "selected");
	$('.clear').val('');
	$('.clear').val('');	
	$(".clear").prop("checked", false);	
	$(".clear").val('').trigger("liszt:updated");
}

function ajaxcall(url,val,div)
{			
	loading(true);			 
	var datax = {'value':val,'div':div,'msp_token':msp_token};
	$.ajax({
		url 	: url,
		type	: 'post',
		data	: datax,
		cache	: false,
		success	: function (param){		
			 var json = eval('('+param+')');
			 	if(json.csrf_token !== undefined) 
				 $('meta[name=msp_token]').attr("content", json.csrf_token);
				 $('.msp_token').attr("value", json.csrf_token);				
			 if (json.error == 0){	
				 	if (json.select_attribute == true){				 		
				 		$("#price_old").html(json.element['price_old']);
				 		$("#price_total").html(json.element['price_total']);
				 		$("#promocarosel").html(json.element['promocarosel']);
				 		$("#available").html(json.element['stok']);
				 		$("#btnorder").html(json.element['btnorder']);
				 	}else if (json.change_address == true){
				 		$("#"+div).html(json.address);
				 		$("#courierlist").html(json.metod_shipp);	
				 		$('html,body').scrollTop(0);
				 	}else if (json.change_courier == true){
				 		$("#costshipp").html(json.costshipp);
				 		$("#costshipp2").html(json.costshipp);
				 		$("#totalshopp").html(json.totalshopp);
				 		$("#totalshopp2").html(json.totalshopp);	
				 		$("#paymentlist").html(json.paymentlist);
				 	}else if (json.edit_qty_cart == true){				 								
						$('[name=quantity'+div+']').val(json.newvalue);		
						$('#subtotal'+json.subid).html(json.subtotal);	
						$('#totqty').html(json.totqty);
						$('#subtotal').html(json.subtotprice);
						$('#subtotal2').html(json.subtotprice);
						$("#costshipp").html(0);
						$("#costshipp2").html(0);
						$("#totalshopp").html(0);
						$("#totalshopp2").html(0);
						$("#list-cart").html(json.listcart);
						$("#courierlist").html(json.metod_shipp);	
						$("#amount_tax").html(json.amount_tax);
						$("#amount_tax2").html(json.amount_tax);
				 	}else if (json.districtarea == true){				 		
				 		$("#districtarea").html(json.content);
				 	}else{
				 		$("#"+div).html(json.element);
				 	}				 	
				 	if (json.msg != null){
				 		show_alert('.success','success',json.msg);
			 		}
				 
			 }else if (json.error == 1){
				
				 show_alert('.danger','danger',json.msg);
			 }		
			 loading(false);	
			 clearconsole();
	         return json.data;//back to reload data 		
		},error : function(jqXHR, status, errorThrown){				
			if (jqXHR.status == 403){
				alert("CSRF session is expired Or AcccesDenied,Refresh and try process again!");
			}else{
				alert(status+': '+errorThrown);
			}
		}
	})
}

function ajaxcheck(url,val,obj,div=''){		
	loading(true);	
	var cb = document.getElementsByTagName('input');
	var spl = val.split('#');
	var access = spl[1];
	if (obj.checked){
		var check = 1;
		/*jika melakukan check all sesuai access*/
		if (url == 'bo/mpgroup/check_all'){					
			for (var i=0; i < cb.length; i++)
			{
				if (cb[i].className == access)
				{
					cb[i].checked = true;
				}
			}
		}
	}else{
		var check = 0;
		/*jika melakukan check all sesuai access*/
		if (url == 'bo/mpgroup/check_all'){
			for (var i=0; i < cb.length; i++)
			{
				if (cb[i].className == access)
				{
					cb[i].checked = false;
				}
			} 
		}
	}		
	var datax = {'value':val,'check':check,'msp_token':msp_token};
	$.ajax({
		url 	: url,
		type	: 'post',
		data	: datax,
		cache	: false,
		success	: function (param){
			var json = eval('('+param+')');
			if (json.error == 0){
				//jika ada element yang direplace
				if (json.element != null){
					$("#"+div).html(json.element);
					//use for share customer to employee
					$("#tbltele").html(json.table);
				}else if (json.defaults != null){
					//used for check default and redirect page
					bootbox_alert(true,json.msg);
					window.location.href = json.redirect;
					return false;
				}
				show_alert('.success','success',json.msg);
				bootbox_alert(true,json.msg);
				
			}else if (json.error == 1){
				show_alert('.danger','danger',json.msg);
				bootbox_alert(false,json.msg);
				
			}
			loading(false);
			clearconsole();
			if(json.csrf_token !== undefined) $('meta[name=msp_token]').attr("content", json.csrf_token);
            return json.data;//back to reload data 
			
		},error : function(jqXHR, status, errorThrown){			
			if (jqXHR.status == 403){
				alert("CSRF session is expired Or CSRF not working, try process again!");
			}else{
				alert(status+': '+errorThrown);
			}
		}
	})
}
function DeleteConfirm(url,val,div){
	bootbox.confirm("Are you sure delete this data ?", function(result) {
		if(result) {
			//actions
			loading(true);
			var datax = {'value':val,'msp_token':msp_token};
			$.ajax({
				url 	: url,
				type	: 'post',
				data	: datax,
				cache	: false,
				success	: function (param){
					var json = eval('('+param+')');
					if (json.error == 0){						
						if (json.table == 'dt'){
							//jika datatable
							$(".selected").fadeOut('slow');																		
						}else{
							$(".selected-df").fadeOut('slow');	
						}
						
						if (div != ""){
							if (json.cart != null){
								//used for delete in cart
								$("#"+div).html(json.cart);
								$("#shipping").html(json.shipping);
							}else if (json.mgm != null){
								$("#"+div).html(json.stock);
								document.getElementById('global').value = json.global;//show stock global
							}else{
								$("#"+div).html(json.content);
							}
							
						}
						show_alert('.success','success',json.msg);
						bootbox.alert('<div class="alert alert-success bigger-150 center"><i class="ace-icon fa fa-check-square-o"></i> CONGRATULATIONS! <div class="space-6"></div>'+json.msg+'<div>');
					}else{
						//return proses false
						bootbox.alert('<div class="alert alert-danger bigger-150 center"><i class="ace-icon fa fa-remove"></i> ERROR! <div class="space-6"></div>'+json.msg+'<div>');
						show_alert('.danger','danger',json.msg);
					}					
					loading(false);
					clearconsole();
					if(json.csrf_token !== undefined) $('meta[name=msp_token]').attr("content", json.csrf_token);
		            return json.data;//back to reload data 
				},error : function(jqXHR, status, errorThrown){			
					if (jqXHR.status == 403){
						alert("CSRF session is expired Or CSRF not working, try process again!");
					}else{
						alert(status+': '+errorThrown);
					}
				}
			})
		}
	});
}

function ajaxModal(urlx,val,div){	
	loading(true);	
	var datax = {'value':val,'msp_token':msp_token};
	$.ajax({
		url : urlx,
		type : 'post',
		data : datax,
		cache	: false,
		success	: function(param){		
			var json = eval('('+param+')');
			if(json.csrf_token !== undefined) 
			$('meta[name=msp_token]').attr("content", json.csrf_token);
			$('.msp_token').attr("value", json.csrf_token);
			$('#'+div).html(json.modal);			
			loading(false);				
			load_form_modal();
			clearconsole();		
			return json.data;//back to reload data 
		},error : function(jqXHR, status, errorThrown){			
			if (jqXHR.status == 403){
				alert("CSRF session is expired Or CSRF not working, try process again!");
			}else{
				alert(status+': '+errorThrown);
			}
		}
	})
}

function load_form_modal(){
	//save form submit required
	$("#formmodal").on('submit',function(e){			
		e.preventDefault();							
		var spl = $(this).attr('action').split("#");			
		var url = spl[0];
		var div = spl[1];
		
		var formData = 	new FormData(this);				
		loading(true);				
		$.ajax ({
			url		: url,
			type	: 'post',
			mimeType : 'multipart/form-data',
			data	: formData,
			contentType: false,
			processData: false,
			cache	: false,
			async:false,
			timeout : 600000,
			success	: function (param)
			{			
				var json = eval('('+param+')');
				if(json.csrf_token !== undefined)
				$('meta[name=msp_token]').attr("content", json.csrf_token);
				$('.msp_token').attr("value", json.csrf_token);					
				if (json.error == 0)
				{		
					if (json.change_address == true)
					{
						$("#"+div).html(json.address);
						$("#courierlist").html(json.metod_shipp);
					}else if (json.cancel_order == true){
						$("#"+div).html(json.statepay);
					}else if (json.receive == true){
						$("#"+div).html(json.dataorder);
					}					
					$("#MyModal").modal('toggle');
					$('html,body').scrollTop(0);
					show_alert('.success_modal','success',json.msg);
					show_alert('.success','success',json.msg);
					
				}											
				else if (json.error == 1 && json.type == "error")
				{				
					//show dialog popup					
					show_alert('.danger_modal','danger',json.msg);
				}			
				else if (json.error == 1 && json.type == "error_loop")
				{				
					//proses looping notif
					var msg = json.msg;
					for(var i=0; i < msg.length; i++){
						//show dialog popup
						//bootbox_alert(false,msg[i]);
						alert(msg[i]);						
					}					
				}	
				loading(false);		
				clearconsole();
	            return json.data;//back to reload data 
			},
			error : function(jqXHR, status, errorThrown){			
				if (jqXHR.status == 403){
					alert("CSRF session is expired Or CSRF not working, try process again!");
				}else{
					alert(status+': '+errorThrown);
				}
			}
		});
	})
}
var colorbox_params = {
		rel: 'colorbox',
		reposition:true,
		scalePhotos:true,
		scrolling:false,
		previous:'<i class="ace-icon fa fa-arrow-left"></i>',
		next:'<i class="ace-icon fa fa-arrow-right"></i>',
		close:'&times;',
		current:'{current} of {total}',
		maxWidth:'100%',
		maxHeight:'100%',
		onOpen:function(){
			$overflow = document.body.style.overflow;
			document.body.style.overflow = 'hidden';
		},
		onClosed:function(){
			document.body.style.overflow = $overflow;
		},
		onComplete:function(){
			$.colorbox.resize();
		}
	};
function loading(is_show)
{	var idbtn= "#btn-submit";			
	if (is_show == true)
	{
		var valuebtn = $(idbtn).val();
		document.cookie = valuebtn;		
		$('.loading').html("<div class='loader-fixed'></div>");
		$(idbtn).val('Please Wait...');
		$(idbtn).attr("disabled","disabled");			
	}
	else 
	{
		$(".loading").html('');
		var ck = document.cookie.split(";");
		$(idbtn).val(ck[0]);
		$(idbtn).removeAttr("disabled");
	}
}	
function loading_modal(is_show)
{
	if (is_show == true)
	{
		$(".loading_modal").html("<div class='loader-fixed'></div>");
		
	}
	else 
	{
		$(".loading_modal").html('');
	}
}
function ajaxDelete(url,val,div)
{		
	if (confirm('Are you sure delete this data ?')){
		loading(true);
		var datax = {'value':val,'msp_token':msp_token};
		$.ajax({
			url 	: url,
			type	: 'post',
			data	: datax,
			cache	: false,
			success	: function (param){
				var json = eval('('+param+')');
				if(json.csrf_token !== undefined)
					$('meta[name=msp_token]').attr("content", json.csrf_token);
					$('.msp_token').attr("value", json.csrf_token);
				if (json.error == 0){		
					if (json.rowcart == true){
						$("#"+json.rowid).hide(100);	
						$("#courierlist").html(json.metod_shipp);	
						$("#subtotal").html(json.subtotal);
						$("#subtotal2").html(json.subtotal);
						$("#costshipp").html(0);
						$("#costshipp2").html(0);
						$("#totalshopp").html(0);
						$("#totalshopp2").html(0);
						$('#totqty').html(json.totqty);
						$("#"+div).html(json.listcart);
					}else{
						$("#"+div).html(json.content);	
						show_alert('.success','success',json.msg);	
					}
									
				}				
				else{
					show_alert('.danger','danger',json.msg);					
				}			
				loading(false);
			},error : function(jqXHR, status, errorThrown){			
				if (jqXHR.status == 403){
					alert("CSRF session is expired Or CSRF not working, try process again!");
				}else{
					alert(status+': '+errorThrown);
				}
			}
		})
	}	
	
}
function pagination(url,val,div){		
	loading(true);			 
	var datax = {'value':val,'div':div,'msp_token':msp_token};
	$.ajax({
		url 	: url,
		type	: 'post',
		data	: datax,
		cache	: false,
		success	: function (param){	
			var json = eval('('+param+')');
			if(json.csrf_token !== undefined) 
			$('meta[name=msp_token]').attr("content", json.csrf_token);
			$('.msp_token').attr("value", json.csrf_token);
			if (json.error == 0){
				$('#'+div).html(json.element); 	
			}else{
				alert('error 1');
			}						
			loading(false);
			clearconsole();
		},error : function(jqXHR, status, errorThrown){				
			if (jqXHR.status == 403){
				alert("CSRF session is expired Or AcccesDenied,Refresh and try process again!");
			}else{
				alert(status+': '+errorThrown);
			}
		}
	})
}
function search_form(){
	$("#search-form").on('submit',function(e){			
		e.preventDefault();				
		loading(true);				
		var formData = new FormData(this);		
		$.ajax({
			url 	: urlprotocol() + 'w/search/product/x',
			type	: 'post',
			mimeType : 'multipart/form-data',
			data	: formData,
			contentType: false,
			processData: false,
			cache	: false,
			async:false,
			timeout : 600000,		
			success	: function (param){	
				var json = eval('('+param+')');
				if(json.csrf_token !== undefined) 
				$('meta[name=msp_token]').attr("content", json.csrf_token);
				$('.msp_token').attr("value", json.csrf_token);
				$("#content-list").html(json.content);				
				var key = json.keyword.replace(/\s/g,"-");//replace all space					
				window.history.pushState("keyword", "Replace URL",urlprotocol() + "w/search/product/"+key);
				loading(false);
				clearconsole();
			},error : function(jqXHR, status, errorThrown){				
				if (jqXHR.status == 403){
					alert("CSRF session is expired Or AcccesDenied,Refresh and try process again!");
				}else{
					alert(status+': '+errorThrown);
				}
			}
		})
	})
}
function sortby_grid(){
	$(".sortby, .sortbysearch").on('click',function(){		
		var val = $(this).prop('id');
		var permalink = $(this).prop('name');		
		var value = permalink+'#'+val;
		if (this.className == 'sortby'){
			var url = urlprotocol() +'w/sort_by';
		}			
		else{
			var url = urlprotocol() +'w/search/sortby_search';	
		}				
		ajaxcall(url,value,'produk');
	});
}
function clearconsole() { 
	  console.log(window.console);
	  if(window.console || window.console.firebug) {
	   console.clear();
	  }
}