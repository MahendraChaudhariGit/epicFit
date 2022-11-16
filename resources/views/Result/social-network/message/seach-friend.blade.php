    <style>
    .chat_ib h5 {
      font-size: 15px;
      color: #464646;
      margin: 14px 0 8px 0;
     }
   .no-found{
      text-align: center;
      margin-top: 10px;
      color: red;
   }
    </style>
    
    <!-- friend list -->  

          <!-- <div class="inbox_chat"> -->
           @if(count($search_data) > 0)
               @foreach($search_data as $data)
               <a href="javascript:;" onclick="showChat({{ $data->id }})">
                  <div class="chat_list active_chat">
                     <div class="chat_people">
                        <div class="chat_img"> 
                            <img src="{{asset('uploads/thumb_'.$data['profilepic'])}}" onerror="this.onerror=null;this.src='{{asset('assets/images/no-image-icon.jpg')}}';" alt="user profile image">
                        </div>
                        <div class="chat_ib">
                           <h5>{{$data['firstname']}} {{$data['lastname']}}</h5>
                        </div>
                     </div>
                  </div>
                </a>
               @endforeach  
            @else
            <h5 class="no-found"> No People </h5>
            @endif   
          <!-- </div> -->
            <!-- friend list -->    