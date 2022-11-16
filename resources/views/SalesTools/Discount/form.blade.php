<div class="sucMes hidden"></div>
            <fieldset class="padding-15 m-t-40">
                <legend>
                    General
                </legend>
                <div class="form-group">
                    {!! Form::label('discountname', 'Name *', ['class' => 'strong']) !!}
                    {!! Form::text('discountname', isset($data)?$data->std_name:null , ['class' => 'form-control', 'required' => 'required']) !!}                        
                </div>  
                <div class="form-group">
                    <?php
                        $taxSelect = null;
                        if(isset($data))
                            $taxSelect = $data->std_tax;
                        elseif(isset($globleTaxId))
                             $taxSelect = $globleTaxId;
                    ?>
                    {!! Form::label('tax', 'Tax *', ['class' => 'strong']) !!}
                    {!! Form::select('tax', $taxesdata , $taxSelect, ['class' => 'form-control onchange-set-neutral','required' => 'required', 'id'=>'taxid']) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('discount', 'Discount type *', ['class' => 'strong']) !!}
                    {!! Form::select('discount', ['Fixed amount'=>'Fixed amount','Generic'=>'Generic (specify discount when applying)','Percentage'=>'Percentage'] , isset($data)?$data->std_type:null, ['class' => 'form-control onchange-set-neutral','required' => 'required', 'id'=>'discountid','data-title'=>'-- Select --']) !!}
                </div>
                 <div class="form-group moveErrMsg" id="targetDiv">
                    {!! Form::label('discountvalue', 'Discount value *', ['class' => 'strong']) !!}
                   <div class="input-group">
                    {!! Form::text('discountvalue', isset($data)?$data->std_value:null , ['class' => 'form-control input-sm no-clear price-field', 'required' => 'required']) !!} 
                    <span class="input-group-addon"><i class="fa"></></i></span>
                  </div>
                  <span class="help-block placeErrMsg"></span>
                 </div>
            </fieldset>
            