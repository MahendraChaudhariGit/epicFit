@php
    use App\Models\PipelineProcess\EpicProcess;
    use App\Clients;
    use App\CalendarSetting;
@endphp
<div class="modal-dialog">
                            
    <!-- Modal content-->
    <div class="modal-content">
        <div class="modal-header">
        @php
            $client = App\Clients::select('firstname','lastname','id','sale_process_setts')->where('id',$task->content)->first();
        @endphp
        <button type="button" class="close cancel{{$column->id}}{{$task->id}}">&times;</button>
            <h4 class="mb-2"></h4>
        </div>
        <div class="modal-body">
            
            @php
                $epic_process = EpicProcess::where('pipeline_process_task_id',$task->id)->where('column_id',$column->id)->first();
                $data = explode(',',$epic_process->sales_group);
            @endphp
                        @php
                            // $client = App\Clients::select('id','sale_process_setts','firstname','lastname')->where('id',$task->content)->first();
                            $calendar_setting = App\CalendarSetting::select('cs_client_id','sales_process_settings')->where('cs_business_id',Session::get('businessId'))->select('sales_process_settings')->first();
                            if($client->sale_process_setts != null || $client->sale_process_setts != ''){
                                $json = (json_decode($client->sale_process_setts));
                            }else{
                                $json = (json_decode($calendar_setting->sales_process_settings));
                            }

                            // if($column->name != "COMPLIMENTARY TRAINING"){
                            //     $disable = 'disabled';
                            // }else{
                            //     $disable = '';
                            // }
                            $total = $epic_process->sales_group != null && $epic_process->sales_group != '' ? count($data) : 0;
                            
                        @endphp
                        <script>
                            @if($epic_process->sales_group == '')
                                $(".progress-inner"+'{{  $column->id  }}'+'{{  $epic_process->pipeline_process_task_id  }}').removeAttr("style").attr("style","display:none");
                            @endif
                         </script>
                        @if(strtoupper($column->name) == strtoupper("COMPLIMENTARY TRAINING"))
                        <h4 class="text-center mb-5">Are you sure you want to move {{ $client->firstname}} {{ $client->lastname}} from {{$column->name}} to <span id="second-column-name{{ $column->id }}{{ $task->id }}"></span>? 
                            @if(count($data) != ($json->teamCount ? $json->teamCount : 0) + ($json->indivCount ? $json->indivCount : 0) + 1) <br>Below are not completed.</h4> @endif
                        {{-- @if($json->teamCount > 0 || $json->indivCount > 0) --}}
                        @if ($json->teamCount > 0)
                        <div class="flex flex-col mb-4">
                            @for ($i = 1; $i <= $json->teamCount; $i++)
                            @php
                                // $data = explode(',',$task->sales_group);
                                if (in_array("team$i",$data, TRUE)) {
                                    $check = 'hidden';
                                }else{
                                    $check = '';
                                }
                            @endphp
                            <div class="flex items-center px-4 py-2 mb-1 rounded-lg {{$check}}">
                                <div>
                                    <input type="checkbox" class="completeProccess{{ $column->id }}{{ $task->id }} form-checkbox w-5 h-5 rounded-full text-green-400"
                                    column-id="{{ $column->id }}" data-from="other" task-id = "{{ $task->id }}" proccess ='team{{ $i }}' total-sales="{{($json->teamCount ? $json->teamCount : 0) + ($json->indivCount ? $json->indivCount : 0) + 1}}">
                                </div>
                                
                                <div class="pl-3 w-full">
                                    <div class="w-full text-sm">
                                        <div class="cursor-pointer mb-0">
                                            TEAM {{$i}} 
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endfor
                        </div>
                        @endif

                        @if ($json->indivCount > 0)
                        <div class="flex flex-col mb-4 ml-4">
                            @for ($i = 1; $i <= $json->indivCount; $i++)
                            @php
                                // $data = explode(',',$task->sales_group);
                                if (in_array("indiv$i",$data, TRUE)) {
                                    $check = 'hidden';
                                }else{
                                    $check = '';
                                }
                            @endphp
                            <div class="flex items-center py-2 mb-1 rounded-lg {{$check}}">
                                
                                <div>
                                    <input type="checkbox" class="completeProccess{{ $column->id }}{{ $task->id }} form-checkbox w-5 h-5 rounded-full text-green-400" 
                                    column-id="{{ $column->id }}" data-from="other" task-id = "{{ $task->id }}" proccess ='indiv{{ $i }}' total-sales="{{($json->teamCount ? $json->teamCount : 0) + ($json->indivCount ? $json->indivCount : 0) + 1}}">
                                </div>
                                
                                <div class="pl-3 w-full">
                                    <div class="w-full text-sm">
                                        <div class="cursor-pointer mb-0">
                                            INDIVIDUAL {{ $i }}
                                        </div>
                                    </div>
                                </div>
                                
                            </div>
                            @endfor
                        </div>
                        @endif
                        @php
                            // $data = explode(',',$task->sales_group);
                            if (in_array("complementary_teams",$data, TRUE)) {
                                $check11 = 'hidden';
                            }else{
                                $check11 = '';
                            }
                        @endphp
                        <div class="flex items-center px-4 py-2 mb-1 rounded-lg {{$check11}}">
                            <div>
                                <input type="checkbox" class="completeProccess{{ $column->id }}{{ $task->id }} form-checkbox w-5 h-5 rounded-full text-green-400"
                                column-id="{{ $column->id }}" data-from="other" task-id = "{{ $task->id }}" proccess ='complementary_teams' total-sales="{{($json->teamCount ? $json->teamCount : 0) + ($json->indivCount ? $json->indivCount : 0) + 1}}">
                            </div>
                            
                            <div class="pl-3 w-full">
                                <div class="w-full text-sm">
                                    <div class="cursor-pointer mb-0">
                                        Complementary Teams
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- @endif --}}

                        @endif
                       
                        @if (strtoupper($column->name) == strtoupper('CONSULTATION'))
                        <h4 class="text-center mb-5">Are you sure you want to move {{ $client->firstname}} {{ $client->lastname}} from {{$column->name}} to <span id="second-column-name{{ $column->id }}{{ $task->id }}"></span>? 
                            @if(count($data) != 3) <br>Below are not completed.</h4> @endif
                            <div class="row items-center px-4 py-2 mb-1 rounded-lg">
                                <div class="flex col-lg-6 col-md-6 col-xs-12 py-2 @if(in_array("consulation1",$data, TRUE)) hidden @endif">
                                    <input type="checkbox" class="completeProccess{{ $column->id }}{{ $task->id }} form-checkbox w-5 h-5 rounded-full text-green-400" 
                                    column-id="{{ $column->id }}" data-from="other" task-id = "{{ $task->id }}" proccess ='consulation1' total-sales="3">
                                <div class="pl-3 pt-1">
                                    <div class="text-sm">
                                        <div class="cursor-pointer mb-0">
                                            Consultation
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="flex col-lg-6 col-md-6 col-xs-12 py-2 @if(in_array("consulation2",$data, TRUE)) hidden @endif">
                                <input type="checkbox" class="completeProccess{{ $column->id }}{{ $task->id }} form-checkbox w-5 h-5 rounded-full text-green-400" 
                                column-id="{{ $column->id }}" data-from="other" task-id = "{{ $task->id }}" proccess ='consulation2' total-sales="3">
                            <div class="pl-3 pt-1">
                                <div class="text-sm">
                                    <div class="cursor-pointer mb-0">
                                        Movement Analysis
                                    </div>
                                </div>
                            </div>
                        </div>
                            <div class="flex col-lg-6 col-md-6 col-xs-12 py-2 @if(in_array("consulation3",$data, TRUE)) hidden @endif">
                                    <input type="checkbox" class="completeProccess{{ $column->id }}{{ $task->id }} form-checkbox w-5 h-5 rounded-full text-green-400" 
                                    column-id="{{ $column->id }}" data-from="other" task-id = "{{ $task->id }}" proccess ='consulation3' total-sales="3">
                                <div class="pl-3 pt-1">
                                    <div class="text-sm">
                                        <div class="cursor-pointer mb-0">
                                            Introduction to movement
                                        </div>
                                    </div>
                                </div>
                            </div>
                            </div>
                        @endif
                        @if (strtoupper($column->name) == strtoupper('BENCHMARK'))
                        <h4 class="text-center mb-5">Are you sure you want to move {{ $client->firstname}} {{ $client->lastname}} from {{$column->name}} to <span id="second-column-name{{ $column->id }}{{ $task->id }}"></span>? 
                            @if(count($data) != 2) <br>Below are not completed.</h4> @endif
                            <div class="row items-center px-4 py-2 mb-1 rounded-lg">
                                <div class="flex col-lg-6 col-md-6 col-xs-12 py-2 @if(in_array("benchmark1",$data, TRUE)) hidden @endif">
                                    <input id="benchmark1Benchmark" type="checkbox" class="completeProccess{{ $column->id }}{{ $task->id }} form-checkbox w-5 h-5 rounded-full text-green-400" 
                                    column-id="{{ $column->id }}" data-from="other" task-id = "{{ $task->id }}" proccess ='benchmark1' total-sales="2">
                                <div class="pl-3 pt-1">
                                    <div class="text-sm">
                                        <label for="benchmark1Benchmark" class="cursor-pointer mb-0">
                                            Benchmark
                                        </label>
                                    </div>
                                </div>
                                </div>
                                <div class="flex col-lg-6 col-md-6 col-xs-12 py-2 @if(in_array("benchmark2",$data, TRUE)) hidden @endif">
                                    <input id="benchmark2movement" type="checkbox" class="completeProccess{{ $column->id }}{{ $task->id }} form-checkbox w-5 h-5 rounded-full text-green-400" 
                                    column-id="{{ $column->id }}" data-from="other" task-id = "{{ $task->id }}" proccess ='benchmark2' total-sales="2">
                                <div class="pl-3 pt-1">
                                    <div class="text-sm">
                                        <label for="benchmark2movement" class="cursor-pointer mb-0">
                                            Introduction to movement
                                        </label>
                                    </div>
                                </div>
                                </div>
                            </div>
                        @endif
                        @if (strtoupper($column->name) == strtoupper('POSTURE ANALYSIS 1'))
                        <h4 class="text-center mb-5">Are you sure you want to move {{ $client->firstname}} {{ $client->lastname}} from {{$column->name}} to <span id="second-column-name{{ $column->id }}{{ $task->id }}"></span>? 
                            @if(count($data) != 2) <br>Below are not completed.</h4> @endif
                            <div class="row items-center px-4 py-2 mb-1 rounded-lg">
                                <div class="flex col-lg-6 col-md-6 col-xs-12 py-2 @if(in_array("posture_analysis11",$data, TRUE)) hidden @endif">
                                    <input type="checkbox" class="completeProccess{{ $column->id }}{{ $task->id }} form-checkbox w-5 h-5 rounded-full text-green-400" 
                                    column-id="{{ $column->id }}" data-from="other" task-id = "{{ $task->id }}" proccess ='posture_analysis11' total-sales="2">
                                <div class="pl-3 pt-1">
                                    <div class="text-sm">
                                        <div class="cursor-pointer mb-0">
                                            Posture Analysis
                                        </div>
                                    </div>
                                </div>
                                </div>
                                <div class="flex col-lg-6 col-md-6 col-xs-12 py-2 @if(in_array("posture_analysis12",$data, TRUE)) hidden @endif">
                                    <input type="checkbox" class="completeProccess{{ $column->id }}{{ $task->id }} form-checkbox w-5 h-5 rounded-full text-green-400" 
                                    column-id="{{ $column->id }}" data-from="other" task-id = "{{ $task->id }}" proccess ='posture_analysis12' total-sales="2">
                                <div class="pl-3 pt-1">
                                    <div class="text-sm">
                                        <div class="cursor-pointer mb-0">
                                            Stretch Program
                                        </div>
                                    </div>
                                </div>
                                </div>
                            </div>
                        @endif
                        @if (strtoupper($column->name) == strtoupper('POSTURE ANALYSIS 2'))
                        <h4 class="text-center mb-5">Are you sure you want to move {{ $client->firstname}} {{ $client->lastname}} from {{$column->name}} to <span id="second-column-name{{ $column->id }}{{ $task->id }}"></span>? 
                            @if(count($data) != 2) <br>Below are not completed.</h4> @endif
                            <div class="row items-center px-4 py-2 mb-1 rounded-lg">
                                <div class="flex col-lg-6 col-md-6 col-xs-12 py-2 @if(in_array("posture_analysis21",$data, TRUE)) hidden @endif">
                                    <input type="checkbox" class="completeProccess{{ $column->id }}{{ $task->id }} form-checkbox w-5 h-5 rounded-full text-green-400" 
                                    column-id="{{ $column->id }}" data-from="other" task-id = "{{ $task->id }}" proccess ='posture_analysis21' total-sales="2">
                                <div class="pl-3 pt-1">
                                    <div class="text-sm">
                                        <div class="cursor-pointer mb-0">
                                            Posture Analysis
                                        </div>
                                    </div>
                                </div>
                                </div>
                                <div class="flex col-lg-6 col-md-6 col-xs-12 py-2 @if(in_array("posture_analysis22",$data, TRUE)) hidden @endif">
                                    <input type="checkbox" class="completeProccess{{ $column->id }}{{ $task->id }} form-checkbox w-5 h-5 rounded-full text-green-400" 
                                    column-id="{{ $column->id }}" data-from="other" task-id = "{{ $task->id }}" proccess ='posture_analysis22' total-sales="2">
                                <div class="pl-3 pt-1">
                                    <div class="text-sm">
                                        <div class="cursor-pointer mb-0">
                                            Stretch Program
                                        </div>
                                    </div>
                                </div>
                                </div>
                            </div>
                        @endif
                        @if (strtoupper($column->name) == strtoupper('SUB MAX TESTING'))
                        <h4 class="text-center mb-5">Are you sure you want to move {{ $client->firstname}} {{ $client->lastname}} from {{$column->name}} to <span id="second-column-name{{ $column->id }}{{ $task->id }}"></span>? 
                            @if(count($data) != 1) <br>Below are not completed.</h4> @endif
                            <div class="row items-center px-4 py-2 mb-1 rounded-lg">
                                <div class="flex col-lg-6 col-md-6 col-xs-12 py-2 @if(in_array("sub_max_test",$data, TRUE)) hidden @endif">
                                    <input type="checkbox" class="completeProccess{{ $column->id }}{{ $task->id }} form-checkbox w-5 h-5 rounded-full text-green-400" 
                                    column-id="{{ $column->id }}" data-from="other" task-id = "{{ $task->id }}" proccess ='sub_max_test' total-sales="1">
                                <div class="pl-3 pt-1">
                                    <div class="text-sm">
                                        <div class="cursor-pointer mb-0">
                                            Sub Max Test
                                        </div>
                                    </div>
                                </div>
                                </div>
                                
                            </div>
                        @endif
                        @if (strtoupper($column->name) == strtoupper('GOAL SETTING 1'))
                        <h4 class="text-center mb-5">Are you sure you want to move {{ $client->firstname}} {{ $client->lastname}} from {{$column->name}} to <span id="second-column-name{{ $column->id }}{{ $task->id }}"></span>? 
                            @if(count($data) != 5) <br>Below are not completed.</h4> @endif
                            <div class="row items-center px-4 py-2 mb-1 rounded-lg">
                                <div class="flex col-lg-6 col-md-6 col-xs-12 py-2 @if(in_array("goal_setting11",$data, TRUE)) hidden @endif">
                                    <input type="checkbox" class="completeProccess{{ $column->id }}{{ $task->id }} form-checkbox w-5 h-5 rounded-full text-green-400" 
                                    column-id="{{ $column->id }}" data-from="other" task-id = "{{ $task->id }}" proccess ='goal_setting11' total-sales="5">
                                <div class="pl-3 pt-1">
                                    <div class="text-sm">
                                        <div class="cursor-pointer mb-0">
                                            Goal Setting 1
                                        </div>
                                    </div>
                                </div>
                                </div>
                                <div class="flex col-lg-6 col-md-6 col-xs-12 py-2 @if(in_array("goal_setting12",$data, TRUE)) hidden @endif">
                                    <input type="checkbox" class="completeProccess{{ $column->id }}{{ $task->id }} form-checkbox w-5 h-5 rounded-full text-green-400" 
                                    column-id="{{ $column->id }}" data-from="other" task-id = "{{ $task->id }}" proccess ='goal_setting12' total-sales="5">
                                <div class="pl-3 pt-1">
                                    <div class="text-sm">
                                        <div class="cursor-pointer mb-0">
                                            Sleep Questionnaire
                                        </div>
                                    </div>
                                </div>
                                </div>
                                <div class="flex col-lg-6 col-md-6 col-xs-12 py-2 @if(in_array("goal_setting13",$data, TRUE)) hidden @endif">
                                    <input type="checkbox" class="completeProccess{{ $column->id }}{{ $task->id }} form-checkbox w-5 h-5 rounded-full text-green-400" 
                                    column-id="{{ $column->id }}" data-from="other" task-id = "{{ $task->id }}" proccess ='goal_setting13' total-sales="5">
                                <div class="pl-3 pt-1">
                                    <div class="text-sm">
                                        <div class="cursor-pointer mb-0">
                                            Goal Setting Follow up 1
                                        </div>
                                    </div>
                                </div>
                                </div>
                                <div class="flex col-lg-6 col-md-6 col-xs-12 py-2 @if(in_array("goal_setting14",$data, TRUE)) hidden @endif">
                                    <input type="checkbox" class="completeProccess{{ $column->id }}{{ $task->id }} form-checkbox w-5 h-5 rounded-full text-green-400" 
                                    column-id="{{ $column->id }}" data-from="other" task-id = "{{ $task->id }}" proccess ='goal_setting14' total-sales="5">
                                <div class="pl-3 pt-1">
                                    <div class="text-sm">
                                        <div class="cursor-pointer mb-0">
                                            Goal Setting Follow up 2
                                        </div>
                                    </div>
                                </div>
                                </div>
                                <div class="flex col-lg-6 col-md-6 col-xs-12 py-2 @if(in_array("goal_setting15",$data, TRUE)) hidden @endif">
                                    <input type="checkbox" class="completeProccess{{ $column->id }}{{ $task->id }} form-checkbox w-5 h-5 rounded-full text-green-400" 
                                    column-id="{{ $column->id }}" data-from="other" task-id = "{{ $task->id }}" proccess ='goal_setting15' total-sales="5">
                                <div class="pl-3 pt-1">
                                    <div class="text-sm">
                                        <div class="cursor-pointer mb-0">
                                            Progression session
                                        </div>
                                    </div>
                                </div>
                                </div>
                            </div>
                        @endif
                        @if (strtoupper($column->name) == strtoupper('GOAL SETTING 2'))
                        <h4 class="text-center mb-5">Are you sure you want to move {{ $client->firstname}} {{ $client->lastname}} from {{$column->name}} to <span id="second-column-name{{ $column->id }}{{ $task->id }}"></span>? 
                            @if(count($data) != 6) <br>Below are not completed.</h4> @endif
                        <div class="row items-center px-4 py-2 mb-1 rounded-lg">
                            <div class="flex col-lg-6 col-md-6 col-xs-12 py-2 @if(in_array("goal_setting21",$data, TRUE)) hidden @endif">
                                <input type="checkbox" class="completeProccess{{ $column->id }}{{ $task->id }} form-checkbox w-5 h-5 rounded-full text-green-400" 
                                column-id="{{ $column->id }}" data-from="other" task-id = "{{ $task->id }}" proccess ='goal_setting21' total-sales="6">
                            <div class="pl-3 pt-1">
                                <div class="text-sm">
                                    <div class="cursor-pointer mb-0">
                                        Goal Setting 2
                                    </div>
                                </div>
                            </div>
                            </div>
                            <div class="flex col-lg-6 col-md-6 col-xs-12 py-2 @if(in_array("goal_setting22",$data, TRUE)) hidden @endif">
                                <input type="checkbox" class="completeProccess{{ $column->id }}{{ $task->id }} form-checkbox w-5 h-5 rounded-full text-green-400" 
                                column-id="{{ $column->id }}" data-from="other" task-id = "{{ $task->id }}" proccess ='goal_setting22' total-sales="6">
                            <div class="pl-3 pt-1">
                                <div class="text-sm">
                                    <div class="cursor-pointer mb-0">
                                        Sleep Questionnaire
                                    </div>
                                </div>
                            </div>
                            </div>
                            <div class="flex col-lg-6 col-md-6 col-xs-12 py-2 @if(in_array("goal_setting23",$data, TRUE)) hidden @endif">
                                <input type="checkbox" class="completeProccess{{ $column->id }}{{ $task->id }} form-checkbox w-5 h-5 rounded-full text-green-400" 
                                column-id="{{ $column->id }}" data-from="other" task-id = "{{ $task->id }}" proccess ='goal_setting23' total-sales="6">
                            <div class="pl-3 pt-1">
                                <div class="text-sm">
                                    <div class="cursor-pointer mb-0">
                                        Goal Setting Follow up 1
                                    </div>
                                </div>
                            </div>
                            </div>
                            <div class="flex col-lg-6 col-md-6 col-xs-12 py-2 @if(in_array("goal_setting24",$data, TRUE)) hidden @endif">
                                <input type="checkbox" class="completeProccess{{ $column->id }}{{ $task->id }} form-checkbox w-5 h-5 rounded-full text-green-400" 
                                column-id="{{ $column->id }}" data-from="other" task-id = "{{ $task->id }}" proccess ='goal_setting24' total-sales="6">
                            <div class="pl-3 pt-1">
                                <div class="text-sm">
                                    <div class="cursor-pointer mb-0">
                                        Goal Setting Follow up 2
                                    </div>
                                </div>
                            </div>
                            </div>
                            <div class="flex col-lg-6 col-md-6 col-xs-12 py-2 @if(in_array("goal_setting25",$data, TRUE)) hidden @endif">
                                <input type="checkbox" class="completeProccess{{ $column->id }}{{ $task->id }} form-checkbox w-5 h-5 rounded-full text-green-400" 
                                column-id="{{ $column->id }}" data-from="other" task-id = "{{ $task->id }}" proccess ='goal_setting25' total-sales="6">
                            <div class="pl-3 pt-1">
                                <div class="text-sm">
                                    <div class="cursor-pointer mb-0">
                                        Progression session
                                    </div>
                                </div>
                            </div>
                            </div>
                            <div class="flex col-lg-6 col-md-6 col-xs-12 py-2 @if(in_array("goal_setting26",$data, TRUE)) hidden @endif">
                                <input type="checkbox" class="completeProccess{{ $column->id }}{{ $task->id }} form-checkbox w-5 h-5 rounded-full text-green-400" 
                                column-id="{{ $column->id }}" data-from="other" task-id = "{{ $task->id }}" proccess ='goal_setting26' total-sales="6">
                            <div class="pl-3 pt-1">
                                <div class="text-sm">
                                    <div class="cursor-pointer mb-0">
                                        Movement Analysis	
                                    </div>
                                </div>
                            </div>
                            </div>
                        </div>
                    @endif
                    @if (strtoupper($column->name) == strtoupper('GOAL SETTING 3'))
                    <h4 class="text-center mb-5">Are you sure you want to move {{ $client->firstname}} {{ $client->lastname}} from {{$column->name}} to <span id="second-column-name{{ $column->id }}{{ $task->id }}"></span>? 
                        @if(count($data) != 5) <br>Below are not completed.</h4> @endif
                    <div class="row items-center px-4 py-2 mb-1 rounded-lg">
                        <div class="flex col-lg-6 col-md-6 col-xs-12 py-2 @if(in_array("goal_setting31",$data, TRUE)) hidden @endif">
                            <input type="checkbox" class="completeProccess{{ $column->id }}{{ $task->id }} form-checkbox w-5 h-5 rounded-full text-green-400" 
                            column-id="{{ $column->id }}" data-from="other" task-id = "{{ $task->id }}" proccess ='goal_setting31' total-sales="5">
                        <div class="pl-3 pt-1">
                            <div class="text-sm">
                                <div class="cursor-pointer mb-0">
                                    Goal Setting 3
                                </div>
                            </div>
                        </div>
                        </div>
                        <div class="flex col-lg-6 col-md-6 col-xs-12 py-2 @if(in_array("goal_setting32",$data, TRUE)) hidden @endif">
                            <input type="checkbox" class="completeProccess{{ $column->id }}{{ $task->id }} form-checkbox w-5 h-5 rounded-full text-green-400" 
                            column-id="{{ $column->id }}" data-from="other" task-id = "{{ $task->id }}" proccess ='goal_setting32' total-sales="5">
                        <div class="pl-3 pt-1">
                            <div class="text-sm">
                                <div class="cursor-pointer mb-0">
                                    Sleep Questionnaire
                                </div>
                            </div>
                        </div>
                        </div>
                        <div class="flex col-lg-6 col-md-6 col-xs-12 py-2 @if(in_array("goal_setting33",$data, TRUE)) hidden @endif">
                            <input type="checkbox" class="completeProccess{{ $column->id }}{{ $task->id }} form-checkbox w-5 h-5 rounded-full text-green-400" 
                            column-id="{{ $column->id }}" data-from="other" task-id = "{{ $task->id }}" proccess ='goal_setting33' total-sales="5">
                        <div class="pl-3 pt-1">
                            <div class="text-sm">
                                <div class="cursor-pointer mb-0">
                                    Goal Setting Follow up 1
                                </div>
                            </div>
                        </div>
                        </div>
                        <div class="flex col-lg-6 col-md-6 col-xs-12 py-2 @if(in_array("goal_setting34",$data, TRUE)) hidden @endif">
                            <input type="checkbox" class="completeProccess{{ $column->id }}{{ $task->id }} form-checkbox w-5 h-5 rounded-full text-green-400" 
                            column-id="{{ $column->id }}" data-from="other" task-id = "{{ $task->id }}" proccess ='goal_setting34' total-sales="5">
                        <div class="pl-3 pt-1">
                            <div class="text-sm">
                                <div class="cursor-pointer mb-0">
                                    Goal Setting Follow up 2
                                </div>
                            </div>
                        </div>
                        </div>
                        <div class="flex col-lg-6 col-md-6 col-xs-12 py-2 @if(in_array("goal_setting35",$data, TRUE)) hidden @endif">
                            <input type="checkbox" class="completeProccess{{ $column->id }}{{ $task->id }} form-checkbox w-5 h-5 rounded-full text-green-400" 
                            column-id="{{ $column->id }}" data-from="other" task-id = "{{ $task->id }}" proccess ='goal_setting35' total-sales="5">
                        <div class="pl-3 pt-1">
                            <div class="text-sm">
                                <div class="cursor-pointer mb-0">
                                    Progression session
                                </div>
                            </div>
                        </div>
                        </div>
                    </div>
                @endif
                        @if (strtoupper($column->name) == strtoupper('NUTRITIONAL JOURNAL 1'))
                        <h4 class="text-center mb-5">Are you sure you want to move {{ $client->firstname}} {{ $client->lastname}} from {{$column->name}} to <span id="second-column-name{{ $column->id }}{{ $task->id }}"></span>? 
                            @if(count($data) != 3) <br>Below are not completed.</h4> @endif
                            <div class="row items-center px-4 py-2 mb-1 rounded-lg">
                                <div class="flex col-lg-6 col-md-6 col-xs-12 py-2 @if(in_array("nutrition_journal11",$data, TRUE)) hidden @endif">
                                    <input type="checkbox" class="completeProccess{{ $column->id }}{{ $task->id }} form-checkbox w-5 h-5 rounded-full text-green-400" 
                                    column-id="{{ $column->id }}" data-from="other" task-id = "{{ $task->id }}" proccess ='nutrition_journal11' total-sales="3">
                                <div class="pl-3 pt-1">
                                    <div class="text-sm">
                                        <div class="cursor-pointer mb-0">
                                            Nutritional Journal 1
                                        </div>
                                    </div>
                                </div>
                                </div>
                                <div class="flex col-lg-6 col-md-6 col-xs-12 py-2 @if(in_array("nutrition_journal12",$data, TRUE)) hidden @endif">
                                    <input type="checkbox" class="completeProccess{{ $column->id }}{{ $task->id }} form-checkbox w-5 h-5 rounded-full text-green-400" 
                                    column-id="{{ $column->id }}" data-from="other" task-id = "{{ $task->id }}" proccess ='nutrition_journal12' total-sales="3">
                                <div class="pl-3 pt-1">
                                    <div class="text-sm">
                                        <div class="cursor-pointer mb-0">
                                            Journal follow up 1
                                        </div>
                                    </div>
                                </div>
                                </div>
                                <div class="flex col-lg-6 col-md-6 col-xs-12 py-2 @if(in_array("nutrition_journal13",$data, TRUE)) hidden @endif">
                                    <input type="checkbox" class="completeProccess{{ $column->id }}{{ $task->id }} form-checkbox w-5 h-5 rounded-full text-green-400" 
                                    column-id="{{ $column->id }}" data-from="other" task-id = "{{ $task->id }}" proccess ='nutrition_journal13' total-sales="3">
                                <div class="pl-3 pt-1">
                                    <div class="text-sm">
                                        <div class="cursor-pointer mb-0">
                                            Journal follow up 2
                                        </div>
                                    </div>
                                </div>
                                </div>
                            </div>
                        @endif
                        @if (strtoupper($column->name) == strtoupper('NUTRITIONAL JOURNAL 2'))
                        <h4 class="text-center mb-5">Are you sure you want to move {{ $client->firstname}} {{ $client->lastname}} from {{$column->name}} to <span id="second-column-name{{ $column->id }}{{ $task->id }}"></span>? 
                            @if(count($data) != 3) <br>Below are not completed.</h4> @endif
                            <div class="row items-center px-4 py-2 mb-1 rounded-lg">
                                <div class="flex col-lg-6 col-md-6 col-xs-12 py-2 @if(in_array("nutrition_journal21",$data, TRUE)) hidden @endif">
                                    <input type="checkbox" class="completeProccess{{ $column->id }}{{ $task->id }} form-checkbox w-5 h-5 rounded-full text-green-400" 
                                    column-id="{{ $column->id }}" data-from="other" task-id = "{{ $task->id }}" proccess ='nutrition_journal21' total-sales="3">
                                <div class="pl-3 pt-1">
                                    <div class="text-sm">
                                        <div class="cursor-pointer mb-0">
                                            Nutritional Journal 2
                                        </div>
                                    </div>
                                </div>
                                </div>
                                <div class="flex col-lg-6 col-md-6 col-xs-12 py-2 @if(in_array("nutrition_journal22",$data, TRUE)) hidden @endif">
                                    <input type="checkbox" class="completeProccess{{ $column->id }}{{ $task->id }} form-checkbox w-5 h-5 rounded-full text-green-400" 
                                    column-id="{{ $column->id }}" data-from="other" task-id = "{{ $task->id }}" proccess ='nutrition_journal22' total-sales="3">
                                <div class="pl-3 pt-1">
                                    <div class="text-sm">
                                        <div class="cursor-pointer mb-0">
                                            Journal follow up 1
                                        </div>
                                    </div>
                                </div>
                                </div>
                                <div class="flex col-lg-6 col-md-6 col-xs-12 py-2 @if(in_array("nutrition_journal23",$data, TRUE)) hidden @endif">
                                    <input type="checkbox" class="completeProccess{{ $column->id }}{{ $task->id }} form-checkbox w-5 h-5 rounded-full text-green-400" 
                                    column-id="{{ $column->id }}" data-from="other" task-id = "{{ $task->id }}" proccess ='nutrition_journal23' total-sales="3">
                                <div class="pl-3 pt-1">
                                    <div class="text-sm">
                                        <div class="cursor-pointer mb-0">
                                            Journal follow up 2
                                        </div>
                                    </div>
                                </div>
                                </div>
                            </div>
                        @endif
                        @if (strtoupper($column->name) == strtoupper('NUTRITIONAL JOURNAL 3'))
                        <h4 class="text-center mb-5">Are you sure you want to move {{ $client->firstname}} {{ $client->lastname}} from {{$column->name}} to <span id="second-column-name{{ $column->id }}{{ $task->id }}"></span>? 
                            @if(count($data) != 3) <br>Below are not completed.</h4> @endif
                            <div class="row items-center px-4 py-2 mb-1 rounded-lg">
                                <div class="flex col-lg-6 col-md-6 col-xs-12 py-2 @if(in_array("nutrition_journal31",$data, TRUE)) hidden @endif">
                                    <input type="checkbox" class="completeProccess{{ $column->id }}{{ $task->id }} form-checkbox w-5 h-5 rounded-full text-green-400" 
                                    column-id="{{ $column->id }}" data-from="other" task-id = "{{ $task->id }}" proccess ='nutrition_journal31' total-sales="3">
                                <div class="pl-3 pt-1">
                                    <div class="text-sm">
                                        <div class="cursor-pointer mb-0">
                                            Nutritional Journal 3
                                        </div>
                                    </div>
                                </div>
                                </div>
                                <div class="flex col-lg-6 col-md-6 col-xs-12 py-2 @if(in_array("nutrition_journal32",$data, TRUE)) hidden @endif">
                                    <input type="checkbox" class="completeProccess{{ $column->id }}{{ $task->id }} form-checkbox w-5 h-5 rounded-full text-green-400" 
                                    column-id="{{ $column->id }}" data-from="other" task-id = "{{ $task->id }}" proccess ='nutrition_journal32' total-sales="3">
                                <div class="pl-3 pt-1">
                                    <div class="text-sm">
                                        <div class="cursor-pointer mb-0">
                                            Journal follow up 1
                                        </div>
                                    </div>
                                </div>
                                </div>
                                <div class="flex col-lg-6 col-md-6 col-xs-12 py-2 @if(in_array("nutrition_journal33",$data, TRUE)) hidden @endif">
                                    <input type="checkbox" class="completeProccess{{ $column->id }}{{ $task->id }} form-checkbox w-5 h-5 rounded-full text-green-400" 
                                    column-id="{{ $column->id }}" data-from="other" task-id = "{{ $task->id }}" proccess ='nutrition_journal33' total-sales="3">
                                <div class="pl-3 pt-1">
                                    <div class="text-sm">
                                        <div class="cursor-pointer mb-0">
                                            Journal follow up 2
                                        </div>
                                    </div>
                                </div>
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-success" id="confirm{{$column->id}}{{$task->id}}">Yes</button>
                        <button type="button" class="btn btn-danger cancel{{$column->id}}{{$task->id}}">No</button>
                        {{-- <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button> --}}
                    </div>
                </div>
    
</div>
