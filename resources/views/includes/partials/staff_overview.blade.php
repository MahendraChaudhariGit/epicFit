<div class="col-sm-5 col-md-4">
	<div class="user-left">
		<div class="center">
			<h4>
				<span data-realtime="firstName">{{ $staff->first_name }}</span> 
				<span data-realtime="lastName">{{ $staff->last_name }}</span>
			</h4>
			<div>
				<div class="user-image">
					<div class="thumbnail">
						<a href="{{ dpSrc($staff->profile_picture, $staff->gender) }}" data-lightbox="image-1" 
							>
						<img src="{{ dpSrc($staff->profile_picture, $staff->gender) }}" class="img-responsive staffProfilePicturePreviewPics previewPics" id="profile-userpic-img" alt="{{ $staff->fullName }}" data-realtime="gender" style="max-width: 120px !important;"></a>

					</div>
                    <div class="form-group upload-group">
                        <input type="hidden" name="prePhotoName" value="{{ $staff->profile_picture }}">
                        <input type="hidden" name="entityId" value="{{$staff->id}}">
                        <input type="hidden" name="saveUrl" value="staff/photo/save">
                        <input type="hidden" name="photoHelper" value="staffProfilePicture">
                        <input type="hidden" name="cropSelector" value="square">
                        <div>
                            <label class="btn btn-primary btn-file">
                                <span>Change Photo</span> <input type="file" class="hidden" onChange="fileSelectHandler(this)" accept="image/*">
                            </label>
                        </div>
                    </div>
				</div>
			</div>
			<hr>
		</div>
		<table class="table table-condensed table-hover">
			<thead>
				<tr>
					<th colspan="3">Contact Information</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>Email:</td>
					<td><a href="mailto:{{ $staff->email ?? '' }}" data-realtime="email">{{ $staff->email ?? '' }}</a></td>
					<td><a href="#" class="editFieldModal" data-label="Email" data-value="{{ $staff->email }}" data-required="true" data-realtime="email"><i class="fa fa-pencil edit-user-info"></i></a></td>
				</tr>
				<tr>
					<td>Phone:</td>
					<td><a href="tel:{{ $staff->phone }}" data-realtime="phone">{{ $staff->phone }}</a></td>
					<td><a href="#" class="editFieldModal" data-label="Phone" data-value="{{ $staff->phone }}" data-required="true" data-realtime="phone"><i class="fa fa-pencil edit-user-info"></i></a></td>
				</tr>
				@if($staff->website)
				<!--<tr>
					<td>Website:</td>
					<td><a href="{{ $staff->website }}" target="_blank">{{ $staff->website }}</a></td>
					<td><a href="#panel_edit_account" class="show-tab"><i class="fa fa-pencil edit-user-info"></i></a></td>
				</tr>-->
				@endif
				@if($staff->facebook)
				<!--<tr>
					<td>Facebook:</td>
					<td><a href="{{ $staff->facebook }}" target="_blank">{{ $staff->facebook }}</a></td>
					<td><a href="#panel_edit_account" class="show-tab"><i class="fa fa-pencil edit-user-info"></i></a></td>
				</tr>-->
				@endif
                <!--<tr>
					<td>Address:</td>
					<td>
						@if($staff->fixed_location)
						{{ $staff->address_line_one.', '.$staff->address_line_two.', '}}
						@endif
						{{$staff->city.', '.$staff->stateName.', './*$countries[$staff->country].*/', '.$staff->postal_code }}</td>
					<td><a href="#panel_edit_account" class="show-tab"><i class="fa fa-pencil edit-user-info"></i></a></td>
				</tr>-->
			</tbody>
		</table>
		<table class="table table-condensed table-hover">
			<thead>
				<tr>
					<th colspan="3">General information</th>
				</tr>
			</thead>
			<tbody>
				<tr>
                    <td>Gender</td>
                    <td data-realtime="gender">{{ $staff->gender }}</td>
                    <td><a href="#" class="editFieldModal" data-label="Gender" data-value="{{ $staff->gender }}" data-required="true" data-realtime="gender"><i class="fa fa-pencil edit-user-info"></i></a></td>
                </tr>
            	<tr>
					<td>Job Title</td>
					<td data-realtime="jobTitle">{{ $staff->job_title }}</td>
					<td><a href="#" class="editFieldModal" data-label="Job Title" data-value="{{ $staff->job_title }}" data-required="true" data-realtime="jobTitle"><i class="fa fa-pencil edit-user-info"></i></a></td>
				</tr>
				<tr>
                    <td>Permission Group</td>
                    <td><span class="label label-info" data-realtime="permGroup">{{ $staff->type->ut_name }}</span></td>
                    <td>
                    	@if(isSuperUser())
                    		<a href="#" class="editFieldModal" data-label="Permission Group" data-value="{{ $staff->ut_id }}" data-required="true" data-realtime="permGroup" data-options='{{json_encode($permTyp)}}'><i class="fa fa-pencil edit-user-info"></i></a>
                    	@endif
                    </td>
                </tr>
				<!--<tr>
					<td>Book Staff Member Online</td>
					<td>
						@if($staff->clients_book_staff)
                            <span class="label label-info">Yes</span>
                        @else
                            <span class="label label-danger">No</span>
                        @endif
                    </td>
                    <td></td>
				</tr>-->
			</tbody>
		</table>
		<table class="table table-condensed table-hover">
			<thead>
				<tr>
					<th colspan="3">Personal information</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>Date of Birth</td>
					<td data-realtime="dob">{{ $staff->overviewDob }}</td>
					<td><a href="#" class="editFieldModal" data-label="Date of Birth" data-value="{{ $staff->date_of_birth }}" data-required="true" data-realtime="dob"><i class="fa fa-pencil edit-user-info"></i></a></td>
				</tr>
			</tbody>
		</table>
	</div>
</div>