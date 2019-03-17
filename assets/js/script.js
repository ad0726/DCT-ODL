$(document).ready(function() {
    /**
     * Variables for Toggle content_period and Pagination
     */
    var styles = {
        color : 'red',
        border: 'solid 1px grey',
        borderRadius: '10px'
      };
    var NoStyles = {
        color : 'lightgrey',
        border: 'none'
    };

    /**
     * Toggle content_period
     */
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

    /**
     * Pagination
     */
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

    /**
     * Trash button
     */
    $('.btn_trash').click(function() {
        var id         = $(this).parents('tr').attr('id');
        var sectionODL = $('section.odl').attr('id');
        var era        = "";
        if (sectionODL ===  undefined) {
            era = $('section.results_page').attr('id');
        } else {
            var tmp = new RegExp(/([a-z]+)_page/, "i");
                era = sectionODL.match(tmp)[1];
        }
        var answer = confirm('Voulez-vous vraiment supprimer la ligne '+id+' ?');
        if (answer === true) {
            location.href='delete.php?rm='+id+'&from='+era;
        }
    });

    /**
     * Search bar
     */
    noDisplay = function() {
        $('div.autocompletion').css('display', 'none');
    }

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

    /**
     * Checkbox behaviour
     */
    $('#CBisUrban').click(function() {
        var isChecked = $('#CBisUrban').prop('checked');
        if (isChecked === true) {
            $('#LinkUrban').css('display', 'block');
            $('#LinkUrban').prop('required', true);
        } else {
            $('#LinkUrban').css('display', 'none');
            $('#LinkUrban').attr('value', "");
            $('#LinkUrban').prop('required', false);
        }
    })
    $('#CBisDCT').click(function() {
        var isChecked = $('#CBisDCT').prop('checked');
        if (isChecked === true) {
            $('#LinkDCT').css('display', 'block');
            $('#LinkDCT').prop('required', true);
        } else {
            $('#LinkDCT').css('display', 'none');
            $('#LinkDCT').attr('value', "");
            $('#LinkDCT').prop('required', false);
        }
    })

    $('#selectCreate').click(function() {
        var ChoiceCreate = $('.optionCreate:selected').attr('value');
        if (ChoiceCreate === "era") {
            $('#create_era').css('display', 'block');
            $('#create_period').css('display', 'none');
            $('.btn_send').css('display', 'block');
        } else if (ChoiceCreate === "period") {
            $('#create_era').css('display', 'none');
            $('#create_period').css('display', 'block');
            $('.btn_send').css('display', 'block');
        } else if (ChoiceCreate === "") {
            $('#create_era').css('display', 'none');
            $('#create_period').css('display', 'none');
            $('.btn_send').css('display', 'none');
        }
    })

    /**
     * Back to top button
     */
    $('#btnup').click(function() {
        $('html,body').animate( {
            scrollTop: $('body').offset().top
        }, 'slow');
    });

    /**
     * Button close on bottom of content_period
     */
    $('.btn_hide.down').click(function() {
        var div = $(this).parent('div.content_period').attr('id');
        $('div.content_period#'+div).toggle();
    });

    /**
     * Preview for 'add' form
     */
    var name = "";
    $('input.input').keyup(function() {
        name = $(this).attr('name');
        if (name == "titre_arc") {
            $('td.cel_title span h3').html($(this).val());
        }
    });
    $('input.file').click(function() {
        name = $(this).attr('name');
        if (name == "titre_arc") {
            $('td.cel_title span h3').html($(this).val());
        }
    });
    $('textarea.content').keyup(function() {
        name = $(this).attr('name');
        if (name == "contenu") {
            $('td.cel_content p').html($(this).val());
        }
    });
    $('input#checkboxIsEvent').click(function() {
        var isChecked = $(this).prop('checked');
        if (isChecked === true) {
            $('tr.line').addClass('isEvent');
        } else {
            $('tr.line').removeClass('isEvent');
        }
    });
    $('input#CBisUrban').click(function() {
        var isChecked = $(this).prop('checked');
        if (isChecked === true) {
            $('img#logoUrban').removeClass('logo_opacity');
        } else {
            $('img#logoUrban').addClass('logo_opacity');
        }
    });
    $('input#CBisDCT').click(function() {
        var isChecked = $(this).prop('checked');
        if (isChecked === true) {
            $('img#logoDCT').removeClass('logo_opacity');
        } else {
            $('img#logoDCT').addClass('logo_opacity');
        }
    });
    function readURL(input) {
        if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            $('td.cel_img img').attr('src', e.target.result);
        }
        reader.readAsDataURL(input.files[0]);
        }
    }
    $("input.file").change(function() {
        readURL(this);
    });

    $('.btn_head.update_tr').click(function() {
        var id = $(this).attr('id');
        $('.btn.update_td.'+id).toggle();
    })

    $('.btn.update_td').click(function() {
        var id   = $(this).attr('class').replace("btn update_td line_", "");
        var text = $('tr#'+id+' .cel_title span h3').text();

        $('tr#'+id+' .cel_title span').replaceWith('<input id="updating" type="text" value="'+text+'">');

        $('#updating').keypress(function(e) {
            if(e.which == 13) {
                var isEvent       = "off";
                var isEventReturn = "";
                if ($(this).parent('td').parent('tr#'+id).attr('class').replace("line ", "") == "isEvent") {
                    isEvent       = "on";
                    isEventReturn = "checked";
                }
                console.log(isEvent);
                var newText = $(this).val();
                var data = {
                    formfilled   : 42,
                    id           : id,
                    new_title    : newText,
                    name_era     : "rebirth",
                    isEvent      : isEvent,
                    isEventReturn: isEventReturn
                };
                console.log(data);
                $.ajax({
                    method: "POST",
                    url: "modify.php",
                    data: data
                  })
            }
        });
    });
})