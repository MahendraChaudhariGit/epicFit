@extends('calculators.layout')

@section('title', 'Resting Metabolism Calculator')

@section('content')
    <h1>Resting Metabolism Calculator</h1>
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
            <div id="metric">
                <div class="form-group row">
                    <label for="weight" class="col-sm-2 col-form-label">Weight (kg)</label>
                    <div class="col-sm-10">
                        <input type="number" class="form-control" placeholder="Weight (kg)" name="weight">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="height" class="col-sm-2 col-form-label">Lean Mass</label>
                    <div class="col-sm-10">
                        <input type="number" class="form-control" placeholder="Lean Mass" name="mass">
                    </div>
                </div>
                <fieldset class="form-group">
                    <div class="row">
                        <legend class="col-form-legend col-sm-2">Lean Mass In</legend>
                        <div class="col-sm-10">
                            <div class="form-check">
                                <label class="form-check-label">
                                    <input class="form-check-input" type="radio" name="unit-type-m" value="percent" checked>
                                    Percent
                                </label>
                            </div>
                            <div class="form-check">
                                <label class="form-check-label">
                                    <input class="form-check-input" type="radio" name="unit-type-m" value="unit">
                                    Kilograms
                                </label>
                            </div>
                        </div>
                    </div>
                </fieldset>
            </div>
            <div id="imperial" style="display: none;">
                <div class="form-group row">
                    <label for="weight" class="col-sm-2 col-form-label">Weight (lbs)</label>
                    <div class="col-sm-10">
                        <input type="number" class="form-control" placeholder="Weight (lbs)" name="weight">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="height" class="col-sm-2 col-form-label">Lean Mass</label>
                    <div class="col-sm-10">
                        <input type="number" class="form-control" placeholder="Lean Mass" name="mass">
                    </div>
                </div>
                <fieldset class="form-group">
                    <div class="row">
                        <legend class="col-form-legend col-sm-2">Lean Mass In</legend>
                        <div class="col-sm-10">
                            <div class="form-check">
                                <label class="form-check-label">
                                    <input class="form-check-input" type="radio" name="unit-type-i" value="percent" checked>
                                    Percent
                                </label>
                            </div>
                            <div class="form-check">
                                <label class="form-check-label">
                                    <input class="form-check-input" type="radio" name="unit-type-i" value="unit">
                                    Pounds
                                </label>
                            </div>
                        </div>
                    </div>
                </fieldset>
            </div>
            <fieldset class="form-group" id="metric-result">
                <div class="row">
                    <legend class="col-form-legend col-sm-2">Result</legend>
                    <div class="col-sm-10">
                        <div class="form-check">
                            <label class="form-check-label">
                                Resting Metabolism :
                                <input class="form-control-plaintext rm" type="text" readonly>
                            </label>
                        </div>
                        <div class="form-check">
                            <label class="form-check-label">
                                Lean Mass :
                                <input class="form-control-plaintext lm" type="text" readonly>
                            </label>
                        </div><div class="form-check">
                            <label class="form-check-label">
                                Fat Mass :
                                <input class="form-control-plaintext fm" type="text" readonly>
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
                                Resting Metabolism :
                                <input class="form-control-plaintext rm" type="text" readonly>
                            </label>
                        </div>
                        <div class="form-check">
                            <label class="form-check-label">
                                Lean Mass :
                                <input class="form-control-plaintext lm" type="text" readonly>
                            </label>
                        </div><div class="form-check">
                            <label class="form-check-label">
                                Fat Mass :
                                <input class="form-control-plaintext fm" type="text" readonly>
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

            var selected_unit_type = 'percent'

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

            $('input[name=unit-type-m]').on('change', function () {
                selected_unit_type = $('input[name=unit-type-m]:checked').val()

                calculateMetricRestingMetabolism(selected_unit_type)
            })

            $('input[name=unit-type-i]').on('change', function () {
                selected_unit_type = $('input[name=unit-type-i]:checked').val()

                calculateImperialRestingMetabolism(selected_unit_type)
            })

            $('#metric input[type=number]').on('keyup', function () {
                calculateMetricRestingMetabolism(selected_unit_type)
            })

            $('#imperial input[type=number]').on('keyup', function () {
                calculateImperialRestingMetabolism(selected_unit_type)
            })
        })

        function generateMetricRestingMetabolism(selected_unit_type, weight, mass) {
            var data = {}

            if (selected_unit_type === 'percent') {
                data.lm = weight * (mass/100)

                data.rm = 370 + (21.6 * data.lm)

                data.lmp = mass

                data.fm = weight - data.lm

                data.fmp = 100 - mass
            } else {
                data.lm = mass

                data.rm = 370 + (21.6 * data.lm)

                data.lmp = ((mass / weight) * 100).toFixed(1)

                data.fm = weight - data.lm

                data.fmp = (100 - data.lmp).toFixed(1)
            }

            return data
        }

        function calculateMetricRestingMetabolism(selected_unit_type) {
            var weight = $('#metric input[name=weight]').val()

            var mass = $('#metric input[name=mass]').val()

            if (!isNaN(weight) && isFinite(mass)) {
                var data = generateMetricRestingMetabolism(selected_unit_type, weight, mass)

                $('#metric-result .rm').val(Math.round(data.rm) + ' calories per day')
                $('#metric-result .lm').val(Math.round(data.lm) + 'kg    (' + data.lmp + '%)')
                $('#metric-result .fm').val(Math.round(data.fm) + 'kg    (' + data.fmp + '%)')
            } else {
                $('#metric-result .rm').val('')
                $('#metric-result .lm').val('')
                $('#metric-result .fm').val('')
            }
        }

        function generateImperialRestingMetabolism(selected_unit_type, weight, mass) {
            var data = {}

            if (selected_unit_type === 'percent') {
                data.lm = (weight * (mass/100))

                data.rm = 370 + (21.6 * (data.lm / 2.2))

                data.lmp = mass

                data.fm = weight - data.lm

                data.fmp = 100 - mass
            } else {
                data.lm = mass

                data.rm = 370 + (21.6 * (data.lm / 2.2))

                data.lmp = ((mass / weight) * 100).toFixed(1)

                data.fm = weight - data.lm

                data.fmp = (100 - data.lmp).toFixed(1)
            }

            return data
        }


        function calculateImperialRestingMetabolism(selected_unit_type) {
            var weight = $('#imperial input[name=weight]').val()

            var mass = $('#imperial input[name=mass]').val()

            if (!isNaN(weight) && isFinite(mass)) {
                var data = generateImperialRestingMetabolism(selected_unit_type, weight, mass)

                $('#imperial-result .rm').val(Math.round(data.rm) + ' calories per day')
                $('#imperial-result .lm').val(Math.round(data.lm) + 'lbs    (' + data.lmp + '%)')
                $('#imperial-result .fm').val(Math.round(data.fm) + 'lbs    (' + data.fmp + '%)')
            } else {
                $('#imperial-result .rm').val('')
                $('#imperial-result .lm').val('')
                $('#imperial-result .fm').val('')
            }
        }

    </script>
@endsection
