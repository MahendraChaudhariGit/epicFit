
<div class="row">
    <div class="col-sm-5 col-md-4">
        <div class="user-left">
            <div class="center">
                <h4>{{ ucwords( $selectedMemberShip->cm_label ) }}</h4>
                Last Paid Amount: ${{ $lastPaidAmount }}
                <br>
             
                Next Payment: {{ $clients->account_status == 'On Hold' ? '-':'$'.((count($nextMemberShip))?$nextMemberShip->cm_next_emi:($selectedMemberShip ? $selectedMemberShip->cm_next_emi:0)) }}
                <br>
                Due Date: {{ $clients->account_status == 'On Hold' ? '-':($selectedMemberShip->cm_due_date ? dbDateToDateString( $selectedMemberShip->cm_due_date ) : '-') }}
                <!--<div>
                    <div class="user-image">
                        <div class="thumbnail">
                            <img src="{{ dpSrc($clients->profilepic, $clients->gender) }}" class="img-responsive clientPreviewPics previewPics" id="profile-userpic-img" alt="{{ $clients->firstname }} {{ $clients->lastname }}" data-realtime="gender">
                        </div>
                        <div class="form-group upload-group">
                            <input type="hidden" name="prePhotoName" value="{{ $clients->profilepic }}">
                            <input type="hidden" name="entityId" value="{{$parq->client_id}}">
                            <input type="hidden" name="saveUrl" value="client/photo/save">
                            <input type="hidden" name="photoHelper" value="client">
                            <input type="hidden" name="cropSelector" value="square">
                            <div>
                                <label class="btn btn-primary btn-file">
                                    <span>Change Photo</span> <input type="file" class="hidden" onChange="fileSelectHandler(this)" accept="image/*">
                                </label>
                            </div>
                        </div>
                        <div class="user-image-buttons" style="display:none;">
                            <span class="btn btn-teal btn-file btn-sm"><span class="fileupload-new"><i class="fa fa-pencil"></i></span><span class="fileupload-exists"><i class="fa fa-pencil"></i></span>
                                <input type="file">
                            </span>
                            <a href="#" class="btn fileupload-exists btn-bricky btn-sm" data-dismiss="fileupload">
                                <i class="fa fa-times"></i>
                            </a>
                        </div>
                    </div>
                </div>-->
                <hr>
            </div>
            <table class="table table-condensed">
                <thead>
                    <tr>
                        <th colspan="3">General Information</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Subscribed On:</td>
                        @if($selectedMemberShip)
                        <td>{{ dbDateToDateString(new Carbon\Carbon($initialSubscriptionDate)) }}</td>
                        <td></td>
                        @else
                        <td>{{'-'}}</td>
                            @endif
                    </tr>
                    <!-- <tr>
                        <td>Amount paid:</td>
                        <td>${{ $selectedMemberShip->cm_emi }}</td>
                        <td></td>
                    </tr> -->
                    <tr>
                        <td>Length:</td>
                        <td>{{ isset($selectedMemberShip) && $selectedMemberShip->cm_validity_length != null ?  $selectedMemberShip->cm_validity_length : '-' }} {{ $selectedMemberShip->cm_validity_type }}</td>
                        <td></td>
                    </tr>
                    
                    <tr>
                        <td>Renewal On:</td>
                        @if($selectedMemberShip)
                        <td>{{  dbDateToDateString(new Carbon\Carbon($selectedMemberShip->cm_start_date)) }}</td>
                        <td></td>
                        @else
                    <td>{{'-'}}</td>
                        @endif
                    </tr>
                    @if(strtolower($selectedMemberShip->cm_auto_renewal) == 'on')
                    <tr>
                        <td>Auto Renewal:</td>
                        <td>{{ ucfirst( $selectedMemberShip->cm_auto_renewal ) }}</td>
                        <td></td>
                    </tr>
                    @else
                    <tr>
                        <td>Ends On:</td>
                        @if($selectedMemberShip)
                        <td>{{ isset($selectedMemberShip) ? dbDateToDateString($selectedMemberShip->cm_end_date) :'-' }}</td>
                        <td></td>
                        @else
                        <td>{{'-'}}</td>
                            @endif
                    </tr>
                    @endif
                    <tr>
                        <td>Status:</td>
                        <td>
                            @if($selectedMemberShip)
                            @if($selectedMemberShip->cm_status=='Active')
                            <span class="label label-info"> Active </span>
                            @elseif($selectedMemberShip->cm_status=='On Hold') 
                            <span class="label label-warning"> On Hold </span>
                            @elseif($selectedMemberShip->cm_status=='Expired')
                            <span class="label label-danger">  Expired </span>
                            @else
                            <span class="label label-danger"> Inactive </span>
                            @endif 
                            @else
                            <span> - </span>
                            @endif     
                        </td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
            <table class="table table-condensed">
                <thead>
                    <tr>
                        <th colspan="3">Billing Details</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Payment Plan:</td>
                        <td>
                            @if($selectedMemberShip->cm_pay_plan)
                            <?php $plans = memberShipPayPlans(); ?>
                            {{ $plans[$selectedMemberShip->cm_pay_plan]['name'] }}
                            @else
                            <span> - </span>
                            @endif 
                        </td>
                        <td></td>
                    </tr>
                    <!-- <tr>
                        <td>Installment Amount:</td>
                        <td>${{-- $selectedMemberShip->cm_total_amount --}}</td>
                        <td></td>
                    </tr> -->
                    <!-- <tr>
                        <td>Due Date:</td>
                        <td>{{ $selectedMemberShip->cm_due_date ? dbDateToDateString( $selectedMemberShip->cm_due_date ) : '' }}</td>
                        <td></td>
                    </tr> -->
                </tbody>
            </table>
            <table class="table table-condensed">
                <thead>
                    <tr>
                        <th colspan="3">Service Classes</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Services:</td>
                        <td>{!! $membershipServices == "" ? '-' : $membershipServices !!}</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Classes:</td>
                        <td>{!! $membershipClasses == "" ? '-' : $membershipClasses !!}</td>
                        <td></td>
                    </tr>
                    <!-- <tr>
                        <td>Enrollment Limit:</td>
                        <td>Upto {{-- $selectedMemberShip->cm_enrollment_limit --}} classes</td>
                        <td></td>
                    </tr> -->
                    <tr>
                        <td>Class Limit:</td>
                        <td>
                            @if( $selectedMemberShip->cm_class_limit == 'limited' )
                            @if( $selectedMemberShip->cm_class_limit_type == 'every_week' )
                            {{ '', $cltext = ' classes every week' }}
                            @elseif( $selectedMemberShip->cm_class_limit_type == 'every_month' )
                            {{ '', $cltext = ' classes every month' }}
                            @else
                            {{ '', $cltext = ' class cards' }}
                            @endif
                            {{ $selectedMemberShip->cm_class_limit_length }} {{ $cltext }}
                            @elseif(!$selectedMemberShip)
                            {{'-'}}
                            @else
                            Unlimited
                            @endif
                        </td>
                        <td></td>
                    </tr>
                    <tr>
                        <td> Appointments Booked</td>
                        <td> @if( $selectedMemberShip->cm_class_limit == 'limited' )
                            @if( $selectedMemberShip->cm_class_limit_type == 'every_week' )
                            {{ '', $cltext = ' week' }}
                            @elseif( $selectedMemberShip->cm_class_limit_type == 'every_month' )
                            {{ '', $cltext = ' month' }}
                            @endif
                            {{ $limitCount }} booking this {{ $cltext }}
                            @elseif(!$selectedMemberShip)
                            {{'-'}}
                            @else
                            Unlimited
                            @endif
                        </td>
                        
                        <td></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="col-sm-7 col-md-8">

        <!-- start: Appointments accordian -->
        <div class="panel panel-white">
            <!-- start: PANEL HEADING -->
            <div class="panel-heading">
                <h5 class="panel-title">
                    <span class="icon-group-left">
                      <i class="fa fa-calendar"></i>
                  </span> 
                  Current Membership Appointments
                  <span class="icon-group-right">
                    <a class="btn btn-xs pull-right" href="#" data-toggle="modal" data-target="#configModal">
                        <i class="fa fa-wrench"></i>
                    </a>
                    <a class="btn btn-xs pull-right panel-collapse" href="#" data-panel-group="membership">
                        <i class="fa fa-chevron-down"></i>
                    </a>
                </span>
            </h5>
        </div>
        <!-- end: PANEL HEADING -->
        <!-- start: PANEL BODY -->
        <div class="panel-body"><!--panel-scroll show-on-load style="height:300px"-->
            @include('includes.partials.overview-events', ['latestPastEvent' => $latestPastEventInMembership, 'oldestFutureEvent' => $oldestFutureEventInMembership])
        </div>
        <!-- end: PANEL BODY -->
    </div>
    <!-- end: Appointments accordian -->

    <!-- start: Sales accordian -->
    <div class="panel panel-white">
        <!-- start: PANEL HEADING -->
        <div class="panel-heading">
            <h5 class="panel-title">
                <span class="icon-group-left">
                    <i class="fa fa-file-o"></i>
                </span> 
                Membership History
                <span class="icon-group-right">
                    <a class="btn btn-xs pull-right" href="#" data-toggle="modal" data-target="#configModal">
                        <i class="fa fa-wrench"></i>
                    </a>
                    <a class="btn btn-xs pull-right panel-collapse closed" href="#" data-panel-group="membership">
                        <i class="fa fa-chevron-down"></i>
                    </a>
                </span>
            </h5>
        </div>
        <!-- end: PANEL HEADING -->
        <!-- start: PANEL BODY -->
        <div class="panel-body">
            @if( count( $membershipHistory ) )
            @foreach( $membershipHistory as $membership )
            <div class="panel panel-default">
                <div class="panel-heading">
                    <a href="#">{{ setLocalToBusinessTimeZone($membership->created_at,'dateString') }}</a>&nbsp;</div>
                    <div class="panel-body">    
                    	<div><i class="fa fa-cog" style="color:#008000"></i> {{ $membership->cm_subscription_type == 'manual' ? 'Manually' : 'Automatically' }} subscribed to {{ ucwords( $membership->cm_label ) }}</div>
                        
                    </div>
                </div>
                @endforeach
                @endif
                @if(!count( $membershipHistory) && count($membershipRemoveHistory))
            <div class="panel panel-default">
                <div class="panel-heading">
                    <a href="#">{{ setLocalToBusinessTimeZone($membershipRemoveHistory->deleted_at,'dateString') }}</a>&nbsp;</div>
                    <div class="panel-body">    
                    	<div><i class="fa fa-cog" style="color:#008000"></i> {{ $membershipRemoveHistory->cm_status == 'Removed' ? 'Membership Cancelled' : '' }} By {{ ucwords( $membershipRemoveHistory->cm_cancelled_by ) }}</div>
                        
                    </div>
                </div>
                @endif
            </div>
            <!-- end: PANEL BODY -->
        </div>
        <!-- end: Sales accordian -->

    <!-- start: membership history accordian -->
    <div class="panel panel-white">
        <!-- start: PANEL HEADING -->
        <div class="panel-heading">
            <h5 class="panel-title">
                <span class="icon-group-left">
                    <i class="fa fa-file-o"></i>
                </span> 
                Status History
                <span class="icon-group-right">
                    <a class="btn btn-xs pull-right" href="#" data-toggle="modal" data-target="#configModal">
                        <i class="fa fa-wrench"></i>
                    </a>
                    <a class="btn btn-xs pull-right panel-collapse closed" href="#" data-panel-group="membership">
                        <i class="fa fa-chevron-down"></i>
                    </a>
                </span>
            </h5>
        </div>
        <!-- end: PANEL HEADING -->
        <!-- start: PANEL BODY -->
        <div class="panel-body">
            @if( count( $membershipHistoryData ) )
            @foreach( $membershipHistoryData as $membership )
            <div class="panel panel-default">
                <div class="panel-heading">
                    <a href="#">{{ date("D, d M Y h:i A", strtotime($membership->created_at))}}</a>&nbsp;</div>
                    <div class="panel-body">    
                        <div><i class="fa fa-cog" style="color:#008000"></i> {{ $membership->history }}</div>
                    </div>
                </div>
                @endforeach
                @endif
            </div>
            <!-- end: PANEL BODY -->
        </div>
        <!-- end: membership history accordian -->




    </div>
</div>
<script type="text/javascript">
    $(document).ready(function(){
       setTimeout("$('.show-on-load').show()", 1000);
   });
</script>
