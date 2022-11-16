@if(!isset($businessId))
    {!! Form::open(['url' => 'settings/product', 'id' => 'form-5', 'class' => 'margin-bottom-30', 'data-form-mode' => 'unison']) !!}
    {!! Form::hidden('businessId', null , ['class' => 'businessId no-clear']) !!}
    <div class="row">
        <div class="col-xs-12">
            <p class="margin-top-5 italic">This is a brief summary of the location of your venue or venues.</p>
        </div>
    </div>
    <div class="row margin-top-90">
@else
    @if(isset($product))
        {!! Form::model($product, ['method' => 'patch', 'route' => ['products.update', $product->id], 'id' => 'form-5', 'class' => 'margin-bottom-30', 'data-form-mode' => 'standAlone']) !!}
    @else
        {!! Form::open(['route' => ['products.store'], 'id' => 'form-5', 'class' => 'margin-bottom-30', 'data-form-mode' => 'standAlone']) !!}
    @endif
    {!! Form::hidden('businessId', $businessId , ['class' => 'businessId no-clear']) !!}
    <div class="row">
@endif
    <div class="sucMes hidden"></div>
    <div class="col-md-6">
        <fieldset class="padding-15">
            <legend>
                General
            </legend>
            <div class="form-group {{ $errors->has('name') ? 'has-error' : ''}}">
                {!! Form::label('name', 'Product Name *', ['class' => 'strong']) !!}
                <span class="epic-tooltip" data-toggle="tooltip" title="This is the name by which the product is identified"><i class="fa fa-question-circle"></i></span>
                <div>
                    {!! Form::text('name', null, ['class' => 'form-control', 'required' => 'required']) !!}
                    {!! $errors->first('name', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="form-group {{ $errors->has('sku_id') ? 'has-error' : ''}}">
                {!! Form::label('sku_id', 'SKU / Product ID *', ['class' => 'strong']) !!}
                <span class="epic-tooltip" data-toggle="tooltip" title="This is the product code that is used to stack take the specific product"><i class="fa fa-question-circle"></i></span>
                <div>
                    {!! Form::text('sku_id', null, ['class' => 'form-control', 'required' => 'required']) !!}
                    {!! $errors->first('sku_id', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="form-group {{ $errors->has('pro_slug') ? 'has-error' : ''}}">
                {!! Form::label('pro_slug', 'Product Slug', ['class' => 'strong']) !!}
                <span class="epic-tooltip" data-toggle="tooltip" title="This is the slug by which the product is identified uniquely"><i class="fa fa-question-circle"></i></span>
                <div>
                    {!! Form::hidden('old_pro_slug',isset($product)?$product->pro_slug:'') !!}
                    {!! Form::text('pro_slug', null, ['class' => 'form-control']) !!}
                    {!! $errors->first('pro_slug', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="form-group">
                {!! Form::label('pro_sub_category', 'Product Category *', ['class' => 'strong']) !!}
                <span class="epic-tooltip" data-toggle="tooltip" title="This is the product subcategory"><i class="fa fa-question-circle"></i></span>
                <a href="{{ route('product.getSubCat') }}" class="pull-right add-more-subcat" data-modal-title="Product Subcategories" data-field="productSubCat">Manage Categories</a>
                {!! Form::hidden('pro-cat-val',isset($parentCat)?json_encode($parentCat):'') !!}
                <div>
                    {!! Form::select('pro_category', isset($pro_cat)?$pro_cat:0, isset($pro_category)?$pro_category:0, ['class' => 'form-control category productSubCat', 'required' => 'required', 'multiple']) !!}
                    {!! $errors->first('pro_category', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="form-group {{ $errors->has('description') ? 'has-error' : ''}}">
                {!! Form::label('description', 'Product Description *', ['class' => 'strong']) !!}
                <span class="epic-tooltip" data-toggle="tooltip" title="This is a detailed description of the product"><i class="fa fa-question-circle"></i></span>
                <div>
                    {!! Form::textarea('description', null, ['class' => 'form-control ckeditor cutomck-validation', 'required' => 'required']) !!}
                    {!! $errors->first('description', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="form-group upload-group">
                {!! Form::label(null, 'Upload Product Image *', ['class' => 'strong']) !!}
                <span class="epic-tooltip" data-toggle="tooltip" title="This allows for images of the product to be uploaded"><i class="fa fa-question-circle"></i></span>
                <input type="hidden" name="prePhotoName" value="{{ isset($product)?$product->logo:'' }}" class="no-clear">
                <input type="hidden" name="imgSrcCls" value="productImagePreviewPics" class="no-clear">
                <input type="hidden" name="inputElem" value="productImage" class="no-clear">
                <input type="hidden" name="saveUrl" value="product/photo/save">
                <input type="hidden" name="entityId" value="{{ isset($product)?$product->id:'' }}">
                <div>
                    <label class="btn btn-primary btn-file">
                        <span><i class="fa fa-plus"></i> Select File</span> 
                        <input type="file" class="hidden" onChange="imageUploadHandel(this)" accept="image/*" data-type="product">
                    </label>
                    <div class="m-t-10">
                        @if(isset($product))
                        <img class="productImagePreviewPics previewImg" src="{{ url('/') }}/uploads/prod_11_{{ $product->logo }}" width="100px" height="100px"/>
                        @else
                        <img class="hidden productImagePreviewPics previewImg" width="100px" height="100px"/>
                        @endif
                    </div>
                </div>
                <span class="help-block m-b-0"></span>
                <input type="hidden" name="productImage" value="{{ isset($product)?$product->logo:'' }}">
            </div>
            
            <div class="form-group {{ $errors->has('sale_price') ? 'has-error' : ''}}">
                {!! Form::label('sale_price', 'Sale Price *', ['class' => 'strong']) !!}
                <span class="epic-tooltip" data-toggle="tooltip" title="This is the recommended retail price for the product"><i class="fa fa-question-circle"></i></span>
                <div>
                    {!! Form::text('sale_price', null, ['class' => 'form-control price-field', 'required' => 'required']) !!}
                    {!! $errors->first('sale_price', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="form-group {{ $errors->has('salesTax') ? 'has-error' : ''}}">
                {!! Form::label('salesTax', 'Tax *', ['class' => 'strong']) !!}
                <span class="epic-tooltip" data-toggle="tooltip" title="The indicates if the product includes or excludes tax"><i class="fa fa-question-circle"></i></span>
                <div>
                    {!! Form::select('salesTax', ['Excluding' => 'Excluding', 'Including' => 'Including', 'N/A' => 'N/A'], null, ['class' => 'form-control', 'required' => 'required','data-title'=>'-- Select --']) !!}
                    {!! $errors->first('salesTax', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            
        </fieldset>
    </div>
    <div class="col-md-6">
        <fieldset class="padding-15">
            <legend>
                Inventory
            </legend>
            <div class="form-group {{ $errors->has('stock_location') ? 'has-error' : ''}}">
                {!! Form::label('stock_location', 'Stock Location *', ['class' => 'strong']) !!}
                <span class="epic-tooltip" data-toggle="tooltip" title="This indicates which locations have this stock"><i class="fa fa-question-circle"></i></span>
                <a href="#" class="pull-right callSubview" data-target-subview="location">+ Add New Location</a>
                <div>
                    {!! Form::select('stock_location', isset($businessId)?$locs:['' => '-- Select --'], null, ['class' => 'form-control location', 'required'=>'required']) !!}
                    {!! $errors->first('stock_location', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="form-group">
                {!! Form::hidden('stockLevel', isset($product)?$product->stock_level:'0', ['class' => 'no-clear']) !!}
                {!! Form::hidden('stockLevelHistory', !isset($product)?'decrease,'.\Carbon\Carbon::now().',Units were reset to zero.':null) !!}
                <div class="clearfix">
                    <div class="pull-left">
                        {!! Form::label('stock_level', 'Edit Stock Levels *', ['class' => 'strong']) !!}
                        <span class="epic-tooltip" data-toggle="tooltip" title="This indicated the stock levels that are available at each specific locations"><i class="fa fa-question-circle"></i></span>
                    </div>
                    <div class="pull-right">
                        <span id="stockLevelUi">{{ isset($product)?$product->stock_level:'0' }}</span>
                        <a tabindex="0" class="btn btn-sm btn-default" data-content="<div class='row'><div class='col-md-6'><div class='form-group'><label class='strong' for='amount'>Add</label><div><input id='amount' class='form-control numericField' type='number' name='amount' min='1'></div></div></div><div class='col-md-6'><div class='form-group'><label class='strong' for='reason'>Reason</label><div><select id='reason' class='form-control' name='reason'><option value='New stock'>New stock</option><option value='Return'>Return</option><option value='Transfer'>Transfer</option><option value='Adjustment'>Adjustment</option><option value='Other'>Other</option></select></div></div></div></div><div class='clearfix'><a href='#' class='pull-left toggleStock' data-toggle-value='Unlimited'>Make unlimited</a><button class='btn btn-primary btn-sm m-l-10 pull-right submitStock' type='button' data-action='add'> Add </button><button class='btn btn-primary btn-sm btn-o pull-right closePopup' type='button'> Cancel </button></div>" data-placement="top" data-html="true" data-toggle="popover">
                            <i class="fa fa-plus"></i>
                        </a>
                        <a tabindex="0" class="btn btn-sm btn-default" data-content="<div class='row'><div class='col-md-6'><div class='form-group'><label class='strong' for='amount'>Remove</label><div><input id='amount' class='form-control numericField' type='number' name='amount' min='1'></div></div></div><div class='col-md-6'><div class='form-group'><label class='strong' for='reason'>Reason</label><div><select id='reason' class='form-control' name='reason'><option value='Damaged'>Damaged</option><option value='Out of date'>Out of date</option><option value='Sold'>Sold</option><option value='Removed'>Removed</option><option value='Adjustment'>Adjustment</option><option value='Other'>Other</option></select></div></div></div></div><div class='clearfix'><a href='#' class='pull-left toggleStock' data-toggle-value='0'>Reset to zero</a><button class='btn btn-primary btn-sm m-l-10 pull-right submitStock' type='button' data-action='remove'> Remove </button><button class='btn btn-primary btn-sm btn-o pull-right closePopup' type='button'> Cancel </button></div>" data-placement="top" data-html="true" data-toggle="popover">
                            <i class="fa fa-minus"></i>
                        </a>
                    </div>
                </div>
                <div class="stockHelperFields {{ !isset($product) || $product->stock_level != 'Unlimited'?'':'hidden' }}">
                    <div class="checkbox clip-check check-primary m-b-0">
                        <input type="checkbox" name="if_ofs_sale" id="if_ofs_sale" value="1" {{ isset($product) && $product->if_ofs_sale?'checked':'' }}>
                        <label for="if_ofs_sale">
                            <strong>Allow product to be sold even when out of stock?</strong> <span class="epic-tooltip" data-toggle="tooltip" title="If your stock level falls to zero this product will still be available and the stock will reduce to a negative value."><i class="fa fa-question-circle"></i></span>
                        </label>
                    </div>
                    <div class="checkbox clip-check check-primary m-b-0 m-t-5">
                        <input type="checkbox" name="if_stock_alert" id="if_stock_alert" value="1" {{ isset($product) && $product->if_stock_alert?'checked':'' }}>
                        <label for="if_stock_alert" class="m-r-0">
                            <strong>Send emails when available stock reaches</strong> 
                        </label>
                        {!! Form::number('stock_alert', null, ['class' => 'mw-80 numericField', 'min' => 0]) !!}
                        <span class="epic-tooltip" data-toggle="tooltip" title="If your available stock level reaches the alert level set above an email will be sent to the main account holder. Even when this setting is disabled in-app notifications are still displayed."><i class="fa fa-question-circle"></i></span>
                    </div>
                </div>
            </div>
            
            <div class="form-group {{ $errors->has('expirey_date') ? 'has-error' : ''}}">
                {!! Form::label('expirey_date', 'Expiry date', ['class' => 'strong']) !!}
                <span class="epic-tooltip" data-toggle="tooltip" title="The indicates if the product expiry date"><i class="fa fa-question-circle"></i></span>
                <div>

                    {!! Form::text('expirey_date', isset($product) && ($product->expirey_date != null)? dbDateToDateString(\Carbon\Carbon::parse($product->expirey_date)):'', ['class' => 'form-control datepicker onchange-set-neutral','autocomplete'=>'off', 'id'=>'proExpireyDate']) !!}
                    {!! $errors->first('expirey_date', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="form-group {{ $errors->has('product_supplier') ? 'has-error' : ''}} ">
                {!! Form::label('product_supplier', 'Supplier ', ['class' => 'strong']) !!}
                <span class="epic-tooltip" data-toggle="tooltip" title="The indicates if the product supplier"><i class="fa fa-question-circle"></i></span>
                <a href="#" class="pull-right callSubview" data-target-subview="contact">+ Add New Contact</a>
                <div>
                     {!! Form::text(null, isset($supplierName)?$supplierName:'', ['class' => 'form-control proContact', 'autocomplete' => 'off']) !!}
                     {!! Form::hidden('proId', isset($product)?$product->contact_id:'') !!}
                    {!! $errors->first('product_supplier', '<p class="help-block">:message</p>') !!}
                </div>

                <!-- <div class="hidden clone-class">
                     {!! Form::text(null, null, ['class' => 'form-control ', 'autocomplete' => 'off']) !!}
                </div>
                {!! Form::hidden('proId', isset($product)?$product->contact_id:'') !!}
                {!! $errors->first('product_supplier', '<p class="help-block">:message</p>') !!} -->
            </div>
            <div class="form-group {{ $errors->has('cost_price') ? 'has-error' : ''}}">
                {!! Form::label('cost_price', 'Cost Price *', ['class' => 'strong']) !!}
                <span class="epic-tooltip" data-toggle="tooltip" title="This is the cost price of the item including all fees"><i class="fa fa-question-circle"></i></span>
                <div>
                    {!! Form::text('cost_price', null, ['class' => 'form-control price-field', 'required' => 'required']) !!}
                    {!! $errors->first('cost_price', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="form-group {{ $errors->has('tax') ? 'has-error' : ''}}">
                {!! Form::label('tax', 'Tax *', ['class' => 'strong']) !!}
                <span class="epic-tooltip" data-toggle="tooltip" title="The indicates if the product includes or excludes tax"><i class="fa fa-question-circle"></i></span>
                <div>
                    {!! Form::select('tax', ['Excluding' => 'Excluding', 'Including' => 'Including', 'N/A' => 'N/A'], null, ['class' => 'form-control', 'required' => 'required','data-title'=>'-- Select --']) !!}
                    {!! $errors->first('tax', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="form-group {{ $errors->has('stock_note') ? 'has-error' : ''}}">
                {!! Form::label('stock_note', 'Stock Note', ['class' => 'strong']) !!}
                <span class="epic-tooltip" data-toggle="tooltip" title="This is notes relating to the product that may be relevant at a later date."><i class="fa fa-question-circle"></i></span>
                <div>
                    {!! Form::textarea('stock_note', null, ['class' => 'form-control']) !!}
                    {!! $errors->first('stock_note', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="form-group {{ $errors->has('featured') ? 'has-error' : ''}}">
                <div class="checkbox clip-check check-primary m-b-0">
                    <input type="checkbox" name="featured" id="featured_product" value="1" <?php if(isset($product) && $product->featured==1)echo "checked"; else echo ""; ?> >
                    <label for="featured_product">
                        <strong>Add product as featured product</strong> <span class="epic-tooltip" data-toggle="tooltip" title="Check if product is featurd type."><i class="fa fa-question-circle"></i></span>
                    </label>
                    {!! $errors->first('featured', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
        </fieldset>
        <fieldset class="padding-15">
            <legend>Appearance</legend>
            <div class="form-group {{ $errors->has('pro_size') ? 'has-error' : ''}}">
                {!! Form::label('pro_size', 'Size', ['class' => 'strong']) !!}
                <span class="epic-tooltip" data-toggle="tooltip" title="The indicates product size"><i class="fa fa-question-circle"></i></span>
                <a href="{{ route('product.getSize') }}" class="pull-right add-more" data-modal-title="Product size" data-field="prodSize">Manage Size</a>
                <div>
                    {!! Form::select('pro_size', isset($proSize)?$proSize:0, isset($productSize)?$productSize:0, ['class' => 'form-control category prodSize','multiple']) !!}
                    {!! $errors->first('pro_size', '<p class="help-block">:message</p>') !!} 
                </div>
            </div>
            <div class="form-group">
                <div class="checkbox clip-check check-primary m-b-0">
                    <input type="checkbox" name="pro_color_check" id="product_color_checkbox" value="1" <?php if(isset($product) && $product->pro_color_check==1)echo "checked"; else echo ""; ?> >
                    <label for="product_color_checkbox">
                        <strong>Set product color</strong> <span class="epic-tooltip" data-toggle="tooltip" title="Check if you want to choose product color."><i class="fa fa-question-circle"></i></span>
                    </label>
                </div>
            </div>
            <div class="form-group {{ $errors->has('pro_color') ? 'has-error' : ''}}" id="product_color_field" style="display:<?php if(isset($product) && $product->pro_color_check==1)echo "block"; else echo "none"; ?>">
                {!! Form::label('pro_color', 'Color', ['class' => 'strong']) !!}
                <span class="epic-tooltip" data-toggle="tooltip" title="The indicates product color"><i class="fa fa-question-circle"></i></span>
                <div>
                    {!! Form::color('pro_color', null, ['class' => 'form-control']) !!}
                    {!! $errors->first('pro_color', '<p class="help-block">:message</p>') !!} 
                </div>
            </div>
        </fieldset>
    </div>
    @if(isset($product) && $stockHistories->count())
        <div class="col-md-12">
            <fieldset class="padding-15">
                <legend>
                    Stock Level History
                </legend>
                @foreach($stockHistories as $stockHistory)
                    <div class="m-t-20">
                        <div class="font-15">
                            @if($stockHistory->psh_type == 'decrease')
                                <span class="label label-warning">Decrease</span>
                            @else
                                <span class="label label-success">Increase</span>
                            @endif
                            {{ setLocalToBusinessTimeZone($stockHistory->created_at, 'dateTimeString') }}
                            - by {{ $stockHistory->name }}
                        </div>
                        <p class="m-t-10">
                            {{ $stockHistory->psh_text }}
                        </p>
                    </div>
                @endforeach
            </fieldset>
        </div>
    @endif
</div>
@if(!isset($businessId))
    <div class="row">
        <div class="col-sm-6">
            <div class="form-group">
                <button class="btn btn-primary btn-o back-step btn-wide pull-left">
                    <i class="fa fa-circle-arrow-left"></i> Back
                </button>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <button class="btn btn-primary btn-o next-step btn-wide pull-right">
                    Next <i class="fa fa-arrow-circle-right"></i>
                </button>
                <button class="btn btn-primary btn-wide pull-right margin-right-15 btn-add-more-form">
                    <i class="fa fa-plus"></i> Add Product
                </button>
                <button type="button" class="btn btn-primary btn-wide pull-right margin-right-15 skipnextbutton skipbutton hidden">
                    Skip to next
                </button>
            </div>
        </div>
    </div>
@else
    <div class="row">
        <div class="col-sm-12">
            <div class="form-group">
                <button class="btn btn-primary btn-wide pull-right btn-add-more-form">
                    @if(isset($product))
                        <i class="fa fa-edit"></i> Update Product
                    @else
                        <i class="fa fa-plus"></i> Add Product
                    @endif
                </button>
            </div>
        </div>
    </div>
@endif
{!! Form::close() !!}

