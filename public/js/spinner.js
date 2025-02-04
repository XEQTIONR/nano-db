$(function() {
    $('body').on('click', '.pagination a', function(e) {
        e.preventDefault();

        $('#load a').css('color', '#dfecf6');
        $('#load').append('<img style="position: absolute; left: 0; top: 0; z-index: 100000;" src="/images/spinner.gif" />');

        var url = $(this).attr('href');
        getTyres(url);
        window.history.pushState("", "", url);
    });

    function getTyres(url) {
        $.ajax({
            url : url
        }).done(function (data) {
            $('#tyreCatalog').html(data);
        }).fail(function () {
            alert('Tyres Catalog could not be loaded.');
        });
    }
});
