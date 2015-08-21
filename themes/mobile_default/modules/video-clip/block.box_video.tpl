<!-- BEGIN: main -->
<ul class="hd">
	<li class="cr"><a href="{MODULE_LINK}">{MODULE_TITLE}</a></li>
	<li>&nbsp;</li>
	<!-- BEGIN: cat -->
	<li><a href="{CAT.link}">{CAT.title}</a></li>
	<!-- END: cat -->
</ul>
<div class="clear"></div>
<div class="cbox">
	<!-- BEGIN: first -->
	<a href="{ROW.href}" title="{ROW.title}"><img class="fl" src="{ROW.img}" alt="{ROW.title}" width="100"/></a>
	<h3><a href="{ROW.href}" title="{ROW.title}">{ROW.title}</a></h3>
	<p>{ROW.hometext}</p>
	<div class="clear"></div>
	<div class="hr"></div>
	<!-- END: first -->
	<!-- BEGIN: loop -->
	<a href="{ROW.href}" title="{ROW.title}"><img class="fl" src="{ROW.img}" alt="{ROW.title}" width="100"/></a>
	<h3><a href="{ROW.href}" title="{ROW.title}">{ROW.title}</a></h3>
	<p>{ROW.hometext}</p>
	<div class="clear"></div>
	<div class="hr"></div>
	<!-- END: loop -->
</div>
<!-- END: main -->