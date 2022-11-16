@foreach($message_list->get()->reverse() as $message)

  @include('Result.social-network.message.single_message')

@endforeach

