{{-- @if($post->getCommentCount() > 2 && (empty($comment_count) || $comment_count < 3 )) --}}
@if($post->getCommentCount() > 2 && (empty($comment_count) || $comment_count < 3))
<style type="text/css">
	.show_comments .btn{
	text-align: left;
    margin-top: 10px;
    font-weight: 600;
    color: #65676b;
}
</style>
<a class="btn btn-link btn-block btn-xs" onclick="showAllComment({{ $post->id }})">Show all comments</a> 
  {{-- <a class="btn btn-link btn-block btn-xs" target="_blank" href="{{route('post.single_post', $post->id)}}">Show all comments</a> --}}
@endif