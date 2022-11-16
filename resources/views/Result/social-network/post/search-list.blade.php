
@if($all_clients->count() > 0)
    @foreach($all_clients as $client)
        <div class="joms-stream__header friend-section">
           <a href="{{ route('search.all_list', ['client_id' => $client->id]) }}">
            <div class="joms-avatar--stream ">
                    <img src="{{asset('uploads/thumb_'.$client->profilepic)}}" onerror="this.onerror=null;this.src='{{asset('assets/images/no-image-icon.jpg')}}';" alt="user profile image">
            </div>
            <div class="joms-stream__meta">
                <div class="friend-list">
                        {{ $client->firstname }} {{ $client->lastname }}
                </div>
                {{-- <div class="friend-list-detail">
                    <i class="fa fa-close"></i>
                </div> --}}
            </div>
        </a>
        </div>
    @endforeach
@else 
    <div class="no-search">
        <div class="joms-stream__header friend-section">
        <span>No record found</span>
    </div>
    </div>
@endif


