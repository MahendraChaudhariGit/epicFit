<div id="memberships" class="tab-pane" style="position:relative">
    @if( isset( $selectedMemberShip ) && $selectedMemberShip )
    <div class="row">
        <div class="col-sm-5 col-md-4">
            <div class="user-left">
                <div class="center">
                    <h4>{{ ucwords( $selectedMemberShip->cm_label ) }}</h4>

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
                            <td>{{ dbDateToDateString(new Carbon\Carbon($selectedMemberShip->cm_start_date)) }}</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>Length:</td>
                            <td>{{ $selectedMemberShip->cm_validity_length }} {{ $selectedMemberShip->cm_validity_type }}s</td>
                            <td></td>
                        </tr>

                        <tr>
                            <td>Auto Renewal:</td>
                            <td>{{ ucfirst( $selectedMemberShip->cm_auto_renewal ) }}</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>Ends On:</td>
                            <td>{{ dbDateToDateString($selectedMemberShip->cm_end_date) }}</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>Status:</td>
                            <td>
                                @if($selectedMemberShip)
                                @if($selectedMemberShip->cm_status=='Paid')
                                <span class="label label-info"> Paid </span>
                                @elseif($selectedMemberShip->cm_status=='Unpaid') 
                                <span class="label label-warning"> Unpaid </span>
                                @elseif($selectedMemberShip->cm_status=='Expired')
                                <span class="label label-danger">  Expired </span>
                                @endif  
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
                                @endif
                            </td>
                            <td></td>
                        </tr>

                        <tr>
                            <td>Due Date:</td>
                            <td>{{ $selectedMemberShip->cm_due_date ? dbDateToDateString( $selectedMemberShip->cm_due_date ) : '' }}</td>
                            <td></td>
                        </tr>
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
                            <td>{!! $membershipServices !!}</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>Classes:</td>
                            <td>{!! $membershipClasses !!}</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>Enrollment Limit:</td>
                            <td>Upto {{ $selectedMemberShip->cm_enrollment_limit }} classes</td>
                            <td></td>
                        </tr>
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

                <div class="panel-heading">
                    <h5 class="panel-title">
                        <span class="icon-group-left">
                            <i class="fa fa-calendar"></i>
                        </span> 
                        Appointments
                        <span class="icon-group-right">
                            <a class="btn btn-xs pull-right" href="#" data-toggle="modal" data-target="#configModal">
                                <i class="fa fa-wrench"></i>
                            </a>
                            <a class="btn btn-xs pull-right panel-collapse closed" href="#" data-panel-group="client-overview">
                                <i class="fa fa-chevron-down"></i>
                            </a>
                        </span>
                    </h5>
                </div>
                <!-- end: PANEL HEADING -->
                <!-- start: PANEL BODY -->
                <div class="panel-body">
                    <div id="calendEvent" class="calendEvent"></div>
                    <input type="hidden" name="duration_val_in" id="duration_val_in" value="">



                    <div class="panel-body"><!--panel-scroll show-on-load style="height:300px"-->
                        @include('Result.partials.overview-events', ['latestPastEvent' => $latestPastEventInMembership, 'oldestFutureEvent' => $oldestFutureEventInMembership])
                    </div>



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
                            <!--<a href="#">{{ dbDateToDateString($membership->created_at) }}</a>&nbsp;</div>-->
                            {{ dbDateToDateString($membership->created_at) }}&nbsp;</div>
                        <div class="panel-body">    
                            <div><i class="fa fa-cog" style="color:#008000"></i> {{ $membership->cm_subscription_type == 'manual' ? 'Manually' : 'Automatically' }} subscribed to {{ ucwords( $membership->cm_label ) }}</div>

                        </div>
                    </div>
                    @endforeach
                    @endif
                </div>
                <!-- end: PANEL BODY -->
            </div>
            <!-- end: Sales accordian -->
        </div>
    </div>
    <script type="text/javascript">
    //$(document).ready(function(){
    //	setTimeout("$('.show-on-load').show()", 1000);
    //});
    </script>
    @endif
</div>