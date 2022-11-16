@extends('Result.masters.app')

@section('required-styles')
{!! Html::style('result/css/autocomplete.css') !!}
<style type="text/css">
	select{
	-webkit-appearance: auto;
    -moz-appearance: initial;
    appearance: auto;
    padding-left: 4px !important;
	}
</style>
@stop

@section('page-title')
    <span>Create Challange</span>
@stop

@section('content')


<div class="container">
	<div class="row">
		<form action="{{ url('fitness-mapper/save/challenge') }}" method="post">
			@csrf
		<div class="col-sm-6">
			<input type="hidden" name="client_id" value="{{ Auth::user()->account_id }}">
			<input type="hidden" name="fitness_mapper_route_id" value="{{ $id }}">
			<input type="hidden" name="challenge_id" value="{{ isset($challenge)?$challenge->id:'' }}">
			<div class="form-group">
				<label class="strong">Challenge Name</label>
				<input class="form-control" type="text" name="name" value="{{ isset($challenge)?$challenge->name:'' }}">
			</div>
			<div class="form-group">
				<label class="strong">Route Name</label>
				<input class="form-control" type="text" readonly name="route_name" value="{{ isset($fitness_map)?$fitness_map->name:'' }}">
			</div>
			<div class="form-group">
				<label class="strong">Challenge Type</label>
				<select class="form-control" name="challenge_type_id">
					@foreach ($challenge_types as $challenge_type)
					<option @if(isset($challenge) && $challenge->challenge_type_id == $challenge_type['id']) selected @endif value="{{ $challenge_type['id'] }}">{{ $challenge_type['type'] }}</option>
					@endforeach
				</select>
			</div>
			<div class="form-group">
				<label class="strong">Activity Type</label>
				<select class="form-control" name="activity_type_id">
					@foreach ($activity_types as $activity_type)
					<option @if(isset($challenge) && $challenge->activity_type_id == $activity_type['id']) selected @endif value="{{ $activity_type['id'] }}">{{ $activity_type['type'] }}</option>
					@endforeach
				</select>
			</div>
			{{-- <div class="form-group">
				<label class="strong">Duration</label>
					<select class="form-control">
					<option>Day</option>
					<option>Week</option>
					<option>Month</option>
					<option>Custom</option>

				</select>
			</div> --}}
			<div class="form-group">
				<label class="strong">Dates</label>
				<input class="form-control" type="date" name="date" value="{{ isset($challenge)?$challenge->date:'' }}">
			</div>
			<div class="form-group">
				<label class="strong">Friends</label>
				<input class="form-control autocomplete" type="text" name="shared_client_id" value="{{ isset($challenge)?implode(',',$challenge->challenge_friend->pluck('client_id')->toArray()):'' }}">
			</div>
			<div class="form-group">
				<button type="submit" class="btn btn-primary btn-wide updatePassword">
					START CHALLANGE
				</button>
			</div>
		</div>
	</form>
	</div>
</div>

@stop

@section('required-script')
{!! Html::script('result/js/autocomplete.js?v='.time()) !!}
<script>
	
	    var options = [
			@foreach($all_clients as $key => $client)
			{ 'tag':'{{$client["name"]}}','value': '{{$client["id"]}}' },
			@endforeach
		];
		$('.autocomplete').amsifySuggestags({
			type :'bootstrap',
			suggestions: options,
			whiteList:true,
			defaultTagClass:'',
			classes: [],
			backgrounds: [],
			colors: []
		});

</script>
@stop