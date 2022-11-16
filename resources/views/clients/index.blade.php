@extends('blank')

@section('page-title')
Client list
@if(isUserType(['Admin']) && Auth::user()->hasPermission(Auth::user(), 'create-client'))

<style type="text/css">
    .menuList > .dropdown-toggle, .menuList > .dropdown-toggle:active, .menuList > .dropdown-toggle:focus, .menuList > .dropdown-toggle:hover{
        color: inherit;
        font-size: 14px;
        padding:6px 12px;
        background: white;
    }
    .select-menus-list{
        width: 165px;
        float: right;
        margin-right: 15px;
        margin-bottom: 0px;
        margin-top: -5px;
    }
    .select-menus-list .btn{
        width: 165px;
    }
    @media(max-width: 767px){
        .page-header .mob-c{
            width: 32.33% !important;
            float: left !important;
            margin-right: 1% !important;
            font-size: 12px !important;
            margin-bottom: 15px;
        }
     .mob-c .btn{
        font-size: 12px;
        margin: 0px !important;
        width: 100%;

    }
    .select-menus-list{
        float: left !important;
        font-size: 12px !important;
        /* width: 53px !important; */
        /* margin-top: 10px !important; */
        width: 100% !important;
        min-width: 100%;
    }
    .rightts{
        float:left !important ;
        width: 100%;
        text-align: center;
    }
    .select-menus-list .bootstrap-select{
        width: 100% !important;
    }
}
</style>

<div class="pull-right rightts">
    
  <a class="btn btn-primary pull-right mob-c" href="{{ route('clients.create') }}"><i class="ti-plus"></i> Add Client</a>
{{--<a class="btn btn-primary pull-right" href="{{ route('clients') }}?my-client={{ auth()->user()->id }}" style="margin-right: 1rem;"><i class="ti-users"></i> My Clients</a>--}}
{{--@if(Request::get('my-client') == null)--}}
    <a class="btn btn-primary pull-right mob-c" href="{{ Request::url() }}?search={{ Request::get('search') }}&my-client={{ auth()->user()->id }}&page=1" style="margin-right: 1rem;"><i class="ti-users"></i> My Clients</a>
  
    <form method="GET" class="form-horizontal pull-right mob-c" action="{{ route('status.export') }}">
         <input class="status-change" name="status" value="all" hidden>
         <button class="btn btn-primary pull-right" type="submit" style="margin-right: 1rem;"> Export </button>
    </form>
    <div class="client-menu-list select-menus-list " style="">

    {{-- <input class="status-change" name="status" value="all" hidden> --}}
    <select name="client_status" class="form-control menuList change-client-status" >
        {{-- <option data-hidden="true" value=""> Move To </option> --}}
        <option value="all">Select All</option>
         @foreach(clientStatuses() as $key => $value)
           @if($value != 'Contra')
              <option value="{{$value}}">{{$value}}</option>
              @endif
         @endforeach
        </select>
    </div>
</div>
    {{--@endif--}}
@endif
@stop

@section('content')
{!! displayAlert()!!}

@if(isUserType(['Admin']) && Auth::user()->hasPermission(Auth::user(), 'delete-client'))
<!-- start: Delete Form -->
@include('includes.partials.delete_form')
<!-- end: Delete Form -->
@endif

{!! Form::hidden('salesProcessCompleted') !!}
{!! Form::hidden('consultationDate') !!}

<div class="panel panel-default">
    <div class="panel-body">
    <div class="row">
        <!-- start: Datatable Header -->
        @include('includes.partials.datatable_header', ['extra' => '<div class="btn-group"><!-- id="changeStatus"--><a class="btn btn-primary btn-o dropdown-toggle" data-toggle="dropdown" href="#">'.($filter?ucwords($filter):'All').' <span class="caret"></span></a><ul role="menu" class="dropdown-menu dropdown-light"><li><a href="'.route('clients').'">All</a></li><li></li><li><a href="'.route('clients').'/pre-consultation">Pre-Consultation</a></li><li><a href="'.route('clients').'/pre-benchmarking">Pre-Benchmarking</a></li><li><a href="'.route('clients').'/pre-training">Pre-Training</a></li><li><a href="'.route('clients').'/active">Active</a></li><li><a href="'.route('clients').'/inactive">Inactive</a></li><li><a href="'.route('clients').'/pending">Pending</a></li><li><a href="'.route('clients').'/on-hold">On hold</a></li><li><a href="'.route('clients').'/active-lead">Active lead</a></li><li><a href="'.route('clients').'/inactive-lead">Inactive lead</a></li><li><a href="'.route('clients').'/contra">Contra</a></li></ul></div>']) <!--<a href="'.route('clients').'/lead">Lead</a>-->
        <!-- end: Datatable Header -->
    </div>
       <input type="hidden" name="operateAsClient" value="">
        <table class="table table-striped table-bordered table-hover m-t-10" id="client-datatable" style="width:100%">
            <thead>
                <tr>
                    <th class="center mw-70 w70">Photo</th>
                    <th>Full Name</th>
                    <th class="">Contact Details</th>
                    <th class="">Risk Factor</th>
                    <th class="">EPIC Credit balance</th>
                    <th class="">Current membership</th>
                    <th class="center">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php //$rowIndex = 0; ?>
                @foreach($allClients as $clients)
                <tr data-status="{{  strtolower( $clients->account_status ) }}" data-id="{{ $clients->id }}">
                    <td class="center mw-70 w70">
                        <a href="{{ route('clients.show', $clients->id) }}"><!--url('client/'.$clients->id)-->
                            <img src="{{ dpSrc($clients->profilepic, $clients->gender) }}" alt="{{ $clients->firstname }} {{ $clients->lastname }}" class="mw-50 mh-50">
                        </a>
                    </td>
                    <td>
                        <a href="{{ route('clients.show', $clients->id) }}">{{ $clients->firstname ?? 'Default' }} {{ $clients->lastname ?? 'Default' }}</a> <!--url('client/'.$clients->id)-->
                        <br>
                        {{  ucfirst($clients->account_status) }}
                        @if($clients->account_status == 'Active' || $clients->account_status == 'Contra')
                        <i class="fa fa-check" ></i>
                        @endif
                    </td>
                    <td class="">
                        <a href="mailto:{{ $clients->email ?? '' }}">{{ $clients->email ?? '' }}</a>
                        <br>
                        <a href="tel:{{ $clients->phonenumber ?? '' }}">{{ $clients->phonenumber ?? '' }}</a>
                    </td>
                    <td class="">
                        {!! ($clients->risk_factor >= 2)?'<span class="epic-tooltip" rel="tooltip" data-toggle="tooltip" data-placement="left" title="This client has high risk factor"><i class="fa fa-warning"></i></span>':'' !!}
                        {{ $clients->risk_factor }}
                    </td>
                    <td class="">
                     $<span class="epic-bal-{{$clients->id}}" >{{ number_format($clients->epic_credit_balance, 2, '.', ',') }}</span>
                 </td>
                 <td class="">
                    {!! $clients->clientMembershipType($clients->id) !!}

                    </td>
                    <td class="center">
                        <div>
                            @if(isUserType(['Admin']) && Auth::user()->hasPermission(Auth::user(), 'view-client'))
                            <a href="javascript:void(0)" data-client-id="{{$clients->id}}" data-client-name="{{ $clients->firstname ?? 'Default' }} {{ $clients->lastname ?? 'Default' }}" data-image="{{ dpSrc($clients->profilepic, $clients->gender) }}" class="btn btn-xs btn-default operateAsClient tooltips" data-placement="top" data-original-title="Login as client">
                                <i class="fa fa-sign-in" style="color:#253746;"></i>
                            </a><!--url('client/'.$clients->id)-->
                            @endif
                            @if(isUserType(['Admin']) && Auth::user()->hasPermission(Auth::user(), 'view-client'))
                            <a href="{{ route('clients.show', $clients->id) }}" class="btn btn-xs btn-default tooltips" data-placement="top" data-original-title="View">
                                <i class="fa fa-share" style="color:#253746;"></i>
                            </a><!--url('client/'.$clients->id)-->
                            @endif

                        @if(isUserType(['Admin']) && Auth::user()->hasPermission(Auth::user(), 'edit-client'))
                        <a class="btn btn-xs btn-default tooltips" href="{{ route('clients.edit', $clients->id) }}" data-placement="top" data-original-title="Edit">
                            <i class="fa fa-pencil" style="color:#253746;"></i><!--editLink-->
                        </a>
                        @endif

                        
                        @if(isUserType(['Admin']) && Auth::user()->hasPermission(Auth::user(), 'delete-client'))
                        <a class="btn btn-xs btn-default tooltips delLink" href="{{ route('clients.destroy', $clients->id) }}" data-placement="top" data-original-title="Delete" data-entity="client">
                            <i class="fa fa-trash-o" style="color:#253746;"></i>
                        </a> 
                        @endif
                        
                        <div class="btn-group">

                         
                            @if(isUserType(['Admin']) && Auth::user()->hasPermission(Auth::user(), 'edit-client'))

                            <!-- <a class="btn btn-xs btn-default tooltips data-btn-{{$clients->id}}" data-toggle="modal" data-target="#raiseMakeUpModel" data-placement="top" data-original-title="Raise Make-Up" data-client-id="{{$clients->id}}" data-check="no" data-labelval="rise" data-netamount="{{ $clients->AllNetAmount }}" data-totalmakeup="{{ $clients->AllRiseMakeup }}"><i class="fa fa-plus" style="color:#253746;"></i></a> -->

                            <a class="btn btn-xs btn-default tooltips data-btn-{{ $clients->id }}" data-toggle="modal" data-target="#raiseMakeUpModel" data-placement="top" data-original-title="EPIC Credit" data-client-id="{{$clients->id}}" data-check="no" data-labelval="rise" data-netamount="{{ $clients->AllNetAmount }}"><i class="fa fa-dollar" style="color:#253746;"></i></a>
                            @endif
                            
                        </div>

                        <div class="form-group client-menu-list" >
                            <select name="menu" class="menuList onChangePop" data-client-id="{{$clients->id}}" data-sales-process-completed="{{$clients->sale_process_step}}" data-consultation-date="{{$clients->consultation_date}}" >
                                <option data-hidden="true" value=""> Move To </option>
                                @foreach(clientStatuses() as $key => $value)
                                <option value="{{$key}}">{{$value}}</option>
                                @endforeach
                                    <!--<option value="lead">Lead</option>
                                    <option value="pre-consultation">Pre-Consultation</option>
                                    <option value="pre-benchmarking">Pre-Benchmarking</option>
                                    <option value="pre-training">Pre-Training</option>
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                    <option value="pending">Pending</option>
                                    <option value="on-hold">On hold</option>
                                    <option value="active-lead">Active lead</option>
                                    <option value="inactive-lead">Inactive lead</option>
                                    <option value="contra">Contra</option>-->
                                </select>
                            </div>
                            <!--<div class="btn-group">
                                <a class="btn btn-primary btn-xs btn-o dropdown-toggle" data-toggle="dropdown" href="#">
                                    Move To <span class="caret"></span>
                                </a>
                                <ul role="menu" class="dropdown-menu dropdown-light">
                                    <li>
                                        <a href="{{ url('client/'.$clients->id.'/lead') }}">
                                            Lead
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ url('client/'.$clients->id.'/pre-consultation') }}">
                                            Pre-Consultation
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ url('client/'.$clients->id.'/pre-benchmarking') }}">
                                            Pre-Benchmarking
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ url('client/'.$clients->id.'/pre-training') }}">
                                            Pre-Training
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ url('client/'.$clients->id.'/active') }}">
                                            Active
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ url('client/'.$clients->id.'/inactive') }}">
                                            Inactive
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ url('client/'.$clients->id.'/pending') }}">
                                            Pending
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ url('client/'.$clients->id.'/on-hold') }}">
                                            On hold
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ url('client/'.$clients->id.'/active-lead') }}">
                                            Active lead
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ url('client/'.$clients->id.'/inactive-lead') }}">
                                            Inactive lead
                                        </a>
                                    </li>
                                </ul>
                            </div>-->
                        </div>
                    </td>
                </tr>
                <?php //$rowIndex++; ?>
                @endforeach 
            </tbody>
        </table>
        
        <!-- start: Paging Links -->
        @include('includes.partials.paging', ['entity' => $allClients])
        <!-- end: Paging Links -->
    </div>
</div>

<!-- start: makeup modal -->
@include('includes.partials.makeup_modal')
<!-- end: makeup modal -->

<!-- Start: Edit Membership Subscription Modal -->
@include('includes.partials.edit_memb_sub', ['memberships' => $allMemberShipData, 'clientId' => 0, 'clientMembership' => null,'paymenttype'=>$paymenttype])
<!-- End: Edit Membership Subscription Modal -->
<!--modal-->
<div id="myModal" class="modal fade new-modal" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"></h4>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-md-12">
             <div class="img-div">
                 <img class="loginClientImg" width="150" height="150" src="" style="border-radius: 50%">
             </div>
             <div class="side-content">
                <h2>You have switched into <span class="loginClient">Timmy</span>'s Account</h2>
                <p>We've detected you've switched into <span class="loginClient">Timmy</span>'s Account in another tab. You can have only one active session at any time.</p>
                <a class="openClient" href="" target="_blank" class="btn">Refresh and view <span class="loginClient"></span> account</a>
            </div>
            
        </div>
        
    </div>
</div>
</div>

</div>
</div>
@stop
@section('script')
<script>
    var cookieSlug = "client";
    $.fn.dataTable.moment('ddd, D MMM YYYY');
    $('#client-datatable').dataTable({"searching": false, "paging": false, "info": false });
</script>
<!-- start: moment -->
<!-- {!! Html::script('vendor/moment/moment.min.js') !!}
{!! Html::script('vendor/moment/moment-timezone-with-data.js') !!}
{!! Html::script('assets/js/set-moment-timezone.js') !!}  -->
<!-- end : moment  -->

{!! Html::script('assets/js/helper.js') !!}

{!! Html::script('assets/js/makeup.js') !!}
<script>
    function changeStatus(clientId, newStatus){
        var formData={};
        formData['clientId']=clientId;
        formData['newStatus']=newStatus;
    //formData['currenturl']=window.location.href;   
    toggleWaitShield('show');
    $.ajax({
        url: public_url+'client/change-status',
        method: "POST",
        data: formData,
        success: function(data) {
            toggleWaitShield('hide');
            myObj=JSON.parse(data);
            if(myObj.status=='succsess'){
                if(newStatus=='active'){
                    swal.close();
                    var emm = $('#editMembSub');
                    emm.modal('show');
                    emm.find('input[name=clientId]').val(clientId);
                    emm.on("hidden.bs.modal", function () {
                        location.reload();
                    });
                }
                else {
                    location.reload(true);
                }
            }
        }
    });
    /*var curl=window.location.href;
    window.location.href = public_url+'client/'+clientId+'/'+newStatus+'?u='+curl;*/ 
}
function statusChangeSwalCancle(){
    $("select.menuList").val('').selectpicker('refresh')
}
jQuery(document).ready(function() { 
    //$("div#changeStatus").html('<a class="btn btn-primary btn-o dropdown-toggle" data-toggle="dropdown" href="#">{{ ($filter)?ucwords($filter):'All' }} <span class="caret"></span></a><ul role="menu" class="dropdown-menu dropdown-light"><li><a href="{{route('clients')}}">All</a></li><li><a href="{{route('clients')}}/lead">Lead</a></li><li><a href="{{route('clients')}}/pre-consultation">Pre-Consultation</a></li><li><a href="{{route('clients')}}/pre-benchmarking">Pre-Benchmarking</a></li><li><a href="{{route('clients')}}/pre-training">Pre-Training</a></li><li><a href="{{route('clients')}}/active">Active</a></li><li><a href="{{route('clients')}}/inactive">Inactive</a></li><li><a href="{{route('clients')}}/pending">Pending</a></li><li><a href="{{route('clients')}}/on-hold">On hold</a></li><li><a href="{{route('clients')}}/active-lead">Active lead</a></li><li><a href="{{route('clients')}}/inactive-lead">Inactive lead</a></li></ul>');
})
function statusChangeSwalConfirm($currentSelectElem){
    var $this = $currentSelectElem,
    newStatus = $this.val(),
    clientId = $this.data('client-id')
    lastStatus = $this.closest('tr').data('status');
    if(lastStatus==newStatus){
        $this.val('').selectpicker('refresh')
        swal.close();
        return;
    }
    
    $('input[name="salesProcessCompleted"]').val($this.data('sales-process-completed'))
    $('input[name="consultationDate"]').val($this.data('consultation-date'))

    
    salesProcessUpgradeCheck(newStatus, function(){
        changeStatus(clientId, newStatus)
    }, statusChangeSwalCancle);  
}

$('.operateAsClient').click(function(){
    var clientId = $(this).data('client-id');
    var clientName = $(this).data('client-name');
    var clientImg = $(this).data('image');
    var formData = {};
    formData['clientId'] = clientId;
    var url = "{{url('clients/operate-as-client/')}}"+'/'+clientId;
    var weburl = "https://epicresult.com/login/";
    $.get(url,function(response){
        if(response.status == 'ok'){
            var loginUrl = weburl+response.bussName+"?id="+clientId;
            if(response.operateAsClient == "yes"){
                $('#myModal').modal('show');
                $('#myModal').find('.loginClient').text(clientName);
                $('#myModal').find('.loginClientImg').attr('src',clientImg);
                $('#myModal').find('.openClient').attr('href',loginUrl);
            }else{
                window.open(loginUrl, '_blank');
            }
        }else{
            swal({
                type: 'error',
                title: 'Error!',
                showCancelButton: false,
                allowOutsideClick: false,
                text: response.message,
                showConfirmButton: true,     
            });
        }
    })
})

/* select */
       $(document).on('change', '.change-client-status', function() {
            if($(this).val()){      
                $('input[name="status"]').attr('value', $(this).val());
            }    
        });

/* end */

/*$(".menuList").change(function(){

    var $this = $(this),
        newStatus = $this.val(),
        clientId = $this.data('client-id'),
        currenturl=$this.data('currenturl');

         //$('input[name="currentUrl"]').val($this.data('currenturl'));
    $('input[name="salesProcessCompleted"]').val($this.data('sales-process-completed'))
    $('input[name="consultationDate"]').val($this.data('consultation-date'))

   
    salesProcessUpgradeCheck(newStatus, function(){
        changeStatus(clientId, newStatus)
    }, cancelChangeStatus);
});*/

/*$('#raiseMakeUpModel').click(function(){
    var $this = $(this),
        newStatus = $this.val(),
        clientId = $this.data('client-id');

    })*/


  /* end: Submitting custom pla
/*$('.editLink').click(function(){
    var cookieName = calcRecord_indexCookieName(cookieSlug);
    $.cookie(cookieName, JSON.stringify({rowIndex:$(this).closest('tr').data('row-index'), use:false}), {path:'/'})
});*/
</script>

<!-- start: Client-Membership Modal -->
{!! Html::script('assets/js/client-membership.js?v='.time()) !!}
<!-- end: Client-Membership Modal -->
@stop