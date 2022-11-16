@extends('calculators.layout')

@section('title', 'Basal Metabolism Rate Calculator')

@section('content')
    <h1>Basal Metabolism Rate Calculator</h1>
    <br><br><br>

    <div class="container">
        <form>
            <fieldset class="form-group">
                <div class="row">
                    <legend class="col-form-legend col-sm-2">Input Type</legend>
                    <div class="col-sm-10">
                        <div class="form-check">
                            <label class="form-check-label">
                                <input class="form-check-input" type="radio" name="type" value="metric" checked>
                                Metric System
                            </label>
                        </div>
                        <div class="form-check">
                            <label class="form-check-label">
                                <input class="form-check-input" type="radio" name="type" value="imperial">
                                Imperial System
                            </label>
                        </div>
                    </div>
                </div>
            </fieldset>
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
            <fieldset class="form-group">
                <div class="row">
                    <legend class="col-form-legend col-sm-2">BRM Equation Type</legend>
                    <div class="col-sm-10">
                        <div class="form-check">
                            <label class="form-check-label">
                                <input class="form-check-input" type="radio" name="equation" value="msj" checked>
                                Mifflin - St Jeor
                            </label>
                        </div>
                        <div class="form-check">
                            <label class="form-check-label">
                                <input class="form-check-input" type="radio" name="equation" value="hb">
                                Harris-Benedict
                            </label>
                        </div>
                    </div>
                </div>
            </fieldset>
            <div class="form-group row">
                <label for="weight" class="col-sm-2 col-form-label">Age</label>
                <div class="col-sm-10">
                    <input type="number" class="form-control" placeholder="Age" name="age">
                </div>
            </div>
            <div id="metric">
                <div class="form-group row">
                    <label for="weight" class="col-sm-2 col-form-label">Weight (kg)</label>
                    <div class="col-sm-10">
                        <input type="number" class="form-control" placeholder="Weight (kg)" name="weight">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="height" class="col-sm-2 col-form-label">Height (cm)</label>
                    <div class="col-sm-10">
                        <input type="number" class="form-control" placeholder="Height (cm)" name="height">
                    </div>
                </div>
            </div>
            <div id="imperial" style="display: none;">
                <div class="form-group row">
                    <label for="weight" class="col-sm-2 col-form-label">Weight (lbs)</label>
                    <div class="col-sm-10">
                        <input type="number" class="form-control" id="weight" placeholder="Weight (lbs)" name="weight">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="height-ft" class="col-sm-2 col-form-label">Height (ft)</label>
                    <div class="col-sm-10">
                        <input type="number" class="form-control" id="height-ft" placeholder="Height (ft)" name="height-ft">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="height-in" class="col-sm-2 col-form-label">Height (in)</label>
                    <div class="col-sm-10">
                        <input type="number" class="form-control" id="height-in" placeholder="Height (in)" name="height-in">
                    </div>
                </div>
            </div>
            <fieldset class="form-group" id="metric-result">
                <div class="row">
                    <legend class="col-form-legend col-sm-2">Result</legend>
                    <div class="col-sm-10">
                        <div class="form-check">
                            <label class="form-check-label">
                                BRM :
                                <input class="form-control-plaintext brm" type="text" readonly>
                            </label>
                        </div>
                    </div>
                </div>
            </fieldset>
            <fieldset class="form-group" id="imperial-result" style="display: none;">
                <div class="row">
                    <legend class="col-form-legend col-sm-2">Result</legend>
                    <div class="col-sm-10">
                        <div class="form-check">
                            <label class="form-check-label">
                                BRM :
                                <input class="form-control-plaintext brm" type="text" readonly>
                            </label>
                        </div>
                    </div>
                </div>
            </fieldset>
        </form>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function () {
            var selected_type = 'metric'

            var selected_gender = 'male'

            var selected_equation = 'msj'

            var metric = $('#metric')

            var metric_result = $('#metric-result')

            var imperial = $('#imperial')

            var imperial_result = $('#imperial-result')

            $('input[name=type]').on('change', function () {
                selected_type = $('input[name=type]:checked').val()

                if (selected_type === 'metric') {
                    imperial.hide()
                    imperial_result.hide()

                    metric.show()
                    metric_result.show()
                } else {
                    metric.hide()
                    metric_result.hide()

                    imperial.show()
                    imperial_result.show()
                }
            })

            $('input[name=gender]').on('change', function () {
                selected_gender = $('input[name=gender]:checked').val()

                if (selected_type === 'metric') {
                    calculateMetricBaseMetabolismRate(selected_equation, selected_gender)
                } else {
                    calculateImperialBaseMetabolismRate(selected_equation, selected_gender)
                }
            })

            $('input[name=equation]').on('change', function () {
                selected_equation = $('input[name=equation]:checked').val()

                if (selected_type === 'metric') {
                    calculateMetricBaseMetabolismRate(selected_equation, selected_gender)
                } else {
                    calculateImperialBaseMetabolismRate(selected_equation, selected_gender)
                }
            })


            $('#metric input[type=number]').on('keyup', function () {
                calculateMetricBaseMetabolismRate(selected_equation, selected_gender)
            })

            $('#imperial input[type=number]').on('keyup', function () {
                calculateImperialBaseMetabolismRate(selected_equation, selected_gender)
            })
        })

        function generateMetricBasalMetabolismRate(equation, gender, weight, height, age) {
            var base_brm

            if (equation === 'msj') {
                base_brm = (10 * weight) + (6.25 * height) - (5 * age)

              return gender === 'male' ? base_brm + 5 : base_brm -  161
            } else {
                if (gender === 'male') {
                    return 66.47 + (13.75 * weight) + (5.003 * height) - (6.755 * age)
                } else {
                    return 655.1 + (9.563 * weight) + (1.85 * height) - (4.676 * age)
                }
            }
        }

        function calculateMetricBaseMetabolismRate(selected_equation, selected_gender) {
            var weight = $('#metric input[name=weight]').val()

            var height = $('#metric input[name=height]').val()

            var age = $('input[name=age]').val()

            var brm = generateMetricBasalMetabolismRate(selected_equation, selected_gender, weight, height, age)

            if (!isNaN(brm) && isFinite(brm)) {
                brm = Math.round(brm)

                $('#metric-result .brm').val(brm)
            } else {
                $('#metric-result .brm').val('')
            }
        }

        function generateImperialBasalMetabolismRate(equation, gender, weight, height, age) {
            var base_brm

            if (equation === 'msj') {
                base_brm = (4.536 * weight) + (15.88 * height) - (5 * age)

                return gender === 'male' ? base_brm + 5 : base_brm -  161
            } else {
                if (gender === 'male') {
                    return 66.47 + (6.24 * weight) + (12.7 * height) - (6.755 * age)
                } else {
                    return 655.1 + (4.35 * weight) + (4.7 * height) - (4.7 * age)
                }
            }
        }


        function calculateImperialBaseMetabolismRate(selected_equation, selected_gender) {
            var weight = $('#imperial input[name=weight]').val()

            var height_ft = $('#imperial input[name=height-ft]').val()

            var height_in = $('#imperial input[name=height-in]').val()

            var height = height_ft * 12 + Number(height_in)

            var age = $('input[name=age]').val()

            var brm = generateImperialBasalMetabolismRate(selected_equation, selected_gender, weight, height, age)

            if (!isNaN(brm) && isFinite(brm)) {
                brm = Math.round(brm)

                $('#imperial-result .brm').val(brm)
            } else {
                $('#imperial-result .brm').val('')
            }
        }

    </script>
@endsection
