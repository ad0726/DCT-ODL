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

    $('.btn_trash').click(function() {
        var id = $(this).parents('tr').attr('id');
        var answer = confirm('Voulez-vous vraiment supprimer la ligne '+id+' ?');
        if (answer === true) {
            location.href='delete.php?rm='+id;
        }
    });

    $('input.prompt').keyup(function() {
        var input = $('input.prompt').val();
        var td = [];
        $('td').each(function(){
            var tmp = new RegExp(input, "ig");
            var search = $(this).text();
            if(search.match(tmp)) {
                td.push(search);
            }
        });

        $('div.autocompletion').css('display', 'block');
        var n = td.length;
        // console.log(n);
        // $('div.autocompletion').empty();
        if (n >= 3) {
            $('div.autocompletion').html('<a href="toto.fr"><div>'+td[n-1]+'</div></a>\n<div>'+td[n-2]+'</div>\n<div>'+td[n-3]+'</div>');
        } else if (n == 2) {
            $('div.autocompletion').html('<div>'+td[n-1]+'</div>\n<div>'+td[n-2]+'</div>');
        } else if (n == 1) {
            $('div.autocompletion').html('<div>'+td[n-1]+'</div>');
        }
    });
    plouf = function() {
        $('div.autocompletion').css('display', 'none');
    }
    $('div.search').focusout(function() {
        $('div.autocompletion').focusout(plouf());
    });
});