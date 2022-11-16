@if($post->getLikeCount() == 0)
<div class="alert alert-danger" role="alert" style="margin: 10px;">There is no like</div>
@else
<h5>{{ $post->getLikeCount() }} @if($post->getLikeCount() > 1){{ 'likes' }}@else{{ 'like' }}@endif</h5>
@foreach($post->soical_likes()->limit(2000000)->with('client')->get() as $like)
<div class="joms-stream__header">
   <div class="joms-avatar--stream ">
      <a href="javascript:void(0)">
          <img data-author="906" src="{{asset('uploads/thumb_'.$like->client->profilepic)}}" onerror="this.onerror=null;this.src='{{asset('assets/images/no-image-icon.jpg')}}';" alt="user profile image">
      </a>
   </div>
   <div class="joms-stream__meta row">
      <div class="col-md-12 pl-0">
         <a href="javascript:void(0)" data-joms-username="" class="joms-stream__user">{{ $like->client->firstname }} {{ $like->client->lastname }}</a>
         <!-- <a href="javascript:void(0)">
         <span class="joms-stream__time">
         <small>35 minutes ago </small>
         </span>
         </a> -->
      </div>
   </div>
</div>
@endforeach
@endif