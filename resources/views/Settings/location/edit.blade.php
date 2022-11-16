@extends('Settings.business_setup')

@section('required-styles-for-this-page')
    @parent

    <!-- start: Bootstrap datetimepicker -->
    {!! Html::style('vendor/bootstrap-datetimepicker/bootstrap-datetimepicker.min.css') !!}
    <!-- end: Bootstrap datetimepicker -->
    
    <!-- Start: NEW timepicker css -->  
    {{-- {!! Html::style('assets/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css?v='.time()) !!} --}}
    <!-- End: NEW timepicker css -->
    {!! Html::style('assets/plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css') !!}
    {!! Html::style('assets/plugins/bootstrap-material-datetimepicker/css/custom-css-style.css') !!}

    <!-- start: JCrop -->
    {!! Html::style('assets/plugins/Jcrop/css/jquery.Jcrop.min.css') !!}
    <!-- end: JCrop -->
@stop

@if(!isset($subview))
    @section('page-title')
        @if(isset($location))
            Edit Location
        @elseif(isset($area))   
            Edit Area
        @elseif(isset($entityType))
            @if($entityType == 'location')
                Add Location
            @elseif($entityType == 'area')
                Add Area
            @endif
        @endif
    @stop
@endif

@section('form')
	<!-- start: Pic crop Model -->
    @include('includes.partials.pic_crop_model')
    <!-- end: Pic crop Model -->

    @include('Settings.location.form')
@stop

@section('script')
    <!-- start: JCrop -->
    {!! Html::script('assets/plugins/Jcrop/js/jquery.Jcrop.min.js') !!}
    {!! Html::script('assets/plugins/Jcrop/js/script.js') !!}
    <!-- end: JCrop -->

    <!-- Start:  NEW timepicker js -->
   {{-- {!! Html::script('assets/plugins/bootstrap-timepicker/js/bootstrap-timepicker.js?v='.time()) !!}  --}}
    {!! Html::script('assets/plugins/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js?v='.time()) !!}
    <!-- End: NEW timepicker js -->

    <script type="text/javascript">
        /* Start: New time picker */
        $('body').on('focus','.timepicker1',function(){
                $(this).bootstrapMaterialDatePicker({
                date: false,
                shortTime: true,
                format: 'hh:mm A',
            });
        });
        /* End: New time picker */
    </script>

@stop()