    <div class='modal fade' id='setup-section-modal' tabindex='-1'>
        <div class='modal-dialog'>
            <div class='modal-content'>
                <div class='modal-header'>
                    <button aria-hidden='true' class='close' data-dismiss='modal' type='button'>×</button>
                    <h4 class='modal-title' id='myModalLabel'>Dynamic Section</h4>
                </div>
                <div class='modal-body'>
                    <strong>Section Title</strong>
                    <div class="responsive-table">
                        <div class="scrollable-area">
                            <table class="table data-table table table-bordered table-striped">
                                <tbody>
                                    <tr>
                                        <td>
                                            <input class='form-control setup_section_name' data-rule-required='true'
                                                   name='section' type='text' autufocus="true">
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
            </div>
            <div class='modal-footer'>
                <button class='btn btn-default overflow' data-dismiss='modal' type='button'>Close</button>
                <button class='btn btn-primary overflow'  id="add-setup-section" data-dismiss='modal' type='button'>Add Section</button>
            </div>
        </div>
    </div>
</div>
@section('custom-script')
    <script type="text/javascript">

        $(document).ready(function () {


        });
    </script>
@stop