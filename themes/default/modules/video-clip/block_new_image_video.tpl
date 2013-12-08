<!-- BEGIN: main -->
<!-- BEGIN: first -->
<div class="zing-clip-big">
	<a class="img" href="{ROW.href}">
		<img src="{ROW.img}" alt="{ROW.title}" width="300"/>
	</a>
	<p class="icon">&nbsp;</p>
</div>
<h1 class="zing-clip-big-h1"><a href="{ROW.href}">{ROW.title}</a></h1>
<!-- END: first -->
<!-- BEGIN: loop -->
<div class="zing-clip-small{ROW.last}">
	<a href="{ROW.href}" class="img">
		<img src="{ROW.img}" alt="{ROW.title}" width="145"/>
	</a>
	<p class="icon">&nbsp;</p>
	<h1><a href="{ROW.href}">{ROW.title}</a></h1>
</div>
<!-- BEGIN: break --><div class="clear"></div><!-- END: break -->
<!-- END: loop -->
<!-- END: main -->