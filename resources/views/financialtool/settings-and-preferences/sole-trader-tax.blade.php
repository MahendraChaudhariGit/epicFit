<div class="col-md-6">
    <fieldset class="padding-15">
        <legend>Sole Trader Tax</legend>
        @if(count($soleTraderTaxes) == 0)
        <a class="btn btn-primary m-t-10 pull-right m-b-10 addTax"
           data-toggle="modal" href="#sole-trader-tax-modal"><i class="ti-plus"></i> Add Tax</a>
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
            @foreach($soleTraderTaxes as $soleTraderTax)
                <?php
                $taxTempName = ($soleTraderTax->tax_name) ? $soleTraderTax->tax_name : '-';
                $taxName = $soleTraderTax->tax_type == 'other' ? $taxTempName : $soleTraderTax->tax_type;
                ?>
                <tr>
                    <td>{{$i}}</td>
                    <td>{{ucfirst($taxName)}}</td>
                    <td>{{$soleTraderTax->tax_code}}</td>
                    <td class="center">
                        @if(count($partnershipTaxes) == 0)
                        <button type="button" title="Clone to Partnership Tax" data-id="{{$soleTraderTax->id}}" class="btn btn-xs btn-default
                        clone">
                            <i class="fa fa-copy"></i> Clone
                        </button>
                        @endif
                        <button type="button" data-id="{{$soleTraderTax->id}}" class="btn btn-xs btn-default
                        soleTraderTaxEdit">
                            <i class="fa fa-pencil"></i> Edit
                        </button>
                        <button  type="button" data-id="{{$soleTraderTax->id}}"  class="btn btn-xs btn-default
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
@include('financialtool.settings-and-preferences.partials.sole-trader-tax') <!-- sole trader modal -->