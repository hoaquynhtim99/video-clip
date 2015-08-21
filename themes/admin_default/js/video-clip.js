/**
 * @Project VIDEO CLIPS AJAX 4.x
 * @Author PHAN TAN DUNG (phantandung92@gmail.com)
 * @Copyright (C) 2014 PHAN TAN DUNG. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Dec 01, 2014, 04:33:14 AM
 */

function nv_topic_del(a) {
    if( ! confirm(nv_is_del_confirm[0]) ){
    	return false;
    }
    
	$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=topic&nocache=' + new Date().getTime(), 'del=1&tid=' + a, function(res) {
		"OK" == res ? window.location.href = window.location.href : alert(nv_is_del_confirm[2]);
	});
	
	return false;
}

function nv_chang_weight(a) {
    nv_settimeout_disable("weight" + a, 5E3);
    var b = document.getElementById("weight" + a).options[document.getElementById("weight" + a).selectedIndex].value;
    
	$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=topic&nocache=' + new Date().getTime(), 'changeweight=1&tid=' + a + '&new=' + b, function(res) {
	    "OK" != res && alert(nv_is_change_act_confirm[2]);
	    clearTimeout(nv_timer);
	    window.location.href = window.location.href
	});
	
	return false;
}

function nv_chang_status(a) {
    nv_settimeout_disable("change_status" + a, 5E3);
    
	$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=topic&nocache=' + new Date().getTime(), 'changestatus=1&tid=' + a, function(res) {
	    "OK" != res && (alert(nv_is_change_act_confirm[2]), window.location.href = window.location.href);
	});
}