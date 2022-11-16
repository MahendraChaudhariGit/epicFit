@extends('blank')

@section('page-title')
	Discount List
      @if(isUserEligible(['Admin'], 'add-discount'))
        <a class="btn btn-primary pull-right" href="{{ route('salestools.discount.create') }}"><i class="ti-plus"></i> Add Discount</a>
      @endif  
@stop()

@section('content')
{!! displayAlert()!!}

@if(isUserEligible(['Admin'], 'delete-discount'))
  @include('includes.partials.delete_form')
@endif

    <div class="panel panel-default">
        <div class="panel-body">
            <div class="row">
                @include('includes.partials.datatable_header')
            </div>
            <table class="table table-striped table-bordered table-hover m-t-10" id="discount-datatable">
                <thead>
                    <tr>
                        <!--<th class="center mw-70 w70">Photo</th>-->
                        <th>Name</th>
                        <th class="">Tax</th>
                        <th >Type</th>
                        <th class="center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($alldiscount as $alldiscounts)
                    <tr>
                       <!--<td class="center mw-70 w70">
                            <a href="">
                                <img src="" alt="">
                            </a>
                        </td>-->
                        <td>
                            <!--<a href="">-->{{ $alldiscounts->std_name ?? 'Default' }}<!--</a> <br>-->
                        </td>
                        <td class="">
                            {{ $alldiscounts->tax->mtax_label ?? 'Not applicable' }} <br>

                        </td>
                        <td>
                            {{ $alldiscounts->std_type ?? 'Default' }} <br>
                        </td>

                        <td class="center">
                            <div>
                                <!--<a href="" class="btn btn-xs btn-default tooltips" data-placement="top" data-original-title="View" ><i class="fa fa-share" style="color:#ff4401;"></i></a>-->
                            @if(isUserEligible(['Admin'], 'edit-discount'))
                                <a class="btn btn-xs btn-default tooltips" href="{{ route('salestools.discount.edit', $alldiscounts->std_id) }}" data-placement="top" data-original-title="Edit">
                                    <i class="fa fa-pencil text-primary"></i>
                                </a>
                            @endif    
                             @if(isUserEligible(['Admin'], 'delete-discount'))     
                                <a class="btn btn-xs btn-default tooltips delLink" href="{{ route('salestools.discount.destroy', $alldiscounts->std_id) }}" data-placement="top" data-original-title="Delete" data-entity="discount">
                                <i class="fa fa-trash-o text-primary"></i>
                                </a>
                             @endif   
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            @include('includes.partials.paging', ['entity' => $alldiscount])
        </div>
    </div>

@stop

@section('script')
    <script>
    var cookieSlug = "sales-tools-discount";
    $.fn.dataTable.moment('ddd, D MMM YYYY');
    $('#discount-datatable').dataTable({"searching": false, "paging": false, "info": false });
    </script>
    {!! Html::script('assets/js/helper.js?v='.time()) !!}

    <!-- start: Moment Library -->
    <!-- {!! Html::script('vendor/moment/moment.min.js') !!} -->
    <!-- end: Moment Library -->
      
@stop
