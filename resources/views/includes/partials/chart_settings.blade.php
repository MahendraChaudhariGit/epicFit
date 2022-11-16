<?php
    $flag=false;
   if(isset($client_chart) && isset($sales_chart)){
        $flag=true;
    }
?>
<div class="modal fade" id="chartSetting" role="dialog">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Persanalize your chart </h4>
            </div>
            <div class="modal-body bg-white">
                {!! Form::open(['url' => '', 'role' => 'form', 'id'=>'chart-setting-form']) !!}
                {!! Form::hidden('chart_type') !!}
                <div id="clientsChart">
                    <div class="row">                    
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="checkbox clip-check check-primary m-b-0 m-t-0">
                                <input type="checkbox" name="active" id="active" value="1"  class="no-clear" <?php if($flag && isset($client_chart->active))echo 'checked'; ?> >
                                <label for="active" class="m-r-0 strong">Active</label>
                                <input type="color" name="activeColor" class="m-l-30" value="<?php if($flag && isset($client_chart->active))echo $client_chart->active; ?>">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="checkbox clip-check check-primary m-b-0 m-t-0">
                                <input type="checkbox" name="contra" id="contra" value="1" class="no-clear" <?php if($flag && isset($client_chart->contra))echo 'checked'; ?> >
                                <label for="contra" class="m-r-0 strong">Contra</label>
                                <input type="color" name="contraColor" class="m-l-30" value="<?php if($flag && isset($client_chart->contra))echo $client_chart->contra; ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">    
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="checkbox clip-check check-primary m-b-0 m-t-0">
                                <input type="checkbox" name="inactive" id="inactive" value="1" class="no-clear" <?php if($flag && isset($client_chart->inactive))echo 'checked'; ?> >
                                <label for="inactive" class="m-r-0 strong">Inactive</label>
                                <input type="color" name="inactiveColor" class="m-l-30" value="<?php if($flag && isset($client_chart->inactive))echo $client_chart->inactive; ?>">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="checkbox clip-check check-primary m-b-0 m-t-0">
                                <input type="checkbox" name="on_hold" id="on_hold" value="1" class="no-clear" <?php if($flag && isset($client_chart->on_hold))echo 'checked'; ?> >
                                <label for="on_hold" class="m-r-0 strong">On Hold</label>
                                <input type="color" name="on_holdColor" class="m-l-30" value="<?php if($flag && isset($client_chart->on_hold))echo $client_chart->on_hold; ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">    
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="checkbox clip-check check-primary m-b-0 m-t-0">
                                <input type="checkbox" name="pending" id="pending" value="1" class="no-clear" <?php if($flag && isset($client_chart->pending))echo 'checked'; ?> >
                                <label for="pending" class="m-r-0 strong">Pending</label>
                                <input type="color" name="pendingColor" class="m-l-30" value="<?php if($flag && isset($client_chart->pending))echo $client_chart->pending; ?>">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="checkbox clip-check check-primary m-b-0 m-t-0">
                                <input type="checkbox" name="other" id="other" value="1" class="no-clear" <?php if($flag && isset($client_chart->other))echo 'checked'; ?> >
                                <label for="other" class="m-r-0 strong">Other</label>
                                <input type="color" name="otherColor" class="m-l-30" value="<?php if($flag && isset($client_chart->other))echo $client_chart->other; ?>">
                                </div>
                            </div>
                        </div>    
                    </div>
                </div>
                <div id="salesProChart">
                    <div class="row">                    
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="checkbox clip-check check-primary m-b-0 m-t-0">
                                <input type="checkbox" name="sales_pending" id="sales_pending" value="1" class="no-clear" <?php if($flag && isset($sales_chart->sales_pending))echo 'checked'; ?> >
                                <label for="sales_pending" class="m-r-0 strong">Pending</label>
                                <input type="color" name="sales_pendingColor" class="m-l-30" value="<?php if($flag && isset($sales_chart->sales_pending))echo $sales_chart->sales_pending; ?>">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="checkbox clip-check check-primary m-b-0 m-t-0">
                                <input type="checkbox" name="pre_consultation" id="pre_consultation" value="1" class="no-clear" <?php if($flag && isset($sales_chart->pre_consultation))echo 'checked'; ?> >
                                <label for="pre_consultation" class="m-r-0 strong">Pre Consultation</label>
                                <input type="color" name="pre_consultationColor" class="m-l-30" value="<?php if($flag && isset($sales_chart->pre_consultation))echo $sales_chart->pre_consultation; ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">                    
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="checkbox clip-check check-primary m-b-0 m-t-0">
                                <input type="checkbox" name="pre_benchmark" id="pre_benchmark" value="1" class="no-clear" <?php if($flag && isset($sales_chart->pre_benchmark))echo 'checked'; ?> >
                                <label for="pre_benchmark" class="m-r-0 strong">Pre Benchmark</label>
                                <input type="color" name="pre_benchmarkColor" class="m-l-30" value="<?php if($flag && isset($sales_chart->pre_benchmark))echo $sales_chart->pre_benchmark; ?>">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="checkbox clip-check check-primary m-b-0 m-t-0">
                                <input type="checkbox" name="pre_training" id="pre_training" value="1" class="no-clear" <?php if($flag && isset($sales_chart->pre_training))echo 'checked'; ?> >
                                <label for="pre_training" class="m-r-0 strong">Pre Training</label>
                                <input type="color" name="pre_trainingColor" class="m-l-30" value="<?php if($flag && isset($sales_chart->pre_training))echo $sales_chart->pre_training; ?>">
                                </div>
                            </div>
                        </div>
                    </div>   
                </div>       
                {!! Form::close() !!}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="chart-setting-save">Save</button>
            </div>
        </div>
    </div>
</div>