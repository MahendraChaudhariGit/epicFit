<fieldset class="padding-15 " id="resource-form">
  <legend>
      Resource
  </legend>

  @if(isset($res))
    <div id="service-resource">
      <div class="row p-l-0 m-b-5">
          <div class="col-md-5 col-xs-5">Resource</div>
          <div class="col-md-5 col-xs-5">Items</div>
          <div class="col-md-2 col-xs-2">
            <a class="btn btn-xs btn-primary add-resource-row" href="#"><i class=" fa fa-plus fa fa-white"></i></a>
          </div>   
      </div>
      <div class="row resource-clone-class hidden">
        <div class="col-md-5 col-xs-5"> 
          <div class="form-group resItemLocDd">
            <select name="resources0" class="form-control resource-option onchange-set-neutral"><!--required-->
               <option value="">-- Select --</option>
               @if(count($res))
                 @foreach ($res as $key => $value) 
                    <option value="{{ $value->id }}" data-noofitem="{{ $value->items->count() }}">{{ $value->res_name }}</option>
                 @endforeach
               @endif
            </select> 
          </div>
        </div> 
        <div class="col-md-5 col-xs-5"> 
            <div class="form-group resItemLocDd2">
              {!! Form::select('items0', [], null, ['class' => 'form-control item-op onchange-set-neutral']) !!}<!--, 'required' => 'required'-->
            </div>
        </div>
        <div class="col-md-2 col-xs-2">
            <a class="btn btn-xs btn-red remove-resourcelink-row" href="#" '=""><i class="fa fa-times fa fa-white"></i></a>
        </div> 
      </div>

        <!-- new resource dropdown -->
      <?php $i=1; ?>
      @if(isset($newres) && count($newres))
          @foreach($newres as $newkey => $newvalue)
            <div class="row resource-clone-class">
              <div class="col-md-5 col-xs-5"> 
                <div class="form-group resItemLocDd">
                    <select name="newResources{{$i}}" class="form-control resource-option required" data-item="{{ $newvalue->sr_item_quantity }}">
                        <option value="">-- Select --</option>  
                        @foreach ($res as $key => $value) 
                            <option value="{{ $value->id }}" <?php if($newvalue->sr_res_id == $value->id) echo 'selected=selected' ?> data-noofitem="{{ $value->items->count() }}">{{ $value->res_name }}</option>
                        @endforeach
                     </select> 
                </div>
              </div> 
              <div class="col-md-5 col-xs-5"> 
                  <div class="form-group resItemLocDd2">
                      {!! Form::select('newItem'.$i, [], null, ['class' => 'form-control item-op' , 'required' => 'required']) !!}
                  </div>
              </div>
              <div class="col-md-2 col-xs-2">
                  <a class="btn btn-xs btn-red remove-resourcelink-row" href="#" '=""><i class="fa fa-times fa fa-white"></i></a>
              </div> 
            </div>
            <?php $i++; ?>
          @endforeach
      @endif
      <input type="hidden" name="flagvalue" value="{{$i}}">
    </div>  
  @else
        {!! displayNonClosingAlert('info', 'You can assign resources once they have been added.') !!}
  @endif 

</fieldset>