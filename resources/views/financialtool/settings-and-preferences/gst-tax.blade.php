<div class="col-md-6">
    <fieldset class="padding-15">
        <legend>GST Tax</legend>
        @if(count($gstTaxes) == 0)
        <a class="btn btn-primary m-t-10 pull-right m-b-10 addTax"
           data-toggle="modal" href="#gst-tax-modal"><i class="ti-plus"></i> Add Tax</a>
        @endif

        <table class="table table-striped table-bordered table-hover m-t-10" 
              id="gst-tax-datatable">
            <thead>
                <tr>
                    <th>#</th>
                    <th class="hidden-xs">Tax Name</th>
                    <th class="hidden-xs">GST Number</th>
                    <th class="center">Actions</th>
                </tr>
            </thead>
            <tbody>
             <?php $i = 1; ?>
           @foreach($gstTaxes as $gstTax)
               <?php
               $taxName = $gstTax->tax_type == 'gst' ? $gstTax->tax_type : '';
               ?>
               <tr>
                   <td>{{$i}}</td>
                   <td>{{ucfirst($taxName)}}</td>
                   <td>{{$gstTax->tax_code}}</td>
                   <td class="center">
                       <button  type="button" data-id="{{$gstTax->id}}"  class="btn btn-xs btn-default
                       gstTaxEdit" > <i class="fa fa-pencil"></i> Edit
                       </button>
                       <button  type="button" data-id="{{$gstTax->id}}"  class="btn btn-xs btn-default
                       delete-ft-pref" > <i class="fa fa-trash-o"></i> Delete
                       </button>
                    </td>
                </tr>
                <?php $i++; ?>
            @endforeach 
            </tbody>
        </table>
    </fieldset>
</div>
<!-- modal -->
@include('financialtool.settings-and-preferences.partials.gst-tax')