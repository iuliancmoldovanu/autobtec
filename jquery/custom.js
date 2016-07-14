
$(function() {
    $(".buttom-enquire").fancybox({
        'hideOnOverlayClick' : true,
        'autoScale' : false,
        'scrolling' : 'no',
        'titleShow' 	: false,
        'showCloseButton' : true,
        'showNavArrows' : false,
        'width'	: 795,
        'height' : 320,
        'onComplete' : function() {
            $('#fancybox-frame').load(function() { // wait for frame to load and then gets it's height
                $('#fancybox-content').height($(this).contents().find('body').height()+30);
                $('#fancybox-content').width($(this).contents().find('body').width()+30);
            });
        }
    });
});

