<div class="page-header">
    <h1>Epic process</h1>
</div>

<!-- start: acc1 -->
<div class="panel panel-white">
    <!-- start: PANEL HEADING -->
    <div class="panel-heading">
        <h5 class="panel-title">
            <span class="icon-group-left">
                <i class="fa fa-ellipsis-v"></i>
            </span> 
            Assess & Progress
            <span class="icon-group-right">
                <a class="btn btn-xs pull-right" href="#" data-toggle="modal" data-target="#configModal">
                    <i class="fa fa-wrench"></i>
                </a>
                <a class="btn btn-xs pull-right panel-collapse" id="epic-process-panel" href="#" data-panel-group="epic-process">
                    <i class="fa fa-chevron-down"></i>
                </a>
            </span>
        </h5>
    </div>
    <!-- end: PANEL HEADING -->
    <!-- start: PANEL BODY -->
    <div class="panel-body">

        <form action="#" role="form" class="smart-wizard" id="form" data-form-mode="view" data-parq-mode="summary"><!--form-horizontal-->
            {!! Form::token() !!}
            <input id="client_id" type="hidden" name="client_id" value="{{$parq->client_id}}">
          
            <input type="hidden" name="step_status" value="{{$parq->parq1}}, {{$parq->parq2}}, {{$parq->parq3}}, {{$parq->parq4}}, {{$parq->parq5}}">

            <input id="parqId" type="hidden" name="parqId" value="{{$parq->id}}">
            <input type="hidden" name="waiverComp" value="{{$parq->waiverTerms}}">
            <map name="Map" id="Map"></map>
            <div id="wizard" class="swMain parqForm">
                <ul>
                    <li>
                        <a href="#step-1">
                            <div class="stepNumber">
                                1
                            </div>
                            <span class="stepDesc"><small>Personal Details</small></span>
                        </a>
                    </li>
                    <li>
                        <a href="#step-2">
                            <div class="stepNumber">
                                2
                            </div>
                            <span class="stepDesc"><small>Exercise Preference</small></span>
                        </a>
                    </li>
                    <li>
                        <a href="#step-3">
                            <div class="stepNumber">
                                3
                            </div>
                            <span class="stepDesc"><small>Injury Profile &amp; Family History</small></span>
                        </a>
                    </li>
                    <li>
                        <a href="#step-4">
                            <div class="stepNumber">
                                4
                            </div>
                            <span class="stepDesc"><small>Pre Activity Readiness Questionnaire</small></span>
                        </a>
                    </li>
                    <li>
                        <a href="#step-5">
                            <div class="stepNumber">
                                5
                            </div>
                            <span class="stepDesc"><small>Goals & Motivation</small></span>
                        </a>
                    </li>
                </ul>

                <!-- start: WIZARD STEP 1 -->
                <div id="step-1">
                    <div class="row step-show">
                        @include('parq.view.parq-step1')
                        <div class="col-sm-6"></div>

                            <div class="col-sm-6">
                                <button class="btn btn-primary next-step btn-wide pull-right">
                                Next <i class="fa fa-arrow-circle-right"></i>
                                </button>

                                @if(hasPermission('edit-parq'))
                                @if($parq->waiverTerms == 0 ||  isTypeSuperUser())
                                    <button class="btn btn-primary btn-o togglebtn btn-wide pull-right margin-right-15">
                                    <i class="fa fa-edit"></i> Edit 
                                    </button>
                                    @endif
                                @endif
                            </div>
                    </div>
                    @if(hasPermission('edit-parq'))
                        <div class="row step-create hidden">
                            @include('parq-step1')
                            <div class="col-sm-6"></div>
                                <div class="col-sm-6">
                                    <button class="btn btn-primary submit-step btn-wide pull-right " data-step="1"> Save </button>
                                    <button class="btn btn-primary togglebtn btn-o btn-wide pull-right margin-right-15"> Cancel
                                    </button>
                                </div>
                        </div>
                    @endif
                </div>
                <!-- end: WIZARD STEP 1 -->
                
                <!-- start: WIZARD STEP 2 -->
                <div id="step-2">
                    <div class="sucMes hidden">
                        {!! displayAlert()!!}
                    </div>
                    <div class="row step-show">
                        <div class="row">
                            @include('parq.view.parq-step2')
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <button class="btn btn-primary back-step btn-wide pull-left">
                                    <i class="fa fa-arrow-circle-left"></i> Back
                                </button>
                            </div>
                            <div class="col-sm-6">
                                <button class="btn btn-primary next-step btn-wide pull-right">
                                    Next <i class="fa fa-arrow-circle-right"></i>
                                </button>
                                @if(hasPermission('edit-parq'))
                                 @if($parq->waiverTerms == 0 ||  isTypeSuperUser())
                                    <button class="btn btn-primary btn-o togglebtn btn-wide pull-right margin-right-15">
                                        <i class="fa fa-edit"></i> Edit 
                                    </button>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                    @if(hasPermission('edit-parq'))
                        <div class="row step-create hidden">
                            @include('parq-step2')
                            <div class="col-sm-6"></div>
                                <div class="col-sm-6">
                                    <button class="btn btn-primary submit-step btn-wide pull-right margin-right-15" data-step="2"> Save </button>
                                    <button class="btn btn-primary togglebtn btn-o btn-wide pull-right margin-right-15"> Cancel
                                    </button>   
                                </div>
                        </div>  
                    @endif
                </div>
                <!-- end: WIZARD STEP 2 -->
                    
                <!-- start: WIZARD STEP 3 -->
                <div id="step-3">
                    <div class="row step-show">
                        @include('parq.view.parq-step3')
                        <div class="col-sm-6">
                            <button class="btn btn-primary back-step btn-wide pull-left">
                                <i class="fa fa-arrow-circle-left"></i> Back
                            </button>
                        </div>
                        <div class="col-sm-6">
                            <button class="btn btn-primary next-step btn-wide pull-right">
                                Next <i class="fa fa-arrow-circle-right"></i>
                            </button>
                            @if(hasPermission('edit-parq'))
                                 @if($parq->waiverTerms == 0 ||  isTypeSuperUser())
                                <button class="btn btn-primary btn-o togglebtn btn-wide pull-right margin-right-15">
                                    <i class="fa fa-edit"></i> Edit 
                                </button>
                                @endif
                            @endif
                        </div>
                    </div>
                    @if(hasPermission('edit-parq'))
                        <div class="row step-create hidden">
                            @include('parq-step3')
                            <div class="col-sm-6"></div>
                                <div class="col-sm-6">
                                    <button class="btn btn-primary submit-step btn-wide pull-right"  data-step="3"> Save </button>
                                    <button class="btn btn-primary togglebtn btn-o btn-wide pull-right margin-right-15"> Cancel
                                    </button>   
                                </div>
                        </div>
                    @endif
                </div>
                <!-- end: WIZARD STEP 3 -->
                        
                <!-- start: WIZARD STEP 4 -->
                <div id="step-4">
                    <div class="sucMes hidden">
                        {!! displayAlert()!!}
                    </div>
                    <div class="row step-show">
                        @include('parq.view.parq-step4')
                        <div class="col-sm-6">
                            <button class="btn btn-primary back-step btn-wide pull-left">
                                <i class="fa fa-arrow-circle-left"></i> Back
                            </button>
                        </div>
                        <div class="col-sm-6">
                            <button class="btn btn-primary next-step btn-wide pull-right">
                                Next <i class="fa fa-arrow-circle-right"></i>
                            </button>
                            @if(hasPermission('edit-parq'))
                            @if($parq->waiverTerms == 0 ||  isTypeSuperUser())
                                <button class="btn btn-primary btn-o togglebtn btn-wide pull-right margin-right-15">
                                    <i class="fa fa-edit"></i> Edit 
                                </button>
                                @endif
                            @endif
                        </div>
                    </div>
                    @if(hasPermission('edit-parq'))
                        <div class="row step-create hidden"> 
                            @include('parq-step4')
                            <div class="col-sm-6"></div>
                                <div class="col-sm-6">
                                    <button class="btn btn-primary submit-step btn-wide pull-right " data-step="4"> Save </button>
                                    <button class="btn btn-primary togglebtn btn-o btn-wide pull-right margin-right-15"> Cancel
                                    </button>   
                                </div>
                        </div>
                    @endif
                </div>
                <!-- end: WIZARD STEP 4 -->
                        
                <!-- start: WIZARD STEP 5 -->
                <div id="step-5">
                    <div class="row step-show">
                        <div class="row">
                            @include('parq.view.parq-step5')
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <button class="btn btn-primary back-step btn-wide pull-left">
                                    <i class="fa fa-arrow-circle-left"></i> Back
                                </button>
                            </div>
                            <div class="col-sm-6">
                                <button class="btn btn-primary btn-wide pull-right" id="finish-parq-summary">
                                    Finish <i class="fa fa-arrow-circle-right"></i>
                                </button>
                                @if(hasPermission('edit-parq'))
                                 @if($parq->waiverTerms == 0 ||  isTypeSuperUser())
                                    <button class="btn btn-primary btn-o togglebtn btn-wide pull-right margin-right-15">
                                        <i class="fa fa-edit"></i> Edit 
                                    </button>
                                    @endif
                                @endif
                            </div>
                        </div>  
                    </div>
                    @if(hasPermission('edit-parq'))
                        <div class="row step-create hidden">
                            @include('parq-step5') 
                            <div class="col-sm-6"></div>
                                <div class="col-sm-6">
                                  <!--   <button class="btn btn-primary btn-wide pull-right" id="finish-parq"> Finish <i class="fa fa-arrow-circle-right"></i> </button> -->

                                    <button class="btn btn-primary btn-wide pull-right" id="finish-parq"> Save </button>  
                                    <button class="btn btn-primary togglebtn btn-o btn-wide pull-right margin-right-15"> Cancel
                                    </button>
                                </div>   
                        </div>
                    @endif
                </div>
                <!-- end: WIZARD STEP 5 -->
                        
                <!--<div class="clear-widget"></div>-->
            </div>
        </form>
    </div>
    <!-- end: PANEL BODY -->
</div>
<!-- end: acc1 -->

<!-- start: acc2 -->
<div class="panel panel-white" >
    <!-- start: PANEL HEADING -->
    <div class="panel-heading">
        <h5 class="panel-title">
            <span class="icon-group-left">
                <i class="fa fa-ellipsis-v"></i>
            </span>
            Weight & Date
            <span class="icon-group-right">
              
                 <a class="btn btn-xs pull-right" href="#">
                    <i class="fa fa-wrench"></i>
                </a>
                <a class="btn btn-xs pull-right panel-collapse closed" href="#" data-panel-group="epic-process" id="goal-buddy-panel">
                    <i class="fa fa-chevron-down"></i>
                </a>
              
            </span>
        </h5>
    </div>
    <!-- end: PANEL HEADING -->
    <!-- start: PANEL BODY -->
    <div class="panel-body">
        <!-- start: goal buddy calendar panal -->
   @include('goal-buddy.client_goal_buddy') 
      
    </div>
    <!-- end: PANEL BODY -->
</div>
<!-- end: acc2 -->
        
<!-- start: acc3 -->
<div class="panel panel-white">
    <!-- start: PANEL HEADING -->
    <div class="panel-heading">
        <h5 class="panel-title">
            <span class="icon-group-left">
                <i class="fa fa-ellipsis-v"></i>
            </span>
            Train & Gain
            <span class="icon-group-right">
                <a class="btn btn-xs pull-right" href="#" data-toggle="modal" data-target="#configModal">
                    <i class="fa fa-wrench"></i>
                </a>
                <a class="btn btn-xs pull-right panel-collapse closed" href="#" data-panel-group="epic-process">
                    <i class="fa fa-chevron-down"></i>
                </a>
            </span>
        </h5>
    </div>
    <!-- end: PANEL HEADING -->
    <!-- start: PANEL BODY -->
    <div class="panel-body">
       
    </div>
    <!-- end: PANEL BODY -->
</div>
<!-- end: acc3 -->
        
<!-- start: acc4 -->
<div class="panel panel-white">
    <!-- start: PANEL HEADING -->
    <div class="panel-heading">
        <h5 class="panel-title">
            <span class="icon-group-left">
                <i class="fa fa-ellipsis-v"></i>
            </span>
            Trace & Replace
            <span class="icon-group-right">
                <a class="btn btn-xs pull-right" href="#" data-toggle="modal" data-target="#configModal">
                    <i class="fa fa-wrench"></i>
                </a>
                <a class="btn btn-xs pull-right panel-collapse closed" href="#" data-panel-group="epic-process">
                    <i class="fa fa-chevron-down"></i>
                </a>
            </span>
        </h5>
    </div>
    <!-- end: PANEL HEADING -->
    <!-- start: PANEL BODY -->
    <div class="panel-body">
        {{-- <p>Services</p>
        <p>Products</p> --}}
        <div class="row review-mode">
            @include('nutrition-step1')
        </div>
        <div class="row editable-mode" style="display: none">
            @include('edit-nutrition-step1')
        </div>
    </div>
    <!-- end: PANEL BODY -->
</div>
<!-- end: acc4 -->
    
<!-- start: acc5 -->
<div class="panel panel-white">
    <!-- start: PANEL HEADING -->
    <div class="panel-heading">
        <h5 class="panel-title">
            <span class="icon-group-left">
                <i class="fa fa-ellipsis-v"></i>
            </span>
            Diarise & Prioritise
            <span class="icon-group-right">
                <a class="btn btn-xs pull-right" href="#" data-toggle="modal" data-target="#configModal">
                    <i class="fa fa-wrench"></i>
                </a>
                <a class="btn btn-xs pull-right panel-collapse closed" href="#" data-panel-group="epic-process">
                    <i class="fa fa-chevron-down"></i>
                </a>
            </span>
        </h5>
    </div>
    <!-- end: PANEL HEADING -->
    <!-- start: PANEL BODY -->
    <div class="panel-body">
        <p>Services</p>
        <p>Products</p>

        
    </div>
    <!-- end: PANEL BODY -->
</div>
<!-- end: acc5 -->