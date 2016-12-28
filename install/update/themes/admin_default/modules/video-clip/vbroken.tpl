<!-- BEGIN: main -->
<div class="panel panel-default">
    <div class="panel-heading">
        <h2>{LANG.vbroken}</h2>
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
        <div class="loop-item item-danger">
            <div class="item-title item-title-nowrap">
                {DATA.title}
            </div>
            <div class="item-time">
                {DATA.adddate}
            </div>
            <div class="item-description">
                <a href="{DATA.topicUrl}">{DATA.topicname}</a> 
            </div>
            <div class="item-tools">
                <a href="{MODULE_URL}=main&edit&id={DATA.id}">{GLANG.edit}</a>
                |
                <a class="remove" href="{DATA.id}">{LANG.Remove}</a>
                |
                <a class="del" href="{DATA.id}">{GLANG.delete}</a>
            </div>
            <div class="item-status">
                <a href="{DATA.id}" title="{DATA.status}" class="changeStatus"><img style="vertical-align:middle;margin-right:10px" alt="{DATA.status}" title="{DATA.status}" src="{NV_BASE_SITEURL}themes/{NV_ADMIN_THEME}/images/{MODULE_FILE}/{DATA.icon}.png" width="12" height="12" /></a>
            </div>
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
$("a.del").click(function(){confirm("{LANG.delConfirm} ?")&&$.ajax({type:"POST",url:"{MODULE_URL}=main",data:"del="+$(this).attr("href"),success:function(a){"OK"==a?window.location.href=window.location.href:alert(a)}});return!1});$("a.remove").click(function(){$.ajax({type:"POST",url:"{MODULE_URL}=vbroken",data:"remove="+$(this).attr("href"),success:function(){window.location.href=window.location.href}});return!1});
$("a.changeStatus").click(function(){var a=this;$.ajax({type:"POST",url:"{MODULE_URL}=main",data:"changeStatus="+$(this).attr("href"),success:function(b){$(a).html(b)}});return!1});
//]]>
</script>
<!-- END: main -->