<div class="main-chat">
<div class="people-list" id="people-list">
    <div class="search search-chat" style="text-align: center">
        <span style="font-size:16px; text-decoration:none; color: white;"><i class="fa fa-user"></i> {{auth()->user()->name}} {{auth()->user()->last_name}}</span>
    </div>
    <ul class="list list-chat">
        @foreach($threads as $inbox)
            @if(!is_null($inbox->thread))
        <li class="clearfix">
            <a href="{{route('message.read', $inbox->withUser->id)}}">
            <!--img src="{{$inbox->withUser->avatar}}" alt="avatar" /-->
            <img src="https://cfi.co.in/wp-content/uploads/2017/08/no-image-icon-md-e1503394497373.png" alt="avatar" />
            <div class="about">
                <div class="name">{{$inbox->withUser->name}} {{ $inbox->withUser->last_name }}</div>
                <div class="status">
                    @if(auth()->user()->id == $inbox->thread->sender->id)
                        <span class="fa fa-reply"></span>
                    @endif
                    <span>{{substr($inbox->thread->message, 0, 20)}}</span>
                </div>
            </div>
            </a>
        </li>
            @endif
        @endforeach

    </ul>
</div>
