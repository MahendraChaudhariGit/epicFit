@extends('calculators.layout')

@section('title', 'Body Mass Index Calculator')

@section('content')
    <h1>Body Mass Index Calculator</h1>
    <br><br><br>

    <div class="container">
        <form>
            <fieldset class="form-group">
                <div class="row">
                    <legend class="col-form-legend col-sm-2">Input Type</legend>
                    <div class="col-sm-10">
                        <div class="form-check">
                            <label class="form-check-label">
                                <input class="form-check-input" type="radio" name="type" id="gridRadios1" value="metric" checked>
                                Metric System
                            </label>
                        </div>
                        <div class="form-check">
                            <label class="form-check-label">
                                <input class="form-check-input" type="radio" name="type" id="gridRadios2" value="imperial">
                                Imperial System
                            </label>
                        </div>
                    </div>
                </div>
            </fieldset>
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
                                BMI :
                                <input class="form-control-plaintext bmi" type="text" readonly>
                            </label>
                        </div>
                        <div class="form-check">
                            <label class="form-check-label">
                                Classification :
                                <input class="form-control-plaintext classification" type="text" readonly>
                            </label>
                        </div>
                        <div class="form-check">
                            <label class="form-check-label">
                                Weight Range :
                                <input class="form-control-plaintext weight-range" type="text" readonly>
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
                                BMI :
                                <input class="form-control-plaintext bmi" type="text" readonly>
                            </label>
                        </div>
                        <div class="form-check">
                            <label class="form-check-label">
                                Classification :
                                <input class="form-control-plaintext classification" type="text" readonly>
                            </label>
                        </div>
                        <div class="form-check">
                            <label class="form-check-label">
                                Weight Range :
                                <input class="form-control-plaintext weight-range" type="text" readonly>
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

            $('#metric input[type=number]').on('keyup', function () {
                var weight = $('#metric input[name=weight]').val()

                var height = $('#metric input[name=height]').val()

                var bmi = weight / (height * height) * 10000

                if (!isNaN(bmi) && isFinite(bmi)) {
                    bmi = bmi.toFixed(1)

                    var data = calculateClassificationAndWeightRange(bmi, weight, 'kg')

                    $('#metric-result .bmi').val(bmi)
                    $('#metric-result .classification').val(data.classification)
                    $('#metric-result .weight-range').val(data.weight_range)
                } else {
                    $('#metric-result .bmi').val('')
                    $('#metric-result .classification').val('')
                    $('#metric-result .weight-range').val('')
                }
            })

            $('#imperial input[type=number]').on('keyup', function () {
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

        function calculateClassificationAndWeightRange(bmi, weight, unit) {
            var data = {}

            if (bmi > 40) {
                data.classification = 'Extremely Obese'

                data.weight_range = Math.floor(weight / bmi * 40) + ' ' + unit +  ' or more'
            } else if (bmi > 30 && bmi <= 40) {
                data.classification = 'Obese'

                data.weight_range = Math.floor(weight / bmi * 30) + ' to ' + Math.floor(weight / bmi * 40) + ' ' + unit
            } else if (bmi > 25 && bmi <= 30) {
                data.classification = 'Overweight'

                data.weight_range = Math.floor(weight / bmi * 25) + ' to ' + Math.floor(weight / bmi * 30) + ' ' + unit
            } else if (bmi > 18.5 && bmi <= 25) {
                data.classification = 'Normal'

                data.weight_range = Math.floor(weight / bmi * 18.5) + ' to ' + Math.floor(weight / bmi * 25) + ' ' +unit
            } else if (bmi <= 18.5) {
                data.classification = 'Underweight'

                data.weight_range = Math.floor(weight / bmi * 18.5) + ' ' + unit + ' or less'
            }

            return data
        }
    </script>
@endsection
