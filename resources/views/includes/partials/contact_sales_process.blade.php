<div class="row" style="margin:0">
<!-- 	@if(!$clients->gender)
    <div class="form-group gender">
        <div>
            <div class="radio clip-radio radio-primary radio-inline m-b-0">
                <input type="radio" name="gender" id="male" value="Male" required class="onchange-set-neutral">
                <label for="male"> Male</label>
            </div>
            <div class="radio clip-radio radio-primary radio-inline m-b-0">
                <input type="radio" name="gender" id="female" value="Female" required class="onchange-set-neutral">
                <label for="female"> Female</label>
            </div>
        </div>                   
        <span class="help-block m-b-0"></span>        
    </div>
    @endif -->
    <div class="form-group">
        {!! Form::label('contactStatus', 'Select Status *', ['class' => 'strong']) !!}
        {!! Form::select('contactStatus', array('' => '-- Select --', 'contacted' => 'Contact made', 'messaged' => 'Left a message', 'noanswer' => 'No answer'), null, ['class' => 'form-control onchange-set-neutral', 'required']) !!}
    </div>
    <div class="form-group">
        {!! Form::label('contactMadeOpt', 'Contact Result *', ['class' => 'strong']) !!}
        {!! Form::select('contactMadeOpt', array('' => '-- Select --', 'book' => 'Book Consultation', 'ni' => 'Not interested', 'cb' => 'Call back later'), null, ['class' => 'form-control onchange-set-neutral', 'required']) !!}
    </div>
    <div class="form-group">
        {!! Form::label('clientStatusInContactNotes', 'Client Status *', ['class' => 'strong']) !!}
        {!! Form::select('clientStatusInContactNotes', array('' => '-- Select --', 'active-lead' => 'Active Lead', 'inactive-lead' => 'Inactive Lead'), null, ['class' => 'form-control onchange-set-neutral', 'required']) !!}
    </div>
    <div id="cbFields">
        <div class="form-group callback">
            {!! Form::label('contactCbkDate', 'Callback Date *', ['class' => 'strong']) !!}
            {!! Form::text('contactCbkDate', null, ['class' => 'form-control onchange-set-neutral', 'required', 'autocomplete'=>'off', 'readonly']) !!}
        </div>
        <div class="form-group getTimeField">
            {!! Form::label('contactMadeTime', 'Callback Time *', ['class' => 'strong']) !!}
            {!! Form::text('contactMadeTime', null, ['class' => 'form-control onchange-set-neutral timepicker1', 'required', 'readonly']) !!} 
        </div>
    </div>
    <div class="form-group clearfix p-t-5"><!--notes-create-btn-->
        <a href="#" class="btn btn-primary pull-left create-note">Create Note</a>
        <a href="#" class="btn btn-primary pull-left show-note">Show Notes</a>

        <div class="pull-right">
            {!! Form::Button('Close', ['class' => 'btn btn-default margin-right-15 closeContactNoteSubview']) !!}
            {!! Form::Button('Submit', ['class' => 'btn btn-primary', 'type' => 'submit','data-onlynotes'=>'0']) !!}
        </div>
    </div>

    <div class="form-group noteWrap">
        <textarea class="summernote" id="contactNote" placeholder="Write note here..." ></textarea>
    </div>
    
    <div class="form-group notes-list">
            <h5 class="panel-title">
                Notes  
            </h5>   
        <div class="col-md-12 m-t-10 contact-notes"> 
            <div class="panel-scroll mh-350">
                @if(count($noteArray))      
                    @foreach($noteArray as $noteData)
                        @if($noteData->category->nc_name =='contact')
                        <div class="{{$noteData->cn_type}}-{{$noteData->cn_id}}">
                                <p>
                                   @if($noteData->cn_source)
                                   <small>({!! $noteData->cn_source !!})</small></br>
                                   @endif
                                </p> 
                                <p> {!! $noteData->cn_notes !!} </p>
                                <p>
                                <small>Created on: {{ setLocalToBusinessTimeZone($noteData->created_at,'dateString') }}&nbsp;&nbsp;</small></p>
                                <hr class="notes-hr">
                        </div>
                        @endif         
                    @endforeach
                @endif    
            </div>
        </div>
    </div>
</div>
<!-- <div class="row m-t-10">
    <div class="col-sm-6">
        <button type="button" class="btn btn-success callSubview hidden" data-target-subview="calendar">Book Consultation</button>
    </div>
    <div class="col-sm-6 text-right">
        {!! Form::Button('Close', ['class' => 'btn btn-default margin-right-15 closeContactNoteSubview']) !!}
        {!! Form::Button('Submit', ['class' => 'btn btn-primary', 'type' => 'submit','data-onlynotes'=>'0']) !!}
    </div>
</div> -->