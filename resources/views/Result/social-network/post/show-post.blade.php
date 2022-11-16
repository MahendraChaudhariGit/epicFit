{!! Html::style('result/css/jquery.fancybox.min.css') !!}
{!! Html::script('result/js/jquery.fancybox.min.js') !!}
<style type="text/css">
.class-name-text {
  white-space: pre-wrap;
}
   #content{
      line-height: normal;
          padding-top: 8px;
   }
</style>

@if($post->client_id != null ||  $post->goal_friend_id == Auth::user()->account_id)
<div class="joms-stream joms-embedly--left joms-js--stream joms-js--stream-112 panel-post-div " data-post-id= "{{$post->id}}" id="panel-post-{{ $post->id }}">
   <div class="joms-stream__header">
      <div class="joms-avatar--stream ">
         @if($post->client_id != null)
          <a href="@if($post->client_id != Auth::user()->account_id){{ url('social/my/friend/'.$post['client']['id']) }}@else javascript:void(0)@endif">
            <img class="show-full-image async" data-author="906" data-image="@if(!empty($post['client']['profilepic'])){{asset('uploads/thumb_'.$post['client']['profilepic'])}}@else{{asset('assets/images/no-image-icon.jpg')}}@endif" onerror="this.onerror=null;this.src='{{asset('assets/images/no-image-icon.jpg')}}';" alt="user profile image"> 
          </a>
         @else
     
          <a href="@if($post->goal_friend_id != Auth::user()->account_id){{ url('social/my/friend/'.$post['goal_client']['id']) }}@else javascript:void(0)@endif">
            <img class="show-full-image async" data-author="906" data-image="@if(!empty($post['goal_client']['profilepic'])){{asset('uploads/thumb_'.$post['goal_client']['profilepic'])}}@else{{asset('assets/images/no-image-icon.jpg')}}@endif" onerror="this.onerror=null;this.src='{{asset('assets/images/no-image-icon.jpg')}}';" alt="user profile image"> 
          </a>
         @endif
      </div>
      <div class="joms-stream__meta row">
         <div class="col-md-10 col-xs-9 pl-0">
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
         <div class="col-md-2 col-xs-3 text-align-right pr-0 dropdown delete-dropdown">
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
                  <article class="joms-stream-fetch-content" style="margin-left:0; padding-top:0"> {{-- <span class="joms-stream-fetch-title" style="margin-bottom: 15px;">{{$post['content']}}</span> --}} <span class="joms-stream-fetch-title" style="margin-bottom: 15px;"> @if (strlen($post['content']) > 480) <span class="class-name-text" id="content-{{$post->id}}">{!! \Illuminate\Support\Str::limit(trans($post['content']), $limit = 480, $end = '') !!}</span> <span id="dots-{{$post->id}}">...</span><a onclick="seeMore({{$post->id}})" id="seeMoreBtn-{{$post->id}}">See more</a> <span  class="class-name-text" id="more-{{$post->id}}" style="display:  none;">{!! trans($post['content']) !!}</span> {{-- <span id="more-{{$post->id}}" style="display:  none;">{{ substr($post['content'], 480) }}</span> --}} {{-- <span id="more-{{$post->id}}" style="display:  none;">{{ substr_replace($post['content'], $post['content'], 0) }}</span>  --}} @else <span class="class-name-text"> {!! trans($post['content'])  !!}</span> {{-- <span class="class-name-text"> {!! nl2br($post['content'])  !!}</span> --}} {{-- {!! trans($user->proffile) !!} --}} @endif</span></article>
               </div>
            <div class="col-md-12 text-center bg-ornge show-post">
               @if($post['social_post_image']->count() > 0) 
                @if($post['social_post_image']->count() > 5)
                  @php
                    $num = $post['social_post_image']->count() - 4;
                  @endphp
                  <span class="more5img-show">+{{ $num}}</span>
                 @endif
              
                  @foreach($post['social_post_image'] as $key => $post_image)
                     <a rel="{{$post->id}}"> 
                       {{-- @if($key < 5) --}}
                        <img data-id="{{$post->id}}" class="photo joms-stream-thumb async fb-popup-image-post-id" fbphotobox-src="{{asset('uploads/posts/'.$post_image['image_path'])}}"  alt="image for post" src="{{asset('uploads/posts/'.$post_image['image_path'])}}">  
                      {{-- @endif --}}
                     </a>
                  @endforeach
               @endif
               @if($post['social_post_video']['video_path'])
                   <a data-fancybox="video_{{$post->id}}" href="{{asset('uploads/posts/'.$post['social_post_video']['video_path'])}}"> 
                        <video class="photo joms-stream-thumb video-status1 inactive async" width="100%" controls="" playsinline="" data-video="{{asset('uploads/posts/'.$post['social_post_video']['video_path'])}}" ></video>
                    </a>
              
               @endif
                </div>
            </div>
         </div>
         <div class="row like-section">
            <div class="col-md-8 col-xs-6">
               <a onclick="showLikes({{ $post->id }})">
                 <img src="{{ asset('assets/images/download.svg') }}" alt="Jen" width="18px;">
               </a>
               &nbsp;&nbsp;<span class="all_likes" id="all-likes-{{ $post->id }}"> {{ $post->getLikeCount() }}</span>
            </div>
            <div class="col-md-4 col-xs-6 text-align-right" id="comment-count-{{$post->id}}">
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
               <a href="javascript:void(0)" class="unlike" id="like-{{ $post->id }}" onclick="likePost({{ $post['id'] }})">
               <i class="fa fa-thumbs-up" aria-hidden="true"></i>&nbsp;&nbsp;<span>Unlike</span>
               </a>
               @else
               <a href="javascript:void(0)" class="like" id="like-{{ $post->id }}" onclick="likePost({{ $post['id'] }})">
               <i class="fa fa-thumbs-o-up" aria-hidden="true"></i>&nbsp;&nbsp;<span>Like</span>
               </a>
               @endif
      
            </div>
            <div class="col-xs-6 col-md-6 text-center">
               <span><i class="fa fa-comment" aria-hidden="true"></i></span>  &nbsp;&nbsp;
               <a href="javascript:void(0)" class="comment" onclick="showCommentBox({{ $post['id'] }})">comments</a>
            </div>
         </div>
         <div class="show_comments comments-show-hide comments-show-hide-{{ $post['id'] }}">
             @include('Result.social-network.post.show-comment')
         </div>
         <!-- comment -->
         <div class="comment-section write-comment">
            <div class="joms-stream__header" id="form-new-comment">
               <div class="joms-avatar--stream ">
                  <a href="javascript:void(0)">
                  <img class="async" data-author="906" data-image="{{asset('uploads/thumb_'.$client_profile['profilepic'])}}" onerror="this.onerror=null;this.src='{{asset('assets/images/no-image-icon.jpg')}}';" alt="user profile image">
                  </a>
               </div>
               <div class="joms-stream__meta emoji-div">
                  <div class="textarea-container">
                     <textarea class="add-emoji" rows="1" name="emoji" data-emoji-id ="{{$post->id}}" placeholder="Write a Comment..." required autocomplete="off" id="comment-submit-button-{{$post->id}}"></textarea>
                  {{-- <textarea class="" rows="1" placeholder="Write a Comment..." required autocomplete="off" id="comment-submit-button-{{$post->id}}" onkeyup="submitComment(event,{{ $post->id }})" ></textarea> --}}
                  <button onclick="submitComment($('#comment-submit-button-{{ $post->id }}').val(),{{ $post->id }})"><img src="{{asset('assets/images/arrow.png')}}"></button>
               </div>
                  {{-- <button class="joms-focus__button--add comment-btn" id="comment-submit-button-{{$post->id}}" onclick="submitComment({{ $post->id }})">Submit</button> --}}

                 <!--  <textarea class="form-control" rows="1" placeholder="Write a Comment..." required autocomplete="off"></textarea> -->
               <!--    <button class="joms-focus__button--add comment-btn" id="comment-submit-button-{{$post->id}}" onclick="submitComment({{ $post->id }})">Submit</button> -->

               </div>
            </div>
         </div>
         <!-- comment -->
         <div class="post-comments comments-show-hide comments-show-hide-{{ $post['id'] }}">
            @foreach($post->soical_comments()->limit($comment_count)->orderBY('id', 'DESC')->with('client')->get() as $key1 => $comment)
              @include('Result.social-network.post.single-comment')
            @endforeach
         </div>
         
      </div>
   </div>
</div>
@endif