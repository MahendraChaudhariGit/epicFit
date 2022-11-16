<div class="container-fluid container-fullw bg-white vp-form" ng-app="vf-business-plan">

	<div class="starting-screen fade-in" ng-controller="BPController">
		<div class="enter-btn active">
			<button type="button" class="btn btn-primary" ng-click="startFormInput()">
				Start <i class="fa fa-check" aria-hidden="true"></i>
			</button>
			<span class="press-enter">press <b>ENTER</b> to continue</span>

			<div class="starting-screen-input-container">
				<div class="starting-screen-input-overlay"></div>
				<input id="input-starting-screen" type="text" ng-keypress="pressEnter($event)">
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-md-12  table-responsive">
			<!-- <h5 class="over-title margin-bottom-15">Wizard <span class="text-bold">demo</span></h5> -->
			<!-- <p>
				Some textboxes in this example is required.
			</p> -->

			<script>
				$(document).ready(function () {
					var h = $('.main-content').height();

					$('#app').css({'min-height': (h * 1.3)+'px'});
                })
			</script>
			<!-- start: WIZARD FORM -->
			<input id="m-selected-step" type="hidden" value="1">
			<form name="vf_business_plan" action="#" role="form" class="smart-wizard" id="businessplan-form" novalidate="novalidate">
				<input type="hidden" name="businessplan_id" value="{{($businessplan)?$businessplan->bp_id:0}}" />
				<div id="bunisesspalnWizard" class="swMain vf_business_plan" >

					<!-- start: WIZARD SEPS -->
					<ul class="anchor custom-anchor">
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
								<span class="stepDesc"> <small> Company & Management Summary </small></span>
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
								<span class="stepDesc"> <small> Financial Plan </small> </span>
							</a>
						</li>
					</ul>

					<div class="stepContainer" style="height: 559px;">

							<!-- Start: step-1 Executive Summary FORM WIZARD ACCORDION -->
						<div id="step-1" class="content" data-group='ex_summary' ng-controller="BPWidgetOne" style="display: block;">

							<div class="row vp-form-container container-bp-step-1">

								<!-- Start: Description text Step-1 -->
								<div class="col-md-12">
									<div class="container">
										<div class="container">
											<div class="col-xs-12" id="msg">
											</div>
										</div>
									</div>
									<div class="step-description">
										<p>In this section the main things to discuss are:</p>
										<ul>
											<li>Company structure, location and brief explanation of service and market needs business solves</li>
											<li>Services and products</li>
											<li>Market analysis</li>
											<li>Business strategy</li>
											<li>Management</li>
											<li>Financial plan</li>
										</ul>
									</div>
								</div>
								<!-- End: Description text -->


								<div class="col-md-12">

									<ul id="viewport-1" class="vp-form-input-list">

										<!-- start: company | 0 -->
										<li class="vp-item vp-form-active" data-index="0" data-sub-index="null" data-type="text" data-valid="@{{vf_business_plan.company.$valid}}">
											<div class="vp-input input-text-name">
												<h3 class="vp-index pull-left">1. &nbsp;&nbsp;</h3>

												<div class="input-header">
													<h3 class="mbi-0">
														<!-- label -->
														<i class="fa fa-arrow-right" aria-hidden="true"></i>
														<span>
															<b>Company</b>
															<i class="type-popover fa fa-question-circle"
															   data-content="<p>EPICFIT STUDIOS is a Limited Company located on Auckland’s North Shore in New Zealand.</p>
																		<p>EPICFIT STUDIOS is a non-intimidation training environment for people of all abilities, from specialized athletes through to kids learning their fundamental motor skills. This model caters for Health & Wellness, Rehabilitation and general fitness requirements for the greater population.</p>"
															   rel="popover" data-trigger="hover"  data-html="true" ></i>
														</span>
														<!-- description -->
														<div class="description">
															<p>This is a brief explanation of what the company structure would be, this may include:</p>
															<ul>
																<li>Sole trader</li>
																<li>Partnership</li>
																<li>Company</li>
															</ul>
															<p>This would also include the company location and address.</p>
															<p>A brief explanation of what the company primary business is and who it will service.</p>
														</div>
														<!-- end description -->
													</h3>
												</div> <!-- end: INPUT HEADER -->

												<div class="input-body mb">
													<div class="row">
														<div class="col-sm-8 ml-20">
															<textarea rows="3" id="company" class="ckeditor" name="company" ng-model="company" ng-init="company='{!! ($businessplan) ? $businessplan->bp_company : '' !!}'" placeholder="" class="form-control">{!! ($businessplan) ? $businessplan->bp_company : '' !!}</textarea>
														</div>
													</div>

													<div ng-if="vf_business_plan.company.$touched && vf_business_plan.company.$invalid" class="vp-tooltip">
														<span>This field is required!</span>
													</div>

													<div ng-show="vf_business_plan.company.$valid" class="enter-btn active">
														<button type="button" class="mli-20 btn btn-primary" ng-click="jumpToNextInput()">
															OK <i class="fa fa-check" aria-hidden="true"></i>
														</button>
														<span class="press-enter">click <b>OK</b></span>
													</div>
												</div> <!-- end: INPUT BODY -->
											</div> <!-- end: INPUT TEXT NAME -->
											<div class="clear-both"></div>
										</li>
										<!-- end: company | 0 -->



										<!-- start: services_products | 1 -->
										<li class="vp-item vp-form-active" data-index="1" data-sub-index="null" data-type="text" data-valid="@{{vf_business_plan.services_products.$valid}}">
											<div class="vp-input input-text-name">
												<h3 class="vp-index pull-left">2. &nbsp;&nbsp;</h3>

												<div class="input-header">
													<h3 class="mbi-0">
														<!-- label -->
														<i class="fa fa-arrow-right" aria-hidden="true"></i>
														<span>
															<b>Services & Products</b>
															<i class="type-popover fa fa-question-circle"
															   data-content="
																	<p>EPICFIT Studios provides individuals with the opportunity to experience training in a non-intimidating yet result based train-ing studio concentrating on a holistic approach to Health & Wellness and Nutrition.</p>
																		<p>Training is provided in a fun T.E.A.M (Together Everyone Achieves More) training environment by trained professionals using tried and tested training techniques, simple and effective high quality equipment, varied times to cater for the busiest indi-vidual and safe and fun training programs and routines. All movements within our studio are functional and relate to every-day movements and our motto movement matters is reflected in everything we do.</p>
																		<p>By creating a safe, fun and effective training environment and by the added support within the T.E.A.M individuals are more likely to create new habits relating to physical activity resulting in less injuries, eating healthier and the correct portions which assist in healthy weight loss and building lean muscle.</p>
																		<p>At EPIC we believe” if the client has the will, we have the way.” We can provide all the resources and support that is required for a client to achieve their SMARTER Goals.</p>
																	"
																	rel="popover" data-trigger="hover" data-html="true" ></i>
														</span>
														<!-- description -->
														<div class="description">
															<p>It this section you offer a description of your offerings to your clientele and how you will be delivering these s services and products.</p>
															<p>Provide benefits for the client in this section and ensure that you understand the reasoning behind your business and why it will succeed.</p>
														</div>
														<!-- end description -->
													</h3>
												</div> <!-- end: INPUT HEADER -->

												<div class="input-body mb">
													<div class="row">
														<div class="col-sm-8 ml-20">
															<textarea rows="3" id="services_products" name="services_products" ng-model="services_products" ng-init="services_products='{!! ($businessplan) ? $businessplan->bp_services_products : '' !!}'" placeholder="" class="form-control">{!! ($businessplan) ? $businessplan->bp_services_products : '' !!}</textarea>
														</div>
													</div>

													<div ng-if="vf_business_plan.services_products.$touched && vf_business_plan.services_products.$invalid" class="vp-tooltip">
														<span>This field is required!</span>
													</div>

													<div ng-show="vf_business_plan.services_products.$valid" class="enter-btn active">
														<button type="button" class="mli-20 btn btn-primary" ng-click="jumpToNextInput()">
															OK <i class="fa fa-check" aria-hidden="true"></i>
														</button>
														<span class="press-enter">click <b>OK</b></span>
													</div>
												</div> <!-- end: INPUT BODY -->
											</div> <!-- end: INPUT TEXT NAME -->
											<div class="clear-both"></div>
										</li>
										<!-- end: services_products | 1 -->

										<!-- start: market_analysis | 2 -->
										<li class="vp-item vp-form-active" data-index="2" data-sub-index="null" data-type="text" data-valid="@{{vf_business_plan.market_analysis.$valid}}">
											<div class="vp-input input-text-name">
												<h3 class="vp-index pull-left">3. &nbsp;&nbsp;</h3>

												<div class="input-header">
													<h3 class="mbi-0">
														<!-- label -->
														<i class="fa fa-arrow-right" aria-hidden="true"></i>
														<span>
															<b>Market Analysis</b>
															<i class="type-popover fa fa-question-circle"
															   data-content="
																	<p>EPICFIT Studios is the favorable choice for individuals who are intimidated by the standard gym model which has mirrors, people clad in spandex and gym grunters. Our main clientele are new to physical activity or have not partaken in physical activity for numerous years and are not in great shape. We also cater for clients that are receiving chiropractic, physiothera-py and osteopathy that are nearing the end of their rehabilitation and need assistance to recovery. Most of these individu-als work or live in the area and the market for these individuals is huge.</p>
																	<p>This target market is not our only clientele and EPICFIT Studios appeals to a large range of individuals.</p>
																	"
															   rel="popover" data-trigger="hover" data-html="true" ></i>
														</span>
														<!-- description -->
														<div class="description">
															<p>This discusses the niche market and the demographic and physical location of the business.<br>
																This information includes but is not limited to:</p>
															<ul>
																<li>Working class</li>
																<li>Gender</li>
																<li>Age</li>
																<li>Location</li>
															</ul>
														</div>
														<!-- end description -->
													</h3>
												</div> <!-- end: INPUT HEADER -->

												<div class="input-body mb">
													<div class="row">
														<div class="col-sm-8 ml-20">
															<textarea rows="3" id="market_analysis" name="market_analysis" ng-model="market_analysis" ng-init="market_analysis='{!! ($businessplan) ? $businessplan->bp_market_analysis : '' !!}'" placeholder="" class="form-control">{!! ($businessplan) ? $businessplan->bp_market_analysis : '' !!}</textarea>
														</div>
													</div>

													<div ng-if="vf_business_plan.market_analysis.$touched && vf_business_plan.market_analysis.$invalid" class="vp-tooltip">
														<span>This field is required!</span>
													</div>

													<div ng-show="vf_business_plan.market_analysis.$valid" class="enter-btn active">
														<button type="button" class="mli-20 btn btn-primary" ng-click="jumpToNextInput()">
															OK <i class="fa fa-check" aria-hidden="true"></i>
														</button>
														<span class="press-enter">click <b>OK</b></span>
													</div>
												</div> <!-- end: INPUT BODY -->
											</div> <!-- end: INPUT TEXT NAME -->
											<div class="clear-both"></div>
										</li>
										<!-- end: market_analysis | 2 -->

										<!-- start: business_stratergy | 3 -->
										<li class="vp-item vp-form-active" data-index="3" data-sub-index="null" data-type="text" data-valid="@{{vf_business_plan.business_stratergy.$valid}}">
											<div class="vp-input input-text-name">
												<h3 class="vp-index pull-left">4. &nbsp;&nbsp;</h3>

												<div class="input-header">
													<h3 class="mbi-0">
														<!-- label -->
														<i class="fa fa-arrow-right" aria-hidden="true"></i>
														<span>
															<b>Business Strategy</b>
															<i class="type-popover fa fa-question-circle"
															   data-content="
																	<p>EPICFIT Studios will initially use any resource available to commence business, the first stage of marketing requires that a substantial amount of direct marketing be implemented which requires cold calling in the local vicinity offering a free weeks introduction to the EPIC Experience. This complimentary introduction will consist of a 60 minute personalized consultation followed by a 30 minute benchmarking session to access the clients base fitness level relating to strength, endurance and cardiovascular ability. This introduction allows the client to experience the EPIC environment and create an interest in what we have to offer. EPIC will have an above average web and social media presence which would include interesting and rele-vant blogs to assist clients in all aspects of Health & Wellness. This will create a sense of community and support and will be a strong lead generator.</p>
																	"
															   rel="popover" data-trigger="hover" data-html="true" ></i>
														</span>
														<!-- description -->
														<div class="description">
															<p>This section explains the business strategy that ensures success. This explains the process that needs to be followed from the initial stages including setup and marketing.</p>
														</div>
														<!-- end description -->
													</h3>
												</div> <!-- end: INPUT HEADER -->

												<div class="input-body mb">
													<div class="row">
														<div class="col-sm-8 ml-20">
															<textarea rows="3" id="business_stratergy" name="business_stratergy" ng-model="business_stratergy" ng-init="business_stratergy='{!! ($businessplan) ? $businessplan->bp_business_stratergy : '' !!}'" placeholder="" class="form-control">{!! ($businessplan) ? $businessplan->bp_business_stratergy : '' !!}</textarea>
														</div>
													</div>

													<div ng-if="vf_business_plan.business_stratergy.$touched && vf_business_plan.business_stratergy.$invalid" class="vp-tooltip">
														<span>This field is required!</span>
													</div>

													<div ng-show="vf_business_plan.business_stratergy.$valid" class="enter-btn active">
														<button type="button" class="mli-20 btn btn-primary" ng-click="jumpToNextInput()">
															OK <i class="fa fa-check" aria-hidden="true"></i>
														</button>
														<span class="press-enter">click <b>OK</b></span>
													</div>
												</div> <!-- end: INPUT BODY -->
											</div> <!-- end: INPUT TEXT NAME -->
											<div class="clear-both"></div>
										</li>
										<!-- end: business_stratergy | 3 -->

										<!-- start: management | 4 -->
										<li class="vp-item vp-form-active" data-index="4" data-sub-index="null" data-type="text" data-valid="@{{vf_business_plan.management.$valid}}">
											<div class="vp-input input-text-name">
												<h3 class="vp-index pull-left">5. &nbsp;&nbsp;</h3>

												<div class="input-header">
													<h3 class="mbi-0">
														<!-- label -->
														<i class="fa fa-arrow-right" aria-hidden="true"></i>
														<span>
															<b>Management</b>
															<i class="type-popover fa fa-question-circle"
															   data-content="
																		<p>Carlyle David the founding member of Auckland New Zealand. He has been developing EPIC since 2010 and has 15 years’ experience in both I.T. / Construction project management and a successful business background while being a certified per-sonal trainer. This combination of skills provides a solid base to create an affiliate network of leads and professional health care providers that may be interested in EPICS services and products.</p>
																		<p>Lauren Mason who heads up the marketing implementation has an educational background and has been involved with EPIC since 2014 and has been involved with the initial setup of the EPICFIT Studios.</p>
																	"
															   rel="popover" data-trigger="hover" data-html="true" ></i>
														</span>
														<!-- description -->
														<div class="description">
															<p>This section discusses the founding members and any management that will be crucial to the daily operation of the business. <br>This will cover their experience, qualifications and roles.</p>
														</div>
														<!-- end description -->
													</h3>
												</div> <!-- end: INPUT HEADER -->

												<div class="input-body mb">
													<div class="row">
														<div class="col-sm-8 ml-20">
															<textarea rows="3" id="management" name="management" ng-model="management" ng-init="management='{!! ($businessplan) ? $businessplan->bp_management : '' !!}'" placeholder="" class="form-control">{!! ($businessplan) ? $businessplan->bp_management : '' !!}</textarea>
														</div>
													</div>

													<div ng-if="vf_business_plan.management.$touched && vf_business_plan.management.$invalid" class="vp-tooltip">
														<span>This field is required!</span>
													</div>

													<div ng-show="vf_business_plan.management.$valid" class="enter-btn active">
														<button type="button" class="mli-20 btn btn-primary" ng-click="jumpToNextInput()">
															OK <i class="fa fa-check" aria-hidden="true"></i>
														</button>
														<span class="press-enter">click <b>OK</b></span>
													</div>
												</div> <!-- end: INPUT BODY -->
											</div> <!-- end: INPUT TEXT NAME -->
											<div class="clear-both"></div>
										</li>
										<!-- end: management | 4 -->

										<!-- start: financial_plan | 5 -->
										<li class="vp-item vp-form-active" data-index="5" data-sub-index="null" data-type="text" data-valid="@{{vf_business_plan.financial_plan.$valid}}">
											<div class="vp-input input-text-name">
												<h3 class="vp-index pull-left">6. &nbsp;&nbsp;</h3>

												<div class="input-header">
													<h3 class="mbi-0">
														<!-- label -->
														<i class="fa fa-arrow-right" aria-hidden="true"></i>
														<span>
															<b>Financial Plan</b>
															<i class="type-popover fa fa-question-circle"
															   data-content="
																		<p>EPIC Studios will get 100 clients within the first year with an average spend of $150 per month, this will allow for a turnover of $15000 per month which allows for breakeven to be achieved at this point allowing the company to be cash flow positive going into year 2. The growth required is a constant introduction of clients at a rate of 2 new clients a week.</p>
																		<p>Once this milestone has been achieved the business will grow at a rate of another 100 clients in the following year which will result in a 100% capacity to be achieved within Alpha 1. This model relies on a good retention rate and recurring revenues. This is too be obtained through EPIC service and client management.</p>
																		<p>EPIC Alpha 1 will secure $80000 through an external investor. This amount will be used to obtain a facility, facility setup, equipment and startup expenses, there will also be an amount allocated as a cushion to fun d the startup year until the busi-ness is cash flow positive.</p>
																		<p>This investment will be at an agreed rate of 15% and will be paid back within the first 3 years. This is to be paid back at $1000 per month which is the interest portion with the initial capital of $80000 paid back in a lump sum payment.</p>
																	"
															   rel="popover" data-trigger="hover" data-html="true" ></i>
														</span>
														<!-- description -->
														<div class="description">
															<p>The financial plan addresses the cash flow requirements and growth to ensure the business success. This will discuss the reve-nue models.</p>

															<p>This section also discusses the setup fees and any loans required to initial commence with business.</p>
														</div>
														<!-- end description -->
													</h3>
												</div> <!-- end: INPUT HEADER -->

												<div class="input-body mb">
													<div class="row">
														<div class="col-sm-8 ml-20">
															<textarea rows="3" id="financial_plan" name="financial_plan" ng-model="financial_plan" ng-init="financial_plan='{!! ($businessplan) ? $businessplan->bp_financial_plan : '' !!}'" placeholder="" class="form-control">{!! ($businessplan) ? $businessplan->bp_financial_plan : '' !!}</textarea>
														</div>
													</div>

													<div ng-if="vf_business_plan.financial_plan.$touched && vf_business_plan.financial_plan.$invalid" class="vp-tooltip">
														<span>This field is required!</span>
													</div>

													<div ng-show="vf_business_plan.financial_plan.$valid" class="enter-btn active">
														<button type="button" class="mli-20 btn btn-primary" ng-click="jumpToNextInput()">
															OK <i class="fa fa-check" aria-hidden="true"></i>
														</button>
														<span class="press-enter">click <b>OK</b></span>
													</div>
												</div> <!-- end: INPUT BODY -->
											</div> <!-- end: INPUT TEXT NAME -->
											<div class="clear-both"></div>
										</li>
										<!-- end: financial_plan | 5 -->

									</ul> <!-- end viewport 1 -->

								</div>  <!-- end col12 -->

							</div>
							
								<div class="col-md-12 ">
									<div class="form-group">
										<button type="button" data-stepval='1' class="btn btn-primary btn-o next-step btn-wide pull-right bp-first-step">
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

								<div class="col-sm-12">
									<div class="vp-progress-bar">
										<div class="col-sm-10 col-sm-offset-2 vp-progress">
											<div class="vp-progress-content">
												<p>@{{ percentCompleted }}% complete</p>
												<progress value="@{{ percentCompleted }}" max="100"> </progress>
											</div> <!--  -->
											<div class="create-type-form">
												<a class="create-account" target="_blank" href="javascript:void(0)">Powered by Epic Trainer</a>
												<a href="javascript:void(0)" ng-click="jumpToPrevInput()"><i class="fa fa-chevron-up" aria-hidden="true"></i></a>
												<a href="javascript:void(0)" ng-click="jumpToNextInput()"><i class="fa fa-chevron-down" aria-hidden="true"></i></a>
											</div> <!--  -->
										</div> <!-- end: COL8 || SUBMIT -->
									</div>
								</div> <!-- end col12 -->

							</div> <!-- end: row -->
						</div>
						<!-- End: step-1 Executive Summmary FORM WIZARD ACCORDION -->





						<!-- Start: step-2  Company Summary FORM WIZARD ACCORDION -->
						<div id="step-2" class="content" data-group='company_summary' ng-controller="BPWidgetTwo" style="display: none;">

							<div class="row vp-form-container container-bp-step-2">
								<!-- Start: Description text step-2 -->
								<div class="col-md-12">
									<div class="step-description">
										<p>In this section the main things to discuss are:</p>
										<ul>
											<li>Company structure and when company was formed and business location</li>
											<li>Company ownership</li>
											<li>Company Profile</li>
											<li>Company Vision & Mission Statement</li>
											<li>Accomplishments</li>
											<li>Unique Qualifications</li>
										</ul>
									</div>
								</div>
								<!-- End: Description text -->

								<div class="col-md-12">

									<!-- start: VIEW PORT 2 -->
									<ul id="viewport-2" class="vp-form-input-list">

										<!-- start: company_ownership_location | 0 -->
										<li class="vp-item vp-form-active" data-index="0" data-sub-index="null" data-type="text" data-valid="@{{vf_business_plan.company_ownership_location.$valid}}">
											<div class="vp-input input-text-name">
												<h3 class="vp-index pull-left">1. &nbsp;&nbsp;</h3>

												<div class="input-header">
													<h3 class="mbi-0">
														<!-- label -->
														<i class="fa fa-arrow-right" aria-hidden="true"></i>
														<span>
															<b>Company Structure, Ownership, Offerings & Location</b>
															<i class="type-popover fa fa-question-circle"
															   data-content="
																	<p>EPIC is a registered company that has been registered in New Zealand under the name “EPIC Alpha 1 Limited” since 2014. In addition to this individual companies will be established for each independent training studio which is required to stay with-in our business plan and exit strategy.</p>
																	<p>EPIC Alpha 1 Limited's sole director and shareholder is Carlyle David Van Rensburg while shares within the subsidiary com-panies would include EPIC FITNESS LIMITED, Carlyle David Van Rensburg, Lauren Mason and other shareholders and future owner operators.</p>
																	<p>EPIC is an all-encompassing Health & Wellness hub that addresses all aspects of fitness from general health to strength and conditioning for all ability levels. This is obtained by creating a large network of trainers and health professionals who act either as internal instructor or trainers or as external advisors. Our EPIC Experience allows for a broad training spectrum that is all inclusive of any client irrespective of injury or performance improvements required.</p>
																	<p>AT EPIC we have the network and knowledge to be “Specializing in generalizing” which results in various goals and desires to be delivered to our growing client base. We create healthy and lasting habits that result in a happy and healthy lifestyle. Our fusion training ensures that all 11 aspects of fitness are focused on, these are listed below:</p>
																	<strong>HEALTH COMPONENTS</strong>
																	<ul>
																		<li>Body composition</li>
																		<li>Cardiovascular endurance</li>
																		<li>Muscle strength</li>
																		<li>Muscular endurance</li>
																		<li>Flexibility</li>
																	</ul>
																	<strong>SKILL RELATED COMPONENTS</strong>
																	<ul>
																		<li>Balance</li>
																		<li>Coordination</li>
																		<li>Agility</li>
																		<li>Power</li>
																		<li>Speed</li>
																		<li>Reaction time</li>
																	</ul>
																	<p>At EPIC we train smarter and harder to ensure we are a fully Result Based Training Venue.</p>
																	<p>EPICs HQ will be housed within one of the EPICFIT Studios and the initial location would be on Auckland North Shore in New Zealand. This venue will be approximately 250m2 and will be light industrial warehouse space which would consist of multi-ple training and consultation area, rest rooms and showers and a reception area.</p>
																	"
															   rel="popover" data-trigger="hover"  data-html="true" ></i>
														</span>
														<!-- description -->
														<div class="description">
															<p>This section addresses the company structure, who the owners are and their involvement and responsibilities, included in this should be the incorporation year.</p>

															<p>The offerings of the venue are also listed in this section allowing you to have a clear understanding of primary and secondary offerings for your clientele.</p>

															<p>Your location will also need to represent your target area and the target market within this area.</p>
														</div>
														<!-- end description -->
													</h3>
												</div> <!-- end: INPUT HEADER -->

												<div class="input-body mb">
													<div class="row">
														<div class="col-sm-8 ml-20">
															<textarea rows="3" id="company_ownership_location" class="ckeditor" name="company_ownership_location" ng-model="company_ownership_location" ng-init="company_ownership_location='{!! ($businessplan) ? $businessplan->bp_company_ownership_location : '' !!}'" placeholder="" class="form-control">{!! ($businessplan) ? $businessplan->bp_company_ownership_location : '' !!}</textarea>
														</div>
													</div>

													<div ng-if="vf_business_plan.company_ownership_location.$touched && vf_business_plan.company_ownership_location.$invalid" class="vp-tooltip">
														<span>This field is required!</span>
													</div>

													<div ng-show="vf_business_plan.company_ownership_location.$valid" class="enter-btn active">
														<button type="button" class="mli-20 btn btn-primary" ng-click="jumpToNextInput()">
															OK <i class="fa fa-check" aria-hidden="true"></i>
														</button>
														<span class="press-enter">click <b>OK</b></span>
													</div>
												</div> <!-- end: INPUT BODY -->
											</div> <!-- end: INPUT TEXT NAME -->
											<div class="clear-both"></div>
										</li>
										<!-- end: company_ownership_location | 0 -->


										<!-- start: management_structure | 1 -->
										<li class="vp-item vp-form-active" data-index="1" data-sub-index="null" data-type="text" data-valid="@{{vf_business_plan.management_structure.$valid}}">
											<div class="vp-input input-text-name">
												<h3 class="vp-index pull-left">2. &nbsp;&nbsp;</h3>

												<div class="input-header">
													<h3 class="mbi-0">
														<!-- label -->
														<i class="fa fa-arrow-right" aria-hidden="true"></i>
														<span>
															<b>Management Structure</b>
															<i class="type-popover fa fa-question-circle"
															   data-content="
																	<p>The company management and daily operational duties will be done by Carlyle David Van Rensburg, marketing and promo-tions will be done by Lauren Mason and training sessions will be done by Gareth Heeps. All works will be completed by these individuals until there has been enough growth to employ additional individuals to assist where needed. This will eventuate in year two of operation when additional trainers will be required.</p>
																	<p>All staff will need to be trained within the EPIC Trainer process allowing for all staff to be on the same level of understanding their roles and what us as EPIC offer as the EPIC Experience. All staff will be required to be EPIC Trainer certified before work-ing with clients.</p>
																	"
															   rel="popover" data-trigger="hover"  data-html="true" ></i>
														</span>
														<!-- description -->
														<div class="description">
															<p>What is the management structure and what does the management tree look like, explain roles and responsibilities?</p>
														</div>
														<!-- end description -->
													</h3>
												</div> <!-- end: INPUT HEADER -->

												<div class="input-body mb">
													<div class="row">
														<div class="col-sm-8 ml-20">
															<textarea rows="3" id="management_structure" class="ckeditor" name="management_structure" ng-model="management_structure" ng-init="management_structure='{!! ($businessplan) ? $businessplan->bp_management_structure : '' !!}'" placeholder="" class="form-control">{!! ($businessplan) ? $businessplan->bp_management_structure : '' !!}</textarea>
														</div>
													</div>

													<div ng-if="vf_business_plan.management_structure.$touched && vf_business_plan.management_structure.$invalid" class="vp-tooltip">
														<span>This field is required!</span>
													</div>

													<div ng-show="vf_business_plan.management_structure.$valid" class="enter-btn active">
														<button type="button" class="mli-20 btn btn-primary" ng-click="jumpToNextInput()">
															OK <i class="fa fa-check" aria-hidden="true"></i>
														</button>
														<span class="press-enter">click <b>OK</b></span>
													</div>
												</div> <!-- end: INPUT BODY -->
											</div> <!-- end: INPUT TEXT NAME -->
											<div class="clear-both"></div>
										</li>
										<!-- end: management_structure | 1 -->

									</ul>
									<!-- end: VIEW PORT 2 -->
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

								<div class="col-sm-12">
									<div class="vp-progress-bar">
										<div class="col-sm-10 col-sm-offset-2 vp-progress">
											<div class="vp-progress-content">
												<p>@{{ percentCompleted }}% complete</p>
												<progress value="@{{ percentCompleted }}" max="100"> </progress>
											</div> <!--  -->
											<div class="create-type-form">
												<a class="create-account" target="_blank" href="javascript:void(0)">Powered by Epic Trainer</a>
												<a href="javascript:void(0)" ng-click="jumpToPrevInput()"><i class="fa fa-chevron-up" aria-hidden="true"></i></a>
												<a href="javascript:void(0)" ng-click="jumpToNextInput()"><i class="fa fa-chevron-down" aria-hidden="true"></i></a>
											</div> <!--  -->
										</div> <!-- end: COL8 || SUBMIT -->
									</div>
								</div> <!-- end col12 -->

							</div>
						</div>
						<!-- End: step-2  Company Summary FORM WIZARD ACCORDION -->

						<!-- Start: step-3 Services and Products  FORM WIZARD ACCORDION -->
						<div id="step-3" class="content" data-group='services_products' ng-controller="BPWidgetThree" style="display: none;">
							<div class="row vp-form-container container-bp-step-3">

								<div class="col-md-12">

									<!-- start: VIEW PORT 3 -->
									<ul id="viewport-3" class="vp-form-input-list">

										<!-- start: description | 0 -->
										<li class="vp-item vp-form-active" data-index="0" data-sub-index="null" data-type="text" data-valid="@{{vf_business_plan.description.$valid}}">
											<div class="vp-input input-text-name">
												<h3 class="vp-index pull-left">1. &nbsp;&nbsp;</h3>

												<div class="input-header">
													<h3 class="mbi-0">
														<!-- label -->
														<i class="fa fa-arrow-right" aria-hidden="true"></i>
														<span>
															<b>Description</b>
															<i class="type-popover fa fa-question-circle"
															   data-content="
															   			<p>AT EPICFIT Studio our primary offering is to provide clients with the perfect training that exceeds their expectation and spe-cifically for their ability within a T.E.A.M Training or 1 on 1 environment if required. This is provided using specialized and high quality equipment and training techniques at convenient times to cater for any individuals requirements. Training is provided by a qualified EPIC Trainer that has the required experience and knowledge to deliver this sessions effectively and safely creating a fun and friendly experience.</p>
																		<p>This fun, safe, effective and functional training ensures all clients enjoy physical activity which results in individuals being more likely to continue training more often, improve their nutrition, limit their injuries and chances of getting injured while losing size and increasing lean muscle mass. Our EPIC programs cater for most goals and desires.</p>
																	"
															   rel="popover" data-trigger="hover"  data-html="true" ></i>
														</span>
														<!-- description -->
														<div class="description">
															<p>Here you need to briefly describe what services and products your business will offer, be clear and concise when mentioning these and be sure that you understand the requirements to offer these.</p>
														</div>
														<!-- end description -->
													</h3>
												</div> <!-- end: INPUT HEADER -->

												<div class="input-body mb">
													<div class="row">
														<div class="col-sm-8 ml-20">
															<textarea rows="3" id="description" class="ckeditor" name="description" ng-model="description" ng-init="description='{!! ($businessplan) ? $businessplan->bp_description : '' !!}'" placeholder="" class="form-control">{!! ($businessplan) ? $businessplan->bp_description : '' !!}</textarea>
														</div>
													</div>

													<div ng-if="vf_business_plan.description.$touched && vf_business_plan.description.$invalid" class="vp-tooltip">
														<span>This field is required!</span>
													</div>

													<div ng-show="vf_business_plan.description.$valid" class="enter-btn active">
														<button type="button" class="mli-20 btn btn-primary" ng-click="jumpToNextInput()">
															OK <i class="fa fa-check" aria-hidden="true"></i>
														</button>
														<span class="press-enter">click <b>OK</b></span>
													</div>
												</div> <!-- end: INPUT BODY -->
											</div> <!-- end: INPUT TEXT NAME -->
											<div class="clear-both"></div>
										</li>
										<!-- end: description | 0 -->


										<!-- start: features_benefits | 1 -->
										<li class="vp-item vp-form-active" data-index="1" data-sub-index="null" data-type="text" data-valid="@{{vf_business_plan.features_benefits.$valid}}">
											<div class="vp-input input-text-name">
												<h3 class="vp-index pull-left">2. &nbsp;&nbsp;</h3>

												<div class="input-header">
													<h3 class="mbi-0">
														<!-- label -->
														<i class="fa fa-arrow-right" aria-hidden="true"></i>
														<span>
															<b>Features & Benefits</b>
															<i class="type-popover fa fa-question-circle"
															   data-content="
																		<p>By creating a professional and detailed consultation process we ensure that all aspects of the client’s requirements and abili-ties are addressed in an orderly fashion and that any injuries and ability restrictions are flagged in the initial consultation. This administrative consultation process in followed by a brief movement competency and introduction to the basic move-ment patterns which are covered in this order to ensure that no muscle fatigue in a certain area occurs:</p>
																		<ul>
																			<li>Squat – standard squat</li>
																			<li>Pull – Low & high row</li>
																			<li>Push – Chest press</li>
																			<li>Lunge – Stand single leg or alternating depending on ability</li>
																			<li>Pull – Bicep curl</li>
																			<li>Push – Triceps press</li>
																			<li>Rotate – Arms crossed at shoulder height and controlled rotation</li>
																			<li>Bend – Deadlift with hands placed behind hips fingers pointed downwards</li>
																		</ul>
																		<p>These basic movements will ensure that the client can commence with T.E.A.M training once the benchmarking session and second introduction to movement has been completed allowing for a seamless transition into our training experience. All the sessions are based around these simple movement patterns and are varied according each participants ability level by either movement or intensity. Session vary between either time based intervals or repetitions and all individuals are moni-tored through heart rate straps that are provided for the sessions. This allows for tracking a client’s workout while ensuring that they work to their full potential.</p>
																		<p>This initial introduction to EPIC is complimentary and obligation free.</p>
																		<p>Session price start from an introductory fee of $15 and may be as low as $10 for sessions on the day according to availabil-ity. Payment is done upfront and charged at monthly intervals.</p>
																	"
															   rel="popover" data-trigger="hover"  data-html="true" ></i>
														</span>
														<!-- description -->
														<div class="description">
															<p>Explain in detail what the benefits and features are that make your services and products superior to the competition.</p>
														</div>
														<!-- end description -->
													</h3>
												</div> <!-- end: INPUT HEADER -->

												<div class="input-body mb">
													<div class="row">
														<div class="col-sm-8 ml-20">
															<textarea rows="3" id="features_benefits" class="ckeditor" name="features_benefits" ng-model="features_benefits" ng-init="features_benefits='{!! ($businessplan) ? $businessplan->bp_features_benefits : '' !!}'" placeholder="" class="form-control">{!! ($businessplan) ? $businessplan->bp_features_benefits : '' !!}</textarea>
														</div>
													</div>

													<div ng-if="vf_business_plan.features_benefits.$touched && vf_business_plan.features_benefits.$invalid" class="vp-tooltip">
														<span>This field is required!</span>
													</div>

													<div ng-show="vf_business_plan.features_benefits.$valid" class="enter-btn active">
														<button type="button" class="mli-20 btn btn-primary" ng-click="jumpToNextInput()">
															OK <i class="fa fa-check" aria-hidden="true"></i>
														</button>
														<span class="press-enter">click <b>OK</b></span>
													</div>
												</div> <!-- end: INPUT BODY -->
											</div> <!-- end: INPUT TEXT NAME -->
											<div class="clear-both"></div>
										</li>
										<!-- end: features_benefits | 1 -->


										<!-- start: competitors | 2 -->
										<li class="vp-item vp-form-active" data-index="2" data-sub-index="null" data-type="text" data-valid="@{{vf_business_plan.competitors.$valid}}">
											<div class="vp-input input-text-name">
												<h3 class="vp-index pull-left">3. &nbsp;&nbsp;</h3>

												<div class="input-header">
													<h3 class="mbi-0">
														<!-- label -->
														<i class="fa fa-arrow-right" aria-hidden="true"></i>
														<span>
															<b>Competitors</b>
															<i class="type-popover fa fa-question-circle"
															    data-content="
																	<p>EPIC has no direct competitors as we provide a unique form of T.E.A.M Training and specialized 1 on1 training that caters for a wide variety of individuals and we have eliminated the common barriers that prevent people from partaking in physi-cal activity or frequenting a gym or training venue. There are numerous physical activities that are available to individuals such as:</p>
																	<ul>
																		<li>Gyms</li>
																		<li>Mobile trainers</li>
																		<li>Online trainers</li>
																		<li>Running</li>
																		<li>Sports clubs</li>
																		<li>Cycling</li>
																		<li>Hiking and walking</li>
																	</ul>
																	<p>EPICFIT Studios has numerous other training venues within our immediate area and our intention is to attract clients from these venues through offering an EPIC Experience backed up by EPIC Service and customer care.</p>
															    "
															    rel="popover" data-trigger="hover"  data-html="true" ></i>
														</span>
														<!-- description -->
														<div class="description">
															<p>Discuss your competitors within the industry that are directly competing with you, include similar businesses that are within a close proximity that may affect your growth and that may change their business model to be more in line with yours if you are a success.</p>
														</div>
														<!-- end description -->
													</h3>
												</div> <!-- end: INPUT HEADER -->

												<div class="input-body mb">
													<div class="row">
														<div class="col-sm-8 ml-20">
															<textarea rows="3" id="competitors" class="ckeditor" name="competitors" ng-model="competitors" ng-init="competitors='{!! ($businessplan) ? $businessplan->bp_competitors : '' !!}'" placeholder="" class="form-control">{!! ($businessplan) ? $businessplan->bp_competitors : '' !!}</textarea>
														</div>
													</div>

													<div ng-if="vf_business_plan.competitors.$touched && vf_business_plan.competitors.$invalid" class="vp-tooltip">
														<span>This field is required!</span>
													</div>

													<div ng-show="vf_business_plan.competitors.$valid" class="enter-btn active">
														<button type="button" class="mli-20 btn btn-primary" ng-click="jumpToNextInput()">
															OK <i class="fa fa-check" aria-hidden="true"></i>
														</button>
														<span class="press-enter">click <b>OK</b></span>
													</div>
												</div> <!-- end: INPUT BODY -->
											</div> <!-- end: INPUT TEXT NAME -->
											<div class="clear-both"></div>
										</li>
										<!-- end: competitors | 2 -->


										<!-- start: competitive_advantage | 3 -->
										<li class="vp-item vp-form-active" data-index="3" data-sub-index="null" data-type="text" data-valid="@{{vf_business_plan.competitive_advantage.$valid}}">
											<div class="vp-input input-text-name">
												<h3 class="vp-index pull-left">4. &nbsp;&nbsp;</h3>

												<div class="input-header">
													<h3 class="mbi-0">
														<!-- label -->
														<i class="fa fa-arrow-right" aria-hidden="true"></i>
														<span>
															<b>Competitive Advantage</b>
															<i class="type-popover fa fa-question-circle"
															   data-content="
																	<p>EPIC is hoping to change the way that the general public view fitness and training studios by changing the way these individ-uals get trained and by being a Result Based Training Venue. We have assessed the existing fitness industry and new trends such as 24hr gyms and the regular global gym market and have removed the barriers that are associated with the venues.</p>

																	<p>EPICFIT Studios offer personalized T.E.A.M Training at a fraction of the cost of a Personal Trainer and we deliver the same if not better results than the standard Personal Trainer can provide. We are typically 2/3 to 3/4 more affordable that the aver-age personal trainer. This price point allows us to deliver the same service more often which results in a greater chance of achieving goals.</p>

																	<p>Our sessions are designed to cover both the cardiovascular and resistance elements of training and focus on full body func-tional movement. “EPIC is a total body training system that caters for the needs of any individual irrespective of their abil-ity.” Our main objective is to create safe and fun workouts that are at a high intensity that are adaptable to any ability, age, gender and any other ability dependent aspect of training.</p>
																	<p>AT present data is indicating that the standard gyms and 24 hr. venues are not retaining their clientele and individuals are moving away from the cost effective venue that offer no support.</p>
																	<p>AT EPICFIT Studios we do not have any intimidating expensive machines, we do not have racks and weight plates, and we don’t focus on isolated movement patterns instead we create balance and strength through compound movements that are functional and beneficial to everyday living. This allows us to cater for a larger client base that is focused more on Health & Wellness than solely on cosmetics. Our client achieve a large range of goals ranging from improved health, healthy blood pressure, loss of body fat, increased muscle mass and improved performance in the components of fitness.</p>
																"
															   rel="popover" data-trigger="hover"  data-html="true" ></i>
														</span>
														<!-- description -->
														<div class="description">
															<p>What makes you think that your services and products are superior to you competitors, give a detailed description of your unique selling points and services.</p>
														</div>
														<!-- end description -->
													</h3>
												</div> <!-- end: INPUT HEADER -->

												<div class="input-body mb">
													<div class="row">
														<div class="col-sm-8 ml-20">
															<textarea rows="3" id="competitive_advantage" class="ckeditor" name="competitive_advantage" ng-model="competitive_advantage" ng-init="competitive_advantage='{!! ($businessplan) ? $businessplan->bp_competitive_advantage : '' !!}'" placeholder="" class="form-control">{!! ($businessplan) ? $businessplan->bp_competitive_advantage : '' !!}</textarea>
														</div>
													</div>

													<div ng-if="vf_business_plan.competitive_advantage.$touched && vf_business_plan.competitive_advantage.$invalid" class="vp-tooltip">
														<span>This field is required!</span>
													</div>

													<div ng-show="vf_business_plan.competitive_advantage.$valid" class="enter-btn active">
														<button type="button" class="mli-20 btn btn-primary" ng-click="jumpToNextInput()">
															OK <i class="fa fa-check" aria-hidden="true"></i>
														</button>
														<span class="press-enter">click <b>OK</b></span>
													</div>
												</div> <!-- end: INPUT BODY -->
											</div> <!-- end: INPUT TEXT NAME -->
											<div class="clear-both"></div>
										</li>
										<!-- end: competitive_advantage | 3 -->


										<!-- start: future_expansion | 4 -->
										<li class="vp-item vp-form-active" data-index="4" data-sub-index="null" data-type="text" data-valid="@{{vf_business_plan.future_expansion.$valid}}">
											<div class="vp-input input-text-name">
												<h3 class="vp-index pull-left">5. &nbsp;&nbsp;</h3>

												<div class="input-header">
													<h3 class="mbi-0">
														<!-- label -->
														<i class="fa fa-arrow-right" aria-hidden="true"></i>
														<span>
															<b>Future Expansion</b>
															<i class="type-popover fa fa-question-circle"
															   data-content="
																	<p>The added benefit of our business model is the small client base we require to break even and low startup costs allowing us to open numerous venues within close proximity of each other while still being successful and not saturating the market. This cater for the increased need of our services and products in our local area while staying small enough to have the close relationships each Studio has with its clientele</p>

																	<p>We have also allowed for the expansion of our unique training approach to be incorporated into local schools allowing for kids to become more active by implementing and teaching the fundamental motor skills and functional movements either at the school or within our venue which allows for billable timeslots in times that are usually not occupied. This will be offered as activity programs, workshops and as education programs.</p>

																	<p>Our online presence will increase as we increase our offering of merchandise and services which may include nutritional products and seminars, cooking workshops, movement screen and posture analysis screening workshops. These additional services and products will allow for increased revenue internally and additional support for our clients.</p>
																"
															   rel="popover" data-trigger="hover"  data-html="true" ></i>
														</span>
														<!-- description -->
														<div class="description">
															<p>What are your plans to grow your business and what does this future expansion require?</p>
														</div>
														<!-- end description -->
													</h3>
												</div> <!-- end: INPUT HEADER -->

												<div class="input-body mb">
													<div class="row">
														<div class="col-sm-8 ml-20">
															<textarea rows="3" id="future_expansion" class="ckeditor" name="future_expansion" ng-model="future_expansion" ng-init="future_expansion='{!! ($businessplan) ? $businessplan->bp_future_expansion : '' !!}'" placeholder="" class="form-control">{!! ($businessplan) ? $businessplan->bp_future_expansion : '' !!}</textarea>
														</div>
													</div>

													<div ng-if="vf_business_plan.future_expansion.$touched && vf_business_plan.future_expansion.$invalid" class="vp-tooltip">
														<span>This field is required!</span>
													</div>

													<div ng-show="vf_business_plan.future_expansion.$valid" class="enter-btn active">
														<button type="button" class="mli-20 btn btn-primary" ng-click="jumpToNextInput()">
															OK <i class="fa fa-check" aria-hidden="true"></i>
														</button>
														<span class="press-enter">click <b>OK</b></span>
													</div>
												</div> <!-- end: INPUT BODY -->
											</div> <!-- end: INPUT TEXT NAME -->
											<div class="clear-both"></div>
										</li>
										<!-- end: future_expansion | 4 -->

									</ul>
									<!-- end: VIEW PORT 3 -->

								</div> <!-- end col12 -->

								<div class="col-md-12">
									<div class="form-group">
										<button class="btn btn-primary btn-o back-step btn-wide pull-left" data-stepval='12'>
											<i class="fa fa-arrow-circle-left"></i> Back
										</button>
										<button class="btn btn-primary btn-o next-step btn-wide pull-right" data-stepval='12'>
											Next <i class="fa fa-arrow-circle-right"></i>
										</button>
									</div>
								</div> <!-- end col12 -->

								<div class="col-sm-12">
									<div class="vp-progress-bar">
										<div class="col-sm-10 col-sm-offset-2 vp-progress">
											<div class="vp-progress-content">
												<p>@{{ percentCompleted }}% complete</p>
												<progress value="@{{ percentCompleted }}" max="100"> </progress>
											</div> <!--  -->
											<div class="create-type-form">
												<a class="create-account" target="_blank" href="javascript:void(0)">Powered by Epic Trainer</a>
												<a href="javascript:void(0)" ng-click="jumpToPrevInput()"><i class="fa fa-chevron-up" aria-hidden="true"></i></a>
												<a href="javascript:void(0)" ng-click="jumpToNextInput()"><i class="fa fa-chevron-down" aria-hidden="true"></i></a>
											</div> <!--  -->
										</div> <!-- end: COL8 || SUBMIT -->
									</div>
								</div> <!-- end col12 -->

							</div>
						</div>
						<!-- End: step-3 Services and Products  FORM WIZARD ACCORDION -->



						<!-- Start: step-4  Market Analysis Summary  FORM WIZARD ACCORDION -->
						<div id="step-4" class="content" data-group='market_analysis' ng-controller="BPWidgetFour" style="display: none;">
							<div class="row vp-form-container container-bp-step-4">

								<!-- Start: Description text Step-4 -->
								<div class="col-md-12">
									<div class="step-description">
										<p>In this section the main things to discuss are:</p>
										<ul>
											<li>Target market</li>
											<li>Target market size relating to the relevant market that can realistically be a client</li>
											<li>Current key market trends and how it benefits you</li>
											<li>SWOT Analysis</li>
										</ul>
									</div>
								</div>
								<!-- End: Description text -->

								<div class="col-sm-12">
									<!-- start: VIEW PORT 4 -->
									<ul id="viewport-4" class="vp-form-input-list">

										<!-- start: niche_market | 0 -->
										<li class="vp-item vp-form-active" data-index="0" data-sub-index="null" data-type="text" data-valid="@{{vf_business_plan.niche_market.$valid}}">
											<div class="vp-input input-text-name">
												<h3 class="vp-index pull-left">1. &nbsp;&nbsp;</h3>

												<div class="input-header">
													<h3 class="mbi-0">
														<!-- label -->
														<i class="fa fa-arrow-right" aria-hidden="true"></i>
														<span>
																<b>Niche Market</b>
																<i class="type-popover fa fa-question-circle"
																   data-content="
																		<p>There are numerous factors that play a role in the way fitness business operate and this relates to their clients, their loca-tions and their services. Below we will discuss these factors and how they are beneficial to our business model:</p>
																		<ul>
																			<li>Gender – Due to the scalability of our training we cater for both males and females and of any ability. We can assist males in becoming more active and healthier or becoming better athletes and improving performance. We can assist women in Health & Wellness, becoming more confident and finding a friendly environment that is non-intimidating to be physically active in while having “YOU” time.</li>

																			<li>Age – Our unique training and the personalized nature allows us to cater for kids through to the elderly across all as-pects of health and performance, irrespective of ability. The personalized service allows for the feeling of having a Per-sonal Trainer and the T.E.A.M Training has the benefits of comradery and support.</li>

																			<li>Local population – Due to the small number of clients required to generate sufficient income to be profitable per venue we do not rely on having tens of thousands of individuals in a catchment area of the venue as other gyms do that rely on having hundreds if not thousands of members.</li>

																			<li>Gym statistics – EPIC is aiming to capture the individuals that are intimidated by gyms, have not achieved results on their own or with a trainer, have been injured and have health problems that need addressing and the regular Jane and Joe that are just too busy or have neglected physical activity. This is the majority of the population throughout the world and are the individuals that need it the most.</li>

																			<li>Financial – Due to the shared expense of the trainer across the T.E.A.M we have eliminated the financial barriers that was ever present when deciding on a Personal Trainer. This barrier meant that mostly affluent clients where the only individuals who could employ trainers without it effecting their financial situation. We have removed this barrier and in so have expanded our target market to the general population irrespective of the high income bracket requirement that was normal criteria for trainers.</li>

																		</ul>
																	"
																   rel="popover" data-trigger="hover"  data-html="true" ></i>
															</span>
														<!-- description -->
														<div class="description">
															<p>Explain in detail your perfect target market. This needs to be both clients that you prefer to train and clients that are likely to train with you.</p>
														</div>
														<!-- end description -->
													</h3>
												</div> <!-- end: INPUT HEADER -->

												<div class="input-body mb">
													<div class="row">
														<div class="col-sm-8 ml-20">
															<textarea rows="3" id="niche_market" class="ckeditor" name="niche_market" ng-model="niche_market" ng-init="niche_market='{!! ($businessplan) ? $businessplan->bp_niche_market : '' !!}'" placeholder="" class="form-control">{!! ($businessplan) ? $businessplan->bp_niche_market : '' !!}</textarea>
														</div>
													</div>

													<div ng-if="vf_business_plan.niche_market.$touched && vf_business_plan.niche_market.$invalid" class="vp-tooltip">
														<span>This field is required!</span>
													</div>

													<div ng-show="vf_business_plan.niche_market.$valid" class="enter-btn active">
														<button type="button" class="mli-20 btn btn-primary" ng-click="jumpToNextInput()">
															OK <i class="fa fa-check" aria-hidden="true"></i>
														</button>
														<span class="press-enter">click <b>OK</b></span>
													</div>
												</div> <!-- end: INPUT BODY -->
											</div> <!-- end: INPUT TEXT NAME -->
											<div class="clear-both"></div>
										</li>
										<!-- end: niche_market | 0 -->

										<!-- start: market_size | 1 -->
										<li class="vp-item vp-form-active" data-index="1" data-sub-index="null" data-type="text" data-valid="@{{vf_business_plan.market_size.$valid}}">
											<div class="vp-input input-text-name">
												<h3 class="vp-index pull-left">2. &nbsp;&nbsp;</h3>

												<div class="input-header">
													<h3 class="mbi-0">
														<!-- label -->
														<i class="fa fa-arrow-right" aria-hidden="true"></i>
														<span>
																<b>Market Size</b>
																<i class="type-popover fa fa-question-circle"
																   data-content="
																		<p>EPICFIT studios is the choice of a large range of individuals that include all ages and genders. Our clients either live or work in the local area and we have regular clients that travel a fair distance to train with us.
																				Auckland’s North Shore has a population of more than 200,000 and is fairly split between male and female, this will initially be our main target market although we do attract clients from other areas.</p>
																		<p>As discussed above, this figure allows for a substantial amount of EPICFIT Studios to operate within this area, although not a requirement the Shore is a medium to high income area with numerous residential expansion and business development happening presently. There is a large percentage of individuals who do not enjoy or have not obtained results from gyms in the area, these are the ideal EPICFIT Studio clients and we believe that with our superior service and knowledge and fun training sessions we can and will cater for a large percentage of this market.</p>
																		<p>By capturing a small percentage of this market we will be able to cater for the planned growth of the company.</p>

																	"
																   rel="popover" data-trigger="hover"  data-html="true" ></i>
															</span>
														<!-- description -->
														<div class="description">
															<p>This needs to address the size of the target market that are directly available to you.</p>
														</div>
														<!-- end description -->
													</h3>
												</div> <!-- end: INPUT HEADER -->

												<div class="input-body mb">
													<div class="row">
														<div class="col-sm-8 ml-20">
															<textarea rows="3" id="market_size" class="ckeditor" name="market_size" ng-model="market_size" ng-init="market_size='{!! ($businessplan) ? $businessplan->bp_market_size : '' !!}'" placeholder="" class="form-control">{!! ($businessplan) ? $businessplan->bp_market_size : '' !!}</textarea>
														</div>
													</div>

													<div ng-if="vf_business_plan.market_size.$touched && vf_business_plan.market_size.$invalid" class="vp-tooltip">
														<span>This field is required!</span>
													</div>

													<div ng-show="vf_business_plan.market_size.$valid" class="enter-btn active">
														<button type="button" class="mli-20 btn btn-primary" ng-click="jumpToNextInput()">
															OK <i class="fa fa-check" aria-hidden="true"></i>
														</button>
														<span class="press-enter">click <b>OK</b></span>
													</div>
												</div> <!-- end: INPUT BODY -->
											</div> <!-- end: INPUT TEXT NAME -->
											<div class="clear-both"></div>
										</li>
										<!-- end: market_size | 1 -->

										<!-- start: current_trends | 2 -->
										<li class="vp-item vp-form-active" data-index="2" data-sub-index="null" data-type="text" data-valid="@{{vf_business_plan.current_trends.$valid}}">
											<div class="vp-input input-text-name">
												<h3 class="vp-index pull-left">3. &nbsp;&nbsp;</h3>

												<div class="input-header">
													<h3 class="mbi-0">
														<!-- label -->
														<i class="fa fa-arrow-right" aria-hidden="true"></i>
														<span>
																<b>Current Trends</b>
																<i class="type-popover fa fa-question-circle"
																   data-content="
																		<p>While researching the health & wellness industry we noted that there was a definite move from the conventional form of training at a gym or fitness club and doing the same boring routines either strength or cardiovascular to the more effective, exciting and fun T.E.A.M Training sessions that created a community, a bond.</p>
																	"
																   rel="popover" data-trigger="hover"  data-html="true" ></i>
															</span>
														<!-- description -->
														<div class="description">
															<p>Explain what the present trends are and why they are unique and will benefit your business and how.</p>
														</div>
														<!-- end description -->
													</h3>
												</div> <!-- end: INPUT HEADER -->

												<div class="input-body mb">
													<div class="row">
														<div class="col-sm-8 ml-20">
															<textarea rows="3" id="current_trends" class="ckeditor" name="current_trends" ng-model="current_trends" ng-init="current_trends='{!! ($businessplan) ? $businessplan->bp_current_trends : '' !!}'" placeholder="" class="form-control">{!! ($businessplan) ? $businessplan->bp_current_trends : '' !!}</textarea>
														</div>
													</div>

													<div ng-if="vf_business_plan.current_trends.$touched && vf_business_plan.current_trends.$invalid" class="vp-tooltip">
														<span>This field is required!</span>
													</div>

													<div ng-show="vf_business_plan.current_trends.$valid" class="enter-btn active">
														<button type="button" class="mli-20 btn btn-primary" ng-click="jumpToNextInput()">
															OK <i class="fa fa-check" aria-hidden="true"></i>
														</button>
														<span class="press-enter">click <b>OK</b></span>
													</div>
												</div> <!-- end: INPUT BODY -->
											</div> <!-- end: INPUT TEXT NAME -->
											<div class="clear-both"></div>
										</li>
										<!-- end: current_trends | 2 -->

										<!-- start: swot_analysis | 3 -->
										<li class="vp-item vp-form-active" data-index="3" data-sub-index="null" data-type="text" data-valid="@{{vf_business_plan.swot_analysis.$valid}}">
											<div class="vp-input input-text-name">
												<h3 class="vp-index pull-left">4. &nbsp;&nbsp;</h3>

												<div class="input-header">
													<h3 class="mbi-0">
														<!-- label -->
														<i class="fa fa-arrow-right" aria-hidden="true"></i>
														<span>
																<b>Current Trends</b>
																<i class="type-popover fa fa-question-circle"
																   data-content="
																		<p>By implementing a SWOT analysis into our business we are able to implement issues that need to be addressed that relate to our current strengths and weaknesses and what require to succeed. This highlights not only the opportunities but will also address the threats that may be present and that may hinder our success. By addressing these potential barriers, we limit the risk of failure.</p>
																		<strong>EPIC STRENGTHS</strong>
																		<ul>
																			<li>Knowledgeable, friendly and professional staff & trainers that are passionate about the health & wellness industry and helping not only the members but also the community become healthier and happier. Our mission is to over deliver on our already high promise for expectation to ensure clients stay motivated and achieve their goal. Each client is an indi-vidual and we treat them all that way, At EPIC YOU are unique.</li>

																			<li>Simple and effective equipment that ensures a total body training experience that caters for any individual’s ability.</li>

																			<li>Fun T.E.A.M Training atmosphere that ensure all clients feel comfortable and motivated to train at their full potential while being supported by the T.E.A.M. There are no intimidation machines or mirrors eliminating added barriers that may be present with some individuals.</li>

																			<li>Online support and client relationship manager, this allows the client to monitor progress and allows us to support them online if and when they require it. This would include online booking that includes automated messaging allowing a sim-ple and effective way of staying in touch and communicating with our members.</li>

																			<li>Understanding the market needs and providing the simple, safe and effective service required to assist individuals achieve their goals. We have researched and put together the exercises that work while limiting injury, how to motivate and keep clients motivated and accountable for their goals creating not only loyal but excited and passionate members.</li>

																			<li>At EPIC our Result based training is monitored through continuous progression sessions that keep clients motivated and working towards ever changing goals.</li>

																		</ul>
																		<strong>EPIC WEAKNESSES</strong>
																		<ul>
																			<li>Change from the norm, although we have a loyal client base or training and modalities is not industry standard yet.</li>

																			<li>Financial outlay is higher than the traditional gyms due to the personalized element that is required for our T.E.A.M Training environment that guarantees results and limits injury and imbalances while still being more affordable than personal training.</li>

																			<li>Replicating the trainers and the specific energy that has become associated with the EPIC Experience.</li>

																		</ul>
																		<strong>EPIC OPPORTUNITIES</strong>
																		<ul>
																			<li>The market is opening up to group fitness sessions and the need and desire for a more formalized and professional per-sonalized T.E.A.M Training environment is becoming more evident for a larger group of the community. Our unique net-work building and relationship building drives ensures we have room to expand as required.</li>

																			<li>EPIC believes in strength in numbers and that clients train harder and are more committed when part of a T.E.A.M. These social bonds make training together more successful and fun that training alone in a gym.</li>

																			<li>By creating the unique EPICRESULT client dashboard we are able to monitor and support clients through all aspects of their balanced healthy lifestyle changes allowing them to share their progressions with friends, family and other T.E.A.M members. Offering online services and products will be a large opportunity in the future.</li>

																			<li>Expanding market and interest in our way of training and the results we provide existing clients, while EPIC is new to the market we have built a substantial foundation that will support the growth of the brand irrespective of how quickly we expand.</li>

																		</ul>
																		<strong>EPIC THREATS</strong>
																		<ul>
																			<li>Tough economy and client priority on expenditures.</li>

																			<li>Other venues trying to replicate our services and training environment, we are sure that our services, products, pro-grams and the EPIC Experience will outclass any competition that may affect us.</li>
																		</ul>
																	"
																   rel="popover" data-trigger="hover"  data-html="true" ></i>
															</span>
														<!-- description -->
														<div class="description">
															<p>Discuss your strengths, weaknesses, opportunities and threats that are present in your industry and the future business opera-tion.</p>
														</div>
														<!-- end description -->
													</h3>
												</div> <!-- end: INPUT HEADER -->

												<div class="input-body mb">
													<div class="row">
														<div class="col-sm-8 ml-20">
															<textarea rows="3" id="swot_analysis" class="ckeditor" name="swot_analysis" ng-model="swot_analysis" ng-init="swot_analysis='{!! ($businessplan) ? $businessplan->bp_swot_analysis : '' !!}'" placeholder="" class="form-control">{!! ($businessplan) ? $businessplan->bp_swot_analysis : '' !!}</textarea>
														</div>
													</div>

													<div ng-if="vf_business_plan.swot_analysis.$touched && vf_business_plan.swot_analysis.$invalid" class="vp-tooltip">
														<span>This field is required!</span>
													</div>

													<div ng-show="vf_business_plan.swot_analysis.$valid" class="enter-btn active">
														<button type="button" class="mli-20 btn btn-primary" ng-click="jumpToNextInput()">
															OK <i class="fa fa-check" aria-hidden="true"></i>
														</button>
														<span class="press-enter">click <b>OK</b></span>
													</div>
												</div> <!-- end: INPUT BODY -->
											</div> <!-- end: INPUT TEXT NAME -->
											<div class="clear-both"></div>
										</li>
										<!-- end: swot_analysis | 3 -->

									</ul>
									<!-- start: VIEW PORT 4 -->

								</div> <!-- end col12 -->


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


								<div class="col-sm-12">
									<div class="vp-progress-bar">
										<div class="col-sm-10 col-sm-offset-2 vp-progress">
											<div class="vp-progress-content">
												<p>@{{ percentCompleted }}% complete</p>
												<progress value="@{{ percentCompleted }}" max="100"> </progress>
											</div> <!--  -->
											<div class="create-type-form">
												<a class="create-account" target="_blank" href="javascript:void(0)">Powered by Epic Trainer</a>
												<a href="javascript:void(0)" ng-click="jumpToPrevInput()"><i class="fa fa-chevron-up" aria-hidden="true"></i></a>
												<a href="javascript:void(0)" ng-click="jumpToNextInput()"><i class="fa fa-chevron-down" aria-hidden="true"></i></a>
											</div> <!--  -->
										</div> <!-- end: COL8 || SUBMIT -->
									</div>
								</div> <!-- end col12 -->

							</div> <!-- end row -->
						</div>
						<!-- End: step-4  Market Analysis Summary  FORM WIZARD ACCORDION -->

						<!-- Start: step-5 Marketing Strategy and Business Implementation FORM WIZARD ACCORDION -->
						<div id="step-5" class="content" data-group='ms_and_bi' ng-controller="BPWidgetFive" style="display: none;">
							<div class="row vp-form-container container-bp-step-5">

								<!-- Start: Description text Step-5 -->
								{{--<div class="col-md-12">--}}
									{{--<div class="step-description">--}}
										{{--<p>No any description here</p>--}}
										{{--<ul>--}}
											{{--<li>No any description here</li>--}}
										{{--</ul>--}}
									{{--</div>--}}
                                {{--</div>--}}
								<!-- End: Description text -->

								<div class="col-md-12">

									<!-- start: VIEW PORT 5 -->
									<ul id="viewport-5" class="vp-form-input-list">

										<!-- start: business_philosophy | 0 -->
										<li class="vp-item vp-form-active" data-index="0" data-sub-index="null" data-type="text" data-valid="@{{vf_business_plan.business_philosophy.$valid}}">
											<div class="vp-input input-text-name">
												<h3 class="vp-index pull-left">1. &nbsp;&nbsp;</h3>

												<div class="input-header">
													<h3 class="mbi-0">
														<!-- label -->
														<i class="fa fa-arrow-right" aria-hidden="true"></i>
														<span>
																<b>Business Philosophy</b>
																<i class="type-popover fa fa-question-circle"
																   data-content="
																		<p>At EPIC we will ensure the best training experience for clients by proving not only the most professional and dedicated train-ers but the safest and most effective training techniques available, our staff will constantly be keep up to date with new de-velopments and training techniques ensuring the client always achieves their desired results. Our venues will always have a friendly feel and never be overcrowded ensuring the feel of a personalized service at all times.</p>
																	"
																   rel="popover" data-trigger="hover"  data-html="true" ></i>
															</span>
														<!-- description -->
														<div class="description">
															<p>Discuss your views on training and the services and products you will be providing.</p>
														</div>
														<!-- end description -->
													</h3>
												</div> <!-- end: INPUT HEADER -->

												<div class="input-body mb">
													<div class="row">
														<div class="col-sm-8 ml-20">
															<textarea rows="3" id="business_philosophy" class="ckeditor" name="business_philosophy" ng-model="business_philosophy" ng-init="business_philosophy='{!! ($businessplan) ? $businessplan->bp_business_philosophy : '' !!}'" placeholder="" class="form-control">{!! ($businessplan) ? $businessplan->bp_business_philosophy : '' !!}</textarea>
														</div>
													</div>

													<div ng-if="vf_business_plan.business_philosophy.$touched && vf_business_plan.business_philosophy.$invalid" class="vp-tooltip">
														<span>This field is required!</span>
													</div>

													<div ng-show="vf_business_plan.business_philosophy.$valid" class="enter-btn active">
														<button type="button" class="mli-20 btn btn-primary" ng-click="jumpToNextInput()">
															OK <i class="fa fa-check" aria-hidden="true"></i>
														</button>
														<span class="press-enter">click <b>OK</b></span>
													</div>
												</div> <!-- end: INPUT BODY -->
											</div> <!-- end: INPUT TEXT NAME -->
											<div class="clear-both"></div>
										</li>
										<!-- end: business_philosophy | 0 -->

										<!-- start: web_presence | 1 -->
										<li class="vp-item vp-form-active" data-index="1" data-sub-index="null" data-type="text" data-valid="@{{vf_business_plan.web_presence.$valid}}">
											<div class="vp-input input-text-name">
												<h3 class="vp-index pull-left">2. &nbsp;&nbsp;</h3>

												<div class="input-header">
													<h3 class="mbi-0">
														<!-- label -->
														<i class="fa fa-arrow-right" aria-hidden="true"></i>
														<span>
																<b>Web Presence</b>
																<i class="type-popover fa fa-question-circle"
																   data-content="
																		<p>Our website <a href='http://epicfitstudios.com' target='_blank'>www.epicfitstudios.com</a> allow clients to view all current and future information such as promotions, events, schedules, programs, progression session dates and allow links to online shopping for active wear and other relevant prod-ucts and services. There will also be a link to www.epicresult .com which will be the perfect online training client manage-ment tool. The website will be promoted on all print media and promotional material.</p>
																		<p>This site will be simple to navigate and will have the same design elements as the rest of the EPIC Group and be maintained and managed internally including all the billing and scheduling.</p>
																	"
																   rel="popover" data-trigger="hover"  data-html="true" ></i>
															</span>
														<!-- description -->
														<div class="description">
															<p>How will you have an online presence and what this presence provide to your business and the clientele.</p>
														</div>
														<!-- end description -->
													</h3>
												</div> <!-- end: INPUT HEADER -->

												<div class="input-body mb">
													<div class="row">
														<div class="col-sm-8 ml-20">
															<textarea rows="3" id="web_presence" class="ckeditor" name="web_presence" ng-model="web_presence" ng-init="web_presence='{!! ($businessplan) ? $businessplan->bp_web_presence : '' !!}'" placeholder="" class="form-control">{!! ($businessplan) ? $businessplan->bp_web_presence : '' !!}</textarea>
														</div>
													</div>

													<div ng-if="vf_business_plan.web_presence.$touched && vf_business_plan.web_presence.$invalid" class="vp-tooltip">
														<span>This field is required!</span>
													</div>

													<div ng-show="vf_business_plan.web_presence.$valid" class="enter-btn active">
														<button type="button" class="mli-20 btn btn-primary" ng-click="jumpToNextInput()">
															OK <i class="fa fa-check" aria-hidden="true"></i>
														</button>
														<span class="press-enter">click <b>OK</b></span>
													</div>
												</div> <!-- end: INPUT BODY -->
											</div> <!-- end: INPUT TEXT NAME -->
											<div class="clear-both"></div>
										</li>
										<!-- end: web_presence | 1 -->

										<!-- start: marketing_strategy | 2 -->
										<li class="vp-item vp-form-active" data-index="2" data-sub-index="null" data-type="text" data-valid="@{{vf_business_plan.marketing_strategy.$valid}}">
											<div class="vp-input input-text-name">
												<h3 class="vp-index pull-left">3. &nbsp;&nbsp;</h3>

												<div class="input-header">
													<h3 class="mbi-0">
														<!-- label -->
														<i class="fa fa-arrow-right" aria-hidden="true"></i>
														<span>
																<b>Marketing Strategy</b>
																<i class="type-popover fa fa-question-circle"
																   data-content="
																		<p>Our marketing strategy requires that we include but are not limited to the following:</p>
																		<ul>
																			<li>Direct marketing which would require that we canvass local businesses and individuals with a door to door campaign</li>

																			<li>Marketing to current clients to confirm a sure referral base from these members</li>

																			<li>Facebook marketing to existing clients and creating awareness</li>

																			<li>Grow community awareness through complimentary exercise programs and EPIC Kids through schools</li>

																			<li>Sponsorship in the form of training for local athletes</li>

																			<li>Internet support which includes newsletters and interesting relevant blogs</li>

																			<li>Give back to the community with charity events</li>
																		</ul>
																		<strong>PROMOTIONS AND MARKETING MEDIA</strong>
																		<p>EPICS main promotion strategy consists of building a strong and loyal professional network that consists of an array of local businesses that may work well within our brand offering services and products to the same clientele.</p>
																		<p>Other strategies include word of mouth from satisfied existing clients, direct marketing and email campaigns linked to Face-book.</p>
																		<p>We will approach local schools to offer them a programs that are specifically beneficial for the youth by offering simple, fun and functional training programs focusing on our EPIC Kids Movement Matters campaign.</p>
																		<p>If required, we may approach the professional services such as the Fire Services and the Police buy this is not a sure mar-keting option at this time.</p>
																		<p>In addition to the above our website will be a major factor relating to promotions and will secure our presence online with a simple data capture form that will allow us to contact these leads.</p>
																		<strong>POSITION WITHIN THE MARKETPLACE</strong>
																		<p>EPIC is the ideal place for clients that desire results ad that are goal orientated but lack the motivation to consistently train on their own. We cater for clients that are not happy with their existing results and training routines. EPIC provides a wel-come change to this environment by providing a fun, safe and effective EPIC Training Experience.</p>
																	"
																   rel="popover" data-trigger="hover"  data-html="true" ></i>
															</span>
														<!-- description -->
														<div class="description">
															<p>How will you grow your business and what form of marketing will you be implementing to ensure substantial growth?</p>
														</div>
														<!-- end description -->
													</h3>
												</div> <!-- end: INPUT HEADER -->

												<div class="input-body mb">
													<div class="row">
														<div class="col-sm-8 ml-20">
															<textarea rows="3" id="marketing_strategy" class="ckeditor" name="marketing_strategy" ng-model="marketing_strategy" ng-init="marketing_strategy='{!! ($businessplan) ? $businessplan->bp_marketing_strategy : '' !!}'" placeholder="" class="form-control">{!! ($businessplan) ? $businessplan->bp_marketing_strategy : '' !!}</textarea>
														</div>
													</div>

													<div ng-if="vf_business_plan.marketing_strategy.$touched && vf_business_plan.marketing_strategy.$invalid" class="vp-tooltip">
														<span>This field is required!</span>
													</div>

													<div ng-show="vf_business_plan.marketing_strategy.$valid" class="enter-btn active">
														<button type="button" class="mli-20 btn btn-primary" ng-click="jumpToNextInput()">
															OK <i class="fa fa-check" aria-hidden="true"></i>
														</button>
														<span class="press-enter">click <b>OK</b></span>
													</div>
												</div> <!-- end: INPUT BODY -->
											</div> <!-- end: INPUT TEXT NAME -->
											<div class="clear-both"></div>
										</li>
										<!-- end: marketing_strategy | 2 -->

										<!-- start: sales_strategy | 3 -->
										<li class="vp-item vp-form-active" data-index="3" data-sub-index="null" data-type="text" data-valid="@{{vf_business_plan.sales_strategy.$valid}}">
											<div class="vp-input input-text-name">
												<h3 class="vp-index pull-left">4. &nbsp;&nbsp;</h3>

												<div class="input-header">
													<h3 class="mbi-0">
														<!-- label -->
														<i class="fa fa-arrow-right" aria-hidden="true"></i>
														<span>
																<b>Sales Strategy</b>
																<i class="type-popover fa fa-question-circle"
																   data-content="
																		<p>Our offer to existing customers is that they invite friends and family to try the EPIC Experience at no cost for a 2-week period this allows them to experience the atmosphere while benefitting from a personal consultation and introduction to move-ment and partaking in a T.E.A.M Training session showcasing the effectiveness and fun factor of EPIC. These client swill be linked to our client support site which once again will showcase our professionalism and support structure.</p>
																		<p>This process allows us the opportunity to generate a constant flow of leads while allowing new clients to opportunity to try before you buy with no obligation and no pressure selling from EPIC Staff, our process is a soft sell and the EPIC Experience sells itself. This process is set in our business modal and will always be offered to clients and their referrals.</p>
																		<p>Clients who refer individuals who sign up get rewarded through our EPIC Credit modal which can be used for any EPIC prod-ucts and services.</p>
																	"
																   rel="popover" data-trigger="hover"  data-html="true" ></i>
															</span>
														<!-- description -->
														<div class="description">
															<p>How will you be introducing new leads and clients into the business, this is critical for success of any business.</p>
														</div>
														<!-- end description -->
													</h3>
												</div> <!-- end: INPUT HEADER -->

												<div class="input-body mb">
													<div class="row">
														<div class="col-sm-8 ml-20">
															<textarea rows="3" id="sales_strategy" class="ckeditor" name="sales_strategy" ng-model="sales_strategy" ng-init="sales_strategy='{!! ($businessplan) ? $businessplan->bp_sales_strategy : '' !!}'" placeholder="" class="form-control">{!! ($businessplan) ? $businessplan->bp_sales_strategy : '' !!}</textarea>
														</div>
													</div>

													<div ng-if="vf_business_plan.sales_strategy.$touched && vf_business_plan.sales_strategy.$invalid" class="vp-tooltip">
														<span>This field is required!</span>
													</div>

													<div ng-show="vf_business_plan.sales_strategy.$valid" class="enter-btn active">
														<button type="button" class="mli-20 btn btn-primary" ng-click="jumpToNextInput()">
															OK <i class="fa fa-check" aria-hidden="true"></i>
														</button>
														<span class="press-enter">click <b>OK</b></span>
													</div>
												</div> <!-- end: INPUT BODY -->
											</div> <!-- end: INPUT TEXT NAME -->
											<div class="clear-both"></div>
										</li>
										<!-- end: sales_strategy | 3 -->

										<!-- start: strategic_alliances | 4 -->
										<li class="vp-item vp-form-active" data-index="4" data-sub-index="null" data-type="text" data-valid="@{{vf_business_plan.strategic_alliances.$valid}}">
											<div class="vp-input input-text-name">
												<h3 class="vp-index pull-left">5. &nbsp;&nbsp;</h3>

												<div class="input-header">
													<h3 class="mbi-0">
														<!-- label -->
														<i class="fa fa-arrow-right" aria-hidden="true"></i>
														<span>
															<b>Strategic Alliances</b>
															<i class="type-popover fa fa-question-circle"
															   data-content="
																	<p>EPIC will build relationships and affiliations with a professional network that promote health and wellbeing through their client base by understanding our products and services. These affiliates will have the opportunity to train at a reduced rate as this will create a clear understanding of the services we offer when discussing it with their clients. EPIC will open numer-ous venues which will cater for a large clientele and we expect huge growth through this simple model that offers the same service across numerous venues.</p>
																"
															   rel="popover" data-trigger="hover"  data-html="true" ></i>
														</span>
														<!-- description -->
														<div class="description">
															<p>Who will you partnering with and how will this benefit your business, the growth and overall feel of your business, what bene-fits will be available to the affiliates.</p>
														</div>
														<!-- end description -->
													</h3>
												</div> <!-- end: INPUT HEADER -->

												<div class="input-body mb">
													<div class="row">
														<div class="col-sm-8 ml-20">
															<textarea rows="3" id="strategic_alliances" class="ckeditor" name="strategic_alliances" ng-model="strategic_alliances" ng-init="strategic_alliances='{!! ($businessplan) ? $businessplan->bp_strategic_alliances : '' !!}'" placeholder="" class="form-control">{!! ($businessplan) ? $businessplan->bp_strategic_alliances : '' !!}</textarea>
														</div>
													</div>

													<div ng-if="vf_business_plan.strategic_alliances.$touched && vf_business_plan.strategic_alliances.$invalid" class="vp-tooltip">
														<span>This field is required!</span>
													</div>

													<div ng-show="vf_business_plan.strategic_alliances.$valid" class="enter-btn active">
														<button type="button" class="mli-20 btn btn-primary" ng-click="jumpToNextInput()">
															OK <i class="fa fa-check" aria-hidden="true"></i>
														</button>
														<span class="press-enter">click <b>OK</b></span>
													</div>
												</div> <!-- end: INPUT BODY -->
											</div> <!-- end: INPUT TEXT NAME -->
											<div class="clear-both"></div>
										</li>
										<!-- end: strategic_alliances | 4 -->

										<!-- start: company_objectives_and_vision | 5 -->
										<li class="vp-item vp-form-active" data-index="5" data-sub-index="null" data-type="text" data-valid="@{{vf_business_plan.company_objectives_and_vision.$valid}}">
											<div class="vp-input input-text-name">
												<h3 class="vp-index pull-left">6. &nbsp;&nbsp;</h3>

												<div class="input-header">
													<h3 class="mbi-0">
														<!-- label -->
														<i class="fa fa-arrow-right" aria-hidden="true"></i>
														<span>
															<b>Company Objectives And Vision</b>
															<i class="type-popover fa fa-question-circle"
															   data-content="
																	<p>AT EPIC the staff focus not only on marketing and sales but on providing EPIC Training sessions while continually learning and developing their skills. All staff and trainers will be trainer and only once they have demonstrated the ability to be EPIC Trainers will they be able to provide EPIC Training within a venue. We aim to continually improve both EPIC as a company and EPIC staff member knowledge relating to health & wellness and general nutritional advice.</p>
																	<p>Our main goal is to create a friendly environment where the general population feel comfortable while being trained in truly functional movements that mimic everyday life and everyday movements ensuring we make them fitter, faster and stronger while limiting injury and removing the common barriers associated with general fitness and the current venues and services.</p>
																	<p>Our monthly target is 10 new clients a month which will ensure that we become profitable within the first 12 months of busi-ness which would result in the venue running at full capacity within the first two years with a total of 200 members.</p>
																"
															   rel="popover" data-trigger="hover"  data-html="true" ></i>
														</span>
														<!-- description -->
														<div class="description">
															<p>What are your objectives and your company vison and how are you planning at achieving these objectives?</p>
														</div>
														<!-- end description -->
													</h3>
												</div> <!-- end: INPUT HEADER -->

												<div class="input-body mb">
													<div class="row">
														<div class="col-sm-8 ml-20">
															<textarea rows="3" id="company_objectives_and_vision" class="ckeditor" name="company_objectives_and_vision" ng-model="company_objectives_and_vision" ng-init="company_objectives_and_vision='{!! ($businessplan) ? $businessplan->bp_company_objectives_and_vision : '' !!}'" placeholder="" class="form-control">{!! ($businessplan) ? $businessplan->bp_company_objectives_and_vision : '' !!}</textarea>
														</div>
													</div>

													<div ng-if="vf_business_plan.company_objectives_and_vision.$touched && vf_business_plan.company_objectives_and_vision.$invalid" class="vp-tooltip">
														<span>This field is required!</span>
													</div>

													<div ng-show="vf_business_plan.company_objectives_and_vision.$valid" class="enter-btn active">
														<button type="button" class="mli-20 btn btn-primary" ng-click="jumpToNextInput()">
															OK <i class="fa fa-check" aria-hidden="true"></i>
														</button>
														<span class="press-enter">click <b>OK</b></span>
													</div>
												</div> <!-- end: INPUT BODY -->
											</div> <!-- end: INPUT TEXT NAME -->
											<div class="clear-both"></div>
										</li>
										<!-- end: company_objectives_and_vision | 5 -->

										<!-- start: exit_strategy | 6 -->
										<li class="vp-item vp-form-active" data-index="6" data-sub-index="null" data-type="text" data-valid="@{{vf_business_plan.exit_strategy.$valid}}">
											<div class="vp-input input-text-name">
												<h3 class="vp-index pull-left">7. &nbsp;&nbsp;</h3>

												<div class="input-header">
													<h3 class="mbi-0">
														<!-- label -->
														<i class="fa fa-arrow-right" aria-hidden="true"></i>
														<span>
															<b>Exit Strategy</b>
															<i class="type-popover fa fa-question-circle"
															   data-content="
																	<p>EPIC was established to create a service that is lacking in the marketplace for both the general population as well as the fitness trainers in the industry who have a passion for both health & wellness and the ability to succeed in a market with a high turnover rate. EPIC Alpha 1 will provide a sufficient income to settle all liabilities within the first two years while allow-ing the owners, investors and trainers to earn above average returns on investment both capital and time.</p>
																	<p>EPIC FITNESS LIMITED is hoping to manage a large number of these venues and to offer them as owner operated venues within an agreed timeframe allowing the venues to be independently owned while we receive revenue for support and roy-alties for the use of our processes and systems.</p>
																"
															   rel="popover" data-trigger="hover"  data-html="true" ></i>
														</span>
														<!-- description -->
														<div class="description">
															<p>What is your exit strategy, how do you anticipate your business growth and how do you plan to sell the business if and when required?</p>
														</div>
														<!-- end description -->
													</h3>
												</div> <!-- end: INPUT HEADER -->

												<div class="input-body mb">
													<div class="row">
														<div class="col-sm-8 ml-20">
															<textarea rows="3" id="exit_strategy" class="ckeditor" name="exit_strategy" ng-model="exit_strategy" ng-init="exit_strategy='{!! ($businessplan) ? $businessplan->bp_exit_strategy : '' !!}'" placeholder="" class="form-control">{!! ($businessplan) ? $businessplan->bp_exit_strategy : '' !!}</textarea>
														</div>
													</div>

													<div ng-if="vf_business_plan.exit_strategy.$touched && vf_business_plan.exit_strategy.$invalid" class="vp-tooltip">
														<span>This field is required!</span>
													</div>

													<div ng-show="vf_business_plan.exit_strategy.$valid" class="enter-btn active">
														<button type="button" class="mli-20 btn btn-primary" ng-click="jumpToNextInput()">
															OK <i class="fa fa-check" aria-hidden="true"></i>
														</button>
														<span class="press-enter">click <b>OK</b></span>
													</div>
												</div> <!-- end: INPUT BODY -->
											</div> <!-- end: INPUT TEXT NAME -->
											<div class="clear-both"></div>
										</li>
										<!-- end: exit_strategy | 6 -->


									</ul>
									<!-- end: VIEW PORT 5 -->

								</div> <!-- end col12 -->

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

								<div class="col-sm-12">
									<div class="vp-progress-bar">
										<div class="col-sm-10 col-sm-offset-2 vp-progress">
											<div class="vp-progress-content">
												<p>@{{ percentCompleted }}% complete</p>
												<progress value="@{{ percentCompleted }}" max="100"> </progress>
											</div> <!--  -->
											<div class="create-type-form">
												<a class="create-account" target="_blank" href="javascript:void(0)">Powered by Epic Trainer</a>
												<a href="javascript:void(0)" ng-click="jumpToPrevInput()"><i class="fa fa-chevron-up" aria-hidden="true"></i></a>
												<a href="javascript:void(0)" ng-click="jumpToNextInput()"><i class="fa fa-chevron-down" aria-hidden="true"></i></a>
											</div> <!--  -->
										</div> <!-- end: COL8 || SUBMIT -->
									</div>
								</div> <!-- end col12 -->

							</div>
						</div>
						<!-- End: step-5 Marketing Strategy and Business Implementation FORM WIZARD ACCORDION -->


						<!-- Start: step-6  Financial Plan   FORM WIZARD ACCORDION -->
						<div id="step-6" class="content" data-group='financial_plan_group' ng-controller="BPWidgetSix" style="display: none;" >
							<div class="row vp-form-container container-bp-step-6">

								<!-- Start: Description text Step-1 -->
								{{--<div class="col-md-12">--}}
									{{--<div class="step-description">--}}
										{{--<p>No any descriptions here.</p>--}}
										{{--<ul>--}}
											{{--<li>No any descriptions here.</li>--}}
										{{--</ul>--}}
									{{--</div>--}}
                                {{--</div>--}}
								<!-- End: Description text -->

								<div class="col-md-12">

									<!-- start: VIEW PORT 6 -->
									<ul id="viewport-6" class="vp-form-input-list">

										<!-- start: startup_req_and_alloc_capital | 0 -->
										<li class="vp-item vp-form-active" data-index="0" data-sub-index="null" data-type="text" data-valid="@{{vf_business_plan.startup_req_and_alloc_capital.$valid}}">
											<div class="vp-input input-text-name">
												<h3 class="vp-index pull-left">1. &nbsp;&nbsp;</h3>

												<div class="input-header">
													<h3 class="mbi-0">
														<!-- label -->
														<i class="fa fa-arrow-right" aria-hidden="true"></i>
														<span>
																<b>Startup Requirements And Allocation Of Capital</b>
																<i class="type-popover fa fa-question-circle"
																   data-content="
																		<p>EPIC Alpha 1 would require an initial capital investment of $80000 which will allow us to operate for the first year without the added stresses of cash flow. This investment will allow for all equipment purchases to be completed before we open al-lowing for a fully operational venue from day one. This injection will be provided by a business partner that will receive 30% equity in EPIC Alpha 1.</p>
																		<p>This investment will be used to secure a venue, do any cosmetic works required, purchase equipment, initial marketing mate-rials with the balance being used as operating expenses while the business becomes profitable.</p>
																	"
																   rel="popover" data-trigger="hover"  data-html="true" ></i>
															</span>
														<!-- description -->
														<div class="description">
															<p>Explain how the initial capital will be distributed through the company setup and future operational expenses.</p>
														</div>
														<!-- end description -->
													</h3>
												</div> <!-- end: INPUT HEADER -->

												<div class="input-body mb">
													<div class="row">
														<div class="col-sm-8 ml-20">
															<textarea rows="3" id="startup_req_and_alloc_capital" class="ckeditor" name="startup_req_and_alloc_capital" ng-model="startup_req_and_alloc_capital" ng-init="startup_req_and_alloc_capital='{!! ($businessplan) ? $businessplan->bp_startup_req_and_alloc_capital : '' !!}'" placeholder="" class="form-control">{!! ($businessplan) ? $businessplan->bp_startup_req_and_alloc_capital : '' !!}</textarea>
														</div>
													</div>

													<div ng-if="vf_business_plan.startup_req_and_alloc_capital.$touched && vf_business_plan.startup_req_and_alloc_capital.$invalid" class="vp-tooltip">
														<span>This field is required!</span>
													</div>

													<div ng-show="vf_business_plan.startup_req_and_alloc_capital.$valid" class="enter-btn active">
														<button type="button" class="mli-20 btn btn-primary" ng-click="jumpToNextInput()">
															OK <i class="fa fa-check" aria-hidden="true"></i>
														</button>
														<span class="press-enter">click <b>OK</b></span>
													</div>
												</div> <!-- end: INPUT BODY -->
											</div> <!-- end: INPUT TEXT NAME -->
											<div class="clear-both"></div>
										</li>
										<!-- end: startup_req_and_alloc_capital | 0 -->

										<!-- start: cash_flow_proj_and_bal_sheets | 1 -->
										<li class="vp-item vp-form-active" data-index="1" data-sub-index="null" data-type="text" data-valid="@{{vf_business_plan.cash_flow_proj_and_bal_sheets.$valid}}">
											<div class="vp-input input-text-name">
												<h3 class="vp-index pull-left">2. &nbsp;&nbsp;</h3>

												<div class="input-header">
													<h3 class="mbi-0">
														<!-- label -->
														<i class="fa fa-arrow-right" aria-hidden="true"></i>
														<span>
																<b>Cash Flow Projection And Balance Sheets</b>
																<i class="type-popover fa fa-question-circle"
																   data-content="
																		<p>EPIC Alpha 1 will officially commence operation in mid-2015 and the first year revenue will be projected to be $150,000. This revenue will increase to $250,00 in the second year with revenues growing substantial once venues are opened at a similar turnover. Due to the different variations and sizes of venues turnover will vary per venue.</p>
																		<p>The first year will be break-even but the year two net profit will be approximately $100-125,000. Further details will be esti-mated and recorded in the finance section. The breakeven point for EPIC Alpha 1 is once it has acquired 75 members with an estimated average spend of $170 per month. EPIC Alpha 1 will be profitable in the second year of trading with all the liabili-ties, startup capital and investors funds will be settled by the end of the second full financial year.</p>
																		<p>The business will have the primary assets which will consist of both equipment and cash.</p>
																	"
																   rel="popover" data-trigger="hover"  data-html="true" ></i>
															</span>
														<!-- description -->
														<div class="description">
															<p>Provide detailed forecasts to explain how you plan to be profitable and what your requirements are to make this happen within a set period of time.</p>
														</div>
														<!-- end description -->
													</h3>
												</div> <!-- end: INPUT HEADER -->

												<div class="input-body mb">
													<div class="row">
														<div class="col-sm-8 ml-20">
															<textarea rows="3" id="cash_flow_proj_and_bal_sheets" class="ckeditor" name="cash_flow_proj_and_bal_sheets" ng-model="cash_flow_proj_and_bal_sheets" ng-init="cash_flow_proj_and_bal_sheets='{!! ($businessplan) ? $businessplan->bp_cash_flow_proj_and_bal_sheets : '' !!}'" placeholder="" class="form-control">{!! ($businessplan) ? $businessplan->bp_cash_flow_proj_and_bal_sheets : '' !!}</textarea>
														</div>
													</div>

													<div ng-if="vf_business_plan.cash_flow_proj_and_bal_sheets.$touched && vf_business_plan.cash_flow_proj_and_bal_sheets.$invalid" class="vp-tooltip">
														<span>This field is required!</span>
													</div>

													<div ng-show="vf_business_plan.cash_flow_proj_and_bal_sheets.$valid" class="enter-btn active">
														<button type="button" class="mli-20 btn btn-primary" ng-click="jumpToNextInput()">
															OK <i class="fa fa-check" aria-hidden="true"></i>
														</button>
														<span class="press-enter">click <b>OK</b></span>
													</div>
												</div> <!-- end: INPUT BODY -->
											</div> <!-- end: INPUT TEXT NAME -->
											<div class="clear-both"></div>
										</li>
										<!-- end: cash_flow_proj_and_bal_sheets | 1 -->

										<!-- start: assumptions | 2 -->
										<li class="vp-item vp-form-active" data-index="2" data-sub-index="null" data-type="text" data-valid="@{{vf_business_plan.assumptions.$valid}}">
											<div class="vp-input input-text-name">
												<h3 class="vp-index pull-left">3. &nbsp;&nbsp;</h3>

												<div class="input-header">
													<h3 class="mbi-0">
														<!-- label -->
														<i class="fa fa-arrow-right" aria-hidden="true"></i>
														<span>
																<b>Assumptions</b>
																<i class="type-popover fa fa-question-circle"
																   data-content="
																		<p>The projections provided are based purely on the assumption that the existing fitness trends and business model will contin-ue with its growth and that the competition remains as per the existing marketplace and that they do not fully change their business model to cater for our client base providing the same service at a better price point</p>
																	"
																   rel="popover" data-trigger="hover"  data-html="true" ></i>
															</span>
														<!-- description -->
														<div class="description">
															<p>What needs to happen to ensure that this happens, this is the requirements for success to ensure that the business succeeds under certain conditions.</p>
														</div>
														<!-- end description -->
													</h3>
												</div> <!-- end: INPUT HEADER -->

												<div class="input-body mb">
													<div class="row">
														<div class="col-sm-8 ml-20">
															<textarea rows="3" id="assumptions" class="ckeditor" name="assumptions" ng-model="assumptions" ng-init="assumptions='{!! ($businessplan) ? $businessplan->bp_assumptions : '' !!}'" placeholder="" class="form-control">{!! ($businessplan) ? $businessplan->bp_assumptions : '' !!}</textarea>
														</div>
													</div>

													<div ng-if="vf_business_plan.assumptions.$touched && vf_business_plan.assumptions.$invalid" class="vp-tooltip">
														<span>This field is required!</span>
													</div>

													<div ng-show="vf_business_plan.assumptions.$valid" class="enter-btn active">
														<button type="button" class="mli-20 btn btn-primary" ng-click="jumpToNextInput()">
															OK <i class="fa fa-check" aria-hidden="true"></i>
														</button>
														<span class="press-enter">click <b>OK</b></span>
													</div>
												</div> <!-- end: INPUT BODY -->
											</div> <!-- end: INPUT TEXT NAME -->
											<div class="clear-both"></div>
										</li>
										<!-- end: assumptions | 2 -->

									</ul>
									<!-- end: VIEW PORT 6 -->

								</div> <!-- end col12 -->

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

								<div class="col-sm-12">
									<div class="vp-progress-bar">
										<div class="col-sm-10 col-sm-offset-2 vp-progress">
											<div class="vp-progress-content">
												<p>@{{ percentCompleted }}% complete</p>
												<progress value="@{{ percentCompleted }}" max="100"> </progress>
											</div> <!--  -->
											<div class="create-type-form">
												<a class="create-account" target="_blank" href="javascript:void(0)">Powered by Epic Trainer</a>
												<a href="javascript:void(0)" ng-click="jumpToPrevInput()"><i class="fa fa-chevron-up" aria-hidden="true"></i></a>
												<a href="javascript:void(0)" ng-click="jumpToNextInput()"><i class="fa fa-chevron-down" aria-hidden="true"></i></a>
											</div> <!--  -->
										</div> <!-- end: COL8 || SUBMIT -->
									</div>
								</div> <!-- end col12 -->

							</div>
						</div>
						<!-- End: step-6  Financial Plan   FORM WIZARD ACCORDION -->
					</div>
		    	</div>
			</form>
			<!-- end: WIZARD FORM -->
		</div>
	</div>
</div>





<div class="modal fade model-rounded" id="tooltipModel" tabindex="-1" role="dialog" aria-labelledby="Tooltip Modal" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">

			<div class="modal-body" style="padding-bottom: 60px; background: #fff;"></div>
			<button type="button" class="btn btn-primary pull-right" style="right: 15px; bottom: 50px" data-dismiss="modal" >Ok</button>

		</div>
	</div>
</div>

