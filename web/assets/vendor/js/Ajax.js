$(document).ready(function () {
    var url = Routing.generate('home', true);
    $("#form").submit(function (e) {
        // e.preventDefault();
        $.ajax({
            type: "POST",
            url: url,
            data: data,
            success: success,
            dataType: dataType
        });
    });
});