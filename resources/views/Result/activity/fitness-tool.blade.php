@extends('Result.masters.app')

@section('page-title') 
  <span>GENERATE, CHOOSE OR DESIGN YOUR PERSONALISED PLAN</span> 
@stop

@section('required-styles')
  {!! Html::style('result/css/custom.css?v='.time()) !!}
  {!! Html::style('result/plugins/bootstrap-select-master/css/bootstrap-select.min.css') !!}
  {!! Html::style('result/plugins/DataTables/media/css/dataTables.bootstrap.min.css') !!}
  {!! Html::style('result/plugins/sweetalert/sweet-alert.css') !!}

  <!-- Start: Activities planner -->
  {!! HTML::style('result/plugins/fitness-planner/css/pt-planner.css') !!}
  {!! HTML::style('result/plugins/fitness-planner/css/jquery.ui.labeledslider.css') !!}
  {!! HTML::style('assets/plugins/fitness-planner/custom/style.css?v='.time()) !!} 
  {!! Html::style('assets/plugins/fitness-planner/css/api.css?v='.time()) !!}
  <!-- End: Activities planner -->

@stop

@section('content')
<!-- Start: Waiting Shield -->
<div id="waitingShield" class="hidden text-center">
    <div>
        <i class="fa fa-circle-o-notch"></i>
    </div>
</div>
<!-- End: Waiting Shield -->

<!-- Start: Activity planner tool -->
<div class="row">
  <div class="col-md-12">
    @include('includes.partials.add_exercise_modal',['exerciseData'=>$exerciseData])
    @include('includes.partials.activities_planner_tools',['parq'=>$parq])
  </div>
</div>
@stop

@section('required-script')
    {!! Html::script('plugins/bootstrap/js/tethr.js') !!}
    {!! Html::script('assets/js/metronic.js?v='.time()) !!}
    {!! Html::script('plugins/modernizr/modernizr.js') !!}
    {!! Html::script('result/plugins/moment/moment.min.js') !!}
    {!! Html::script('result/plugins/DataTables/media/js/jquery.dataTables.min.js') !!}
    {!! Html::script('result/plugins/DataTables/media/js/dataTables.bootstrap.js') !!}
    {!! Html::script('result/plugins/DataTables/media/js/dataTableDateSort.js') !!}
    {!! Html::script('result/plugins/bootstrap-select-master/js/bootstrap-select.min.js') !!}
    {!! Html::script('result/plugins/fitness-planner/js/jquery.ui.labeledslider.js') !!}
    {!! Html::script('result/plugins/jquery-validation/jquery.validate.min.js') !!}
    {!! Html::script('result/plugins/sweetalert/sweet-alert.min.js') !!}

    {!! Html::script('assets/js/helper.js?v='.time()) !!}

    <!-- Start: Activity Planner -->
    {!! Html::script('assets/js/fitness-planner/api.js?v='.time()) !!} 
    {!! Html::script('assets/js/fitness-planner/bodymapper.js?v='.time()) !!}
    {!! Html::script('assets/plugins/fitness-planner/jquery.json-2.4.min.js') !!}
    {!! Html::script('assets/plugins/fitness-planner/custom/js/jquery.placeholder.js') !!}
    {!! Html::script('assets/plugins/fitness-planner/custom/js/jquery.ui.touch-punch.min.js') !!}
    {!! Html::script('assets/plugins/fitness-planner/custom/jwplayer/jwplayer.js') !!}
    {!! Html::script('assets/plugins/fitness-planner/js/jquery.ui.labeledslider.js') !!}
    {!! Html::script('assets/plugins/fitness-planner/custom/js/popup.js?v=1') !!}
    {!! Html::script('assets/js/fitness-planner/fitness-planner.js?v='.time()) !!}
    <!-- ENd: Activity Planner -->

<script>
  $(document).ready(function(){
    $.fn.dataTable.moment('ddd, D MMM YYYY');
    $('#fitness-datatable').dataTable({"searching": false, "paging": false, "info": false });  
  });
</script>
@stop

@section('script-handler-for-this-page')
  Metronic.init();
  $( ".panel-collapse.closed" ).trigger( "click" );
@stop