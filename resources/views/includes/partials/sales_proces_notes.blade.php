
<div class="panel panel-white" id="notesPanel">
    <!-- start: PANEL HEADING -->
    <div class="panel-heading">
        <h5 class="panel-title">
            <span class="icon-group-left">
                <i class="fa fa-pencil"></i>
            </span> 
            Notes
            <span class="icon-group-right">
                <a class="btn btn-xs pull-right" href="#" data-toggle="modal" data-target="#notesModal">
                    <i class=" fa fa-plus fa fa-white"></i>
                </a>
                <a class="btn btn-xs pull-right panel-collapse closed" href="#" data-panel-group="client-overview">
                    <i class="fa fa-chevron-down"></i>
                </a>
            </span>
        </h5>
    </div>    
    <!-- end: PANEL HEADING -->
    <!-- start: PANEL BODY -->

    <div class="panel-body"> 
        @if(count($noteArray))
        @foreach($noteArray as $noteData)
          <div class="col-md-12 {{$noteData->cn_type}}-{{$noteData->cn_id}}">
                    <p> @if($noteData->cn_source)
                    <small>({!! $noteData->cn_source !!})</small>
                    @endif</p>

                    <p>{!! $noteData->cn_notes !!} </p>
                    <p>
                    <small>Created on: {{ setLocalToBusinessTimeZone($noteData->created_at, 'dateString')}}&nbsp;&nbsp; | &nbsp;&nbsp;
                    Category: {{ isset($noteData->category)?$noteData->category->nc_name:'' }} </small></p>
                    <hr class="notes-hr">
          </div>         
        @endforeach
        @endif    
    </div>
    <!-- end: PANEL BODY -->
</div>


  
  

