<!-- BEGIN: main -->
<div class="vd-blbox">
    <div class="vheader">
        <a class="pri" href="{MODULE_LINK}">{MODULE_TITLE}</a>
        <!-- BEGIN: cat -->
        <a class="oth" href="{CAT.link}">{CAT.title}</a>
        <!-- END: cat -->
    </div>
    <div class="vcontent">
        <!-- BEGIN: first -->
        <div class="fvd">
            <div class="img">
                <img src="{ROW.img}" alt="{ROW.title}" width="230"/>
                <a href="{ROW.href}" title="{ROW.title}">&nbsp;</a>
            </div>
            <h3><a href="{ROW.href}" title="{ROW.title}">{ROW.title}</a></h3>
            <p>{ROW.hometext}</p>
            <div class="clear"></div>
        </div>
        <!-- END: first -->
        <div class="lvd">
            <!-- BEGIN: loop -->
            <div class="it">
                <div class="ct">
                    <div class="img">
                        <img src="{ROW.img}" alt="{ROW.title}"/>
                        <a href="{ROW.href}" title="{ROW.title}">&nbsp;</a>
                    </div>
                    <h3><a href="{ROW.href}" title="{ROW.title}">{ROW.title}</a></h3>
                </div>
                <div class="clear"></div>
            </div>
            <!-- END: loop -->
            <div class="clear"></div>
        </div>
    </div>
</div>
<!-- END: main -->