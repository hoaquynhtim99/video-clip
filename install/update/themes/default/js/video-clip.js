/**
 * @Project VIDEO CLIPS AJAX 4.x
 * @Author PHAN TAN DUNG (phantandung92@gmail.com)
 * @Copyright (C) 2014 PHAN TAN DUNG. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Dec 01, 2014, 04:33:14 AM
 */

function clipUrldecodeAjax(my_url, containerid) {
    my_url = rawurldecode(my_url);
    $("#" + containerid).load(my_url, function(){
        responsiveVideoGird()
    });
    return;
}

function responsiveVideoGird() {
    if ($('.videoMain').length) {
        var main = $('.videoMain'),
            mainW = main.innerWidth()
            
        if (mainW < 500) {
            $('.videoMain').addClass('mobile')
        } else {
            $('.videoMain').removeClass('mobile')
        }
    }
    
    if ($('#VideoPageData').length) {
        $('#VideoPageData').find('.clipBreakLine').remove()
        var gird = $('#VideoPageData'),
            girdW = gird.innerWidth(),
            i = 1,
            numOnLine = 0
        if (girdW < 310) {
            gird.removeClass('cThree')
            gird.removeClass('cTwo')
            gird.addClass('cOne')
        } else if (girdW < 470) {
            gird.removeClass('cOne')
            gird.removeClass('cThree')
            gird.addClass('cTwo')
            numOnLine = 2
        } else if (girdW < 620) {
            gird.removeClass('cOne')
            gird.removeClass('cTwo')
            gird.addClass('cThree')
            numOnLine = 3
        } else {
            gird.removeClass('cOne')
            gird.removeClass('cTwo')
            gird.removeClass('cThree')
            numOnLine = 4
        }
        if (numOnLine) {
            $('.otherClipsContent', gird).each(function(){
                if(!(i++ % numOnLine)) {
                    $('<div class="clipBreakLine"></div>').insertAfter($(this))
                }
            })
        }
    }
}

var VideoGirdTimer

$(function(){
    $(window).resize(function(){
        clearTimeout(VideoGirdTimer)
        VideoGirdTimer = setTimeout(function(){
            responsiveVideoGird()
        }, 100)
    })
    //
    $('#toggleVideoNav').click(function(e){
        e.preventDefault()
        if ($('.videoMain .col1').length) {
            $('.videoMain .col1').toggleClass('open')
        }
    })
})

$(window).on('load', function(){
    VideoGirdTimer = setTimeout(function(){
        responsiveVideoGird()
    }, 100)
})