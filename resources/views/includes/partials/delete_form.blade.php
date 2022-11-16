{{ Form::open(['url' => '#', 'method' => 'delete', 'id' => 'deleteForm']) }}
    @if(isset($extraFields))
      @foreach($extraFields as $key=>$value)
           <input type="hidden" name="{{ $key }}" value="{{ $value }}">
      @endforeach
    @endif
{{ Form::close() }}