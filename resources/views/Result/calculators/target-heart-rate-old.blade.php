@extends('calculators.layout')

@section('title', 'Target Heart Rate Calculator')

@section('content')
    <h1>Target Heart Rate Calculator</h1>
    <br><br><br>

    <div class="container">
        <form>
            <fieldset class="form-group">
                <div class="row">
                    <legend class="col-form-legend col-sm-2">Fitness Goal</legend>
                    <div class="col-sm-10">
                        <div class="form-check">
                            <label class="form-check-label">
                                <input class="form-check-input" type="radio" name="goal" value="get-fit" checked>
                                Get Fit
                            </label>
                        </div>
                        <div class="form-check">
                            <label class="form-check-label">
                                <input class="form-check-input" type="radio" name="goal" value="lose-weight">
                                Lose Weight
                            </label>
                        </div>
                        <div class="form-check">
                            <label class="form-check-label">
                                <input class="form-check-input" type="radio" name="goal" value="increase-endurance">
                                Increase Endurance
                            </label>
                        </div>
                        <div class="form-check">
                            <label class="form-check-label">
                                <input class="form-check-input" type="radio" name="goal" value="excellent-fitness">
                                Excellent Fitness
                            </label>
                        </div>
                        <div class="form-check">
                            <label class="form-check-label">
                                <input class="form-check-input" type="radio" name="goal" value="competitive-athletics">
                                Competitive Athletics
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
                    <label for="height" class="col-sm-2 col-form-label">Resting Heart Rate Average</label>
                    <div class="col-sm-10">
                        <input type="number" class="form-control" placeholder="Resting Heart Rate Average" name="rhra">
                    </div>
                </div>
            </div>
            <fieldset class="form-group" id="metric-result">
                <div class="row">
                    <legend class="col-form-legend col-sm-2">Target Heart Rate (THR)</legend>
                    <div class="col-sm-10">
                        <div class="form-check">
                            <label class="form-check-label">
                                Beats Per Minute :
                                <input class="form-control-plaintext bpm" type="text" readonly>
                            </label>
                        </div>
                        <div class="form-check">
                            <label class="form-check-label">
                                Beats In 10 Secs :
                                <input class="form-control-plaintext bit" type="text" readonly>
                            </label>
                        </div>
                    </div>
                </div>
            </fieldset>
            <fieldset class="form-group" id="metric-result2">
                <div class="row">
                    <legend class="col-form-legend col-sm-2">Max Heart Rate (MHR)</legend>
                    <div class="col-sm-10">
                        <div class="form-check">
                            <label class="form-check-label">
                                Beats Per Minute :
                                <input class="form-control-plaintext mrh" type="text" readonly>
                            </label>
                        </div>
                        <div class="form-check">
                            <label class="form-check-label">
                                Beats In 10 Secs :
                                <input class="form-control-plaintext mrhit" type="text" readonly>
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
            var selected_fitness_goal = 'get-fit'

            var metric = $('#metric')

            var metric_result = $('#metric-result')

            $('input[name=goal]').on('change', function () {
                selected_fitness_goal = $('input[name=goal]:checked').val()

                calculateTargetHeartRate(selected_fitness_goal)
            })

            $('#metric input[type=number]').on('keyup', function () {
                calculateTargetHeartRate(selected_fitness_goal)
            })
        })

        function calculateTargetHeartRate(selected_fitness_goal) {
            var age = $('#metric input[name=age]').val()

            var rhra = $('#metric input[name=rhra]').val()

            if (!isNaN(age) && !isNaN(rhra)) {
                var data = generateTargetHeartRate(age, rhra, selected_fitness_goal)

                $('#metric-result .bpm').val(Math.round(data.thr1) + ' - ' + Math.round(data.thr2) + ' bpm')

                $('#metric-result2 .mrh').val(Math.round(data.mhr))

                $('#metric-result .bit').val(Math.round(data.thr1 / 6) + ' - ' + Math.round(data.thr2 / 6))

                $('#metric-result2 .mrhit').val(Math.round(data.mhr / 6))
            }
        }

        function generateTargetHeartRate(age, rhra, selected_fitness_goal) {
            var data = {}
            var mhr = 220 - age

            data.mhr = mhr

            if (selected_fitness_goal === 'get-fit') {
                data.thr1 = (mhr - rhra) * .5 + Number(rhra)

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
