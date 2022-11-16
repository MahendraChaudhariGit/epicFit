@if($entity->count())
<div class="row paginationn">
    <div class="col-md-6 col-xs-12 col-sm-12">
        <p class="ftext">
            Showing {{ $entity->firstItem() }} to {{ $entity->lastItem() }} of {{ $entity->total() }} entries
        </p>
    </div>

   <div class="col-md-6 text-right col-xs-12 col-sm-12 col-sm-text">

        @if(Request::get('my-client') || Request::get('search') || Request::get('filter') || Request::get('tab'))
        {{ $entity->appends(['my-client' => Request::get('my-client'), 'search' => Request::get('search'),'filter' => Request::get('filter'),'tab' =>Request::get('tab')])->links() }}
        @else
        {{ $entity->appends(request()->all())->links() }}
        {{-- {{ $entity->links() }} --}}
        @endif
    </div>
    @else
    <div style="text-align: center;"> no records found !!</div> 

</div>

@endif