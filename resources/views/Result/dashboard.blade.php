@extends('Result.masters.app')

@section('page-title')
<span data-realtime="firstName">{{ Auth::user()->name }}</span> <span data-realtime="lastName">{{ Auth::user()->last_name }}</span>
@stop

@section('required-styles')
{!! Html::style('result/plugins/tooltipster-master/tooltipster.css') !!}
{!! Html::style('result/plugins/bootstrap-select-master/css/bootstrap-select.min.css') !!}
{!! Html::style('result/plugins/intl-tel-input-master/build/css/intlTelInput.css') !!}
{!! Html::style('result/plugins/Jcrop/css/jquery.Jcrop.min.css') !!}
{!! Html::style('result/css/custom.css') !!}
<!-- {!! Html::style('result/css/bootstrap-timepicker.min.css') !!} -->
<!--  -->

@stop

@section('content')
<!-- start: Pic crop Model -->
@include('includes.partials.pic_crop_model')
<!-- end: Pic crop Model -->
<style type="text/css">
	a.disabledform {
  /* Make the disabled links grayish*/
  color: gray;
  /* And disable the pointer events */
  pointer-events: none;
}
@media(min-width: 768px){
	#health-section-row .dash-graph canvas{
		height: 300px !important;
		width: auto !important;
		max-width: 100% !important;
	}
	.b-measurement canvas{
		max-height: 55vh !important;
		width: auto !important;
		max-width: 100% !important;
	}
}
</style>
<!-- Start: Waiting Shield -->
<div id="waitingShield" class="hidden text-center">
    <div>
        <i class="fa fa-circle-o-notch"></i>
    </div>
</div>
<!-- End: Waiting Shield -->

<!-- Start: First row -->
<div class="row pos-of-acc pos-of-acc1 pos-of-accc" style="">
	<!-- Start: Genaral section -->
    <div class="col-md-6 col-sm-6" id="app-section-row">
		<div class="panel panel-white no-radius load1 panel-white-me" id="visits">
			<div class="panel-heading panel-heading-me border-light" style="">
				<div class="panel-title">
					<div class="input-group pull-left top-decrease">
						<input type="hidden" id="dashboard-filter">
					</div>
					<div class="btn-group btn-group-xs pull-right rapidoDD">
			            <a class="btn  dropdown-toggle" data-toggle="dropdown" href="#" >
			              	<span id="app-section-text" class="rapidlitext">All</span> <span class="caret"></span>
			            </a>
			            <ul role="menu" class="dropdown-menu dropdown-light ">
			                <li><a href="javascript::void(0)" data-text="All" class="app-section">All</a></li>
			                <li><a href="javascript::void(0)" data-text="Appointments" class="app-section">Appointments</a></li>      
			     			<li><a href="javascript::void(0)" data-text="Goals" class="app-section">Goals</a></li> 
			     			<li><a href="javascript::void(0)" data-text="Activities" class="app-section">Activities</a></li> 
			            </ul>
			        </div>
				</div>
			</div>
			<div collapse="visits" class="panel-wrapper">
				<div class="panel panel-white m-b-zero">
					<div class="panel-group accordion minh-350 m-b-zero" id="accordionn">


						<!--Start: Appointment section -->
						<div id="appointments-section" class="panel panel-white app-section-area">
							<div class="panel-heading">
								<h5 class="panel-title">
									<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordionn" href="#collapseOne1" aria-expanded="true">
										<i class="icon-arrow"></i> Appointments
									</a>
								</h5>
							</div>
							<div id="collapseOne1" class="panel-collapse collapse in" aria-expanded="true" style="">
								<div class="panel-body">
									<table class="table" style="margin-bottom: 0px;">
										<tbody id="appointments-field">
										@if(count($appointments))
										@foreach($appointments as $appointment)
											<tr>
												<td>
													<div class="deshboard-date">{{ $appointment['date'] }}</div>
													<div class="fourth-staff">
														@if($appointment['type'] == 'class')
															<i class="fa fa-bullhorn" style="color:#ff0000"></i>
														@else
															<i class="fa fa-cog" style="color:#ff0000"></i>
														@endif 
														{{ $appointment['desc'] }}
													</div>
												</td>
											</tr>
										@endforeach
										@else
											<tr>
												<td>
													No record found.
												</td>
											</tr>
										@endif																	
										</tbody>
									</table>
								</div>
							</div>
						</div>
						<!--End: Appointment section -->

						<!--Start: Goal section -->
						<div id="goals-section" class="panel panel-white app-section-area">
							<div class="panel-heading">
								<h5 class="panel-title">
									<a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordionn" href="#collapseTwo2" aria-expanded="false"><i class="icon-arrow"></i> Goals</a>
								</h5>
							</div>
							<div id="collapseTwo2" class="panel-collapse collapse" aria-expanded="false" style="height:0px;">
								<div class="panel-body no-padding partition-light-grey height-400-me">
									<table class="table"  style="margin-bottom: 0px;">
										<thead>
											<tr role="row">
												{{-- <th class="center hidden-xxs">S. No.</th> --}}
												<th>Goal Name</th>
												<th>Due Date</th>
												<th class="">Progress</th>
											</tr>
										</thead>
										<tbody id="goals-field">
										@if(count($goals))
										<?php $i=1; ?>
										@foreach($goals as $goal)
											<tr>
												{{-- <td class="center hidden-xs">{{$i}}</td> --}}
												<td>{{ $goal['name'] }}</td>
												<td >{{ $goal['due_date'] }}</td>
												<td>
													<div class="progress progress-striped progress-xs"style="margin-bottom:10px">
					                                    <div class="progress-bar progress-bar-success" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="{{$goal['per']}}" style="width:{{$goal['per']}}% ">
					                                    </div>
					                                </div>
													<p class="progress-percentage"><strong>Milestones:</strong> {{$goal['per']}}%</p>
												</td>
											</tr>
										<?php $i++; ?>
										@endforeach
										@else
											<tr>
												<td>
													No record found.
												</td>
											</tr>
										@endif		
										</tbody>
									</table>
								</div>
							</div>
						</div>
						<!--End: Goal section -->

						<!--Start: Activities section -->
						<div id="activities-section" class="panel panel-white app-section-area">
							<div class="panel-heading">
								<h5 class="panel-title"><a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordionn" href="#collapseThree3" aria-expanded="false"><i class="icon-arrow"></i> Activity</a>
								</h5>
							</div>
							<div id="collapseThree3" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
								<div class="panel-body">
									<table class="table"  style="margin-bottom: 0px;">
										<tbody id="activities-field">
										@if(count($activities))
										@foreach($activities as $activity)
											<tr>
												<td>
													<div class="deshboard-date">{{ $activity['date'] }}</div>
													<div class="fourth-staff">
														<i class="fa fa-heartbeat" style="color:#ff0000"></i>
														{{ $activity['desc'] }}
													</div>
												</td>
											</tr>
										@endforeach
										@else
											<tr>
												<td>
													No record found.
												</td>
											</tr>
										@endif														
										</tbody>
									</table>
								</div>
							</div>
						</div>
						<!--End: Activities section -->
					</div>		
				</div>
			</div>
		</div>
	</div>
	<!-- End: Genaral section -->

	<!-- Start: Helath section -->
    <div class="col-md-6 col-sm-6" id="health-section-row">
		<div class="panel panel-white no-radius load1 panel-white-me dash-graph" id="visits">
			<div class="panel-heading panel-heading-me border-light">
				<div class="panel-title">
					<div class="pull-left">
						<span style="color:#fff; font-size:16px;"> Your Health </span>
					</div>
					<div class="btn-group btn-group-xs pull-right rapidoDD">
			            <a class="btn  dropdown-toggle" data-toggle="dropdown" href="#" >
			              	<span class="rapidlitext">Height</span> <span class="caret"></span>
			            </a>
			            <ul role="menu" class="dropdown-menu dropdown-light ">
			                <li><a href="javascript::void(0)" data-text="Height" class="health-section measurements" duration="6" bodypart="height"> Height </a></li>
			                <li><a href="javascript::void(0)" data-text="Chest" class="health-section measurements" duration="6" bodypart="chest"> Chest </a></li>      
			     			<li><a href="javascript::void(0)" data-text="Neck" class="health-section measurements" duration="6" bodypart="neck"> Neck </a></li> 
							 <li><a href="javascript::void(0)" data-text="Bicep R" class="health-section measurements" duration="6" bodypart="bicepR"> Bicep R </a></li> 
							 <li><a href="javascript::void(0)" data-text="Bicep L" class="health-section measurements" duration="6" bodypart="bicepL">  Bicep L  </a></li>
			                <li><a href="javascript::void(0)" data-text="Forearm R" class="health-section measurements" duration="6" bodypart="forearmR">  Forearm R  </a></li>      
							 <li><a href="javascript::void(0)" data-text="Forearm L" class="health-section measurements" duration="6" bodypart="forearmL">  Forearm L </a></li> 
							 
							 <li><a href="javascript::void(0)" data-text="Abdomen" class="health-section measurements" duration="6" bodypart="abdomen">  Abdomen  </a></li>
			                <li><a href="javascript::void(0)" data-text="Hip" class="health-section measurements" duration="6" bodypart="hip">  Hip  </a></li>      
							 <li><a href="javascript::void(0)" data-text="Thigh R" class="health-section measurements" duration="6" bodypart="thighR">  Thigh R  </a></li> 
							 
							 <li><a href="javascript::void(0)" data-text="Thigh L" class="health-section measurements" duration="6" bodypart="thighL">  Thigh L </a></li>
			                <li><a href="javascript::void(0)" data-text="Calf R" class="health-section measurements" duration="6" bodypart="calfR">  Calf R  </a></li>      
							 <li><a href="javascript::void(0)" data-text="Calf L" class="health-section measurements" duration="6" bodypart="calfL">  Calf L </a></li> 
							 
							 <li><a href="javascript::void(0)" data-text="Weight" class="health-section measurements" duration="6" bodypart="weight">  Weight  </a></li>
			                <li><a href="javascript::void(0)" data-text="BFP" class="health-section measurements-other" duration="6" bodypart="bfp">  BFP  </a></li>      
							 <li><a href="javascript::void(0)" data-text="SMM" class="health-section measurements-other" duration="6" bodypart="smm">  SMM  </a></li> 
							 
							 <li><a href="javascript::void(0)" data-text="BMR" class="health-section measurements-other" duration="6" bodypart="bmr">  BMR  </a></li>
			                <li><a href="javascript::void(0)" data-text="BMI" class="health-section measurements-other" duration="6" bodypart="bmi">  BMI  </a></li>      
							 <li><a href="javascript::void(0)" data-text="BFM" class="health-section measurements-other" duration="6" bodypart="bfm">  BFM  </a></li>
							 
							 <li><a href="javascript::void(0)" data-text="H/W Ratio" class="health-section measurements-other" duration="6" bodypart="hw">   H/W Ratio   </a></li>
			                <li><a href="javascript::void(0)" data-text="Vis Fat" class="health-section measurements-other" duration="6" bodypart="vis_fat">   Vis Fat   </a></li>      
							 <li><a href="javascript::void(0)" data-text="Pulse" class="health-section measurements-other" duration="6" bodypart="pulse">   Pulse   </a></li>
							 <li><a href="javascript::void(0)" data-text="Blood Pressure" class="health-section measurements-other" duration="6" bodypart="bp">   Blood Pressure   </a></li>
							 
			            </ul>
			        </div>
				</div>
			</div>
			@php
			// dd($data);
			$dataPoints = [];
			$xaxis = [];
					
			foreach ($data as $key => $value) {
				
				$xaxis[] = $value['date'];
				$dataPoints[] = $value['value'];
				

			}
			if($duration == 1){
				$unit = 'day';
			}else{
				$unit = 'month';
			}
			if($body_part == 'Weight'){
				$yaxis_label_suffix = $weight_unit;
			}elseif($body_part == 'Height'){
				$yaxis_label_suffix = $height_unit;
			}
			else{
				$yaxis_label_suffix = 'cm';
			}
			// dd($xaxis);
			// $minVal = min($dataPoints);
			// $maxVal = max($dataPoints)
			@endphp
			<div collapse="visits" class="panel-wrapper">
				<div class="panel-body">
					@if($bodypart == 'bp')
					@if (count($dataPoints) > 0)
					<div class="minh-350 my-div graph">
						<div class="health-section-area">
							<canvas id="myChart" height="200"></canvas>
						</div>
					</div>
					@else
					<div class="row" style="padding: 10%">
						<center><img src="{{ asset('no-graph.png') }}" alt="no-graph" width="100" srcset=""></center>
						<center><h4>There are no stats to graph right now.</h4></center>
						<center><h6>Try changing the timeframe, or adding new stats.</h6></center>
					</div>
					@endif
					@endif
					@if($bodypart != 'bp')
					@if (count($dataPoints) > 0)
					<div class="minh-350 my-div graph">
						<div class="health-section-area">
							<canvas id="myChart" height="200"></canvas>
						</div>
					</div>
					@else
					<div class="row" style="padding: 10%">
						<center><img src="{{ asset('no-graph.png') }}" alt="no-graph" width="100" srcset=""></center>
						<center><h4>There are no stats to graph right now.</h4></center>
						<center><h6>Try changing the timeframe, or adding new stats.</h6></center>
					</div>
					@endif
					@endif
					
					<div class="minh-350 my-div filter-div" style="display: none">
						<div class="health-section-area">
							{{-- <canvas id="filterChart"></canvas> --}}
						</div>
					</div>
					{{-- <div class="minh-350 my-div"> --}}
						{{-- <div class="health-section-area" id="BMI-section">
							<canvas id="myChart"></canvas>
						</div> --}}
						{{-- <div class="health-section-area" id="BMR-section" style="display: none;">
							<canvas id="bmrChart"></canvas>
						</div> --}}
					{{-- </div> --}}
				</div>
			</div>
		</div>
	</div>
	<!-- End: Helath section -->

</div>
<div class="row pos-of-acc pos-of-acc1 pos-of-accc" style="">
		<!-- Start: Genaral section -->
    <div class="col-md-6 col-sm-6" id="app-section-row">
		<div class="panel panel-white no-radius load1 panel-white-me" id="visits">
			<div class="panel-heading panel-heading-me border-light" style="">
				<div class="panel-title">
	                <div class="pull-left">
						<span style="color:#fff; font-size:16px;"> Consultation form status</span>
					</div>					
					<div class="input-group pull-left top-decrease">
						<input type="hidden" id="dashboard-filter">
					</div>
<!-- 					<div class="btn-group btn-group-xs pull-right rapidoDD">
			            <a class="btn  dropdown-toggle" data-toggle="dropdown" href="#" >
			              	<span id="app-section-text" class="rapidlitext">All</span> <span class="caret"></span>
			            </a>
			            <ul role="menu" class="dropdown-menu dropdown-light ">
			                <li><a href="javascript::void(0)" data-text="All" class="app-section">All</a></li>
			                <li><a href="javascript::void(0)" data-text="Appointments" class="app-section">Appointments</a></li>      
			     			<li><a href="javascript::void(0)" data-text="Goals" class="app-section">Goals</a></li> 
			     			<li><a href="javascript::void(0)" data-text="Activities" class="app-section">Activities</a></li> 
			            </ul>
			        </div> -->
				</div>
			</div>
			<div collapse="visits" class="panel-wrapper">
				<div class="panel panel-white m-b-zero">
					<div class="panel-group accordion minh-350 m-b-zero" id="accordionn">

						<!--- Start: Forms ---->
					<div id="appointments-section" class="panel panel-white app-section-area">
																<table class="table" style="margin-bottom: 0px;">
										<tbody>

		@if($forms->parq1 == "completed" && $forms->parq2 == "completed" && $forms->parq3 == "completed" && $forms->parq4 == "completed" && $forms->parq5 == "completed")
								<tr>
									<td>
									<a href="{{ url('epicprogress/AssessAndProgress/PersonalDetails') }}" >Personal Details</a>
														@if($forms->parq1 == 'completed')
									<i class="fa fa-check-circle pull-right" style="color:#ff0000"></i></a>
														@else
									<i class="fa fa-circle-thin pull-right" style="color:#ff0000"></i></a>
														@endif 


									</td>
								</tr>										
									
								<tr>
									<td>
									<a href="{{ url('epicprogress/AssessAndProgress/ExercisePreference') }}">Exercise Preference</a>
													@if($forms->parq2 == 'completed')
										<i class="fa fa-check-circle pull-right" style="color:#ff0000"></i>
													@else
										<i class="fa fa-circle-thin pull-right" style="color:#ff0000"></i>
													@endif 


									</td>
								</tr>
								<tr>
									<td>
									<a href="{{ url('epicprogress/AssessAndProgress/IllnessAndInjury') }}">Injury Profile & Family History</a>
													@if($forms->parq3 == 'completed')
												<i class="fa fa-check-circle pull-right" style="color:#ff0000"></i>
													@else
												<i class="fa fa-circle-thin pull-right" style="color:#ff0000"></i>
													@endif 


									</td>
								</tr>
								<tr>
									<td>
									<a href="{{ url('epicprogress/AssessAndProgress/PARQ') }}">PARQ</a>
													@if($forms->parq4 == 'completed')
													<i class="fa fa-check-circle pull-right" style="color:#ff0000"></i>
													@else
													<i class="fa fa-circle-thin pull-right" style="color:#ff0000"></i>
													@endif 


									</td>
								</tr>								
								<tr>
									<td>
											<a  href="{{ url('epicprogress/AssessAndProgress/GoalAndMotivation') }}">Goals & Motivation</a>
											@if($forms->parq5 == 'completed')
											<i class="fa fa-check-circle pull-right"style="color:#ff0000"></i>
													@else
											<i class="fa fa-circle-thin" style="color:#ff0000"></i>
											@endif 
									</td>
								</tr>												


	    @else
								
								<tr>
									<td>
									<a href="{{ url('epicprogress/AssessAndProgress/PersonalDetails') }}" >Personal Details</a>
														@if($forms->parq1 == 'completed')
									<i class="fa fa-check-circle pull-right" style="color:#ff0000"></i></a>
														@else
									<i class="fa fa-circle-thin pull-right" style="color:#ff0000"></i></a>
														@endif 


									</td>
								</tr>	    										
								<tr>
									<td>

								@if($forms->parq1 == 'pending')
								<a href="{{ url('epicprogress/AssessAndProgress/
									ExercisePreference') }}" class="disabledform" tabindex="-1">Exercise Preference</a>
								@else
									<a href="{{ url('epicprogress/AssessAndProgress/
									ExercisePreference') }}">Exercise Preference</a>
								@endif
													@if($forms->parq2 == 'completed')
										<i class="fa fa-check-circle pull-right" style="color:#ff0000"></i>
													@else
										<i class="fa fa-circle-thin pull-right" style="color:#ff0000"></i>
													@endif 


									</td>
								</tr>
								<tr>
									<td>
								@if($forms->parq2 == 'pending')
								<a href="{{ url('epicprogress/AssessAndProgress/IllnessAndInjury') }}" class="disabledform" tabindex="-1">Injury Profile & Family History</a>
								@else
								<a href="{{ url('epicprogress/AssessAndProgress/IllnessAndInjury') }}">Injury Profile & Family History</a>
								@endif
													@if($forms->parq3 == 'completed')
												<i class="fa fa-check-circle pull-right" style="color:#ff0000"></i>
													@else
												<i class="fa fa-circle-thin pull-right" style="color:#ff0000"></i>
													@endif 


									</td>
								</tr>
								<tr>
									<td>
								@if($forms->parq3 == 'pending')
                                <a href="{{ url('epicprogress/AssessAndProgress/PARQ') }}"
                                class="disabledform" tabindex="-1">PARQ</a>
                                @else
                                <a href="{{ url('epicprogress/AssessAndProgress/PARQ') }}">PARQ</a>
								@endif	

													@if($forms->parq4 == 'completed')
													<i class="fa fa-check-circle pull-right" style="color:#ff0000"></i>
													@else
													<i class="fa fa-circle-thin pull-right" style="color:#ff0000"></i>
													@endif 


									</td>
								</tr>								
								<tr>
									<td>
								@if($forms->parq4 == 'pending')
								<a  href="{{ url('epicprogress/AssessAndProgress/GoalAndMotivation') }}" class="disabledform" tabindex="-1">Goals & Motivation</a>
                                @else
								<a  href="{{ url('epicprogress/AssessAndProgress/GoalAndMotivation') }}">Goals & Motivation</a>
								@endif	

											@if($forms->parq5 == 'completed')
											<i class="fa fa-check-circle pull-right"style="color:#ff0000"></i>
													@else
											<i class="fa fa-circle-thin pull-right" style="color:#ff0000"></i>
											@endif 


									</td>
								</tr>										
		@endif
										</tbody>
									</table>

				<!-- <a href="{{ url('epicprogress/AssessAndProgress/PersonalDetails') }}" ></a> -->

						</div>
						<!--- End: Forms --->

					</div>		
				</div>
			</div>
		</div>
	</div>
	<!-- End: Genaral section -->
</div>
<!-- End: First Row -->

<!-- Start: Second Row -->

<div class="row pos-of-acc pos-of-accc">
	<!-- End: Benchmark Section -->
	@if(in_array('benchmark', $clientSelectedMenus))
	<div class="col-md-6 col-sm-6" class="{{in_array('benchmark', $clientSelectedMenus)?'':'disable'}}">
		<div class="panel panel-white no-radius load1 panel-white-me" id="visits">
			<div class="panel-heading panel-heading-me border-light" style="">
				<h4 class="panel-title"> Benchmark </h4>
			</div>
			<div collapse="visits" class="panel-wrapper">
				<div class="panel-body">
					<div class="minh-350">
						<div class="panel-collapse collapse in" id="" aria-expanded="true" style="">
							<div class="panel-body no-padding ">
								<table class="table" style="padding-right: 10px;padding-left: 10px" id="benchmark-table">
									<thead>
										<tr role="row">
											<th class="center  hidden-xxs">S. No.</th>
											<th>Date</th>
											<th>Time</th>
											<th class="center">Actions</th>
										</tr>
									</thead>
									<tbody>
									@if($benchmarks->count())
									<?php $i = 1; ?>
									@foreach($benchmarks as $benchmark)
										<tr>
											<td class="center  hidden-xxs">{{$i}}</td>
											<td>{{ dbDateToDateString($benchmark->nps_day) }}</td>
											<td> {{ $benchmark->nps_time_hour }} Hour {{ $benchmark->nps_time_min }} Minutes</td>
											<td class="center">
												<a href="#" class="btn btn-xs btn-default tooltips benchmark-view-btn" data-placement="top" data-original-title="View" data-id="{{ $benchmark->id }}">
													<i class="fa fa-share" style="color:#ff4401;"></i>
												</a>
											</td>
										</tr>
										<?php $i++; ?>
									@endforeach
									@else
										<tr>
											<td colspan="4">No record found</td>
										</tr>
									@endif	
									</tbody>
								</table>
								<div id="benchmark-div" style="padding:10px; display:none;">
									<button class="btn btn-default pull-right" id="close-benchmark-btn">Close</button>
									@include('Result.partials.benchmark_overview')
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	@endif
	<!-- End: Benchmark Section -->

	<!-- Start: Invoice Section -->
@if(in_array('invoice', $clientSelectedMenus))
	<div class="col-md-6 col-sm-6" class="{{in_array('invoice', $clientSelectedMenus)?'':'disable'}}">
		<div class="panel panel-white no-radius load1 panel-white-me" id="visits">
			<div class="panel-heading panel-heading-me border-light">
				<h4 class="panel-title"> Invoices </h4>
			</div>
			<div collapse="visits" class="panel-wrapper">
				<div class="panel-body">
					<div class="minh-350">
						<div id="accordion" class="panel-group accordion accordion-white no-margin">
							<div class="panel no-radius">
								<div class="panel-heading">
									<h4 class="panel-title">
										<a href="#collapseOne" data-parent="#accordion" data-toggle="collapse" class="accordion-toggle padding-15">
											<i class="icon-arrow"></i>Unpaid 
										</a>
									</h4>
								</div>
								<div class="panel-collapse collapse in" id="collapseOne">
									<div class="panel-body no-padding table-top-border">
										<table class="table">
											<thead>
												<tr role="row">
													<th>Invoice #</th>
													<th>Appointment Date & Time</th>
													<th>Due Date</th>
													<th class="">Amount</th>
												</tr>
											</thead>
											<tbody>
											@if($unpaid_invoice->count())
											@foreach($unpaid_invoice as $unpaidInv)
												<tr>
													<td>{{ $unpaidInv->inv_id }}</td>
													<td>
														@if($unpaidInv['appointment_date_time'])
															{{ dbDateToDateString($unpaidInv['appointment_date_time'], 'dateString') }}
															{{ dbTimeToTimeString($unpaidInv['appointment_date_time'], 'dateString') }}
														@endif
													</td>
													<td>{{ dbDateToDateString($unpaidInv->inv_due_date) }}</td>
													<td>{{ $unpaidInv->inv_total }}</td>
												</tr>
											@endforeach
											@else
												<tr>
													<td colspan="3">No record found</td>
												</tr>
											@endif
											</tbody>
										</table>
									</div>
								</div>
							</div>
							<div class="panel no-radius">
								<div class="panel-heading">
									<h4 class="panel-title">
										<a href="#collapseTwo" data-parent="#accordion" data-toggle="collapse" class="accordion-toggle padding-15 collapsed"><i class="icon-arrow"></i>Paid
										</a>
									</h4>
								</div>
								<div class="panel-collapse collapse" id="collapseTwo">
									<div class="panel-body no-padding table-top-border">
										<table class="table">
											<thead>
												<tr role="row">
													<th>Invoice #</th>
													<th>Appointment Date & Time</th>
													<th>Due Date</th>
													<th class="">Amount</th>
												</tr>
											</thead>
											<tbody>
											@if($paid_invoice->count())
											@foreach($paid_invoice as $paidInv)
												<tr>
													<td>{{ $paidInv->inv_id }}</td>
													<td>
														@if($paidInv['appointment_date_time'])
															{{ dbDateToDateString($paidInv['appointment_date_time'], 'dateString') }}
															{{ dbTimeToTimeString($paidInv['appointment_date_time'], 'dateString') }}
														@endif
													</td>
													<td>{{ dbDateToDateString($paidInv->inv_due_date) }}</td>
													<td>{{ $paidInv->inv_total }}</td>
												</tr>
											@endforeach
											@else
												<tr>
													<td colspan="3">No record found</td>
												</tr>
											@endif
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- End: Invoice Section -->
</div>
@endif
<!-- End: Second Row -->
@endsection

@section('script')
{!! Html::script('result/js/jquery-ui.min.js') !!}
{!! Html::script('result/plugins/summernote/dist/summernote.min.js') !!}
{!! Html::script('result/plugins/bootstrap-rating/bootstrap-rating.min.js') !!}
{!! Html::script('result/plugins/bootstrap3-typeahead/js/bootstrap3-typeahead.min.js') !!}  
{!! Html::script('result/plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.min.js') !!}
{!! Html::script('result/js/bootstrap-timepicker.js') !!}
{!! Html::script('result/plugins/tooltipster-master/jquery.tooltipster.min.js') !!}
{!! Html::script('result/plugins/bootstrap-select-master/js/bootstrap-select.min.js') !!}
{!! Html::script('result/plugins/intl-tel-input-master/build/js/utils.js') !!}
{!! Html::script('result/plugins/intl-tel-input-master/build/js/intlTelInput.js') !!}
{!! Html::script('result/plugins/jquery-validation/jquery.validate.min.js') !!}
{!! Html::script('result/plugins/Jcrop/js/jquery.Jcrop.min.js') !!}
{!! Html::script('result/plugins/Jcrop/js/script.js') !!}
{!! Html::script('result/plugins/chartjs/dist/Chart.bundle.min.js') !!}
{!! Html::script('result/js/helper.js?v='.time()) !!}
{!! Html::script('result/js/dashboard.js?v='.time()) !!}
{!! Html::script('result/js/dashboard-chart.js?v='.time()) !!}


<script>
	$("#dashboard-filter").datepicker({
        showOn: 'button',
        buttonText: moment().format('D MMM YYYY'),
        buttonImageOnly: false,
        dateFormat:"d M yy",
        
        onSelect: function( newText ){
            $('.ui-datepicker-trigger').text(newText);
            //var dropDownName=$('.rapidodd').text();
            //var triggerChange = $('.rapidoDropdown li a').filter(function () { return $(this).html() == dropDownName; });
            //$(triggerChange).trigger('click');
            getAppSectionData(newText);
        }
    });

   $('.ui-datepicker-trigger').addClass('btn btn-sm btn-transparent-white');
   
    $(document).ready(function(){
		$('.tooltips').tooltipster();
	})
	$(document).on('click','.measurements', function(){
		
		var duration = $(this).attr('duration');
		var bodypart = $(this).attr('bodypart');
		// alert(duration);alert(bodypart);
		$.ajax({
			url: public_url + 'filter-body-measurement/' + bodypart + '/' + duration,
			method: "get",
			success: function (data) {
				$(".graph").hide();
				$(".filter-div").show();
				$(".filter-div").html(data)
			}
		});
	})

	$(document).on('click','.measurements-other', function(){
		var duration = $(this).attr('duration');
		var bodypart = $(this).attr('bodypart');
		$.ajax({
			url: public_url + 'filter-personal-stastic/' + bodypart + '/' + duration,
			method: "get",
			success: function (data) {
				$(".graph").hide();
				$(".filter-div").show();
				$(".filter-div").html(data)
			}
		});
	})
	
	let draw = Chart.controllers.line.prototype.draw;
	Chart.controllers.line = Chart.controllers.line.extend({
    draw: function() {
        draw.apply(this, arguments);
        let ctx = this.chart.chart.ctx;
        let _stroke = ctx.stroke;
        ctx.stroke = function() {
            ctx.save();
            ctx.shadowColor = '#D7D4D4';
            ctx.shadowBlur = 6;
			ctx.shadowOffsetX = 0;
			ctx.shadowOffsetY = 10;
            _stroke.apply(this, arguments)
            ctx.restore();
        }
    }
});
	var ctx = document.getElementById('myChart').getContext('2d');
	var myChart = new Chart(ctx, {
		type: 'line',
		data: {
			labels: <?php echo json_encode($xaxis); ?>,
			datasets: [{
				
				backgroundColor:'transparent',
				borderColor: '#FF0000',
				// borderColor: '#E56590',
				data:  <?php echo json_encode($dataPoints); ?>,
				label: '{{ $body_part }}',
			}]
		},
		options: {
			legend: {
				display: true,
				position: 'bottom',
			},
			tooltips: {
				mode: 'index',
			},
			scales: {
				yAxes: [{
					ticks: {
						beginAtZero: false,
						// stepSize: 1,
						// min: @php echo $minVal - 1.0; @endphp,
						// max: @php echo $maxVal + 1.0; @endphp,
						callback: function (value, index, values) {
                           return value + ' {{ $yaxis_label_suffix }}'  ;
						},
						// type: 'time',
						// time: {
						// 	stepSize: 1,
						// 	// tooltipFormat: 'DD/MM/YY',
						// }
					}
				}],
				xAxes: [{
					gridLines: { 
						display: false 
					} ,
					ticks: {
						autoskip: true,
					},
					type: 'time',
                    time: {
						unit: '{{ $unit }}',
						stepSize: '{{$stepSize}}',
						min:'{{$startOfTheMonth}}',
						max:'{{$endOfTheMonth}}',
                    }
				}]
			}
		}
	});
</script>
@stop

@section('script-handler-for-this-page')
	DashboardChart.init();
@stop