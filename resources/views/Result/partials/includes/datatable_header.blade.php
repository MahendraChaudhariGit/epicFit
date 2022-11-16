
    <div class="col-xs-12 col-sm-4">
        @if(isset($extra))
            {!! $extra !!}
        @endif
    </div>
    <div class="col-480p-12 col-xs-7 col-sm-4">
        {!! Form::open(['url' => Request::url(), 'method' => 'get']) !!}
     <span class="search-bar-align">
            {!! Form::text('search', Request::get('search'), ['class'=>'search-wd','autofocus'=>'autofocus']) !!}
            {!! Form::submit('Search', ['class'=>'btn btn-primary btn-sm']) !!}
    </span>
    <span class="btn-sm-clear-new">
            @if(Request::get('search'))
            
                <a class="btn btn-primary btn-sm " href="{{ Request::url() }}">
                    Clear
                </a>
            @endif
    </span>
        {!! Form::close() !!}

    </div>
    <div class="col-480p-12 col-xs-5 col-sm-4 text-right"><!--select_val-->
        <label>
            Show
            <select class="mw-80 selectpicker" id="datatableLengthDd"> <!--name="client-datatable_length"-->
                <option value="10">10</option>
                <option value="25">25</option>
                <option value="50">50</option>
                <option value="100">100</option>
                <option value="1000000">All</option>
            </select>
            entries
        </label>
    </div>
