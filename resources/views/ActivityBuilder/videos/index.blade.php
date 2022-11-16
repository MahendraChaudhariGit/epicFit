@extends('blank')

@section('page-title')
    Videos list 
    @if(isUserType(['Admin']) && Auth::user()->hasPermission(Auth::user(), 'create-exercise')) 
        <a class="btn btn-primary pull-right" href="{{ route('videos.create') }}"><i class="ti-plus"></i> Add Videos</a>
    @endif
@stop

@section('content')
    {!! displayAlert()!!}

    @if(isUserType(['Admin']) && Auth::user()->hasPermission(Auth::user(), 'delete-exercise')) 
        <!-- start: Delete Form -->
        @include('includes.partials.delete_form')
        <!-- end: Delete Form -->
    @endif
    
    <div class="panel panel-default">
        <div class="panel-body">

            <div class="row">
            <!-- start: Datatable Header -->
            @include('includes.partials.datatable_header',['source' => 'actvity-video','abWorkouts' => $abWorkouts])
            <!-- end: Datatable Header -->
            </div>
            <!--<div class="table-responsive">-->
                <table class="table table-striped table-bordered table-hover m-t-10" id="exercise-datatable">
                    <thead>
                        <tr>
                            <th>Sr No</th>
                            <th>Video Title</th>
                            <th>Workout Type</th>
                            <th>Video Duration</th>
                            <th class="center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(isUserType(['Admin']) && Auth::user()->hasPermission(Auth::user(), 'list-exercise')) 
                        @foreach($videos as $video)
                        <tr>
                            <td>{{$loop->iteration}}</td>
                            <td>
                            {{ $video->title }}
                            </td>
                            <td>{{$video->workout->desc}}</td>
                            <td>{{$video->video_duration}}</td>
                            <td class="center">
                                <div>
                                <a class="btn btn-xs btn-default tooltips viewModal" data-placement="top" data-original-title="View" data-video-id="{{$video->id}}" data-video-url="{{$video->video}}">
                                        <i class="fa fa-share text-primary"></i>
                                    </a>
                                    @if(isUserType(['Admin']) && Auth::user()->hasPermission(Auth::user(), 'edit-exercise')) 
                                    <a class="btn btn-xs btn-default tooltips" href="{{ route('videos.edit', $video->id) }}" data-placement="top" data-original-title="Edit">
                                        <i class="fa fa-pencil text-primary"></i>
                                    </a>
                                    @endif

                                    @if(isUserType(['Admin']) && Auth::user()->hasPermission(Auth::user(), 'delete-exercise')) 
                                    <a class="btn btn-xs btn-default tooltips delLink" href="{{ route('videos.destroy', $video->id) }}" data-placement="top" data-original-title="Delete" data-entity="video">
                                        <i class="fa fa-trash-o text-primary"></i>
                                    </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                        @endif  
                    </tbody>
                </table> 
                <!-- start: Paging Links -->
                    @include('includes.partials.paging', ['entity' => $videos]) 
                <!-- end: Paging Links -->
            <!--</div>-->
        </div>
    </div>    
    <!-- Modal -->
    <div class="modal fade" id="viewModal" tabindex="-1" role="dialog" aria-labelledby="viewModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewModalLabel">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                        <video controls class="video" height="300" width="100%" id="myVideo"></video>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            {!! Form::label('title', 'Description ', ['class' => 'strong']) !!}
                            <p id="description"></p>
                        </div>
                        <div class="form-group">
                            <table class="tb-movement">
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
@stop

@section('script')
    <script>
    var cookieSlug = "videos";
    $.fn.dataTable.moment('ddd, D MMM YYYY');
    $('#exercise-datatable').dataTable({"searching": false, "paging": false, "info": false });
    </script>
    {!! Html::script('assets/js/helper.js?v='.time()) !!}
    <!-- Start: Movement -->
    {!! Html::script('assets/js/movement.js?v='.time()) !!}
    <!-- End: Movement -->

    {!! Html::script('assets/plugins/Jcrop/js/jquery.Jcrop.min.js') !!}
    {!! Html::script('assets/plugins/Jcrop/js/script.js') !!}

   <!-- Start: Activity Planner -->
    {!! Html::script('assets/js/fitness-planner/api.js?v='.time()) !!} 
    {!! Html::script('assets/js/fitness-planner/bodymapper.js?v='.time()) !!}
    {!! Html::script('assets/plugins/fitness-planner/jquery.json-2.4.min.js') !!}
    {!! Html::script('assets/plugins/fitness-planner/custom/js/jquery.placeholder.js') !!}
    {!! Html::script('assets/plugins/fitness-planner/custom/js/jquery.ui.touch-punch.min.js') !!}
    {!! Html::script('assets/plugins/fitness-planner/custom/jwplayer/jwplayer.js') !!}
    {!! Html::script('assets/plugins/fitness-planner/js/jquery.ui.labeledslider.js') !!}
    {!! Html::script('assets/plugins/fitness-planner/custom/js/popup.js?v=1') !!}
    {!! Html::script('assets/js/fitness-planner/fitness-planner.js?v='.time()) !!}
    <!-- ENd: Activity Planner -->
    <script>
        jQuery(document).ready(function() {
            //Metronic.init();
            $( ".panel-collapse.closed" ).trigger( "click" );
        });
    </script>
    <script>
        $('body').on('click','.viewModal',function(){
            var videoId = $(this).data('video-id');
            toggleWaitShield("show");
            $.get("{{url('activity-builder/videos/view')}}"+"/"+videoId,function(response){
                toggleWaitShield("hide");
                if(response.status == 'ok'){
                    $('#viewModalLabel').text(response.title);
                    $('#description').text(response.description);
                    var movementHtml = '';
                    $.each(response.movementData,function(key,value){
                        movementHtml += '<tr>\
                                            <td>'+value.name+'</td>\
                                            <td>'+value.time+'</td>\
                                        </tr>';
                    });
                    $('.tb-movement').empty();
                    $('.tb-movement').append(movementHtml);
                    var video = $('#myVideo')[0];
                    video.src = "{{url('uploads')}}/"+response.videoUrl;
                    video.load();
                    $('#viewModal').modal('show');
                }
            },'json')
        })
        $("#viewModal").on('hide.bs.modal', function(){
            var video = $('#myVideo')[0];
            video.pause();
        });
    </script>

    {!! Html::script('assets/js/fitness-planner/generator-helper.js?v='.time()) !!}

@stop