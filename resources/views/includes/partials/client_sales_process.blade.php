<div class="panel panel-white">
	<!-- start: PANEL HEADING -->
	<div class="panel-heading">
		<h5 class="panel-title">
			<span class="icon-group-left">
				<i class="clip-checkmark-2"></i>
			</span>
			Sales Process
			<span class="icon-group-right">
				<a class="btn btn-xs pull-right" href="#" data-toggle="modal" data-target="#salesProcSettModal"><!-- salesProcSettModal -->
					<i class="fa fa-wrench"></i>
				</a>
				<a class="btn btn-xs pull-right panel-collapse" href="#" data-panel-group="client-overview">
					<i class="fa fa-chevron-down"></i>
				</a>
			</span>
		</h5>
	</div>
	<!-- start: PANEL HEADING -->
	<!-- start: PANEL BODY -->
	@if($defaultAndComplServCount < 2)
	<div class="panel-body">
		{!! displayNonClosingAlert('info', 'Inbuilt services like Consultation, Benchmarking needs to be edited first.') !!}	    	
	</div>
	@else 
	<div class="panel-body panel-scroll mh-350">
		<ul class="todo" id="salesProcess">
			{!! Form::hidden('salesProcessCompleted', $client->sale_process_step) !!}
			{!! Form::hidden('stepsBooked', $stepsBooked) !!}

			{!! Form::hidden('consultationDate', $client->consultation_date,['data-realtime'=>'consultation']) !!}
			{!! Form::hidden('consultationExpDate', $client->consul_exp_date,['data-realtime'=>'consultation']) !!}
			{!! Form::hidden('salesProgress', $client->SalesCompSteps) !!}

			<?php
			$team = teamBookingSteps();
			$teamed = teamAttendSteps();
			$indiv = indivBookingSteps();
			$indived = indivAttendSteps();

			if($client->TeamEnabledCount){
				$compTeam = $client->salesProgress->whereIn('spp_step_numb', $team);
				$manualTeamCount = $compTeam->where('spp_comp_manual', 1)->count();
				$bookTeamCount = ($compTeam->count())-$manualTeamCount;

				$compTeamed = $client->salesProgress->whereIn('spp_step_numb', $teamed);
				$manualTeamedCount = $compTeamed->where('spp_comp_manual', 1)->count();
				$bookTeamedCount = ($compTeamed->count())-$manualTeamedCount;
			}
			else
				$manualTeamCount = $manualTeamedCount = $bookTeamCount = $bookTeamedCount = 0;

			if($client->IndivEnabledCount){
				$compIndiv = $client->salesProgress->whereIn('spp_step_numb', $indiv);
				$manualIndivCount = $compIndiv->where('spp_comp_manual', 1)->count();
				$bookIndivCount = ($compIndiv->count())-$manualIndivCount;

				$compIndived = $client->salesProgress->whereIn('spp_step_numb', $indived);
				$manualIndivedCount = $compIndived->where('spp_comp_manual', 1)->count();
				$bookIndivedCount = ($compIndived->count())-$manualIndivedCount;					
			}
			else
				$manualIndivCount = $manualIndivedCount = $bookIndivCount = $bookIndivedCount = 0;
			?>
			{!! Form::hidden('bookTeamCount', $bookTeamCount) !!}
			{!! Form::hidden('manualTeamCount', $manualTeamCount) !!}
			{!! Form::hidden('bookTeamedCount', $bookTeamedCount) !!}
			{!! Form::hidden('manualTeamedCount', $manualTeamedCount) !!}
			{!! Form::hidden('bookIndivCount', $bookIndivCount) !!}
			{!! Form::hidden('manualIndivCount', $manualIndivCount) !!}
			{!! Form::hidden('bookIndivedCount', $bookIndivedCount) !!}
			{!! Form::hidden('manualIndivedCount', $manualIndivedCount) !!}

			<?php $saleProcessSett = $client->SaleProcessSett; ?>
			<li>
				<a class="todo-actions clearfix" href="#" data-target-subview="contact" data-step-number="1">
					<!-- {!! renderSalesProcessSteps(['salesProcessCompleted' => $client->sale_process_step, 'stepNumb' => 1, 'stepText' => 'Contact', 'salesProcess' => $client->salesProcesses]) !!}  -->
					{!! renderSalesProcessSteps(['salesProgress' => $client->salesProgress, 'stepNumb' => 1, 'stepText' => 'Contact']) !!}
				</a>
			</li>
			<li>
				<a class="todo-actions clearfix callSubview" href="#" data-target-subview="calendar" data-step-number="2">
					<!-- {!! renderSalesProcessSteps(['salesProcessCompleted' => $client->sale_process_step, 'stepNumb' => 2, 'stepText' => 'Book consultation', 'salesProcess' => $client->salesProcesses]) !!} -->
					{!! renderSalesProcessSteps(['salesProgress' => $client->salesProgress, 'stepNumb' => 2, 'stepText' => 'Book consultation']) !!}
				</a>
			</li>
			<li>
				<a class="todo-actions clearfix" href="javascript:void(0)" data-step-number="3" data-booking-step="2" data-step-dependant="1"><!--data-step-dependant="2" -->
					<!-- {!! renderSalesProcessSteps(['salesProcessCompleted' => $client->sale_process_step, 'stepNumb' => 3, 'stepText' => 'Consultation', 'salesProcess' => $client->salesProcesses]) !!} -->
					{!! renderSalesProcessSteps(['salesProgress' => $client->salesProgress, 'stepNumb' => 3, 'stepText' => 'Consultation', 'consultationDate' => $client->consultation_date]) !!}
				</a>
			</li>
			<li class="{{showSaleStep($saleProcessSett, 4)}}">
				<a class="todo-actions clearfix callSubview" href="#" data-target-subview="calendar" data-step-number="4" data-step-dependant="2">
					<!-- {!! renderSalesProcessSteps(['salesProcessCompleted' => $client->sale_process_step, 'stepNumb' => 4, 'stepText' => 'Book Benchmark', 'salesProcess' => $client->salesProcesses]) !!} -->
					{!! renderSalesProcessSteps(['salesProgress' => $client->salesProgress, 'stepNumb' => 4, 'stepText' => 'Book Benchmark']) !!}
				</a>
			</li>
			<li class="{{showSaleStep($saleProcessSett, 5)}}">
				<a class="todo-actions clearfix" href="javascript:void(0)" data-step-number="5" data-booking-step="4" data-step-dependant="3">
					<!-- {!! renderSalesProcessSteps(['salesProcessCompleted' => $client->sale_process_step, 'stepNumb' => 5, 'stepText' => 'Benchmark', 'salesProcess' => $client->salesProcesses]) !!} -->
					{!! renderSalesProcessSteps(['salesProgress' => $client->salesProgress, 'stepNumb' => 5, 'stepText' => 'Benchmark']) !!}
				</a>
			</li>

			<?php
				/*$team = teamBookingSteps();
				$teamed = teamAttendSteps();
				$indiv = indivBookingSteps();
				$indived = indivAttendSteps();*/
				$teamCount = 0;
				$indivCount = 0;
				$prevBookingStep = 4;
				$prevAttendStep = 5;
				?>
				@foreach($client->SalesSessionOrder as $order)
				<?php 
				$value = explode('-', $order); 
				if($value[0] == 'team'){
					$stepNumb = $team[$teamCount];
					$attendNumb = $teamed[$teamCount];
					$teamCount++;
					$count = $teamCount;
					$stepName = 'T.E.A.M';
					$attendCheck = $teamed[0];
					$classId =  DB::table('staff_event_class_clients')->select('secc_sec_id')->where('secc_client_id',$client->id)->where('sales_step_number', $stepNumb)->whereNull('deleted_at')->first();
					$class = DB::table('staff_event_classes')->select('sec_date')->where('sec_id',$classId->secc_sec_id)->first();
                            if($class){
								$date = dbDateToDateString($class->sec_date);
							}	else {
									$date = null;
								}
				}
				else if($value[0] == 'indiv'){
					$stepNumb = $indiv[$indivCount];
					$attendNumb = $indived[$indivCount];
					$indivCount++;
					$count = $indivCount;
					$stepName = 'Individual';
					$attendCheck = $indived[0];
					$serviceDate =  DB::table('staff_event_single_services')->select('sess_date')->where('sess_client_id',$client->id)->where('sales_step_number', $stepNumb)->whereNull('deleted_at')->first();
                            if($serviceDate)
								$date = dbDateToDateString($serviceDate->sess_date);
								else {
									$date = null;
								}
				}
			
				$bookingDependStep = $prevBookingStep;
				$prevBookingStep = $stepNumb;	
				// $data['salesProcess'] = $client->salesProcesses;
				// $salesProcess = $data['salesProcess']->where('sp_step', $stepNumb)->first();				dd($salesProcess,$stepNumb);	
				$attendDependStep = $prevAttendStep;
				$prevAttendStep = $attendNumb;
				
						
				?>
				<li>
					<a class="todo-actions clearfix callSubview" href="#" data-target-subview="calendar" data-step-number="{{$stepNumb}}" data-step-dependant="{{$bookingDependStep}}" data-book-type="{{$value[0]}}" data-step-type="book">
						{!! renderSalesProcessSteps(['salesProgress' => $client->salesProgress, 'stepNumb' => $stepNumb, 'stepText' => 'Book '.$stepName.'  '.$count]) !!}
					</a>
				</li>
				<li class="{{showSaleStep($saleProcessSett, $attendCheck)}}">
				{{-- {{dd($stepNumb,$client->sale_process_step,$attendNumb)}} --}}
					<a class="todo-actions clearfix" href="javascript:void(0)" data-step-number="{{$attendNumb}}" data-booking-step="{{$stepNumb}}" data-step-dependant="{{$attendDependStep}}" data-book-type="{{$value[0]}}" data-step-type="attend"> 
						{!! renderSalesProcessSteps(['bookTeamCompleted' =>  $client->sale_process_step,'salesProgress' => $client->salesProgress, 'stepNumb' => $attendNumb, 'stepText' => $stepName.'  '.$count, 'bookDate' => $date,'bookStepNum'=>$stepNumb,'bookSalesProcess' => $client->salesProcesses]) !!}
					</a>
				</li>
				@endforeach
				
				<li class="{{showSaleStep($saleProcessSett, 18)}}">
					<a class="todo-actions clearfix" href="javascript:void(0)" data-step-number="18" data-step-dependant=""><!--9--> <!--{{ (($saleProcessSett['indivCount'])?17:11)}}-->
						<!-- {!! renderSalesProcessSteps(['salesProcessCompleted' => $client->sale_process_step, 'stepNumb' => 18, 'stepText' => 'Email pricing', 'salesProcess' => $client->salesProcesses]) !!} -->
						{!! renderSalesProcessSteps(['salesProgress' => $client->salesProgress, 'stepNumb' => 18, 'stepText' => 'Email pricing']) !!}
					</a>
				</li>
			</ul>
			<!-- <div class="form-group m-t-10 {{($client->sale_process_step < 9)?'hidden':''}}" id="clientStatusGroupUnderSalesProcess"> -->
				<div class="form-group m-t-10 {{($client->account_status != 'Active' && $client->account_status != 'Contra')?'hidden':''}}" id="clientStatusGroupUnderSalesProcess">
					<div>
						{!! Form::label('clientStatusUnderSalesProcess', 'Status', ['class' => 'strong']) !!}
					</div>
					<?php $clientStatus = clientStatuses(); ?>
					{!! Form::select('clientStatusUnderSalesProcess', $clientStatus, isset($client)?$client->account_status_backend:null, ['class' => 'form-control', 'data-realtime' => 'accStatus','data-prevdata'=>isset($client)?$client->account_status_backend:null]) !!}
				</div>
			</div>
			@endif
		</div> 

		<!-- start: Sales process settings Modal -->
		<div class="modal fade" id="salesProcSettModal" role="dialog">
			<div class="modal-dialog">   
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">Sales Process Settings</h4>
					</div>
					<div class="modal-body bg-white">
						{!! Form::model(null, ['method' => 'patch', 'route' => ['clients.salesProcSett', $client->id]]) !!}
						{!! Form::hidden('clientId', $client->id) !!}
						{!! Form::hidden('sales_process_data',$client->sale_process_setts)!!}
						<div class="row">
							<div class="col-md-6">
								<fieldset class="padding-15">
									<legend>General</legend>
									<div class="col-md-12">
										<div class="form-group">
											<div class="checkbox clip-check check-primary m-b-0">
												<input id="saleStep4" type="checkbox" name="saleStepGen[]" value="4" class="sales_checkbox">
												<label for="saleStep4">Book Benchmark</label>
											</div>
										</div>
									</div>
									<div class="col-md-12">
										<div class="form-group">
											<div class="checkbox clip-check check-primary m-b-0">
												<input id="saleStep5" type="checkbox" name="saleStepGen[]" value="5" class="sales_checkbox">
												<label for="saleStep5">Benchmark</label>
											</div>
										</div>
									</div>
									<div class="col-md-12">
										<div class="form-group">
											<div class="checkbox clip-check check-primary m-b-0">
												<input id="saleStepBookTeam" type="checkbox" name="saleStepSession[]" value="bookTeam" class="sales_checkbox">
												<label for="saleStepBookTeam">Book Team</label>
											</div>
										</div>
									</div>
									<div class="col-md-12">
										<div class="form-group">
											<div class="checkbox clip-check check-primary m-b-0">
												<input id="saleStepBookIndiv" type="checkbox" name="saleStepSession[]" value="bookIndiv" class="sales_checkbox">
												<label for="saleStepBookIndiv">Book Individual</label>
											</div>
										</div>
									</div>
									<div class="col-md-12">
										<div class="form-group">
											<div class="checkbox clip-check check-primary m-b-0">
												<input id="saleStep18" type="checkbox" name="saleStepGen[]" value="18" class="sales_checkbox">
												<label for="saleStep18">Email pricing</label>
											</div>
										</div>
									</div>
								</fieldset>

								<fieldset class="padding-15" id="sessionOrder">
									<legend>Order</legend>
									<div class="form-group">
										<label class="strong" for="referrer">Drag the following session steps relating to your specific needs</label>
										<div class="dd" id="salesNestable">
											<ol class="dd-list">
												@foreach($client->SalesSessionOrder as $order)
												<?php 
												$value = explode('-', $order); 
												if($value[0] == 'team')
													$stepName = 'Team';
												else if($value[0] == 'indiv')
													$stepName = 'Individual';
												?>
												<li class="dd-item {{$value[0]}}" data-id="{{$value[0]}}-{{$value[1]}}">
													<div class="dd-handle">Book {{$stepName}}</div>
												</li>
												@endforeach
											</ol>
										</div>
										<input type="hidden" name="salesNestable" />
									</div>		
								</fieldset>
							</div>
							<div class="col-md-6">
								<fieldset class="padding-15" id="bookTeam">
		            			<!-- <legend>Session</legend>
		      					<div class="form-group">
                                    <ul id="salesSessionTypeSelectable" class="clearfix p-l-0">
                                        <li class="col-xs-6 ui-widget-content ui-selected" data-val="team">Team</li>
                                        <li class="col-xs-6 ui-widget-content" data-val="indiv">Individual</li>
                                    </ul>
                                    <input name="salesSessionType" type="hidden" value="team">
                                </div> -->
                                <legend>Team</legend>
                                <div class="form-group clearfix">
                                	<label for="teamNumb" class="strong">Number</label>
                                	<select class="form-control onchange-set-neutral" name="teamNumb">
                                		<option value=""> -- Select -- </option>
                                		<option value="1">1</option>
                                		<option value="2">2</option>
                                		<option value="3">3</option>
                                		<option value="4">4</option>
                                		<option value="5">5</option>
                                	</select>
                                	<!-- <span class="error-block hidden text-primary">This field is required.</span> -->
                                </div>
                                <div class="form-group team-checkbox-field">
                                	<div class="checkbox clip-check check-primary m-b-0">
                                		<input id="saleStep11" type="checkbox" name="saleStepGen[]" value="11" class="sales_checkbox team ">
                                		<label for="saleStep11">T.E.A.M</label>
                                	</div>
                                </div>
			            		<!-- <div class="form-group hidden indiv-checkbox-field">
									<div class="checkbox clip-check check-primary m-b-0">
					    				<input id="saleStep17" type="checkbox" name="saleStepGen[]" value="17" class="sales_checkbox indiv ">
					    				<label for="saleStep17">Individual Completed</label>
					    			</div>
					    		</div>  -->			
					    	</fieldset>

					    	<fieldset class="padding-15" id="bookIndiv">
					    		<legend>Individual</legend>
					    		<div class="form-group clearfix">
					    			<label for="indivNumb" class="strong">Number</label>
					    			<select class="form-control onchange-set-neutral" name="indivNumb">
					    				<option value=""> -- Select -- </option>
					    				<option value="1">1</option>
					    				<option value="2">2</option>
					    				<option value="3">3</option>
					    				<option value="4">4</option>
					    				<option value="5">5</option>
					    			</select>
					    			<!-- <span class="error-block hidden text-primary">This field is required.</span> -->
					    		</div>
					    		<div class="form-group indiv-checkbox-field">
					    			<div class="checkbox clip-check check-primary m-b-0">
					    				<input id="saleStep17" type="checkbox" name="saleStepGen[]" value="17" class="sales_checkbox indiv ">
					    				<label for="saleStep17">Individual Completed</label>
					    			</div>
					    		</div> 			
					    	</fieldset>

					    	<?php 
					    	$consultExpDate = date('D, d M Y ', strtotime($client->consultation_date. ' + 15 days'));
					    	?>
					    	<fieldset class="padding-15" id="bookIndiv">
					    		<legend>Consultation</legend>
					    		<div class="form-group clearfix">
					    			<div class="form-group">
					    				{!! Form::label('consultExpDate', 'Expiration Date *', ['class' => 'strong']) !!}
					    				{!! Form::text('consultExpDate', $client->consul_exp_date ? date('D, d M Y ' ,strtotime($client->consul_exp_date)) : $consultExpDate, ['class' => 'form-control onchange-set-neutral', 'required', 'autocomplete' => 'off', 'readonly']) !!}
					    				<span class="help-block"></span>
					    			</div>
					    		</div>

					    	</fieldset>
					    </div>
		            	<!-- <div class="col-md-4">
		            		<div class="form-group">
								<div class="checkbox clip-check check-primary m-b-0">
				    				<input id="saleStep6" type="checkbox" name="saleStep[]" value="6" class="sales_checkbox team">
				    				<label for="saleStep6">Book T.E.A.M 1</label>
				    			</div>
		            		</div>
		            	</div>
		            	<div class="col-md-4">
		            		<div class="form-group">
								<div class="checkbox clip-check check-primary m-b-0">
				    				<input id="saleStep7" type="checkbox" name="saleStep[]" value="7" class="sales_checkbox team">
				    				<label for="saleStep7">Book T.E.A.M 2</label>
				    			</div>
		            		</div>
		            	</div>
		            	<div class="col-md-4">
		            		<div class="form-group">
								<div class="checkbox clip-check check-primary m-b-0">
				    				<input id="saleStep8" type="checkbox" name="saleStep[]" value="8" class="sales_checkbox team">
				    				<label for="saleStep8">Book T.E.A.M 3</label>
				    			</div>
		            		</div>
		            	</div>
		            	<div class="col-md-4">
		            		<div class="form-group">
								<div class="checkbox clip-check check-primary m-b-0">
				    				<input id="saleStep9" type="checkbox" name="saleStep[]" value="9" class="sales_checkbox team">
				    				<label for="saleStep9">Book T.E.A.M 4</label>
				    			</div>
		            		</div>
		            	</div>
		            	<div class="col-md-4">
		            		<div class="form-group">
								<div class="checkbox clip-check check-primary m-b-0">
				    				<input id="saleStep10" type="checkbox" name="saleStep[]" value="10" class="sales_checkbox team">
				    				<label for="saleStep10">Book T.E.A.M 5</label>
				    			</div>
		            		</div>
		            	</div>
		            	<div class="col-md-4">
		            		<div class="form-group">
								<div class="checkbox clip-check check-primary m-b-0">
				    				<input id="saleStep11" type="checkbox" name="saleStep[]" value="11" class="sales_checkbox team">
				    				<label for="saleStep11">T.E.A.M</label>
				    			</div>
		            		</div>
		            	</div>
		            	<div class="col-md-4">
		            		<div class="form-group">
								<div class="checkbox clip-check check-primary m-b-0">
				    				<input id="saleStep12" type="checkbox" name="saleStep[]" value="12" class="sales_checkbox indiv">
				    				<label for="saleStep12">Book Individual 1</label>
				    			</div>
		            		</div>
		            	</div>
		            	<div class="col-md-4">
		            		<div class="form-group">
								<div class="checkbox clip-check check-primary m-b-0">
				    				<input id="saleStep13" type="checkbox" name="saleStep[]" value="13" class="sales_checkbox indiv">
				    				<label for="saleStep13">Book Individual 2</label>
				    			</div>
		            		</div>
		            	</div>
		            	<div class="col-md-4">
		            		<div class="form-group">
								<div class="checkbox clip-check check-primary m-b-0">
				    				<input id="saleStep14" type="checkbox" name="saleStep[]" value="14" class="sales_checkbox indiv">
				    				<label for="saleStep14">Book Individual 3</label>
				    			</div>
		            		</div>
		            	</div>
		            	<div class="col-md-4">
		            		<div class="form-group">
								<div class="checkbox clip-check check-primary m-b-0">
				    				<input id="saleStep15" type="checkbox" name="saleStep[]" value="15" class="sales_checkbox indiv">
				    				<label for="saleStep15">Book Individual 4</label>
				    			</div>
		            		</div>
		            	</div>
		            	<div class="col-md-4">
		            		<div class="form-group">
								<div class="checkbox clip-check check-primary m-b-0">
				    				<input id="saleStep16" type="checkbox" name="saleStep[]" value="16" class="sales_checkbox indiv">
				    				<label for="saleStep16">Book Individual 5</label>
				    			</div>
		            		</div>
		            	</div>
		            	<div class="col-md-4">
		            		<div class="form-group">
								<div class="checkbox clip-check check-primary m-b-0">
				    				<input id="saleStep17" type="checkbox" name="saleStep[]" value="17" class="sales_checkbox indiv">
				    				<label for="saleStep17">Individual Completed</label>
				    			</div>
		            		</div>
		            	</div> -->
		            	
		            </div>
		            {!! Form::close() !!}
		        </div>
		        <div class="modal-footer">
		        	<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
		        	<input class="btn btn-primary" id="salesProcSettSub" type="submit" value="Save"> 
		        	<!-- <button type="button" class="btn btn-primary" id="salesProcSettSub">Save</button> -->
		        </div>  
		    </div>
		</div>      
	</div>
<!-- start: Sales process settings Modal -->