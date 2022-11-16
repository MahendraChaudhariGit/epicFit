
@if(!isset($businessId))
{!! Form::open(['url' => '', 'id' => 'form-4', 'class' => 'margin-bottom-30', 'data-form-mode' => 'unison']) !!}
{!! Form::hidden('businessId', null , ['class' => 'businessId no-clear']) !!}
<div class="row">
    <div class="col-xs-12">
        <p class="margin-top-5 italic">This is a brief summary of the location of your venue or venues.</p>
    </div>
</div>
<div class="row margin-top-90">
    @else
    @if(isset($service))
    {!! Form::model($service, ['method' => 'patch', 'route' => ['services.update', $service->id], 'id' => 'form-4', 'class' => 'margin-bottom-30', 'data-form-mode' => 'standAlone']) !!}
    @elseif(isset($class))
    {!! Form::model($class, ['method' => 'patch', 'route' => ['classes.update', $class->cl_id], 'id' => 'form-4', 'class' => 'margin-bottom-30', 'data-form-mode' => 'standAlone']) !!}
    @elseif(isset($entityType))
    @if($entityType == 'service')
    {!! Form::open(['route' => ['services.store'], 'id' => 'form-4', 'class' => 'margin-bottom-30', 'data-form-mode' => 'standAlone']) !!}
    @elseif($entityType == 'class')
    {!! Form::open(['route' => ['classes.store'], 'id' => 'form-4', 'class' => 'margin-bottom-30', 'data-form-mode' => 'standAlone']) !!}
    @endif
    @endif
    {!! Form::hidden('businessId', $businessId , ['class' => 'businessId no-clear']) !!}
    <div class="row">
        @endif
        {!! Form::hidden('catText') !!}
        {!! Form::hidden('effectiveDate') !!}
        <div class="sucMes hidden"></div>
        <div class="col-md-6">
            <fieldset class="padding-15">
                <legend>
                    General
                </legend>
                <div class="row">
                    <ul class="form-type-selectable">
                        @if(!isset($businessId) || isset($service) || (isset($entityType) && $entityType == 'service'))
                        <li class="col-xs-6 ui-widget-content ui-selected">Service</li>
                        @endif
                        @if(!isset($businessId) || isset($class) || (isset($entityType) && $entityType == 'class'))
                        <li class="col-xs-6 ui-widget-content {{ isset($class) || (isset($entityType) && $entityType == 'class')?'ui-selected':'' }}">Class</li>
                        @endif
                        {!! Form::hidden('form_type_opt', null) !!}
                    </ul>
                </div>
                @if(!isset($businessId) || isset($class) || (isset($entityType) && $entityType == 'class'))
                <div class="form-type-class-fields">
                    <div class="form-group {{ $errors->has('classCat') ? 'has-error' : ''}}">
                        {!! Form::label('classCat', 'Category *', ['class' => 'strong']) !!}
                        <span class="epic-tooltip" data-toggle="tooltip" title="This relates to the category"><i class="fa fa-question-circle"></i></span>
                        <a href="{{ route('class.getCat') }}" class="pull-right add-more" data-modal-title="Class Categories" data-field="classCat">Manage Categories</a>
                        <!-- <a href="#" class="pull-right btn-add-more">+ Add New Category</a> -->
                        {!! Form::hidden('btn-add-more-action', 'class/cat/create', ['class' => 'no-clear']) !!}
                        <div>
                            
                            {{-- {!! Form::select('classCat', (isset($entityType) && $entityType == 'class') || isset($class)?$clsCat:['' => '-- Select --'], isset($class)?$class->cl_clcat_id:null, ['class' => 'form-control classCat', 'required' => 'required']) !!} --}}
                             
                            <select name="classCat" class="form-control classCat" title="-- Select --" required>
                           
                            @foreach ($clsCat as $key =>$value)
                            <option value="{{$key}}" data-price="{{ $value['price']}}" {{isset($class) && $class->cl_clcat_id == $key ?'selected':''}}>{{ $value['name']}}</option>  
                            @endforeach  
                            </select>
                            {!! $errors->first('classCat', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('classLoc') ? 'has-error' : ''}}">
                        {!! Form::label('classLoc', 'Location *', ['class' => 'strong']) !!}
                        <span class="epic-tooltip" data-toggle="tooltip" title="This relates to the location that these specific classes are offered"><i class="fa fa-question-circle"></i></span>
                        @if(!isset($subview))
                        <a href="#" class="pull-right callSubview" data-target-subview="location">+ Add New Location</a>
                        @endif
                        <div>
                            {!! Form::select('classLoc', (isset($entityType) && $entityType == 'class') || isset($class)?$locs:['' => '-- Select --'], isset($class)?$class->cl_location_id:null, ['class' => 'form-control location', 'required'=>'required']) !!}
                            {!! $errors->first('classLoc', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('classAreas', 'Area *', ['class' => 'strong']) !!}
                        <span class="epic-tooltip" data-toggle="tooltip" title="This relates to the areas that these specific classes are offered"><i class="fa fa-question-circle"></i></span>
                        <div>
                            {!! Form::select('classAreas', ['' => '-- Select --'], null, ['class' => 'form-control area', 'required', 'multiple', 'data-selected' => isset($class) && count($classAreas)?implode(',', $classAreas):'']) !!}
                            {!! $errors->first('classAreas', '<p class="help-block">:message</p>') !!}
                        </div>
                        <div class="checkbox clip-check check-primary m-b-0 m-t-5">
                            <input type="checkbox" id="classAreasAll" class="selAllDd">
                            <label for="classAreasAll" class="no-error-label">
                                <strong>Select All</strong>
                            </label>
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('className') ? 'has-error' : ''}}">
                        {!! Form::label('className', 'Class Name *', ['class' => 'strong']) !!}
                        <span class="epic-tooltip" data-toggle="tooltip" title="This is the name of the specific class"><i class="fa fa-question-circle"></i></span>
                        <div>
                            {!! Form::text('className', isset($class)?$class->cl_name:null, ['class' => 'form-control', 'required' => 'required']) !!}
                            {!! $errors->first('className', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('classDescription') ? 'has-error' : ''}}">
                        {!! Form::label('classDescription', 'Class Description *', ['class' => 'strong']) !!}
                        <span class="epic-tooltip" data-toggle="tooltip" title="This describes the class and what the class offers"><i class="fa fa-question-circle"></i></span>
                        <div>
                            {!! Form::textarea('classDescription', isset($class)?$class->cl_description:null, ['class' => 'form-control', 'required' => 'required']) !!}
                            {!! $errors->first('classDescription', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                    <div class="form-group upload-group">
                        {!! Form::label(null, 'Class Logo', ['class' => 'strong']) !!}<!--Trainers profile picture-->
                        <span class="epic-tooltip" data-toggle="tooltip" title="This allows for a identification logo to be inputted allowing it to be displayed on the calendar"><i class="fa fa-question-circle"></i></span>
                        <input type="hidden" name="prePhotoName" value="{{ isset($class)?$class->cl_logo:'' }}" class="no-clear">
                        <input type="hidden" name="entityId" value="" class="no-clear">
                        <input type="hidden" name="saveUrl" value="" class="no-clear">
                        <input type="hidden" name="photoHelper" value="classLogo" class="no-clear">
                        <input type="hidden" name="cropSelector" value="square">
                        <div>
                            <label class="btn btn-primary btn-file">
                                <span><i class="fa fa-plus"></i> Select File</span> <input type="file" class="hidden" onChange="fileSelectHandler(this)" accept="image/*">
                            </label>
                            <div class="m-t-10">
                                @if(isset($class) && $class->cl_logo)
                                <img class="classLogoPreviewPics previewPics removePic" src="{{ dpSrc($class->cl_logo) }}" />
                                <span class="removePicBtn" style="display: none;" data-entity-id="{{ isset($class)?$class->cl_id:'' }}" data-entity="class">Remove</span>
                                @else
                                <img class="hidden classLogoPreviewPics previewPics removePic" />
                                <span class="removePicBtn" style="display: none;" data-entity-id="{{ isset($class)?$class->cl_id:'' }}" data-entity="class">Remove</span>
                                @endif
                            </div>
                        </div>
                        <input class="photoName" type="hidden" name="classLogo" value="{{ isset($class) && $class->cl_logo?$class->cl_logo:'' }}">
                    </div>
                    <div class="form-group {{ $errors->has('classColour') ? 'has-error' : ''}}">
                        {!! Form::label('classColour', 'Class Colour *', ['class' => 'strong']) !!}
                        <span class="epic-tooltip" data-toggle="tooltip" title="This allows for the colour customization to easily identify classes on the calendar"><i class="fa fa-question-circle"></i></span>
                        <div>
                            {!! Form::color('classColour', isset($class)?$class->cl_colour:null, ['class' => 'form-control', 'required' => 'required']) !!}
                            {!! $errors->first('classColour', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('bookOnline') ? 'has-error' : ''}}">
                        <div class="checkbox clip-check check-primary m-b-0">
                            <input type="checkbox" name="bookOnline" id="bookOnline" value="1" {{ isset($class) && $class->cl_book_online?'checked':'' }}>
                            <label for="bookOnline">
                                <strong>Can client book this class online</strong> <span class="epic-tooltip" data-toggle="tooltip" title="This allows clients to book this class from the online hub"><i class="fa fa-question-circle"></i></span>
                            </label>
                            {!! $errors->first('bookOnline', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('classDuration') ? 'has-error' : ''}}">
                        {!! Form::label('classDuration', 'Duration *', ['class' => 'strong']) !!}
                        <span class="epic-tooltip" data-toggle="tooltip" title="This indicates the time that is allocated to the specific class"><i class="fa fa-question-circle"></i></span>
                        <div>
                            {!! Form::select('classDuration', ['' => '-- Select --', '5' => '5 min', '10' => '10 min', '15' => '15 min', '20' => '20 min', '25' => '25 min', '30' => '30 min', '35' => '35 min', '40' => '40 min', '45' => '45 min', '50' => '50 min', '55' => '55 min', '60' => '60 min'], isset($class)?$class->cl_duration:null, ['class' => 'form-control', 'required' => 'required']) !!}
                            {!! $errors->first('classDuration', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('classCapacity') ? 'has-error' : ''}}">
                        {!! Form::label('classCapacity', 'Capacity *', ['class' => 'strong']) !!}
                        <span class="epic-tooltip" data-toggle="tooltip" title="This limits the amount of clients who are able to partake in this class at one time"><i class="fa fa-question-circle"></i></span>
                        <div>
                            {!! Form::number('classCapacity', isset($class)?$class->cl_capacity:null, ['class' => 'form-control numericField', 'required' => 'required', 'min' => 1]) !!}
                            {!! $errors->first('classCapacity', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('classStaffs') ? 'has-error' : ''}}">
                        {!! Form::label('classStaffs', 'Staff Selection', ['class' => 'strong']) !!}
                        <span class="epic-tooltip" data-toggle="tooltip" title="This displays a list of trainers or staff that are able to deliver the class"><i class="fa fa-question-circle"></i></span>
                        @if(!isset($subview))
                        <a href="#" class="pull-right callSubview" data-target-subview="staff">+ Add New Staff</a>
                        @endif
                        <div>
                            {!! Form::select('classStaffs', [], null, ['class' => 'form-control staff classStaff', 'multiple', 'data-selected' => isset($class) && count($classStaffs)?implode(',', $classStaffs):'']) !!}
                            {!! $errors->first('classStaffs', '<p class="help-block">:message</p>') !!}
                        </div>
                        <div class="checkbox clip-check check-primary m-b-0 m-t-5">
                            <input type="checkbox" id="classStaffsAll" class="selAllDd">
                            <label for="classStaffsAll" class="no-error-label">
                                <strong>Select All</strong>
                            </label>
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('classPrice') ? 'has-error' : ''}}">
                        {!! Form::label('classPrice', 'Price *', ['class' => 'strong']) !!}
                        <span class="epic-tooltip" data-toggle="tooltip" title="This is the price charged for the class"><i class="fa fa-question-circle"></i></span>
                        {!! Form::hidden('classPrice_hidden', isset($class)?$class->cl_price:null) !!}
                        <div>
                            {!! Form::text('classPrice', isset($class)?$class->cl_price:null, ['class' => 'form-control price-field', 'required' => 'required']) !!}
                            {!! $errors->first('classPrice', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('classTax') ? 'has-error' : ''}}">
                        {!! Form::label('classTax', 'Tax *', ['class' => 'strong']) !!}
                        <span class="epic-tooltip" data-toggle="tooltip" title="This allows for the price to be either inclusive or exclusive of tax"><i class="fa fa-question-circle"></i></span>
                        <div>
                            {!! Form::select('classTax', ['Excluding' => 'Excluding', 'Including' => 'Including', 'N/A' => 'N/A'], isset($class)?$class->cl_tax:null, ['class' => 'form-control', 'required' => 'required','data-title'=>'-- Select --']) !!}
                            {!! $errors->first('classTax', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                </div>
                @endif
                @if(!isset($businessId) || isset($service) || (isset($entityType) && $entityType == 'service'))
                <div class="form-type-service-fields">
                    <div class="form-group {{ $errors->has('type') ? 'has-error' : ''}}">
                        {!! Form::label('type', 'Type *', ['class' => 'strong']) !!}
                        <span class="epic-tooltip" data-toggle="tooltip" title="This relates to the type of service that you offer if you have multiple fields such as Training, Nutrition, Coaching etc..."><i class="fa fa-question-circle"></i></span>
                        <!-- <a href="#" class="pull-right btn-add-more">+ Add New Type</a> -->
                        <a href="{{ route('services.getType') }}" class="pull-right add-more" data-modal-title="Service Types" data-field="serType">Manage Types</a>
                        {!! Form::hidden('btn-add-more-action', 'serviceType', ['class' => 'no-clear']) !!}
                        <div>
                            {!! Form::select('type', $serviceTypes, null, ['class' => 'form-control serviceType serType', 'required' => 'required']) !!}
                            {!! $errors->first('type', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('category') ? 'has-error' : ''}}">
                        {!! Form::label('category', 'Category *', ['class' => 'strong']) !!}
                        <span class="epic-tooltip" data-toggle="tooltip" title="This relates to the category below the service type"><i class="fa fa-question-circle"></i></span>
                        <a href="{{ route('services.getCat') }}" class="pull-right add-more" data-modal-title="Service Categories" data-field="serviceCat">Manage Categories</a>
                        <!-- <a href="#" class="pull-right btn-add-more">+ Add New Category</a> -->
                        {!! Form::hidden('btn-add-more-action', 'serviceCat', ['class' => 'no-clear']) !!}
                        <div>
                            {!! Form::select('category', $serviceCats, null, ['class' => 'form-control serviceCat', 'required' => 'required']) !!}
                            {!! $errors->first('category', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('location') ? 'has-error' : ''}}">
                        {!! Form::label('location', 'Location *', ['class' => 'strong']) !!}
                        <span class="epic-tooltip" data-toggle="tooltip" title="This relates to the location that these specific services are offered"><i class="fa fa-question-circle"></i></span>
                        @if(!isset($subview))
                        <a href="#" class="pull-right callSubview" data-target-subview="location">+ Add New Location</a>
                        @endif
                        <div>
                            {!! Form::select('location', (isset($entityType) && $entityType == 'service') || isset($service)?$locs:['' => '-- Select --'], null, ['class' => 'form-control location', 'required'=>'required']) !!}
                            {!! $errors->first('location', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('area', 'Area *', ['class' => 'strong']) !!}
                        <span class="epic-tooltip" data-toggle="tooltip" title="This relates to the areas that these specific services are offered"><i class="fa fa-question-circle"></i></span>
                        <div>
                            {!! Form::select('area', ['' => '-- Select --'], null, ['class' => 'form-control area', 'required', 'multiple', 'data-selected' => isset($service)?$service->srvc_la_id:'']) !!}
                            {!! $errors->first('area', '<p class="help-block">:message</p>') !!}
                        </div>
                        <div class="checkbox clip-check check-primary m-b-0 m-t-5">
                            <input type="checkbox" id="serviceAreasAll" class="selAllDd">
                            <label for="serviceAreasAll" class="no-error-label">
                                <strong>Select All</strong>
                            </label>
                        </div>
                    </div>
                </div>
                @endif
            </fieldset>
            @if(!isset($businessId) || isset($service) || (isset($entityType) && $entityType == 'service'))
            <fieldset class="padding-15" id="oneOnOne">
                <legend>
                    1 On 1
                </legend>
                <div class="form-group {{ $errors->has('one_on_one_name') ? 'has-error' : ''}}">
                    {!! Form::label('one_on_one_name', 'Service Name *', ['class' => 'strong']) !!}
                    <span class="epic-tooltip" data-toggle="tooltip" title="This is the name of the specific service offered"><i class="fa fa-question-circle"></i></span>
                    <div>
                        {!! Form::text('one_on_one_name', null, ['class' => 'form-control', 'required' => 'required']) !!}
                        {!! $errors->first('one_on_one_name', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>
                <div class="form-group {{ $errors->has('one_on_one_description') ? 'has-error' : ''}}">
                    {!! Form::label('one_on_one_description', 'Service Description *', ['class' => 'strong']) !!}
                    <span class="epic-tooltip" data-toggle="tooltip" title="This describes the service and what the service offers"><i class="fa fa-question-circle"></i></span>
                    <div>
                        {!! Form::textarea('one_on_one_description', null, ['class' => 'form-control', 'required' => 'required']) !!}
                        {!! $errors->first('one_on_one_description', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>
                <div class="form-group upload-group">
                    {!! Form::label(null, 'Service Logo', ['class' => 'strong']) !!}<!--Trainers profile picture-->
                    <span class="epic-tooltip" data-toggle="tooltip" title="This allows for a identification logo to be inputted allowing it to be displayed on the calendar"><i class="fa fa-question-circle"></i></span>
                    <input type="hidden" name="prePhotoName" value="{{ isset($service)?$service->one_on_one_training_logo:'' }}" class="no-clear">
                    <input type="hidden" name="entityId" value="" class="no-clear">
                    <input type="hidden" name="saveUrl" value="" class="no-clear">
                    <input type="hidden" name="photoHelper" value="oneOnoneLogo" class="no-clear">
                    <input type="hidden" name="cropSelector" value="square">
                    <div>
                        <label class="btn btn-primary btn-file">
                            <span><i class="fa fa-plus"></i> Select File</span> <input type="file" class="hidden" onChange="fileSelectHandler(this)" accept="image/*">
                        </label>
                        <div class="m-t-10">
                            @if(isset($service) && $service->one_on_one_training_logo)
                            <img class="oneOnoneLogoPreviewPics previewPics removePic" src="{{ dpSrc($service->one_on_one_training_logo) }}" />
                            <span class="removePicBtn" style="display: none;" data-entity-id="{{ isset($service)?$service->id:'' }}" data-entity="service">Remove</span>
                            @else
                            <img class="hidden oneOnoneLogoPreviewPics previewPics removePic" />
                            <span class="removePicBtn" style="display: none;" data-entity-id="{{ isset($service)?$service->id:'' }}" data-entity="service">Remove</span>
                            @endif
                        </div>
                    </div>
                    <input class="photoName" type="hidden" name="oneOnoneLogo" value="{{ isset($service) && $service->one_on_one_training_logo?$service->one_on_one_training_logo:'' }}">
                </div>
                <div class="form-group {{ $errors->has('one_on_one_colour') ? 'has-error' : ''}}">
                    {!! Form::label('one_on_one_colour', 'Service Colour *', ['class' => 'strong']) !!}
                    <span class="epic-tooltip" data-toggle="tooltip" title="This allows for the colour customization to easily identify services on the calendar"><i class="fa fa-question-circle"></i></span>
                    <div>
                        {!! Form::color('one_on_one_colour', null, ['class' => 'form-control', 'required' => 'required']) !!}
                        {!! $errors->first('one_on_one_colour', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>
                <div class="form-group {{ $errors->has('one_on_one_call_client_online') ? 'has-error' : ''}}">
                    <div class="checkbox clip-check check-primary m-b-0">
                        <input type="checkbox" name="one_on_one_call_client_online" id="one_on_one_call_client_online" value="1" {{ isset($service) && $service->one_on_one_call_client_online?'checked':'' }}>
                        <label for="one_on_one_call_client_online">
                            <strong>Can client book this service online</strong> <span class="epic-tooltip" data-toggle="tooltip" title="This allows clients to book this trainer and services from the online hub"><i class="fa fa-question-circle"></i></span>
                        </label>
                        {!! $errors->first('one_on_one_call_client_online', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group {{ $errors->has('one_on_one_duration_h') ? 'has-error' : ''}}">
                            {!! Form::label('one_on_one_duration', 'Duration(h) *', ['class' => 'strong']) !!}
                            <span class="epic-tooltip" data-toggle="tooltip" title="This indicates the time that is allocated to the specific service"><i class="fa fa-question-circle"></i></span>
                            <div>
                                {!! Form::select('one_on_one_duration_h', ['0' => '00', '1' => '1', '2' => '2', '3' => '3', '4' => '4', '5' => '5', '6' => '6', '7' => '7', '8' => '8', '9' => '9', '10' => '10', '11' => '11', '12' => '12'], null, ['class' => 'form-control']) !!}
                                {!! $errors->first('one_on_one_duration_h', '<p class="help-block">:message</p>') !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group {{ $errors->has('one_on_one_duration_m') ? 'has-error' : ''}}">
                            {!! Form::label('one_on_one_duration_m', 'Duration(min)', ['class' => 'strong']) !!}
                            <span class="epic-tooltip" data-toggle="tooltip" title="This indicates the time that is allocated to the specific service"><i class="fa fa-question-circle"></i></span>
                            <div>
                                {!! Form::select('one_on_one_duration_m', ['' => '--Select min--', '0' => '00', '5' => '5', '10' => '10', '15' => '15', '20' => '20', '25' => '25', '30' => '30', '35' => '35', '40' => '40', '45' => '45', '50' => '50', '55' => '55'], null, ['class' => 'form-control', 'required' => 'required']) !!}
                                {!! $errors->first('one_on_one_duration_m', '<p class="help-block">:message</p>') !!}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group {{ $errors->has('one_on_one_staffs') ? 'has-error' : ''}}">
                    {!! Form::label('one_on_one_staffs', 'Staff Selection', ['class' => 'strong']) !!}
                    <span class="epic-tooltip" data-toggle="tooltip" title="This displays a list of trainers or staff that are able to deliver the service"><i class="fa fa-question-circle"></i></span>
                    @if(!isset($subview))
                    <a href="#" class="pull-right callSubview" data-target-subview="staff">+ Add New Staff</a>
                    @endif
                    <div>
                        {!! Form::select('one_on_one_staffs', [], null, ['class' => 'form-control staff serviceStaff', 'multiple', 'data-selected' => isset($service) && $service->one_on_one_staffs?$service->one_on_one_staffs:'']) !!}
                        {!! $errors->first('one_on_one_staffs', '<p class="help-block">:message</p>') !!}
                    </div>
                    <div class="checkbox clip-check check-primary m-b-0 m-t-5">
                        <input type="checkbox" id="one_on_one_staffs_all" class="selAllDd">
                        <label for="one_on_one_staffs_all" class="no-error-label">
                            <strong>Select All</strong>
                        </label>
                    </div>
                </div>
                <div class="form-group {{ $errors->has('one_on_one_price') ? 'has-error' : ''}}">
                    {!! Form::label('one_on_one_price', 'Price *', ['class' => 'strong']) !!}
                    <span class="epic-tooltip" data-toggle="tooltip" title="This is the price charged for the service"><i class="fa fa-question-circle"></i></span>
                    {!! Form::hidden('one_on_one_price_hidden', isset($service)?$service->one_on_one_price:null) !!}
                    <div>
                        {!! Form::text('one_on_one_price', null, ['class' => 'form-control price-field', 'required' => 'required']) !!}
                        {!! $errors->first('one_on_one_price', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>
                <div class="form-group {{ $errors->has('one_on_one_tax') ? 'has-error' : ''}}">
                    {!! Form::label('one_on_one_tax', 'Tax *', ['class' => 'strong']) !!}
                    <span class="epic-tooltip" data-toggle="tooltip" title="This allows for the price to be either inclusive or exclusive of tax"><i class="fa fa-question-circle"></i></span>
                    <div>
                        {!! Form::select('one_on_one_tax', ['Excluding' => 'Excluding', 'Including' => 'Including', 'N/A' => 'N/A'], null, ['class' => 'form-control', 'required' => 'required','data-title'=>'-- Select --']) !!}
                        {!! $errors->first('one_on_one_tax', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>
            </fieldset>
            @endif
            @if(!isset($businessId) || isset($service) || (isset($entityType) && $entityType == 'service'))
            <fieldset class="padding-15" id="team">
                <legend>
                    T.E.A.M &nbsp;&nbsp;&nbsp;&nbsp;
                </legend>
                <div class="form-group {{ $errors->has('team_name') ? 'has-error' : ''}}">
                    {!! Form::label('team_name', 'Service Name *', ['class' => 'strong']) !!}
                    <span class="epic-tooltip" data-toggle="tooltip" title="This is the name of the specific service offered"><i class="fa fa-question-circle"></i></span>
                    <div>
                        {!! Form::text('team_name', null, ['class' => 'form-control', 'required' => 'required']) !!}
                        {!! $errors->first('team_name', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>
                <div class="form-group {{ $errors->has('team_description') ? 'has-error' : ''}}">
                    {!! Form::label('team_description', 'Service Description *', ['class' => 'strong']) !!}
                    <span class="epic-tooltip" data-toggle="tooltip" title="This describes the service and what the service offers"><i class="fa fa-question-circle"></i></span>
                    <div>
                        {!! Form::textarea('team_description', null, ['class' => 'form-control', 'required' => 'required']) !!}
                        {!! $errors->first('team_description', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>
                <div class="form-group upload-group">
                    {!! Form::label(null, 'Service Logo', ['class' => 'strong']) !!}<!-- Upload T.E.A.M Training Logo -->
                    <span class="epic-tooltip" data-toggle="tooltip" title="This allows for a identification logo to be inputted allowing it to be displayed on the calendar"><i class="fa fa-question-circle"></i></span>
                    <input type="hidden" name="prePhotoName" value="{{ isset($service)?$service->team_training_logo:'' }}" class="no-clear">
                    <input type="hidden" name="entityId" value="" class="no-clear">
                    <input type="hidden" name="saveUrl" value="" class="no-clear">
                    <input type="hidden" name="photoHelper" value="teamLogo" class="no-clear">
                    <input type="hidden" name="cropSelector" value="">
                    <div>
                        <label class="btn btn-primary btn-file">
                            <span><i class="fa fa-plus"></i> Select File</span> <input type="file" class="hidden" onChange="fileSelectHandler(this)" accept="image/*">
                        </label>
                        <div class="m-t-10">
                            @if(isset($service) && $service->team_training_logo)
                            <img class="teamLogoPreviewPics previewPics removePic" src="{{ dpSrc($service->team_training_logo) }}" />
                            <span class="removePicBtn" style="display: none;" data-entity-id="{{ isset($service)?$service->id:'' }}" data-entity="service">Remove</span>
                            @else
                            <img class="hidden teamLogoPreviewPics previewPics removePic" />
                            <span class="removePicBtn" style="display: none;" data-entity-id="{{ isset($service)?$service->id:'' }}" data-entity="service">Remove</span>
                            @endif
                        </div>
                    </div>
                    <input class="photoName" type="hidden" name="teamLogo" value="{{ isset($service) && $service->team_training_logo?$service->team_training_logo:'' }}">
                </div>
                <div class="form-group {{ $errors->has('team_colour') ? 'has-error' : ''}}">
                    {!! Form::label('team_colour', 'Service Colour *', ['class' => 'strong']) !!}
                    <span class="epic-tooltip" data-toggle="tooltip" title="This allows for the colour customization to easily identify services on the calendar"><i class="fa fa-question-circle"></i></span>
                    <div>
                        {!! Form::color('team_colour', null, ['class' => 'form-control', 'required' => 'required']) !!}
                        {!! $errors->first('team_colour', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>
                <div class="form-group {{ $errors->has('team_can_book_online') ? 'has-error' : ''}}">
                    <div class="checkbox clip-check check-primary m-b-0">
                        <input type="checkbox" name="team_can_book_online" id="team_can_book_online" value="1" {{ isset($service) && $service->team_can_book_online?'checked':'' }}>
                        <label for="team_can_book_online">
                            <strong>Can client book this service online</strong> <span class="epic-tooltip" data-toggle="tooltip" title="This allows clients to book this trainer and services from the online hub"><i class="fa fa-question-circle"></i></span>
                        </label>
                        {!! $errors->first('team_can_book_online', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group {{ $errors->has('team_duration_h') ? 'has-error' : ''}}">
                            {!! Form::label('team_duration_h', 'Duration *', ['class' => 'strong']) !!}
                            <span class="epic-tooltip" data-toggle="tooltip" title="This indicates the time that is allocated to the specific service"><i class="fa fa-question-circle"></i></span>
                            <div>
                                {!! Form::select('team_duration_h', ['0' => '00', '1' => '1', '2' => '2', '3' => '3', '4' => '4', '5' => '5', '6' => '6', '7' => '7', '8' => '8', '9' => '9', '10' => '10', '11' => '11', '12' => '12'], null, ['class' => 'form-control']) !!}
                                {!! $errors->first('team_duration_h', '<p class="help-block">:message</p>') !!}
                            </div>
                        </div> 
                    </div>
                    <div class="col-md-6">
                         <div class="form-group {{ $errors->has('team_duration_m') ? 'has-error' : ''}}">
                            {!! Form::label('team_duration_m', 'Duration *', ['class' => 'strong']) !!}
                            <span class="epic-tooltip" data-toggle="tooltip" title="This indicates the time that is allocated to the specific service"><i class="fa fa-question-circle"></i></span>
                            <div>
                                {!! Form::select('team_duration_m', ['' => '--Select min--', '0' => '00', '5' => '5', '10' => '10', '15' => '15', '20' => '20', '25' => '25', '30' => '30', '35' => '35', '40' => '40', '45' => '45', '50' => '50', '55' => '55'], null, ['class' => 'form-control', 'required' => 'required']) !!}
                                {!! $errors->first('team_duration_m', '<p class="help-block">:message</p>') !!}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group {{ $errors->has('team_staffs') ? 'has-error' : ''}}">
                    {!! Form::label('team_staffs', 'Staff Selection', ['class' => 'strong']) !!}
                    <span class="epic-tooltip" data-toggle="tooltip" title="This displays a list of trainers or staff that are able to deliver the service"><i class="fa fa-question-circle"></i></span>
                    @if(!isset($subview))
                    <a href="#" class="pull-right callSubview" data-target-subview="staff">+ Add New Staff</a>
                    @endif
                    <div>
                        {!! Form::select('team_staffs', [], null, ['class' => 'form-control staff serviceStaff', 'multiple', 'data-selected' => isset($service) && $service->team_staffs?$service->team_staffs:'']) !!}
                        {!! $errors->first('team_staffs', '<p class="help-block">:message</p>') !!}
                    </div>
                    <div class="checkbox clip-check check-primary m-b-0 m-t-5">
                        <input type="checkbox" id="team_staffs_all" class="selAllDd">
                        <label for="team_staffs_all" class="no-error-label">
                            <strong>Select All</strong>
                        </label>
                    </div>
                </div>
                <div class="form-group {{ $errors->has('team_price') ? 'has-error' : ''}}">
                    {!! Form::label('team_price', 'Price *', ['class' => 'strong']) !!}
                    <span class="epic-tooltip" data-toggle="tooltip" title="This is the price charged for the service"><i class="fa fa-question-circle"></i></span>
                    {!! Form::hidden('team_price_hidden', isset($service)?$service->team_price:null) !!}
                    <div>
                        {!! Form::text('team_price', null, ['class' => 'form-control price-field', 'required' => 'required']) !!}
                        {!! $errors->first('team_price', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>
                <div class="form-group {{ $errors->has('team_tax') ? 'has-error' : ''}}">
                    {!! Form::label('team_tax', 'Tax *', ['class' => 'strong']) !!}
                    <span class="epic-tooltip" data-toggle="tooltip" title="This allows for the price to be either inclusive or exclusive of tax"><i class="fa fa-question-circle"></i></span>
                    <div>
                        {!! Form::select('team_tax', ['Excluding' => 'Excluding', 'Including' => 'Including', 'N/A' => 'N/A'], null, ['class' => 'form-control', 'required' => 'required','data-title'=>'-- Select --']) !!}
                        {!! $errors->first('team_tax', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>
            </fieldset>
            @endif
            @if(!isset($businessId) || isset($class) || (isset($entityType) && $entityType == 'class'))
            <fieldset class="padding-15 form-type-class-fields">
                <legend>
                    Padding & Processing Times &nbsp;&nbsp;&nbsp;&nbsp;
                </legend>
                <div class="form-group {{ $errors->has('classIfPadding') ? 'has-error' : ''}}">
                    <div class="checkbox clip-check check-primary m-b-0">
                        <input type="checkbox" name="classIfPadding" id="classIfPadding" value="1" {{ isset($class) && ($class->cl_pad_before || $class->cl_proc_time)?'checked':'' }}>
                        <label for="classIfPadding">
                            <strong>Add padding and processing times</strong>
                        </label>
                        {!! $errors->first('classIfPadding', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>
                <div class="form-group paddingFields {{ $errors->has('classPadBefore') ? 'has-error' : ''}}">
                    {!! Form::label('classPadBefore', 'Padding Before *', ['class' => 'strong']) !!}
                    <span class="epic-tooltip" data-toggle="tooltip" title="Padding time will block out additional time in your calendar"><i class="fa fa-question-circle"></i></span>
                    <div>
                        {!! Form::select('classPadBefore', ['' => '-- Select --', '5' => '5 min', '10' => '10 min', '15' => '15 min', '20' => '20 min', '25' => '25 min', '30' => '30 min', '35' => '35 min', '40' => '40 min', '45' => '45 min', '50' => '50 min', '55' => '55 min', '60' => '60 min'], isset($class)?$class->cl_pad_before:null, ['class' => 'form-control', 'required' => 'required']) !!}
                        {!! $errors->first('classPadBefore', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>
                <div class="form-group paddingFields {{ $errors->has('classProcesTime') ? 'has-error' : ''}}">
                    {!! Form::label('classProcesTime', 'Processing Time *', ['class' => 'strong']) !!}
                    <span class="epic-tooltip" data-toggle="tooltip" title="Processing time will create a free gap after the service that is bookable for other appointments"><i class="fa fa-question-circle"></i></span>
                    <div>
                        {!! Form::select('classProcesTime', ['' => '-- Select --', '5' => '5 min', '10' => '10 min', '15' => '15 min', '20' => '20 min', '25' => '25 min', '30' => '30 min', '35' => '35 min', '40' => '40 min', '45' => '45 min', '50' => '50 min', '55' => '55 min', '60' => '60 min'], isset($class)?$class->cl_proc_time:null, ['class' => 'form-control', 'required' => 'required']) !!}
                        {!! $errors->first('classProcesTime', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>
            </fieldset>
            @endif
        </div>
        <div class="col-md-6">
            
            <!-- start: Resource fieldset -->
            @if(isset($res))
            @include('includes.partials.service_resource', ['res' => $res, 'newres' => $newres])
            @else
            @include('includes.partials.service_resource')    
            @endif
            
            <fieldset class="padding-15 form-type-class-fields">
                <legend>
                    Class Memberships
                </legend>
                <div class="form-group {{ $errors->has('classMembership') ? 'has-error' : ''}}">
                    {!! Form::label('classMembership', 'Membership Selection', ['class' => 'strong']) !!}
                    <span class="epic-tooltip" data-toggle="tooltip" title="This displays a list of membership that are connected to the class"><i class="fa fa-question-circle"></i></span>
                    <div>
                        {!! Form::select('classMembership', !empty($allMemberships)?$allMemberships : [], !empty($clsMemberships) ? $clsMemberships : [], ['class' => 'form-control classMembership', 'multiple']) !!} 
                        {!! $errors->first('classMembership', '<p class="help-block">:message</p>') !!}
                    </div>
                    <div class="checkbox clip-check check-primary m-b-0 m-t-5">
                        <input type="checkbox" id="classMembershipAll" class="selAllDd">
                        <label for="classMembershipAll" class="no-error-label">
                            <strong>Select All</strong>
                        </label>
                    </div>
                </div>
            </fieldset>
            
            <!-- end: Resource fieldset -->
        </div>
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
                <button type="button" class="btn btn-primary btn-wide pull-right margin-right-15 btn-add-more-form">
                    <i class="fa fa-plus"></i> <span>Add Service</span>
                </button>
                <button type="button" class="btn btn-primary btn-wide pull-right margin-right-15 skipnextbutton skipbutton hidden">
                    Skip to next
                </button>
                @if(isset($subview))
                <button class="btn btn-default pull-right margin-right-15 closeSubView" type="button">
                    Close
                </button>
                @endif
            </div>
        </div>
    </div>
    @else
    <div class="row">
        <div class="col-sm-12">
            <div class="form-group">
                @if(isset($service))
                <button class="hidden btn-add-more-form"></button>
                <a class="btn btn-primary btn-wide pull-right btn-edit-service-class">
                    <i class="fa fa-edit"></i> Update Service
                </a>
                @elseif(isset($class))
                <button class="hidden btn-add-more-form"></button>
                <a class="btn btn-primary btn-wide pull-right btn-edit-service-class">
                    <i class="fa fa-edit"></i> Update Class
                </a>
                
                @elseif(isset($entityType))
                <button class="btn btn-primary btn-wide pull-right btn-add-more-form">
                    @if($entityType == 'service')
                    <i class="fa fa-plus"></i> Add Service
                    @elseif($entityType == 'class')
                    <i class="fa fa-plus"></i> Add Class
                    @endif
                </button>
                @endif
                @if(isset($subview))
                <button class="btn btn-default pull-right margin-right-15 closeSubView" type="button">
                    Close
                </button>
                @endif
            </div>
        </div>
    </div>
    @endif
    {!! Form::close() !!}
    
    
    
    <!-- Start: price update modal -->
    <div class="modal fade" id="priceUpdateModal" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Apply effective price</h4>
                </div>
                <div class="modal-body bg-white">
                    {!! Form::open(['url'=>'','id'=>'priceForm'])!!}
                    <div class="form-group">
                        {!! Form::label('applyDate', 'Date *', ['class' => 'strong']) !!}
                        {!! Form::text('applyDate', null, ['class' => 'form-control applyDateCls', 'required' => 'required','readonly'=>'readonly']) !!}
                    </div>
                    {!! Form::close() !!}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="newPriceSubmit">Submit</button>
                </div>
            </div>
            
        </div>
    </div>
    <!-- End: price update modal -->