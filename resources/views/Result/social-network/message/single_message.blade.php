<style type="text/css">
   .fancybox-infobar{
   display: none;
   }
   .fancybox-button--thumbs{
   display: none;
   }
   .fancybox-container{
   z-index: 999999;
   }
</style>
@if($message->sender_user_id == $user->account_id)  
<div class="outgoing_msg" id="chat-message-{{ $message->id }}">
   <div class="d-flex sent_msg">
      <div class="text-align-right pr-0 dropdown delete-dropdown">
         <a href="javascript:;" class="dropdown-toggle for-delete" data-toggle="dropdown" aria-expanded="false">
         <i class="fa fa-circle"></i><br> <i class="fa fa-circle"></i><br><i class="fa fa-circle"></i>
         </a>
         <ul class="dropdown-menu">
            <i class="fa fa-caret-up" aria-hidden="true"></i>
            <li><a href="javascript:;" onclick="deleteMessage({{ $message->id }})">Delete</a></li>
         </ul>
      </div>
      <div class="">
         @if($message->file != null)
         <div class="d-flex">
            <div>
               <a id="chatFileDownlod" href="{{asset('uploads/'.$message->file)}}" rel="nofollow" download="{{asset('uploads/'.$message->file)}}"><i class="fa fa-download" aria-hidden="true"></i></a>
            </div>
            <div>
               @if($message->file_type == 'other')
               <div style="width: 50px;margin-bottom: 5px;">
                  <img src="{{asset('assets/images/preview-icon.png')}}" >
               </div>
               @elseif($message->file_type == 'image')
               <a data-fancybox="gallery" href="{{asset('uploads/'.$message->file)}}">
               <img src="{{asset('uploads/thumb_'.$message->file)}}" onerror="this.onerror=null;this.src='{{asset('assets/images/no-chat-image.jpg')}}';" alt="image" class="preview-img">
               </a>
               @else
               <video  width="176" height="136" class="preview-video" controls="" playsinline="" src="{{asset('uploads/'.$message->file)}}" >
               @endif
            </div>
         </div>
         @endif
         @if($message->message != "")
         <p>
            {!! $message->message !!}
         </p>
         @endif
         @if($message->message != "")
         <span class="time_date">   {{ $message->created_at->diffForHumans() }}</span> 
         @endif
      </div>
   </div>
</div>
@else
@if($message->message != "") 
<div class="incoming_msg d-flex" id="chat-message-{{ $message->id }}">
   <div class="incoming_msg_img">
      <img src="{{asset('uploads/thumb_'.$friend->profilepic)}}" onerror="this.onerror=null;this.src='{{asset('assets/images/no-image-icon.jpg')}}';" alt="user profile image">
   </div>
   <div class="received_msg">
      <div class="d-flex">
         <div class="received_withd_msg">
            <?php if($message->file != null){ ?>
            <a id="chatFileDownlod" href="{{asset('uploads/'.$message->file)}}" rel="nofollow" download="{{asset('uploads/'.$message->file)}}">                    
            <img src="{{asset('uploads/thumb_'.$message->file)}}" onerror="this.onerror=null;this.src='{{asset('assets/images/no-chat-image.jpg')}}';" alt="image">
            <i class="fa fa-download" aria-hidden="true"></i>
            </a>
            <?php }else{ ?>
            @if($message->message != "")
            <p>
               {!! $message->message !!}
            </p>
            @endif
            <?php } ?>
            @if($message->message != "")
            <span class="time_date">  {{ $message->created_at->diffForHumans() }}</span>
            @endif
         </div>
         @if($friend->about_me != 'epichq')
         <div class="text-align-right pr-0 dropdown delete-dropdown">
            <a href="javascript:;" class="dropdown-toggle for-delete" data-toggle="dropdown" aria-expanded="false">
            <i class="fa fa-circle"></i><br> <i class="fa fa-circle"></i><br><i class="fa fa-circle"></i>
            </a>
            <ul class="dropdown-menu">
               <i class="fa fa-caret-up" aria-hidden="true"></i>
               <li><a href="javascript:;" onclick="deleteMessage({{ $message->id }})">Delete</a></li>
            </ul>
         </div>
         @endif
      </div>
   </div>
</div>
@endif
@endif