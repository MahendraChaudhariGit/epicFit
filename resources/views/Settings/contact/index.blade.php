@extends('blank')

@section('page-title')
    Contact list
    @if(isUserType(['Admin']) && Auth::user()->hasPermission(Auth::user(), 'create-contact'))
        <a class="btn btn-primary pull-right" href="{{ route('contacts.create') }}"><i class="ti-plus"></i> Add Contact</a>
    @endif
@stop

@section('content')
{!! displayAlert()!!}

@if(isUserType(['Admin']) && Auth::user()->hasPermission(Auth::user(), 'delete-contact'))
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
            <table class="table table-striped table-bordered table-hover m-t-10" id="contact-datatable">
                <thead>
                    <tr>
                        <th>Full Name</th>
                        <th>Company Name</th>
                        <th class="">Contact Details</th>
                        <th class="center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($allContacts as $contacts)
                    <tr>
                        <td>
                            <a href="{{ route('contacts.show', $contacts->id) }}">{{ $contacts->contact_name ?? 'Default' }}</a> <!--url('contact/'.$contacts->id)-->
                        </td>
                        <td>
                            {{ $contacts->company_name ?? 'Default' }}
                        </td>
                        <td class="">
                            <a href="mailto:{{ $contacts->email ?? '' }}" style="color:#ff4401;">{{ $contacts->email ?? '' }}</a>
                            <br>
                            <a href="tel:{{ $contacts->phone ?? '' }}">{{ $contacts->phone ?? '' }}</a>
                        </td>
                        <td class="center">
                            <div>
                                @if(isUserType(['Admin']) && Auth::user()->hasPermission(Auth::user(), 'view-contact'))
                                <a href="{{ route('contacts.show', $contacts->id) }}" class="btn btn-xs btn-default tooltips" data-placement="top" data-original-title="View" ><i class="fa fa-share text-primary"></i></a><!--{{ url('contact/'.$contacts->id) }}-->
                                @endif

                                @if(isUserType(['Admin']) && Auth::user()->hasPermission(Auth::user(), 'edit-contact'))
                                <a class="btn btn-xs btn-default tooltips" href="{{ route('contacts.edit', $contacts->id) }}" data-placement="top" data-original-title="Edit">
                                    <i class="fa fa-pencil text-primary"></i>
                                </a>
                                @endif

                                @if(isUserType(['Admin']) && Auth::user()->hasPermission(Auth::user(), 'delete-contact'))
                                    <a class="btn btn-xs btn-default tooltips delLink" href="{{ route('contacts.destroy', $contacts->id) }}" data-placement="top" data-original-title="Delete" data-entity="contact">
                                        <i class="fa fa-trash-o text-primary"></i>
                                    </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach 
                </tbody>
            </table>
            <!-- start: Paging Links -->
            @include('includes.partials.paging', ['entity' => $allContacts])
            <!-- end: Paging Links -->
        </div>
    </div>

@stop
@section('script')
<script>
var cookieSlug = "contact";
    $.fn.dataTable.moment('ddd, D MMM YYYY');
    $('#contact-datatable').dataTable({"searching": false, "paging": false, "info": false });
</script>
{!! Html::script('assets/js/helper.js') !!}
@stop