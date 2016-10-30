var anchor = document.getElementsByClassName('like');
var heart;
var parentAnchor;
var caption;
var countlike;
var like;
var heartvalue;
var a = 0;
$(document).ready(function () {
    addComment();
    for (var i = 0; i < anchor.length; i++) {
        anchor[i].addEventListener("click", addLike);
    }
});


function addLike(e) {
    e.preventDefault();
    heart = this.children[0];
    heartvalue = this.children[0].innerHTML;
    parentAnchor = this.parentNode;
    caption = parentAnchor.closest('.caption');
    like = caption.children[6];
    countlike = caption.children[6].innerHTML;
    var url = this.getAttribute("href");
    $.ajax({
        url: url,
        type: "POST",
        dataType: "json",
        success: function (data) {
            var likeCount = data[0][1];
            if (heartvalue == 'favorite') {
                heartvalue = "favorite_border";
                likeCount--;
            } else {
                heartvalue = "favorite";
                likeCount++;
            }
            like.innerHTML = likeCount;
            heart.innerHTML = heartvalue;
        },
        error: function (request, error) {
            console.log("something went wrong");
        }
    });

}

function addComment() {
    $("form").submit(function (e) {
        e.preventDefault();
        var $form = $(this);
        var url = $form.attr("action");
        var $commentBox = $form.find('.form-control');
        var comment = $commentBox.val();
        $.ajax({
            url: url,
            type: "POST",
            data: {"data": comment},
            dataType: "json",
            success: function (data) {
                console.log(data);
                $commentBox.val('');
                var commentSection = $form.closest('.form-group').prev();
                var col = createDomElement({tagName: 'div', attributes: {class: 'col-sm-5'}});
                var panelDefault = createDomElement({tagName: 'div', attributes: {class: 'panel panel-default'}});
                var panelHeading = createDomElement({tagName: 'div', attributes: {class: 'panel-heading'}});
                var panelBody = createDomElement({tagName: 'div', attributes: {class: 'panel-body'}});
                var span = createDomElement({tagName: 'span', attributes: {class: 'text-muted'}});
                var strong = createDomElement({tagName: 'strong'});
                var p = createDomElement({tagName: 'p'});
                commentSection.append(col);
                col.append(panelDefault);
                panelDefault.append(panelHeading);
                panelDefault.append(panelBody);
                panelHeading.append(strong);
                panelHeading.append(span);
                panelBody.append(p);
                var today = new Date();
                var date = today.getDate() + '-' + (today.getMonth() + 1) + '-' + today.getUTCFullYear().toString().substr(2, 2);
                var time = today.getHours() + ":" + today.getMinutes();
                var CurrentDateTime = date + ' ' + time;
                for (var i = 0; i < data.length; i++) {
                    strong.innerHTML = data[i].username;
                    span.innerHTML = " commented " + CurrentDateTime;
                    p.innerHTML = data[i][0].text;
                }
            },
            error: function () {
                alert("something went wrong");
            }
        });
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