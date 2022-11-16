    <div class='modal fade' id='setup-modal' tabindex='-1'>
        <div class='modal-dialog modal-md'>
            <div class='modal-content'>
                <div class='modal-header'>
                    <button aria-hidden='true' class='close' data-dismiss='modal' type='button'>&nbsp; ×</button>
                    <h4 class='modal-title' id='myModalLabel'>Enter Expenses
                        <a class='btn btn-default p-y-0 pull-right add_dynamic_sec_btn'
                           data-toggle='modal' href='#setup-section-modal' role='button' value="Small Default">
                            <span style="color:#ff4401;">Add Section</span> </a>
                    </h4>
                </div>
                <div class='modal-body setup-expense-content'>
                    <div class='setup-dynamic-section'></div>
                </div>
            <div class='modal-footer'>
                <button class='btn btn-default' data-dismiss='modal' type='button'>Close</button>
                <button class='btn btn-primary setup_expense_submit' data-dismiss="modal" type='button'>Save changes</button>
            </div>
        </div>
    </div>
</div>
@include('financialtoolnew.setup-expenses.dynamic_section-modal')
@include('financialtoolnew.setup-expenses.dynamic_field-modal')