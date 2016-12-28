<!-- BEGIN: main -->
<div class="detailContent clearfix">
    <div class="videoTitle" id="videoTitle"><h1>{DETAILCONTENT.title}</h1></div>
    <div class="videoplayer" style="padding-bottom:{MODULECONFIG.aspectratioPadding}%">
        <div class="message" id="mesHide"></div>
        <div class="cont"><div id="videoCont"></div></div>
    </div>
    <div class="clearfix"></div>
</div>
<script type="text/javascript">
$(document).ready(function(){    
    jwplayer("videoCont").setup({
        file: "{DETAILCONTENT.filepath}",
        width: "100%",
        aspectratio: "{MODULECONFIG.aspectratio}",
        autostart: {MODULECONFIG.playerAutostart},
    });
    <!-- BEGIN: scrollPlayer -->$("html,body").animate({scrollTop:$(".detailContent").offset().top}, 500)<!-- END: scrollPlayer -->
});
</script>
<div id="otherClipsAj">
    <div class="videoInfo marginbottom15 clearfix">
        <div class="cont">
            <div class="cont2">
                <div class="fl">
                    <div class="shareFeelings">{LANG.shareFeelings}</div>
                    <a class="likeButton" href="{DETAILCONTENT.url}"><img class="likehit" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/images/pix.gif" alt="" width="15" height="14" /><span>{LANG.like}</span></a>
                    <a class="likeButton" href="{DETAILCONTENT.url}"><img class="unlikehit" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/images/pix.gif" alt="" width="15" height="14" /><span>{LANG.unlike}</span></a>
                    <a class="likeButton" href="{DETAILCONTENT.url}"><img class="broken" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/images/pix.gif" alt="" width="15" height="14" /><span>{LANG.broken}</span></a>
                </div>
                <div class="fr">
                    <div class="viewcount">
                        <!-- BEGIN: isAdmin -->
                        <a href="{DETAILCONTENT.editUrl}">{LANG.edit}</a>,&nbsp;
                        <!-- END: isAdmin -->
                        {LANG.viewHits}: <span>{DETAILCONTENT.view}</span><!-- BEGIN: ifComm -->,&nbsp;{LANG.commHits}: <span id="commHits">{DETAILCONTENT.comment}</span><!-- END: ifComm -->
                    </div>
                    <div style="float:right;" id="likeDetailWrap">
                        <div class="image image0"><img id="imglike" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/images/pix.gif" alt="" width="1" /></div>
                        <div class="likeDetail">
                            <div class="likeLeft">{LANG.like}: <span class="strong" id="ilikehit">{DETAILCONTENT.likehit}</span><br /><span id="plike"></span></div>
                            <div class="likeRight">{LANG.unlike}: <span class="strong" id="iunlikehit">{DETAILCONTENT.unlikehit}</span><br /><span id="punlike"></span></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="hometext">
                {DETAILCONTENT.hometext}
            </div>
            <!-- BEGIN: bodytext -->
            <div class="bodytext" style="display:none">{DETAILCONTENT.bodytext}</div>
            <div class="bodybutton"><a href="open" class="bodybutton">{LANG.moreContent}</a></div>
            <!-- END: bodytext -->
        </div>
    </div>
    <script type="text/javascript">
    function addLikeImage() {
        var b = intval($("#ilikehit").text()), c = intval($("#iunlikehit").text()), d = $(".image").width();
        if (0 == b && 0 == c) $(".image").removeClass("imageunlike").addClass("image0"), $("#imglike").removeClass("like").width(1), $("#plike,#punlike").text("");
        else if ($(".image").removeClass("image0").addClass("imageunlike"), 0 == b) $("#imglike").removeClass("like").width(1), $("#plike").text("0%"), $("#punlike").text("100%");
        else {
            var a = intval(100 * b / (b + c)),
                b = intval(a * (d / 100)),
                e = 100 - a;
            $("#imglike").addClass("like").animate({
                    width: b
                },
                1500,
                function() {
                    $("#plike").text(a + "%");
                    $("#punlike").text(e + "%")
                })
        }
    }
    $(function() {
        addLikeImage()
    });
    $("a.likeButton").click(function() {
        var b = $(this).attr("href"), c = $("img", this).attr("class");
        $.ajax({
            type: "POST",
            url: b,
            data: "aj=" + c,
            success: function(a) {
                if ("access forbidden" == a) return alert("{LANG.accessForbidden}"), !1;
                var a = a.split("_"), b = "like" == a[0] || "unlike" == a[0] ? "{LANG.thank}" : "{LANG.thankBroken}";
                $("#i" + a[0]).text(a[1]);
                addLikeImage();
                $("#mesHide").text(b).css({"z-index": 1E4}).show("slow");
                setTimeout(function() {
                    $("div#mesHide").css({
                        "z-index": "-1"
                    }).hide("slow")
                },
                3E3)
            }
        });
        return !1
    });
    $("a.bodybutton").click(function() {
        "open" == $(this).attr("href") ? ($(".bodytext").slideDown("slow"), $(this).attr("href", "close").text("{LANG.collapseContent}"), $("html,body").animate({
            scrollTop: $(".hometext").offset().top
        }, 500)) : ($(".bodytext").slideUp("slow"), $(this).attr("href", "open").text("{LANG.moreContent}"), $("html,body").animate({
            scrollTop: $(".detailContent").offset().top
        }, 500));
        return !1
    });
    </script>
</div>
<!-- END: main -->