<div class="col-md-6">
    <fieldset class="padding-15">
        <legend>Company Tax</legend>
        @if(count($companyTaxes) == 0)
        <a class="btn btn-primary m-t-10 pull-right m-b-10 addTax"
           data-toggle="modal" href="#company-tax-modal"><i class="ti-plus"></i> Add Tax</a>
        @endif

        <table class="table table-striped table-bordered table-hover m-t-10" id="company-tax-datatable">
            <thead>
                <tr>
                    <th>#</th>
                    <th class="hidden-xs">Tax Name</th>
                    <th class="hidden-xs">Tax Code</th>
                    <th class="center">Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php $i = 1; ?>
           @foreach($companyTaxes as $companyTax)
               <?php
               $taxTempName = ($companyTax->tax_name) ? $companyTax->tax_name : '-';
               $taxName = $companyTax->tax_type == 'other' ? $taxTempName : $companyTax->tax_type;
               ?>
               <tr>
                   <td>{{$i}}</td>
                   <td>{{ucfirst($taxName)}}</td>
                   <td>{{$companyTax->tax_code}}</td>
                   <td class="center">
                       <button  type="button" data-id="{{$companyTax->id}}"  class="btn btn-xs btn-default
                       companyTaxEdit" > <i class="fa fa-pencil"></i> Edit
                       </button>
                       <button  type="button" data-id="{{$companyTax->id}}"  class="btn btn-xs btn-default
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
@include('financialtool.settings-and-preferences.partials.company-tax')