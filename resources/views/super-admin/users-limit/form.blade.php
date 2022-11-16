<div class="row">
	<div class="col-md-6">
		<fieldset class="padding-15">
			<div class="form-group{{ $errors->has('maximum_users') ? 'has-error' : ''}}">
				<label class="strong">Users(upto)</label>
				<input type="number" name="maximum_users" class="form-control" value="{{isset($userLimit)?$userLimit->maximum_users:''}}">
				{!! $errors->first('maximum_users', '<p class="help-block">:message</p>') !!}
			</div>
			<div class="form-group{{ $errors->has('price') ? 'has-error' : ''}}">
				<label class="strong">Price</label>
				<input type="number" name="price" class="form-control" value="{{isset($userLimit)?$userLimit->price:''}}">
				{!! $errors->first('price', '<p class="help-block">:message</p>') !!}
			</div>
			<div class="form-group form-actions">
                <button type="submit" class="btn btn-primary pull-left">{{$button}}</button>
            </div>
		</fieldset>
	</div>
</div>