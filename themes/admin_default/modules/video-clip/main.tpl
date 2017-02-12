<!-- BEGIN: main -->
<div class="panel panel-default">
    <div class="panel-heading form-inline">
        <select name="tList" class="form-control ajvd-input">
            <option value="">{LANG.topicselect}</option>
            <!-- BEGIN: psopt4 --><option value="{OPTION4.id}">{OPTION4.name}</option><!-- END: psopt4 -->
        </select>
        <input name="ok2" type="button" value="{LANG.view}" class="btn btn-primary"/>
        <input name="addNew" type="button" value="{LANG.addClip}" class="btn btn-success"/>
    </div>
    <div class="panel-body items">
        <div class="loop-item item-header">
            <div class="item-title">
                {LANG.title}
            </div>
            <div class="item-time">
                {LANG.adddate}
            </div>
            <div class="item-description">
                {LANG.topic_parent}
            </div>
            <div class="item-tools">
                {LANG.feature}
            </div>
            <div class="item-status">
                {LANG.is_active}
            </div>
        </div>
        <!-- BEGIN: loop -->
        <div class="loop-item">
            <div class="item-title item-title-nowrap">
                {DATA.title}
            </div>
            <div class="item-time">
                {DATA.adddate}
            </div>
            <div class="item-description">
                <a href="{MODULE_URL}=main&tid={DATA.tid}">{DATA.topicname}</a>
            </div>
            <div class="item-tools">
                <a href="{MODULE_URL}=main&edit&id={DATA.id}">{GLANG.edit}</a>
                |
                <a class="del" href="{DATA.id}">{GLANG.delete}</a>
            </div>
            <div class="item-status">
                <a href="{DATA.id}" title="{DATA.status}" class="changeStatus"><img style="vertical-align:middle;margin-right:10px" alt="{DATA.status}" title="{DATA.status}" src="{NV_BASE_SITEURL}themes/{NV_ADMIN_THEME}/images/{module}/{DATA.icon}.png" width="12" height="12" /></a>            </div>
            </div>
        <!-- END: loop -->
    </div>
    <!-- BEGIN: generate_page -->
    <div class="panel-footer clearfix">
        <div class="pull-right">
            {GENERATE_PAGE}
        </div>
    </div>
    <!-- END: generate_page -->
</div>

<script type="text/javascript">
//<![CDATA[
$("a.del").click(function(){confirm("{LANG.delConfirm} ?")&&$.ajax({type:"POST",url:"{MODULE_URL}",data:"del="+$(this).attr("href"),success:function(a){"OK"==a?window.location.href=window.location.href:alert(a)}});return!1});
$("input[name=addNew]").click(function(){window.location.href="{MODULE_URL}&add";return!1});
$("a.changeStatus").click(function(){var a=this;$.ajax({type:"POST",url:"{MODULE_URL}",data:"changeStatus="+$(this).attr("href"),success:function(b){$(a).html(b)}});return!1});
$("input[name=ok2]").click(function(){var a=$("select[name=tList]").val();window.location.href=""!=a?"{MODULE_URL}=main&tid="+a[0]:"{MODULE_URL}=main";return!1});
//]]>
</script>
<!-- END: main -->

<!-- BEGIN: add -->
<div class="page-title">
    <h2>{INFO_TITLE}</h2>
</div>
<form action="{POST.action}" method="post" role="form" class="form-horizontal" autocomplete="off" data-toggle="validate" data-type="ajax">
    <div class="form-result"></div>
    <div class="form-element">
        <div class="form-group">
            <label for="title" class="control-label col-sm-8"><i class="fa fa-asterisk"></i> {LANG.title}:</label>
            <div class="col-sm-16 col-lg-6">
                <input class="form-control required" type="text" name="title" id="title" value="{POST.title}" maxlength="255" />
            </div>
        </div>
        <div class="form-group">
            <label for="tid" class="control-label col-sm-8">{LANG.topic_parent}:</label>
            <div class="col-sm-16 col-lg-6">
                <select class="form-control" name="tid" id="tid">
                    <!-- BEGIN: option3 -->
                    <option value="{OPTION3.value}"{OPTION3.selected}>
                        {OPTION3.name}
                    </option>
                    <!-- END: option3 -->
                </select>
            </div>
        </div>
        <div class="form-group">
            <label for="internalpath" class="control-label col-sm-8">{LANG.internalpath}:</label>
            <div class="col-sm-16 col-lg-6">
                <div class="input-group">
                    <input class="form-control" type="text" name="internalpath" id="internalpath" value="{POST.internalpath}" maxlength="255" />
                    <span class="input-group-btn">
                        <button class="btn btn-success selectfile" type="button">{LANG.BrowseServer}</button>
                      </span>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label for="externalpath" class="control-label col-sm-8">{LANG.externalpath}:</label>
            <div class="col-sm-16 col-lg-6">
                <input class="form-control" type="text" name="externalpath" id="externalpath" value="{POST.externalpath}" maxlength="255" />
            </div>
        </div>
        <div class="form-group">
            <label for="img" class="control-label col-sm-8">{LANG.homeImg}:</label>
            <div class="col-sm-16 col-lg-6">
                <div class="input-group">
                    <input class="form-control" type="text" name="img" id="img" value="{POST.img}" maxlength="255" />
                    <span class="input-group-btn">
                        <button class="btn btn-success selectimg" type="button">{LANG.BrowseServer}</button>
                      </span>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label for="hometext" class="control-label col-sm-8"><i class="fa fa-asterisk"></i> {LANG.hometext}:</label>
            <div class="col-sm-16 col-lg-6">
                <textarea class="form-control required" name="hometext" id="hometext" rows="5">{POST.hometext}</textarea>
            </div>
        </div>
        <div class="form-group">
            <label for="keywords" class="control-label col-sm-8">{LANG.keywords}:</label>
            <div class="col-sm-16 col-lg-6">
                <input class="form-control" type="text" name="keywords" id="keywords" value="{POST.keywords}" maxlength="255" />
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-offset-8 col-sm-16 col-lg-6">
                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="showcover" id="showcover" value="1"{POST.showcover} /> {LANG.showcover}
                    </label>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="col-xs-24">
                <div class="ckeditor required">
                    <label class="control-label">{LANG.bodytext} <i class="fa fa-asterisk"></i></label>
                    <div class="clearfix">{CONTENT}</div>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-offset-8 col-sm-16">
                <input type="hidden" name="submit" value="1"/>
                <input name="redirect" type="hidden" value="{POST.redirect}" />
                <input type="submit" value="{LANG.save}" class="btn btn-primary"/>
            </div>
        </div>
    </div>
</form>


<script type="text/javascript">
//<![CDATA[
$(".selectfile").click(function(){
    var a = $(this).parent().prev().attr("id");
    nv_open_browse(script_name + "?" + nv_lang_variable + "=" + nv_lang_data + "&" + nv_name_variable + "=upload&popup=1&area=" + a + "&path={UPLOAD_FILE_PATH}&type=all&currentpath={UPLOAD_FILE_PATH}", "NVImg", "850", "420", "resizable=no,scrollbars=no,toolbar=no,location=no,status=no");
    return !1
});
$(".selectimg").click(function(){
    var a = $(this).parent().prev().attr("id");
    nv_open_browse(script_name + "?" + nv_lang_variable + "=" + nv_lang_data + "&" + nv_name_variable + "=upload&popup=1&area=" + a + "&path={UPLOAD_IMG_PATH}&type=image&currentpath={UPLOAD_IMG_CURRENT}", "NVImg", "850", "420", "resizable=no,scrollbars=no,toolbar=no,location=no,status=no");
    return !1
});
$("form#addInformation").submit(function(){
    $(window).unbind();
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