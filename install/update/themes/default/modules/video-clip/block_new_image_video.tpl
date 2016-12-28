<!-- BEGIN: main -->
<!-- BEGIN: first -->
<div class="zing-clip-big">
    <a class="img" href="{ROW.href}">
        <img src="{ROW.img}" alt="{ROW.title}"/>
    </a>
    <p class="icon">&nbsp;</p>
</div>
<h1 class="zing-clip-big-h1"><a href="{ROW.href}">{ROW.title}</a></h1>
<!-- END: first -->
<div class="row">
    <!-- BEGIN: loop -->
    <div class="zing-clip-small col-xs-12">
        <a href="{ROW.href}" class="img">
            <img src="{ROW.thumb}" alt="{ROW.title}"/>
        </a>
        <p class="icon">&nbsp;</p>
        <h1><a href="{ROW.href}">{ROW.title}</a></h1>
    </div>
    <!-- BEGIN: break --><div class="clear"></div><!-- END: break -->
    <!-- END: loop -->
</div>
<!-- END: main -->