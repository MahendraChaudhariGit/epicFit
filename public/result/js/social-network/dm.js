
$(function() {


    fetchPeopleList();
    // setInterval(function(){
    //      fetchPeopleList();
    //      fetchNewMessages();
    // }, 10000);
    // setInterval(function(){
    //     var id = $('.chat input[name=chat_friend_id]').val();
    //     $('.dm .friends-list .friend').removeClass('active');
    //     $('.dm .friends-list #chat-people-list-'+id).addClass('active');
    // }, 1);
  });

  let chat_text = '';
  /* search friend for message list */
$(document).on("keyup","#friend-search-box",function(event){
     var search = $(this).val();
     searchPeopleList(search);
  })

  function friendSearch(){
     var search = $("#friend-search-box").val();
     searchPeopleList(search);
  }

  function searchPeopleList(search){
    $.post(BASE_URL+'/social/direct-message/search/friend', {'search':search}, function(response){
          $("#input-search-frined-div").html(response.friends);
     }); 
  }
/* search friend for message list */

/* people list */
function fetchPeopleList(){
    $.ajax({
        url: BASE_URL + '/social/direct-message/people-list',
        type: "POST",
        headers: {'X-CSRF-TOKEN': CSRF},
        success: function (response) {
            if (response.status == 'success') {
                $('#people_list').html(response.html);
                $('#contact_list').html(response.html);
            }
        },
        error: function () {
        }
     });
  }

  $(document).on("keyup",".search-from-all-contact",function(event){
    var search = $(this).val();
    $.post(public_url+'social/direct-message/contact', {'search':search}, function(response){
        $('#contact_list').html(response.html);
        // $(".all-my-friends").html(resp);
    });
  })
  /*  */

function showChat(id){
    if($(window).width() < 768){
                  $('.chat-detail').show() ;
                  $('.chat-list').hide() ;
       }
    $.ajax({
        url: BASE_URL + '/social/direct-message/chat',
        type: "POST",
        data: {'id':id},
        headers: {'X-CSRF-TOKEN': CSRF},
        success: function (response) {
            if (response.status == 'success') {
                $(".direct-message").show();
                $('.show-direct-message').html(response.html);
                $('#friend-search-popup').modal('hide');
                $(".msg_history").animate({ scrollTop: $('.msg_history').prop("scrollHeight")}, 1000);

      /* emoji */
         $(".chat-emoji").emojioneArea({
            // pickerPosition: "bottom",
             search: false,
            filtersPosition: "bottom",
            tonesStyle: "radio",
            saveEmojisAs:'image',
            searchPosition: "bottom",
            // shortnames: true,
            // useInternalCDN: true,
            // hidePickerOnBlur: false,
            events: {
                keyup: function (editor, event) {
                    if (this.getText().trim() != '') {
                        if (event.which == 13 && ($.trim(editor.text()).length > 0 || $.trim(editor.html()).length > 0)) {
                            const form = event.currentTarget.closest('.chat-emoji-div');
                            const field = form.querySelector('.chat-emoji[name="chat_emoji"]');
                            var message =  field.emojioneArea.getText().trim();
                            field.emojioneArea.setText('');
                            $(".chat-emoji").data("emojioneArea").hidePicker();
                            chat_text = message;
                            sendMessage(message);
                            event.preventDefault();
                            event.stopPropagation();
                            editor.focus();
                        }
                    } else {
                        if(event.which == 13){
                            event.preventDefault();
                            return false;
                         }
                    }
    
                },
                emojibtn_click: function (button, event) {
                    // $(".chat-emoji")[0].emojioneArea.hidePicker(); 
                  },
                  blur: function (editor, event) {
                    const form = event.currentTarget.closest('.chat-emoji-div');
                    const field = form.querySelector('.chat-emoji[name="chat_emoji"]');
                    var html =  field.emojioneArea.getText().trim();
                    var rx = /<img\s+(?=(?:[^>]*?\s)?class="[^">]*emojione)(?:[^>]*?\s)?alt="([^"]*)"[^>]*>(?:[^<]*<\/img>)?/gi;
                    var text   = html.replace(rx, "$1") ;
                    field.emojioneArea.setText(text);
                },
    
            }
    
        });
      /* emoji */

            }else{
                swal({
                    type: 'warning',
                    title:'Oops...',
                    text: 'Something went wrong!',
                    allowOutsideClick: true,
                }); 
            }
        },
        error: function () {
            swal({
                type: 'warning',
                title:'Oops...',
                text: 'Something went wrong!',
                allowOutsideClick: true,
            }); 
        }
    });
}

function hidePicker(){
    //  var el = $('.chat-emoji').emojioneArea();
	//      el[0].emojioneArea.hidePicker();
    // //  if($('.emojionearea-button').hasClass('active')){ 
    //      $('.emojionearea-button-close').click();
    //      $('.emojionearea-button-close').click();
    // //   }
    
}
// function sendMessage(){
 function sendMessage(message){
          $('#thumbnil').hide();
          $("#update-chat-video").addClass("hidden");
          $(".other-file").css("display", "none");
          if(message == undefined){
            var message = $('#send-message').val();
             chat_text = message;
         }
          $(".close-image").css("display", "none");
          $(".close-video").css("display", "none");

          $(".msg_history").css({"height": "calc(100vh - 32vh)"});
          if($(window).width() < 768){
              $(".message-page .msg_history").css({"height": "78vh"});
                }
                else{
                $(".message-page .msg_history").css({"height": "calc(100vh - 27vh)"});
            }

          $(".type_msg").css({"margin-top": "0px"});
          $(".img-video-other-file").css({"bottom": "0"});

          $(".img-video-other-file").css({"padding": "0"});
        //check for chat file
          if($("#chatFile").val() != ''){
            $("#upload_image_form").submit();
            return false;
        }
        //check for chat file - end
    $(".msg_send_btn").attr('disabled','disabled');

        var id = $('#receiver-id').val();
       
        // var message = $('#send-message').val();
        // $(".emojionearea.form-control").css("display", "none");
        if (message.trim() != '') {
            $.ajax({
                url: BASE_URL + '/social/direct-message/send',
                type: "POST",
                timeout: 5000,
                data:{'id':id, 'message':message},
                headers: {'X-CSRF-TOKEN': CSRF},
                success: function (response) {
                    $(".msg_send_btn").removeAttr('disabled'); 
                    if (response.status == 'success') {
                         $('#send-message').val('');
                         $('.msg_history .alert').remove();
                        //  if($('.chat-emoji-div.emojionearea-button').hasClass('active')){ 
                        //     $('.chat-emoji-div.emojionearea-button-close').click();
                        // }
                        //  $('#send-message').val("");
                         $(".emojionearea-editor").html('');
                         $('.msg_history').append(response.html);
                         $(".msg_history").animate({ scrollTop: $('.msg_history').prop("scrollHeight")}, 1000);
                    } else {
                        swal({
                            type: 'warning',
                            title:'Oops...',
                            text: 'Something went wrong!',
                            allowOutsideClick: true,
                        }); 
                    }
                },
                error: function () {
                    $(".msg_send_btn").removeAttr('disabled'); 
                    swal({
                        type: 'warning',
                        title:'Oops...',
                        text: 'Something went wrong!',
                        allowOutsideClick: true,
                    }); 
                }
            });
        } else {
            $(".msg_send_btn").removeAttr('disabled'); 
            return false;
            // alert('Please write comment'); 
        }
  }


 function sendMessageEnter(e){
    if (e.which == 13) {
        sendMessage();
    }
  }

  $(document).on('click','#close-direct-message-div',function(){
      $(".direct-message").hide();
      $('.show-direct-message').html('');

  });

//   $(".chat-show-full-image").click(function(){
 $(document).on('click','.chat-show-full-image',function(){
    $(this).attr('data-target','#preview-big-image');
    $(this).attr('data-toggle','modal');
    var image = $(this).data('image');
    $("#chat-full-image").attr('src',image)
})


function deleteMessage(id){
     swal({
         type: 'warning',
         title: 'Are you sure to delete message',
         allowOutsideClick: true,
         showCancelButton: true,
         confirmButtonText: 'Yes',
         confirmButtonColor: '#ff4401',
         cancelButtonText: "No"
      },
     function (isConfirm) {
         if (isConfirm) {
             $.post(BASE_URL+'/social/direct-message/delete', {message_id:id}, function (response) {
                 if (response.status = 'success') {    
                     $('#chat-message-'+id).remove();
                 } else {
                         swal({
                             type: 'warning',
                             title:'Oops...',
                             text: 'Something went wrong!',
                             allowOutsideClick: true,
                         }); 
                     }
                 });
             }
         });
     }


    function fetchNewMessages(){
        var id = $('.chat_people input[name=chat_friend_id]').val();
        if (id > 0){
            $.ajax({
                url: BASE_URL + '/social/direct-message/new-messages',
                type: "POST",
                data: {'id':id},
                headers: {'X-CSRF-TOKEN': CSRF},
                success: function (response) {
                    if (response.status == 'success') {
                         if (response.find == 1) {
                            $('msg_history .alert').remove();
                            $('.msg_history').append(response.html);
                            $(".msg_history").animate({ scrollTop: $('.msg_history').prop("scrollHeight")}, 1000);
                        }
                    }
                },
                error: function () {
                    swal({
                        type: 'warning',
                        title:'Oops...',
                        text: 'Something went wrong!',
                        allowOutsideClick: true,
                    }); 
                }
            });
        }  
    }

 function showChatFile(fileInput) {
        // $('#thumbnil').hide();
        var files = fileInput.files;
        for (var i = 0; i < files.length; i++) {           
            var file = files[i];
            var imageType = /image.*/;  
          
            if (!file.type.match(imageType)) {
                showChatVideo(fileInput);
                continue;
            }  
            $('#thumbnil').show(); 
            $('#chat-file-type').val('image'); 
                 
            var img=document.getElementById("thumbnil"); 
             $(".close-image").css("display", "block");
             $(".msg_history").css({"height": "calc(100vh - 38vh)"});
            if($(window).width() < 768){
                $(".message-page .msg_history").css({"height": "calc(100vh - 23vh)"});
             }else{
                $(".message-page .msg_history").css({"height": "calc(100vh - 34vh)"});
            }

             $(".type_msg").css({"margin-top": "40px"});
             $(".img-video-other-file").css({"bottom": "43px"}); 
             $(".img-video-other-file").css({"padding": "5px"});
            img.width = 45;
            img.height = 45;          
            img.file = file;    
            var reader = new FileReader();
            reader.onload = (function(aImg) { 
                return function(e) { 
                    aImg.src = e.target.result; 
                }; 
            })(img);
            reader.readAsDataURL(file);
        }    
    }

function showChatVideo(fileInput) {
    var files = fileInput.files;
    for (var i = 0; i < files.length; i++) {
        var f = files[i];
        // Only process video files.
        if (!f.type.match('video.*')) {
            showChatOtherFile(fileInput);
            continue;
        }
        $('#chat-file-type').val('video'); 

          $(".close-video").css("display", "block");
        // update-chat-video
        $("#update-chat-video").removeClass("hidden");
        $('#update-chat-video').attr('src', URL.createObjectURL(f)); 
    
      }
   }

   function showChatOtherFile(fileInput) {

       $(".other-file").css("display", "block");
       $('#chat-file-type').val('other'); 
       var fileName = fileInput.files[0].name;
       $("#other-file-name").text(fileName);
       
    if ( $(".other-file").css('display') == 'block')
    {
     $(".msg_history").css({"height": "calc(100vh - 40vh)"});
     if($(window).width() < 768){
     $(".message-page .msg_history").css({"height": "calc(100vh - 23vh)"});
    }
    else{
     $(".message-page .msg_history").css({"height": "calc(100vh - 34vh)"});
     
 }
     $(".type_msg").css({"margin-top": "40px"});
     $(".img-video-other-file").css({"bottom": "41px"});
     $(".img-video-other-file").css({"padding": "5px"});
    }
   }

$(document).on('click','.close-image',function(){
      $('#thumbnil').removeAttr("src");
      $('#thumbnil').hide();
      $('.close-image').hide();
      var $el = $('#chatFile');                                        
       $el.wrap('<form>').closest('form').get(0).reset();
       $el.unwrap();
});

/* ------------------------------------------------------------ */
