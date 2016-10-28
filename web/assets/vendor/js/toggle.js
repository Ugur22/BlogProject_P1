$(document).ready(function () {
    var form = document.getElementsByClassName('pull-right');
    for (var i = 0; i < form.length; i++) {
        form[i].addEventListener('click', blogOn, true);
    }

});

function blogOn(e) {
    e.preventDefault();
    var $form = $(this);
    var url = $form.attr("action");
    var on = this.children[0];
    on.className = "blogOff ";
    console.log(on);
}