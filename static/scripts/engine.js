function show_loader(){
	$('#title').fadeOut('fast',function(){
		$('#loader').fadeIn('fast');
		$('#search').val('');
		$('#content').animate({opacity:0.7},100);
	});
}

function hide_loader(){
	$('#content').animate({opacity:1},100);
	$('#loader').fadeOut('fast',function(){
		$('#title').fadeIn('fast');
	});
}

function login_form(show){
	if (show){
		login_button();
		$('#login').show();
		$('#login_loader').hide();
	} else {

	}
}

var histpush=false;
if (typeof history == 'undefined' || !'pushState' in history || (typeof window.addEventListener == 'undefined')) {
    histpush=false;
	log('[History Push]: disabled');
} else {
	histpush=true;
	window.addEventListener('popstate', function() {
		rd_ajax(history.state,1);
	});
	log('[History Push]: enabled');
}

function show_lightbox(titles,infos,pic){
	var br=((titles.length>0) && (infos.length>0)) ? '<b'+'r/>' : '';
	var text=titles+br+infos;
	$('body').css('overflow','hidden');
	center_lightbox();
	$('#lightbox-img').css('background-image','none').css('background-image',"url('"+pic+"')");
	$('#lightbox-text').html(text);
	$('#lightbox-viewer-bg,#lightbox-viewer-container').fadeIn('fast');
	$('#lightbox-view-full').attr('href',pic).attr('target','_blank');
}
function center_lightbox(){
	var a=($(window).height()/2)-($('#lightbox-viewer').height()/2)-20;
	var b=($(window).width()/2)-($('#lightbox-viewer').width()/2)-20;
	if (a<0){a=0;}
	if (b<0){b=0;}
	$('#lightbox-viewer')
		.css('top',a)
		.css('left',b);
}
function init_lightbox(){
	if ($('#lightbox-viewer-bg,#lightbox-viewer-container').length==0){
		$('body').append("<div id='lightbox-viewer-bg'></div><div id='lightbox-viewer-container'><div id='lightbox-viewer'><div id='lightbox-view-buttons' class='btn-toolbar'><a id='lightbox-view-full' data-title='Открыть полное изображение' class='btn btn-mini'><i class='icon-zoom-in'></i></a><a id='lightbox-view-close' data-title='Закрыть' class='btn btn-mini'><i class='icon-remove'></i></a></div><div id='lightbox-img-loading'></div><div id='lightbox-img'></div><div id='lightbox-panel'><p id='lightbox-text'></p></div></div></div>");
	}
	center_lightbox();
	$('#lightbox-view-close,#lightbox-viewer-bg').click(function(){
		$('body').css('overflow','visible');
		$('#lightbox-viewer-bg,#lightbox-viewer-container').fadeOut('fast');
	});
}

function Parse_Data(data,loadto){
	$("#"+loadto).fadeOut('fast',function(){
		log("Parsing data [to: '"+loadto+"']");
		hastitle=data.indexOf('#title#');
		hastext=data.indexOf('#content#');
		if ((hastitle==-1) || (hastext==-1)){
			p_title="Ошибка!";
			p_text="<di"+"v class='alert alert-error'>Произошла ошибка при получении данных!<b"+"r>Перейдите на <"+"a href='index.php?act=news' class='rdajax'>главную страницу</"+"a><b"+"r><sm"+"all>Описание ошибки: parse error, title: "+hastitle+", content: "+hastext+".</sm"+"all></di"+"v><sc"+"ript>alert('Произошла ошибка при получении данных','error');</sc"+"ript>";
		} else {
			p_title=data.slice(hastitle+7,data.indexOf('#/title#'));
			p_text=data.slice(hastext+9,data.indexOf('#/content#'));
		}
		$("#title").html(p_title);
		$("#"+loadto).html(p_text);
		document.title=js_site_name+": "+p_title;
		init();
		hide_loader();
		if (loadto!='ajax_temp'){
			$("#"+loadto).slideDown('normal');
		}
	});
}

var hash_changed=0;
var ajax_lock=false;
var noscrollup=true;
function rd_ajax(url,no_change_hash,meth,loadto,noparse){
	meth=meth || 'get';
	loadto=loadto || 'content';
	noparse=noparse || false;
	no_change_hash=no_change_hash || false;
	if ((meth!='get') && (meth!='post')){ meth='get'; }
	if (((url!==null) || ((url.indexOf('del')==-1) || (url.indexOf('confirmed')!=-1))) && (!ajax_lock)){
		query_string=url;
		ajax_lock=true;
		log('[AJAX] =Parameters=\r\nMethod: '+meth+'\r\nLoadTo: '+loadto+'\r\nNoParse: '+noparse+'\r\nNoHashChange: '+no_change_hash+'\r\nUrl: '+url);
		if ((location.hash.indexOf('debug')!=-1) && (!debug)){ debug=true; log('Debugger enabled'); }
		if (no_change_hash!=1){
			if (histpush){
				history.pushState('?'+url, '?'+url, '?' + url)
			} else {
				window.location.hash="#!/"+url;
			}
		} 
		hash_changed=2;
		show_loader();
		
		$('#like-box-button').popover('hide').popover('destroy');
		
		if ((loadto=='content') && !noscrollup){
			var topy=$('#title').position().top;
			$('html, body').animate({
				scrollTop:topy
			},300);
		}
		
		if (noscrollup){ noscrollup=false; }
		
		$.ajax({
			type: meth,
			url: 'index.php',
			dataType: 'html',
			timeout: 60000,
			cache: false,
			data: url,
			success: function(data){ ajax_lock=false; if (noparse){ hide_loader(); } else { Parse_Data(data,loadto); }},
			error: function(objAJAXRequest, strError){ if (noparse){ hide_loader(); alert('Ошибка при загрузке данных:<br>'+strError,'error'); } else { ajax_lock=false; hide_loader(); alert('Ошибка при загрузке данных:<br>'+strError,'error'); }; }
		});
	} else if (url===null){
		log('[AJAX]: error! Url is NULL');
	} else if (ajax_lock){
		alert('Пожалуйста, дождитесь окончания выполнения предыдущего запроса!');
		log('[AJAX]: ajax locked');
	} else {
		hide_loader();
		asker=confirm('Действительно удалить?');
		if (asker){
			rd_ajax(url+'&confirmed=1',no_change_hash,meth,loadto,noparse);
		}
	}
}

function first_nav(){
	if ((query_string=='') || (query_string=='#!/')){
		rd_ajax('act=news',1);
	} else {
		rd_ajax(query_string.replace('#!/',''),1);
	}
}

function checkHash(){
    if (window.location.hash != hash) {
		if (hash_changed==1) {
			hash = window.location.hash; 
			processHash(hash); 
		}
    } 
	hash = window.location.hash; 
	hash_changed=1;
}

function processHash(hash){
	hash=hash.replace('#!/','');
    rd_ajax(hash,1);
}

function insert(a, b) {
    element = document.getElementById('editor-area');
    if (document.selection) {
        element.focus();
        sel = document.selection.createRange();
        sel.text = a + sel.text + b
    } else if (element.selectionStart || element.selectionStart == '0') {
        element.focus();
        var c = element.selectionStart;
        var d = element.selectionEnd;
        element.value = element.value.substring(0, c) + a + element.value.substring(c, d) + b + element.value.substring(d, element.value.length)
    } else {
        element.value += a + b
    }
}

function modify_links(){
	if (ajax_enabled){
		var modif_cnt=0;
		
		init_lightbox();
		$('.text a:not(.lightbox)').each(function(i){
			if ($(this).children('img').length==1){
				var titles=($(this).children('p').html()==undefined) ? '' : '<str'+'ong>'+$(this).children('p').html()+'</str'+'ong>';
				var infos=($(this).attr('data-info')==undefined) ? '' : $(this).attr('data-info');
				var pic=$(this).attr('href');
				$(this).unbind().removeClass('news_link').addClass('lightbox thumbnail').attr('onClick',"show_lightbox(\""+titles+"\",\""+infos+"\",\""+pic+"\"); return false;").css('cursor','pointer');
				modif_cnt++;
			}
		});
		
		$('a.news_link').unbind();
		$('a.news_link').each( function(i){
			af=escape(this.href);
			$(this).attr('onClick',"rd_ajax('act=redir&to="+af+"',1,'get','ajax_temp'); return false;");
			modif_cnt+=1;
		});
		
		$('a.rdajax').unbind();
		$('a.rdajax').each( function(i){
			du=$(this).attr('data-url');
			dh=$(this).attr('data-hide');
			dc=$(this).attr('data-cont');
			dl=this.search.replace('?','');
			if (du==undefined){
				af=dl;
			} else {
				af=du;
			}
			prms="";
			if (dh=='true'){
				prms=",0,'get','ajax_temp',true";
			} else if (dc!=undefined){
				prms=",1,'get','"+dc+"',false";
			}
			
			$(this).attr('onClick',"rd_ajax('"+af+"'"+prms+"); return false;");
			modif_cnt+=1;
			du="";
			dl="";
		});
		
		$('*[data-title]').tipTip({maxWidth: "400px", edgeOffset: 10, delay:0, fadeIn:200, fadeOut:100, removeContent:false, attribute: 'data-title'});
		
		/*
		$('div.rdvideo').each( function(i){
			vsc=$(this).attr('data-src');
			vht=$(this).attr('data-height');
			if (vht==undefined){
				vht=360;
			}
			vid='rdvideo'+i;
			$(this).attr('id',vid);
			
			jwplayer(vid).setup({
				'flashplayer': 'http://static.rdrag.ru/media/player.swf',
				'skin': 'http://static.rdrag.ru/media/rd.xml',
				'dock': 'true',
				'controlbar': 'bottom',
				'width': '100%',
				'height': vht,
				'volume': '50',
				'bufferlength':'2',
				'repeat':'none',
				'shuffle':'false',
				'file':vsc,
				'plugins': {
					'timeslidertooltipplugin-3': {},
					'hd-2': {},
					'sharing-3':{
						'code':encodeURIComponent("<object classid='clsid:D27CDB6E-AE6D-11cf-96B8-444553540000' width='470' height='320'><param name='movie' value='player.swf'><param name='allowfullscreen' value='true'><param name='allowscriptaccess' value='always'><param name='wmode' value='transparent'><param name='flashvars' value='file="+vsc+"&skin=http://static.rdrag.ru/media/rd.zip'><embed src='http://static.rdrag.ru/media/player.swf' width='470' height='320' bgcolor='#000000' allowscriptaccess='always' allowfullscreen='true' flashvars='file="+vsc+"&skin=http://static.rdrag.ru/media/rd.zip'/></object>"),
						'link':vsc
					}
				}
			});
		});*/
		
		$('form[id!="login"]').unbind('submit').submit(function(event) {
			event.preventDefault();
			log('[AJAX] Form submit request detected');
			if ($(this).attr('method')){
				if ($(this).attr('method').toLowerCase()=='post'){
					rd_ajax($(this).serialize(),1,'post',$(this).attr('data-cont'));
				} else {
					rd_ajax($(this).serialize(),1,'get',$(this).attr('data-cont'));
				}
			} else {
				rd_ajax($(this).serialize());
			}
			return false;
		});
		
		$('#pager').unbind('submit').submit(function(event) {
			event.preventDefault();
			log('[AJAX] Pager request detected');
			rd_ajax('act=news&part='+$('#pager-part').val()+$('#addit').val());
		});
		
        $('#sub-upload').unbind('submit').submit(function(event) {
			event.preventDefault();
			show_loader();
			$.ajaxFileUpload({
				url:'index.php?ajax=1&'+$(this).serialize(), 
				secureuri:false,
				fileElementId:'file',
				dataType: 'text',
				success: function (data, status){ hide_loader(); Parse_Data(data,'content'); },
				error: function (data, status, e){ alert(e,'error'); hide_loader(); }
			});
		});
		
		$('.spoiler').children('.spoiler-title').unbind('click').click(function(){
			$(this).parent().children('.spoiler-inner').slideToggle('fast');
		});
		
		log('[AJAX]: AJAXification completed. AJAX-links: '+modif_cnt);
	}
}

function login_button(){
	$('#user_menu').html("").slideUp('fast');
	$('#login').slideDown('fast');
}

function login_req(form){
	log('Login form submit');
	$.post('index.php', $(form).serialize(),function(){},'script');
	$('#login').hide();
	$('#login_loader').show();
}

function html_codes_init(){
	$('#editor-tags').tagsInput({width:'auto',height:'65px',autocomplete_url:'index.php?act=complete&ajax=2',autocomplete:{selectFirst:true,width:'100px',autoFill:true}});
    $('#editor-codes img').click(function () {
        var a = attribs = $(this).attr("alt");
        a = a.replace(/\[.*\]/, '');
        if (/\[.*\]/.test(attribs)) {
            attribs = attribs.replace(/.*\[(.*)\]/, ' $1')
        } else attribs = '';
        var b = '';
        if (a == 'a') {
            var c = prompt("Введите ссылку", "http://");
			if (c==null){ return false; }
            attribs = 'href="' + c + '"';
            b = ' '
        }
        if (a == 'img') {
            var c = prompt("Введите ссылку на картинку", "http://");
			if (c==null){ return false; }
            attribs = 'src="' + c + '"';
            b = ' '
        }
        if (a == 'video') {
			a="div";
			var c = prompt("Введите ссылку на видео", "http://");
			if (c==null){ return false; }
            attribs = 'data-src="' + c + '"';
            b = " class='rdvideo' ";
        }
        if (a == 'spoiler') {
			a="spoiler";
			var c = prompt("Введите заголовок для спойлера или оставьте поле пустым", "");
			if (c==null || c==''){ c=''; } else { c='='+c; }
            attribs = '';
            b = c;
        }
        if (a == 'code') {
            a = 'code';
        }
        var d = '<' + a + b + attribs + '>';
		if ((a=='hr') || (a=='img')){ var e=''; } else { var e = '</' + a + '>'; }
        insert(d, e);
        return false
    });
}

function get_tags(title){
	var a,b,c;
	a=title.split(' ');
	for (b in a){
		if (a[b].length<4){
			a.splice(b,1);
		}
	}
	c=a.toString();
	if ($('#editor-tags').val()==""){
		$('#editor-tags').val(c);
		$('#editor-tags').importTags(c);
	}
	return c;
}

var inits_count=0;
function init(){
	modify_links();
	html_codes_init();
	VK.Auth.getLoginStatus(vk_auth_info);
	
	if (inits_count==0){
		// first-time initialization
	}
	inits_count++;
};

function set_user_info(id,name,nick,photo){
	if (nick!=''){
		show_as=nick;
	} else {
		show_as=name;
	}
	$('#user_menu').html("<div class='center'><img src='"+photo+"' class='img-polaroid userpic'><p><a href='?act=users&profile="+id+"' class='rdajax' data-title='Открыть страницу профиля'>"+show_as+"</a></p><a href='?act=users&mode=edit&id="+id+"' class='rdajax btn btn-mini btn-block' data-title='Редактировать профиль'><i class='icon-pencil'></i> Редактировать</a><a class='btn btn-mini btn-block' href='javascript:' onClick='doLogout(); return false;' data-title='Выход'><i class='icon-remove'></i> Выйти</a></p></div>").slideDown('fast');
	$('#login').slideUp('fast');
	$('#login_loader').hide();
	modify_links();
}

function vk_auth_info(response) {
	if (response.session) {	log('[VK Auth] SID: '+ response.session.sid); auth_state(true); } else { log('[VK Auth] SID: N/A'); auth_state(false); }
}

function auth_state(state){
	log('Checking User Info');
	if (((admin) || (user) || (moder)) && (state)){
		set_user_info(userid,username,usernick,userphoto);
		login_form(0);
	} else {
		log('[auth_state] Requesting User Info');
		$.getScript('index.php?act=auth&ajax=2',function(data){
			if ((admin) || (user) || (moder)){
				if (!admin){ $("#admin_menu").slideUp('fast'); }
				if (!moder){ $("#mod_menu").slideUp('fast'); }
				set_user_info(userid,username,usernick,userphoto);
				login_form(0);
				//$('#login_loader').hide();
			} else {
				login_button();
			}
		});
	}
}

function doLogin() {
	log('[doLogin] Login request');
	VK.Auth.login(function (response) {
		log('[doLogin] Login response:', response);
		auth_state(true);
	}, null);
}

function doLogout(){
	log('[doLogout] Logout request');
	$.get('/modules/cookie.php?reset=1','',function(data){
		admin=false;
		user=false;
		auth_state(false);
		VK.Auth.logout();
		$("#admin_menu").slideUp('fast');
		$("#mod_menu").slideUp('fast');
		$("#profile_popup").slideUp('fast');
		alert('Выход произведен!','noerror');
	});
}

function alert(text,error,timeout){
	timeout=timeout||20;
	alert_id+=1;
	type='';
	if (error!=null){
		if (error=='noerror'){
			type=' alert-success';
		} else if (error=='debug'){
			type=' alert-info';
		} else {
			type=' alert-error';
		}
	}
	$('#alerts_container').append("<di"+"v id='popup_msg_"+alert_id+"' class='popup-msg alert"+type+"' onClick='$(this).slideUp(\"fast\");' data-rdtime='"+timeout+"'>"+text+"</di"+"v>");
	$('#popup_msg_'+alert_id).slideDown('fast');
}

function special_notify(text){
	$('html, body').animate({
		scrollTop:0
	},300);
	$('#vk_msg').html(text);
	$("#vk_frame_bg").fadeIn('fast');
	return true;
}

function log(text){
	if (debug){
		console.log(text);
		text=text.replace(/\r\n/g,'<br/>');
		alert('[Console]<br>'+text,'debug');
	}
}

function recache(cacher){
	show_loader();
	$('html, body').animate({
		scrollTop:0
	},300);
	$.ajax({
		type: 'get',
		url: cacher,
		dataType: 'html',
		timeout: 60000,
		cache: false,
		data: '',
		success: function(data){ special_notify("Результат кеширования файлов: <pre>"+data+"</pre><a href='/'>Обновить страницу</a>"); hide_loader(); },
		error: function(objAJAXRequest, strError){ special_notify("<di"+"v class='alert alert-error'>Произошла ошибка при получении данных!</"+"div><b"+"r><sm"+"all>Описание ошибки: "+strError+".</sm"+"all>");hide_loader(); }
	});
}

function center_elements(){
	$('#login_form_container').css('top',($(window).height()/2)-($('#login_form_container').height()/2)).css('left',($(window).width()/2)-($('#login_form_container').width()/2));
	$('#vk_frame_inner').css('top',($(window).height()/2)-($('#vk_frame_inner').height()/2)).css('left',($(window).width()/2)-($('#vk_frame_inner').width()/2));
}

function toggle_adm(id){
	$("#form"+id).slideToggle("slow");
	$("#admin"+id).toggleClass("editor_show");
}

function insert_reply(el){
	var toins='<b>' + el + '</b>, ';
	var ctx=$('#coms_editor').val();
	$('#coms_editor').val(toins+ctx);
}

$(document).ready(function () {
	var disable_rd=disable_rd || false;
	if (!disable_rd){
		VK.init({
			apiId: js_vk_api_id,
			nameTransportPath: "/include/xd_reciever.html"
		});
		checkHash();
		var ch_int=setInterval("checkHash()",100);
		first_nav();
		center_elements();
		setInterval(function(){
			for (i=0; i<=alert_id; i++){
				attr=$('#popup_msg_'+i).attr('data-rdtime');
				if (attr>0){
					$('#popup_msg_'+i).attr('data-rdtime',attr-1);
				} else {
					$('#popup_msg_'+i).slideUp('fast');
				}
			}
		},500);
	}
	$('.navbar-hider').click(function(){ $('.navbar-menu').toggleClass('navbar-hidden'); });
});

$(window).resize(function(){
	center_elements();
	center_lightbox();
});

$(window).scroll(function(){
	if ($('html').offset().top<-130){
		$('body').addClass('scroll');
	} else {
		$('body').removeClass('scroll');
	}
});

$(document).ready(function(){
	$('#profile_button').addClass("ajaxloader");
});
