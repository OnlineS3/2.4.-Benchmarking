
$(document).ready(function(){
    $("#site-header .menu ul li a, .access-tool").click(function() {
        var page_id = ($(this).attr('id')=='tool-btn') ? 'tool' : $(this).attr('id');
        setPage(page_id);
    });

    $('#site-header .menu ul li a, .access-tool').mousedown(function(event) {
        if (event.which == 3) {
            document.cookie = 'menu_ben='+(($(this).attr('id')=='tool-btn') ? 'tool' : $(this).attr('id'));
        }
    });
    
    $('#site-header .menu ul li a#tool, .access-tool').click(function() {
        showTutorial();
    });
    
});

function setPage(page_id) {
    $('a#' + page_id).parents().find('#site-header ul li a').removeClass('active');
    $('a#' + page_id).addClass('active');
    document.cookie = 'menu_ben='+page_id;
    
    if (page_id=='tool') {
        $('.tool-sidebar').removeClass('hide');
        $('.base-sidebar').addClass('hide');
    } else {
        $('.tool-sidebar').addClass('hide');
        $('.base-sidebar').removeClass('hide');
    }
    $('.section-page').addClass('hide');
    $('.section-page#' + page_id).removeClass('hide');
    
    return false;
}
