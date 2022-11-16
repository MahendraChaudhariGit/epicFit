<!-- start: Recure class delete modal -->
<div class="modal fade" id="recurClassDeleteModal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button aria-label="Close" class="close" data-dismiss="modal" type="button">
                    <span aria-hidden="true">
                        ×
                    </span>
                </button>
                <h4 class="modal-title">
                    Delete Recuring Classes
                </h4>
            </div>
            <div class="modal-body bg-white">
                <form id="recurClassDeleteFormProRate" method="POST">
                    <div class="row">
                        <div class="col-md-12">
                            <h4>
                                Select recurence class for deletion:
                            </h4>
                            <ul class="list-group" id="services">
                            </ul>
                            <div class="form-group">
                                <ul width="100%">
                                    @foreach($futureRecureClassesProRate as $futureClass)
                                    @if($futureClass->pivot->secc_class_extra == 0)

                                    <li>
                                        <div class="fc-event-container">
                                            <a class="calendEventWrap" id="class-13976">
                                                <div class="fc-content tooltipstered calendEvent">
                                                    <input id="{{ isset($futureClass->sec_secr_id) ? $futureClass->sec_secr_id : '' }}" name="class-{{ isset($futureClass->sec_secr_id) ? $futureClass->sec_secr_id : '' }}" type="checkbox" value="{{ isset($futureClass->sec_secr_id) ? $futureClass->sec_secr_id : '' }}">
                                                        <label for="{{ isset($futureClass->sec_secr_id) ? $futureClass->sec_secr_id : '' }}">
                                                            <div class="eventStatusIcon">
                                                            </div>
                                                            <strong>
                                                                <span class="eventTimeRange">
                                                                    {{ isset($futureClass->sec_start_datetime) ? date( 'l, d M, Y', strtotime($futureClass->sec_start_datetime)) : '' }}
                                                                </span>
                                                                <br>
                                                                    <span class="eventTimeRange">
                                                                        {{ isset( $futureClass->sec_start_datetime) ? date('H:i A', strtotime( $futureClass->sec_start_datetime)) : '' }} - {{ isset($futureClass->sec_end_datetime) ? date('H:i A', strtotime($futureClass->sec_end_datetime)) : ''}}
                                                                    </span>
                                                                </br>
                                                            </strong>
                                                            <br>
                                                                {{ isset($futureClass->clas->cl_name) ? ucwords($futureClass->clas->cl_name): '' }}
                                                            </br>
                                                        </label>
                                                    </input>
                                                </div>
                                            </a>
                                        </div>
                                       
                                    </li>
                                    @endif
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </form>
                <form id="recurClassDeleteFormNextCycle" method="POST">
                    <div class="row">
                        <div class="col-md-12">
                            <h4>
                                Select recurence class for deletion:
                            </h4>
                            <ul class="list-group" id="services">
                            </ul>
                            <div class="form-group">
                                <ul width="100%">
                                    @foreach($futureRecureClassesNextCycle as $futureClass)
                                    @if($futureClass->pivot->secc_class_extra == 0)
                                    <li>
                                        <div class="fc-event-container">
                                            <a class="calendEventWrap" id="class-13976">
                                                <div class="fc-content tooltipstered calendEvent">
                                                    <input id="{{isset($futureClass->sec_secr_id) ? $futureClass->sec_secr_id : '' }}" name="class-{{ isset($futureClass->sec_secr_id) ? $futureClass->sec_secr_id : '' }}" type="checkbox" value="{{ isset($futureClass->sec_secr_id) ? $futureClass->sec_secr_id : ''  }}">
                                                        <label for="{{ isset($futureClass->sec_secr_id) ? $futureClass->sec_secr_id : '' }}">
                                                            <div class="eventStatusIcon">
                                                            </div>
                                                            <strong>
                                                                <span class="eventTimeRange">
                                                                    {{ isset($futureClass->sec_start_datetime) ? date( 'l, d M, Y', strtotime($futureClass->sec_start_datetime)) : '' }}
                                                                </span>
                                                                <br>
                                                                    <span class="eventTimeRange">
                                                                        {{ isset( $futureClass->sec_start_datetime) ? date('H:i A', strtotime( $futureClass->sec_start_datetime)) : '' }} - {{ isset($futureClass->sec_end_datetime) ? date('H:i A', strtotime($futureClass->sec_end_datetime)) : ''}}
                                                                    </span>
                                                                </br>
                                                            </strong>
                                                            <br>
                                                                {{ isset($futureClass->clas->cl_name) ? ucwords($futureClass->clas->cl_name): '' }}
                                                            </br>
                                                        </label>
                                                    </input>
                                                </div>
                                            </a>
                                        </div>
                                    </li>
                                    @endif
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-default" data-dismiss="modal" type="button">
                    Close
                </button>
                <button class="btn btn-default submit" id="proceedWithoutDeleting" type="button">
                    Continue Without Deleting
                </button>
                <button class="btn btn-primary submit" id="recurClassDeleteSubmit" type="button">
                    Delete
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="recurSessionDeleteModal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button aria-label="Close" class="close" data-dismiss="modal" type="button">
                    <span aria-hidden="true">
                        ×
                    </span>
                </button>
                <h4 class="modal-title">
                    Delete Recuring Extra Sessions
                </h4>
            </div>
            <div class="modal-body bg-white">
                <form id="recurSessionDeleteFormProRate" method="POST">
                    <input type="hidden" name="sessionType" value="">
                    <div class="row">
                        <div class="col-md-12">
                            <h4>
                                Select recurence session for deletion:
                            </h4>
                            <ul class="list-group" id="extraSessions">
                            </ul>
                            <div class="form-group">
                                <ul width="100%" class="sessionExtra">
                                   
                                </ul>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            {{-- <div class="form-group">
                <div>
                  <div class="radio clip-radio radio-primary radio-inline m-b-0">
                    <input name="updateOptionSession" id="updateSessionWithProRate" value="1" type="radio" class="onchange-set-neutral">
                    <label for="updateSessionWithProRate">
                      Change session immediately with pro-rate.
                    </label>
                  </div>
                </div>
                <div>
                  <div class="radio clip-radio radio-primary radio-inline m-b-0">
                    <input name="updateOptionSession" id="sessionNextCycle" value="2" type="radio" class="onchange-set-neutral">
                    <label for="sessionNextCycle">
                      Change session on next cycle.
                    </label>
                  </div>
                </div>
                <span class="help-block"></span>
              </div> --}}
            <div class="modal-footer">
                <button class="btn btn-default" data-dismiss="modal" type="button">
                    Close
                </button>
                <button class="btn btn-primary submit" id="recurSessionDeleteSubmit" type="button">
                    Delete
                </button>
            </div>
        </div>
    </div>
</div>
<style type="text/css">
    #recurClassDeleteModal ul {
    padding: 0px;
    text-align: center;
}
#recurClassDeleteModal ul li {
    width: 32%;
    display: inline-block;
    margin-bottom: 10px;
    text-align: left;
    vertical-align: top;
}
#recurClassDeleteModal a.calendEventWrap {
    color: rgb(0, 0, 0);
    display: block;
    background: #f8d9b4;
    padding: 5px 10px;
    border: 1px solid #ddd;
    position: relative;
}
#recurClassDeleteModal ul li input[type="checkbox"]{
    position: absolute;
    bottom: 3px;
    left: 43%;
}
#recurClassDeleteModal li label{
    color: black;
    font-size: 12px;
    width: 100%;
    padding-bottom: 15px;
}

#recurSessionDeleteModal ul {
    padding: 0px;
    text-align: center;
}
#recurSessionDeleteModal ul li {
    width: 32%;
    display: inline-block;
    margin-bottom: 10px;
    text-align: left;
    vertical-align: top;
}
#recurSessionDeleteModal a.calendEventWrap {
    color: rgb(0, 0, 0);
    display: block;
    background: #f8d9b4;
    padding: 5px 10px;
    border: 1px solid #ddd;
    position: relative;
}
#recurSessionDeleteModal ul li input[type="checkbox"]{
    position: absolute;
    bottom: 3px;
    right: 2%;
}
#recurSessionDeleteModal li label{
    color: black;
    font-size: 12px;
    width: 100%;
    padding-bottom: 15px;
}
#recurSessionDeleteModal{
    z-index: 999999 !important;
}
</style>
<!-- end: Recure class delete modal -->
