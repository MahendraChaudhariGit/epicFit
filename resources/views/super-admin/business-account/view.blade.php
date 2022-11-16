@extends('super-admin.layout.master')
@section('page-title')
Business Account	
@stop

@section('content')
<div class="tabbable">
	<ul class="nav nav-tabs tab-padding tab-space-3 tab-blue epic-mobile-tab" id="myTab4">
		<li class="active">
			<a data-toggle="tab" href="#panel_overview">
				Overview
			</a>
		</li>              
	</ul>
	<div class="tab-content">
		<div id="panel_overview" class="tab-pane in active">
			<div class="row">
				<div class="col-sm-6 col-md-6">
					<div class="user-left">
						<table class="table table-condensed table-hover">
							<thead>
								<tr>
									<th colspan="3">General Information</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td>Name:</td>
									<td>{{isset($businessAccount)?$businessAccount->name.' '.$businessAccount->last_name:''}}</td>											
								</tr>
								<tr>
									<td>Web Url:</td>
									<td>{{isset($businessAccount) ? url('/').'/login/'.$businessAccount->web_url:''}}</td>
								</tr>
								<tr>
									<td>Email:</td>
									<td><a href="mailto:{{isset($businessAccount) ? $businessAccount->email:''}}"></a>{{isset($businessAccount) ? $businessAccount->email:''}}</td>
								</tr>
								<tr>
									<td>Phone:</td>
									<td>{{isset($businessAccount) ? $businessAccount->telephone:''}}</td>
								</tr>
								<tr>
									<td>Referral:</td>
									<td>{{isset($businessAccount) ? $businessAccount->referral:''}}</td>
								</tr>
								<tr>
									<td>Created Date:</td>
									<td>{{isset($businessAccount) ? $businessAccount->created_at->format('d-m-Y H:i:s'):''}}</td>
								</tr>
							</tbody>
						</table>
						<table class="table table-condensed table-hover">
							<thead>
								<tr>
									<th colspan="3">What are your expectations?</th>
								</tr>
							</thead>
							<tbody>
								@if($businessAccount->client_management == '1')
			                	<tr>
									<td>Client Management</td>
								</tr>
								@endif
								@if($businessAccount->business_support == '1')
								<tr>
									<td>Business Support</td>
								</tr>
								@endif
								@if($businessAccount->Knowledge == '1')
								<tr>
									<td>Knowledge</td>
								</tr>
								@endif
								@if($businessAccount->resources == '1')
								<tr>
									<td>Tools &amp; Resources</td>
								</tr>
								@endif
								@if($businessAccount->mentoring == '1')
								<tr>
									<td>Mentoring</td>
								</tr>
								@endif
							</tbody>
						</table>
						<table class="table table-condensed table-hover">
							<tbody>
								<tr>
									<td>Verification Status</td>
									<td>
										@if($businessAccount->confirmed == '0')
			                            <span class="label label-warning">In Process</span>
			                            @endif
			                            @if($businessAccount->confirmed == '1')
			                            <span class="label label-info">Activated</span>
			                            @endif
			                            @if($businessAccount->confirmed == '2')
			                            <span class="label label-warning">Under Review</span>
			                            @endif
			                            @if($businessAccount->confirmed == '3')
			                            <span class="label label-warning">On Hold</span>
			                            @endif
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

@stop