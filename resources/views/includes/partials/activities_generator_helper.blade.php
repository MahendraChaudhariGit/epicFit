<!-- Start:generator modal  -->
<div class="modal fade" id="dataSelector" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button> -->
        <h4 class="modal-title" id="myModalLabel">Get Plan</h4>
      </div>
      <div class="modal-body bg-white">
        {!! Form::open(['url' => '', 'role' => 'form']) !!}
          <div class="row"> 
            <div class="col-md-12">
              <div class="errorMsg"></div>
              <fieldset>
                <legend>Gender</legend>
                <div class="radio clip-radio radio-primary radio-inline m-b-0">
                    <input type="radio" name="gender" id="male" value="male" >
                    <label for="male">
                        Male
                    </label>
                </div>
                <div class="radio clip-radio radio-primary radio-inline m-b-0">
                    <input type="radio" name="gender" id="female" value="female" >
                    <label for="female">
                        Female
                    </label>
                </div>
              </fieldset>
            </div>
          </div>
        {!! Form::close() !!}
     </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal" aria-label="Close" >Cancel</button>
        <button type="button" class="btn btn-primary" id="dataSelector-btn">Go &nbsp;<i class="fa fa-arrow-circle-right"></i></button>
      </div>
    </div>
  </div>
</div>
<!-- End:generator modal  -->
<!-- Start:generator modal  -->
<div class="modal fade" id="generatorModal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button> -->
        <h4 class="modal-title" id="myModalLabel">Set Generate plan Filter</h4>
      </div>
      <div class="modal-body bg-white">
        {!! Form::open(['url' => '', 'role' => 'form']) !!}
        {!! Form::hidden('workout_name') !!}
          <div class="row"> 
            <div class="col-md-6">
              <fieldset>
                <legend>General</legend>
                <div class="form-group">
                 {!! Form::label('purpose', 'Purpose *', ['class' => 'strong']) !!}
                 {!! Form::select('purpose', ['1'=>'Increase Strength', '2'=>'Weight Loss/Tone','3'=>'General Health'] ,null, ['class' => 'form-control onchange-set-neutral', 'multiple', 'required']) !!}
                </div>
                <div class="form-group">
                 {!! Form::label('equipment', 'EQUIPMENT *', ['class' => 'strong']) !!}
                 {!! Form::select('equipment', ['1'=>'GYM', '2'=>'FREE WEIGHTS','3'=>'BODY WEIGHT','4'=>'SWISS BALL'] ,null, ['class' => 'form-control onchange-set-neutral', 'multiple', 'required']) !!}
                </div>
              </fieldset>
            </div>
            <div class="col-md-6">
              <fieldset>
               <legend>Habits</legend>
              <div class="form-group">
               {!! Form::label('curr_phy_act', 'Current Physical Activity *', ['class' => 'strong']) !!}
               {!! Form::select('curr_phy_act', ['1'=>'Regular Exercise', '2'=>'Infrequent Exercise','3'=>'No Exercise'] ,null, ['class' => 'form-control onchange-set-neutral', 'multiple', 'required']) !!}
              </div>
              <div class="form-group">
               {!! Form::label('prev_phy_act', 'Previous Physical Activity *', ['class' => 'strong']) !!}
               {!! Form::select('prev_phy_act', ['1'=>'A Little', '2'=>'Some','3'=>'A Lot','4'=>'Intermediate','5'=>'A Ton'] ,null, ['class' => 'form-control onchange-set-neutral', 'multiple' ,'required']) !!}
              </div>
              <div class="form-group">
               {!! Form::label('next_phy_act', 'Wanna be Physical Activity', ['class' => 'strong']) !!}
               {!! Form::select('next_phy_act', ['1'=>'A Little', '2'=>'Some','3'=>'A Lot','4'=>'Intermediate','5'=>'A Ton'] ,null, ['class' => 'form-control onchange-set-neutral', 'multiple', 'required']) !!}
              </div>
              <div class="form-group">
               {!! Form::label('curr_intensity_phy_act', 'Current Intensity of Physical Activity', ['class' => 'strong']) !!}
               {!! Form::select('curr_intensity_phy_act', ['1'=>'Sendatory','2'=>'Light', '3'=>'Moderate', '4'=>'Vigorous', '5'=>'High'], null, ['class' => 'form-control onchange-set-neutral', 'multiple', 'required']) !!}
              </div>
            </fieldset>
            </div>
          </div>
        {!! Form::close() !!}
     </div>
      <div class="modal-footer">
        <!-- <button type="button" class="btn btn-default" id="generator-cancel" >Cancel</button> -->
        <button type="button" class="btn btn-primary" id="generator-save">Save</button>
      </div>
    </div>
  </div>
</div>
<!-- End:generator modal  -->