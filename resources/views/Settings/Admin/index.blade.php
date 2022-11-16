@extends('blank')

@section('page-title')
	Administrator List
      @if(isUserEligible(['Admin'], 'create-administrator'))
        <a class="btn btn-primary pull-right" href="{{ route('admin.create') }}"><i class="ti-plus"></i> Add Administrator</a>
       @endif
@stop()

@section('content')
{{ displayAlert()}}

@if(isUserEligible(['Admin'], 'delete-administrator'))
  @include('includes.partials.delete_form')
@endif
    <div class="panel panel-default">
        <div class="panel-body">
            <div class="row">
                @include('includes.partials.datatable_header')
            </div>
            <table class="table table-striped table-bordered table-hover m-t-10" id="admin-datatable">
                <thead>
                    <tr>
                        <th class="center mw-70 w70">Photo</th>
                        <th>First Name</th>
                        <th class="">Last Name</th>
                        <th class="">Email</th>
                        <th class="">Phone</th>
                        <th class="center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($alladmins as $alladmin)
                    <tr>
                       <td class="center mw-70 w70">
                            <img src="{{ dpSrc($alladmin->profile_picture) }}" alt="{{ $alladmin->name }} {{ $alladmin->last_name }}" class="mw-50 mh-50">
                        </td>
                        <td>
                            <!--<a href="">-->{{ $alladmin->name ?? 'Default' }}<!--</a> <br>-->
                        </td>
                        <td class="">
                            {{ $alladmin->last_name ?? 'Not applicable' }} <br>

                        </td>
                        <td class="">
                            <a href="mailto:{{ $alladmin->email ?? '' }}" style="color:#ff4401;">
                                {{ $alladmin->email ?? 'Default' }}
                            </a> <br>
                        </td>

                        <td class="">
                            <a href="tel:{{ $alladmin->telephone ?? '' }}">
                                {{ $alladmin->telephone ?? 'Default' }}
                            </a> <br>
                        </td>

                        <td class="center">
                            <div>
                                <!--<a href="" class="btn btn-xs btn-default tooltips" data-placement="top" data-original-title="View" ><i class="fa fa-share text-primary"></i></a>-->
                            @if(isUserEligible(['Admin'], 'edit-administrator'))
                                <a class="btn btn-xs btn-default tooltips" href="{{ route('admin.edit', $alladmin->id) }}" data-placement="top" data-original-title="Edit">
                                    <i class="fa fa-pencil text-primary"></i>
                                </a>
                            @endif    
                            @if(isUserEligible(['Admin'], 'delete-administrator'))     
                                <a class="btn btn-xs btn-default tooltips delLink" href="{{ route('admin.destroy', $alladmin->id) }}" data-placement="top" data-original-title="Delete" data-entity="admin">
                                <i class="fa fa-trash-o text-primary" ></i>
                                </a>
                            @endif   
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            @include('includes.partials.paging', ['entity' => $alladmins])
        </div>
    </div>
@stop

@section('script')
    <script>
    var cookieSlug = "admin";
    $.fn.dataTable.moment('ddd, D MMM YYYY');
    $('#admin-datatable').dataTable({"searching": false, "paging": false, "info": false });
    </script>
    {{ Html::script('assets/js/helper.js?v='.time()) }}
@stop
