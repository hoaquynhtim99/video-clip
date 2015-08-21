<!-- BEGIN: main -->
<!-- BEGIN: clipForbidden -->
<div class="cbox err">
	{LANG.accessForbidden}.
</div>
<!-- END: clipForbidden -->
<!-- BEGIN: clipDetail -->
<h1 class="mvc-title">{DETAILCONTENT.title}</h1>
<div class="message" id="mesHide"></div>
<div class="mb10">
	<div id="clip-content">&nbsp;</div>
</div>
<div class="clearfix"></div>
<script type="text/javascript">
$(document).ready(function(){
	var playerWidth = $('#clip-content').width();
	var playerHeight = Math.ceil(45 * playerWidth / 80) + 4;
	
    jwplayer("clip-content").setup({
        file: "{DETAILCONTENT.filepath}",
        width: '100%',
        height: playerHeight,
		autostart: {MODULECONFIG.playerAutostart},
    });
});
</script>
<div>
	<a href="likehit" class="mvc-bt mb10 likeButton">{LANG.like}: <strong id="ilikehit">{DETAILCONTENT.likehit}</strong></a>
	<a href="unlikehit" class="mvc-bt mb10 likeButton">{LANG.unlike}: <strong id="iunlikehit">{DETAILCONTENT.unlikehit}</strong></a>
	<a href="broken" class="mvc-bt mb10 likeButton">{LANG.broken}</a>
    <!-- BEGIN: isAdmin -->
    <a class="mvc-bt mb10" href="{DETAILCONTENT.editUrl}">{LANG.edit}</a>
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
    <div class="ac mb10">
		<a href="open" class="mvc-bt bodybutton">{LANG.moreContent}</a>
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
				<img src="{NV_BASE_SITEURL}images/pix.gif" alt="{OTHERCLIPSCONTENT.title}" width="120" height="80" />
			</a>
			<div class="play">
				<a href="{OTHERCLIPSCONTENT.href}" title="{OTHERCLIPSCONTENT.title}">
					<img src="{NV_BASE_SITEURL}images/pix.gif" alt="{OTHERCLIPSCONTENT.title}" width="120" height="32" />
				</a>
			</div>
		</div>
		<h3><a href="{OTHERCLIPSCONTENT.href}" title="{OTHERCLIPSCONTENT.title}">{OTHERCLIPSCONTENT.title}</a></h3>
		<div>{LANG.topic}: <a href="{OTHERCLIPSCONTENT.topicLink}" title="{OTHERCLIPSCONTENT.topicTitle}">{OTHERCLIPSCONTENT.topicTitle}</a></div>
		<div class="viewHits">{LANG.viewHits} <span>{OTHERCLIPSCONTENT.view}</span></div>
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
<ul class="hd">
	<li class="cr">{LANG.topic}</li>
</ul>
<div class="clear"></div>
<div class="cbox">
	<ul class="list">
		<!-- BEGIN: loop -->
		<li>
			<a class="topicList{OTHERTOPIC.current}" href="{OTHERTOPIC.href}">{OTHERTOPIC.title}</a>
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