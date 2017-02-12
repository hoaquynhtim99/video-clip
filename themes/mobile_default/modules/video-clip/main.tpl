<!-- BEGIN: main -->
<!-- BEGIN: clipForbidden -->
<div class="alert alert-danger">
    {LANG.accessForbidden}.
</div>
<!-- END: clipForbidden -->
<!-- BEGIN: clipDetail -->
<h1 class="mvc-title">{DETAILCONTENT.title}</h1>
<div class="message" id="mesHide"></div>
<div class="mb10">
    <div id="clip-content-wrap" style="padding-bottom:{MODULECONFIG.aspectratioPadding}%">
        <div id="clip-content">&nbsp;</div>
    </div>
</div>
<div class="clearfix"></div>
<script type="text/javascript">
$(document).ready(function(){
    jwplayer("clip-content").setup({
        file: "{DETAILCONTENT.filepath}",
        <!-- BEGIN: showcover -->image: "{COVER_IMAGE}",<!-- END: showcover -->
        width: '100%',
        aspectratio: "{MODULECONFIG.aspectratio}",
        autostart: {MODULECONFIG.playerAutostart},
        <!-- BEGIN: playerSkin -->skin: {name: "{MODULECONFIG.playerSkin}"},<!-- END: playerSkin -->
    });
});
</script>
<div>
    <a href="likehit" class="btn btn-info mb10 likeButton">{LANG.like}: <strong id="ilikehit">{DETAILCONTENT.likehit}</strong></a>
    <a href="unlikehit" class="btn btn-info mb10 likeButton">{LANG.unlike}: <strong id="iunlikehit">{DETAILCONTENT.unlikehit}</strong></a>
    <a href="broken" class="btn btn-info mb10 likeButton">{LANG.broken}</a>
    <!-- BEGIN: isAdmin -->
    <a class="btn btn-info mb10" href="{DETAILCONTENT.editUrl}">{LANG.edit}</a>
    <!-- END: isAdmin -->
    <div class="fr">
        {LANG.viewHits}: <span>{DETAILCONTENT.view}</span>
    </div>
</div>
<div class="hometext">
    {DETAILCONTENT.hometext}
    <!-- BEGIN: bodytext -->
    <div class="bodytext">
        <div class="cbox">
            {DETAILCONTENT.bodytext}
        </div>
    </div>
    <div class="ac mb10 text-center">
        <a href="open" class="btn btn-info bodybutton">{LANG.moreContent}</a>
    </div>
    <!-- END: bodytext -->
</div>
<script type="text/javascript">
$("a.likeButton").click(function(e){
    e.preventDefault();
    var action = $(this).attr("href");
    $.ajax({
        type: "POST",
        url: '{DETAILCONTENT.url}',
        data: "aj=" + action,
        success: function(a){
            if("access forbidden" == a){
                alert("{LANG.accessForbidden}");
            }
            var a = a.split("_"), b = "like" == a[0] || "unlike" == a[0] ? "{LANG.thank}" : "{LANG.thankBroken}";
            $("#i" + a[0]).text(a[1]);
            alert(b);
        }
    });
});
$("a.bodybutton").click(function(e){
    e.preventDefault();
    if( "open" == $(this).attr("href") ){
        $(".bodytext").slideDown("slow");
        $(this).attr("href", "close").text("{LANG.collapseContent}");
        $("html,body").animate({
            scrollTop: $(".hometext").offset().top
        }, 500);        
    }else{
        $(".bodytext").slideUp("slow");
        $(this).attr("href", "open").text("{LANG.moreContent}");
        $("html,body").animate({
            scrollTop: $(".mvc-title").offset().top
        }, 500);
    }
});
</script>
<!-- END: clipDetail -->
<div class="mvc-video-wrap">
    <!-- BEGIN: otherClipsContent -->
    <div class="mvc-video-list">
        <div class="img" style="background:transparent url({OTHERCLIPSCONTENT.img}) no-repeat center center">
            <a href="{OTHERCLIPSCONTENT.href}" title="{OTHERCLIPSCONTENT.title}">
                <img src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/images/pix.gif" alt="{OTHERCLIPSCONTENT.title}" width="120" height="80" />
            </a>
            <div class="play">
                <a href="{OTHERCLIPSCONTENT.href}" title="{OTHERCLIPSCONTENT.title}">
                    <img src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/images/pix.gif" alt="{OTHERCLIPSCONTENT.title}" width="120" height="32" />
                </a>
            </div>
        </div>
        <h3><a href="{OTHERCLIPSCONTENT.href}" title="{OTHERCLIPSCONTENT.title}">{OTHERCLIPSCONTENT.title}</a></h3>
        <div>{LANG.topic}: <a href="{OTHERCLIPSCONTENT.topicLink}" title="{OTHERCLIPSCONTENT.topicTitle}">{OTHERCLIPSCONTENT.topicTitle}</a></div>
        <div class="viewHits"><span title="{LANG.viewHits} {OTHERCLIPSCONTENT.view}"><i class="fa fa-eye"></i> {OTHERCLIPSCONTENT.view}</span></div>
        <div class="clear"></div>
    </div>
    <!-- END: otherClipsContent -->
</div>
<!-- BEGIN: nv_generate_page -->
<div class="clearfix"></div>
<div class="generate_page">
    <div class="ctn3">
        {NV_GENERATE_PAGE}
    </div>
</div>
<!-- END: nv_generate_page -->
<!-- BEGIN: topicList -->
<div class="well">
    <div class="vc-header">{LANG.topic}</div>
    <hr>
    <ul class="list">
        <!-- BEGIN: loop -->
        <li>
            <a class="topicList{OTHERTOPIC.current}" href="{OTHERTOPIC.href}"><i class="fa fa-angle-double-right"></i> {OTHERTOPIC.title}</a>
            <!-- BEGIN: sub -->
            <!-- BEGIN: loop -->
            <li class="sub"><a title="{OTHERTOPICSUB.title}" class="topicList sub{OTHERTOPICSUB.current}" href="{OTHERTOPICSUB.href}">{OTHERTOPICSUB.title}</a></li>
            <!-- END: loop -->
            <!-- END: sub -->
        </li>
        <!-- END: loop -->
    </ul>
</div>
<!-- END: topicList -->
<!-- END: main -->