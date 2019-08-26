$(document).ready(function() {
    $('select[name="universe"]').click(function() {
        fillSelect("universe", "era")
    });
    $('select[name="era"]').click(function() {
        fillSelect("era", "period")
    });
})
