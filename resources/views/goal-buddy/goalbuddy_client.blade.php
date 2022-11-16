<div class="container ">
  <div class="col-md-8" >
    <div class="goalbuddy-client-tbl">
      <table class="table table-striped table-bordered table-hover m-t-10 goalbuddy-clienttbl" id="client-datatable" >
        <thead>
          <tr>
            <th></th>
            <th>Full Name</th>
            <th>Company Name</th>
          </tr>
        </thead>
        <tbody id="tBody">
          @if(isset($allClientArray) && count($allClientArray))
          @foreach($allClientArray as $clientdata)
          <tr>
            <td><img src="{{ dpSrc($clientdata->profilepic,$clientdata->gender) }}" /> </td>
            <td>{{ isset($clientdata->firstname)?$clientdata->firstname :null.' '.isset($clientdata->lastname)?$clientdata->lastname :null }} </td>
            <td>{{isset($clientdata->trading_name)?$clientdata->trading_name :null }}</td>
          </tr>
          @endforeach
          @endif  
        </tbody>
      </table>
    </div>
  </div>
  <div class="col-md-4">
    <div class="row">
      <h3>Search:</h3>
      <form id="search_form" action="{{ route('searchingclientgoal') }}" method="get">
        <div class="form-group">
          <label for="buddy_keyword">Keyword</label>
            <input type="text" name="buddy_keyword" id="buddy_keyword" class="form-control" required/>
        </div>
        <div class="form-group">
          <label for="sel1"></label>
          {!! Form::label('country_Id', 'Select Country:', ['class'=>'strong']) !!}
          {!! Form::select('goalcountry', isset($countries)?$countries:[], null, ['class'=>'form-control countries', 'id'=>'country_Id']) !!}    
        </div>
        <div class="form-group">
          <label for="sel12">Select State:</label>
          <select class="form-control states bootstrap-select" name="state" id="sel12 state_Id "  >
            <option value="">Select State</option>
          </select>
        </div>
        <div class="form-group">
          <label for="sel13">Select City:</label>
          <input type="text" name="cities"  class="form-control cities" />
        </div>
        <div class="row">
          <div class="col-md-6">
            <button type="button" class="btn btn-primary btn-o next-step btn-wide pull-right search-goal" >
              Search <i class="fa fa-arrow-circle-right"></i>
            </button>
          </div>
          <div class="col-md-6">
            <button type="reset" class="btn btn-primary btn-o reset_btn btn-wide pull-left">
              Reset <i class="fa fa-arrow-circle-right"></i>
            </button>
          </div>
        </div>  
      </form>                      
    </div>
    <hr />
    <div class="row category_section">
      <h3>Categories:</h3>
      <a href="#"><p>All Category</p></a>
      <a href="#"><p>i will become a leader</p></a>
      <a href="#"><p>i will have better relationship</p></a>
      <a href="#"><p>i will become a leader</p></a>
      <a href="#"><p>i will have better relationship</p></a>
      <a href="#"><p>i will become a leader</p></a>
      <a href="#"><p>i will have better relationship</p></a>
    </div>
  </div>
</div>