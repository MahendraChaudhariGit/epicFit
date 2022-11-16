@extends('super-admin.layout.master')
@section('page-title')
User Limit	
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
									<td>User Limit(upto):</td>
									<td>{{isset($userLimit)?$userLimit->maximum_users:''}}</td>											
								</tr>
								<tr>
									<td>Price</td>
									<td>${{isset($userLimit) ?$userLimit->price:''}}</td>
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