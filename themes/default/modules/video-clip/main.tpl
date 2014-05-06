<!-- BEGIN: main -->
<div class="videoMain">
	<div class="col1">
		<!-- BEGIN: topicList -->
		<!-- BEGIN: loop -->
		<a class="topicList{OTHERTOPIC.current}" href="javascript:void(0);" rel="{OTHERTOPIC.href}">{OTHERTOPIC.title}</a>
		<!-- BEGIN: sub -->
		<div class="col1-sub">
			<!-- BEGIN: loop -->
			<a title="{OTHERTOPICSUB.title}" class="topicList sub{OTHERTOPICSUB.current}" href="javascript:void(0);" rel="{OTHERTOPICSUB.href}">{OTHERTOPICSUB.title}</a>
			<!-- END: loop -->
		</div>
		<!-- END: sub -->
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
		<!-- BEGIN: clear --><div class="video-break"></div><!-- END: clear -->
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
		if( $(this).hasClass('sub') ){
			$('.videoMain .topicList.sub').removeClass('current');
			$(this).addClass('current');
			$(this).parent().show();
			$(this).parent().prev().addClass('current');
		}else{
			if( ! $(this).hasClass('current') ){
				$('.videoMain .topicList:not(.sub)').removeClass('current');
				$(this).addClass('current');
				$('.col1-sub').hide();
				if( $(this).next().attr('class') == 'col1-sub' ){
					$(this).next().show();
				}
			}
		}
		$('#VideoPageData').load($(this).attr('rel'));
	});
});
$(window).load(function(){
	var ele = $('.videoMain .col1').find('.current');
	ele.trigger('click');
});
</script>
<!-- END: main -->