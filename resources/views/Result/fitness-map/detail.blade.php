@extends('Result.masters.app')

@section('required-styles')

<script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
{!! Html::style('result/css/fitness-mapper/FitMapper.css') !!}
<style type="text/css">
	.d-flex{
		display: flex;
		align-items: center;
	}
	.img-icon img{
		width:30px;
		height: 30px;
		border-radius: 50%;

	}
	.d-flex .border-right{
		border-right-width: 2px;
		height: 18px;
	}
	.d-flex span{
		margin-right: 10px;
	}
	.d-flex svg{
		width: 15px;
		fill: gray;
		margin-top: 5px;
	}
	.jss311 span{
		background: #f64c1e;
		color: white;
		padding: 5px 10px;
		border-radius: 16px;
		align-items: center;
		display: flex;
	}
	.jss311 img{
		width: 20px
	}
	.jss311 svg{
		fill: none;
		width: 18px;
		height: 18px;
		stroke: #fff;
		margin: 0px
	}
	.elevation-graph{
		box-shadow: 0px 0px 2px 0px grey;
		padding-bottom: 20px;
		padding-top: 20px;
		margin-bottom: 20px;
	}
	.elevation-graph {
		text-transform: uppercase;
		font-weight: 600;
	}
	h1{
		font-weight: 600
	}
	.bg-orng{
		background: #f64c1e12;
		padding-top: 20px;
	}
	.map-mneus .navbar1 {
		overflow: hidden;
		background-color: white;
		width: max-content;
		margin-left: auto;
		margin-right: auto;
		height: auto;
		align-items: center;
		display: flex;
		min-height: 50px;

	}
	.map-mneus{
		display: inline-block;
		width: 100%;
		position: relative;
		bottom: -30px;
		z-index: 99;
	}
	.map-mneus .navbar1 a {
		float: left;
		font-size: 14px;
		color: #f64c1e;
		text-align: center;
		padding: 8px 16px;
		text-decoration: none;
		text-transform: uppercase;
		font-weight: 600;
		display: flex;
		border-right: 1px solid #fb6e4887;
	}
	.map-mneus .navbar1 a svg{
		fill: #f64c1e;
		width: 15px;
	}

	.map-mneus .dropdown {
		float: left;
		overflow: hidden;
		position: initial;
	}

	.map-mneus .dropdown .dropbtn {
		font-size: 14px;  
		border: none;
		outline: none;
		color: #f64c1e;
		padding: 8px 30px;
		background-color: inherit;
		font-family: inherit;
		margin: 0;
		font-weight: 600;
		text-transform: uppercase;		
		border-right: 1px solid #fb6e4887
	}
	.border-0{
		border-width:0px !important;
	}
	/*.map-mneus .navbar a:hover, .map-mneus .dropdown:hover .dropbtn {
		background-color: red;
	}
	*/
	.map-mneus .dropdown-content {
		display: none;
		position: absolute;
		background-color: #ffffff;
		min-width: 160px;
		z-index: 99999999999;

	}

	.map-mneus .dropdown-content a {
		float: none;
		color:#f64c1e;
		padding: 14px 30px;
		text-decoration: none;
		display: block;
		text-align: left;
		border:0px;
		font-weight: 500;
	}

	.map-mneus .dropdown-content a:hover {
		background-color: #ddd;
	}

	.map-mneus .dropdown:hover .dropdown-content {
		display: block;
	}
	.elevation-heading svg{
		width: 20px;
		height: 20px;
		fill: none;
		width: 18px;
		height: 18px;
		stroke: #000000;
		margin: 0px;
	}
	.elevation-graph h6{
		text-transform: uppercase;
		font-weight: 600;
	}
	.elevation-graph h5{
		font-weight: 600;
		font-size: 20px;
		text-transform: lowercase;
	}
	.elevation-graph span{
		margin:0px;
	}
	.elevation-graph div{
		margin-left: 20px;
		margin-right: 20px;
	}
	#spaceless2{
		display: none;
	}
	.m-t-10{
		margin-top: 15px;
	}
	#routeMap.fitMap {
		width: 100%;
		height: 750px;
		margin: auto;
		margin-top: 0px;
		float: left;
		border: 0px solid rgba(0, 0, 0, .3);
		-webkit-background-clip: padding-box;
		background-clip: padding-box;
		-webkit-border-radius: 7px;
		-moz-border-radius: 7px;
		border-radius: 7px;
	}
	.routeMapContentWrapper{
		float: left;
		width: 100%;
	}
	#routeMapContentColumn.contentColumn {
		height: 100%;
		margin-left: 0px;
	}
	section#page-title {
		width: 100%;
		float: left;
	}
	@media(min-width: 1500px){
		#routeMap,#fitMap{
			max-height: 800px !important
		}
	}
	@media(max-width: 1499px){
		#routeMap,#fitMap{
			max-height: 500px !important
		}
	}
	@media(max-width: 767px){
		.jss311{
			display: inline-block !important;
		}
		.jss311 span{
             margin-bottom: 10px;
		}
		.map-mneus .navbar1{
			display: inline-block !important;
			width: auto;
		}
		.map-mneus{
			bottom: 0px;
		}
		.map-mneus .navbar1 a,.map-mneus .dropdown .dropbtn{
			font-size: 11px;
		}
	}
</style>
@stop
@section('page-title')
<!-- <span>Search for running routes
</span> -->
@stop
@section('content')

<div class="row m-t-10 bg-orng">
	<div class="col-xs-12 d-flex">
		<span class="img-icon"><img src="@if(!empty($client_details->profilepic)){{asset('uploads/thumb_'.$client_details->profilepic)}}@else{{asset('assets/images/no-image-icon.jpg')}}@endif"> </span>
		<span class="name">{{ $client_details->firstname.''.$client_details->lastname }}</span>
		{{-- <span class="border-right"></span> --}}
		{{-- <span class="icon"><svg id="privacy-friends" viewBox="0 0 24 24"><path d="M9.12 12.48c2.353 0 6.988 1.636 7.193 4.895l.007.225v.832c0 .904-.694 1.646-1.579 1.722l-.149.006H3.648a1.728 1.728 0 0 1-1.722-1.579l-.006-.149V17.6c0-3.408 4.793-5.12 7.2-5.12zm0 1.92c-2.549 0-4.8 1.398-4.8 3.102 0 .408.33.738.738.738h8.124a.738.738 0 0 0 .731-.638l.007-.1c0-1.704-2.251-3.102-4.8-3.102zm7.238-1.142c2.293.251 5.525 1.299 5.713 3.133l.009.169v1.68h-3.84v-1.68c0-1.42-.768-2.496-1.882-3.302zM16.8 6.37a2.745 2.745 0 0 1 2.75 2.749 2.745 2.745 0 0 1-2.75 2.75 2.745 2.745 0 0 1-2.382-1.375 6.206 6.206 0 0 0 .928-3.71c.421-.262.92-.414 1.454-.414zM9.12 2.88a4.325 4.325 0 0 1 4.32 4.32 4.325 4.325 0 0 1-4.32 4.32A4.325 4.325 0 0 1 4.8 7.2a4.325 4.325 0 0 1 4.32-4.32zm0 1.92a2.397 2.397 0 0 0-2.4 2.4c0 1.328 1.072 2.4 2.4 2.4 1.328 0 2.4-1.072 2.4-2.4 0-1.328-1.072-2.4-2.4-2.4z" id="Combined-Shape"></path></svg></span>
		<span class="fr">Friends</span> --}}
	</div>
	<div class="col-xs-12 d-flex m-t-10 jss311">
		@php
		if($data->workout == '1'){
		$workout = 'Running';
	}elseif($data->workout == '2'){
	$workout = 'Swimming';
}elseif($data->workout == '3'){
$workout = 'Walking';
}elseif($data->workout == '4'){
$workout = 'Mountain Biking';
}elseif($data->workout == '5'){
$workout = 'Kayaking';
}elseif($data->workout == '6'){
$workout = 'Rowing';
}elseif($data->workout == '7'){
$workout = 'Orienteering';
}elseif($data->workout == '8'){
$workout = 'Cycling';
}elseif($data->workout == '9'){
$workout = 'Other';
}else
@endphp

<span>{{ $workout }}</span>
<span><img src="{{asset('assets/images/location.png')}}"> &nbsp;&nbsp;{{ $data->city }}</span>
<span><img src="{{asset('assets/images/locations.png')}}"> &nbsp;&nbsp;{{ number_format((float)($data->km * 0.621371)/1000, 2, '.', '') }} mi</span>
<span style="padding: 1px;"><svg id="ic-elevation" viewBox="0 0 28 28"><path fill-rule="evenodd" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.052 19.948L6 23 22 7 9.052 19.948zM13 7h9v10"></path></svg>
	&nbsp; <span id="gain_feet">0 FT</span></span>
</div>
<div class="col-xs-12 m-t-10">
	<h1>{{ $data->name }} <a href="{{ url('epic/train-gain/fitness-mapper?search=null') }}" class="btn btn-primary back_create" style="float:right">Go Back</a></h1>

</div>
<div class="map-mneus">

	<div class="navbar1">
		<a href="#"><svg id="ic-bookmark" viewBox="0 0 30 30"><path fill-rule="evenodd" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.833" d="M21.418 26.299l-6.23-5.86-6.13 5.862c-.604.469-1.308.057-1.308-.688V4.5h15v21.112c0 .744-.729 1.156-1.332.687z"></path></svg> Bookmark</a>
		<a href="#news">Log Workout</a>
		<a href="#news">Add to website</a>
		<div class="dropdown">
			<button class="dropbtn">Share 
			</button>
			<div class="dropdown-content">
				<a href="#">Email</a>
				<a href="#">Facebook</a>
				<a href="#">Twitter</a>
			</div>
		</div> 
		<div class="dropdown">
			<button class="dropbtn border-0">More
			</button>
			<div class="dropdown-content">
				<a href="{{ url('editRoute/'.$data->id) }}">Edit Route</a>
				<a href="{{ url('copyRoute/'.$data->id) }}">Duplicate Route</a>
				<a href="#">Download GPX</a>
				<a href="#">Download KML</a>
			</div>
		</div> 
	</div>


</div>

</div>

<div id="routeMap" class="fitMap ">
	<div class="fitMapMain row">
		<div id="routeMapContentWrapper" class="contentWrapper">
			<div id="routeMapContentColumn" class="contentColumn">
				<div class="fitMapInner">
					<input type="hidden" id="polyline" value="{{ $data->polyline }}">
					{{-- <div id="routeDetailMap" class="fitCanvas"></div> --}}
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
				</div>
			</div>
		</div>
		<div class="row m-t-10">
			<div class="col-xs-12 col-sm-8 col-sm-offset-2 elevation-graph">
				<h4 class="elevation-heading">
					<svg id="ic-elevation" viewBox="0 0 28 28"><path fill-rule="evenodd" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.052 19.948L6 23 22 7 9.052 19.948zM13 7h9v10"></path></svg> ELEVATION (FT)
				</h4>
				<hr>
				<div class="d-flex mb-2">

					<div>
						<h6 class=""> <span>Start</span> </h6>
						<h5 class=""><span>
							<span id="minValue" data-value="">0</span> 
							<span data-unit="">ft</span></span>
						</h5>
					</div>
					<div>
						<h6 class=""> <span>Max</span> </h6>
						<h5 class=""><span>
							<span id="maxValue" data-value="">0</span>
							<span data-unit="">ft</span></span>
						</h5>
					</div>
					<div>
						<h6 class="">
							<span>Gain</span>
						</h6>
						<h5 class="">
							<span><span id="gain_value" data-value="">0</span> 
							<span data-unit="">ft</span>
						</span>
					</h5>
				</div>

			</div>
			<canvas id="elevation_chart" height="100"></canvas>
		</div>
	</div>
	<input type="hidden" id="elevationData" value="{{$elevationData}}">
	<input type="hidden" id="elevationLevelData" value="{{$elevationLevelData}}">

<!-- challenge friends -->
	<div class="row view-challange">
		<div class="col-md-12">
			<div class="table-responsive">
				<table class="data-table table table-striped table-bordered table-hover m-t-10" >
				<thead>
					<tr>
						<th>S.No.</th>
						<th>Image</th>
						<th>Name</th>
					</tr>
				</thead>
				<tbody>
			
			  	 @foreach($challenge_data['challenge_friend'] as $key => $value)
					<tr>
						<td><span>{{ $key+1}}</span></td>
						<td>
					 	  <img src="{{asset('uploads/'.$value['client']['profilepic'])}}" onerror="this.onerror=null;this.src='{{asset('assets/images/no-image-icon.jpg')}}';" class="plyr-img">
						<!-- <img src="{{asset('assets/images/avatar-7.jpg')}}" class="plyr-img"> -->
						</td>
						<td>{{ucfirst($value['client']['firstname'])}} {{ucfirst($value['client']['lastname'])}}</td>
					</tr>
				 @endforeach
					<!-- <tr >
						<td>
							<span>2</span>
						</td>
						<td><img src="{{asset('assets/images/avatar-7.jpg')}}" class="plyr-img"></td>
						<td>Albert Eientsteen</td>
					</tr>
					<tr>
						<td>
							<span>3</span>
						</td>
						<td><img src="{{asset('assets/images/avatar-7.jpg')}}" class="plyr-img"></td>
						<td>Albert Eientsteen</td>
					</tr>
					<tr>
						<td>
							<span>4</span>
						</td>
						<td><img src="{{asset('assets/images/avatar-7.jpg')}}" class="plyr-img"></td>
						<td>Albert Eientsteen</td>
					</tr>
					<tr >
						<td>
							<span>5</span>
						</td>
						<td><img src="{{asset('assets/images/avatar-7.jpg')}}" class="plyr-img"></td>
						<td>Albert Eientsteen</td>
					</tr>
					<tr>
						<td>
							<span>6</span>
						</td>
						<td><img src="{{asset('assets/images/avatar-7.jpg')}}" class="plyr-img"></td>
						<td>Albert Eientsteen</td>
					</tr> -->
				</tbody>
			</table>

			</div>
		</div>

	</div>
	<!-- end challenge friends -->
	@stop

	@section('required-script')
	{!! Html::script('result/plugins/chartjs/dist/Chart.bundle.min.js') !!}
	<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCI9fgvBgIW52M1jvW5rWQ9LOSdweGy8kg&libraries=geometry"></script>
	{!! Html::script('result/js/fitness-core/datetimepicker.min.js') !!}
	{!! Html::script('result/js/fitness-mapper/jquery.fullscreen.js') !!}
	{!! Html::script('result/js/fitness-mapper/FitMapper.js') !!}
	<script>
		$(document).ready(function(){
			Fit.Mapper.Start( '', null );
			// $('.data-table').DataTable({"info":false,searching: false});
		});

		
	</script>
	@stop