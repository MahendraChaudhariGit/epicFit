
<section class="comments css-ytmiob" id="ReviewsPanel">
    <ul role="tablist" aria-label="User feedback for this recipe">
      <li>
          <button class="btn--link" type="button" role="tab" disabled>Reviews (<span class="totalReview" data-old="{{$totalReview}}">{{$totalReview}}</span>)</button>
      </li>
  </ul>

  <h3 class="comments__title">
      <span class="icon__comments"></span><span class="totalReview" data-old="{{$totalReview}}">{{$totalReview}}</span> Reviews</h3>
<!--review popup-->


  <div class="css-1kstmw2">
      <div class="css-o3dr02 leave-review-show">
        {{-- <img src="{{asset('uploads/thumb_'.$item->client->profilepic)}}" onerror="this.onerror=null;this.src='{{asset('assets/images/no-image-icon.jpg')}}';" alt="user profile image"/> --}}

          <img class="avatar avatar--responsive" src="{{asset('uploads/thumb_'.$auth_user->profilepic)}}" onerror="this.onerror=null;this.src='{{asset('assets/images/no-image-icon.jpg')}}';" alt="user profile image"/>
          <span class="comments__username">{{$auth_user->firstname}} {{$auth_user->lastname}}</span>
      </div>
      <div class="css-1nfyqj1">
      <!--     <span class="css-ac381z leave-review-show">Review our <a href="/code_conduct" target="_blank">Code of Conduct</a> -->
          </span>
          <div class="css-1z0x7h2">
              <div class="css-1juasd6-container">
                  <label for="LeaveaReview" class="css-v23pgw">
                      <span class="css-hlp6ko">Leave a Review</span>
                  </label>
                  <div class="transparent-bg"></div>
                  <textarea id="LeaveaReview" class="css-1oh5j1q forfocuss leave-review" autocomplete="off"></textarea>
                  <span class="validation-error" style="display: none">Must have at least 1 character.</span>

              </div>
          </div>
      </div>
      <div class="css-1nfyqj1 leave-review-show">
          <button class="btn btn--default btn--inline-desktop submit-review" data-ga-event="inPageInteraction" data-ga-event-action="Review Submitted" data-ga-event-label="Receive email updates: true">Submit Review</button>
      </div>
      <div class="css-smihfb rating-popup">
    <h4 class="css-hj9l4n">How did you like this recipe?</h4>
    <div class="css-dnxrme">
        <div class="rating rating--btns rating--btns-large rating--centered" role="radiogroup" aria-labelledby="ratingLabel">
          <span class="rating-label"></span>          
           

            @for($i=5;$i>= 1;$i--)
               <input type="radio" name="rating" tabindex="-1" id="rating_btn_comments_86557_{{$i}}" value="{{$i}}" />
               <input name="comment_rating" class="save-comment-rating-val" value="" hidden>
                <label for="rating_btn_comments_86557_{{$i}}" data-val="{{$i}}" class="rating__star rating__star--btns commentStarRating"   title="{{$i}} Stars:{{$stars[$i]}}" aria-label="{{$i}} Stars:{{$stars[$i]}}" data-toggle="tooltip" data-placement="bottom" title="{{$i}}">
                    <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 25 23.8">
                        <path fill="currentColor" d="M12.5 0l2.9 9.1H25l-7.7 5.6 2.9 9.1-7.7-5.7-7.7 5.7 2.9-9.1L0 9.1h9.6z"></path>
                    </svg>
                </label>
            @endfor
             {{-- <input type="radio" name="rating" tabindex="-1" id="rating_btn_comments_86557_5" value="5" /> --}}
            {{-- <input type="radio" name="rating" tabindex="-1" id="rating_btn_comments_86557_5" value="5" />
            <label for="rating_btn_comments_86557_5" class="rating__star rating__star--btns" aria-label="5 Stars: Love Love Love." data-toggle="tooltip" data-placement="bottom" title="5 Stars: Love Love Love." style="color:inherit;">

                <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 25 23.8"><path fill="currentColor" d="M12.5 0l2.9 9.1H25l-7.7 5.6 2.9 9.1-7.7-5.7-7.7 5.7 2.9-9.1L0 9.1h9.6z"></path></svg>
            </label>

            <input type="radio" name="rating" tabindex="-1" id="rating_btn_comments_86557_4" value="4" />
            <label for="rating_btn_comments_86557_4" class="rating__star rating__star--btns" aria-label="4 Stars: I'd have seconds!" data-toggle="tooltip" data-placement="bottom" title="4 Stars: I'd have seconds!" style="color:inherit;">
                <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 25 23.8"><path fill="currentColor" d="M12.5 0l2.9 9.1H25l-7.7 5.6 2.9 9.1-7.7-5.7-7.7 5.7 2.9-9.1L0 9.1h9.6z"></path></svg>
            </label>

            <input type="radio" name="rating" tabindex="-1" id="rating_btn_comments_86557_3" value="3" />
            <label for="rating_btn_comments_86557_3" class="rating__star rating__star--btns" aria-label="3 Stars: Meh..." data-toggle="tooltip" data-placement="bottom" title="3 Stars: Meh..." style="color:inherit;">
                <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 25 23.8"><path fill="currentColor" d="M12.5 0l2.9 9.1H25l-7.7 5.6 2.9 9.1-7.7-5.7-7.7 5.7 2.9-9.1L0 9.1h9.6z"></path></svg>
            </label>

            <input type="radio" name="rating" tabindex="-1" id="rating_btn_comments_86557_2" value="2" />
            <label for="rating_btn_comments_86557_2" class="rating__star rating__star--btns" aria-label="2 Stars: Not feeling it." data-toggle="tooltip" data-placement="bottom" title="2 Stars: Not feeling it." style="color:inherit;">
                <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 25 23.8"><path fill="currentColor" d="M12.5 0l2.9 9.1H25l-7.7 5.6 2.9 9.1-7.7-5.7-7.7 5.7 2.9-9.1L0 9.1h9.6z"></path></svg>
            </label>

            <input type="radio" name="rating" tabindex="0" id="rating_btn_comments_86557_1" value="1" />
            <label for="rating_btn_comments_86557_1" class="rating__star rating__star--btns" aria-label="1 Star: Needs a do-over." data-toggle="tooltip" data-placement="bottom" title="1 Star: Needs a do-over." style="color:inherit;">
                <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 25 23.8"><path fill="currentColor" d="M12.5 0l2.9 9.1H25l-7.7 5.6 2.9 9.1-7.7-5.7-7.7 5.7 2.9-9.1L0 9.1h9.6z"></path></svg>
            </label> --}}

        </div>
    </div>
    <div class="css-1rf1zvz">

      <button type="button" class="btn btn--default btn--outline skip-btn">Skip</button>
      <button class="btn btn--default submit-rating">Submit Rating</button>
    </div>
</div>
<div class="thanks-popup" style="display:none;">
  <div class="css-125iry3">
  <h4>Consider yourself heard. Thanks!</h4>
</div>
</div>
  </div>
  {{-- <div class="dropdown-show">
  </div>
  <div class="filterDiv"> --}}
      <div class="comments__filters">
          <div class="comments__filter">
              <span id="orderFilterLabel">Order By</span>
              <select  name="filter_option"  class="reviewFilterDropdown">
               <option value="Newest">
                   Newest
               </option>
               <option value="Oldest">
                   Oldest
               </option>
               <option value="Popular">
                   Popular
               </option>
           </select>
       </div>  
     </div>
         {{-- <div class="comments__comment" id="answer484840"> --}}
    <div class="filterDiv">
    <div class="appendDiv"></div>
    @if(count($meal_detail->mealReview) > 0)
      @foreach ($meal_detail->mealReview->reverse() as $item)
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
                        <li><a class="comments__action comment_reply reply-Review-{{$item['id']}}"  data-id="{{$item['id']}}" aria-label="Reply to comment by keithcancook" href="javascript:void(0);">Reply</a></li>
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
                                        <li><a class="comments__action comment_reply reply-Reply-{{$reply_review['id']}}" data-id="{{$reply_review['id']}}" aria-label="Reply to comment by inpatskitchen" href="javascript:void(0);">Reply</a></li>
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
                                                    <textarea id="WriteaReply"  class="css-1oh5j1q forfocuss Reply-reply-{{$reply_review->id}}"></textarea>
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
    </div>    {{-- add div for filter --}}

      <div class="css-512ip5">
          <div class="pagination__text css-15nfcbi">Showing <span class="totalReview" data-old="{{$totalReview}}">{{$totalReview}}</span>  out of <span class="totalReview" data-old="{{$totalReview}}">{{$totalReview}}</span> Reviews</div>
          <a type="button" class="css-dz10t4" style="background:transparent;border:0" href="#ReviewsPanel"><span class="icon__arrow icon__arrow--up"></span>Back to top</a>
      </div>
  
  </section>

  <script type="text/javascript">
    
    $(document).ready(function() { 
    //  $('.submit-review').click(function () {
    //    $("#LeaveaReview").parent().parent().parent().parent().removeClass("leave-review");
    //    $('.rating-popup').show();
    //  });

     $('.skip-btn').click(function () {
        $('.leave-review').css('min-height','50px');
       $('.rating-popup').hide();
     });

     $('.commentStarRating').click(function(){
         var rating = $(this).attr('data-val');
         $('.save-comment-rating-val').attr('value',rating);

     })

     $('.submit-rating').click(function () {
        var star = $('.save-comment-rating-val').val();
           var recipe_id = $('.recipe_id').val();
          var formData = {
            star: star,
            recipe_id:recipe_id
           }
          $.post(public_url+'meal-planner/post-rating', {formData}, function(response){ 
           console.log('data----', response);
           if(response.status == "success"){
            $(".thanks-popup").css("display", "inline-flex");
            //    $('.thanks-popup').show();
            setTimeout(function(){
               $('.thanks-popup').hide();
              }, 1500);
             $('.rating-popup').hide();
           }       
        });
     });

     $(".reviewFilterDropdown").change(function () {                            
            var option= $('select[name=filter_option]').val() // Here we can get the value of selected item
            var recipe_id = $('.recipe_id').val();
          
            var formData = {
                   option: option,
                    recipe_id:recipe_id
                }
            $.post(public_url+'meal-planner/review-filter', {formData}, function(response){ 
            console.log('data----', response);
            if(response.status == "success"){
                $('.filterDiv').html(response.review);
                $('.reply-textbox').hide();
             
                // $('.comment_reply').trigger('click');  
                 
              }       
          });
       });

     $( ".rating__star rating__star--btns" ).mouseover(function() {
     $( ".rating-label" ).append( "<div>1 Star: Needs a do-over.</div>" );
        });

   }); 
  </script>