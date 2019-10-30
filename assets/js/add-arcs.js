$(document).ready(function() {
    fillSelect("universe", "era");
    updatePreview();
    fillSelect("era", "period");
    $('select[name="universe"]').click(function() {
        fillSelect("universe", "era");
        updatePreview();
        fillSelect("era", "period");
    });
    $('select[name="era"]').click(function() {
        fillSelect("era", "period")
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
    $("input.file").change(function() {
        readURL(this);
    });

})

function updatePreview() {
    var universe_selected = $('select[name="universe"] > option:selected').attr('value');

    if ((universe_selected !== "") && (universe_selected !== undefined)) {
        $.ajax({
            method : "GET",
            async  : false,
            url    : "/ajax/fetch-universe.php?id="+universe_selected+"&links=1",
            success: function(data) {
                $('img#logo_a').attr('src', '/assets/img/logos/'+data.logo_a);
                $('img#logo_b').attr('src', '/assets/img/logos/'+data.logo_b);
            },
        })
    }
}
