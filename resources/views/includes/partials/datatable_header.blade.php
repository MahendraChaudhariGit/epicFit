@php
$clientInvoicePaginateLength = isset($_COOKIE['clientInvoicePaginateLength']) ? (int)$_COOKIE['clientInvoicePaginateLength'] : null ;
@endphp
<style>
    .select-width{
        width:74% !important;
    }
    .search-clear-btn{
            margin: 0 !important;
    margin-left: 10px !important;
    }
</style>
    <div class="col-xs-12 col-sm-0 col-lg-4 for-tab">
        @if(isset($extra))
            {!! $extra !!}
        @endif
        @if(isset($source) && $source == 'meal')
        {!! Form::open(['url' => Request::url(), 'method' => 'get', 'class'=>'d-flex']) !!}
        <select name="filter" class="select-width search-height" style="<?php echo (Request::get('filter'))?'width:55%':'width:75%'; ?>">
            <option value="">--select--</option>
            @foreach($mealCategories as $mealCategory)
            <option value="{{$mealCategory->name}}" {{Request::get('filter') == $mealCategory->name?'selected':''}}>{{$mealCategory->name}}</option>
            @endforeach
        </select>
        {!! Form::submit('Filter', ['class'=>'btn btn-primary btn-sm search-submit-btn']) !!}
        @if(Request::get('filter'))
            <a class="btn btn-primary btn-sm search-clear-btn" href="{{ Request::url() }}">
                Clear
            </a>
        @endif
        {!! Form::close() !!}
        @endif
        @if(isset($source) && $source == 'actvity-video')
        {!! Form::open(['url' => Request::url(), 'method' => 'get']) !!}
        <select name="filter" class="select-width search-height" style="<?php echo (Request::get('filter'))?'width:55%':'width:77%'; ?>" onchange="javascript:$(this).closest('form').submit();">
            @foreach($abWorkouts as $key => $value)
            <option value="{{$key}}" {{Request::get('filter') == $key?'selected':''}}>{{$value}}</option>
            @endforeach
        </select>
        {!! Form::close() !!}
        @endif
    </div>
    
    @if(isset($source) && $source == 'client-profile-invoice')
        <div class="col-480p-12 col-xs-8 col-lg-4 col-sm-6">
        </div>
    @else
    <div class="col-480p-12 col-xs-8 col-lg-4 col-sm-6">
        {!! Form::open(['url' => Request::url(), 'method' => 'get', 'class'=>'d-flex']) !!}
        <input type="text" name="search" value="{{ Request::get('search') }}" autofocus="autofocus" class="search-wd search-height" style="<?php echo (Request::get('search'))?'width:55%':'width:75%'; ?>">
        <input type="hidden" name="my-client" value="{{ Request::get('my-client') }}">
        {!! Form::submit('Search', ['class'=>'btn btn-primary btn-sm search-submit-btn']) !!}
        @if(Request::get('search'))
            <a class="btn btn-primary btn-sm search-clear-btn" href="{{ Request::url() }}">
                Clear
            </a>
        @endif
        {!! Form::close() !!}
    </div>
    @endif

    <div class="col-480p-12 col-xs-4 col-sm-6 col-lg-4 text-right"><!--select_val-->
        <span>Show</span>
        @if(isset($source) && $source == 'client-profile-invoice')
            <select class="search-bar-select" id="datatableLengthDd"> <!--name="client-datatable_length"-->
                <option value="10" @if($clientInvoicePaginateLength && $clientInvoicePaginateLength == 10) selected @endif>10</option>
                <option value="25" @if($clientInvoicePaginateLength && $clientInvoicePaginateLength == 25) selected @endif>25</option>
                <option value="50" @if($clientInvoicePaginateLength && $clientInvoicePaginateLength == 50) selected @endif>50</option>
                <option value="100" @if($clientInvoicePaginateLength && $clientInvoicePaginateLength == 100) selected @endif>100</option>
                <option value="1000000" @if($clientInvoicePaginateLength && $clientInvoicePaginateLength == 1000000) selected @endif>All</option>
            </select>
        @else
           <select class="search-bar-select" id="datatableLengthDd"> <!--name="client-datatable_length"-->
                <option value="10">10</option>
                <option value="25">25</option>
                <option value="50">50</option>
                <option value="100">100</option>
                <option value="1000000">All</option>
            </select>
        @endif

        <span>entries</span> 
    </div>
