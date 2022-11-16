@extends('blank')

@section('page-title')
    Closed Dates List
        @if(isUserEligible(['Admin'], 'add-closed-dates'))
            <a class="btn btn-primary pull-right" href="{{ route('closeddate.create') }}"><i class="ti-plus"></i> Add Close Date</a>
        @endif    
@stop()

@section('content')
{!! displayAlert()!!}

 @if(isUserEligible(['Admin'], 'delete-closed-dates'))
     @include('includes.partials.delete_form')
 @endif    
    <div class="panel panel-default">
        <div class="panel-body">
            <div class="row">
                @include('includes.partials.datatable_header')
            </div>
            <table class="table table-striped table-bordered table-hover m-t-10" id="close-date-datatable">
                <thead>
                    <tr>
                        <!--<th class="center mw-70 w70">Photo</th>-->
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th class="">Description</th>
                        <th class="center">Actions</th>
                    </tr>
                </thead>
                <tbody>
               
                 @foreach($alldates as $alldate)
                    <tr>
                       <!--<td class="center mw-70 w70">
                            <a href="">
                                <img src="" alt="">
                            </a>
                        </td>-->
                        <td>
                            <!--<a href="">-->{!! $alldate->StartDate or 'Default' !!}<!--</a> <br>-->
                        </td>
                        <td>
                            {!! $alldate->EndDate or 'Default' !!} <br>

                        </td>
                        <td class="">
                            {!! $alldate->cd_description or 'Default' !!} <br>
                        </td>

                        <td class="center">
                            <div>
                               
                               @if(isUserEligible(['Admin'], 'edit-closed-dates'))
                                <a class="btn btn-xs btn-default tooltips" href="{{ route('closeddate.edit', $alldate->cd_id) }}" data-placement="top" data-original-title="Edit">
                                    <i class="fa fa-pencil text-primary" ></i>
                                </a>
                                @endif    
                                @if(isUserEligible(['Admin'], 'delete-closed-dates'))
                                <a class="btn btn-xs btn-default tooltips delLink" href="{{ route('closeddate.destroy', $alldate->cd_id) }}" data-placement="top" data-original-title="Delete" data-entity="date">
                                <i class="fa fa-trash-o text-primary"></i>
                                </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            @include('includes.partials.paging', ['entity' => $alldates])
        </div>
    </div>
@stop

@section('script')
    <script>
    var cookieSlug = "sales-tools-discount";
    $.fn.dataTable.moment('ddd, D MMM YYYY');
    $('#close-data-datatable').dataTable({"searching": false, "paging": false, "info": false });
    </script>
    {!! Html::script('assets/js/helper.js') !!}
@stop
