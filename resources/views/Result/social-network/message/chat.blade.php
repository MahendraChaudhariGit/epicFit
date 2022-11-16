
<style>
 .element {
  display: inline-flex;
  align-items: center;
  position: absolute;
  z-index: 99999999;
  top: 5px;
  right: 58px;
  height: 41px;
}
.element  .fa {
  margin: 10px;
  cursor: pointer;
  font-size: 20px;
}
.element  i:hover {
  opacity: 0.6;
}
.element  input {
  display: none;
}
</style> 
 <div class="">
      <div class="chat_list">
         <button type="button" class="close" data-dismiss="modal" id="close-direct-message-div">×</button>  
         <div class="chat_people">
             <input type="hidden" name="chat_friend_id" value="{{ $friend->id }}">
             <input type="hidden" id="chat_friend_about" value="{{ $friend->about_me }}">
            <div class="chat_img">
               <img src="{{asset('uploads/thumb_'.$friend->profilepic)}}" onerror="this.onerror=null;this.src='{{asset('assets/images/no-image-icon.jpg')}}';" alt="user profile image"> 
            </div>
            <div class="chat_ib">
               <h5> 
                  {{$friend->firstname}} {{$friend->lastname}}
               </h5>
            </div>
         </div>
      </div>
   </div>
   <div class="messaging">
      <div class="inbox_msg">
         <div class="inbox_people">
            <div class="msg_history">
          <!-- start  -->
                @if($message_list->count() == 0)
                    <div class="alert alert-info">
                        No messages
                    </div>
                @else
                    @foreach($message_list->get()->reverse() as $message)

                         @include('Result.social-network.message.single_message')
                    @endforeach
                @endif
              <!-- end --> 
            </div>
            <div class="type_msg">
               <div class="input_msg_write chat-emoji-div">
                  <input type="hidden" id="receiver-id" name="user_id" value="{{ $friend->id }}">
                 <textarea type="textbox" name="chat_emoji" class="write_msg chat-emoji" id="send-message" placeholder="Type a message" onkeyup="sendMessageEnter(event)" data-emoji-input="unicode" data-emojiable="true"></textarea>
            <!--       <input type="text" name="chat_emoji" class="write_msg chat-emoji" id="send-message" placeholder="Type a message" onkeyup="sendMessageEnter(event)"> -->
                  {{-- <textarea type="text" name="chat_emoji" class="write_msg chat-emoji" id="send-message" placeholder="Type a message" onkeyup="sendMessageEnter(event)"> </textarea> --}}
                  <button class="msg_send_btn" type="button" onclick="sendMessage()"><i class="fa fa-paper-plane-o" aria-hidden="true"></i></button>

                  <div>
                    <form method="POST" enctype="multipart/form-data" id="upload_image_form" action="javascript:void(0)" >
                  
                        <div class="row">
                            <!-- <div class="col-md-12 mb-2">
                                <img id="image_preview_container" src="{{ asset('public/image/image-preview.png') }}"
                                    alt="preview image" style="max-height: 150px;">
                            </div> -->
                            <div class="img-video-other-file">
                              <div class="d-flex other-file" style="display: none;">
                                <div class="file-upload-design"><img src="{{ asset('assets/images/preview-icon.png') }}"></div>
                                <span id="other-file-name"></span>
                                <a href="#" class="close-other-file"><i class="fa fa-times"></i></a>
                              </div>
                             <div style="position: relative">
                                  <img id="thumbnil" />
                                  <a href="#" class="close-image"><i class="fa fa-times"></i></a>
                             </div>
                              <div style="position: relative">
                              <video  width="45" height="45" id="update-chat-video" controls="" playsinline="" src="" class="hidden"></video>
                               <a href="#" class="close-video"><i class="fa fa-times"></i></a>
                           </div>
                              </div>
                                <div class="element">
                                  <label for="chatFile"> <i class="fa fa-paperclip"></i></label>
                                    <input type="file" name="file" placeholder="Choose image" id="chatFile" onchange="showChatFile(this)">
                                    <span class="text-danger">{{ $errors->first('title') }}</span>
                                </div>
                            
                            <input type="hidden" id="receiver-id-form" name="user_id_form" value="{{ $friend->id }}">
                            <input type="hidden" id="chat-file-type" name="file_type" value="">
                              
                           <!--  <div class="col-md-12">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div> -->
                        </div>     
                    </form>

                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>

<script type="text/javascript">
   $(document).ready(function (e) {
        
        if($('#chat_friend_about').val() == 'epichq'){
            $('.inbox_people > .type_msg').css("display", "none");
        }else{
            $('.inbox_people > .type_msg').css("display", "block");
        }
        
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
 
       /* $('#image').change(function(){
          
            let reader = new FileReader();
            reader.onload = (e) => { 
              $('#image_preview_container').attr('src', e.target.result); 
            }
            reader.readAsDataURL(this.files[0]); 
 
        });*/
 
        $('#upload_image_form').submit(function(e) {
            e.preventDefault();
            console.log('chat_text=====', chat_text);
            var formData = new FormData(this);
            formData.append('message',chat_text);
            var thisForm = this;
            $.ajax({
                type:'POST',
                url: BASE_URL + '/social/direct-message/sendFile',
                timeout: 5000,
                data: formData,
                cache:false,
                contentType: false,
                processData: false,
                success: (data) => {
                    thisForm.reset();
                    $(".msg_send_btn").removeAttr('disabled'); 
                    $('.msg_history .alert').remove();
                    $(".emojionearea-editor").html('');
                    $('.msg_history').append(data.html);
                    $(".msg_history").animate({ scrollTop: $('.msg_history').prop("scrollHeight")}, 1000);
                  //  alert('Image has been uploaded successfully');
                },
                error: function(data){
                    thisForm.reset();
                  //  alert("Upload Error ! Please upload different file or with different size");
                    $(".msg_send_btn").removeAttr('disabled'); 
                }
            });
        });

// $( "#chatFile" ).click(function() {
//   //alert( "Handler for .click() called." );
//    if($("#thumbnil").attr("src") == "")// check image has src
//         {
//         alert('k');
//         $(".emojionearea.write_msg").​​​​​​css({'height':'56px'});
//         }
//         else{
//         $(".emojionearea.write_msg").​​​​​​css({'height':'auto'});
//         }
//         $(".emojionearea.write_msg").​​​​​​css({'height':'auto'});
// });
     
    });

</script>
