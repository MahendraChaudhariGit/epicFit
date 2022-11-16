
@extends('Result.masters.app')

@section('required-styles')

{!! Html::style('result/css/fitness-core/jquery-ui-1.10.1.custom.css') !!}
{!! Html::style('result/css/fitness-core/jquery.ui.labeledslider.css') !!}
{!! Html::style('result/css/fitness-core/datetimepicker.min.css') !!}
<!--{!! Html::style('css/fitness-core/jquery.ui.1.10.1.ie.css') !!} -->
{!! Html::style('result/css/fitness-mapper/FitMapper.css') !!}
<style type="text/css">

    .tabb > li.active a, .tabb > li.active a:hover, .tabb > li.active a:focus{
        border-width: 1px;

    }
    .my-route{
        width: 100%;
    }
    .map-btn{
      position: absolute;
      top: 77px;
      color: black;
    }
    .map-btn .active{
        color: gray;
    }
    .map-btn .mapp{
    border-right: 1px solid #dbdbdb;
    border-radius: 4px 0px 0px 4px;
    font-weight: 600;
    }
    .map-btn .satellite{
    border-radius: 0px 4px 4px 0px;
    font-weight: 600;
}
    .reset{
        background: none;
        border: 0;
        color: #f64c1e;
        font-weight: 600;
        margin-top: 5px;
        margin-left:5px;
    }
    select{
        -webkit-appearance: auto;
        -moz-appearance: auto;
        appearance: auto;
    }
    #data-table_paginate{
        float: left;
    }
    .d-color{
        color:#f64c1e; 
    }
    #data-table td{
        font-weight: 600;
    }
    .m-t-10{
        margin-top: 10px;
    }
    .action-btn{
        background: #f64c1e;
        color: white !important;
        padding: 5px 8px;
        border-radius: 4px;
        margin:5px;
        display: inline-block;
    }
    .div-tooltip {
        display: none;
        position: absolute;
        background-color: #000;
        padding: 10px;
        color: #fff;
        border-radius:3px;
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
<span>Fitness mapper</span>
@stop

@section('content')
<style>

</style>
<div id="alertMsg">
    {!! displayAlert()!!}

</div>
<h2 class="clearfix"><span></span> 
</h2>

    <a href="{{url('search/routes')}}" class="btn btn-primary filter">Search Route</a>
    <a href="{{ url('epic/train-gain/fitness-mapper') }}" class="btn btn-primary" style="float:right">Create Route</a>
    <hr>
<ul class="nav nav-tabs tabb">
  <li class="active"><a data-toggle="tab" href="#edit-route">Edit route</a></li>
</ul>
<div class="tab-content">
    <input type="hidden" id="polyline" value="{{ $data->polyline }}">
    <div id="edit-route" class="tab-pane fade in active">
       <div id="fitMap" class="fitMap">
          <div id="fitMapMainContainer" class="fitMapMain">
             <div id="fitMapContentWrapper" class="contentWrapper">
                <div id="fitMapContentColumn" class="contentColumn">
                   <div class="fitMapInner">
                      <div id="fitMapCanvas" class="fitCanvas"></div>
                   </div>
                </div>
             </div>
             <div class="main-box">
                <div class="col-md-4 col-xs-12 col-sm-4">
                   <input type="text" id="fitSearchAddress" value="{{ $data->city }}" class="searchInput" size="15" placeholder="Enter a location"/>
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
                         <li><a id="fitMapBtnSave" class="fitMapBtn" href="#"><i class="fa fa-check" aria-hidden="true"></i></a><span class="fitMapBtnText">Edit</span></li>
                      </ul>
                   </div>
                </div>
                
                <div class="col-md-2 col-xs-6 col-sm-2">
                   <div class="headerGradient">Workout <span class="caret"></span></div>
                   <div class="selectedWorkout" style="display: none">
                      <div>
                         <span>Workout</span>
                         <select id="selectedWorkout">
                            <option @if($data->workout == 1) selected @endif value="1">Running</option>
                            <option @if($data->workout == 3) selected @endif value="3">Walking</option>
                            <option @if($data->workout == 8) selected @endif value="8">Cycling</option>
                            <option @if($data->workout == 4) selected @endif value="4">Mountain Biking</option>
                            <option @if($data->workout == 2) selected @endif value="2">Swimming</option>
                            <option @if($data->workout == 5) selected @endif value="5">Kayaking</option>
                            <option @if($data->workout == 6) selected @endif value="6">Rowing</option>
                            <option @if($data->workout == 7) selected @endif value="7">Orienteering</option>
                            <option @if($data->workout == 9) selected @endif value="9">Other</option>
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
                         <input id="selectedDuration" value="{{ $data->duration }}" type="text" data-wmt="Enter Duration" />
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
                <button class="btn mapp active changeMap" data-map="roadmap">Map</button><button class="btn satellite changeMap" data-map="satellite">Satellite</button>
             </div>
             <div id="fitControlPanel" class="">
                <!--controlPanel-->
                <div class="fitControlPanelMapInner">
                   <div class="minimizeDiv"></div>
                   <div id="accordion" class="accordian">
                      
                      <div class="displayPanel workoutGradient">
                         <div class="ab1">
                            <div class="distanceDiv">
                               <div><img src="{{ asset('result/css/fitness-mapper/css/images/Control/i-1.png') }}" alt="Distance"></div>
                               <div class="upperDisplayText">
                                  <span id="fitDistance">0</span>
                                  <span id="fitDistanceType">Kms</span>
                               </div>
                            </div>
                            <div class="elevationDiv">
                               <div><img src="{{ asset('result/css/fitness-mapper/css/images/Control/i-2.png') }} " alt="Elevation"></div>
                               <div class="upperDisplayText"><span id="map-altitude">0m</span></div>
                            </div>
                            <!--div class="clear"></div-->
                         </div>
                         <!--div class="clear"></div-->
                         <div class="ab2">
                            <div class="caloriesDiv">
                               <div><img src="{{ asset('result/css/fitness-mapper/css/images/Control/i-3.png') }}" alt="Calories"></div>
                               <div class="caloriesText"><span id="map-calories">0 calories</span></div>
                            </div>
                            <div class="timeDiv">
                               <div><img src="{{ asset('result/css/fitness-mapper/css/images/Control/i-4.png') }} " alt="Time"></div>
                               <div class="displayText"><span id="map-time">0:00</span></div>
                            </div>
                            <div class="clear"></div>
                         </div>
                         <div class="clear"></div>
                      </div>
                      
                   </div>
                </div>
             </div>
             
          </div>
       </div>
    </div>
</div>

<div class="clear"></div>
<div class="chartContainer">
    <div id="fitChartDiv">
        <div id="fitAltChart" class="chart" ></div>
    </div>
    <div class="clear"></div>
    <div class="chartControl">
        <!--<img alt="Toggle" src='/wordpress/wp-content/plugins/fitness-mapper/css/images/control/up.jpg' id="chartToggle" class="toggleImage" />-->
    </div>
</div>


<div id="fitSaveDialog" title="Save Route and Workout" style="display: none;">

    <div id="fitMappedActivity">
        <div class="saveInfo">
            <div id="fitRoute" class="form-horizontal">
                <input type="hidden" id="map_id" value="{{ $data->id }}">
                <div class="form-group">
                    <label class="fitLabel control-label col-sm-2" for="fitRouteTitle">Title</label>
                    <div class="col-sm-10">
                        <input id="fitRouteTitle" class="form-control fitInput required" type="text" value="{{ $data->name }}" data-wmt="Enter a short route time" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="fitLabel control-label col-sm-2" for="fitRouteDesc">Description</label>
                    <div class="col-sm-10">
                        <input id="fitRouteDesc" class="form-control fitInputDesc required" type="text" cols="30" rows="2" value="{{ $data->description }}" data-wmt="Enter a description of your route" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="fitLabel control-label col-sm-2" for="fitRouteKeywords">Keywords</label>
                    <div class="col-sm-10">
                        <input id="fitRouteKeywords" class="form-control fitInput required" type="text" value="{{ $data->keywords }}" data-wmt="Optional tags, e.g. flat,hill,scenic,roads,footpaths,offroad,steep"  />
                    </div>
                </div>
            </div>        
            
        </div>
        <hr />

        <div class="workoutWrapper fit-ui">
            <div id="workoutDiv" class="workoutContainer">
                <div class="workoutPlanner">
                    <div id="popupBoxDiv" class="popupBox">
                        <div class="">
                            <div class="form-group">
                                <label class=" control-label col-sm-2" for="">Workout</label>
                                <select class="form-control" id="workoutSelector">
                                    <option @if($data->workout == 1) selected @endif value="1">Running</option>
                                    <option @if($data->workout == 3) selected @endif value="3">Walking</option>
                                    <option @if($data->workout == 8) selected @endif value="8">Cycling</option>
                                    <option @if($data->workout == 4) selected @endif value="4">Mountain Biking</option>
                                    <option @if($data->workout == 2) selected @endif value="2">Swimming</option>
                                    <option @if($data->workout == 5) selected @endif value="5">Kayaking</option>
                                    <option @if($data->workout == 6) selected @endif value="6">Rowing</option>
                                    <option @if($data->workout == 7) selected @endif value="7">Orienteering</option>
                                    <option @if($data->workout == 9) selected @endif value="9">Other</option>
                                </select>
                            </div>
                            <div class="form-group" id = "exerciseSelectorDiv">
                                <label class=" control-label col-sm-2" for="">Exercise</label>
                                <select  class="form-control" id="exerciseSelector">
                                    <option value="1">Easy Run</option>
                                    <option value="3">Long Run</option>
                                    <option value="8">Hill</option>
                                    <option value="4">Speed</option>
                                    <option value="2">Tempo Run</option>
                                    <option value="5">Fartlek</option>
                                    {{-- <option value="6">Easy Run</option>
                                    <option value="7">Long Run</option>
                                    <option value="1">Hill</option>
                                    <option value="5">Speed</option>
                                    <option value="8">Tempo Run</option>
                                    <option value="20">Fartlek</option>
                                    <option value="21">Easy</option>
                                    <option value="22">Hill</option>
                                    <option value="23">Interval</option>
                                    <option value="24">Long</option>
                                    <option value="25">Tempo</option>
                                    <option value="26">Race</option>
                                    <option value="17">Freestyle</option>
                                    <option value="11">Backstroke</option>
                                    <option value="12">Breaststroke</option>
                                    <option value="13">Butterfly</option>
                                    <option value="18">Sidestroke</option>
                                    <option value="19">Mixed</option> --}}
                                </select>
                            </div>
                            <div class="clearDiv">
                            </div>

                            <div class="form-group">
                                <label class=" control-label col-sm-2" for="">Date</label>
                                <input id="dateSelector"  class="form-control" type="text" />
                            </div>
                            <div class="form-group">
                                <label class=" control-label col-sm-2" for="">Duration</label>
                                <input id="durationSelector" value="{{ $data->duration }}" type="text" data-wmt="Enter Duration" class="form-control" />
                            </div>
                            <div class="clearDiv">
                            </div>
                        </div>
                    </div>
            <!-- <div class="workoutLoader">
                <img src="/wordpress/wp-content/plugins/fitness-workout-logger/css/wo_images/ajax-loader.gif" alt="loading" />
            </div> -->
        </div>

    </div>

</div>

@stop

@section('required-script')

<!-- <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script> -->
{!! Html::script('result/js/fitness-core/jquery.js?ver=1.12.4') !!}
{!! Html::script('result/js/fitness-core/jquery-migrate.min.js?ver=1.4.1') !!}
<script src="https://code.jquery.com/ui/1.10.1/jquery-ui.min.js" integrity="sha256-Nnknf1LUP3GHdxjWQgga92LMdaU2+/gkzoIUO+gfy2M=" crossorigin="anonymous"></script>


<!--{!! Html::script('js/fitness-core/bootstrap.min.js') !!}-->
<!-- {!! Html::script('js/fitness-core/map.js') !!}  -->
{!! Html::script('result/js/fitness-core/arraylist.js') !!}
{!! Html::script('result/js/fitness-core/utility.js') !!}
{!! Html::script('result/js/fitness-core/jquery.tinymce.js') !!}
{!! Html::script('result/js/fitness-core/fitness-core-clean.js') !!}
{!! Html::script('result/js/fitness-core/jquery.livequery.js') !!}
{!! Html::script('result/js/fitness-core/jquery.json-2.4.min.js') !!}
<!--{!! Html::script('js/fitness-core/modernizr-2.5.2.min.js') !!}-->
{!! Html::script('result/js/fitness-core/jquery.cookie.js') !!}
{!! Html::script('result/js/fitness-core/raphael.js') !!}
{!! Html::script('result/js/fitness-core/g.Raphael.min.js') !!}
{!! Html::script('result/js/fitness-core/g.pie.js') !!}
{!! Html::script('result/js/fitness-core/jquery.ui.labeledslider.js') !!}
{!! Html::script('result/js/fitness-core/datetimepicker.min.js') !!}
{!! Html::script('result/js/fitness-mapper/jquery.watermark.min.js') !!}
{!! Html::script('result/js/fitness-core/jquery.ui.combobox.js') !!}
{!! Html::script('result/js/fitness-core/jquery.ui.touch-punc.js') !!}


<script src="//maps.googleapis.com"></script>
<!--<script src="//fonts.googleapis.com"></script>-->
<script src="https://www.google.com/jsapi"></script>

<script src='https://maps.googleapis.com/maps/api/js?key=AIzaSyBXlJaQ-UcLSOuUXSoNUCpOmsm5Pe1a11A&sensor=false&libraries=geometry'></script>
<!-- <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBXlJaQ-UcLSOuUXSoNUCpOmsm5Pe1a11A&#038;sensor=false&#038;&libraries=geometry"></script> -->



{!! Html::script('result/js/fitness-mapper/jquery.fullscreen.js') !!}
{!! Html::script('result/js/fitness-mapper/FitMapper.js?v='.time()) !!}
<script type='text/javascript'>

        // Set a callback to run when the Google Visualization API is loaded.
        //google.setOnLoadCallback(Fit.Mapper.Initialize);

        // Load the Visualization API and the piechart package.
        //google.load("visualization", "1", { packages: ["columnchart"] });

        jQuery(document).ready( function() {
		    Fit.Mapper.Start( '', null );
            var url = window.location.pathname;
            
        });
	</script>
    <script type="text/javascript">
        $(".map-btn > .btn").click(function(){
    $(".map-btn > .btn").removeClass("active");
    $(this).addClass("active");
});
    </script>
    
  <script type="text/javascript">  jQuery(document).ready(function() {
    $('#data-table').DataTable({"info":false,searching: false});
});</script>
@stop