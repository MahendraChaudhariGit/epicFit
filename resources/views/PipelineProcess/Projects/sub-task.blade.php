<input type="hidden" id="total-sub-task-{{ $parent_task_id }}" value="{{ count($completeTasks)+count($pendingTasks) }}">
<input type="hidden" id="complete-sub-task-{{ $parent_task_id }}" value="{{ count($completeTasks) }}">
@if (count($pendingTasks) > 0)
    <div class="pb-2 border-b border-gray-200 mb-1">
        <h4 class="font-medium text-lg">Todo ({{ count($pendingTasks) }})</h4>
    </div>
    @foreach ($pendingTasks as $key => $subTask)
        <div class="flex items-center px-4 py-2 mb-1 hover:bg-gray-100 rounded-lg tasktablelist">
            <div>
                <input type="hidden" class="sub-Task-Id" value="{{ $subTask['id'] }}">
                <input type="checkbox" class="subTaskComplete form-checkbox w-6 h-6 rounded-full text-green-400" main-task-id="{{ $parent_task_id }}" total-sub-task="{{ count($completeTasks)+count($pendingTasks) }}" complete-sub-task="{{ count($completeTasks) }}"
                    @if ($subTask['completed_at'] != null) checked
    @endif>
    </div>
    <div class="pl-3 w-full">

        <div class="hideSubTask w-full text-sm">
            <div class="cursor-pointer">
                {{ $subTask['content'] }}
            </div>
        </div>
        <div class="editSubTask mt-1 flex rounded-md shadow-sm">
            <div class="relative flex-grow focus-within:z-10">
                <input
                    class="subtaskname form-input block w-full rounded-none rounded-l-md transition ease-in-out duration-150 sm:text-sm sm:leading-5"
                    value="{{ $subTask['content'] }}">
                <input type="hidden" class="subtaskid" value="{{ $subTask['id'] }}">
            </div>
            <span class="btn-group">
                <button type="button" class="hideSubTaskButton btn btn-white rounded-l-none border-l-0">
                    <svg fill="currentColor" viewBox="0 0 20 20" class="h-4 w-4 text-gray-400">
                        <path
                            d="M10 8.586L2.929 1.515 1.515 2.929 8.586 10l-7.071 7.071 1.414 1.414L10 11.414l7.071 7.071 1.414-1.414L11.414 10l7.071-7.071-1.414-1.414L10 8.586z">
                        </path>
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
                    <a href="javascript:void(0)" @if ($subTask['due_date'] != '0000-00-00') style="color:black" @endif title="{{ $subTask['due_date'] }}" sub-task-id="{{ $subTask['id'] }}"
                        class="sub-task-duedate ml-1 flex items-center justify-center rounded-full overflow-hidden text-gray-400 border w-6 h-6
                        overflow-hidden hover:text-gray-500 hover:bg-gray-50"><svg viewBox="0 0 20 20" fill="currentColor" class="w-3 h-3">
                            <path
                                d="M1 4c0-1.1.9-2 2-2h14a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V4zm2 2v12h14V6H3zm2-6h2v2H5V0zm8 0h2v2h-2V0zM5 9h2v2H5V9zm0 4h2v2H5v-2zm4-4h2v2H9V9zm0 4h2v2H9v-2zm4-4h2v2h-2V9zm0 4h2v2h-2v-2z">
                            </path>
                        </svg>
                        <input type='text' class='subTaskDueDate' data-date-format="Y-m-d" value="@if($subTask['due_date'] != '0000-00-00') {{ $subTask['due_date'] }} @else null @endif">                                          
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
                                            <div class="w-64 overflow-y-auto" style="height: 120px;background-color: #fff" id="filter-assign-users-sub-task">
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
@if (count($completeTasks) > 0)
    <div class="pb-2 border-b border-gray-200 mb-1">
        <h4 class="font-medium text-lg">Completed ({{ count($completeTasks) }})</h4>
    </div>
    @foreach ($completeTasks as $key => $subTask)
        <div class="flex items-center px-4 py-2 mb-1 hover:bg-gray-100 rounded-lg">
            <div>
                <input type="hidden" class="sub-Task-Id" value="{{ $subTask['id'] }}">
                <input type="checkbox" class="subTaskComplete form-checkbox w-6 h-6 rounded-full text-green-400" main-task-id="{{ $parent_task_id }}"  total-sub-task="{{ count($completeTasks)+count($pendingTasks) }}" complete-sub-task="{{ count($completeTasks) }}"
                    @if ($subTask['completed_at'] != null) checked
    }
    }
    @endif>
    </div>
    <div class="pl-3 w-full">

        <div class="hideSubTask w-full text-sm">
            <div class="cursor-pointer">
                {{ $subTask['content'] }}
            </div>
        </div>
        <div class="editSubTask mt-1 flex rounded-md shadow-sm">
            <div class="relative flex-grow focus-within:z-10">
                <input
                    class="subtaskname form-input block w-full rounded-none rounded-l-md transition ease-in-out duration-150 sm:text-sm sm:leading-5"
                    value="{{ $subTask['content'] }}">
                <input type="hidden" class="subtaskid" value="{{ $subTask['id'] }}">
            </div>
            <span class="btn-group">
                <button type="button" class="hideSubTaskButton btn btn-white rounded-l-none border-l-0">
                    <svg fill="currentColor" viewBox="0 0 20 20" class="h-4 w-4 text-gray-400">
                        <path
                            d="M10 8.586L2.929 1.515 1.515 2.929 8.586 10l-7.071 7.071 1.414 1.414L10 11.414l7.071 7.071 1.414-1.414L11.414 10l7.071-7.071-1.414-1.414L10 8.586z">
                        </path>
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
                    <a href="javascript:void(0)" @if ($subTask['due_date'] != '0000-00-00') style="color:black" @endif title="{{ $subTask['due_date'] }}" sub-task-id="{{ $subTask['id'] }}"
                            class="sub-task-duedate ml-1 flex items-center justify-center rounded-full overflow-hidden text-gray-400 border w-6 h-6
                            overflow-hidden hover:text-gray-500 hover:bg-gray-50"><svg viewBox="0 0 20 20" fill="currentColor" class="w-3 h-3">
                                <path
                                    d="M1 4c0-1.1.9-2 2-2h14a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V4zm2 2v12h14V6H3zm2-6h2v2H5V0zm8 0h2v2h-2V0zM5 9h2v2H5V9zm0 4h2v2H5v-2zm4-4h2v2H9V9zm0 4h2v2H9v-2zm4-4h2v2h-2V9zm0 4h2v2h-2v-2z">
                                </path>
                            </svg>
                            <input type='text' class='subTaskDueDate' data-date-format="Y-m-d" value="@if($subTask['due_date'] != '0000-00-00') {{ $subTask['due_date'] }} @else null @endif">                                          
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

<script>

    $('.assignSubTask').click( function(e) { 
        e.preventDefault();
        e.stopPropagation();
        $(this).parent().siblings('.assignSubTaskPopup').toggle();
    });    
    $('.assignSubTaskPopup').click( function(e) {
        e.stopPropagation();
    });
    $('body').click( function() {
        $('.assignSubTaskPopup').hide();
    });

    $('.editSubTask').hide();
    $(".hideSubTask").click(function(){
        $(this).hide();
        $(this).siblings('.editSubTask').show();
    })
    $(".hideSubTaskButton").click(function(){
        $(this).parent().parent('.editSubTask').hide();
        $(this).parent().parent().siblings('.hideSubTask').show();
    })
    $(".editSubTaskButton").click(function(){
        var current = $(this);
        var site_url = "{{ url('/') }}" + '/pipeline-process/task/updateSubTask';
        var csrf = $("#token").val();
        var subtaskid = $(this).parent().siblings().children(".subtaskid").val();
        var task_name = $(this).parent().siblings().children(".subtaskname").val();
        $.ajax({
            type:'POST',
            url:site_url,
            data:{'id':subtaskid,'task_name':task_name,'csrf':csrf},
            success:function(data){
                var taskName = data.task_name;
                current.parent().parent().siblings('.hideSubTask').children().html(taskName);
                current.parent().parent('.editSubTask').hide();
                current.parent().parent().siblings('.hideSubTask').show();
            }               
        });
    });

    $(".subTaskComplete").click(function(){
        var currentInstance = $(this);
        if($(this). prop("checked") == true){
            var is_completed = '1';
        }
        else if($(this). prop("checked") == false){
            var is_completed = '0';
        }
       
        var site_url = "{{ url('/') }}" + '/pipeline-process/task/status';
        var csrf = $("#token").val();
        var task_id = $(this).attr('main-task-id');
        var id = $(this).siblings('.sub-Task-Id').val();
        var total_sub_task = $(this).attr('total-sub-task');
        var complete_sub_task = $(this).attr('complete-sub-task');
        $.ajax({
            type:'POST',
            url:site_url,
            data:{'id':id,'task_id':task_id,'is_completed':is_completed,'csrf':csrf},
            success:function(data){
                if(is_completed == '1'){
                    var tasks = (Number(complete_sub_task) + 1)+'/'+(Number(total_sub_task));
                }
                if(is_completed == '0'){
                    var tasks = (Number(complete_sub_task) - 1)+'/'+(Number(total_sub_task));
                }
                
                var html = '';
                html += '<svg viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 fill-current mr-1.5">'
                html += '<path d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zm12.73-1.41A8 8 0 1 0 4.34 4.34a8 8 0 0 0 11.32 11.32zM6.7 9.29L9 11.6l4.3-4.3 1.4 1.42L9 14.4l-3.7-3.7 1.4-1.42z">'
                html += '</path> </svg>'
                html += tasks
                $(".real-time-task-"+task_id).html(html);
                $(".completed-task-"+task_id).html(data);
            }               
        });
    });
    
    $(".sub-task-duedate").click(function(){
        var currentInstance = $(this);
        var id = $(this).attr('sub-task-id');
        // var id = $(this).children('.dueDateSubTaskId').val();
        localStorage.setItem('id', id);
        localStorage.setItem('currentInstance', currentInstance);

    })
    $(".subTaskDueDate").flatpickr({
        // minDate: "today",
        dateFormat: "Y-m-d",
        disableMobile: "true",
        onChange: function(selectedDates, dateStr, instance) {
            var site_url = "{{ url('/') }}" + '/pipeline-process/task/dueDate';
            var date = dateStr;
            var id =localStorage.getItem('id');
            var currentInstance =localStorage.getItem('currentInstance');
            var csrf = $("#token").val();
            $.ajax({
                type: "Post",
                url: site_url,
                data: {'id':id,'duedate':date,'csrf':csrf},
                success: function(data)
                {
                    currentInstance.css('color','black');
                    localStorage.removeItem('id');
                    localStorage.removeItem('currentInstance');
                }
            });  
            
        }
    });

    $(".assign-users-sub-task-search").on("keyup", function() {
    var value = $(this).val().toLowerCase();
    $("#filter-assign-users-sub-task a").filter(function() {
      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });
  });

    $(".assign-user").on('click',function(){
        var site_url = '/pipeline-process/task/assign-user';
        var task_id = $(this).attr('id');
        var user_id = $(this).attr('data-id');
        $('.assign-user-image'+task_id).show();
        $('.user-assigned-checbox'+task_id).hide();
        $(this).find('.assign-user-image'+task_id).hide();
        $(this).find('.user-assigned-checbox'+task_id).show();
        $(".assign-user-name"+task_id).text($(this).attr('data-item'));
        if(task_id)
        {
            $.ajax({
                    headers: {
                    'X-CSRF-TOKEN': $('#token').val()
                    },
                    type: "POST",
                    url: site_url,
                    data:
                    {
                        task_id: task_id,
                        user_id: user_id,
                    },
                    }); 
        }
    });

    $(".assign-user-sub-task").on('click',function(){
        var site_url = '/pipeline-process/task/assign-user';
        var sub_task_id = $(this).attr('id');
        var user_id = $(this).attr('data-id');
        $('.assign-user-image-sub-task'+sub_task_id).show();
        $('.user-assigned-checbox-sub-task'+sub_task_id).hide();
        $(this).find('.assign-user-image-sub-task'+sub_task_id).hide();
        $(this).find('.user-assigned-checbox-sub-task'+sub_task_id).show();
        $(".assign-user-profile-image-sub-task"+sub_task_id).attr('src','');
        $(".assign-user-profile-image-sub-task"+sub_task_id).attr('src',document.location.origin+"/uploads/thumb_"+$(this).attr('data-item').trim());
        if(sub_task_id)
        {
            $.ajax({
                    headers: {
                    'X-CSRF-TOKEN': $('#token').val()
                    },
                    type: "POST",
                    url: site_url,
                    data:
                    {
                        task_id: sub_task_id,
                        user_id: user_id,
                    },
                    }); 
        }
    });
    
</script>