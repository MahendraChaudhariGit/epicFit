{{-- <div class="comments__comment" id="answer484840"> --}}
 <div class="comments__comment"> 
    <div class="comments__main">
        <div class="comments__avatar avatar avatar--responsive-medium">
            <a href="#" title="Karen">
                <div class="css-1udnd8u">
                    <div class="css-1kdubnn">
                        <a href="{{ url('social/my/friend/'.$create_review->client->id) }}"><img  src="{{asset('uploads/thumb_'.$create_review->client->profilepic)}}" onerror="this.onerror=null;this.src='{{asset('assets/images/no-image-icon.jpg')}}';" alt="user profile image"></a>
                        {{-- <img src="{{asset('assets/images/user.png')}}"/> --}}
                    </div>
                </div>
            </a>
        </div>
        <div class="comments__content">
            <div class="comments__header">
                <span class="comments__username"><a href="{{ url('social/my/friend/'.$create_review->client->id) }}" title="Karen">{{$create_review->client->firstname}} {{$create_review->client->lastname}}</a></span>{{date('F j, Y', strtotime($create_review->created_at))}}
            </div>
            <div class="comments__body"><span class="class-name-text">{{$create_review->comment}}</span></div>
            <ul class="comments__actions">
                <li><a class="comments__action upvote" data-type="{{$type}}" data-id="{{$create_review['id']}}" href="javascript:void(0);" aria-label="Upvote comment by {{$create_review->client->firstname}}" style="pointer-events: auto;">Upvote <span class="addCount-{{$type}}-{{$create_review['id']}}"></span></a></li>
                <li><a class="comments__action comment_reply reply-{{$type}}-{{$create_review['id']}}" data-id="{{$create_review['id']}}" aria-label="Reply to comment by Karen" href="javascript:void(0);">Reply</a></li>
                <!--   <li><a class="comments__action comments__flag" href="javascript:void(0);" aria-label="Flag comment by Karen" style="pointer-events: none;">Comment Flagged</a></li> -->
            </ul>
            <div class="css-1kstmw2 reply-textbox reply-textbox-{{$create_review['id']}}" style="display: none;">
                <div class="css-o3dr02">

                    <a href="{{ url('social/my/friend/'.$client_data->id) }}"><img class="avatar avatar--responsive"  src="{{asset('uploads/thumb_'.$client_data->profilepic)}}" onerror="this.onerror=null;this.src='{{asset('assets/images/no-image-icon.jpg')}}';" alt="user profile image"></a>
                    <span class="comments__username"> <a href="{{ url('social/my/friend/'.$client_data->id) }}">{{$client_data->firstname}} {{$client_data->lastname}}</a></span>
                </div>
                <div class="css-1nfyqj1">
                    {{-- <span class="css-ac381z">Review our <a href="/code_conduct" target="_blank">Code of Conduct</a></span> --}}
                    <div class="css-1z0x7h2">
                        <div class="css-1juasd6-container">
                            <label for="WriteaReply" class="css-v23pgw">
                                <span class="css-hlp6ko">Write a Reply</span>
                            </label>
                            <textarea id="WriteaReply reply-{{$create_review['id']}}" class="css-1oh5j1q forfocuss {{$type}}-reply-{{$create_review['id']}}" ></textarea>
                            <span class="reply-validation-error reply-validation-error-{{$create_review['id']}}" style="display: none">Must have at least 1 character.</span>
                        </div>
                    </div>
                </div>
                <div class="css-1nfyqj1">
                    <button class="btn btn--default btn--inline-desktop reply-submit-comment" data-type="{{$type}}" data-id="{{ $create_review->id }}" @if($type == 'Reply') data-review="{{ $create_review->recipe_review_id }}" @endif>Submit Reply</button>
                </div>
            </div>
        </div>
    </div>
 </div>
