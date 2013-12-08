<!-- BEGIN: main -->
<div class="videoMain">
	<div class="col1">
		<!-- BEGIN: topicList -->
		<!-- BEGIN: loop -->
		<a class="topicList{OTHERTOPIC.current}" href="javascript:void(0);" rel="{OTHERTOPIC.href}">{OTHERTOPIC.title}</a>
		<!-- END: loop -->
		<!-- END: topicList -->
	</div>
	<div class="col2">
		<div id="VideoPageData" class="otherClips marginbottom15 clearfix">
		<!-- BEGIN: otherClips -->
		<!-- BEGIN: otherClipsContent -->
		<div class="otherClipsContent">
			<div class="ctn1">
				<div class="ctn2">
					<div style="background:transparent url({OTHERCLIPSCONTENT.img}) no-repeat center center">
						<a class="otcl" href="{OTHERCLIPSCONTENT.href}" title="{OTHERCLIPSCONTENT.title}">
						<img src="{NV_BASE_SITEURL}images/pix.gif" alt="{OTHERCLIPSCONTENT.title}" width="120" height="80" /></a>
					</div>
					<div class="vtitle"><a class="otcl" href="{OTHERCLIPSCONTENT.href}" title="{OTHERCLIPSCONTENT.title}">{OTHERCLIPSCONTENT.sortTitle}</a></div>
					<div class="viewHits">{LANG.viewHits} <span>{OTHERCLIPSCONTENT.view}</span></div>
					<div class="play">
						<a class="otcl" href="{OTHERCLIPSCONTENT.href}" title="{OTHERCLIPSCONTENT.title}">
						<img src="{NV_BASE_SITEURL}images/pix.gif" alt="{OTHERCLIPSCONTENT.title}" width="120" height="32" /></a>
					</div>
				</div>
			</div>
		</div>
		<!-- END: otherClipsContent -->
		<!-- BEGIN: nv_generate_page -->
		<div class="clearfix"></div>
		<div class="generate_page">
			<div class="ctn3">
				{NV_GENERATE_PAGE}
			</div>
		</div>
		<!-- END: nv_generate_page -->
		<!-- END: otherClips -->
		</div>
	</div>
</div>
<script type="text/javascript">
$(function(){
	$('.videoMain .topicList').click(function(){
		$('.videoMain .topicList').removeClass('current');
		$(this).addClass('current');
		$('#VideoPageData').load($(this).attr('rel'));
	});
});
</script>
<!-- END: main -->