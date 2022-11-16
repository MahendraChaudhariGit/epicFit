
<!-- start:CLIENT MODEL For NOTES -->
   <!--Notes Modal -->
  <div class="modal fade" id="notesModal" role="dialog" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Notes</h4>
        </div>
        <div class="modal-body bg-white">
            {!! Form::open(['url' => '','id'=>'notes-form']) !!}

              {!! Form::hidden('notes_id') !!}
               <div class="form-group check-notes-type hidden">
                  {!! Form::label('type','Category *',['class'=>'strong']) !!}
                  {!! Form::select('type',isset($notesCat)?array_merge(array(''=>'-- Select --','contact'=>'Contact','general'=>'General','makeup'=>'Makeup'),$notesCat):[''=>'-- Select --','contact'=>'Contact','general'=>'General','makeup'=>'Makeup'],null,['class'=>'form-control','id'=>'notesType','required']) !!}

                  <!-- {!! Form::select('type',[''=>'-- Select --','contact'=>'Contact','general'=>'General','makeup'=>'Makeup'],null,['class'=>'form-control','id'=>'notesType','required']) !!} -->
                  
               </div>  
               <div class="form-group">
                  {!! Form::textarea('note',null,['class'=>'form-control textarea','required']) !!}
               </div>   
            {!! Form::close() !!}
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary notes-create" >Create note</button>
        </div>
      </div>
      
    </div>
  </div>
<!-- end:CLIENT MODEL for NOTES -->