@extends('masters.app')



@section('required-styles')

{!! Html::style('plugins/bootstrap-select-master/css/bootstrap-select.min.css') !!}
{!! Html::style('css/goal-buddy.css?v='.time()) !!}
{!! Html::style('plugins/DataTables/media/css/DT_bootstrap.css') !!}
{!! Html::style('plugins/sweetalert/sweet-alert.css') !!} 
{!! Html::style('css/custom.css?v='.time()) !!}


@stop()

@section('page-title')
   <span >Goal Buddy </span> 
@stop

@section('content')

 <!-- start: Delete Form -->
    @include('partials.delete_form')
    <!-- end: Delete Form -->
<div class="container ">
<div class="col-md-8" >
<div class="goalbuddy-client-tbl">
 <table class="table table-striped table-bordered table-hover m-t-10" id="client-datatable" >
    <thead>
        <tr>
         <th></th>
            <th>Full Name</th>
            <th>Company Name</th>
        </tr>
    </thead>
    <tbody id="tBody">
	@foreach($clientArray as $clientdata)
        <tr>
            <td><img src="{{ dpSrc($clientdata->profilepic,$clientdata->gender) }}" /> </td>
            <td>{{ $clientdata->firstname.' '.$clientdata->lastname }} </td>
            <td>{{ $clientdata->trading_name}}</td>
        </tr>
    @endforeach 
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
 <label for="sel1">Select Country:</label>
  <select class="form-control countries" name="country" id="country_Id sel1">
    <option value="">Select Country</option>
    <?php $countries = \Country::getCountryLists(); 
	foreach($countries as $key => $Countryvalue){
		
		echo '<option value="'.$key.'">'.$Countryvalue.'</option>';
	}
	
	 ?>
  </select>
              
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
<button type="submit" class="btn btn-primary btn-o next-step btn-wide pull-right " >
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




@stop

@section('required-script')
{!! Html::script('plugins/jquery-validation/dist/jquery.validate.js') !!}

{!! Html::script('plugins/bootstrap-datepicker/js/bootstrap-datepicker.js') !!}
{!! Html::script('plugins/bootstrap-select-master/js/bootstrap-select.min.js') !!}
	{!! Html::script('plugins/sweetalert/sweet-alert.min.js') !!} 	
{!! Html::script('plugins/DataTables/media/js/jquery.dataTables.min.js') !!}
{!! Html::script('plugins/DataTables/media/js/DT_bootstrap.js') !!}

<script>
var pagingLinkAmnt = 3;
</script>
{!! Html::script('plugins/DataTables/media/js/DT_bootstrap.js') !!}
{!! Html::script('js/helper.js?v='.time()) !!}


<script  >
    $(document).ready(function() {
	        $('.countries').on('changed.bs.select', function(e){
				updateState($(this));
				});

             updateState($('#search_form').find('select.countries'));

             $('#client-datatable').dataTable();
			 
			  /*$('#client-datatable').on('init', function(){
				    if(typeof cookieSlug != 'undefined' && cookieSlug != null){
						var datatableRowsCountDd = $('#client-datatable_length select');
						setDatatableRowsAmount(datatableRowsCountDd, cookieSlug);
						datatableRowsCountDd.on("change", function(){
							var cookieName = calcCookieName(cookieSlug); 
							$.cookie(cookieName, datatableRowsCountDd.val())
						});   
					}
				}).dataTable();*/
				  
	
		
       
    
          });
 
		
		
		
			function updateState(contryDd){
			var public_url = $('meta[name="public_url"]').attr('content');
			if(contryDd.length){
				
			var country_code = contryDd.val(),
			selectedStates = contryDd.closest('form').find('select.states');
			
			if(country_code == "" || country_code == "undefined" || country_code == null){
			selectedStates.html('<option value="">-- Select --</option>');
			selectedStates.selectpicker('refresh');
			}
			else{	
			$.ajax({
			url: public_url+'countries/'+country_code,
			method: "get",
			data: {},
			success: function(data) {
			var defaultState = selectedStates.data('selected'),
			formGroup = selectedStates.closest('.form-group');
			
			selectedStates.html("");
			$.each(data, function(val, text){
			var option = '<option value="">Select State</option><option value="' + val + '"';
			if(defaultState != '' && defaultState != null && val == defaultState)
			option += ' selected';
			option += '>' + text + '</option>';
			selectedStates.append(option);
			});
			
			contryDd.selectpicker('refresh');
			selectedStates.selectpicker('refresh');
			//setFieldValid(formGroup, formGroup.find('span.help-block'))
			}
			});
			}
			}
			} 
 
      </script>
@stop()

