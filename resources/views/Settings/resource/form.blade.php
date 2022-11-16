

 <div class="row">
      <div class="sucMes hidden"></div>
        <div class="col-md-6">
            <fieldset class="padding-15 " id="option-value-fieldset">
            <legend>
                    Resource Details 
            </legend>
            <div class="row">
                <div class="col-md-12"> 
                   <div class="form-group">
                    {!! Form::label('resName', 'Resource name *', ['class' => 'strong']) !!}
                    {!! Form::text('resName', isset($data)?$data->res_name:null , ['class' => 'form-control','required' => 'required']) !!}
                   </div>
                </div>   
            </div>
            <div class="row m-b-5">
                <div class="col-md-5 col-xs-5">              
                     <strong>Item</strong>
                </div>     
                <div class="col-md-5 col-xs-5">
                     <strong>Item location</strong>
                </div>
               
            </div>
            @if(isset($data))
            <?php $i=0;?>
            @foreach($data->items as $item)
            <div class="row resource-clone-row">
                <input type="hidden" name="itemId{{$i}}" value="{{ $item->id }}">
                <div class="col-md-5 col-xs-5">   
                   <div class="form-group">
                    {!! Form::text('resItem'.$i, $item->ri_name, ['class' => 'form-control clone-textbox onchange-set-neutral','data-clone'=>'0', 'required']) !!}
                   </div>
                </div>
                <div class="col-md-5 col-xs-5">   
                   <div class="form-group resItemLocDd">
                    {!! Form::select('resLoc'.$i, $loc,$item->ri_location, ['class' => 'form-control resItemLoc-select onchange-set-neutral', 'required']) !!}
                   </div>
                </div>
                <div class="col-md-2 col-xs-2">
                @if($i)
                  <a class="btn btn-xs btn-red remove-resource-row" href="#" '=""><i class="fa fa-times fa fa-white"></i></a>
                @else
                     <a class="btn btn-xs btn-primary" href="#" id="add-resource"><i class=" fa fa-plus fa fa-white"></i></a>
                @endif
             </div> 
            </div>
            <?php $i++; ?>
            @endforeach
            @else
                <div class="row resource-clone-row">
                <div class="col-md-5 col-xs-5">   
                   <div class="form-group">
                    {!! Form::text('newResItem0',null, ['class' => 'form-control clone-textbox onchange-set-neutral','data-clone'=>'0', 'required']) !!}
                   </div>
                </div>
                <div class="col-md-5 col-xs-5">   
                   <div class="form-group resItemLocDd">
                    {!! Form::select('newResLoc0', $loc, null , ['class' => 'form-control resItemLoc-select onchange-set-neutral', 'required']) !!}
                   </div>
                </div>
                 <div class="col-md-2 col-xs-2">
                    <a class="btn btn-xs btn-primary" href="#" id="add-resource"><i class=" fa fa-plus fa fa-white"></i></a>
                 </div>
            </div>
            @endif    
            </fieldset> 
        </div>

       
        <div class="col-md-6" id="service-cls-form">
        <!-- Start: Services -->
           <fieldset class="padding-15 ">
             
              <legend>
                    Services 
              </legend>
              @if(isset($data))
                <!--
                {!! displayNonClosingAlert('info', 'You can assign this resource to services once the resource has been saved.') !!}  
                -->
                <div class="row p-l-0 m-b-5">
                   <div class="col-md-6 col-xs-6">Services</div>
                   <div class="col-md-6 col-xs-6">Items</div>
                   <!--
                   <div class="col-md-2">
                       <a class="btn btn-xs btn-primary add-service-row" href="#"><i class=" fa fa-plus fa fa-white"></i></a>
                   </div> 
                   -->  
                </div>
                 <!--
                  <div class="row service-clone-class hidden">
                      <div class="col-md-5"> 
                          <div class="form-group resItemLocDd">
                            
                             <select name="newService0" class="form-control service-cls-option onchange-set-neutral required">

                                 <option value="">-- Select --</option>
                                 @if(isset($serviceData))
                                  @foreach($serviceData as $serv)
                                   <option value="{{ $serv->id}}" data-locid="{{ $serv->location}}">{{ $serv->name }}</option>
                                  @endforeach
                                 @endif
                            </select> 

                          </div>
                       </div> 
                       <div class="col-md-5"> 
                          <div class="form-group resItemLocDd2">
                              {!! Form::select('newSrvItem0', [], null, ['class' => 'form-control service-cls-item-op onchange-set-neutral' , 'required' => 'required']) !!}
                          </div>
                       </div>
                       <div class="col-md-2">
                            <a class="btn btn-xs btn-red remove-resource-row" href="#"><i class="fa fa-times fa fa-white"></i></a>
                       </div> 
                  </div>
                  -->
                  @if(isset($services))
                    <?php //$i=0;?>
                    @foreach($services as $service)
                      <div class="row service-clone-class">
                        <div class="col-md-6 col-xs-6"> 
                            <div class="form-group resItemLocDd">
                              <!-- <select name="service{{$i}}" class="form-control service-cls-option onchange-set-neutral required" data-itemqunt=""> <?php //{{ $services->sr_item_quantity }} ?>

                                   <option value="">-- Select --</option>
                                   @if(isset($serviceData))
                                    @foreach($serviceData as $serv)
                                     <option value="{{ $serv->id }}" data-locid="{{ $serv->location}}" <?php //if($services->sr_entity_id==$serv->id)// echo 'selected=selected'; ?>>{{ $serv->name }}</option>
                                    @endforeach
                                   @endif
                              </select> 
                              --> 
                              <p><a href="{{ route('services.show', $service->id) }}">{{ $service->name }}</a></p>
                            </div>
                         </div> 
                         <div class="col-md-6 col-xs-6"> 
                            <div class="form-group resItemLocDd2">
                            <!--
                                {!! Form::select('srvItem'.$i, [], null, ['class' => 'form-control service-cls-item-op onchange-set-neutral' , 'required' => 'required']) !!}
                              --> 
                              <p>{{ $data->getServiceQuantity($data, $service->id) }}</p> 
                            </div>
                         </div>
                         <!--
                         <div class="col-md-2">
                              <a class="btn btn-xs btn-red remove-resource-row" href="#"><i class="fa fa-times fa fa-white"></i></a>
                         </div> 
                         -->
                      </div>
                      <?php //$i++;?>
                    @endforeach
                  @endif
             @else
                 {!! displayNonClosingAlert('info', 'You can assign this resource once it has been saved.') !!}  
             @endif
             
         </fieldset> 
         <!-- End: Services -->
         <!-- Start: Classes -->
         <fieldset class="padding-15 ">
            <legend>
                Classes 
            </legend>
            @if(isset($data))
                <!--
                {!! displayNonClosingAlert('info', 'You can assign this resource to services once the resource has been saved.') !!}  
                -->
              <div class="row p-l-0 m-b-5">
                 <div class="col-md-6 col-xs-6">Classes</div>
                 <div class="col-md-6 col-xs-6">Items</div>
                 <!--
                 <div class="col-md-2">
                     <a class="btn btn-xs btn-primary add-class-row" href="#"><i class=" fa fa-plus fa fa-white"></i></a>
                 </div>
                 -->   
                </div>
              <!--
              <div class="row class-clone-class hidden">
                  <div class="col-md-5"> 
                      <div class="form-group resItemLocDd">
                         <select name="newClass0" class="form-control service-cls-option onchange-set-neutral required">
                             <option value="">-- Select --</option>
                              @if(isset($classes))
                              @foreach($classes as $value)
                               <option value="{{ $value->cl_id }}" data-locid="{{ $value->cl_location_id }}">{{ $value->cl_name }}</option>
                              @endforeach
                             @endif
                         </select>
                      </div>
                   </div> 
                   <div class="col-md-5"> 
                      <div class="form-group resItemLocDd2">
                          {!! Form::select('newClsItem0', [], null, ['class' => 'form-control service-cls-item-op onchange-set-neutral' , 'required' => 'required']) !!}
                      </div>
                   </div>
                   <div class="col-md-2">
                        <a class="btn btn-xs btn-red remove-resource-row" href="#"><i class="fa fa-times fa fa-white"></i></a>
                   </div> 
               </div>
               -->
               @if(isset($clases))
               <?php //$i=0;?>
                 @foreach($clases as $clas)
                   <div class="row class-clone-class">
                  <div class="col-md-6 col-xs-6"> 
                      <div class="form-group resItemLocDd">
                      <!--
                         <select name="class{{$i}}" class="form-control service-cls-option onchange-set-neutral required" ><?php //data-itemqunt="{{ $clasValue->sr_item_quantity }}" ?>
                             <option value="">-- Select --</option>
                              @if(isset($classes))
                              @foreach($classes as $value)
                               <option value="{{ $value->cl_id }}" data-locid="{{ $value->cl_location_id }}" <?php //if($clasValue->sr_entity_id==$value->cl_id)// echo 'selected=selected';?>>{{ $value->cl_name }}</option>
                              @endforeach
                             @endif
                         </select>
                         -->
                         <p><a href="{{ route('classes.show', $clas->cl_id) }}">{{ $clas->cl_name }}</a></p>
                      </div>
                   </div> 
                   <div class="col-md-6 col-xs-6"> 
                      <div class="form-group resItemLocDd2">
                      <!--
                          {!! Form::select('clsItem'.$i, [], null, ['class' => 'form-control service-cls-item-op onchange-set-neutral' , 'required' => 'required']) !!}
                        -->
                        <p>{{ $data->getClassQuantity($data, $clas->cl_id) }}</p>
                      </div>
                   </div>
                   <!--
                   <div class="col-md-2">
                        <a class="btn btn-xs btn-red remove-resource-row" href="#"><i class="fa fa-times fa fa-white"></i></a>
                   </div> 
                   -->
               </div>
               <?php //$i++;?>
                 @endforeach
               @endif
            @else
               {!! displayNonClosingAlert('info', 'You can assign this resource once it has been saved.') !!}  
            @endif    
        </fieldset> 
         <!-- End: Classes -->    
        </div>
 </div>