$(document).ready(function(){
    var styles = {
        color : 'red',
        border: 'solid 1px grey',
        borderRadius: '10px'
      };
    var NoStyles = {
        color : 'lightgrey',
        border: 'none'
    };

    $('.title_period').click(function(){
        var period = $(this).attr('class');
        period = period.replace('title_period btn_', '');
        $('#'+period).toggle();
        if ($('#'+period).css('display') == 'block') {
            $('#'+period).children('table').css('display', 'none');
            $('.page_1').toggle();
            $('.content_period').css('display', 'none');
            $('#'+period).toggle();
        }

        $('.btn_page').css(NoStyles);
        $('button#btn_page_1').css(styles);
        $('.btn_prev').css('display', 'none');
        $('.btn_next').css('display', 'block');
    });

    $('.btn_next').click(function() {
        var length = $('.btn_pagination > button').length;
        length = length - 2;
        for(a=1;a<=length;a++) {
            var search = $('#btn_page_'+a).css('color') == 'rgb(255, 0, 0)';
            if(search === true) {
                var Hp = a;
                var Sp = a+1;
                break;
            }
        }
        var id = $(this).parent('div').parent('div').attr('id');
        var ShowPage = "page_" + Sp;
        var HidePage = "page_" + Hp;
        $('.' + ShowPage).toggle();
        $('.' + HidePage).toggle();
        $('html,body').animate( {
            scrollTop: $("#" + id).offset().top
        }, 'slow');
        $('.btn_prev').css('display', 'block');
        $('.btn_page').css(NoStyles);
        $('#btn_page_' + Sp).css(styles);
        if (Sp == length) {
            $(".btn_next").css("display", "none");
        }
    });

    $('.btn_prev').click(function() {
        var length = $('.btn_pagination > button').length;
        length = length - 2;
        for(a=1;a<=length;a++) {
            var search = $('#btn_page_'+a).css('color') == 'rgb(255, 0, 0)';
            if(search === true) {
                var Hp = a;
                var Sp = a-1;
                break;
            }
        }
            var id = $(this).parent('div').parent('div').attr('id');
            var ShowPage = "page_" + Sp;
            var HidePage = "page_" + Hp;
            $('.' + ShowPage).toggle();
            $('.' + HidePage).toggle();
            $('html,body').animate( {
                scrollTop: $("#" + id).offset().top
            }, 'slow');
            $('.btn_next').css('display', 'block');
            $('.btn_page').css(NoStyles);
            $('#btn_page_' + Sp).css(styles);
            if (Sp == 1) {
                $(".btn_prev").css("display", "none");
            }
    });

    $('.btn_page').click(function() {
        $('.page_1').css('display', 'none');
        var nbrP = $(this).parent().children('button').length;
        nbrP = nbrP - 2;
        var Sp = $(this).html();
        var ShowPage = 'page_'+Sp;
        var id = $(this).parent('div').parent('div').attr('id');
        for(a=1;a<=nbrP;a++) {
            var search = $('.page_'+a).css('display') == 'table';
            if(search === true) { 
                var HidePage = 'page_'+a;
                break;
            }
        }
        $('.'+HidePage).toggle();
        $('.'+ShowPage).toggle();
        $('html,body').animate( {
            scrollTop: $('#' + id).offset().top
        }, 'slow');
        var styles = {
            color : 'red',
            border: 'solid 1px grey',
            borderRadius: '10px'
          };
        var NoStyles = {
            color : 'lightgrey',
            border: 'none'
        };
        $('.btn_page').css(NoStyles);
        $(this).css(styles);
        $('.btn_prev').css('display', 'block');
        $('.btn_next').css('display', 'block');
        if (ShowPage == 'page_1') {
            $('.btn_prev').css('display', 'none');
        }
        if (ShowPage == 'page_'+nbrP) {
            $('.btn_next').css('display', 'none');
        }
    });
});