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

<!-- End: NEW datetimepicker css -->

<!-- start: Full Calendar -->
{!! Html::style('assets/plugins/fullcalendar-2.9.1/fullcalendar.min.css') !!}
<!-- end: Full Calendar -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.2.3/flatpickr.css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
<style type="text/css">

h2 {
    display: none !important;
}
#calendar .fixed{
	padding-top: 50px;
}
input[type="file"], #page-title{
    display:none;
}
label{
    cursor:pointer;
}
.deleteColumnId btn-danger{
    background-color: #e02424!important; 
    overflow:visible!important;
}
#calendar{
    padding-top: 20px;
}
#calendar.process_calender .fc-button{
    border-radius: 0px !important;
}
.fc button .fc-icon{
    font-size: 1em;
    font-family: inherit !important;
}
.fc-basic-view .fc-body .fc-row{
    max-height: 74px;
}
.fc-basic-view .fc-body .fc-row {
    max-height: none;
    height: auto !important;
}
</style>

@stop()
@section('page-title')
Calendar
@stop


@section('content')
<input type="hidden" id="session-data" value="{{ session()->get('adminData')->account_type }}">
<div class="response"></div>
<div id='calendar' class="process_calender"></div>  
<!-- start: Calendar Jumper -->
<div class="btn-group calJumper">
    <a class="btn btn-primary btn-o dropdown-toggle hidden" data-toggle="dropdown" href="javascript:void(0)">
        <i class="fa fa-angle-double-left"></i>
    </a>
    <ul role="menu" class="dropdown-menu dropdown-light">
        <li>
            <a href="javascript:void(0)" class="filter-date" data-jump-amount="1" data-jump-unit="weeks">
                1 week
            </a>
        </li>
        <li>
            <a href="javascript:void(0)" class="filter-date" data-jump-amount="2" data-jump-unit="weeks">
                2 weeks
            </a>
        </li>
        <li>
            <a href="javascript:void(0)" class="filter-date" data-jump-amount="3" data-jump-unit="weeks">
                3 weeks
            </a>
        </li>
        <li>
            <a href="javascript:void(0)" class="filter-date" data-jump-amount="4" data-jump-unit="weeks">
                4 weeks
            </a>
        </li>
        <li>
            <a href="javascript:void(0)" class="filter-date" data-jump-amount="5" data-jump-unit="weeks">
                5 weeks
            </a>
        </li>
        <li>
            <a href="javascript:void(0)" class="filter-date" data-jump-amount="6" data-jump-unit="weeks">
                6 weeks
            </a>
        </li>
        <li>
            <a href="javascript:void(0)" class="filter-date" data-jump-amount="7" data-jump-unit="weeks">
                7 weeks
            </a>
        </li>
        <li>
            <a href="javascript:void(0)" class="filter-date" data-jump-amount="8" data-jump-unit="weeks">
                8 weeks
            </a>
        </li>
        <li>
            <a href="javascript:void(0)" class="filter-date" data-jump-amount="6" data-jump-unit="months">
                6 months
            </a>
        </li>
        <li>
            <a href="javascript:void(0)" class="filter-date" data-jump-amount="1" data-jump-unit="years">
                1 year
            </a>
        </li>
    </ul>
</div>
<!-- end: Calendar Jumper -->

@php
use App\Models\PipelineProcess\Column;
use App\Models\PipelineProcess\Attachment;
use App\Models\PipelineProcess\Comment;
use App\Models\PipelineProcess\Project;
use App\Models\PipelineProcess\PipelineProcessTask;
use App\Models\PipelineProcess\EpicProcess;
use App\Staff;
use Auth;
@endphp
<input type="hidden" name="" id="add-sub-task-due-date" value="">
<input type="hidden" name="" id="add-sub-task-assign-user" value="">
@foreach ($tasks as $key => $task)
@if (!empty($task->parent->clients->firstname))
<div class="commentOnColumnPopup fixed inset-0 max-h-full overflow-y-auto p-6 z-40" id="task-popup{{ $task->id }}"
    style="background-color: rgba(0, 0, 0, 0.4); display:none;margin-top:60px;" >
    <div class="deleteTaskModal fixed inset-0 max-h-full overflow-y-auto p-6 z-40"
    style="background-color: rgba(0, 0, 0, 0.4); margin-top:78px; display:none;">
        <div class="mx-auto max-w-xl">
            <div class="mx-auto max-w-lg">
                <div class="flex flex-col rounded-lg bg-white shadow">
                    <div class="p-6">
                        <h3 class="font-semibold text-lg">Confirmation</h3>
                    </div>
                    <div class="p-6 " style="font-size:.877rem; opacity:2; font-weight:500;">
                        Do you want to permanently delete this task?
                    </div>
                    <div class="p-6 flex justify-end">
                        <button type="button" class="hideDeleteTaskPopup btn btn-flat mr-3">Cancel</button>
                        <button type="button" class="deleteTaskId btn btn-danger" name="id" value="{{ $task->id }}" style="hover:red;">Confirm</button>
                    </div>
                </div>
            </div>
        </div>

    </div>
    @php
        // $client = App\Clients::select('firstname','lastname','id')->where('id',$task->content)->first();
        // if($task->parent->column->name == "COMPLIMENTARY TRAINING"){
        //     $disable1 = 'disabled';
        // }else{
        //     $disable1 = '';
        // }
    @endphp
    <div class="mx-auto max-w-xl">
        <div class="bg-white rounded-lg shadow-xl transform transition-all sm:max-w-xl sm:w-full">
            <div class="row">
                <div class="col-md-8 col-md-offset-2 pt-6 pb-3 text-center text-bold">
                <div class="cursor-pointer mb-0 editTask client-name-head">
                    {{ $task->parent->clients->firstname }} {{ $task->parent->clients->lastname }}
                </div>
            <div class="showEditTask mt-1 flex rounded-md shadow-sm">
            <div class="relative flex-grow focus-within:z-10">
                <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
                <input type="hidden" class="task-id" value="{{ $task->task_id }}">
                {{-- <input class="task-name form-input block w-full rounded-none rounded-l-md transition ease-in-out duration-150 sm:text-sm sm:leading-5" value="{{ $task->content }}"> --}}
                <select class="form-control task-name{{ $task->task_id }}">
                    <option value="">select client</option>
                    {{-- <option selected value="{{ $task->parent->content }}">{{ $task->parent->clients->firstname }} {{ $task->parent->clients->lastname }}</option> --}}
                    @foreach ($clients as $client_val)
                    <option @if($client_val['id'] == $task->parent->content) selected @endif value="{{ $client_val['id'] }}">{{ $client_val['name'] }}</option>
                    @endforeach
                </select> 
            </div>
            <span class="btn-group">
                <button type="button" class="hideEditTask btn btn-white rounded-l-none border-l-0">
                    <svg fill="currentColor" viewBox="0 0 20 20" class="h-4 w-4 text-gray-400">
                        <path d="M10 8.586L2.929 1.515 1.515 2.929 8.586 10l-7.071 7.071 1.414 1.414L10 11.414l7.071 7.071 1.414-1.414L11.414 10l7.071-7.071-1.414-1.414L10 8.586z"></path>
                    </svg>
                </button> 
                <button type="button" class="btn btn-white saveTask" task-id="{{ $task->task_id }}">
                    <svg fill="currentColor" viewBox="0 0 20 20" class="h-4 w-4 text-gray-400">
                        <path d="M0 11l2-2 5 5L18 3l2 2L7 18z"></path>
                    </svg>
                </button>
            </span>
        </div></div>

    </div>
            <div class="flex items-center px-6 pb-4">
                <div>
                    <input type="checkbox" class="contentComplete form-checkbox w-6 h-6 rounded-full text-green-400" id="{{$task->id}}" 
                    @if ($task->completed_at != NULL) checked @endif >
                </div>
                @php
                $client = App\Clients::select('id','sale_process_setts')->where('id',$task->parent->content)->first();
                $calendar_setting = App\CalendarSetting::select('cs_client_id','sales_process_settings')->where('cs_business_id',Session::get('businessId'))->select('sales_process_settings')->first();
                if($client->sale_process_setts != null || $client->sale_process_setts != ''){
                    $json = (json_decode($client->sale_process_setts));
                }else{
                    $json = (json_decode($calendar_setting->sales_process_settings));
                }

                // if(strtoupper($task->parent->column->name) != strtoupper("COMPLIMENTARY TRAINING")){
                //     $disable = 'disabled';
                //     $hidden = 'none';
                // }else{
                //     $disable = '';
                //     $hidden = '';
                // }
                
                // if($json->teamCount > 0 || $json->indivCount > 0){
                //     $status_options = 'status-options';
                // }
                // else{
                //     $status_options = '';
                // }
                
            @endphp
                <div class="pl-3 checktask w-full">
                    <div class="block w-full">
                    <div class="relative">
                        <div class="change-cloumn-div">
                            <a href="javascript:void(0)"
                                class="selected_priority priority status-options flex items-center text-xs text-gray-600 hover:underline">
                                <span class="selected_column{{$task->parent->column->id}}{{$task->task_id}}">
                                    {{ $task->parent->column->name}}
                                </span>
                            </a>
                        </div>
                        <div class="priorityPopup selectpriorityPopup status-div-toggle origin-top-right absolute mt-2 w-auto rounded-md shadow-lg z-40 left-0"
                            style="display: none;">
                        @php
                            $epic_process = EpicProcess::where('pipeline_process_task_id',$task->task_id)->where('column_id',$task->parent->column->id)->first();
                            $data = explode(',',$epic_process->sales_group);
                        @endphp
                        @if (strtoupper($task->parent->column->name) == strtoupper('COMPLIMENTARY TRAINING'))
                            
                        {{-- @if($json->teamCount > 0 || $json->indivCount > 0) --}}
                    <div class="w-full bg-white shadow-xs hide{{$task->parent->column->id}}{{$task->task_id}}" style="display: {{$hidden}}">
                            <div class="flex flex-col px-4">
                            <div class="flex items-center py-1 mb-1 rounded-lg">
                            @php
                                // $data = explode(',',$task->sales_group);
                                if (in_array("complementary_teams",$data, TRUE)) {
                                    $check11 = 'checked';
                                }else{
                                    $check11 = '';
                                }
                            @endphp
                                <div>
                                    <input id="processCheckBox1{{$task->id}}complementary_teams{{ $task->parent->column->id }}" type="checkbox" {{ $check11 }} proccess-data="complementary_teams"
                                    class="processCheckBox1{{$task->id}}complementary_teams{{ $task->parent->column->id }} processCheckBox1{{$task->id}}{{ $task->parent->column->id }} form-checkbox w-6 h-6 rounded-full text-green-400" 
                                    onclick="processCheckMe1({{ $task->id }},{{ $task->parent->column->id }},'complementary_teams',{{($json->teamCount ? $json->teamCount : 0) + ($json->indivCount ? $json->indivCount : 0) + 1}})">
                                </div>
                                
                                <div class="pl-3 w-full">
                                    <div class="w-full text-sm">
                                        <label for="processCheckBox1{{$task->id}}complementary_teams{{ $task->parent->column->id }}" class="cursor-pointer mb-0">
                                            Complementary Teams
                                        </label>
                                    </div>
                                </div>
                                    
                            </div>
                        </div>
                        <div class="flex px-4 py-2 mb-1 pt-4 rounded-lg">
                        @if ($json->teamCount > 0)
                        <div class="flex flex-col">
                            <div class="pb-2 border-b border-gray-200 mb-1">
                                <h4 class="font-medium text-lg"> Team ({{ $json->teamCount }})</h4>
                            </div>
                            @for ($i = 1; $i <= $json->teamCount; $i++)
                            @php
                                // $data = explode(',',$task->sales_group);
                                if (in_array("team$i",$data, TRUE)) {
                                    $check = 'checked';
                                }else{
                                    $check = '';
                                }
                            @endphp
                            <div class="flex items-center py-2 mb-1 rounded-lg">
                                <div>
                                    <input id="processCheckBox1{{$task->id}}team{{ $i }}{{ $task->parent->column->id }}" type="checkbox" {{$check}} 
                                    class="processCheckBox1{{$task->id}}team{{ $i }}{{ $task->parent->column->id }} processCheckBox1{{$task->id}}{{ $task->parent->column->id }} form-checkbox w-6 h-6 rounded-full text-green-400" 
                                    onclick="processCheckMe1({{ $task->id }},{{ $task->parent->column->id }},'team{{ $i }}',{{($json->teamCount ? $json->teamCount : 0) + ($json->indivCount ? $json->indivCount : 0) + 1}})">
                                </div>
                                
                                <div class="pl-3 w-full">
                                    <div class="w-full text-sm">
                                        <label for="processCheckBox1{{$task->id}}team{{ $i }}{{ $task->parent->column->id }}" class="cursor-pointer mb-0">
                                            TEAM {{$i}} 
                                        </label>
                                    </div>
                                </div>
                            </div>
                            @endfor
                        </div>
                        @endif

                        @if ($json->indivCount > 0)
                        <div class="flex flex-col ml-5">
                            <div class="pb-2 border-b border-gray-200 mb-1">
                                <h4 class="font-medium text-lg">Individuals ({{ $json->indivCount }})</h4>
                            </div>
                            @for ($i = 1; $i <= $json->indivCount; $i++)
                            @php
                                // $data = explode(',',$task->sales_group);
                                if (in_array("indiv$i",$data, TRUE)) {
                                    $check = 'checked';
                                }else{
                                    $check = '';
                                }
                            @endphp
                            <div class="flex items-center py-2 mb-1 hover:bg-gray-100 rounded-lg">
                                
                                <div>
                                    <input id="processCheckBox1{{$task->id}}indiv{{ $i }}{{ $task->parent->column->id }}" type="checkbox" {{$check}} 
                                    class="processCheckBox1{{$task->id}}indiv{{ $i }}{{ $task->parent->column->id }} processCheckBox1{{$task->id}}{{ $task->parent->column->id }} form-checkbox w-6 h-6 rounded-full text-green-400" 
                                    onclick="processCheckMe1({{ $task->id }},{{ $task->parent->column->id }},'indiv{{ $i }}',{{($json->teamCount ? $json->teamCount : 0) + ($json->indivCount ? $json->indivCount : 0) + 1}})">
                                </div>
                                
                                <div class="pl-3 w-full">
                                    <div class="w-full text-sm">
                                        <label for="processCheckBox1{{$task->id}}indiv{{ $i }}{{ $task->parent->column->id }}" class="cursor-pointer mb-0">
                                            INDIVIDUAL {{ $i }}
                                        </label>
                                    </div>
                                </div>
                                
                            </div>
                            @endfor
                        </div>
                        @endif                                                    
                    </div>
                    
                </div>
                    {{-- @endif --}}
                    @endif     
                       
                    @if (strtoupper($task->parent->column->name) == strtoupper('CONSULTATION'))
                    <div class="w-full bg-white shadow-xs hide{{$task->parent->column->id}}{{$task->task_id}}" style="display: {{$hidden}}">
                    <div class="flex px-4 py-2 mb-1 pt-4 rounded-lg">
                        <div class="flex flex-col mb-4">
                            <div class="flex items-center py-2 mb-1 rounded-lg">
                                <div class="pl-3">
                                    <input id="processCheckBox1{{$task->id}}consulation1{{ $task->parent->column->id }}" type="checkbox" proccess-data='consulation1' @if(in_array("consulation1",$data, TRUE)) checked @endif
                                    class="processCheckBox1{{$task->id}}consulation1{{ $task->parent->column->id }} processCheckBox1{{$task->id}}{{ $task->parent->column->id }} form-checkbox w-6 h-6 rounded-full text-green-400" 
                                    onclick="processCheckMe1({{ $task->id }},{{$task->parent->column->id }},'consulation1',3)">
                                </div>
                                
                                <div class="pl-3 w-full">
                                    <div class="w-full text-sm">
                                        <label for="processCheckBox1{{$task->id}}consulation1{{ $task->parent->column->id }}" class="cursor-pointer mb-0 mb-0">
                                            Consultation 
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center py-2 mb-1 rounded-lg">

                                <div class="pl-3">
                                    <input id="processCheckBox1{{$task->id}}consulation2{{ $task->parent->column->id }}" type="checkbox" proccess-data='consulation2' @if(in_array("consulation2",$data, TRUE)) checked @endif
                                    class="processCheckBox1{{$task->id}}consulation2{{ $task->parent->column->id }} processCheckBox1{{$task->id}}{{ $task->parent->column->id }} form-checkbox w-6 h-6 rounded-full text-green-400" 
                                    onclick="processCheckMe1({{ $task->id }},{{$task->parent->column->id }},'consulation2',3)">
                                </div>
                                
                                <div class="pl-3 w-full">
                                    <div class="w-full text-sm">
                                        <label for="processCheckBox1{{$task->id}}consulation2{{ $task->parent->column->id }}" class="cursor-pointer mb-0 mb-0">
                                            Movement Analysis
                                        </label>
                                    </div>
                                </div>
                            </div>
                                <div class="flex items-center py-2 mb-1 rounded-lg">

                                <div class="pl-3">
                                    <input id="processCheckBox1{{$task->id}}consulation3{{ $task->parent->column->id }}" type="checkbox" proccess-data='consulation3' @if(in_array("consulation3",$data, TRUE)) checked @endif
                                    class="processCheckBox1{{$task->id}}consulation3{{ $task->parent->column->id }} processCheckBox1{{$task->id}}{{ $task->parent->column->id }} form-checkbox w-6 h-6 rounded-full text-green-400" 
                                    onclick="processCheckMe1({{ $task->id }},{{$task->parent->column->id }},'consulation3',3)">
                                </div>
                                
                                <div class="pl-3 w-full">
                                    <div class="w-full text-sm">
                                        <label for="processCheckBox1{{$task->id}}consulation3{{ $task->parent->column->id }}" class="cursor-pointer mb-0">
                                            Introduction to movement
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    </div>
                    @endif
                    @if (strtoupper($task->parent->column->name) == strtoupper('BENCHMARK'))
                    <div class="w-full bg-white shadow-xs hide{{$task->parent->column->id}}{{$task->task_id}}" style="display: {{$hidden}}">
                        <div class="flex px-4 py-2 mb-1 pt-4 rounded-lg">
                            <div class="flex flex-col mb-4">
                                <div class="flex items-center py-2 mb-1 rounded-lg">
                                    <div class="pl-3">
                                        <input id="processCheckBox1{{$task->id}}benchmark1{{ $task->parent->column->id }}" type="checkbox" proccess-data='benchmark1' @if(in_array("benchmark1",$data, TRUE)) checked @endif
                                        class="processCheckBox1{{$task->id}}benchmark1{{ $task->parent->column->id }} processCheckBox1{{$task->id}}{{ $task->parent->column->id }} form-checkbox w-6 h-6 rounded-full text-green-400" 
                                        onclick="processCheckMe1({{ $task->id }},{{$task->parent->column->id }},'benchmark1',2)">
                                    </div>
                                    
                                    <div class="pl-3 w-full">
                                        <div class="w-full text-sm">
                                            <label for="processCheckBox1{{$task->id}}benchmark1{{ $task->parent->column->id }}" class="cursor-pointer mb-0">
                                                Benchmark
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex items-center py-2 mb-1 rounded-lg">

                                    <div class="pl-3">
                                        <input id="processCheckBox1{{$task->id}}benchmark2{{ $task->parent->column->id }}" type="checkbox" proccess-data='benchmark2' @if(in_array("benchmark2",$data, TRUE)) checked @endif
                                        class="processCheckBox1{{$task->id}}benchmark2{{ $task->parent->column->id }} processCheckBox1{{$task->id}}{{ $task->parent->column->id }} form-checkbox w-6 h-6 rounded-full text-green-400" 
                                        onclick="processCheckMe1({{ $task->id }},{{$task->parent->column->id }},'benchmark2',2)">
                                    </div>
                                    
                                    <div class="pl-3 w-full">
                                        <div class="w-full text-sm">
                                            <label for="processCheckBox1{{$task->id}}benchmark2{{ $task->parent->column->id }}" class="cursor-pointer mb-0">
                                                Introduction to movement
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </div>
                    @endif

                    @if (strtoupper($task->parent->column->name) == strtoupper('POSTURE ANALYSIS 1'))
                    <div class="w-full bg-white shadow-xs hide{{$task->parent->column->id}}{{$task->task_id}}" style="display: {{$hidden}}">
                        <div class="flex px-4 py-2 mb-1 pt-4 rounded-lg">
                            <div class="flex flex-col mb-4">
                                <div class="flex items-center py-2 mb-1 rounded-lg">
                                    <div class="pl-3">
                                        <input id="processCheckBox1{{$task->id}}posture_analysis11{{ $task->parent->column->id }}" type="checkbox" proccess-data='posture_analysis11' @if(in_array("posture_analysis11",$data, TRUE)) checked @endif
                                        class="processCheckBox1{{$task->id}}posture_analysis11{{ $task->parent->column->id }} processCheckBox1{{$task->id}}{{ $task->parent->column->id }} form-checkbox w-6 h-6 rounded-full text-green-400" 
                                        onclick="processCheckMe1({{ $task->id }},{{$task->parent->column->id }},'posture_analysis11',2)">
                                    </div>
                                    
                                    <div class="pl-3 w-full">
                                        <div class="w-full text-sm">
                                            <label for="processCheckBox1{{$task->id}}posture_analysis11{{ $task->parent->column->id }}" class="cursor-pointer mb-0">
                                                Posture Analysis 
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                    <div class="flex items-center py-2 mb-1 rounded-lg">

                                    <div class="pl-3">
                                        <input id="processCheckBox1{{$task->id}}posture_analysis12{{ $task->parent->column->id }}" type="checkbox" proccess-data='posture_analysis12' @if(in_array("posture_analysis12",$data, TRUE)) checked @endif
                                        class="processCheckBox1{{$task->id}}posture_analysis12{{ $task->parent->column->id }} processCheckBox1{{$task->id}}{{ $task->parent->column->id }} form-checkbox w-6 h-6 rounded-full text-green-400" 
                                        onclick="processCheckMe1({{ $task->id }},{{$task->parent->column->id }},'posture_analysis12',2)">
                                    </div>
                                    
                                    <div class="pl-3 w-full">
                                        <div class="w-full text-sm">
                                            <label for="processCheckBox1{{$task->id}}posture_analysis12{{ $task->parent->column->id }}" class="cursor-pointer mb-0">
                                                Stretch Program
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </div>
                    @endif

                    @if (strtoupper($task->parent->column->name) == strtoupper('POSTURE ANALYSIS 2'))
                    <div class="w-full bg-white shadow-xs hide{{$task->parent->column->id}}{{$task->task_id}}" style="display: {{$hidden}}">
                        <div class="flex px-4 py-2 mb-1 pt-4 rounded-lg">
                            <div class="flex flex-col mb-4">
                                <div class="flex items-center py-2 mb-1 rounded-lg">
                                    <div class="pl-3">
                                        <input id="processCheckBox1{{$task->id}}posture_analysis21{{ $task->parent->column->id }}" type="checkbox" proccess-data='posture_analysis21' @if(in_array("posture_analysis21",$data, TRUE)) checked @endif
                                        class="processCheckBox1{{$task->id}}posture_analysis21{{ $task->parent->column->id }} processCheckBox1{{$task->id}}{{ $task->parent->column->id }} form-checkbox w-6 h-6 rounded-full text-green-400" 
                                        onclick="processCheckMe1({{ $task->id }},{{$task->parent->column->id }},'posture_analysis21',2)">
                                    </div>
                                    
                                    <div class="pl-3 w-full">
                                        <div class="w-full text-sm">
                                            <label for="processCheckBox1{{$task->id}}posture_analysis21{{ $task->parent->column->id }}" class="cursor-pointer mb-0">
                                                Posture Analysis 
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                        <div class="flex items-center py-2 mb-1 rounded-lg">
                                    <div class="pl-3">
                                        <input id="processCheckBox1{{$task->id}}posture_analysis22{{ $task->parent->column->id }}" type="checkbox" proccess-data='posture_analysis22' @if(in_array("posture_analysis22",$data, TRUE)) checked @endif
                                        class="processCheckBox1{{$task->id}}posture_analysis22{{ $task->parent->column->id }} processCheckBox1{{$task->id}}{{ $task->parent->column->id }} form-checkbox w-6 h-6 rounded-full text-green-400" 
                                        onclick="processCheckMe1({{ $task->id }},{{$task->parent->column->id }},'posture_analysis22',2)">
                                    </div>
                                    
                                    <div class="pl-3 w-full">
                                        <div class="w-full text-sm">
                                            <label for="processCheckBox1{{$task->id}}posture_analysis22{{ $task->parent->column->id }}" class="cursor-pointer mb-0">
                                                Stretch Program
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </div>
                    @endif

                    @if (strtoupper($task->parent->column->name) == strtoupper('SUB MAX TESTING'))
                    <div class="w-full bg-white shadow-xs hide{{$task->parent->column->id}}{{$task->task_id}}" style="display: {{$hidden}}">
                        <div class="flex px-4 py-2 mb-1 pt-4 rounded-lg">
                            <div class="flex flex-col mb-4">
                                <div class="flex items-center py-2 mb-1 rounded-lg">
                                    <div class="pl-3">
                                        <input id="processCheckBox1{{$task->id}}sub_max_test{{ $task->parent->column->id }}" type="checkbox" proccess-data='sub_max_test' @if(in_array("sub_max_test",$data, TRUE)) checked @endif
                                        class="processCheckBox1{{$task->id}}sub_max_test{{ $task->parent->column->id }} processCheckBox1{{$task->id}}{{ $task->parent->column->id }} form-checkbox w-6 h-6 rounded-full text-green-400" 
                                        onclick="processCheckMe1({{ $task->id }},{{$task->parent->column->id }},'sub_max_test',1)">
                                    </div>
                                    
                                    <div class="pl-3 w-full">
                                        <div class="w-full text-sm">
                                            <label for="processCheckBox1{{$task->id}}sub_max_test{{ $task->parent->column->id }}" class="cursor-pointer mb-0">
                                                Sub Max Test
                                            </label>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        </div>
                    @endif

                    @if (strtoupper($task->parent->column->name) == strtoupper('GOAL SETTING 1'))
                    <div class="w-full bg-white shadow-xs hide{{$task->parent->column->id}}{{$task->task_id}}" style="display: {{$hidden}}">
                        <div class="flex px-4 py-2 mb-1 pt-4 rounded-lg">
                            <div class="flex flex-col mb-4">
                                <div class="flex items-center py-2 mb-1 rounded-lg">
                                    <div class="pl-3">
                                        <input id="processCheckBox1{{$task->id}}goal_setting11{{ $task->parent->column->id }}" type="checkbox" proccess-data='goal_setting11' @if(in_array("goal_setting11",$data, TRUE)) checked @endif
                                        class="processCheckBox1{{$task->id}}goal_setting11{{ $task->parent->column->id }} processCheckBox1{{$task->id}}{{ $task->parent->column->id }} form-checkbox w-6 h-6 rounded-full text-green-400" 
                                        onclick="processCheckMe1({{ $task->id }},{{$task->parent->column->id }},'goal_setting11',5)">
                                    </div>
                                    
                                    <div class="pl-3 w-full">
                                        <div class="w-full text-sm">
                                            <label for="processCheckBox1{{$task->id}}goal_setting11{{ $task->parent->column->id }}" class="cursor-pointer mb-0">
                                                Goal Setting 1
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                    <div class="flex items-center py-2 mb-1 rounded-lg">

                                    <div class="pl-3">
                                        <input id="processCheckBox1{{$task->id}}goal_setting12{{ $task->parent->column->id }}" type="checkbox" proccess-data='goal_setting12' @if(in_array("goal_setting12",$data, TRUE)) checked @endif
                                        class="processCheckBox1{{$task->id}}goal_setting12{{ $task->parent->column->id }} processCheckBox1{{$task->id}}{{ $task->parent->column->id }} form-checkbox w-6 h-6 rounded-full text-green-400" 
                                        onclick="processCheckMe1({{ $task->id }},{{$task->parent->column->id }},'goal_setting12',5)">
                                    </div>
                                    
                                    <div class="pl-3 w-full">
                                        <div class="w-full text-sm">
                                            <label for="processCheckBox1{{$task->id}}goal_setting12{{ $task->parent->column->id }}" class="cursor-pointer mb-0">
                                                Sleep Questionnaire
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                    <div class="flex items-center py-2 mb-1 rounded-lg">

                                    <div class="pl-3">
                                        <input id="processCheckBox1{{$task->id}}goal_setting13{{ $task->parent->column->id }}" type="checkbox" proccess-data='goal_setting13' @if(in_array("goal_setting13",$data, TRUE)) checked @endif
                                        class="processCheckBox1{{$task->id}}goal_setting13{{ $task->parent->column->id }} processCheckBox1{{$task->id}}{{ $task->parent->column->id }} form-checkbox w-6 h-6 rounded-full text-green-400" 
                                        onclick="processCheckMe1({{ $task->id }},{{$task->parent->column->id }},'goal_setting13',5)">
                                    </div>
                                    
                                    <div class="pl-3 w-full">
                                        <div class="w-full text-sm">
                                            <label for="processCheckBox1{{$task->id}}goal_setting13{{ $task->parent->column->id }}" class="cursor-pointer mb-0">
                                                Goal Setting Follow up 1
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                    <div class="flex items-center py-2 mb-1 rounded-lg">

                                    <div class="pl-3">
                                        <input id="processCheckBox1{{$task->id}}goal_setting14{{ $task->parent->column->id }}" type="checkbox" proccess-data='goal_setting14' @if(in_array("goal_setting14",$data, TRUE)) checked @endif
                                        class="processCheckBox1{{$task->id}}goal_setting14{{ $task->parent->column->id }} processCheckBox1{{$task->id}}{{ $task->parent->column->id }} form-checkbox w-6 h-6 rounded-full text-green-400" 
                                        onclick="processCheckMe1({{ $task->id }},{{$task->parent->column->id }},'goal_setting14',5)">
                                    </div>
                                    
                                    <div class="pl-3 w-full">
                                        <div class="w-full text-sm">
                                            <label for="processCheckBox1{{$task->id}}goal_setting14{{ $task->parent->column->id }}" class="cursor-pointer mb-0">
                                                Goal Setting Follow up 2
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                    <div class="flex items-center py-2 mb-1 rounded-lg">

                                    <div class="pl-3">
                                        <input id="processCheckBox1{{$task->id}}goal_setting15{{ $task->parent->column->id }}" type="checkbox" proccess-data='goal_setting15' @if(in_array("goal_setting15",$data, TRUE)) checked @endif
                                        class="processCheckBox1{{$task->id}}goal_setting15{{ $task->parent->column->id }} processCheckBox1{{$task->id}}{{ $task->parent->column->id }} form-checkbox w-6 h-6 rounded-full text-green-400" 
                                        onclick="processCheckMe1({{ $task->id }},{{$task->parent->column->id }},'goal_setting15',5)">
                                    </div>
                                    
                                    <div class="pl-3 w-full">
                                        <div class="w-full text-sm">
                                            <label for="processCheckBox1{{$task->id}}goal_setting15{{ $task->parent->column->id }}" class="cursor-pointer mb-0">
                                                Progression session
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </div>
                    @endif

                    @if (strtoupper($task->parent->column->name) == strtoupper('GOAL SETTING 2'))
                    <div class="w-full bg-white shadow-xs hide{{$task->parent->column->id}}{{$task->task_id}}" style="display: {{$hidden}}">
                        <div class="flex px-4 py-2 mb-1 pt-4 rounded-lg">
                            <div class="flex flex-col mb-4">
                                <div class="flex items-center py-2 mb-1 rounded-lg">
                                    <div class="pl-3">
                                        <input id="processCheckBox1{{$task->id}}goal_setting21{{ $task->parent->column->id }}" type="checkbox" proccess-data='goal_setting21' @if(in_array("goal_setting21",$data, TRUE)) checked @endif
                                        class="processCheckBox1{{$task->id}}goal_setting21{{ $task->parent->column->id }} processCheckBox1{{$task->id}}{{ $task->parent->column->id }} form-checkbox w-6 h-6 rounded-full text-green-400" 
                                        onclick="processCheckMe1({{ $task->id }},{{$task->parent->column->id }},'goal_setting21',6)">
                                    </div>
                                    
                                    <div class="pl-3 w-full">
                                        <div class="w-full text-sm">
                                            <label for="processCheckBox1{{$task->id}}goal_setting21{{ $task->parent->column->id }}" class="cursor-pointer mb-0">
                                                Goal Setting 2
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                    <div class="flex items-center py-2 mb-1 rounded-lg">

                                    <div class="pl-3">
                                        <input id="processCheckBox1{{$task->id}}goal_setting22{{ $task->parent->column->id }}" type="checkbox" proccess-data='goal_setting22' @if(in_array("goal_setting22",$data, TRUE)) checked @endif
                                        class="processCheckBox1{{$task->id}}goal_setting22{{ $task->parent->column->id }} processCheckBox1{{$task->id}}{{ $task->parent->column->id }} form-checkbox w-6 h-6 rounded-full text-green-400" 
                                        onclick="processCheckMe1({{ $task->id }},{{$task->parent->column->id }},'goal_setting22',6)">
                                    </div>
                                    
                                    <div class="pl-3 w-full">
                                        <div class="w-full text-sm">
                                            <label for="processCheckBox1{{$task->id}}goal_setting22{{ $task->parent->column->id }}" class="cursor-pointer mb-0">
                                                Sleep Questionnaire
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                    <div class="flex items-center py-2 mb-1 rounded-lg">

                                    <div class="pl-3">
                                        <input id="processCheckBox1{{$task->id}}goal_setting23{{ $task->parent->column->id }}" type="checkbox" proccess-data='goal_setting23' @if(in_array("goal_setting23",$data, TRUE)) checked @endif
                                        class="processCheckBox1{{$task->id}}goal_setting23{{ $task->parent->column->id }} processCheckBox1{{$task->id}}{{ $task->parent->column->id }} form-checkbox w-6 h-6 rounded-full text-green-400" 
                                        onclick="processCheckMe1({{ $task->id }},{{$task->parent->column->id }},'goal_setting23',6)">
                                    </div>
                                    
                                    <div class="pl-3 w-full">
                                        <div class="w-full text-sm">
                                            <label for="processCheckBox1{{$task->id}}goal_setting23{{ $task->parent->column->id }}" class="cursor-pointer mb-0">
                                                Goal Setting Follow up 1
                                            </label>
                                        </div>
                                    </div>
                                </div> 
                                <div class="flex items-center py-2 mb-1 rounded-lg">

                                    <div class="pl-3">
                                        <input id="processCheckBox1{{$task->id}}goal_setting24{{ $task->parent->column->id }}" type="checkbox" proccess-data='goal_setting24' @if(in_array("goal_setting24",$data, TRUE)) checked @endif
                                        class="processCheckBox1{{$task->id}}goal_setting24{{ $task->parent->column->id }} processCheckBox1{{$task->id}}{{ $task->parent->column->id }} form-checkbox w-6 h-6 rounded-full text-green-400" 
                                        onclick="processCheckMe1({{ $task->id }},{{$task->parent->column->id }},'goal_setting24',6)">
                                    </div>
                                    
                                    <div class="pl-3 w-full">
                                        <div class="w-full text-sm">
                                            <label for="processCheckBox1{{$task->id}}goal_setting24{{ $task->parent->column->id }}" class="cursor-pointer mb-0">
                                                Goal Setting Follow up 2
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                    <div class="flex items-center py-2 mb-1 rounded-lg">

                                    <div class="pl-3">
                                        <input id="processCheckBox1{{$task->id}}goal_setting25{{ $task->parent->column->id }}" type="checkbox" proccess-data='goal_setting25' @if(in_array("goal_setting25",$data, TRUE)) checked @endif
                                        class="processCheckBox1{{$task->id}}goal_setting25{{ $task->parent->column->id }} processCheckBox1{{$task->id}}{{ $task->parent->column->id }} form-checkbox w-6 h-6 rounded-full text-green-400" 
                                        onclick="processCheckMe1({{ $task->id }},{{$task->parent->column->id }},'goal_setting25',6)">
                                    </div>
                                    
                                    <div class="pl-3 w-full">
                                        <div class="w-full text-sm">
                                            <label for="processCheckBox1{{$task->id}}goal_setting25{{ $task->parent->column->id }}" class="cursor-pointer mb-0">
                                                Progression session
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                    <div class="flex items-center py-2 mb-1 rounded-lg">

                                    <div class="pl-3">
                                        <input id="processCheckBox1{{$task->id}}goal_setting26{{ $task->parent->column->id }}" type="checkbox" proccess-data='goal_setting26' @if(in_array("goal_setting26",$data, TRUE)) checked @endif
                                        class="processCheckBox1{{$task->id}}goal_setting26{{ $task->parent->column->id }} processCheckBox1{{$task->id}}{{ $task->parent->column->id }} form-checkbox w-6 h-6 rounded-full text-green-400" 
                                        onclick="processCheckMe1({{ $task->id }},{{$task->parent->column->id }},'goal_setting26',6)">
                                    </div>
                                    
                                    <div class="pl-3 w-full">
                                        <div class="w-full text-sm">
                                            <label for="processCheckBox1{{$task->id}}goal_setting26{{ $task->parent->column->id }}" class="cursor-pointer mb-0">
                                                Movement Analysis
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </div>
                    @endif

                    @if (strtoupper($task->parent->column->name) == strtoupper('GOAL SETTING 3'))
                    <div class="w-full bg-white shadow-xs hide{{$task->parent->column->id}}{{$task->task_id}}" style="display: {{$hidden}}">
                        <div class="flex px-4 py-2 mb-1 pt-4 rounded-lg">
                            <div class="flex flex-col mb-4">
                                <div class="flex items-center py-2 mb-1 rounded-lg">
                                    <div class="pl-3">
                                        <input id="processCheckBox1{{$task->id}}goal_setting31{{ $task->parent->column->id }}" type="checkbox" proccess-data='goal_setting31' @if(in_array("goal_setting31",$data, TRUE)) checked @endif
                                        class="processCheckBox1{{$task->id}}goal_setting31{{ $task->parent->column->id }} processCheckBox1{{$task->id}}{{ $task->parent->column->id }} form-checkbox w-6 h-6 rounded-full text-green-400" 
                                        onclick="processCheckMe1({{ $task->id }},{{$task->parent->column->id }},'goal_setting31',5)">
                                    </div>
                                    
                                    <div class="pl-3 w-full">
                                        <div class="w-full text-sm">
                                            <label for="processCheckBox1{{$task->id}}goal_setting31{{ $task->parent->column->id }}" class="cursor-pointer mb-0">
                                                Goal Setting 3
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                    <div class="flex items-center py-2 mb-1 rounded-lg">

                                    <div class="pl-3">
                                        <input id="processCheckBox1{{$task->id}}goal_setting32{{ $task->parent->column->id }}" type="checkbox" proccess-data='goal_setting32' @if(in_array("goal_setting32",$data, TRUE)) checked @endif
                                        class="processCheckBox1{{$task->id}}goal_setting32{{ $task->parent->column->id }} processCheckBox1{{$task->id}}{{ $task->parent->column->id }} form-checkbox w-6 h-6 rounded-full text-green-400" 
                                        onclick="processCheckMe1({{ $task->id }},{{$task->parent->column->id }},'goal_setting32',5)">
                                    </div>
                                    
                                    <div class="pl-3 w-full">
                                        <div class="w-full text-sm">
                                            <label for="processCheckBox1{{$task->id}}goal_setting32{{ $task->parent->column->id }}" class="cursor-pointer mb-0">
                                                Sleep Questionnaire
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                    <div class="flex items-center py-2 mb-1 rounded-lg">

                                    <div class="pl-3">
                                        <input id="processCheckBox1{{$task->id}}goal_setting33{{ $task->parent->column->id }}" type="checkbox" proccess-data='goal_setting33' @if(in_array("goal_setting33",$data, TRUE)) checked @endif
                                        class="processCheckBox1{{$task->id}}goal_setting33{{ $task->parent->column->id }} processCheckBox1{{$task->id}}{{ $task->parent->column->id }} form-checkbox w-6 h-6 rounded-full text-green-400" 
                                        onclick="processCheckMe1({{ $task->id }},{{$task->parent->column->id }},'goal_setting33',5)">
                                    </div>
                                    
                                    <div class="pl-3 w-full">
                                        <div class="w-full text-sm">
                                            <label for="processCheckBox1{{$task->id}}goal_setting33{{ $task->parent->column->id }}" class="cursor-pointer mb-0">
                                                Goal Setting Follow up 1
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                    <div class="flex items-center py-2 mb-1 rounded-lg">

                                    <div class="pl-3">
                                        <input id="processCheckBox1{{$task->id}}goal_setting34{{ $task->parent->column->id }}" type="checkbox" proccess-data='goal_setting34' @if(in_array("goal_setting34",$data, TRUE)) checked @endif
                                        class="processCheckBox1{{$task->id}}goal_setting34{{ $task->parent->column->id }} processCheckBox1{{$task->id}}{{ $task->parent->column->id }} form-checkbox w-6 h-6 rounded-full text-green-400" 
                                        onclick="processCheckMe1({{ $task->id }},{{$task->parent->column->id }},'goal_setting34',5)">
                                    </div>
                                    
                                    <div class="pl-3 w-full">
                                        <div class="w-full text-sm">
                                            <label for="processCheckBox1{{$task->id}}goal_setting34{{ $task->parent->column->id }}" class="cursor-pointer mb-0">
                                                Goal Setting Follow up 2
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                    <div class="flex items-center py-2 mb-1 rounded-lg">

                                    <div class="pl-3">
                                        <input id="processCheckBox1{{$task->id}}goal_setting35{{ $task->parent->column->id }}" type="checkbox" proccess-data='goal_setting35' @if(in_array("goal_setting35",$data, TRUE)) checked @endif
                                        class="processCheckBox1{{$task->id}}goal_setting35{{ $task->parent->column->id }} processCheckBox1{{$task->id}}{{ $task->parent->column->id }} form-checkbox w-6 h-6 rounded-full text-green-400" 
                                        onclick="processCheckMe1({{ $task->id }},{{$task->parent->column->id }},'goal_setting35',5)">
                                    </div>
                                    
                                    <div class="pl-3 w-full">
                                        <div class="w-full text-sm">
                                            <label for="processCheckBox1{{$task->id}}goal_setting35{{ $task->parent->column->id }}" class="cursor-pointer mb-0">
                                                Progression session
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </div>
                    @endif
                    @if (strtoupper($task->parent->column->name) == strtoupper('NUTRITIONAL JOURNAL 1'))
                    <div class="w-full bg-white shadow-xs hide{{$task->parent->column->id}}{{$task->task_id}}" style="display: {{$hidden}}">
                        <div class="flex px-4 py-2 mb-1 pt-4 rounded-lg">
                            <div class="flex flex-col mb-4">
                                <div class="flex items-center py-2 mb-1 rounded-lg">
                                    <div class="pl-3">
                                        <input id="processCheckBox1{{$task->id}}nutrition_journal11{{$task->parent->column->id }}" type="checkbox" proccess-data='nutrition_journal11' @if(in_array("nutrition_journal11",$data, TRUE)) checked @endif
                                        class="processCheckBox1{{$task->id}}nutrition_journal11{{$task->parent->column->id }} processCheckBox1{{$task->id}}{{$task->parent->column->id }} form-checkbox w-6 h-6 rounded-full text-green-400" 
                                        onclick="processCheckMe1({{ $task->id }},{{$task->parent->column->id }},'nutrition_journal11',3)">
                                    </div>
                                    
                                    <div class="pl-3 w-full">
                                        <div class="w-full text-sm">
                                            <label for="processCheckBox1{{$task->id}}nutrition_journal11{{$task->parent->column->id }}" class="cursor-pointer mb-0">
                                                Nutritional Journal 1
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                        <div class="flex items-center py-2 mb-1 rounded-lg">

                                    <div class="pl-3">
                                        <input id="processCheckBox1{{$task->id}}nutrition_journal12{{$task->parent->column->id }}" type="checkbox" proccess-data='nutrition_journal12' @if(in_array("nutrition_journal12",$data, TRUE)) checked @endif
                                        class="processCheckBox1{{$task->id}}nutrition_journal12{{$task->parent->column->id }} processCheckBox1{{$task->id}}{{$task->parent->column->id }} form-checkbox w-6 h-6 rounded-full text-green-400" 
                                        onclick="processCheckMe1({{ $task->id }},{{$task->parent->column->id }},'nutrition_journal12',3)">
                                    </div>
                                    
                                    <div class="pl-3 w-full">
                                        <div class="w-full text-sm">
                                            <label for="processCheckBox1{{$task->id}}nutrition_journal12{{$task->parent->column->id }}" class="cursor-pointer mb-0">
                                                Journal follow up 1
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                    <div class="flex items-center py-2 mb-1 rounded-lg">

                                    <div class="pl-3">
                                        <input id="processCheckBox1{{$task->id}}nutrition_journal13{{$task->parent->column->id }}" type="checkbox" proccess-data='nutrition_journal13' @if(in_array("nutrition_journal13",$data, TRUE)) checked @endif
                                        class="processCheckBox1{{$task->id}}nutrition_journal13{{$task->parent->column->id }} processCheckBox1{{$task->id}}{{$task->parent->column->id }} form-checkbox w-6 h-6 rounded-full text-green-400" 
                                        onclick="processCheckMe1({{ $task->id }},{{$task->parent->column->id }},'nutrition_journal13',3)">
                                    </div>
                                    
                                    <div class="pl-3 w-full">
                                        <div class="w-full text-sm">
                                            <label for="processCheckBox1{{$task->id}}nutrition_journal13{{$task->parent->column->id }}" class="cursor-pointer mb-0">
                                                Journal follow up 2
                                            </label>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        </div>
                    @endif

                    @if (strtoupper($task->parent->column->name) == strtoupper('NUTRITIONAL JOURNAL 2'))
                    <div class="w-full bg-white shadow-xs hide{{$task->parent->column->id}}{{$task->task_id}}" style="display: {{$hidden}}">
                        <div class="flex px-4 py-2 mb-1 pt-4 rounded-lg">
                            <div class="flex flex-col mb-4">
                                <div class="flex items-center py-2 mb-1 rounded-lg">
                                    <div class="pl-3">
                                        <input id="processCheckBox1{{$task->id}}nutrition_journal21{{$task->parent->column->id }}" type="checkbox" proccess-data='nutrition_journal21' @if(in_array("nutrition_journal21",$data, TRUE)) checked @endif
                                        class="processCheckBox1{{$task->id}}nutrition_journal21{{$task->parent->column->id }} processCheckBox1{{$task->id}}{{$task->parent->column->id }} form-checkbox w-6 h-6 rounded-full text-green-400" 
                                        onclick="processCheckMe1({{ $task->id }},{{$task->parent->column->id }},'nutrition_journal21',3)">
                                    </div>
                                    
                                    <div class="pl-3 w-full">
                                        <div class="w-full text-sm">
                                            <label for="processCheckBox1{{$task->id}}nutrition_journal21{{$task->parent->column->id }}" class="cursor-pointer mb-0">
                                                Nutritional Journal 2
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                    <div class="flex items-center py-2 mb-1 rounded-lg">

                                    <div class="pl-3">
                                        <input id="processCheckBox1{{$task->id}}nutrition_journal22{{$task->parent->column->id }}" type="checkbox" proccess-data='nutrition_journal22' @if(in_array("nutrition_journal22",$data, TRUE)) checked @endif
                                        class="processCheckBox1{{$task->id}}nutrition_journal22{{$task->parent->column->id }} processCheckBox1{{$task->id}}{{$task->parent->column->id }} form-checkbox w-6 h-6 rounded-full text-green-400" 
                                        onclick="processCheckMe1({{ $task->id }},{{$task->parent->column->id }},'nutrition_journal22',3)">
                                    </div>
                                    
                                    <div class="pl-3 w-full">
                                        <div class="w-full text-sm">
                                            <label for="processCheckBox1{{$task->id}}nutrition_journal22{{$task->parent->column->id }}" class="cursor-pointer mb-0">
                                                Journal follow up 1
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                    <div class="flex items-center py-2 mb-1 rounded-lg">

                                    <div class="pl-3">
                                        <input id="processCheckBox1{{$task->id}}nutrition_journal23{{$task->parent->column->id }}" type="checkbox" proccess-data='nutrition_journal23' @if(in_array("nutrition_journal23",$data, TRUE)) checked @endif
                                        class="processCheckBox1{{$task->id}}nutrition_journal23{{$task->parent->column->id }} processCheckBox1{{$task->id}}{{$task->parent->column->id }} form-checkbox w-6 h-6 rounded-full text-green-400" 
                                        onclick="processCheckMe1({{ $task->id }},{{$task->parent->column->id }},'nutrition_journal23',3)">
                                    </div>
                                    
                                    <div class="pl-3 w-full">
                                        <div class="w-full text-sm">
                                            <label for="processCheckBox1{{$task->id}}nutrition_journal23{{$task->parent->column->id }}" class="cursor-pointer mb-0">
                                                Journal follow up 2
                                            </label>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        </div>
                    @endif

                    @if (strtoupper($task->parent->column->name) == strtoupper('NUTRITIONAL JOURNAL 3'))
                    <div class="w-full bg-white shadow-xs hide{{$task->parent->column->id}}{{$task->task_id}}" style="display: {{$hidden}}">
                        <div class="flex px-4 py-2 mb-1 pt-4 rounded-lg">
                            <div class="flex flex-col mb-4">
                                <div class="flex items-center py-2 mb-1 rounded-lg">
                                    <div class="pl-3">
                                        <input id="processCheckBox1{{$task->id}}nutrition_journal31{{$task->parent->column->id }}" type="checkbox" proccess-data='nutrition_journal31' @if(in_array("nutrition_journal31",$data, TRUE)) checked @endif
                                        class="processCheckBox1{{$task->id}}nutrition_journal31{{$task->parent->column->id }} processCheckBox1{{$task->id}}{{$task->parent->column->id }} form-checkbox w-6 h-6 rounded-full text-green-400" 
                                        onclick="processCheckMe1({{ $task->id }},{{$task->parent->column->id }},'nutrition_journal31',3)">
                                    </div>
                                    
                                    <div class="pl-3 w-full">
                                        <div class="w-full text-sm">
                                            <label for="processCheckBox1{{$task->id}}nutrition_journal31{{$task->parent->column->id }}" class="cursor-pointer mb-0">
                                                Nutritional Journal 3
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                        <div class="flex items-center py-2 mb-1 rounded-lg">
                                    <div class="pl-3">
                                        <input id="processCheckBox1{{$task->id}}nutrition_journal32{{$task->parent->column->id }}" type="checkbox" proccess-data='nutrition_journal32' @if(in_array("nutrition_journal32",$data, TRUE)) checked @endif
                                        class="processCheckBox1{{$task->id}}nutrition_journal32{{$task->parent->column->id }} processCheckBox1{{$task->id}}{{$task->parent->column->id }} form-checkbox w-6 h-6 rounded-full text-green-400" 
                                        onclick="processCheckMe1({{ $task->id }},{{$task->parent->column->id }},'nutrition_journal32',3)">
                                    </div>
                                    
                                    <div class="pl-3 w-full">
                                        <div class="w-full text-sm">
                                            <label for="processCheckBox1{{$task->id}}nutrition_journal32{{$task->parent->column->id }}" class="cursor-pointer mb-0">
                                                Journal follow up 1
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                        <div class="flex items-center py-2 mb-1 rounded-lg">

                                    <div class="pl-3">
                                        <input id="processCheckBox1{{$task->id}}nutrition_journal33{{$task->parent->column->id }}" type="checkbox" proccess-data='nutrition_journal33' @if(in_array("nutrition_journal33",$data, TRUE)) checked @endif
                                        class="processCheckBox1{{$task->id}}nutrition_journal33{{$task->parent->column->id }} processCheckBox1{{$task->id}}{{$task->parent->column->id }} form-checkbox w-6 h-6 rounded-full text-green-400" 
                                        onclick="processCheckMe1({{ $task->id }},{{$task->parent->column->id }},'nutrition_journal33',3)">
                                    </div>
                                    
                                    <div class="pl-3 w-full">
                                        <div class="w-full text-sm">
                                            <label for="processCheckBox1{{$task->id}}nutrition_journal33{{$task->parent->column->id }}" class="cursor-pointer mb-0">
                                                Journal follow up 2
                                            </label>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        </div>
                    @endif

                        </div>
                    </div>
                
                </div>
            </div>
                <div class="pl-3 flex items-center">
                    <div class="relative inline-block text-left">
                        <div>
                            <button class="deleteComment btn btn-sm btn-flat">
                                <svg viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 fill-current">
                                    <path d="M10 12a2 2 0 1 1 0-4 2 2 0 0 1 0 4zm0-6a2 2 0 1 1 0-4 2 2 0 0 1 0 4zm0 12a2 2 0 1 1 0-4 2 2 0 0 1 0 4z">
                                    </path>
                                </svg>
                            </button>
                        </div>

                        <div class=" deleteCommentPopup origin-top-right absolute mt-2 w-auto rounded-md shadow-lg z-40 right-0"
                            style="display: none;">
                            <div class="w-40 rounded-md bg-white shadow-xs p-1">
                                <a href="javascript:void(0)" class="deleteTask dropdown-item" >
                                    Delete
                                </a>
                            </div>
                        </div>
                    </div> 
                    <button class="hideCommentOnColumnPopup btn btn-sm btn-flat">
                        <svg viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 fill-current">
                            <path d="M10 8.586L2.929 1.515 1.515 2.929 8.586 10l-7.071 7.071 1.414 1.414L10 11.414l7.071 7.071 1.414-1.414L11.414 10l7.071-7.071-1.414-1.414L10 8.586z">
                            </path>
                        </svg>
                    </button>
                </div>
            </div>
            <div class="flex items-center px-6 pb-6 pr-6">
                <div class="flex items-center">
                    <div class="relative inline-block text-left">
                        <div>
                            <a href="javascript:void(0)"
                                class="assignTask flex items-center text-xs text-gray-600 hover:underline" data-id="{{$task->task_id}}">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" class="w-4 h-4 mr-1.5 fill-current">
                                    <path d="M2 6H0v2h2v2h2V8h2V6H4V4H2v2zm7 0a3 3 0 0 1 6 0v2a3 3 0 0 1-6 0V6zm11 9.14A15.93 15.93 0 0 0 12 13c-2.91 0-5.65.78-8 2.14V18h16v-2.86z">
                                    </path>
                                </svg> 
                                @php
                                $staff_name = Staff::find($task->parent->user_id);
                            @endphp
                            @if($staff_name && isset($staff_name['first_name']))
                            <span class="assign-user-name{{$task->task_id}}">
                                {{$staff_name['first_name']}} {{$staff_name['last_name']}}
                            </span>
                            @else
                            <span class="assign-user-name{{$task->task_id}}">Assign User</span>
                            @endif
                            </a>
                        </div>
                        <div class="assignTaskPopup origin-top-right absolute mt-2 w-auto rounded-md shadow-lg z-40 left-0"
                            style="display: none;">
                            <div class="w-64 bg-white shadow-xs">
                                <div class="flex flex-col">
                                    <div class="px-4 pt-4 mb-2">
                                        <input placeholder="Search.." class="form-input assign-users-search">
                                    </div>
                                    <div class="w-64 overflow-y-auto" style="height: 220px;" id="filter-assign-users">
                                        @foreach($staffs as $staff)
                                    @if($staff['id'] == $staff_name['id'])
                                    @php
                                    $show_checkbox = '';
                                    $show_user_image = 'none';
                                    @endphp
                                    @else
                                    @php
                                    $show_checkbox = 'none';
                                    $show_user_image = 'block';
                                    @endphp
                                    @endif
                                    <a href="javascript:void(0)"
                                        class="dropdown-item flex items-center assign-user" data-item=" {{$staff['first_name']}} {{$staff['last_name']}}" data-id="{{$staff['id']}}" id="{{$task->task_id}}">
                                        <div class="flex-shrink-0 flex items-center assign-user-image{{$task->task_id}}" style="display: {{$show_user_image}};" >
                                            <img src="{{url('uploads/thumb_')}}{{$staff['profile_picture']}}"
                                                alt="avatar" class="avatar avatar-xs">
                                        </div>
                                        <div class="inline-flex w-6 h-6 justify-center items-center text-xs bg-green-400 text-white rounded-full user-assigned-checbox{{$task->task_id}}" style="display:{{$show_checkbox}};">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" class="w-3 h-3 fill-current">
                                                <path d="M0 11l2-2 5 5L18 3l2 2L7 18z"></path>
                                            </svg>
                                        </div>
                                        <div class="text-sm leading-5 text-gray-700 group-hover:text-gray-900 truncate pl-4">
                                            {{$staff['first_name']}} {{$staff['last_name']}}
                                        </div>
                                    </a>
                                    @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="relative inline-block text-left">
                    <div>
                        <a href="javascript:void(0)" class="dueDate11 flex items-center text-xs text-gray-600 pl-6 hover:underline" data-id="{{$task->task_id}}" due-date="{{ $task->due_date }}">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" class="w-4 h-4 mr-1.5 fill-current">
                                <path d="M1 4c0-1.1.9-2 2-2h14a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V4zm2 2v12h14V6H3zm2-6h2v2H5V0zm8 0h2v2h-2V0zM5 9h2v2H5V9zm0 4h2v2H5v-2zm4-4h2v2H9V9zm0 4h2v2H9v-2zm4-4h2v2h-2V9zm0 4h2v2h-2v-2z">
                                </path>
                            </svg>
                            <input type="hidden" class="due-date-value" value="{{ $task->parent->due_date  }}"> 
                            <input type="hidden" class="dueDateTaskId" value="{{ $task->task_id }}"> 
                            <span class="due-date"  id="due-date{{$task->task_id}}">
                                @if ($task->parent->due_date != NULL && $task->parent->due_date != '0000-00-00')
                                    {{-- {{ $task->parent->due_date  }} --}}
                                    <input type="hidden" style="width: 85px;border: none;" class="dueDate" value="{{ $task->parent->due_date  }}">
                                @else
                                    Due Date
                                @endif
                            </span>
                        </a>
                    </div>
                    <div class="dueDatePopup origin-top-right absolute mt-2 w-auto rounded-md shadow-lg z-40 left-0"
                        style="display: none;" >
                        
                        <input class="form-input hidden dueDateValue"  type="text" readonly="readonly">
                    </div>
                </div>
                <div class="flex items-center">
                    <div class="relative inline-block text-left">
                        <div class="priority-div">
                            <a href="javascript:void(0)"
                                class="priority-name flex items-center text-xs text-gray-600 pl-6 hover:underline">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" class="w-4 h-4 mr-1.5 fill-current">
                                    <path d="M7.667 12H2v8H0V0h12l.333 2H20l-3 6 3 6H8l-.333-2z"></path>
                                </svg> 
                                <span class="selectedPriority">
                                    @php
                                        if($task->parent->priority == 4){
                                            $priority = 'Urgent';
                                        }elseif($task->parent->priority == 3){
                                            $priority = 'High';
                                        }elseif($task->parent->priority == 2){
                                            $priority = 'Medium';
                                        }elseif($task->parent->priority == 1){
                                            $priority = 'Low';
                                        }else{
                                            $priority = 'Priority';
                                        }
                                    @endphp
                                    {{ $priority }}
                                </span>
                            </a>
                        </div>
                        <div class="priority-name-popup origin-top-right absolute mt-2 w-auto rounded-md shadow-lg z-40 left-0"
                            style="display: none;">
                            <input type="hidden" class="priorityTaskId" value="{{ $task->task_id }}">
                            <div class="w-60 bg-white shadow-xs">
                                <a href="javascript:void(0)" class="choosePriority dropdown-item" priority-value="4">
                                    Urgent
                                </a>
                                <a href="javascript:void(0)" class="choosePriority dropdown-item" priority-value="3">
                                    High
                                </a>
                                <a href="javascript:void(0)" class="choosePriority dropdown-item" priority-value="2">
                                    Medium
                                </a>
                                <a href="javascript:void(0)" class="choosePriority dropdown-item" priority-value="1">
                                    Low
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex items-center">
                    <div class="relative inline-block text-left">
                        <div class="change-cloumn-div">
                            <a href="javascript:void(0)"
                                class="status-name flex items-center text-xs text-gray-600 pl-6 hover:underline">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" class="w-4 h-4 mr-1.5 fill-current">
                                    <path d="M7.667 12H2v8H0V0h12l.333 2H20l-3 6 3 6H8l-.333-2z"></path>
                                </svg> 
                                <span class="selected_column">
                                    Status 
                                </span>
                            </a>
                        </div>
                        <div class="status-name-popup origin-top-right absolute mt-2 w-auto rounded-md shadow-lg z-40 left-0"
                            style="display: none;">
                            <input type="hidden" class="priorityTaskId" value="{{ $task->task_id }}">
                            <div class="w-60 bg-white shadow-xs">
                                @php
                                    $project_columns  = Column::where('project_id',$task->parent->column->project_id)->pluck('name','id')->toArray();
                                @endphp
                                @if (!empty($project_columns))
                                @foreach ($project_columns as $key1=>  $project_column)
                                <a href="javascript:void(0)" class="select-column dropdown-item"  data-id= "{{$key1}}" data-item="{{$task->task_id}}">
                                    {{ $project_column }}
                                </a>
                            @endforeach
                            @endif
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div>
                <div class="bg-gray-50 p-6 sm:hidden">
                    <select class="form-select DetailsTab" task-id="{{ $task->id }}">
                        <option value="tabComments-{{ $task->id }}">Comments</option>
                        <option value="tabTasks-{{ $task->id }}">Tasks</option>
                        <option value="tabAttachments-{{ $task->id }}">Attachments</option>
                        <option value="tabSales-{{ $task->id }}">Sales Preferences</option>
                    </select>
                </div>
                <div class="sm:block">
                    <div class="border-b border-gray-200 commonpopupmodel">
                        <ul class="nav nav-tabs flex nav hideTabmobile" id="myTab" role="tablist">
                            <li class="nav-item active flex-1">
                                <a class="nav-link  py-4 px-1 text-center border-b-2 border-transparent font-medium text-sm leading-5 text-gray-600" id="comments-tab{{ $task->id }}" data-toggle="tab" href="#comments{{ $task->id }}" role="tab" aria-controls="comments{{ $task->id }}" aria-selected="true">Comments</a>
                            </li>
                            <li class="nav-item flex-1">
                                <a class="nav-link py-4 px-1 text-center border-b-2 border-transparent font-medium text-sm leading-5 text-gray-600" id="tasks-tab{{ $task->id }}" data-toggle="tab" href="#tasks{{ $task->id }}" role="tab" aria-controls="tasks{{ $task->id }}" aria-selected="false">Tasks</a>
                            </li>
                            <li class="nav-item flex-1">
                                <a class="nav-link py-4 px-1 text-center border-b-2 border-transparent font-medium text-sm leading-5 text-gray-600" id="attachments-tab{{ $task->id }}" data-toggle="tab" href="#attachments{{ $task->id }}" role="tab" aria-controls="attachments{{ $task->id }}" aria-selected="false">Attachments</a>
                            </li>
                            <li class="nav-item flex-1">
                                <a class="nav-link py-4 px-1 text-center border-b-2 border-transparent font-medium text-sm leading-5 text-gray-600" id="sales-preferences-tab{{ $task->id }}" data-toggle="tab" href="#sales-preferences{{ $task->id }}" role="tab" aria-controls="sales-preferences{{ $task->id }}" aria-selected="false">Epic Process</a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab_pane_comments-{{ $task->id }} tab-pane fade active in" id="comments{{ $task->id }}" role="tabpanel" aria-labelledby="comments-tab{{ $task->id }}">
                                <section class="p-3">
                                    <div class="flex flex-col">
                                        <form class="commentForm" method="post" enctype="multipart/form-data" action="javascript:void(0)">
                                            @csrf
                                        <div class="flex flex-col rounded-lg shadow-sm border border-gray-300">
                                            <textarea name="content" class="comment-text form-textarea w-full block resize-none border-none shadow-none focus:outline-none focus:shadow-none rounded-lg"
                                                placeholder="Write something.." style="height: 80px;" id="{{$task->id}}"></textarea>
                                            <input type="hidden" name="id" class="comment_task_id" value="{{ $task->task_id }}">
                                            <div class="mt-2 px-3 pb-3 flex items-center justify-between">
                                            <div>
                                                <button type="submit" value="upload" class="comment-btn btn btn-sm btn-indigo" disabled id="comment-btn{{$task->id }}" data-id="{{$task->task_id}}">
                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" class="w-3 h-3 mr-1.5 fill-current">
                                                        <path d="M0 0l20 10L0 20V0zm0 8v4l10-2L0 8z"></path>
                                                    </svg>
                                                    Send
                                                </button>
                                            </div>
                                            <div class="attachmentfile">
                                                <span class="btn-group shadow-none">
                                                    <button type="button" class="btn btn-sm btn-flat dz-clickable">
                                                    <input type="file" id="file{{ $task->task_id }}" class="filename comment-attachment" name="filename[]"  accept="icon/*" data-id="{{$task->task_id}}">
                                                    <label for="file{{ $task->task_id }}">
                                                        <svg viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4">
                                                            <path 
                                                                d="M15 3H7a7 7 0 1 0 0 14h8v-2H7A5 5 0 0 1 7 5h8a3 3 0 0 1 0 6H7a1 1 0 0 1 0-2h8V7H7a3 3 0 1 0 0 6h8a5 5 0 0 0 0-10z">
                                                            </path>
                                                        </svg>
                                                        </label>
                                                    </button>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="flex flex-col dz-started" id="comment-attachment-preview{{$task->task_id}}">  
                                        </div>
                                            
                                        </div>
                                    </form>                                
                                        @php
                                        // $user_comments = Comment::where('pipeline_process_task_id',$task->task_id)->get();

                                        $auth_staff = Staff::where('email',Auth::User()->email)->first();
                                        @endphp
                                        <div class="latestComments flex flex-col mt-2">
                                            @if(count($task->parent->comments) > 0)
                                                @foreach ($task->parent->comments as $comment)
                                            <div class="">
                                                <div class="flex mt-6 pb-4">
                                                    <div class="w-12 flex-shrink-0">
                                                        @if($auth_staff)
                                                        <img src="{{url('uploads/thumb_')}}{{$auth_staff->profile_picture}}" alt="avatar" class="avatar avatar-sm">
                                                        @endif
                                                    </div> 
                                                    <div class="flex flex-col flex-1">
                                                        <div class="flex items-center justify-between">
                                                            <div>
                                                                <span class="text-sm leading-5 font-medium text-blue-600">{{Auth::User()->name}} {{Auth::User()->last_name}}</span>
                                                            </div>
                                                            <div>
                                                                <span class="text-xs text-gray-500">{{ $comment->created_at->diffForHumans() }}</span>
                                                            </div>
                                                        </div> 
                                                        <div class="mt-2">
                                                            <p class="leading-5 text-gray-900">{{ $comment->content }}</p>
                                                        </div> 
                                                            @php
                                                                $attachments = Attachment::where('comment_id',$comment->id)->get()->toArray();
                                                            @endphp
                                                            @if ($attachments)
                                                            <ul class="border border-gray-200 rounded-md mt-4">
                                                                @foreach ($attachments as $attachment)
                                                                <li class="pl-3 pr-2 py-3 flex items-center justify-between text-sm leading-5">
                                                                    <div class="w-0 flex-1 flex items-center">
                                                                        <svg fill="currentColor" viewBox="0 0 20 20"
                                                                            class="flex-shrink-0 h-5 w-5 text-gray-400">
                                                                            <path fill-rule="evenodd"
                                                                                d="M8 4a3 3 0 00-3 3v4a5 5 0 0010 0V7a1 1 0 112 0v4a7 7 0 11-14 0V7a5 5 0 0110 0v4a3 3 0 11-6 0V7a1 1 0 012 0v4a1 1 0 102 0V7a3 3 0 00-3-3z"
                                                                                clip-rule="evenodd"></path>
                                                                        </svg> 
                                                                        <a href="{{ url('pipeline-process/task/download') }}/{{ $attachment['filename'] }}"
                                                                            class="hover:underline"><span class="ml-2 truncate">{{ $attachment['filename'] }}</span>
                                                                        </a>
                                                                    </div>
                                                                    <div class="ml-4 flex-shrink-0">
                                                                        <a href="{{ url('pipeline-process/task/download') }}/{{ $attachment['filename'] }}"
                                                                            title="Download" class="btn btn-flat btn-xs">
                                                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                                                viewBox="0 0 20 20" class="w-3 h-3 fill-current">
                                                                                <path d="M13 8V2H7v6H2l8 8 8-8h-5zM0 18h20v2H0v-2z"></path>
                                                                            </svg>
                                                                        </a>
                                                                    </div>
                                                                </li>
                                                                @endforeach
                                                            </ul>
                                                            @endif                         
                                                    </div>
                                                </div>
                                            </div>
                                            @endforeach
                                            @else
                                            <div class="flex flex-col items-center"><div class="flex flex-col items-center"><svg data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="300" height="300" viewBox="0 0 1094 798.15"><defs><linearGradient id="b86fccb4-da21-4a09-8d3b-ff994f3aa5aa" x1="639.03" y1="672.43" x2="639.03" y2="50.93" gradientUnits="userSpaceOnUse"><stop offset="0" stop-color="gray" stop-opacity="0.25"></stop> <stop offset="0.54" stop-color="gray" stop-opacity="0.12"></stop> <stop offset="1" stop-color="gray" stop-opacity="0.1"></stop></linearGradient> <linearGradient id="d2adb5db-d5c7-49ab-bfdd-50ec6aa449ae" x1="319.12" y1="309.68" x2="319.12" y2="212.15" xlink:href="#b86fccb4-da21-4a09-8d3b-ff994f3aa5aa"></linearGradient> <linearGradient id="fd1a872a-c2fb-4cd4-906a-a62e176bbce4" x1="332.56" y1="304.74" x2="332.56" y2="246.12" gradientTransform="matrix(-1, 0, 0, 1, 652, 0)" xlink:href="#b86fccb4-da21-4a09-8d3b-ff994f3aa5aa"></linearGradient> <linearGradient id="636ac3f3-8a9d-4cd8-9529-d45e35aeb867" x1="803.65" y1="796.85" x2="803.65" y2="184.5" xlink:href="#b86fccb4-da21-4a09-8d3b-ff994f3aa5aa"></linearGradient></defs> <title>wall post</title> <g opacity="0.1"><path d="M119.37,170.3c10.2,17.75,4.55,40.79-6.53,58S86,259.17,73.72,275.59C49.31,308.35,43,360.08,73.78,387c6.63,5.8,14.83,10.39,18.87,18.21C98.23,416,94.1,429,89.89,440.43l-.35.94C73.63,484.5,97.07,532.3,141,545.83c24.31,7.49,46.25,19.63,62.49,39.26,14.19,17.16,22.39,38.33,31.75,58.53C252.16,680.11,274,715,304.18,741.57s69.56,44.18,109.76,42.73c91.82-3.31,159.43-100,251.16-105.09,6.61-.37,13.65-.12,19.18,3.52,7.8,5.14,10.19,15.23,13.47,24,17.37,46.43,69.67,67.83,116.5,84.09l88,30.58c47.8,16.6,98,33.45,148,25.76s98.82-48.75,96.65-99.31c-1.78-41.53-36.2-78.08-31.69-119.41,3.12-28.56,24.51-52.55,28.09-81.06,3.73-29.67-12.49-58.16-31.16-81.52-53.72-67.24-130.28-111.56-205-154.31-52.63-30.12-105.53-60.36-162.19-82-139.68-53.21-293.2-50.92-442-65.44-25.59-2.5-52.34-5.44-75.79-16.75-18-8.68-32.07-28.75-51.82-33-15.05-3.24-37.31,3.45-50.45,10.76C100.94,138.54,107.54,149.71,119.37,170.3Z" transform="translate(-53 -50.93)" fill="#5850ec"></path></g> <path d="M1040.87,50.93H237.18A28,28,0,0,0,209.3,79.08V644.28a28,28,0,0,0,27.88,28.15h803.69a28,28,0,0,0,27.88-28.15V79.08A28,28,0,0,0,1040.87,50.93Z" transform="translate(-53 -50.93)" fill="url(#b86fccb4-da21-4a09-8d3b-ff994f3aa5aa)"></path> <path d="M1065.12,110.81V637.54a27.64,27.64,0,0,1-27.64,27.64H240.89a27.64,27.64,0,0,1-27.64-27.64V110.81Z" transform="translate(-53 -50.93)" fill="#f6f7f9"></path> <path d="M421.34,382.3V665.17H240.89c-15.26,0-27.64-12.62-27.64-28.18V382.3Z" transform="translate(-53 -50.93)" fill="#dde1ec"></path> <rect x="430.12" y="124.37" width="346.82" height="9.75" fill="#dde1ec"></rect> <rect x="430.12" y="193.73" width="209.17" height="9.75" fill="#dde1ec"></rect> <rect x="685.9" y="193.73" width="70.45" height="9.75" fill="#dde1ec"></rect> <g opacity="0.1"><rect x="224.19" y="437.04" width="70.45" height="9.75" fill="#5850ec"></rect></g> <rect x="685.9" y="284.77" width="70.45" height="9.75" fill="#dde1ec"></rect> <rect x="685.9" y="375.81" width="70.45" height="9.75" fill="#dde1ec"></rect> <rect x="685.9" y="224.08" width="70.45" height="9.75" fill="#dde1ec"></rect> <rect x="807.28" y="224.08" width="70.45" height="9.75" fill="#dde1ec"></rect> <rect x="685.9" y="254.42" width="70.45" height="9.75" fill="#dde1ec"></rect> <rect x="807.28" y="254.42" width="70.45" height="9.75" fill="#dde1ec"></rect> <rect x="807.28" y="310.78" width="70.45" height="9.75" fill="#dde1ec"></rect> <rect x="807.28" y="341.13" width="70.45" height="9.75" fill="#dde1ec"></rect> <rect x="807.28" y="406.16" width="70.45" height="9.75" fill="#dde1ec"></rect> <rect x="430.12" y="224.08" width="209.17" height="9.75" fill="#dde1ec"></rect> <rect x="430.12" y="254.42" width="209.17" height="9.75" fill="#dde1ec"></rect> <rect x="430.12" y="284.77" width="209.17" height="9.75" fill="#dde1ec"></rect> <rect x="430.12" y="315.12" width="209.17" height="9.75" fill="#dde1ec"></rect> <rect x="430.12" y="345.46" width="209.17" height="9.75" fill="#dde1ec"></rect> <rect x="430.12" y="375.81" width="209.17" height="9.75" fill="#dde1ec"></rect> <rect x="430.12" y="406.16" width="209.17" height="9.75" fill="#dde1ec"></rect> <rect x="160.25" y="59.34" width="208.09" height="277.45" fill="#5850ec"></rect> <path d="M1065.12,82.63A27.64,27.64,0,0,0,1037.48,55H240.89a27.64,27.64,0,0,0-27.64,27.64v31.43h851.87Z" transform="translate(-53 -50.93)" fill="#e8eaf1"></path> <circle cx="193.85" cy="33.33" r="10.84" fill="#ff5252"></circle> <circle cx="229.61" cy="33.33" r="10.84" fill="#ff0"></circle> <circle cx="265.38" cy="33.33" r="10.84" fill="#69f0ae"></circle> <path d="M378,249a60.41,60.41,0,0,1-13.52,38.17c-.33.41-.66.81-1,1.21h0a1.64,1.64,0,0,0-.18.22,61,61,0,0,1-10.47,9.59l-.5.37c-.69.49-1.39,1-2.1,1.42l-.49.31-1.05.65a5.33,5.33,0,0,1-.48.27,3.21,3.21,0,0,1-.41.25c-1.54.9-3.13,1.73-4.76,2.49-.18.1-.38.18-.57.27s-.41.2-.63.28-.65.29-1,.42a59.06,59.06,0,0,1-10.83,3.4,60.5,60.5,0,0,1-10.75,1.31h-.55l-1.43,0A60.59,60.59,0,0,1,303.11,308l-.87-.21q-3.06-.8-6-1.86c-1.19-.44-2.37-.92-3.52-1.43-.29-.13-.57-.25-.86-.39l-.87-.39-.76-.39h0l-.8-.41-.89-.47-1-.57-.16-.09a60.93,60.93,0,0,1-11.78-8.71c-.3-.29-.61-.57-.9-.88s-.63-.62-.93-.94A60.69,60.69,0,1,1,378,249Z" transform="translate(-53 -50.93)" fill="#fff"></path> <path d="M360,270.62a17.33,17.33,0,0,1-.59,2.67,2.47,2.47,0,0,1-.13.4c0-.38-.07-.78-.09-1.21h0c-.3-4.45-.56-11.48.34-15.64.85-3.91-5.34-2.72-9.47.78h0a10.89,10.89,0,0,0-2.88,3.61c-.09-.12-.18-.23-.28-.35a10.9,10.9,0,0,0-8.2-4.05,27.91,27.91,0,0,1-17.43-7.64c-.2-.81-.35-1.61-.47-2.42,0-.25-.08-.51-.11-.76a15.07,15.07,0,0,0,4.63-7.81s0-.08,0-.12a15.1,15.1,0,0,0,.38-3.32c0-.46,0-.9-.05-1.34,0-.16,0-.33-.05-.49s0-.38-.08-.56a6.61,6.61,0,0,0,.73-.38,8.38,8.38,0,0,0,3.24-3.67l0-.08a13.83,13.83,0,0,0,.64-1.66,0,0,0,0,0,0,0,20.07,20.07,0,0,0,.83-5c0-.21,0-.42,0-.65,0-1.31-.26-2.95-1.54-3.22-1.69-.36-2.92,2.19-4.63,1.92a2.6,2.6,0,0,1-1.34-.81c-1.16-1.15-1.94-2.63-3.1-3.79a10.6,10.6,0,0,0-6.62-2.81,25,25,0,0,0-7.28.67,12.26,12.26,0,0,0-3.76,1.28,4.48,4.48,0,0,0-2.25,3.09,4.41,4.41,0,0,0,0,.85c0,.42,0,.86,0,1.29a4.8,4.8,0,0,1-.07.55,4.57,4.57,0,0,1-2.31,2.76c-1.06.63-2.24,1.07-3.25,1.79a8.51,8.51,0,0,0-3.13,5,13.22,13.22,0,0,0-.35,3.39,22.43,22.43,0,0,0,.17,3.08,33.06,33.06,0,0,0,2.86,9.92,15.84,15.84,0,0,0,1.42,2.5s0,0,0,0c.14.2.28.38.43.55a15.09,15.09,0,0,0,3.23,2.73l.69.47a2,2,0,0,0,1.71.44,1.82,1.82,0,0,0,.52-.3,4,4,0,0,0,1.25-3.15v-.16a1,1,0,0,1,0,.17,1.64,1.64,0,0,1,0,.23l.1,0a14.8,14.8,0,0,1,0,1.53,5.59,5.59,0,0,1,0,.62c0,.12,0,.24,0,.35l-.16.14h0c-.33.28-.93.77-1.76,1.42h0a52.89,52.89,0,0,1-11.24,6.69,16,16,0,0,0-9.21,10.23,35.89,35.89,0,0,1-4.37,9.47c-.48.7-1.8,6-3.34,11.44.3.33.62.64.93.94s.6.59.9.88a60.93,60.93,0,0,0,11.78,8.71l.16.09,1,.57.89.47c0-.75-.1-1.44-.15-2.09,0-.39-.06-.76-.1-1.12.23-.38.43-.72.62-1,0,.29.07.61.1,1h0c.11,1,.22,2.23.34,3.64q0,.67.1,1.4c0-.49-.05-1-.09-1.4l.76.39.87.39c.28.14.56.26.86.39,1.15.51,2.33,1,3.52,1.43q2.91,1.07,6,1.86l.87.21a60.59,60.59,0,0,0,14.19,1.67l1.43,0h.55A60.5,60.5,0,0,0,330,308.34a59.06,59.06,0,0,0,10.83-3.4q.49-.2,1-.42c.22-.09.42-.18.63-.28s.39-.17.57-.27c-1-3.51-1.54-5.76-1.54-5.76l1.32-3.39.16-.41.4-1.05v0c0,.13.06.26.11.39v0a15.93,15.93,0,0,0,4.7,7.49,5.33,5.33,0,0,0,.48-.27l1.05-.65.49-.31c.72-.47,1.41-.93,2.1-1.42l.5-.37a61,61,0,0,0,10.47-9.59l.18-.22h0c.34-.4.67-.8,1-1.21Zm-8.72,4.56a1.1,1.1,0,0,1,.09.15l-.08-.07Z" transform="translate(-53 -50.93)" fill="url(#d2adb5db-d5c7-49ab-bfdd-50ec6aa449ae)"></path> <path d="M351.44,273.31v.08l.07.07C351.49,273.4,351.46,273.36,351.44,273.31Zm-61.16,30c-.1-1.4-.2-2.66-.29-3.74-.07-.76-.14-1.44-.2-2a.18.18,0,0,1,0-.08c0-.14,0-.27,0-.4a23,23,0,0,0-2.36,4.7l.16.09,1,.57.89.47.8.41q0,.67.1,1.4C290.33,304.25,290.31,303.79,290.28,303.34Zm13.31-57.17,0,0,0,0,.05.09h0a.23.23,0,0,1,0,.07A.39.39,0,0,0,303.59,246.17Z" transform="translate(-53 -50.93)" fill="url(#fd1a872a-c2fb-4cd4-906a-a62e176bbce4)"></path> <path d="M321,235.93s-3.61,16.22,6.31,24.79S303.88,276,298,268.83,302.08,258,302.08,258s5-4.06,0-16.22Z" transform="translate(-53 -50.93)" fill="#e0a17e"></path> <path d="M330,308.34a60.5,60.5,0,0,1-10.75,1.31h-.55l-1.43,0A60.59,60.59,0,0,1,303.11,308l-.87-.21q-3.06-.8-6-1.86l-1-15,4.34-20.77,2.55-12.24.72-3.42.73,1,.16.22.2.26,8,10.65,8.19-8.82.42-.46,2.25-2.43.85-.91.4,3.09,1.44,11.11,2.67,20.49Z" transform="translate(-53 -50.93)" fill="#f0f1f5"></path> <path d="M352.32,298.56c-.69.49-1.39,1-2.1,1.42l-.49.31-1.05.65a5.33,5.33,0,0,1-.48.27,3.21,3.21,0,0,1-.41.25A16.08,16.08,0,0,1,343,294.4a11,11,0,0,1-.3-1.06,1,1,0,0,1,0-.17l-.57,1.5L340.38,299s.52,2.12,1.45,5.48q-.49.23-1,.42a59.06,59.06,0,0,1-10.83,3.4,60.5,60.5,0,0,1-10.75,1.31h-.55a99.44,99.44,0,0,1-.53-14l1.32-25.17.67-12.7.37-7s.13.14.38.37.42.4.72.67h0a27,27,0,0,0,16,6.61,10.62,10.62,0,0,1,8.32,4.42,19.59,19.59,0,0,1,3.39,8.65l.3,2.75s0,0,0,0l.08.78.11,1Z" transform="translate(-53 -50.93)" opacity="0.1"></path> <path d="M352.82,298.2l-.5.37c-.69.49-1.39,1-2.1,1.42l-.49.31-1.05.65a16.58,16.58,0,0,1-5.18-7.22v0a2.77,2.77,0,0,1-.12-.36c-.11-.35-.21-.72-.29-1.08l-.41,1.07-.56,1.45-1.28,3.34s.59,2.4,1.63,6.1c-.21.1-.41.2-.63.28s-.65.29-1,.42a59.06,59.06,0,0,1-10.83,3.4,60.5,60.5,0,0,1-10.75,1.31,99.49,99.49,0,0,1-.63-14.92l1.28-24.39.68-13,.33-6.13.06-1.27a2,2,0,0,0,.17.17,27.09,27.09,0,0,0,17,7.49,10.62,10.62,0,0,1,8.24,4.31,19.54,19.54,0,0,1,3.48,8.76l.53,4.93v.07h0l.11,1Z" transform="translate(-53 -50.93)" fill="#293158"></path> <path d="M304.2,253l0,.61-.16,2.32-.56,7.94s-.33,2.29-.57,7.45c-.35,6.8-.56,18.58.25,36.7l-.87-.21q-3.06-.8-6-1.86c-1.19-.44-2.37-.92-3.52-1.43-.29-.13-.57-.25-.86-.39-.29-4-.64-6.45-.64-6.45s-.27.34-.68,1c-.17.27-.38.6-.6,1l-.05.09h0q-.29.5-.62,1.14c-.25.5-.5,1-.74,1.63l-1-.57-.16-.09a60.93,60.93,0,0,1-11.78-8.71c1.61-5.67,3-11.37,3.5-12.1a34.76,34.76,0,0,0,4.25-9.27,15.71,15.71,0,0,1,9-10A36.5,36.5,0,0,0,304.18,253a0,0,0,0,1,0,0A0,0,0,0,0,304.2,253Z" transform="translate(-53 -50.93)" opacity="0.1"></path> <path d="M304.21,252.72a54.08,54.08,0,0,1-12.81,8.07,15.71,15.71,0,0,0-9,10,34.76,34.76,0,0,1-4.25,9.27c-.49.73-1.89,6.44-3.5,12.12.29.3.6.59.9.88a60.93,60.93,0,0,0,11.78,8.71l.16.09a22.88,22.88,0,0,1,2.2-4.24l.05-.08c.35-.52.57-.8.57-.8s.09.66.22,1.85.3,3,.46,5.1l.87.39c.28.14.56.26.86.39,1.15.51,2.33,1,3.52,1.43q2.91,1.07,6,1.86c-.82-17.94-.64-29.76-.31-36.73.26-5.62.61-8.11.61-8.11l1.27-7.3.36-2.06.17-1Z" transform="translate(-53 -50.93)" fill="#293158"></path> <path d="M310.64,251.71a14.81,14.81,0,0,1-6.49-1.49,29.17,29.17,0,0,0-2.07-7.53L321,236.84a36.22,36.22,0,0,0-.39,11A14.82,14.82,0,0,1,310.64,251.71Z" transform="translate(-53 -50.93)" opacity="0.1"></path> <circle cx="257.64" cy="185.01" r="14.87" fill="#e0a17e"></circle> <g opacity="0.1"><path d="M340.86,304.94c-1.17-4.11-1.82-6.81-1.82-6.81l2.25-5.86a12.65,12.65,0,0,0,.77,2.4l.05.13-1.28,3.34s.59,2.4,1.63,6.1c-.21.1-.41.2-.63.28S341.18,304.81,340.86,304.94Z" transform="translate(-53 -50.93)"></path></g> <path d="M358.41,276.94s-1.35-13.07,0-19.38-15.77.9-12.17,9.46a73.58,73.58,0,0,0,7.21,13.52Z" transform="translate(-53 -50.93)" fill="#e0a17e"></path> <path d="M363.29,288.6a61.05,61.05,0,0,1-10.47,9.59l-.5.37c-.69.49-1.39,1-2.1,1.42a42.26,42.26,0,0,1-4.3-6.13l-.13-.23,3.61-18.93.38.39c.16.15.37.35.62.55h0l.05,0c1.87,1.6,5.82,4,7.72-1.58a16.85,16.85,0,0,0,.69-3l.37,1.38h0l.22.81,4,15.09h0Z" transform="translate(-53 -50.93)" opacity="0.1"></path> <path d="M363.29,288.6a61,61,0,0,1-10.47,9.59l-.5.37c-.69.49-1.39,1-2.1,1.42l-.49.31a43.62,43.62,0,0,1-3.94-5.78l.13-.67,3.48-18.26s.18.21.49.5l.62.55c.26.22.56.46.89.68a0,0,0,0,1,0,0c2.07,1.42,5.22,2.49,6.82-2.5a16.89,16.89,0,0,0,.64-2.84l.46,1.7Z" transform="translate(-53 -50.93)" fill="#293158"></path> <path d="M363.29,288.6a61,61,0,0,1-10.47,9.59l-.5.37c-.69.49-1.39,1-2.1,1.42l-.49.31a43.62,43.62,0,0,1-3.94-5.78l.13-.67,3.48-18.26s.18.21.49.5l.62.55c.26.22.56.46.89.68a0,0,0,0,1,0,0c2.07,1.42,5.22,2.49,6.82-2.5a16.89,16.89,0,0,0,.64-2.84l.46,1.7Z" transform="translate(-53 -50.93)" opacity="0.02"></path> <path d="M289.79,297.58l-.05.08c0-.05,0-.11,0-.15.63-2.19,1.2-3.67,1.71-4.11,0,0,.67,4.45,1.27,11.12-.29-.13-.57-.25-.86-.39l-.87-.39c-.15-2.15-.31-3.9-.46-5.1s-.22-1.85-.22-1.85S290.14,297.06,289.79,297.58Z" transform="translate(-53 -50.93)" opacity="0.1"></path> <path d="M310.82,231.84a4.08,4.08,0,0,1,2.55-.46,2.91,2.91,0,0,1,1,.7c1.17,1.11,2.33,2.4,3.91,2.74a7.56,7.56,0,0,0,2.93-.13,14,14,0,0,0,4.82-1.47c3.35-2,4.42-6.33,4.63-10.22.07-1.31-.2-3-1.49-3.28-1.64-.35-2.84,2.14-4.5,1.87a2.51,2.51,0,0,1-1.3-.79c-1.13-1.13-1.9-2.58-3-3.72a10.31,10.31,0,0,0-6.44-2.76,24.08,24.08,0,0,0-7.09.67,11.65,11.65,0,0,0-3.65,1.25,4.39,4.39,0,0,0-2.2,3c-.11.87.12,1.77-.05,2.63a4.42,4.42,0,0,1-2.24,2.71c-1,.62-2.18,1-3.16,1.76a8.37,8.37,0,0,0-3.05,4.86,16.46,16.46,0,0,0-.16,5.83,32.28,32.28,0,0,0,2.78,9.72,13.77,13.77,0,0,0,1.81,3,16.81,16.81,0,0,0,3.65,3,2.22,2.22,0,0,0,1.83.55,1.59,1.59,0,0,0,.51-.3c1.28-1.07,1.4-3,1-4.63s-1.08-3.18-1.16-4.84a2.33,2.33,0,0,1,.26-1.35,6,6,0,0,1,.9-.91,5.44,5.44,0,0,0,1-1.69,16.53,16.53,0,0,0,1.55-4.86,6.55,6.55,0,0,0-.87-4A5.17,5.17,0,0,0,310.82,231.84Z" transform="translate(-53 -50.93)" opacity="0.1"></path> <path d="M310.82,231.32a4.08,4.08,0,0,1,2.55-.46,2.91,2.91,0,0,1,1,.7c1.17,1.11,2.33,2.4,3.91,2.74a7.56,7.56,0,0,0,2.93-.13,14,14,0,0,0,4.82-1.47c3.35-2,4.42-6.33,4.63-10.22.07-1.31-.2-3-1.49-3.28-1.64-.35-2.84,2.14-4.5,1.87a2.51,2.51,0,0,1-1.3-.79c-1.13-1.13-1.9-2.58-3-3.72a10.31,10.31,0,0,0-6.44-2.76,24.08,24.08,0,0,0-7.09.67,11.65,11.65,0,0,0-3.65,1.25,4.39,4.39,0,0,0-2.2,3c-.11.87.12,1.77-.05,2.63a4.42,4.42,0,0,1-2.24,2.71c-1,.62-2.18,1-3.16,1.76a8.37,8.37,0,0,0-3.05,4.86,16.46,16.46,0,0,0-.16,5.83,32.28,32.28,0,0,0,2.78,9.72,13.77,13.77,0,0,0,1.81,3,16.81,16.81,0,0,0,3.65,3,2.22,2.22,0,0,0,1.83.55,1.59,1.59,0,0,0,.51-.3c1.28-1.07,1.4-3,1-4.63s-1.08-3.18-1.16-4.84a2.33,2.33,0,0,1,.26-1.35,6,6,0,0,1,.9-.91,5.44,5.44,0,0,0,1-1.69,16.53,16.53,0,0,0,1.55-4.86,6.55,6.55,0,0,0-.87-4A5.17,5.17,0,0,0,310.82,231.32Z" transform="translate(-53 -50.93)" fill="#463e3b"></path> <ellipse cx="176" cy="557.1" rx="25.19" ry="9.25" fill="#cd9494"></ellipse> <ellipse cx="176" cy="556.07" rx="12.85" ry="4.72" opacity="0.05"></ellipse> <path d="M203.8,606s27.76,15.42,50.38,2.06c0,0-3.08,53.47-18.51,58.61H215.12S197.64,630.64,203.8,606Z" transform="translate(-53 -50.93)" fill="#ff7361"></path> <path d="M250.75,508c7.07,25.83-23.07,99.41-23.07,99.41s-63.39-48-70.46-73.82A48.48,48.48,0,1,1,250.75,508Z" transform="translate(-53 -50.93)" fill="#5850ec"></path> <path d="M250.75,508c7.07,25.83-23.07,99.41-23.07,99.41s-63.39-48-70.46-73.82A48.48,48.48,0,1,1,250.75,508Z" transform="translate(-53 -50.93)" opacity="0.1"></path> <path d="M191.45,474.17s39.46,59.57,36.49,133.37" transform="translate(-53 -50.93)" fill="none" stroke="#535461" stroke-miterlimit="10"></path> <path d="M260.82,555.2S225,535.73,229.57,607.59c0,0,29.85-1.07,40.44-25.42a21.12,21.12,0,0,0-8.66-26.67Z" transform="translate(-53 -50.93)" fill="#5850ec"></path> <path d="M261.41,555.32s-4.62,37.3-31.84,52.27" transform="translate(-53 -50.93)" fill="none" stroke="#535461" stroke-miterlimit="10"></path> <path d="M250.82,609.77c-1,12.3-5.31,50.5-18.22,54.81H214.17c.59,1.31.95,2.06.95,2.06h20.57C251.1,661.49,254.19,608,254.19,608A37.44,37.44,0,0,1,250.82,609.77Z" transform="translate(-53 -50.93)" opacity="0.05"></path> <rect x="430.12" y="472.81" width="209.17" height="121.39" fill="#dde1ec"></rect> <path d="M1035.83,284.14a8.76,8.76,0,0,0,0-1.18c-.36-4.88-5-8.41-9.6-10.29s-9.74-2.8-13.81-5.65c-6.88-4.82-9-13.83-9.62-22.07-.19-2.56-.27-5.12-.28-7.69s.16-5.41.4-8.11c.4-4.48,1.06-9.12,1-13.61a26.15,26.15,0,0,0-2.4-12.45c-4.08-8.06-13.24-12.18-21.72-15.69-3.26-1.35-6.6-2.72-10.14-2.87-3.74-.16-7.38,1.05-10.92,2.25-5.62,1.9-11.32,4.37-17.13,5.66-3.26.72-6.83.18-10,1.33-6.22,2.28-7.55,7.87-7.45,14,0,.39,0,.77,0,1.16-.62-.57-1.26-1.11-1.91-1.63l-.38-.3q-1-.81-2.13-1.53l-.36-.23a35.84,35.84,0,0,0-4.79-2.61l-.25-.11q-1.25-.55-2.54-1l-.16-.06a36.46,36.46,0,0,0-5.46-1.44h0a36.37,36.37,0,0,0-32.52,10.22l-6.87,7h0c-31.23-7-32.63,20.13-32.63,20.13l.23-.05-.08.19A96.44,96.44,0,0,0,837,247.67L595.83,494l-24.38,75.56,13.69-4.88,62.54-22.34L871.62,313.58c16.76,11.91,34.18,27.87,34.18,27.87S895,347.16,899,373.32c4.89,32-2.46,77.55-2.46,77.55v29.46l1.38-.38c-.17,1-.35,2-.52,3.15a168.08,168.08,0,0,0-.86,44.72c3.7,31.26,18.48,73.94,18.48,73.94s5.54,15,6.78,27.05a25.22,25.22,0,0,0,1.58,6.95,55.26,55.26,0,0,1,4.32,20.87c0,13.08,1.71,32.71,9.5,52.74,6.25,16.08,8.56,25.5,9.25,31-4.43,5.86-10.22,12.25-14.79,12.89-8.62,1.2-25.26,2.4-14.17,16.83a10.21,10.21,0,0,0,1.29,1.37c-8.42,1.36-17.68,4.36-8.68,16.06,11.09,14.43,76.39,7.82,76.39,7.82s.42-8,0-16.82c4.58-.32,7.41-.61,7.41-.61s1.85-34.87-6.16-39.08a16.9,16.9,0,0,0-4.32,1.06c-.86-4.82-1.52-10.81-1.93-15.25l3.17-.24V672.1s-3.7-32.46-17.25-46.89a29.15,29.15,0,0,1-4.43-6c.1-4.17.11-10,.06-16.73a13.89,13.89,0,0,1,1.91-2.52s7.39-9,32.65-66.13c14.9-33.69,11.58-53.14,6.35-63.44-1-3.66-3.6-14.11-7-33.35-4.31-24.65.62-48.7.62-48.7s16-41.48,23.41-58.31a27.39,27.39,0,0,0,2.08-12.85c1.93-1.41,2.86-3.76,3.69-6l7.59-20.25c.82-2.19,1.66-4.47,1.48-6.8ZM932.75,247.68a30.8,30.8,0,0,0,2.75,3.66h0s-1.13-1.18-3-3.08C932.59,248.07,932.67,247.87,932.75,247.68Zm-37.32,41.57,20.89-21.34c7.22,6.6,14.95,13.34,21.41,18.89a61.84,61.84,0,0,1-2.44,8.37c-2.8,1.39-6.5,1.84-11-.61C917.16,290.71,904,289.57,895.43,289.25Z" transform="translate(-53 -50.93)" fill="url(#636ac3f3-8a9d-4cd8-9529-d45e35aeb867)"></path> <path d="M878.62,210.5S863.13,219.44,867.9,232c0,0,16.09,4.77,22.64,16.69s57.21,54.82,57.21,54.82L984.69,285s-12.85-17.16-53.5-29.14c0,0-24-25.69-32.31-30.45S878.62,210.5,878.62,210.5Z" transform="translate(-53 -50.93)" fill="#febdd5"></path> <path d="M878.62,210.5S863.13,219.44,867.9,232c0,0,16.09,4.77,22.64,16.69s57.21,54.82,57.21,54.82L984.69,285s-12.85-17.16-53.5-29.14c0,0-24-25.69-32.31-30.45S878.62,210.5,878.62,210.5Z" transform="translate(-53 -50.93)" opacity="0.05"></path> <path d="M602.62,496.37,871.4,215a34.68,34.68,0,0,1,49-1.12h0a34.68,34.68,0,0,1,1.12,49L652.78,544.29l-73.74,27Z" transform="translate(-53 -50.93)" fill="#5850ec"></path> <path d="M905.75,208.89h0a34.68,34.68,0,0,1,1.12,49L638.1,539.26l-55.36,20.25L579,571.27l73.74-27L921.55,263a34.68,34.68,0,0,0-1.12-49h0a34.52,34.52,0,0,0-20.31-9.41A34.82,34.82,0,0,1,905.75,208.89Z" transform="translate(-53 -50.93)" fill="#fff" opacity="0.1"></path> <polygon points="549.62 445.45 599.78 493.36 539.28 515.5 526.04 520.34 534.43 493.71 549.62 445.45" fill="#efc8c4"></polygon> <path d="M587.43,544.63a14.75,14.75,0,0,1,5.16,2.07c1.82,1.31,3.14,3.58,2.64,5.76a13.82,13.82,0,0,1-2.08,3.77,11.77,11.77,0,0,0-.87,10.2L579,571.27Z" transform="translate(-53 -50.93)" fill="#727a9c"></path> <path d="M975.16,718.8s1.79,26.22,4.77,29.2-26.82,4.77-26.82,4.77l4.17-34Z" transform="translate(-53 -50.93)" fill="#ffb9b9"></path> <path d="M975.16,718.8s1.79,26.22,4.77,29.2-26.82,4.77-26.82,4.77l4.17-34Z" transform="translate(-53 -50.93)" opacity="0.05"></path> <path d="M981.71,739.06s-4.17,0-19.07,7.75c0,0-10.13-1.79-14.3-16.09,0,0-12.51,21.45-20.86,22.64s-24.43,2.38-13.71,16.69,73.89,7.75,73.89,7.75S989.46,743.23,981.71,739.06Z" transform="translate(-53 -50.93)" fill="#cbcdda"></path> <path d="M981.71,739.06s-4.17,0-19.07,7.75c0,0-10.13-1.79-14.3-16.09,0,0-12.51,21.45-20.86,22.64s-24.43,2.38-13.71,16.69,73.89,7.75,73.89,7.75S989.46,743.23,981.71,739.06Z" transform="translate(-53 -50.93)" opacity="0.05"></path> <path d="M972.77,759.32l-4.17,11.32-27.41-7.75,1.56-11.24.38-2.71,1-7.5s23.24-6,23.24-3a46.23,46.23,0,0,0,1.53,7.26c1,3.66,2.15,7.84,3,10.59C972.42,758.13,972.77,759.32,972.77,759.32Z" transform="translate(-53 -50.93)" fill="#ffb9b9"></path> <path d="M957.82,603.91c-1.88,3.18-4,9.14-.05,16.58a28.87,28.87,0,0,0,4.28,5.94c13.11,14.3,16.69,46.48,16.69,46.48v51.84l-8.94.69-14.3,1.1L934,638.36s-12.51-64.36-6.55-75.68,11.32-82.23,11.32-82.23l4.95-5.12,11.74-12.16h34a22.84,22.84,0,0,1,6.61,7.39c5.7,9.44,10.82,29.07-4.82,65.31-24.43,56.61-31.58,65.55-31.58,65.55A13.73,13.73,0,0,0,957.82,603.91Z" transform="translate(-53 -50.93)" fill="#4c4c78"></path> <path d="M957.82,603.91c-1.88,3.18-4,9.14-.05,16.58a28.87,28.87,0,0,0,4.28,5.94c13.11,14.3,16.69,46.48,16.69,46.48v51.84l-8.94.69-14.3,1.1L934,638.36s-12.51-64.36-6.55-75.68,11.32-82.23,11.32-82.23l4.95-5.12,11.74-12.16h34a22.84,22.84,0,0,1,6.61,7.39c5.7,9.44,10.82,29.07-4.82,65.31-24.43,56.61-31.58,65.55-31.58,65.55A13.73,13.73,0,0,0,957.82,603.91Z" transform="translate(-53 -50.93)" opacity="0.05"></path> <path d="M968.94,745.72l-25.81,3.22,1-7.5s23.24-6,23.24-3A46.23,46.23,0,0,0,968.94,745.72Z" transform="translate(-53 -50.93)" opacity="0.1"></path> <path d="M956.09,527.52s1.48,46.91,1.73,76.39c.05,6.67,0,12.45-.05,16.58a36.74,36.74,0,0,1-.48,6.54c-1.79,5.36,1.79,17.28,1.79,17.28,14.9,39.33,10.73,73.89,10.73,73.89v26.22L941.19,748s4.77-3.58-8.34-38.14c-7.53-19.86-9.16-39.31-9.19-52.28a55.94,55.94,0,0,0-4.18-20.68A25.52,25.52,0,0,1,918,630c-1.19-11.92-6.55-26.82-6.55-26.82s-14.3-42.31-17.88-73.3a170.67,170.67,0,0,1,.83-44.33,110.63,110.63,0,0,1,2.15-11.09s58.25-36.66,80.25-11.32a28.69,28.69,0,0,1,5,8.34,55.06,55.06,0,0,1,2.81,9.28C991.37,514.42,956.09,527.52,956.09,527.52Z" transform="translate(-53 -50.93)" fill="#4c4c78"></path> <circle cx="901.3" cy="185.2" r="30.39" fill="#ffb9b9"></circle> <path d="M937.62,255.19s12.51,35.16,0,53.63,39.92,12.51,39.92,12.51l20.26-24.43s-23.84,0-25-41.71S937.62,255.19,937.62,255.19Z" transform="translate(-53 -50.93)" fill="#ffb9b9"></path> <path d="M997.8,477.46a78.2,78.2,0,0,1-13.28,3.33c-7.42,1.14-16.17,1.19-21.29-3.33-3-2.68-10.51-3-19.49-2.15-18.91,1.81-44.51,8.87-49.41,10.26a110.63,110.63,0,0,1,2.15-11.09s58.25-36.66,80.25-11.32h12.71a22.84,22.84,0,0,1,6.61,7.39C997.16,475.29,997.8,477.46,997.8,477.46Z" transform="translate(-53 -50.93)" opacity="0.1"></path> <path d="M972.77,759.32l-4.17,11.32-27.41-7.75,1.56-11.24c4.71,10.42,12.74,11.83,12.74,11.83,8.66-4.5,13.7-6.39,16.4-7.18C972.42,758.13,972.77,759.32,972.77,759.32Z" transform="translate(-53 -50.93)" opacity="0.1"></path> <path d="M974.56,756.34s-4.17,0-19.07,7.75c0,0-10.13-1.79-14.3-16.09,0,0-12.51,21.45-20.86,22.64S895.9,773,906.63,787.33s73.89,7.75,73.89,7.75S982.31,760.51,974.56,756.34Z" transform="translate(-53 -50.93)" fill="#cbcdda"></path> <path d="M833.19,241.95s1.35-26.89,31.56-19.95L857,236.53Z" transform="translate(-53 -50.93)" fill="#ffb9b9"></path> <path d="M974,304.06s-29.2-4.77-36.95-10.73c0,0-6,11.32-16.69,5.36s-35.75-5.36-35.75-5.36-20.86-11.32-25-36.95c0,0-4.17-15.49-3-19.66,0,0-15.49-16.09-23.24,5.36,3,14.9,5.36,13.71,4.77,25.62l9.53,26.22s.6,8.94,17.88,20.86,36.95,30.39,36.95,30.39-10.43,5.66-6.55,31.58c4.73,31.68-2.38,76.87-2.38,76.87v29.2s59.59-17.28,69.72-8.34,34.56,0,34.56,0-3-10.13-7.15-34.56.6-48.27.6-48.27,15.49-41.12,22.64-57.8-5.36-39.33-17.88-47.67-17.17-3.33-17.17-3.33S1000.78,298.7,974,304.06Z" transform="translate(-53 -50.93)" fill="#febdd5"></path> <path d="M921.43,228.69a10.54,10.54,0,0,0,.62,3.64,11.48,11.48,0,0,0,2.79,3.47c4.46,4.36,8.38,9.54,9.95,15.58,1.06,4.09,1,8.37.94,12.6-.26,17.11-1,35.58-11.54,49-3.88,4.95-8.88,8.88-13.57,13.07A4.09,4.09,0,0,0,909.2,328a3.57,3.57,0,0,0,.44,2.36c2.18,4.53,6.77,7.31,11.12,9.83,2,1.15,4.08,2.34,6.38,2.41,5.42.18,9-5.55,13.93-7.8,7.18-3.28,15.23,1.28,22.36,4.68a78.79,78.79,0,0,0,16.14,5.63c7.63,1.76,16.25,2.19,22.67-2.28a17.31,17.31,0,0,0,7.06-11.05c.52-2.91.44-6.29,2.64-8.26,1.14-1,2.69-1.44,3.91-2.34,1.91-1.4,2.81-3.75,3.62-6l7.34-20.07a16.49,16.49,0,0,0,1.43-6.74c-.35-4.83-4.81-8.34-9.28-10.2s-9.42-2.77-13.36-5.6c-6.65-4.78-8.71-13.71-9.31-21.88a105.34,105.34,0,0,1,.12-16.85c.73-8.31,2.35-17.17-1.35-24.65-3.95-8-12.81-12.07-21-15.55-3.15-1.34-6.39-2.69-9.81-2.84-3.62-.16-7.14,1-10.56,2.23-5.44,1.89-11,4.33-16.57,5.61-3.15.72-6.61.18-9.65,1.32C916.3,204.16,921.43,219.7,921.43,228.69Z" transform="translate(-53 -50.93)" opacity="0.1"></path> <path d="M921.43,227.5a10.54,10.54,0,0,0,.62,3.64,11.48,11.48,0,0,0,2.79,3.47c4.46,4.36,8.38,9.54,9.95,15.58,1.06,4.09,1,8.37.94,12.6-.26,17.11-1,35.58-11.54,49-3.88,4.95-8.88,8.88-13.57,13.07a4.09,4.09,0,0,0-1.43,1.91,3.57,3.57,0,0,0,.44,2.36c2.18,4.53,6.77,7.31,11.12,9.83,2,1.15,4.08,2.34,6.38,2.41,5.42.18,9-5.55,13.93-7.8,7.18-3.28,15.23,1.28,22.36,4.68a78.79,78.79,0,0,0,16.14,5.63c7.63,1.76,16.25,2.19,22.67-2.28a17.31,17.31,0,0,0,7.06-11.05c.52-2.91.44-6.29,2.64-8.26,1.14-1,2.69-1.44,3.91-2.34,1.91-1.4,2.81-3.75,3.62-6l7.34-20.07a16.49,16.49,0,0,0,1.43-6.74c-.35-4.83-4.81-8.34-9.28-10.2s-9.42-2.77-13.36-5.6c-6.65-4.78-8.71-13.71-9.31-21.88a105.34,105.34,0,0,1,.12-16.85c.73-8.31,2.35-17.17-1.35-24.65-3.95-8-12.81-12.07-21-15.55-3.15-1.34-6.39-2.69-9.81-2.84-3.62-.16-7.14,1-10.56,2.23-5.44,1.89-11,4.33-16.57,5.61-3.15.72-6.61.18-9.65,1.32C916.3,203,921.43,218.51,921.43,227.5Z" transform="translate(-53 -50.93)" fill="#b96b6b"></path> <path d="M897.41,390.13S902.16,389,926,385.4s65.55,4.77,65.55,4.77" transform="translate(-53 -50.93)" opacity="0.05"></path></svg> <span class="text-gray-600">There are no comments to show here</span></div></div>
                                            @endif
                                        </div>
                                    </div>
                                </section>
                            </div>
                            <div class="tab_pane_tasks-{{ $task->id }} tab-pane fade" id="tasks{{ $task->id }}" role="tabpanel" aria-labelledby="tasks-tab{{ $task->id }}">
                                @php
                                    $pendingTasks = PipelineProcessTask::with('assignUser')->where('task_id',$task->task_id)->where('completed_at','=', NULL)->get()->toArray();
                                    $completeTasks = PipelineProcessTask::with('assignUser')->where('task_id',$task->task_id)->where('completed_at','!=', NULL)->get()->toArray();
                                    $totalTasks = PipelineProcessTask::where('task_id',$task->task_id)->get()->count();
                                @endphp
                                <section class="p-3">
                                    <div class="flex flex-col">
                                        <div class="mb-6">
                                            <div class="mt-1 flex rounded-md shadow-sm">
                                                <div class="relative flex-grow focus-within:z-10">
                                                    <input placeholder="Enter a new task.." class="subTaskVal form-input rounded-r-none" value="">
                                                    <input type="hidden" class="subTaskId" value="{{ $task->task_id }}">
                                                </div> 
                                                <div class="ml-auto pl-2 flex items-center">
                                                <div>
                                                    <div class="relative inline-block text-left">
                                                        <div>
                                                            <a href="javascript:void(0)"
                                                                class="add-sub-task-due-date-calendar ml-1 flex items-center justify-center rounded-full overflow-hidden text-gray-400 border w-6 h-6 overflow-hidden hover:text-gray-500 hover:bg-gray-50">
                                                                <svg
                                                                    viewBox="0 0 20 20" fill="currentColor" class="w-3 h-3" >
                                                                    <path
                                                                        d="M1 4c0-1.1.9-2 2-2h14a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V4zm2 2v12h14V6H3zm2-6h2v2H5V0zm8 0h2v2h-2V0zM5 9h2v2H5V9zm0 4h2v2H5v-2zm4-4h2v2H9V9zm0 4h2v2H9v-2zm4-4h2v2h-2V9zm0 4h2v2h-2v-2z">
                                                                    </path>
                                                                </svg>
                                                            </a>
                                                        </div>
                                                        
                                                    </div>
                                                </div>
                                                <div class="ml-1 mr-2">
                                                    <div class="flex items-center">
                                                        <div class="relative inline-block text-left">
                                                            <div>
                                                            <a href="javascript:void(0)"
                                                                    class="assignSubTask-1 ml-1 flex items-center justify-center rounded-full overflow-hidden text-gray-400 border w-6 h-6 overflow-hidden hover:text-gray-500 hover:bg-gray-50">
                                                                    <img src="{{asset('profiles/assign_user.png') }}" alt="avatar" class="avatar avatar-xs assign-user-profile-image-add-sub-task{{ $task->task_id }}">
                                                                </a>
                                                            </div>
                                                            <div class="assignSubTaskPopup-1 origin-top-right absolute mt-2 w-auto rounded-md shadow-lg z-40 right-0"
                                                                style="display: none;">
                                                                <div class="w-64">
                                                                    <div class="flex flex-col">
                                                                        <div class="px-4 pt-4 mb-2">
                                                                            <input placeholder="Search.." class="form-input assign-users-add-sub-task-search">
                                                                        </div>
                                                                        <div class="w-64 overflow-y-auto" style="height: 120px; background-color: #fff" id="filter-assign-users-add-sub-task">
                                                                            @foreach($staffs as $staff)
                                                                            <a href="javascript:void(0)" class="dropdown-item flex items-center assign-user-add-sub-task" data-item=" {{$staff['profile_picture']}}" data-id="{{$staff['id']}} " id="{{$task->task_id}}" data-sub="{{$staff['id']}}" data-plus="{{$subTask['assignUser']['id']}}" >
                                                                                <div class="flex-shrink-0 flex items-center assign-user-image-add-sub-task{{$task->task_id}} ">
                                                                                    <img src="{{url('uploads/thumb_')}}{{$staff['profile_picture']}}"
                                                                                        alt="avatar" class="avatar avatar-xs">
                                                                                </div>
                                                                                <div class="inline-flex w-6 h-6 justify-center items-center text-xs bg-green-400 text-white rounded-full user-assigned-checbox-add-sub-task{{$task->task_id}}" style="display:none;"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" class="w-3 h-3 fill-current"><path d="M0 11l2-2 5 5L18 3l2 2L7 18z"></path></svg></div>
                                                                                <div
                                                                                    class="text-sm leading-5 text-gray-700 group-hover:text-gray-900 truncate pl-4">
                                                                                {{$staff['first_name']}} {{$staff['last_name']}}
                                                                                </div>
                                                                            </a>
                                                                            @endforeach
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                                <button class="addSubTask -ml-px relative inline-flex 
                                                items-center px-4 py-2 border border-gray-300 text-sm 
                                                leading-5 font-medium rounded-r-md text-gray-700 bg-gray-50 
                                                hover:text-gray-500 hover:bg-white focus:outline-none 
                                                focus:shadow-outline-blue focus:border-blue-300 
                                                active:bg-gray-100 active:text-gray-700 transition 
                                                ease-in-out duration-150" total-sub-task="{{ count($completeTasks)+count($pendingTasks) }}" complete-sub-task="{{ count($completeTasks) }}">
                                                    <svg fill="currentColor" viewBox="0 0 20 20" class="h-4 w-4 text-gray-400">
                                                        <path
                                                            d="M11 9V5H9v4H5v2h4v4h2v-4h4V9h-4zm-1 11a10 10 0 1 1 0-20 10 10 0 0 1 0 20z">
                                                        </path>
                                                    </svg> 
                                                    <span class="ml-2">Add</span>
                                                </button>
                                            </div>
                                        </div>
                                        <!---->
                                        
                                    <div class="flex flex-col mb-4 latestSubTask completed-task-{{ $task->task_id }}">
                                        <input type="hidden" id="total-sub-task-{{ $task->task_id }}" value="{{ count($completeTasks)+count($pendingTasks) }}">
                                        <input type="hidden" id="complete-sub-task-{{ $task->task_id }}" value="{{ count($completeTasks) }}">
                                        @if($totalTasks > 0)
                                            @if(count($pendingTasks)>0)
                                                <div class="pb-2 border-b border-gray-200 mb-1">
                                                    <h4 class="font-medium text-lg">Todo ({{ count($pendingTasks) }})</h4>
                                                </div>
                                                @foreach ($pendingTasks as $key => $subTask) 
                                                <div class="flex items-center px-4 py-2 mb-1 hover:bg-gray-100 rounded-lg">
                                                    <div>
                                                        <input type="hidden" class="sub-Task-Id" value="{{ $subTask['id'] }}">
                                                        <input type="checkbox" class="subTaskComplete form-checkbox w-6 h-6 rounded-full text-green-400" main-task-id="{{ $task->task_id }}" total-sub-task="{{ count($completeTasks)+count($pendingTasks) }}" complete-sub-task="{{ count($completeTasks) }}"
                                                        @if ($subTask['completed_at'] != NULL) checked @endif>
                                                    </div>
                                                    <div class="pl-3 w-full">
                                                        
                                                        <div class="hideSubTask w-full text-sm">
                                                            <div class="cursor-pointer mb-0">
                                                                {{ $subTask['content'] }}
                                                            </div>
                                                        </div>
                                                        <div class="editSubTask mt-1 flex rounded-md shadow-sm">
                                                            <div class="relative flex-grow focus-within:z-10">
                                                                <input class="subtaskname form-input block w-full rounded-none rounded-l-md transition ease-in-out duration-150 sm:text-sm sm:leading-5" value="{{ $subTask['content'] }}">
                                                                <input type="hidden" class="subtaskid" value="{{ $subTask['id'] }}">
                                                            </div> 
                                                            <span class="btn-group">
                                                                <button type="button" class="hideSubTaskButton btn btn-white rounded-l-none border-l-0">
                                                                    <svg fill="currentColor" viewBox="0 0 20 20" class="h-4 w-4 text-gray-400">
                                                                        <path d="M10 8.586L2.929 1.515 1.515 2.929 8.586 10l-7.071 7.071 1.414 1.414L10 11.414l7.071 7.071 1.414-1.414L11.414 10l7.071-7.071-1.414-1.414L10 8.586z"></path>
                                                                    </svg>
                                                                </button> 
                                                                <button type="button" class="editSubTaskButton btn btn-white">
                                                                    <svg fill="currentColor" viewBox="0 0 20 20" class="h-4 w-4 text-gray-400">
                                                                        <path d="M0 11l2-2 5 5L18 3l2 2L7 18z"></path>
                                                                    </svg>
                                                                </button>
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="ml-auto pl-2 flex items-center">
                                                        <div>
                                                            <div class="relative inline-block text-left">
                                                                <div>
                                                                    <a href="javascript:void(0)" @if($subTask['due_date'] != '0000-00-00') style="color:black" @endif title="{{ $subTask['due_date'] }}" sub-task-id="{{ $subTask['id'] }}"
                                                                        class="sub-task-duedate ml-1 flex items-center justify-center rounded-full overflow-hidden text-gray-400 border w-6 h-6 overflow-hidden hover:text-gray-500 hover:bg-gray-50"><svg
                                                                            viewBox="0 0 20 20" fill="currentColor" class="w-3 h-3">
                                                                            <path
                                                                                d="M1 4c0-1.1.9-2 2-2h14a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V4zm2 2v12h14V6H3zm2-6h2v2H5V0zm8 0h2v2h-2V0zM5 9h2v2H5V9zm0 4h2v2H5v-2zm4-4h2v2H9V9zm0 4h2v2H9v-2zm4-4h2v2h-2V9zm0 4h2v2h-2v-2z">
                                                                            </path>
                                                                        </svg>
                                                                        <input type="text" class='subTaskDueDate' data-date-format="Y-m-d" value="@if($subTask['due_date'] != '0000-00-00') {{ $subTask['due_date'] }} @else null @endif">
                                                                        {{-- <input type="hidden" class="dueDateSubTaskId" value="{{ $subTask['id'] }}"> --}}
                                                                    </a>
                                                                </div>
                                                                
                                                            </div>
                                                        </div>
                                                        <div class="ml-1">
                                                            <div class="flex items-center">
                                                                <div class="relative inline-block text-left">
                                                                    <div>
                                                                        <a href="javascript:void(0)"
                                                                            class="assignSubTask ml-1 flex items-center justify-center rounded-full overflow-hidden text-gray-400 border w-6 h-6 overflow-hidden hover:text-gray-500 hover:bg-gray-50">
                                                                            @if($subTask['assign_user'] && $subTask['assign_user']['profile_picture'] )
                                                                            <img src="{{url('uploads/thumb_')}}{{$subTask['assign_user']['profile_picture']}}" alt="avatar" title="{{ $subTask['assign_user']['first_name'] }} {{ $subTask['assign_user']['last_name'] }}" class="avatar avatar-xs assign-user-profile-image-sub-task{{ $subTask['id'] }}">
                                                                            @else
                                                                            <img src="{{asset('profiles/assign_user.png') }}" alt="avatar" class="avatar avatar-xs assign-user-profile-image-sub-task{{ $subTask['id'] }}">
                                                                            @endif
                                                                        </a>
                                                                    </div>
                                                                    <div class="assignSubTaskPopup origin-top-right absolute mt-2 w-auto rounded-md shadow-lg z-40 right-0"
                                                                        style="display: none;">
                                                                        <div class="w-64">
                                                                            <div class="flex flex-col">
                                                                                <div class="px-4 pt-4 mb-2">
                                                                                    <input placeholder="Search.." class="form-input assign-users-sub-task-search">
                                                                                </div>
                                                                                <div class="w-64 overflow-y-auto" style="height: 120px; background-color: #fff" id="filter-assign-users-sub-task">
                                                                                    @foreach($staffs as $staff)
                                                                                    @if($staff['id'] == $subTask['assign_user']['id'])
                                                                                    @php
                                                                                    $show_checkbox = '';
                                                                                    $show_user_image = 'none';
                                                                                    @endphp
                                                                                    @else
                                                                                    @php
                                                                                    $show_checkbox = 'none';
                                                                                    $show_user_image = 'block';
                                                                                    @endphp
                                                                                    @endif
                                                                                    <a href="javascript:void(0)" class="dropdown-item flex items-center assign-user-sub-task" data-item=" {{$staff['profile_picture']}}" data-id="{{$staff['id']}} " id="{{$subTask['id']}}" data-sub="{{$staff['id']}}" data-plus="{{$subTask['assignUser']['id']}}" >
                                                                                        <div class="flex-shrink-0 flex items-center assign-user-image-sub-task{{$subTask['id']}} " style="display: {{$show_user_image}};">
                                                                                            <img src="{{url('uploads/thumb_')}}{{$staff['profile_picture']}}"
                                                                                                alt="avatar" class="avatar avatar-xs">
                                                                                        </div>
                                                                                        <div class="inline-flex w-6 h-6 justify-center items-center text-xs bg-green-400 text-white rounded-full user-assigned-checbox-sub-task{{$subTask['id']}}" style="display:{{$show_checkbox}};"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" class="w-3 h-3 fill-current"><path d="M0 11l2-2 5 5L18 3l2 2L7 18z"></path></svg></div>
                                                                                        <div
                                                                                            class="text-sm leading-5 text-gray-700 group-hover:text-gray-900 truncate pl-4">
                                                                                        {{$staff['first_name']}} {{$staff['last_name']}}
                                                                                        </div>
                                                                                    </a>
                                                                                    @endforeach
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endforeach
                                                @endif
                                                @if(count($completeTasks)>0)
                                                <div class="pb-2 border-b border-gray-200 mb-1">
                                                <h4 class="font-medium text-lg">Completed ({{ count($completeTasks) }})</h4>
                                                </div>
                                                @foreach ($completeTasks as $key => $subTask) 
                                                <div class="flex items-center px-4 py-2 mb-1 hover:bg-gray-100 rounded-lg ">
                                                    <div>
                                                        <input type="hidden" class="sub-Task-Id" value="{{ $subTask['id'] }}">
                                                        <input type="checkbox" class="subTaskComplete form-checkbox w-6 h-6 rounded-full text-green-400" main-task-id="{{ $task->task_id }}" total-sub-task="{{ count($completeTasks)+count($pendingTasks) }}" complete-sub-task="{{ count($completeTasks) }}"
                                                        @if ($subTask['completed_at'] != NULL) checked @endif>
                                                    </div>
                                                    <div class="pl-3 w-full">
                                                        
                                                        <div class="hideSubTask w-full text-sm">
                                                            <div class="cursor-pointer mb-0">
                                                                {{ $subTask['content'] }}
                                                            </div>
                                                        </div>
                                                        <div class="editSubTask mt-1 flex rounded-md shadow-sm">
                                                            <div class="relative flex-grow focus-within:z-10">
                                                                <input class="subtaskname form-input block w-full rounded-none rounded-l-md transition ease-in-out duration-150 sm:text-sm sm:leading-5" value="{{ $subTask['content'] }}">
                                                                <input type="hidden" class="subtaskid" value="{{ $subTask['id'] }}">
                                                            </div> 
                                                            <span class="btn-group">
                                                                <button type="button" class="hideSubTaskButton btn btn-white rounded-l-none border-l-0">
                                                                    <svg fill="currentColor" viewBox="0 0 20 20" class="h-4 w-4 text-gray-400">
                                                                        <path d="M10 8.586L2.929 1.515 1.515 2.929 8.586 10l-7.071 7.071 1.414 1.414L10 11.414l7.071 7.071 1.414-1.414L11.414 10l7.071-7.071-1.414-1.414L10 8.586z"></path>
                                                                    </svg>
                                                                </button> 
                                                                <button type="button" class="editSubTaskButton btn btn-white">
                                                                    <svg fill="currentColor" viewBox="0 0 20 20" class="h-4 w-4 text-gray-400">
                                                                        <path d="M0 11l2-2 5 5L18 3l2 2L7 18z"></path>
                                                                    </svg>
                                                                </button>
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="ml-auto pl-2 flex items-center">
                                                        <div>
                                                            <div class="relative inline-block text-left">
                                                                <div>
                                                                    <a href="javascript:void(0)" @if($subTask['due_date'] != '0000-00-00') style="color:black" @endif title="{{ $subTask['due_date'] }}" sub-task-id="{{ $subTask['id'] }}"
                                                                        class="sub-task-duedate ml-1 flex items-center justify-center rounded-full overflow-hidden text-gray-400 border w-6 h-6 overflow-hidden hover:text-gray-500 hover:bg-gray-50">
                                                                        <svg
                                                                            viewBox="0 0 20 20" fill="currentColor" class="w-3 h-3">
                                                                            <path
                                                                                d="M1 4c0-1.1.9-2 2-2h14a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V4zm2 2v12h14V6H3zm2-6h2v2H5V0zm8 0h2v2h-2V0zM5 9h2v2H5V9zm0 4h2v2H5v-2zm4-4h2v2H9V9zm0 4h2v2H9v-2zm4-4h2v2h-2V9zm0 4h2v2h-2v-2z">
                                                                            </path>
                                                                        </svg>                          
                                                                        <input type="text" class='subTaskDueDate' data-date-format="Y-m-d" value="@if($subTask['due_date'] != '0000-00-00') {{ $subTask['due_date'] }} @else null @endif">                                          
                                                                        {{-- <input type="hidden" class="dueDateSubTaskId" value="{{ $subTask['id'] }}"> --}}
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="ml-1">
                                                                    <div class="flex items-center">
                                                                        <div class="relative inline-block text-left">
                                                                            <div>
                                                                                <a href="javascript:void(0)"
                                                                                class="assignSubTask ml-1 flex items-center justify-center rounded-full overflow-hidden text-gray-400 border w-6 h-6 overflow-hidden hover:text-gray-500 hover:bg-gray-50">
                                    
                                                                                @if($subTask['assign_user'] && $subTask['assign_user']['profile_picture'] )
                                                                                <img src="{{url('uploads/thumb_')}}{{$subTask['assign_user']['profile_picture']}}" alt="avatar" title="{{ $subTask['assign_user']['first_name'] }} {{ $subTask['assign_user']['last_name'] }}" class="avatar avatar-xs assign-user-profile-image-sub-task{{ $subTask['id'] }}">
                                                                                @else
                                                                                <img src="{{asset('profiles/assign_user.png')}}" alt="avatar" class="avatar avatar-xs assign-user-profile-image-sub-task{{ $subTask['id'] }}">
                                                                                @endif
                                                                                </a>
                                                                            </div>
                                                                            <div class="assignSubTaskPopup origin-top-right absolute mt-2 w-auto rounded-md shadow-lg z-40 right-0"
                                                                            style="display: none;">
                                                                                <div class="w-64">
                                                                                    <div class="flex flex-col">
                                                                                        <div class="px-4 pt-4 mb-2">
                                                                                            <input placeholder="Search.." class="form-input assign-users-sub-task-search">
                                                                                        </div>
                                                                                        <div class="w-64 overflow-y-auto" style="height: 120px; background-color: #fff" id="filter-assign-users-sub-task">
                                                                                            @foreach($staffs as $staff)
                                                                                            @if($staff['id'] == $subTask['assign_user']['id'])
                                                                                            @php
                                                                                            $show_checkbox = '';
                                                                                            $show_user_image = 'none';
                                                                                            @endphp
                                                                                            @else
                                                                                            @php
                                                                                            $show_checkbox = 'none';
                                                                                            $show_user_image = 'block';
                                                                                            @endphp
                                                                                            @endif
                                                                                            <a href="javascript:void(0)" class="dropdown-item flex items-center assign-user-sub-task" data-item=" {{$staff['profile_picture']}}" data-id="{{$staff['id']}} " id="{{$subTask['id']}}" data-sub="{{$staff['id']}}" data-plus="{{$subTask['assignUser']['id']}}">
                                                                                            <div class="flex-shrink-0 flex items-center assign-user-image-sub-task{{$subTask['id']}} " style="display: {{$show_user_image}};">
                                                                                            <img src="{{url('uploads/thumb_')}}{{$staff['profile_picture']}}"
                                                                                                alt="avatar" class="avatar avatar-xs">
                                                                                            </div>
                                                                                            <div class="inline-flex w-6 h-6 justify-center items-center text-xs bg-green-400 text-white rounded-full user-assigned-checbox-sub-task{{$subTask['id']}}" style="display:{{$show_checkbox}};"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" class="w-3 h-3 fill-current"><path d="M0 11l2-2 5 5L18 3l2 2L7 18z"></path></svg></div>
                                                                                            <div
                                                                                            class="text-sm leading-5 text-gray-700 group-hover:text-gray-900 truncate pl-4">
                                                                                            {{$staff['first_name']}} {{$staff['last_name']}}
                                                                                            </div>
                                                                                            </a>
                                                                                            @endforeach
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                    </div>
                                                </div>
                                                @endforeach
                                                @endif
                                                @else
                                                    <div class="flex flex-col items-center"><div class="flex flex-col items-center"><svg data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="300" height="300" viewBox="0 0 1094 798.15"><defs><linearGradient id="b86fccb4-da21-4a09-8d3b-ff994f3aa5aa" x1="639.03" y1="672.43" x2="639.03" y2="50.93" gradientUnits="userSpaceOnUse"><stop offset="0" stop-color="gray" stop-opacity="0.25"></stop> <stop offset="0.54" stop-color="gray" stop-opacity="0.12"></stop> <stop offset="1" stop-color="gray" stop-opacity="0.1"></stop></linearGradient> <linearGradient id="d2adb5db-d5c7-49ab-bfdd-50ec6aa449ae" x1="319.12" y1="309.68" x2="319.12" y2="212.15" xlink:href="#b86fccb4-da21-4a09-8d3b-ff994f3aa5aa"></linearGradient> <linearGradient id="fd1a872a-c2fb-4cd4-906a-a62e176bbce4" x1="332.56" y1="304.74" x2="332.56" y2="246.12" gradientTransform="matrix(-1, 0, 0, 1, 652, 0)" xlink:href="#b86fccb4-da21-4a09-8d3b-ff994f3aa5aa"></linearGradient> <linearGradient id="636ac3f3-8a9d-4cd8-9529-d45e35aeb867" x1="803.65" y1="796.85" x2="803.65" y2="184.5" xlink:href="#b86fccb4-da21-4a09-8d3b-ff994f3aa5aa"></linearGradient></defs> <title>wall post</title> <g opacity="0.1"><path d="M119.37,170.3c10.2,17.75,4.55,40.79-6.53,58S86,259.17,73.72,275.59C49.31,308.35,43,360.08,73.78,387c6.63,5.8,14.83,10.39,18.87,18.21C98.23,416,94.1,429,89.89,440.43l-.35.94C73.63,484.5,97.07,532.3,141,545.83c24.31,7.49,46.25,19.63,62.49,39.26,14.19,17.16,22.39,38.33,31.75,58.53C252.16,680.11,274,715,304.18,741.57s69.56,44.18,109.76,42.73c91.82-3.31,159.43-100,251.16-105.09,6.61-.37,13.65-.12,19.18,3.52,7.8,5.14,10.19,15.23,13.47,24,17.37,46.43,69.67,67.83,116.5,84.09l88,30.58c47.8,16.6,98,33.45,148,25.76s98.82-48.75,96.65-99.31c-1.78-41.53-36.2-78.08-31.69-119.41,3.12-28.56,24.51-52.55,28.09-81.06,3.73-29.67-12.49-58.16-31.16-81.52-53.72-67.24-130.28-111.56-205-154.31-52.63-30.12-105.53-60.36-162.19-82-139.68-53.21-293.2-50.92-442-65.44-25.59-2.5-52.34-5.44-75.79-16.75-18-8.68-32.07-28.75-51.82-33-15.05-3.24-37.31,3.45-50.45,10.76C100.94,138.54,107.54,149.71,119.37,170.3Z" transform="translate(-53 -50.93)" fill="#5850ec"></path></g> <path d="M1040.87,50.93H237.18A28,28,0,0,0,209.3,79.08V644.28a28,28,0,0,0,27.88,28.15h803.69a28,28,0,0,0,27.88-28.15V79.08A28,28,0,0,0,1040.87,50.93Z" transform="translate(-53 -50.93)" fill="url(#b86fccb4-da21-4a09-8d3b-ff994f3aa5aa)"></path> <path d="M1065.12,110.81V637.54a27.64,27.64,0,0,1-27.64,27.64H240.89a27.64,27.64,0,0,1-27.64-27.64V110.81Z" transform="translate(-53 -50.93)" fill="#f6f7f9"></path> <path d="M421.34,382.3V665.17H240.89c-15.26,0-27.64-12.62-27.64-28.18V382.3Z" transform="translate(-53 -50.93)" fill="#dde1ec"></path> <rect x="430.12" y="124.37" width="346.82" height="9.75" fill="#dde1ec"></rect> <rect x="430.12" y="193.73" width="209.17" height="9.75" fill="#dde1ec"></rect> <rect x="685.9" y="193.73" width="70.45" height="9.75" fill="#dde1ec"></rect> <g opacity="0.1"><rect x="224.19" y="437.04" width="70.45" height="9.75" fill="#5850ec"></rect></g> <rect x="685.9" y="284.77" width="70.45" height="9.75" fill="#dde1ec"></rect> <rect x="685.9" y="375.81" width="70.45" height="9.75" fill="#dde1ec"></rect> <rect x="685.9" y="224.08" width="70.45" height="9.75" fill="#dde1ec"></rect> <rect x="807.28" y="224.08" width="70.45" height="9.75" fill="#dde1ec"></rect> <rect x="685.9" y="254.42" width="70.45" height="9.75" fill="#dde1ec"></rect> <rect x="807.28" y="254.42" width="70.45" height="9.75" fill="#dde1ec"></rect> <rect x="807.28" y="310.78" width="70.45" height="9.75" fill="#dde1ec"></rect> <rect x="807.28" y="341.13" width="70.45" height="9.75" fill="#dde1ec"></rect> <rect x="807.28" y="406.16" width="70.45" height="9.75" fill="#dde1ec"></rect> <rect x="430.12" y="224.08" width="209.17" height="9.75" fill="#dde1ec"></rect> <rect x="430.12" y="254.42" width="209.17" height="9.75" fill="#dde1ec"></rect> <rect x="430.12" y="284.77" width="209.17" height="9.75" fill="#dde1ec"></rect> <rect x="430.12" y="315.12" width="209.17" height="9.75" fill="#dde1ec"></rect> <rect x="430.12" y="345.46" width="209.17" height="9.75" fill="#dde1ec"></rect> <rect x="430.12" y="375.81" width="209.17" height="9.75" fill="#dde1ec"></rect> <rect x="430.12" y="406.16" width="209.17" height="9.75" fill="#dde1ec"></rect> <rect x="160.25" y="59.34" width="208.09" height="277.45" fill="#5850ec"></rect> <path d="M1065.12,82.63A27.64,27.64,0,0,0,1037.48,55H240.89a27.64,27.64,0,0,0-27.64,27.64v31.43h851.87Z" transform="translate(-53 -50.93)" fill="#e8eaf1"></path> <circle cx="193.85" cy="33.33" r="10.84" fill="#ff5252"></circle> <circle cx="229.61" cy="33.33" r="10.84" fill="#ff0"></circle> <circle cx="265.38" cy="33.33" r="10.84" fill="#69f0ae"></circle> <path d="M378,249a60.41,60.41,0,0,1-13.52,38.17c-.33.41-.66.81-1,1.21h0a1.64,1.64,0,0,0-.18.22,61,61,0,0,1-10.47,9.59l-.5.37c-.69.49-1.39,1-2.1,1.42l-.49.31-1.05.65a5.33,5.33,0,0,1-.48.27,3.21,3.21,0,0,1-.41.25c-1.54.9-3.13,1.73-4.76,2.49-.18.1-.38.18-.57.27s-.41.2-.63.28-.65.29-1,.42a59.06,59.06,0,0,1-10.83,3.4,60.5,60.5,0,0,1-10.75,1.31h-.55l-1.43,0A60.59,60.59,0,0,1,303.11,308l-.87-.21q-3.06-.8-6-1.86c-1.19-.44-2.37-.92-3.52-1.43-.29-.13-.57-.25-.86-.39l-.87-.39-.76-.39h0l-.8-.41-.89-.47-1-.57-.16-.09a60.93,60.93,0,0,1-11.78-8.71c-.3-.29-.61-.57-.9-.88s-.63-.62-.93-.94A60.69,60.69,0,1,1,378,249Z" transform="translate(-53 -50.93)" fill="#fff"></path> <path d="M360,270.62a17.33,17.33,0,0,1-.59,2.67,2.47,2.47,0,0,1-.13.4c0-.38-.07-.78-.09-1.21h0c-.3-4.45-.56-11.48.34-15.64.85-3.91-5.34-2.72-9.47.78h0a10.89,10.89,0,0,0-2.88,3.61c-.09-.12-.18-.23-.28-.35a10.9,10.9,0,0,0-8.2-4.05,27.91,27.91,0,0,1-17.43-7.64c-.2-.81-.35-1.61-.47-2.42,0-.25-.08-.51-.11-.76a15.07,15.07,0,0,0,4.63-7.81s0-.08,0-.12a15.1,15.1,0,0,0,.38-3.32c0-.46,0-.9-.05-1.34,0-.16,0-.33-.05-.49s0-.38-.08-.56a6.61,6.61,0,0,0,.73-.38,8.38,8.38,0,0,0,3.24-3.67l0-.08a13.83,13.83,0,0,0,.64-1.66,0,0,0,0,0,0,0,20.07,20.07,0,0,0,.83-5c0-.21,0-.42,0-.65,0-1.31-.26-2.95-1.54-3.22-1.69-.36-2.92,2.19-4.63,1.92a2.6,2.6,0,0,1-1.34-.81c-1.16-1.15-1.94-2.63-3.1-3.79a10.6,10.6,0,0,0-6.62-2.81,25,25,0,0,0-7.28.67,12.26,12.26,0,0,0-3.76,1.28,4.48,4.48,0,0,0-2.25,3.09,4.41,4.41,0,0,0,0,.85c0,.42,0,.86,0,1.29a4.8,4.8,0,0,1-.07.55,4.57,4.57,0,0,1-2.31,2.76c-1.06.63-2.24,1.07-3.25,1.79a8.51,8.51,0,0,0-3.13,5,13.22,13.22,0,0,0-.35,3.39,22.43,22.43,0,0,0,.17,3.08,33.06,33.06,0,0,0,2.86,9.92,15.84,15.84,0,0,0,1.42,2.5s0,0,0,0c.14.2.28.38.43.55a15.09,15.09,0,0,0,3.23,2.73l.69.47a2,2,0,0,0,1.71.44,1.82,1.82,0,0,0,.52-.3,4,4,0,0,0,1.25-3.15v-.16a1,1,0,0,1,0,.17,1.64,1.64,0,0,1,0,.23l.1,0a14.8,14.8,0,0,1,0,1.53,5.59,5.59,0,0,1,0,.62c0,.12,0,.24,0,.35l-.16.14h0c-.33.28-.93.77-1.76,1.42h0a52.89,52.89,0,0,1-11.24,6.69,16,16,0,0,0-9.21,10.23,35.89,35.89,0,0,1-4.37,9.47c-.48.7-1.8,6-3.34,11.44.3.33.62.64.93.94s.6.59.9.88a60.93,60.93,0,0,0,11.78,8.71l.16.09,1,.57.89.47c0-.75-.1-1.44-.15-2.09,0-.39-.06-.76-.1-1.12.23-.38.43-.72.62-1,0,.29.07.61.1,1h0c.11,1,.22,2.23.34,3.64q0,.67.1,1.4c0-.49-.05-1-.09-1.4l.76.39.87.39c.28.14.56.26.86.39,1.15.51,2.33,1,3.52,1.43q2.91,1.07,6,1.86l.87.21a60.59,60.59,0,0,0,14.19,1.67l1.43,0h.55A60.5,60.5,0,0,0,330,308.34a59.06,59.06,0,0,0,10.83-3.4q.49-.2,1-.42c.22-.09.42-.18.63-.28s.39-.17.57-.27c-1-3.51-1.54-5.76-1.54-5.76l1.32-3.39.16-.41.4-1.05v0c0,.13.06.26.11.39v0a15.93,15.93,0,0,0,4.7,7.49,5.33,5.33,0,0,0,.48-.27l1.05-.65.49-.31c.72-.47,1.41-.93,2.1-1.42l.5-.37a61,61,0,0,0,10.47-9.59l.18-.22h0c.34-.4.67-.8,1-1.21Zm-8.72,4.56a1.1,1.1,0,0,1,.09.15l-.08-.07Z" transform="translate(-53 -50.93)" fill="url(#d2adb5db-d5c7-49ab-bfdd-50ec6aa449ae)"></path> <path d="M351.44,273.31v.08l.07.07C351.49,273.4,351.46,273.36,351.44,273.31Zm-61.16,30c-.1-1.4-.2-2.66-.29-3.74-.07-.76-.14-1.44-.2-2a.18.18,0,0,1,0-.08c0-.14,0-.27,0-.4a23,23,0,0,0-2.36,4.7l.16.09,1,.57.89.47.8.41q0,.67.1,1.4C290.33,304.25,290.31,303.79,290.28,303.34Zm13.31-57.17,0,0,0,0,.05.09h0a.23.23,0,0,1,0,.07A.39.39,0,0,0,303.59,246.17Z" transform="translate(-53 -50.93)" fill="url(#fd1a872a-c2fb-4cd4-906a-a62e176bbce4)"></path> <path d="M321,235.93s-3.61,16.22,6.31,24.79S303.88,276,298,268.83,302.08,258,302.08,258s5-4.06,0-16.22Z" transform="translate(-53 -50.93)" fill="#e0a17e"></path> <path d="M330,308.34a60.5,60.5,0,0,1-10.75,1.31h-.55l-1.43,0A60.59,60.59,0,0,1,303.11,308l-.87-.21q-3.06-.8-6-1.86l-1-15,4.34-20.77,2.55-12.24.72-3.42.73,1,.16.22.2.26,8,10.65,8.19-8.82.42-.46,2.25-2.43.85-.91.4,3.09,1.44,11.11,2.67,20.49Z" transform="translate(-53 -50.93)" fill="#f0f1f5"></path> <path d="M352.32,298.56c-.69.49-1.39,1-2.1,1.42l-.49.31-1.05.65a5.33,5.33,0,0,1-.48.27,3.21,3.21,0,0,1-.41.25A16.08,16.08,0,0,1,343,294.4a11,11,0,0,1-.3-1.06,1,1,0,0,1,0-.17l-.57,1.5L340.38,299s.52,2.12,1.45,5.48q-.49.23-1,.42a59.06,59.06,0,0,1-10.83,3.4,60.5,60.5,0,0,1-10.75,1.31h-.55a99.44,99.44,0,0,1-.53-14l1.32-25.17.67-12.7.37-7s.13.14.38.37.42.4.72.67h0a27,27,0,0,0,16,6.61,10.62,10.62,0,0,1,8.32,4.42,19.59,19.59,0,0,1,3.39,8.65l.3,2.75s0,0,0,0l.08.78.11,1Z" transform="translate(-53 -50.93)" opacity="0.1"></path> <path d="M352.82,298.2l-.5.37c-.69.49-1.39,1-2.1,1.42l-.49.31-1.05.65a16.58,16.58,0,0,1-5.18-7.22v0a2.77,2.77,0,0,1-.12-.36c-.11-.35-.21-.72-.29-1.08l-.41,1.07-.56,1.45-1.28,3.34s.59,2.4,1.63,6.1c-.21.1-.41.2-.63.28s-.65.29-1,.42a59.06,59.06,0,0,1-10.83,3.4,60.5,60.5,0,0,1-10.75,1.31,99.49,99.49,0,0,1-.63-14.92l1.28-24.39.68-13,.33-6.13.06-1.27a2,2,0,0,0,.17.17,27.09,27.09,0,0,0,17,7.49,10.62,10.62,0,0,1,8.24,4.31,19.54,19.54,0,0,1,3.48,8.76l.53,4.93v.07h0l.11,1Z" transform="translate(-53 -50.93)" fill="#293158"></path> <path d="M304.2,253l0,.61-.16,2.32-.56,7.94s-.33,2.29-.57,7.45c-.35,6.8-.56,18.58.25,36.7l-.87-.21q-3.06-.8-6-1.86c-1.19-.44-2.37-.92-3.52-1.43-.29-.13-.57-.25-.86-.39-.29-4-.64-6.45-.64-6.45s-.27.34-.68,1c-.17.27-.38.6-.6,1l-.05.09h0q-.29.5-.62,1.14c-.25.5-.5,1-.74,1.63l-1-.57-.16-.09a60.93,60.93,0,0,1-11.78-8.71c1.61-5.67,3-11.37,3.5-12.1a34.76,34.76,0,0,0,4.25-9.27,15.71,15.71,0,0,1,9-10A36.5,36.5,0,0,0,304.18,253a0,0,0,0,1,0,0A0,0,0,0,0,304.2,253Z" transform="translate(-53 -50.93)" opacity="0.1"></path> <path d="M304.21,252.72a54.08,54.08,0,0,1-12.81,8.07,15.71,15.71,0,0,0-9,10,34.76,34.76,0,0,1-4.25,9.27c-.49.73-1.89,6.44-3.5,12.12.29.3.6.59.9.88a60.93,60.93,0,0,0,11.78,8.71l.16.09a22.88,22.88,0,0,1,2.2-4.24l.05-.08c.35-.52.57-.8.57-.8s.09.66.22,1.85.3,3,.46,5.1l.87.39c.28.14.56.26.86.39,1.15.51,2.33,1,3.52,1.43q2.91,1.07,6,1.86c-.82-17.94-.64-29.76-.31-36.73.26-5.62.61-8.11.61-8.11l1.27-7.3.36-2.06.17-1Z" transform="translate(-53 -50.93)" fill="#293158"></path> <path d="M310.64,251.71a14.81,14.81,0,0,1-6.49-1.49,29.17,29.17,0,0,0-2.07-7.53L321,236.84a36.22,36.22,0,0,0-.39,11A14.82,14.82,0,0,1,310.64,251.71Z" transform="translate(-53 -50.93)" opacity="0.1"></path> <circle cx="257.64" cy="185.01" r="14.87" fill="#e0a17e"></circle> <g opacity="0.1"><path d="M340.86,304.94c-1.17-4.11-1.82-6.81-1.82-6.81l2.25-5.86a12.65,12.65,0,0,0,.77,2.4l.05.13-1.28,3.34s.59,2.4,1.63,6.1c-.21.1-.41.2-.63.28S341.18,304.81,340.86,304.94Z" transform="translate(-53 -50.93)"></path></g> <path d="M358.41,276.94s-1.35-13.07,0-19.38-15.77.9-12.17,9.46a73.58,73.58,0,0,0,7.21,13.52Z" transform="translate(-53 -50.93)" fill="#e0a17e"></path> <path d="M363.29,288.6a61.05,61.05,0,0,1-10.47,9.59l-.5.37c-.69.49-1.39,1-2.1,1.42a42.26,42.26,0,0,1-4.3-6.13l-.13-.23,3.61-18.93.38.39c.16.15.37.35.62.55h0l.05,0c1.87,1.6,5.82,4,7.72-1.58a16.85,16.85,0,0,0,.69-3l.37,1.38h0l.22.81,4,15.09h0Z" transform="translate(-53 -50.93)" opacity="0.1"></path> <path d="M363.29,288.6a61,61,0,0,1-10.47,9.59l-.5.37c-.69.49-1.39,1-2.1,1.42l-.49.31a43.62,43.62,0,0,1-3.94-5.78l.13-.67,3.48-18.26s.18.21.49.5l.62.55c.26.22.56.46.89.68a0,0,0,0,1,0,0c2.07,1.42,5.22,2.49,6.82-2.5a16.89,16.89,0,0,0,.64-2.84l.46,1.7Z" transform="translate(-53 -50.93)" fill="#293158"></path> <path d="M363.29,288.6a61,61,0,0,1-10.47,9.59l-.5.37c-.69.49-1.39,1-2.1,1.42l-.49.31a43.62,43.62,0,0,1-3.94-5.78l.13-.67,3.48-18.26s.18.21.49.5l.62.55c.26.22.56.46.89.68a0,0,0,0,1,0,0c2.07,1.42,5.22,2.49,6.82-2.5a16.89,16.89,0,0,0,.64-2.84l.46,1.7Z" transform="translate(-53 -50.93)" opacity="0.02"></path> <path d="M289.79,297.58l-.05.08c0-.05,0-.11,0-.15.63-2.19,1.2-3.67,1.71-4.11,0,0,.67,4.45,1.27,11.12-.29-.13-.57-.25-.86-.39l-.87-.39c-.15-2.15-.31-3.9-.46-5.1s-.22-1.85-.22-1.85S290.14,297.06,289.79,297.58Z" transform="translate(-53 -50.93)" opacity="0.1"></path> <path d="M310.82,231.84a4.08,4.08,0,0,1,2.55-.46,2.91,2.91,0,0,1,1,.7c1.17,1.11,2.33,2.4,3.91,2.74a7.56,7.56,0,0,0,2.93-.13,14,14,0,0,0,4.82-1.47c3.35-2,4.42-6.33,4.63-10.22.07-1.31-.2-3-1.49-3.28-1.64-.35-2.84,2.14-4.5,1.87a2.51,2.51,0,0,1-1.3-.79c-1.13-1.13-1.9-2.58-3-3.72a10.31,10.31,0,0,0-6.44-2.76,24.08,24.08,0,0,0-7.09.67,11.65,11.65,0,0,0-3.65,1.25,4.39,4.39,0,0,0-2.2,3c-.11.87.12,1.77-.05,2.63a4.42,4.42,0,0,1-2.24,2.71c-1,.62-2.18,1-3.16,1.76a8.37,8.37,0,0,0-3.05,4.86,16.46,16.46,0,0,0-.16,5.83,32.28,32.28,0,0,0,2.78,9.72,13.77,13.77,0,0,0,1.81,3,16.81,16.81,0,0,0,3.65,3,2.22,2.22,0,0,0,1.83.55,1.59,1.59,0,0,0,.51-.3c1.28-1.07,1.4-3,1-4.63s-1.08-3.18-1.16-4.84a2.33,2.33,0,0,1,.26-1.35,6,6,0,0,1,.9-.91,5.44,5.44,0,0,0,1-1.69,16.53,16.53,0,0,0,1.55-4.86,6.55,6.55,0,0,0-.87-4A5.17,5.17,0,0,0,310.82,231.84Z" transform="translate(-53 -50.93)" opacity="0.1"></path> <path d="M310.82,231.32a4.08,4.08,0,0,1,2.55-.46,2.91,2.91,0,0,1,1,.7c1.17,1.11,2.33,2.4,3.91,2.74a7.56,7.56,0,0,0,2.93-.13,14,14,0,0,0,4.82-1.47c3.35-2,4.42-6.33,4.63-10.22.07-1.31-.2-3-1.49-3.28-1.64-.35-2.84,2.14-4.5,1.87a2.51,2.51,0,0,1-1.3-.79c-1.13-1.13-1.9-2.58-3-3.72a10.31,10.31,0,0,0-6.44-2.76,24.08,24.08,0,0,0-7.09.67,11.65,11.65,0,0,0-3.65,1.25,4.39,4.39,0,0,0-2.2,3c-.11.87.12,1.77-.05,2.63a4.42,4.42,0,0,1-2.24,2.71c-1,.62-2.18,1-3.16,1.76a8.37,8.37,0,0,0-3.05,4.86,16.46,16.46,0,0,0-.16,5.83,32.28,32.28,0,0,0,2.78,9.72,13.77,13.77,0,0,0,1.81,3,16.81,16.81,0,0,0,3.65,3,2.22,2.22,0,0,0,1.83.55,1.59,1.59,0,0,0,.51-.3c1.28-1.07,1.4-3,1-4.63s-1.08-3.18-1.16-4.84a2.33,2.33,0,0,1,.26-1.35,6,6,0,0,1,.9-.91,5.44,5.44,0,0,0,1-1.69,16.53,16.53,0,0,0,1.55-4.86,6.55,6.55,0,0,0-.87-4A5.17,5.17,0,0,0,310.82,231.32Z" transform="translate(-53 -50.93)" fill="#463e3b"></path> <ellipse cx="176" cy="557.1" rx="25.19" ry="9.25" fill="#cd9494"></ellipse> <ellipse cx="176" cy="556.07" rx="12.85" ry="4.72" opacity="0.05"></ellipse> <path d="M203.8,606s27.76,15.42,50.38,2.06c0,0-3.08,53.47-18.51,58.61H215.12S197.64,630.64,203.8,606Z" transform="translate(-53 -50.93)" fill="#ff7361"></path> <path d="M250.75,508c7.07,25.83-23.07,99.41-23.07,99.41s-63.39-48-70.46-73.82A48.48,48.48,0,1,1,250.75,508Z" transform="translate(-53 -50.93)" fill="#5850ec"></path> <path d="M250.75,508c7.07,25.83-23.07,99.41-23.07,99.41s-63.39-48-70.46-73.82A48.48,48.48,0,1,1,250.75,508Z" transform="translate(-53 -50.93)" opacity="0.1"></path> <path d="M191.45,474.17s39.46,59.57,36.49,133.37" transform="translate(-53 -50.93)" fill="none" stroke="#535461" stroke-miterlimit="10"></path> <path d="M260.82,555.2S225,535.73,229.57,607.59c0,0,29.85-1.07,40.44-25.42a21.12,21.12,0,0,0-8.66-26.67Z" transform="translate(-53 -50.93)" fill="#5850ec"></path> <path d="M261.41,555.32s-4.62,37.3-31.84,52.27" transform="translate(-53 -50.93)" fill="none" stroke="#535461" stroke-miterlimit="10"></path> <path d="M250.82,609.77c-1,12.3-5.31,50.5-18.22,54.81H214.17c.59,1.31.95,2.06.95,2.06h20.57C251.1,661.49,254.19,608,254.19,608A37.44,37.44,0,0,1,250.82,609.77Z" transform="translate(-53 -50.93)" opacity="0.05"></path> <rect x="430.12" y="472.81" width="209.17" height="121.39" fill="#dde1ec"></rect> <path d="M1035.83,284.14a8.76,8.76,0,0,0,0-1.18c-.36-4.88-5-8.41-9.6-10.29s-9.74-2.8-13.81-5.65c-6.88-4.82-9-13.83-9.62-22.07-.19-2.56-.27-5.12-.28-7.69s.16-5.41.4-8.11c.4-4.48,1.06-9.12,1-13.61a26.15,26.15,0,0,0-2.4-12.45c-4.08-8.06-13.24-12.18-21.72-15.69-3.26-1.35-6.6-2.72-10.14-2.87-3.74-.16-7.38,1.05-10.92,2.25-5.62,1.9-11.32,4.37-17.13,5.66-3.26.72-6.83.18-10,1.33-6.22,2.28-7.55,7.87-7.45,14,0,.39,0,.77,0,1.16-.62-.57-1.26-1.11-1.91-1.63l-.38-.3q-1-.81-2.13-1.53l-.36-.23a35.84,35.84,0,0,0-4.79-2.61l-.25-.11q-1.25-.55-2.54-1l-.16-.06a36.46,36.46,0,0,0-5.46-1.44h0a36.37,36.37,0,0,0-32.52,10.22l-6.87,7h0c-31.23-7-32.63,20.13-32.63,20.13l.23-.05-.08.19A96.44,96.44,0,0,0,837,247.67L595.83,494l-24.38,75.56,13.69-4.88,62.54-22.34L871.62,313.58c16.76,11.91,34.18,27.87,34.18,27.87S895,347.16,899,373.32c4.89,32-2.46,77.55-2.46,77.55v29.46l1.38-.38c-.17,1-.35,2-.52,3.15a168.08,168.08,0,0,0-.86,44.72c3.7,31.26,18.48,73.94,18.48,73.94s5.54,15,6.78,27.05a25.22,25.22,0,0,0,1.58,6.95,55.26,55.26,0,0,1,4.32,20.87c0,13.08,1.71,32.71,9.5,52.74,6.25,16.08,8.56,25.5,9.25,31-4.43,5.86-10.22,12.25-14.79,12.89-8.62,1.2-25.26,2.4-14.17,16.83a10.21,10.21,0,0,0,1.29,1.37c-8.42,1.36-17.68,4.36-8.68,16.06,11.09,14.43,76.39,7.82,76.39,7.82s.42-8,0-16.82c4.58-.32,7.41-.61,7.41-.61s1.85-34.87-6.16-39.08a16.9,16.9,0,0,0-4.32,1.06c-.86-4.82-1.52-10.81-1.93-15.25l3.17-.24V672.1s-3.7-32.46-17.25-46.89a29.15,29.15,0,0,1-4.43-6c.1-4.17.11-10,.06-16.73a13.89,13.89,0,0,1,1.91-2.52s7.39-9,32.65-66.13c14.9-33.69,11.58-53.14,6.35-63.44-1-3.66-3.6-14.11-7-33.35-4.31-24.65.62-48.7.62-48.7s16-41.48,23.41-58.31a27.39,27.39,0,0,0,2.08-12.85c1.93-1.41,2.86-3.76,3.69-6l7.59-20.25c.82-2.19,1.66-4.47,1.48-6.8ZM932.75,247.68a30.8,30.8,0,0,0,2.75,3.66h0s-1.13-1.18-3-3.08C932.59,248.07,932.67,247.87,932.75,247.68Zm-37.32,41.57,20.89-21.34c7.22,6.6,14.95,13.34,21.41,18.89a61.84,61.84,0,0,1-2.44,8.37c-2.8,1.39-6.5,1.84-11-.61C917.16,290.71,904,289.57,895.43,289.25Z" transform="translate(-53 -50.93)" fill="url(#636ac3f3-8a9d-4cd8-9529-d45e35aeb867)"></path> <path d="M878.62,210.5S863.13,219.44,867.9,232c0,0,16.09,4.77,22.64,16.69s57.21,54.82,57.21,54.82L984.69,285s-12.85-17.16-53.5-29.14c0,0-24-25.69-32.31-30.45S878.62,210.5,878.62,210.5Z" transform="translate(-53 -50.93)" fill="#febdd5"></path> <path d="M878.62,210.5S863.13,219.44,867.9,232c0,0,16.09,4.77,22.64,16.69s57.21,54.82,57.21,54.82L984.69,285s-12.85-17.16-53.5-29.14c0,0-24-25.69-32.31-30.45S878.62,210.5,878.62,210.5Z" transform="translate(-53 -50.93)" opacity="0.05"></path> <path d="M602.62,496.37,871.4,215a34.68,34.68,0,0,1,49-1.12h0a34.68,34.68,0,0,1,1.12,49L652.78,544.29l-73.74,27Z" transform="translate(-53 -50.93)" fill="#5850ec"></path> <path d="M905.75,208.89h0a34.68,34.68,0,0,1,1.12,49L638.1,539.26l-55.36,20.25L579,571.27l73.74-27L921.55,263a34.68,34.68,0,0,0-1.12-49h0a34.52,34.52,0,0,0-20.31-9.41A34.82,34.82,0,0,1,905.75,208.89Z" transform="translate(-53 -50.93)" fill="#fff" opacity="0.1"></path> <polygon points="549.62 445.45 599.78 493.36 539.28 515.5 526.04 520.34 534.43 493.71 549.62 445.45" fill="#efc8c4"></polygon> <path d="M587.43,544.63a14.75,14.75,0,0,1,5.16,2.07c1.82,1.31,3.14,3.58,2.64,5.76a13.82,13.82,0,0,1-2.08,3.77,11.77,11.77,0,0,0-.87,10.2L579,571.27Z" transform="translate(-53 -50.93)" fill="#727a9c"></path> <path d="M975.16,718.8s1.79,26.22,4.77,29.2-26.82,4.77-26.82,4.77l4.17-34Z" transform="translate(-53 -50.93)" fill="#ffb9b9"></path> <path d="M975.16,718.8s1.79,26.22,4.77,29.2-26.82,4.77-26.82,4.77l4.17-34Z" transform="translate(-53 -50.93)" opacity="0.05"></path> <path d="M981.71,739.06s-4.17,0-19.07,7.75c0,0-10.13-1.79-14.3-16.09,0,0-12.51,21.45-20.86,22.64s-24.43,2.38-13.71,16.69,73.89,7.75,73.89,7.75S989.46,743.23,981.71,739.06Z" transform="translate(-53 -50.93)" fill="#cbcdda"></path> <path d="M981.71,739.06s-4.17,0-19.07,7.75c0,0-10.13-1.79-14.3-16.09,0,0-12.51,21.45-20.86,22.64s-24.43,2.38-13.71,16.69,73.89,7.75,73.89,7.75S989.46,743.23,981.71,739.06Z" transform="translate(-53 -50.93)" opacity="0.05"></path> <path d="M972.77,759.32l-4.17,11.32-27.41-7.75,1.56-11.24.38-2.71,1-7.5s23.24-6,23.24-3a46.23,46.23,0,0,0,1.53,7.26c1,3.66,2.15,7.84,3,10.59C972.42,758.13,972.77,759.32,972.77,759.32Z" transform="translate(-53 -50.93)" fill="#ffb9b9"></path> <path d="M957.82,603.91c-1.88,3.18-4,9.14-.05,16.58a28.87,28.87,0,0,0,4.28,5.94c13.11,14.3,16.69,46.48,16.69,46.48v51.84l-8.94.69-14.3,1.1L934,638.36s-12.51-64.36-6.55-75.68,11.32-82.23,11.32-82.23l4.95-5.12,11.74-12.16h34a22.84,22.84,0,0,1,6.61,7.39c5.7,9.44,10.82,29.07-4.82,65.31-24.43,56.61-31.58,65.55-31.58,65.55A13.73,13.73,0,0,0,957.82,603.91Z" transform="translate(-53 -50.93)" fill="#4c4c78"></path> <path d="M957.82,603.91c-1.88,3.18-4,9.14-.05,16.58a28.87,28.87,0,0,0,4.28,5.94c13.11,14.3,16.69,46.48,16.69,46.48v51.84l-8.94.69-14.3,1.1L934,638.36s-12.51-64.36-6.55-75.68,11.32-82.23,11.32-82.23l4.95-5.12,11.74-12.16h34a22.84,22.84,0,0,1,6.61,7.39c5.7,9.44,10.82,29.07-4.82,65.31-24.43,56.61-31.58,65.55-31.58,65.55A13.73,13.73,0,0,0,957.82,603.91Z" transform="translate(-53 -50.93)" opacity="0.05"></path> <path d="M968.94,745.72l-25.81,3.22,1-7.5s23.24-6,23.24-3A46.23,46.23,0,0,0,968.94,745.72Z" transform="translate(-53 -50.93)" opacity="0.1"></path> <path d="M956.09,527.52s1.48,46.91,1.73,76.39c.05,6.67,0,12.45-.05,16.58a36.74,36.74,0,0,1-.48,6.54c-1.79,5.36,1.79,17.28,1.79,17.28,14.9,39.33,10.73,73.89,10.73,73.89v26.22L941.19,748s4.77-3.58-8.34-38.14c-7.53-19.86-9.16-39.31-9.19-52.28a55.94,55.94,0,0,0-4.18-20.68A25.52,25.52,0,0,1,918,630c-1.19-11.92-6.55-26.82-6.55-26.82s-14.3-42.31-17.88-73.3a170.67,170.67,0,0,1,.83-44.33,110.63,110.63,0,0,1,2.15-11.09s58.25-36.66,80.25-11.32a28.69,28.69,0,0,1,5,8.34,55.06,55.06,0,0,1,2.81,9.28C991.37,514.42,956.09,527.52,956.09,527.52Z" transform="translate(-53 -50.93)" fill="#4c4c78"></path> <circle cx="901.3" cy="185.2" r="30.39" fill="#ffb9b9"></circle> <path d="M937.62,255.19s12.51,35.16,0,53.63,39.92,12.51,39.92,12.51l20.26-24.43s-23.84,0-25-41.71S937.62,255.19,937.62,255.19Z" transform="translate(-53 -50.93)" fill="#ffb9b9"></path> <path d="M997.8,477.46a78.2,78.2,0,0,1-13.28,3.33c-7.42,1.14-16.17,1.19-21.29-3.33-3-2.68-10.51-3-19.49-2.15-18.91,1.81-44.51,8.87-49.41,10.26a110.63,110.63,0,0,1,2.15-11.09s58.25-36.66,80.25-11.32h12.71a22.84,22.84,0,0,1,6.61,7.39C997.16,475.29,997.8,477.46,997.8,477.46Z" transform="translate(-53 -50.93)" opacity="0.1"></path> <path d="M972.77,759.32l-4.17,11.32-27.41-7.75,1.56-11.24c4.71,10.42,12.74,11.83,12.74,11.83,8.66-4.5,13.7-6.39,16.4-7.18C972.42,758.13,972.77,759.32,972.77,759.32Z" transform="translate(-53 -50.93)" opacity="0.1"></path> <path d="M974.56,756.34s-4.17,0-19.07,7.75c0,0-10.13-1.79-14.3-16.09,0,0-12.51,21.45-20.86,22.64S895.9,773,906.63,787.33s73.89,7.75,73.89,7.75S982.31,760.51,974.56,756.34Z" transform="translate(-53 -50.93)" fill="#cbcdda"></path> <path d="M833.19,241.95s1.35-26.89,31.56-19.95L857,236.53Z" transform="translate(-53 -50.93)" fill="#ffb9b9"></path> <path d="M974,304.06s-29.2-4.77-36.95-10.73c0,0-6,11.32-16.69,5.36s-35.75-5.36-35.75-5.36-20.86-11.32-25-36.95c0,0-4.17-15.49-3-19.66,0,0-15.49-16.09-23.24,5.36,3,14.9,5.36,13.71,4.77,25.62l9.53,26.22s.6,8.94,17.88,20.86,36.95,30.39,36.95,30.39-10.43,5.66-6.55,31.58c4.73,31.68-2.38,76.87-2.38,76.87v29.2s59.59-17.28,69.72-8.34,34.56,0,34.56,0-3-10.13-7.15-34.56.6-48.27.6-48.27,15.49-41.12,22.64-57.8-5.36-39.33-17.88-47.67-17.17-3.33-17.17-3.33S1000.78,298.7,974,304.06Z" transform="translate(-53 -50.93)" fill="#febdd5"></path> <path d="M921.43,228.69a10.54,10.54,0,0,0,.62,3.64,11.48,11.48,0,0,0,2.79,3.47c4.46,4.36,8.38,9.54,9.95,15.58,1.06,4.09,1,8.37.94,12.6-.26,17.11-1,35.58-11.54,49-3.88,4.95-8.88,8.88-13.57,13.07A4.09,4.09,0,0,0,909.2,328a3.57,3.57,0,0,0,.44,2.36c2.18,4.53,6.77,7.31,11.12,9.83,2,1.15,4.08,2.34,6.38,2.41,5.42.18,9-5.55,13.93-7.8,7.18-3.28,15.23,1.28,22.36,4.68a78.79,78.79,0,0,0,16.14,5.63c7.63,1.76,16.25,2.19,22.67-2.28a17.31,17.31,0,0,0,7.06-11.05c.52-2.91.44-6.29,2.64-8.26,1.14-1,2.69-1.44,3.91-2.34,1.91-1.4,2.81-3.75,3.62-6l7.34-20.07a16.49,16.49,0,0,0,1.43-6.74c-.35-4.83-4.81-8.34-9.28-10.2s-9.42-2.77-13.36-5.6c-6.65-4.78-8.71-13.71-9.31-21.88a105.34,105.34,0,0,1,.12-16.85c.73-8.31,2.35-17.17-1.35-24.65-3.95-8-12.81-12.07-21-15.55-3.15-1.34-6.39-2.69-9.81-2.84-3.62-.16-7.14,1-10.56,2.23-5.44,1.89-11,4.33-16.57,5.61-3.15.72-6.61.18-9.65,1.32C916.3,204.16,921.43,219.7,921.43,228.69Z" transform="translate(-53 -50.93)" opacity="0.1"></path> <path d="M921.43,227.5a10.54,10.54,0,0,0,.62,3.64,11.48,11.48,0,0,0,2.79,3.47c4.46,4.36,8.38,9.54,9.95,15.58,1.06,4.09,1,8.37.94,12.6-.26,17.11-1,35.58-11.54,49-3.88,4.95-8.88,8.88-13.57,13.07a4.09,4.09,0,0,0-1.43,1.91,3.57,3.57,0,0,0,.44,2.36c2.18,4.53,6.77,7.31,11.12,9.83,2,1.15,4.08,2.34,6.38,2.41,5.42.18,9-5.55,13.93-7.8,7.18-3.28,15.23,1.28,22.36,4.68a78.79,78.79,0,0,0,16.14,5.63c7.63,1.76,16.25,2.19,22.67-2.28a17.31,17.31,0,0,0,7.06-11.05c.52-2.91.44-6.29,2.64-8.26,1.14-1,2.69-1.44,3.91-2.34,1.91-1.4,2.81-3.75,3.62-6l7.34-20.07a16.49,16.49,0,0,0,1.43-6.74c-.35-4.83-4.81-8.34-9.28-10.2s-9.42-2.77-13.36-5.6c-6.65-4.78-8.71-13.71-9.31-21.88a105.34,105.34,0,0,1,.12-16.85c.73-8.31,2.35-17.17-1.35-24.65-3.95-8-12.81-12.07-21-15.55-3.15-1.34-6.39-2.69-9.81-2.84-3.62-.16-7.14,1-10.56,2.23-5.44,1.89-11,4.33-16.57,5.61-3.15.72-6.61.18-9.65,1.32C916.3,203,921.43,218.51,921.43,227.5Z" transform="translate(-53 -50.93)" fill="#b96b6b"></path> <path d="M897.41,390.13S902.16,389,926,385.4s65.55,4.77,65.55,4.77" transform="translate(-53 -50.93)" opacity="0.05"></path></svg> <span class="text-gray-600">There are no tasks to show here</span></div></div>
                                                @endif
                                    </div>
                                        <!---->
                                    </div>
                                </section>
                            </div>
                            <div class="tab_pane_attachments-{{ $task->id }} tab-pane fade" id="attachments{{ $task->id }}" role="tabpanel" aria-labelledby="attachments-tab{{ $task->id }}">
                                <section class="p-3">
                                    <div class="flex flex-col">
                                        <ul class="border border-gray-200 rounded-md mt-4 attachment-preview-{{ $task->task_id }}">
                                        @php
                                        // $comments = Comment::where('pipeline_process_task_id',$task->task_id)->get(); 
                                        @endphp
                                        @if(count($task->parent->comments) > 0)
                                        @foreach ($task->parent->comments as $key => $comment) 
                                            @php
                                            $attachments = Attachment::where('comment_id',$comment['id'])->get();
                                                @endphp
                                                @if (count($attachments) > 0)
                                                    @foreach ($attachments->toArray() as $attachment)
                                                    <li class="pl-3 pr-2 py-3 flex items-center justify-between text-sm leading-5">
                                                        <div class="w-0 flex-1 flex items-center">
                                                            <svg fill="currentColor" viewBox="0 0 20 20"
                                                                class="flex-shrink-0 h-5 w-5 text-gray-400">
                                                                <path fill-rule="evenodd"
                                                                    d="M8 4a3 3 0 00-3 3v4a5 5 0 0010 0V7a1 1 0 112 0v4a7 7 0 11-14 0V7a5 5 0 0110 0v4a3 3 0 11-6 0V7a1 1 0 012 0v4a1 1 0 102 0V7a3 3 0 00-3-3z"
                                                                    clip-rule="evenodd"></path>
                                                            </svg> 
                                                            <a href="{{ url('pipeline-process/task/download') }}/{{ $attachment['filename'] }}"
                                                                class="hover:underline"><span class="ml-2 truncate">{{ $attachment['filename'] }}</span>
                                                            </a>
                                                        </div>
                                                        <div class="ml-4 flex-shrink-0">
                                                            <a href="{{ url('pipeline-process/task/download') }}/{{ $attachment['filename'] }}"
                                                                title="Download" class="btn btn-flat btn-xs">
                                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                                    viewBox="0 0 20 20" class="w-3 h-3 fill-current">
                                                                    <path d="M13 8V2H7v6H2l8 8 8-8h-5zM0 18h20v2H0v-2z"></path>
                                                                </svg>
                                                            </a>
                                                        </div>
                                                    </li>
                                                    @endforeach
                                                    @endif
                                                
                                            @endforeach
                                            @else
                                            <div class="flex flex-col items-center"><div class="flex flex-col items-center"><svg data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="300" height="300" viewBox="0 0 1094 798.15"><defs><linearGradient id="b86fccb4-da21-4a09-8d3b-ff994f3aa5aa" x1="639.03" y1="672.43" x2="639.03" y2="50.93" gradientUnits="userSpaceOnUse"><stop offset="0" stop-color="gray" stop-opacity="0.25"></stop> <stop offset="0.54" stop-color="gray" stop-opacity="0.12"></stop> <stop offset="1" stop-color="gray" stop-opacity="0.1"></stop></linearGradient> <linearGradient id="d2adb5db-d5c7-49ab-bfdd-50ec6aa449ae" x1="319.12" y1="309.68" x2="319.12" y2="212.15" xlink:href="#b86fccb4-da21-4a09-8d3b-ff994f3aa5aa"></linearGradient> <linearGradient id="fd1a872a-c2fb-4cd4-906a-a62e176bbce4" x1="332.56" y1="304.74" x2="332.56" y2="246.12" gradientTransform="matrix(-1, 0, 0, 1, 652, 0)" xlink:href="#b86fccb4-da21-4a09-8d3b-ff994f3aa5aa"></linearGradient> <linearGradient id="636ac3f3-8a9d-4cd8-9529-d45e35aeb867" x1="803.65" y1="796.85" x2="803.65" y2="184.5" xlink:href="#b86fccb4-da21-4a09-8d3b-ff994f3aa5aa"></linearGradient></defs> <title>wall post</title> <g opacity="0.1"><path d="M119.37,170.3c10.2,17.75,4.55,40.79-6.53,58S86,259.17,73.72,275.59C49.31,308.35,43,360.08,73.78,387c6.63,5.8,14.83,10.39,18.87,18.21C98.23,416,94.1,429,89.89,440.43l-.35.94C73.63,484.5,97.07,532.3,141,545.83c24.31,7.49,46.25,19.63,62.49,39.26,14.19,17.16,22.39,38.33,31.75,58.53C252.16,680.11,274,715,304.18,741.57s69.56,44.18,109.76,42.73c91.82-3.31,159.43-100,251.16-105.09,6.61-.37,13.65-.12,19.18,3.52,7.8,5.14,10.19,15.23,13.47,24,17.37,46.43,69.67,67.83,116.5,84.09l88,30.58c47.8,16.6,98,33.45,148,25.76s98.82-48.75,96.65-99.31c-1.78-41.53-36.2-78.08-31.69-119.41,3.12-28.56,24.51-52.55,28.09-81.06,3.73-29.67-12.49-58.16-31.16-81.52-53.72-67.24-130.28-111.56-205-154.31-52.63-30.12-105.53-60.36-162.19-82-139.68-53.21-293.2-50.92-442-65.44-25.59-2.5-52.34-5.44-75.79-16.75-18-8.68-32.07-28.75-51.82-33-15.05-3.24-37.31,3.45-50.45,10.76C100.94,138.54,107.54,149.71,119.37,170.3Z" transform="translate(-53 -50.93)" fill="#5850ec"></path></g> <path d="M1040.87,50.93H237.18A28,28,0,0,0,209.3,79.08V644.28a28,28,0,0,0,27.88,28.15h803.69a28,28,0,0,0,27.88-28.15V79.08A28,28,0,0,0,1040.87,50.93Z" transform="translate(-53 -50.93)" fill="url(#b86fccb4-da21-4a09-8d3b-ff994f3aa5aa)"></path> <path d="M1065.12,110.81V637.54a27.64,27.64,0,0,1-27.64,27.64H240.89a27.64,27.64,0,0,1-27.64-27.64V110.81Z" transform="translate(-53 -50.93)" fill="#f6f7f9"></path> <path d="M421.34,382.3V665.17H240.89c-15.26,0-27.64-12.62-27.64-28.18V382.3Z" transform="translate(-53 -50.93)" fill="#dde1ec"></path> <rect x="430.12" y="124.37" width="346.82" height="9.75" fill="#dde1ec"></rect> <rect x="430.12" y="193.73" width="209.17" height="9.75" fill="#dde1ec"></rect> <rect x="685.9" y="193.73" width="70.45" height="9.75" fill="#dde1ec"></rect> <g opacity="0.1"><rect x="224.19" y="437.04" width="70.45" height="9.75" fill="#5850ec"></rect></g> <rect x="685.9" y="284.77" width="70.45" height="9.75" fill="#dde1ec"></rect> <rect x="685.9" y="375.81" width="70.45" height="9.75" fill="#dde1ec"></rect> <rect x="685.9" y="224.08" width="70.45" height="9.75" fill="#dde1ec"></rect> <rect x="807.28" y="224.08" width="70.45" height="9.75" fill="#dde1ec"></rect> <rect x="685.9" y="254.42" width="70.45" height="9.75" fill="#dde1ec"></rect> <rect x="807.28" y="254.42" width="70.45" height="9.75" fill="#dde1ec"></rect> <rect x="807.28" y="310.78" width="70.45" height="9.75" fill="#dde1ec"></rect> <rect x="807.28" y="341.13" width="70.45" height="9.75" fill="#dde1ec"></rect> <rect x="807.28" y="406.16" width="70.45" height="9.75" fill="#dde1ec"></rect> <rect x="430.12" y="224.08" width="209.17" height="9.75" fill="#dde1ec"></rect> <rect x="430.12" y="254.42" width="209.17" height="9.75" fill="#dde1ec"></rect> <rect x="430.12" y="284.77" width="209.17" height="9.75" fill="#dde1ec"></rect> <rect x="430.12" y="315.12" width="209.17" height="9.75" fill="#dde1ec"></rect> <rect x="430.12" y="345.46" width="209.17" height="9.75" fill="#dde1ec"></rect> <rect x="430.12" y="375.81" width="209.17" height="9.75" fill="#dde1ec"></rect> <rect x="430.12" y="406.16" width="209.17" height="9.75" fill="#dde1ec"></rect> <rect x="160.25" y="59.34" width="208.09" height="277.45" fill="#5850ec"></rect> <path d="M1065.12,82.63A27.64,27.64,0,0,0,1037.48,55H240.89a27.64,27.64,0,0,0-27.64,27.64v31.43h851.87Z" transform="translate(-53 -50.93)" fill="#e8eaf1"></path> <circle cx="193.85" cy="33.33" r="10.84" fill="#ff5252"></circle> <circle cx="229.61" cy="33.33" r="10.84" fill="#ff0"></circle> <circle cx="265.38" cy="33.33" r="10.84" fill="#69f0ae"></circle> <path d="M378,249a60.41,60.41,0,0,1-13.52,38.17c-.33.41-.66.81-1,1.21h0a1.64,1.64,0,0,0-.18.22,61,61,0,0,1-10.47,9.59l-.5.37c-.69.49-1.39,1-2.1,1.42l-.49.31-1.05.65a5.33,5.33,0,0,1-.48.27,3.21,3.21,0,0,1-.41.25c-1.54.9-3.13,1.73-4.76,2.49-.18.1-.38.18-.57.27s-.41.2-.63.28-.65.29-1,.42a59.06,59.06,0,0,1-10.83,3.4,60.5,60.5,0,0,1-10.75,1.31h-.55l-1.43,0A60.59,60.59,0,0,1,303.11,308l-.87-.21q-3.06-.8-6-1.86c-1.19-.44-2.37-.92-3.52-1.43-.29-.13-.57-.25-.86-.39l-.87-.39-.76-.39h0l-.8-.41-.89-.47-1-.57-.16-.09a60.93,60.93,0,0,1-11.78-8.71c-.3-.29-.61-.57-.9-.88s-.63-.62-.93-.94A60.69,60.69,0,1,1,378,249Z" transform="translate(-53 -50.93)" fill="#fff"></path> <path d="M360,270.62a17.33,17.33,0,0,1-.59,2.67,2.47,2.47,0,0,1-.13.4c0-.38-.07-.78-.09-1.21h0c-.3-4.45-.56-11.48.34-15.64.85-3.91-5.34-2.72-9.47.78h0a10.89,10.89,0,0,0-2.88,3.61c-.09-.12-.18-.23-.28-.35a10.9,10.9,0,0,0-8.2-4.05,27.91,27.91,0,0,1-17.43-7.64c-.2-.81-.35-1.61-.47-2.42,0-.25-.08-.51-.11-.76a15.07,15.07,0,0,0,4.63-7.81s0-.08,0-.12a15.1,15.1,0,0,0,.38-3.32c0-.46,0-.9-.05-1.34,0-.16,0-.33-.05-.49s0-.38-.08-.56a6.61,6.61,0,0,0,.73-.38,8.38,8.38,0,0,0,3.24-3.67l0-.08a13.83,13.83,0,0,0,.64-1.66,0,0,0,0,0,0,0,20.07,20.07,0,0,0,.83-5c0-.21,0-.42,0-.65,0-1.31-.26-2.95-1.54-3.22-1.69-.36-2.92,2.19-4.63,1.92a2.6,2.6,0,0,1-1.34-.81c-1.16-1.15-1.94-2.63-3.1-3.79a10.6,10.6,0,0,0-6.62-2.81,25,25,0,0,0-7.28.67,12.26,12.26,0,0,0-3.76,1.28,4.48,4.48,0,0,0-2.25,3.09,4.41,4.41,0,0,0,0,.85c0,.42,0,.86,0,1.29a4.8,4.8,0,0,1-.07.55,4.57,4.57,0,0,1-2.31,2.76c-1.06.63-2.24,1.07-3.25,1.79a8.51,8.51,0,0,0-3.13,5,13.22,13.22,0,0,0-.35,3.39,22.43,22.43,0,0,0,.17,3.08,33.06,33.06,0,0,0,2.86,9.92,15.84,15.84,0,0,0,1.42,2.5s0,0,0,0c.14.2.28.38.43.55a15.09,15.09,0,0,0,3.23,2.73l.69.47a2,2,0,0,0,1.71.44,1.82,1.82,0,0,0,.52-.3,4,4,0,0,0,1.25-3.15v-.16a1,1,0,0,1,0,.17,1.64,1.64,0,0,1,0,.23l.1,0a14.8,14.8,0,0,1,0,1.53,5.59,5.59,0,0,1,0,.62c0,.12,0,.24,0,.35l-.16.14h0c-.33.28-.93.77-1.76,1.42h0a52.89,52.89,0,0,1-11.24,6.69,16,16,0,0,0-9.21,10.23,35.89,35.89,0,0,1-4.37,9.47c-.48.7-1.8,6-3.34,11.44.3.33.62.64.93.94s.6.59.9.88a60.93,60.93,0,0,0,11.78,8.71l.16.09,1,.57.89.47c0-.75-.1-1.44-.15-2.09,0-.39-.06-.76-.1-1.12.23-.38.43-.72.62-1,0,.29.07.61.1,1h0c.11,1,.22,2.23.34,3.64q0,.67.1,1.4c0-.49-.05-1-.09-1.4l.76.39.87.39c.28.14.56.26.86.39,1.15.51,2.33,1,3.52,1.43q2.91,1.07,6,1.86l.87.21a60.59,60.59,0,0,0,14.19,1.67l1.43,0h.55A60.5,60.5,0,0,0,330,308.34a59.06,59.06,0,0,0,10.83-3.4q.49-.2,1-.42c.22-.09.42-.18.63-.28s.39-.17.57-.27c-1-3.51-1.54-5.76-1.54-5.76l1.32-3.39.16-.41.4-1.05v0c0,.13.06.26.11.39v0a15.93,15.93,0,0,0,4.7,7.49,5.33,5.33,0,0,0,.48-.27l1.05-.65.49-.31c.72-.47,1.41-.93,2.1-1.42l.5-.37a61,61,0,0,0,10.47-9.59l.18-.22h0c.34-.4.67-.8,1-1.21Zm-8.72,4.56a1.1,1.1,0,0,1,.09.15l-.08-.07Z" transform="translate(-53 -50.93)" fill="url(#d2adb5db-d5c7-49ab-bfdd-50ec6aa449ae)"></path> <path d="M351.44,273.31v.08l.07.07C351.49,273.4,351.46,273.36,351.44,273.31Zm-61.16,30c-.1-1.4-.2-2.66-.29-3.74-.07-.76-.14-1.44-.2-2a.18.18,0,0,1,0-.08c0-.14,0-.27,0-.4a23,23,0,0,0-2.36,4.7l.16.09,1,.57.89.47.8.41q0,.67.1,1.4C290.33,304.25,290.31,303.79,290.28,303.34Zm13.31-57.17,0,0,0,0,.05.09h0a.23.23,0,0,1,0,.07A.39.39,0,0,0,303.59,246.17Z" transform="translate(-53 -50.93)" fill="url(#fd1a872a-c2fb-4cd4-906a-a62e176bbce4)"></path> <path d="M321,235.93s-3.61,16.22,6.31,24.79S303.88,276,298,268.83,302.08,258,302.08,258s5-4.06,0-16.22Z" transform="translate(-53 -50.93)" fill="#e0a17e"></path> <path d="M330,308.34a60.5,60.5,0,0,1-10.75,1.31h-.55l-1.43,0A60.59,60.59,0,0,1,303.11,308l-.87-.21q-3.06-.8-6-1.86l-1-15,4.34-20.77,2.55-12.24.72-3.42.73,1,.16.22.2.26,8,10.65,8.19-8.82.42-.46,2.25-2.43.85-.91.4,3.09,1.44,11.11,2.67,20.49Z" transform="translate(-53 -50.93)" fill="#f0f1f5"></path> <path d="M352.32,298.56c-.69.49-1.39,1-2.1,1.42l-.49.31-1.05.65a5.33,5.33,0,0,1-.48.27,3.21,3.21,0,0,1-.41.25A16.08,16.08,0,0,1,343,294.4a11,11,0,0,1-.3-1.06,1,1,0,0,1,0-.17l-.57,1.5L340.38,299s.52,2.12,1.45,5.48q-.49.23-1,.42a59.06,59.06,0,0,1-10.83,3.4,60.5,60.5,0,0,1-10.75,1.31h-.55a99.44,99.44,0,0,1-.53-14l1.32-25.17.67-12.7.37-7s.13.14.38.37.42.4.72.67h0a27,27,0,0,0,16,6.61,10.62,10.62,0,0,1,8.32,4.42,19.59,19.59,0,0,1,3.39,8.65l.3,2.75s0,0,0,0l.08.78.11,1Z" transform="translate(-53 -50.93)" opacity="0.1"></path> <path d="M352.82,298.2l-.5.37c-.69.49-1.39,1-2.1,1.42l-.49.31-1.05.65a16.58,16.58,0,0,1-5.18-7.22v0a2.77,2.77,0,0,1-.12-.36c-.11-.35-.21-.72-.29-1.08l-.41,1.07-.56,1.45-1.28,3.34s.59,2.4,1.63,6.1c-.21.1-.41.2-.63.28s-.65.29-1,.42a59.06,59.06,0,0,1-10.83,3.4,60.5,60.5,0,0,1-10.75,1.31,99.49,99.49,0,0,1-.63-14.92l1.28-24.39.68-13,.33-6.13.06-1.27a2,2,0,0,0,.17.17,27.09,27.09,0,0,0,17,7.49,10.62,10.62,0,0,1,8.24,4.31,19.54,19.54,0,0,1,3.48,8.76l.53,4.93v.07h0l.11,1Z" transform="translate(-53 -50.93)" fill="#293158"></path> <path d="M304.2,253l0,.61-.16,2.32-.56,7.94s-.33,2.29-.57,7.45c-.35,6.8-.56,18.58.25,36.7l-.87-.21q-3.06-.8-6-1.86c-1.19-.44-2.37-.92-3.52-1.43-.29-.13-.57-.25-.86-.39-.29-4-.64-6.45-.64-6.45s-.27.34-.68,1c-.17.27-.38.6-.6,1l-.05.09h0q-.29.5-.62,1.14c-.25.5-.5,1-.74,1.63l-1-.57-.16-.09a60.93,60.93,0,0,1-11.78-8.71c1.61-5.67,3-11.37,3.5-12.1a34.76,34.76,0,0,0,4.25-9.27,15.71,15.71,0,0,1,9-10A36.5,36.5,0,0,0,304.18,253a0,0,0,0,1,0,0A0,0,0,0,0,304.2,253Z" transform="translate(-53 -50.93)" opacity="0.1"></path> <path d="M304.21,252.72a54.08,54.08,0,0,1-12.81,8.07,15.71,15.71,0,0,0-9,10,34.76,34.76,0,0,1-4.25,9.27c-.49.73-1.89,6.44-3.5,12.12.29.3.6.59.9.88a60.93,60.93,0,0,0,11.78,8.71l.16.09a22.88,22.88,0,0,1,2.2-4.24l.05-.08c.35-.52.57-.8.57-.8s.09.66.22,1.85.3,3,.46,5.1l.87.39c.28.14.56.26.86.39,1.15.51,2.33,1,3.52,1.43q2.91,1.07,6,1.86c-.82-17.94-.64-29.76-.31-36.73.26-5.62.61-8.11.61-8.11l1.27-7.3.36-2.06.17-1Z" transform="translate(-53 -50.93)" fill="#293158"></path> <path d="M310.64,251.71a14.81,14.81,0,0,1-6.49-1.49,29.17,29.17,0,0,0-2.07-7.53L321,236.84a36.22,36.22,0,0,0-.39,11A14.82,14.82,0,0,1,310.64,251.71Z" transform="translate(-53 -50.93)" opacity="0.1"></path> <circle cx="257.64" cy="185.01" r="14.87" fill="#e0a17e"></circle> <g opacity="0.1"><path d="M340.86,304.94c-1.17-4.11-1.82-6.81-1.82-6.81l2.25-5.86a12.65,12.65,0,0,0,.77,2.4l.05.13-1.28,3.34s.59,2.4,1.63,6.1c-.21.1-.41.2-.63.28S341.18,304.81,340.86,304.94Z" transform="translate(-53 -50.93)"></path></g> <path d="M358.41,276.94s-1.35-13.07,0-19.38-15.77.9-12.17,9.46a73.58,73.58,0,0,0,7.21,13.52Z" transform="translate(-53 -50.93)" fill="#e0a17e"></path> <path d="M363.29,288.6a61.05,61.05,0,0,1-10.47,9.59l-.5.37c-.69.49-1.39,1-2.1,1.42a42.26,42.26,0,0,1-4.3-6.13l-.13-.23,3.61-18.93.38.39c.16.15.37.35.62.55h0l.05,0c1.87,1.6,5.82,4,7.72-1.58a16.85,16.85,0,0,0,.69-3l.37,1.38h0l.22.81,4,15.09h0Z" transform="translate(-53 -50.93)" opacity="0.1"></path> <path d="M363.29,288.6a61,61,0,0,1-10.47,9.59l-.5.37c-.69.49-1.39,1-2.1,1.42l-.49.31a43.62,43.62,0,0,1-3.94-5.78l.13-.67,3.48-18.26s.18.21.49.5l.62.55c.26.22.56.46.89.68a0,0,0,0,1,0,0c2.07,1.42,5.22,2.49,6.82-2.5a16.89,16.89,0,0,0,.64-2.84l.46,1.7Z" transform="translate(-53 -50.93)" fill="#293158"></path> <path d="M363.29,288.6a61,61,0,0,1-10.47,9.59l-.5.37c-.69.49-1.39,1-2.1,1.42l-.49.31a43.62,43.62,0,0,1-3.94-5.78l.13-.67,3.48-18.26s.18.21.49.5l.62.55c.26.22.56.46.89.68a0,0,0,0,1,0,0c2.07,1.42,5.22,2.49,6.82-2.5a16.89,16.89,0,0,0,.64-2.84l.46,1.7Z" transform="translate(-53 -50.93)" opacity="0.02"></path> <path d="M289.79,297.58l-.05.08c0-.05,0-.11,0-.15.63-2.19,1.2-3.67,1.71-4.11,0,0,.67,4.45,1.27,11.12-.29-.13-.57-.25-.86-.39l-.87-.39c-.15-2.15-.31-3.9-.46-5.1s-.22-1.85-.22-1.85S290.14,297.06,289.79,297.58Z" transform="translate(-53 -50.93)" opacity="0.1"></path> <path d="M310.82,231.84a4.08,4.08,0,0,1,2.55-.46,2.91,2.91,0,0,1,1,.7c1.17,1.11,2.33,2.4,3.91,2.74a7.56,7.56,0,0,0,2.93-.13,14,14,0,0,0,4.82-1.47c3.35-2,4.42-6.33,4.63-10.22.07-1.31-.2-3-1.49-3.28-1.64-.35-2.84,2.14-4.5,1.87a2.51,2.51,0,0,1-1.3-.79c-1.13-1.13-1.9-2.58-3-3.72a10.31,10.31,0,0,0-6.44-2.76,24.08,24.08,0,0,0-7.09.67,11.65,11.65,0,0,0-3.65,1.25,4.39,4.39,0,0,0-2.2,3c-.11.87.12,1.77-.05,2.63a4.42,4.42,0,0,1-2.24,2.71c-1,.62-2.18,1-3.16,1.76a8.37,8.37,0,0,0-3.05,4.86,16.46,16.46,0,0,0-.16,5.83,32.28,32.28,0,0,0,2.78,9.72,13.77,13.77,0,0,0,1.81,3,16.81,16.81,0,0,0,3.65,3,2.22,2.22,0,0,0,1.83.55,1.59,1.59,0,0,0,.51-.3c1.28-1.07,1.4-3,1-4.63s-1.08-3.18-1.16-4.84a2.33,2.33,0,0,1,.26-1.35,6,6,0,0,1,.9-.91,5.44,5.44,0,0,0,1-1.69,16.53,16.53,0,0,0,1.55-4.86,6.55,6.55,0,0,0-.87-4A5.17,5.17,0,0,0,310.82,231.84Z" transform="translate(-53 -50.93)" opacity="0.1"></path> <path d="M310.82,231.32a4.08,4.08,0,0,1,2.55-.46,2.91,2.91,0,0,1,1,.7c1.17,1.11,2.33,2.4,3.91,2.74a7.56,7.56,0,0,0,2.93-.13,14,14,0,0,0,4.82-1.47c3.35-2,4.42-6.33,4.63-10.22.07-1.31-.2-3-1.49-3.28-1.64-.35-2.84,2.14-4.5,1.87a2.51,2.51,0,0,1-1.3-.79c-1.13-1.13-1.9-2.58-3-3.72a10.31,10.31,0,0,0-6.44-2.76,24.08,24.08,0,0,0-7.09.67,11.65,11.65,0,0,0-3.65,1.25,4.39,4.39,0,0,0-2.2,3c-.11.87.12,1.77-.05,2.63a4.42,4.42,0,0,1-2.24,2.71c-1,.62-2.18,1-3.16,1.76a8.37,8.37,0,0,0-3.05,4.86,16.46,16.46,0,0,0-.16,5.83,32.28,32.28,0,0,0,2.78,9.72,13.77,13.77,0,0,0,1.81,3,16.81,16.81,0,0,0,3.65,3,2.22,2.22,0,0,0,1.83.55,1.59,1.59,0,0,0,.51-.3c1.28-1.07,1.4-3,1-4.63s-1.08-3.18-1.16-4.84a2.33,2.33,0,0,1,.26-1.35,6,6,0,0,1,.9-.91,5.44,5.44,0,0,0,1-1.69,16.53,16.53,0,0,0,1.55-4.86,6.55,6.55,0,0,0-.87-4A5.17,5.17,0,0,0,310.82,231.32Z" transform="translate(-53 -50.93)" fill="#463e3b"></path> <ellipse cx="176" cy="557.1" rx="25.19" ry="9.25" fill="#cd9494"></ellipse> <ellipse cx="176" cy="556.07" rx="12.85" ry="4.72" opacity="0.05"></ellipse> <path d="M203.8,606s27.76,15.42,50.38,2.06c0,0-3.08,53.47-18.51,58.61H215.12S197.64,630.64,203.8,606Z" transform="translate(-53 -50.93)" fill="#ff7361"></path> <path d="M250.75,508c7.07,25.83-23.07,99.41-23.07,99.41s-63.39-48-70.46-73.82A48.48,48.48,0,1,1,250.75,508Z" transform="translate(-53 -50.93)" fill="#5850ec"></path> <path d="M250.75,508c7.07,25.83-23.07,99.41-23.07,99.41s-63.39-48-70.46-73.82A48.48,48.48,0,1,1,250.75,508Z" transform="translate(-53 -50.93)" opacity="0.1"></path> <path d="M191.45,474.17s39.46,59.57,36.49,133.37" transform="translate(-53 -50.93)" fill="none" stroke="#535461" stroke-miterlimit="10"></path> <path d="M260.82,555.2S225,535.73,229.57,607.59c0,0,29.85-1.07,40.44-25.42a21.12,21.12,0,0,0-8.66-26.67Z" transform="translate(-53 -50.93)" fill="#5850ec"></path> <path d="M261.41,555.32s-4.62,37.3-31.84,52.27" transform="translate(-53 -50.93)" fill="none" stroke="#535461" stroke-miterlimit="10"></path> <path d="M250.82,609.77c-1,12.3-5.31,50.5-18.22,54.81H214.17c.59,1.31.95,2.06.95,2.06h20.57C251.1,661.49,254.19,608,254.19,608A37.44,37.44,0,0,1,250.82,609.77Z" transform="translate(-53 -50.93)" opacity="0.05"></path> <rect x="430.12" y="472.81" width="209.17" height="121.39" fill="#dde1ec"></rect> <path d="M1035.83,284.14a8.76,8.76,0,0,0,0-1.18c-.36-4.88-5-8.41-9.6-10.29s-9.74-2.8-13.81-5.65c-6.88-4.82-9-13.83-9.62-22.07-.19-2.56-.27-5.12-.28-7.69s.16-5.41.4-8.11c.4-4.48,1.06-9.12,1-13.61a26.15,26.15,0,0,0-2.4-12.45c-4.08-8.06-13.24-12.18-21.72-15.69-3.26-1.35-6.6-2.72-10.14-2.87-3.74-.16-7.38,1.05-10.92,2.25-5.62,1.9-11.32,4.37-17.13,5.66-3.26.72-6.83.18-10,1.33-6.22,2.28-7.55,7.87-7.45,14,0,.39,0,.77,0,1.16-.62-.57-1.26-1.11-1.91-1.63l-.38-.3q-1-.81-2.13-1.53l-.36-.23a35.84,35.84,0,0,0-4.79-2.61l-.25-.11q-1.25-.55-2.54-1l-.16-.06a36.46,36.46,0,0,0-5.46-1.44h0a36.37,36.37,0,0,0-32.52,10.22l-6.87,7h0c-31.23-7-32.63,20.13-32.63,20.13l.23-.05-.08.19A96.44,96.44,0,0,0,837,247.67L595.83,494l-24.38,75.56,13.69-4.88,62.54-22.34L871.62,313.58c16.76,11.91,34.18,27.87,34.18,27.87S895,347.16,899,373.32c4.89,32-2.46,77.55-2.46,77.55v29.46l1.38-.38c-.17,1-.35,2-.52,3.15a168.08,168.08,0,0,0-.86,44.72c3.7,31.26,18.48,73.94,18.48,73.94s5.54,15,6.78,27.05a25.22,25.22,0,0,0,1.58,6.95,55.26,55.26,0,0,1,4.32,20.87c0,13.08,1.71,32.71,9.5,52.74,6.25,16.08,8.56,25.5,9.25,31-4.43,5.86-10.22,12.25-14.79,12.89-8.62,1.2-25.26,2.4-14.17,16.83a10.21,10.21,0,0,0,1.29,1.37c-8.42,1.36-17.68,4.36-8.68,16.06,11.09,14.43,76.39,7.82,76.39,7.82s.42-8,0-16.82c4.58-.32,7.41-.61,7.41-.61s1.85-34.87-6.16-39.08a16.9,16.9,0,0,0-4.32,1.06c-.86-4.82-1.52-10.81-1.93-15.25l3.17-.24V672.1s-3.7-32.46-17.25-46.89a29.15,29.15,0,0,1-4.43-6c.1-4.17.11-10,.06-16.73a13.89,13.89,0,0,1,1.91-2.52s7.39-9,32.65-66.13c14.9-33.69,11.58-53.14,6.35-63.44-1-3.66-3.6-14.11-7-33.35-4.31-24.65.62-48.7.62-48.7s16-41.48,23.41-58.31a27.39,27.39,0,0,0,2.08-12.85c1.93-1.41,2.86-3.76,3.69-6l7.59-20.25c.82-2.19,1.66-4.47,1.48-6.8ZM932.75,247.68a30.8,30.8,0,0,0,2.75,3.66h0s-1.13-1.18-3-3.08C932.59,248.07,932.67,247.87,932.75,247.68Zm-37.32,41.57,20.89-21.34c7.22,6.6,14.95,13.34,21.41,18.89a61.84,61.84,0,0,1-2.44,8.37c-2.8,1.39-6.5,1.84-11-.61C917.16,290.71,904,289.57,895.43,289.25Z" transform="translate(-53 -50.93)" fill="url(#636ac3f3-8a9d-4cd8-9529-d45e35aeb867)"></path> <path d="M878.62,210.5S863.13,219.44,867.9,232c0,0,16.09,4.77,22.64,16.69s57.21,54.82,57.21,54.82L984.69,285s-12.85-17.16-53.5-29.14c0,0-24-25.69-32.31-30.45S878.62,210.5,878.62,210.5Z" transform="translate(-53 -50.93)" fill="#febdd5"></path> <path d="M878.62,210.5S863.13,219.44,867.9,232c0,0,16.09,4.77,22.64,16.69s57.21,54.82,57.21,54.82L984.69,285s-12.85-17.16-53.5-29.14c0,0-24-25.69-32.31-30.45S878.62,210.5,878.62,210.5Z" transform="translate(-53 -50.93)" opacity="0.05"></path> <path d="M602.62,496.37,871.4,215a34.68,34.68,0,0,1,49-1.12h0a34.68,34.68,0,0,1,1.12,49L652.78,544.29l-73.74,27Z" transform="translate(-53 -50.93)" fill="#5850ec"></path> <path d="M905.75,208.89h0a34.68,34.68,0,0,1,1.12,49L638.1,539.26l-55.36,20.25L579,571.27l73.74-27L921.55,263a34.68,34.68,0,0,0-1.12-49h0a34.52,34.52,0,0,0-20.31-9.41A34.82,34.82,0,0,1,905.75,208.89Z" transform="translate(-53 -50.93)" fill="#fff" opacity="0.1"></path> <polygon points="549.62 445.45 599.78 493.36 539.28 515.5 526.04 520.34 534.43 493.71 549.62 445.45" fill="#efc8c4"></polygon> <path d="M587.43,544.63a14.75,14.75,0,0,1,5.16,2.07c1.82,1.31,3.14,3.58,2.64,5.76a13.82,13.82,0,0,1-2.08,3.77,11.77,11.77,0,0,0-.87,10.2L579,571.27Z" transform="translate(-53 -50.93)" fill="#727a9c"></path> <path d="M975.16,718.8s1.79,26.22,4.77,29.2-26.82,4.77-26.82,4.77l4.17-34Z" transform="translate(-53 -50.93)" fill="#ffb9b9"></path> <path d="M975.16,718.8s1.79,26.22,4.77,29.2-26.82,4.77-26.82,4.77l4.17-34Z" transform="translate(-53 -50.93)" opacity="0.05"></path> <path d="M981.71,739.06s-4.17,0-19.07,7.75c0,0-10.13-1.79-14.3-16.09,0,0-12.51,21.45-20.86,22.64s-24.43,2.38-13.71,16.69,73.89,7.75,73.89,7.75S989.46,743.23,981.71,739.06Z" transform="translate(-53 -50.93)" fill="#cbcdda"></path> <path d="M981.71,739.06s-4.17,0-19.07,7.75c0,0-10.13-1.79-14.3-16.09,0,0-12.51,21.45-20.86,22.64s-24.43,2.38-13.71,16.69,73.89,7.75,73.89,7.75S989.46,743.23,981.71,739.06Z" transform="translate(-53 -50.93)" opacity="0.05"></path> <path d="M972.77,759.32l-4.17,11.32-27.41-7.75,1.56-11.24.38-2.71,1-7.5s23.24-6,23.24-3a46.23,46.23,0,0,0,1.53,7.26c1,3.66,2.15,7.84,3,10.59C972.42,758.13,972.77,759.32,972.77,759.32Z" transform="translate(-53 -50.93)" fill="#ffb9b9"></path> <path d="M957.82,603.91c-1.88,3.18-4,9.14-.05,16.58a28.87,28.87,0,0,0,4.28,5.94c13.11,14.3,16.69,46.48,16.69,46.48v51.84l-8.94.69-14.3,1.1L934,638.36s-12.51-64.36-6.55-75.68,11.32-82.23,11.32-82.23l4.95-5.12,11.74-12.16h34a22.84,22.84,0,0,1,6.61,7.39c5.7,9.44,10.82,29.07-4.82,65.31-24.43,56.61-31.58,65.55-31.58,65.55A13.73,13.73,0,0,0,957.82,603.91Z" transform="translate(-53 -50.93)" fill="#4c4c78"></path> <path d="M957.82,603.91c-1.88,3.18-4,9.14-.05,16.58a28.87,28.87,0,0,0,4.28,5.94c13.11,14.3,16.69,46.48,16.69,46.48v51.84l-8.94.69-14.3,1.1L934,638.36s-12.51-64.36-6.55-75.68,11.32-82.23,11.32-82.23l4.95-5.12,11.74-12.16h34a22.84,22.84,0,0,1,6.61,7.39c5.7,9.44,10.82,29.07-4.82,65.31-24.43,56.61-31.58,65.55-31.58,65.55A13.73,13.73,0,0,0,957.82,603.91Z" transform="translate(-53 -50.93)" opacity="0.05"></path> <path d="M968.94,745.72l-25.81,3.22,1-7.5s23.24-6,23.24-3A46.23,46.23,0,0,0,968.94,745.72Z" transform="translate(-53 -50.93)" opacity="0.1"></path> <path d="M956.09,527.52s1.48,46.91,1.73,76.39c.05,6.67,0,12.45-.05,16.58a36.74,36.74,0,0,1-.48,6.54c-1.79,5.36,1.79,17.28,1.79,17.28,14.9,39.33,10.73,73.89,10.73,73.89v26.22L941.19,748s4.77-3.58-8.34-38.14c-7.53-19.86-9.16-39.31-9.19-52.28a55.94,55.94,0,0,0-4.18-20.68A25.52,25.52,0,0,1,918,630c-1.19-11.92-6.55-26.82-6.55-26.82s-14.3-42.31-17.88-73.3a170.67,170.67,0,0,1,.83-44.33,110.63,110.63,0,0,1,2.15-11.09s58.25-36.66,80.25-11.32a28.69,28.69,0,0,1,5,8.34,55.06,55.06,0,0,1,2.81,9.28C991.37,514.42,956.09,527.52,956.09,527.52Z" transform="translate(-53 -50.93)" fill="#4c4c78"></path> <circle cx="901.3" cy="185.2" r="30.39" fill="#ffb9b9"></circle> <path d="M937.62,255.19s12.51,35.16,0,53.63,39.92,12.51,39.92,12.51l20.26-24.43s-23.84,0-25-41.71S937.62,255.19,937.62,255.19Z" transform="translate(-53 -50.93)" fill="#ffb9b9"></path> <path d="M997.8,477.46a78.2,78.2,0,0,1-13.28,3.33c-7.42,1.14-16.17,1.19-21.29-3.33-3-2.68-10.51-3-19.49-2.15-18.91,1.81-44.51,8.87-49.41,10.26a110.63,110.63,0,0,1,2.15-11.09s58.25-36.66,80.25-11.32h12.71a22.84,22.84,0,0,1,6.61,7.39C997.16,475.29,997.8,477.46,997.8,477.46Z" transform="translate(-53 -50.93)" opacity="0.1"></path> <path d="M972.77,759.32l-4.17,11.32-27.41-7.75,1.56-11.24c4.71,10.42,12.74,11.83,12.74,11.83,8.66-4.5,13.7-6.39,16.4-7.18C972.42,758.13,972.77,759.32,972.77,759.32Z" transform="translate(-53 -50.93)" opacity="0.1"></path> <path d="M974.56,756.34s-4.17,0-19.07,7.75c0,0-10.13-1.79-14.3-16.09,0,0-12.51,21.45-20.86,22.64S895.9,773,906.63,787.33s73.89,7.75,73.89,7.75S982.31,760.51,974.56,756.34Z" transform="translate(-53 -50.93)" fill="#cbcdda"></path> <path d="M833.19,241.95s1.35-26.89,31.56-19.95L857,236.53Z" transform="translate(-53 -50.93)" fill="#ffb9b9"></path> <path d="M974,304.06s-29.2-4.77-36.95-10.73c0,0-6,11.32-16.69,5.36s-35.75-5.36-35.75-5.36-20.86-11.32-25-36.95c0,0-4.17-15.49-3-19.66,0,0-15.49-16.09-23.24,5.36,3,14.9,5.36,13.71,4.77,25.62l9.53,26.22s.6,8.94,17.88,20.86,36.95,30.39,36.95,30.39-10.43,5.66-6.55,31.58c4.73,31.68-2.38,76.87-2.38,76.87v29.2s59.59-17.28,69.72-8.34,34.56,0,34.56,0-3-10.13-7.15-34.56.6-48.27.6-48.27,15.49-41.12,22.64-57.8-5.36-39.33-17.88-47.67-17.17-3.33-17.17-3.33S1000.78,298.7,974,304.06Z" transform="translate(-53 -50.93)" fill="#febdd5"></path> <path d="M921.43,228.69a10.54,10.54,0,0,0,.62,3.64,11.48,11.48,0,0,0,2.79,3.47c4.46,4.36,8.38,9.54,9.95,15.58,1.06,4.09,1,8.37.94,12.6-.26,17.11-1,35.58-11.54,49-3.88,4.95-8.88,8.88-13.57,13.07A4.09,4.09,0,0,0,909.2,328a3.57,3.57,0,0,0,.44,2.36c2.18,4.53,6.77,7.31,11.12,9.83,2,1.15,4.08,2.34,6.38,2.41,5.42.18,9-5.55,13.93-7.8,7.18-3.28,15.23,1.28,22.36,4.68a78.79,78.79,0,0,0,16.14,5.63c7.63,1.76,16.25,2.19,22.67-2.28a17.31,17.31,0,0,0,7.06-11.05c.52-2.91.44-6.29,2.64-8.26,1.14-1,2.69-1.44,3.91-2.34,1.91-1.4,2.81-3.75,3.62-6l7.34-20.07a16.49,16.49,0,0,0,1.43-6.74c-.35-4.83-4.81-8.34-9.28-10.2s-9.42-2.77-13.36-5.6c-6.65-4.78-8.71-13.71-9.31-21.88a105.34,105.34,0,0,1,.12-16.85c.73-8.31,2.35-17.17-1.35-24.65-3.95-8-12.81-12.07-21-15.55-3.15-1.34-6.39-2.69-9.81-2.84-3.62-.16-7.14,1-10.56,2.23-5.44,1.89-11,4.33-16.57,5.61-3.15.72-6.61.18-9.65,1.32C916.3,204.16,921.43,219.7,921.43,228.69Z" transform="translate(-53 -50.93)" opacity="0.1"></path> <path d="M921.43,227.5a10.54,10.54,0,0,0,.62,3.64,11.48,11.48,0,0,0,2.79,3.47c4.46,4.36,8.38,9.54,9.95,15.58,1.06,4.09,1,8.37.94,12.6-.26,17.11-1,35.58-11.54,49-3.88,4.95-8.88,8.88-13.57,13.07a4.09,4.09,0,0,0-1.43,1.91,3.57,3.57,0,0,0,.44,2.36c2.18,4.53,6.77,7.31,11.12,9.83,2,1.15,4.08,2.34,6.38,2.41,5.42.18,9-5.55,13.93-7.8,7.18-3.28,15.23,1.28,22.36,4.68a78.79,78.79,0,0,0,16.14,5.63c7.63,1.76,16.25,2.19,22.67-2.28a17.31,17.31,0,0,0,7.06-11.05c.52-2.91.44-6.29,2.64-8.26,1.14-1,2.69-1.44,3.91-2.34,1.91-1.4,2.81-3.75,3.62-6l7.34-20.07a16.49,16.49,0,0,0,1.43-6.74c-.35-4.83-4.81-8.34-9.28-10.2s-9.42-2.77-13.36-5.6c-6.65-4.78-8.71-13.71-9.31-21.88a105.34,105.34,0,0,1,.12-16.85c.73-8.31,2.35-17.17-1.35-24.65-3.95-8-12.81-12.07-21-15.55-3.15-1.34-6.39-2.69-9.81-2.84-3.62-.16-7.14,1-10.56,2.23-5.44,1.89-11,4.33-16.57,5.61-3.15.72-6.61.18-9.65,1.32C916.3,203,921.43,218.51,921.43,227.5Z" transform="translate(-53 -50.93)" fill="#b96b6b"></path> <path d="M897.41,390.13S902.16,389,926,385.4s65.55,4.77,65.55,4.77" transform="translate(-53 -50.93)" opacity="0.05"></path></svg> <span class="text-gray-600">There are no attachments to show here</span></div></div>
                                            @endif
                                    </ul>
                                    </div>
                                </section>
                            </div>

                                <div class="tab_pane_sales-{{ $task->id }} tab-pane fade" id="sales-preferences{{ $task->id }}" role="tabpanel" aria-labelledby="sales-preferences-tab{{ $task->id }}">
                                <section class="p-3">
                                    <div class="flex flex-col">
                                        
                                        <!---->
                                        <div class="cursor-pointer mb-0">
                                            @if (!empty($project_columns))
                                                @foreach ($project_columns as $key1=>  $project_column)
                                                @php
                                                    $epic_process = EpicProcess::where('pipeline_process_task_id',$task->task_id)->where('column_id',$key1)->first();
                                                    $data = explode(',',$epic_process->sales_group);
                                                @endphp
                                                <div class="panel-default flex flex-col">
                                                    @if(strtoupper($project_column) == strtoupper('COMPLIMENTARY TRAINING'))
                                                    @php
                                                        if($epic_process->sales_group != '' && count($data) == $epic_process->total_sales){
                                                            $visibility = "checked";
                                                        }else{
                                                            $visibility = "";
                                                        }

                                                        $client = App\Clients::select('id','sale_process_setts')->where('id',$task->parent->content)->first();
                                                        $calendar_setting = App\CalendarSetting::select('cs_client_id','sales_process_settings')->where('cs_business_id',Session::get('businessId'))->select('sales_process_settings')->first();
                                                        if($client->sale_process_setts != null || $client->sale_process_setts != ''){
                                                            $json = (json_decode($client->sale_process_setts));
                                                        }else{
                                                            $json = (json_decode($calendar_setting->sales_process_settings));
                                                        }

                                                        // $count = count(array_filter(explode(',',$task->parent->sales_group)));
                                                        // if($count > 0 && $task->parent->total_sales > 0 && $count == $task->parent->total_sales){
                                                        //     $visibility = "checked";
                                                        // }else{
                                                        //     $visibility = "";
                                                        // }
                                                    @endphp     
                                                    <div class="pl-2 pb-1 border-b border-gray-200 mb-1 mt-1 gray-bg">
                                                        <div class="flex items-center rounded-lg">
                                                            <div class="mr-2">
                                                                <input type="checkbox" {{ $disable }} {{ $visibility }} class="form-checkbox w-6 h-6 rounded-full text-green-400 check-column1" task-id="{{ $task->id }}" column-id="{{ $key1 }}" data-from="complimentry" total-sales="{{($json->teamCount ? $json->teamCount : 0) + ($json->indivCount ? $json->indivCount : 0) + 1}}">
                                                            </div>
                                                            <a data-toggle="collapse" data-parent="#accordion" href="#collapse{{$key1}}{{$task->id}}" class="pt-2"> {{ $project_column }}</a>
                                                        </div>
                                                    </div>
                                                    <div id="collapse{{$key1}}{{$task->id}}"  class="collapse">
                                                        <div class="pl-4 py-2 mb-1 rounded-lg">
                                                            @php
                                                           

                                                            // if($task->parent->column->name != "COMPLIMENTARY TRAINING"){
                                                            //     $disable = 'disabled';
                                                            // }else{
                                                            //     $disable = '';
                                                            // }
                                                        @endphp
                                                        <div class="row"> 
                                                            @if ($json->teamCount > 0)
                                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                                <div class="pb-2 border-b border-gray-200 mb-1">
                                                                    <h4 class="font-medium text-lg"> Team ({{ $json->teamCount }})</h4>
                                                                </div>
                                                                @for ($i = 1; $i <= $json->teamCount; $i++)
                                                                @php
                                                                    // $data = explode(',',$task->parent->sales_group);
                                                                    if (in_array("team$i",$data, TRUE)) {
                                                                        $check = 'checked';
                                                                    }else{
                                                                        $check = '';
                                                                    }
                                                                @endphp
                                                                <div class="flex items-center py-2 mb-1 rounded-lg">
                                                                    <div>
                                                                        <input id="processCheckBox{{$task->id}}team{{ $i }}{{ $key1 }}" type="checkbox" {{$check}} {{ $disable }} proccess-data='team{{ $i }}'
                                                                        class="processCheckBox{{$task->id}}team{{ $i }}{{ $key1 }} processCheckBox{{$task->id}}{{ $key1 }} form-checkbox w-5 h-5 rounded-full text-green-400" 
                                                                        onclick="processCheckMe({{ $task->id }},{{ $key1 }},'team{{ $i }}',{{($json->teamCount ? $json->teamCount : 0) + ($json->indivCount ? $json->indivCount : 0) + 1}})">
                                                                    </div>
                                                                    
                                                                    <div class="pl-3 w-full">
                                                                        <div class="w-full text-sm">
                                                                            <label for="processCheckBox{{$task->id}}team{{ $i }}{{ $key1 }}" class="cursor-pointer mb-0">
                                                                                TEAM {{$i}} 
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                @endfor
                                                            </div>
                                                            @endif

                                                            @if ($json->indivCount > 0)
                                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                                <div class="pb-2 border-b border-gray-200 mb-1">
                                                                    <h4 class="font-medium text-lg">Individuals ({{ $json->indivCount }})</h4>
                                                                </div>
                                                                @for ($i = 1; $i <= $json->indivCount; $i++)
                                                                @php
                                                                    // $data = explode(',',$task->parent->sales_group);
                                                                    if (in_array("indiv$i",$data, TRUE)) {
                                                                        $check = 'checked';
                                                                    }else{
                                                                        $check = '';
                                                                    }
                                                                @endphp
                                                                <div class="flex items-center py-2 mb-1 rounded-lg">
                                                                    
                                                                    <div>
                                                                        <input id="processCheckBox{{$task->id}}indiv{{ $i }}{{ $key1 }}" type="checkbox" {{$check}} {{ $disable }} proccess-data="indiv{{ $i }}"
                                                                        class="processCheckBox{{$task->id}}indiv{{ $i }}{{ $key1 }} processCheckBox{{$task->id}}{{ $key1 }} form-checkbox w-5 h-5 rounded-full text-green-400" 
                                                                        onclick="processCheckMe({{ $task->id }},{{ $key1 }},'indiv{{ $i }}',{{($json->teamCount ? $json->teamCount : 0) + ($json->indivCount ? $json->indivCount : 0) + 1}})">
                                                                    </div>
                                                                    
                                                                    <div class="pl-3 w-full">
                                                                        <div class="w-full text-sm">
                                                                            <label for="processCheckBox{{$task->id}}indiv{{ $i }}{{ $key1 }}" class="cursor-pointer mb-0">
                                                                                INDIVIDUAL {{ $i }}
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                        
                                                                </div>
                                                                @endfor
                                                            </div>
                                                            
                                                        </div>
                                                        @endif
                                                        <div class="row">
                                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                            <div class="flex items-center py-2 mb-1 rounded-lg" @if((($json->teamCount) == 0) || (($json->indivCount) == 0)) style="margin-left: 15px;" @endif>
                                                            @php
                                                                // $data = explode(',',$task->parent->sales_group);
                                                                if (in_array("complementary_teams",$data, TRUE)) {
                                                                    $check11 = 'checked';
                                                                }else{
                                                                    $check11 = '';
                                                                }
                                                            @endphp
                                                                <div>
                                                                    <input id="processCheckBox{{$task->id}}complementary_teams{{ $key1 }}" type="checkbox" {{ $check11 }} proccess-data="complementary_teams"
                                                                    class="processCheckBox{{$task->id}}complementary_teams{{ $key1 }} processCheckBox{{$task->id}}{{ $key1 }} form-checkbox w-5 h-5 rounded-full text-green-400" 
                                                                    onclick="processCheckMe({{ $task->id }},{{ $key1 }},'complementary_teams',{{($json->teamCount ? $json->teamCount : 0) + ($json->indivCount ? $json->indivCount : 0) + 1}})">
                                                                </div>
                                                                
                                                                <div class="pl-3 w-full">
                                                                    <div class="w-full text-sm">
                                                                        <label for="processCheckBox{{$task->id}}complementary_teams{{ $key1 }}" class="cursor-pointer mb-0">
                                                                            Complementary Teams
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                                    
                                                            </div>
                                                        </div>
                                                        </div>
                                                    </div>
                                                    </div>
                                                @else
                                                
                                                @if (strtoupper($project_column) == strtoupper('CONSULTATION'))
                                                @php
                                                    if($epic_process->sales_group != '' && count($data) == $epic_process->total_sales){
                                                        $visibility = "checked";
                                                    }else{
                                                        $visibility = "";
                                                    }
                                                @endphp
                                                <div class="pl-2 pb-1 border-b border-gray-200 mb-1 mt-1 gray-bg">
                                                    <div class="flex items-center rounded-lg">
                                                        <div class="mr-2">
                                                            <input type="checkbox" {{ $visibility }} {{ $disable }} class="form-checkbox w-6 h-6 rounded-full text-green-400 check-column1" task-id="{{ $task->id }}" column-id="{{ $key1 }}" data-from="other" total-sales="3">
                                                        </div>
                                                        
                                                        <a data-toggle="collapse" data-parent="#accordion" href="#collapse{{$key1}}{{$task->id}}" class="pt-2"> {{ $project_column }}</a>
                                                    </div>
                                                </div>
                                                <div id="collapse{{$key1}}{{$task->id}}"  class="collapse">
                                                    <div class="row items-center pl-4 py-2 mb-1 rounded-lg">
                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12"> 
                                                            <div class="flex w-full py-2 mb-1">
                                                                <input id="processCheckBox{{$task->id}}consulation1{{ $key1 }}" type="checkbox" {{ $disable }} proccess-data='consulation1' @if(in_array("consulation1",$data, TRUE)) checked @endif
                                                                class="processCheckBox{{$task->id}}consulation1{{ $key1 }} processCheckBox{{$task->id}}{{ $key1 }} form-checkbox w-5 h-5 rounded-full text-green-400" 
                                                                onclick="processCheckMe({{ $task->id }},{{$key1 }},'consulation1',3)">
                                                                <div class="w-full text-sm ml-2 mt-1">
                                                                    <label for="processCheckBox{{$task->id}}consulation1{{ $key1 }}" class="cursor-pointer mb-0">
                                                                        Consultation
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12"> 
                                                            <div class="flex w-full py-2 mb-1">
                                                                <input id="processCheckBox{{$task->id}}consulation2{{ $key1 }}" type="checkbox" {{ $disable }} proccess-data='consulation2' @if(in_array("consulation2",$data, TRUE)) checked @endif
                                                                class="processCheckBox{{$task->id}}consulation2{{ $key1 }} processCheckBox{{$task->id}}{{ $key1 }} form-checkbox w-5 h-5 rounded-full text-green-400" 
                                                                onclick="processCheckMe({{ $task->id }},{{$key1 }},'consulation2',3)">
                                                                <div class="w-full text-sm ml-2 mt-1">
                                                                    <label for="processCheckBox{{$task->id}}consulation2{{ $key1 }}" class="cursor-pointer mb-0">
                                                                        Movement Analysis
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                            <div class="flex w-full py-2 mb-1">
                                                                <input id="processCheckBox{{$task->id}}consulation3{{ $key1 }}" type="checkbox" {{ $disable }} proccess-data='consulation3' @if(in_array("consulation3",$data, TRUE)) checked @endif
                                                                class="processCheckBox{{$task->id}}consulation3{{ $key1 }} processCheckBox{{$task->id}}{{ $key1 }} form-checkbox w-5 h-5 rounded-full text-green-400" 
                                                                onclick="processCheckMe({{ $task->id }},{{$key1 }},'consulation3',3)">
                                                                <div class="w-full text-sm ml-2 mt-1">
                                                                    <label for="processCheckBox{{$task->id}}consulation3{{ $key1 }}" class="cursor-pointer mb-0">
                                                                        Introduction to movement
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endif
                                                @if (strtoupper($project_column) == strtoupper('BENCHMARK'))
                                                @php
                                                    if($epic_process->sales_group != '' && count($data) == $epic_process->total_sales){
                                                        $visibility = "checked";
                                                    }else{
                                                        $visibility = "";
                                                    }
                                                @endphp
                                                <div class="pl-2 pb-1 border-b border-gray-200 mb-1 mt-1 gray-bg">
                                                    <div class="flex items-center rounded-lg">
                                                        <div class="mr-2">
                                                            <input type="checkbox" {{ $visibility }} {{ $disable }} class="form-checkbox w-6 h-6 rounded-full text-green-400 check-column1" task-id="{{ $task->id }}" column-id="{{ $key1 }}" data-from="other" total-sales="2">
                                                        </div>
                                                        <a data-toggle="collapse" data-parent="#accordion" href="#collapse{{$key1}}{{$task->id}}" class="pt-2"> {{ $project_column }}</a>
                                                    </div>
                                                </div>
                                                <div id="collapse{{$key1}}{{$task->id}}"  class="collapse">
                                                    <div class="row items-center pl-4 py-2 mb-1 rounded-lg">
                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                            <div class="flex w-full py-2 mb-1">
                                                                <input id="processCheckBox{{$task->id}}benchmark1{{ $key1 }}" type="checkbox" {{ $disable }} proccess-data='benchmark1' @if(in_array("benchmark1",$data, TRUE)) checked @endif
                                                                class="processCheckBox{{$task->id}}benchmark1{{ $key1 }} processCheckBox{{$task->id}}{{ $key1 }} form-checkbox w-5 h-5 rounded-full text-green-400" 
                                                                onclick="processCheckMe({{ $task->id }},{{$key1 }},'benchmark1',2)">
                                                                <div class="w-full text-sm ml-2 mt-1">
                                                                    <label for="processCheckBox{{$task->id}}benchmark1{{ $key1 }}" class="cursor-pointer mb-0">
                                                                        Benchmark
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                            <div class="flex w-full py-2 mb-1">
                                                                <input id="processCheckBox{{$task->id}}benchmark2{{ $key1 }}" type="checkbox" {{ $disable }} proccess-data='benchmark2' @if(in_array("benchmark2",$data, TRUE)) checked @endif
                                                                class="processCheckBox{{$task->id}}benchmark2{{ $key1 }} processCheckBox{{$task->id}}{{ $key1 }} form-checkbox w-5 h-5 rounded-full text-green-400" 
                                                                onclick="processCheckMe({{ $task->id }},{{$key1 }},'benchmark2',2)">
                                                                <div class="w-full text-sm ml-2 mt-1">
                                                                    <label for="processCheckBox{{$task->id}}benchmark2{{ $key1 }}" class="cursor-pointer mb-0">
                                                                        Introduction to movement
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endif
                                                
                                                @if (strtoupper($project_column) == strtoupper('POSTURE ANALYSIS 1'))
                                                @php
                                                    if($epic_process->sales_group != '' && count($data) == $epic_process->total_sales){
                                                        $visibility = "checked";
                                                    }else{
                                                        $visibility = "";
                                                    }
                                                @endphp
                                                <div class="pl-2 pb-1 border-b border-gray-200 mb-1 mt-1 gray-bg">
                                                    <div class="flex items-center rounded-lg">
                                                        <div class="mr-2">
                                                            <input type="checkbox" {{ $visibility }} {{ $disable }} class="form-checkbox w-6 h-6 rounded-full text-green-400 check-column1" task-id="{{ $task->id }}" column-id="{{ $key1 }}" data-from="other" total-sales="2">
                                                        </div>
                                                        <a data-toggle="collapse" data-parent="#accordion" href="#collapse{{$key1}}{{$task->id}}" class="pt-2"> {{ $project_column }}</a>
                                                    </div>
                                                </div>
                                                <div id="collapse{{$key1}}{{$task->id}}"  class="collapse">
                                                    <div class="row pl-4 py-2 mb-1 rounded-lg">
                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">   
                                                                <div class="flex w-full py-2 mb-1">
                                                                    <input id="processCheckBox{{$task->id}}posture_analysis11{{ $key1 }}" type="checkbox" {{ $disable }} proccess-data='posture_analysis11' @if(in_array("posture_analysis11",$data, TRUE)) checked @endif
                                                                    class="processCheckBox{{$task->id}}posture_analysis11{{ $key1 }} processCheckBox{{$task->id}}{{ $key1 }} form-checkbox w-5 h-5 rounded-full text-green-400" 
                                                                    onclick="processCheckMe({{ $task->id }},{{$key1 }},'posture_analysis11',2)">
                                                                    <div class="w-full text-sm ml-2 mt-1">
                                                                        <label for="processCheckBox{{$task->id}}posture_analysis11{{ $key1 }}" class="cursor-pointer mb-0">
                                                                            Posture Analysis
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                        </div>
                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                            <div class="flex w-full py-2 mb-1">
                                                                <input id="processCheckBox{{$task->id}}posture_analysis12{{ $key1 }}" type="checkbox" {{ $disable }} proccess-data='posture_analysis12' @if(in_array("posture_analysis12",$data, TRUE)) checked @endif
                                                                class="processCheckBox{{$task->id}}posture_analysis12{{ $key1 }} processCheckBox{{$task->id}}{{ $key1 }} form-checkbox w-5 h-5 rounded-full text-green-400" 
                                                                onclick="processCheckMe({{ $task->id }},{{$key1 }},'posture_analysis12',2)">
                                                                <div class="w-full text-sm ml-2 mt-1">
                                                                    <label for="processCheckBox{{$task->id}}posture_analysis12{{ $key1 }}" class="cursor-pointer mb-0">
                                                                        Stretch Program
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endif

                                                @if (strtoupper($project_column) == strtoupper('SUB MAX TESTING'))
                                                @php
                                                    if($epic_process->sales_group != '' && count($data) == $epic_process->total_sales){
                                                        $visibility = "checked";
                                                    }else{
                                                        $visibility = "";
                                                    }
                                                @endphp
                                                <div class="pl-2 pb-1 border-b border-gray-200 mb-1 mt-1 gray-bg">
                                                    <div class="flex items-center rounded-lg">
                                                        <div class="mr-2">
                                                            <input type="checkbox" {{ $visibility }} {{ $disable }} class="form-checkbox w-6 h-6 rounded-full text-green-400 check-column1" task-id="{{ $task->id }}" column-id="{{ $key1 }}" data-from="other" total-sales="1">
                                                        </div>
                                                        
                                                        <a data-toggle="collapse" data-parent="#accordion" href="#collapse{{$key1}}{{$task->task_id}}" class="pt-2"> {{ $project_column }}</a>
                                                    </div>
                                                </div>
                                                <div id="collapse{{$key1}}{{$task->task_id}}"  class="collapse">
                                                    <div class="row pl-4 py-2 mb-1 rounded-lg">
                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">   
                                                            <div class="flex w-full py-2 mb-1">
                                                                <input id="processCheckBox{{$task->id}}sub_max_test{{ $key1 }}" type="checkbox" {{ $disable }} proccess-data='sub_max_test' @if(in_array("sub_max_test",$data, TRUE)) checked @endif
                                                                class="processCheckBox{{$task->id}}sub_max_test{{ $key1 }} processCheckBox{{$task->id}}{{ $key1 }} form-checkbox w-5 h-5 rounded-full text-green-400" 
                                                                onclick="processCheckMe({{ $task->id }},{{$key1 }},'sub_max_test',1)">
                                                                <div class="w-full text-sm ml-2 mt-1">
                                                                    <label for="processCheckBox{{$task->id}}sub_max_test{{ $key1 }}" class="cursor-pointer mb-0">
                                                                        Sub Max Test
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        
                                                    </div>
                                                </div>
                                                @endif
                                                @if (strtoupper($project_column) == strtoupper('GOAL SETTING 1'))
                                                @php
                                                    if($epic_process->sales_group != '' && count($data) == $epic_process->total_sales){
                                                        $visibility = "checked";
                                                    }else{
                                                        $visibility = "";
                                                    }
                                                @endphp
                                                <div class="pl-2 pb-1 border-b border-gray-200 mb-1 mt-1 gray-bg">
                                                    <div class="flex items-center rounded-lg">
                                                        <div class="mr-2">
                                                            <input type="checkbox" {{ $visibility }} {{ $disable }} class="form-checkbox w-6 h-6 rounded-full text-green-400 check-column1" task-id="{{ $task->id }}" column-id="{{ $key1 }}" data-from="other" total-sales="5">
                                                        </div>
                                                        
                                                        <a data-toggle="collapse" data-parent="#accordion" href="#collapse{{$key1}}{{$task->id}}" class="pt-2"> {{ $project_column }}</a>
                                                    </div>
                                                </div>
                                                <div id="collapse{{$key1}}{{$task->id}}"  class="collapse">
                                                    <div class="row pl-4 py-2 mb-1 rounded-lg">
                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">   
                                                                <div class="flex w-full py-2 mb-1">
                                                                    <input id="processCheckBox{{$task->id}}goal_setting11{{ $key1 }}" type="checkbox" {{ $disable }} proccess-data='goal_setting11' @if(in_array("goal_setting11",$data, TRUE)) checked @endif
                                                                    class="processCheckBox{{$task->id}}goal_setting11{{ $key1 }} processCheckBox{{$task->id}}{{ $key1 }} form-checkbox w-5 h-5 rounded-full text-green-400" 
                                                                    onclick="processCheckMe({{ $task->id }},{{$key1 }},'goal_setting11',5)">
                                                                    <div class="w-full text-sm ml-2 mt-1">
                                                                        <label for="processCheckBox{{$task->id}}goal_setting11{{ $key1 }}" class="cursor-pointer mb-0">
                                                                            Goal Setting 1
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                        </div>
                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                            <div class="flex w-full py-2 mb-1">
                                                                <input id="processCheckBox{{$task->id}}goal_setting12{{ $key1 }}" type="checkbox" {{ $disable }} proccess-data='goal_setting12' @if(in_array("goal_setting12",$data, TRUE)) checked @endif
                                                                class="processCheckBox{{$task->id}}goal_setting12{{ $key1 }} processCheckBox{{$task->id}}{{ $key1 }} form-checkbox w-5 h-5 rounded-full text-green-400" 
                                                                onclick="processCheckMe({{ $task->id }},{{$key1 }},'goal_setting12',5)">
                                                                <div class="w-full text-sm ml-2 mt-1">
                                                                    <label for="processCheckBox{{$task->id}}goal_setting12{{ $key1 }}" class="cursor-pointer mb-0">
                                                                        Sleep Questionnaire
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                            <div class="flex w-full py-2 mb-1">
                                                                <input id="processCheckBox{{$task->id}}goal_setting13{{ $key1 }}" type="checkbox" {{ $disable }} proccess-data='goal_setting13' @if(in_array("goal_setting13",$data, TRUE)) checked @endif
                                                                class="processCheckBox{{$task->id}}goal_setting13{{ $key1 }} processCheckBox{{$task->id}}{{ $key1 }} form-checkbox w-5 h-5 rounded-full text-green-400" 
                                                                onclick="processCheckMe({{ $task->id }},{{$key1 }},'goal_setting13',5)">
                                                                <div class="w-full text-sm ml-2 mt-1">
                                                                    <label for="processCheckBox{{$task->id}}goal_setting13{{ $key1 }}" class="cursor-pointer mb-0">
                                                                        Goal Setting Follow up 1
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                            <div class="flex w-full py-2 mb-1">
                                                                <input id="processCheckBox{{$task->id}}goal_setting14{{ $key1 }}" type="checkbox" {{ $disable }} proccess-data='goal_setting14' @if(in_array("goal_setting14",$data, TRUE)) checked @endif
                                                                class="processCheckBox{{$task->id}}goal_setting14{{ $key1 }} processCheckBox{{$task->id}}{{ $key1 }} form-checkbox w-5 h-5 rounded-full text-green-400" 
                                                                onclick="processCheckMe({{ $task->id }},{{$key1 }},'goal_setting14',5)">
                                                                <div class="w-full text-sm ml-2 mt-1">
                                                                    <label for="processCheckBox{{$task->id}}goal_setting14{{ $key1 }}" class="cursor-pointer mb-0">
                                                                        Goal Setting Follow up 2
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                            <div class="flex w-full py-2 mb-1">
                                                                <input id="processCheckBox{{$task->id}}goal_setting15{{ $key1 }}" type="checkbox" {{ $disable }} proccess-data='goal_setting15' @if(in_array("goal_setting15",$data, TRUE)) checked @endif
                                                                class="processCheckBox{{$task->id}}goal_setting15{{ $key1 }} processCheckBox{{$task->id}}{{ $key1 }} form-checkbox w-5 h-5 rounded-full text-green-400" 
                                                                onclick="processCheckMe({{ $task->id }},{{$key1 }},'goal_setting15',5)">
                                                                <div class="w-full text-sm ml-2 mt-1">
                                                                    <label for="processCheckBox{{$task->id}}goal_setting15{{ $key1 }}" class="cursor-pointer mb-0">
                                                                        Progression session
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endif
                                                @if (strtoupper($project_column) == strtoupper('NUTRITIONAL JOURNAL 1'))
                                                @php
                                                    if($epic_process->sales_group != '' && count($data) == $epic_process->total_sales){
                                                        $visibility = "checked";
                                                    }else{
                                                        $visibility = "";
                                                    }
                                                @endphp
                                                <div class="pl-2 pb-1 border-b border-gray-200 mb-1 mt-1 gray-bg">
                                                    <div class="flex items-center rounded-lg">
                                                        <div class="mr-2">
                                                            <input type="checkbox" {{ $visibility }} {{ $disable }} class="form-checkbox w-6 h-6 rounded-full text-green-400 check-column1" task-id="{{ $task->id }}" column-id="{{ $key1 }}" data-from="other" total-sales="3">
                                                        </div>
                                                        <a data-toggle="collapse" data-parent="#accordion" href="#collapse{{$key1}}{{$task->id}}" class="pt-2"> {{ $project_column }}</a>
                                                    </div>
                                                </div>
                                                <div id="collapse{{$key1}}{{$task->id}}"  class="collapse">
                                                    <div class="row pl-4 py-2 mb-1 rounded-lg">
                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">                                                                                        
                                                            <div class="flex w-full py-2 mb-1">
                                                                <input id="processCheckBox{{$task->id}}nutrition_journal11{{ $key1 }}" type="checkbox" {{ $disable }} proccess-data='nutrition_journal11' @if(in_array("nutrition_journal11",$data, TRUE)) checked @endif
                                                                class="processCheckBox{{$task->id}}nutrition_journal11{{ $key1 }} processCheckBox{{$task->id}}{{ $key1 }} form-checkbox w-5 h-5 rounded-full text-green-400" 
                                                                onclick="processCheckMe({{ $task->id }},{{$key1 }},'nutrition_journal11',3)">
                                                                <div class="w-full text-sm ml-2 mt-1">
                                                                    <label for="processCheckBox{{$task->id}}nutrition_journal11{{ $key1 }}" class="cursor-pointer mb-0">
                                                                        Nutritional Journal 1
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">                                                                                        
                                                            <div class="flex w-full py-2 mb-1">
                                                                <input id="processCheckBox{{$task->id}}nutrition_journal12{{ $key1 }}" type="checkbox" {{ $disable }} proccess-data='nutrition_journal12' @if(in_array("nutrition_journal12",$data, TRUE)) checked @endif
                                                                class="processCheckBox{{$task->id}}nutrition_journal12{{ $key1 }} processCheckBox{{$task->id}}{{ $key1 }} form-checkbox w-5 h-5 rounded-full text-green-400" 
                                                                onclick="processCheckMe({{ $task->id }},{{$key1 }},'nutrition_journal12',3)">
                                                                <div class="w-full text-sm ml-2 mt-1">
                                                                    <label for="processCheckBox{{$task->id}}nutrition_journal12{{ $key1 }}" class="cursor-pointer mb-0">
                                                                        Journal follow up 1
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">                                                                                        
                                                            <div class="flex w-full py-2 mb-1">
                                                                <input id="processCheckBox{{$task->id}}nutrition_journal13{{ $key1 }}" type="checkbox" {{ $disable }} proccess-data='nutrition_journal13' @if(in_array("nutrition_journal13",$data, TRUE)) checked @endif
                                                                class="processCheckBox{{$task->id}}nutrition_journal13{{ $key1 }} processCheckBox{{$task->id}}{{ $key1 }} form-checkbox w-5 h-5 rounded-full text-green-400" 
                                                                onclick="processCheckMe({{ $task->id }},{{$key1 }},'nutrition_journal13',3)">
                                                                <div class="w-full text-sm ml-2 mt-1">
                                                                    <label for="processCheckBox{{$task->id}}nutrition_journal13{{ $key1 }}" class="cursor-pointer mb-0">
                                                                        Journal follow up 2
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endif
                                                @if (strtoupper($project_column) == strtoupper('GOAL SETTING 2'))
                                                @php
                                                    if($epic_process->sales_group != '' && count($data) == $epic_process->total_sales){
                                                        $visibility = "checked";
                                                    }else{
                                                        $visibility = "";
                                                    }
                                                @endphp
                                                <div class="pl-2 pb-1 border-b border-gray-200 mb-1 mt-1 gray-bg">
                                                    <div class="flex items-center rounded-lg">
                                                        <div class="mr-2">
                                                            <input type="checkbox" {{ $visibility }} {{ $disable }} class="form-checkbox w-6 h-6 rounded-full text-green-400 check-column1" task-id="{{ $task->id }}" column-id="{{ $key1 }}" data-from="other" total-sales="6">
                                                        </div>
                                                        <a data-toggle="collapse" data-parent="#accordion" href="#collapse{{$key1}}{{$task->id}}" class="pt-2"> {{ $project_column }}</a>
                                                    </div>
                                                </div>
                                                <div id="collapse{{$key1}}{{$task->id}}"  class="collapse">
                                                    <div class="row pl-4 py-2 mb-1 rounded-lg">
                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">   
                                                                <div class="flex w-full py-2 mb-1">
                                                                    <input id="processCheckBox{{$task->id}}goal_setting21{{ $key1 }}" type="checkbox" {{ $disable }} proccess-data='goal_setting21' @if(in_array("goal_setting21",$data, TRUE)) checked @endif
                                                                    class="processCheckBox{{$task->id}}goal_setting21{{ $key1 }} processCheckBox{{$task->id}}{{ $key1 }} form-checkbox w-5 h-5 rounded-full text-green-400" 
                                                                    onclick="processCheckMe({{ $task->id }},{{$key1 }},'goal_setting21',6)">
                                                                    <div class="w-full text-sm ml-2 mt-1">
                                                                        <label for="processCheckBox{{$task->id}}goal_setting21{{ $key1 }}" class="cursor-pointer mb-0">
                                                                            Goal Setting 1
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                        </div>
                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                            <div class="flex w-full py-2 mb-1">
                                                                <input id="processCheckBox{{$task->id}}goal_setting22{{ $key1 }}" type="checkbox" {{ $disable }} proccess-data='goal_setting22' @if(in_array("goal_setting22",$data, TRUE)) checked @endif
                                                                class="processCheckBox{{$task->id}}goal_setting22{{ $key1 }} processCheckBox{{$task->id}}{{ $key1 }} form-checkbox w-5 h-5 rounded-full text-green-400" 
                                                                onclick="processCheckMe({{ $task->id }},{{$key1 }},'goal_setting22',6)">
                                                                <div class="w-full text-sm ml-2 mt-1">
                                                                    <label for="processCheckBox{{$task->id}}goal_setting22{{ $key1 }}" class="cursor-pointer mb-0">
                                                                        Sleep Questionnaire
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                            <div class="flex w-full py-2 mb-1">
                                                                <input id="processCheckBox{{$task->id}}goal_setting23{{ $key1 }}" type="checkbox" {{ $disable }} proccess-data='goal_setting23' @if(in_array("goal_setting23",$data, TRUE)) checked @endif
                                                                class="processCheckBox{{$task->id}}goal_setting23{{ $key1 }} processCheckBox{{$task->id}}{{ $key1 }} form-checkbox w-5 h-5 rounded-full text-green-400" 
                                                                onclick="processCheckMe({{ $task->id }},{{$key1 }},'goal_setting23',6)">
                                                                <div class="w-full text-sm ml-2 mt-1">
                                                                    <label for="processCheckBox{{$task->id}}goal_setting23{{ $key1 }}" class="cursor-pointer mb-0">
                                                                        Goal Setting Follow up 1
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                            <div class="flex w-full py-2 mb-1">
                                                                <input id="processCheckBox{{$task->id}}goal_setting24{{ $key1 }}" type="checkbox" {{ $disable }} proccess-data='goal_setting24' @if(in_array("goal_setting24",$data, TRUE)) checked @endif
                                                                class="processCheckBox{{$task->id}}goal_setting24{{ $key1 }} processCheckBox{{$task->id}}{{ $key1 }} form-checkbox w-5 h-5 rounded-full text-green-400" 
                                                                onclick="processCheckMe({{ $task->id }},{{$key1 }},'goal_setting24',6)">
                                                                <div class="w-full text-sm ml-2 mt-1">
                                                                    <label for="processCheckBox{{$task->id}}goal_setting24{{ $key1 }}" class="cursor-pointer mb-0">
                                                                        Goal Setting Follow up 2
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                            <div class="flex w-full py-2 mb-1">
                                                                <input id="processCheckBox{{$task->id}}goal_setting25{{ $key1 }}" type="checkbox" {{ $disable }} proccess-data='goal_setting25' @if(in_array("goal_setting25",$data, TRUE)) checked @endif
                                                                class="processCheckBox{{$task->id}}goal_setting25{{ $key1 }} processCheckBox{{$task->id}}{{ $key1 }} form-checkbox w-5 h-5 rounded-full text-green-400" 
                                                                onclick="processCheckMe({{ $task->id }},{{$key1 }},'goal_setting25',6)">
                                                                <div class="w-full text-sm ml-2 mt-1">
                                                                    <label for="processCheckBox{{$task->id}}goal_setting25{{ $key1 }}" class="cursor-pointer mb-0">
                                                                        Progression session
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                            <div class="flex w-full py-2 mb-1">
                                                                <input id="processCheckBox{{$task->id}}goal_setting26{{ $key1 }}" type="checkbox" {{ $disable }} proccess-data='goal_setting26' @if(in_array("goal_setting26",$data, TRUE)) checked @endif
                                                                class="processCheckBox{{$task->id}}goal_setting26{{ $key1 }} processCheckBox{{$task->id}}{{ $key1 }} form-checkbox w-5 h-5 rounded-full text-green-400" 
                                                                onclick="processCheckMe({{ $task->id }},{{$key1 }},'goal_setting26',6)">
                                                                <div class="w-full text-sm ml-2 mt-1">
                                                                    <label for="processCheckBox{{$task->id}}goal_setting26{{ $key1 }}" class="cursor-pointer mb-0">
                                                                        Movement Analysis
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endif

                                                @if (strtoupper($project_column) == strtoupper('POSTURE ANALYSIS 2'))
                                                @php
                                                    if($epic_process->sales_group != '' && count($data) == $epic_process->total_sales){
                                                        $visibility = "checked";
                                                    }else{
                                                        $visibility = "";
                                                    }
                                                @endphp
                                                <div class="pl-2 pb-1 border-b border-gray-200 mb-1 mt-1 gray-bg">
                                                    <div class="flex items-center rounded-lg">
                                                        <div class="mr-2">
                                                            <input type="checkbox" {{ $visibility }} {{ $disable }} class="form-checkbox w-6 h-6 rounded-full text-green-400 check-column1" task-id="{{ $task->id }}" column-id="{{ $key1 }}" data-from="other" total-sales="2">
                                                        </div>
                                                        <a data-toggle="collapse" data-parent="#accordion" href="#collapse{{$key1}}{{$task->id}}" class="pt-2"> {{ $project_column }}</a>
                                                    </div>
                                                </div>
                                                <div id="collapse{{$key1}}{{$task->id}}"  class="collapse">
                                                    <div class="row pl-4 py-2 mb-1 rounded-lg">
                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">   
                                                                <div class="flex w-full py-2 mb-1">
                                                                    <input id="processCheckBox{{$task->id}}posture_analysis21{{ $key1 }}" type="checkbox" {{ $disable }} proccess-data='posture_analysis21' @if(in_array("posture_analysis21",$data, TRUE)) checked @endif
                                                                    class="processCheckBox{{$task->id}}posture_analysis21{{ $key1 }} processCheckBox{{$task->id}}{{ $key1 }} form-checkbox w-5 h-5 rounded-full text-green-400" 
                                                                    onclick="processCheckMe({{ $task->id }},{{$key1 }},'posture_analysis21',2)">
                                                                    <div class="w-full text-sm ml-2 mt-1">
                                                                        <label for="processCheckBox{{$task->id}}posture_analysis21{{ $key1 }}" class="cursor-pointer mb-0">
                                                                            Posture Analysis
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                        </div>
                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                            <div class="flex w-full py-2 mb-1">
                                                                <input id="processCheckBox{{$task->id}}posture_analysis22{{ $key1 }}" type="checkbox" {{ $disable }} proccess-data='posture_analysis22' @if(in_array("posture_analysis22",$data, TRUE)) checked @endif
                                                                class="processCheckBox{{$task->id}}posture_analysis22{{ $key1 }} processCheckBox{{$task->id}}{{ $key1 }} form-checkbox w-5 h-5 rounded-full text-green-400" 
                                                                onclick="processCheckMe({{ $task->id }},{{$key1 }},'posture_analysis22',2)">
                                                                <div class="w-full text-sm ml-2 mt-1">
                                                                    <label for="processCheckBox{{$task->id}}posture_analysis22{{ $key1 }}" class="cursor-pointer mb-0">
                                                                        Stretch Program
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endif

                                                @if (strtoupper($project_column) == strtoupper('NUTRITIONAL JOURNAL 2'))
                                                @php
                                                    if($epic_process->sales_group != '' && count($data) == $epic_process->total_sales){
                                                        $visibility = "checked";
                                                    }else{
                                                        $visibility = "";
                                                    }
                                                @endphp
                                                <div class="pl-2 pb-1 border-b border-gray-200 mb-1 mt-1 gray-bg">
                                                    <div class="flex items-center rounded-lg">
                                                        <div class="mr-2">
                                                            <input type="checkbox" {{ $visibility }} {{ $disable }} class="form-checkbox w-6 h-6 rounded-full text-green-400 check-column1" task-id="{{ $task->id }}" column-id="{{ $key1 }}" data-from="other" total-sales="3">
                                                        </div>
                                                        <a data-toggle="collapse" data-parent="#accordion" href="#collapse{{$key1}}{{$task->id}}" class="pt-2"> {{ $project_column }}</a>
                                                    </div>
                                                </div>
                                                <div id="collapse{{$key1}}{{$task->id}}"  class="collapse">
                                                    <div class="row pl-4 py-2 mb-1 rounded-lg">
                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">                                                                                        
                                                            <div class="flex w-full py-2 mb-1">
                                                                <input id="processCheckBox{{$task->id}}nutrition_journal21{{ $key1 }}" type="checkbox" {{ $disable }} proccess-data='nutrition_journal21' @if(in_array("nutrition_journal21",$data, TRUE)) checked @endif
                                                                class="processCheckBox{{$task->id}}nutrition_journal21{{ $key1 }} processCheckBox{{$task->id}}{{ $key1 }} form-checkbox w-5 h-5 rounded-full text-green-400" 
                                                                onclick="processCheckMe({{ $task->id }},{{$key1 }},'nutrition_journal21',3)">
                                                                <div class="w-full text-sm ml-2 mt-1">
                                                                    <label for="processCheckBox{{$task->id}}nutrition_journal21{{ $key1 }}" class="cursor-pointer mb-0">
                                                                        Nutritional Journal 2
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">                                                                                        
                                                            <div class="flex w-full py-2 mb-1">
                                                                <input id="processCheckBox{{$task->id}}nutrition_journal22{{ $key1 }}" type="checkbox" {{ $disable }} proccess-data='nutrition_journal22' @if(in_array("nutrition_journal22",$data, TRUE)) checked @endif
                                                                class="processCheckBox{{$task->id}}nutrition_journal22{{ $key1 }} processCheckBox{{$task->id}}{{ $key1 }} form-checkbox w-5 h-5 rounded-full text-green-400" 
                                                                onclick="processCheckMe({{ $task->id }},{{$key1 }},'nutrition_journal22',3)">
                                                                <div class="w-full text-sm ml-2 mt-1">
                                                                    <label for="processCheckBox{{$task->id}}nutrition_journal22{{ $key1 }}" class="cursor-pointer mb-0">
                                                                        Journal follow up 1
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">                                                                                        
                                                            <div class="flex w-full py-2 mb-1">
                                                                <input id="processCheckBox{{$task->id}}nutrition_journal23{{ $key1 }}" type="checkbox" {{ $disable }} proccess-data='nutrition_journal23' @if(in_array("nutrition_journal22",$data, TRUE)) checked @endif
                                                                class="processCheckBox{{$task->id}}nutrition_journal23{{ $key1 }} processCheckBox{{$task->id}}{{ $key1 }} form-checkbox w-5 h-5 rounded-full text-green-400" 
                                                                onclick="processCheckMe({{ $task->id }},{{$key1 }},'nutrition_journal23',3)">
                                                                <div class="w-full text-sm ml-2 mt-1">
                                                                    <label for="processCheckBox{{$task->id}}nutrition_journal23{{ $key1 }}" class="cursor-pointer mb-0">
                                                                        Journal follow up 2
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endif

                                                @if (strtoupper($project_column) == strtoupper('GOAL SETTING 3'))
                                                @php
                                                    if($epic_process->sales_group != '' && count($data) == $epic_process->total_sales){
                                                        $visibility = "checked";
                                                    }else{
                                                        $visibility = "";
                                                    }
                                                @endphp
                                                <div class="pl-2 pb-1 border-b border-gray-200 mb-1 mt-1 gray-bg">
                                                    <div class="flex items-center rounded-lg">
                                                        <div class="mr-2">
                                                            <input type="checkbox" {{ $visibility }} {{ $disable }} class="form-checkbox w-6 h-6 rounded-full text-green-400 check-column1" task-id="{{ $task->id }}" column-id="{{ $key1 }}" data-from="other" total-sales="5">
                                                        </div>
                                                        
                                                        <a data-toggle="collapse" data-parent="#accordion" href="#collapse{{$key1}}{{$task->id}}" class="pt-2"> {{ $project_column }}</a>
                                                    </div>
                                                </div>
                                                <div id="collapse{{$key1}}{{$task->id}}"  class="collapse">
                                                    <div class="row pl-4 py-2 mb-1 rounded-lg">
                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">   
                                                                <div class="flex w-full py-2 mb-1">
                                                                    <input id="processCheckBox{{$task->id}}goal_setting31{{ $key1 }}" type="checkbox" {{ $disable }} proccess-data='goal_setting31' @if(in_array("goal_setting31",$data, TRUE)) checked @endif
                                                                    class="processCheckBox{{$task->id}}goal_setting31{{ $key1 }} processCheckBox{{$task->id}}{{ $key1 }} form-checkbox w-5 h-5 rounded-full text-green-400" 
                                                                    onclick="processCheckMe({{ $task->id }},{{$key1 }},'goal_setting31',5)">
                                                                    <div class="w-full text-sm ml-2 mt-1">
                                                                        <label for="processCheckBox{{$task->id}}goal_setting31{{ $key1 }}" class="cursor-pointer mb-0">
                                                                            Goal Setting 3
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                        </div>
                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                            <div class="flex w-full py-2 mb-1">
                                                                <input id="processCheckBox{{$task->id}}goal_setting32{{ $key1 }}" type="checkbox" {{ $disable }} proccess-data='goal_setting32' @if(in_array("goal_setting32",$data, TRUE)) checked @endif
                                                                class="processCheckBox{{$task->id}}goal_setting32{{ $key1 }} processCheckBox{{$task->id}}{{ $key1 }} form-checkbox w-5 h-5 rounded-full text-green-400" 
                                                                onclick="processCheckMe({{ $task->id }},{{$key1 }},'goal_setting32',5)">
                                                                <div class="w-full text-sm ml-2 mt-1">
                                                                    <label for="processCheckBox{{$task->id}}goal_setting32{{ $key1 }}" class="cursor-pointer mb-0">
                                                                        Sleep Questionnaire
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                            <div class="flex w-full py-2 mb-1">
                                                                <input id="processCheckBox{{$task->id}}goal_setting33{{ $key1 }}" type="checkbox" {{ $disable }} proccess-data='goal_setting33' @if(in_array("goal_setting33",$data, TRUE)) checked @endif
                                                                class="processCheckBox{{$task->id}}goal_setting33{{ $key1 }} processCheckBox{{$task->id}}{{ $key1 }} form-checkbox w-5 h-5 rounded-full text-green-400" 
                                                                onclick="processCheckMe({{ $task->id }},{{$key1 }},'goal_setting33',5)">
                                                                <div class="w-full text-sm ml-2 mt-1">
                                                                    <label for="processCheckBox{{$task->id}}goal_setting33{{ $key1 }}" class="cursor-pointer mb-0">
                                                                        Goal Setting Follow up 1
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                            <div class="flex w-full py-2 mb-1">
                                                                <input id="processCheckBox{{$task->id}}goal_setting34{{ $key1 }}" type="checkbox" {{ $disable }} proccess-data='goal_setting34' @if(in_array("goal_setting34",$data, TRUE)) checked @endif
                                                                class="processCheckBox{{$task->id}}goal_setting34{{ $key1 }} processCheckBox{{$task->id}}{{ $key1 }} form-checkbox w-5 h-5 rounded-full text-green-400" 
                                                                onclick="processCheckMe({{ $task->id }},{{$key1 }},'goal_setting34',5)">
                                                                <div class="w-full text-sm ml-2 mt-1">
                                                                    <label for="processCheckBox{{$task->id}}goal_setting34{{ $key1 }}" class="cursor-pointer mb-0">
                                                                        Goal Setting Follow up 2
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                            <div class="flex w-full py-2 mb-1">
                                                                <input id="processCheckBox{{$task->id}}goal_setting35{{ $key1 }}" type="checkbox" {{ $disable }} proccess-data='goal_setting35' @if(in_array("goal_setting35",$data, TRUE)) checked @endif
                                                                class="processCheckBox{{$task->id}}goal_setting35{{ $key1 }} processCheckBox{{$task->id}}{{ $key1 }} form-checkbox w-5 h-5 rounded-full text-green-400" 
                                                                onclick="processCheckMe({{ $task->id }},{{$key1 }},'goal_setting35',5)">
                                                                <div class="w-full text-sm ml-2 mt-1">
                                                                    <label for="processCheckBox{{$task->id}}goal_setting35{{ $key1 }}" class="cursor-pointer mb-0">
                                                                        Progression session
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endif

                                                @if (strtoupper($project_column) == strtoupper('NUTRITIONAL JOURNAL 3'))
                                                @php
                                                    if($epic_process->sales_group != '' && count($data) == $epic_process->total_sales){
                                                        $visibility = "checked";
                                                    }else{
                                                        $visibility = "";
                                                    }
                                                @endphp
                                                <div class="pl-2 pb-1 border-b border-gray-200 mb-1 mt-1 gray-bg">
                                                    <div class="flex items-center rounded-lg">
                                                        <div class="mr-2">
                                                            <input type="checkbox" {{ $visibility }} {{ $disable }} class="form-checkbox w-6 h-6 rounded-full text-green-400 check-column1" task-id="{{ $task->id }}" column-id="{{ $key1 }}" data-from="other" total-sales="3">
                                                        </div>
                                                        <a data-toggle="collapse" data-parent="#accordion" href="#collapse{{$key1}}{{$task->id}}" class="pt-2"> {{ $project_column }}</a>
                                                    </div>
                                                </div>
                                                <div id="collapse{{$key1}}{{$task->id}}"  class="collapse">
                                                    <div class="row pl-4 py-2 mb-1 rounded-lg">
                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">                                                                                        
                                                            <div class="flex w-full py-2 mb-1">
                                                                <input id="processCheckBox{{$task->id}}nutrition_journal31{{ $key1 }}" type="checkbox" {{ $disable }} proccess-data='nutrition_journal31' @if(in_array("nutrition_journal31",$data, TRUE)) checked @endif
                                                                class="processCheckBox{{$task->id}}nutrition_journal31{{ $key1 }} processCheckBox{{$task->id}}{{ $key1 }} form-checkbox w-5 h-5 rounded-full text-green-400" 
                                                                onclick="processCheckMe({{ $task->id }},{{$key1 }},'nutrition_journal31',3)">
                                                                <div class="w-full text-sm ml-2 mt-1">
                                                                    <label for="processCheckBox{{$task->id}}nutrition_journal31{{ $key1 }}" class="cursor-pointer mb-0">
                                                                        Nutritional Journal 2
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">                                                                                        
                                                            <div class="flex w-full py-2 mb-1">
                                                                <input id="processCheckBox{{$task->id}}nutrition_journal32{{ $key1 }}" type="checkbox" {{ $disable }} proccess-data='nutrition_journal32' @if(in_array("nutrition_journal32",$data, TRUE)) checked @endif
                                                                class="processCheckBox{{$task->id}}nutrition_journal32{{ $key1 }} processCheckBox{{$task->id}}{{ $key1 }} form-checkbox w-5 h-5 rounded-full text-green-400" 
                                                                onclick="processCheckMe({{ $task->id }},{{$key1 }},'nutrition_journal32',3)">
                                                                <div class="w-full text-sm ml-2 mt-1">
                                                                    <label for="processCheckBox{{$task->id}}nutrition_journal32{{ $key1 }}" class="cursor-pointer mb-0">
                                                                        Journal follow up 1
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">                                                                                        
                                                            <div class="flex w-full py-2 mb-1">
                                                                <input id="processCheckBox{{$task->id}}nutrition_journal33{{ $key1 }}" type="checkbox" {{ $disable }} proccess-data='nutrition_journal33' @if(in_array("nutrition_journal33",$data, TRUE)) checked @endif
                                                                class="processCheckBox{{$task->id}}nutrition_journal33{{ $key1 }} processCheckBox{{$task->id}}{{ $key1 }} form-checkbox w-5 h-5 rounded-full text-green-400" 
                                                                onclick="processCheckMe({{ $task->id }},{{$key1 }},'nutrition_journal33',3)">
                                                                <div class="w-full text-sm ml-2 mt-1">
                                                                    <label for="processCheckBox{{$task->id}}nutrition_journal33{{ $key1 }}" class="cursor-pointer mb-0">
                                                                        Journal follow up 2
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endif

                                                @endif
                                                </div>
                                                @endforeach
                                            @endif
                                        {{--  --}}
                                        </div>
                                    </div>
                                </section>
                                </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@endforeach

    @stop

    @section('required-script-for-this-page')
    {!! Html::script('assets/js/jquery-ui.min.js') !!}

    <!-- {!! Html::script('vendor/moment/moment.min.js') !!}
    {!! Html::script('vendor/moment/moment-timezone-with-data.js') !!}
    {!! Html::script('assets/js/set-moment-timezone.js?v='.time()) !!} -->

    <!-- start: jquery validation -->
    {{-- {!! Html::script('vendor/jquery-validation/jquery.validate.min.js') !!} --}}
    <!-- end: jquery validation -->

    <!-- start: Bootstrap Select Master -->
    {{-- {!! Html::script('vendor/bootstrap-select-master/js/bootstrap-select.min.js') !!} --}}
    <!-- end: Bootstrap Select Master -->

    <!-- start: Bootstrap timepicker -->
    {{-- <!--{!! Html::script('vendor/bootstrap-datetimepicker/bootstrap-datetimepicker.min.js') !!}--> --}}
    <!-- end: Bootstrap timepicker -->
    
    <!-- start: Country Code Selector -->
    {{-- {!! Html::script('assets/plugins/intl-tel-input-master/build/js/utils.js') !!} --}}
    {{-- {!! Html::script('assets/plugins/intl-tel-input-master/build/js/intlTelInput.js') !!} --}}
    <!-- end: Country Code Selector -->

    <!-- start: Bootstrap Typeahead -->
    {{-- {!! Html::script('assets/plugins/bootstrap3-typeahead.min.js') !!} --}}
    <!-- end: Bootstrap Typeahead -->

    <!-- start: Full Calendar -->
    <!-- {!! Html::script('assets/plugins/fullcalendar-2.9.1/fullcalendar.min.js') !!} -->
    <!-- end: Full Calendar -->

    <!-- start: Sweet Alert -->
    {!! Html::script('vendor/sweetalert/sweet-alert.min.js') !!}
    <!-- end: Sweet Alert -->

    <!-- start: Dirty Form -->
    {{-- {!! Html::script('assets/js/dirty-form.js?v='.time()) !!} --}}
    <!-- end: Dirty Form -->

    {{-- {!! Html::script('assets/js/helper.js?v='.time()) !!} --}}

    <!-- start: Events -->
    {!! Html::script('/js/app.js?id=652569a003aa16284bf7') !!}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>

  <!-- <script src="{{asset('assets/js/events.js?v='.time())}}"></script> -->
    <script src="{{asset('/js/project.js?v='.time())}}"></script>
	<!-- end: Events -->
<script src='https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.17.1/moment.min.js'></script>

	<!-- start: Full Calendar Custom Script -->
	<script src='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.1.0/fullcalendar.min.js'></script>

	<!-- end: Full Calendar Custom Script -->   

  <script>
    var comment_attachments_array = [];
    var staff_member_id=[];
    var remove_member_id = [];
    var attachment_task_id;
    var session_data = $('#session-data').val();
    $(document).ready(function() {
        calendar = $('#calendar');
            $('#calendar').fullCalendar({
            customButtons: {
					add: {
						text: ''
					},
					filter: {
						text: ''
					},
					titleDatepicker: {
						text: '',
						click: function(){
							$('#datepicker').datepicker("show");
						}
					},
					jumpBack: {
						icon: 'left-double-arrow',
						click: function(e){
							e.stopPropagation();
							$("#jumpBack .calJumper > a").dropdown("toggle");
						}
					},
					jumpforw: {
						icon: 'right-double-arrow',
						click: function(e){
							e.stopPropagation();
							$("#jumpforw .calJumper > a").dropdown("toggle");
						}
					}
				},
				
            header: {
                left: 'prev,jumpBack,today,title,titleDatepicker,jumpforw,next', 
                center: '',
                right: '',
            },
            editable : true,
            events : [
                @foreach($tasks as $task)
                {
                  id : '{{$task->id}}',
                  title : '{{ $task->parent->clients->firstname }} {{ $task->parent->clients->lastname }} \n {{ $task->content }}' ,                
                  start : '{{ $task->due_date }}', 
                  color : '#63B3ED !important',       
                  textcolor : '#63B3ED !important'
                },
                //   @if(count($task->child) > 0)
                //   @foreach($task->child as $subtask)
                //   {
                //   id : '{{$task->id}}',
                //   title : '{{ $task->parent->clients->firstname }} {{ $task->parent->clients->lastname }} \n {{ $subtask->content }}' ,                
                //   start : '{{ $subtask->due_date }}', 
                //   color : '#63B3ED !important',       
                //   textcolor : '#63B3ED !important',
                //   },
                //   @endforeach
                //   @endif
                
                @endforeach
            ],
            eventClick: function(event) {
              due_date_id = event.id;
              if (event.id) 
              {
                $("#task-popup"+event.id).show();
                
              }
            },
            views: {
				month: { 
					titleFormat: 'MMM YYYY'
				}
            },
            eventAfterAllRender: function(view){
				var titleDatepicker = calendar.find('.fc-titleDatepicker-button');
				titleDatepicker.html(calendar.find('h2').text());

				titleDatepicker.closest('div').addClass('fc-date-picker-btn custom-calendar-width');
				titleDatepicker.closest('.fc-center').addClass('custom-parent');

				if(!calendar.find('#datepicker').length){
					$('<input type="hidden" id="datepicker">').insertBefore(titleDatepicker);
					$('#datepicker').datepicker({
						numberOfMonths: 2,
						onSelect: function (dateText, inst){
							calendar.fullCalendar('gotoDate', new Date(dateText));
						}
					});
				}

                
				if(!calendar.find('.calJumper').length){
					var jumper = $(".calJumper");
					var jumperHtml = jumper.prop('outerHTML');
					$('<div id="jumpBack">'+jumperHtml+'</div>').insertBefore(".fc-jumpBack-button");
					$('<div id="jumpforw">'+jumperHtml+'</div>').insertBefore(".fc-jumpforw-button");
					jumper.remove();
					$(".fc-jumpBack-button").attr({
						"rel": "tooltip",
						"title": "Jump back"
					}).addClass('epic-tooltip')
					$(".fc-jumpforw-button").attr({
						"rel": "tooltip",
						"title": "Jump forward"
					}).addClass('epic-tooltip')
				}
			}
			
        })
        $(document).on('click', '.filter-date', function(e){
            e.preventDefault();
			var jumpAmount = $(this).data('jump-amount');
			jumpUnit = $(this).data('jump-unit');
			operation = $(this).closest('.calJumper').parent().attr('id');
            datepicker = calendar.find('#datepicker');
			datepickerVal = datepicker.val();
			if(datepicker.length && datepickerVal != '' && datepickerVal != null)
				var momentt = moment(datepickerVal, "MM/DD/YYYY");
			else
				var momentt = moment();
			
			if(operation == 'jumpforw')
				calendar.fullCalendar('gotoDate', momentt.add(jumpAmount, jumpUnit))
			else if(operation == 'jumpBack')
				calendar.fullCalendar('gotoDate', momentt.subtract(jumpAmount, jumpUnit))
        })

        $(document).on('click', '.fc-next-button,.fc-prev-button', function () {
            localStorage.setItem('savedMonth',$('#calendar').fullCalendar('getView').intervalStart._d);
        });
        if(localStorage.getItem('savedMonth')!=null){
                $('#calendar').fullCalendar('gotoDate',localStorage.getItem('savedMonth'));
        }

        $(".dueDate").flatpickr({
            // mode: 'single',
            dateFormat: "Y-m-d",
            disableMobile: "true",
            defaultDate: due_date_value,
            onChange: function(selectedDates, dateStr, instance) {
                var site_url = '/pipeline-process/task/dueDate';
                var date = dateStr;
                var id = due_date_id;
                var csrf = $("#token").val();
                $.ajax({
                    type: "Post",
                    url: site_url,
                    data: {'id':id,'duedate':date,'csrf':csrf},
                    success: function(data)
                    {
                        var html = '';
                        html += '<svg viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 fill-current mr-1.5">'
                        html += '<path d="M1 4c0-1.1.9-2 2-2h14a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V4zm2 2v12h14V6H3zm2-6h2v2H5V0zm8 0h2v2h-2V0zM5 9h2v2H5V9zm0 4h2v2H5v-2zm4-4h2v2H9V9zm0 4h2v2H9v-2zm4-4h2v2h-2V9zm0 4h2v2h-2v-2z"></path>'
                        html += '</svg>'
                        html += data.duedate
                        // $("#due-date"+id).html(data.duedate);
                        $(".real-time-duedate-"+id).html(html);
                        $("#due-date-"+id).html(data.duedate);
                        $(".hidden-div-"+id).show();
                        // sessionStorage.removeItem('due_date_value');
                    }
                });  
                
            }
        });
    });

    $(document).on('click','.assignSubTask-1', function(e) { 
        e.preventDefault();
        e.stopPropagation();
        $(this).parent().siblings('.assignSubTaskPopup-1').toggle();
    });    
    $(document).on('click','.assignSubTaskPopup-1', function(e) {
        e.stopPropagation();
    });
    $(document).on('click','body', function() {
        $('.assignSubTaskPopup-1').hide();
    });
  
  </script>
	
	@stop()

	@section('script-handler-for-this-page')
	@stop()

	@section('script-after-page-handler')
	@stop()