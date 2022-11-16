@extends('Result.masters.app')

@section('page-title')
@php
	use App\SocialFriend;
@endphp
@stop
@section('required-styles')

{!! Html::style('result/css/social-style.css') !!}
{!! Html::style('result/css/legacy-grid.css') !!}
{!! Html::style('result/css/autocomplete.css') !!}
{!! Html::style('result/plugins/dropzone/cropper.css') !!}
{!! Html::style('result/plugins/Jcrop/css/jquery.Jcrop.min.css?v='.time()) !!}
{!! Html::style('result/css/emoji.css') !!}
{!! Html::style('/assets/image-uploader/image-uploader.min.css') !!}
<style type="text/css">
	select{
		-webkit-appearance: auto;
		-moz-appearance: initial;
		appearance: auto;
		padding-left: 4px !important;
	}
	.modal:before {
		display: initial !important;
	}

	/* image crop */

.add-css-for-dp .cropper-crop-box, .add-css-for-dp .cropper-view-box {
    border-radius: 50%;
}
.add-css-for-dp .cropper-view-box {
    box-shadow: 0 0 0 1px #39f;
    outline: 0;
}
.add-css-for-dp .cropper-face {
  background-color:inherit !important;
}

.add-css-for-dp .cropper-dashed, .cropper-line {
  display:none !important;
}
.add-css-for-dp .cropper-view-box {
  outline:inherit !important;
}

.add-css-for-dp .cropper-point.point-se {
  top: calc(85% + 1px);
  right: 14%;
}
.add-css-for-dp .cropper-point.point-sw {
  top: calc(85% + 1px);
  left: 14%;
}
.add-css-for-dp .cropper-point.point-nw {
  top: calc(15% - 5px);
  left: 14%;
}
.add-css-for-dp .cropper-point.point-ne {
  top: calc(15% - 5px);
  right: 14%;
}


.cropper-modal{ 
	background: transparent !important;
}
.cropper-bg{
	background-image: none !important;
} 
/* end crop */
</style>
@stop
@section('content')
@php
	$send_request = SocialFriend::where('client_id',Auth::user()->account_id)->where('status','Accepted')->pluck('added_client_id')->toArray();
	$recieve_request = SocialFriend::where('added_client_id',Auth::user()->account_id)->where('status','Accepted')->pluck('client_id')->toArray();
	$friends = array_merge($send_request,$recieve_request,[Auth::user()->account_id]);
	if($client->privacy == 'Only friends'){
		if(in_array($client->id, $friends)){
			$detail_div = '';
			$privacy_div = 'hidden';
			$show_image = 'show-full-image';
		}else{
			$detail_div = 'hidden';
			$privacy_div = '';
			$show_image = '';
		}
	}else{
		$detail_div = '';
		$privacy_div = 'hidden';
		$show_image = 'show-full-image';
	}
@endphp
{{-- loader --}}
<div id="waitingShield" class="text-center hidden">
    <div>
        <i class="fa fa-circle-o-notch"></i>
    </div>
  </div>
{{-- loader --}}

<div class="joms-focus">
	<div class="row">
		
	</div>
</div>
<div class="t3-content col-xs-12 col-sm-12 col-md-12 p-0">

	<div class="t3-content-block">

		<div id="community-wrap" class="joms-reaction--on jomsocial-wrapper on-socialize ltr cProfile">
			<div class="jomsocial">
				<div class="joms-body">
						{{-- scroll up --}}
						<a id="top"></a>
						{{-- end --}}
					<!-- begin: focus area -->
					<div class="joms-focus">
						<div class="joms-focus__cover">
							<div class="joms-focus__cover-image joms-js--cover-image">
								<img class="preview_cover_image {{ $show_image }}" data-image="@if(!empty($client->cover_image)){{asset('uploads/thumb_'.$client->cover_image)}}@else{{asset('assets/images/bg.jpg')}}@endif" @if(!empty($client->cover_image)) src="{{asset('uploads/thumb_'.$client->cover_image)}}" @else src="{{asset('assets/images/bg.jpg')}}" @endif alt="{{ $client->firstname }}">
								{{-- <img src="{{asset('assets/images/bg.jpg')}}" alt="Jen" id="preview_cover_image"> --}}
							</div>
							<div class="joms-focus__header">
								<div class="joms-avatar--focus">
									<a href="javascript:void(0)">
										<img class="preview_profile_image {{ $show_image }}" data-image="@if(!empty($client->profilepic)){{asset('uploads/thumb_'.$client->profilepic)}}@else{{asset('result/images/noimage.gif')}}@endif" @if(!empty($client->profilepic)) src="{{asset('uploads/thumb_'.$client->profilepic)}}" @else src="{{asset('result/images/noimage.gif')}}" @endif alt="{{ $client->firstname }}" onerror="this.onerror=null;this.src='{{asset('assets/images/no-image-icon.jpg')}}';">
									</a>
									@if(Auth::user()->account_id == $client->id)
									<div class="upload-profile-img upload-group">
										<input type="hidden" name="prePhotoName" value="{{ $client->profilepic }}">
										<input type="hidden" name="entityId" value="{{$client->id}}">
										<input type="hidden" name="saveUrl" value="client/photo/save">
										<input type="hidden" name="photoHelper" value="client">
										<input type="hidden" name="cropSelector" value="square">
										<input type="file" accept="image/*" onchange="fileSelectHandlerNew(this)" class="image-input" data-client-id="{{ $client->id }}" name="profile_image" id="profile_image">
										<button type="button" class="btn joms-focus__button--add">
											{{-- onclick="uploadProfileImage()" --}}
											<i class="fa fa-plus"></i><br> Upload<br> image
										</button>
									</div>
									@endif
								</div>
							
								<div class="joms-focus__title">
									<h2>{{ $client->firstname }} {{ $client->lastname }}</h2>
									<div class="joms-focus__info--desktop">
										{{-- FromDenver,<br>
											Colorado,<br>
											United States --}}
											{{ $client->address1 }}
										</div>
									</div>
									<div class="joms-focus__actions__wrapper">
                              <div class="joms-focus__actions--desktop">
                                 <!-- Friending buton -->
								@if(Auth::user()->account_id == $client->id)
									<form id="form-upload-cover" enctype="multipart/form-data">
										<div class="add-cover-img upload-group">
											<input type="hidden" name="prePhotoName" value="{{ $client->profilepic }}">
											<input type="hidden" name="entityId" value="{{$client->id}}">
											<input type="hidden" name="saveUrl" value="client/photo/save">
											<input type="hidden" name="photoHelper" value="client">
											<input type="hidden" name="cover_image" value="cover_image">
											<input type="hidden" name="cropSelector" value="square">
											<input type="file" accept="image/*" class="image-input cover_input" onchange="fileSelectHandlerNew(this)" data-client-id="{{ $client->id }}" name="cover_image" id="cover_image">
											<button type="button" class="btn joms-focus__button--add" >
												{{-- onclick="uploadCoverImage()" --}}
												<i class="fa fa-upload"></i> Change Cover
											</button>
										</div>
									</form>
								@endif
                                 @if(Auth::user()->account_id != $client->id)
                                 @if(!empty($search_id))
                                 @if ($client->social_friend->status == 'No Action')
                                 {{-- <a href="javascript:void(0)" class="joms-focus__button--add" data-client-id="{{ $client->id }}">
                                    Requested</a> --}}
                                 <a href="javascript:void(0)" class="joms-focus__button--add cancel-friend" data-message="Are you sure you want to cancel this friend request?" data-client-id="{{ $client->id }}">
                                    Cancel Request</a>
                                 @elseif($client->social_friend->status == 'Rejected' || $client->social_friend->status == 'Unfriend')
                                 <a href="javascript:void(0)" class="joms-focus__button--add add-friend" data-client-id="{{ $client->id }}">
                                    Add as Friend    </a>
                                 @elseif($client->social_friend->status == 'Accepted')
                                 <a href="javascript:void(0)" class="joms-focus__button--message" onclick="showChat({{ $client->id }})" >
                                    Send Message</a>
								@else
								<a href="javascript:void(0)" class="joms-focus__button--add add-friend" data-client-id="{{ $client->id }}">
                                    Add as Friend    </a>
                                @endif
                                 
                                 @elseif(!empty($client_id))
                                 @if ($client->recieve_friend->status == 'No Action')
                                 <a href="javascript:void(0)" class="joms-focus__button--add confirm-friend" data-client-id="{{ $client->id }}">
                                    Confirm</a>
                                 <a href="javascript:void(0)" class="joms-focus__button--add reject-friend" data-message="Are you sure you want to reject this friend request?" data-client-id="{{ $client->id }}">
                                    Cancel</a>
                                 @elseif($client->recieve_friend->status == 'Rejected' || $client->recieve_friend->status == 'Unfriend')
                                 <a href="javascript:void(0)" class="joms-focus__button--add add-friend" data-client-id="{{ $client->id }}">
                                    Add as Friend    </a>
                                 @elseif($client->recieve_friend->status == 'Accepted')
                                 <a href="javascript:void(0)" class="joms-focus__button--message" onclick="showChat({{ $client->id }})" >
                                    Send Message</a>
                                 @endif
                                 @else
                                 <a href="javascript:void(0)" class="joms-focus__button--add add-friend" data-client-id="{{ $client->id }}">
                                    Add as Friend    </a>
                                 @endif
                                 @endif
                                 <!-- Send Message button -->
                                 {{-- @if(Auth::user()->account_id == $client->id)
                                 <a href="javascript:void(0)" class="joms-focus__button--message">
                                 Send Message</a>
                                 @endif --}}
                              </div>
                           </div>

								</div>
							</div>
							{{-- <div class="joms-focus__actions">
								<!-- Friending buton -->
								<a href="javascript:" class="joms-focus__button--add">
								Add as Friend                    </a>
								<!-- Send Message button -->
								<a href="javascript:" class="joms-focus__button--message">
								Send Message                </a>
							</div>
							<div class="joms-focus__info">
								FromDenver,
								Colorado,
								United States    
							</div> --}}
							
						
						</div>
						<div class="middle-wiz-section">
						<div class="middle-wiz">
						<div class="privacy-div {{ $privacy_div }} text-center">
							<h4>{{ $client->firstname }} profile is private.Please send request and you will be able to view profile once friend request is accepted. </h4>
							<div>
						<!-- 	<img style="height: 120px;" class="preview_profile_image show-full-image" data-image="@if(!empty($client->profilepic)){{asset('uploads/thumb_'.$client->profilepic)}}@else{{asset('result/images/noimage.gif')}}@endif" @if(!empty($client->profilepic)) src="{{asset('uploads/thumb_'.$client->profilepic)}}" @else src="{{asset('result/images/noimage.gif')}}" @endif alt="{{ $client->firstname }}"> -->
							<img src="{{asset('assets/images/lock-icon.png')}}">
						</div>
						</div>
						<div class="menus-with-content">
						
											<ul class="joms-focus__link {{ $detail_div }}">
									@if($client->id != Auth::user()->account_id)
									<li class="half">
										<a class="" href="{{ url('social/home') }}">My Profile</a>
									</li>
									@endif
								<li class="full"><a href="javascript:void(0)" class="my-friend">
									Friend <span class="joms-text--light">{{ count($data) }}</span> </a>
								</li>
								@if(Auth::user()->account_id == $client->id)
								<li class="full"><a href="javascript:void(0)" id="message-link">
									Messages <span class="joms-text--light"></span> </a>
								</li>
								@endif
								<li class="half"><a href="javascript:void(0)" class="my-photo">Photos <span class="joms-text--light">{{ $total_image }}</span></a></li>
								<li class="half"><a href="javascript:void(0)" class="my-video">Videos <span class="joms-text--light">{{ $my_image_video_post->whereNotNull('social_post_video')->count() }}</span></a></li>
								<li class="half">
									<a href="{{ route('social.index',['tab' => 'my_post']) }}">
										My Post
									</a>
								</li>
								<li class="half">
									<a href="{{ route('social.index') }}">
										Live Feed
									</a>
								
								</li>
								@if(Auth::user()->account_id == $client->id)

								<li class="half privacy-tab">
									<div class="dropdown delete-dropdown">
										<a href="javascript:;" class="dropdown-toggle for-delete" data-toggle="dropdown">
											Privacy
											</a>
											<ul class="dropdown-menu"><i class="fa fa-caret-up" aria-hidden="true"></i>
												<li class="user-privacy" data-privacy="Only friends" data-client-id="{{ $client->id }}"><div class="form-group">
													<label class="container_radio version_2">Only Friends
														<input type="radio" name="chooseGoal" @if($client->privacy == 'Only friends')checked @endif>
														<span class="checkmark"></span>
													</label>
												</div>
											</li>   
											<li class="user-privacy" data-privacy="Anyone" data-client-id="{{ $client->id }}">
												<div class="form-group">
													<label class="container_radio version_2">Anyone
														<input type="radio" name="chooseGoal" @if($client->privacy == 'Anyone')checked @endif>
														<span class="checkmark"></span>
													</label>
												</div>
											</li>     
										</ul>
									
								</div>
							</li>
            
       
								@endif
								<li class="half search-for-frnd">
									<div class="">
										<form action="{{ url('social/search/friend') }}" method="post">
											@csrf
											<div class=" mb-3 search-btn">
												<input class="form-control search-suggestags-input"  type="text" name="client_id" value="" placeholder="Search for Friend">
												
												<div class="search-popup hidden">
													
												</div>
											</div>
										</form>
									</div>
								</li>
							</ul>
						
						<div class="main-post-div {{ $detail_div }}">
			
						<div class="joms-sidebar">
							<div class="joms-module__wrapper">
								<ul class="nav nav-tabs tab-padding tab-space-3 tab-blue epic-mobile-tab">
									<li class="active">
										<a data-toggle="tab" href="#my_friends">
											My Friends <span class="text-success text-bold friend_count">  {{ count($data) }} </span>
										</a>
									</li>
									@if(Auth::user()->account_id == $client->id)
									<li>
										<a data-toggle="tab" href="#friend_requests">
											Friend Requests <span class="text-danger text-bold request-count"> {{ count($client->request_recieve) }} </span>
										</a>
									</li>
									<li>
										<a data-toggle="tab" href="#send_requests">
											Send Requests <span class="text-warning text-bold send-count"> {{ count($client->request_send) }} </span>
										</a>
									</li>
									@endif
									{{-- <li>
										<a data-toggle="tab" href="#my_groups">
											My Groups
										</a>
									</li> --}}
								</ul>
								<div class="tab-content">
									<div id="my_friends" class="tab-pane in active" style="position:relative">
										<div class="joms-tab__content  app-core ">
											@if(Auth::user()->account_id == $client->id)
											<div class="form-group">
												<div class="input-group">
													<input type="text" class="form-control search-from-all-my-friends" placeholder="Search friends">
												<!-- 	<div class="input-group-addon"><i class="fa fa-search"></i></div> -->
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
											<div class="joms-stream__header friend-section" id="send-friend-{{ $client_data->id }}">
												<div class="joms-avatar--stream ">
													<a @if($client_data->about_me != 'epichq') href="{{ url('social/my/friend/'.$client_data->id) }}" @endif>
														<img @if(!empty($client_data->profilepic)) src="{{asset('uploads/thumb_'.$client_data->profilepic)}}" @else src="{{asset('result/images/noimage.gif')}}" @endif alt="{{ $client_data->firstname }}" onerror="this.onerror=null;this.src='{{asset('assets/images/no-image-icon.jpg')}}';">
													</a>
												</div>
												<div class="joms-stream__meta">
													<div class="friend-list">
													
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
																<div>
																	<a href="javascript:void(0)" onclick="showChat({{ $client_data->id }})"><i class="fa fa-comments" style="font-size: 12px;"></i> 
																		Message
																		
																	</a>
																</div>
															</li>
															@if($client_data->about_me != 'epichq')
																<li>
																	<div>
																		<a href="javascript:void(0)" class="cancel-friend unfriend" data-message="Are you sure you want to Unfriend this friend?" data-client-id="{{ $client_data->id }}">
																			<i class="fa fa-user-times" style="font-size: 12px;"></i> 
																				Unfriend
																			
																		</a>
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
						<div id="friend_requests" class="tab-pane" style="position:relative">
							<div class="joms-tab__content  app-core friend_requests">
								@if(Auth::user()->account_id == $client->id)
								<div class="form-group">
									<div class="input-group">
										<input type="text" class="form-control search-from-requested-friends" placeholder="Search friends" autocomplete="off">
										<!-- <div class="input-group-addon"><i class="fa fa-search"></i></div> -->
									</div>
								</div>
								@endif
								<div class="" id="filter-requested-friends">
								<div class="row">
									<div class="col-md-8">
										<strong>Friend Request <span class="request-count"> {{ count($client->request_recieve) }} </span></strong>
									</div>
									{{-- <div class="col-md-4 text-align-right">
										<a href="javascript:void(0)"><strong>Show all</strong></a>
									</div> --}}
								</div>
								
								@if(isset($client->request_recieve))
								@foreach($client->request_recieve as $client_val)
										<div class="joms-stream__header friend-section" id="request-friend-{{$client_val->client_id}}">
											<div class="joms-avatar--stream ">
												<a href="@if(Auth::user()->account_id == $client->id){{ url('social/my/friend/'.$client_val->clients_recieve_request->id) }}@else javascript:void(0) @endif">
										<img @if(!empty($client_val->clients_recieve_request->profilepic)) src="{{asset('uploads/thumb_'.$client_val->clients_recieve_request->profilepic)}}" @else src="{{asset('result/images/noimage.gif')}}" @endif alt="{{ $client_val->clients_recieve_request->firstname }}" onerror="this.onerror=null;this.src='{{asset('assets/images/no-image-icon.jpg')}}';">

									</a>
											</div>
											<div class="joms-stream__meta">
												<div class="friend-list">
													<a href="@if(Auth::user()->account_id == $client->id){{ url('social/my/friend/'.$client_val->clients_recieve_request->id) }}@else javascript:void(0) @endif" data-joms-username="" class="joms-stream__user">{{ $client_val->clients_recieve_request->firstname }} {{ $client_val->clients_recieve_request->lastname }}</a>
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
								
							</div>
						</div>
						</div>
                  		<div id="send_requests" class="tab-pane" style="position:relative">
							<div class="joms-tab__content  app-core friend_requests">
								@if(Auth::user()->account_id == $client->id)
								<div class="form-group">
									<div class="input-group">
										<input type="text" class="form-control search-from-sended-friends" placeholder="Search friends" autocomplete="off">
										<!-- <div class="input-group-addon"><i class="fa fa-search"></i></div> -->
									</div>
								</div>
								@endif
								<div class="" id="filter-sended-friends">
								<div class="row">
									<div class="col-md-8">
										<strong>Send Request <span class="send-count">{{ count($client->request_send) }} </span></strong>
									</div>
									{{-- <div class="col-md-4 text-align-right">
										<a href="javascript:void(0)"><strong>Show all</strong></a>
									</div> --}}
								</div>
								
								@if(isset($client->request_send))
								@foreach($client->request_send as $client_value)
										<div class="joms-stream__header friend-section" id="send-friend-{{$client_value->added_client_id}}">
											<div class="joms-avatar--stream ">
												<a href="@if(Auth::user()->account_id == $client->id){{ url('social/my/friend/'.$client_value->clients->id) }}@else javascript:void(0) @endif">
										          <img @if(!empty($client_value->clients->profilepic)) src="{{asset('uploads/thumb_'.$client_value->clients->profilepic)}}" @else src="{{asset('result/images/noimage.gif')}}" @endif alt="{{ $client_value->clients->firstname }}" onerror="this.onerror=null;this.src='{{asset('assets/images/no-image-icon.jpg')}}';">
									
								            	</a>
											</div>
											<div class="joms-stream__meta">
												<div class="friend-list">
													<a href="@if(Auth::user()->account_id == $client->id){{ url('social/my/friend/'.$client_value->clients->id) }}@else javascript:void(0) @endif" data-joms-username="" class="joms-stream__user">{{ $client_value->clients->firstname }} {{ $client_value->clients->lastname }}</a>
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
												{{-- <button class="btn btn-primary">Requested</button> --}}
												<button class="btn btn-default cancel-friend" data-message="Are you sure you want to cancel this friend request?" data-client-id="{{ $client_value->added_client_id }}">Cancel Request</button>
											</div>
											</div>
											
										</div>
								@endforeach
								@endif
								
							</div>
						</div>
						</div>
						{{-- <div id="my_groups" class="tab-pane">
							<div class="joms-tab__content  app-core ">
								<div class="joms-plugin__body">
									<div class="joms-stream__header">
										<div class="joms-avatar--stream">
											<a href="javascript:void(0)">
												<img src="https://js-main.demo.jomsocial.com/components/com_community/assets/group_thumb.png" alt="Test">
											</a>
										</div>
										<div class="joms-stream__meta">
											<a class="joms-text--title" href="javascript:void(0)">
											Test    </a>
											<a href="javascript:void(0)" class="joms-block"><small>
											1 member</small></a>
										</div>

									</div>
								</div>
							</div>
						</div> --}}
					</div>
				</div>
				<div class="js_profile_side_top"></div>
				<ul class="nav nav-tabs tab-padding tab-space-3 tab-blue epic-mobile-tab">
					<li class="active">
						<a data-toggle="tab" href="#my_photos">
							My photos
						</a>
					</li>
					<li>
						<a data-toggle="tab" href="#my_videos">
							My Videos
						</a>
					</li>
				</ul>
				<div class="tab-content">
					<div id="my_photos" class="tab-pane in active" style="position:relative">
						<div class="joms-tab__content  app-core ">
							<div class="joms-plugin__body">
								<ul class="joms-list--photos">
									@if ($my_image_video_post->where('social_post_image','!=','[]')->count() > 0)
										@foreach($my_image_video_post->where('social_post_image','!=','[]')->toArray() as $key => $post)
											@foreach($post['social_post_image'] as $social_post_image)
												@if(!empty($social_post_image['image_path']))
													<li class="joms-list__item">

														{{-- <a href="javascript:">
															<img class="show-full-image async"  data-image="{{asset('uploads/posts/'.$social_post_image['image_path'])}}"  alt="image"> --}}
															{{-- <img class="show-full-image async"  data-image="{{asset('uploads/posts/'.$social_post_image['image_path'])}}" src="{{asset('uploads/posts/'.$social_post_image['image_path'])}}" alt="image"> --}}

														<a data-fancybox="gallery" href="{{asset('uploads/posts/'.$social_post_image['image_path'])}}">
															<img class="show-full-image"  src="{{asset('uploads/posts/'.$social_post_image['image_path'])}}" alt="image" >

														</a>
													</li>
												@endif
											@endforeach
										@endforeach
									@else
										No Photo
									@endif
									
								</ul>
							</div>
						</div>
					</div>
					<div id="my_videos" class="tab-pane">
						<div class="joms-tab__content  app-core ">
							<div class="joms-plugin__body">
								<!--DEFLIMIT 12 USERPARAMLIMIT 12-->
								<ul class="joms-list--half clearfix">
									@if ($my_image_video_post->whereNotNull('social_post_video')->count() > 0)
										@foreach($my_image_video_post->whereNotNull('social_post_video')->toArray() as $key => $post)
											<li class="joms-list__item">
												<a href="javascript:">
													<video class="joms-stream-thumb" width="100%" id="post_video" controls="" playsinline="" src="{{asset('uploads/posts/'.$post['social_post_video']['video_path'])}}" >
												</a>
											</li>
										@endforeach
									@else
										No video
									@endif
								</ul>
							</div>
						</div>
					</div>
				</div>
				<div class="js_profile_side_bottom"></div>
			</div>
			<div class="joms-main">
				<div data-ui-object="frontpage-main">
					<div class="joms-middlezone" data-ui-object="joms-tabs">
						
							<ul class="nav nav-tabs tab-padding tab-space-3 tab-blue epic-mobile-tab">
								<li class="active">
									<a data-toggle="tab" href="#stream">
										Stream
									</a>
								</li>
								<li>
									<a data-toggle="tab" href="#about_me">
										About me
									</a>
								</li>
								{{-- @if(Auth::user()->account_id == $client->id)
								<li>
									<a data-toggle="tab" href="#post">
										Post
									</a>
								</li>
								@endif --}}
							</ul>
							<div class="tab-content">
							
								<div id="stream" class="tab-pane in active fbphotobox" style="position:relative">
									<div id="joms-app--feeds-special" class="joms-tab__content joms-tab__content--stream  app-core" style="display: block;">
										<div class="joms-gap"></div>
										<!-- end: .app-box-header -->
										<!-- begin: .app-box-content -->
										<div class="joms-app--wrapper">
											
											@if(Auth::user()->account_id == $client->id)
											<div class="joms-stream joms-embedly--left joms-js--stream joms-js--stream-112 " id="panel-post-{{ $post->id }}">
												<div class="joms-stream__header p-0">
													@include('Result.social-network.post.add-post')
												</div>
											</div>
											@endif
												
											<!-- begin: .joms-stream__wrapper -->
											<div class="joms-stream__wrapper">
												<!-- begin: .joms-stream__container -->
												<div class="joms-stream__container">
													<!-- ----------------start  --------------->
                                         @if(count( $posts))
											@foreach($posts as $key => $post)
									
											<input hidden value="{{$post->id}}" data-post-id="{{ $post->id }}" id="post-name-dev-{{$key}}"/> 
											 @include('Result.social-network.post.show-post')
										<!--edit popup  Modal -->
									  	      @include('Result.social-network.post.edit-post')
										<!-------------- end ----------- -->
                                            @endforeach

										 @else 
                                           <p style="text-align: center; color: red;"> No Posts Found </p>
									     @endif
                                          <!--------------post loop end ----------- -->
                                      </div>
                                      <!-- end: .joms-stream__container -->
                                  </div>
                                  <!-- end: .joms-stream__wrapper -->
                              </div>
                              <!-- end: .app-box-content -->
                          </div>
                      </div>

                      <!-- end: .app-box -->
                      <!-- begin: .app-box -->
                      <div id="about_me" class="tab-pane">
                      	<div class="joms-tab__content joms-tab__content--stream  app-core">
                      		<div class="joms-gap"></div>
                      		<!-- end: .app-box-header -->
                      		<!-- begin: .app-box-content -->
                      		<div class="joms-app--wrapper">
                      			<div class="app-box-content">
                      				@if ($client->id == Auth::user()->account_id)
                      				<div class="col-md-12  text-align-right">
                      					<a href="javascript:void(0)" class="edit-info edit-btn">
                      						<i class="fa fa-edit"></i>
                      					</a>
                      					<a href="javascript:void(0)"
                      					class="close-info edit-btn">
                      					<i class="fa fa-times"></i>
                      				</a>
                      			</div>
                      			@endif
                      			<ul class="joms-list__row joms-push edit-information-detail">
                      				<li>
                      					<h4 class="joms-text--title joms-text--bold">Basic
                      					Information</h4>
                      				</li>
                      				<li class="basic-info-detail">
                      					<h5 class="joms-text--light">Gender</h5>
                      					<span> {{ $client->gender }} </span>
                      				</li>
                      				<li class="basic-info-detail">
                      					<h5 class="joms-text--light">About me</h5>
                      					<span> {{ $client->about_me }}</span>
                      				</li>
                      				<li class="basic-info-detail">
                      					<h5 class="joms-text--light">Birthdate</h5>
                      					<span> {{ $client->birthday }} </span>
                      				</li>

                      			</ul>
                      			<ul class="joms-list__row joms-push edit-information-detail">
                      				<li>
                      					<h4 class="joms-text--title joms-text--bold"><br>Contact
                      					Information</h4>
                      				</li>
                      				{{-- <li>
                      					<h5 class="joms-text--light">State</h5>
                      					<span>
                      						<a href="javascript:void(0)"> Colorado </a></span>
                      					</li> --}}
                      					<li>
                      						<h5 class="joms-text--light">City / Town</h5>
                      						<span> {{ $client->address_city }} </span>
                      					</li>
                      					<li>
                      						<h5 class="joms-text--light">Country</h5>
                      						<span> {{ $client->country }} </span>
                      					</li>
                      				</ul>
                      				<div class="edit-information">
                      					<form action="{{ url('social/update-profile') }}" method="post">
                      						@csrf
                      						<input type="hidden" name="client_id" value="{{ $client->id }}">
                      						<ul class="joms-list__row joms-push">
                      							<li>
                      								<h4 class="joms-text--title joms-text--bold">
                      								Basic Information</h4>
                      							</li>
                      							{{-- <li class="basic-info-detail">
                      								<h5 class="joms-text--light">Birthdate</h5>
                      								<span>
                      								09. 03. 1983</span>
                      							</li> --}}

                      							<li class="basic-info-edit">
                      								<h5 class="joms-text--light">Gender</h5>
                      								<select class="form-control" name="gender">
                      									<option value="">Select</option>
                      									<option @if ($client->gender == 'Female') selected @endif
                      										value="Female">Female</option>
                      										<option @if ($client->gender == 'Male') selected @endif
                      											value="Male">Male</option>
                      										</select>

                      									</li>
                      									<li class="basic-info-edit">
                      										<h5 class="joms-text--light">About me</h5>
                      										<textarea class="form-control" name="about_me" rows="4">{{ $client->about_me }}</textarea>

                      									</li>
                      									<li class="basic-info-edit">
                      										<h5 class="joms-text--light">Birthdate</h5>
                      										<input type="date" name="birthday" value="@if (!empty($client->birthday)){{ $client->birthday }}@endif" class="form-control">
                      									</li>
                      								</ul>
                      								<ul class="joms-list__row joms-push">
                      									<li>
                      										<h4 class="joms-text--title joms-text--bold">
                      											<br>Contact Information</h4>
                      										</li>
                      										{{-- <li>
                      											<h5 class="joms-text--light">State</h5>
                      											<input type="date" name="" value="@if (!empty($client->birthday)){{ $client->birthday }}@endif" class="form-control">
                      											<select class="form-control">
                      												<option>Select</option>
                      											</select>
                      										</li> --}}
                      										<li>
                      											<h5 class="joms-text--light">City / Town</h5>
                      											<input type="text" class="form-control" name="address_city" value="{{ $client->address_city }}">
                      											{{-- <select class="form-control">
                      												<option>Select</option>
                      											</select> --}}
                      										</li>
                      										<li>
                      											<h5 class="joms-text--light">Country</h5>
                      											<input type="text" class="form-control" name="country" value="{{ $client->country }}">
                      											{{-- <select class="form-control">
                      												<option>Select</option>
                      											</select> --}}
                      										</li>
                      										<li>
                      											<button type="submit"
                      											class="joms-focus__button--add comment-btn">Submit</button>
                      										</li>
                      									</ul>
                      								</form>
                      							</div>
                      						</div>
                      						<div class="joms-module__footer">
                      						</div>
                      					</div>
                      					<!-- end: .app-box-content -->
                      				</div>
                      			</div>
                      			<!-- end: .app-box -->
                      			<!--------   add post  ------------------>
								  {{-- @include('Result.social-network.post.add-post') --}}
                    <!----------------   add post ------------------------>
                </div>
           		
        </div>
    </div>
</div>
</div>
<div class="main-post-div my-photo-div hidden">
	@include('Result.social-network.my-photos')
</div>
<div class="main-post-div my-video-div hidden">
	@include('Result.social-network.my-videos')
</div>
<div class="main-post-div my-friend-div hidden">
	@include('Result.social-network.my-friends')
</div>
</div>
<div class="contacts-section">
<div class="contact-heading d-flex">
	<h4>Contacts</h4> 
	

</div>
<div class="form-group col-md-9 col-xs-12 mt-10">
		{{-- <input type="text" name="" class="form-control search-from-all-my-friends" placeholder="search friends"> --}}
		<input type="text" name="" class="form-control search-from-all-contact" placeholder="search friends">

	</div>
	<div class="contact-list" id="contact_list">
	
			@include('Result.social-network.message.people_list')

		{{-- <a href="javascript:;">
			<div class="chat_list">   
				<div class="chat_people">
					<div class="chat_img">
						<img src="{{asset('assets/images/no-image-icon.jpg')}}" alt="user profile image">
					</div>
					<div class="chat_ib">
						<h5>Wendy White

						</h5>
						<span class="text-msg"><span class="text-msg-less">gfhtfgjtfg.</span>
                        </span>
                        <span class="chat_date">5 days ago </span>
					</div>
				</div>
			</div>
		</a> --}}

		
        
           

</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
  <!--show like  Modal -->
 
  @include('Result.social-network.post.like-popup')
  @include('Result.social-network.message.direct-message')

     <!--show like  Modal -->
  
	<!-------------- end ----------- -->
 <!--show-big-image Modal -->
  {{-- <button class="show-full-image">show-big-image</button>  --}}
  <div class="modal fade" id="show-big-image" role="dialog">
  	<div class="modal-dialog modal-md">
  		<div class="modal-content">       	
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          	<i class="fa fa-close"></i>
          </button>
  			<div class="modal-body">	
				<img id="full-image" class="img-responsive"> 
  			</div>
  		</div>
  	</div>
  </div>

  <!--Start: Extra field -->
<div class="modal fade" id="cropperModal" tabindex="-1" role="dialog" aria-labelledby="cropperModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" role="document" style="width: 70%;">
      <div class="modal-content">
        <div class="modal-header">
        	
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
          <h5 class="modal-title" id="cropperModalLabel">Cropper</h5>
        </div>
        <div class="modal-body">
            <input type="hidden" name="photoName">
          <div class="img-container">
		
            <img id="imageCrop" src="" alt="Picture" height="340" width="100%">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-success cropImg">Crop</button>
          <button type="button" class="btn btn-secondary saveImg" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
</div>

<!--End: Extra field -->
 <!--preview img popup-->
               <div class="modal fade" id="preview-big-image" role="dialog">
                  <div class="modal-dialog modal-md">
                     <div class="modal-content">         
                       <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i class="fa fa-close"></i>
                     </button>
                     <div class="modal-body">   
                        <img  id="chat-full-image"class="img-responsive" src=""> 
                     </div>
                  </div>
               </div>
            </div>
 <!--preview img popup-->
@endsection

@section('custom-script')
<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
{!! Html::script('result/js/social-network/post.js?v='.time()) !!}
{!! Html::script('result/js/autocomplete.js?v='.time()) !!}
{!! Html::script('result/social-network/social-network.js?v='.time()) !!}
{!! Html::script('result/plugins/dropzone/cropper.js') !!}
{!! Html::script('result/plugins/Jcrop/js/jquery.Jcrop.min.js') !!}
{!! Html::script('result/plugins/Jcrop/js/script.js') !!}
{!! Html::script('result/js/social-network/emoji.js?v='.time()) !!}
{!! Html::script('/assets/image-uploader/image-uploader.min.js?v='.time()) !!}

<script>

//	$(".show-full-image").click(function(){
//		$(this).attr('data-target','#show-big-image');
//		$(this).attr('data-toggle','modal');
//	 var image = $(this).data('image');
//		$("#full-image").attr('src',image)
//	})



	// var options = [
	// @foreach($all_clients as $key => $client)
	// { 'tag':'{{$client["name"]}}','value': '{{$client["id"]}}' },
	// @endforeach
	// ];

	// $('.autocomplete').amsifySuggestags({
	// 	type :'bootstrap',
	// 	suggestions: options,
	// 	whiteList:true,
	// 	defaultTagClass:'',
	// 	classes: [],
	// 	backgrounds: [],
	// 	colors: [],
	// 	tagLimit: 1

	// });

</script>
<script>
	$(document).ready(function(){
	
		// $(".comment").click(function(){
		// 	$(".comments-show-hide").toggle();;
		// });

		/* image load after loding page */
		  $('img.async').each(function(i, ele) {
                 $(ele).attr('src',$(ele).attr('data-image'));
            });

			/* video  */
		$('video.async').each(function(i, ele) {
                 $(ele).attr('src',$(ele).attr('data-video'));
            });
		/* end  */


      $('.input-images').imageUploader({
		extensions: ['.jpg','.jpeg','.png','.gif','.svg'],
         mimes: ['image/jpeg','image/png','image/gif','image/svg+xml'],
		 label: '',
		
	  });

	});
	

	$(".edit-information").hide();
	$(".close-info").hide();
	$(".edit-info").click(function(){
		$(".edit-information").show();
		$(".edit-information-detail").hide();
		$(".edit-info").hide();
		$(".close-info").show();
	});
	$(".close-info").click(function(){
		$(".edit-information").hide();
		$(".edit-information-detail").show();
		$(".edit-info").show();
		$(".close-info").hide();

	});
</script>
<!--photos section visibilty-->
<script>
	function photossection(){
	var divWidth= $(".joms-sidebar .joms-list--photos .joms-list__item").width();
$(".joms-sidebar .joms-list--photos .joms-list__item").css({"height": divWidth + 4});
$( ".my-photo" ).click(function() {
 
 $(".my-photo-div").removeClass( "hidden" );
 var divWidth1= $(".my-photo-div .joms-list--photos .joms-list__item").width();
$(".my-photo-div .joms-list--photos .joms-list__item").css({"height": divWidth1 + 4});
});
}
$(document).ready(function(){
photossection();
});
 window.onresize = function() {
 photossection();	
 };

</script>
@stop
