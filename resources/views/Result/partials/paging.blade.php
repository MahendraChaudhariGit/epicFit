@if($entity->count())
    <div class="row">
        <div class="col-md-6">
            <p class="m-t-20">
                Showing {{ $entity->firstItem() }} to {{ $entity->lastItem() }} of {{ $entity->total() }} entries
            </p>
        </div>
        <div class="col-md-6 text-right">
            @if(Request::get('search'))
                {{ $entity->appends(['search' => Request::get('search')])->links() }}
            @else
                {{ $entity->links() }}
            @endif
        </div>
    </div>
@endif