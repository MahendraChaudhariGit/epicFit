@extends('blank')

@section('page-title')
	{{ $class->cl_name }}
@stop

@section('content')
<div class="modal fade" id="configModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    &times;
                </button>
                <h4 class="modal-title">Panel Configuration</h4>
            </div>
            <div class="modal-body">
                Here will be a configuration form
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">
                    Close
                </button>
                <button type="button" class="btn btn-primary">
                    Save changes
                </button>
            </div>
        </div>
    </div>
</div>

<!-- start: Pic crop Model -->
@include('includes.partials.pic_crop_model')
<!-- end: Pic crop Model -->

<div class="row">
	<div class="col-sm-12">
		<div class="tabbable">
			<ul class="nav nav-tabs tab-padding tab-space-3 tab-blue epic-mobile-tab" id="myTab4">
				<li class="active">
					<a data-toggle="tab" href="#panel_overview">
						Overview
					</a>
				</li>              
			</ul>
			<div class="tab-content">
				<div id="panel_overview" class="tab-pane in active">
					<div class="row">
						<div class="col-sm-5 col-md-4">
							<div class="user-left">
								<div class="center">
									<h4>{{ $class->cl_name }}</h4>
									<div>
										<div class="user-image">
											<div class="thumbnail">
												<a href="{{ dpSrc($class->cl_logo) }}" data-lightbox="image-1" 
													>
												<img src="{{ dpSrc($class->cl_logo) }}" class="img-responsive classPreviewPics previewPics" id="profile-userpic-img" alt="{{ $class->cl_name }}" style="max-width: 120px !important;"></a>

												{{-- <img src="{{ dpSrc($class->cl_logo) }}" class="img-responsive classPreviewPics previewPics" id="profile-userpic-img" alt="{{ $class->cl_name }}">		                         --}}
											</div>
					                        <div class="form-group upload-group">
					                            <input type="hidden" name="prePhotoName" value="{{ $class->cl_logo }}">
					                            <input type="hidden" name="entityId" value="{{$class->cl_id}}">
					                            <input type="hidden" name="saveUrl" value="class/photo/save">
					                            <input type="hidden" name="photoHelper" value="class">
					                            <input type="hidden" name="cropSelector" value="">
					                            <div>
					                                <label class="btn btn-primary btn-file">
					                                    <span>Change Logo</span> <input type="file" class="hidden" onChange="fileSelectHandler(this)" accept="image/*">
					                                </label>
					                            </div>
					                        </div>
										</div>
									</div>
									<hr>
								</div>
								<table class="table table-condensed table-hover">
									<thead>
										<tr>
											<th colspan="3">General Information</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td>Category:</td>
											<td>
												 @if($class->cat)
												 	{{ $class->cat->clcat_value }}
												 @endif
											</td>
											<td><a href="#panel_edit_account" class="show-tab"><i class="fa fa-pencil edit-user-info"></i></a></td>
										</tr>
										<tr>
											<td>Name:</td>
											<td>{{ $class->cl_name }}</td>
											<td><a href="#panel_edit_account" class="show-tab"><i class="fa fa-pencil edit-user-info"></i></a></td>
										</tr>
                                        <tr>
											<td>Description:</td>
											<td><span class="">{{ $class->cl_description }}</span></td>
											<td><a href="#panel_edit_account" class="show-tab"><i class="fa fa-pencil edit-user-info"></i></a></td>
										</tr>
                                        <tr>
											<td>Colour:</td>
											<td>
												<div class="colorStrip">
													<div style="background-color:{!! $class->cl_colour !!}">&nbsp;</div>
												</div>
											</td>
											<td><a href="#panel_edit_account" class="show-tab"><i class="fa fa-pencil edit-user-info"></i></a></td>
										</tr>
										<tr>
											<td>Can client book this class online</td>
											<td>
												@if($class->cl_book_online)
						                            <span class="label label-info">Yes</span>
						                        @else
						                            <span class="label label-danger">No</span>
						                        @endif
						                    </td>
					                        <td></td>
										</tr>
                                        <tr>
											<td>Duration:</td>
											<td>{{$class->cl_duration}} min</td>
											<td><a href="#panel_edit_account" class="show-tab"><i class="fa fa-pencil edit-user-info"></i></a></td>
										</tr>
                                        <tr>
											<td>Capacity:</td>
											<td>{{$class->cl_capacity}}</td>
											<td><a href="#panel_edit_account" class="show-tab"><i class="fa fa-pencil edit-user-info"></i></a></td>
										</tr>
                                        <tr>
											<td>Staff:</td>
											<td>{{ implode(', ', $clStaffs) }}</td>
											<td><a href="#panel_edit_account" class="show-tab"><i class="fa fa-pencil edit-user-info"></i></a></td>
										</tr>
                                          <tr>
											<td>Price:</td>
											<td>${{ $class->cl_price}}</td>
											<td><a href="#panel_edit_account" class="show-tab"><i class="fa fa-pencil edit-user-info"></i></a></td>
										</tr>
                                        <tr>
											<td>Tax:</td>
											<td>{{ $class->cl_tax}}</td>
											<td><a href="#panel_edit_account" class="show-tab"><i class="fa fa-pencil edit-user-info"></i></a></td>
										</tr>
									</tbody>
								</table>
								<table class="table table-condensed table-hover">
									<thead>
										<tr>
											<th colspan="3">Padding information</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td>Padding and processing times</td>
											<td>
												@if($class->cl_pad_before)
						                            <span class="label label-info">Yes</span>
						                        @else
						                            <span class="label label-danger">No</span>
						                        @endif
						                    </td>
					                        <td></td>
										</tr>
										@if($class->cl_pad_before)
										<tr>
											<td>Padding Before</td>
											<td>{{ $class->cl_pad_before }} min</td>
					                        <td></td>
										</tr>
										<tr>
											<td>Processing Time </td>
											<td>{{ $class->cl_proc_time }} min</td>
					                        <td></td>
										</tr>
										@endif
									</tbody>
								</table>
							</div>
						</div>
						<div class="col-sm-7 col-md-8">
							<div class="row">
								<div class="col-sm-3">
									<button class="btn btn-icon btn-block">
										<i class="clip-clip block"></i>
										Projects <span class="badge badge-info"> 4 </span>
									</button>
								</div>
								<div class="col-sm-3">
									<button class="btn btn-icon btn-block pulsate">
										<i class="clip-bubble-2 block"></i>
										Messages <span class="badge badge-info"> 23 </span>
									</button>
								</div>
								<div class="col-sm-3">
									<button class="btn btn-icon btn-block">
										<i class="clip-calendar block"></i>
										Calendar <span class="badge badge-info"> 5 </span>
									</button>
								</div>
								<div class="col-sm-3">
									<button class="btn btn-icon btn-block">
										<i class="clip-list-3 block"></i>
										Notifications <span class="badge badge-info"> 9 </span>
									</button>
								</div>
							</div>
					        
					        <div class="panel panel-white">
					            <!-- start: PANEL HEADING -->
					            <div class="panel-heading">
					                <h5 class="panel-title">
					                    <span class="icon-group-left">
					                        <i class="clip-checkmark-2"></i>
					                    </span>
					                    Sales Process
					                    <span class="icon-group-right">
					                        <a class="btn btn-xs pull-right" href="#" data-toggle="modal" data-target="#configModal">
					                            <i class="fa fa-wrench"></i>
					                        </a>
					                        <a class="btn btn-xs pull-right panel-collapse" href="#">
					                            <i class="fa fa-chevron-down"></i>
					                        </a>
					                    </span>
					                </h5>
					            </div>
					            <!-- start: PANEL HEADING -->
					            <!-- start: PANEL BODY -->
					            <div class="panel-body panel-scroll" style="height:300px">
					            	<ul class="todo">
										<li>
											<a class="todo-actions contact" href="javascript:void(0)">
					                            <i class="fa fa-square-o"></i>
												<span class="desc" style="opacity:1;text-decoration: none;"> Contact</span>
											</a>
										</li>
										<li>
											<a class="todo-actions" href="javascript:void(0)">
												<i class="fa fa-square-o"></i>
												<span class="desc" style="opacity: 1; text-decoration: none;"> Book consultation</span>
											</a>
										</li>
										<li>
											<a class="todo-actions" href="javascript:void(0)">
												<i class="fa fa-square-o"></i>
												<span class="desc"> Consultation</span>
											</a>
										</li>
										<li>
											<a class="todo-actions" href="javascript:void(0)">
												<i class="fa fa-square-o"></i>
												<span class="desc"> Book Benchmark</span>
											</a>
										</li>
										<li>
											<a class="todo-actions" href="javascript:void(0)">
												<i class="fa fa-square-o"></i>
												<span class="desc"> Benchmark</span>
											</a>
										</li>
										<li>
											<a class="todo-actions" href="javascript:void(0)">
												<i class="fa fa-square-o"></i>
												<span class="desc"> Book T.E.A.M</span>
											</a>
										</li>
										<li>
											<a class="todo-actions" href="javascript:void(0)">
												<i class="fa fa-square-o"></i>
												<span class="desc"> T.E.A.M</span>
											</a>
										</li>
										<li>
											<a class="todo-actions" href="javascript:void(0)">
												<i class="fa fa-square-o"></i>
												<span class="desc"> Email pricing</span>
											</a>
										</li>
									</ul>
					            </div>
					        </div>
					        
					        <div class="panel panel-white">
					            <!-- start: PANEL HEADING -->
					            <div class="panel-heading">
					                <h5 class="panel-title">
					                    <span class="icon-group-left">
					                        <i class="clip-menu"></i>
					                    </span>
					                    Recent Activities
					                    <span class="icon-group-right">
					                        <a class="btn btn-xs pull-right" href="#" data-toggle="modal" data-target="#configModal">
					                            <i class="fa fa-wrench"></i>
					                        </a>
					                        <a class="btn btn-xs pull-right panel-collapse" href="#">
					                            <i class="fa fa-chevron-down"></i>
					                        </a>
					                    </span>
					                </h5>
					            </div>
					            <!-- start: PANEL HEADING -->
					            <!-- start: PANEL BODY -->
					            <div class="panel-body panel-scroll" style="height:300px">
					            	<ul class="activities">
										<li>
											<a class="activity" href="javascript:void(0)">
												<i class="clip-upload-2 circle-icon circle-green"></i>
												<span class="desc">You uploaded a new release.</span>
												<div class="time">
													<i class="fa fa-time bigger-110"></i>
													2 hours ago
												</div>
											</a>
										</li>
										<li>
											<a class="activity" href="javascript:void(0)">
												<img alt="image" src="{{ asset('assets/images/avatar-2.jpg') }}">
												<span class="desc">Nicole Bell sent you a message.</span>
												<div class="time">
													<i class="fa fa-time bigger-110"></i>
													3 hours ago
												</div>
											</a>
										</li>
										<li>
											<a class="activity" href="javascript:void(0)">
												<i class="clip-data circle-icon circle-bricky"></i>
												<span class="desc">DataBase Migration.</span>
												<div class="time">
													<i class="fa fa-time bigger-110"></i>
													5 hours ago
												</div>
											</a>
										</li>
										<li>
											<a class="activity" href="javascript:void(0)">
												<i class="clip-clock circle-icon circle-teal"></i>
												<span class="desc">You added a new event to the calendar.</span>
												<div class="time">
													<i class="fa fa-time bigger-110"></i>
													8 hours ago
												</div>
											</a>
										</li>
										<li>
											<a class="activity" href="javascript:void(0)">
												<i class="clip-images-2 circle-icon circle-green"></i>
												<span class="desc">Kenneth Ross uploaded new images.</span>
												<div class="time">
													<i class="fa fa-time bigger-110"></i>
													9 hours ago
												</div>
											</a>
										</li>
										<li>
											<a class="activity" href="javascript:void(0)">
												<i class="clip-image circle-icon circle-green"></i>
												<span class="desc">Peter Clark uploaded a new image.</span>
												<div class="time">
													<i class="fa fa-time bigger-110"></i>
													12 hours ago
												</div>
											</a>
										</li>
									</ul>
					            </div>
					        </div>
						</div>
					</div>
				</div>    
			</div>
		</div>
	</div>
</div>
@endsection
@section('script')
@stop