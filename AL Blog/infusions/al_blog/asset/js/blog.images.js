$(document).ready(function(){

    $('img.redactor-inserted-image').css('cursor','pointer').click(function(e){
        e.preventDefault();
        var link = $(this).attr('data-original-src');
        window.open(link,'_blank');
    });

    var shadowContainer = '<div class="post-shadow"></div>';

    $('.post-body').each(function(){

        if ($(this).height() > 100) {
            $(this).find('.post-body-inner').addClass('trunc');
            $(this).find('.post-toggle').css('display','block');
            $(this).find('.post-body-inner').prepend(shadowContainer);
        }

    });

    $('.post-toggle').on('click',function(e){
        e.preventDefault();
        var postInner = $(this).parent().find('.post-body-inner');
        if (postInner.hasClass('trunc')) {
            postInner.removeClass('trunc');
            postInner.find('.post-shadow').remove();
            $(this).text('Свернуть');
        } else {
            postInner.addClass('trunc').prepend(shadowContainer);
            $(this).text('Развернуть');
        }
    });

});