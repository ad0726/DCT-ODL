$(document).ready(function(){
    var Sp = 2;
    var Hp = 1;
    $('.btn_next').click(function() {
        var id = $(this).parent('div').parent('div').attr('id');
        var ShowPage = "page_" + Sp;
        var HidePage = "page_" + Hp;
        $('.' + ShowPage).toggle();
        $('.' + HidePage).toggle();
        $('html,body').animate( {
            scrollTop: $("#" + id).offset().top
        }, 'slow');
        $(".btn_prev").html("< Page " + Hp + " | ");
        Sp++;
        Hp++;
        $(".btn_next").html("Page " + Sp + " >");
    });

    $('.btn_prev').click(function() {
        if (Hp > 1) {
            var id = $(this).parent('div').parent('div').attr('id');
            var show = Hp - 1;
            var ShowPage = "page_" + show;
            var HidePage = "page_" + Hp;
            $('.' + ShowPage).toggle();
            $('.' + HidePage).toggle();
            $('html,body').animate( {
                scrollTop: $("#" + id).offset().top
            }, 'slow');
            Sp--;
            Hp--;
            var prev = false;
            if (Hp > 1) {
                prev = "< Page " + (Hp - 1);
            } else {
                prev = "";
            }
            $(".btn_prev").html(prev);
            $(".btn_next").html("Page " + Sp + " >");
        }
    });
    
    $('.btn_page').click(function() {
        $('.btn_prev').html('< Page ' + Hp + ' | ');
    });
});