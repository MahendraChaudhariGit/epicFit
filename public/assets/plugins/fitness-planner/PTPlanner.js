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
        TimeOnClient: "",
        FixedProgramId: null,       // Prewritten programe id
        GetPreWritten: false,       // Ask API to return matching prewritten programmes 
        WeeksToExercise: 12,        // Number of weeks to exercise
        Custom: false               // Custom builder activated
    };

    PT.Plan = {
        RequiredDayPattern: null    // Day pattern dictated by this program (if any)
    };

    PT.Config = {
        WeightMin: 35,
        WeightMax: 160,
        HeightMin: 140,
        HeightMax: 200,
        AgeMin: 18,
        AgeMax: 100,
        ShowPreview: true
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
        //this.APIURL = 'http://resdev.epictrainer.com';
        this.APIURL = 'http://localhost:2879';

        this.UpdatePlan = function () {

            // If we are previewing the plan during creation
            /*if (PT.Config.ShowPreview == false) {
                return;
            }*/

            $("#planSchedule").empty();
//            $("#planSchedule").addClass("loading");
            $.getJSON(
                PT.Ajax.APIURL + "/Planner/GetPlan?jsoncallback=?",
                PT.State,
                function (data) {
                 
                    $("#planSchedule").removeClass("loading");
                    $("#planMessage", "#planInfo").html(data.Message);

                    if (data.Mode == "Prewritten") {
                        PT.ShowPrewrittenPlans(data.Response);
                  //   alert("rew");
                    }
                    else {
                        PT.GeneratePlan(data.Response.Plans);
                     alert("not okk");
                    }

                    // Populate any pre-written plans

                }
            );
        };

        this.SavePlan = function () {

            // Show spinner ajax loader
            $(".fit_saveplan_ajax").show();
            $("#doneButton").addClass("ui-state-disabled");
            //$("#doneButton").button("option", "disabled", true);

            // For custom programs used the custom builders FixedProgramId
            if (PT.State["Plan"] == 7) {
                console.log('custom', CB.selectedProgram.id);
                PT.State['FixedProgramId'] = CB.selectedProgram.id;
            }

            console.log('saving', PT.State['FixedProgramId']);

            $.getJSON(
                PT.Ajax.APIURL + "/Planner/SavePlan?jsoncallback=?",
                PT.State,
                function (data) {

                    $("#planMessage", "#planInfo").html(data.Message);
                    $("#planMessage", "#planInfo").show();

                    // Hide last slide
                    $(".sliderItem").css("display", "none");
                    $("#planPreviewTitle").hide();

                    // For fixed programs display a link to the full scheduler, for repeating programs show a preview
                    if (data.PlanType.FixedProgramId == undefined || data.PlanType.FixedProgramId == null) {
                        $("#planFinalTitle").show();
                    }
                    else {
                        $("#planLink").show();
                    }

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
            );
        }
    };

    /* Will impose HTML to be updated based on received json data */
    PT.GeneratePlan = function (Plans) {

        var dayNumber = 1;

        for (var plan in Plans) {

            var injectDiv = $("<div class='planItem'></div>");
            $("<div class='planItemHeader'>" + plan + "</div>").appendTo(injectDiv);

            // Tag every second row for styling
            if ((dayNumber % 2) == 0) {
                injectDiv.addClass('planItem-alternate');
            }
            else {
                injectDiv.removeClass('planItem-alternate');
            }

            dayNumber++;

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

        dayNumber++;
    };

    /* Shows matching pre-written plans */
    PT.ShowPrewrittenPlans = function (Plans) {

        $("#sliderItem4-prewritten .itemBody").html("");

        for (var i = 0; i < Plans.length; i++) {
            var prePaidClass = Plans[i].IsPaidProgram == true ? " class='.purchased-service' " : "";
            var injectDiv = $('<input type="image" ' + prePaidClass + ' title="' + Plans[i].ProgramName + '" alt="' + Plans[i].ProgramName + '" data-daypattern="' + Plans[i].DayPattern + '" data-defaultweeks="' + Plans[i].DefaultWeeks + '" value="' + Plans[i].FixedProgramId + '" src="/wp-content/plugins/fitness-planner/css/program_images/' + Plans[i].Image + '"/>');
            $("#sliderItem4-prewritten .itemBody").append(injectDiv);
        }
    };

    /* Responsible for switching the state */
    PT.Switcher = new function () {

        this.HideUnsupportedProgrammes = function (currentValue) {

            // If strength programme, only allow gym and dumbbell options
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
            this.SetSlider(currentValue + 1, true);

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

                    // Hide the preview if requested (until the end of the process)
                    PT.SetPreviewState(PT.Config.ShowPreview);

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
            this.SetSlider(currentValue - 1), false;
            this.HideUnsupportedProgrammes();

        };

        /* Changes Slide visibility */
        this.SetSlider = function (index, isGoingForward) {

            var nextScreen = "#sliderItem" + index;
            PT.State.GetPreWritten = false;

            // Special case for prewritten plans.  TODO: Could use class or data driven if this approach expands in future
            if (PT.State["Plan"] == 6 || PT.State["Plan"] == 7) {

                if (index == 3 && PT.State["Plan"] == 7) {
                    nextScreen += "-custom";
                    $("#timeSelection").hide();  // No time selection for prewritten plans
                    CB.FX.start();
                }

                // Swap screen #4 (equipment) for alternate with prewritten programmes
                if (index == 4 && PT.State["Plan"] == 6) {
                    nextScreen += "-prewritten";
                    PT.State.GetPreWritten = true;
                }
                else if (index == 4 && PT.State["Plan"] == 7) {
                    nextScreen += "-custom";
                    PT.State.GetPreWritten = true;
                }

                // Skip how hard screen
                if (index == 5 && isGoingForward) {

                    // Fixed plan skips how hard; custom builder skips to end
                    if (PT.State["Plan"] == 6) {
                        nextScreen = "#sliderItem6";
                        $("#progressBar").progressbar("option", "value", 6);
                    }
                    else {
                        nextScreen = "#sliderItem7";
                        $("#progressBar").progressbar("option", "value", 7);
                    }
                }
                else if (index == 5 && !isGoingForward) {

                    // Fixed plan skipped how hard; custom builder skiped to end - reverse skips
                    if (PT.State["Plan"] == 6) {
                        nextScreen = "#sliderItem4-prewritten";
                        $("#progressBar").progressbar("option", "value", 4);
                    }
                    else {
                        nextScreen = "#sliderItem4-custom";
                        $("#progressBar").progressbar("option", "value", 4);
                    }

                }

                // No time selection for prewritten plans
                if (index == 7 ) {
                    $("#timeSelection").hide();
                  }

                if (index == 3) {
                    $(".program-dynamic").hide();
                    $(".program-fixed").show();
                }

            }
            else {

                // Prewritten plans hide this
                if (index == 7) {
                    $("#timeSelection").show();
                }

                if (index == 3) {
                    $(".program-dynamic").show();
                    $(".program-fixed").hide();
                }
            }

            $(".sliderItem").css("display", "none");
            $(nextScreen).css("display", "block");
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
    PT.InitializeWeekDays = function (weekPattern, doRefresh) {
        for (var i = 1; i <= weekPattern.length; i++) {
            if (weekPattern.charAt(i - 1) == '1') {
                $("input:nth-child(" + (2 * (i == 1 ? 7 : i - 1) - 1) + ")", "#weekDays").attr('checked', 'checked');
            }
            else {
                $("input:nth-child(" + (2 * (i == 1 ? 7 : i - 1) - 1) + ")", "#weekDays").removeAttr('checked');
            }
        }

        if (doRefresh) {
            $("#weekDays input[type='checkbox']").button("refresh");
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

    PT.SetPreviewState = function (showPreview) {

        if (showPreview == false) {
            $("#planSchedule").hide();
            $("#planPreviewTitle").hide();
            $("#planFinalTitle").hide();
        }
        else {
            $("#planPreviewTitle").show();
            $("#planFinalTitle").hide();
        }

    }

    /* Start planner - initialization */
    PT.Start = function (showPreview, webkeyGuid) {

        // showPreview = false;

        // Store in global config
        PT.Config.ShowPreview = showPreview;

        // Hide the preview if requested (until the end of the process)
        PT.SetPreviewState(showPreview);

        PT.State.TimeOnClient = PT.Converter.formatDateTime(new Date());                 // Timezone on client
        PT.State.Email = $("#fit_email").val();                                          // 't'
        PT.State.SessionGuid = $("#fit_session").val();                                  // '590EC926-0942-481E-B11D-3666A9FBF157'
        PT.State.Gender = $("#fit_gender").val() == "M" ? 2 : 1;                         // 1: female, 2: male
        PT.State.Height = $("#fit_height").val() == "" ? 170 : $("#fit_height").val();   // Height in CMS
        PT.State.Weight = $("#fit_weight").val() == "" ? 55 : $("#fit_weight").val();    // Weight in KG
        PT.State.Age = $("#fit_age").val() == "" ? 25 : $("#fit_age").val();    // Weight in KG

        // Custom functionality for jetts license
       //alert(PT.State.Age);
        if (webkeyGuid == "1a728943-4153-48f0-b7e3-2ad5b5c58fbe") {
          //  $(".custom-only").show();

        }

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
        PT.InitializeWeekDays(PT.State.DaysOfWeek, false);
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

                PT.State['Weight'] = ui.value;

                if ($("#weightButton").val().indexOf("lbs") >= 0) {

                    // If in KG mode
                   // $("#weightKGSelector").val(ui.value);
                    //$("#weightLBSSelector").val(PT.Converter.ToLBS(ui.value));
                }
                else {

                    // If in LBS mode
                    $("#weightKGSelector").val(PT.Converter.ToKG(ui.value));
                    $("#weightLBSSelector").val(ui.value);
                }
            }

        });

        $("#weekSlider").labeledslider({
            max: 16,
            tickInterval: 1,
            value: PT.State.WeeksToExercise,
            //tickLabels: timeLabels,
            range: "min",
            min: 1,
            create: function (event, ui) {
                //PT.UpdateWeeklyWorkoutTime(PT.State.TimePerWeek);
                //$(".ui-slider-handle", "#timeSlider").html("<span class='handleText'>" + PT.Converter.ToPerDayTime(PT.State.TimePerWeek, $('#weekDays input:checkbox:checked').length) + "</span>");
            },
            slide: function (event, ui) {

                //PT.UpdateWeeklyWorkoutTime(ui.value);
                PT.State.WeeksToExercise = ui.value;

                //$(".ui-slider-handle", "#timeSlider").html("<span class='handleText'>" + PT.Converter.ToPerDayTime(ui.value, $('#weekDays input:checkbox:checked').length) + "</span>");
            },
            change: function () {
                PT.State['WeeksToExercise'] = $('#weekSlider').labeledslider('option', 'value');
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

                PT.State['Height'] = ui.value;
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
                PT.State['Age'] = ui.value;
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

        $("#prevNav").bind('click', function () { PT.Switcher.GoToPrev(); $("#planMessage, #planLink").hide() });
        $("#nextNav").bind('click', function () { PT.Switcher.GoToNext(); });

        $("#customNextButton").button();
        $("#customNextButton").bind('click', function () { PT.Switcher.GoToNext(); });

        $("#doneButton").bind('click', function () {
                 //alert("fde");
            if (PT.Plan.RequiredDayPattern != null) {
                var daysRequired = PT.Plan.RequiredDayPattern.split("1").length - 1;
                var daysSelected = $('input[type="checkbox"]:checked', "#planSelector").size();
                         //alert(day);
                if (daysSelected > daysRequired) {planSelector
                    var warningDiv = $("<div> Please choose at most " + daysRequired + " days to be selected.  Note some plans workouts will not necessarily be scheduled on all the days you choose.</div>");
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
                }
                else {
                    PT.Ajax.SavePlan();
                }

            }
            else {
                PT.Ajax.SavePlan();
            }


        });
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
        //$('input[type="image"]', "#planSelector").bind("click", function (evt) {
        $('#planSelector1').on("click", "input[type='image']", function (evt) {
               //alert("re");
               $(evt.target).siblings('input[type="image"]').removeClass("active");
               $(evt.target).addClass("active");
               var stateName = $(evt.target).parent().prev("div.itemHeader").attr("data-name");
               PT.State[stateName] = $(evt.target).val();
                    //alert(stateName);
            //      alert(PT.State[stateName]);
            // If element controls how many weeks the plan runs for..
                    var defaultWeeks = $(evt.target).attr("data-defaultweeks");
                    var dayPattern = $(evt.target).attr("data-daypattern");

            // ..then set slider value and state
              if (defaultWeeks != undefined) {
                  $("#weekSlider").labeledslider("option", "max", defaultWeeks);
                  $("#weekSlider").labeledslider("value", defaultWeeks);
                  PT.State['WeeksToExercise'] = defaultWeeks;
            }

            if (stateName == "Plan") {

                // If we have selecred a Cardio, Transformation or Structured program
                if (PT.State.Plan == 5 || PT.State.Plan == 5 || PT.State.Plan == 5) {
                    PT.State['Method'] = null;
                }
                else if (PT.State.Plan == 7) {
                    console.log('plan one day', PT.State['FixedProgramId']);
                    PT.Plan.RequiredDayPattern = null;
                    PT.State.DaysOfWeek = "0101010"; // Reset to default selection
                    PT.InitializeWeekDays(PT.State.DaysOfWeek, true);
                    $("#daySelection").html("Please choose at least one day to work out");
                }
                else
                    {
                    PT.Plan.RequiredDayPattern = null;
                    //PT.State.DaysOfWeek = "0101010"; // Reset to default selection
                    PT.State.DaysOfWeek = "0101010"; // Reset to default selection
                    PT.InitializeWeekDays(PT.State.DaysOfWeek, true);

                    $("#daySelection").html("Please choose at least two days to work out");
                    PT.State['FixedProgramId'] = null;

                }
            }

            // For the alternate wizard screen (#4), either a method OR a fixed programm id is selected.


            //if (stateName == "Method") {
            //            }



            if (stateName == "FixedProgramId") {

                // ..then set required day pattern for this program
                if (dayPattern != undefined) {

                    PT.Plan.RequiredDayPattern = dayPattern;

                    if (PT.Plan.RequiredDayPattern != null) {
                        daysRequired = PT.Plan.RequiredDayPattern.split("1").length - 1;
                        PT.State.DaysOfWeek = PT.Plan.RequiredDayPattern; // Reset to required day pattern by default
                        PT.InitializeWeekDays(PT.Plan.RequiredDayPattern, true);
                        $("#daySelection").html("Please choose at most " + daysRequired + " days to work out");
                    }

                }
            }

            PT.Switcher.GoToNext();
            /* If user wants us to make schedule we will not make ajax call as it will be done by that selection */
            if ($(evt.target).val() != '1' || !($("#sliderItem5").find(evt.target).length > 0)) {
                PT.Ajax.UpdatePlan();
            }
        });


        /*$(".ui-slider").on("slidechange", function (event, ui) {
            
            var stateName = $(event.target).parent().prev("div.itemHeader").attr("data-name");
            console.log('slidegeneric', stateName);
            PT.State[stateName] = ui.value;
            PT.Ajax.UpdatePlan();
        });*/

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

            // By default the dynamic option requires 2 days selected
            var daysRequired = 2;

            if (PT.Plan.RequiredDayPattern != null) {
                daysRequired = PT.Plan.RequiredDayPattern.split("1").length - 1;
            }

            if (!$(evt.target).is(':checked') && $('input[type="checkbox"]:checked', "#planSelector").size() < daysRequired) {
                evt.preventDefault();

                var warningDiv = $("<div> At least " + daysRequired + " days must be selected, although with some plans workouts will not necessarily be scheduled on all the days you choose.</div>");
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

        /* User wants us to plan our own. So we decide here the next step values. */
        $('input[type="image"]', '#sliderItem5').bind("click", function (evt) {
            if ($(evt.target).val() == '1') {
                PT.State.DaysOfWeek = "0101010";
                PT.State.TimePerWeek = 200;
                //console.log('reset days of week');
                PT.InitializeWeekDays(PT.State.DaysOfWeek, true);
                $("#timeSlider").labeledslider("value", PT.State.TimePerWeek);
                $(".ui-slider-handle", "#timeSlider").html("<span class='handleText'>" + PT.Converter.ToPerDayTime(PT.State.TimePerWeek, 3) + "</span>");
                $("input[type='checkbox']", ".itemBody").button('refresh');
            }
        });

    };




   
    //jQuery(document).ready(function () {
    //	CB.FX.start();
    //});

    //jQuery(window).bind('load',function(){
    //	var wid = jQuery(document).width();
    //	if(wid < 720){
    //		jQuery( "<style> .pt-popup .popup_content { width:"+(wid-24)+"px; margin:5px 3px 3px 3px; }</style>" ).appendTo( "head" );
    //		CB.UI.popupParams.width = wid - 20;
    //	}
    //});


});



var CB = {
    //API_PATH: 'http://resdev.epictrainer.com',
    API_PATH: 'http://localhost:2879',
    platformWebKey: '',
    IsSetup: false,
    IMAGE_DIR_URL: 'http://fmdev.azurewebsites.net/pt/images/',
    NO_IMAGE: '',
    DifficultyLevels: ['beginner', 'intermediate', 'advanced'],
    selectedProgram: null,
    ProgramExerciseMap: { 'WeekIndex': 1, 'DayIndex': 1, 'Sets': 1, 'Priority': 1, 'RepOrSeconds': 30, 'Resistance': '', 'RestSeconds': 60, 'TempoDesc': '', 'TempoTiming': '', 'EstimatedTime': 60 },
    ExBodyParts: ['Shoulders', 'Chest', 'Anterior Arms', 'Core', 'Forearms', 'Anterior Thigh', 'Neck and Upper Back', 'Posterior Arms', 'Lats and Middle Back', 'Lower Back', 'Hips and Glutes', 'Posterior Thigh', 'Lower Leg'],
    UI: {
        popupParams: { className: 'pt-popup', modal: true, width: 720 },
        aexTo: null, isMobile: false, currentPage: 1, searchScroll: null,
        start: function () {
            CB.UI.isMobile = jQuery(document).width() <= 480;

            var wid = jQuery(document).width();

            if(wid < 720){
                jQuery( "<style> .pt-popup .popup_content { width:"+(wid-24)+"px; margin:5px 3px 3px 3px; }</style>" ).appendTo( "head" );
                CB.UI.popupParams.width = wid - 20;
            }

            var mplist = jQuery('.pt .my_pt');
            //console(mplist[0]);
            //current layout is for: My Programs
            if (mplist[0]) {
                CB.FX.loadProgramsList();
                return;
            }
            /*
            
            //current layout is for: My Programs
            if (mdet[0]) {
            
            }*/
        },
        startManage: function()
        {
            if( CB.IsSetup == false )
            {
                CB.UI.isMobile = jQuery(document).width() <= 480;

                var h3s = jQuery('#pt-accordion h3');
                h3s.click(function () {
                    jQuery(this).next().slideToggle();
                    jQuery(this).toggleClass('open-state');
                    return false;
                }).next().hide();
                h3s.find('a').click(CB.UI.addExercise);

                /*var acc = jQuery('#pt-accordion');
                acc.accordion({
                    collapsible: true,
                    heightStyle: 'fill'
                });
                acc.find('h3 a').click(CB.UI.addExercise);*/
            
                jQuery(".planner-next-bottom").button();
                jQuery(".planner-next-bottom").bind('click', function () { PT.Switcher.GoToNext(); });

                CB.IsSetup = true;
            }

            
            var p = jQuery.parseJSON(localStorage['CB.SelectedProgram']);
            console.log('selected prog', p, p.id);
            if (p.id) {
                var mdet = jQuery('.pt .manage_det');
                CB.selectedProgram = p;
                mdet.find('label').html(p.name);
                jQuery(mdet.find('p')[0]).html(p.desc);
                CB.FX.loadProgram(p.id);
            }
        },
        addProgramInList: function (p) {
            jQuery(CB.UI.isMobile ? '<a href="javascript:void(0)">' : '<div>')
            .attr({ 'class': 'row', 'data': jQuery.toJSON({ id: p.FixedProgramId, name: p.ProgramName, desc: p.ProgramDesc }) })
            .append('<div class="left program_title"><label>' + p.ProgramName + '</label></div>\
				<div class="left exercise_count mob_view"><label>'+ p.Snippet + '</label></div>\
				<div class="right">\
					<a class="btn_white mob_view" href="javascript:void(0)" onclick="CB.UI.goTo(\'manage\',this)">manage &rsaquo;</a>\
					<span class="mob_view_btn">&rsaquo;</span>\
				</div>\
				<div class="clr"></div>')
            .click(function () {
                if (this.tagName.toLowerCase() == 'a')
                    CB.UI.goTo('manage', jQuery(this).find('div')[0]);
            })
            .appendTo(jQuery('.pt .my_pt'));
        },
        //extract data from UI and ask FX to redirect
        goTo: function (to, obj) {
            obj = jQuery(obj);
            switch (to) {
                case 'manage':
                    localStorage['CB.SelectedProgram'] = obj.parents('.row').attr('data');
                    //location.href = 'manage.html';
                    PT.Switcher.GoToNext();
                    CB.UI.startManage();
                    break;
            }
        },
        createProgram: function (f) {
            var pn = f.pname.value;
            if (pn.replace(/ /g, '').length > 0)
                CB.FX.createProgram(pn, function (response) {
                    if (response.MessageId == 0) {
                        localStorage['CB.SelectedProgram'] = jQuery.toJSON({ id: response.Program.FixedProgramId, 'name': pn, 'desc': '' });
                        // location.href = 'manage.html';
                        PT.Switcher.GoToNext();
                        CB.UI.startManage();
                    }
                });
            else CB.showErrorDiv('.create_pt .error-msg');
        },
        //show edit program view
        showEPForm: function () {
            var md = jQuery('.manage_det');
            var p = CB.selectedProgram;
            md.find('input.txtbox').val(p.name);
            md.find('textarea').val(p.desc);
            jQuery(md.find('div')[0]).hide();
            md.find('form').fadeIn();
        },
        hideEPForm: function () {
            var md = jQuery('.manage_det');
            md.find('form').hide();
            jQuery(md.find('div')[0]).fadeIn();
        },
        addExercise: function (e) {
            CB.UI.aexTo = jQuery(this).parents('h3');
            popup.show('/wp-content/plugins/fitness-planner/custom/add_exercise.html?r=' + Math.random(), jQuery.extend({}, CB.UI.popupParams, { onload: CB.UI.initExSearch }));
            e.stopPropagation();
        },
        updateProgram: function (f) {
            var id = CB.selectedProgram.id;
            var pn = f.pname.value;
            if (pn.replace(/ /g, '').length > 0) {
                var pd = f.pdesc.value;
                CB.FX.updateProgram(id, pn, pd, function (response) {
                    if (response.MessageId == 0) {
                        var md = jQuery('.manage_det');
                        md.find('label').html(pn);
                        jQuery(md.find('p')[0]).html(pd);
                        CB.UI.hideEPForm();
                        with (CB.selectedProgram) {
                            name = pn;
                            desc = pd;
                        }
                        localStorage['CB.SelectedProgram'] = jQuery.toJSON(CB.selectedProgram);
                    }
                });
            }
            else CB.showErrorDiv('.manage_det .error-msg');
        },
        addExerciseToProgram: function (ex, edit) {
            var pc = jQuery('#pt-accordion .' + ex.WorkOut.replace(' ', '-').toLowerCase()).next(' .cat_det');
            if (pc[0]) {
                var iurl = CB.IMAGE_DIR_URL + (ex.Image ? ex.Image.ResourceName : CB.NO_IMAGE);
                var d = jQuery('<li>')
                .attr({ data: jQuery.toJSON({ id: ex.FixedProgramExerciseID, sets: ex.Sets, reps: ex.RepOrSeconds, tempo: ex.TempoTiming, rest: ex.RestSeconds, duration: ex.EstimatedTime, resist: ex.Resistance, typeid: ex.ExerciseTypeID, workout: ex.WorkOut }) })
                .append('<div class="details_list"><div class="left desc">\
						<div class="left pic"><img src="'+ iurl + '" /></div>\
						<div class="left lh18">\
							<div class="ex-name">'+ ex.ExerciseDesc + '</div>\
							<div class="font11">\
								sets: '+ ex.Sets + ', reps: ' + ex.RepOrSeconds + ', tempo: ' + ex.TempoTiming + '\
							</div>\
						</div>\
						<div class="clr"></div>\
					</div>\
					<div class="right">\
						<a href="javascript:void(0)" rel="edit"><img src="/wp-content/plugins/fitness-planner/custom/images/icon/edit.png" width="18"></a> &nbsp; \
						<a href="javascript:void(0)"><img src="/wp-content/plugins/fitness-planner/custom/images/icon/delete.png" width="18"></a>\
					</div>\
					<div class="clr"></div></div>\
					<div class="details_edit">\
						<div class="left desc"><img src="'+ iurl + '" width="79"></div>\
						<div class="right right_sec">\
							<div class="left fields">\
								<div>\
									<div class="left small"><span>Sets: </span><input type="text" class="txtbox_small sets" placeholder="sets" /></div>\
									<div class="left small"><span>Reps: </span><input type="text" class="txtbox_small reps" placeholder="reps" /></div>\
									<div class="left large"><span>Duration: </span><input type="text" class="txtbox_small duration" placeholder="duration" /></div>\
									<div class="clr"></div>\
								</div>\
								<div class="ptop5">\
									<div class="left small"><span>Tempo: </span><input type="text" class="txtbox_small tempo" placeholder="tempo" /></div>\
									<div class="left small"><span>Rest: </span><input type="text" class="txtbox_small rest" placeholder="rest" /></div>\
									<div class="left large"><span>Resistance: </span><input type="text" class="txtbox_small resist" placeholder="resistance" /></div>\
									<div class="clr"></div>\
								</div>\
							</div>\
							<div class="right">\
								<a href="javascript:void(0)" rel="save"><img src="/wp-content/plugins/fitness-planner/custom/images/icon/save.png" width="18"></a> &nbsp; \
								<a href="javascript:void(0)" rel="cancel"><img src="/wp-content/plugins/fitness-planner/custom/images/icon/delete.png" width="18"></a>\
							</div>\
							<div class="clr"></div>\
						</div>\
                        <div class="clr"></div></div>')
                .appendTo(pc);
                jQuery('#pt-build-instruction').hide();
                var as = d.find('a');

                //add onclick events to manage exercise, such as edit, update & delete
                as.click(function () {
                    var de = d.find('.details_edit');
                    var dl = d.find('.details_list');
                    switch (this.rel) {
                        case 'edit':
                            var dt = jQuery.parseJSON(d.attr('data'));
                            de.find('.sets').val(dt.sets);
                            de.find('.reps').val(dt.reps);
                            de.find('.rest').val(dt.rest);
                            de.find('.duration').val(dt.duration);
                            de.find('.tempo').val(dt.tempo);
                            de.find('.resist').val(dt.resist);
                            dl.hide();
                            de.fadeIn();
                            break;
                        case 'save':
                            var sts = de.find('.sets').val();
                            var rps = de.find('.reps').val();
                            var rst = de.find('.rest').val();
                            var dur = de.find('.duration').val();
                            if (isNaN(sts) || isNaN(rps) || isNaN(rst) || isNaN(dur)) {
                                alert('Sets, Reps, Rest & Duration fields must contain only numeric values.');
                                return;
                            }

                            CB.FX.updateProgramEx({ 'FixedProgramExerciseID': ex.FixedProgramExerciseID, 'FixedProgramID': CB.selectedProgram.id, 'ExerciseTypeID': ex.ExerciseTypeID, 'WorkOut': ex.WorkOut, 'Sets': sts, 'RepOrSeconds': rps, 'Resistance': de.find('.resist').val(), 'RestSeconds': rst, 'TempoTiming': de.find('.tempo').val(), 'EstimatedTime': dur }, function (response) {
                                if (response.MessageId == 0) {
                                    var ex = response.UpdatedExercise;
                                    var obj = { id: ex.FixedProgramExerciseID, sets: ex.Sets, reps: ex.RepOrSeconds, tempo: ex.TempoTiming, rest: ex.RestSeconds, duration: ex.EstimatedTime, resist: ex.Resistance, typeid: ex.ExerciseTypeID, workout: ex.WorkOut };
                                    d.attr('data', jQuery.toJSON(obj));
                                    dl.find('.font11').html('sets: ' + ex.Sets + ', reps: ' + ex.RepOrSeconds + ', tempo: ' + ex.TempoTiming);
                                    de.hide();
                                    dl.fadeIn();
                                    CB.UI.calculateAndUpdateProgramTime();
                                }
                                else {
                                  //  alert(response.Message);
                                    //restore text fields to original values
                                    var dt = jQuery.parseJSON(d.attr('data'));
                                    de.find('.sets').val(dt.sets);
                                    de.find('.reps').val(dt.reps);
                                    de.find('.resist').val(dt.resist);
                                    de.find('.rest').val(dt.rest);
                                    de.find('.tempo').val(dt.tempo);
                                    de.find('.duration').val(dt.duration);
                                }
                            });
                            break;
                        case 'cancel':
                            de.hide();
                            dl.fadeIn();
                            break;
                        default:
                            if (confirm('Are you sure you wish to remove ' + d.find('.ex-name').text() + ' from your program?')) {
                                CB.FX.removeExercise(ex.FixedProgramExerciseID, function (response) {
                                    if (response.MessageId == 0) {
                                        var p = d.parent();
                                        d.remove();
                                        var otherLi = p.find('li');
                                        if (otherLi.length) CB.UI.updateExPriorities(false, jQuery(otherLi));
                                        else CB.UI.calculateAndUpdateProgramTime();
                                        jQuery('.cat_det').sortable('refresh');
                                    }
                                });
                            }
                    }
                });
                //trigger edit mode
                if (edit) jQuery(as[0]).trigger('click');
            }
            return d;
        },
        initExSearch: function () {
            var sv = jQuery('.ex-search');
            var ticker = null;
            var keyfield = sv.find('.txtbox');
            keyfield.placeholder();
            keyfield.keyup(function () {
                clearTimeout(ticker);
                ticker = setTimeout('CB.UI.searchExercise()', 1000);
            });
            sv.find('select, #pt-favorite').change(CB.UI.searchExercise);
            CB.UI.searchScroll = new CB.InfiniteScroller(sv.find('.exercise_list'), CB.UI.searchExercise);
            CB.UI.searchExercise();

            var pc = sv.parent();
            pc.find('.tab').click(CB.UI.switchEDTab);
            pc.find('.act-btns a').click(function () {
                if (this.innerHTML.indexOf('add to') == 0) {
                    var ex = jQuery.parseJSON(jQuery(this).parents('.ex-detail').find('.tab.sel').attr('data'));
                    var ca = CB.UI.aexTo.attr('class').split(' ');
                    CB.FX.addExToProgram({ FixedProgramID: CB.selectedProgram.id, Workout: ca[0].replace('-', ' '), ExerciseTypeID: ex.id }, function (response) {
                        if (response.MessageId == 0) {
                            var ne = response.NewExercise;
                            ne.ExerciseDesc = ex.name;
                            ne.Image = ex.Image;
                            var d = CB.UI.addExerciseToProgram(ne, true);
                            sv.find('#' + ex.id + ' .right img')[1].src = '/wp-content/plugins/fitness-planner/custom/images/icon/added.png';
                            CB.UI.backToSearch();
                            jQuery('.cat_det').sortable('refresh');
                            CB.UI.updateExPriorities(false, d);
                        }
                    });
                }
                else CB.UI.backToSearch();
            });
            sv.find('.bodies img').load(function () {
                var pos = jQuery(this).position();
                //not sure why but we've to change position by 3 pixels to appear body parts at their correct places
                pos.left -= 3;
                pos.top -= 3;
                sv.find('svg').css(pos);

                var paths = sv.find('path');
                paths.click(function () {
                    paths.css('opacity', 0);
                    jQuery(this).css('opacity', 0.6);
                    var index = this.id.substr(5);
                    jQuery('#pt-bodypart').val(CB.ExBodyParts[index]);
                    CB.UI.searchExercise();
                });
                paths.hover(
                    function () {
                        var p = jQuery(this);
                        if (p.css('opacity') < 0.6)
                            p.css('opacity', 0.5);
                    },
                    function () {
                        var p = jQuery(this);
                        if (p.css('opacity') < 0.6)
                            p.css('opacity', 0);
                    }
                );
            });
        },
        //contnue indicates if search from first page or continue from last seen page
        searchExercise: function (contnue) {
            var pagenum;
            var elc = jQuery('.ex-search .exercise_list');
            var iss = CB.UI.searchScroll;
            if (contnue == true) {
                pagenum = ++CB.UI.currentPage;
                iss.enabled = false;
            }
            else {
                pagenum = CB.UI.currentPage = 1;
                iss.enabled = true;
                elc.find('.exercise').remove();
            }
            if (this.id == 'pt-bodypart') {
                var ep = elc.parent();
                ep.find('path').css('opacity', 0);
                var si = jQuery.inArray(jQuery(this).val(), CB.ExBodyParts);
                if (si >= 0) ep.find('#pt-bp' + si).css('opacity', 0.6);
            }
            var elmsg = elc.find('.result-msg');
            elmsg.html('Loading...').show();
            CB.FX.searchExercise({ keyWords: jQuery('.ex-search').find('input').val(), category: jQuery("#pt-category").val(), equipment: jQuery('#pt-equipment').val(), ability: jQuery('#pt-ability').val(), bodypart: jQuery('#pt-bodypart').val(), myFavourites: jQuery('#pt-favorite')[0].checked, perPage: 10, pageNumber: pagenum }, function (response) {
                var es = response.Exercises;
                if (response.MessageId == 0 && es.length) {
                    var clrdiv = elc.find('.clr.last');
                    for (var i = 0; i < es.length; i++) {
                        var rn = es[i].Resources[0].ResourceName;
                        jQuery('<div>')
                        .attr({ 'class': 'left exercise', 'id': es[i].ExerciseTypeID })
                        .append('<div class="left pic"><img src="' + CB.IMAGE_DIR_URL + (rn ? rn : CB.NO_IMAGE) + '" /></div>\
			                <div class="left desc">'
                                + '<div>' + es[i].ExerciseDesc + '</div>\
								<div>\
									<div class="left font11">\
										'+ CB.UI.getDLHTML(es[i].DifficultyLevel) + '\
									</div>\
									<div class="right">\
										<div><img src="/wp-content/plugins/fitness-planner/custom/images/icon/' + (es[i].IsFav ? 'remove' : 'add') + '-fav.png" width="20" /></div>\
										<div><img src="/wp-content/plugins/fitness-planner/custom/images/icon/add.png" width="23" /></div>\
									</div>\
									<div class="clr"></div>\
								</div>\
							</div>\
							<div class="clr"></div>')
                        .insertBefore(clrdiv)
                        .click(CB.UI.showExDetail)
                        .find('.right img').click(function (e) {
                            e.stopPropagation();
                            var ed = jQuery(this).parents('.exercise');
                            var id = ed.attr('id');
                            var img = this;
                            if (this.src.indexOf('-fav') > 0) {
                                //if exercise is to be added as favorite
                                if (this.src.indexOf('add') != -1)
                                    CB.FX.manageFavoriteEx(id, 'Add', function (response) {
                                        if (response.MessageId == 0) img.src = img.src.replace('add-', 'remove-');
                                    });
                                    //if exercise is to be removed from favorite
                                else
                                    CB.FX.manageFavoriteEx(id, 'Remove', function (response) {
                                        if (response.MessageId == 0) img.src = img.src.replace('remove-', 'add-');
                                    });
                            }
                            else if (this.src.indexOf('add.png') > 0) {
                                var ca = CB.UI.aexTo.attr('class').split(' ');
                                CB.FX.addExToProgram({ FixedProgramID: CB.selectedProgram.id, WorkOut: ca[0].replace('-', ' '), ExerciseTypeID: id }, function (response) {
                                    if (response.MessageId == 0) {
                                        var ne = response.NewExercise;
                                        ne.ExerciseDesc = ed.find('.desc div')[0].innerHTML;
                                        var pic = ed.find('img')[0].src;
                                        pic = pic.substr(pic.lastIndexOf('/'));
                                        ne.Image = { 'ResourceName': pic };
                                        var d = CB.UI.addExerciseToProgram(ne, true);
                                        img.src = '/wp-content/plugins/fitness-planner/custom/images/icon/added.png';
                                        jQuery('.cat_det').sortable('refresh');
                                        CB.UI.updateExPriorities(false, d);
                                    }
                                });
                            }
                        });
                    }
                    iss.enabled = true;
                    elmsg.hide();
                }
                else {
                    elmsg.html('No ' + (pagenum == 1 ? '' : 'more') + ' exercise found.');
                    iss.enabled = false;
                }
            });
            CB.UI.currentPage++;
        },
        showExDetail: function () {
            var id = this.id;
            CB.FX.getExercise(id, function (response) {
                if (response.MessageId == 0) {
                    var edc = jQuery('.ex-detail');
                    jQuery('.ex-search').hide();
                    edc.show();

                    var exs = response.Exercises;
                    var es;
                    //if exercises is not an array we need to put in array to let same code work for both
                    if (exs.length) es = exs;
                    else {
                        exs.ExerciseTypeID = id;
                        es = [exs];
                    }
                    var tcd = edc.find('.tabs .clr');
                    var tabName = es.length > 1 ? 'P<span>rogression</span>-1' : '<span>Exercise </span>Detail';
                    for (var i = 0, c = 1; i < es.length; i++) {
                        var selClass = es[i].ExerciseTypeID == id ? ' sel' : '';
                        jQuery('<div>')
                        .addClass('left tab' + selClass)
                        .html(tabName)
                        .click(CB.UI.switchEDTab)
                        .attr('data', jQuery.toJSON({ 'id': es[i].ExerciseTypeID, 'name': es[i].ExerciseDesc, 'Image': es[i].Resources[0] }))
                        .insertBefore(tcd);

                        CB.UI.renderExercise(es[i], c, selClass);
                        tabName = 'P<span>rogression</span>-' + (++c);
                    }
                }
            });
        },
        getDLHTML: function (level) {
            var dlIcons = [];
            for (var j = 0; j <= level; j++) dlIcons.push('<img src="/wp-content/plugins/fitness-planner/custom/images/icon/blue_dot.png" width="8" />');
            return dlIcons.join(' ') + '&nbsp; ' + CB.DifficultyLevels[level];
        },
        renderExercise: function (ex, index, selected) {
            var edc = jQuery('<div>')
            .addClass('details tab-' + index)
            .append('<div class="detail_content">\
					<div class="left enlarged-pic"><img src="" width="100%" /></div>\
					<div class="left pics"></div>\
					<div class="clr"></div>\
				</div>\
				<div class="desc">\
					<div class="short">\
						<div class="left latt">\
							<strong>Difficulty Level: </strong>&nbsp; <span>'+ CB.UI.getDLHTML(ex.DifficultyLevel) + '</span><br />\
							<strong>Muscles involved: </strong>&nbsp; <span>'+ CB.getOVOA(ex.MuscleAreas, 'MuscleAreaDescription').join(', ') + '</span>\
						</div>\
						<div class="left ratt">\
							<strong>Categories: </strong>&nbsp; <span>'+ CB.getOVOA(ex.Categories, 'ExerciseCategoryDescription').join(', ') + '</span><br />\
							<strong>Equipments: </strong>&nbsp; <span>'+ CB.getOVOA(ex.Equipment, 'EquipmentName').join(', ') + '</span>\
						</div>\
						<div class="clr"></div>\
					</div>\
					<div class="explanation">'+ ex.Explanation + '</div>\
				</div>')
            .insertBefore(jQuery('.ex-detail .act-btns'));
            if (!selected)
                edc.css('display', 'none')

            var resc = ex.Resources;
            var pd = edc.find('.pics');
            
            var placedImage = false;
            for (var i = 0; i < resc.length; i++) {
                if (resc[i].ResourceTypeCD == 'I') {
                    
                    var url = CB.IMAGE_DIR_URL + resc[i].ResourceName;

                    var newImage = jQuery('<div>')
					   .addClass('left')
					   .append('<img src="' + url + '" />')
					   .appendTo(pd);
					
                    newImage.find('img').click(function (e) {

                        var ep = edc.find('.enlarged-pic img');
                        var src = jQuery(this)[0].src;
                        
                        ep.fadeOut('fast', function (e) {
					        ep.attr('src', src);
					        ep.fadeIn('fast');
					    });
                    });

                    if (placedImage == false) {
                        var ep = edc.find('.enlarged-pic img');
                        console.log('placing', url, ep[0].src);
                        placedImage = true;
                        ep[0].src = url;
                    }
                    
                }
            }




            jQuery('<div>').addClass('clr').appendTo(pd);
            popup.autofit();
        },
        switchEDTab: function () {
            var a = jQuery(this);
            var pc = a.parents('.popup_content');
            pc.find('.tab').removeClass('sel');
            a.addClass('sel');
            pc.find('.details').hide();
            var di = a.html().indexOf('-');
            pc.find('.tab-' + (di > 0 ? a.html().substr(di + 1) : 1)).show();
            popup.autofit();
        },
        backToSearch: function () {
            var pc = jQuery('.popup_content');
            var ed = pc.find('.ex-detail');
            ed.find('.details').remove();
            ed.find('.tab').remove();
            ed.find('.pics').html('');
            ed.hide();
            pc.find('.ex-search').show();
            jQuery(popup.win).css('height', 100);
            popup.autofit();
        },
        programsLoaded: function () {
            jQuery('.cat_det').sortable({ stop: CB.UI.updateExPriorities });
            jQuery(jQuery('#pt-accordion h3')[0]).trigger('click');
            CB.UI.calculateAndUpdateProgramTime();
        },
        //can be called from sortable jquery UI, or pass evt as false & ui as any element in the li
        updateExPriorities: function (evt, ui) {
            var items = [], obj;

            //checks if user only changed the order of exercises
            if (evt) obj = ui.item;
                //otherwise this function will be called only if exercise is added or removed
            else {
                obj = ui;
                CB.UI.calculateAndUpdateProgramTime();
            }
            var obj = evt ? ui.item : ui;
            obj.parents('ul').find('li').each(function (key, exd) {
                items.push(jQuery.parseJSON(jQuery(exd).attr('data')).id);
            });
            if (items.length)
                CB.FX.updateExPriorities(items.join(','));
        },
        calculateAndUpdateProgramTime: function () {
            var secs = 0;
            jQuery('#pt-accordion li').each(function (k, d) {
                secs += parseInt(jQuery.parseJSON(jQuery(d).attr('data')).duration);
            });
            jQuery('#pt-time-estimate span').html(Math.ceil(secs / 60) + ' minutes');
        }
    },
    FX: {
        start: function (webKey) {
            //not sure why webKey is being used
            if (webKey == undefined || webKey == null)
                webKey = jQuery("#fm-webkey").val();

            // Test Account
            if (webKey == undefined || webKey == null)
                webKey = "www.testwebkey.com";

            // Set platform webkey
            CB.platformWebKey = webKey;

            //load current user session
            CB.FX.loadUserSession();

            //start CB module depending upon current page
            CB.UI.start();
        },
        loadUserSession: function () {
            //API not provided to add/get current user and so using fixed values as used in API sample
            localStorage['CB.userEmail'] = 't';
            localStorage['CB.sessionGUID'] = '590EC926-0942-481E-B11D-3666A9FBF157';
        },
        loadProgramsList: function () {
            var url = CB.API_PATH + '/CustomPlan/GetUsersPlans?jsoncallback=?';
            CB.APICallback(url, {}, function (response) {
                var plans = response.Plans;
                for (var i = 0; i < plans.length; i++) {
                    CB.UI.addProgramInList(plans[i]);
                }
            });
        },
        createProgram: function (name, callback) {
            var url = CB.API_PATH + '/CustomPlan/CreateProgram?jsoncallback=?';
            var data = { name: name, description: '', snippet: '' };
            CB.APICallback(url, data, callback);
        },
        loadProgram: function (id, callback) {

            // Clear existing data first, if any
            jQuery("#pt-accordion .cat_det").empty();

            var url = CB.API_PATH + '/CustomPlan/GetUsersPlanDetail?jsoncallback=?';
            var data = { fixedProgramId: id };
            CB.APICallback(url, data, function (response) {
                var exs = response.Exercises;
                if (exs) {
                    for (var i = 0; i < exs.length; i++) {
                        CB.UI.addExerciseToProgram(exs[i]);
                    }
                    CB.UI.programsLoaded();
                }
            });
        },
        updateProgram: function (id, name, desc, callback) {
            var url = CB.API_PATH + '/CustomPlan/UpdateProgram?jsoncallback=?';
            var data = { name: name, description: desc, snippet: '', fixedProgramId: id };
            CB.APICallback(url, data, callback);
        },
        removeExercise: function (id, callback) {
            var url = CB.API_PATH + '/CustomPlan/RemoveExerciseFromProgram?jsoncallback=?';
            CB.APICallback(url, { fixedProgramExerciseID: id }, callback);
        },
        searchExercise: function (data, callback) {
            var url = CB.API_PATH + '/CustomPlan/SearchExercises?jsoncallback=?';
            CB.APICallback(url, data, callback);
        },
        getExercise: function (id, callback) {
            var url = CB.API_PATH + '/CustomPlan/GetExerciseProgressionsForExercise?jsoncallback=?';
            CB.APICallback(url, { exerciseTypeId: id }, callback);
        },
        addExToProgram: function (exercise, callback) {
            CB.MapObject(CB.ProgramExerciseMap, exercise);
            var url = CB.API_PATH + '/CustomPlan/AddExerciseToProgram?jsoncallback=?';
            CB.APICallback(url, { jsonExercise: jQuery.toJSON(exercise) }, callback);
        },
        updateProgramEx: function (exercise, callback) {
            CB.MapObject(CB.ProgramExerciseMap, exercise);
            var url = CB.API_PATH + '/CustomPlan/UpdateExercise?jsoncallback=?';
            CB.APICallback(url, { jsonExercise: jQuery.toJSON(exercise) }, callback);
        },
        manageFavoriteEx: function (id, act, callback) {
            var url = CB.API_PATH + '/CustomPlan/' + act + 'FavExercise?jsoncallback=?';
            CB.APICallback(url, { exerciseTypeId: id }, callback);
        },
        updateExPriorities: function (ids) {
            var url = CB.API_PATH + '/CustomPlan/UpdateExercisePriority?jsoncallback=?';
            CB.APICallback(url, { fixedProgramExerciseIDsInOrder: ids }, function () { });
        }
    },
    APICallback: function (url, data, responseFunc) {
        var extraData = {
            email: localStorage['CB.userEmail'],
            SessionGuid: localStorage['CB.sessionGUID']
        };
        jQuery.extend(data, extraData);
        jQuery.getJSON(url, data, responseFunc);
    },
    showErrorDiv: function (selector) {
        jQuery(selector).show();
        setTimeout("jQuery('" + selector + "').hide()", 2000);
    },
    getProgramIDFromURL: function () {
        var id = 0;
        try {
            var qs = location.search.substr(1).split('&'), id;
            if (qs[0].indexOf('id=') == 0) id = qs[0].substr(3);
        }
        catch (e) { }
        return id;
    },
    getOVOA: function (arr, key) {
        var output = [];
        for (var i = 0; i < arr.length; i++) {
            output.push(arr[i][key]);
        }
        return output;
    },
    MapObject: function (source, target) {
        for (var i in source) if (!target[i]) target[i] = source[i];
        //return target;
    },
    InfiniteScroller: function (obj, callback) {
        _self = this;
        this.obj = obj;
        this.callback = callback;
        this.ticker = setInterval('_self.test()', 100);
        this.height = 265;
        this.enabled = true;

        this.test = function () {
            if (!this.enabled) return;
            var ib = this.obj.scrollTop();
            var xs = (this.obj[0].scrollHeight - this.height);
            if (ib > 0 && ib >= xs) this.callback(true);
        }
    }
};
