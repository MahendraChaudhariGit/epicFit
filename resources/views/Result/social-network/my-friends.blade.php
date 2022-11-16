    <div class="col-md-12 bg-white my-friend-div hidden">
        <div class="">
            <br>
            <!-- <h2><i class="fa fa-comments-o"></i> Send Messages</h2> -->
            <h2><i class="fa fa-user"></i> My Friends </h2> 
            <hr>
        </div>
<div class="app-core ">
    @if(Auth::user()->account_id == $client->id)
    <div class="form-group">
        <div class="input-group">
            <input type="text" class="form-control search-from-all-my-friends" placeholder="Search friends">
           <!--  <div class="input-group-addon"><i class="fa fa-search"></i></div> -->
        </div>
    </div>
    @endif
    <div class="all-my-friends">
    <div class="row">
        <div class="col-md-8">
            <strong>All Friends {{ count($data) }} </strong>
        </div>
        {{-- <div class="col-md-4 text-align-right">
            <a href="javascript:void(0)"><strong>Show all</strong></a>
        </div> --}}
    </div>
    
    @if(isset($data))
    @foreach($data as $client_data)
    <div class="joms-stream__header friend-section">
        <div class="joms-avatar--stream ">
            {{-- <a href="@if(Auth::user()->account_id == $client->id){{ url('social/my/friend/'.$client_data->id) }}@else javascript:void(0) @endif"> --}}
            <a 	@if($client_data->about_me != 'epichq')  href="{{ url('social/my/friend/'.$client_data->id) }}" @endif>
                <img @if(!empty($client_data->profilepic)) src="{{asset('uploads/thumb_'.$client_data->profilepic)}}" @else src="{{asset('result/images/noimage.gif')}}" @endif alt="{{ $client_data->firstname }}" onerror="this.onerror=null;this.src='{{asset('assets/images/no-image-icon.jpg')}}';">
          
            </a>
        </div>
        <div class="joms-stream__meta">
            <div class="friend-list">
                {{-- <a href="@if(Auth::user()->account_id == $client->id){{ url('social/my/friend/'.$client_data->id) }}@else javascript:void(0) @endif" data-joms-username="" class="joms-stream__user"> --}}
                <a 	@if($client_data->about_me != 'epichq') href="{{ url('social/my/friend/'.$client_data->id) }}" @endif data-joms-username="" class="joms-stream__user">
                    {{ $client_data->firstname }} {{ $client_data->lastname }}
                </a>
            </div>
            <div class="friend-list-detail">
                <li class="dropdown current-user">
                    @if(Auth::user()->account_id == $client->id)
                    <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                        <i class="fa fa-circle"></i>  <i class="fa fa-circle"></i>  <i class="fa fa-circle"></i>
                    </a>
                    @endif
                    <ul class="dropdown-menu dropdown-dark">
                        <li>
                            <div class="joms-stream__header friend-section">
                                <div class="joms-avatar--stream ">
                                    <a 	@if($client_data->about_me != 'epichq')  href="@if(Auth::user()->account_id == $client->id){{ url('social/my/friend/'.$client_data->id) }}@else javascript:void(0) @endif" @endif>
                                        <img @if(!empty($client_data->profilepic)) src="{{asset('uploads/thumb_'.$client_data->profilepic)}}" @else src="{{asset('result/images/noimage.gif')}}" @endif alt="{{ $client_data->firstname }}" onerror="this.onerror=null;this.src='{{asset('assets/images/no-image-icon.jpg')}}';">
                                    </a>
                                </div>
                                <div class="joms-stream__meta">
                                    <div class="friend-list">
                                        <a 	@if($client_data->about_me != 'epichq')  href="@if(Auth::user()->account_id == $client->id){{ url('social/my/friend/'.$client_data->id) }}@else javascript:void(0) @endif" @endif data-joms-username="" class="joms-stream__user">{{ $client_data->firstname }} {{ $client_data->lastname }}</a>
                                    </span> 
                                    <span class="joms-stream__time">
                                        <small> </small> 
                                        {{-- <small>friend since April 2020 </small> --}}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li>
                        <div>
                            <a href="javascript:void(0)"><i class="fa fa-comments" style="
                            font-size: 12px;
                            "></i> Message {{ $client_data->firstname }} {{ $client_data->lastname }}</a>
                        </div>
                    </li>
                    @if($client_data->about_me != 'epichq') 
                    <li>
                        <div>
                            <a href="javascript:void(0)" class="cancel-friend unfriend" data-message="Are you sure you want to Unfriend this friend?" data-client-id="{{ $client_data->id }}"><i class="fa fa-user-times" style="
                            font-size: 12px;
                            "></i> Unfriend {{ $client_data->firstname }} {{ $client_data->lastname }}</a>
                        </div>
                    </li>
                    @endif
                </ul>
            </li> 
        </div>
    </div>
</div>
@endforeach
@endif
</div>
</div>
</div>