@foreach($post->soical_comments()->limit($comment_count)->orderBY('id', 'DESC')->with('client')->get() as $key1 => $comment)
      @include('Result.social-network.post.single-comment-preview')
@endforeach