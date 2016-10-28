$(document).ready(function () {
    var form = document.getElementById('search-form');
    form.addEventListener('keyup', searchUser, true);


});


function searchUser(e) {
    e.preventDefault();
    var url = this.getAttribute("action");
    var $form = $(this);
    var $inputField = $form.find('#user-input');
    var userSearch = $inputField.val();
    $.ajax({
        url: url,
        type: "POST",
        data: {"data": userSearch},
        dataType: "json",
        success: function (data) {
            $('#user-table').html("");
            $.each(data, function (index, val) {
                $('#user-table').append('<tr>' +
                    '<td>' + val.id + '</td>' +
                    '<td>' + val.firstname + '</td>' +
                    '<td>' + val.surname + '</td>' +
                    '<td>' + val.email + '</td>' +
                    '<td>' + val.roles[0] + '</td>' +
                    '<td>' + '<a href="/admin/detailuser/' + val.id + '"><i class="small material-icons">edit</i></a>' + '</td>' +
                    '<td>' + '<a href="/delete/' + val.id + '"><i class="small material-icons">delete</i></a>' + '</td>' +
                    '</tr>');
            });
        },
        error: function (request, error) {
            console.log("something went wrong");
        }
    });
}