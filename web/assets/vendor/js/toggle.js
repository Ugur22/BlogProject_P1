$(document).ready(function () {
    var url = Routing.generate('test', true);
    $('#toggle').on('click', function (e) {
        e.preventDefault();
        console.log(url);
        $.post( url, function( data ) {
            alert( "Data Loaded: " + data );
        });

    });
});