<ul>
    @if(isset($projects))
        @foreach ($projects as $project)
       
    <li class="border-b border-gray-100 last:border-0"><a
            href="{{ url('pipeline-process/projects') }}/{{ $project['slug'] }}"
            class="block hover:bg-gray-50 focus:outline-none focus:bg-gray-50 transition duration-150 ease-in-out">
            <div class="px-6 py-4 flex items-center">
                <div class="min-w-0 flex-1 md:grid md:grid-cols-8 md:gap-4">
                    <div class="md:col-span-4">
                        <div class="d-flex">
                            <div class="checkbox clip-check check-primary m-b-0">
                                <input id="project-checkbox{{$project['id']}}" type="checkbox" name="only_admin_manage" value="yes" data-id="{{$project['id']}}" class="project_checkbox">
                                <label for="project-checkbox{{$project['id']}}">
                             </label>
                         </div>
                         <div>
                            <div class="text-sm leading-5 font-medium text-blue-600 truncate">
                                {{ $project['name'] }}
                            </div>
                            <div class="mt-2 flex">
                                <div
                                class="flex items-center text-sm leading-5 text-gray-500 truncate">
                                <span>{{ $project['description'] }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                    </div>
                    @php
                        $pendingTask = App\Models\PipelineProcess\PipelineProcessTask::where('task_id',$project['columns.pipeline_process_tasks']['id'])->where('completed_at',NULL)->where('index','!=',0)->get()->toArray();
                        // dd($pendingTask);
                        $no_of_days = 0;
                        $start_date = new DateTime($project['start_date']);
                        $end_date = new DateTime($project['end_date']);
                        $no_of_days = $end_date->diff($start_date)->format("%a");
                    @endphp
                    <div class="mt-2 md:col-span-2 sm:mt-0">
                        <div>
                            <div class="flex items-center text-sm leading-5 text-gray-500"><svg
                                    viewBox="0 0 20 20"
                                    class="flex-shrink-0 mr-1.5 h-4 w-4 text-gray-400 fill-current">
                                    <path
                                        d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zm12.73-1.41A8 8 0 1 0 4.34 4.34a8 8 0 0 0 11.32 11.32zM6.7 9.29L9 11.6l4.3-4.3 1.4 1.42L9 14.4l-3.7-3.7 1.4-1.42z">
                                    </path>
                                </svg> <span class="font-medium">@if(!empty($pendingTask)) {{ $pendingTask }} @else 0 @endif</span>&nbsp;Tasks Left
                            </div>
                            <div class="mt-2 flex items-center text-sm leading-5 text-gray-500"><svg
                                    viewBox="0 0 20 20"
                                    class="flex-shrink-0 mr-1.5 h-4 w-4 text-gray-400 fill-current">
                                    <path
                                        d="M1 4c0-1.1.9-2 2-2h14a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V4zm2 2v12h14V6H3zm2-6h2v2H5V0zm8 0h2v2h-2V0zM5 9h2v2H5V9zm0 4h2v2H5v-2zm4-4h2v2H9V9zm0 4h2v2H9v-2zm4-4h2v2h-2V9zm0 4h2v2h-2v-2z">
                                    </path>
                                </svg> 
                                <span v-if="project.days_left && project.days_left > 0">
                                    <span class="font-medium">{{ $no_of_days }}</span>&nbsp;Days left
                               </span>

                               {{-- <span v-else-if="project.days_left === 0" class="text-red-700">
                                    Overdue today
                               </span>

                               <span v-else-if="project.days_left && project.days_left < 0" class="text-red-700">
                                   <span class="font-medium">{{ project.days_left * -1 }}</span> days overdue
                               </span>

                               <span v-else>
                                   -
                               </span> --}}
                            </div>
                        </div>
                    </div>
                    <div class="mt-2 flex items-center md:col-span-1 sm:mt-0">
                        @if($project['completed_at'] != NULL)
                        <span class="badge badge-green">Completed</span>
                        @else
                        @if($project['start_date'] > date('Y-m-d'))
                        <span class="badge badge-yellow">Upcoming</span>
                        @elseif (($project['start_date'] <= date('Y-m-d')) && ($project['end_date'] > date('Y-m-d')))
                        <span class="badge badge-blue">Ongoing</span>
                        @elseif($project['end_date'] <= date('Y-m-d'))
                        <span class="badge badge-red">Overdue</span>
                        @endif
                        @endif
                        
                        {{-- <span class="badge" v-else-if="project.status === 'archived'">Archived</span> --}}
                    </div>
                    <div class="mt-4 flex-shrink-0 flex items-center sm:mt-0">
                        <div class="avatar-group">

                               <?php 
                               $projectMembers = App\Models\PipelineProcess\ProjectMember::where('project_id',$project['id'])->get();
                                if(count($projectMembers) > 0){
                                    foreach ($projectMembers as $key => $projectMember) {
                                        $staff = App\Staff::where('id',$projectMember->member_id)->first();
                                ?>
                                        <img src="{{url('uploads/thumb_')}}{{$staff->profile_picture}}"
                                        alt="{{$staff->first_name}} {{$staff->last_name}}" title="{{$staff->first_name}} {{$staff->last_name}}" class="avatar avatar-xs">
                                <?php 
                                    }
                                }
                                ?>
                                
                        </div>
                    </div>
                </div>
                <div class="ml-5 flex-shrink-0">
                    <svg viewBox="0 0 20 20"
                        class="h-5 w-5 text-gray-400 fill-current">
                        <path fill-rule="evenodd"
                            d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                            clip-rule="evenodd"></path>
                    </svg>
                </div>
            </div>
        </a></li>
        @endforeach
        @endif
</ul>
{{ $projects->links() }}