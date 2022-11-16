
<script>
        var task_id = '{{ $task_id }}';
        $(".attachment-preview-"+task_id).empty();
        var html = '';
        @if ($attachments) @foreach ($attachments as $attachment)
        html += '<li class="pl-3 pr-2 py-3 flex items-center justify-between text-sm leading-5">'
        html += '<div class="w-0 flex-1 flex items-center">'
        html += '<svg fill="currentColor" viewBox="0 0 20 20" class="flex-shrink-0 h-5 w-5 text-gray-400">'
        html += '<path fill-rule="evenodd" d="M8 4a3 3 0 00-3 3v4a5 5 0 0010 0V7a1 1 0 112 0v4a7 7 0 11-14 0V7a5 5 0 0110 0v4a3 3 0 11-6 0V7a1 1 0 012 0v4a1 1 0 102 0V7a3 3 0 00-3-3z" clip-rule="evenodd"></path>'
        html += '</svg>'
        html += '<a href="{{ url("pipeline-process/task/download") }}/{{ $attachment["filename"] }}" class="hover:underline">'
        html += '<span class="ml-2 truncate">{{ $attachment["filename"] }}</span>'
        html += '</a> </div>'
        html += '<div class="ml-4 flex-shrink-0">'
        html += '<a href="{{ url("pipeline-process/task/download") }}/{{ $attachment["filename"] }}" title="Download" class="btn btn-flat btn-xs">'
        html += '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" class="w-3 h-3 fill-current">'
        html += '<path d="M13 8V2H7v6H2l8 8 8-8h-5zM0 18h20v2H0v-2z"></path>'
        html += '</svg>'
        html += '</a> </div> </li>'
        @endforeach @endif
        $(".attachment-preview-"+task_id).html(html);
  
    
</script>
@php
    use App\Models\PipelineProcess\Attachment;
    use App\Models\PipelineProcess\Comment;
    use App\Staff;
    $auth_staff = Staff::where('email',Auth::User()->email)->first();
@endphp
@foreach ($data as $comment)
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

                    </ul>
                @endif
            </div>
        </div>
    </div>
@endforeach

