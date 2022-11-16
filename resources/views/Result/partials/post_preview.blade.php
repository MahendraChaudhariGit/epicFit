{!! Html::style('result/css/jquery.fancybox.min.css') !!}
{!! Html::script('result/js/jquery.fancybox.min.js') !!}

@if($post->client_id != null ||  $post->goal_friend_id == Auth::user()->account_id)
<div class="joms-stream joms-embedly--left joms-js--stream joms-js--stream-112 panel-post-div fb-popup-image-page" data-post-id= "{{$post->id}}" id="panel-post2-{{ $post->id }}">
   <div class="joms-stream__header">
      <div class="joms-avatar--stream ">
         @if($post->client_id != null)
          <a href="@if($post->client_id != Auth::user()->account_id){{ url('social/my/friend/'.$post['client']['id']) }}@else javascript:void(0)@endif">
            <img class="show-full-image async" data-author="906" data-image="@if(!empty($post['client']['profilepic'])){{asset('uploads/thumb_'.$post['client']['profilepic'])}}@else{{asset('assets/images/no-image-icon.jpg')}}@endif" onerror="this.onerror=null;this.src='{{asset('assets/images/no-image-icon.jpg')}}';" alt="user profile image" src="@if(!empty($post['client']['profilepic'])){{asset('uploads/thumb_'.$post['client']['profilepic'])}}@else{{asset('assets/images/no-image-icon.jpg')}}@endif"> 
          </a>
         @else
     
          <a href="@if($post->goal_friend_id != Auth::user()->account_id){{ url('social/my/friend/'.$post['goal_client']['id']) }}@else javascript:void(0)@endif">
            <img class="show-full-image async" data-author="906" data-image="@if(!empty($post['goal_client']['profilepic'])){{asset('uploads/thumb_'.$post['goal_client']['profilepic'])}}@else{{asset('assets/images/no-image-icon.jpg')}}@endif" onerror="this.onerror=null;this.src='{{asset('assets/images/no-image-icon.jpg')}}';" alt="user profile image" src="@if(!empty($post['client']['profilepic'])){{asset('uploads/thumb_'.$post['client']['profilepic'])}}@else{{asset('assets/images/no-image-icon.jpg')}}@endif"> 
          </a>
         @endif
      </div>
      <div class="joms-stream__meta row">
         <div class="col-md-9 col-xs-9 pl-0">
            @if($post->client_id != null)
              <a href="@if($post->client_id != Auth::user()->account_id){{ url('social/my/friend/'.$post['client']['id']) }}@else javascript:void(0)@endif" data-joms-username="" class="joms-stream__user">{{$post['client']['firstname']}} {{$post['client']['lastname']}}</a>
            @else 
            <a href="@if($post->goal_friend_id != Auth::user()->account_id){{ url('social/my/friend/'.$post['goal_client']['id']) }}@else javascript:void(0)@endif" data-joms-username="" class="joms-stream__user">{{$post['goal_client']['firstname']}} {{$post['goal_client']['lastname']}}</a>
            @endif
            <a href="javascript:void(0)">
            <span class="joms-stream__time">
            <small>{{ $post->created_at->diffForHumans() }} </small>
            </span>
            </a>
         </div>
         @if($post->client_id == Auth::user()->account_id)
         <!-- delete button -->
         <div class="col-md-3 col-xs-3 text-align-right pr-0 dropdown delete-dropdown">
            <a href="javascript:;" class="dropdown-toggle for-delete" data-toggle="dropdown">
               <i class="fa fa-circle"></i>&nbsp; <i class="fa fa-circle"></i>&nbsp; <i class="fa fa-circle"></i>
               <ul class="dropdown-menu"><i class="fa fa-caret-up" aria-hidden="true"></i>
                  {{-- <li> <a href="javascript:;" data-toggle="modal" data-target="#edit-post-{{$post->id}}">Edit</a></li>   --}}
                  <li> <a href="javascript:;" onclick="editPostModal({{ $post->id }})" >Edit</a></li>
                  <li><a href="javascript:;" onclick="deletePost({{ $post->id }})">Delete</a></li>        
            </ul>
            </a>
         </div>
         <!-- delete button -->
         @endif
      </div>
   </div>
   <div class="joms-stream__body">
      <!-- Fetched data -->
      <div class="">
         <div style="position:relative;">
            <div class="row">
               <div class="col-md-12">
                  
                  <article class="joms-stream-fetch-content" style="margin-left:0; padding-top:0">
                     <span class="joms-stream-fetch-title" style="margin-bottom: 15px;">
                        @if (strlen($post['content']) > 480)
                           <span id="content-{{$post->id}}"> {{ \Illuminate\Support\Str::limit($post['content'], $limit = 480, $end = '')}}  </span>
                           <span id="dots-{{$post->id}}">...</span><a onclick="seeMore({{$post->id}})" id="seeMoreBtn-{{$post->id}}">See more</a>
                          <span id="more-{{$post->id}}" style="display:  none;">{{ $post['content'] }}</span> 
                       @else
                         {{ $post['content'] }}
                       @endif 
                     </span> 
                  </article>
               </div>
            </div>
         </div>
         <div class="row like-section">
            <div class="col-md-6 col-xs-6">
               <a onclick="showLikes({{ $post->id }})">
                 <img src="{{ asset('assets/images/download.svg') }}" alt="Jen" width="18px;">
               </a>
               &nbsp;&nbsp;<span class="all_likes" id="all-likes2-{{ $post->id }}"> {{ $post->getLikeCount() }}</span>
            </div>
            <div class="col-md-6 col-xs-6 text-align-right" id="comment-count2-{{$post->id}}">
               @if($post->getCommentCount() > 0)
               @if($post->getCommentCount() > 1)
               {{ $post->getCommentCount().' comments' }}
               @else
               {{ $post->getCommentCount().' comment' }}
               @endif
               @else
               No Comments
               @endif
            </div>
         </div>
         <div class="row like-section">
            <div class="col-xs-6 col-md-6 text-center">
               @if($post->checkLike($user_id))
        
               <a href="javascript:void(0)" class="unlike" id="like2-{{ $post->id }}" onclick="likePost({{ $post['id'] }})">
               <i class="fa fa-thumbs-up" aria-hidden="true"></i>&nbsp;&nbsp;<span>Unlike</span>
               </a>
               @else
       
               <a href="javascript:void(0)" class="like" id="like2-{{ $post->id }}" onclick="likePost({{ $post['id'] }})">
               <i class="fa fa-thumbs-o-up" aria-hidden="true"></i>&nbsp;&nbsp;<span>Like</span>
               </a>
               @endif
      
            </div>
            <div class="col-xs-6 col-md-6 text-center">
               <span><i class="fa fa-comment" aria-hidden="true"></i></span>	&nbsp;&nbsp;
               <a href="javascript:void(0)" class="comment" onclick="showCommentBox({{ $post['id'] }})">comments</a>
            </div>
         </div>
        {{-- show all comment --}}
        <div class="show_comments fb-image-popup comments-show-hide comments-show-hide-{{ $post['id'] }}">
         @include('Result.social-network.post.show-comment')
       </div>
       {{--  --}}
          
       <div class="comment-section write-comment">
         <div class="joms-stream__header" id="form-new-comment2">
            <div class="joms-avatar--stream ">
               <a href="javascript:void(0)">
               
               <img class="async" data-author="906" src="{{asset('uploads/thumb_'.$client_profile['profilepic'])}}" onerror="this.onerror=null;this.src='{{asset('assets/images/no-image-icon.jpg')}}';" alt="user profile image">
               {{-- <img class="async" src='{{asset('assets/images/no-image-icon.jpg')}}' alt="user profile image"> --}}
               </a>
            </div>
            <div class="joms-stream__meta emoji-div">
               <div class="textarea-container">
                   <textarea class="fb-add-emoji" rows="1" name="emoji" data-emoji-id ="{{$post->id}}" placeholder="Write a Comment..." required autocomplete="off" id="comment-submit-button2-{{$post->id}}"></textarea>
                  <button onclick="submitComment($('#comment-submit-button2-{{ $post->id }}').val(),{{ $post->id }})"><img src="{{asset('assets/images/arrow.png')}}"></button>
            </div>
            </div>
         </div>
      </div>
     
         <!-- comment -->
         <div class="post-comments comments-show-hide comments-show-hide-{{ $post['id'] }}">
            @foreach($post->soical_comments()->limit($comment_count)->orderBY('id', 'DESC')->with('client')->get() as $key1 => $comment)
              @include('Result.social-network.post.single-comment-preview')
            @endforeach
         </div>
       <!-- comment -->

 
         
      </div>
   </div>
</div>
<script type="text/javascript">
 $(".fbphotobox-main-container .dropdown-toggle" ).click(function() {
 $(".fbphotobox-main-container").css({"z-index": 999999999});
 $(".app-sidebar-fixed #sidebarNew").css({"z-index": 999999999});
});

function showCommentBox(post_id){
  $(".comments-show-hide-"+post_id).toggle();
}


</script>
@endif