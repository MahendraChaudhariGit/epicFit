@extends('blank')

@section('page-title')
    Single Phase Library Program list
    @if(isUserType(['Admin']) && Auth::user()->hasPermission(Auth::user(), 'create-library-program'))  
        <a class="btn btn-primary pull-right" href="{{ route('libraryprogram.create') }}"><i class="ti-plus"></i> Add Single Phase Library Program</a>
    @endif
@stop

@section('content')
    {!! displayAlert()!!}

    @if(isUserType(['Admin']) && Auth::user()->hasPermission(Auth::user(), 'delete-library-program')) 
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
                <table class="table table-striped table-bordered table-hover m-t-10" id="libPro-datatable">
                    <thead>
                        <tr>
                            <th class="center mw-70 w70">Image</th>
                            <th>Name</th>
                            <th class="center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(isUserType(['Admin']) && Auth::user()->hasPermission(Auth::user(), 'list-library-program'))
                        @foreach($libraryPrograms as $libraryProgram)
                        <tr>
                            <td class="center mw-70 mh-70">
                            <img src="{{ dpSrc($libraryProgram->image) }}"  class="mw-50 mh-50" />
                            </td>
                            <td>
                            {{ $libraryProgram->name }}
                            </td>
                            <td class="center">
                                <div>
                                    <!-- <a href="" class="btn btn-xs btn-default tooltips" data-placement="top" data-original-title="View" ><i class="fa fa-share text-primary" ></i></a> -->

                                    @if(isUserType(['Admin']) && Auth::user()->hasPermission(Auth::user(), 'edit-library-program'))
                                    <a class="btn btn-xs btn-default tooltips" href="{{ route('libraryprogram.edit', $libraryProgram->id) }}" data-placement="top" data-original-title="Edit">
                                        <i class="fa fa-pencil text-primary"></i>
                                    </a>
                                    @endif
                                    @if(isUserType(['Admin']) && Auth::user()->hasPermission(Auth::user(), 'delete-library-program'))
                                    <a class="btn btn-xs btn-default tooltips delLink" href="{{ route('libraryprogram.destroy', $libraryProgram->id) }}" data-placement="top" data-original-title="Delete" data-entity="exercise">
                                        <i class="fa fa-trash-o text-primary"></i>
                                    </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach 
                        @endif 
                    </tbody>
                </table> 
                <!-- start: Paging Links -->
                    @include('includes.partials.paging', ['entity' => $libraryPrograms]) 
                <!-- end: Paging Links -->
            <!--</div>-->
        </div>
    </div>
@stop

@section('script')
    <script>
    var cookieSlug = "libraryPrograms";
    $.fn.dataTable.moment('ddd, D MMM YYYY');
    $('#libPro-datatable').dataTable({"searching": false, "paging": false, "info": false });
    </script>
    {!! Html::script('assets/js/helper.js?v='.time()) !!}
@stop