<?php 
	if(isset($user)){
	    if($user->profile_picture != '')
	        $userimage = url('uploads/thumb_'.$user->profile_picture);
	    else
	        $userimage = url('assets/images/media-user.png');

	    $username = ucwords($user->name.' '.$user->last_name);
	    $useremail = $user->email;
	}
?>
<!-- start: LOCK SCREEN -->
<div class="lock-screen">
	<div class="box-ls">
		<img id="user-profile" alt="" src="{{ isset($userimage)?$userimage:asset('assets/images/media-user.png') }}" class="img-rounded img-thumbnail" width="70px" height="70px"/>
		<div class="user-info">
			<h4 id="username">{{ isset($username)?$username:'' }}</h4>
			<span id="useremail">{{ isset($useremail)?$useremail:'' }}</span>
			{!! Form::open(['url' => '', 'class' => 'form-unlock','id'=>'form-unlock']) !!}
        		{!! Form::hidden('username',isset($useremail)?$useremail:'') !!}
				<div class="input-group">
					<input type="password" placeholder="Password to unlock" class="form-control" name="password" required="required">
					<span class="input-group-btn">
						<button class="btn btn-primary" id="unlock-btn" type="submit"><i class="fa fa-chevron-right"></i></button> 
					</span>
				</div>
				<span class="error"></span>
			{!! Form::close() !!}
			<div class="screen-lock-logout">
				<a href="{{ url('logout') }}" class="btn btn-red"><i class="fa fa-power-off"></i></a>
			</div>
		</div>
	</div>
</div>
<!-- End: LOCK SCREEN -->
