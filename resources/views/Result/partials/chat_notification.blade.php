@foreach($data as $key => $value)
<a href="javascript:;" onclick="showChat({{ $value['sender']->id }})">
  <div class="chat_list">   
      <div class="chat_people">
         <div class="chat_img">
           <img data-author="906" src="{{asset('uploads/thumb_'.$value['sender']->profilepic)}}" onerror="this.onerror=null;this.src='http://127.0.0.1:8000/assets/images/no-image-icon.jpg';" alt="user profile image">
          </div>
         <div class="chat_ib">
           <h5>{{$value['sender']->firstname . ' '. $value['sender']->lastname}}</h5>
            <span>{!! $value['message'] !!}
            </span> <span class="time_date">   {{ $value['created_at']->diffForHumans() }}</span> 
         </div>
      </div>
  </div>
</a>
@endforeach