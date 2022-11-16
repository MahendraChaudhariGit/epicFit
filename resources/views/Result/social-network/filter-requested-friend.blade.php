<div class="row">
    <div class="col-md-8">
        <strong>Friend Request {{ count($client) }}</strong>
    </div>
    {{-- <div class="col-md-4 text-align-right">
        <a href="javascript:void(0)"><strong>Show all</strong></a>
    </div> --}}
</div>
@if(isset($client))
    @foreach($client as $client_val)
            <div class="joms-stream__header friend-section">
                <div class="joms-avatar--stream ">
                    <a href="@if(Auth::user()->account_id == $client_val->added_client_id){{ url('social/my/friend/'.$client_val->clients_recieve_request->id) }}@else javascript:void(0) @endif">
            <img @if(!empty($client_val->clients_recieve_request->profilepic)) src="{{asset('uploads/thumb_'.$client_val->clients_recieve_request->profilepic)}}" @else src="{{asset('result/images/noimage.gif')}}" @endif alt="{{ $client_val->clients_recieve_request->firstname }}" onerror="this.onerror=null;this.src='{{asset('assets/images/no-image-icon.jpg')}}';">
        {{-- <img src="{{asset('assets/images/social-p1.jpg')}}" alt="Jen"> --}}
        </a>
                </div>
                <div class="joms-stream__meta">
                    <div class="friend-list">
                        <a href="@if(Auth::user()->account_id == $client_val->added_client_id){{ url('social/my/friend/'.$client_val->clients_recieve_request->id) }}@else javascript:void(0) @endif" data-joms-username="" class="joms-stream__user">{{ $client_val->clients_recieve_request->firstname }} {{ $client_val->clients_recieve_request->lastname }}</a>
                    </div>
                    <div class="friend-list-detail">
                    @php
                        $to = \Carbon\Carbon::createFromFormat('Y-m-d H:s:i', $client_val->updated_at);
                        $from = \Carbon\Carbon::createFromFormat('Y-m-d H:s:i', date("Y-m-d H:i:s"));
                        $diff_in_days = $to->diffInDays($from);
                    @endphp
                    {{ ($diff_in_days==0)?'Today':$diff_in_days.'d' }}
                    </div>
                    <div>
                    <button class="btn btn-primary confirm-friend" data-client-id="{{ $client_val->client_id }}">Confirm</button>
                    <button class="btn btn-default reject-friend" data-message="Are you sure you want to reject this friend request?" data-client-id="{{ $client_val->client_id }}">Delete</button>
                </div>
                </div>
                
            </div>
    @endforeach
@endif