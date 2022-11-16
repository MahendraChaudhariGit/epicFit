<div class="container-fluid container-fullw bg-white">
	<div class="row">
		<div class="col-md-12">
			<!-- <h5 class="over-title margin-bottom-15">Wizard <span class="text-bold">demo</span></h5> -->
			<!-- <p>
				Some textboxes in this example is required.
			</p> -->
			<!-- start: WIZARD FORM -->
			<form action="#" role="form" class="smart-wizard" id="businessplan-form" novalidate="novalidate">
			<input type="hidden" name="businessplan_id" value="{{($businessplan)?$businessplan->bp_id:0}}" />
			<div id="bunisesspalnWizard" class="swMain" >
					<!-- start: WIZARD SEPS -->
					<ul class="anchor" style="position: static;">
						<li>
							<a href="#step-1" class="selected" isdone="1" rel="1">
								<div class="stepNumber">
									1
								</div>
								<span class="stepDesc"><small> Executive Summmary </small></span>
							</a>
						</li>
						<li>
							<a href="#step-2" class="disabled" isdone="0" rel="2">
								<div class="stepNumber">
									2
								</div>
								<span class="stepDesc"> <small> Company Summary </small></span>
							</a>
						</li>
						<li>
							<a href="#step-3" class="disabled" isdone="0" rel="3">
								<div class="stepNumber">
									3
								</div>
								<span class="stepDesc"> <small> Services and Products </small> </span>
							</a>
						</li>
						<li>
							<a href="#step-4" class="disabled" isdone="0" rel="4">
								<div class="stepNumber">
									4
								</div>
								<span class="stepDesc"> <small> Market Analysis Summary </small> </span>
							</a>
						</li>
						<li>
							<a href="#step-5" class="disabled" isdone="0" rel="5">
								<div class="stepNumber">
									5
								</div>
								<span class="stepDesc"> <small> Marketing Strategy and Business Implementation </small> </span>
							</a>
						</li>
						<li>
							<a href="#step-6" class="disabled" isdone="0" rel="6">
								<div class="stepNumber">
									6
								</div>
								<span class="stepDesc"> <small> Management Summary </small> </span>
							</a>
						</li>
						<li>
							<a href="#step-7" class="disabled" isdone="0" rel="7">
								<div class="stepNumber">
									7
								</div>
								<span class="stepDesc"> <small> Financial Plan </small> </span>
							</a>
						</li>
						<!-- <li>
							<a href="#step-7" class="disabled" isdone="0" rel="7">
								<div class="stepNumber">
									7
								</div>
								<span class="stepDesc"> <small> Financial Plan </small> </span>
							</a>
						</li> -->
					</ul>
					
			<div class="stepContainer" style="height: 559px;">
			    <!-- Start: step-1 Executive Summary FORM WIZARD ACCORDION -->
				    <div id="step-1" class="content" data-group='ex_summary' style="display: block;">
						<div class="row">
						    <div class="col-md-12"> 
								<div class="panel-group epic-accordion" >
									<div class="panel panel-white">
			                            <div class="panel-heading">
			                                <h5 class="panel-title">
			                                    <span class="icon-group-left">
			                                    	<i class="fa fa-ellipsis-v"></i>
			                                    </span> 
			                                    Company 
			                                    <span class="icon-group-right">
			                                    	<a class="btn btn-xs pull-right" href="#">
	                                                    <i class="fa fa-wrench"></i>
	                                                </a>
	                                                <a class="btn btn-xs pull-right panel-collapse closed first-col" href="#" data-panel-group="executive-summary">
	                                                    <i class="fa fa-chevron-down"></i>
	                                                </a>

			                                    	<!-- <i class="fa fa-wrench pull-right"></i>
			                                    	<a class="btn btn-xs pull-right panel-collapse" href="#" data-panel-group="epic-accordion">
                        							<i class="fa fa-chevron-up"></i>
               					 					</a> -->
			                                    </span>
			                                </h5>

			                            </div>
			                            <div class="panel-body">
	                                        <div class="row">
	                                        <!-- START: NOTES TEXT AREA --> 
													<div class="col-md-12">
														<textarea class="ckeditor form-control" cols="10" rows="10" name='company'>{!! ($businessplan)?$businessplan->bp_company:'' !!}
														</textarea>	
													</div>
												
	                                        <!-- END: NOTES TEXT AREA -->  
	                                        </div>
	                                        
	                                        <!-- <div class="row m-t-20">
	                                            <div class="col-sm-6"></div>
	                                            <div class="col-sm-6">
	                                                <div class="form-group">
	                                                    <button data-stepval='1' class="btn btn-primary btn-o bp-next-step btn-wide pull-right bp-next-1">
	                                                        Next <i class="fa fa-arrow-circle-right"></i>
	                                                    </button>
	                                                </div>
	                                            </div>
	                                        </div> -->
			                                    
			                            </div>
			                        </div>
			                    	<!-- END :BUSINESS PLAN ACCORDIAN STEP 1 -->    
			                    	<!-- START :BUSINESS PLAN ACCORDIAN STEP 2 -->      
			                        <div class="panel panel-white">
			                            <div class="panel-heading">
			                                <h5 class="panel-title">
			                                    <span class="icon-group-left"><i class="fa fa-ellipsis-v"></i></span> Services & Products <!-- <span class="icon-group-right"><i class="fa fa-wrench pull-right"></i>
			                                    <a class="btn btn-xs pull-right panel-collapse closed" href="#" data-panel-group="client-overview">
                                                    <i class="fa fa-chevron-down"></i>
                                                </a>
			                                    </span> -->
			                                    <span class="icon-group-right">
				                                    <a class="btn btn-xs pull-right" href="#">
	                                                    <i class="fa fa-wrench"></i>
	                                                </a>
	                                                <a class="btn btn-xs pull-right panel-collapse" href="#" data-panel-group="executive-summary">
	                                                    <i class="fa fa-chevron-down"></i>
	                                                </a>
		                                        </span>
			                                </h5>
			                            </div>
			                            <div class="panel-body">
			                                       <div class="row">
			                                            <div class="col-md-12">
																<textarea class="ckeditor form-control" cols="10" rows="10" name='services_products'>{!! ($businessplan)?$businessplan->bp_services_products:'' !!}</textarea>
																
														</div>
			                                        </div>
			                                        
			                                        <!-- <div class="row m-t-20">
			                                            <div class="col-sm-6">
			                                                <div class="form-group">
			                                                    <button data-stepval='2' class="btn btn-primary btn-o bp-back-step btn-wide pull-left">
			                                                        <i class="fa fa-arrow-circle-left"></i> Back 
			                                                    </button>
			                                                </div>
			                                                <span></span>
			                                            </div>
			                                            <div class="col-sm-6">
			                                                <div class="form-group">
			                                                    <button data-stepval='2' class="btn btn-primary btn-o bp-next-step btn-wide pull-right">
			                                                        Next <i class="fa fa-arrow-circle-right"></i>
			                                                    </button>
			                                                    <span></span>
			                                                </div>
			                                            </div>
			                                        </div> -->
			                                   
			                                </div>
			                            </div>
			                        
			                    <!-- END :BUSINESS PLAN ACCORDIAN STEP 2 -->
			                    <!-- START :BUSINESS PLAN ACCORDIAN STEP 3 --> 
			                    	<div class="panel panel-white">
			                            <div class="panel-heading">
			                                <h5 class="panel-title">
			                                    <span class="icon-group-left"><i class="fa fa-ellipsis-v"></i></span> Market Analysis 
			                                    <span class="icon-group-right">
			                                    <!-- <i class="fa fa-wrench pull-right"></i><!-- <i class="fa fa-chevron-down pull-right"></i> -->
			                                    <!-- <a class="btn btn-xs pull-right panel-collapse" href="#" data-panel-group="epic-accordion">
                        						<i class="fa fa-chevron-up"></i>
               					 				</a> -->
               					 				<a class="btn btn-xs pull-right" href="#">
	                                                <i class="fa fa-wrench"></i>
                                                </a>
                                                <a class="btn btn-xs pull-right panel-collapse" href="#" data-panel-group="executive-summary">
                                                    <i class="fa fa-chevron-down"></i>
                                                </a>
			                                    </span>
			                                </h5>
			                            </div>
			                            <div class="panel-body">
			                                        <div class="row">
			                                           <div class="col-md-12">
															<textarea class="ckeditor form-control" cols="10" rows="10" name='market_analysis'>
																{!! ($businessplan)?$businessplan->bp_market_analysis:'' !!}
															</textarea>	
														</div>
			                                        </div>
			                                        
			                                        <!-- <div class="row m-t-20">
			                                            <div class="col-sm-6">
			                                                <div class="form-group">
			                                                    <button data-stepval='3' class="btn btn-primary btn-o bp-back-step btn-wide pull-left">
			                                                        <i class="fa fa-arrow-circle-left"></i> Back
			                                                    </button>
			                                                </div>
			                                                <span></span>
			                                            </div>
			                                            <div class="col-sm-6">
			                                                <div class="form-group">
			                                                    <button data-stepval='3' class="btn btn-primary btn-o bp-next-step btn-wide pull-right">
			                                                        Next <i class="fa fa-arrow-circle-right"></i>
			                                                    </button>
			                                                    <span></span>
			                                                </div>
			                                            </div>
			                                        </div> -->
			                                   
			                                </div>
			                            </div>
			                    <!-- END :BUSINESS PLAN ACCORDIAN STEP 3 --> 
			                    <!-- START :BUSINESS PLAN ACCORDIAN STEP 4 --> 
			                    	<div class="panel panel-white">
			                            <div class="panel-heading">
			                                <h5 class="panel-title">
			                                    <span class="icon-group-left"><i class="fa fa-ellipsis-v"></i></span> Business Strategy 
			                                    <span class="icon-group-right">
			                                    <!-- <i class="fa fa-wrench pull-right"></i>
			                                    <a class="btn btn-xs pull-right panel-collapse" href="#" data-panel-group="epic-accordion">
                        						<i class="fa fa-chevron-up"></i>
               					 				</a> -->
	               					 				<a class="btn btn-xs pull-right" href="#">
		                                                <i class="fa fa-wrench"></i>
	                                                </a>
	                                                <a class="btn btn-xs pull-right panel-collapse" href="#" data-panel-group="executive-summary">
	                                                    <i class="fa fa-chevron-down"></i>
	                                                </a>
			                                    </span>
			                                </h5>
			                            </div>
			                            <div class="panel-body">
			                                 <div class="row">
			                                            <div class="col-md-12">
															<textarea class="ckeditor form-control" cols="10" rows="10" name='business_stratergy'>
																{!! ($businessplan)?$businessplan->bp_business_stratergy:'' !!}
															</textarea>	
													    </div> 
			                                        </div>
			                                        
			                                        <!-- <div class="row m-t-20">
			                                            <div class="col-sm-6">
			                                                <div class="form-group">
			                                                    <button data-stepval='4' class="btn btn-primary btn-o bp-back-step btn-wide pull-left">
			                                                        <i class="fa fa-arrow-circle-left"></i> Back
			                                                    </button>
			                                                </div>
			                                                <span></span>
			                                            </div>
			                                            <div class="col-sm-6">
			                                                <div class="form-group">
			                                                    <button data-stepval='4' class="btn btn-primary btn-o bp-next-step btn-wide pull-right">
			                                                        Next <i class="fa fa-arrow-circle-right"></i>
			                                                    </button>
			                                                    <span></span>
			                                                </div>
			                                            </div>
			                                        </div> -->
			                                    
			                                </div>
			                            </div>
			                    <!-- END :BUSINESS PLAN ACCORDIAN STEP 4 -->
			                    <!-- START :BUSINESS PLAN ACCORDIAN STEP 5 -->
			                    	<div class="panel panel-white">
			                            <div class="panel-heading">
			                                <h5 class="panel-title">
			                                    <span class="icon-group-left"><i class="fa fa-ellipsis-v"></i></span> Management  
			                                    <span class="icon-group-right">
			                                    <!-- <i class="fa fa-wrench pull-right"></i>
			                                    <a class="btn btn-xs pull-right panel-collapse" href="#" data-panel-group="epic-accordion">
                        						<i class="fa fa-chevron-up"></i>
               					 				</a> -->
	               					 				<a class="btn btn-xs pull-right" href="#">
		                                                <i class="fa fa-wrench"></i>
	                                                </a>
	                                                <a class="btn btn-xs pull-right panel-collapse" href="#" data-panel-group="executive-summary">
	                                                    <i class="fa fa-chevron-down"></i>
	                                                </a>
			                                    </span>
			                                </h5>
			                            </div>
			                            <div class="panel-body">
			                                        <div class="row">
			                                            <div class="col-md-12">
															<textarea class="ckeditor form-control" cols="10" rows="10" name='management'>
																{!! ($businessplan)?$businessplan->bp_management:'' !!}
															</textarea>	
													    </div> 
			                                        </div>
			                                        
			                                        <!-- <div class="row m-t-20">
			                                            <div class="col-sm-6">
			                                                <div class="form-group">
			                                                    <button data-stepval='5' class="btn btn-primary btn-o bp-back-step btn-wide pull-left">
			                                                        <i class="fa fa-arrow-circle-left"></i> Back
			                                                    </button>
			                                                </div>
			                                                <span></span>
			                                            </div>
			                                            <div class="col-sm-6">
			                                                <div class="form-group">
			                                                    <button data-stepval='5' class="btn btn-primary btn-o bp-next-step btn-wide pull-right">
			                                                        Next <i class="fa fa-arrow-circle-right"></i>
			                                                    </button>
			                                                    <span></span>
			                                                </div>
			                                            </div>
			                                        </div> -->
			                                   
			                                </div>
			                            </div>
			                       
			                    <!-- END :BUSINESS PLAN ACCORDIAN STEP 5 -->
			                    <!-- START :BUSINESS PLAN ACCORDIAN STEP 6 -->
			                    	<div class="panel panel-white">
			                            <div class="panel-heading" data-step="2">
			                                <h5 class="panel-title">
			                                    <span class="icon-group-left"><i class="fa fa-ellipsis-v"></i></span> Financial Plan  
			                                    <span class="icon-group-right">
			                                    <!-- <i class="fa fa-wrench pull-right"></i>
			                                    <a class="btn btn-xs pull-right panel-collapse closed" href="#" data-panel-group="client-overview">
                                                    <i class="fa fa-chevron-down"></i>
                                                </a> -->
	                                                <a class="btn btn-xs pull-right" href="#">
			                                                <i class="fa fa-wrench"></i>
	                                                </a>
	                                                <a class="btn btn-xs pull-right panel-collapse" href="#" data-panel-group="executive-summary">
	                                                    <i class="fa fa-chevron-down"></i>
	                                                </a>
			                                    </span>
			                                </h5>
			                            </div>
			                            <div class="panel-body">

			                                        <div class="row">
			                                           <div class="col-md-12">
															<textarea class="ckeditor form-control" cols="10" rows="10" name="financial_plan">
																{!! ($businessplan)?$businessplan->bp_financial_plan:'' !!}
															</textarea>	
													    </div> 
			                                        </div>
			                                        
			                                       <!--  <div class="row m-t-20">
			                                            <div class="col-sm-6">
			                                                <div class="form-group">
			                                                    <button data-stepval='6' class="btn btn-primary btn-o bp-back-step btn-wide pull-left">
			                                                        <i class="fa fa-arrow-circle-left"></i> Back
			                                                    </button>
			                                                </div>
			                                                <span></span>
			                                            </div>
			                                            <div class="col-sm-6">
			                                                <div class="form-group">
			                                                    <button class="btn btn-primary btn-o bp-next-step btn-wide pull-right">
			                                                        Finish <i class="fa fa-arrow-circle-right"></i>
			                                                    </button>
			                                                    <span></span>
			                                                </div>
			                                                 <div class="form-group">
																<button data-stepval='6' class="btn btn-primary btn-o next-step btn-wide pull-right bp-first-step">
																	Next <i class="fa fa-arrow-circle-right"></i>
																</button>
															</div> 
			                                            </div>
			                                        </div> -->
			                                    
			                                </div>
			                            </div>
			                       
			                    <!-- END :BUSINESS PLAN ACCORDIAN STEP 6 -->

								</div>
						    </div>
						    <div class="col-md-12 ">
						        <div class="form-group">
									<button data-stepval='6' class="btn btn-primary btn-o next-step btn-wide pull-right bp-first-step">
										Next <i class="fa fa-arrow-circle-right"></i>
									</button>
								</div>
							</div>	
							<!-- <div class="col-md-12">
								<div class="form-group">
									<button class="btn btn-primary btn-o next-step btn-wide pull-right">
										Next <i class="fa fa-arrow-circle-right"></i>
									</button>
								</div>
							</div> -->
						</div>
					</div>
				<!-- End: step-1 Executive Summmary FORM WIZARD ACCORDION -->
				<!-- Start: step-2  Company Summary FORM WIZARD ACCORDION -->	
					<div id="step-2" class="content" data-group='company_summary' style="display: none;">
						<div class="row">
							<div class="col-md-12">
							 	<div class="panel-group epic-accordion" > 
							 		<div class="panel panel-white">
			                            <div class="panel-heading">
			                                <h5 class="panel-title">
			                                    <span class="icon-group-left"><i class="fa fa-ellipsis-v"></i></span> Company Structure, Ownership, Offerings & Location  
			                                    <span class="icon-group-right">
			                                    <!-- <i class="fa fa-wrench pull-right"></i>
			                                    <a class="btn btn-xs pull-right panel-collapse" href="#" data-panel-group="epic-accordion">
                        						<i class="fa fa-chevron-up"></i>
               					 				</a> -->
	               					 				<a class="btn btn-xs pull-right" href="#">
				                                                <i class="fa fa-wrench"></i>
	                                                </a>
	                                                <a class="btn btn-xs pull-right panel-collapse first-col" href="#" data-panel-group="comp-summary">
	                                                    <i class="fa fa-chevron-down"></i>
	                                                </a>
			                                    </span>
			                                </h5>
			                            </div>
			                            <div class="panel-body" id="bp-step-7">
	                                        <div class="row">
	                                            <div class="col-md-12">
													<textarea class="ckeditor form-control" cols="10" rows="10" name='company_ownership_location'>
													{!! ($businessplan)?$businessplan->bp_company_ownership_location:'' !!}	
													</textarea>	
											    </div> 
	                                        </div>
	                                        
	                                        <!-- <div class="row m-t-20">
	                                            <div class="col-sm-6">
	                                                <div class="form-group">
	                                                    <button data-stepval='7' class="btn btn-primary btn-o bp-back-step btn-wide pull-left">
	                                                        <i class="fa fa-circle-arrow-left"></i> Back
	                                                    </button>
	                                                </div>
	                                                <span></span>
	                                            </div>
	                                            <div class="col-sm-6">
	                                                <div class="form-group">
	                                                    <button data-stepval='7' class="btn btn-primary btn-o bp-next-step btn-wide pull-right">
	                                                        Next <i class="fa fa-arrow-circle-right"></i>
	                                                    </button>
	                                                    <span></span>
	                                                </div>
	                                            </div>
	                                        </div> -->

	                                        <!-- <div class="row m-t-20">
	                                        	<div class="col-md-12">	
													<div class="form-group">
														<button class="btn btn-primary btn-o back-step btn-wide pull-left" data-stepval='7'>
															<i class="fa fa-arrow-circle-left"></i> Back
														</button>
														<button class="btn btn-primary btn-o next-step btn-wide pull-right" data-stepval='7'>
															Next <i class="fa fa-arrow-circle-right"></i>
														</button>
													</div>
												</div>
	                                        </div> -->
			                                   
			                            </div>
			                        </div>	
							 	</div> 
							</div>
							<div class="col-md-12">	
								<div class="form-group">
									<button class="btn btn-primary btn-o back-step btn-wide pull-left" data-stepval='7'>
										<i class="fa fa-arrow-circle-left"></i> Back
									</button>
									<button class="btn btn-primary btn-o next-step btn-wide pull-right" data-stepval='7'>
										Next <i class="fa fa-arrow-circle-right"></i>
									</button>
								</div>
							</div>
							<!-- <div class="col-md-12">	
								<div class="form-group">
									<button class="btn btn-primary btn-o back-step btn-wide pull-left" data-stepval='7'>
										<i class="fa fa-circle-arrow-left"></i> Back
									</button>
									<button class="btn btn-primary btn-o next-step btn-wide pull-right" data-stepval='7'>
										Next <i class="fa fa-arrow-circle-right"></i>
									</button>
								</div>
							</div> -->
						</div>
					</div>
				<!-- End: step-2  Company Summary FORM WIZARD ACCORDION -->
				<!-- Start: step-3 Services and Products  FORM WIZARD ACCORDION -->
					<div id="step-3" class="content" data-group='services_products' style="display: none;">
						<div class="row">
							<div class="col-md-12">
							 	<div class="panel-group epic-accordion" >
							 		<div class="panel panel-white">
			                            <div class="panel-heading">
			                                <h5 class="panel-title">
			                                    <span class="icon-group-left"><i class="fa fa-ellipsis-v"></i></span> Description  
			                                    <span class="icon-group-right">
			                                    <!-- <i class="fa fa-wrench pull-right"></i>
			                                    <a class="btn btn-xs pull-right panel-collapse" href="#" data-panel-group="epic-accordion">
                        						<i class="fa fa-chevron-up"></i>
               					 				</a> -->
               					 					<a class="btn btn-xs pull-right" href="#">
				                                                <i class="fa fa-wrench"></i>
	                                                </a>
	                                                <a class="btn btn-xs pull-right panel-collapse closed first-col" href="#" data-panel-group="serv_prod">
	                                                    <i class="fa fa-chevron-down"></i>
	                                                </a>
			                                    </span>
			                                </h5>
			                            </div>
			                            <div class="panel-body" id="bp-step-8">
	                                        <div class="row">
	                                            <div class="col-md-12">
													<textarea class="ckeditor form-control" cols="10" rows="10" name='description'>
													{!! ($businessplan)?$businessplan->bp_description:'' !!}
													</textarea>	
											    </div> 
	                                        </div>
	                                        
	                                        <!-- <div class="row m-t-20">
	                                            <div class="col-sm-6">
	                                                <div class="form-group">
	                                                    <button data-stepval='8' class="btn btn-primary btn-o back-step btn-wide pull-left">
	                                                        <i class="fa fa-arrow-circle-left"></i> Back
	                                                    </button>

	                                                </div>
	                                                <span></span>
	                                            </div>
	                                            <div class="col-sm-6">
	                                                <div class="form-group">
	                                                    <button data-stepval='8' class="btn btn-primary btn-o bp-next-step btn-wide pull-right">
	                                                        Next <i class="fa fa-arrow-circle-right"></i>
	                                                    </button>
	                                                    <span></span>
	                                                </div>
	                                            </div>
	                                        </div> -->
			                                   
			                            </div>
			                        </div>
			                        <div class="panel panel-white">
			                            <div class="panel-heading">
			                                <h5 class="panel-title">
			                                    <span class="icon-group-left"><i class="fa fa-ellipsis-v"></i></span> Features & Benefits  
			                                    <span class="icon-group-right">
			                                    <!-- <i class="fa fa-wrench pull-right"></i>
			                                    <a class="btn btn-xs pull-right panel-collapse" href="#" data-panel-group="epic-accordion">
                        						<i class="fa fa-chevron-up"></i>
               					 				</a> -->
               					 					<a class="btn btn-xs pull-right" href="#">
				                                                <i class="fa fa-wrench"></i>
	                                                </a>
	                                                <a class="btn btn-xs pull-right panel-collapse" href="#" data-panel-group="serv_prod">
	                                                    <i class="fa fa-chevron-down"></i>
	                                                </a>
			                                    </span>
			                                </h5>
			                            </div>
			                            <div class="panel-body" id="bp-step-9">
	                                        <div class="row">
	                                            <div class="col-md-12">
													<textarea class="ckeditor form-control" cols="10" rows="10" name='features_benefits'>
													{!! ($businessplan)?$businessplan->bp_features_benefits:'' !!}	
													</textarea>	
											    </div> 
	                                        </div>
	                                        
	                                        <!-- <div class="row m-t-20">
	                                            <div class="col-sm-6">
	                                                <div class="form-group">
	                                                    <button data-stepval='9' class="btn btn-primary btn-o bp-back-step btn-wide pull-left">
	                                                        <i class="fa fa-arrow-circle-left"></i> Back
	                                                    </button>
	                                                </div>
	                                                <span></span>
	                                            </div>
	                                            <div class="col-sm-6">
	                                                <div class="form-group">
	                                                    <button data-stepval='9' class="btn btn-primary btn-o bp-next-step btn-wide pull-right">
	                                                        Next <i class="fa fa-arrow-circle-right"></i>
	                                                    </button>
	                                                    <span></span>
	                                                </div>
	                                            </div>
	                                        </div> -->
			                                   
			                            </div>
			                        </div>
			                        <div class="panel panel-white">
			                            <div class="panel-heading">
			                                <h5 class="panel-title">
			                                    <span class="icon-group-left"><i class="fa fa-ellipsis-v"></i></span> Competitors 
			                                    <span class="icon-group-right">
			                                    <!-- <i class="fa fa-wrench pull-right"></i>
			                                    <a class="btn btn-xs pull-right panel-collapse" href="#" data-panel-group="epic-accordion">
                        						<i class="fa fa-chevron-up"></i>
               					 				</a> -->
               					 					<a class="btn btn-xs pull-right" href="#">
				                                                <i class="fa fa-wrench"></i>
	                                                </a>
	                                                <a class="btn btn-xs pull-right panel-collapse" href="#" data-panel-group="serv_prod">
	                                                    <i class="fa fa-chevron-down"></i>
	                                                </a>
			                                    </span>
			                                </h5>
			                            </div>
			                            <div class="panel-body" id="bp-step-10">
	                                        <div class="row">
	                                            <div class="col-md-12">
													<textarea class="ckeditor form-control" cols="10" rows="10" name='competitors'>
													{!! ($businessplan)?$businessplan->bp_competitors:'' !!}	
													</textarea>	
											    </div> 
	                                        </div>
	                                        
	                                        <!-- <div class="row m-t-20">
	                                            <div class="col-sm-6">
	                                                <div class="form-group">
	                                                    <button data-stepval='10' class="btn btn-primary btn-o bp-back-step btn-wide pull-left">
	                                                        <i class="fa fa-arrow-circle-left"></i> Back
	                                                    </button>
	                                                </div>
	                                                <span></span>
	                                            </div>
	                                            <div class="col-sm-6">
	                                                <div class="form-group">
	                                                    <button data-stepval='10' class="btn btn-primary btn-o bp-next-step btn-wide pull-right">
	                                                        Next <i class="fa fa-arrow-circle-right"></i>
	                                                    </button>
	                                                    <span></span>
	                                                </div>
	                                            </div>
	                                        </div> -->
			                                   
			                            </div>
			                        </div>
			                        <div class="panel panel-white">
			                            <div class="panel-heading">
			                                <h5 class="panel-title">
			                                    <span class="icon-group-left"><i class="fa fa-ellipsis-v"></i></span> Competitive Advantage 
			                                    <span class="icon-group-right">
			                                    <!-- <i class="fa fa-wrench pull-right"></i>
			                                    <a class="btn btn-xs pull-right panel-collapse" href="#" data-panel-group="epic-accordion">
                        						<i class="fa fa-chevron-up"></i>
               					 				</a> -->
               					 					<a class="btn btn-xs pull-right" href="#">
				                                                <i class="fa fa-wrench"></i>
	                                                </a>
	                                                <a class="btn btn-xs pull-right panel-collapse" href="#" data-panel-group="serv_prod">
	                                                    <i class="fa fa-chevron-down"></i>
	                                                </a>
			                                    </span>
			                                </h5>
			                            </div>
			                            <div class="panel-body" id="bp-step-11">
	                                        <div class="row">
	                                            <div class="col-md-12">
													<textarea class="ckeditor form-control" cols="10" rows="10" name='competitive_advantage'>
													{!! ($businessplan)?$businessplan->bp_competitive_advantage:'' !!}		
													</textarea>	
											    </div> 
	                                        </div>
	                                        
	                                        <!-- <div class="row m-t-20">
	                                            <div class="col-sm-6">
	                                                <div class="form-group">
	                                                    <button data-stepval='11' class="btn btn-primary btn-o bp-back-step btn-wide pull-left">
	                                                        <i class="fa fa-arrow-circle-left"></i> Back
	                                                    </button>
	                                                </div>
	                                                <span></span>
	                                            </div>
	                                            <div class="col-sm-6">
	                                                <div class="form-group">
	                                                    <button data-stepval='11' class="btn btn-primary btn-o bp-next-step btn-wide pull-right">
	                                                        Next <i class="fa fa-arrow-circle-right"></i>
	                                                    </button>
	                                                    <span></span>
	                                                </div>
	                                            </div>
	                                        </div> -->
			                                   
			                            </div>
			                        </div>
			                        <div class="panel panel-white">
			                            <div class="panel-heading">
			                                <h5 class="panel-title">
			                                    <span class="icon-group-left"><i class="fa fa-ellipsis-v"></i></span> Future Expansion  
			                                    <span class="icon-group-right">
			                                    <!-- <i class="fa fa-wrench pull-right"></i>
			                                    <a class="btn btn-xs pull-right panel-collapse" href="#" data-panel-group="epic-accordion">
                        						<i class="fa fa-chevron-up"></i>
               					 				</a> -->
               					 					<a class="btn btn-xs pull-right" href="#">
				                                                <i class="fa fa-wrench"></i>
	                                                </a>
	                                                <a class="btn btn-xs pull-right panel-collapse" href="#" data-panel-group="serv_prod">
	                                                    <i class="fa fa-chevron-down"></i>
	                                                </a>
			                                    </span>
			                                </h5>
			                            </div>
			                            <div class="panel-body" id="bp-step-12">
	                                        <div class="row">
	                                            <div class="col-md-12">
													<textarea class="ckeditor form-control" cols="10" rows="10" name='future_expansion'>
													{!! ($businessplan)?$businessplan->bp_competitive_advantage:'' !!}		
													</textarea>	
											    </div> 
	                                        </div>
	                                        
	                                       <!--  <div class="row m-t-20">
	                                            <div class="col-sm-6">
	                                                <div class="form-group">
	                                                    <button data-stepval='13' class="btn btn-primary btn-o bp-back-step btn-wide pull-left">
	                                                        <i class="fa fa-circle-arrow-left"></i> Back
	                                                    </button>
	                                                </div>
	                                                <span></span>
	                                            </div>
	                                            <div class="col-sm-6">
	                                                <div class="form-group">
	                                                    <button data-stepval='13' class="btn btn-primary btn-o bp-next-step btn-wide pull-right">
	                                                        Next <i class="fa fa-arrow-circle-right"></i>
	                                                    </button>
	                                                    <span></span>
	                                                </div>
	                                            </div>
	                                        </div> -->
	                                        <!-- <div class="row m-t-20">
	                                        	<div class="col-md-12">	
													<div class="form-group">
														<button class="btn btn-primary btn-o bp-back-step btn-wide pull-left" data-stepval='12'>
															<i class="fa fa-arrow-circle-left"></i> Back
														</button>
														<button class="btn btn-primary btn-o next-step btn-wide pull-right" data-stepval='12'>
															Next <i class="fa fa-arrow-circle-right"></i>
														</button>
													</div>
												</div>
	                                        </div> -->
			                                   
			                            </div>
			                        </div>	
							 	</div>
							</div>
							<div class="col-md-12">	
								<div class="form-group">
									<button class="btn btn-primary btn-o back-step btn-wide pull-left" data-stepval='12'>
										<i class="fa fa-arrow-circle-left"></i> Back
									</button>
									<button class="btn btn-primary btn-o next-step btn-wide pull-right" data-stepval='12'>
										Next <i class="fa fa-arrow-circle-right"></i>
									</button>
								</div>
							</div>
							<!-- <div class="col-md-12">	
								<div class="form-group">
									<button class="btn btn-primary btn-o back-step btn-wide pull-left" data-stepval='13'>
										<i class="fa fa-circle-arrow-left"></i> Back
									</button>
									<button class="btn btn-primary btn-o next-step btn-wide pull-right" data-stepval='13'>
										Next <i class="fa fa-arrow-circle-right"></i>
									</button>
								</div>
							</div> -->
						</div>
					</div>
				<!-- End: step-3 Services and Products  FORM WIZARD ACCORDION -->
				<!-- Start: step-4  Market Analysis Summary  FORM WIZARD ACCORDION -->
					<div id="step-4" class="content" data-group='market_analysis' style="display: none;">
						<div class="row">
							<div class="col-md-12">
							 	<div class="panel-group epic-accordion" >
							 		<div class="panel panel-white">
			                            <div class="panel-heading">
			                                <h5 class="panel-title">
			                                    <span class="icon-group-left"><i class="fa fa-ellipsis-v"></i></span> 
			                                    Niche Market  
			                                    <span class="icon-group-right">
			                                    <!-- <i class="fa fa-wrench pull-right"></i>
			                                    <a class="btn btn-xs pull-right panel-collapse" href="#" data-panel-group="epic-accordion">
                        						<i class="fa fa-chevron-up"></i>
               					 				</a> -->
               					 					<a class="btn btn-xs pull-right" href="#">
				                                                <i class="fa fa-wrench"></i>
	                                                </a>
	                                                <a class="btn btn-xs pull-right panel-collapse closed first-col" href="#" data-panel-group="market_analy">
	                                                    <i class="fa fa-chevron-down"></i>
	                                                </a>
			                                    </span>
			                                </h5>
			                            </div>
			                            <div class="panel-body" id="bp-step-13">
	                                        <div class="row">
	                                            <div class="col-md-12">
													<textarea class="ckeditor form-control" cols="10" rows="10" name='niche_market'>
													{!! ($businessplan)?$businessplan->bp_niche_market:'' !!}	
													</textarea>	
											    </div> 
	                                        </div>
	                                        
	                                        <!-- <div class="row m-t-20">
	                                            <div class="col-sm-6">
	                                                <div class="form-group">
	                                                    <button data-stepval='13' class="btn btn-primary btn-o back-step btn-wide pull-left">
	                                                        <i class="fa fa-arrow-circle-left"></i> Back
	                                                    </button>
	                                                </div>
	                                                <span></span>
	                                            </div>
	                                            <div class="col-sm-6">
	                                                <div class="form-group">
	                                                    <button data-stepval='13' class="btn btn-primary btn-o bp-next-step btn-wide pull-right">
	                                                        Next <i class="fa fa-arrow-circle-right"></i>
	                                                    </button>
	                                                    <span></span>
	                                                </div>
	                                            </div>
	                                        </div> -->
			                                   
			                            </div>
			                        </div>
			                        <div class="panel panel-white">
			                            <div class="panel-heading">
			                                <h5 class="panel-title">
			                                    <span class="icon-group-left"><i class="fa fa-ellipsis-v"></i></span> 
			                                    Market Size  
			                                    <span class="icon-group-right">
			                                    <!-- <i class="fa fa-wrench pull-right"></i>
			                                    <a class="btn btn-xs pull-right panel-collapse" href="#" data-panel-group="epic-accordion">
                        						<i class="fa fa-chevron-up"></i>
               					 				</a> -->
               					 					<a class="btn btn-xs pull-right" href="#">
				                                                <i class="fa fa-wrench"></i>
	                                                </a>
	                                                <a class="btn btn-xs pull-right panel-collapse " href="#" data-panel-group="market_analy">
	                                                    <i class="fa fa-chevron-down"></i>
	                                                </a>
			                                    </span>
			                                </h5>
			                            </div>
			                            <div class="panel-body" id="bp-step-14">
	                                        <div class="row">
	                                            <div class="col-md-12">
													<textarea class="ckeditor form-control" cols="10" rows="10" name='market_size'>
													{!! ($businessplan)?$businessplan->bp_market_size:'' !!}	
													</textarea>	
											    </div> 
	                                        </div>
	                                        
	                                        <!-- <div class="row m-t-20">
	                                            <div class="col-sm-6">
	                                                <div class="form-group">
	                                                    <button data-stepval='14' class="btn btn-primary btn-o bp-back-step btn-wide pull-left">
	                                                        <i class="fa fa-arrow-circle-left"></i> Back
	                                                    </button>
	                                                </div>
	                                                <span></span>
	                                            </div>
	                                            <div class="col-sm-6">
	                                                <div class="form-group">
	                                                    <button data-stepval='14' class="btn btn-primary btn-o bp-next-step btn-wide pull-right">
	                                                        Next <i class="fa fa-arrow-circle-right"></i>
	                                                    </button>
	                                                    <span></span>
	                                                </div>
	                                            </div>
	                                        </div> -->
			                                   
			                            </div>
			                        </div>
			                        <div class="panel panel-white">
			                            <div class="panel-heading">
			                                <h5 class="panel-title">
			                                    <span class="icon-group-left"><i class="fa fa-ellipsis-v"></i></span> 
			                                    Current Trends  
			                                    <span class="icon-group-right">
			                                    <!-- <i class="fa fa-wrench pull-right"></i>	
			                                    <a class="btn btn-xs pull-right panel-collapse" href="#" data-panel-group="epic-accordion">
                        						<i class="fa fa-chevron-up"></i>
               					 				</a> -->
               					 					<a class="btn btn-xs pull-right" href="#">
				                                                <i class="fa fa-wrench"></i>
	                                                </a>
	                                                <a class="btn btn-xs pull-right panel-collapse " href="#" data-panel-group="market_analy">
	                                                    <i class="fa fa-chevron-down"></i>
	                                                </a>
			                                    </span>
			                                </h5>
			                            </div>
			                            <div class="panel-body" id="bp-step-15">
	                                        <div class="row">
	                                            <div class="col-md-12">
													<textarea class="ckeditor form-control" cols="10" rows="10" name='current_trends'>
													{!! ($businessplan)?$businessplan->bp_current_trends:'' !!}	
													</textarea>	
											    </div> 
	                                        </div>
	                                        
	                                        <!-- <div class="row m-t-20">
	                                            <div class="col-sm-6">
	                                                <div class="form-group">
	                                                    <button data-stepval='15' class="btn btn-primary btn-o bp-back-step btn-wide pull-left">
	                                                        <i class="fa fa-arrow-circle-left"></i> Back
	                                                    </button>
	                                                </div>
	                                                <span></span>
	                                            </div>
	                                            <div class="col-sm-6">
	                                                <div class="form-group">
	                                                    <button data-stepval='15' class="btn btn-primary btn-o bp-next-step btn-wide pull-right">
	                                                        Next <i class="fa fa-arrow-circle-right"></i>
	                                                    </button>
	                                                    <span></span>
	                                                </div>
	                                            </div>
	                                        </div> -->
			                                   
			                            </div>
			                        </div>
			                        <div class="panel panel-white">
			                            <div class="panel-heading">
			                                <h5 class="panel-title">
			                                    <span class="icon-group-left"><i class="fa fa-ellipsis-v"></i></span> 
			                                    Swot Analysis  
			                                    <span class="icon-group-right">
			                                    <!-- <i class="fa fa-wrench pull-right"></i>
			                                    <a class="btn btn-xs pull-right panel-collapse" href="#" data-panel-group="epic-accordion">
                        						<i class="fa fa-chevron-up"></i>
               					 				</a> -->
               					 					<a class="btn btn-xs pull-right" href="#">
				                                                <i class="fa fa-wrench"></i>
	                                                </a>
	                                                <a class="btn btn-xs pull-right panel-collapse " href="#" data-panel-group="market_analy">
	                                                    <i class="fa fa-chevron-down"></i>
	                                                </a>
			                                    </span>
			                                </h5>
			                            </div>
			                            <div class="panel-body" id="bp-step-16">
	                                        <div class="row">
	                                            <div class="col-md-12">
													<textarea class="ckeditor form-control" cols="10" rows="10" name='swot_analysis'>
													{!! ($businessplan)?$businessplan->bp_swot_analysis:'' !!}		
													</textarea>	
											    </div> 
	                                        </div>
	                                        
	                                        <!-- <div class="row m-t-20">
	                                            <div class="col-sm-6">
	                                                <div class="form-group">
	                                                    <button data-stepval='17' class="btn btn-primary btn-o bp-back-step btn-wide pull-left">
	                                                        <i class="fa fa-circle-arrow-left"></i> Back
	                                                    </button>
	                                                </div>
	                                                <span></span>
	                                            </div>
	                                            <div class="col-sm-6">
	                                                <div class="form-group">
	                                                    <button data-stepval='17' class="btn btn-primary btn-o bp-next-step btn-wide pull-right">
	                                                        Next <i class="fa fa-arrow-circle-right"></i>
	                                                    </button>
	                                                    <span></span>
	                                                </div>
	                                            </div>
	                                        </div> -->
	                                        <!-- <div class="row m-t-20">
	                                        	<div class="col-md-12">	
													<div class="form-group">
														<button class="btn btn-primary btn-o bp-back-step btn-wide pull-left" data-stepval='16'>
															<i class="fa fa-arrow-circle-left"></i> Back
														</button>
														<button class="btn btn-primary btn-o next-step btn-wide pull-right" data-stepval='16'>
															Next <i class="fa fa-arrow-circle-right"></i>
														</button>
													</div>
												</div>
	                                        </div> -->
			                                   
			                            </div>
			                        </div>			
							 	</div>
							</div>
							<div class="col-md-12">	
								<div class="form-group">
									<button class="btn btn-primary btn-o back-step btn-wide pull-left" data-stepval='16'>
										<i class="fa fa-arrow-circle-left"></i> Back
									</button>
									<button class="btn btn-primary btn-o next-step btn-wide pull-right" data-stepval='16'>
										Next <i class="fa fa-arrow-circle-right"></i>
									</button>
								</div>
							</div>
							<!-- <div class="col-md-12">	
								<div class="form-group">
									<button class="btn btn-primary btn-o back-step btn-wide pull-left" data-stepval='17'>
										<i class="fa fa-circle-arrow-left"></i> Back
									</button>
									<button class="btn btn-primary btn-o next-step btn-wide pull-right" data-stepval='17'>
										Next <i class="fa fa-arrow-circle-right"></i>
									</button>
								</div>
							</div> -->
						</div>
					</div>
				<!-- End: step-4  Market Analysis Summary  FORM WIZARD ACCORDION -->
				<!-- Start: step-5 Marketing Strategy and Business Implementation FORM WIZARD ACCORDION -->
					<div id="step-5" class="content" data-group='ms_and_bi' style="display: none;">
						<div class="row">
							<div class="col-md-12">
							  	<div class="panel-group epic-accordion" >
							  		<div class="panel panel-white">
			                            <div class="panel-heading">
			                                <h5 class="panel-title">
			                                    <span class="icon-group-left"><i class="fa fa-ellipsis-v"></i></span> 
			                                    Business Philosophy  
			                                    <span class="icon-group-right">
			                                    <!-- <i class="fa fa-wrench pull-right"></i>
			                                    <a class="btn btn-xs pull-right panel-collapse" href="#" data-panel-group="epic-accordion">
                        						<i class="fa fa-chevron-up"></i>
               					 				</a> -->
               					 					<a class="btn btn-xs pull-right" href="#">
				                                                <i class="fa fa-wrench"></i>
	                                                </a>
	                                                <a class="btn btn-xs pull-right panel-collapse closed first-col" href="#" data-panel-group="m_and_b">
	                                                    <i class="fa fa-chevron-down"></i>
	                                                </a>
			                                    </span>
			                                </h5>
			                            </div>
			                            <div class="panel-body" id="bp-step-17">
	                                        <div class="row">
	                                            <div class="col-md-12">
													<textarea class="ckeditor form-control" cols="10" rows="10" name='business_philosophy'>
													{!! ($businessplan)?$businessplan->bp_business_philosophy:'' !!}	
													</textarea>	
											    </div> 
	                                        </div>
	                                        
	                                        <!-- <div class="row m-t-20">
	                                            <div class="col-sm-6">
	                                                <div class="form-group">
	                                                    <button data-stepval='17' class="btn btn-primary btn-o back-step btn-wide pull-left">
	                                                        <i class="fa fa-arrow-circle-left"></i> Back
	                                                    </button>
	                                                </div>
	                                                <span></span>
	                                            </div>
	                                            <div class="col-sm-6">
	                                                <div class="form-group">
	                                                    <button data-stepval='17' class="btn btn-primary btn-o bp-next-step btn-wide pull-right">
	                                                        Next <i class="fa fa-arrow-circle-right"></i>
	                                                    </button>
	                                                    <span></span>
	                                                </div>
	                                            </div>
	                                        </div> -->
			                                   
			                            </div>
			                        </div>
			                        <div class="panel panel-white">
			                            <div class="panel-heading">
			                                <h5 class="panel-title">
			                                    <span class="icon-group-left"><i class="fa fa-ellipsis-v"></i></span> 
			                                    Web Presence  
			                                    <span class="icon-group-right">
			                                    <!-- <i class="fa fa-wrench pull-right"></i>
			                                    <a class="btn btn-xs pull-right panel-collapse" href="#" data-panel-group="epic-accordion">
                        						<i class="fa fa-chevron-up"></i>
               					 				</a> -->
               					 					<a class="btn btn-xs pull-right" href="#">
				                                                <i class="fa fa-wrench"></i>
	                                                </a>
	                                                <a class="btn btn-xs pull-right panel-collapse " href="#" data-panel-group="m_and_b">
	                                                    <i class="fa fa-chevron-down"></i>
	                                                </a>
			                                    </span>
			                                </h5>
			                            </div>
			                            <div class="panel-body" id="bp-step-18">
	                                        <div class="row">
	                                            <div class="col-md-12">
													<textarea class="ckeditor form-control" cols="10" rows="10" name='web_presence'>
													{!! ($businessplan)?$businessplan->bp_web_presence:'' !!}	
													</textarea>	
											    </div> 
	                                        </div>
	                                        
	                                        <!-- <div class="row m-t-20">
	                                            <div class="col-sm-6">
	                                                <div class="form-group">
	                                                    <button data-stepval='18' class="btn btn-primary btn-o bp-back-step btn-wide pull-left">
	                                                       <i class="fa fa-arrow-circle-left"></i> Back
	                                                    </button>
	                                                </div>
	                                                <span></span>
	                                            </div>
	                                            <div class="col-sm-6">
	                                                <div class="form-group">
	                                                    <button data-stepval='18' class="btn btn-primary btn-o bp-next-step btn-wide pull-right">
	                                                        Next <i class="fa fa-arrow-circle-right"></i>
	                                                    </button>
	                                                    <span></span>
	                                                </div>
	                                            </div>
	                                        </div> -->
			                                   
			                            </div>
			                        </div>
			                        <div class="panel panel-white">
			                            <div class="panel-heading">
			                                <h5 class="panel-title">
			                                    <span class="icon-group-left"><i class="fa fa-ellipsis-v"></i></span> 
			                                    Marketing Strategy  
			                                    <span class="icon-group-right">
			                                    <!-- <i class="fa fa-wrench pull-right"></i>
			                                    <a class="btn btn-xs pull-right panel-collapse" href="#" data-panel-group="epic-accordion">
                        						<i class="fa fa-chevron-up"></i>
               					 				</a> -->
               					 					<a class="btn btn-xs pull-right" href="#">
				                                                <i class="fa fa-wrench"></i>
	                                                </a>
	                                                <a class="btn btn-xs pull-right panel-collapse " href="#" data-panel-group="m_and_b">
	                                                    <i class="fa fa-chevron-down"></i>
	                                                </a>
			                                    </span>
			                                </h5>
			                            </div>
			                            <div class="panel-body" id="bp-step-19">
	                                        <div class="row">
	                                            <div class="col-md-12">
													<textarea class="ckeditor form-control" cols="10" rows="10" name='marketing_strategy'>
													{!! ($businessplan)?$businessplan->bp_marketing_strategy:'' !!}	
													</textarea>	
											    </div> 
	                                        </div>
	                                        
	                                        <!-- <div class="row m-t-20">
	                                            <div class="col-sm-6">
	                                                <div class="form-group">
	                                                    <button data-stepval='19' class="btn btn-primary btn-o bp-back-step btn-wide pull-left">
	                                                        <i class="fa fa-arrow-circle-left"></i> Back
	                                                    </button>
	                                                </div>
	                                                <span></span>
	                                            </div>
	                                            <div class="col-sm-6">
	                                                <div class="form-group">
	                                                    <button data-stepval='19' class="btn btn-primary btn-o bp-next-step btn-wide pull-right">
	                                                        Next <i class="fa fa-arrow-circle-right"></i>
	                                                    </button>
	                                                    <span></span>
	                                                </div>
	                                            </div>
	                                        </div> -->
			                                   
			                            </div>
			                        </div>
			                        <div class="panel panel-white">
			                            <div class="panel-heading">
			                                <h5 class="panel-title">
			                                    <span class="icon-group-left"><i class="fa fa-ellipsis-v"></i></span> 
			                                    Sales Strategy  
			                                    <span class="icon-group-right">
			                                    <!-- <i class="fa fa-wrench pull-right"></i>
			                                    <a class="btn btn-xs pull-right panel-collapse" href="#" data-panel-group="epic-accordion">
                        						<i class="fa fa-chevron-up"></i>
               					 				</a> -->
               					 					<a class="btn btn-xs pull-right" href="#">
				                                                <i class="fa fa-wrench"></i>
	                                                </a>
	                                                <a class="btn btn-xs pull-right panel-collapse " href="#" data-panel-group="m_and_b">
	                                                    <i class="fa fa-chevron-down"></i>
	                                                </a>
			                                    </span>
			                                </h5>
			                            </div>
			                            <div class="panel-body" id="bp-step-20">
	                                        <div class="row">
	                                            <div class="col-md-12">
													<textarea class="ckeditor form-control" cols="10" rows="10" name='sales_strategy'>
													{!! ($businessplan)?$businessplan->bp_sales_strategy:'' !!}	
													</textarea>	
											    </div> 
	                                        </div>
	                                        
	                                        <!-- <div class="row m-t-20">
	                                            <div class="col-sm-6">
	                                                <div class="form-group">
	                                                    <button data-stepval='20' class="btn btn-primary btn-o bp-back-step btn-wide pull-left">
	                                                        <i class="fa fa-arrow-circle-left"></i> Back
	                                                    </button>
	                                                </div>
	                                                <span></span>
	                                            </div>
	                                            <div class="col-sm-6">
	                                                <div class="form-group">
	                                                    <button data-stepval='20' class="btn btn-primary btn-o bp-next-step btn-wide pull-right">
	                                                        Next <i class="fa fa-arrow-circle-right"></i>
	                                                    </button>
	                                                    <span></span>
	                                                </div>
	                                            </div>
	                                        </div> -->
			                                   
			                            </div>
			                        </div>
			                        <div class="panel panel-white">
			                            <div class="panel-heading">
			                                <h5 class="panel-title">
			                                    <span class="icon-group-left"><i class="fa fa-ellipsis-v"></i></span> 
			                                    Strategic Alliances  
			                                    <span class="icon-group-right">
			                                    <!-- <i class="fa fa-wrench pull-right"></i>
			                                    <a class="btn btn-xs pull-right panel-collapse" href="#" data-panel-group="epic-accordion">
                        						<i class="fa fa-chevron-up"></i>
               					 				</a> -->
               					 					<a class="btn btn-xs pull-right" href="#">
				                                                <i class="fa fa-wrench"></i>
	                                                </a>
	                                                <a class="btn btn-xs pull-right panel-collapse " href="#" data-panel-group="m_and_b">
	                                                    <i class="fa fa-chevron-down"></i>
	                                                </a>
			                                    </span>
			                                </h5>
			                            </div>
			                            <div class="panel-body" id="bp-step-21">
	                                        <div class="row">
	                                            <div class="col-md-12">
													<textarea class="ckeditor form-control" cols="10" rows="10" name='strategic_alliances'>
													{!! ($businessplan)?$businessplan->bp_strategic_alliances:'' !!}		
													</textarea>	
											    </div> 
	                                        </div>
	                                        
	                                       <!--  <div class="row m-t-20">
	                                            <div class="col-sm-6">
	                                                <div class="form-group">
	                                                    <button data-stepval='21' class="btn btn-primary btn-o bp-back-step btn-wide pull-left">
	                                                        <i class="fa fa-arrow-circle-left"></i> Back
	                                                    </button>
	                                                </div>
	                                                <span></span>
	                                            </div>
	                                            <div class="col-sm-6">
	                                                <div class="form-group">
	                                                    <button data-stepval='21' class="btn btn-primary btn-o bp-next-step btn-wide pull-right">
	                                                        Next <i class="fa fa-arrow-circle-right"></i>
	                                                    </button>
	                                                    <span></span>
	                                                </div>
	                                            </div>
	                                        </div> -->
			                                   
			                            </div>
			                        </div>
			                        <div class="panel panel-white">
			                            <div class="panel-heading">
			                                <h5 class="panel-title">
			                                    <span class="icon-group-left"><i class="fa fa-ellipsis-v"></i></span> 
			                                    Company Objectives And Vision  
			                                    <span class="icon-group-right">
			                                    <!-- <i class="fa fa-wrench pull-right"></i>
			                                    <a class="btn btn-xs pull-right panel-collapse" href="#" data-panel-group="epic-accordion">
                        						<i class="fa fa-chevron-up"></i>
               					 				</a> -->
               					 					<a class="btn btn-xs pull-right" href="#">
				                                                <i class="fa fa-wrench"></i>
	                                                </a>
	                                                <a class="btn btn-xs pull-right panel-collapse " href="#" data-panel-group="m_and_b">
	                                                    <i class="fa fa-chevron-down"></i>
	                                                </a>
			                                    </span>
			                                </h5>
			                            </div>
			                            <div class="panel-body" id="bp-step-22">
	                                        <div class="row">
	                                            <div class="col-md-12">
													<textarea class="ckeditor form-control" cols="10" rows="10" name='company_objectives_and_vision'>
													{!! ($businessplan)?$businessplan->bp_company_objectives_and_vision:'' !!}	
													</textarea>	
											    </div> 
	                                        </div>
	                                        
	                                        <!-- <div class="row m-t-20">
	                                            <div class="col-sm-6">
	                                                <div class="form-group">
	                                                    <button data-stepval='22' class="btn btn-primary btn-o bp-back-step btn-wide pull-left">
	                                                        <i class="fa fa-arrow-circle-left"></i> Back
	                                                    </button>
	                                                </div>
	                                                <span></span>
	                                            </div>
	                                            <div class="col-sm-6">
	                                                <div class="form-group">
	                                                    <button data-stepval='22' class="btn btn-primary btn-o bp-next-step btn-wide pull-right">
	                                                        Next <i class="fa fa-arrow-circle-right"></i>
	                                                    </button>
	                                                    <span></span>
	                                                </div>
	                                            </div>
	                                        </div> -->
			                                   
			                            </div>
			                        </div>
			                        <div class="panel panel-white">
			                            <div class="panel-heading">
			                                <h5 class="panel-title">
			                                    <span class="icon-group-left"><i class="fa fa-ellipsis-v"></i></span> 
			                                    Exit Strategy  
			                                    <span class="icon-group-right">
			                                    <!-- <i class="fa fa-wrench pull-right"></i>
			                                    <a class="btn btn-xs pull-right panel-collapse" href="#" data-panel-group="epic-accordion">
                        						<i class="fa fa-chevron-up"></i>
               					 				</a> -->
               					 					<a class="btn btn-xs pull-right" href="#">
				                                                <i class="fa fa-wrench"></i>
	                                                </a>
	                                                <a class="btn btn-xs pull-right panel-collapse " href="#" data-panel-group="m_and_b">
	                                                    <i class="fa fa-chevron-down"></i>
	                                                </a>
			                                    </span>
			                                </h5>
			                            </div>
			                            <div class="panel-body" id="bp-step-23">
	                                        <div class="row">
	                                            <div class="col-md-12">
													<textarea class="ckeditor form-control" cols="10" rows="10" name='exit_strategy'>
													{!! ($businessplan)?$businessplan->bp_exit_strategy:'' !!}	
													</textarea>	
											    </div> 
	                                        </div>
	                                        
	                                        <!-- <div class="row m-t-20">
	                                            <div class="col-sm-6">
	                                                <div class="form-group">
	                                                    <button data-stepval='24' class="btn btn-primary btn-o bp-back-step btn-wide pull-left">
	                                                        <i class="fa fa-circle-arrow-left"></i> Back
	                                                    </button>
	                                                </div>
	                                                <span></span>
	                                            </div>
	                                            <div class="col-sm-6">
	                                                <div class="form-group">
	                                                    <button data-stepval='24' class="btn btn-primary btn-o bp-next-step btn-wide pull-right">
	                                                        Next <i class="fa fa-arrow-circle-right"></i>
	                                                    </button>
	                                                    <span></span>
	                                                </div>
	                                            </div>
	                                        </div> -->
	                                        <!-- <div class="row m-t-20">
	                                        	<div class="col-md-12">	
													<div class="form-group">
														<button class="btn btn-primary btn-o bp-back-step btn-wide pull-left" data-stepval='23'>
															<i class="fa fa-arrow-circle-left"></i> Back
														</button>
														<button class="btn btn-primary btn-o next-step btn-wide pull-right" data-stepval='23'>
															Next <i class="fa fa-arrow-circle-right"></i>
														</button>
													</div>
												</div>	
	                                        </div> -->
			                                   
			                            </div>
			                        </div>		
							  	</div>
							</div>
							<div class="col-md-12">	
								<div class="form-group">
									<button class="btn btn-primary btn-o back-step btn-wide pull-left" data-stepval='23'>
										<i class="fa fa-arrow-circle-left"></i> Back
									</button>
									<button class="btn btn-primary btn-o next-step btn-wide pull-right" data-stepval='23'>
										Next <i class="fa fa-arrow-circle-right"></i>
									</button>
								</div>
							</div>	
							<!-- <div class="col-md-12">	
								<div class="form-group">
									<button class="btn btn-primary btn-o back-step btn-wide pull-left" data-stepval='24'>
										<i class="fa fa-circle-arrow-left"></i> Back
									</button>
									<button class="btn btn-primary btn-o next-step btn-wide pull-right" data-stepval='24'>
										Next <i class="fa fa-arrow-circle-right"></i>
									</button>
								</div>
							</div> -->
						</div>
					</div>
				<!-- End: step-5 Marketing Strategy and Business Implementation FORM WIZARD ACCORDION -->
				<!-- Start: step-6  Management Summary  FORM WIZARD ACCORDION -->
					<div id="step-6" class="content" data-group='managament_summary' style="display: none;">
						<div class="row">
							<div class="col-md-12">
							  	<div class="panel-group epic-accordion" >
			                        <div class="panel panel-white">
			                            <div class="panel-heading">
			                                <h5 class="panel-title">
			                                    <span class="icon-group-left"><i class="fa fa-ellipsis-v"></i></span> Management Structure  
			                                    <span class="icon-group-right">
			                                    <!-- <i class="fa fa-wrench pull-right"></i>
			                                    <a class="btn btn-xs pull-right panel-collapse" href="#" data-panel-group="epic-accordion">
                        						<i class="fa fa-chevron-up"></i>
               					 				</a> -->
               					 					<a class="btn btn-xs pull-right" href="#">
				                                                <i class="fa fa-wrench"></i>
	                                                </a>
	                                                <a class="btn btn-xs pull-right panel-collapse closed first-col" href="#" data-panel-group="manag_summary">
	                                                    <i class="fa fa-chevron-down"></i>
	                                                </a>
			                                    </span>
			                                </h5>
			                            </div>
			                            <div class="panel-body " id="bp-step-24">
	                                        <div class="row">
	                                            <div class="col-md-12">
													<textarea class="ckeditor form-control" cols="10" rows="10" name='management_structure'>
													{!! ($businessplan)?$businessplan->bp_management_structure:'' !!}	
													</textarea>	
											    </div>  
	                                        </div>
	                                        
	                                         <!--<div class="row m-t-20">
	                                            <div class="col-sm-6">
	                                                <div class="form-group">
	                                                    <button data-stepval='24' class="btn btn-primary btn-o bp-back-step btn-wide pull-left">
	                                                        <i class="fa fa-circle-arrow-left"></i> Back
	                                                    </button>
	                                                </div>
	                                                <span></span>
	                                            </div>
	                                            <div class="col-sm-6">
	                                                <div class="form-group">
	                                                    <button data-stepval='24' class="btn btn-primary btn-o bp-next-step btn-wide pull-right">
	                                                        Next <i class="fa fa-arrow-circle-right"></i>
	                                                    </button>
	                                                    <span></span>
	                                                </div>
	                                            </div>
	                                        </div> -->
	<!-- 	                                        <div class="row m-t-20">
	                                        	<div class="col-md-12">	
													<div class="form-group">
														<button class="btn btn-primary btn-o back-step btn-wide pull-left" data-stepval='24'>
															<i class="fa fa-arrow-circle-left"></i> Back
														</button>
														<button class="btn btn-primary btn-o next-step btn-wide pull-right" data-stepval='24'>
															Next <i class="fa fa-arrow-circle-right"></i>
														</button>
													</div>
												</div>	
	                                        </div> -->
			                                   
			                            </div>
			                        </div>		
							  	</div>
							</div>
							<div class="col-md-12">	
								<div class="form-group">
									<button class="btn btn-primary btn-o back-step btn-wide pull-left" data-stepval='24'>
										<i class="fa fa-arrow-circle-left"></i> Back
									</button>
									<button class="btn btn-primary btn-o next-step btn-wide pull-right" data-stepval='24'>
										Next <i class="fa fa-arrow-circle-right"></i>
									</button>
								</div>
							</div>	
							<!-- <div class="col-md-12">	
								<div class="form-group">
									<button class="btn btn-primary btn-o back-step btn-wide pull-left" data-stepval='24'>
										<i class="fa fa-circle-arrow-left"></i> Back
									</button>
									<button class="btn btn-primary btn-o next-step btn-wide pull-right" data-stepval='24'>
										Next <i class="fa fa-arrow-circle-right"></i>
									</button>
								</div>
							</div> -->
						</div>
					</div>
				<!-- End: step-6  Management Summary  FORM WIZARD ACCORDION -->
				<!-- Start: step-7  Financial Plan   FORM WIZARD ACCORDION -->
					<div id="step-7" class="content" data-group='financial_plan_group' style="display: none;" >
						<div class="row">
							<div class="col-md-12">
							  	<div class="panel-group epic-accordion" >
			                        <div class="panel panel-white">
				                            <div class="panel-heading">
				                                <h5 class="panel-title">
				                                    <span class="icon-group-left"><i class="fa fa-ellipsis-v"></i></span> Startup Requirements And Allocation Of Capital  
				                                    <span class="icon-group-right">
				                                    <!-- <i class="fa fa-wrench pull-right"></i>
				                                    <a class="btn btn-xs pull-right panel-collapse" href="#" data-panel-group="epic-accordion">
	                        						<i class="fa fa-chevron-up"></i>
	               					 				</a> -->
		               					 				<a class="btn btn-xs pull-right" href="#">
					                                                <i class="fa fa-wrench"></i>
		                                                </a>
		                                                <a class="btn btn-xs pull-right panel-collapse closed first-col" href="#" data-panel-group="fin_group">
		                                                    <i class="fa fa-chevron-down"></i>
		                                                </a>
				                                    </span>
				                                </h5>
				                            </div>
				                            <div class="panel-body" id="bp-step-25">
		                                        <div class="row">
		                                            <div class="col-md-12">
														<textarea class="ckeditor form-control" cols="10" rows="10" name='startup_req_and_alloc_capital'>
														{!! ($businessplan)?$businessplan->bp_startup_req_and_alloc_capital:'' !!}	
														</textarea>	
												    </div> 
		                                        </div>
		                                        
		                                        <!-- <div class="row m-t-20">
		                                            <div class="col-sm-6">
		                                                <div class="form-group">
		                                                    <button data-stepval='25' class="btn btn-primary btn-o back-step btn-wide pull-left">
		                                                        <i class="fa fa-arrow-circle-left"></i> Back
		                                                    </button>
		                                                </div>
		                                                <span></span>
		                                            </div>
		                                            <div class="col-sm-6">
		                                                <div class="form-group">
		                                                    <button data-stepval='25' class="btn btn-primary btn-o bp-next-step btn-wide pull-right">
		                                                        Next <i class="fa fa-arrow-circle-right"></i>
		                                                    </button>
		                                                    <span></span>
		                                                </div>
		                                            </div>
		                                        </div> -->
				                                   
				                            </div>
				                    </div>
				                    <div class="panel panel-white">
				                            <div class="panel-heading">
				                                <h5 class="panel-title">
				                                    <span class="icon-group-left"><i class="fa fa-ellipsis-v"></i></span> Cash Flow Projection And Balance Sheets  
				                                    <span class="icon-group-right">
				                                    <!-- <i class="fa fa-wrench pull-right"></i>
				                                    <a class="btn btn-xs pull-right panel-collapse" href="#" data-panel-group="epic-accordion">
	                        						<i class="fa fa-chevron-up"></i>
	               					 				</a> -->
	               					 					<a class="btn btn-xs pull-right" href="#">
					                                                <i class="fa fa-wrench"></i>
		                                                </a>
		                                                <a class="btn btn-xs pull-right panel-collapse " href="#" data-panel-group="fin_group">
		                                                    <i class="fa fa-chevron-down"></i>
		                                                </a>
				                                    </span>
				                                </h5>
				                            </div>
				                            <div class="panel-body" id="bp-step-26">
		                                        <div class="row">
		                                            <div class="col-md-12">
														<textarea class="ckeditor form-control" cols="10" rows="10" name='cash_flow_proj_and_bal_sheets'>
														{!! ($businessplan)?$businessplan->bp_cash_flow_proj_and_bal_sheets:'' !!}		
														</textarea>	
												    </div> 
		                                        </div>
		                                        
		                                        <!-- <div class="row m-t-20">
		                                            <div class="col-sm-6">
		                                                <div class="form-group">
		                                                    <button data-stepval='26' class="btn btn-primary btn-o bp-back-step btn-wide pull-left">
		                                                        <i class="fa fa-arrow-circle-left"></i> Back
		                                                    </button>
		                                                </div>
		                                                <span></span>
		                                            </div>
		                                            <div class="col-sm-6">
		                                                <div class="form-group">
		                                                    <button data-stepval='26' class="btn btn-primary btn-o bp-next-step btn-wide pull-right">
		                                                        Next <i class="fa fa-arrow-circle-right"></i>
		                                                    </button>
		                                                    <span></span>
		                                                </div>
		                                            </div>
		                                        </div> -->
				                                   
				                            </div>
				                    </div>
				                    <div class="panel panel-white">
				                    	<div class="panel-heading">
				                                <h5 class="panel-title">
				                                    <span class="icon-group-left"><i class="fa fa-ellipsis-v"></i></span> Assumptions  
				                                    <span class="icon-group-right">
				                                    <!-- <i class="fa fa-wrench pull-right"></i>
				                                    <a class="btn btn-xs pull-right panel-collapse" href="#" data-panel-group="epic-accordion">
	                        						<i class="fa fa-chevron-up"></i>
	               					 				</a> -->
	               					 					<a class="btn btn-xs pull-right" href="#">
					                                                <i class="fa fa-wrench"></i>
		                                                </a>
		                                                <a class="btn btn-xs pull-right panel-collapse " href="#" data-panel-group="fin_group">
		                                                    <i class="fa fa-chevron-down"></i>
		                                                </a>
				                                    </span>
				                                </h5>
				                        </div>
				                        <div class="panel-body" id="bp-step-27">
				                        	<div class="row">
		                                            <div class="col-md-12">
														<textarea class="ckeditor form-control" cols="10" rows="10" name='assumptions'>
														{!! ($businessplan)?$businessplan->bp_assumptions:'' !!}		
														</textarea>	
												    </div> 
		                                    </div>
		                                     <!-- <div class="row m-t-20">
	                                        	<div class="col-md-12">	
													<div class="form-group">
														<button class="btn btn-primary btn-o bp-back-step btn-wide pull-left" data-stepval='23'>
															<i class="fa fa-circle-arrow-left"></i> Back
														</button>
														<button class="btn btn-primary btn-o next-step btn-wide pull-right" data-stepval='27'>
															Finish
														</button>
													</div>
												</div>	
	                                        </div>	 -->
				                        </div>
				                    </div>	
							  	</div>
							</div>
							<div class="col-md-12 m-r-20">	
								<div class="form-group">
									<!-- <button class="btn btn-primary btn-o bp-back-step btn-wide pull-left" data-stepval='23'>
										<i class="fa fa-circle-arrow-left"></i> Back
									</button> -->
									<button class="btn btn-primary btn-o next-step btn-wide pull-right" data-stepval='27'>
										Finish
									</button>
								</div>
							</div>	
							<!-- <div class="col-md-12">	
								<div class="form-group">
									<button class="btn btn-primary btn-o back-step btn-wide pull-left" data-stepval='24'>
										<i class="fa fa-circle-arrow-left"></i> Back
									</button>
									<button class="btn btn-primary btn-o next-step btn-wide pull-right" data-stepval='24'>
										Next <i class="fa fa-arrow-circle-right"></i>
									</button>
								</div>
							</div> -->
						</div>
					</div>
				<!-- End: step-7  Financial Plan   FORM WIZARD ACCORDION -->
			</div>
		    </div>
			</form>
			<!-- end: WIZARD FORM -->
		</div>
	</div>
</div>

