<!--Start: Make-up session count Model-->

<div class="modal fade" id="raiseMakeUpModel" role="dialog">
    <div class="modal-dialog">   
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">EPIC Credit</h4>
        </div>
        <div class="modal-body bg-white">
         {!! Form::open(['url' => '', 'role' => 'form']) !!}
         {!! Form::hidden('clientId') !!}
         {!! Form::hidden('net_amount') !!}
         {!! Form::hidden('reload_check') !!}

         <!-- callback hidden field use for only calender view -->
         {!! Form::hidden('callback') !!} 

            <div class="row m-b-10">
                <div class="col-sm-12 sucMes hidden"></div>    
            </div>
            <div class="form-group">
                {!! Form::label('purpose', 'Purpose *', ['class' => 'strong']) !!}
                <div>
                  {!! Form::select('purpose',[''=>'-- Select --','class'=>'Class Makeup','service'=>'Service Makeup','manual'=>'Manual by Admin'], null, ['class' => 'form-control onchange-set-neutral', 'required'=>'required']) !!}
                </div>
            </div>
            <div class="form-group">
                
                {!! Form::label('amount', 'Amount *', ['class' => 'strong']) !!}
                {!! Form::text('amount', null, ['class' => 'form-control price-field','required','id'=>'amount']) !!}
                <div class="checkbox clip-check check-primary m-b-0 m-t-5">
                  <input type="checkbox" name="makeupCheck" id="makeup-check" value="1"  class="no-clear" >
                  <label for="makeup-check" class="m-r-0"><strong>Amount to be deducted</strong></label>
                </div>
            </div>
            <div class="form-group">
                <div>
                <span class="strong">Total EPIC Credit</span>
                </div>
                <div>
                  $<span id="netamount">0.00</span><br>
                </div>  
            </div>      
            <div class="form-group">
                <div>
                  {!! Form::label('notes', 'Notes', ['class' => 'strong']) !!}
                </div>
                {!! Form::textarea('notes', null, ['class' => 'form-control textarea']) !!}
            </div>
            
         {!! Form::close() !!}  
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>

          <button type="button" class="btn btn-primary submit" id="raiseMakeUpModelSubmit">Submit</button>
        </div>
    </div>      
  </div>
</div>
<!--End: Make-up session count Model-->