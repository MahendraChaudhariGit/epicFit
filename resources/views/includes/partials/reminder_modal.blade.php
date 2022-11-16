<!---Start: show upcoming task with reminder or overdue (reminder task) -->
    <div class="modal fade" id="tasksReminderModal" role="dialog"  aria-hidden="true" >
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                    </button>
                    <h4 class="modal-title">Tasks</h4>
                </div>
                <div class="modal-body bg-white">
                <!-- <input type="hidden" name="taskIds" value="(111,112,113,114,115,116,117,118)"> -->
                {!! Form::hidden('taskIds') !!}
                    <ul class="todo" id="taskfield" ><!-- ps-container panel-scroll height-330 perfect-scrollbar -->
                        
                    </ul>

                </div>
                <div class="modal-footer">
                    <!-- <button type="button" class="btn btn-primary btn-o" data-dismiss="modal">Close
                    </button> -->
                    <!-- <button type="button" class="btn btn-primary submit" >Submit</button> -->
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Done
                    </button>
                </div>
                
            </div>
        </div>
    </div>
<!---Start: show upcoming task with reminder or overdue (reminder task) -->

<!---Start: show task details on click task area (show task) -->
    <div class="modal fade" id="showModal" role="dialog"  aria-hidden="true" >
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                    </button>
                    <h4 class="modal-title">Show Task</h4>
                </div>
                <div class="modal-body bg-white">
                    {!! Form::open(['url' => '', 'role' => 'form']) !!}
                    {!! Form::hidden('taskID') !!}
                    <input type="hidden" name="taskFormId" value="">
                    <div class="row">
                        <div class="col-md-12">
                            <fieldset class="padding-15"> <!-- class="client-form" -->
                                <legend>
                                    Task 
                                </legend>
                                <div class="form-group">
                                    <strong>Task Name </strong>
                                    <div>
                                    <span id="taskName"></span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <strong>Task Due Date </strong>
                                    <div class="row">
                                        <div class="col-md-12"> 
                                        <span id="taskDate"></span>
                                        <span id="taskTime"></span>
                                        </div>
                                    </div>
                                </div>
                            
                                <div class="form-group">
                                <strong>Task Category</strong>
                                <div>
                                    <span id="taskCategory"></span>
                                </div>
                                </div>

                                <div class="form-group">
                                {!! Form::label('taskNote', 'Task Note *', ['class' => 'strong']) !!}
                                {!! Form::textarea('taskNote', null, ['class' => 'form-control textarea']) !!}
                                </div>

                            <div class="form-group form-inline hidden rmb">
                                <strong>Remind Me </strong>
                                <div>
                                    Before
                                    <span id="reminderVal"></span>
                                </div>
                            </div>

                            </fieldset>

                            <fieldset class="padding-15 repeat hidden"><!-- class="client-form" -->
                                <legend>
                                    Recurrence 
                                </legend>
                                
                                <!--<div>-->
                                    <div class="form-group">
                                        <strong>Repeat</strong>
                                        <div> 
                                            <span id="eventRepeat"></span>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <strong >Repeat every</strong>
                                        <div>
                                            <span id="eventRepeatInterval"></span>
                                        </div>
                                    </div> 

                                    <div class="form-group">
                                        <strong >End</strong>
                                        <div>
                                            <span id="eventRepeatEnd"></span>
                                            <span id="eventRepeatNo"></span>
                                        </div>
                                    </div>      
                                <!--</div>--> 
                            </fieldset>
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary btn-o" data-dismiss="modal">Close
                    </button>
                    <button type="button" class="btn btn-primary submit" >Submit</button>
                </div>
                
            </div>
        </div>
    </div>
<!---Start: show task details on click task area (show task) -->    