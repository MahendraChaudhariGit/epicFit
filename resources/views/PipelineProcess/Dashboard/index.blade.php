@extends('layouts.app')

@section('meta_description')
@stop()

@section('meta_author')
@stop()

@section('meta')
@stop()

@section('before-styles-end')
@stop()

@section('required-styles-for-this-page')

{!! Html::style('css/app.css?id=7b4ff59559b29dba5f3c') !!}

@stop()

@section('required-styles-for-this-page')
<!-- start: Bootstrap Select Master -->
{!! Html::style('vendor/bootstrap-select-master/css/bootstrap-select.min.css') !!}
<!-- end: Bootstrap Select Master -->

<!-- start: Bootstrap timepicker -->
<!--{!! Html::style('vendor/bootstrap-datetimepicker/bootstrap-datetimepicker.min.css') !!}-->
<!-- end: Bootstrap timepicker -->

<!-- Start: Old timepicker css -->
{{-- {!! Html::style('assets/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css') !!} --}}
<!-- End: Old timepicker css -->

<!-- Start: NEW datetimepicker css -->
{!! Html::style('assets/plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css') !!}
{!! Html::style('assets/plugins/bootstrap-material-datetimepicker/css/custom-css-style.css') !!}
<!-- End: NEW datetimepicker css -->

<!-- start: Full Calendar -->
{!! Html::style('assets/plugins/fullcalendar-2.9.1/fullcalendar.min.css') !!}
<!-- end: Full Calendar -->

<!-- start: Sweet Alert -->
{!! Html::style('vendor/sweetalert/sweet-alert.css') !!}
<!-- end: Sweet Alert -->

@stop()

@section('required-styles-for-this-page')

{!! Html::style('css/app.css?id=7b4ff59559b29dba5f3c') !!}

@stop()


@section('content')
<div class="flex flex-col w-0 flex-1 overflow-hidden mt-0" style="display: contents;">
    <main tabindex="0" class="flex flex-col flex-1 relative z-0 overflow-y-auto pt-6 focus:outline-none">
      <div class="flex items-center px-4 mb-4 sm:px-6 lg:px-8">
      <h1 class="text-2xl font-semibold text-gray-900">Dashboard</h1>
      </div>  
          <div class="container mx-auto px-4 sm:px-6 md:px-8 py-4">
              <div class="mt-5 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4 mb-6">
                    <div class="bg-white overflow-hidden shadow rounded-lg">
                        <div class="px-4 py-5 sm:p-6">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 bg-blue-500 rounded-md text-white p-3">
                                      <svg viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 fill-current"><path d="M10 1l10 6-10 6L0 7l10-6zm6.67 10L20 13l-10 6-10-6 3.33-2L10 15l6.67-4z"></path>
                                      </svg>
                                    </div> 
                                      <div class="ml-5 w-0 flex-1">
                                        <dl><dt class="text-sm leading-5 font-medium text-gray-500 truncate">
                                                          Total Projects
                                              </dt> 
                                                <dd class="flex items-baseline">
                                                    <div class="text-2xl leading-8 font-semibold text-gray-900">
                                                            {{$projects}}
                                                      </div>
                                                </dd>
                                            </dl>
                                        </div>
                                </div>
                            </div>
                      </div> 

                        <div class="bg-white overflow-hidden shadow rounded-lg">
                            <div class="px-4 py-5 sm:p-6">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 bg-blue-500 text-white rounded-md p-3">
                                        <svg role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="h-6 w-6 fill-current">
                                            <path fill="currentColor" d="M48 48a48 48 0 1 0 48 48 48 48 0 0 0-48-48zm0 160a48 48 0 1 0 48 48 48 48 0 0 0-48-48zm0 160a48 48 0 1 0 48 48 48 48 0 0 0-48-48zm448 16H176a16 16 0 0 0-16 16v32a16 16 0 0 0 16 16h320a16 16 0 0 0 16-16v-32a16 16 0 0 0-16-16zm0-320H176a16 16 0 0 0-16 16v32a16 16 0 0 0 16 16h320a16 16 0 0 0 16-16V80a16 16 0 0 0-16-16zm0 160H176a16 16 0 0 0-16 16v32a16 16 0 0 0 16 16h320a16 16 0 0 0 16-16v-32a16 16 0 0 0-16-16z"></path>
                                        </svg>
                                    </div> 
                                    <div class="ml-5 w-0 flex-1">
                                        <dl>
                                            <dt class="text-sm leading-5 font-medium text-gray-500 truncate">
                                            Total Tasks
                                            </dt> 
                                            <dd class="flex items-baseline">
                                                <div class="text-2xl leading-8 font-semibold text-gray-900">
                                                {{$total_tasks}}
                                                </div>
                                            </dd>
                                        </dl>
                                    </div>
                                </div>
                            </div>
                        </div> 
                        <div class="bg-white overflow-hidden shadow rounded-lg">
                            <div class="px-4 py-5 sm:p-6">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 bg-blue-500 text-white rounded-md p-3">
                                        <svg role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="h-6 w-6 fill-current">
                                            <path fill="currentColor" d="M256 8C119 8 8 119 8 256s111 248 248 248 248-111 248-248S393 8 256 8zm0 448c-110.5 0-200-89.5-200-200S145.5 56 256 56s200 89.5 200 200-89.5 200-200 200zm61.8-104.4l-84.9-61.7c-3.1-2.3-4.9-5.9-4.9-9.7V116c0-6.6 5.4-12 12-12h32c6.6 0 12 5.4 12 12v141.7l66.8 48.6c5.4 3.9 6.5 11.4 2.6 16.8L334.6 349c-3.9 5.3-11.4 6.5-16.8 2.6z"></path>
                                        </svg>
                                    </div> 
                                    <div class="ml-5 w-0 flex-1">
                                        <dl>
                                            <dt class="text-sm leading-5 font-medium text-gray-500 truncate">
                                                Open Tasks
                                            </dt> 
                                            <dd class="flex items-baseline">
                                                <div class="text-2xl leading-8 font-semibold text-gray-900">
                                                {{$pending_tasks}}
                                                </div>
                                            </dd>
                                        </dl>
                                    </div>
                                </div>
                            </div>
                        </div> 
                        <div class="bg-white overflow-hidden shadow rounded-lg">
                            <div class="px-4 py-5 sm:p-6">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 bg-blue-500 text-white rounded-md p-3">
                                        <svg role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="h-6 w-6 text-white">
                                            <path fill="currentColor" d="M256 8C119.033 8 8 119.033 8 256s111.033 248 248 248 248-111.033 248-248S392.967 8 256 8zm0 48c110.532 0 200 89.451 200 200 0 110.532-89.451 200-200 200-110.532 0-200-89.451-200-200 0-110.532 89.451-200 200-200m140.204 130.267l-22.536-22.718c-4.667-4.705-12.265-4.736-16.97-.068L215.346 303.697l-59.792-60.277c-4.667-4.705-12.265-4.736-16.97-.069l-22.719 22.536c-4.705 4.667-4.736 12.265-.068 16.971l90.781 91.516c4.667 4.705 12.265 4.736 16.97.068l172.589-171.204c4.704-4.668 4.734-12.266.067-16.971z"></path>
                                        </svg>
                                    </div> 
                                    <div class="ml-5 w-0 flex-1">
                                        <dl>
                                            <dt class="text-sm leading-5 font-medium text-gray-500 truncate">
                                            Completed Tasks
                                            </dt> 
                                            <dd class="flex items-baseline">
                                                <div class="text-2xl leading-8 font-semibold text-gray-900">
                                                {{$complete_tasks}}
                                                </div>
                                            </dd>
                                        </dl>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> 
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div class="flex flex-col bg-white rounded-lg shadow">
                            <div class="p-4">
                                <div class="font-semibold">
                                    Most productive month was
                                    <span class="border-b-2 border-blue-300">September</span>
                                </div>
                            </div> 
                            <div class="p-4">
                                <div class="">
                                    <div class="chartjs-size-monitor">
                                        <div class="chartjs-size-monitor-expand">
                                            <div class="">
                                                </div>
                                            </div>
                                            <div class="chartjs-size-monitor-shrink">
                                                <div class=""></div>
                                            </div>
                                        </div>
                                        <canvas id="line-chart" width="428" height="400" style="display: block; width: 428px; height: 400px;" class="chartjs-render-monitor"></canvas>
                                    </div>
                                </div>
                            </div> 
                            <div class="flex flex-col bg-white rounded-lg shadow">
                                <div class="p-4">
                                    <span class="font-semibold">
                                        You have accomplished the most on
                                        <span class="border-b-2 border-blue-300">Monday</span>
                                    </span>
                                </div> 
                                <div class="p-4">
                                    <div class="">
                                        <div class="chartjs-size-monitor">
                                            <div class="chartjs-size-monitor-expand">
                                                <div class=""></div>
                                            </div>
                                            <div class="chartjs-size-monitor-shrink">
                                                <div class=""></div>
                                            </div>
                                        </div>
                                        <canvas id="line-chart" width="428" height="400" class="chartjs-render-monitor" style="display: block; width: 428px; height: 400px;"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
</div>


@stop

@section('required-script-for-this-page')
{!! Html::script('assets/js/jquery-ui.min.js') !!}

<!-- {!! Html::script('vendor/moment/moment.min.js') !!}
{!! Html::script('vendor/moment/moment-timezone-with-data.js') !!}
{!! Html::script('assets/js/set-moment-timezone.js?v='.time()) !!} -->

<!-- start: jquery validation -->
{!! Html::script('vendor/jquery-validation/jquery.validate.min.js') !!}
<!-- end: jquery validation -->

<!-- start: Bootstrap Select Master -->
{!! Html::script('vendor/bootstrap-select-master/js/bootstrap-select.min.js') !!}
<!-- end: Bootstrap Select Master -->

<!-- start: Bootstrap timepicker -->
<!--{!! Html::script('vendor/bootstrap-datetimepicker/bootstrap-datetimepicker.min.js') !!}-->
<!-- end: Bootstrap timepicker -->

<!-- Start:  Old timepicker js -->
{!! Html::script('assets/plugins/bootstrap-timepicker/js/bootstrap-timepicker.js') !!}
<!-- End: Old timepicker js --> 

<!-- Start:  NEW datetimepicker js -->
{!! Html::script('assets/plugins/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js') !!}
<!-- End: NEW datetimepicker js --> 

<!-- start: Country Code Selector -->
{!! Html::script('assets/plugins/intl-tel-input-master/build/js/utils.js') !!}
{!! Html::script('assets/plugins/intl-tel-input-master/build/js/intlTelInput.js') !!}
<!-- end: Country Code Selector -->

<!-- start: Bootstrap Typeahead -->
{!! Html::script('assets/plugins/bootstrap3-typeahead.min.js') !!}
<!-- end: Bootstrap Typeahead -->

<!-- start: Full Calendar -->
{!! Html::script('assets/plugins/fullcalendar-2.9.1/fullcalendar.min.js') !!}
<!-- end: Full Calendar -->

<!-- start: Sweet Alert -->
{!! Html::script('vendor/sweetalert/sweet-alert.min.js') !!}
<!-- end: Sweet Alert -->

<!-- start: Dirty Form -->
{!! Html::script('assets/js/dirty-form.js?v='.time()) !!}
<!-- end: Dirty Form -->

{!! Html::script('assets/js/helper.js?v='.time()) !!}

<!-- start: Events -->


<script src="{{asset('assets/js/events.js?v='.time())}}"></script>
<!-- end: Events -->

<!-- start: Full Calendar Custom Script -->
<script src="{{asset('assets/js/calendar.js?v='.time())}}"></script>

@stop()
@section('required-script-for-this-page')

    {!! Html::script('/js/app.js?id=652569a003aa16284bf7') !!}

@stop()


@section('script-handler-for-this-page')
@stop()

@section('script-after-page-handler')
@stop()