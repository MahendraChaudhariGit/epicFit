@extends('blank')

@section('page-title')
    Exercise list 
    @if(isUserType(['Admin']) && Auth::user()->hasPermission(Auth::user(), 'create-exercise')) 
        <a class="btn btn-primary pull-right" href="{{ route('exercise.create') }}"><i class="ti-plus"></i> Add Exercise</a>
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
                    @include('includes.partials.datatable_header')
                    <!-- end: Datatable Header -->
                    </div>
                    <!--<div class="table-responsive">-->
                        <table class="table table-striped table-bordered table-hover m-t-10" id="exercise-datatable">
                            <thead>
                                <tr>
                                    <th>Thumbnail image</th>
                                    <th>Exercise Name</th>
                                    <th>Sub Heading</th>
                                    <th class="center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(isUserType(['Admin']) && Auth::user()->hasPermission(Auth::user(), 'list-exercise')) 
                                @foreach($exercises as $exercise)
                                <tr>
                                    @php
                                    $video = $exercise->exevideos()->where('type',1)->first();
                                    @endphp
                                    @if(isset($video) && $video->thumbnail_program != '' && $video->thumbnail_program != null)
                                    <td>
                                    <img src="{{ dpSrc($video->thumbnail_program) }}" width="108" height="60">
                                    </td>
                                    @else
                                    <td></td>
                                     @endif
                                    <td>
                                    {{ $exercise->name }}
                                    </td>
                                    <td>{{ $exercise->sub_heading }}</td>
                                    <td class="center">
                                        <div>
                                            
                                            <a data-toggle="modal" class="lungemodalCls" data-exercise-name="{{$exercise->name}}" data-exeid="{{$exercise->id}}">  
                                            <span class="btn btn-xs btn-default tooltips" data-placement="top" data-original-title="View exercise modal"><i class="fa fa-external-link text-primary"></i></span></a>

                                            <a href="{{ route('exercise.show', $exercise->id) }}" class="btn btn-xs btn-default tooltips" data-placement="top" data-original-title="View" ><i class="fa fa-share text-primary"></i></a>

                                            @if(isUserType(['Admin']) && Auth::user()->hasPermission(Auth::user(), 'edit-exercise')) 
                                            <a class="btn btn-xs btn-default tooltips" href="{{ route('exercise.edit', $exercise->id) }}" data-placement="top" data-original-title="Edit">
                                                <i class="fa fa-pencil text-primary"></i>
                                            </a>

                                            @endif

                                            @if(isUserType(['Admin']) && Auth::user()->hasPermission(Auth::user(), 'delete-exercise')) 
                                            <a class="btn btn-xs btn-default tooltips delLink" href="{{ route('exercise.destroy', $exercise->id) }}" data-placement="top" data-original-title="Delete" data-entity="exercise">
                                                <i class="fa fa-trash-o text-primary"></i>
                                            </a>
                                            @endif
                                            

                                             <a class="btn btn-primary pull-right" href="{{ route('exercise.clone', $exercise->id) }}" ><i class="ti-plus"></i> Clone</a>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                               
                                @endif  
                            </tbody>
                        </table> 
                        <!-- start: Paging Links -->
                            @include('includes.partials.paging', ['entity' => $exercises]) 
                        <!-- end: Paging Links -->

                        <!-- start: plan design -->
                        @include('includes.partials.listing_exercise_modal',['exerciseData'=>$exerciseData])
                    <!--</div>-->
                </div>
            </div>
@stop

@section('script')
    <script>
    var cookieSlug = "exercise";
    $.fn.dataTable.moment('ddd, D MMM YYYY');
    $('#exercise-datatable').dataTable({"searching": false, "paging": false, "info": false });
    </script>
    {!! Html::script('assets/js/helper.js?v='.time()) !!}








    {!! Html::script('assets/plugins/bootstrap-timepicker/js/bootstrap-timepicker.js') !!}
    <!-- End: NEW timepicker js --> 

    <!-- Start: Movement -->
    {!! Html::script('assets/js/movement.js?v='.time()) !!}
    <!-- End: Movement -->

    {!! Html::script('assets/plugins/Jcrop/js/jquery.Jcrop.min.js') !!}
    {!! Html::script('assets/plugins/Jcrop/js/script.js?v='.time()) !!}

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

    {!! Html::script('assets/js/fitness-planner/generator-helper.js?v='.time()) !!}

@stop