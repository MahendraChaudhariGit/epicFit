@extends('blank')

@section('page-title')
    Product list 
    @if(isUserType(['Admin']) && Auth::user()->hasPermission(Auth::user(), 'create-product'))
        <a class="btn btn-primary pull-right" href="{{ route('products.create') }}"><i class="ti-plus"></i> Add Product</a>
    @endif
@stop

@section('content')
{!! displayAlert()!!}

@if(isUserType(['Admin']) && Auth::user()->hasPermission(Auth::user(), 'delete-product'))
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
                <table class="table table-striped table-bordered table-hover m-t-10" id="product-datatable">
                    <thead>
                        <tr>
                            <th class="center mw-70 w70">Logo</th>
                            <th>Product Name</th>
                            <th class="">SKU / Product ID</th>
                            <th class="">Sale Price</th>
                            <th class="">Stock</th>
                            <th class="center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($allProducts as $products)
                        <tr>
                            <td class="center mw-70 w70">
                            	<a href="{{ route('products.show', $products->id) }}"><!--url('product/'.$products->id)-->
                                	<img src="{{ url('/') }}/uploads/prod_11_{{ $products->logo }}" alt="{{ $products->name}}" class="mw-50 mh-50">
                                </a>
                            </td>
                            <td>
                            <a href="{{ route('products.show', $products->id) }}">{{ $products->name ?? '' }} </a> <br><!--url('product/'.$products->id)-->
                            </td>
                            <td class="">
                                <a href="{{ route('products.show', $products->id) }}">{{ $products->sku_id ?? '' }}</a><!--url('product/'.$products->id)-->
                          </td>
                          <td class="">
                                ${{ $products->sale_price ?? '' }}
                          </td>
                          <td class="">
                                {{ $products->stock_level ?? '' }}
                          </td>
                            <td class="center">
                                <div>
                                    @if(isUserType(['Admin']) && Auth::user()->hasPermission(Auth::user(), 'view-product'))
                                    <a href="{{ route('products.show', $products->id) }}" class="btn btn-xs btn-default tooltips" data-placement="top" data-original-title="View" ><i class="fa fa-share text-primary" ></i></a><!--{{ url('product/'.$products->id) }}-->
                                    @endif

                                    @if(isUserType(['Admin']) && Auth::user()->hasPermission(Auth::user(), 'edit-product'))
                                    <a class="btn btn-xs btn-default tooltips" href="{{ route('products.edit', $products->id) }}" data-placement="top" data-original-title="Edit">
                                        <i class="fa fa-pencil text-primary"></i>
                                    </a>
                                    @endif

                                    @if(isUserType(['Admin']) && Auth::user()->hasPermission(Auth::user(), 'edit-product'))
                                    <a class="btn btn-xs btn-default tooltips" href="{{ route('products.clone', $products->id) }}" data-placement="top" data-original-title="Clone">
                                        <i class="fa fa-copy text-primary" ></i>
                                    </a>
                                    @endif
                                    
                                    @if(isUserType(['Admin']) && Auth::user()->hasPermission(Auth::user(), 'delete-product'))
                                        <a class="btn btn-xs btn-default tooltips delLink" href="{{ route('products.destroy', $products->id) }}" data-placement="top" data-original-title="Delete" data-entity="product">
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
                @include('includes.partials.paging', ['entity' => $allProducts])
                <!-- end: Paging Links -->
            <!--</div>-->
        </div>
    </div>
@stop
@section('script')
<script>
var cookieSlug = "product";
    $.fn.dataTable.moment('ddd, D MMM YYYY');
    $('#product-datatable').dataTable({"searching": false, "paging": false, "info": false });
</script>
{!! Html::script('assets/js/helper.js?v='.time()) !!}
@stop