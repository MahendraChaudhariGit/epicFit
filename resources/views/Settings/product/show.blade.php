@extends('blank')

@section('page-title')
	<span class="product-name-text">{{ $product->name }}</span>
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

<!-- Start: Edit filed modal -->
@include('includes.partials.edit_field_from_view_modal',['entityId'=>$product->id,'entityType'=>'product'])
<!-- End: Edit filed modal -->

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
									<h4 class="product-name-text">{{ $product->name }}</h4>
									<div>
										<div class="user-image">
					                        <div class="form-group upload-group">
					                        	<div class="thumbnail">

													<a href="{{ url('/') }}/uploads/{{ $product->logo }}" data-lightbox="image-1" 
														>
													<img src="{{ url('/') }}/uploads/{{ $product->logo }}" class="img-responsive productImagePreviewPics previewImg" id="profile-userpic-img" alt="{{ $product->name }}" style="max-width: 120px !important;"></a>

												</div>
								                <input type="hidden" name="prePhotoName" value="{{ isset($product)?$product->logo:'' }}">
								                <input type="hidden" name="imgSrcCls" value="productImagePreviewPics">
								                <input type="hidden" name="inputElem" value="productImage">
								                <input type="hidden" name="saveUrl" value="product/photo/save">
								                <input type="hidden" name="entityId" value="{{$product->id}}">
								                <div>
								                    <label class="btn btn-primary btn-file">
								                        <span>Change Logo</span><input type="file" class="hidden" onChange="imageUploadHandel(this)" accept="image/*" data-type="product">
								                    </label>
								                </div>
								                <input type="hidden" name="productImage" value="{{ isset($product)?$product->logo:'' }}">
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
											<td>Product Name:</td>
											<td class="product-name-text">{{ $product->name ?? '' }}</td>
											<td>
												<a href="#" class="show-edit-modal" data-field="product-name-text" data-fieldvalue="{{ $product->name ?? '' }}" data-label="Product Name">
													<i class="fa fa-pencil edit-user-info"></i>
												</a>
											</td>
										</tr>

                                        <tr>
											<td>SKU / Product ID:</td>
											<td class="product-skuid-text">{{ $product->sku_id }}</td>
											<td>
												<a href="#" class="show-edit-modal" data-field="product-skuid-text" data-fieldvalue="{!! $product->sku_id !!}" data-label="SKU / Product ID">
													<i class="fa fa-pencil edit-user-info"></i>
												</a>
											</td>
										</tr>
										<tr>
											<td>Product Description:</td>
											<td class="product-disc-textarea">{!! $product->description !!}</td>
											<td>
												<a href="#" class="show-edit-modal" data-field="product-disc-textarea" data-fieldvalue="{{ $product->description }}" data-label="Product Description" data-log="ckeditor">
													<i class="fa fa-pencil edit-user-info"></i>
												</a>
											</td>
										</tr>
                                        <tr>
											<td>Sale Price:</td>
											<td>$<span class="product-saleprice-number">{{ $product->sale_price }}</span></td>
											<td>
												<a href="#" class="show-edit-modal" data-field="product-saleprice-number" data-fieldvalue="{!! $product->sale_price !!}" data-label="Sale Price">
													<i class="fa fa-pencil edit-user-info"></i>
												</a>
											</td>
										</tr>
										<tr>
											<td>Tax:</td>
											<td class="product-saletax-select">{{ $product->salesTax }}</td>
											<td>
												<a href="#" class="show-edit-modal" data-field="product-saletax-select" data-fieldvalue="{!! $product->salesTax !!}" data-label="Sale Tax">
													<input name="selectData" type="hidden" value='<?php echo json_encode(['Excluding' => 'Excluding', 'Including' => 'Including', 'N/A' => 'N/A']) ?>'>
													<i class="fa fa-pencil edit-user-info"></i>
												</a>
											</td>
										</tr>

                                 	</tbody>
								</table>
                                <table class="table table-condensed table-hover">
									<thead>
										<tr>
											<th colspan="3">Inventory information</th>
										</tr>
                                                                                 <tr>
											<td>Stock Location:</td>
											<td class="product-stackloc-select">{{ $product->stockLocations }}</td>
											<td>
												<a href="#" class="show-edit-modal" data-field="product-stackloc-select" data-fieldvalue="{!! $product->stockLocations !!}" data-label="Stock Location">
													<input name="selectData" type="hidden" value='<?php echo json_encode($locs) ?>' >
													<i class="fa fa-pencil edit-user-info"></i>
												</a>
											</td>
										</tr>
                                        <tr>
											<td>Stock:</td>
											<td class="product-stacklevel-number">{{ $product->stock_level}}</td>
											<td>
												<a href="#" class="show-edit-modal" data-field="product-stacklevel-number" data-fieldvalue="{!! $product->stock_level !!}" data-label="Stock">
													<i class="fa fa-pencil edit-user-info"></i>
												</a>
											</td>
										</tr>
                                        <tr>
											<td>Product History:</td>
											<td >{{ $product->historyUi}}</td>
											<td><!-- <a href="#" class="show-edit-modal"><i class="fa fa-pencil edit-user-info"></i></a> --></td>
										</tr>
                                         <tr>
											<td>Cost Price:</td>
											<td>$<span class="product-costprice-number">{{ $product->cost_price}}</span></td>
											<td>
												<a href="#" class="show-edit-modal" data-field="product-costprice-number" data-fieldvalue="{!! $product->cost_price !!}" data-label="Cost Price">
													<i class="fa fa-pencil edit-user-info"></i>
												</a>
											</td>
										</tr>
                                        <tr>
											<td>Tax:</td>
											<td class="product-tax-select">{{ $product->tax}}</td>
											<td>
												<a href="#" class="show-edit-modal" data-field="product-tax-select" data-fieldvalue="{!! $product->tax !!}" data-label="Tax">
													<input name="selectData" type="hidden" value='<?php echo json_encode(['Excluding' => 'Excluding', 'Including' => 'Including', 'N/A' => 'N/A']) ?>'>
													<i class="fa fa-pencil edit-user-info"></i>
												</a>
											</td>
										</tr>
                                        <tr>
											<td>Stock Note:</td>
											<td class="product-note-textarea">{{ $product->stock_note}}</td>
											<td>
												<a href="#" class="show-edit-modal" data-field="product-note-textarea" data-fieldvalue="{!! $product->stock_note !!}" data-label="Stock Note">
													<i class="fa fa-pencil edit-user-info"></i>
												</a>
											</td>
										</tr>
									</thead>
									<tbody>
					                	
									</tbody>
								</table>
								<table class="table table-condensed table-hover">
									<thead>
										<tr>
											<th colspan="3">Appearance information</th>
										</tr>
                                        <tr>
											<td>Size:</td>
											<td class="product-size-select">{{ $product->sizeName($product->pro_size) }}</td>
											<td>
												<a href="#" class="show-edit-modal" data-field="product-size-select" data-fieldvalue="{!! $product->pro_size !!}" data-label="Size">
													<input name="selectData" type="hidden" data-select-type="multiple" value='<?php echo json_encode($proSize) ?>'>
													<i class="fa fa-pencil edit-user-info"></i>
												</a>
											</td>
										</tr>
										<tr>
											<td>Color:</td>
											<td><p class="product-color-color" style="background-color:{{ $product->pro_color }};" >&nbsp;</p></td>
											<td>
												<a href="#" class="show-edit-modal" data-field="product-color-color" data-fieldvalue="{!! $product->pro_color !!}" data-label="Color">
													<i class="fa fa-pencil edit-user-info"></i>
												</a>
											</td>
										</tr>
									</thead>
									<tbody>
					                	
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
{!! Html::script('assets/plugins/ckeditor/ckeditor.js?v='.time()) !!}
{!! Html::script('assets/plugins/ckeditor/adapters/jquery.js?v='.time()) !!}
{!! Html::script('assets/js/ckeditor.js?v='.time()) !!}
{!! Html::script('assets/js/helper.js?v='.time()) !!}
{!! Html::script('assets/js/product.js?v='.time()) !!}
{!! Html::script('assets/js/edit-field-from-view.js?v='.time()) !!}
@stop