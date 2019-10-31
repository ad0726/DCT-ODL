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


    $('.title_universe').click(function() {
        var classThis   = $(this).attr('class');
        var tmp         = new RegExp(/btn_([a-z0-9]+)/, "i");
        var id_universe = classThis.match(tmp)[1];
        $('#'+id_universe).toggle();
    })

    /**
     * Toggle content_period
     */
    $('.title_period').click(function(){
        var classThis  = $(this).attr('class');
        var period     = classThis.replace('title_period btn_', '');

        $('div.content_period#'+period).toggle();
        if ($('div.content_period#'+period).css('display') == 'block') {
            getCovers($(this), 'page_1');
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
        getCovers($(this), ShowPage);
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
        getCovers($(this), ShowPage);
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
        getCovers($(this), ShowPage);
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
        var id       = $(this).parents('tr').attr('id');
        var position = $('tr#'+id+' .cel_id span').text();
        var era      = getUrlParameter('era');
        // var sectionODL = $('section.odl').attr('id');
        // var isResultPage;

        // if (sectionODL ===  undefined) {
        //     isResultPage = true;
        //     era          = $('section.results_page').attr('id');
        // } else {
        //     var tmp = new RegExp(/([a-z]+)_page/, "i");
        //         era = sectionODL.match(tmp)[1];
        // }
        var answer = confirm("Voulez-vous vraiment supprimer l'arc "+position+" ?");
        if (answer === true) {
            $.ajax({
                method: "GET",
                url   : '/ajax/delete.php?rm='+id+'&from='+era,
                statusCode: {
                    200: function() {
                        $('tr#'+id).parent('tbody').parent('table').remove();
                        // if (isResultPage !== true)
                        //     location.reload();
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
        var era   = getUrlParameter('era');
        if ((isODL === "odl") && (era !== undefined)) {
            $('div.autocompletion').css('display', 'block');
        } else if ((isODL !== "results_page") && (era === undefined)) {
            era = "all";
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
                if($.inArray(id, td['id']) !== -1) return true;
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
    });

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
    });

    $('#selectCreate').click(function() {
        var ChoiceCreate = $('.optionCreate:selected').attr('value');
        if (ChoiceCreate === "universe") {
            $('#create_universe').css('display', 'block');
            $('#create_era').css('display', 'none');
            $('#create_period').css('display', 'none');
            $('.btn_send').css('display', 'block');
        } else if (ChoiceCreate === "era") {
            $('#create_universe').css('display', 'none');
            $('#create_era').css('display', 'block');
            $('#create_period').css('display', 'none');
            $('.btn_send').css('display', 'block');
        } else if (ChoiceCreate === "period") {
            $('#create_universe').css('display', 'none');
            $('#create_era').css('display', 'none');
            $('#create_period').css('display', 'block');
            $('.btn_send').css('display', 'block');
        } else if (ChoiceCreate === "") {
            $('#create_universe').css('display', 'none');
            $('#create_era').css('display', 'none');
            $('#create_period').css('display', 'none');
            $('.btn_send').css('display', 'none');
        }
    });

    $('#whichUniverseForEra').click(function() {
        var universeSelected = $('.selectUniverseForEra:selected').attr('value');
        var selectEra;

        if ((universeSelected !== "") && (universeSelected !== undefined)) {
            $.ajax({
                method: "GET",
                url: "/ajax/fetch-section.php?type=universe&id="+universeSelected,
                success: function(data) {
                    selectEra    = '<option value="" selected>Cliquez</option>\n';
                    selectEra   += '<option value="first">En premier</option>\n';
                    $(data).each(function(i) {
                        selectEra += '<option value="'+data[i].id+'">Après '+data[i].name+'</option>\n';
                    })
                    $('#selectEraForEra').toggle();
                    $('#whereEra').html(selectEra);
                },
            })
        }
    });

    $('#whichUniverseForPeriod').click(function() {
        var universeSelected = $('.selectUniverseForPeriod:selected').attr('value');
        var selectEra;

        if ((universeSelected !== "") && (universeSelected !== undefined)) {
            $.ajax({
                method: "GET",
                url: "/ajax/fetch-section.php?type=universe&id="+universeSelected,
                success: function(data) {
                    selectEra  = '<option value="" selected>Cliquez</option>\n';
                    $(data).each(function(i) {
                        selectEra += '<option class="selectEra" value="'+data[i].id+'">'+data[i].name+'</option>\n';
                    })
                    $('#selectEraForPeriod').toggle();
                    $('#whichEra').html(selectEra);
                }
            })
        }
    })

    $('#whichEra').click(function() {
        var eraSelected = $('.selectEra:selected').attr('value');
        var selectPeriod;

        if ((eraSelected !== "") && (eraSelected !== undefined)) {
            $.ajax({
                method: "GET",
                url: "/ajax/fetch-section.php?type=era&id="+eraSelected,
                success: function(data) {
                    selectPeriod = '<option value="first">En premier</option>\n';
                    $(data).each(function(i) {
                        selectPeriod += '<option value="'+data[i].id+'">Après '+data[i].name+'</option>\n';
                    })
                    $('#selectPeriod').toggle();
                    $('#wherePeriod').html(selectPeriod);
                }
            })
        }
    });

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
     * Update-in-line
     */
    $('.update_tr').click(function() {
        var alreadyFormUpdate = $('tr#updateInLine');
        if (alreadyFormUpdate !== undefined) alreadyFormUpdate.remove();
        var id       = $(this).attr('id').replace("line_", "");
        var position = $('tr#'+id+' .cel_id span').text();
        var title    = $('tr#'+id+' .cel_title').text();
        var content  = $('tr#'+id+' .cel_content').text();

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
                position  : position,
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
            var period;

            era    = getUrlParameter('era');
            period = $(this).parents('div.period').attr('id');

            $('#updateInLine div input').each(function() {
                if ($(this).prop('name') == 'title')
                    newTitle = checkUpdateEqual($(this).val(), title);
                if ($(this).prop('name') == 'urban')
                    newUrban = checkUpdateEqual($(this).val(), urban);
                if ($(this).prop('name') == 'dctrad')
                    newDctrad = checkUpdateEqual($(this).val(), dctrad);
                if ($(this).prop('name') == 'new_id')
                    newId = checkUpdateEqual($(this).val(), position);
                if ($(this).prop('name') == 'cover')
                    cover = $(this);
            });
            newContent = checkUpdateEqual($('#updateInLine div textarea').val(), content);

            var isEvent       = "off";
            if ($('#updateInLine div #checkboxIsEvent').prop('checked') === true) {
                isEvent       = "on";
            }

            var requestData = {
                id_arc       : id,
                new_title    : newTitle,
                new_content  : newContent,
                new_urban    : newUrban,
                new_dctrad   : newDctrad,
                id_era       : era,
                id_period    : period,
                isEvent      : isEvent,
                isEventReturn: isEventReturn,
                new_id       : newId,
                noCover      : true
            };

            $.ajax({
                method: "POST",
                url   : "/ajax/update-arc.php",
                data  : requestData,
                success: function(data) {
                    $.ajax({
                        method: "POST",
                        url   : "/ajax/refresh-data.php",
                        data  : {
                            id: id,
                        },
                        success: function(data) {
                            var tdName;
                            var tdPubli;

                            if ((data.link_a == false) && (data.link_b == false)) {
                                tdPubli = '<img class="logo_opacity" src="/assets/img/logo_urban_mini.png"><img class="logo_opacity" src="/assets/img/logo_dct_mini.png">';
                            } else if ((data.link_a != false) && (data.link_b == false)) {
                                tdPubli = '<a href="'+data.link_a+'"><img src="/assets/img/logo_urban_mini.png"></a><img class="logo_opacity" src="/assets/img/logo_dct_mini.png">';
                            } else if ((data.link_a == false) && (data.link_b != false)) {
                                tdPubli = '<img class="logo_opacity" src="/assets/img/logo_urban_mini.png"><a href="'+data.link_b+'"><img src="/assets/img/logo_dct_mini.png"></a>';
                            } else if ((data.link_a != false) && (data.link_b != false)) {
                                tdPubli = '<a href="'+data.link_a+'"><img src="/assets/img/logo_urban_mini.png"></a><a href="'+data.link_b+'"><img src="/assets/img/logo_dct_mini.png"></a>';
                            }

                            if (data.is_event == 0) {
                                $('tr#'+id).attr('class', 'line');
                            } else {
                                $('tr#'+id).attr('class', 'line isEvent');
                            }

                            $('tr#'+id).children('td').each(function() {
                                tdName = $(this).attr('class');
                                if (tdName == "cel_title") {
                                    $(this).html('<h3>'+data.title+'</h3>');
                                } else if (tdName == "cel_content") {
                                    $(this).html('<p>'+nl2br(data.content)+'</p>');
                                } else if (tdName == "cel_publi") {
                                    $(this).children('div').html(tdPubli);
                                }
                            })

                            if (newTitle !== undefined)
                                title   = newTitle;
                            if (newContent !== undefined)
                                content = newContent;
                            if (newUrban !== undefined)
                                urban   = newUrban;
                            if (newDctrad !== undefined)
                                dctrad  = newDctrad;

                            newTitle   = undefined;
                            newContent = undefined;
                            newUrban   = undefined;
                            newDctrad  = undefined;

                            if (newId !== undefined)
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
                    url: '/ajax/update-arc.php?id_arc='+id+'&id_era='+era+'&id_period='+period,
                    data: fd,
                    processData: false,
                    contentType: false,
                    type: 'POST',
                    success: function(data) {
                        $.ajax({
                            method: "POST",
                            url   : "/ajax/refresh-data.php",
                            data  : {
                                id: id,
                            },
                            success: function(data) {
                                var tdName;

                                $('tr#'+id).children('td').each(function() {
                                    tdName = $(this).attr('class');
                                    if (tdName == "cel_img") {
                                        $(this).children('img').prop('src', data.cover);
                                    }
                                })
                                resetInput($('#cover'));
                                $('#result-file-selected').text("");
                            }
                        })
                    }
                });
            }
        });
        $("#update_close").click(function() {
            $('tr#updateInLine').remove();
        })
        $('#fake-input').click(function(){
            $('#cover').click();
        });
        fileInput = document.querySelector("#cover");
        fileInput.addEventListener("change", function(event) {
            var text = $(this).val().replace("C:\\fakepath\\", "");
            $('#result-file-selected').text(text);
        });
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

function readURL(input) {
    if (input.files && input.files[0]) {
    var reader = new FileReader();
    reader.onload = function(e) {
        $('td.cel_img img').attr('src', e.target.result);
    }
    reader.readAsDataURL(input.files[0]);
    }
}

function resetInput(e) {
    e.wrap('<form>').closest('form').get(0).reset();
    e.unwrap();
}

function getCovers(object, page) {
    var period     = object.parents('div.period').attr('id');
    var lines      = $('div.period#'+period).find('table.'+page+' tr');
    var ids        = [];

    lines.each(function() {
        ids.push($(this).find('.cel_id span').text());
    });

    $.ajax({
        method: 'POST',
        url: '/ajax/get-covers.php',
        data: {
            period: period,
            ids: ids
        },
        success: function(ret) {
            var i = 0;
            lines.each(function() {
                if (i < 20) {
                    $(this).find('td.cel_img img').prop('src', "/"+ret[i]);
                    i++;
                } else {
                    return false;
                }
            });
        }
    })
}

function fillSelect(name_selected, name_select_to_add) {
    var universe_selected = $('select[name="'+name_selected+'"] > option:selected').attr('value');
    var options;

    if ((universe_selected !== "") && (universe_selected !== undefined)) {
        $.ajax({
            method : "GET",
            async  : false,
            url    : "/ajax/fetch-section.php?type="+name_selected+"&id="+universe_selected,
            success: function(data) {
                options    = '<option value="" selected>'+name_select_to_add+'</option>\n';
                n          = data.length;
                isSelected = '';
                $(data).each(function(i) {
                    if (i == (n-1)) {
                        isSelected = "selected";
                    }
                    options += '<option value="'+data[i].id+'" '+isSelected+'>'+data[i].name+'</option>\n';
                })
                $('select[name="'+name_select_to_add+'"]').html(options);
            },
        })
    }
}

function getUrlParameter(sParam) {
    var sPageURL = window.location.search.substring(1),
        sURLVariables = sPageURL.split('&'),
        sParameterName,
        i;

    for (i = 0; i < sURLVariables.length; i++) {
        sParameterName = sURLVariables[i].split('=');

        if (sParameterName[0] === sParam) {
            return sParameterName[1] === undefined ? true : decodeURIComponent(sParameterName[1]);
        }
    }
}
