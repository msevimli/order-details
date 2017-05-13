 jQuery(document).ready(function($){
       $(".header").click(function () {
                $header = $(this);
                $content = $header.next();
                $content.slideToggle(100, function () {
                    $header.text(function () {
                         return $content.is(":visible") ? "Collapse" : "Expand ( "+$header.attr('data')+' )';
                    });
                });
            })
        })