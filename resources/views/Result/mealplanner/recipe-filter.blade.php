<div class="appendDiv"></div>
@if(count($review) > 0)
@foreach ($review as $item)
{{-- {{dd($item['upvote'])}} --}}
  <div class="comments__comment">
      <div class="comments__main">
          <div class="comments__avatar avatar avatar--responsive-medium">
              <a href="#" title="keithcancook">
                  <div class="css-1udnd8u">
                      <div class="css-1kdubnn">
                          {{-- href="@if($post->client_id != Auth::user()->account_id){{ url('social/my/friend/'.$post['client']['id']) }} --}}
                          <a href="{{ url('social/my/friend/'.$item->client->id) }}"><img  src="{{asset('uploads/thumb_'.$item->client->profilepic)}}" onerror="this.onerror=null;this.src='{{asset('assets/images/no-image-icon.jpg')}}';" alt="user profile image"/></a>
                      </div>
                  </div>
              </a>
          </div>
          <div class="comments__content">
              <div class="comments__header">
                  <span class="comments__username"><a href="{{ url('social/my/friend/'.$item->client->id) }}" title="keithcancook">{{ $item->client->firstname}} {{ $item->client->lastname}}</a></span>{{date('F j, Y', strtotime($item->created_at))}}
              </div>
          
              <div class="comments__body"><span class="class-name-text">{{ $item->comment }}</span></div>
              <ul class="comments__actions">
                  <li><a class="comments__action upvote" data-type="Review" data-id="{{$item['id']}}" href="javascript:void(0);" aria-label="Upvote comment by keithcancook" style="pointer-events: auto;">Upvote <span class="addCount-Review-{{$item['id']}}">@if($item['upvote']> 0) ({{$item['upvote']}})@endif</span></a></li>
                  <li><a class="comments__action comment_reply reply-Review-{{$item['id']}}" aria-label="Reply to comment by keithcancook" href="javascript:void(0);">Reply</a></li>
                  <!--  <li><a class="comments__action comments__flag" href="javascript:void(0);" aria-label="Flag comment by keithcancook" style="pointer-events: auto;">Flag Inappropriate</a></li> -->
              </ul>
              <div class="css-1kstmw2 reply-textbox reply-textbox-{{$item->id}}">
                  <div class="css-o3dr02">
                   
                     <a  href="{{ url('social/my/friend/'.$auth_user->id) }}"> <img class="avatar avatar--responsive"  src="{{asset('uploads/thumb_'.$auth_user->profilepic)}}" onerror="this.onerror=null;this.src='{{asset('assets/images/no-image-icon.jpg')}}';" alt="user profile image"/></a>
                      <span class="comments__username"><a  href="{{ url('social/my/friend/'.$auth_user->id) }}">{{$auth_user->firstname}} {{$auth_user->lastname}}  </a></span>
                    
                  </div>
                  <div class="css-1nfyqj1">
                      {{-- <span class="css-ac381z">Review our <a href="/code_conduct" target="_blank">Code of Conduct</a></span> --}}
                      <div class="css-1z0x7h2">
                          <div class="css-1juasd6-container">
                              <label for="WriteaReply" class="css-v23pgw">
                                  <span class="css-hlp6ko">Write a Reply</span>
                              </label>
                              <textarea id="WriteaReply" class="css-1oh5j1q forfocuss Review-reply-{{$item->id}}"></textarea>
                              <span class="reply-validation-error reply-validation-error-{{$item->id}}" style="display: none">Must have at least 1 character.</span>
                          </div>
                      </div>
                  </div>
                  <div class="css-1nfyqj1">
                      <button class="btn btn--default btn--inline-desktop reply-submit-comment" data-id="{{ $item->id }}" data-type="Review" data-ga-event="inPageInteraction" data-ga-event-action="Reply Submitted" data-ga-event-label="Receive email updates: true">Submit Reply</button>
                  </div>
              </div>
              {{--  --}}
             
              @if(count($item['replyRecipeReview']) > 0)
                  @foreach($item['replyRecipeReview'] as $reply_review)
          
                   <div class="comments__comment">
                      {{-- <div class="comments__author">Author Comment</div> --}}
                      <div class="comments__main">
                          <div class="comments__avatar avatar avatar--responsive-medium">
                              <a href="#" title="inpatskitchen">
                                  <div class="css-1udnd8u">
                                      <div class="css-1kdubnn">
                                          <a href="{{ url('social/my/friend/'.$reply_review->client->id) }}"> <img src="{{asset('uploads/thumb_'.$reply_review->client->profilepic)}}" onerror="this.onerror=null;this.src='{{asset('assets/images/no-image-icon.jpg')}}';" alt="user profile image"
                                          />
                                          </a>
                                      </div>
                                  </div>
                              </a>
                          </div>
                          <div class="comments__content">
                              <div class="comments__header">
                                  <span class="comments__username"><a href="{{ url('social/my/friend/'.$reply_review->client->id) }}" title="inpatskitchen">{{$reply_review->client->firstname}} {{$reply_review->client->lastname}}</a></span> {{date('F j, Y', strtotime($reply_review->created_at))}}
                              </div>
                              <div class="comments__body"><span class="class-name-text">{{$reply_review->comment}}</span></div>
                              <ul class="comments__actions">
                                  <li><a class="comments__action upvote" data-type="Reply" data-id="{{$reply_review['id']}}" href="javascript:void(0);" aria-label="Upvote comment by inpatskitchen" style="pointer-events: auto;">Upvote <span class="addCount-Reply-{{$reply_review['id']}}">@if($reply_review['upvote']> 0) ({{$reply_review['upvote']}})@endif</span></a></li>
                                  <li><a class="comments__action comment_reply reply-Reply-{{$reply_review['id']}}" aria-label="Reply to comment by inpatskitchen" href="javascript:void(0);">Reply</a></li>
                                  <!--  <li><a class="comments__action comments__flag" href="javascript:void(0);" aria-label="Flag comment by inpatskitchen" style="pointer-events: auto;">Flag Inappropriate</a></li> -->
                              </ul>
                              <div class="css-1kstmw2 reply-textbox">
                                  <div class="css-o3dr02">
                                        
                                      <a  href="{{ url('social/my/friend/'.$auth_user->id) }}"><img class="avatar avatar--responsive" src="{{asset('uploads/thumb_'.$auth_user->profilepic)}}" onerror="this.onerror=null;this.src='{{asset('assets/images/no-image-icon.jpg')}}';" alt="user profile image"/></a>
                                      <span class="comments__username"> <a  href="{{ url('social/my/friend/'.$auth_user->id) }}">{{$auth_user->firstname}} {{$auth_user->lastname}}</a></span>
                                  </div>
                                  <div class="css-1nfyqj1">
                                      {{-- <span class="css-ac381z">Review our <a href="/code_conduct" target="_blank">Code of Conduct</a></span> --}}
                                      <div class="css-1z0x7h2">
                                          <div class="css-1juasd6-container">
                                              <label for="WriteaReply" class="css-v23pgw">
                                                  <span class="css-hlp6ko">Write a Reply</span>
                                              </label>
                                              <textarea id="WriteaReply" class="css-1oh5j1q forfocuss Reply-reply-{{$reply_review->id}}"></textarea>
                                              <span class="reply-validation-error reply-validation-error-{{$reply_review->id}}" style="display: none">Must have at least 1 character.</span>
                                          
                                          </div>
                                      </div>
                                  </div>
                                  <div class="css-1nfyqj1">
                                      <button class="btn btn--default btn--inline-desktop reply-submit-comment" data-type="Reply" data-id="{{ $reply_review->id }}" data-review="{{ $reply_review->recipe_review_id }}" data-ga-event="inPageInteraction" data-ga-event-action="Reply Submitted" data-ga-event-label="Receive email updates: true">Submit Reply
                                      </button>
                                  </div>
                              </div>
                          </div>
                      </div>
                  </div>
                  @endforeach
              @endif
              
          {{--  --}}
          </div>
      </div>
  </div>
 @endforeach
@endif
