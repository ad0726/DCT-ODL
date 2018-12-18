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
    noDisplay = function() {
        $('div.autocompletion').css('display', 'none');
    }

    $('.title_period').click(function(){
        var period = $(this).attr('class');
        period = period.replace('title_period btn_', '');
        $('div.content_period#'+period).toggle();
        if ($('div.content_period#'+period).css('display') == 'block') {
            $('div.content_period#'+period).children('table').css('display', 'none');
            $('div.content_period#'+period).children('table.page_1').toggle();
            $('div.content_period').css('display', 'none');
            $('div.content_period#'+period).toggle();
        }

        $('div.content_period#'+period).children('div').children('.btn_page').css(NoStyles);
        $('div.content_period#'+period).children('div').children('button.btn_page_1').css(styles);
        $('div.content_period#'+period).children('div').children('.btn_prev').css('display', 'none');
        $('div.content_period#'+period).children('div').children('.btn_next').css('display', 'block');
    });

    $('.btn_next').click(function() {
        var id     = $(this).parent('div').parent('div').attr('id');
        var length = $('div.content_period#'+id).children('div').children('.btn_pagination.top > button').length;
            length = length - 2;
        for(a=1;a<=length;a++) {
            var search = $('div.content_period#'+id).children('div').children('.btn_page_'+a).css('color') == 'rgb(255, 0, 0)';
            if(search === true) {
                var Hp = a;
                var Sp = a+1;
                break;
            }
        }
        var ShowPage = "page_" + Sp;
        var HidePage = "page_" + Hp;
        $('div.content_period#'+id).children('.' + ShowPage).toggle();
        $('div.content_period#'+id).children('.' + HidePage).toggle();
        $('html,body').animate( {
            scrollTop: $('h2.btn_' + id).offset().top
        }, 'slow');
        $('div.content_period#'+id).children('div').children('.btn_prev').css('display', 'block');
        $('div.content_period#'+id).children('div').children('.btn_page').css(NoStyles);
        $('div.content_period#'+id).children('div').children('.btn_page_' + Sp).css(styles);
        if (Sp == length) {
            $('div.content_period#'+id).children('div').children('.btn_next').css("display", "none");
        }
    });

    $('.btn_prev').click(function() {
        var id     = $(this).parent('div').parent('div').attr('id');
        var length = $('div.content_period#'+id).children('div').children('.btn_pagination.top > button').length;
            length = length - 2;
        for(a=1;a<=length;a++) {
            var search = $('div.content_period#'+id).children('div').children('.btn_page_'+a).css('color') == 'rgb(255, 0, 0)';
            if(search === true) {
                var Hp = a;
                var Sp = a-1;
                break;
            }
        }

        var ShowPage = "page_" + Sp;
        var HidePage = "page_" + Hp;
        $('div.content_period#'+id).children('.' + ShowPage).toggle();
        $('div.content_period#'+id).children('.' + HidePage).toggle();
        $('html,body').animate( {
            scrollTop: $('h2.btn_' + id).offset().top
        }, 'slow');
        $('div.content_period#'+id).children('div').children('.btn_next').css('display', 'block');
        $('div.content_period#'+id).children('div').children('.btn_page').css(NoStyles);
        $('div.content_period#'+id).children('div').children('.btn_page_' + Sp).css(styles);
        if (Sp == 1) {
            $('div.content_period#'+id).children('div').children('.btn_prev').css("display", "none");
        }
    });

    $('.btn_page').click(function() {
        // $('.page_1').css('display', 'none');
        var nbrP     = $(this).parent('.btn_pagination.top').children('button').length;
            nbrP     = nbrP - 2;
        var Sp       = $(this).html();
        var ShowPage = 'page_'+Sp;
        var id       = $(this).parent('div').parent('div').attr('id');
        for(a=1;a<=nbrP;a++) {
            var search = $('div.content_period#'+id).children('table.page_'+a).css('display') == 'table';
            if(search === true) {
                var HidePage = 'page_'+a;
                break;
            }
        }
        $('div.content_period#'+id).children('.'+HidePage).toggle();
        $('div.content_period#'+id).children('.'+ShowPage).toggle();
        $('html,body').animate( {
            scrollTop: $('h2.btn_' + id).offset().top
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
        $('div.content_period#'+id).children('div').children('.btn_page').css(NoStyles);
        $('div.content_period#'+id).children('div').children('button.btn_'+ShowPage).css(styles);
        $('div.content_period#'+id).children('div').children('.btn_prev').css('display', 'block');
        $('div.content_period#'+id).children('div').children('.btn_next').css('display', 'block');
        if (ShowPage == 'page_1') {
            $('div.content_period#'+id).children('div').children('.btn_prev').css('display', 'none');
        }
        if (ShowPage == 'page_'+nbrP) {
            $('div.content_period#'+id).children('div').children('.btn_next').css('display', 'none');
        }
    });

    $('.btn_trash').click(function() {
        var id = $(this).parents('tr').attr('id');
        var answer = confirm('Voulez-vous vraiment supprimer la ligne '+id+' ?');
        var tmp = new RegExp(/([a-z]+)_page/, "i");
        var era = $('section.odl').attr('id').match(tmp)[1];
        if (answer === true) {
            location.href='delete.php?rm='+id+'&from='+era;
        }
    });

    $('input.prompt').keyup(function() {
        var isODL = $('section').attr('class');
        var tmp   = "";
        var era   = "";
        if (isODL === "odl") {
            tmp = new RegExp(/([a-z]+)_page/, "i");
            era = $('section.odl').attr('id').match(tmp)[1];
            $('div.autocompletion').css('display', 'block');
        } else {
            era = $('section').attr('id');
            if (era === undefined) {
                era = "all";
            }
        }
        var input = $('input.prompt').val();
        var td    = {
            'id'  : [],
            'text': []
        };
        $('td').each(function(){
                tmp    = new RegExp(input, "ig");
            var search = $(this).text();
            var id     = $(this).parent('tr').attr('id');
            if(search.match(tmp)) {
                td['id'].push(id);
                td['text'].push(search);
            }
        });
        $('input.hide-input.era').attr('value', era);
        var n = td['text'].length;
        autocompletion = function(era, ARtd, countARtd, n=1) {
            var ret = "";
            for (i=1;i<=n;i++) {
                ret += '<a href="results.php?era='+era+'&search='+ARtd['id'][countARtd-i]+'"><div>'+ARtd['text'][countARtd-i]+'</div></a>\n';
            }
            return ret;
        }
        if (n >= 3) {
            $('div.autocompletion').html(autocompletion(era, td, n, 3));
        } else if (n == 2) {
            $('div.autocompletion').html(autocompletion(era, td, n, 2));
        } else if (n == 1) {
            $('div.autocompletion').html(autocompletion(era, td, n));
        }
    });

    $('div.search').focusout(function() {
        $('div.autocompletion').slideUp(300).delay(800).noDisplay();
    });
});