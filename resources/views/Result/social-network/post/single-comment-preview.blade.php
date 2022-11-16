
<style type="text/css">
	.comments{
	width: fit-content;
    background: #f0f2f5ba;
    padding: 10px;
    border-radius: 13px;
    font-size: 12px;
    margin-bottom: 2px;
	}
	.comments span{
		font-size: 12px;
	}
	.joms-stream__header, .joms-comment__header, .joms-poll__header {
    display: table;
    padding: 9px 8px;
    width: 100%;
}
.comment-section{
	width: calc(100% - 40px);
    display: inline-block;
}
.comment-delete{
	width: 35px;
    display: inline-block;
    vertical-align: top;
}
</style>
<div class="joms-stream__header" id="post-comment2-{{ $comment->id }}">                                         			
	<div class="joms-avatar--stream ">
		<a href="#">
	    	<img data-author="906" src="{{asset('uploads/thumb_'.$comment->client->profilepic)}}" onerror="this.onerror=null;this.src='{{asset('assets/images/no-image-icon.jpg')}}';" alt="user profile image">
		</a>
	</div>
	<div class="joms-stream__meta edit-emoji-div">	
		<div class="comment-section" id="comment2-{{$comment->id}}">
			<div class="comments">

				<a href="@if(Auth::user()->account_id == $comment->client_id) #top @else {{ route('social.my_friend', $comment->client_id) }} @endif" data-joms-username="" class="joms-stream__user">
					{{ $comment->client->firstname }} {{ $comment->client->lastname }}
				</a>
				<a href="javascript:;">
					<span class="joms-stream__time" style="font-size: 16px;">
						<small id="comment-value2-{{$comment->id}}"> {!! $comment->comment !!} </small>
					</span>
				</a>
			</div>
		</div>
 <!-- delete button --> 
   @if($post->client_id == Auth::user()->account_id || $comment->client_id == Auth::user()->account_id)
		<div class="text-align-right pr-0 dropdown delete-dropdown comment-delete" id="delete-option2-{{$comment->id}}">
			<a href="javascript:;" class="dropdown-toggle for-delete" data-toggle="dropdown" aria-expanded="false">
				<i class="fa fa-circle"></i>&nbsp; <i class="fa fa-circle"></i>&nbsp; <i class="fa fa-circle"></i>
			<ul class="dropdown-menu"><i class="fa fa-caret-up" aria-hidden="true"></i>
			@if($comment->client_id == Auth::user()->account_id)
		    	<li><a href="javascript:;" onclick="editComment({{ $comment->id }}, {{ $post->id }} )" >Edit</a></li> 
			 @endif
		    	 <li><a href="javascript:;" onclick="removeComment({{ $comment->id }}, {{ $post->id }})">Delete</a></li>                      
		</ul>
		</a>
	  </div>
	@endif
<!-- delete button -->		
		<div class="times">
			<span>{{ $comment->created_at->diffForHumans() }}</span>
			@if($comment->edit == '1')
			  &nbsp; &nbsp;<i class="fa fa-pencil" aria-hidden="true"></i>
            @endif
		</div>
	</div>
</div>

