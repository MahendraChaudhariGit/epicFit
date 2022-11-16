$(".chat-history ul").animate({ scrollTop: $(document).height() }, "fast");
$(document).ready(function () {
    $('#talkSendMessage').on('submit', function(e) {
        e.preventDefault();
        var url, request, tag, data;
        tag = $(this);
        url = public_url + '/ajax/message/send';
        data = tag.serialize();

        request = $.ajax({
            method: "post",
            url: url,
            data: data
        });

        request.done(function (response) {
            if (response.status == 'success') {
                $('#talkMessages').append(response.html);
                tag[0].reset();
                console.log($(document).height())
                $(".chat-history ul").animate({ scrollTop: $(document).height() }, "fast");
            }
        });

    });


    $('body').on('click', '.talkDeleteMessage', function (e) {
        e.preventDefault();
        var tag, url, id, request;

        tag = $(this);
        id = tag.data('message-id');
        url = public_url + '/ajax/message/delete/' + id;

        if(!confirm('Do you want to delete this message?')) {
            return false;
        }

        request = $.ajax({
            method: "post",
            url: url,
            data: {"_method": "DELETE"}
        });

        request.done(function(response) {
           if (response.status == 'success') {
                $('#message-' + id).hide(500, function () {
                    $(this).remove();
                });
           }
        });
    })
});
