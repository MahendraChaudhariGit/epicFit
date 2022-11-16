@extends('administration.layouts.app')

@section('title')
@stop()

@section('meta')
@stop()

@section('before-styles-end')
@stop()

@section('required-styles-for-this-page')
@stop()

@section('content')
    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title">{{ trans('strings.backend.dashboard.welcome') }} {!! access()->user()->name !!}!</h3>
            <div class="box-tools pull-right">
                <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            </div><!-- /.box tools -->
        </div><!-- /.box-header -->
        <div class="box-body">
            <div>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Cupiditate, delectus fugit pariatur provident
                quibusdam quos similique sit tenetur voluptas voluptates! Accusamus adipisci consequatur error molestiae
                nostrum odio suscipit tempore voluptas.
            </div>
            <div>A accusantium adipisci aliquid atque aut eligendi excepturi fugiat laborum libero magni minima
                molestias nesciunt nihil nulla officia omnis, pariatur qui quis rem rerum sapiente, similique, soluta
                tempora unde voluptatum.
            </div>
            <div>Aut culpa dolores eos illo ipsa mollitia nesciunt officiis, repellendus saepe voluptatem. Culpa dolorem
                exercitationem, labore nam repellat voluptatibus? Ab assumenda deleniti doloribus eaque ipsum mollitia
                nam quis quo, voluptatum?
            </div>
            <div>Animi aspernatur assumenda beatae commodi consectetur cumque cupiditate dolore dolores exercitationem,
                incidunt iste itaque laborum natus nesciunt nostrum odio perspiciatis quidem recusandae reiciendis vitae
                voluptatem voluptates voluptatibus? Deleniti, quisquam, sed.
            </div>
            <div>Accusamus, autem consequuntur dolore dolorem expedita inventore ipsum magnam maiores non obcaecati,
                officia perferendis qui recusandae reprehenderit sed sequi similique suscipit. Commodi illo, maxime nisi
                non quaerat reiciendis? Autem, laboriosam!
            </div>
        </div><!-- /.box-body -->
    </div><!--box box-success-->
@stop()

@section('required-script-for-this-page')
@stop()

@section('script-handler-for-this-page')
@stop()
