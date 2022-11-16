<div class="row">
    <div id='meal-cal-form'>
        <div class="col-md-12">
            <div class="form-group" id="form-group-edit" style="display: none;">
                
            </div>
            <div class="form-group" id="form-group-create">
                <a href="#" class="pull-right sampleRecipe">Try a Sample Recipe</a>
                <textarea name="tool-name" id="tool-name" class="form-control custom-foods-textarea" placeholder="Copy & paste your foods here! Make sure to list one ingredient per line.">{!! isset($ingradiant)?$ingradiant:'' !!}</textarea>
            </div>
            <div class="row">
                <div class="form-group col-md-10 col-xs-10">
                    {!! Form::label('serving','Select Number of Servings:', ['class'=>'strong']) !!}
                    <select id="serving" name="serving" class="custom-foods-serving">
                        @for($i = 1; $i <= 72; $i++)
                            <option value="{{ $i }}">{{ $i }}</option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-2 col-xs-2">
                    <a href="#" class="refresh-textarea-one" style="display: none;"> &nbsp;<i class="fa fa-refresh fa-2x"></i></a>
                </div>
            </div>
            <div class="form-group">
                <a href="#" class="btn btn-primary" id="cal-nut-btn-submit"> Calculate Nutrition </a>
            </div>
        </div> 
    </div>            
</div>
<div class="row" id="nutrition-result-area" style="display: none;">
    <div class="col-md-12">
        <hr>
        <h4 class="text-primary"><b>Meal Analyzer Results</b></h4>
        <h3>A single serving of this food has <span class="caloriesCls"></span> calories.</h3>

        <p>Read through the nutrition label for a snapshot of this food’s nutritional profile. If the label lists less than 5 percent daily value for a nutrient it is considered low, while 20 percent or more is high. In general, you want to limit saturated fat, cholesterol, and sodium, and get enough fiber, vitamins, and minerals.</p>
    </div>
    <div class="col-md-12">
        <hr>
        <div class="col-md-4">
            <a href="#" class="scroll-on-top"> <b>EDIT FOOD</b> &nbsp;<i class="fa fa-arrow-circle-o-up" style="font-size: 1.2em;"></i></a>
        </div>
        <div class="col-md-4">
            <a href="#" class="refresh-textarea"> <b>CLEAR FOOD</b> &nbsp;<i class="fa fa-refresh" style="font-size: 1.2em;"></i></a>
        </div>
    </div>
    <div class="col-md-12">
        <fieldset class="padding-15" id="nutrition-info">
            <table class="table table-condensed">
                <thead>
                    <tr>
                        <th colspan="2" class="b-b-s-5">
                            <h3>Nutrition Facts</h3>
                            Servings: <span id="servingId"></span>
                        </th>
                    </tr>
                </thead>    
                <tbody>
                    <tr>
                        <td>
                            <p><b>Amount per serving</b></p>
                            <h3>Calories</h3>
                        </td>
                        <td>
                            <p>&nbsp;</p>
                            <h3><span class="caloriesCls"></span></h3>
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>% Daily Value*</td>
                    </tr>
                    <tr>
                        <td><b>Total Fat</b> <span id="totalFatNum"></span></td>
                        <td><span id="totalFatPer"></span></td>
                    </tr>
                    <tr>
                        <td>&nbsp;Saturated Fat <span id="saturFatNum"></span></td>
                        <td><span id="saturFatPer"></span></td>
                    </tr>
                    <tr>
                        <td><b>Cholesterol</b> <span id="cholesterolNum"></span></td>
                        <td><span id="cholesterolPer"></span></td>
                    </tr>
                    <tr>
                        <td><b>Sodium </b> <span id="sodiumNum"></span></td>
                        <td><span id="sodiumPer"></span></td>
                    </tr>
                    <tr>
                        <td><b>Total Carbohydrate</b> <span id="carbohydrateNum"></span></td>
                        <td><span id="carbohydratePer"></span></td>
                    </tr>
                    <tr>
                        <td>&nbsp;Dietary Fiber <span id="fiberNum"></span></td>
                        <td><span id="fiberPer"></span></td>
                    </tr>
                    <tr>
                        <td>&nbsp;Total Sugars <span id="sugarNum"></span></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td colspan="2" class="b-b-s-5"><b>Protein <span id="proteinNum"></span></b></td>
                    </tr>
                    <tr>
                        <td>Vitamin D <span id="vitaminDNum"></span></td>
                        <td><span id="vitaminDPer"></span></td>
                    </tr>
                    <tr>
                        <td>Calcium <span id="calciumNum"></span></td>
                        <td><span id="calciumPer"></span></td>
                    </tr>
                    <tr>
                        <td>Iron <span id="ironNum"></span></td>
                        <td><span id="ironPer"></span></td>
                    </tr>
                    <tr>
                        <td>Potassium <span id="potassiumNum"></span></td>
                        <td><span id="potassiumPer"></span></td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            *The % Daily Value (DV) tells you how much a nutrient in a food serving contributes to a daily diet. 2,000 calorie a day is used for general nutrition advice.
                        </td>
                    </tr>
                </tbody>
            </table>
        </fieldset>
    </div>
</div>