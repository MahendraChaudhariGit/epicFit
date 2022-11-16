@extends('calculators.layout')

@section('title', 'Calorie Breakdown Calculator')

@section('content')
    <h1>Calorie Breakdown Calculator</h1>
    <br><br><br>

    <div class="container">
        <form>
            <fieldset class="form-group">
                <div class="row">
                    <legend class="col-form-legend col-sm-2">Gender</legend>
                    <div class="col-sm-10">
                        <div class="form-check">
                            <label class="form-check-label">
                                <input class="form-check-input" type="radio" name="gender" value="male" checked>
                                Male
                            </label>
                        </div>
                        <div class="form-check">
                            <label class="form-check-label">
                                <input class="form-check-input" type="radio" name="gender" value="female">
                                Female
                            </label>
                        </div>
                    </div>
                </div>
            </fieldset>
            <div id="metric">
                <div class="form-group row">
                    <label for="weight" class="col-sm-2 col-form-label">Age</label>
                    <div class="col-sm-10">
                        <input type="number" class="form-control" placeholder="Age" name="age">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="height" class="col-sm-2 col-form-label">Daily Calorie Intake</label>
                    <div class="col-sm-10">
                        <input type="number" class="form-control" placeholder="Daily Calorie Intake" name="calorie">
                    </div>
                </div>
            </div>
            <h3>Result</h3>
            <ul class="list-group" id="metric-result">
                <li class="list-group-item fat">Fat : <span></span></li>
                <li class="list-group-item protein">protein : <span></span></li>
                <li class="list-group-item carb">Total Carbohydrates : <span></span></li>
                <li class="list-group-item fiber">Fiber : <span></span></li>
                <li class="list-group-item sugar">Sugars : <span></span></li>
            </ul>
        </form>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function () {
            var selected_gender = 'male'

            var metric_result = $('#metric-result')

            $('input[name=gender]').on('change', function () {
                selected_gender = $('input[name=gender]:checked').val()

                calculateCalorieBreakdown(selected_gender)
            })

            $('#metric input[type=number]').on('keyup', function () {
                calculateCalorieBreakdown(selected_gender)
            })
        })

        function calculateCalorieBreakdown(selected_gender) {
            var age = $('#metric input[name=age]').val()

            var calorie = $('#metric input[name=calorie]').val()

            if (!isNaN(age) && !isNaN(calorie)) {
                $('#metric-result .fat span').text('15% - 25% ' + Math.round(calorie * .15) + ' - ' + Math.round(calorie * .25) + ' calories')

                $('#metric-result .protein span').text('15% - 25% ' + Math.round(calorie * .15) + ' - ' + Math.round(calorie * .25) + ' calories')

                $('#metric-result .carb span').text('50% - 70% ' + Math.round(calorie * .5) + ' - ' + Math.round(calorie * .7) + ' calories')

                if (selected_gender === 'male') {
                    if (age < 50) {
                        $('#metric-result .fiber span').text('38 grams')
                    } else {
                        $('#metric-result .fiber span').text('30 grams')
                    }
                } else {
                    if (age < 50) {
                        $('#metric-result .fiber span').text('25 grams')
                    } else {
                        $('#metric-result .fiber span').text('21 grams')
                    }
                }

                $('#metric-result .sugar span').text('<25% <' + Math.round(calorie * .25) + ' calories')
            }
        }

        function generateCalorieBreakdown(age, calorie, selected_gender) {
            var data = {}
            var mhr = 220 - age

            data.mhr = mhr

            if (selected_gender === 'get-fit') {
                data.thr1 = (mhr - calorie) * .5 + Number(rhra)

                data.thr2 = (mhr - rhra) * .6 + Number(rhra)
            }

            if (selected_fitness_goal === 'lose-weight') {
                data.thr1 = (mhr - rhra) * .6 + Number(rhra)

                data.thr2 = (mhr - rhra) * .7 + Number(rhra)
            }

            if (selected_fitness_goal === 'increase-endurance') {
                data.thr1 = (mhr - rhra) * .7 + Number(rhra)

                data.thr2 = (mhr - rhra) * .8 + Number(rhra)
            }

            if (selected_fitness_goal === 'excellent-fitness') {
                data.thr1 = (mhr - rhra) * .8 + Number(rhra)

                data.thr2 = (mhr - rhra) * .9 + Number(rhra)
            }

            if (selected_fitness_goal === 'competitive-athletics') {
                data.thr1 = (mhr - rhra) * .9 + Number(rhra)

                data.thr2 = (mhr - rhra) + Number(rhra)
            }

            return data
        }
    </script>
@endsection
