@extends('Result.masters.app')
@section('page-title')
@stop
@section('required-styles')
   {!! Html::style('result/css/social-style.css') !!}
   {{-- {!! Html::style('result/css/legacy-grid.css') !!} --}}
   {{-- {!! Html::style('result/css/autocomplete.css') !!} --}}
   {{-- {!! Html::style('result/plugins/dropzone/cropper.css') !!} --}}
   {{-- {!! Html::style('result/plugins/Jcrop/css/jquery.Jcrop.min.css?v='.time()) !!} --}}
   {!! Html::style('result/css/emoji.css') !!}   
<style type="text/css">
   #page-title{
   display:none;
   }
</style>
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
   footer{
   display: none !important;
   }
   textarea{
   line-height: 48px;
   font-size: 12px;
   height: 48px;
   border-radius: 0px !important;
   }
   @media(max-width: 767px){
      .chat-detail{
         display: none;
      }
   .chat_list{
   float: left;
   width: 100%;
   }
   .chat_people{
   float: left;
   width: 100%;
   }
   .back-btn{
   float: right;
   font-size: 30px;
   width: 35px;
   }
   .message-page .show-direct-message .msg_history {
   height: 78vh;
   padding-bottom: 51px;
   padding-left: 9px;
    padding-right: 9px;
   }
   .back-btn a{
   padding: 10px;
   }
   }
   @media (min-width: 768px){
   .back-btn{
   display: none;
   }
   }
   .chat_list .close{
   position: absolute;
    top: 14px;
    right: 15px;
}
.message-page .show-direct-message .chat_ib{
   margin-top: 11px;
}
</style>
@stop
@section('content')
<div class="row message-page">
   <div class="col-xs-12 col-md-4 col-sm-4 border-rightt chat-list">
      <div class="message-heading ">
         <div class="d-flex">
            <h4>Chats </h4>
            <!-- friend list  head-->   
            <div class="headind_srch" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="New Message">
               <div class="recent_heading">
                  <span id="hide-message">
                  <button class="btn btn-success btn-sm" id="show-search-popup" type="button" data-toggle="modal" data-target="#friend-search-popup">
                  <i class="fa fa-edit"></i> 
                  </button>
                  </span>
                  <span class="input-group-addon1" id="show-friend-chat-list">
                  </span> 
               </div>
            </div>
            <!-- friend list -->  
         </div>
         <div>
            <input type="text" class="form-control chat-search" placeholder="search Messenger">
         </div>
      </div>
      <div class="messaging">
         <div class="inbox_msg">
            <div class="inbox_people">
               <div class="inbox_chat" id="chat_list">
                  @include('Result.social-network.message.chat-message')
                  <!-- <div class="chat_list active_chat"> -->
                  {{-- 
                  <a href="javascript:;">
                     <div class="chat_list">
                        <div class="chat_people">
                           <div class="chat_img">
                              <img data-author="906" src="{{asset('assets/images/no-image-icon.jpg')}}"  alt="user profile image">
                           </div>
                           <div class="chat_ib">
                              <h5>Wendy White
                              </h5>
                              <span class="text-msg">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
                              </span>  <span class="chat_date">2 days ago </span>
                           </div>
                        </div>
                     </div>
                  </a>
                  --}}
               </div>
               <!-- friend list -->
            </div>
         </div>
      </div>
   </div>

{{-- single chat  --}}
   <div class="col-xs-12 col-md-8 col-sm-8 pr-0 pl-0 chat-detail">
      <div class="show-direct-message">

         {{-- <div class="">
            <div class="chat_list">
               <div class="chat_people">
                  <div class="chat_img">
                     <img src="{{asset('assets/images/no-image-icon.jpg')}}"alt="user profile image"> 
                  </div>
                  <div class="chat_ib">
                     <h5> 
                        Wendy White
                     </h5>
                     <span>Active 33 min ago.
                     </span>
                  </div>
               </div>
               <div class="back-btn"><a href="#"><i class="fa fa-caret-left"></i></a></div>
            </div>
         </div>
         <div class="messaging">
            <div class="inbox_msg">
               <div class="inbox_people">
                  <div class="msg_history">
                     <div class="outgoing_msg" >
                        <div class="d-flex sent_msg">
                           <div class="text-align-right pr-0 dropdown delete-dropdown">
                              <a href="javascript:;" class="dropdown-toggle for-delete" data-toggle="dropdown">
                                 <i class="fa fa-circle"></i><br> <i class="fa fa-circle"></i><br> <i class="fa fa-circle"></i>
                                 <ul class="dropdown-menu">
                                    <i class="fa fa-caret-up" aria-hidden="true"></i>
                                    
                            
                              <li><a href="javascript:;">Delete</a></li>        
                              </ul>
                              </a>
                           </div>
                           <div class="">
                              <p>
                                 Hi Wendy White, Test Van Rensburg created a habit  saa with a frequency of every day.
                              </p>
                              <span class="time_date">   1 week ago</span> 
                           </div>
                        </div>
                     </div>
                     <div class="incoming_msg d-flex" id="chat-message-8586">
                        <div class="incoming_msg_img">
                           <img src="https://result.testingserver.in/uploads/thumb_c93981d12bd9f2e32acf00380223bf7a.jpg"  alt="user profile image">
                        </div>
                        <div class="received_msg">
                           <div class="d-flex">
                              <div class="received_withd_msg">
                                 <p>
                                    Hi Test Van Rensburg, Test Three created a habit  h1 day 1 of every month.
                                 </p>
                                 <span class="time_date">  54 minutes ago</span>
                              </div>
                              <div class="text-align-right pr-0 dropdown delete-dropdown">
                                 <a href="javascript:;" class="dropdown-toggle for-delete" data-toggle="dropdown">
                                    <i class="fa fa-circle"></i><br> <i class="fa fa-circle"></i><br> <i class="fa fa-circle"></i>
                                    <ul class="dropdown-menu">
                                       <i class="fa fa-caret-up" aria-hidden="true"></i>
    
                                 <li><a href="javascript:;">Delete</a></li>        
                                 </ul>
                                 </a>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="type_msg">
                     <div class="input_msg_write chat-emoji-div">
                        <input type="hidden" id="receiver-id" name="user_id" value="{{ $friend->id }}">
                        <textarea type="textbox" name="chat_emoji" class="write_msg chat-emoji" id="send-message" placeholder="Type a message" onkeyup="sendMessageEnter(event)" data-emoji-input="unicode" data-emojiable="true"></textarea>
                        <button class="msg_send_btn" type="button" onclick="sendMessage()"><i class="fa fa-paper-plane-o" aria-hidden="true"></i></button>
                        <div>
                           <form method="POST" enctype="multipart/form-data" id="upload_image_form" action="javascript:void(0)" >
                              <div class="row">
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
                                 </div>
                                 <input type="hidden" id="receiver-id-form" name="user_id_form" value="{{ $friend->id }}">
                                 <input type="hidden" id="chat-file-type" name="file_type" value="">
                              </div>
                           </form>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div> --}}

      </div>
   </div>
{{--end  single chat  --}}
</div>

<!--show search popup-->
<div class="modal fade" id="friend-search-popup" role="dialog">
   <div class="modal-dialog modal-md">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">New Message</h4>
         </div>
         <div class="modal-body">
            <div class="srch_bar">
               <div class="stylish-input-group">  
                  <input id="friend-search-box" type="text" class="search-bar"  placeholder="Search for names" >
                  <span class="input-group-addon1">
                  <button type="button" onclick="friendSearch()"> <i class="fa fa-search" aria-hidden="true"></i> </button>
                  </span> 
               </div>
            </div>
            <div id="input-search-frined-div">
            </div>
         </div>
      </div>
   </div>
</div>
<!--show search popup -->
@endsection
@section('custom-script')

   {!! Html::style('result/css/jquery.fancybox.min.css') !!}
   {!! Html::script('result/js/jquery.fancybox.min.js') !!}
   {!! Html::script('result/js/social-network/dm.js') !!}

{!! Html::script('result/js/social-network/emoji.js?v='.time()) !!}
<script type="text/javascript">
   $(document).ready(function () {
      fetchPeopleList();
   
     
   $(document).on("keyup",".chat-search",function(event){
      var search = $(this).val();
      $.post(public_url+'social/direct-message/chat-list', {'search':search}, function(response){
         $('#chat_list').html(response.html);
      });
   })

   });
   if($(window).width() < 768){
      $(document).on('click','#close-direct-message-div',function(){
            $('.chat-detail').hide() ;
            $('.chat-list').show() ;
      });
}
 
let chat_text_msg = '';
   function fetchPeopleList(){
    $.ajax({
        url: BASE_URL + '/social/direct-message/chat-list',
        type: "POST",
        headers: {'X-CSRF-TOKEN': CSRF},
        success: function (response) {
            if (response.status == 'success') {
                $('#chat_list').html(response.html);
                $('.show-direct-message').html(response.single_chat_html);
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
                                 chat_text_msg = message;
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
            }
        },
        error: function () {
        }
     });
   }

      /*  */
   function showSingleChat(id){
             if($(window).width() < 768){
                  $('.chat-detail').show() ;
                  $('.chat-list').hide() ;
       }
         $.ajax({
            url: BASE_URL + '/social/direct-message/single_chat/'+ id,
            type: "POST",
            // data: {'id':id},
            headers: {'X-CSRF-TOKEN': CSRF},
            success: function (response) {
                  if (response.status == 'success') {
                     // $(".direct-message").show();
                     $('.show-direct-message').html(response.html);
                     // $('#friend-search-popup').modal('hide');
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
                                 chat_text_msg = message;
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


   /*  */
   
</script>

@stop