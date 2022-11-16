<div class="row">
    <div class="col-md-8">
        <strong>Send Request {{ count($client) }}</strong>
    </div>
    
</div>
@if(isset($client))
    @foreach($client as $client_value)
            <div class="joms-stream__header friend-section">
                <div class="joms-avatar--stream ">
                    <a href="@if(Auth::user()->account_id == $client_value->client_id){{ url('social/my/friend/'.$client_value->clients->id) }}@else javascript:void(0) @endif">
            <img @if(!empty($client_value->clients->profilepic)) src="{{asset('uploads/thumb_'.$client_value->clients->profilepic)}}" @else src="{{asset('result/images/noimage.gif')}}" @endif alt="{{ $client_value->clients->firstname }}" onerror="this.onerror=null;this.src='{{asset('assets/images/no-image-icon.jpg')}}';">
        
        </a>
                </div>
                <div class="joms-stream__meta">
                    <div class="friend-list">
                        <a href="@if(Auth::user()->account_id == $client_value->client_id){{ url('social/my/friend/'.$client_value->clients->id) }}@else javascript:void(0) @endif" data-joms-username="" class="joms-stream__user">{{ $client_value->clients->firstname }} {{ $client_value->clients->lastname }}</a>
                    </div>
                    <div class="friend-list-detail">
                    @php
                        $to1 = \Carbon\Carbon::createFromFormat('Y-m-d H:s:i', $client_value->updated_at);
                        $from1 = \Carbon\Carbon::createFromFormat('Y-m-d H:s:i', date("Y-m-d H:i:s"));
                        $days = $to1->diffInDays($from1);
                    @endphp
                    {{ ($days==0)?'Today':$days.'d' }}
                    </div>
                    <div>
                    <button class="btn btn-primary">Requested</button>
                    <button class="btn btn-default cancel-friend" data-message="Are you sure you want to cancel this friend request?" data-client-id="{{ $client_value->added_client_id }}">Cancel</button>
                </div>
                </div>
                
            </div>
    @endforeach
@endif