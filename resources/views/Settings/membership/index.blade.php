@extends('blank')

@section('page-title')
    Membership list 
    @if(isUserType(['Admin']) && Auth::user()->hasPermission(Auth::user(), 'create-membership')) 
    <a class="btn btn-primary pull-right" href="{{ route('memberships.create') }}"><i class="ti-plus"></i> Add Membership Option</a>
    <!--url('settings/business/'.Session::get('businessId').'/edit#3')-->
    @endif
@stop

@section('content')
{!! displayAlert()!!}

@if(isUserType(['Admin']) && Auth::user()->hasPermission(Auth::user(), 'delete-staff'))
    <!-- start: Delete Form -->
    @include('includes.partials.delete_form')
    <!-- end: Delete Form -->
@endif

    <div class="panel panel-default">
        <div class="panel-body">
            <div class="row">
            <!-- start: Datatable Header -->
            @include('includes.partials.datatable_header')
            <!-- end: Datatable Header -->
            </div>
            <!--<div class="table-responsive">-->
                <table class="table table-striped table-bordered table-hover m-t-10" id="membership-datatable">
                    <thead>
                        <tr>     
                            <th>Label</th>
                            <th class="">Category </th>
                            <th >Length</th>
                            <th class="center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($allMemberShip as $memberShip)
                      <?php 
                      //dd($memberShip->categorymember->pluck('mc_category_value'));
                      $allcategoryArr=[];
                     $allcategoryArr[]=$memberShip->categorymember->pluck('mc_category_value')->toArray();

                     if(isset($allcategoryArr[0]))
                     $categoryArrData=implode(",",$allcategoryArr[0]);
                     else
                        $categoryArrData='';

                      ?>
                        <tr>
                           
                            <td> {{isset($memberShip->me_membership_label )?$memberShip->me_membership_label :null}}</td>
                            <td class="">
                             {{$categoryArrData}}
                            </td>
                            <td>{{isset($memberShip->me_validity_length )?$memberShip->me_validity_length :null}}</td>
                            <td class="center">
                                <div>
                                    <!--a href="{{ route('membership.show', $memberShip->id) }}" class="btn btn-xs btn-default tooltips" data-placement="top" data-original-title="View" ><i class="fa fa-share text-primary"></i></a-->
                                    @if(isUserType(['Admin']) && Auth::user()->hasPermission(Auth::user(), 'edit-membership')) 
                                    <a class="btn btn-xs btn-default tooltips" href="{{ route('membership.edit', $memberShip->id) }}" data-placement="top" data-original-title="Edit">
                                        <i class="fa fa-pencil text-primary"></i>
                                    </a>
                                    @endif
                                    
                                 	@if(isUserEligible(['Admin'], 'delete-membership'))      
                                    <a class="btn btn-xs btn-default tooltips delLink" href="{{ route('membership.destroy', $memberShip->id) }}" data-placement="top" data-original-title="Delete" data-entity="membership">
                                    <i class="fa fa-trash-o text-primary"></i>
                                    </a>
                                    @endif
                                    <!--a class="btn btn-xs btn-default tooltips delLink" href="{{ route('membership.destroy', $memberShip->id) }}" data-placement="top" data-original-title="Delete" data-entity="membership"><i class="fa fa-trash-o text-primary"></i></a-->
                                    
                                </div>
                            </td>
                        </tr>
                        @endforeach 
                    </tbody>
                </table>
                <!-- start: Paging Links -->
                @include('includes.partials.paging', ['entity' => $allMemberShip])
                <!-- end: Paging Links -->
            <!--</div>-->
            
        </div>
    </div>
   
@stop
@section('script')
<script>
var cookieSlug = "membership";
    $.fn.dataTable.moment('ddd, D MMM YYYY');
    $('#membership-datatable').dataTable({"searching": false, "paging": false, "info": false });
</script>
{!! Html::script('assets/js/helper.js?v='.time()) !!}
@stop