/* Prototype extensions */
jQuery(function ($) {

    /* Extending string prototype */
    String.prototype.replaceAt = function (index, character) {
        return this.substr(0, index) + character + this.substr(index + character.length);
    }

    PT = {};

    /* preserves all state information posted to server */
    PT.State = {
        Gender: 0,
        Plan: 1,
        Habit: 2,
        Method: 1,
        Intensity: 300,
        Experience: 300,
        ScheduleType: 2,
        TimePerWeek: 60,
        DaysOfWeek: "0101010", /* Server WeekDays -> Sun,Mon,Tue,Wed,Thr,Fri,Sat */
        Height: 170, /* Server will posted the data in cms regardless of what system UI has */
        Weight: 50, /* Server will posted the data in kg regardless of what system UI has */
        Age: 25,
        Email: "",
        SessionGuid: "",
        TimeOnClient: ""
    };

    PT.Config = {
        WeightMin: 35,
        WeightMax: 160,
        HeightMin: 140,
        HeightMax: 200,
        AgeMin: 18,
        AgeMax: 100
    };

    /* Handles all conversion processes */
    PT.Converter = new function () {

        this.ToKG = function (lbsValue) {
            return Math.round((lbsValue / 2.2));
        };

        this.ToCMS = function (feetInchValue) {
            var FI = feetInchValue.split("ft ");
            var feetValue = parseInt(FI[0]);
            var inchValue = parseInt(FI[1]);
            return Math.round((inchValue * 2.54) + (feetValue * 12 * 2.52));
        };

        this.ToLBS = function (kgValue) {
            return Math.round(kgValue * 2.2);
        };

        this.ToFeetInch = function (cmsValue) {
            cmsForInch = cmsValue % 30;
            var feetValue = parseInt("" + (cmsValue / 30));
            var inchValue = Math.round(cmsForInch / 2.56);
            return (feetValue + "ft " + inchValue);
        };

        this.ToPerDayTime = function (minsPerWeek, daysTraining) {

            var minsPerDay = minsPerWeek / daysTraining;
            return PT.Converter.formatTime(minsPerWeek) + " (~" + Math.floor(minsPerDay) + " mins daily)";
        };

        this.formatDateTime = function (date) {

            var leadingMin = ':';

            if (date.getMinutes() < 10)
                leadingMin = ':0';

            return $.datepicker.formatDate("dd-M-yy ", date) + date.getHours() + leadingMin + date.getMinutes();
        }

        /*this.formatTime2 = function (secs) {
            var hours = Math.floor(secs / 60 / 60),
                minutes = Math.floor((secs - (hours * 60 * 60)) / 60),
                seconds = Math.round(secs - (hours * 60 * 60) - (minutes * 60));
            return hours + ':' + ((minutes < 10) ? '0' + minutes : minutes) + ':' + ((seconds < 10) ? '0' + seconds : seconds);
        };*/

        this.formatTime = function (mins) {

            var hours = Math.floor(mins / 60),
                minutes = Math.floor(mins % 60);

            return hours + ':' + ((minutes < 10) ? '0' + minutes : minutes);
        };
    };

    /* Handles all ajax calls for Personal Trainer */
    PT.Ajax = new function () {


        /* Where to call back */
        // this.APIURL = 'http://api.onesportevent.com/DevApi';
        this.APIURL = 'http://192.168.0.50/result/public';

        this.UpdatePlan = function () {

            $("#planSchedule").empty();
            $("#planSchedule").addClass("loading");
            $.ajax({
                url: PT.Ajax.APIURL + "/Planner/GetPlan",
                data: PT.State,
                dataType: 'json',
                type: "POST",
                success: function (data) {
                    $("#planSchedule").removeClass("loading");
                    $("#planMessage", "#planInfo").html(data.Message);
                    PT.GeneratePlan(data.Response.Plans);
                }
            });
        };

        this.SavePlan = function () {

            // Show spinner ajax loader
            $(".fit_saveplan_ajax").show();
            $("#doneButton").addClass("ui-state-disabled");
            //$("#doneButton").button("option", "disabled", true);

            $.ajax({
                url: PT.Ajax.APIURL + "/Planner/SavePlan",
                data: PT.State,
                dataType: 'json',
                type: "POST",
                success: function (data) {
                    $("#planMessage", "#planInfo").html(data.Message);
                    $("#planMessage", "#planInfo").show();

                    // Hide last slide
                    $(".sliderItem").css("display", "none");
                    $("#planPreviewTitle").hide();
                    $("#planFinalTitle").show();

                    // Ensure plan is shown
                    $("#planSchedule").show();

                    // Hide spinner ajax loader
                    $(".fit_saveplan_ajax").hide();
                    $("#doneButton").removeClass("ui-state-disabled");
                    //$("#doneButton").button("option", "disabled", false);
                }
                /*                error: function () {
                
                                    // Hide spinner ajax loader
                                    alert('Sorry, there has been a connection problem and we were unable to determine if your plan saved correctly.  You can safely generate and save it again.');
                                    $(".fit_saveplan_ajax").show();
                                    $("#doneButton").button("option", "disabled", false);
                
                                }*/
            });
        }
    };

    /* Will impose HTML to be updated based on received json data */
    PT.GeneratePlan = function (Plans) {
        for (var plan in Plans) {
            var injectDiv = $("<div class='planItem'></div>");
            $("<div class='planItemHeader'>" + plan + "</div>").appendTo(injectDiv);

            $.each(Plans[plan].SubGroups, function (index, item) {
                $("<div>" + item.Key + "</div>").appendTo(injectDiv);
                $.each(item.Value, function (index, exercise) {
                    var exerciseDiv = $("<div class='planItemBody'></div>");
                    $("<div class='item1'>" + exercise.Item1 + "</div>").appendTo(exerciseDiv);
                    $("<div class='item2'>" + exercise.Item2 + "</div>").appendTo(exerciseDiv);
                    exerciseDiv.appendTo(injectDiv);
                    $("<div class='clear'></div>").appendTo(injectDiv);
                });

            });
            $("#planSchedule", "#planInfo").append(injectDiv);
        }
    };

    /* Responsible for switching the state */
    PT.Switcher = new function () {

        this.HideUnsupportedProgrammes = function (currentValue) {

            // If strength programme, disable gym and dumbbell options
            if (PT.State.Plan == 1) {
                $("#fit_equipment_bodyweight").hide();
                $("#fit_equipment_swiss").hide();
            }
            else {
                $("#fit_equipment_bodyweight").show();
                $("#fit_equipment_swiss").show();
            }
        }

        /* Switchs Slider to Next Slide */
        this.GoToNext = function () {
            var currentValue = $("#progressBar").progressbar("option", "value");
            var maxValue = $("#progressBar").progressbar("option", "max");

            /* Handles progress bar */
            $("#progressBar").progressbar("option", "value", currentValue + 1);

            /* Handles navigators */
            if ((currentValue + 1) == maxValue) {
                //$("#nextNav").css("visibility", "hidden");
                $("#nextNav").button("option", "disabled", true);
            }
            else {
                //$("#nextNav").css("visibility", "visible");
                $("#nextNav").button("option", "disabled", false);
            }
            //$("#prevNav").css("visibility", "visible");
            $("#prevNav").button("option", "disabled", false);
            this.SetSlider(currentValue + 1);

            this.HideUnsupportedProgrammes();
        };

        /* Switchs Slider to Previous Slide */
        this.GoToPrev = function () {

            var currentValue = $("#progressBar").progressbar("option", "value");
            var maxValue = $("#progressBar").progressbar("option", "max");

            // If are going from the end of the process to the last slide
            if (maxValue == currentValue) {
                if (!$("#sliderItem7").is(":visible")) {
                    $("#sliderItem7").css("display", "block");
                    return;
                }
            }

            /* Handles progress bar */
            $("#progressBar").progressbar("option", "value", currentValue - 1);

            /* Handles navigators */
            if (currentValue == 2) {
                //$("#prevNav").css("visibility", "hidden");
                $("#prevNav").button("option", "disabled", true);
            }
            else {
                //$("#prevNav").css("visibility", "visible");
                $("#prevNav").button("option", "disabled", false);
            }
            //$("#nextNav").css("visibility", "visible");
            $("#nextNav").button("option", "disabled", false);
            this.SetSlider(currentValue - 1);
            this.HideUnsupportedProgrammes();

        };

        /* Changes Slide visibility */
        this.SetSlider = function (index) {
            $(".sliderItem").css("display", "none");
            $("#sliderItem" + index).css("display", "block");
        };
    };

    /* Switches the image sources between male and female on ste-p1 */
    PT.SetImageSource = function (replace) {
        var currentSrc = replace == "female" ? "male" : "female";
        var currentTitle = replace == "female" ? "Mens" : "Womens";
        var newTitle = replace == "female" ? "Womens" : "Mens";

        $('input[type="image"]', "#planChoice").each(function (index, el) {
            var src = $(el).attr('src');
            src = src.replace(currentSrc, replace);
            $(el).attr('src', src);

            var alt = $(el).attr('alt');
            alt = alt.replace(currentTitle, newTitle);
            $(el).attr('alt', alt);

            var title = $(el).attr('title');
            title = title.replace(currentTitle, newTitle);
            $(el).attr('title', title);

        });
    };

    /* Day checkboxes initialization */
    PT.InitilizeWeekDays = function () {
        for (var i = 1; i <= PT.State.DaysOfWeek.length; i++) {
            if (PT.State.DaysOfWeek.charAt(i - 1) == '1') {
                $("input:nth-child(" + (2 * (i == 1 ? 7 : i - 1) - 1) + ")", "#weekDays").attr('checked', 'checked');
            }
            else {
                $("input:nth-child(" + (2 * (i == 1 ? 7 : i - 1) - 1) + ")", "#weekDays").removeAttr('checked');
            }
        }
    };

    PT.UpdateWeeklyWorkoutTime = function (minsPerWeek) {
        $(".ui-slider-handle", "#timeSlider").html("<span class='handleText'>" + PT.Converter.ToPerDayTime(minsPerWeek, $('#weekDays input:checkbox:checked').length) + "</span>");
    };

    PT.AddLabel = function (labelArray, labelText, count) {

        for (var i = 0; i < count; i++) {
            labelArray.push(labelText);
        }
    };

    PT.HandleSliderChange = function (value, stateName) {
        PT.State[stateName] = value;
        PT.Ajax.UpdatePlan();
    };

    /* Start planner - initialization */
    PT.Start = function (showPreview) {

        // Hide the preview if requested (until the end of the process)
        if (showPreview == false) {
            $("#planSchedule").hide();
            $("#planPreviewTitle").hide();
            $("#planFinalTitle").hide();
        }
        else {
            $("#planPreviewTitle").show();
            $("#planFinalTitle").hide();
        }

        PT.State.TimeOnClient = PT.Converter.formatDateTime(new Date());                 // Timezone on client
        PT.State.Email = $("#fit_email").val();                                          // 't'
        PT.State.SessionGuid = $("#fit_session").val();                                  // '590EC926-0942-481E-B11D-3666A9FBF157'
        PT.State.Gender = $("#fit_gender").val() == "M" ? 2 : 1;                         // 1: female, 2: male
        PT.State.Height = $("#fit_height").val() == "" ? 170 : $("#fit_height").val();   // Height in CMS
        PT.State.Weight = $("#fit_weight").val() == "" ? 55 : $("#fit_weight").val();    // Weight in KG
        PT.State.Age = $("#fit_age").val() == "" ? 25 : $("#fit_age").val();    // Weight in KG
        

        PT.Ajax.UpdatePlan();
        /* Progressbar */
        $("#progressBar").progressbar({
            value: 1,
            max: 7
        });

        /* Switcher Slides */
        $(".sliderItem").css("display", "none");
        $("#sliderItem1").css("display", "block");
        //$("#prevNav").css("visibility", "hidden");
        $("#prevNav").button({ disabled: true });

        /* init weekdays - must be above time slider as time slider cals number of days available immediately */
        PT.InitilizeWeekDays();
        $("input[type='checkbox']", ".itemBody").button();

        var intensityLabels = new Array();
        PT.AddLabel(intensityLabels, "Not Very", 50);
        PT.AddLabel(intensityLabels, "A little", 100);
        PT.AddLabel(intensityLabels, "Somewhat", 100);
        PT.AddLabel(intensityLabels, "Very", 100);
        PT.AddLabel(intensityLabels, "Extremely", 150);

        /* Slider inputs */
        $("#intensitySlider").labeledslider({
            max: 400,
            value: PT.State.Intensity - 100,
            tickLabels: intensityLabels,
            tickInterval: 100,
            min: 0,
            range: "min",
            slide: function (event, ui) {
                var intensityVal = 0;

                if (ui.value <= 50) {
                    intensityVal = 100;
                }
                else if (ui.value <= 150) {
                    intensityVal = 200;
                }
                else if (ui.value <= 250) {
                    intensityVal = 300;
                }
                else if (ui.value <= 350) {
                    intensityVal = 400;
                }
                else {
                    intensityVal = 500;
                }

                $("#intensitySelector").val(intensityVal);
            },
            change: function () {
                PT.HandleSliderChange($("#intensitySelector").val() - 1, 'Intensity');
            }

        });
        $("#intensitySelector").bind("change", function () {
            $("#intensitySlider").labeledslider("value", $("#intensitySelector").val() - 100);
            //PT.HandleSliderChange($("#intensitySelector").val() - 1, 'Intensity');
        });
        $("#intensitySelector").val(PT.State.Intensity);

        /* ************* */
        var historyLabels = new Array();
        PT.AddLabel(historyLabels, "Not Much", 50);
        PT.AddLabel(historyLabels, "A little", 100);
        PT.AddLabel(historyLabels, "Some", 100);
        PT.AddLabel(historyLabels, "A lot", 100);
        PT.AddLabel(historyLabels, "A ton", 150);

        $("#experienceSlider").labeledslider({
            max: 400,
            value: PT.State.Experience - 100,
            tickLabels: historyLabels,
            tickInterval: 100,
            min: 0,
            range: "min",
            slide: function (event, ui) {
                var val = 0;

                if (ui.value <= 50) {
                    val = 100;
                }
                else if (ui.value <= 150) {
                    val = 200;
                }
                else if (ui.value <= 250) {
                    val = 300;
                }
                else if (ui.value <= 350) {
                    val = 400;
                }
                else {
                    val = 500;
                }

                $("#experienceSelector").val(val);
            },
            change: function () {
                PT.HandleSliderChange($("#experienceSelector").val() - 1, 'Experience');
            }
        });

        $("#experienceSelector").val(PT.State.Experience);
        $("#experienceSelector").bind("change", function () {
            $("#experienceSlider").labeledslider("value", $("#experienceSelector").val() - 100);
            //PT.HandleSliderChange($("#experienceSelector").val() - 1, 'Experience');
        });
        /* ************* */

        var timeLabels = new Array();

        for (var i = 0; i <= 360; i++) {
            timeLabels.push(PT.Converter.formatTime(i));
        }

        $("#timeSlider").labeledslider({
            max: 360,
            tickInterval: 30,
            value: PT.State.TimePerWeek,
            tickLabels: timeLabels,
            range: "min",
            min: 0,
            create: function (event, ui) {
                PT.UpdateWeeklyWorkoutTime(PT.State.TimePerWeek);
                //$(".ui-slider-handle", "#timeSlider").html("<span class='handleText'>" + PT.Converter.ToPerDayTime(PT.State.TimePerWeek, $('#weekDays input:checkbox:checked').length) + "</span>");
            },
            slide: function (event, ui) {
                PT.UpdateWeeklyWorkoutTime(ui.value);
                PT.State.TimePerWeek = ui.value;
                //$(".ui-slider-handle", "#timeSlider").html("<span class='handleText'>" + PT.Converter.ToPerDayTime(ui.value, $('#weekDays input:checkbox:checked').length) + "</span>");
            },
            change: function () {
                PT.HandleSliderChange($('#timeSlider').labeledslider('option', 'value'), 'TimePerWeek');
            }
        });
        /* ************* */
        $("#weightSlider").labeledslider({
            min: PT.Config.WeightMin,
            max: PT.Config.WeightMax,
            tickInterval: 10,
            value: PT.State.Weight,
            range: "min",
            slide: function (event, ui) {

                if ($("#weightButton").val().indexOf("lbs") >= 0) {

                    // If in KG mode
                    $("#weightKGSelector").val(ui.value);
                    $("#weightLBSSelector").val(PT.Converter.ToLBS(ui.value));
                }
                else {

                    // If in LBS mode
                    $("#weightKGSelector").val(PT.Converter.ToKG(ui.value));
                    $("#weightLBSSelector").val(ui.value);
                }
            }

        });

        //for (var i = $("#weightSlider").slider('option', 'min') ; i <= $("#weightSlider").slider('option', 'max') ; i++) {
        for (var i = PT.Config.WeightMin; i <= PT.Config.WeightMax; i++) {
            $('<option>').val(i).text(i + ' kg').appendTo('#weightKGSelector');
        }

        $("#weightKGSelector").val(PT.State.Weight);

        for (var i = PT.Converter.ToLBS(PT.Config.WeightMin) ; i <= PT.Converter.ToLBS(PT.Config.WeightMax) ; i++) {
            $('<option>').val(i).text(i + ' lbs').appendTo('#weightLBSSelector');
        }

        $("#weightLBSSelector").val(PT.Converter.ToLBS(PT.State.Weight));
        $("#weightKGSelector").bind("change", function () {

            // If changing KG dropdown
            $("#weightSlider").labeledslider("value", $("#weightKGSelector").val());         // set slider value in KG
            $("#weightLBSSelector").val(PT.Converter.ToLBS($("#weightKGSelector").val()));   // set other LBS selector value for when we swap to it

        });
        $("#weightLBSSelector").bind("change", function () {

            // If changing LBS dropdown
            $("#weightSlider").labeledslider("value", $("#weightLBSSelector").val());         // set slider value in LBS
            $("#weightKGSelector").val(PT.Converter.ToKG($("#weightLBSSelector").val()));     // set other KG selector value for when we swap to it

        });
        /* ************* */
        $("#heightSlider").labeledslider({
            tickInterval: 10,
            useFeetInch: true,
            min: PT.Config.HeightMin,
            max: PT.Config.HeightMax,
            value: PT.State.Height,
            range: "min",
            slide: function (event, ui) {
                $("#heightCMSSelector").val(ui.value);
                if (event.originalEvent) {
                    $("#heightFISelector").val(PT.Converter.ToFeetInch(ui.value));
                }
            }
        });

        for (var i = PT.Config.HeightMin; i <= PT.Config.HeightMax; i++) {
            $('<option>').val(i).text(i + ' cm').appendTo('#heightCMSSelector');
        }

        $("#heightCMSSelector").val(PT.State.Height);
        for (var i = PT.Converter.ToCMS(PT.Converter.ToFeetInch(PT.Config.HeightMin)) ;
                    i <= PT.Converter.ToCMS(PT.Converter.ToFeetInch(PT.Config.HeightMax)) ;
                    i = i + 2.54) {
            $('<option>').val(PT.Converter.ToFeetInch(i)).text(PT.Converter.ToFeetInch(i) + ' "').appendTo('#heightFISelector');
        }
        $("#heightFISelector").val(PT.Converter.ToFeetInch(PT.State.Height));
        $("#heightCMSSelector").bind("change", function () {

            // If changing CMS dropdown
            $("#heightSlider").labeledslider("value", $("#heightCMSSelector").val());       // set slider value in CMS
            $("#heightFISelector").val(PT.Converter.ToFeetInch($("#heightCMSSelector").val())); // set other FT selector value for when we swap to it

        });
        $("#heightFISelector").bind("change", function () {

            $("#heightSlider").labeledslider("value", PT.Converter.ToCMS($("#heightFISelector").val()));
            $("#heightCMSSelector").val(PT.Converter.ToCMS($("#heightFISelector").val())); // set other FT selector value for when we swap to it

        });
        /* ************* */
        $("#ageSlider").labeledslider({
            min: PT.Config.AgeMin,
            max: PT.Config.AgeMax,
            tickInterval: 5,
            value: PT.State.Age,
            range: "min",
            slide: function (event, ui) {
                $("#ageSelector").val(ui.value);
            }
        });

        for (var i = PT.Config.AgeMin; i <= PT.Config.AgeMax; i++) {
            $('<option>').val(i).text(i).appendTo('#ageSelector');
        }
        $("#ageSelector").val(PT.State.Age);
        $("#ageSelector").bind("change", function () {
            $("#ageSlider").labeledslider("value", $("#ageSelector").val());
        });
        /* ************* */
        /* End of Slider inputs */

        /* Button click events binding */
        $("#prevNav").button({ icons: { primary: "ui-icon-triangle-1-w" } });
        $("#nextNav").button({ icons: { primary: "ui-icon-triangle-1-e" } });

        $("#prevNav").bind('click', function () { PT.Switcher.GoToPrev(); $("#planMessage").hide() });
        $("#nextNav").bind('click', function () { PT.Switcher.GoToNext(); });

        $("#customNextButton").button();
        $("#customNextButton").bind('click', function () { PT.Switcher.GoToNext(); });

        $("#doneButton").bind('click', function () { PT.Ajax.SavePlan(); });
        $("#maleButton").bind('click', function () { PT.SetImageSource('female'); });
        $("#femaleButton").bind('click', function () { PT.SetImageSource('male'); });
        $("#weightButton").button();
        $("#weightButton").bind('click', function (evt) {
            $("#weightKGSelector").toggle();
            $("#weightLBSSelector").toggle();
            $("#weightSpan").text($(evt.target).val());

            var weight = $("#weightSlider").labeledslider("option", "value");

            if ($(evt.target).val().indexOf("lbs") >= 0) {

                // Moving to KG
                $(evt.target).val($(evt.target).val().replace("lbs", "kg"));
                $('#weightSlider').labeledslider('option', 'min', PT.Converter.ToLBS(PT.Config.WeightMin));
                $('#weightSlider').labeledslider('option', 'max', PT.Converter.ToLBS(PT.Config.WeightMax));
                $('#weightSlider').labeledslider('option', 'tickInterval', 20);
                $("#weightSlider").labeledslider("value", PT.Converter.ToLBS(weight));
            }
            else {

                // Moving to LBS
                $(evt.target).val($(evt.target).val().replace("kg", "lbs"));
                $('#weightSlider').labeledslider('option', 'min', PT.Config.WeightMin);
                $('#weightSlider').labeledslider('option', 'max', PT.Config.WeightMax);
                $('#weightSlider').labeledslider('option', 'tickInterval', 10);
                $("#weightSlider").labeledslider("value", PT.Converter.ToKG(weight));
            }
        });

        $("#heightButton").button();
        $("#heightButton").bind('click', function (evt) {
            $("#heightCMSSelector").toggle();
            $("#heightFISelector").toggle();
            $("#heightSpan").text($(evt.target).val());
            if ($(evt.target).val().indexOf("cm") >= 0) {
                $(evt.target).val($(evt.target).val().replace("cm", 'feet & inch'));


                $('#heightSlider').labeledslider('option', 'tickLabels', null);

            }
            else {
                var ftLabels = new Array();

                for (var i = 0; i <= PT.Config.HeightMax; i++) {
                    ftLabels.push(PT.Converter.ToFeetInch(i));
                }

                $('#heightSlider').labeledslider('option', 'tickLabels', ftLabels);
                $('#heightSlider').labeledslider('option', 'tickInterval', 15);


                $(evt.target).val($(evt.target).val().replace('feet & inch', 'cm'));
            }
        });
        /* End of Button click events binding */


        /*  */

        /* Provides selection event binding to all input in module which changes the state */
        $('input[type="image"]', "#planSelector").bind("click", function (evt) {
            $(evt.target).siblings('input[type="image"]').removeClass("active");
            $(evt.target).addClass("active");
            var stateName = $(evt.target).parent().prev("div.itemHeader").attr("data-name");
            PT.State[stateName] = $(evt.target).val();
            PT.Switcher.GoToNext();
            /* If user wants us to make schedule we will not make ajax call as it will be done by that selection */
            if ($(evt.target).val() != '1' || !($("#sliderItem5").find(evt.target).length > 0)) {
                PT.Ajax.UpdatePlan();
            }
        });


        $(".ui-slider").on("slidechange", function (event, ui) {
            var stateName = $(event.target).parent().prev("div.itemHeader").attr("data-name");
            PT.State[stateName] = ui.value;
            PT.Ajax.UpdatePlan();
        });

        $('input[type="checkbox"]', "#planSelector").bind("change", function (evt) {
            var isChecked = $(evt.target).is(':checked');
            var index = $('input[type="checkbox"]', $(evt.target).parent()).index(evt.target);
            PT.State.DaysOfWeek = PT.State.DaysOfWeek.replaceAt(index == 6 ? 0 : index + 1, isChecked ? '1' : '0');
            PT.Ajax.UpdatePlan();

            // When day choosen update slider message
            PT.UpdateWeeklyWorkoutTime(PT.State.TimePerWeek);

        });
        /* End of State Preservation logic */

        /* Prevents less than 2 checkboxes to be checked in week day selection */
        $('input[type="checkbox"]', "#planSelector").bind("click", function (evt) {
            if (!$(evt.target).is(':checked') && $('input[type="checkbox"]:checked', "#planSelector").size() < 2) {
                evt.preventDefault();
                var warningDiv = $("<div> At least two days must be selected. </div>");
                warningDiv.dialog({
                    create: function (event, ui) {
                        $('.ui-dialog').wrap('<div class="fit-ui" />');
                    },
                    open: function (event, ui) {
                        $('.ui-widget-overlay').wrap('<div class="fit-ui" />');
                    },
                    close: function (event, ui) {
                        $(".fit-ui").filter(function () {
                            if ($(this).text() == "") {
                                return true;
                            }
                            return false;
                        }).remove();
                    },
                    buttons: { Ok: function () { $(this).dialog("close"); } },
                    title: "Message",
                    modal: true,
                    resizable: false
                });




                return false;
            }
        });

        /* User wants us to  plan our own. So we decide here the next step values. */
        $('input[type="image"]', '#sliderItem5').bind("click", function (evt) {
            if ($(evt.target).val() == '1') {
                PT.State.DaysOfWeek = "0101010";
                PT.State.TimePerWeek = 200;
                PT.InitilizeWeekDays();
                $("#timeSlider").labeledslider("value", PT.State.TimePerWeek);
                $(".ui-slider-handle", "#timeSlider").html("<span class='handleText'>" + PT.Converter.ToPerDayTime(PT.State.TimePerWeek, 3) + "</span>");
                $("input[type='checkbox']", ".itemBody").button('refresh');
            }
        });

    };

});