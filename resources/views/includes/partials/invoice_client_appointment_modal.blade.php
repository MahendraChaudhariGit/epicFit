<!-- Client Appointment Modal -->
<div class="modal fade" id="clientAppointModal" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">Other appointments for </h4>
            </div>
            <div class="modal-body bg-white">
            {!! Form::open(['url' => '', 'role' => 'form', 'id' =>'appointment-form']) !!}
                <table class="table table-hover" id="sample-table-1">
                    <thead>
                        <tr class ="all-apointment hide">
                            <th colspan="6">
                                <div class="checkbox clip-check check-primary m-b-0">
                                    <input type="checkbox" name="clientAppointCheckAll" value="1" id="all-appointment-select">
                                    <label for="all-appointment-select">
                                        <strong> Select all</strong>
                                    </label>
                                </div>
							</th>
                        </tr>
                    </thead>
                    <tbody class="appointment-section">
					</tbody>
                </table>
                {!! Form::close() !!}
            </div>
            <div class="modal-footer">
                <div class="row text-left">
                    <div class="col-md-6">
                        <button type="button" class="btn btn-default btn-wide create-invoice-back " data-dismiss="modal" data-toggle="modal" data-target="#invoiceModal">
                            <i class="fa fa-arrow-left"></i>
                            Back to invoice
                        </button>
                    </div>
                    <div class="col-md-6">
                        <button type="button" class="btn btn-success btn-wide submit add-to-invoice pull-right" data-dismiss="modal" data-toggle="modal" data-target="#invoiceModal">
                            <i class="fa fa-plus"></i>
                            Add to invoice
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>