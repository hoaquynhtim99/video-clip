<!-- BEGIN: main -->
<!-- BEGIN: error -->
<div style="width:98%" class="quote">
    <blockquote class="error"><span>{ERROR}</span></blockquote>
</div>
<div class="clear"></div>
<!-- END: error -->
<form class="form-inline" action="{FORM_ACTION}" method="post">
    <table class="table table-striped table-bordered table-hover">
		<col style="width:200px"/>
        <tbody>
            <tr>
                <td>{LANG.topic_name}</td>
                <td><input value="{DATA.title}" name="title" id="title" class="form-control txt-half ajvd-input" maxlength="100" /></td>
            </tr>
            <tr>
                <td>{LANG.description}</td>
                <td><input type="text" value="{DATA.description}" name="description" class="form-control txt-half ajvd-input" maxlength="255" /></td>
            </tr>
            <tr>
                <td>{LANG.keywords}</td>
                <td><input type="text" value="{DATA.keywords}" name="keywords" class="form-control txt-half ajvd-input" maxlength="255" /></td>
            </tr>
            <tr>
                <td>{LANG.topic_parent}</td>
                <td>
                    <select name="parentid" class="form-control ajvd-input">
                        <!-- BEGIN: parentid -->
                        <option value="{LISTCATS.id}"{LISTCATS.selected}>{LISTCATS.name}</option>
                        <!-- END: parentid -->
                    </select>
                </td>
            </tr>
			<tr style="display:none">
				<td>{LANG.homeImg} (24 x 24px)</td>
				<td>
					<input class="form-control" title="{LANG.homeImg}" type="text" name="img" id="img" value="{DATA.img}" style="width:280px" maxlength="255" />
					<input type="button" value="Browse server" class="selectimg" />
				</td>
			</tr>
            <tr>
                <td colspan="2">
                    <input type="submit" name="submit" value="{LANG.save}" class="ajvd-button"/>
                </td>
            </tr>
        </tbody>
    </table>
</form>
<script type="text/javascript">
//<![CDATA[
$("input.selectimg").click(function(){var a=$(this).prev().attr("id");nv_open_browse(script_name + "?" + nv_lang_variable + '=' + nv_sitelang + '&' + nv_name_variable+"=upload&popup=1&area="+a+"&path={UPLOAD_CURRENT}/icons&type=image&currentpath={UPLOAD_CURRENT}/icons","NVImg","850","420","resizable=no,scrollbars=no,toolbar=no,location=no,status=no");return!1});
//]]>
</script>
<!-- END: main -->