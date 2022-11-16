<div class="recipes-section">
	<article class="card-group-item re">
		<header class="card-header">
			<h6 class="css-yarn54">Filter By</h6>
		</header>
		<div class="filter-content">
			<div class="card-body">
			 	{{-- <form class="filter-sub-cat" id="filter-sub-cat" action="{{route('recipes.list')}}" method="get"> --}}

			 	{{-- <form class="filter-sub-cat" id="filter-sub-cat" action="{{route('recipes.list')}}" method="get"> --}}
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
					   {{-- {{dd($tags)}} --}}
					   @foreach($main_cat as $key => $cat)
				
							<div class="panel panel-default">
								<div class="" role="tab" id="headingOne">
									<h4 class="panel-title">
										<a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne-{{$key+1}}" aria-expanded="false" aria-controls="collapseOne" class="collapsed">

										{{$cat->name}}

										</a>
									</h4>
								</div>
									<div id="collapseOne-{{$key+1}}" class="collapse" role="tabpanel" aria-labelledby="headingOne" aria-expanded="false" style="height: 0px;">
										<div class="label-head">
										
										   @foreach($cat->subCategory as $sub_cat)
												<label class="css-10yup1s-container">
													{{-- <input type="checkbox" class="css-tyi62s-input filter-sub-cat-checkbox" onchange="$('.filter-sub-cat').submit()" onchange="document.getElementById('filter-sub-cat').submit()" > --}}
													<input type="checkbox" @if(count($tags) > 0) @foreach($tags as $tag) @if($tag == $sub_cat->id) checked @endif @endforeach @endif class="css-tyi62s-input filter-sub-cat-checkbox" name="filter_tags[]" value="{{ $sub_cat->id }}" onchange="document.getElementById('filter-sub-cat').submit()"  >
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
											{{-- <input type="checkbox" @if(count($recipe_tags) > 0) @foreach($recipe_tags as $tag) @if($tag == $recipe_cat->id) checked @endif @endforeach @endif class="css-tyi62s-input filter-sub-cat-checkbox" name="filter_tags[]" value="{{ $sub_cat->id }}" onchange="document.getElementById('filter-sub-cat').submit()"  > --}}
											<input type="checkbox" class="css-tyi62s-input" @if(count($recipe_tags) > 0) @foreach($recipe_tags as $tag) @if($tag == $recipe_cat->id) checked @endif @endforeach @endif name="recipe_tags[]" value="{{ $recipe_cat->id }}" onchange="document.getElementById('filter-sub-cat').submit()"  >
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
					   {{-- {{dd($recipe_category)}} --}}
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
									
									<input type="text"  onfocus="removeExclude()"  name="include[]" value="" class="form-control" placeholder="Include Ingredient">
									<button class="a-e-ingredient">&#43;</button>
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
										
										 {{-- <div class="alert alert-success alert-dismissible filters-options">
											<a href="#" class="close" data-dismiss="alert" aria-label="close">✕</a>   
											+ Test                           
										</div> --}}
									</div>
								</div>
								{{-- --------------------- --}}
								<div class="form-group float-left">
									<label class="labels">Do not include these ingredients</label>
									<div class="has-feedback float-left">
									<input type="text"  onfocus="removeInclude()" name="exclude[]" value="" class="form-control" placeholder="Exclude Ingredient">
									 <button class="a-e-ingredient">&#43;</button>
							 	</div>
							 	<div class="mt-10 float-left">
									@foreach ($exclude_list as $exclude)
										<div class="alert alert-success alert-dismissible filters-options">
											<input  name="exclude[]" value="{{ $exclude }}" hidden>
											<a class="close exclude-close-btn"  data-val="{{ $exclude }}" data-dismiss="alert" aria-label="close" >✕</a>   
												<s>+ {{ $exclude }}</s>                            
										</div>
									@endforeach
									{{-- <div class="alert alert-success alert-dismissible filters-options">
											<a href="#" class="close" data-dismiss="alert" aria-label="close">✕</a>   
											+ Test                           
									</div> --}}
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
							{{-- <div class="panel panel-default">
								<div class="" role="tab" id="headingTwo">
									<h4 class="panel-title">
										<a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">

											Dish Type

										</a>
									</h4>
								</div>
								<div id="collapseTwo" class="collapse" role="tabpanel" aria-labelledby="headingTwo" aria-expanded="false">
									<div class="label-head">
										<label class="css-10yup1s-container">
											<input type="checkbox" class="css-tyi62s-input">
											<span class="css-1q786di">
												Bread
											</span>
										</label>
										<label class="css-10yup1s-container">
											<input type="checkbox" class="css-tyi62s-input">
											<span class="css-1q786di">
												Cake
											</span>
										</label>
										<label class="css-10yup1s-container">
											<input type="checkbox" class="css-tyi62s-input">
											<span class="css-1q786di">
												Candy
											</span>
										</label>
										<label class="css-10yup1s-container">
											<input type="checkbox" class="css-tyi62s-input">
											<span class="css-1q786di">
												Carrot Cake
											</span>
										</label>
										<label class="css-10yup1s-container">
											<input type="checkbox" class="css-tyi62s-input">
											<span class="css-1q786di">
												Chicken Salad
											</span>
										</label>
										<label class="css-10yup1s-container">
											<input type="checkbox" class="css-tyi62s-input">
											<span class="css-1q786di">
												Chocolate Cake
											</span>
										</label>
										<label class="css-10yup1s-container">
											<input type="checkbox" class="css-tyi62s-input">
											<span class="css-1q786di">
												Chocolate Chip Cookies
											</span>
										</label>
										<label class="css-10yup1s-container">
											<input type="checkbox" class="css-tyi62s-input">
											<span class="css-1q786di">
												Cocktail
											</span>
										</label>
										<label class="css-10yup1s-container">
											<input type="checkbox" class="css-tyi62s-input">
											<span class="css-1q786di">
												Cookie
											</span>
										</label>
										<label class="css-10yup1s-container">
											<input type="checkbox" class="css-tyi62s-input">
											<span class="css-1q786di">
												Cornbread
											</span>
										</label>
										<label class="css-10yup1s-container">
											<input type="checkbox" class="css-tyi62s-input">
											<span class="css-1q786di">
												Corn Chowder
											</span>
										</label>
										<label class="css-10yup1s-container">
											<input type="checkbox" class="css-tyi62s-input">
											<span class="css-1q786di">
												Corn Salad
											</span>
										</label>
										<label class="css-10yup1s-container">
											<input type="checkbox" class="css-tyi62s-input">
											<span class="css-1q786di">
												Fish Taco
											</span>
										</label>
										<label class="css-10yup1s-container">
											<input type="checkbox" class="css-tyi62s-input">
											<span class="css-1q786di">
												Ice Cream/Frozen Desserts
											</span>
										</label>
										<label class="css-10yup1s-container">
											<input type="checkbox" class="css-tyi62s-input">
											<span class="css-1q786di">
												Pasta
											</span>
										</label>
										<label class="css-10yup1s-container">
											<input type="checkbox" class="css-tyi62s-input">
											<span class="css-1q786di">
												Pie
											</span>
										</label>
										<label class="css-10yup1s-container">
											<input type="checkbox" class="css-tyi62s-input">
											<span class="css-1q786di">
												Pizza
											</span>
										</label>
										<label class="css-10yup1s-container">
											<input type="checkbox" class="css-tyi62s-input">
											<span class="css-1q786di">
												Salad
											</span>
										</label>
										<label class="css-10yup1s-container">
											<input type="checkbox" class="css-tyi62s-input">
											<span class="css-1q786di">
												Sandwich
											</span>
										</label>
										<label class="css-10yup1s-container">
											<input type="checkbox" class="css-tyi62s-input">
											<span class="css-1q786di">
												Soup
											</span>
										</label>
										<label class="css-10yup1s-container">
											<input type="checkbox" class="css-tyi62s-input">
											<span class="css-1q786di">
												Stew
											</span>
										</label>
										<label class="css-10yup1s-container">
											<input type="checkbox" class="css-tyi62s-input">
											<span class="css-1q786di">
												Zucchini Bread
											</span>
										</label>
									</div>
								</div>
							</div>

							<div class="col-md-12 col-xs-12 p-0">
								<hr>
							</div>
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
										
										<div class="form-group float-left">
											<label class="labels">Include these ingredients</label>
											<div class="has-feedback float-left">								
											
											<input type="text" name="" class="form-control" placeholder="Include Ingredient">
											<button class="a-e-ingredient">&#43;</button>
											</div>
											<div class="mt-10 float-left">
												<div class="alert alert-success alert-dismissible filters-options">
													<a href="#" class="close" data-dismiss="alert" aria-label="close">✕</a>   
													+ Test Kitchen-Approved                            
												</div><div class="alert alert-success alert-dismissible filters-options">
													<a href="#" class="close" data-dismiss="alert" aria-label="close">✕</a>   
													+ Test                           
												</div>
											</div>
										</div>
										<div class="form-group float-left">
											<label class="labels">Do not include these ingredients</label>
											<div class="has-feedback float-left">
											<input type="text" name="" class="form-control" placeholder="Exclude Ingredient">
                                             <button class="a-e-ingredient">&#43;</button>
										</div>
																					<div class="mt-10 float-left">
												<div class="alert alert-success alert-dismissible filters-options">
													<a href="#" class="close" data-dismiss="alert" aria-label="close">✕</a>   
													+ Test Kitchen-Approved                            
												</div><div class="alert alert-success alert-dismissible filters-options">
													<a href="#" class="close" data-dismiss="alert" aria-label="close">✕</a>   
													+ Test                           
												</div>
											</div>
										</div>

									</div>
								</div>
							</div>

							<div class="col-md-12 col-xs-12 p-0">
								<hr>
							</div>
							<div class="panel panel-default">
								<div class="" role="tab">
									<h4 class="panel-title">
										<a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse4" aria-expanded="false" aria-controls="collapseThree">

											Special Consideration

										</a>
									</h4>
								</div>
								<div id="collapse4" class="collapse" role="tabpanel" aria-labelledby="headingThree" aria-expanded="false">
									<div class="label-head">
										<label class="css-10yup1s-container">
											<input type="checkbox" class="css-tyi62s-input">
											<span class="css-1q786di">
												Alcohol-Free Drinks
											</span>
										</label>
										<label class="css-10yup1s-container">
											<input type="checkbox" class="css-tyi62s-input">
											<span class="css-1q786di">
												Gluten-Free
											</span>
										</label>
										<label class="css-10yup1s-container">
											<input type="checkbox" class="css-tyi62s-input">
											<span class="css-1q786di">
												Vegan
											</span>
										</label>
										<label class="css-10yup1s-container">
											<input type="checkbox" class="css-tyi62s-input">
											<span class="css-1q786di">
												Vegetarian
											</span>
										</label>
									</div>
								</div>
							</div>
							<div class="col-md-12 col-xs-12 p-0">
								<hr>
							</div>
							<div class="panel panel-default">
								<div class="" role="tab">
									<h4 class="panel-title">
										<a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse5" aria-expanded="false" aria-controls="collapseThree">

											Occasion

										</a>
									</h4>
								</div>
								<div id="collapse5" class="collapse" role="tabpanel" aria-labelledby="headingThree" aria-expanded="false">
									<div class="label-head">
										<label class="css-10yup1s-container">
											<input type="checkbox" class="css-tyi62s-input">
											<span class="css-1q786di">
												Christmas
											</span>
										</label>
										<label class="css-10yup1s-container">
											<input type="checkbox" class="css-tyi62s-input">
											<span class="css-1q786di">
												Easter
											</span>
										</label>
										<label class="css-10yup1s-container">
											<input type="checkbox" class="css-tyi62s-input">
											<span class="css-1q786di">
												Fall
											</span>
										</label>
										<label class="css-10yup1s-container">
											<input type="checkbox" class="css-tyi62s-input">
											<span class="css-1q786di">
												Father's Day
											</span>
										</label>
										<label class="css-10yup1s-container">
											<input type="checkbox" class="css-tyi62s-input">
											<span class="css-1q786di">
												Fourth of July
											</span>
										</label>
										<label class="css-10yup1s-container">
											<input type="checkbox" class="css-tyi62s-input">
											<span class="css-1q786di">
												Halloween
											</span>
										</label>
										<label class="css-10yup1s-container">
											<input type="checkbox" class="css-tyi62s-input">
											<span class="css-1q786di">
												Hanukkah
											</span>
										</label>
										<label class="css-10yup1s-container">
											<input type="checkbox" class="css-tyi62s-input">
											<span class="css-1q786di">
												Memorial Day
											</span>
										</label>
										<label class="css-10yup1s-container">
											<input type="checkbox" class="css-tyi62s-input">
											<span class="css-1q786di">
												Mother's Day
											</span>
										</label>
										<label class="css-10yup1s-container">
											<input type="checkbox" class="css-tyi62s-input">
											<span class="css-1q786di">
												Passover
											</span>
										</label>
										<label class="css-10yup1s-container">
											<input type="checkbox" class="css-tyi62s-input">
											<span class="css-1q786di">
												Rosh Hashanah
											</span>
										</label>
										<label class="css-10yup1s-container">
											<input type="checkbox" class="css-tyi62s-input">
											<span class="css-1q786di">
												Spring
											</span>
										</label>
										<label class="css-10yup1s-container">
											<input type="checkbox" class="css-tyi62s-input">
											<span class="css-1q786di">
												Summer
											</span>
										</label>
										<label class="css-10yup1s-container">
											<input type="checkbox" class="css-tyi62s-input">
											<span class="css-1q786di">
												Thanksgiving
											</span>
										</label>
										<label class="css-10yup1s-container">
											<input type="checkbox" class="css-tyi62s-input">
											<span class="css-1q786di">
												Valentine's Day
											</span>
										</label>
										<label class="css-10yup1s-container">
											<input type="checkbox" class="css-tyi62s-input">
											<span class="css-1q786di">
												Winter
											</span>
										</label>
									</div>
								</div>
							</div>
							<div class="col-md-12 col-xs-12 p-0">
								<hr>
							</div>
							<div class="panel panel-default">
								<div class="" role="tab">
									<h4 class="panel-title">
										<a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse6" aria-expanded="false" aria-controls="collapseThree">

											Preparation

										</a>
									</h4>
								</div>
								<div id="collapse6" class="collapse" role="tabpanel" aria-labelledby="headingThree" aria-expanded="false">
									<div class="label-head">
										<label class="css-10yup1s-container">
											<input type="checkbox" class="css-tyi62s-input">
											<span class="css-1q786di">
												5 Ingredients or Fewer
											</span>
										</label>
										<label class="css-10yup1s-container">
											<input type="checkbox" class="css-tyi62s-input">
											<span class="css-1q786di">
												Grill/Barbecue
											</span>
										</label>
										<label class="css-10yup1s-container">
											<input type="checkbox" class="css-tyi62s-input">
											<span class="css-1q786di">
												Make Ahead
											</span>
										</label>
										<label class="css-10yup1s-container">
											<input type="checkbox" class="css-tyi62s-input">
											<span class="css-1q786di">
												One-Pot Wonders
											</span>
										</label>
											<label class="css-10yup1s-container">
											<input type="checkbox" class="css-tyi62s-input">
											<span class="css-1q786di">
												Serves a Crowd
											</span>
										</label>
											<label class="css-10yup1s-container">
											<input type="checkbox" class="css-tyi62s-input">
											<span class="css-1q786di">
												Slow Cooker
											</span>
										</label>

									</div>
								</div>
							</div>
							<div class="col-md-12 col-xs-12 p-0">
								<hr>
							</div>
							<div class="panel panel-default">
								<div class="" role="tab">
									<h4 class="panel-title">
										<a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse7" aria-expanded="false" aria-controls="collapseThree">

											Cuisine

										</a>
									</h4>
								</div>
								<div id="collapse7" class="collapse" role="tabpanel" aria-labelledby="headingThree" aria-expanded="false">
									<div class="label-head">
										<label class="css-10yup1s-container">
											<input type="checkbox" class="css-tyi62s-input">
											<span class="css-1q786di">
												African
											</span>
										</label>
										<label class="css-10yup1s-container">
											<input type="checkbox" class="css-tyi62s-input">
											<span class="css-1q786di">
												Afro-Brazilian
											</span>
										</label>
										<label class="css-10yup1s-container">
											<input type="checkbox" class="css-tyi62s-input">
											<span class="css-1q786di">
												American
											</span>
										</label>
										<label class="css-10yup1s-container">
											<input type="checkbox" class="css-tyi62s-input">
											<span class="css-1q786di">
												Argentine
											</span>
										</label>
										<label class="css-10yup1s-container">
											<input type="checkbox" class="css-tyi62s-input">
											<span class="css-1q786di">
												Ashkenazi
											</span>
										</label>
										<label class="css-10yup1s-container">
											<input type="checkbox" class="css-tyi62s-input">
											<span class="css-1q786di">
												Asian
											</span>
										</label>
										<label class="css-10yup1s-container">
											<input type="checkbox" class="css-tyi62s-input">
											<span class="css-1q786di">
												Australian/New Zealander
											</span>
										</label>
											<label class="css-10yup1s-container">
											<input type="checkbox" class="css-tyi62s-input">
											<span class="css-1q786di">
												Austrian
											</span>
										</label>
										<label class="css-10yup1s-container">
											<input type="checkbox" class="css-tyi62s-input">
											<span class="css-1q786di">
												Bangladeshi
											</span>
										</label>
										<label class="css-10yup1s-container">
											<input type="checkbox" class="css-tyi62s-input">
											<span class="css-1q786di">
												Basque
											</span>
										</label>
										<label class="css-10yup1s-container">
											<input type="checkbox" class="css-tyi62s-input">
											<span class="css-1q786di">
												Belgian
											</span>
										</label>
										<label class="css-10yup1s-container">
											<input type="checkbox" class="css-tyi62s-input">
											<span class="css-1q786di">
												Brazilian
											</span>
										</label>
										<label class="css-10yup1s-container">
											<input type="checkbox" class="css-tyi62s-input">
											<span class="css-1q786di">
												British
											</span>
										</label>
										<label class="css-10yup1s-container">
											<input type="checkbox" class="css-tyi62s-input">
											<span class="css-1q786di">
												Cajun/Creole
											</span>
										</label>
										<label class="css-10yup1s-container">
											<input type="checkbox" class="css-tyi62s-input">
											<span class="css-1q786di">
												Californian
											</span>
										</label>
										<label class="css-10yup1s-container">
											<input type="checkbox" class="css-tyi62s-input">
											<span class="css-1q786di">
												Canadian
											</span>
										</label>
										<label class="css-10yup1s-container">
											<input type="checkbox" class="css-tyi62s-input">
											<span class="css-1q786di">
												Cantonese
											</span>
										</label>
										<label class="css-10yup1s-container">
											<input type="checkbox" class="css-tyi62s-input">
											<span class="css-1q786di">
												Caribbean
											</span>
										</label>
									</div>
								</div>
							</div>
							<div class="col-md-12 col-xs-12 p-0">
								<hr>
							</div> --}}
							{{-- <button type="submit" class="sub-cat-filter-submit-button"> filter</button> --}}
						</div>
					</div>
				{{-- </form> --}}

			</div> <!-- card-body.// -->
		</div>
	</article> 
</div>