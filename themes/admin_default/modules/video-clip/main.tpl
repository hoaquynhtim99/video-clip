<!-- BEGIN: main -->
<div id="ablist" class="form-inline">
	<select name="tList" class="form-control ajvd-input">
		<option value="">{LANG.topicselect}</option>
		<!-- BEGIN: psopt4 --><option value="{OPTION4.id}">{OPTION4.name}</option><!-- END: psopt4 -->
	</select>
	<input style="margin-right:50px" name="ok2" type="button" value="OK" class="btn btn-primary"/>
	<input name="addNew" type="button" value="{LANG.addClip}" class="btn btn-success"/>
</div>
<div class="myh3">
	{PTITLE}
</div>
<div id="pageContent">
	<table class="table table-striped table-bordered table-hover">
		<col style="width:120px" />
		<thead>
			<tr>
				<td>{LANG.adddate}</td>
				<td>{LANG.title}</td>
				<td>{LANG.topic_parent}</td>
				<td style="text-align:right">{LANG.feature}</td>
			</tr>
		</thead>
		<tbody>
		<!-- BEGIN: loop -->
			<tr>
				<td>{DATA.adddate}</td>
				<td>{DATA.title}</td>
				<td><a href="{MODULE_URL}=main&tid={DATA.tid}">{DATA.topicname}</a></td>
				<td style="text-align:right">
					<a href="{DATA.id}" title="{DATA.status}" class="changeStatus"><img style="vertical-align:middle;margin-right:10px" alt="{DATA.status}" title="{DATA.status}" src="{NV_BASE_SITEURL}themes/{NV_ADMIN_THEME}/images/{module}/{DATA.icon}.png" width="12" height="12" /></a>
					<a href="{MODULE_URL}=main&edit&id={DATA.id}">{GLANG.edit}</a>
					|
					<a class="del" href="{DATA.id}">{GLANG.delete}</a>
				</td>
			</tr>
			<!-- END: loop -->
			<tbody>
	</table>
	<div id="nv_generate_page">{NV_GENERATE_PAGE}</div>
</div>
<script type="text/javascript">
//<![CDATA[
$("a.del").click(function(){confirm("{LANG.delConfirm} ?")&&$.ajax({type:"POST",url:"{MODULE_URL}",data:"del="+$(this).attr("href"),success:function(a){"OK"==a?window.location.href=window.location.href:alert(a)}});return!1});$("input[name=addNew]").click(function(){window.location.href="{MODULE_URL}&add";return!1});$("a.changeStatus").click(function(){var a=this;$.ajax({type:"POST",url:"{MODULE_URL}",data:"changeStatus="+$(this).attr("href"),success:function(b){$(a).html(b)}});return!1});
$("input[name=ok2]").click(function(){var a=$("select[name=tList]").val();window.location.href=""!=a?"{MODULE_URL}=main&tid="+a[0]:"{MODULE_URL}=main";return!1});
//]]>
</script>
<!-- END: main -->
<!-- BEGIN: add -->
<h3 class="myh3">{INFO_TITLE}</h3>
<div class="red">{ERROR_INFO}</div>
<form class="form-inline" id="addInformation" method="post" action="{POST.action}">
	<table class="table table-striped table-bordered table-hover">
		<col style="width:220px" />
		<tbody>
			<tr>
				<td>{LANG.title}<span style="color:red">*</span></td>
				<td><input title="{LANG.title}" type="text" name="title" value="{POST.title}" class="form-control txt-half ajvd-input" maxlength="255" /></td>
			</tr>
			<tr>
				<td>{LANG.topic_parent}</td>
				<td>
					<select name="tid" class="form-control ajvd-input">
						<!-- BEGIN: option3 -->
						<option value="{OPTION3.value}"{OPTION3.selected}>
							{OPTION3.name}
						</option>
						<!-- END: option3 -->
					</select>
				</td>
			</tr>
			<tr>
				<td>{LANG.internalpath}</td>
				<td>
					<input title="{LANG.internalpath}" type="text" name="internalpath" id="internalpath" value="{POST.internalpath}" class="form-control txt-half ajvd-input" maxlength="255" />
					<input type="button" value="{LANG.BrowseServer}" class="selectfile btn btn-default" />
				</td>
			</tr>
			<tr>
				<td>{LANG.externalpath}</td>
				<td><input title="{LANG.externalpath}" type="text" name="externalpath" value="{POST.externalpath}" class="form-control txt-half ajvd-input" maxlength="255" /></td>
			</tr>
            <tr style="display:none">
                <td style="vertical-align:top">{LANG.who_view}</td>
                <td>
					<!-- BEGIN: groups_view -->
					<input name="groups_view[]" value="{GROUPS_VIEW.key}" type="checkbox"{GROUPS_VIEW.checked} /> {GROUPS_VIEW.title}<br />
					<!-- END: groups_view -->
                </td>
            </tr>
			<tr style="display:none">
				<td>{LANG.commAllow}</td>
				<td><input name="comm" type="checkbox"{POST.comm} value="1" /></td>
			</tr>
			<tr>
				<td>{LANG.homeImg}</td>
				<td>
					<input title="{LANG.homeImg}" type="text" name="img" id="img" value="{POST.img}" class="form-control txt-half ajvd-input" maxlength="255" />
					<input type="button" value="{LANG.BrowseServer}" class="selectimg btn btn-default" />
				</td>
			</tr>
			<tr>
				<td>
					{LANG.hometext}<span style="color:red">*</span>
				</td>
				<td><textarea title="{LANG.hometext}" name="hometext" class="ajvd-input txt-full" rows="5">{POST.hometext}</textarea></td>
			</tr>
			<tr>
				<td>{LANG.keywords}</td>
				<td><input title="{LANG.keywords}" type="text" name="keywords" value="{POST.keywords}" class="form-control txt-half ajvd-input" maxlength="255" /></td>
			</tr>
		</tbody>
	</table>
	<div class="ajvd-box"><strong>{LANG.bodytext}</strong></div>
	<div>{CONTENT}</div>
	<div class="ajvd-box">
		<input name="redirect" type="hidden" value="{POST.redirect}" />
		<input name="submit" type="submit" value="{LANG.save}" class="ajvd-button"/>
	</div>
</form>
<script type="text/javascript">
//<![CDATA[
$("input.selectfile").click(function(){
    var a = $(this).prev().attr("id");
    nv_open_browse(script_name + "?" + nv_lang_variable + "=" + nv_sitelang + "&" + nv_name_variable + "=upload&popup=1&area=" + a + "&path={UPLOAD_FILE_PATH}&type=all&currentpath={UPLOAD_FILE_PATH}", "NVImg", "850", "420", "resizable=no,scrollbars=no,toolbar=no,location=no,status=no");
    return !1
});
$("input.selectimg").click(function(){
    var a = $(this).prev().attr("id");
    nv_open_browse(script_name + "?" + nv_lang_variable + "=" + nv_sitelang + "&" + nv_name_variable + "=upload&popup=1&area=" + a + "&path={UPLOAD_IMG_PATH}&type=image&currentpath={UPLOAD_IMG_CURRENT}", "NVImg", "850", "420", "resizable=no,scrollbars=no,toolbar=no,location=no,status=no");
    return !1
});
$("form#addInformation").submit(function(){
    var a = trim($("input[name=title]").val());
    $("input[name=title]").val(a);
    if("" == a) return alert("{LANG.error1}"), $("input[name=title]").val("").select(), !1;
    a = trim($("input[name=internalpath]").val());
    $("input[name=internalpath]").val(a);
    b = trim($("input[name=externalpath]").val());
    $("input[name=externalpath]").val(b);
    if("" == a && "" == b) return alert("{LANG.error5}"), $("input[name=internalpath]").select(), !1;
    a = trim($("textarea[name=hometext]").val());
    $("textarea[name=hometext]").val(a);
    if("" == a) return alert("{LANG.error7}"), $("textarea[name=hometext]").val("").select(), !1;
    $("form#addInformation").submit();
    return !1
});
//]]>
</script>
<!-- END: add -->