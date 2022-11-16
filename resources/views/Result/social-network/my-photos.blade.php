<div class="col-md-12 bg-white my-photo-div">
    <div class="">
       <br>
       <!-- <h2><i class="fa fa-comments-o"></i> Send Messages</h2> -->
       <h2><i class="fa fa-picture-o"></i> My Photos </h2>
       <hr>
    </div>
    <div class="app-core ">
       <div class="joms-plugin__body">
          <ul class="joms-list--photos">
             @if ($my_image_video_post->whereNotNull('social_post_image')->count() > 0)
             @foreach($my_image_video_post->whereNotNull('social_post_image')->toArray() as $key => $post_image)
             @foreach($post_image['social_post_image'] as $social_post_image)
               @php
                  $user_id = Auth::user()->account_id;
                  $post = App\SocialPost::with(['social_post_image','social_post_video','client'=>function($q){
                        $q->select('id','firstname','lastname','profilepic');
                     }
                     // ,'goal_client'=>function($q){
                     //     $q->select('id','firstname','lastname','profilepic');
                     // },
                   ])
                  ->where('id',$social_post_image['post_id'])
                  ->first();
               @endphp
             <li class="joms-list__item edit-post-div my-photo-{{$social_post_image['post_id']}}" >
                <a data-fancybox="gallery" href="{{asset('uploads/posts/'.$social_post_image['image_path'])}}">
                <img class="show-full-image" src="{{asset('uploads/posts/'.$social_post_image['image_path'])}}" alt="image">
                </a>
                @if($post['client_id'] == $user_id)
                <a href="javascript:;" class="edit-delete-post dropdown-toggle" data-toggle="dropdown">
                   <i class="fa fa-pencil"></i>
                   <ul class="dropdown-menu dropdown-dark">
                      <li> <a href="javascript:;" onclick="showEditPostPhoto({{ $social_post_image['post_id'] }})"><i class="fa fa-edit"></i> Edit Post</a></li>  
                      <li><a href="javascript:;" onclick="deletePost({{ $social_post_image['post_id'] }})"><i class="fa fa-trash"></i> Delete Post</a></li>  
                   </ul>
                </a>
                @endif
             </li>
           

             <!--edit popup  Modal -->
             <div class="modal fade" id="my-photo-edit-post-{{$post->id}}" role="dialog">
                <form id="my-photo-form-update-post-{{$post->id}}"  method="POST" action="{{ route('post.update_post', $post->id) }}" enctype="multipart/form-data">
                   {{ csrf_field() }}
                   <div class="modal-dialog modal-md">
                      <div class="modal-content">
                         <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Edit Post</h4>
                         </div>
                         <div class="modal-body">
                            <div class="joms-app--wrapper">
                               <div class="joms-stream__wrapper">
                                  <div class="joms-stream__container">
                                     <div class="joms-stream joms-embedly--left joms-js--stream joms-js--stream-112" >
                                        <div class="joms-stream__header">
                                           <div class="joms-avatar--stream ">
                                              <a href="javascript:void(0)">
                                              <img data-author="906" src="{{asset('uploads/thumb_'.$post['client']['profilepic'])}}" onerror="this.onerror=null;this.src='{{asset('assets/images/no-image-icon.jpg')}}';" alt="user profile image">
                                              </a>
                                           </div>
                                           <div class="joms-stream__meta row">
                                              <div class="col-md-12 pl-0">
                                                 <a href="javascript:void(0)" data-joms-username="" class="joms-stream__user"> {{$post['client']['firstname']}} {{$post['client']['lastname']}}</a>
                                                 <a href="javascript:void(0)">
                                                 <span class="joms-stream__time">
                                                 <small> {{ $post->created_at->diffForHumans() }} </small>
                                                 </span>
                                                 </a>
                                              </div>
                                           </div>
                                        </div>
                                        <div class="joms-stream__body">
                                           <div class="social-scroll">
                                              <div style="position:relative;">
                                                 <div class="row">
                                                    <div class="col-md-12">
                                                       <article class="joms-stream-fetch-content" style="margin-left:0; padding-top:0;margin-bottom: 15px;">
                                                          <textarea name="content" class="form-control" rows="6">{{$post['content']}}</textarea>
                                                       </article>
                                                    </div>
                                                    <div class="col-md-12 text-center bg-ornge edit-post" id="my-photo-post-image-{{ $post->id }}">
                                                       @if($post['social_post_image']->count() > 0)       
                                                       @foreach($post['social_post_image'] as $image)
                                                       <div id="my-photo-multiple-post-image-{{ $image->id }}">
                                                          <button type="button" class="close remove-img"  onclick="removeImage({{$image->id}} ,{{$image->post_id }})">×</button>
                                                          <img  class="joms-stream-thumb" src="{{asset('uploads/posts/'.$image['image_path'])}}"  alt="image for post">
                                                       </div>
                                                       @endforeach
                                                       @endif
                                                    </div>
                                                    {{-- <div class="col-md-12 text-center bg-ornge edit-post" id="my-photo-post-video-{{ $post->id }}">
                                                       @if($post['social_post_video']['video_path'])
                                                       <div id="my-photo-remove-update-video-{{ $post->id }}">
                                                          <button type="button" class="close remove-img"  onclick="removeVideo({{ $post->id }})">×</button>
                                                          <video id="my-photo-update-video-{{$post->id}}" class="joms-stream-thumb" width="100%" controls="" playsinline="" src="{{asset('uploads/posts/'.$post['social_post_video']['video_path'])}}" >
                                                       </div>
                                                       @endif
                                                    </div> --}}
                                                    <div class="bg-ornge col-md-12 p-0 add-new-post">
                                                       <button type="button" class="close remove-img delete-image-btn hide" onclick="removeEditPostImage({{ $post->id }})">×</button>
                                                       {{-- <button type="button" class="close remove-img remove-post-video hide" onclick="removeEditPostVideo({{ $post->id }})">×</button> --}}
                                                       <span class="more5img"></span>
                                                       <div class="update-post-image"> </div>
                                                       <video id="my-photo-update-video-{{$post->id}}" width="100%" controls="" playsinline="" src="" class="hidden"></video>
                                                    </div>
                                                 </div>
                                              </div>
                                           </div>
                                        </div>
                                     </div>
                                     <div class="form-group post-section row">
                                        <div class="col-md-3 col-xs-6">
                                           <input type="file" accept="image/*" class="image-input add-more-image" id="my-photo-add-more-image-{{$post->id}}"  data-post-id="{{$post->id}}" name="images[]" multiple> 
                                           <br>
                                           <button type="button" class="btn btn-default btn-add-image btn-sm"  onclick="uploadUpdatePostImagePhoto({{$post->id}})">
                                           <i class="fa fa-image"></i> Add Image
                                           </button>
                                        </div>
                                        {{-- <div class="col-md-3 col-xs-6">
                                           <input type="file" accept="video/*" class="video-input update_video" data-post-id="{{$post->id}}" name="video">
                                           <br>
                                           <button type="button" class="btn btn-default btn-add-video btn-sm"  onclick="uploadUpdatePostVideoPhoto({{$post->id}})">
                                           <i class="fa fa-video"></i> Add Video
                                           </button>
                                        </div> --}}
                                        <br>
                                        <div class="col-md-6 col-xs-12 text-align-right text-centers">
                                           <button class="joms-focus__button--add update-btn" type="submit">
                                           Update</button>
                                        </div>
                                     </div>
                                  </div>
                               </div>
                            </div>
                         </div>
                      </div>
                   </div>
                </form>
             </div>
             <!--edit popup  Modal -->

             @endforeach
             @endforeach
             @else
             No Photo
             @endif
          </ul>
       </div>
    </div>
 </div>
