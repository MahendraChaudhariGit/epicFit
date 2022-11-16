<!-- Start: Membership -->
    <table class="table table-condensed table-hover">
        <thead>
            <tr>
                <th colspan="3">Membership information</th>
            </tr>
        </thead>
        <tbody>
            {!! Form::hidden('membershipStatus', $activeMemb?$activeMemb->cm_status:'Inactive') !!}
            <tr>
                <td>Membership Option</td>
                <td>{{ $selectedMemberShip?$selectedMemberShip->cm_label:'' }}</td><!--data-realtime="membershipOption"-->
                <td> 
                    {{-- @if(isUserEligible(['Admin'], 'manage-client-membership')) --}}
                        <a href="#" data-toggle="modal" data-target="#editMembSub">
                            <i class="fa fa-pencil edit-user-info"></i>
                        </a><!--class="editFieldModal" data-label="Membership Option" data-value="{{ isset($selectedMemberName[0]->id)?$selectedMemberName[0]->id:null }}" data-required="true" data-realtime="membershipOption" data-memberoption="{{ isset($allMemberShipData)?json_encode($allMemberShipData):null }}"-->
                    {{-- @endif --}}
                </td>
            </tr>
            <tr>
                <td>Due Date</td>
                <td>{{ $selectedMemberShip?dbDateToDateString($selectedMemberShip->cm_due_date):'' }}</td>
                <td> 
                    {{-- @if(isUserEligible(['Admin'], 'manage-client-membership')) --}}
                        <a href="#" data-toggle="modal" data-target="#editMembSub">
                            <i class="fa fa-pencil edit-user-info"></i>
                        </a>
                    {{-- @endif --}}
                </td>
            </tr>
            <tr>
                <td>Membership Status:</td>
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
                @endif        
                </td>
                <td> 
                     {{-- @if(isUserEligible(['Admin'], 'manage-client-membership') && $activeMemb && ($activeMemb->cm_status=='Active' || $activeMemb->cm_status=='On Hold')) --}}
                        <a href="#" class="editFieldModal" data-label="Status" data-value="{{ !empty($selectedMemberShip->cm_status) && isset($selectedMemberShip->cm_status) ? $selectedMemberShip->cm_status : 'Inactive' }}" data-required="true" data-realtime="membStatus"><i class="fa fa-pencil edit-user-info"></i></a>
                        <!-- <a href="#" data-toggle="modal" data-target="#editMembSub">
                            <i class="fa fa-pencil edit-user-info"></i>
                        </a> -->
                    {{-- @endif --}}
                </td>
            </tr>
        </tbody>
    </table>
<!-- End: Membership -->