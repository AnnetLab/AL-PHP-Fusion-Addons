$(document).ready(function(){

    $('.calendar-page').hide();
    $('.calendar-page.page1').show();
    $('.calendar-nav-prev').hide();

    $('.calendar-nav').click(function(){
        var currentPage = parseInt($(this).attr('data-current-page'));
        var navType = $(this).attr('data-nav-type');
        var nextPage = navType == 'prev' ? currentPage-1 : currentPage+1;
        if ($('.calendar-page.page'+nextPage).length > 0) {
            $('.calendar-page').hide();
            $('.calendar-page.page'+nextPage).show();
            $('.calendar-nav').attr('data-current-page',nextPage).show();
            //console.log(typeof $('.calendar-page.page'+(nextPage+1)).length);
            if (navType == 'prev') {
                if ($('.calendar-page.page'+(nextPage-1)).length == 0) {
                    $('.calendar-nav-prev').hide();
                }
            } else if (navType == 'next') {
                if ($('.calendar-page.page'+(nextPage+1)).length == 0) {
                    $('.calendar-nav-next').hide();
                }
            }
        }
    });

});