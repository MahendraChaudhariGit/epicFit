@extends('masters.chat')

@section('content')
<div>
    @include('user-chat.userlist')

     <div class="chat chat-new">
	  <div class="chat-header-main">
      <div class="chat-header clearfix">
        @if(isset($user))
            <!--img src="{{@$user->avatar}}" alt="avatar" /-->
		<img src="https://cfi.co.in/wp-content/uploads/2017/08/no-image-icon-md-e1503394497373.png" alt="avatar" />
        @endif
        <div class="chat-about">
            @if(isset($user))
                <div class="chat-with">{{'Chat with ' . @$user->name.' '.@$user->last_name }}</div>
            @else
                <div class="chat-with">No Thread Selected</div>
            @endif
        </div>
        <!--i class="fa fa-star"></i-->
		</div>
      </div> <!-- end chat-header -->
    <div class="chat-history">

        <ul id="talkMessages">

            @foreach($messages as $message)
                @if($message->sender->id == auth()->user()->id)
                    <li class="clearfix client-chat-li" id="message-{{$message->id}}">
                        <div class="message-data message-data-chat align-right">
                            <span class="message-data-time" >{{$message->humans_time}} ago</span> &nbsp; &nbsp;
                            <span class="message-data-name" >{{$message->sender->name}}</span>
                            <a href="#" class="talkDeleteMessage" data-message-id="{{$message->id}}" title="Delete Message"><i class="fa fa-close"></i></a>
                        </div>
                        <div class="message other-message-chat other-message float-right">
                            {{$message->message}}
                        </div>
                    </li>
                @else

                    <li  class="clearfix client-chat-li client-chat-li-to"  id="message-{{$message->id}}">
                        <div class="message-data message-data-chat message-data-chat-to">
                            <span class="message-data-name"> <a href="#" class="talkDeleteMessage" data-message-id="{{$message->id}}" title="Delete Messag"><i class="fa fa-close" style="margin-right: 3px;"></i></a>{{$message->sender->name}}</span>&nbsp; &nbsp;
                            <span class="message-data-time">{{$message->humans_time}} ago</span>
                        </div>
                        <div class="message other-message-chat other-message-chat-to my-message">
                            {{$message->message}}
                        </div>
                    </li>
                    <?php $conversationID = $message->sender->id; ?>
                @endif
            @endforeach


        </ul>

    </div> <!-- end chat-history -->
    <div class="chat-message clearfix">
    <div class="send-chat-btn">
      <form action="" method="post" id="talkSendMessage">
            <textarea name="message-data" id="message-data" placeholder ="Type your message" rows=""></textarea>
            <input type="hidden" name="_id" value="{{@request()->route('id')}}">
            <button type="submit"><i class="fa fa-paper-plane"></i></button>
      </form>

      </div>
      </div>
</div>

      </div>
      </div>
@stop

@section('required-script')
<script>
        var show = function(data) {
            alert(data.sender.name + " - '" + data.message + "'");
        }

        var msgshow = function(data) {
        console.log(data);
            var html = '<li class="clearfix client-chat-li" id="message-' + data.id + '">' +
            '<div class="message-data message-data-chat">' +
            '<span class="message-data-name"> <a href="#" class="talkDeleteMessage" data-message-id="' + data.id + '" title="Delete Messag"><i class="fa fa-close" style="margin-right: 3px;"></i></a>' + data.sender.name + '</span>' +
            '<span class="message-data-time">1 Second ago</span>' +
            '</div>' +
            '<div class="message my-message other-message-chat">' +
            data.message +
            '</div>' +
            '</li>';

            $('#talkMessages').append(html);
        }

    </script>
    {!! talk_live(['user'=>["id"=>auth()->user()->id, 'callback'=>['msgshow']]]) !!}
@stop