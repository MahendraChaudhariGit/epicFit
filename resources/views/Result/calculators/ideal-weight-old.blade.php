@extends('calculators.layout')

@section('title', 'Ideal Weight Calculator')

@section('content')
    <h1>Ideal Weight Calculator</h1>
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
            <div id="metric">
                <div class="form-group row">
                    <label for="height" class="col-sm-2 col-form-label">Height (cm)</label>
                    <div class="col-sm-10">
                        <input type="number" class="form-control" placeholder="Height (cm)" name="height">
                    </div>
                </div>
            </div>
            <div id="imperial" style="display: none;">
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
                                Ideal Weight :
                                <input class="form-control-plaintext ideal-weight" type="text" readonly>
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
                                Ideal Weight :
                                <input class="form-control-plaintext ideal-weight" type="text" readonly>
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
                    generateMetricIdealWeight(selected_gender)
                } else {
                    generateImperialIdealWeight(selected_gender)
                }
            })

            $('#metric input[type=number]').on('keyup', function () {
                generateMetricIdealWeight(selected_gender)
            })

            $('#imperial input[type=number]').on('keyup', function () {
                generateImperialIdealWeight(selected_gender)
                var weight = $('#imperial input[name=weight]').val()

                var height_ft = $('#imperial input[name=height-ft]').val()

                var height_in = $('#imperial input[name=height-in]').val()

                var height = height_ft * 12 + Number(height_in)

                var bmi = weight / (height * height) * 703

                if (!isNaN(bmi) && isFinite(bmi)) {
                    bmi = bmi.toFixed(1)

                    var data = calculateClassificationAndWeightRange(bmi, weight, 'lbs')

                    $('#imperial-result .bmi').val(bmi)
                    $('#imperial-result .classification').val(data.classification)
                    $('#imperial-result .weight-range').val(data.weight_range)
                } else {
                    $('#imperial-result .bmi').val('')
                    $('#imperial-result .classification').val('')
                    $('#imperial-result .weight-range').val('')
                }
            })
        })

        function generateMetricIdealWeight(gender) {
            var height = $('#metric input[name=height]').val()

            var ideal_weight

            if (!isNaN(height) && isFinite(height)) {
                if (gender === 'male'){
                    ideal_weight = 50 + 2.3 * ((Number(height) / 2.54) - 60)
                } else {
                    ideal_weight = 49 + 1.7 * ((Number(height) / 2.54) - 60)
                }

                $('#metric-result .ideal-weight').val(Math.round(ideal_weight) + ' kg')
            } else {
                $('#metric-result .ideal-weight').val('')
            }
        }

        function generateImperialIdealWeight(gender) {
            var height_ft = $('#imperial input[name=height-ft]').val()

            var height_in = $('#imperial input[name=height-in]').val()

            var height = height_ft * 12 + Number(height_in)

            var ideal_weight

            if (!isNaN(height) && isFinite(height)) {
                if (gender === 'male'){
                    ideal_weight = (50 + 2.3 * (Number(height) - 60)) * 2.2
                } else {
                    ideal_weight = (49 + 1.7 * (Number(height) - 60 )) * 2.2
                }

                $('#imperial-result .ideal-weight').val(Math.round(ideal_weight) + ' lbs')
            } else {
                $('#imperial-result .ideal-weight').val('')
            }
        }
    </script>
@endsection
