@php
use App\Models\PipelineProcess\Column;
use App\Models\PipelineProcess\Attachment;
use App\Models\PipelineProcess\Comment;
use App\Models\PipelineProcess\Project;
use App\Models\PipelineProcess\PipelineProcessTask;
@endphp
@foreach ($tasks as $key => $task)
<div class="commentOnColumnPopup fixed inset-0 max-h-full overflow-y-auto p-6 z-40"
style="background-color: rgba(0, 0, 0, 0.4); margin-top:60px; display:none;">
<div class="deleteTaskModal fixed inset-0 max-h-full overflow-y-auto p-6 z-40"
    style="background-color: rgba(0, 0, 0, 0.4); margin-top:78px; display:none;">
    <div class="mx-auto max-w-xl">
        <div class="mx-auto max-w-lg">
            <div class="flex flex-col rounded-lg bg-white shadow">
                <div class="p-6">
                    <h3 class="font-semibold text-lg">Confirmation</h3>
                </div>
                <div class="p-6 " style="font-size:.877rem; opacity:2; font-weight:500;">
                    Do you want to permanently delete this column?
                </div>
                <div class="p-6 flex justify-end">
                    <button type="button" class="hideDeleteTaskPopup btn btn-flat mr-3">Cancel</button>
                    <button type="button" class="deleteTaskId btn btn-danger" name="id" value="{{ $task->id }}"
                        style="hover:red;">Confirm</button>
                </div>
            </div>
        </div>
    </div>

</div>

<div class="mx-auto max-w-xl">
    <div class="bg-white rounded-lg shadow-xl transform transition-all sm:max-w-xl sm:w-full">
        <div class="flex items-center px-6 pt-6 pb-4">
            <div>
                <input type="checkbox" class="contentComplete form-checkbox w-6 h-6 rounded-full text-green-400"
                    @if ($task->completed_at != null) checked @endif >
            </div>
            <div class="pl-3 w-full checktask">
                <div class="block w-full">
                    <div class="cursor-pointer editTask">
                        {{ $task->content }}
                    </div>
                    <div class="showEditTask mt-1 flex rounded-md shadow-sm">
                        <div class="relative flex-grow focus-within:z-10">
                            <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
                            <input type="hidden" class="task-id" value="{{ $task->id }}">
                            <input class="task-name form-input block w-full rounded-none rounded-l-md transition ease-in-out duration-150 sm:text-sm sm:leading-5"
                                value="{{ $task->content }}">
                        </div>
                        <span class="btn-group">
                            <button type="button" class="hideEditTask btn btn-white rounded-l-none border-l-0">
                                <svg fill="currentColor" viewBox="0 0 20 20" class="h-4 w-4 text-gray-400">
                                    <path
                                        d="M10 8.586L2.929 1.515 1.515 2.929 8.586 10l-7.071 7.071 1.414 1.414L10 11.414l7.071 7.071 1.414-1.414L11.414 10l7.071-7.071-1.414-1.414L10 8.586z">
                                    </path>
                                </svg>
                            </button>
                            <button type="button" class="btn btn-white saveTask">
                                <svg fill="currentColor" viewBox="0 0 20 20" class="h-4 w-4 text-gray-400">
                                    <path d="M0 11l2-2 5 5L18 3l2 2L7 18z"></path>
                                </svg>
                            </button>
                        </span>
                    </div>
                </div>
            </div>
            <div class="pl-3 flex items-center">
                <div class="relative inline-block text-left">
                    <div>
                        <button class="deleteComment btn btn-sm btn-flat">
                            <svg viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 fill-current">
                                <path
                                    d="M10 12a2 2 0 1 1 0-4 2 2 0 0 1 0 4zm0-6a2 2 0 1 1 0-4 2 2 0 0 1 0 4zm0 12a2 2 0 1 1 0-4 2 2 0 0 1 0 4z">
                                </path>
                            </svg>
                        </button>
                    </div>

                    <div class=" deleteCommentPopup origin-top-right absolute mt-2 w-auto rounded-md shadow-lg z-40 right-0"
                        style="display: none;">
                        <div class="w-40 rounded-md bg-white shadow-xs p-1">
                            <a href="javascript:void(0)" class="deleteTask dropdown-item">
                                Delete
                            </a>
                        </div>
                    </div>

                </div>
                <button class="hideCommentOnColumnPopup btn btn-sm btn-flat">
                    <svg viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 fill-current">
                        <path
                            d="M10 8.586L2.929 1.515 1.515 2.929 8.586 10l-7.071 7.071 1.414 1.414L10 11.414l7.071 7.071 1.414-1.414L11.414 10l7.071-7.071-1.414-1.414L10 8.586z">
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
                            class="assignTask flex items-center text-xs text-gray-600 hover:underline">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                class="w-4 h-4 mr-1.5 fill-current">
                                <path
                                    d="M2 6H0v2h2v2h2V8h2V6H4V4H2v2zm7 0a3 3 0 0 1 6 0v2a3 3 0 0 1-6 0V6zm11 9.14A15.93 15.93 0 0 0 12 13c-2.91 0-5.65.78-8 2.14V18h16v-2.86z">
                                </path>
                            </svg>
                            <span>Assign User</span>
                        </a>
                    </div>
                    <div class="assignTaskPopup origin-top-right absolute mt-2 w-auto rounded-md shadow-lg z-40 left-0"
                        style="display: none;">
                        <div class="w-64 bg-white shadow-xs">
                            <div class="flex flex-col">
                                <div class="px-4 pt-4 mb-2">
                                    <input placeholder="Search.." class="form-input">
                                </div>
                                <div class="w-64 overflow-y-auto" style="height: 220px;"><a href="javascript:void(0)"
                                        class="dropdown-item flex items-center">
                                        <div class="flex-shrink-0 flex items-center">
                                            <img src="http://getshipboard.com/storage/avatars/022c6084a48cd0cf64f17f7e9242a527.png"
                                                alt="avatar" class="avatar avatar-xs">
                                        </div>
                                        <div
                                            class="text-sm leading-5 text-gray-700 group-hover:text-gray-900 truncate pl-4">
                                            Adelle Emmerich
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="relative inline-block text-left">
                <div>
                    <a href="javascript:void(0)"
                        class="dueDate flex items-center text-xs text-gray-600 pl-6 hover:underline">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" class="w-4 h-4 mr-1.5 fill-current">
                            <path
                                d="M1 4c0-1.1.9-2 2-2h14a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V4zm2 2v12h14V6H3zm2-6h2v2H5V0zm8 0h2v2h-2V0zM5 9h2v2H5V9zm0 4h2v2H5v-2zm4-4h2v2H9V9zm0 4h2v2H9v-2zm4-4h2v2h-2V9zm0 4h2v2h-2v-2z">
                            </path>
                        </svg>
                        <input type="hidden" class="dueDateTaskId" value="{{ $task->id }}">
                        <span class="due-date">
                            @if ($task->due_date != null)
                                {{ $task->due_date }}
                            @else
                                Due Date
                            @endif
                        </span>
                    </a>
                </div>
                <div class="dueDatePopup origin-top-right absolute mt-2 w-auto rounded-md shadow-lg z-40 left-0"
                    style="display: none;">

                    <input class="form-input hidden dueDateValue" type="text" readonly="readonly">
                </div>
            </div>
            <div class="flex items-center">
                <div class="relative inline-block text-left">
                    <div class="priority-div">
                        <a href="javascript:void(0)"
                            class="priority flex items-center text-xs text-gray-600 pl-6 hover:underline">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                class="w-4 h-4 mr-1.5 fill-current">
                                <path d="M7.667 12H2v8H0V0h12l.333 2H20l-3 6 3 6H8l-.333-2z"></path>
                            </svg>
                            <span class="selectedPriority">
                                @php
                                if($task->priority == 4){
                                $priority = 'Urgent';
                                }elseif($task->priority == 3){
                                $priority = 'High';
                                }elseif($task->priority == 2){
                                $priority = 'Medium';
                                }elseif($task->priority == 1){
                                $priority = 'Low';
                                }else{
                                $priority = 'Priority';
                                }
                                @endphp
                                {{ $priority }}
                            </span>
                        </a>
                    </div>
                    <div class="priorityPopup origin-top-right absolute mt-2 w-auto rounded-md shadow-lg z-40 left-0"
                        style="display: none;">
                        <input type="hidden" class="priorityTaskId" value="{{ $task->id }}">
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
        </div>
        <div>
            <div class="bg-gray-50 p-6 sm:hidden"><select class="form-select">
                    <option value="[object Object]">Comments</option>
                    <option value="[object Object]">Tasks</option>
                    <option value="[object Object]">Attachments</option>
                </select></div>
            <div class="sm:block">
                <div class="border-b border-gray-200 commonpopupmodel">
                    <ul class="nav nav-tabs flex nav" id="myTab" role="tablist">
                        <li class="nav-item active flex-1">
                            <a class="nav-link  py-4 px-1 text-center border-b-2 border-transparent font-medium text-sm leading-5 text-gray-600"
                                id="comments-tab{{ $task->id }}" data-toggle="tab" href="#comments{{ $task->id }}"
                                role="tab" aria-controls="comments{{ $task->id }}" aria-selected="true">Comments</a>
                        </li>
                        <li class="nav-item flex-1">
                            <a class="nav-link py-4 px-1 text-center border-b-2 border-transparent font-medium text-sm leading-5 text-gray-600"
                                id="tasks-tab{{ $task->id }}" data-toggle="tab" href="#tasks{{ $task->id }}" role="tab"
                                aria-controls="tasks{{ $task->id }}" aria-selected="false">Tasks</a>
                        </li>
                        <li class="nav-item flex-1">
                            <a class="nav-link py-4 px-1 text-center border-b-2 border-transparent font-medium text-sm leading-5 text-gray-600"
                                id="attachments-tab{{ $task->id }}" data-toggle="tab" href="#attachments{{ $task->id }}"
                                role="tab" aria-controls="attachments{{ $task->id }}"
                                aria-selected="false">Attachments</a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane fade active in" id="comments{{ $task->id }}" role="tabpanel"
                            aria-labelledby="comments-tab{{ $task->id }}">
                            <section class="p-6">
                                <div class="flex flex-col">
                                    <form class="commentForm" method="post" enctype="multipart/form-data"
                                        action="javascript:void(0)">
                                        @csrf
                                        <div class="flex flex-col rounded-lg shadow-sm border border-gray-300">
                                            <textarea name="content"
                                                class="comment-text form-textarea w-full block resize-none border-none shadow-none focus:outline-none focus:shadow-none rounded-lg"
                                                placeholder="Write something.." style="height: 80px;"></textarea>
                                            <input type="hidden" name="id" class="comment_task_id"
                                                value="{{ $task->id }}">
                                            <div class="mt-2 px-3 pb-3 flex items-center justify-between">
                                                <div>
                                                    <button type="submit" value="upload"
                                                        class="comment-btn btn btn-sm btn-indigo">
                                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                            class="w-3 h-3 mr-1.5 fill-current">
                                                            <path d="M0 0l20 10L0 20V0zm0 8v4l10-2L0 8z"></path>
                                                        </svg>
                                                        Send
                                                    </button>
                                                </div>
                                                <div class="attachmentfile">
                                                    <span class="btn-group shadow-none">
                                                        <button type="button" class="btn btn-sm btn-flat dz-clickable">
                                                            <input type="file" id="file{{ $task->id }}" class="filename"
                                                                name="filename[]" multiple accept="icon/*">
                                                            <label for="file{{ $task->id }}">
                                                                <svg viewBox="0 0 20 20" fill="currentColor"
                                                                    class="w-4 h-4">
                                                                    <path
                                                                        d="M15 3H7a7 7 0 1 0 0 14h8v-2H7A5 5 0 0 1 7 5h8a3 3 0 0 1 0 6H7a1 1 0 0 1 0-2h8V7H7a3 3 0 1 0 0 6h8a5 5 0 0 0 0-10z">
                                                                    </path>
                                                                </svg>
                                                            </label>
                                                        </button>
                                                    </span>
                                                </div>
                                            </div>

                                        </div>
                                    </form>

                                    <div class="latestComments flex flex-col mt-2">
                                        @if ($task['comments'] != '[]')
                                            @foreach ($task['comments'] as $comment)
                                                <div class="">
                                                    <div class="flex mt-6 pb-4">
                                                        <div class="w-12 flex-shrink-0">
                                                            <img src="http://getshipboard.com/storage/avatars/022c6084a48cd0cf64f17f7e9242a527.png"
                                                                alt="avatar" class="avatar avatar-sm">
                                                        </div>
                                                        <div class="flex flex-col flex-1">
                                                            <div class="flex items-center justify-between">
                                                                <div>
                                                                    <span
                                                                        class="text-sm leading-5 font-medium text-blue-600">Admin</span>
                                                                </div>
                                                                <div>
                                                                    <span
                                                                        class="text-xs text-gray-500">{{ $comment->created_at->diffForHumans() }}</span>
                                                                </div>
                                                            </div>
                                                            <div class="mt-2">
                                                                <p class="leading-5 text-gray-900">
                                                                    {{ $comment->content }}</p>
                                                            </div>
                                                            @php
                                                            $attachments =
                                                            Attachment::where('comment_id',$comment->id)->get()->toArray();
                                                            @endphp
                                                            @if ($attachments)
                                                                <ul class="border border-gray-200 rounded-md mt-4">
                                                                    @foreach ($attachments as $attachment)
                                                                        <li
                                                                            class="pl-3 pr-2 py-3 flex items-center justify-between text-sm leading-5">
                                                                            <div class="w-0 flex-1 flex items-center">
                                                                                <svg fill="currentColor"
                                                                                    viewBox="0 0 20 20"
                                                                                    class="flex-shrink-0 h-5 w-5 text-gray-400">
                                                                                    <path fill-rule="evenodd"
                                                                                        d="M8 4a3 3 0 00-3 3v4a5 5 0 0010 0V7a1 1 0 112 0v4a7 7 0 11-14 0V7a5 5 0 0110 0v4a3 3 0 11-6 0V7a1 1 0 012 0v4a1 1 0 102 0V7a3 3 0 00-3-3z"
                                                                                        clip-rule="evenodd"></path>
                                                                                </svg>
                                                                                <a href="{{ url('pipeline-process/task/download') }}/{{ $attachment['filename'] }}"
                                                                                    class="hover:underline"><span
                                                                                        class="ml-2 truncate">{{ $attachment['filename'] }}</span>
                                                                                </a>
                                                                            </div>
                                                                            <div class="ml-4 flex-shrink-0">
                                                                                <a href="{{ url('pipeline-process/task/download') }}/{{ $attachment['filename'] }}"
                                                                                    title="Download"
                                                                                    class="btn btn-flat btn-xs">
                                                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                                                        viewBox="0 0 20 20"
                                                                                        class="w-3 h-3 fill-current">
                                                                                        <path
                                                                                            d="M13 8V2H7v6H2l8 8 8-8h-5zM0 18h20v2H0v-2z">
                                                                                        </path>
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
                                            <div class="flex flex-col items-center">
                                                <div class="flex flex-col items-center">
                                                    <svg data-name="Layer 1" xmlns="http://www.w3.org/2000/svg"
                                                        xmlns:xlink="http://www.w3.org/1999/xlink" width="300"
                                                        height="300" viewBox="0 0 1094 798.15">

                                                    </svg>
                                                    <span class="text-gray-600">There are no comments to show
                                                        here
                                                    </span>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </section>
                        </div>
                        <div class="tab-pane fade" id="tasks{{ $task->id }}" role="tabpanel"
                            aria-labelledby="tasks-tab{{ $task->id }}">
                            <section class="p-6">
                                <div class="flex flex-col">
                                    <div class="mb-6">
                                        <div class="mt-1 flex rounded-md shadow-sm">
                                            <div class="relative flex-grow focus-within:z-10">
                                                <input placeholder="Enter a new task.."
                                                    class="subTaskVal form-input rounded-r-none" value="">
                                                <input type="hidden" class="subTaskId" value="{{ $task->id }}">
                                            </div>
                                            <button
                                                class="addSubTask -ml-px relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm leading-5 font-medium rounded-r-md text-gray-700 bg-gray-50 hover:text-gray-500 hover:bg-white focus:outline-none focus:shadow-outline-blue focus:border-blue-300 active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150">
                                                <svg fill="currentColor" viewBox="0 0 20 20"
                                                    class="h-4 w-4 text-gray-400">
                                                    <path
                                                        d="M11 9V5H9v4H5v2h4v4h2v-4h4V9h-4zm-1 11a10 10 0 1 1 0-20 10 10 0 0 1 0 20z">
                                                    </path>
                                                </svg>
                                                <span class="ml-2">Add</span>
                                            </button>
                                        </div>
                                    </div>
                                    <!---->
                                    @php
                                    $pendingTasks = PipelineProcessTask::where('task_id',$task->id)->where('completed_at','=',NULL)->get()->toArray();
                                    $completeTasks = PipelineProcessTask::where('task_id',$task->id)->where('completed_at','!=',NULL)->get()->toArray();
                                    @endphp
                                    <div class="flex flex-col mb-4 latestSubTask">

                                        @if (count($pendingTasks) > 0)
                                            <div class="pb-2 border-b border-gray-200 mb-1">
                                                <h4 class="font-medium text-lg">Todo ({{ count($pendingTasks) }})</h4>
                                            </div>
                                            @foreach ($pendingTasks as $key => $subTask)
                                                <div
                                                    class="flex items-center px-4 py-2 mb-1 hover:bg-gray-100 rounded-lg">
                                                    <div>
                                                        <input type="hidden" class="sub-Task-Id"
                                                            value="{{ $subTask['id'] }}">
                                                        <input type="checkbox"
                                                            class="subTaskComplete form-checkbox w-6 h-6 rounded-full text-green-400"
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
                                                <button type="button"
                                                    class="hideSubTaskButton btn btn-white rounded-l-none border-l-0">
                                                    <svg fill="currentColor" viewBox="0 0 20 20"
                                                        class="h-4 w-4 text-gray-400">
                                                        <path
                                                            d="M10 8.586L2.929 1.515 1.515 2.929 8.586 10l-7.071 7.071 1.414 1.414L10 11.414l7.071 7.071 1.414-1.414L11.414 10l7.071-7.071-1.414-1.414L10 8.586z">
                                                        </path>
                                                    </svg>
                                                </button>
                                                <button type="button" class="editSubTaskButton btn btn-white">
                                                    <svg fill="currentColor" viewBox="0 0 20 20"
                                                        class="h-4 w-4 text-gray-400">
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
                                                    <a href="javascript:void(0)" @if ($subTask['due_date'] != null) style="color:black"
                                                        @endif
                                                        class="subTaskDueDate ml-1 flex items-center justify-center
                                                        rounded-full overflow-hidden text-gray-400 border w-6 h-6
                                                        overflow-hidden hover:text-gray-500 hover:bg-gray-50"><svg
                                                            viewBox="0 0 20 20" fill="currentColor" class="w-3 h-3">
                                                            <path
                                                                d="M1 4c0-1.1.9-2 2-2h14a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V4zm2 2v12h14V6H3zm2-6h2v2H5V0zm8 0h2v2h-2V0zM5 9h2v2H5V9zm0 4h2v2H5v-2zm4-4h2v2H9V9zm0 4h2v2H9v-2zm4-4h2v2h-2V9zm0 4h2v2h-2v-2z">
                                                            </path>
                                                        </svg>
                                                        <input type="hidden" class="dueDateSubTaskId"
                                                            value="{{ $subTask['id'] }}">
                                                    </a>
                                                </div>

                                            </div>
                                        </div>
                                        <div class="ml-1">
                                            <div class="flex items-center">
                                                <div class="relative inline-block text-left">
                                                    <div>
                                                        <a href="javascript:void(0)"
                                                            class="assignSubTask ml-1 flex items-center justify-center rounded-full overflow-hidden text-gray-400 border w-6 h-6 overflow-hidden hover:text-gray-500 hover:bg-gray-50"><svg
                                                                viewBox="0 0 20 20" fill="currentColor" class="w-3 h-3">
                                                                <path
                                                                    d="M5 5a5 5 0 0 1 10 0v2A5 5 0 0 1 5 7V5zM0 16.68A19.9 19.9 0 0 1 10 14c3.64 0 7.06.97 10 2.68V20H0v-3.32z">
                                                                </path>
                                                            </svg>
                                                        </a>
                                                    </div>
                                                    <div class="assignSubTaskPopup origin-top-right absolute mt-2 w-auto rounded-md shadow-lg z-40 right-0"
                                                        style="display: none;">
                                                        <div class="dropdown-menu w-64">
                                                            <div class="flex flex-col">
                                                                <div class="px-4 pt-4 mb-2">
                                                                    <input placeholder="Search.." class="form-input">
                                                                </div>
                                                                <div class="w-64 overflow-y-auto"
                                                                    style="height: 220px;">
                                                                    <a href="javascript:void(0)"
                                                                        class="dropdown-item flex items-center">
                                                                        <div class="flex-shrink-0 flex items-center">
                                                                            <img src="http://getshipboard.com/storage/avatars/022c6084a48cd0cf64f17f7e9242a527.png"
                                                                                alt="avatar" class="avatar avatar-xs">
                                                                        </div>

                                                                        <div
                                                                            class="text-sm leading-5 text-gray-700 group-hover:text-gray-900 truncate pl-4">
                                                                            Adelle Emmerich
                                                                        </div>
                                                                    </a>
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
                                                <input type="checkbox"
                                                    class="subTaskComplete form-checkbox w-6 h-6 rounded-full text-green-400"
                                                    @if ($subTask['completed_at'] != null)
                                                checked
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
                                    <button type="button"
                                        class="hideSubTaskButton btn btn-white rounded-l-none border-l-0">
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
                                        <a href="javascript:void(0)" @if ($subTask['due_date'] != null) style="color:black" </beautify
                                                end=" @endif">
                                            class="subTaskDueDate ml-1 flex items-center justify-center rounded-full
                                            overflow-hidden text-gray-400 border w-6 h-6 overflow-hidden
                                            hover:text-gray-500 hover:bg-gray-50"><svg viewBox="0 0 20 20"
                                                fill="currentColor" class="w-3 h-3">
                                                <path
                                                    d="M1 4c0-1.1.9-2 2-2h14a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V4zm2 2v12h14V6H3zm2-6h2v2H5V0zm8 0h2v2h-2V0zM5 9h2v2H5V9zm0 4h2v2H5v-2zm4-4h2v2H9V9zm0 4h2v2H9v-2zm4-4h2v2h-2V9zm0 4h2v2h-2v-2z">
                                                </path>
                                            </svg>
                                            <input type="hidden" class="dueDateSubTaskId" value="{{ $subTask['id'] }}">
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
                                                <svg viewBox="0 0 20 20" fill="currentColor" class="w-3 h-3">
                                                    <path
                                                        d="M5 5a5 5 0 0 1 10 0v2A5 5 0 0 1 5 7V5zM0 16.68A19.9 19.9 0 0 1 10 14c3.64 0 7.06.97 10 2.68V20H0v-3.32z">
                                                    </path>
                                                </svg>
                                            </a>
                                        </div>
                                        <div class="assignSubTaskPopup origin-top-right absolute mt-2 w-auto rounded-md shadow-lg z-40 right-0"
                                            style="display: none;">
                                            <div class="dropdown-menu w-64">
                                                <div class="flex flex-col">
                                                    <div class="px-4 pt-4 mb-2"><input placeholder="Search.."
                                                            class="form-input"></div>
                                                    <div class="w-64 overflow-y-auto" style="height: 220px;">
                                                        <a href="javascript:void(0)"
                                                            class="dropdown-item flex items-center">
                                                            <div class="flex-shrink-0 flex items-center">
                                                                <img src="http://getshipboard.com/storage/avatars/022c6084a48cd0cf64f17f7e9242a527.png"
                                                                    alt="avatar" class="avatar avatar-xs">
                                                            </div>
                                                            <div
                                                                class="text-sm leading-5 text-gray-700 group-hover:text-gray-900 truncate pl-4">
                                                                Adelle Emmerich
                                                            </div>
                                                        </a>
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
                    {{-- @if (count($pendingTask) > 0 && count($completeTask) > 0)
                        <div class="flex flex-col items-center">
                            <div class="flex flex-col items-center">
                                <svg data-name="Layer 1" xmlns="http://www.w3.org/2000/svg"
                                    xmlns:xlink="http://www.w3.org/1999/xlink" width="300" height="300"
                                    viewBox="0 0 1094 798.15">

                                </svg>
                                <span class="text-gray-600">There are no tasks to show here
                                </span>
                            </div>
                        </div>
                    @endif --}}
                </div>
                <!---->
            </div>
            </section>
        </div>
        <div class="tab-pane fade" id="attachments{{ $task->id }}" role="tabpanel"
            aria-labelledby="attachments-tab{{ $task->id }}">
            <section class="p-6">
                <div class="flex flex-col">
                    <ul class="border border-gray-200 rounded-md mt-4">
                        @php
                        $comments = Comment::where('pipeline_process_task_id',$task->id)->get()->toArray();
                        @endphp
                        @if ($comments)
                            @foreach ($comments as $key => $comment)
                                @php
                                $attachments = Attachment::where('comment_id',$comment['id'])->get()->toArray();
                                @endphp
                                @if ($attachments)
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
                                                    class="hover:underline"><span
                                                        class="ml-2 truncate">{{ $attachment['filename'] }}</span>
                                                </a>
                                            </div>
                                            <div class="ml-4 flex-shrink-0">
                                                <a href="{{ url('pipeline-process/task/download') }}/{{ $attachment['filename'] }}"
                                                    title="Download" class="btn btn-flat btn-xs">
                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                        class="w-3 h-3 fill-current">
                                                        <path d="M13 8V2H7v6H2l8 8 8-8h-5zM0 18h20v2H0v-2z"></path>
                                                    </svg>
                                                </a>
                                            </div>
                                        </li>
                                    @endforeach
                                    {{-- @else
                                    <div class="flex flex-col items-center">
                                        <div class="flex flex-col items-center">
                                            <svg data-name="Layer 1" xmlns="http://www.w3.org/2000/svg"
                                                xmlns:xlink="http://www.w3.org/1999/xlink" width="300" height="300"
                                                viewBox="0 0 1094 798.15">

                                            </svg>
                                            <span class="text-gray-600">There are no attachments to show
                                                here
                                            </span>
                                        </div>
                                    </div> --}}
                                @endif

                            @endforeach
                        @else
                            <div class="flex flex-col items-center">
                                <div class="flex flex-col items-center">
                                    <svg data-name="Layer 1" xmlns="http://www.w3.org/2000/svg"
                                        xmlns:xlink="http://www.w3.org/1999/xlink" width="300" height="300"
                                        viewBox="0 0 1094 798.15">

                                    </svg>
                                    <span class="text-gray-600">There are no attachments to show
                                        here
                                    </span>
                                </div>
                            </div>
                        @endif
                    </ul>
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
@endforeach
