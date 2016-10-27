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
            console.log(data);
            $.each(data, function (index, val) {
                $('#user-table').append('<tr>' +
                    '<td>' + val.id + '</td>' +
                    '<td>' + val.firstname + '</td>' +
                    '<td>' + val.surname + '</td>' +
                    '<td>' + val.email + '</td>' +
                    '<td>' + val.roles[0] + '</td>' +
                    '<td>' + val.roles[0] + '</td>' +
                    '<td>' + val.roles[0] + '</td>' +
                    '</tr>');
            });
        },
        error: function (request, error) {
            console.log("something went wrong");
        }
    });
}


function createDomElement(properties) {
    //Create the element
    var domElement = document.createElement(properties.tagName);

    //Loop through the attributes to set them on the element
    var attributes = properties.attributes;
    for (var prop in attributes) {
        domElement.setAttribute(prop, attributes[prop]);
    }

    //If any content, set the inner HTML
    if (properties.content) {
        domElement.innerHTML = properties.content;
    }

    //Return to use in other functions
    return domElement;
}