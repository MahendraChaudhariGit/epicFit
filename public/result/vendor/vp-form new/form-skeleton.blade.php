
<!-- start: CHECKBOX -->

<!-- start: paIntensity | 13 -->
<li class="vp-item vp-form-active" data-index="13" data-sub-index="0" data-type="checkbox" data-valid="@{{formPQ.paIntensity.$valid}}">
    <div class="vp-input input-yes-no-btn">
        <h3 class="vp-index pull-left">7. &nbsp;&nbsp;</h3>

        <div class="input-header">
            <h3>
                <!-- label -->
                <i class="fa fa-arrow-right" aria-hidden="true"></i> <span>What is your <b>preferred intensity</b> of physical activity?</span>
                <!-- description -->
                {{--<span class="description"><br>Here will be the description of the form!</span>--}}
            </h3>

            {{--<i class="fa fa-bullseye fa-5x title-icon" aria-hidden="true"></i>--}}
        </div> <!-- end: INPUT HEADER -->

        <div class="input-body mb ml-0">



            <?php
            if(!count($parq->paIntensity))
                $parq->paIntensity = [];
            ?>

            <script>
                $(document).ready(function() {
                    setTimeout(function () {
                        var selectedPI = {!! json_encode($parq->paIntensity) !!};
                        $("#paIntensity").selectpicker("val", selectedPI);

                        window.pqs2DataSetPaIntensity(selectedPI);
                        window.digestPqs2();
                    }, 1000);
                });
            </script>

            <ul class="click-box clear-both dib">
                <li ng-repeat="option in data.checkbox[0].options" ng-click="setCheckboxValue(0, $index)" class="@{{ (data.checkbox[0].activeOptions.indexOf(option.value) != '-1') ? 'active' : '' }}">
                    <img class="check-img" src="{{ asset('result/vendor/vp-form/images/check-right.png') }}" alt="Image">
                    <div class="box-content">
                        <p ng-if="option.icon"><i class="@{{ option.icon }}" aria-hidden="true"></i></p>
                        <p ng-if="option.iconUrl"><img src="@{{ option.iconUrl }}" alt="Image"></p>

                        <p ng-if="option.isDataReceiving=='yes'" class="vp-wrap-custom-value">
                                    <span class="clear-both" contenteditable
                                          strip-br="true"
                                          required ng-model="option.customValue" ng-click="stopPropagation($event)">@{{ option.value }}</span>
                            <span class="btn btn-success btn-xs" ng-click="updateCheckboxOptionValue($event, 0, $index)">Ok</span>
                        </p>
                        <p ng-if="option.isDataReceiving=='no'" class="vp-wrap-custom-value">@{{ option.value }}</p>
                    </div> <!-- end: BOX CONTENT -->

                    <span ng-if="option.key" class="yes">@{{ option.key }}</span>
                    <span ng-if="option.key" class="yes-active">key <b>@{{ option.key }}</b></span>
                    <span class="click-box-label">
                                @{{ option.label }}
                            </span>
                </li>
            </ul> <!-- end: CLICK BOX -->

            <input type="hidden" ng-keypress="pressEnter($event)" name="paIntensity" value="paIntensity" ng-model="paIntensity" placeholder="" class="form-control mb">

            <div ng-if="formPQ.paIntensity.$touched && formPQ.paIntensity.$invalid" class="vp-tooltip">
                <span>This field is required!</span>
            </div>

            <div ng-show="formPQ.paIntensity.$valid" class="enter-btn active">
                <button type="button" class="btn btn-primary" ng-click="jumpToNextInput()">
                    OK <i class="fa fa-check" aria-hidden="true"></i>
                </button>
                <span class="press-enter">click <b>OK</b></span>
            </div>

        </div> <!-- end: INPUT BODY -->
    </div> <!-- end: INPUT TEXT NAME -->
    <div class="clear-both"></div>
</li>
<!-- end: paIntensity | 13 -->

<!-- end: CHECKBOX -->




<!-- start: CHECKBOX HORIZONTAL -->

<!-- start: paIntensity | 13 -->
<li class="vp-item vp-form-active" data-index="13" data-sub-index="0" data-type="checkbox" data-valid="@{{formPQ.paIntensity.$valid}}">
    <div class="vp-input input-yes-no-btn">
        <h3 class="vp-index pull-left">7. &nbsp;&nbsp;</h3>

        <div class="input-header">
            <h3>
                <!-- label -->
                <i class="fa fa-arrow-right" aria-hidden="true"></i> <span>What is your <b>preferred intensity</b> of physical activity?</span>
                <!-- description -->
                {{--<span class="description"><br>Here will be the description of the form!</span>--}}
            </h3>

            {{--<i class="fa fa-bullseye fa-5x title-icon" aria-hidden="true"></i>--}}
        </div> <!-- end: INPUT HEADER -->

        <div class="input-body mb ml-0">



            <?php
            if(!count($parq->paIntensity))
                $parq->paIntensity = [];
            ?>

            <script>
                $(document).ready(function() {
                    setTimeout(function () {
                        var selectedPI = {!! json_encode($parq->paIntensity) !!};
                        $("#paIntensity").selectpicker("val", selectedPI);

                        window.pqs2DataSetPaIntensity(selectedPI);
                        window.digestPqs2();
                    }, 1000);
                });
            </script>

            <ul class="yes-no-content">
                <li ng-repeat="option in data.checkbox[0].options" ng-click="vpForm.setCheckboxValue(0, $index)" class="@{{ (data.checkbox[0].activeOptions.indexOf(option.value) != '-1') ? 'active' : '' }}">

                    <span ng-if="option.key" class="yes">@{{ option.key }}</span>
                    @{{ option.label }} &nbsp;&nbsp;&nbsp;
                    <i ng-if="option.icon" class="@{{ option.icon }}" aria-hidden="true"></i>
                    <img ng-if="option.iconUrl" src="@{{ option.iconUrl }}" alt="Image">

                    <p ng-if="option.isDataReceiving=='yes'" class="vp-wrap-custom-value">
                                    <span contenteditable
                                          strip-br="true"
                                          required ng-model="option.customValue" ng-click="stopPropagation($event)">@{{ option.value }}</span><br>
                        <span class="btn btn-success btn-xs" ng-click="updateCheckboxOptionValue($event, 0, $index)">Ok</span>
                    </p>
                </li>
            </ul> <!-- end: CLICK BOX -->

            <input type="hidden" ng-keypress="pressEnter($event)" name="paIntensity" value="paIntensity" ng-model="paIntensity" placeholder="" class="form-control mb">

            <div ng-if="formPQ.paIntensity.$touched && formPQ.paIntensity.$invalid" class="vp-tooltip">
                <span>This field is required!</span>
            </div>

            <div ng-show="formPQ.paIntensity.$valid" class="enter-btn active">
                <button type="button" class="btn btn-primary" ng-click="jumpToNextInput()">
                    OK <i class="fa fa-check" aria-hidden="true"></i>
                </button>
                <span class="press-enter">click <b>OK</b></span>
            </div>

        </div> <!-- end: INPUT BODY -->
    </div> <!-- end: INPUT TEXT NAME -->
    <div class="clear-both"></div>
</li>
<!-- end: paIntensity | 13 -->

<!-- end: CHECKBOX HORIZONTAL -->