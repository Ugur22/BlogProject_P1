window.addEventListener('load', init);
var blogs;
function init() {
    getBlogs();
    blogs = document.getElementById('blogs');
}

function getBlogs() {
    ajaxRequest(showBlogs)
}


function ajaxRequest(ajaxSuccessHandler, data) {
    //Default ajax parameters
    var url = Routing.generate('test', true);
    var parameters = {
        dataType: 'json',
        url: url
    };

    //If data is passed, add it to the AJAX parameters
    if (data) {
        parameters.data = data;
    }

    //Actual AJAX call (only jQuery needed!)
    $.ajax(parameters).done(ajaxSuccessHandler);
}

function showBlogs(data) {
    console.log(data);
}