<div class="row">
    <div class="col-xs-12 col-sm-6 col-md-6">
        <div class="page-header">
            <h1>
                Menu Settings
            </h1>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        @if(count($allMenuOptions))
        <form action="{{ url('clients/'.$clientId.'/menues') }}" method="POST">
            <input name="_token" type="hidden" value="{{ csrf_token() }}">
                <div>
                    @foreach($allMenuOptions as $menu)
                    <div class="checkbox clip-check check-primary m-b-0">
                        <input class="js-ifCreateLogin" id="{{ $menu->menu_value }}" value="{{ $menu->menu_value }}" name="menuOptions[]" type="checkbox" value="1" @if(in_array($menu->menu_value, $selectedMenuOptions)) checked @endif>
                            <label for="{{ $menu->menu_value }}">
                                <strong>
                                    {{ ucwords($menu->menu_name) }}
                                </strong>
                            </label>
                        </input>
                    </div>
                    <br>
                    @endforeach
                </div>
                <button class="btn btn-primary btn-wide margin-right-15 btn-add-more-form" type="submit">
                    Submit
                </button>
            </input>
        </form>
    </div>
</div>
@endif
