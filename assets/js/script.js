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
            $.ajax({
                method: "GET",
                url   : '/ajax/delete.php?rm='+id+'&from='+era,
                success: function(code) {
                    if (code == "200") {
                        $('tr#'+id).parent('tbody').parent('table').remove();
                        location.reload();
                    }
                }
            })
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
                ret += '<a href="/results.php?era='+era+'&search='+ARtd['id'][countARtd-i]+'"><div>'+ARtd['text'][countARtd-i]+'</div></a>\n';
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

    $('#whichEra').click(function() {
        var eraSelected = $('.selectEra:selected').attr('value');
        var selectPeriod;

        if (eraSelected !== "") {
            $.ajax({
                method: "GET",
                url: "/ajax/fetch-periods.php?formfilled=42&era="+eraSelected,
                success: function(data) {
                    selectPeriod = '<option value="">En premier</option>\n'
                    $(data).each(function(i) {
                        selectPeriod += '<option value="after_'+data[i].clean_name+'">Après '+data[i].name+'</option>\n';
                    })
                    $('#selectPeriod').toggle();
                    $('#wherePeriod').html(selectPeriod);
                }
            })
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
            $('td.cel_content p').html(nl2br($(this).val()));
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

    $('.update_tr').click(function() {
        var alreadyFormUpdate = $('tr#updateInLine');
        if (alreadyFormUpdate !== undefined) alreadyFormUpdate.remove();
        var id      = $(this).attr('id').replace("line_", "");
        var title   = $('tr#'+id+' .cel_title').text();
        var content = $('tr#'+id+' .cel_content').text();

        var dctrad = $('tr#'+id+' .cel_publi div').children('a.urlDctrad').prop('href');
        if (dctrad === undefined) dctrad = "";
        var urban = $('tr#'+id+' .cel_publi div').children('a.urlUrban').prop('href');
        if (urban === undefined) urban = "";

        var isEventReturn = "";
        if ($('tr#'+id).attr('class').replace("line ", "") == "isEvent")
            isEventReturn = "checked";

        var formUpdate = $.ajax({
            type: "GET",
            url: "/ajax/update-in-line.php",
            data: {
                formfilled: 42,
                id        : id,
                title     : title,
                content   : content,
                dctrad    : dctrad,
                urban     : urban,
                isEvent   : isEventReturn
            },
            async: false
        }).responseText;
        $('tr#'+id).after(formUpdate);

        $('tr#updateInLine').show("slow");

        $('#updateInLine .btn_send').click(function() {
            var newTitle;
            var newContent;
            var newUrban;
            var newDctrad;
            var newId;
            var cover;
            var era;
            var page;
            var sectionODL;
            var tmp;

            tmp        = new RegExp(/([a-z]+)_page/, "i");
            sectionODL = $('section.odl').attr('id');
            era        = sectionODL.match(tmp)[1];
            tmp        = new RegExp(/page_([1-9]+)/, "i");
            page       = $('#updateInLine').parent('tbody').parent('table').attr('class').match(tmp)[1];

            $('#updateInLine div input').each(function() {
                if ($(this).prop('name') == 'title')
                    newTitle = checkUpdateEqual($(this).val(), title);
                if ($(this).prop('name') == 'urban')
                    newUrban = checkUpdateEqual($(this).val(), urban);
                if ($(this).prop('name') == 'dctrad')
                    newDctrad = checkUpdateEqual($(this).val(), dctrad);
                if ($(this).prop('name') == 'new_id')
                    newId = checkUpdateEqual($(this).val(), id);
                if ($(this).prop('name') == 'cover')
                    cover = $(this);
            });
            newContent = checkUpdateEqual($('#updateInLine div textarea').val(), content);

            var isEvent       = "off";
            if ($('#updateInLine div #checkboxIsEvent').prop('checked') === true) {
                isEvent       = "on";
            }

            var requestData = {
                formfilled   : 42,
                id           : id,
                new_title    : newTitle,
                new_content  : newContent,
                new_urban    : newUrban,
                new_dctrad   : newDctrad,
                name_era     : era,
                isEvent      : isEvent,
                isEventReturn: isEventReturn,
                new_id       : newId,
                noCover      : true
            };

            $.ajax({
                method: "POST",
                url   : "/admin/modify.php",
                data  : requestData,
                success: function(data) {
                    $.ajax({
                        method: "POST",
                        url   : "/ajax/refresh-data.php",
                        data  : {
                            formfilled: 42,
                            id        : id,
                            name_era  : era,
                            page      : page
                        },
                        success: function(data) {
                            var tdName;
                            var tdPubli;

                            if ((data.urban == false) && (data.dctrad == false)) {
                                tdPubli = '<img class="logo_opacity" src="/assets/img/logo_urban_mini.png"><img class="logo_opacity" src="/assets/img/logo_dct_mini.png">';
                            } else if ((data.urban != false) && (data.dctrad == false)) {
                                tdPubli = '<a href="'+data.urban+'"><img src="/assets/img/logo_urban_mini.png"></a><img class="logo_opacity" src="/assets/img/logo_dct_mini.png">';
                            } else if ((data.urban == false) && (data.dctrad != false)) {
                                tdPubli = '<img class="logo_opacity" src="/assets/img/logo_urban_mini.png"><a href="'+data.dctrad+'"><img src="/assets/img/logo_dct_mini.png"></a>';
                            } else if ((data.urban != false) && (data.dctrad != false)) {
                                tdPubli = '<a href="'+data.urban+'"><img src="/assets/img/logo_urban_mini.png"></a><a href="'+data.dctrad+'"><img src="/assets/img/logo_dct_mini.png"></a>';
                            }

                            if (data.isEvent == 0) {
                                $('tr#'+id).attr('class', 'line');
                            } else {
                                $('tr#'+id).attr('class', 'line isEvent');
                            }

                            $('tr#'+id).children('td').each(function() {
                                tdName = $(this).attr('class');
                                if (tdName == "cel_title") {
                                    $(this).html('<h3>'+data.arc+'</h3>');
                                } else if (tdName == "cel_content") {
                                    $(this).html('<p>'+nl2br(data.contenu)+'</p>');
                                } else if (tdName == "cel_publi") {
                                    $(this).children('div').html(tdPubli);
                                }
                            })

                            if (checkUpdateEqual(newId, id) !== undefined)
                                location.reload();
                        }
                    })
                }
            });

            cover = $('#updateInLine #cover')[0].files[0];

            if (cover !== undefined) {
                var fd = new FormData();
                fd.append('cover', cover);

                $.ajax({
                    url: '/admin/modify.php?id='+id+'&formfilled=42&name_era='+era,
                    data: fd,
                    processData: false,
                    contentType: false,
                    type: 'POST',
                    success: function(data) {
                        $.ajax({
                            method: "POST",
                            url   : "/ajax/refresh-data.php",
                            data  : {
                                formfilled: 42,
                                id        : id,
                                name_era  : era,
                                page      : page
                            },
                            success: function(data) {
                                var tdName;

                                $('tr#'+id).children('td').each(function() {
                                    tdName = $(this).attr('class');
                                    if (tdName == "cel_img") {
                                        $(this).children('img').prop('src', data.cover);
                                    }
                                })
                            }
                        })
                    }
                });
            }
        });
        $("#update_close").click(function() {
            $('tr#updateInLine').remove();
        })
    });

});

function nl2br (str) {
    return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1'+ "<br />" +'$2');
}

function checkUpdateEqual(that, ref) {
    if (that == ref)
        return undefined;
    else
        return that;
}
