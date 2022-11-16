<div class="recipes-section">
	<article class="card-group-item re">
		<header class="card-header">
			<h6 class="css-yarn54">Filter By</h6>
		</header>
		<div class="filter-content">
			<div class="card-body">
					{{-- @csrf --}}
					<div class="label-head">
						<label class="css-10yup1s-container">
							<input type="checkbox" class="css-tyi62s-input">
							<span class="css-1q786di">
								Test Kitchen-Approved
							</span>
						</label> <!-- form-check.// -->
						<label class="css-10yup1s-container">
							<input type="checkbox" class="css-tyi62s-input">
							<span class="css-1q786di">
								Contest Winners
							</span>
						</label>
						<!-- form-check.// -->
						<label class="css-10yup1s-container">
							<input type="checkbox" class="css-tyi62s-input">
							<span class="css-1q786di">
								Featured
							</span>
						</label>
					</div>
					<div class="col-md-12 col-xs-12 p-0">
						<hr>
					</div>
					<div class="col-md-12 col-xs-12 p-0">
						<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                       {{-- ------------------------------------ --}}
					  
					   @foreach($main_cat as $key => $cat)
				
							<div class="panel panel-default">
								<div class="" role="tab" id="headingOne">
									<h4 class="panel-title">
										<a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne-{{$key+1}}" aria-expanded="false" aria-controls="collapseOne" class="collapsed">

										{{-- {{$cat->name}} --}}
										{{ ucfirst($cat->name)}}
										</a>
									</h4>
								</div>
									<div id="collapseOne-{{$key+1}}" class="collapse" role="tabpanel" aria-labelledby="headingOne" aria-expanded="false" style="height: 0px;">
										<div class="label-head">
										
										   @foreach($cat->subCategory as $sub_cat)
												<label class="css-10yup1s-container">
													<input type="checkbox" @if(count($tags) > 0) @foreach($tags as $tag) @if($tag == $sub_cat->id) checked @endif @endforeach @endif class="css-tyi62s-input filter-sub-cat-checkbox filter-tag-filter-option" name="filter_tags[]" value="{{ $sub_cat->id }}" onchange="filterChange()">
													<span class="css-1q786di">
														{{$sub_cat->name}}
													</span>
											 
												</label>
										
											 @endforeach
										
										</div>
									</div>
								
							</div>

							<div class="col-md-12 col-xs-12 p-0">
								<hr>
							</div>
                       @endforeach
					   {{-- recipe category  --}}
					   <div class="panel panel-default">
						<div class="" role="tab" id="headingOne">
							<h4 class="panel-title">
								<a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne-recipe" aria-expanded="false" aria-controls="collapseOne" class="collapsed">
									Recipe Category
								</a>
							</h4>
						</div>
							<div id="collapseOne-recipe" class="collapse" role="tabpanel" aria-labelledby="headingOne" aria-expanded="false" style="height: 0px;">
								<div class="label-head">								
								   @foreach($recipe_category as $recipe_cat)
										<label class="css-10yup1s-container">
											<input type="checkbox" class="css-tyi62s-input recipe-tag-filter-option" @if(count($recipe_tags) > 0) @foreach($recipe_tags as $tag) @if($tag == $recipe_cat->id) checked @endif @endforeach @endif name="recipe_tags[]" value="{{ $recipe_cat->id }}" onchange="filterChange()"  >
											<span class="css-1q786di">
												{{$recipe_cat->name}}
											</span>
									 
										</label>
								
									 @endforeach
								
								</div>
							</div>
						
					</div>

					<div class="col-md-12 col-xs-12 p-0">
						<hr>
					</div>
					   {{-- end recipe category  --}}
					
					   <div class="panel panel-default">
						<div class="" role="tab" id="headingThree">
							<h4 class="panel-title">
								<a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseThree" aria-expanded="false" aria-controls="collapseThree">

									Ingredient

								</a>
							</h4>
						</div>
						<div id="collapseThree" class="collapse" role="tabpanel" aria-labelledby="headingThree" aria-expanded="false">
							<div class="label-head">
							{{--  --}}
								<div class="form-group float-left">
									<label class="labels">Include these ingredients</label>
									<div class="has-feedback float-left">								
									
									<input type="text"  onfocus="removeExclude()"  name="include[]" value="" class="form-control include-ingr" placeholder="Include Ingredient">
									<button class="a-e-ingredient include-btn-submit">&#43;</button>
									</div>
									<div class="mt-10 float-left">
										@foreach ($include_list as $include)
										{{-- {{dd($include)}} --}}
											<div class="alert alert-success alert-dismissible filters-options">
												<input name="include[]" value="{{ $include }}" hidden>
												<a  class="close include-close-btn" data-val="{{ $include }}" data-dismiss="alert" aria-label="close" >✕</a>   
												+ {{ $include }}                           
											</div>	
										@endforeach
										
									
									</div>
								</div>
								{{-- --------------------- --}}
								<div class="form-group float-left">
									<label class="labels">Do not include these ingredients</label>
									<div class="has-feedback float-left">
									<input type="text"  onfocus="removeInclude()" name="exclude[]" value="" class="form-control exclude-ingr" placeholder="Exclude Ingredient">
									 <button class="a-e-ingredient exclude-btn-submit">&#43;</button>
							 	</div>
							 	<div class="mt-10 float-left">
									@foreach ($exclude_list as $exclude)
										<div class="alert alert-success alert-dismissible filters-options">
											<input  name="exclude[]" value="{{ $exclude }}" hidden>
											<a class="close exclude-close-btn"  data-val="{{ $exclude }}" data-dismiss="alert" aria-label="close" >✕</a>   
												<s>+ {{ $exclude }}</s>                            
										</div>
									@endforeach
									
								</div>
							</div>
							{{-- ---------- --}}
						  </div>
						</div>
					</div>
					<div class="col-md-12 col-xs-12 p-0">
						<hr>
					</div>

							{{-- ------------------------------------- --}}
						
						</div>
					</div>
				{{-- </form> --}}

			</div> <!-- card-body.// -->
		</div>
	</article> 
</div>