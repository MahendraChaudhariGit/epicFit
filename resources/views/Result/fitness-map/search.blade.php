@extends('Result.masters.app')

@section('required-styles')

{!! Html::style('result/css/fitness-core/jquery-ui-1.10.1.custom.css') !!}
{!! Html::style('result/css/fitness-core/jquery.ui.labeledslider.css') !!}
{!! Html::style('result/css/fitness-core/datetimepicker.min.css') !!}
<!--{!! Html::style('css/fitness-core/jquery.ui.1.10.1.ie.css') !!} -->
{!! Html::style('result/css/fitness-mapper/FitMapper.css') !!}
<style type="text/css">
	select.form-control{
		border-radius: 4px !important;
		-webkit-appearance: auto;
		-moz-appearance: auto;
		appearance: auto;
	}
	.create-route{
		/* background: #f64c1eb8; */
		color: #fcfcfc;
	}
	.search-btn{
		margin-top: 4px;
		width: 100%;
	}
	input.form-control{
		border-radius: 4px !important;

	}
	.form-group{
		margin-bottom: 25px;
	}
	.form-group input[type=number]{
		padding-left: 12px;
	}
	.topCitiesContainer a {
		display: block;
		font-weight: 500;
		margin-bottom: 5px;

	}
	.showtopcities, .hidetopcities{
		font-weight: 500;margin-bottom: 20px;
		width: 100%;
		float: left;
	}
	.mapHeader {
		border: 1px solid #E6E6E5;
		border-bottom: none;
		background: #F9F9F9;
		padding: 15px;
	}
	#spaceless2{
		display: none;
	}
	.m-t-10{
		margin-top: 15px;
	}
	@media(min-width: 1500px){
	#fitMap{
		max-height: 800px !important
	}
}
@media(max-width: 1499px){
	#fitMap{
		max-height: 500px !important
	}
}
</style>
@stop
@section('page-title')
<!-- <span>Search for running routes
</span> -->
@stop
@section('content')
<input type="hidden" id="latroot" value="{{ implode(',',$latroot) }}">
<input type="hidden" id="lngroot" value="{{ implode(',',$lngroot) }}">
<div class="row m-t-10">
	<div class="col-xs-12 col-sm-10">
		<h3>SEARCH FOR RUNNING ROUTES
		</h3>
	</div>
	<div class="col-xs-12 col-sm-2">
		<a href="{{url('epic/train-gain/fitness-mapper?search=null')}}" class="btn btn-primary create-route">MY ROUTE</a>
	</div>
	
</div>

<form action="{{ route('search.route') }}" method="post">
	{{ csrf_field() }}
	<input type="hidden" name="latitude" id="latitude" value="">
	<input type="hidden" name="longitude" id="longitude" value="">
	<div class="row">
		<div class="col-xs-12">
			<div class="row">
				<div class="col-xs-12 col-sm-5 col-md-5">
					<div class="form-group">


						<label for="routeName" class="">
							<div class=""><span>Search Routes:</span></div>
						</label>
						<input name="routeName" id="routeName" value="{{ !empty($search_data['routeName']) ? $search_data['routeName'] : '' }}" placeholder="Route name or keyword" class="form-control">
					</div>

				</div>
				<div class="col-xs-12 col-sm-5 col-md-5">
					<div class="form-group">
						<label class="">
							<div class=""><span>Near:</span><b class="">*</b></div>
						</label>
						{{-- <select class="form-control">
							<option>address</option>
						</select> --}}
						<input id="route-address" class="form-control" value="{{ !empty($search_data['routeAddress']) ? $search_data['routeAddress'] : '' }}" name="routeAddress" required autocomplete="off" />
					</div>

				</div>
				<div class="col-xs-12 col-sm-3 col-md-2">
					<br>
					<div class="form-group">
						<button type="submit" class="btn btn-primary search-btn">SEARCH</button>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-xs-12 col-md-5 col-sm-5">
					<div class="row">
						<div class="col-xs-12 col-md-6 col-sm-6">
							<div class="form-group">
								<select name="activityType" id="activityType" placeholder="All Activities..." class="form-control">
									<option value="">All Activities...</option>
									<option @if($search_data['activityType'] == 1) selected @endif value="1">Running</option>
									<option @if($search_data['activityType'] == 3) selected @endif value="3">Walking</option>
									<option @if($search_data['activityType'] == 8) selected @endif value="8">Cycling</option>
									<option @if($search_data['activityType'] == 4) selected @endif value="4">Mountain Biking</option>
									<option @if($search_data['activityType'] == 2) selected @endif value="2">Swimming</option>
									<option @if($search_data['activityType'] == 5) selected @endif value="5">Kayaking</option>
									<option @if($search_data['activityType'] == 6) selected @endif value="6">Rowing</option>
									<option @if($search_data['activityType'] == 7) selected @endif value="7">Orienteering</option>
									<option @if($search_data['activityType'] == 9) selected @endif value="9">Other</option>
								</select>
							</div>

						</div>
						<div class="col-xs-12 col-md-6 col-sm-6">
							<div class="form-group">
								<select name="distanceType" id="distanceType" placeholder="Distance Type" class="form-control">
									<option value="">Distance Type</option>
									<option @if($search_data['distanceType'] == 'atleast') selected @endif value="atleast">At least</option>
									<option @if($search_data['distanceType'] == 'between') selected @endif value="between">Between</option>
								</select>
							</div>

						</div>
					</div>
					
				</div>
				<div class="col-xs-12 col-sm-1 col-md-1">
					<div class="form-group">
						<input name="distanceMinimum" id="distanceMinimum" type="number" placeholder="Min" size="20" class="form-control" value="3">
					</div>
				</div>
				<div  class="col-xs-12 col-sm-1 col-md-2" id="distanceMaximumdiv">
					<div class="form-group">
						<input name="distanceMaximum" id="distanceMaximum" type="number" placeholder="Min" size="20" class="form-control" value="200">
					</div>
				</div>
				<div class="col-xs-12 col-sm-6 col-md-2">
					<div class="form-group">
						<select name="unit" id="unit" class="form-control">
							<option @if($search_data['unit'] == 'mi') selected @endif value="mi">mi</option>
							<option @if($search_data['unit'] == 'km') selected @endif value="km">km</option>
						</select>
					</div>

				</div>
				<div class="col-xs-12 col-sm-6 col-md-2">
					<div class="form-group">
						<select name="radius" class="form-control">
							<option @if($search_data['radius'] == '50000') selected @endif value="50000">City</option>
							<option value="">Search Radius</option>
							<option value="">Neighborhood</option>
						</select>
					</div>

				</div>
			</div>
			<div class="row">
				<div class="col-md-4">
					<a class="showtopcities hide"><span>Show top cities</span></a>
					<a class="hidetopcities hide"><span>Hide top cities</span></a>
				</div>
			</div>
			<div class="topCitiesContainer">
				{{-- <div class="row">
					<div class="col-md-4 col-sm-4 col-xs-12">
						<a class="" href="#">New York, NY</a>
						<a class="" href="#">London, England</a>
						<a class="" href="#">Chicago, IL</a>
						<a class="" href="#">Sydney, Australia</a>
						<a class="" href="#">San Francisco, CA</a>
						<a class="" href="#">Melbourne, Australia</a>
						<a class="" href="#">Washington, DC</a>
						<a class="" href="#">Los Angeles, CA</a>
					</div>
					<div class="col-md-4 col-sm-4 col-xs-12">
						<a class="" href="#">Auckland, New Zealand</a>
						<a class="" href="#">Dublin, Ireland</a>
						<a class="" href="#">Louisville, KY</a>
						<a class="" href="#">Toronto, Canada</a>
						<a class="" href="#">Austin, TX</a>
						<a class="" href="#">Boston, MA</a>
						<a class="" href="#">Denver, CO</a>
						<a class="" href="#">Houston, TX</a>
					</div>
					<div class="col-md-4 col-sm-4 col-xs-12">
						<a class="" href="#">Minneapolis, MN</a>
						<a class="" href="#">San Diego, CA</a>
						<a class="" href="#">Seattle, WA</a>
						<a class="" href="#">Brisbane, Australia</a>
						<a class="" href="#">Dallas, TX</a>
						<a class="" href="#">Portland, OR</a>
						<a class="" href="#">Atlanta, GA</a>
						<a class="" href="#">United States, USA</a>
					</div>
				</div> --}}
			</div>
			<div class="row">
				<div class="col-xs-12">
					<div class="mapHeader">
						<a class="mapHeaderLink-LyVHY hide" href="#">
							<span>Search This Map Location</span>
						</a>&nbsp;&nbsp;&nbsp;&nbsp;
						<a class="mapHeaderLink-LyVHY hide" href="#"> 
							<span>Show My Location</span>
						</a>
					</div>
				</div>
				<div class="col-xs-12">
					<div id="fitMap" class="fitMap">
						<div id="fitMapMainContainer" class="fitMapMain">
						   <div id="fitMapContentWrapper" class="contentWrapper">
							  <div id="fitMapContentColumn" class="contentColumn">
								 <div class="fitMapInner">
									<div id="fitMapCanvas" class="fitCanvas"></div>
								 </div>
							  </div>
						   </div>
						   <div class="main-box" style="display: none">
							  <div class="col-md-4 col-xs-12 col-sm-4">
								 <input type="text" id="fitSearchAddress" class="searchInput" size="15" placeholder="Enter a location"/>
								 <span id="fitMapBtnSearch" class="fa fa-search"></span>
							  </div>
							  <div class="col-md-4 col-xs-12 col-sm-4">
								 <div class="undo-div">
									<ul class="list-group">
									   <li>
										  <a id="fitMapBtnUndo" class="fitMapBtn" href="#"><i class="fa fa-reply" aria-hidden="true"></i></a>
										  <span class="fitMapBtnText">undo</span>
									   </li>
									   <li>
										  <a id="fitMapBtnPlotBack" class="fitMapBtn" href="#"><i class="fa fa-share" aria-hidden="true"></i></a>
										  <span class="fitMapBtnText">redo</span>
									   </li>
									   <li><a id="fitMapBtnClear" class="fitMapBtn" href="#"><i class="fa fa-times" aria-hidden="true"></i></a><span class="fitMapBtnText">clear</span></li>
									   <li><a id="fitMapBtnOutBack" class="fitMapBtn" href="#"><i class="fa fa-trash-o" aria-hidden="true"></i></a><span class="fitMapBtnText">Out</span></li>
									   <li><a id="fitMapBtnSave" class="fitMapBtn" href="#"><i class="fa fa-check" aria-hidden="true"></i></a><span class="fitMapBtnText">Save</span></li>
									</ul>
								 </div>
							  </div>
							  <!--div class="col-md-2 col-xs-12">
								 <div class="undo-div">
									 <ul class="list-group">
										 <li><a href="#"><i class="fa fa-undo"></i></a><span>Run</span></li>
										 <li><a href="#"><i class="fa fa-undo"></i></a><span>Cycle</span></li>
									 </ul>
								 </div>
								 </div-->
							  <div class="col-md-2 col-xs-6 col-sm-2">
								 <div class="headerGradient">Workout <span class="caret"></span></div>
								 <div class="selectedWorkout" style="display: none">
									<div>
									   <span>Workout</span>
									   <select id="selectedWorkout">
										  <option value="1">Running</option>
										  <option value="3">Walking</option>
										  <option value="8">Cycling</option>
										  <option value="4">Mountain Biking</option>
										  <option value="2">Swimming</option>
										  <option value="5">Kayaking</option>
										  <option value="6">Rowing</option>
										  <option value="7">Orienteering</option>
										  <option value="9">Other</option>
									   </select>
									</div>
									<div id = "selectedExerciseDiv">
									   <span>Exercise</span>
									   <select id="selectedExercise">
										  <option value="1">Easy Run</option>
										  <option value="3">Long Run</option>
										  <option value="8">Hill</option>
										  <option value="4">Speed</option>
										  <option value="2">Tempo Run</option>
										  <option value="5">Fartlek</option>
									   </select>
									</div>
									<div>
									   <span>Duration</span>
									   <input id="selectedDuration" type="text" data-wmt="Enter Duration" />
									</div>
								 </div>
							  </div>
							  <div class="col-md-2 col-xs-6 col-sm-2">
								 <div id="fitMapContentWrapper" class="contentWrapper">
									<div id="fitButtonPanel" class="buttonPanel">
									   <div class="buttonItem" id="ank">
										  <a id="toggleControl">
										  <img src="{{ asset('result/css/fitness-mapper/css/images/map/eye-1.png') }}" alt="Toggle Markers" />
										  </a>
									   </div>
									   <div class="buttonItem">
										  <a id="toggleUnit" href="#">
										  <img src="{{ asset('result/css/fitness-mapper/css/images/map/drawmode.png') }}" alt="Toggle Distance" /></a>
									   </div>
									   <div class="buttonItem" href="" id="both">
										  <a id="toggleRoad">
										  <img src="{{ asset('result/css/fitness-mapper/css/images/map/road.png') }}" alt="Toggle Road"/> 
										  </a>
									   </div>
									   <div class="buttonItem last">
										  <a id="toggleScreen">
										  <img src="{{ asset('result/css/fitness-mapper/css/images/map/max.png') }}" alt="Toggle Screen" /></a>
									   </div>
									</div>
									<div id="fitMapContentColumn" class="contentColumn">
									   <div class="fitMapInner">
										  <div id="fitMapCanvas" class="fitCanvas"></div>
									   </div>
									</div>
								 </div>
							  </div>
						   </div>
						   <div class="col-md-12 map-btn">
							  <button class="btn mapp active changeMap hide" data-map="roadmap">Map</button><button class="btn satellite changeMap hide" data-map="satellite">Satellite</button>
						   </div>
						   <div id="fitControlPanel" class="">
							  <!--controlPanel-->
							  <div class="fitControlPanelMapInner">
								 <div class="minimizeDiv"></div>
								 <div id="accordion" class="accordian">
								 </div>
							  </div>
						   </div>
						</div>
					</div>
				</div>
			</div>
			<div class="row searchResultsContainer m-t-10">
				<div class="col-xs-12">
					<h3><span class="routeCountTotal">{{isset($filterData) && count($filterData)>0 ?count($filterData):'No' }}</span> Routes</h3>
				</div>
				<div class="col-xs-12">
					<table class="table table-striped table-bordered table-hover m-t-10">
						<thead>
							<tr>
								<th><span>Distance</span></th>
								<th><span>Ascent</span></th>
								<th><span>Name</span></th>
								<th><span>City</span></th>
								<th><span>Date</span></th>
							</tr>
						</thead>
						<tbody>
							@foreach ($filterData as $data)
							<tr class="polyline" data-polyline="{{$data->polyline}}">
								<td><a href="javascript:void(0)" style="color: #5b5b60 !important"><span><span data-value="">{{$data->km}}</span> <span data-unit="">km</span></span></a></td>
								<td><a href="javascript:void(0)" style="color: #5b5b60 !important"><span><span data-value="">{{$data->altitude_gain_meter}}</span> <span data-unit="">m</span></span></a></td>
								<td><a href="javascript:void(0)">{{$data->name}}</a></td>
								<td><a href="javascript:void(0)" style="color: #5b5b60 !important">{{$data->city}}</a></td>
								<td><a href="javascript:void(0)" style="color: #5b5b60 !important"><span>{{$data->created_at}}</span></a></td>
							</tr>
							@endforeach
						</tbody>
					</table>
				</div>
				
			</div>
		</div>
		
	</div>
</form>
@stop

@section('required-script')
<script>
	// This sample uses the Places Autocomplete widget to:
	// 1. Help the user select a place
	// 2. Retrieve the address components associated with that place
	// 3. Populate the form fields with those address components.
	// This sample requires the Places library, Maps JavaScript API.
	// Include the libraries=places parameter when you first load the API.
	// For example: <script
	// src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&libraries=places">
	let autocomplete;
	let address1Field;
	

	function initAutocomplete() {
		address1Field = document.querySelector("#route-address");
		// Create the autocomplete object, restricting the search predictions to
		// addresses in the US and Canada.
		autocomplete = new google.maps.places.Autocomplete(address1Field, {
			fields: ["address_components", "geometry"],
			types: ["address"],
		});
		address1Field.focus();
		// When the user selects an address from the drop-down, populate the
		// address fields in the form.
		autocomplete = new google.maps.places.Autocomplete((document.getElementById('route-address')), {
			types: ['geocode']
			
		});

		google.maps.event.addListener(autocomplete, 'place_changed', function () {
			var place = autocomplete.getPlace();
			var latitude = place.geometry.location.lat();
			var longitude = place.geometry.location.lng();
			$('#latitude').val(latitude);
			$('#longitude').val(longitude);
		});
			
		// autocomplete.addListener("place_changed", fillInAddress);
	}

</script>
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCI9fgvBgIW52M1jvW5rWQ9LOSdweGy8kg&libraries=geometry,places&callback=initAutocomplete"></script>

{!! Html::script('result/js/fitness-core/datetimepicker.min.js') !!}
{!! Html::script('result/js/fitness-mapper/jquery.fullscreen.js') !!}
{!! Html::script('result/js/fitness-mapper/FitMapper.js?v='.time()) !!}
<script type="text/javascript">
	$(".topCitiesContainer").hide();
	$(".hidetopcities").hide();
	$(document).ready(function(){
		Fit.Mapper.Start( '', null );
		$(".showtopcities").click(function(){
			$(".topCitiesContainer").show();
			$(".showtopcities").hide();
			$(".hidetopcities").show();
		});
		$(".hidetopcities").click(function(){
			$(".topCitiesContainer").hide();
			$(".showtopcities").show();
			$(".hidetopcities").hide();
		});
	});
	$(document).ready(function(){
	$("#distanceMaximumdiv").hide();
   $('#distanceType').on('change', function() {
      if ( this.value == 'between')
      //.....................^.......
      {
       $("#distanceMaximumdiv").show();
      }
      else
      {
         $("#distanceMaximumdiv").hide();
      }
    });
});

</script>
@if(count($center))
<script>
	$(document).ready(function(){
		var latroot = $("#latroot").val().split(',');
		var lngroot = $("#lngroot").val().split(',');
		console.log(latroot);
		if(latroot.length > 0){
			$.each(latroot,function(key,value){
				console.log(value,lngroot[key]);
				var latlng = new google.maps.LatLng(value,lngroot[key]);
				setTimeout(function(){
					Fit.Global.Map.setCenter(new google.maps.LatLng(value, lngroot[key]));
					var marker = new google.maps.Marker({
						map: Fit.Global.Map,
						position: latlng,
						draggable: false,
						anchorPoint: new google.maps.Point(0, -29)
					});
				},2000)
			});
			
		}else{
			var lat = "{{$center['lat']}}";
			var long = "{{$center['long']}}";
			var latlng = new google.maps.LatLng(lat,long);
			setTimeout(function(){
				Fit.Global.Map.setCenter(new google.maps.LatLng(lat, long));
				var marker = new google.maps.Marker({
					map: Fit.Global.Map,
					position: latlng,
					draggable: false,
					anchorPoint: new google.maps.Point(0, -29)
				});
			},2000)
		}
	
	
	});
</script>
@endif
@stop