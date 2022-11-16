/*   compressed with: http://marijnhaverbeke.nl/uglifyjs

1. performance
5.	“multi add’ – I love this option on my fitness pal. You can add all at once

a. drill through to drinks
b. autocomplete for food

 *
 * map.put("key", "value");
 * var val = map.get("key")
 * ……
 * js library style from Self-Executing Anonymous Function: Part 2 (Public & Private)
 * http://enterprisejquery.com/2010/10/how-good-c-habits-can-encourage-bad-javascript-habits-part-1/
 */

(function (fitCore, $, undefined) {

    // Private properties
    // var API_PATH = "http://api.onesportevent.com/DevApi";
    var API_PATH = "http://192.168.0.50/result/public";


    // For meal planner
    var START_MONDAY = -1;
    var week_map = new Map();

    // date locale array
    var day_arr = ["st", "nd", "rd", "th", "th", "th", "th", "th", "th", "th", "th", "th", "th", "th", "th", "th", "th", "th", "th", "th", "st", "nd", "rd", "th", "th", "th", "th", "th", "th", "th", "st"];
    var mon_arr = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
    var mon_short_arr = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];

    //var session_guid = '590EC926-0942-481E-B11D-3666A9FBF157';

    var currentLogId = 0;
    var currentdiarydate = 0;
    var currentdiary = null;
    var ui_offset_x = 0;
    var ui_offset_y = 0;

    var colorArr = ["#027dd3", "#5db75d", "#de524c", "#E46D0A", "#31849B", "#00B050", "#7030A0", "#00B0F0", "#FFC000", "#92D050"];
    var whenidArr = [1, 2, 3, 4, 5, 6, 7, 8, 9, 22, 23, 24, 25, 26, 27, 28, 29, 31, 32, 33, 34, 35, 36, 37, 38, 39, 40, 41, 42, 43, 44, 45, 46];

    // Food log
    var KCAL_REMAINING_DEFAULT = "";

    /* Global variables */
    var productName = '';
    var productId = 0;
    var kCal = 0;
    var jsonData = 0;
    var kCalsConsumedToday = 0;
    var fitTempRecordId = 0;
    var lastSearch = "";

    // Session specific data
    var email = "LoggedOut";
    var sessionGuid = "LoggedOut";


    /* Maintains all common data sets and general data cleaning functions */
    fitCore.Data = new function () {
        this.CurrentDiaryID = -1;
        this.CurrentDiaryDate = "";
        this.CurrentDiaryObject = null;
    };

    // End food log

    fitCore.CORE = {};

    fitCore.CORE.ConsoleLog = function (item) {
        if (true) {
            try {
                if (window.console) {
                    console.log(item);
                }
            } catch (e) { }
        }
    };
    //SaveLog?jsoncallback=jQuery18306181070262027476_1376215492433&jsonPersonalLog=%7B%22LogId%22%3A0%2C%22LogDate%22%3A%228-Aug-2013%22%2C%22PersonID%22%3A0%2C%22LogNotes%22%3A%22dsafdsfds%22%2C%22Weight%22%3Anull%2C%22RestingPulse%22%3Anull%2C%22Systolic%22%3Anull%2C%22Diastolic%22%3Anull%2C%22SizeArms%22%3Anull%2C%22SizeChest%22%3Anull%2C%22SizeWaist%22%3Anull%2C%22SizeThighs%22%3Anull%2C%22SizeHips%22%3Anull%2C%22HoursSleep%22%3Anull%2C%22BodyFatPercentage%22%3Anull%7D&email=dane%40eatfit.co.nz&SessionGuid=e7986c45-9d3f-4360-9515-9ebd6d4f3640&_=1376215499267
    //SaveLog?email=dane@eatfit.co.nz&sessionGuid=e7986c45-9d3f-4360-9515-9ebd6d4f3640&jsoncallback=jQuery183021878069517458543_1376215400965&LogId=0&LogDate=6-Aug-13&PersonID=0&LogNotes=111&Weight=&RestingPulse=&Systolic=&Diastolic=&SizeArms=&SizeChest=&SizeWaist=&SizeThighs=&SizeHips=&HoursSleep=&BodyFatPercentage=&_=1376215537349

    fitCore.CORE.AddLabel = function (labelArray, labelText, count) {

        for (var i = 0; i < count; i++) {
            labelArray.push(labelText);
        }
    };

    fitCore.CORE.NullIfBlank = function (value) {
        if (value == "")
            return null;
        else
            return value;
    };

    fitCore.CORE.formatMinsToHHMM = function (mins) {

        var hours = Math.floor(mins / 60),
            minutes = Math.floor(mins % 60);

        return hours + ':' + ((minutes < 10) ? '0' + minutes : minutes);
    };

    fitCore.CORE.formatDate = function (dt, format) {

        switch (format) {
            case 1:   //Date object
                return new Date(parseInt(dt));
            case 2:		//time stamp
                return parseInt(dt);
            case 3:		//format string e.g. 2012-1-31
                return new Date(parseInt(dt)).getFullYear() + "-" + (new Date(parseInt(dt)).getMonth() + 1) + "-" + new Date(parseInt(dt)).getDate();
            case 4:   //format string e.g. 31-Jan-12
                return new Date(parseInt(dt)).getDate() + "-" + mon_short_arr[new Date(parseInt(dt)).getMonth()] + "-" + (new Date(parseInt(dt)).getFullYear() + "").substring(2, 4);
            case 5:   //format string e.g. 31-Jan-12
                return dt.getDate() + "-" + mon_short_arr[dt.getMonth()] + "-" + dt.getFullYear() + "";
            default:  //default to timestamp
                return parseInt(dt);
        }
    };

    // Load session information, generally avaliable after document ready()
    function getSessionDetails() {

        // Session specific data
        email = $("#fit_email").val();
        sessionGuid = $("#fit_session").val();
    }

    fitCore.test = "aaa";

    fitCore.mealPlanner = {};

    //fitCore.foodLog = {};
    var foodLog = function () {
        // TODO: known issue, if you select a food, then change the date, you can not add

        /* Constants */
        var KCAL_REMAINING_DEFAULT = "";

        /* Global variables */
        var productName = '';
        var productId = 0;
        var kCal = 0;
        var jsonData = 0;
        var selectedFood = null;
        var kCalsConsumedToday = 0;
        var fitTempRecordId = 0;
        var lastSearch = "";

        // By default search the NZ db but not the USA db
        var searchNZ = true;
        var searchUSA = false;

        // MealPlanner is visible
        var isMealPlanner = false;

        var flSelectedWhenEatenId = -1;              // When saving a meal from a group of food

        function isNumber(n) {
            return !isNaN(parseFloat(n)) && isFinite(n);
        }

        function fmClearFoodGlobal() {
            productName = '';
            productId = 0;
            kCal = 0;
        }

        function fmHideAddAndErrorMessages() {
            $("#entry-message").hide();
            $(".just-added").hide();
        }

        function fmShowError(errorMessage) {

            try {
                $("#entry-message").html(errorMessage);
                $("#entry-message").show();
                $(".just-added").hide();
            }
            catch (err) {
                // just-added may not be available to hide yet
            }
        }

        function deleteFoodEntry(foodEatenId) {

            var url = API_PATH + "/Foods/DeleteFood?jsoncallback=?";
            var email = $("#fit_email").val();
            var sessionGuid = $("#fit_session").val();

            $.getJSON(url, { email: $("#fit_email").val(), SessionGuid: sessionGuid, foodEatenId: foodEatenId }, function (data) {

                if (data.MessageId != 0) {
                    alert(data.Message);
                }

            });
        }

        function fitSetInitialDailyCalorieGoal(adjustment) {
            KCAL_REMAINING_DEFAULT = $('#fit_caloriegoal').val();

            if (!KCAL_REMAINING_DEFAULT || KCAL_REMAINING_DEFAULT == null) {
                KCAL_REMAINING_DEFAULT = "";
            }

            // Goal not set yet
            if (KCAL_REMAINING_DEFAULT == "") {
                $('.calories-remaining .title span').html("<a href='/member/daily-calorie-calculator/'>Set Goal</a>");
            }
            else {
                // Sets initial daily "remaining calories" text on UI
                $('.calories-remaining .title span').text(KCAL_REMAINING_DEFAULT - adjustment);
            }
        }

        // Used from the "list of food" view when you have double-clicked a meal to examine it
        function AddNewFoodLogMeal(name, desc, whenid, ispublic) {

            fitCore.CORE.ConsoleLog('saving ' + name);

            var url = API_PATH + "/Foods/SaveMeal?jsoncallback=?";

            /* When Eaten Id's & descriptions - these can be hardcoded into the HTML has they won't change
            1  Breakfast                  	    2  Morning Snack                	    3  Lunch                        4  Afternoon Snack           	    5  Dinner                     	    6  Evening Snack                	    7  Midnight Snack                   	    8  After workout
            9  Before workout              	    22 1am                          	    23 2am                          24 3am                       	    25 4am                      	    26 5am
            27 6am                      	    28 7am                                  29 8am                  	    31 9am                      	    32 10am                     	    33 11am
            34 12am                     	    35 1pm                          	    36 2pm                  	    37 3pm                      	    38 4pm                      	    39 5pm
            40 6pm                      	    41 7pm                          	    42 8pm                  	    43 9pm                      	    44 10pm                     	    45 11pm
            46 12pm                             */

            var newMealObject = new Object();
            newMealObject.MealName = name; // Max 40 characters
            newMealObject.MealDescription = desc; // max 1800 characters
            newMealObject.WhenEatenId = whenid;      // the default time of day this meal might be eaten, see table of id's and descriptions
            newMealObject.IsPublic = ispublic;      // true if the user is willing to share this with others.

            // Create an array of food id's that are associated with this meal
            newMealObject.MealFoods = new Array();

            // food-eaten
            $("#log-" + flSelectedWhenEatenId + " .food-eaten").each(function (index) {

                var foodId = $(this).data('foodid');
                var servingSizeId = $(this).data('servingsizeid');
                var servings = $(this).data('servings');
                newMealObject.MealFoods.push(new NewMealFoodItem(foodId, servingSizeId, servings));

            });

            // Convert to json
            var json = $.toJSON(newMealObject);

            // Save on server
            $.ajax({
                url: url,
                type: 'GET',
                dataType: 'json',
                data: { jsonMeal: json, email: email, SessionGuid: sessionGuid },
                contentType: 'application/json; charset=utf-8',
                success: function (data) {
                    // get the result to check server updated OK
                    alert("Your new meal has been created successfully!");
                    //$("#fit-shadow").hide();
                    $("#fit-new-meal").dialog("close");

                    location.reload();
                }
            });
        }

        function AddNewMealFromCalendar(name, desc, whenid, ispublic, foodList) {
            var url = API_PATH + "/Foods/SaveMeal?jsoncallback=?";

            /* When Eaten Id's & descriptions - these can be hardcoded into the HTML has they won't change
            1  Breakfast                  	    2  Morning Snack                	    3  Lunch                        4  Afternoon Snack           	    5  Dinner                     	    6  Evening Snack                	    7  Midnight Snack                   	    8  After workout
            9  Before workout              	    22 1am                          	    23 2am                          24 3am                       	    25 4am                      	    26 5am
            27 6am                      	    28 7am                                  29 8am                  	    31 9am                      	    32 10am                     	    33 11am
            34 12am                     	    35 1pm                          	    36 2pm                  	    37 3pm                      	    38 4pm                      	    39 5pm
            40 6pm                      	    41 7pm                          	    42 8pm                  	    43 9pm                      	    44 10pm                     	    45 11pm
            46 12pm                             */

            var newMealObject = new Object();
            newMealObject.MealName = name; // Max 40 characters
            newMealObject.MealDescription = desc; // max 1800 characters
            newMealObject.WhenEatenId = whenid;      // the default time of day this meal might be eaten, see table of id's and descriptions
            newMealObject.IsPublic = ispublic;      // true if the user is willing to share this with others.

            // Create an array of food id's that are associated with this meal
            newMealObject.MealFoods = new Array();


            fitCore.CORE.ConsoleLog(foodList);

            for (var j = 0; j < foodList.size() ; j++) {
                var eatenItem = foodList.get(j);
                var fd = eatenItem.food;

                if (fd == null)
                    continue;


                var foodId = $(this).data('foodid');
                var servingSizeId = $(this).data('servingsizeid');
                var servings = $(this).data('servings');

                newMealObject.MealFoods.push(new NewMealFoodItem(fd.FoodId, eatenItem.servingSizeId, eatenItem.servingCount));
            }

            // Convert to json
            var json = $.toJSON(newMealObject);

            // Save on server
            $.ajax({
                url: url,
                type: 'GET',
                dataType: 'json',
                data: { jsonMeal: json, email: email, SessionGuid: sessionGuid },
                contentType: 'application/json; charset=utf-8',
                success: function (data) {
                    // get the result to check server updated OK
                    alert("New meal is added successfully!");
                    //$("#fit-shadow").hide();
                    $("#fit-new-meal").dialog("close");
                }
            });
        }

        // Create new food entry to attach to meal. 
        function NewMealFoodItem(foodId, servingSizeId, servings) {
            this.FoodId = foodId;
            this.ServingSizeId = servingSizeId;
            this.Servings = servings;
        }

        /**
        * FoodLog:  Initializes UI elements
        */
        this.start = function () {

            /**
            * Initializes UI elements.
            * This function runs just after jQuery is initialized.
            */
            getSessionDetails();

            // See if mealplanner mode - does calednar exist
            isMealPlanner = $("#fit-calendar").length == 1;

            // Reset and display the daily calorie goal
            fitSetInitialDailyCalorieGoal(0);

            /* Initializes food search functionality and the food search result box */
            fmSetupFoodSearch();

            // Load food for given day
            getFoodEatenForDay(new Date());

            // Enable tooltips if library available
            if ($.fn.tooltip != undefined) {

                // Tooltips
                $("#food-log").tooltip({
                    tooltipClass: 'fit-ui',

                    // Attempted wrapping based scoping; close but issues..
                    //              open: function (event, ui) {
                    //                    $(ui.tooltip[0]).wrap("<div class='fit-ui'/>");
                    //},

                    position: {
                        my: "center bottom-15",
                        at: "center top",
                        using: function (position, feedback) {
                            $(this).css(position);
                            $("<div><h1>thing</h1>").addClass("arrow bottom").addClass(feedback.vertical).addClass(feedback.horizontal).appendTo(this);
                        }
                    }
                });
            }
            else {
                fitCore.CORE.ConsoleLog('Tooltip library not available.');
            }

            if (isMealPlanner == true) {

                $("#serving").combobox();

                //$("#combobox").combobox();
                $("#when-serving-cal").combobox();
                $("#when-serving").combobox(
                /*{
                    selected: function (event, ui) {
    
                        // Store selected item
                        selectedEvent = ui.item.text;
                        selectedEventId = ui.item.value;
    
                        // If one of the quick select items is choosen, highlight appropriate icon
                        $("#evType img").removeClass('selected');
    
                        // Find the quickSelect image associated with this activity
                        var quickSelect = $("#evType img[data-activityid='" + selectedEventId + "']");
    
                        // If located, select it, otherwise select the generic 'other' image
                        if (quickSelect.length > 0) {
                            quickSelect.addClass('selected');
                            $("#event-preview #event-type").attr('src', quickSelect.attr('src'));
                        } else {
                            var otherImage = $("#evType img[data-activityid='0']");
                            otherImage.addClass('selected');
                            $("#event-preview #event-type").attr('src', otherImage.attr('src'));
                        }
    
                        // Determine which extra panels to display based on event type, e.g. walking/running/cycling etc
                        evSetEventPanels(selectedEventId);
    
                        // Update this as the currently selected activity
                        //var newEventId = $(this).data('activityid');
                        //var newEvent = $(this).data('activity');
                    }
                }*/);
            }

            // close icon: removing the tab on click
            $("#fit-popup-mealinfo").on("click", '#fit-saveas-meal', function () {

                // Store selected meal
                flSelectedWhenEatenId = $(this).data('wheneatenid');

                // Default when the meal was eaten in the dropdown
                $("#fit-mealwhenid option[value='" + flSelectedWhenEatenId + "']").attr("selected", "selected");

                // Initialise special html editor
                if (fitCore.mealPlanner.isMealEditorInitialised == false) {
                    fitCore.mealPlanner.initialiseMealEditor();
                }

                $('#fit-new-meal').dialog({
                    dialogClass: 'fit-dialogtitle',
                    resizable: false,
                    width: 'auto',
                    modal: true,
                    create: function (event, ui) {
                        $('.ui-dialog').wrap('<div class="fit-ui" />');
                    },
                    open: function (event, ui) {
                        $('.ui-widget-overlay').wrap('<div class="fit-ui" />');

                        // Don't show nutrition fields as this is a calculated entry
                        $(".fit-new-meal-right").hide();
                    },
                    close: function (event, ui) {
                        $(".fit-ui").filter(function () {
                            if ($(this).text() == "") {
                                return true;
                            }
                            return false;
                        }).remove();
                    }
                }).dialog('open');
                return false;
            });

            // Bind enter to add record function
            $(".food-form").on("keydown", ".ui-combobox-input, #how-much", function (e) {

                if (e.keyCode == 13) {
                    return addFoodItem();
                }
            });

            // Ignore entr keypress on this field
            $(".food-form").on("keydown", "#product-name", function (e) {

                if (e.keyCode == 13) {
                    $("#food-log").tooltip("open");
                    return false;
                }
            });

            /*       $(".ui-combobox-input").keydown(function (e) {
            });*/

            // New meal save button from log
            $("#fit-new-meal #btn-cancel").live("click", function () {
                $("#fit-new-meal").dialog("close");
            });

            $("#fit-new-meal #btn-save").live("click", function () {

                //                .fit-new-meal-right
                var name = $("#fit-new-meal #fit-mealname").val();
                var desc = $("#fit-new-meal #fit-mealdesc").val();
                var whenid = $("#fit-new-meal #fit-mealwhenid").val();
                var ispublic = $("#fit-new-meal #fit-mealpublic").is(":checked");

                var totalFat = parseFloat($("#fit-new-meal #fit-newmeal-totalfat").val());     // Total fats
                var satFat = parseFloat($("#fit-new-meal #fit-newmeal-fasat").val());          // Saturated fats

                var totalCarbs = parseFloat($("#fit-new-meal #fit-newmeal-carb").val());        // Total carbs
                var sugarCarbs = parseFloat($("#fit-new-meal #fit-newmeal-sugars").val());      // Sugars

                // Validate the fields
                if (name.length == 0) {
                    alert("Meal name could not be blank!");
                    return;
                }

                if (name.length > 80) {
                    alert("Meal name could not exceed 80 characters!");
                    return;
                }

                if (desc.length == 0) {
                    alert("Meal description could not be blank!");
                    return;
                }

                if (desc.length > 4000) {
                    alert("Meal description could not exceed 4000 characters!");
                    return;
                }

                //$("#fit-popup-window").hide();
                //$("#fit-popup-window .fit-ctnt").html("");
                //$("#fit-shadow").show();

                if ($(".fit-new-meal-right").is(":visible") == true) {

                    // If both fats supplied, then sat fat should be a portion of total fat
                    if (!isNaN(totalFat) && !isNaN(satFat)) {

                        if (satFat > totalFat) {
                            alert("Please enter saturated fats as a portion of total fats -- saturated fats should be less than " + totalFat + " grams");
                            return;
                        }

                    }

                    if (!isNaN(satFat) && isNaN(totalFat)) {
                        alert("If you are unsure how much total fat is in this meal please use the saturated fat figure for total fats.");
                        return;
                    }

                    // If sugars and total carbs fats supplied, then sugars should be a portion of total carbs
                    if (!isNaN(totalCarbs) && !isNaN(sugarCarbs)) {

                        if (sugarCarbs > totalCarbs) {
                            alert("Please enter sugars as a portion of total carbs -- sugars should be less than " + totalCarbs + " grams");
                            return;
                        }

                    }

                    if (!isNaN(sugarCarbs) && isNaN(totalCarbs)) {
                        alert("If you are unsure how many total carbs is in this meal please use the sugars figure for total carbs.");
                        return;
                    }
                    
                    fitCore.CORE.ConsoleLog('Add new meal with manual nutrients');
                    fitCore.mealPlanner.AddNewBasicMeal(name, desc, whenid, ispublic);

                }
                else {
                    fitCore.CORE.ConsoleLog('Add new meal from foodlist ');

                    // Get the list of food
                    var popupObject = $("#fit-popup-mealinfo");
                    var foodList = $.data(document.body, 'foodlist');

                    fitCore.CORE.ConsoleLog(foodList);

                    AddNewMealFromCalendar(name, desc, whenid, ispublic, foodList);
                }

            });

            // close icon: removing the tab on click
            $("#food-log").on("click", '.fit-save-meal', function () {

                // Store selected meal
                flSelectedWhenEatenId = $(this).data('wheneatenid');

                // Default when the meal was eaten in the dropdown
                $("#fit-mealwhenid option[value='" + flSelectedWhenEatenId + "']").attr("selected", "selected");

                $('#fit-new-meal').dialog({
                    dialogClass: 'fit-dialogtitle',
                    resizable: false,
                    width: 'auto',
                    modal: true,
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
                    }
                }).dialog('open');
                return false;
            });


            // Save current group of food as a meal
            /*$(".fit-save-meal-btn").click(function () {
                
                fitCore.mealPlanner.popup_window(500, $("#fit-new-meal").html());
            });*/



            $("#fit_datepicker")
                   .datepicker({
                       beforeShow: function (textbox, instance) {

                           fitCore.CORE.ConsoleLog('dp1');

                           $(this).datepicker("widget").addClass("fit-ui");

                           //// Get button element
                           //var sd = document.getElementById("fit_selecteddate");

                           //// Adjust popup location for datepicker
                           //instance.dpDiv.css({
                           //    marginTop: sd.offsetTop + 'px',
                           //    marginLeft: sd.offsetLeft + 'px'
                           //});
                       },
                       onClose: function () {
                           $(this).datepicker("widget").removeClass("fit-ui");
                       },
                       defaultDate: new Date(),
                       onSelect: function (dateText, inst) {
                           fitCore.CORE.ConsoleLog('dp2');

                           // Hide picker
                           $("#fit_datepicker").datepicker("hide");

                           // Display new selected date
                           var currentDate = $('#fit_datepicker').datepicker("getDate");
                           // $("#fit_selecteddate").button("option", "label", $.datepicker.formatDate("dd-M-y", currentDate));

                           $("#fit_selecteddate").button("option", "label", $.datepicker.formatDate("D", currentDate) + ' ' + currentDate.getDate() + day_arr[currentDate.getDate() - 1]);

                       }
                   }).wrap("<div class='test'/>");


            // Setup split button to select current date
            $("#fit_prevdate")
			.button({
			    text: false, icons: {
			        primary: "ui-icon-triangle-1-w"
			    }
			})
			.click(function () {

			    // Get current date..
			    var currentDate = $('#fit_datepicker').datepicker("getDate");


			    // If moving to the end of a previous week, scroll to previous week if meal planner open
			    if (isMealPlanner == true && currentDate.getDay() == 1) {
			        fitCore.mealPlanner.scrollToPreviousWeek(currentDate);
			    }

			    // ..and subtract one day
			    currentDate.setDate(currentDate.getDate() - 1);

			    // Update datepicker and button display
			    $('#fit_datepicker').datepicker("setDate", currentDate);
			    //$("#fit_selecteddate").button("option", "label", $.datepicker.formatDate("dd-M-y", currentDate));
			    $("#fit_selecteddate").button("option", "label", $.datepicker.formatDate("D", currentDate) + ' ' + currentDate.getDate() + day_arr[currentDate.getDate() - 1]);

			    // Load food for given day
			    if (isMealPlanner == false) {
			        getFoodEatenForDay(currentDate);
			    }
			    return false;

			})
			.next()
				.button({
				    icons: {
				        primary: "ui-icon-calendar"				// Standard jqueryUI left arrow
				    }
				})
				.click(function () {
				    $("#fit_datepicker").datepicker("show");	// Show date picker on click
				    return false;
				})
				.next()
				.button({
				    text: false,
				    icons: {
				        primary: "ui-icon-triangle-1-e"			// Standard jqueryUI right arrow
				    }
				})
				.click(function () {

				    // Get current date and add one day
				    var currentDate = $('#fit_datepicker').datepicker("getDate");

				    // If moving to the start of the next week, scroll to next week if meal planner open
				    if (isMealPlanner == true && currentDate.getDay() == 0) {
				        fitCore.mealPlanner.scrollToNextWeek();
				    }

				    currentDate.setDate(currentDate.getDate() + 1);

				    // Update datepicker and button display
				    $('#fit_datepicker').datepicker("setDate", currentDate);
				    //$("#fit_selecteddate").button("option", "label", $.datepicker.formatDate("dd-M-y", currentDate));
				    $("#fit_selecteddate").button("option", "label", $.datepicker.formatDate("D", currentDate) + ' ' + currentDate.getDate() + day_arr[currentDate.getDate() - 1]);

				    // Load food for given day
				    if (isMealPlanner == false) {
				        getFoodEatenForDay(currentDate);
				    }

				    return false;
				})
				.parent()
				.buttonset();

            // Set default datepicker date to today, and display date label in button
            $('#fit_datepicker').datepicker("setDate", new Date());
            //$("#fit_selecteddate").button("option", "label", $.datepicker.formatDate("dd-M-y", new Date()));
            $("#fit_selecteddate").button("option", "label", $.datepicker.formatDate("D", new Date()) + ' ' + new Date().getDate() + day_arr[new Date().getDate() - 1]);

            // Style add button
            $("#fit_addnutrition").button();

            /* Sets up calorie input methods (free text dish search and manual calorie entry) */
            //$(".food-form").submit(function () {
            $("#fit_addnutrition").click(function () {
                return addFoodItem();
            });

            /* Sets up the input mode switcher UI */
            $(".enter-manually").click(function () {
                enterManually();
                return false;
            });

            $(".search-food").click(function () {
                searchFood();
                return false;
            });

            /* Makes the tables sortable 
            $("li.droptrue > ul").sortable({
                placeholder: "ui-state-highlight",
                connectWith: "li.tbody > ul"
            });*/

            //$( "li.droptrue > ul" ).disableSelection();

        }

        function addFoodItem() {
            // Export to global scope so it can be called from outside jQuery
            //window.fit_setupnutrition = fit_setupnutrition;

            // Allow searching again (even for same search term)
            lastSearch = "";

            if ($("#enter-calories").is(":visible")) {

                var calQuantity = parseFloat($("#calories-quantity").val());

                if (isNumber(calQuantity) && (calQuantity <= 0 || calQuantity > 5000)) {
                    fmShowError("Calories are optional and if specified can be up to 5000");
                    return false;
                }

                //fmDebug("calorie mode");

                if ($('#what-eaten').val().length > 0) {
                    addCaloriesM();
                }
                return false;
            }
            else {

                var howMuch = parseFloat($("#how-much").val());

                if ($("#search-food").is(":visible")) {

                    // Food from database not found/selected
                    if (productName.length <= 0) {

                        if ($("#product-name").val().length > 0) {
                            fmShowError("Click on a food from the database to select it, or if you can't find the food you want click 'enter new food' or let us know and we'll add it.");
                        }
                        else {
                            // they entered something but food not found or selected
                            fmShowError("Enter what food you ate"); 	// Nothing in text field or selected
                        }

                        return false;
                    }

                    // Quantity not provided
                    if (!isNumber($("#how-much").val()) || howMuch <= 0) {
                        fmShowError("So how much did you eat?");
                        return false;
                    }

                    if (!$("#serving").val()) {
                        fmShowError("Please choose a serving option once downloaded.");
                        return false;
                    }

                    if ((productName.length > 0) & (kCal > 0)) { $("#entry-message").hide(); addFood(); }
                }

            }
            return false;
        }

        /**
        * Switches input mode to manual calorie entry
        */
        function enterManually() {
            $('#search-food').hide();
            $('#enter-calories').show();
        }

        /**
        * Switches input mode to free text search
        */
        function searchFood() {
            $('#search-food').show();
            $('#enter-calories').hide();
        }

        /**
        * Initializes the search results box and its related events.
        * TODO: The function has a bad name. In fact, what it does is to initialize the search results box and its related events.
        */
        function fmSetupFoodSearch() {
            var field = $("#product-name");
            field.val('');
            $("#how-much").val('');

            /* Creates search results box if it doesn't exist yet */
            var schResults = $("#sch-results");
            if (field.length > 0 & schResults.length == 0) {
                $("<div id='sch-results'>\n<div id='sch-header'>\n" +
                    "<div class='food-name'>Food</div>\n" +
                    "<div class='vitaminc col'>Energy (kcal)</div>\n" +
                    "<div class='calcium col'>Total fat</div>\n" +
                    "<div class='carbohydrate col'>Saturated fat</div>\n" +
                    "<div class='energkcal col'>Fibre</div>\n" +
                    "<div class='folicacid col'>Carbohydrate</div>\n" +
                    "<div class='magnesium col'>Protein</div>\n" +
                    "<div class='protein col'>Salt</div>\n" +
                    "<div class='sugartot col'>Water</div>\n" +

                /*"<div class='vitaminc col'>VitaminC</div>\n" +
                "<div class='calcium col'>Calcium</div>\n" +
                "<div class='carbohydrate col'>Carbohydrate</div>\n" +
                "<div class='energkcal col'>Calories</div>\n" +
                "<div class='folicacid col'>FolicAcid</div>\n" +
                "<div class='magnesium col'>Magnesium</div>\n" +
                "<div class='protein col'>Protein</div>\n" +
                "<div class='sugartot col'>SugarTot</div>\n" +
                */
                    "</div>\n<div id='sch-content'></div>\n</div>"
                ).appendTo(document.body);
            }

            /* Sets up food name input field to start a search for foods on keypress/focus if there are more than 2 characters in it, or hide it otherwise. */
            field.bind('keyup focusin', function () {
                var text = $.trim(field.val());

                if (text.length > 2) {

                    // Only search if the text is diffent to the last search
                    if (text != lastSearch) {

                        if ($("#sch-results").is(":visible")) {
                            $('#sch-content').text('');
                        }

                        $('#product-name').addClass('ui-autocomplete-loading');
                        searcher(text);
                    }

                } else {
                    searcherHider();
                }
            });

            /* Sets up the interface so that it hides the search results if the user clicks anywhere outside the search result box */
            field.blur(function () {
                $("body").click(function (event) {
                    var target = $(event.target);
                    var targetParent = target.parents('#sch-results');
                    if (targetParent.length == 0) {
                        searcherHider();
                    }
                });
            });

            /* Sets up the UI to re-position the searcher when the window is resized */
            $(window).resize(function () { searcherPosition(); });
        }

        /**
        * Sends a GetFoods API call which calls searcherCallback when finished
        */
        function searcher(text) {
            lastSearch = text;
            var url = API_PATH + "/Foods/GetFoods?jsoncallback=?";

            $.getJSON(url, { 'foodName': text, 'searchNZ': searchNZ, 'searchUSA': searchUSA }, searcherCallback);
        }

        /**
        * Shows free text food search results in the "search results box". Sets up the event handlers for the food list elements in the "search result box".
        * 
        * @global array jsonData Saves the list of food search results in a global variable.
        */
        function searcherCallback(data) {
            /* Saves result to global variable */
            jsonData = data;

            /* Finds search result box HTML elements */
            var resultNode = $('#sch-content');
            var resultNodeParent = $(resultNode).parent();

            // TODO: if out of order could pass incrementing number and only load if latest call results
            // http: //stackoverflow.com/questions/6129145/pass-extra-parameter-to-jquery-getjson-success-callback-function
            // could also use .ajaxStop() to only load on last completed ajax request

            // Clear any existing content
            $('#sch-content').html("");

            /* Shows and positions result box */
            if ((resultNodeParent.is(":hidden")) & (data.length > 0)) {
                resultNodeParent.show();
                searcherPosition();
            }

            /* Fills up result box with data */
            $(data).each(function (x) {

                var foodRow =
                $("<div class='row'>\n" +
                    "<div class='food-name'>" + data[x].LongDesc + "</div>\n" +

                    "<div class='kcal col'>" + data[x].EnergKcal + "</div>\n" +
                    "<div class='col'>" + spaceIfNull(data[x].LipidTotal) + "</div>\n" +
                    "<div class='col'>" + spaceIfNull(data[x].FASat) + "</div>\n" +
                    "<div class='col'>" + spaceIfNull(data[x].Fiber) + "</div>\n" +
                    "<div class='col'>" + spaceIfNull(data[x].Carbohydrate) + "</div>\n" +
                    "<div class='col'>" + spaceIfNull(data[x].Protein) + "</div>\n" +
                    "<div class='col'>" + spaceIfNull(data[x].Sodium) + "</div>\n" +
                    "<div class='col'>" + spaceIfNull(data[x].Water) + "</div>\n" +

                /*"<div class='col'>" + data[x].VitC + "</div>\n" +
                "<div class='col'>" + data[x].Calcium + "</div>\n" +
                "<div class='carbohydrate col'>" + data[x].Carbohydrate + "</div>\n" +
                "<div class='kcal col'>" + data[x].EnergKcal + "</div>\n" +
                "<div class='col'>" + data[x].FolicAcid + "</div>\n" +
                "<div class='magnesium col'>" + data[x].Magnesium + "</div>\n" +
                "<div class='col'>" + data[x].Protein + "</div>\n" +
                "<div class='col'>" + data[x].SugarTot + "</div>\n" +*/
                    "<div class='id col'>" + data[x].FoodId + "</div>\n</div>"
                );

                // Store selected food item
                $(foodRow).data('foodItem', data[x]);

                //foodRow.foodItem = data[x];

                foodRow.appendTo(resultNode);
            });

            $('#product-name').removeClass('ui-autocomplete-loading');

            // TODO: 
            // BUG: ? check doesn't have multiple click handlers added

            /* Sets up the event handler for the search results box rows */
            $("#sch-content .row").click(schContentRowClickHandler);
        }

        /**
        * Event handler for the food list elements in the "search result box".
        * 
        * @global string productName Puts the name of the selected food in this variable.
        * @global int kCal Puts the name of the food's calorie value in this variable.
        */
        function schContentRowClickHandler() {
            //alert('handler');
            productName = $(this).children('.food-name').text();
            productId = $(this).children('.id').text();
            kCal = parseInt($(this).children('.kcal').text());    // standard per 100g
            $("#product-name").val(productName);
            $("#how-much").val('1');

            // Store selected food item
            selectedFood = $(this).data('foodItem');

            /* Hides search result box */
            searcherHider();

            // Put servings options into combo
            getFoodServings(productId);

            // Set cursor focus
            $("#how-much").focus();
        }

        /**
        * Hides and clears search result box
        */
        function searcherHider() {
            $('#sch-results').hide();
            $('#sch-content').text('');
        }

        /**
        * (Re)positions search results box if it's visible
        */
        function searcherPosition() {
            if ($("#sch-results").is(":visible")) {
                var field = $("#product-name");

                /*alert(field.offset().left);*/
                /*'left': offset.left,*/
                var offset = field.offset();
                $('#sch-results').css({

                    'top': offset.top + field.outerHeight()
                });
            }
        }


        /**
        * Adds consumed calories from the free text food search interface and gives a visual feedback about this.
        * 
        * @global int kCal Read only.
        */
        function addFood() {

            // Calculate multipler (all standard fields contain the nutrient factors per 100 grams, so the multipler is the relative porition size eaten)
            var multiplier = getMultipler();

            /* Gives visual feedback about the addition of consumed calories */
            //if ($(".just-added").is(":visible")) {
            $(".just-added").remove();
            //	}

            // Show whats just added
            showJustEaten(productName, (kCal * multiplier).toFixed(0));

            // Count number of calories consumed today
            kCalsConsumedToday += (kCal * multiplier);

            /* Calculates consumed calories */
            updateCaloriesGraph();

            // Create temporary recordId until this is allocated into database
            fitTempRecordId += 1;

            /* Adds food to food log on screen */
            if (isMealPlanner == false) {
                fillFoodLog($('#when-serving').val(), multiplier, fitTempRecordId);
            }

            // Get current date
            var currentDateFormatted = $.datepicker.formatDate("dd-M-y", $('#fit_datepicker').datepicker("getDate"));
            var currentDate = $('#fit_datepicker').datepicker("getDate");

            // Determine column (monday 0 -> sunday 6)
            var dateColumn = currentDate.getDay() == 0 ? 6 : (currentDate.getDay() - 1);

            // Add the food to the meal map and to the planner UI
            if (isMealPlanner == true) {
                fitCore.mealPlanner.addFoodToPlanner(currentDateFormatted, $('#when-serving').val(), selectedFood, fitTempRecordId, dateColumn, $("#how-much").val(), $("#serving option:selected").text(), getServingWeight(), $("#serving").val());
            }

            // Add food to database
            eatFood(productId, $("#serving").val(), $('#when-serving').val(), currentDateFormatted, $("#how-much").val(), fitTempRecordId);

            /* Clears input fields */
            $("#product-name, #how-much").val('');
            $("#serving").html('');
            $("#serving").val('');

            // Clear globals of currentlly selected food
            fmClearFoodGlobal();

            // Return focus to entry field
            $("#product-name").focus();
        }

        /**
        * Adds consumed calories from the "manual calorie entry" interface.
        */
        function addCaloriesM() {

            kCal = parseInt($("#calories-quantity").val());
            var whatEaten = $("#what-eaten").val();

            $(".just-added").remove();

            // Show whats just added - if item has unknown calories no need to update graph
            if (isNumber(kCal)) {
                //fmDebug("calorie - " + kCal);

                showJustEaten(whatEaten, kCal.toFixed(0));

                // Count number of calories consumed today
                kCalsConsumedToday += kCal;
                updateCaloriesGraph();
            }
            else {
                showJustEaten(whatEaten, "unknown");
            }

            var dataServingId = $('#when-serving-cal').val();
            //var dataServingCaption = $("#when-serving-cal").is("selected").text();
            var dataServingCaption = $("#when-serving-cal option:selected").text();
            //$(this).find('option:selected').text();

            // Create temporary recordId until this is allocated into database
            fitTempRecordId += 1;

            // Add to UI
            addFoodToUILog(fitTempRecordId, dataServingId, dataServingCaption, 1, whatEaten, 1, kCal, '&nbsp;', '&nbsp;', '&nbsp;', '&nbsp;', '&nbsp;', '&nbsp;', '&nbsp;', '&nbsp;', null, null);

            // Get current date
            var currentDate = $.datepicker.formatDate("dd-M-y", $('#fit_datepicker').datepicker("getDate"));

            // Add food to database
            eatCalories(dataServingId, currentDate, kCal, $("#what-eaten").val(), fitTempRecordId);

            $("#calories-quantity").val('');

            // Return focus to entry field
            $("#what-eaten").focus();
        }

        function showJustEaten(whatEaten, caloriesEaten) {
            $("<dl class='just-added'>\n" +
                 "<dt>You have just added:</dt>\n" +
                 "<dd>" + whatEaten + " (" + caloriesEaten + " calories)</dd>\n</dl>"
               ).appendTo(".food-form");

            $(".just-added").show();
        }


        /**
        * Refreshes the "Consumed calories" and "Calories remaining" parts of the UI.
        * The consumed calories are calculated as "kCal * kCalQuantity", so the individual parameter values don't matter, just their product.
        * TODO: kCal should be a parameter here.
        *
        * @param int kCalQuantity The number of calories eaten OR the number of portions of dish eaten.
        * @global int kCal The number "1" OR the calories in the dish eaten.
    
        * multipler - all standard fields contain the nutrient factors per 100 grams, so the multipler is the relative porition size eaten
        */
        function updateCaloriesGraph() {

            /* Updates "consumed calories" text on UI */
            $('.box-consumed span').text(Math.round(kCalsConsumedToday).toFixed(0));

            // If goal not set yet can't do graph
            if (KCAL_REMAINING_DEFAULT == "") {
                return;
            }

            // Update "remaining calories" text on UI
            var kCalRemaining = KCAL_REMAINING_DEFAULT - kCalsConsumedToday;
            $('.calories-remaining .title span').text(kCalRemaining.toFixed(0));

            /* Updates "remaining calories" progress bar on UI */
            var progressBarW = $('#progress-bar').width();
            if (progressBarW > 0) {
                progressBarW = (KCAL_REMAINING_DEFAULT - (kCalRemaining)) / (KCAL_REMAINING_DEFAULT / 100) + '%';
            } else {
                //progressBarW = (kCal * multipler) / (KCAL_REMAINING_DEFAULT / 100) + '%';			// Less than 0% ! (e.g. more calories than the goal so we are in negative)
                progressBarW = "100%";
            }

            $('#progress-bar').width(progressBarW).css('visibility', 'visible');
            $('#progress-bar strong').remove();
            if ($('#progress-bar').width() > $('#progress-bar').parent().width()) {
                $('#progress-bar').css('background', 'red');
                $("<strong>" + (kCalRemaining.toFixed(0)) * -1 + " Over Goal</strong>").appendTo("#progress-bar");
            }
        }

        function spaceIfNull(value) {
            return value == null ? '&nbsp;' : value;
        }

        function addFoodToUILog(fitRecordId, dataServingId, dataServingCaption, multiplier, desc, servings, energKcal, lipidTotal, FASat, fiber, carbohydrate, protein, sodium, water, sugars, servingSizeId, foodId) {

            /* Adds a new element to the list if it exists. Otherwise it creates a new list and adds the first item to it. */
            if (!$('#log-' + dataServingId).is(":visible")) {

                /*"<li class='servings'>Servings</li>\n" +
                            "<li class='servings'>&nbsp;</li>\n" +
                */
                // "<h3>" + dataServingCaption + "</h3>" +

                $("<div id='log-" + dataServingId + "'>" +

                    "<ul class='table'>\n<li class='thead'>\n<ul>" +
                    "<li class='foodn'><span data-wheneatenid=" + dataServingId + " class='fit-save-meal' title='Click to save this as a meal for easy entry!'><h3 class='fit-mealtime'>" + dataServingCaption + "</h3><span class='ui-icon ui-icon-disk'></span></span></li>\n" +
                    "<li class='cals'>Cals</li>\n" +
                    "<li class='fat'>Fat</li>\n" +
                    "<li class='satfa'>Sat fat</li>\n" +
                    "<li class='fiber'>Fibre</li>\n" +
                    "<li class='carbs'>Carbs</li>\n" +
                    "<li class='protein'>Protein</li>\n" +
                    "<li class='salt'>Salt</li>\n" +
                    "<li class='sugars'>Sugars</li>\n" +
                    "</ul></li>" +
                    "<li class='tbody droptrue'><ul></ul>\n</li>\n" +
                    "<li class='tfoot'><ul>\n" +
                    "<li class='foodn'>" + dataServingCaption + " Totals</li>\n" +
                    "<li class='cals'>0</li>\n" +
                    "<li class='fat'>0</li>\n" +
                    "<li class='satfa'>0</li>\n" +
                    "<li class='fiber'>0</li>\n" +
                    "<li class='carbs'>0</li>\n" +
                    "<li class='protein'>0</li>\n" +
                    "<li class='salt'>0</li>\n" +
                    "<li class='sugars'>0</li>\n" +
                    "</ul></li>" +
                    "</ul></li></div>"
                ).appendTo('#food-log');
            }

            var holder = $('#log-' + dataServingId).find('li.tbody').children('ul');
            var deleteButon = "<ul class='buttons'><li class='delete ui-state-default ui-corner-all' ><span class='ui-icon ui-icon-close'></span></li></ul>";
            var addedElement = null;

            // Calorie entry, don't know values
            if (lipidTotal == '&nbsp;') {

                // Use calories if supplied, otherise blank column
                var energyConsumed = isNumber(energKcal) ? energKcal.toFixed(2).replace(/[.,]00$/, '') : "&nbsp;";

                addedElement = $("<li class='food-eaten' data-foodid=null data-kcals=0 data-recordid=" + fitRecordId + "><ul>\n" +
                    "<li class='foodn'>" + deleteButon + desc + "</li>\n" +
                    "<li class='manual servings cals'>" + energyConsumed + "</li>\n" +
                    "<li class='manual servings fat'>" + lipidTotal + "</li>\n" +
                    "<li class='manual servings satfa'>" + FASat + "</li>\n" +
                    "<li class='manual servings fiber'>" + fiber + "</li>\n" +
                    "<li class='manual servings carbs'>" + carbohydrate + "</li>\n" +
                    "<li class='manual servings protein'>" + protein + "</li>\n" +
                    "<li class='manual servings salt'>" + sodium + "</li>\n" +
                    "<li class='manual servings sugars'>" + sugars + "</li>\n" +
                    "</ul></li>"
                    ).appendTo(holder);
            }
            else {

                // TODO: if food already eaten, just increase servings? - may have to convert to grams?
                // TODO: if duplicate meal, don't save, give name

                addedElement = $("<li class='food-eaten' data-foodid=" + foodId + " data-servingsizeid=" + servingSizeId + " data-servings=" + servings + " data-kcals=" + (energKcal * multiplier).toFixed(2).replace(/[.,]00$/, '') + " data-recordid=" + fitRecordId + "><ul>\n" +
                    "<li class='foodn'>" + deleteButon + servings + " x " + desc + "</li>\n" +
                    "<li class='servings cals'>" + (energKcal * multiplier).toFixed(2).replace(/[.,]00$/, '') + "</li>\n" +
                    "<li class='servings fat'>" + (lipidTotal * multiplier).toFixed(2).replace(/[.,]00$/, '') + "</li>\n" +
                    "<li class='servings satfa'>" + (FASat * multiplier).toFixed(2).replace(/[.,]00$/, '') + "</li>\n" +
                    "<li class='servings fiber'>" + (fiber * multiplier).toFixed(2).replace(/[.,]00$/, '') + "</li>\n" +
                    "<li class='servings carbs'>" + (carbohydrate * multiplier).toFixed(2).replace(/[.,]00$/, '') + "</li>\n" +
                    "<li class='servings protein'>" + (protein * multiplier).toFixed(2).replace(/[.,]00$/, '') + "</li>\n" +
                    "<li class='servings salt'>" + (sodium * multiplier).toFixed(2).replace(/[.,]00$/, '') + "</li>\n" +
                    "<li class='servings sugars'>" + (sugars * multiplier).toFixed(2).replace(/[.,]00$/, '') + "</li>\n" +
                    "</ul></li>"
                    ).appendTo(holder);
            }

            // Find actual delete button for this record
            var deleteButton = $(addedElement).find('ul.buttons li');

            // Bind the hover to change style when mouse enters
            $(deleteButton).bind({
                mouseenter: function (e) {
                    // Hover event handler
                    $(this).addClass('ui-state-hover');

                },
                mouseleave: function (e) {
                    // Hover event handler
                    $(this).removeClass('ui-state-hover');

                },
                click: function (e) {
                    // Click event handler

                    // Get the weight in grams for the serving choosen
                    //var search = $(this).parent().parent().parent().parent().parent();
                    var search = $(this).parents("li.food-eaten");
                    var recordid = $(search).attr("data-recordid");
                    var kcals = $(search).attr("data-kcals");

                    // Delete from the database
                    deleteFoodEntry(recordid);

                    // Calories are floating point numbers and it's possible that when the last item is removed it doesn't quite go to zero.  Force to zero as a work-around
                    var recordsLeft = $(".food-eaten").length;

                    // Remove from UI
                    $(this).parent().parent().parent().parent().remove();

                    // TODO: consider removing whole panel in each group

                    // just removed last one.
                    if (recordsLeft == 1) {
                        kCalsConsumedToday = 0;	// No more entries in todays diary
                    }
                    else {
                        // Lower calories
                        kCalsConsumedToday -= kcals;
                    }
                    updateCaloriesGraph();
                    fmHideAddAndErrorMessages();

                    return false;
                },
                blur: function (e) {
                    // Blur event handler
                }
            });

            // Show the commands when mouse over record
            $(addedElement).bind({
                mouseenter: function (e) {
                    // Hover event handler
                    $(this).find('.buttons').show();

                },
                mouseleave: function (e) {
                    // Hover event handler
                    $(this).find('.buttons').hide();

                },
                click: function (e) {
                    // Click event handler
                    //alert("click");
                },
                blur: function (e) {
                    // Blur event handler
                }
            });
            /*
            
                                                              $(this).addClass('hover');
                                                              $(this).find('.buttons').show();
                                                      
                                                      
                                                              $(this).removeClass('hover');
                                                              $(this).find('.buttons').hide();
                                              
                                              $('ul li.foodn .buttons li').hover(
                                                      function() {
                                                              $(this).addClass('ui-state-hover');
                                                      },
                                                      function() {
                                                              $(this).removeClass('ui-state-hover');
                                                      }
                                              );*/


            /*
            "<li class='servings'>" + servings + "</li>\n" +
            "<li class='servings'>" + water + "</li>\n" +
            "<li class='vitaminc'>" + vitC + "</li>\n" +
            "<li class='calcium'>" + calcium + "</li>\n" +
            "<li class='carbohydrate'>" + carbohydrate + "</li>\n" +
            "<li class='calories'>" + energKcal + "</li>\n" +
            "<li class='folicacid'>" + folicAcid + "</li>\n" +
            "<li class='magnesium'>" + magnesium + "</li>\n" +
            "<li class='protein'>" + protein + "</li>\n" +
            "<li class='sugartot'>" + sugarTot + "</li>\n" +*/

        }

        function getServingWeight() {
            // Get the weight in grams for the serving choosen
            var selected = $("#serving").find('option:selected');
            return servingWeight = selected.data('servingweight');
        }

        function getMultipler() {

            // How many servings were eaten
            var numberOfServings = $("#how-much").val();
            var servingWeight = getServingWeight();

            // Calculate multipler (all standard fields contain the nutrient factors per 100 grams, so the multipler is the releative porition size eaten)
            var multiplier = (servingWeight / 100) * numberOfServings;

            return multiplier;
        }
        /**
        * Adds a new food to one of the lists on the UI. Creates a new list if necessary.
        * 
        * @global array jsonData Result of the last GetFoods API call in the searcherCallback() function. (The global variable is NOT modified by this function, just read.)
        */
        function fillFoodLog(dataServing, multiplier, fitTempRecordId) {
            if (jsonData != 0) {

                var dataServingCaption = $("#when-serving option:selected").text();
                var dataServingId = $('#when-serving').val();

                /* Finds the selected food in the array */
                // TODO: better to pass the selected food item
                $(jsonData).each(function (x) {
                    //if (jsonData[x].Desc == productName) {  
                    if (jsonData[x].FoodId == productId) {

                        var tmpServings = $("#how-much").val();
                        var tmpServingSizeId = $('#serving').val();

                        //function addFoodToUILog(fitRecordId, dataServingId, dataServingCaption, multiplier, desc, servings, energKcal, lipidTotal, FASat, fiber, carbohydrate, protein, sodium, water, servingSizeId, servings, foodId) {
                        addFoodToUILog(fitTempRecordId, dataServingId, dataServingCaption, multiplier, jsonData[x].ShortDesc, $("#how-much").val(), jsonData[x].EnergKcal, jsonData[x].LipidTotal, jsonData[x].FASat,
                                        jsonData[x].Fiber, jsonData[x].Carbohydrate, jsonData[x].Protein, jsonData[x].Sodium, jsonData[x].Water, jsonData[x].SugarTot, tmpServingSizeId, productId);
                    }
                });

                /* Ensures that all tables are sortable. */
                $("li.droptrue > ul").sortable({
                    placeholder: "ui-state-highlight",
                    connectWith: "li.tbody > ul"
                });

                $("li.droptrue > ul").disableSelection();
            }
        }

        function getFoodServings(foodid) {

            var url = API_PATH + "/Foods/GetFoodServings?jsoncallback=?";

            // Get valid servings for this food
            $.getJSON(url, { 'foodid': foodid }, function (data) {

                if (isMealPlanner == true) {
                    $("#serving").combobox("destroy");
                }

                // Clear existing list
                $('#serving').html("");
                $('#serving').val("");

                // Selects first item in list by default
                var selectedText = "selected=selected ";

                // Append to combobox
                $.each(data, function (val, serving) {

                    if (serving.ServingDesc == "g") {
                        $('#serving').append(
                            $('<option ' + selectedText + 'data-servingweight=' + serving.ServingWeight + '></option>').val(serving.ServingSizeId).html('grams') //serving.ServingDesc)
                        );
                    }
                    else {
                        $('#serving').append(
                          $('<option ' + selectedText + 'data-servingweight=' + serving.ServingWeight + '></option>').val(serving.ServingSizeId).html(serving.ServingDesc + ", " + serving.ServingWeight + "g")
                        );
                    }

                    selectedText = '';

                });

                if (isMealPlanner == true) {
                    $("#serving").combobox();
                }

                if (data.length > 0) {
                    //   $('.ui-combobox-input').val(selectedEvent);
                }

            });
        }

        function eatFood(foodId, servingSizeId, whenEatenId, dateEaten, servings, fitTempRecordId) {

            //&dateEaten=07-Apr-2012&foodId=1821&servings=1&servingSizeId=3638&whenEatenId=1&_=1333752982818

            var url = API_PATH + "/Foods/EatFood?jsoncallback=?";
            var currentDate = $.datepicker.formatDate("dd-M-yy", $('#fit_datepicker').datepicker("getDate"));

            $.getJSON(url, { email: $("#fit_email").val(), dateEaten: currentDate, tokenId: fitTempRecordId, foodId: foodId, servings: servings, servingSizeId: servingSizeId, whenEatenId: whenEatenId }, function (data) {

                // Replace temporary recordId with actual FoodEatenId
                var selected = $("ul").find("[data-recordid='" + fitTempRecordId + "']");

                if (selected) {
                    $(selected).attr("data-recordid", data.foodEatenId);
                }
            });
        }


        // TODO: bug?  dateEaten is param but not actually used
        function eatCalories(whenEatenId, dateEaten, calories, description, fitTempRecordId) {

            var url = API_PATH + "/Foods/EatFood?jsoncallback=?";
            var currentDate = $.datepicker.formatDate("dd-M-yy", $('#fit_datepicker').datepicker("getDate"));

            $.getJSON(url, { email: $("#fit_email").val(), dateEaten: currentDate, tokenId: fitTempRecordId, foodId: null, servings: 1, servingSizeId: 0, whenEatenId: whenEatenId, calories: calories, description: description }, function (data) {

                // Replace temporary recordId with actual FoodEatenId
                var selected = $("ul").find("[data-recordid='" + fitTempRecordId + "']");

                if (selected) {
                    $(selected).attr("data-recordid", data.foodEatenId);
                }

            });
        }

        function getFoodEatenForDay(dateEaten) {
            // Remove added/error messages
            fmHideAddAndErrorMessages();

            var url = API_PATH + "/Foods/GetFoodEaten?jsoncallback=?";
            //var url = "http://localhost:51827" + "/Foods/GetFoodEaten?jsoncallback=?";
            var currentDate = $.datepicker.formatDate("dd-M-yy", dateEaten);
            var email = $("#fit_email").val();
            var sessionGuid = $("#fit_session").val();

            $.getJSON(url, { dateEaten: currentDate, SessionGuid: sessionGuid, email: email }, function (data) {

                if (data.MessageId == 0) {
                    // Clear existing records
                    $("#food-log").html('');

                    kCalsConsumedToday = 0;

                    // Load each food into UI
                    $.each(data.FoodEaten, function (i, food) {

                        // Calculate multipler (all standard fields contain the nutrient factors per 100 grams, so the multipler is the relative portion size eaten)
                        var multiplier = (food.ServingWeight / 100) * food.Servings;

                        // if manual calories
                        if (food.Food == null) {
                            addFoodToUILog(food.FoodEatenId, food.WhenEatenId, food.WhenEaten, 1, food.Description, 1, food.Calories, '&nbsp;', '&nbsp;', '&nbsp;', '&nbsp;', '&nbsp;', '&nbsp;', '&nbsp;', '&nbsp;', null, null);
                        }
                        else {
                            addFoodToUILog(food.FoodEatenId, food.WhenEatenId, food.WhenEaten, multiplier, food.Food.ShortDesc, food.Servings, food.Food.EnergKcal, food.Food.LipidTotal, food.Food.FASat,
                                            food.Food.Fiber, food.Food.Carbohydrate, food.Food.Protein, food.Food.Sodium, food.Food.Water, food.Food.SugarTot, food.ServingSizeId, food.FoodId);
                        }

                        // EMC: TODO: adding ServingSizeId, jsonData.Servings to UILog - check manual null

                        kCalsConsumedToday = kCalsConsumedToday + food.Calories;

                    });

                    // Reset and display the daily calorie goal
                    fitSetInitialDailyCalorieGoal(kCalsConsumedToday);

                    kCal = 0;
                    updateCaloriesGraph();


                }

            });


        }

    }


    //fitCore.foodLog = {};
    /*var foodLog = function () {
        var test = "aaa";
        this.setup = function (test, thing) {
            console.log(test + thing);

        }

        var other = function () {
            console.log('hello');
        }

    }
    */

    fitCore.foodLog = new foodLog();
















    /***************************************************************************************************
    *
    *
    *
    *                                           MEAL PLANNER
    *
    *
    *
    *
    ****************************************************************************************************/



    // Meal functions 
    fitCore.mealPlanner = {

        isMealEditorInitialised: false,

        //var week_map = new Map();
        thisWeeksStats: Object,

        // Holds this weeks statistics for graph
        getNewStatsObject: function () {
            var stats = new Object();
            stats.cals_total = 0;
            stats.fat_total = 0;
            stats.satfa_total = 0;
            stats.fiber_total = 0;
            stats.carbs_total = 0;
            stats.protein_total = 0;
            stats.water_total = 0;
            stats.salt_total = 0;
            stats.sugars_total = 0;

            return stats;
        },

        // Holds this weeks statistics for graph
        initWeeklyStats: function () {
            fitCore.mealPlanner.thisWeeksStats = fitCore.mealPlanner.getNewStatsObject();
        },

        addStat: function (food, statsObject, nutrientMultiplier) {

            //console.log(food);
            if (food.IsManual == true) {
                statsObject.cals_total += food.EnergKcal;

                if (window.console) {
                    console.log('foodstat', food.Calories, statsObject.cals_total);
                }
                return;
            }

            statsObject.cals_total += (food.EnergKcal * nutrientMultiplier);
            statsObject.fat_total += (food.LipidTotal * nutrientMultiplier);
            statsObject.satfa_total += (food.FASat * nutrientMultiplier);            // The portion of total fat that is saturated fat
            statsObject.fiber_total += (food.Fiber * nutrientMultiplier);
            statsObject.carbs_total += (food.Carbohydrate * nutrientMultiplier);
            statsObject.protein_total += (food.Protein * nutrientMultiplier);
            statsObject.salt_total += (food.Sodium * nutrientMultiplier);
            statsObject.water_total += (food.Water * nutrientMultiplier);
            statsObject.sugars_total += (food.SugarTot * nutrientMultiplier);        // The portion of carbs that is sugars
            //console.log('food', food);
        },

        // Add the food to the meal map and to the UI planner
        addFoodToPlanner: function (dateEaten, whenEatenId, food, foodEatenId, plannerColumn, servingCount, servingDesc, servingWeight, servingSizeId) {

            //console.log(dateEaten, whenEatenId, food, foodEatenId, plannerColumn);

            // Get meal object and group an individual food into a generic "Drinks" or "Other meal" if reqd
            var mealEaten = fitCore.mealPlanner.getFoodItemObject(null, dateEaten, whenEatenId, 'desc', food, foodEatenId, servingCount, servingDesc, servingWeight, servingSizeId);
            //                                                                    console.log('meal', mealEaten);
            //                                                                  console.log(week_map);
            var cellEntry = fitCore.mealPlanner.addFoodItemToMap(week_map, mealEaten);
            //                                                                console.log(week_map);
            //console.log(cellEntry);

            // Clear the cell of any existin entries
            fitCore.mealPlanner.clear_calendar_cell(whenEatenId, plannerColumn);

            fitCore.mealPlanner.populate_calendar_day(week_map, dateEaten, whenEatenId, plannerColumn);
        },

        // Initialise the meal planner
        start: function () {


            // TODO: order so calendar and meals i've eaten are loaded first

            getSessionDetails();

            // Weekly stats for graph
            fitCore.mealPlanner.initWeeklyStats();

            ui_offset_x = 0; //$(".fit-ui").offset().left;
            ui_offset_y = $(".fit-ui").offset().top - 80;

            // load the typical data
            var typical_url = API_PATH + "/Foods/GetMyFrequentMeals?jsoncallback=?";

            $.getJSON(typical_url, { 'email': email, 'SessionGuid': sessionGuid }, function (obj) {
                var arr = obj.Meals;

                var map = new Map();

                for (var i = 0; i < arr.length; i++) {
                    var ml = arr[i];

                    if (map.containsKey(ml.Description)) {
                        map.get(ml.Description).add(ml);
                    } else {
                        var list = new ArrayList();
                        list.add(ml);
                        map.put(ml.Description, list);
                    }
                }

                $("#fit-t-l").hide();

                // Add frequent meals to accordian
                for (var i = 0; i < map.keys().length; i++) {
                    $("#fit-typical").children().eq(0).append('<p style="margin:0px;margin-left:-20px;font-size:11px;">--' + map.keys()[i] + '--</p>');

                    var lst = map.get(map.keys()[i]);
                    for (var j = 0; j < lst.size() ; j++) {
                        var itm = $('<p class="ui-state-default ui-corner-all ui-helper-clearfix fit-itm">' + lst.get(j).MealName + '</p>');

                        //console.log(lst.get(j).Description);
                        itm.data("mealid", lst.get(j).MealId);
                        itm.data("desc", lst.get(j).MealDescription);
                        itm.data("mealname", lst.get(j).MealName);

                        $("#fit-typical").children().eq(0).append(itm);
                    }
                }

                fitCore.mealPlanner.render_item();

                // Determine if mealpane area can be collapsed
                var mealpaneCollapsible = $("#fit_mealpane_collapsible").val() == 'true' ? true : false;

                // Automatically put my meals at the top of the accordion, if there are some
                if (arr.length > 0) {
                    $(".fit-meal-typical").prependTo('#fit-accordion');
                    $("#fit-accordion").accordion({ active: false, header: "h3", collapsible: mealpaneCollapsible });
                    $("#fit-accordion").accordion({ active: 0 });

                    if (window.console) {
                        console.log('activating 0');
                    }
                    //$(".fit-meal-typical").prependTo('#fit-accordion');
                    //$("#fit-accordion").accordion({ active: false });
                    //$(".fit-meal-typical").accordion({ active: 0 });
                }
                else {

                    // Accordion
                    var $acc = $("#fit-accordion").accordion({
                        header: "h3",
                        collapsible: mealpaneCollapsible
                    });

                    //$(".fit-meal-shared").hide();
                    $(".fit-meal-typical").hide();

                    //                  $acc.children().first().hide();
                    //                    $acc.children().last().hide();

                    $("#fit-accordion").accordion({ active: 0 });

                    if (window.console) {
                        console.log('activing 1');
                    }
                }

                $("#fit-new-meal-btn").prependTo('#fit-accordion');


            });

            //// Save current group of food as a meal
            //$("#fit-new-meal-btn").click(function () {

            // Redirect to nutrition log
            //var path = $("#fit_nutrition_log_path").val();
            //window.location = path;

            //            });

            function nodeChangeHandler(editor_id, node, undo_index, undo_levels, visual_aid, any_selection) {

                // Debug to Firebug in FF
                //	if (console) {
                //		console.debug(editor_id);
                //}

                var instance = '#' + editor_id;

                $('#evContent').html($(instance).tinymce().getContent());

                //$('#evContent').html(  	$(editor_id).g

                //alert($('#tinymce').tinymce().getContent());
                //$('#evContent').html(  	$(editor_id).getBody().innerHTML()  );
            }

            $("#fit-new-meal-btn").click(function () {
                // window.location = "/member/nutrition-log";



                // Ensure nutrition content panel visible
                $(".fit-new-meal-right").show();

                if (fitCore.mealPlanner.isMealEditorInitialised == false) {
                    fitCore.mealPlanner.initialiseMealEditor();
                }
                else {

                    // Clear existing fields
                    $('#fit-mealname').val('');
                    $("#fit-newmeal-cal").val();
                    $("#fit-newmeal-carb").val();
                    $("#fit-newmeal-chol").val();
                    $("#fit-newmeal-fiber").val();
                    $("#fit-newmeal-totalfat").val();
                    $("#fit-newmeal-fasat").val();
                    $("#fit-newmeal-salt").val();
                    $("#fit-newmeal-protein").val();
                    $("#fit-newmeal-sugars").val();
                    $('textarea.tinymce').html('');
                }

                $('#fit-new-meal').dialog({
                    dialogClass: 'fit-dialogtitle',
                    resizable: false,
                    width: 'auto',
                    modal: true,
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


                    }
                }).dialog('open');

            });

            // New meal save button from log
            $("#fit-new-meal #btn-cancel").live("click", function () {
                $("#fit-new-meal").dialog("close");
            });

            $("fit-new-meal-btn #btn-save").live("click", function () {

                if (window.console) {
                    console.log('new basic meal-1');
                }

                var name = $("#fit-new-meal #fit-mealname").val();
                var desc = $("#fit-new-meal #fit-mealdesc").val();
                var whenid = $("#fit-new-meal #fit-mealwhenid").val();
                var ispublic = $("#fit-new-meal #fit-mealpublic").is(":checked");

                //validate the fields
                if (name.length == 0) {
                    alert("Meal name could not be blank!");
                    return;
                }

                if (name.length > 80) {
                    alert("Meal name could not exceed 80 characters!");
                    return;
                }

                if (desc.length == 0) {
                    alert("Meal description could not be blank!");
                    return;
                }

                if (desc.length > 4000) {
                    alert("Meal description could not exceed 4000 characters!");
                    return;
                }

                //$("#fit-popup-window").hide();
                //$("#fit-popup-window .fit-ctnt").html("");
                //$("#fit-shadow").show();

                fitCore.mealPlanner.AddNewBasicMeal(name, desc, whenid, ispublic);
            });

            // Load the 'all meals' panel
            fitCore.mealPlanner.loadAllMeals();

            // Load the nutrition recipe data
            var nutri_url = API_PATH + "/Foods/GetNutrionistRecipes?jsoncallback=?";

            $.getJSON(nutri_url, { 'email': email, 'SessionGuid': sessionGuid }, function (obj) {
                var arr = obj.Meals;

                $("#fit-n-l").hide();

                for (var j = 0; j < arr.length; j++) {
                    var itm = $('<p class="ui-state-default ui-corner-all ui-helper-clearfix fit-itm">' + arr[j].MealName + '</p>');
                    //itm.data("ttl", 		arr[j].MealDescription);
                    itm.data("mealid", arr[j].MealId);
                    itm.data("desc", arr[j].MealDescription);
                    itm.data("mealname", arr[j].MealName);

                    $("#fit-nutri-content").append(itm);
                }

                fitCore.mealPlanner.render_item();
            });

            // Load the drink data
            var drinks_url = API_PATH + "/Foods/GetDrinks?jsoncallback=?";

            $.getJSON(drinks_url, { 'email': email, 'SessionGuid': sessionGuid }, function (obj) {
                var arr = obj.Drinks;

                $("#fit-d-l").hide();

                for (var j = 0; j < arr.length; j++) {
                    var itm = $('<p class="ui-state-default ui-corner-all ui-helper-clearfix fit-itm">' + arr[j].LongDesc + '</p>');
                    //itm.data("ttl",         arr[j].MealDescription);
                    itm.data("foodid", arr[j].FoodId);
                    itm.data("mealid", -2);
                    itm.data("servingsizeid", arr[j].ServingSizeId);
                    itm.data("desc", arr[j].LongDesc);
                    itm.data("mealname", arr[j].LongDesc);

                    $("#fit-drinks").append(itm);
                }

                fitCore.mealPlanner.render_item();
            });

            //Initialize the calendar
            //var dt = new Date(1327996800000); //for testing
            var dt = new Date();
            //console.log('init1', dt, fitCore.mealPlanner.getDateString(dt));
            var ctx = fitCore.mealPlanner.getQueryStr("ctx");
            if (ctx != "")
                dt = new Date(parseInt(ctx));
            //console.log('init2', dt, fitCore.mealPlanner.getDateString(dt));

            dt = fitCore.mealPlanner.getMonday(dt);

            //dt = new Date(dt.getTime() - ((dt.getDay() + 7) % 8) * 86400000);

            //console.log('init3', dt, fitCore.mealPlanner.getDateString(dt));
            fitCore.mealPlanner.calculate_calendar(dt, "fit-active-cal");
            fitCore.mealPlanner.render_calendar();

            $(".fit-itm").livequery(function () {
                $(this).draggable({
                    helper: "clone",
                    opacity: 0.35,
                    //appendTo: ".fit-ui"
                    appendTo: "parent"
                });
                $(this).disableSelection();
            });

            $(".fit-op").livequery(function () {
                var ctx = $(this);
                $(this).droppable({
                    accept: ".fit-itm, .fit-meal",
                    tolerance: "intersect",
                    hoverClass: "fit-ovr",
                    drop: function (event, ui) {
                        var mealid = ui.draggable.data("mealid");
                        var foodid = ui.draggable.data("foodid");

                        if ((mealid == null || mealid == "undefined" || mealid == undefined) && (foodid == null || foodid == "undefined" || foodid == undefined))
                            return;

                        var oriDate = null;
                        var oriWhen = null;

                        if (ui.draggable.hasClass("fit-meal")) {
                            oriDate = fitCore.mealPlanner.getDate(ui.draggable.parent(), 4);
                            oriWhen = fitCore.mealPlanner.getWhen(ui.draggable.parent());
                        }
                        var newDate = fitCore.mealPlanner.getDate(ctx, 4);
                        var newWhen = fitCore.mealPlanner.getWhen(ctx);
                        if (oriDate == newDate && oriWhen == newWhen)
                            return;

                        $("#fit-w-l").show();

                        // TODO: if it is in the weekmap should it be moved to another place/updated?  - have to query server anyway to get all the foods for this meal
                        // TODO: if it is NOT in the weekmap should it be added

                        if (ui.draggable.hasClass("fit-meal")) {

                            //change the meal timing
                            var update_url = API_PATH + "/Meals/UpdateMealTimings?jsoncallback=?";

                            // Get valid servings for this food
                            $.getJSON(update_url, {
                                'email': email,
                                'SessionGuid': sessionGuid,
                                'mealId': mealid,
                                'originalDateEaten': oriDate,
                                'originalWhenEatenId': oriWhen,
                                'newDateEaten': newDate,
                                'newWhenEatenId': newWhen
                            }, function (data) {
                                if (data.MessageId == 0) {

                                    // TODO:  Find the entry in the meal map
                                    // Grab the food list
                                    // Add to the new correct position in the map

                                    /*ui.draggable.remove();

                                    var con = $('<p class="ui-state-default ui-corner-all ui-helper-clearfix fit-meal"></p>');
                                    
                                    con.data("ttl", 		ui.draggable.data("ttl"));
                                    con.data("mealid", 	ui.draggable.data("mealid"));
                                    con.data("desc", 		ui.draggable.data("desc"));
                                    
                                        con.append($.wrap_text(ui.draggable.html(), 20));*/
                                    ui.draggable.appendTo(ctx);
                                }
                                else {
                                    alert("Sorry, the new meal time could not be saved to the database - is your network connection OK?");
                                }

                                $("#fit-w-l").hide();
                            });
                        } else {
                            if (foodid) {
                                //add drink
                                var save_url = API_PATH + "/Foods/EatFood?jsoncallback=?";

                                // Save food item as eaten on this date
                                $.getJSON(save_url, {
                                    'email': email,
                                    'SessionGuid': sessionGuid,
                                    'dateEaten': newDate,
                                    'foodId': foodid,
                                    'servings': 1,
                                    'servingSizeId': ui.draggable.data("servingsizeid"),
                                    'whenEatenId': newWhen,
                                    'tokenId': 1234
                                },
                                                              function (data) {
                                                                  if (data.MessageId == 0) {

                                                                      // saved Ok
                                                                      var con = $('<p class="ui-state-default ui-corner-all ui-helper-clearfix fit-meal"></p>');

                                                                      //con.data("ttl", 		ui.draggable.data("ttl"));


                                                                      //console.log('eating food', data.Food);

                                                                      // If first drink in this cell, use drink name.  If not, rename existing to Drinks and append 
                                                                      //var key = new Date(parseInt(dt));
                                                                      //key = key.getFullYear() + "-" + (key.getMonth() + 1) + "-" + key.getDate();
                                                                      //var list = week_map.get(edate).get(when_id).get(meal_id);

                                                                      // Add the food to the meal map and to the planner UI
                                                                      fitCore.mealPlanner.addFoodToPlanner(newDate, newWhen, data.Food, data.foodEatenId, ctx.index() - 1, 1, data.ServingSize.ServingDesc, data.ServingSize.ServingWeight, ui.draggable.data("servingsizeid"));


                                                                      //week_map, dateKey, whenId, column

                                                                      // Redisplay 
                                                                      //fitCore.mealPlanner.populate_calendar_cell(

                                                                      // If this is the first entry, then there is only one entry in cell
                                                                      //if (cellEntry.childCount == 1) {

                                                                      //con.data("mealid", -2);
                                                                      //con.data("desc", cellEntry.meal_desc);; //"Drinks");
                                                                      //con.data("mealname", cellEntry.meal_name); //"Drinks");
                                                                      //con.data("whenid", newWhen);
                                                                      ////con.data("eatdate", fitCore.mealPlanner.getDate(ctx, 2) + "");
                                                                      //con.data("eatdate", newDate); //fitCore.mealPlanner.getDate(ctx, 2) + "");

                                                                      ////con.append("Drinks");
                                                                      //con.append(cellEntry.meal_name);
                                                                      //con.appendTo(ctx);
                                                                      //}
                                                                      //else {

                                                                      //fitCore.mealPlanner.displayCell(week_map, newDate, newWhen);

                                                                      /*con.data("mealid", -2);
                                                                      con.data("desc", cellEntry.meal_desc);; //"Drinks");
                                                                      con.data("mealname", cellEntry.meal_name); //"Drinks");
                                                                      con.data("whenid", newWhen);
                                                                      //con.data("eatdate", fitCore.mealPlanner.getDate(ctx, 2) + "");
                                                                      con.data("eatdate", newDate); //fitCore.mealPlanner.getDate(ctx, 2) + "");

                                                                      //con.append("Drinks");
                                                                      con.append(cellEntry.meal_name);
                                                                      con.appendTo(ctx);*/

                                                                      //}

                                                                  }
                                                                  else {
                                                                      // error occurred
                                                                      alert("Save data error!");
                                                                  }

                                                                  $("#fit-w-l").hide();
                                                              });
                            } else {
                                //add new meal from accordion
                                //save the meal data
                                var save_url = API_PATH + "/Foods/EatMeal?jsoncallback=?";

                                // Eat this meal.  
                                // TODO: Could return meal contents and add to meal map immediately so it's on client
                                $.getJSON(save_url, {
                                    'email': email,
                                    'SessionGuid': sessionGuid,
                                    'dateEaten': newDate,
                                    'mealId': mealid,
                                    'whenEatenId': newWhen
                                },
                                                              function (data) {
                                                                  if (data.MessageId == 0) {
                                                                      // saved Ok
                                                                      var con = $('<p class="ui-state-default ui-corner-all ui-helper-clearfix fit-meal"></p>');

                                                                      //con.data("ttl", 		ui.draggable.data("ttl"));

                                                                      con.data("mealid", ui.draggable.data("mealid"));
                                                                      con.data("desc", ui.draggable.data("desc"));
                                                                      con.data("mealname", ui.draggable.html());
                                                                      con.data("whenid", newWhen);
                                                                      //con.data("eatdate", fitCore.mealPlanner.getDate(ctx, 2) + "");

                                                                      con.data("eatdate", newDate);

                                                                      con.append(fitCore.mealPlanner.wrap_text(ui.draggable.html(), 20));
                                                                      con.appendTo(ctx);
                                                                  }
                                                                  else {
                                                                      // error occurred
                                                                      alert("Save data error!");
                                                                  }

                                                                  $("#fit-w-l").hide();
                                                              });
                            }
                        }

                        if ($("#fit-popup-tooltip").is(":visible")) {
                            $("#fit-popup-tooltip").hide();
                        }
                    }
                });
            });

            // Future feature - display tooltip on hover-over
            /*$(".fit-meal, .fit-itm").live("mouseover", function (event) {
                if ($("#fit-popup-tooltip").is(":visible") || $(this).data("desc") == "undefined" || $(this).data("desc") == undefined || $("#fit-popup-window").is(":visible"))
                    return;

                var frame = $("#fit-popup-tooltip").children().eq(1);
                var ovrly = $("#fit-popup-tooltip").children().eq(0).children().eq(1);
                var lft = ($(document).width() - ovrly.width()) / 2;
                

                $("#fit-popup-tooltip .fit-ctnt").html($(this).data("desc").replaceAll("\n", "<br />", false));

                $("#fit-popup-tooltip").show(5, function () {

                    frame.width(400);
                    ovrly.width(422);

                    ovrly.height(frame.height() + 22);

                    var mouse_x = event.pageX;
                    var mouse_y = event.pageY;
                    
                    var off_set_x = 0;
                    var off_set_y = 0;

                    if (mouse_x - $(document).scrollLeft() < $(window).width() / 2)
                        off_set_x = mouse_x + 20;
                    else
                        off_set_x = mouse_x - 460;


                    
                    if (ovrly.height() > ($(window).height() - (mouse_y - $(document).scrollTop()))) {

                        console.log('panel height: ' + ovrly.height());
                        console.log('window height: ' + $(window).height());
                        console.log('mouse y: ' + mouse_y);
                        console.log('scroll top: ' + $(document).scrollTop());

                        off_set_y = $(window).height() - ovrly.height() - 30 + $(document).scrollTop();
                    } else {
                        off_set_y = mouse_y + 20;
                    }

                    

                    

                    frame.css("left", off_set_x - ui_offset_x);
                    ovrly.css("left", off_set_x - ui_offset_x);


                    //frame.css("left", mouse_x);
                    //ovrly.css("left", mouse_x);

                    //frame.css("top", off_set_y - ui_offset_y);
                    //ovrly.css("top", off_set_y - ui_offset_y);


                    // Half of frame
                    var halfFrame = (frame.height() / 2);

                    // Midpoint where the panel is half above the mouse/half below
                    var positionY = mouse_y - halfFrame;

                    // top position, if popup was bottom aligned
                    var potentialTopIfBottomAligned = $(window).height() - frame.height() + $(document).scrollTop();
                    var potentialTopIfTopAligned = $(window).height() - frame.height();

                    console.log('potential top', potentialTopIfBottomAligned);

                    //var btnToBottomWindow = $(window).height() - mouse_y;
                    //var btnToBottomPanel = $

                    // If it doesn't fit in the visible window then see if it would work if moved up
                    //if( positionY + frame.hei

                    console.log('CHOOSEN:' + positionY);

                    frame.css("top", positionY);
                    ovrly.css("top", positionY);

                });
            });

            $(".fit-meal,.fit-itm").live("mouseout", function () {
                $("#fit-popup-tooltip").hide();
            });*/

            $(".fit-meal").livequery(function () {
                $(this).draggable({
                    helper: function () {
                        return $(this).clone().width($(this).width());
                    },
                    opacity: 0.35,
                    appendTo: "body"
                });
                $(this).disableSelection();
            });

            //$("#fit-accordion").droppable({
            $("body").droppable({
                accept: ".fit-meal",
                tolerance: "intersect",
                drop: function (event, ui) {

                    // If they drop it anywhere outside the calendar
                    var range_left = $('#fit-calendar').offset().left;
                    var range_top = $('#fit-calendar').offset().top;
                    var range_width = $('#fit-calendar').width();
                    var range_height = $('#fit-calendar').height();

                    var mouse_x = event.pageX;
                    var mouse_y = event.pageY;

                    // TODO: is it removed from week_map ?

                    if (!(mouse_x > range_left && mouse_x < range_left + range_width && mouse_y > range_top && mouse_y < range_top + range_height)) {
                        $("#fit-w-l").show();

                        var del_url = API_PATH + "/Meals/DeletePersonMeal?jsoncallback=?";
                        var delDate = fitCore.mealPlanner.getDate(ui.draggable.parent(), 4);
                        var delWhen = fitCore.mealPlanner.getWhen(ui.draggable.parent());
                        var mealId = ui.draggable.data("mealid");
                        var foodEatenId = ui.draggable.data("foodeatenid");

                        // If it is a group of drinks or food items, delete each
                        if (mealId == -2 || mealId == -1) {

                            //console.log('foodEatenId', foodEatenId, delWhen, delDate);
                            fitCore.mealPlanner.deleteFoodEntryFromCalendar(delWhen, delDate, (mealId == -2) ? true : false, ui);

                            // remove meal from map
                            //var x = week_map.get(delDate).get(delWhen).get(mealId);

                            // WORKAROUND: reload calendar for now
                            //fitCore.mealPlanner.calculate_calendar(new Date(START_MONDAY), "fit-active-cal");
                            //fitCore.mealPlanner.render_calendar();




                            // var obj = meal_map.get(mealid).get(0);

                            // TODO: update graph


                            // 
                        }
                        else {

                            // Delete this meal
                            $.getJSON(del_url, {
                                'email': email,
                                'SessionGuid': sessionGuid,
                                'mealId': mealId,
                                'dateEaten': delDate,
                                'whenEatenId': delWhen
                            }, function (data) {
                                if (data.MessageId == 0) {
                                    // deleted Ok
                                    ui.draggable.remove();
                                }
                                else {
                                    // error occurred
                                    alert("delete error!");
                                }

                                // TODO: needed?
                                $("#fit-w-l").hide();
                            });
                        }

                        if ($("#fit-popup-tooltip").is(":visible"))
                            $("#fit-popup-tooltip").hide();
                    }
                }
            });

            //timer to prevent exception when user try to double click the arrow button
            var TimeFn = null;

            //scroll left the calendar to previous week
            $("#fit-nav-left").live("click", function () {
                fitCore.mealPlanner.scrollToPreviousWeek(null);
            });

            // Scroll right the calendar to next week
            $("#fit-nav-right").live("click", function () {
                fitCore.mealPlanner.scrollToNextWeek();
            });

            $(".fit-cls").live("click", function () {
                //$("#fit-popup-window").hide();
                //$("#fit-popup-window .fit-ctnt").html("");
                //$("#fit-bg-shadow").hide();
                $("#fit-popup-mealinfo").dialog("close");
            });

            //click the item in calendar cell
            $(".fit-meal").live("click", function () {
                var meal_id = $(this).data("mealid");
                var when_id = $(this).data("whenid");
                var food_id = $(this).data("foodid");
                var meal_desc = $(this).data("desc");
                var meal_name = $(this).data("mealname");
                var edate = $(this).data("eatdate"); //new Date(parseInt($(this).data("eatdate"))); // yyyy-mm-dd
                //edate = edate.getFullYear() + "-" + (edate.getMonth() + 1) + "-" + edate.getDate()

                if (meal_id == null || meal_id == "" || meal_id == undefined)
                    return;

                //console.log(food_id);

                // If it is a single drink in the accordion the mealId is -2
                if (meal_id == -2 && food_id != null) {
                    fitCore.mealPlanner.food_query(food_id);
                    return;
                }

                // See if the item is in the weekmap (it should be but if it is being dragged on fron the sidebar it won't be as sidebar doesn't contain food items)
                var isDataOnClient = $(this).data("dataonclient");

                if (meal_id == -1) {
                    meal_name = 'Other meal';
                } else if (meal_id == -2) {
                    meal_name = 'Drinks';
                }

                if (typeof when_id !== 'undefined' && when_id !== false && isDataOnClient == true) {
                    fitCore.mealPlanner.client_query(meal_id, when_id, meal_desc, meal_name, edate, week_map);
                } else {
                    fitCore.mealPlanner.server_query(meal_id, when_id, meal_desc, meal_name);
                }
            });

            //click the item in accordion
            $(".fit-itm").live("click", function () {
                var meal_id = $(this).data("mealid");
                var when_id = $(this).data("whenid");
                var meal_desc = $(this).data("desc");
                var meal_name = $(this).data("mealname");

                if (meal_id == null || meal_id == "" || meal_id == undefined)
                    return;
                fitCore.mealPlanner.server_query(meal_id, when_id, meal_desc, meal_name);
            });

            $(document).keyup(function (e) {
                if (e.keyCode == 27) { // esc key
                    if ($("#fit-popup-window").is(":visible"))
                        $("#fit-popup-window").hide();
                    $("#fit-bg-shadow").hide();
                }
            });

            // Allow keyboard filtering of the list of shared meals, regex on name
            $('#fit-sharedmeals-filter').keyup(function () {

                // Activate after more than 2 characters
                var a = $(this).val();
                if (a.length > 2) {

                    // Finds all items in the list that contain the input, and hide the ones not containing the input while showing the ones that do
                    var containing = $('#fit-allmeal-content p').filter(function () {
                        var regex = new RegExp('\\b' + a, 'i');
                        return regex.test($(this).text());
                    }).slideDown();

                    $('#fit-allmeal-content p').not(containing).slideUp();

                } else {
                    $('#fit-allmeal-content p').slideDown();
                }

                return false;
            });

            // Allow keyboard filtering of the list of shared meals, regex on name
            $('#fit-recipe-filter').keyup(function () {

                // Activate after more than 2 characters
                var a = $(this).val();
                if (a.length > 2) {

                    // Finds all items in the list that contain the input, and hide the ones not containing the input while showing the ones that do
                    var containing = $('#fit-nutri-content p').filter(function () {
                        var regex = new RegExp('\\b' + a, 'i');
                        return regex.test($(this).text());
                    }).slideDown();

                    $('#fit-nutri-content p').not(containing).slideUp();

                } else {
                    $('#fit-nutri-content p').slideDown();
                }

                return false;
            });


            $("#fit-week-plan-btn").click(function () {
                fitCore.mealPlanner.popup_window2(500, "#fit-weekly-plan");
            });

            // New meal save button from log
            $("#fit-new-meal #btn-cancel").live("click", function () {
                $("#fit-new-meal").dialog("close");
            });

            // duplicating
            $("#btn-pref-cancel, #btn-plan-cancel, #btn-diary-cancel").live("click", function () { // #btn-cancel,
                $(this).closest(".ui-dialog-content").dialog("close");
            });

            $("#btn-pref-save").live("click", function () {
                var ck = "";
                $("#fit-pref-table input:checkbox").each(function () {
                    if ($(this).is(":checked"))
                        ck = ck + $(this).attr('id') + ",";
                });

                ck = ck.substring(0, ck.length - 1);

                if (ck != $.cookie("pref")) {
                    $.cookie("pref", ck, { expires: 7, path: '/' });
                    //window.location = "demo.html?ctx=" + START_MONDAY;
                    //fitCore.mealPlanner.render_calendar();

                    // Create new calendar display from template
                    fitCore.mealPlanner.reload_calendar();

                    /*                    // Create new calendar display from template
                                        var smp = $("#fit-sample").clone().css("display", "block").attr("id", "tmp");
                                        smp.insertBefore($("#fit-active-cal"));
                    
                                        // Get current viewing date
                                        var dat = new Date($("#fit-active-cal").data("ttl"));
                                        fitCore.mealPlanner.calculate_calendar(dat, "tmp");
                    
                                        // Remove old calendar, render the new
                                        $("#fit-active-cal").remove();
                                        smp.attr("id", "fit-active-cal");
                                        fitCore.mealPlanner.render_calendar();
                                        $("#fit-bg-shadow").hide();
                                        */

                }

                // close the dialog
                $(this).closest(".ui-dialog-content").dialog("close");
            });

            $("#btn-diary-save").live("click", function () {

                // Save the diary entry can pass in function to call on success
                // fitCore.mealPlanner.fitSaveDiaryEntry(fitCore.mealPlanner.fitMealplannerSaveDiaryEntrySuccess, currentLogId, currentdiarydate, email, sessionGuid);
                fitCore.mealPlanner.fitSaveDiaryEntry(fitCore.mealPlanner.fitMealplannerSaveDiaryEntrySuccess, fitCore.Data.CurrentDiaryID, fitCore.Data.CurrentDiaryDate, email, sessionGuid);

                // close the dialog
                $(this).closest(".ui-dialog-content").dialog("close");

                return false;
            });

            $("#btn-plan-save").live("click", function () {
                var plan_name = $("#fit-plan-name").val();
                var plan_desc = $("#fit-plan-desc").val();

                //validate the fields
                if (plan_name.length == 0) {
                    alert("Weekly plan name could not be blank!");
                    return;
                }

                if (plan_name.length > 80) {
                    alert("Weekly plan name could not exceed 80 characters!");
                    return;
                }

                if (plan_desc.length == 0) {
                    alert("Weekly plan description could not be blank!");
                    return;
                }

                if (plan_desc.length > 1800) {
                    alert("Weekly plan description could not exceed 1800 characters!");
                    return;
                }

                fitCore.mealPlanner.AddNewWeeklyPlan(plan_name, plan_desc);

                // close the dialog
                $(this).closest(".ui-dialog-content").dialog("close");

                return false;
            });

            var ht = $("#fit-active-cal").offsetHeight < 400 ? 400 : $("#fit-active-cal").offsetHeight;
            $("#fit-mask").height(ht);

            $("#fit-pref-icon").live("click", function () {
                fitCore.mealPlanner.popup_window2(500, "#fit-preference");

                var pref = $.cookie("pref");
                if (pref != null && pref != "") {
                    var pref_arr = pref.split(",");

                    for (var i = 0; i < pref_arr.length; i++) {

                        $("#fit-preference #" + pref_arr[i]).attr("checked", "checked");
                    }
                }
                else {
                    // If no preferences, set defaults
                    $("#fit-preference #1").attr("checked", "checked"); // breakfast
                    $("#fit-preference #3").attr("checked", "checked"); // lunch
                    $("#fit-preference #5").attr("checked", "checked"); // dinner
                }

                return false;
            });

            $(".fit-diary-icon").live("click", function () {
                fitCore.mealPlanner.popup_window2(630, "#fit-diary");

                // confirm the date
                /*currentdiarydate = $(this).data("currentdiarydate"); //parseInt(START_MONDAY) + ($(this).parent().parent().children().index($(this).parent()) - 1) * 86400000;
                currentLogId = $(this).data("currentLogId");
                */
                currentdiary = $(this);
                
                

                var diaryDate = $(this).data("currentdiarydate"); //parseInt(START_MONDAY) + ($(this).parent().parent().children().index($(this).parent()) - 1) * 86400000;
                fitCore.Data.CurrentDiaryID = $(this).data("currentLogId");
                fitCore.Data.CurrentDiaryDate = fitCore.mealPlanner.formatDate(diaryDate, 4);  // into dd-mmm-yy format

                //fitCore.Data.CurrentDiaryObject = $(this);

                var key = new Date(parseInt(diaryDate));

                // fill the diary data if necessary
                fitCore.mealPlanner.getDiaryNotes($(this).data("diary"));

                // render the breakdown chart
                key = key.getFullYear() + "-" + (key.getMonth() + 1) + "-" + key.getDate();
                var whenmap = week_map.get(key);


                // Initialise object to store graph stats
                var graphStats = fitCore.mealPlanner.getNewStatsObject();

                if (whenmap === undefined) {
                    // TODO: hide by default
                    cals_total = fat_total = satfa_total = fiber_total = carbs_total = protein_total = sugars_total = 20;
                } else {
                    for (var i = 0; i < whenmap.values().length; i++) {
                        var mealmap = whenmap.values()[i];

                        for (var j = 0; j < mealmap.values().length; j++) {
                            var foodlist = mealmap.values()[j];

                            for (var k = 0; k < foodlist.size() ; k++) {

                                var eatenItem = foodlist.get(k);
                                var fd = eatenItem.food;

                                if (fd == null)
                                    continue;

                                fitCore.mealPlanner.addStat(fd, graphStats, eatenItem.nutrientMultiplier);
                            }
                        }
                    }
                }

                // Render nutrition chart
                fitCore.mealPlanner.renderPieChart($("#fit-diary-chart"), graphStats, 60);
                return false;
            });

            $("#fit-diary-right input:text").live("keypress", function (event) {

                if (event.which == 0 || event.which == 9 || event.which == 8)
                    return true;

                var limit = 3;
                if ($(this).attr("id") == "fit_jnl_sleep")
                    limit = 2;

                if (event.which < 48 || event.which > 57 || $(this).val().length == limit) {
                    event.preventDefault();
                    return false;
                }

                return true;
            });

            $("#fit-diary-right input:text").live("paste", function (event) {
                event.preventDefault();
            });

            $("#fit-popup-window .fit-dialog").live("resize", function () {

                var frame = $("#fit-popup-window").children().eq(1);
                var ovrly = $("#fit-popup-window").children().eq(0).children().eq(1);
                ovrly.height(frame.height() + 22);
            });
        },

        initialiseMealEditor: function () {

            var mce_url = $("#fit_core_url").val(); // + '/js/tiny_mce/tiny_mce.js';

            $('textarea.tinymce').tinymce({

                // Location of TinyMCE script
                script_url: mce_url + '/js/tiny_mce/tiny_mce.js', //'js/tiny_mce/tiny_mce.js',

                // General options
                theme: "advanced",
                plugins: "pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template",

                // Theme options
                theme_advanced_buttons1: "bold,italic,underline,|,justifyleft,justifycenter,justifyright,formatselect,|,bullist,numlist,|,outdent,indent,|,link,unlinkimage,code,|,forecolor",
                theme_advanced_buttons2: "",
                theme_advanced_buttons3: "",
                theme_advanced_buttons4: "",
                theme_advanced_toolbar_location: "top",
                theme_advanced_toolbar_align: "left",
                theme_advanced_statusbar_location: "bottom",
                theme_advanced_resizing: true,
                theme_advanced_path: false,
                theme_advanced_resize_horizontal: false,
                width: "460",
                height: "201",

                // handle_node_change_callback: "nodeChangeHandler",

                // Example content CSS (should be your site CSS)
                content_css: mce_url + "/js/tiny_mce/css/content.css",

                // Drop lists for link/image/media/template dialogs
                template_external_list_url: "lists/template_list.js",
                external_link_list_url: "lists/link_list.js",
                external_image_list_url: "lists/image_list.js",
                media_external_list_url: "lists/media_list.js",

                // Replace values for the template plugin
                template_replace_values: {
                    username: "Some User",
                    staffid: "991234"
                }
            });

            fitCore.mealPlanner.isMealEditorInitialised = true;
        },

        loadAllMeals: function () {

            // Clear existing accordian
            $("#fit-allmeal-content").html('');

            // Load the 'all meals' data
            var allmeal_url = API_PATH + "/Foods/GetAllMyMeals?jsoncallback=?";

            $.getJSON(allmeal_url, { 'email': email, 'SessionGuid': sessionGuid }, function (obj) {
                var arr = obj.Meals;

                $("#fit-a-l").hide();

                //var shareFilter = $("<input type='text' id='fit-sharedmeal-filter'/>");
                //$("#fit-allmeal").append(shareFilter);

                for (var j = 0; j < arr.length; j++) {
                    var itm = $('<p class="ui-state-default ui-corner-all ui-helper-clearfix fit-itm">' + arr[j].MealName + '</p>');
                    //itm.data("ttl", 		arr[j].MealDescription);
                    itm.data("mealid", arr[j].MealId);
                    itm.data("desc", arr[j].MealDescription);
                    itm.data("mealname", arr[j].MealName);

                    $("#fit-allmeal-content").append(itm);
                }

                fitCore.mealPlanner.render_item();
            });
        },

        getMonday: function (d) {
            d = new Date(d);
            var day = d.getDay(),
                diff = d.getDate() - day + (day == 0 ? -6 : 1); // adjust when day is sunday
            return new Date(d.setDate(diff));
        },

        deleteFoodEntryFromCalendar: function (whenEatenId, dateEaten, isDrink, ui) {

            //var url = "http://localhost:51827/Foods/DeleteFoodEaten?jsoncallback=?";
            var url = API_PATH + "/Foods/DeleteFoodEaten?jsoncallback=?";
            var email = $("#fit_email").val();
            var sessionGuid = $("#fit_session").val();

            $.getJSON(url, { email: $("#fit_email").val(), SessionGuid: sessionGuid, whenEatenId: whenEatenId, dateEaten: dateEaten, isDrink: isDrink }, function (data) {

                if (data.MessageId == 0) {
                    // deleted Ok
                    ui.draggable.remove();

                }
                else {
                    // error occurred
                    alert(data.Message);
                }

                $("#fit-w-l").hide();       // Hide ajax visual indicator

            });
        },

        client_query: function (meal_id, when_id, meal_desc, meal_name, edate, week_map) {

            // Generate table of food html
            var list = week_map.get(edate).get(when_id).get(meal_id);
            var total_object = fitCore.mealPlanner.render_food_table(list, when_id, meal_name);
            $("#popup-food-log").html(total_object.return_html);

            // popup meal window
            fitCore.mealPlanner.popup_window2(780, "#fit-popup-mealinfo");

            // Store the list of food
            var popupObject = $("#fit-popup-mealinfo");
            $.data(document.body, 'foodlist', list);

            //put the meal description at the left lower section of the pop up window
            $("#fit-popup-lower-desc").html(meal_desc.replaceAll("\n", "<br />", false));

            //$("#fit-popup-lower-desc").html("<h1>test</h1>");
            //alert('chart:' + $("#fit-popup-lower-chart").html());

            //put and render the meal nutrition chart at the right lower section of the pop up window
            fitCore.mealPlanner.renderPieChart($("#fit-popup-lower-chart"), total_object, 60);
        },

        scrollToPreviousWeek: function (useDate) {
            clearTimeout(fitCore.mealPlanner.TimeFn);

            fitCore.mealPlanner.TimeFn = setTimeout(function () {
                $("#fit-placeholder").hide();
                var smp = $("#fit-sample").clone().css("display", "block").attr("id", "tmp");
                smp.insertBefore($("#fit-active-cal"));

                // Calculate next date
                var dat = new Date($("#fit-active-cal").data("ttl") - 7 * 86400000);

                if (useDate != null) {
                    if (window.console) {
                        console.log('set ', useDate);
                    }
                }

                // Update 'current' date the food log entry mode will add to
                $('#fit_datepicker').datepicker("setDate", dat);
                $("#fit_selecteddate").button("option", "label", $.datepicker.formatDate("D", dat) + ' ' + dat.getDate() + day_arr[dat.getDate() - 1]);

                fitCore.mealPlanner.calculate_calendar(dat, "tmp");

                $("#fit-banner").css("left", "-721px");
                $("#fit-banner").animate({ left: "+=721px" }, 600, function () {
                    $("#fit-active-cal").remove();
                    smp.attr("id", "fit-active-cal");

                    fitCore.mealPlanner.render_calendar();
                });
            }, 300);
        },

        scrollToNextWeek: function () {
            clearTimeout(fitCore.mealPlanner.TimeFn);

            fitCore.mealPlanner.TimeFn = setTimeout(function () {
                $("#fit-placeholder").hide();
                var smp = $("#fit-sample").clone().css("display", "block").attr("id", "tmp");
                smp.insertAfter($("#fit-active-cal"));

                // Calculate next date
                var dat = new Date(parseInt($("#fit-active-cal").data("ttl")) + 7 * 86400000);

                // Update 'current' date the food log entry mode will add to
                $('#fit_datepicker').datepicker("setDate", dat);
                $("#fit_selecteddate").button("option", "label", $.datepicker.formatDate("D", dat) + ' ' + dat.getDate() + day_arr[dat.getDate() - 1]);

                fitCore.mealPlanner.calculate_calendar(dat, "tmp");

                $("#fit-banner").animate({ left: "-=721px" }, 600, function () {
                    $("#fit-active-cal").remove();
                    $("#fit-banner").css("left", "0px");
                    smp.attr("id", "fit-active-cal");

                    fitCore.mealPlanner.render_calendar();
                });
            }, 300);
        },

        food_query: function (foodId) {

            var food_url = API_PATH + "/Foods/GetFoodItem?";

            $.getJSON(food_url, { 'foodId': foodId }, function (obj) {

                if (obj.meal != null && obj.meal != undefined) {

                    var list = new ArrayList();
                    var obj = new Object();
                    obj.food = data;
                    list.add(obj);

                    var total_object = fitCore.mealPlanner.render_food_table(list, when_id, meal_name);

                    //$("#fit-popup-window .fit-ctnt").html(total_object.return_html)
                    $("#popup-food-log").html(total_object.return_html);
                    fitCore.mealPlanner.popup_window2(780, "#fit-popup-mealinfo");

                    //put the meal description at the left lower section of the pop up window
                    //$("#fit-popup-lower-desc").html(meal_desc.replaceAll("\n", "<br />", false));

                    //put and render the meal nutrition chart at the right lower section of the pop up window
                    fitCore.mealPlanner.renderPieChart($("#fit-popup-lower-chart"), total_object, 60);
                }

            }).error(function () {
                //$("#fit-shadow").hide();
            });
        },

        server_query: function (meal_id, when_id, meal_desc, meal_name) {

            if (meal_id < 0)
                return;


            //$("#fit-shadow").show();

            //var frame = $("#fit-popup-window").children().eq(1);
            //var ovrly = $("#fit-popup-window").children().eq(0).children().eq(1);

            //frame.width(780);
            //ovrly.width(802);

            //load the nutrition recipe data
            var food_url = API_PATH + "/Foods/GetMealFoods?";

            $.getJSON(food_url, { 'mealId': meal_id }, function (obj) {
                if (obj.meal != null && obj.meal != undefined) {
                    var arr = obj.meal.MealFoods;

                    var list = new ArrayList();
                    for (var k = 0; k < arr.length; k++) {
                        var obj = new Object();
                        obj.food = arr[k].Food;

                        // when_id is undefined if they have clicked in the accordion, as it has not been allocated to a specific meal-time slot yet.
                        // in this scenario set the serving count and multipliers.
                        if (when_id == undefined || arr.length == 1) {
                            obj.servingCount = 1;
                            obj.servingDesc = "";
                            obj.nutrientMultiplier = 1;
                        }
                        else {
                            obj.servingCount = arr[k].Servings;
                            obj.servingDesc = "";
                            obj.nutrientMultiplier = 1;
                        }

                        list.add(obj);
                    }

                    var total_object = fitCore.mealPlanner.render_food_table(list, when_id, meal_name);

                    //$("#fit-popup-window .fit-ctnt").html(total_object.return_html)
                    $("#popup-food-log").html(total_object.return_html);
                    fitCore.mealPlanner.popup_window2(780, "#fit-popup-mealinfo");

                    //put the meal description at the left lower section of the pop up window
                    $("#fit-popup-lower-desc").html(meal_desc.replaceAll("\n", "<br />", false));


                    /*$("#fit-popup-window").show(5, function () {
                        ovrly.height(frame.height() + 22);

                        frame.css("left", $(document).scrollLeft() + ($(window).width() - frame.width()) / 2 - ui_offset_x);
                        ovrly.css("left", $(document).scrollLeft() + ($(window).width() - frame.width()) / 2 - ui_offset_x);

                        frame.css("top", $(document).scrollTop() + ($(window).height() - frame.height()) / 2 - ui_offset_y - 100);
                        ovrly.css("top", $(document).scrollTop() + ($(window).height() - frame.height()) / 2 - ui_offset_y - 100);

                        
                    });*/

                    //put and render the meal nutrition chart at the right lower section of the pop up window
                    fitCore.mealPlanner.renderPieChart($("#fit-popup-lower-chart"), total_object, 60);
                }

                //$("#fit-shadow").hide();
                //$("#fit-bg-shadow").show();
            }).error(function () {
                //$("#fit-shadow").hide();
            });
        },

        reload_calendar: function () {
            // Create new calendar display from template
            var smp = $("#fit-sample").clone().css("display", "block").attr("id", "tmp");
            smp.insertBefore($("#fit-active-cal"));

            // Get current viewing date
            var dat = new Date($("#fit-active-cal").data("ttl"));
            fitCore.mealPlanner.calculate_calendar(dat, "tmp");

            // Remove old calendar, render the new
            $("#fit-active-cal").remove();
            smp.attr("id", "fit-active-cal");
            fitCore.mealPlanner.render_calendar();
            //$("#fit-bg-shadow").hide();
        },

        populate_current_calendar: function (current_monday, week_map) {
            $("#fit-active-cal #fit-nutrition-cal tr:first").siblings().remove();

            var pref = $.cookie("pref");
            var pref_arr;

            if (pref != null && pref != "") {
                pref_arr = pref.split(",");
            } else {
                pref_arr = ["1", "2", "3"];
            }

            // console.log(pref_arr);

            for (var i = 0; i < pref_arr.length; i++) {
                var whenid = parseInt(pref_arr[i]);
                fitCore.mealPlanner.addCalendarRow(whenid);
            }

            //var cals_total = fat_total = satfa_total = fiber_total = carbs_total = protein_total = salt_total = 0;

            // Reset weekly stats to zero
            fitCore.mealPlanner.initWeeklyStats();

            // Default graph display if no data
            if (current_monday == 0 || current_monday == undefined || week_map.size() == 0) {

                // TODO: hide by default - these vars not defined/used anywhere
                cals_total = fat_total = satfa_total = fiber_total = carbs_total = protein_total = sugars_total = 20;

            } else {
                for (var i = 0; i < 7; i++) {
                    var dt = parseInt(current_monday) + i * 86400000;
                    var key = new Date(parseInt(dt));
                    key = key.getFullYear() + "-" + (key.getMonth() + 1) + "-" + key.getDate();

                    /*
                  Map:							date
                  Map:					when		when
                  Map:			meal		meal		meal		
                  List:	food		food		food		ood
                  */
                    //console.log(current_monday, key);

                    if (week_map.containsKey(key)) {
                        var when_map = week_map.get(key);

                        for (var j = 0; j < when_map.keys().length; j++) {
                            var whenid = when_map.keys()[j];

                            var meal_map = when_map.get(whenid);

                            for (var k = 0; k < meal_map.keys().length; k++) {
                                var mealid = meal_map.keys()[k];

                                var obj = meal_map.get(mealid).get(0);

                                fitCore.mealPlanner.populate_calendar_cell(whenid, i, obj);

                                for (var idx = 0; idx < meal_map.get(mealid).size() ; idx++) {

                                    var eatenItem = meal_map.get(mealid).get(idx);
                                    var fd = eatenItem.food;

                                    //var fd = meal_map.get(mealid).get(idx).food;
                                    if (fd == null)
                                        continue;

                                    // Add stats to totals for weekly graph
                                    fitCore.mealPlanner.addStat(fd, fitCore.mealPlanner.thisWeeksStats, eatenItem.nutrientMultiplier);
                                }
                            }
                        }
                    }
                }
            }

            //render flot charts
            fitCore.mealPlanner.renderPieChart($("#fit-placeholder"), fitCore.mealPlanner.thisWeeksStats, 60);
        },

        clear_calendar_cell: function (whenid, column) {

            // If there is an 'when' entry (lunch, breakfast etc)
            if ($("#fit-active-cal #fit-nutrition-cal #fit-whenid-" + whenid).size() > 0) {

                // Get table row and cell and clear it
                var tr = $("#fit-active-cal #fit-nutrition-cal #fit-whenid-" + whenid);
                var cell = tr.children().eq(column + 1);
                cell.html('');
                //console.log('cleared',column);

            } else {
                // No row, should never happen
            }

        },

        populate_calendar_day: function (week_map, dateKey, whenId, column) {

            // Delete existing cell contents
            // Redisplay from meal map

            if (week_map.containsKey(dateKey)) {
                var when_map = week_map.get(dateKey);

                for (var j = 0; j < when_map.keys().length; j++) {
                    var whenId = when_map.keys()[j];

                    var meal_map = when_map.get(whenId);

                    for (var k = 0; k < meal_map.keys().length; k++) {
                        var mealid = meal_map.keys()[k];

                        var obj = meal_map.get(mealid).get(0);
                        //console.log('placing', column);
                        fitCore.mealPlanner.populate_calendar_cell(whenId, column, obj);

                        //for (var idx = 0; idx < meal_map.get(mealid).size() ; idx++) {
                        //    var fd = meal_map.get(mealid).get(idx).food;
                        //    if (fd == null)
                        //        continue;

                        //    cals_total += fd.Calcium;
                        //    fat_total += fd.EnergKcal;
                        //    satfa_total += fd.FASat;
                        //    fiber_total += fd.Fiber;
                        //    carbs_total += fd.Carbohydrate;
                        //    protein_total += fd.Protein;
                        //    salt_total += fd.Water;
                        //}
                    }
                }
            }


        },

        populate_calendar_cell: function (whenid, column, obj) {
            var tr;

            if ($("#fit-active-cal #fit-nutrition-cal #fit-whenid-" + whenid).size() > 0) {
                tr = $("#fit-active-cal #fit-nutrition-cal #fit-whenid-" + whenid);
            } else {
                tr = fitCore.mealPlanner.addCalendarRow(whenid);
            }

            var cell = tr.children().eq(column + 1);
            var con = $('<p class="ui-state-default ui-corner-all ui-helper-clearfix fit-meal"></p>');

            con.data("mealid", obj.meal_id);
            con.data("whenid", obj.when);
            con.data("desc", obj.meal_description);
            con.data("eatdate", obj.eatdate);
            con.data("mealname", obj.meal_name);
            con.data("foodeatenid", obj.foodEatenId);
            con.data("dataonclient", true);

            con.append(obj.meal_name);  //  .wrap_text(obj.meal_name, 20));
            con.appendTo(cell);
        },

        wrap_text: function (input, num) {
            if (input == null || input == undefined)
                return "";
            input = input.replace(/<[^>]*>/g, "");

            if (input.length > num) {
                input = input.substring(0, num) + "...";
                return input;
            } else {
                return input;
            }
        },

        drawTab: function (obj, radius, bgcolor, text) {
            if (!obj || !obj.getContext) {
                return;
            }
            var ctx = obj.getContext('2d');

            ctx.strokeStyle = bgcolor;
            ctx.fillStyle = bgcolor;
            ctx.globalAlpha = 1;

            var ht = obj.height;
            var wd = obj.width;

            ctx.beginPath();
            ctx.moveTo(radius, 0);
            ctx.lineTo(wd, 0);
            ctx.lineTo(wd, ht);
            ctx.lineTo(radius, ht);
            ctx.lineTo(0, ht - radius);
            ctx.lineTo(0, radius);
            ctx.closePath();
            ctx.fill();
            ctx.stroke();

            ctx.beginPath();
            ctx.arc(radius, ht - radius, radius, 0.5 * Math.PI, 1 * Math.PI);
            ctx.closePath();
            ctx.fill();
            ctx.stroke();

            ctx.beginPath();
            ctx.arc(radius, radius, radius, 1 * Math.PI, 1.5 * Math.PI);
            ctx.closePath();
            ctx.fill();
            ctx.stroke();


            ctx.translate(0, 0);
            ctx.rotate(-Math.PI / 2);
            ctx.fillStyle = "white";
            ctx.font = "20px Arial";
            ctx.fillText(text, -ht + (ht - text.length * 10) / 2, wd - 12);
        },

        getColor: function (whenid) {
            return colorArr[whenidArr.indexOf(whenid) % colorArr.length];
        },

        getWhenDesc: function (id) {
            switch (id) {
                case 1:
                    return "Breakfast";
                case 2:
                    return "Morning Snack";
                case 3:
                    return "Lunch";
                case 4:
                    return "Afternoon Snack";
                case 5:
                    return "Dinner";
                case 6:
                    return "Evening Snack";
                case 7:
                    return "Midnight Snack";
                case 8:
                    return "After workout";
                case 9:
                    return "Before workout";
                case 22:
                    return "1am";
                case 23:
                    return "2am";
                case 24:
                    return "3am";
                case 25:
                    return "4am";
                case 26:
                    return "5am";
                case 27:
                    return "6am";
                case 28:
                    return "7am";
                case 29:
                    return "8am";
                case 31:
                    return "9am";
                case 32:
                    return "10am";
                case 33:
                    return "11am";
                case 34:
                    return "12am";
                case 35:
                    return "1pm";
                case 36:
                    return "2pm";
                case 37:
                    return "3pm";
                case 38:
                    return "4pm";
                case 39:
                    return "5pm";
                case 40:
                    return "6pm";
                case 41:
                    return "7pm";
                case 42:
                    return "8pm";
                case 43:
                    return "9pm";
                case 44:
                    return "10pm";
                case 45:
                    return "11pm";
                case 46:
                    return "12pm";
                default:
                    return "";
            }
        },

        popup_window2: function (width, selector) {

            $(selector).dialog({
                dialogClass: 'fit-dialogtitle',
                resizable: false,
                width: 'auto',
                modal: true,
                create: function (event, ui) {
                    $('.ui-dialog').wrap('<div class="fit-ui" />');
                },
                open: function (event, ui) {
                    $('.ui-widget-overlay').wrap('<div class="fit-ui" />');
                },
                close: function (event, ui) {

                    //$("#food-log").empty();

                    $(".fit-ui").filter(function () {
                        if ($(this).text() == "") {
                            return true;
                        }
                        return false;
                    }).remove();
                }
            }).dialog('open');

            return;

        },

        popup_window: function (width, inner_html) {

            $("#fit-shadow").show();

            var frame = $("#fit-popup-window").children().eq(1);
            var ovrly = $("#fit-popup-window").children().eq(0).children().eq(1);

            frame.width(width);
            ovrly.width(width + 22);

            $("#fit-popup-window .fit-ctnt").html(inner_html);

            var fn = arguments[2] ? arguments[2] : null;
            if (fn != null) {
                fn();
            }


            $("#fit-popup-window").show(5, function () {

                ovrly.height(frame.height() + 22);

                //frame.css("left", $(document).scrollLeft() + ($(window).width() - frame.width()) / 2 - ui_offset_x);
                //ovrly.css("left", $(document).scrollLeft() + ($(window).width() - frame.width()) / 2 - ui_offset_x);

                var halfOfWindow = ($(document).scrollLeft() + ($(window).width())) / 2;
                var halfOfPopup = frame.width() / 2;
                var popupOffset = halfOfWindow - halfOfPopup;

                //                console.log(halfOfWindow, halfOfPopup, popupOffset);

                frame.css("left", popupOffset);
                ovrly.css("left", popupOffset);

                /*console.log($(document).scrollLeft());
                console.log($(window).width());
                console.log(frame.width());
                console.log(ui_offset_x);*/

                var tp = ($(document).scrollTop() + ($(window).height() - frame.height()) / 2) < 0 ? 0 : $(document).scrollTop() + ($(window).height() - frame.height()) / 2;
                frame.css("top", tp + 10 - ui_offset_y);
                ovrly.css("top", tp + 10 - ui_offset_y);
                $("#fit-shadow").hide();

                $("#fit-bg-shadow").show();
            });
        },

        fitGetDiaryObject: function (currentLogId, currentdiarydate) {

            var personalLog = new Object();
            
            personalLog.LogId = currentLogId;
            personalLog.LogDate = currentdiarydate; // fitCore.mealPlanner.formatDate(currentdiarydate, 4);
            personalLog.PersonID = 0;
            personalLog.LogNotes = $("#fit-diary-text").val();
            personalLog.ProfessionalNotes = $("#fit-diary-professional").val();
            personalLog.Weight = fitCore.CORE.NullIfBlank($("#fit_jnl_weight").val());
            personalLog.RestingPulse = fitCore.CORE.NullIfBlank($("#fit_jnl_pulse").val());
            personalLog.Systolic = fitCore.CORE.NullIfBlank($("#fit_jnl_systolic").val());
            personalLog.Diastolic = fitCore.CORE.NullIfBlank($("#fit_jnl_diastolic").val());
            personalLog.SizeArms = fitCore.CORE.NullIfBlank($("#fit_jnl_arms").val());
            personalLog.SizeChest = fitCore.CORE.NullIfBlank($("#fit_jnl_chest").val());
            personalLog.SizeWaist = fitCore.CORE.NullIfBlank($("#fit_jnl_waist").val());
            personalLog.SizeThighs = fitCore.CORE.NullIfBlank($("#fit_jnl_thighs").val());
            personalLog.SizeHips = fitCore.CORE.NullIfBlank($("#fit_jnl_hips").val());
            personalLog.HoursSleep = fitCore.CORE.NullIfBlank($("#fit_jnl_sleep").val());
            personalLog.BodyFatPercentage = fitCore.CORE.NullIfBlank($("#fit_jnl_bodyfat").val());
            
            return personalLog;

        },

        fitUpdateDiaryObject: function (personalLog, currentLogId, currentdiarydate) {

            personalLog.LogId = currentLogId;
            personalLog.LogDate = fitCore.mealPlanner.formatDate(currentdiarydate, 4);
            personalLog.PersonID = 0;
            personalLog.LogNotes = $("#fit-diary-text").val();
            personalLog.ProfessionalNotes = $("#fit-diary-professional").val();
            personalLog.Weight = fitCore.CORE.NullIfBlank($("#fit_jnl_weight").val());
            personalLog.RestingPulse = fitCore.CORE.NullIfBlank($("#fit_jnl_pulse").val());
            personalLog.Systolic = fitCore.CORE.NullIfBlank($("#fit_jnl_systolic").val());
            personalLog.Diastolic = fitCore.CORE.NullIfBlank($("#fit_jnl_diastolic").val());
            personalLog.SizeArms = fitCore.CORE.NullIfBlank($("#fit_jnl_arms").val());
            personalLog.SizeChest = fitCore.CORE.NullIfBlank($("#fit_jnl_chest").val());
            personalLog.SizeWaist = fitCore.CORE.NullIfBlank($("#fit_jnl_waist").val());
            personalLog.SizeThighs = fitCore.CORE.NullIfBlank($("#fit_jnl_thighs").val());
            personalLog.SizeHips = fitCore.CORE.NullIfBlank($("#fit_jnl_hips").val());
            personalLog.HoursSleep = fitCore.CORE.NullIfBlank($("#fit_jnl_sleep").val());
            personalLog.BodyFatPercentage = fitCore.CORE.NullIfBlank($("#fit_jnl_bodyfat").val());
            
            return personalLog;

        },

        fitMealplannerSaveDiaryEntrySuccess: function (data) {

            if (data.MessageId == 0) {
                alert(data.Message);
            }
            else {
                // Store current logId
                currentdiary.data("currentLogId", data.LogId);
                alert(data.Message);
            }

            $("#fit-shadow").hide();

            //window.location = "demo.html?ctx=" + START_MONDAY;

            $("#fit-placeholder").hide();

            // Create new calendar display from template
            fitCore.mealPlanner.reload_calendar();

            /*// Create new calendar display from template
            var smp = $("#fit-sample").clone().css("display", "block").attr("id", "tmp");
            smp.insertBefore($("#fit-active-cal"));

            // Get current viewing date
            var dat = new Date($("#fit-active-cal").data("ttl"));
            fitCore.mealPlanner.calculate_calendar(dat, "tmp");

            // Remove old calendar, render the new
            $("#fit-active-cal").remove();
            smp.attr("id", "fit-active-cal");
            fitCore.mealPlanner.render_calendar();
            $("#fit-bg-shadow").hide();*/
        },

        fitSaveDiaryEntry: function (funcOnSuccess, logId, diaryDate, email, sessionGuid) {
            $("#fit-shadow").show();

            var personalLog = fitCore.mealPlanner.fitGetDiaryObject(logId, diaryDate);

            //Convert to json
            var json = $.toJSON(personalLog);

            //$.getJSON(API_PATH + '/PersonalLog/SaveLog?email=' + email + '&sessionGuid=' + sessionGuid + '&jsoncallback=?', json, funcOnSuccess);

            // Save on server
            $.ajax({
                url: API_PATH + '/PersonalLog/SaveLog?jsoncallback=?',
                type: 'POST',
                dataType: 'jsonp',
                data: { jsonPersonalLog: json, email: email, SessionGuid: sessionGuid },
                success: function (data) {

                    // Sucessfully added to database
                    if (data.MessageId == 0) {

                    }

                    // TODO: proper updating icons etc
                    location.reload();

                    $("#fit-shadow").hide();
                }
            });

            /*$.ajax({
                url: API_PATH + '/PersonalLog/SaveLog?email=' + email + '&sessionGuid=' + sessionGuid + '&jsoncallback=?',
                type: 'POST',
                dataType: 'jsonp',
                data: json,
                contentType: 'application/json; charset=utf-8',
                success: funcOnSuccess,
                error: function (data) {
                    alert("Error in ajax returned data. Please try again.");
                    $("#fit-shadow").hide();
                }
            });*/
        },



        GetDiaryNotesForWeek: function () {
            // Save on server
            $.ajax({
                url: API_PATH + '/PersonalLog/GetDiaryNotes?jsoncallback=?',
                type: 'POST',
                dataType: 'jsonp',
                data: {
                    dateStarting: fitCore.mealPlanner.formatDate(START_MONDAY, 4),
                    email: email,
                    SessionGuid: sessionGuid
                },
                success: function (data) {
                    // Sucessfully added to database
                    if (data.MessageId == 0) {
                        var week_diary_map = new Map();
                        for (var k = 0; k < data.Logs.length; k++) {
                            var obj = data.Logs[k];
                            var dt = new Date(parseInt(obj.LogDate.substring(obj.LogDate.indexOf("(") + 1, obj.LogDate.lastIndexOf(")"))));
                            var key = dt.getFullYear() + "-" + (dt.getMonth() + 1) + "-" + dt.getDate();
                            week_diary_map.put(key, obj);
                        }

                        for (var i = 0; i < 7; i++) {
                            var dt = parseInt(START_MONDAY) + i * 86400000;
                            var key = new Date(parseInt(dt));
                            key = key.getFullYear() + "-" + (key.getMonth() + 1) + "-" + key.getDate();

                            var img = $("#fit-active-cal #fit-nutrition-cal .fit-diary-icon").eq(i);
                            if (week_diary_map.containsKey(key)) {
                                var obj = week_diary_map.get(key);
                                var ori_pic = img.attr("src");
                                var new_pic = ori_pic.substring(0, ori_pic.lastIndexOf("/")) + "/note.gif";
                                img.attr("src", new_pic);

                                img.data("currentdiarydate", dt);
                                img.data("currentLogId", obj.LogId);
                                img.data("diary", obj);
                            } else {
                                img.data("currentdiarydate", dt);
                                img.data("currentLogId", 0);
                                img.data("diary", null);
                            }


                        }
                    }
                }
            });
        },

        AddNewWeeklyPlan: function (plan_name, plan_desc) {
            $("#fit-shadow").show();

            var newWeeklyPlan = new Object();
            newWeeklyPlan.WeeklyPlanName = plan_name; // Max 40 characters
            newWeeklyPlan.WeeklyPlanDescription = plan_desc; // max 1800 characters

            // Create a list of meals that were eaten this week
            newWeeklyPlan.WeeklyPlanMeals = new Array();
            //start from 1 to ignore the first row of date title
            for (var i = 1; i < $("#fit-active-cal #fit-nutrition-cal tr").size() ; i++) {
                var row = $("#fit-active-cal #fit-nutrition-cal tr").eq(i);

                //start from 1 to ignore the first column of when description
                for (var col = 1; col < row.children().size() ; col++) {
                    var cell = row.children().eq(col);

                    for (var j = 0; j < cell.children().size() ; j++) {
                        var meal = cell.children().eq(j);

                        newWeeklyPlan.WeeklyPlanMeals.push(new fitCore.mealPlanner.NewWeeklyPlanMeal(col - 1, meal.data("whenid"), meal.data("mealid")));
                    }
                }
            }

            //Convert to json
            var json = $.toJSON(newWeeklyPlan);

            // Save on server
            $.ajax({
                url: API_PATH + '/Meals/CreateWeeklyPlan?jsoncallback=?',
                type: 'POST',
                dataType: 'jsonp',
                data: { jsonWeeklyPlan: json, email: email, SessionGuid: sessionGuid },
                success: function (data) {
                    // Sucessfully added to database
                    if (data.MessageId == 0) {
                        alert("Add new weekly plan successfully!");
                    }

                    $("#fit-shadow").hide();
                }
            });
        },

        // Create new weekly plan meal entry to attach to the weekly plan
        NewWeeklyPlanMeal: function (dayId, whenEatenId, mealId) {
            this.DayId = dayId;                 // What day this meal eaten (0 = Monday, 1 = Tuesday, 2 = Wednesday...)
            this.WhenEatenId = whenEatenId;     // What category (breakfast, lunch, dinner..)
            this.MealId = mealId;               // The mealId eaten
        },

        formatDate: function (dt, format) {
            switch (format) {
                case 1:   //Date object
                    return new Date(parseInt(dt));
                case 2:		//time stamp
                    return parseInt(dt);
                case 3:		//format string e.g. 2012-1-31
                    return new Date(parseInt(dt)).getFullYear() + "-" + (new Date(parseInt(dt)).getMonth() + 1) + "-" + new Date(parseInt(dt)).getDate();
                case 4:   //format string e.g. 31-Jan-12
                    return new Date(parseInt(dt)).getDate() + "-" + mon_short_arr[new Date(parseInt(dt)).getMonth()] + "-" + (new Date(parseInt(dt)).getFullYear() + "").substring(2, 4);
                default:  //default to timestamp
                    return parseInt(dt);
            }
        },

        //retrieve the date of particular cell html element in the calendar
        getDate: function (cell, format) {
            var dt = parseInt(START_MONDAY) + (cell.parent().children().index(cell) - 1) * 86400000;
            return fitCore.mealPlanner.formatDate(dt, format);
        },

        //retrieve the when id of particular cell html element in the calendar
        getWhen: function (cell) {
            return cell.parent().data("whenid");
        },

        //renderPieChart: function (target, cals_total, fat_total, satfa_total, fiber_total, carbs_total, protein_total, salt_total, chartsize)
        renderPieChart: function (target, stats, chartsize) {

            // If no information, set default to 20.  TODO: consider hiding graph instead
            if (window.console) {
                console.log('renderstats', stats);
            }

            if (stats.cals_total == 0 && stats.fat_total == 0 && stats.satfa_total == 0 && stats.fiber_total == 0 && stats.carbs_total == 0 && stats.protein_total == 0 && stats.sugars_total == 0)
                stats.cals_total = stats.fat_total = stats.satfa_total = stats.fiber_total = stats.carbs_total = stats.protein_total = stats.sugars_total = 20;

            target.show();
            target.html("");
            target.css('display: block');

            // Graph shows 
            // -- saturated fats, other fats (which is total fats less sat fat)
            // -- sugars
            // -- other carbs that are not sugars
            // -- protein
            var r = Raphael(target.attr("id")); // 210 150
            pie = r.piechart(230,
                                           150,
                                           chartsize,
                                           [(stats.fat_total - stats.satfa_total), stats.satfa_total, (stats.carbs_total - stats.sugars_total), stats.protein_total, stats.sugars_total],
                                           {
                                               legend: [
                                                            "%% - unsaturated fats",
                                                            "%% - saturated fat",
                                                            "%% - carbs",
                                                            "%% - protein",
                                                            "%% - carbs from sugars"
                                               ],
                                               legendpos: "west"
                                           });

            pie.hover(function () {
                this.sector.stop();
                this.sector.scale(1.05, 1.05, this.cx, this.cy);

                if (this.label) {
                    this.label[0].stop();
                    this.label[0].attr({ r: 7.5 });
                    this.label[1].attr({ "font-weight": 800 });
                }
            }, function () {
                this.sector.animate({ transform: 's1 1 ' + this.cx + ' ' + this.cy }, 500, "bounce");

                if (this.label) {
                    this.label[0].animate({ r: 5 }, 500, "bounce");
                    this.label[1].attr({ "font-weight": 400 });
                }
            });

            delete r;
        },

        fillTableWithText: function (foodn, cals, fat, satfa, fiber, carbs, protein, sugar, dataServingId) {
            //return "<li class='foodn'><button id='fit-saveas-meal' data-wheneatenid='" + dataServingId + "' class='ui-button-success'>Save as meal</button>" + foodn + "</li>" +

            $("#fit-saveas-meal").data('wheneatenid', dataServingId);

            return "<li class='foodn'>" + foodn + "</li>" +
                                "<li class='cals'>" + (isNaN(cals) ? cals : fitCore.mealPlanner.fixDecimal(cals, 1)) + "</li>" +
                                "<li class='fat'>" + (isNaN(fat) ? fat : fitCore.mealPlanner.fixDecimal(fat, 2)) + "</li>" +
                                "<li class='satfa'>" + (isNaN(satfa) ? satfa : fitCore.mealPlanner.fixDecimal(satfa, 2)) + "</li>" +
                                "<li class='fiber'>" + (isNaN(fiber) ? fiber : fitCore.mealPlanner.fixDecimal(fiber, 2)) + "</li>" +
                                "<li class='carbs'>" + (isNaN(carbs) ? carbs : fitCore.mealPlanner.fixDecimal(carbs, 1)) + "</li>" +
                                "<li class='protein'>" + (isNaN(protein) ? protein : fitCore.mealPlanner.fixDecimal(protein, 2)) + "</li>" +
                                "<li class='sugars'>" + (isNaN(sugar) ? sugar : fitCore.mealPlanner.fixDecimal(sugar, 1)) + "</li>";
        },

        outputValue: function (value, nutrientMultiplier, decimalPlaces) {
            if (value == undefined) {
                return "&nbsp;";
            }
            else if (isNaN(value)) {
                return value;
            }
            else {
                return fitCore.mealPlanner.fixDecimal(value * nutrientMultiplier, decimalPlaces);
            }
        },

        fillTable: function (foodn, cals, fat, satfa, fiber, carbs, protein, sugar, servingCount, servingsDesc, nutrientMultiplier) {

            //"<li class='foodn'>" + deleteButon + servings + " x " + desc + "</li>\n" +
            //"<li class='servings cals'>" + (energKcal * multiplier).toFixed(2).replace(/[.,]00$/, '') + "</li>\n" +
            //"<li class='servings fat'>" + (lipidTotal * multiplier).toFixed(2).replace(/[.,]00$/, '') + "</li>\n" +
            //+ servingsDesc +

            if (window.console) {
                console.log('table', foodn, cals, fat);
            }

            return "<li class='foodn'>" + servingCount + " x " + foodn + "</li>" +
                                "<li class='cals'>" + fitCore.mealPlanner.outputValue(cals, nutrientMultiplier, 1) + "</li>" +
                                "<li class='fat'>" + fitCore.mealPlanner.outputValue(fat, nutrientMultiplier, 2) + "</li>" +
                                "<li class='satfa'>" + fitCore.mealPlanner.outputValue(satfa, nutrientMultiplier, 2) + "</li>" +
                                "<li class='fiber'>" + fitCore.mealPlanner.outputValue(fiber, nutrientMultiplier, 2) + "</li>" +
                                "<li class='carbs'>" + fitCore.mealPlanner.outputValue(carbs, nutrientMultiplier, 1) + "</li>" +
                                "<li class='protein'>" + fitCore.mealPlanner.outputValue(protein, nutrientMultiplier, 2) + "</li>" +
                                "<li class='sugars'>" + fitCore.mealPlanner.outputValue(sugar, nutrientMultiplier, 1) + "</li>";
        },

        calculate_calendar: function (dat, target) {
            $("#fit-mon").html(mon_arr[dat.getMonth()]);
            $("#fit-yr").html(dat.getFullYear());
            var startMonday = dat.getTime();
            START_MONDAY = startMonday;

            //var key = new Date(parseInt(edate));
            //console.log(dat.getFullYear() + "-" + (dat.getMonth() + 1) + "-" + dat.getDate());
            //console.log(startMonday.getFullYear() + "-" + (startMonday.getMonth() + 1) + "-" + startMonday.getDate());
            //var firstDayNumber = new Date(MONDAY).getDate();

            //((new Date(MONDAY + value * 86400000)).getDate() - 1)
            $("#" + target + " .fit-1day").html(fitCore.mealPlanner.dateCalc(0, startMonday) + fitCore.mealPlanner.wrapPrefix(0, startMonday));
            $("#" + target + " .fit-2day").html(fitCore.mealPlanner.dateCalc(1, startMonday) + fitCore.mealPlanner.wrapPrefix(1, startMonday));
            $("#" + target + " .fit-3day").html(fitCore.mealPlanner.dateCalc(2, startMonday) + fitCore.mealPlanner.wrapPrefix(2, startMonday));
            $("#" + target + " .fit-4day").html(fitCore.mealPlanner.dateCalc(3, startMonday) + fitCore.mealPlanner.wrapPrefix(3, startMonday));
            $("#" + target + " .fit-5day").html(fitCore.mealPlanner.dateCalc(4, startMonday) + fitCore.mealPlanner.wrapPrefix(4, startMonday));
            $("#" + target + " .fit-6day").html(fitCore.mealPlanner.dateCalc(5, startMonday) + fitCore.mealPlanner.wrapPrefix(5, startMonday));
            $("#" + target + " .fit-7day").html(fitCore.mealPlanner.dateCalc(6, startMonday) + fitCore.mealPlanner.wrapPrefix(6, startMonday));

            /*
            console.log('date:', (new Date(START_MONDAY + 4 * 86400000)).getDate() - 1);
            console.log('date:', (new Date(START_MONDAY + 5* 86400000)).getDate() - 1);
            console.log('date:', (new Date(START_MONDAY +6 * 86400000)).getDate() - 1);

            $("#" + target + " .fit-1day").html(day_arr[(new Date(START_MONDAY)).getDate() - 1]);
            $("#" + target + " .fit-2day").html(day_arr[(new Date(START_MONDAY + 1 * 86400000)).getDate() - 1]);
            $("#" + target + " .fit-3day").html(day_arr[(new Date(START_MONDAY + 2 * 86400000)).getDate() - 1]);
            $("#" + target + " .fit-4day").html(day_arr[(new Date(START_MONDAY + 3 * 86400000)).getDate() - 1]);
            $("#" + target + " .fit-5day").html(day_arr[(new Date(START_MONDAY + 4 * 86400000)).getDate() - 1]);
            $("#" + target + " .fit-6day").html(day_arr[(new Date(START_MONDAY + 5 * 86400000)).getDate() - 1]);
            $("#" + target + " .fit-7day").html(day_arr[(new Date(START_MONDAY + 6 * 86400000)).getDate() - 1]);
            */
            $("#" + target).data("ttl", startMonday);
        },

        dateCalc: function (value, startMonday) {
            return ((new Date(startMonday + value * 86400000)).getDate());
        },

        wrapPrefix: function (value, startMonday) {
            //console.log(value, ((new Date(startMonday + (value * 86400000))).getDate()), ((new Date(startMonday + (value * 86400000))).getDate()) - 1, day_arr[((new Date(startMonday + (value * 86400000))).getDate()) - 1]);
            return "<sup class='fit-prefix'>" + day_arr[(new Date(startMonday + value * 86400000)).getDate() - 1] + "</sup>";
        },

        getDateString: function (dateToFormat) {
            return dateToFormat.getFullYear() + "-" + (dateToFormat.getMonth() + 1) + "-" + dateToFormat.getDate();
        },
        //key = key.getFullYear() + "-" + (key.getMonth() + 1) + "-" + key.getDate();

        jsonDateToDate: function (jsonDate) {
            // If date is not json format, assume it is already in our correct key format
            if (jsonDate.indexOf("(") == -1) {
                //console.log('date already formatted:' + jsonDate);
                return jsonDate;
            }

            // Build key for map from date
            var edate = jsonDate.substring(jsonDate.indexOf("(") + 1, jsonDate.lastIndexOf(")"));
            var key = new Date(parseInt(edate));
            key = key.getFullYear() + "-" + (key.getMonth() + 1) + "-" + key.getDate();

            return key;
        },

        getFoodItemObject: function (meal, dateEaten, whenEatenId, description, foodEaten, foodEatenId, servingCount, servingDesc, servingWeight, servingSizeId) {

            var obj = new Object();

            // Build key for map from date
            var key = fitCore.mealPlanner.jsonDateToDate(dateEaten);

            //console.log(edate);
            //console.log(key);

            // If no meal, group any individual foods or drinks
            if (meal == null) {
                //console.log('not meal', foodEaten);
                if (foodEaten.IsDrink) {
                    obj.meal_name = foodEaten.LongDesc; // "Drinks";
                    obj.meal_description = foodEaten.LongDesc; //"Drinks";
                    obj.meal_id = -2;
                } else {
                    obj.meal_name = foodEaten.LongDesc; // "Other meal";
                    obj.meal_description = foodEaten.LongDesc; // "Other meal";
                    obj.meal_id = -1;
                }
            } else {
                obj.meal_name = meal.MealName;
                obj.meal_description = meal.MealDescription;
                obj.meal_id = meal.MealId;
            }

            obj.servingSizeId = servingSizeId;
            obj.when = whenEatenId;
            obj.ttl = description == "" ? "undefined" : description;
            obj.eatdate = key; //edate; yyyy-mm-dd
            obj.food = foodEaten;
            obj.key = key;
            obj.foodEatenId = foodEatenId

            // Calculate multipler (all standard fields contain the nutrient factors per 100 grams, so the multipler is the relative portion size eaten)
            obj.nutrientMultiplier = (servingWeight / 100) * servingCount;
            obj.servingCount = servingCount;
            obj.servingDesc = servingDesc;
            obj.servingWeight = servingWeight;

            //console.log(obj.nutrientMultiplier, servingDesc, servingsCount, servingWeight);

            return obj;
        },



        /*
        Map:							date
        Map:					when		when
        Map:			meal		meal		meal		
        List:	food		food		food		food
        Object:
              meal_id
              meal_description
              when
              ttl
              eatdate  (timestamp)
              food     (Food object)
        */
        addFoodItemToMap: function (weekMap, foodItemEaten) {

            // If contains the date key
            if (weekMap.containsKey(foodItemEaten.key)) {
                var when_map = weekMap.get(foodItemEaten.key);

                // If it contains the category key
                if (when_map.containsKey(foodItemEaten.when)) {

                    // Get meals inside the category
                    var meal_map = when_map.get(foodItemEaten.when);

                    // If that meal is already added
                    if (meal_map.containsKey(foodItemEaten.meal_id)) {

                        var alteredFoodItemEaten = foodItemEaten;

                        if (foodItemEaten.meal_id == -2) {

                            alteredFoodItemEaten = meal_map.get(foodItemEaten.meal_id).get(0);
                            alteredFoodItemEaten.meal_name = "Drinks";
                            alteredFoodItemEaten.meal_description = "Drinks";

                            //console.log('adding drinks');

                        } else if (foodItemEaten.meal_id == -1) {

                            alteredFoodItemEaten = meal_map.get(foodItemEaten.meal_id).get(0);
                            alteredFoodItemEaten.meal_name = "Other meal";
                            alteredFoodItemEaten.meal_description = "Other meal";
                        }

                        // Just add the food item
                        var food_list = meal_map.get(foodItemEaten.meal_id);
                        food_list.add(foodItemEaten);

                        //console.log('children size', meal_map.get(foodItemEaten.meal_id).size());
                        alteredFoodItemEaten.childCount = meal_map.get(foodItemEaten.meal_id).size();

                        // Return the meal with the [potentially] altered description
                        return alteredFoodItemEaten;

                    } else {

                        //console.log('adding first item');

                        // Otherwise create new list of food items and append this one
                        var food_list = new ArrayList();
                        food_list.add(foodItemEaten);
                        meal_map.put(foodItemEaten.meal_id, food_list);
                    }
                } else {
                    var meal_map = new Map();
                    var food_list = new ArrayList();
                    food_list.add(foodItemEaten);
                    meal_map.put(foodItemEaten.meal_id, food_list);
                    when_map.put(foodItemEaten.when, meal_map);
                }
            } else {
                var when_map = new Map();
                var meal_map = new Map();
                var food_list = new ArrayList();
                food_list.add(foodItemEaten);
                meal_map.put(foodItemEaten.meal_id, food_list);
                when_map.put(foodItemEaten.when, meal_map);
                weekMap.put(foodItemEaten.key, when_map);
            }

            foodItemEaten.childCount = 1;
            return foodItemEaten;
        },


        // Load the meals into the calendar
        render_calendar: function () {
            $("#fit-w-l").show();
            //load the weekly data
            var week_url = API_PATH + "/Foods/GetFoodEatenByWeek?jsoncallback=?";
            week_map = new Map();
            var md = new Date(parseInt(START_MONDAY));

            $.getJSON(week_url, { 'email': email, 'date': md.getDate() + "-" + mon_short_arr[md.getMonth()] + "-" + (md.getFullYear() + "").substring(2, 4) }, function (obj) {
                var arr = obj.FoodEaten;

                //console.log(obj);
                for (var i = 0; i < arr.length; i++) {
                    var fe = arr[i];


                    /*
                    Map:							date
                    Map:					when		when
                    Map:			meal		meal		meal		
                    List:	foodeaten		foodeaten		foodeaten		foodeaten
                    Object:
                          meal_id
                          meal_description
                          when
                          ttl
                          eatdate  (timestamp)
                          food     (Food object)
                    */

                    //console.log(fe.DateEaten);

                    // Convert manually entered food into a format for map
                    if (fe.Food == null) {

                        // If manual entry did not supply calories
                        if (fe.Calories == null)
                            fe.Calories = 0;

                        fe.Food = new Object();
                        fe.Food.IsDrink = false;
                        fe.Food.LongDesc = fe.Description;
                        fe.Food.EnergKcal = fe.Calories;
                        fe.Food.IsManual = true;

                        if (window.console) {
                            console.log('manual', fe);
                        }
                    }

                    //                    console.log('getting', fe);

                    // Get meal object and group an individual food into a generic "Drinks" or "Other meal" if reqd
                    var mealEaten = null;

                    // Manual food entry has no serving size
                    if (fe.ServingSize == null) {
                        mealEaten = fitCore.mealPlanner.getFoodItemObject(fe.Meal, fe.DateEaten, fe.WhenEatenId, fe.Description, fe.Food, fe.FoodEatenId, fe.Servings, "", 0, 0);
                    }
                    else {
                        mealEaten = fitCore.mealPlanner.getFoodItemObject(fe.Meal, fe.DateEaten, fe.WhenEatenId, fe.Description, fe.Food, fe.FoodEatenId, fe.Servings, fe.ServingSize.ServingDesc, fe.ServingSize.ServingWeight, fe.ServingSize.ServingSizeId);
                    }

                    //console.log(mealEaten);

                    fitCore.mealPlanner.addFoodItemToMap(week_map, mealEaten);
                }

                //console.log('start', START_MONDAY);
                fitCore.mealPlanner.populate_current_calendar(START_MONDAY, week_map);

                $("#fit-w-l").hide();
            }
            );

            fitCore.mealPlanner.GetDiaryNotesForWeek();
        },

        render_food_table: function (list, when_id, meal_name) {
            //var cals_total = fat_total = satfa_total = fiber_total = carbs_total = protein_total = salt_total = 0;

            // Initialise object to store graph stats
            var graphStats = fitCore.mealPlanner.getNewStatsObject();

            //"<li class='foodn'>" + deleteButon + servings + " x " + desc + "</li>\n" +
            //"<li class='servings cals'>" + (energKcal * multiplier).toFixed(2).replace(/[.,]00$/, '') + "</li>\n" +
            //"<li class='servings fat'>" + (lipidTotal * multiplier).toFixed(2).replace(/[.,]00$/, '') + "</li>\n" +


            var ct =
                  //"<div style='height:30px;'><div class='ui-state-default ui-corner-all fit-cls' title='.ui-icon-closethick'><span class='ui-icon ui-icon-closethick'></span></div></div>" +
                  "<div id='food-log'><div id='log-" + when_id + "'>" +
                  "<ul class='table'>\n<li class='thead'>\n<ul class='food-item'>" +
                  fitCore.mealPlanner.fillTableWithText("<h3 class='fit-meal-header'>" + meal_name + "</h3>", "Cals (k)", "Fat", "Sat fat", "Fiber", "Carbs", "Protein", "Sugars", when_id) +
                  "</ul></li>" +
                  "<li class='tbody droptrue'><ul>";

            for (var j = 0; j < list.size() ; j++) {
                var eatenItem = list.get(j);
                var fd = eatenItem.food;

                if (fd == null)
                    continue;

                ct +=
                    "<li>" +
                        "<ul class='food-item'>" +
                            fitCore.mealPlanner.fillTable(fd.LongDesc, fd.EnergKcal, fd.LipidTotal, fd.FASat, fd.Fiber, fd.Carbohydrate, fd.Protein, fd.SugarTot, eatenItem.servingCount, eatenItem.servingDesc, eatenItem.nutrientMultiplier) +
                        "</ul>" +
                    "</li>";
                //console.log(fd, list.get(j));

                fitCore.mealPlanner.addStat(fd, graphStats, eatenItem.servingCount, eatenItem.nutrientMultiplier);

                //cals_total += fd.Calcium;
                //fat_total += fd.EnergKcal;
                //satfa_total += fd.FASat;
                //fiber_total += fd.Fiber;
                //carbs_total += fd.Carbohydrate;
                //protein_total += fd.Protein;
                //salt_total += fd.Water;
            }

            // TODO drill through chows cals and cals(K) but no salt?

            ct +=
                "</ul>\n</li>\n" +
                "<li class='tfoot'><ul class='food-item'>\n" +
                fitCore.mealPlanner.fillTableWithText("Totals", graphStats.cals_total, graphStats.fat_total, graphStats.satfa_total, graphStats.fiber_total, graphStats.carbs_total, graphStats.protein_total, graphStats.sugars_total, when_id) +
                "</ul></li>" +
                "</ul></li></div></div>";

            //ct += $("#fit-popup-lower").html();

            ct += "<div id='fit-popup-lower'><div><div id='fit-popup-lower-desc'></div><div id='fit-popup-lower-chart'></div></div></div>";
            //ct += "<div><div id='fit-popup-lower-desc'></div><div id='fit-popup-lower-chart'></div></div>";

            //alert($("#fit-popup-lower").html());

            //var total_object = new Object();
            //total_object.cals_total = cals_total;
            //total_object.fat_total = fat_total;
            //total_object.satfa_total = satfa_total;
            //total_object.fiber_total = fiber_total;
            //total_object.carbs_total = carbs_total;
            //total_object.protein_total = protein_total;
            //total_object.salt_total = salt_total;

            graphStats.return_html = ct;
            //total_object.return_html = ct;

            return graphStats;
        },

        addCalendarRow: function (whenid) {
            var tr = $('<tr id="fit-whenid-' + whenid + '">' +
                                    '<td class="fit-hd">' +
                                        '<canvas id="fit-canvas-' + whenid + '" width="40" height="160"></canvas>' +
                                    '</td>' +
                                    '<td class="fit-op"></td>' +
                                    '<td class="fit-op"></td>' +
                                    '<td class="fit-op"></td>' +
                                    '<td class="fit-op"></td>' +
                                    '<td class="fit-op"></td>' +
                                    '<td class="fit-op"></td>' +
                                    '<td class="fit-op"></td>' +
                                '</tr>');
            tr.data("whenid", whenid);

            var trlist = $("#fit-active-cal #fit-nutrition-cal tr");
            var addflag = false;
            for (var i = 1; i < trlist.size() ; i++) {
                var rw = trlist.eq(i);
                if (rw.data("whenid") < whenid) {
                    continue;
                } else {
                    rw.before(tr);
                    addflag = true;
                    break;
                }
            }

            if (!addflag)
                tr.appendTo($("#fit-active-cal #fit-nutrition-cal"))

            var whendesc = fitCore.mealPlanner.getWhenDesc(whenid);

            $("#fit-canvas-" + whenid)[0].height = whendesc.length * 11 < 110 ? 110 : whendesc.length * 11;
            fitCore.mealPlanner.drawTab($("#fit-canvas-" + whenid)[0], 20, fitCore.mealPlanner.getColor(whenid), whendesc);

            return tr;
        },

        getDiaryNotes: function (personalLog) {

            // Clear entries if none
            if (personalLog == null) {
                $("#fit-diary input[type='text']").val("");
                $("#fit-diary-text").val("");
                $("#fit-diary-professional").val("");
                return;
            }
            
            $("#fit-diary-professional").val(personalLog.ProfessionalNotes);
            $("#fit-diary-text").val(personalLog.LogNotes);
            $("#fit_jnl_weight").val(personalLog.Weight);
            $("#fit_jnl_pulse").val(personalLog.RestingPulse);
            $("#fit_jnl_systolic").val(personalLog.Systolic);
            $("#fit_jnl_diastolic").val(personalLog.Diastolic);
            $("#fit_jnl_arms").val(personalLog.SizeArms);
            $("#fit_jnl_chest").val(personalLog.SizeChest);
            $("#fit_jnl_waist").val(personalLog.SizeWaist);
            $("#fit_jnl_thighs").val(personalLog.SizeThighs);
            $("#fit_jnl_hips").val(personalLog.SizeHips);
            $("#fit_jnl_sleep").val(personalLog.HoursSleep);
            $("#fit_jnl_bodyfat").val(personalLog.BodyFatPercentage);
        },

        fixDecimal: function (num, dec) {

            if (num == undefined) {
                return "?";
            }

            var result = parseFloat(Math.round(num * 100) / 100).toFixed(dec).toString();

            if (num == 0)
                return "0";
            else
                return result;

            // Not sure why this was used
            /*
            for (var i = 0; i <= dec; i++) {
                var c = result.charAt(result.length - 1);
                if (c == "0" || c == ".") {
                    result = result.substring(0, result.length - 1);
                } else {
                    break;
                }
            }

            return result;*/
        },

        getQueryStr: function (str) {
            var LocString = String(window.document.location.href);
            var rs = new RegExp("(^|)" + str + "=([^\&]*)(\&|$)", "gi").exec(LocString), tmp;
            if (tmp = rs) {
                return tmp[2];
            }
            return "";
        },

        render_item: function () {
            if (navigator.userAgent.match(/iPad/i) != null) {
                $(".fit-itm").width(182);
            }
        },

        AddNewBasicMeal: function (name, desc, whenid, ispublic) {

            var url = API_PATH + "/Foods/SaveMealAndFood?jsoncallback=?";
            //var url = "http://localhost:51827/Foods/SaveMealAndFood?jsoncallback=?";

            /* When Eaten Id's & descriptions - these can be hardcoded into the HTML has they won't change
            1  Breakfast                  	    2  Morning Snack                	    3  Lunch                        4  Afternoon Snack           	    5  Dinner                     	    6  Evening Snack                	    7  Midnight Snack                   	    8  After workout
            9  Before workout              	    22 1am                          	    23 2am                          24 3am                       	    25 4am                      	    26 5am
            27 6am                      	    28 7am                                  29 8am                  	    31 9am                      	    32 10am                     	    33 11am
            34 12am                     	    35 1pm                          	    36 2pm                  	    37 3pm                      	    38 4pm                      	    39 5pm
            40 6pm                      	    41 7pm                          	    42 8pm                  	    43 9pm                      	    44 10pm                     	    45 11pm
            46 12pm                             */

            var newMealObject = new Object();
            newMealObject.MealName = name; // Max 40 characters
            newMealObject.MealDescription = desc; // max 1800 characters
            newMealObject.WhenEatenId = whenid;      // the default time of day this meal might be eaten, see table of id's and descriptions
            newMealObject.IsPublic = ispublic;      // true if the user is willing to share this with others.

            // Create an array of food id's that are associated with this meal
            newMealObject.MealFoods = new Array();

            // Convert to json
            var json = $.toJSON(newMealObject);
            var jsonFood = $.toJSON(new fitCore.mealPlanner.NewMealFood());

            $("#fit-new-meal-wait").show();

            // Save on server
            $.ajax({
                url: url,
                type: 'GET',
                dataType: 'json',
                data: { jsonMeal: json, jsonFood: jsonFood, email: email, SessionGuid: sessionGuid },
                contentType: 'application/json; charset=utf-8',
                success: function (data) {

                    $("#fit-new-meal-wait").hide();
                    // get the result to check server updated OK
                    alert(data.Message)

                    if (data.MessageId != 0) {
                        alert('Please try again or let us know');
                    }
                    else {

                        // Reload the 'all meals' panel
                        fitCore.mealPlanner.loadAllMeals();

                    }

                    //$("#fit-shadow").hide();
                    $("#fit-new-meal").dialog("close");

                    // Fore reloading all panels
                    //location.reload();

                },
                error: function (ajaxContext) {
                    alert('An error occured trying to save - please try again or let us know');
                    $("#fit-new-meal-wait").show();
                }
            });
        },

        // Create new food entry to attach to meal. 
        /*energKcal, carbohydrate, cholestrl, fiber, lipidTotal, sodium, protein, sugarTot*/
        NewMealFood: function () {

            this.EnergKcal = $("#fit-newmeal-cal").val();
            this.Carbohydrate = $("#fit-newmeal-carb").val();
            this.Cholestrl = $("#fit-newmeal-chol").val();
            this.Fiber = $("#fit-newmeal-fiber").val();
            this.LipidTotal = $("#fit-newmeal-totalfat").val();
            this.FASat = $("#fit-newmeal-fasat").val();
            this.Sodium = $("#fit-newmeal-salt").val();
            this.Protein = $("#fit-newmeal-protein").val();
            this.SugarTot = $("#fit-newmeal-sugars").val();

            // Ensure it is not null if not value entered
            if (this.EnergKcal == "") {
                this.EnergKcal = 0;
            }

            //console.log(this);
            //this.FoodId = -1;

            /*this.EnergKcal = energKcal;
            this.Carbohydrate = carbohydrate;
            this.Cholestrl = cholestrl;
            this.Fiber = fiber;
            this.LipidTotal = lipidTotal;
            this.Sodium = sodium;
            this.Protein = protein;
            this.SugarTot = sugarTot;
            this.FoodId = -1;*/
        }

    };









    /***************************************************************************************************
    *
    *
    *
    *                                           FITNESS PACE CONVERTER
    *
    *
    *
    *
    ****************************************************************************************************/
    var fitConvert = function () {

        this.start = function () {

            $('#fit_paceslider').slider({
                range: 'min',
                value: 10,
                min: 1,
                max: 150,
                slide: function (event, ui) {
                    SetSpeed(ui.value);
                }
            });


            function SetSpeed(speed) {
                var dSpeed = speed / 5;

                document.getElementById('tbKMPerHour').value = (dSpeed).toFixed(2);
                document.getElementById('tbMetersPerSec').value = (dSpeed * 1000 / 60 / 60).toFixed(2);
                document.getElementById('tbMilePerHour').value = (dSpeed / 1.61).toFixed(2);
                document.getElementById('tbYardsPerSec').value = ((dSpeed * 1000 / 60 / 60) / 0.9144).toFixed(2);
                document.getElementById('tbPacePerMile').value = (60 / (dSpeed / 1.61)).toFixed(2) + ' (' + GetPace(dSpeed / 1.61) + ')';
                document.getElementById('tbPacePerKM').value = (60 / dSpeed).toFixed(2) + ' (' + GetPace(dSpeed) + ')';

                document.getElementById('tbHundred').value = GetTime(dSpeed, 0.1);
                document.getElementById('tbOne').value = GetTime(dSpeed, 1);
                document.getElementById('tbFive').value = GetTime(dSpeed, 5);
                document.getElementById('tbTen').value = GetTime(dSpeed, 10);
                document.getElementById('tbHalf').value = GetTime(dSpeed, 21);
                document.getElementById('tbFull').value = GetTime(dSpeed, 42);
            }

            function GetPace(dKmPace) {
                var dMins = Math.floor((60 / (dKmPace))).toFixed(0);
                var dSecs = (((60 / (dKmPace)) % 1) * 60).toFixed(0);
                var sSecs = dSecs.toString();

                if (sSecs.length == 1)
                { sSecs = '0' + sSecs; }

                return dMins + ':' + sSecs;
            }

            function GetTime(dKmPace, dDistance) {
                var dHour = Math.floor(dDistance / dKmPace);
                var dMins = (((dDistance / dKmPace) % 1) * 60).toFixed(0);
                var dSecs = (((((dDistance / dKmPace) % 1) * 60) % 1) * 60).toFixed(0);

                var sMins = dMins.toString();
                var sSecs = dSecs.toString();

                if (sMins.length == 1)
                { sMins = '0' + sMins; }

                if (sSecs.length == 1)
                { sSecs = '0' + sSecs; }

                return dHour + ':' + sMins + ':' + sSecs;
            }


        }
    }

    fitCore.fitConvert = new fitConvert();








    /***************************************************************************************************
    *
    *
    *
    *                                           FITNESS CALORIE/BMI
    *
    *
    *
    *
    ****************************************************************************************************/
    var fitCalorie = function () {

        var x = 0, y = 0;
        var callOutText = "";
        var callOutX = 0;
        var callOutY = 0;
        var current = 1;
        //callout Handler
        $callout = false;
        var isMetric = true;

        var height;
        var weight;
        var age;
        var weeklyChange;
        var activity;

        this.start = function () {

            $callout = $("#cloudOne");

            $("#metric, #imperial").change(function () {

            });

            $("#metric, #imperial").click(function () {
                if ($(this).attr('id') == 'metric') {
                    fitSwitchToMetric();
                }
                else {
                    fitSwitchToImperial();
                }
            });

            $("[name='sex']").change(function () {
                calcBMIInImperialOrMetric(false);
            });

            $("#sliderHeight").slider({
                range: "min",
                value: 149,
                min: 149,
                max: 200,
                step: 1,
                create: function (event, ui) {
                    height = $("#sliderHeight").slider("option", 'value');
                    showValuesOfSlider($("#sliderHeight"), getHeight());
                },
                slide: function (event, ui) {
                    height = ui.value;
                    showValuesOfSlider($("#sliderHeight"), ui.value);
                },
                stop: function (event, ui) {
                    height = ui.value;
                    showValuesOfSlider($("#sliderHeight"), ui.value);
                    calcBMIInImperialOrMetric(true);
                }
            });

            $("#sliderWeight").slider({
                range: "min",
                value: 40,
                min: 40,
                max: 160,
                create: function (event, ui) {
                    weight = $("#sliderWeight").slider('value');
                    showValuesOfSlider($("#sliderWeight"), getWeight());
                },
                slide: function (event, ui) {
                    weight = ui.value;
                    showValuesOfSlider($("#sliderWeight"), ui.value);
                },
                stop: function (event, ui) {
                    weight = ui.value;
                    showValuesOfSlider($("#sliderWeight"), ui.value);
                    calcBMIInImperialOrMetric(true);
                }
            });

            $("#sliderAge").slider({
                range: "min",
                value: 18,
                min: 18,
                max: 100,
                create: function (event, ui) {
                    age = $("#sliderAge").slider('value');
                    $("#amountAge").html('Age  (' + getAge() + ' years old)');
                },
                slide: function (event, ui) {
                    age = ui.value;
                    $("#amountAge").html('Age  (' + ui.value + ' years old)');
                    calcBMIInImperialOrMetric(false);
                }
            });

            $("#sliderWeeklyChange").slider({
                range: "min",
                value: 0,
                min: -100,
                max: 100,
                step: 2,
                create: function (event, ui) {
                    var value = $("#sliderWeeklyChange").slider('value');
                    fitCalcSetWeeklyChangeValue(value);
                    showValuesOfSlider($("#sliderWeeklyChange"), getWeeklyChange());
                },
                slide: function (event, ui) {
                    fitCalcSetWeeklyChangeValue(ui.value);
                    showValuesOfSlider($("#sliderWeeklyChange"), getWeeklyChange());
                }
            });

            // < 20 = -1, <40 >20 = -0.5, <60 >40 = 0, <80 >60 = 0.5, <100 >80 = 1
            $("#sliderActivity").slider({
                range: "min",
                value: 0,
                min: 0,
                max: 100,
                step: 1,
                create: function (event, ui) {
                    var value = activity = $("#sliderActivity").slider('value');
                    fitCalcActivityLabel(value);
                },
                slide: function (event, ui) {
                    activity = ui.value;
                    fitCalcActivityLabel(ui.value);
                    calcBMIInImperialOrMetric(false);
                }
            });

            // Style save button
            $("#fit_savecalories").button();

            // On save, set the users height, weight age etc in the database
            $("#fit_savecalories").click(function () {
                fitSaveGoals();
                return false;
            });

            // Load user default settings
            fitLoadGoals();

            $("#sliderHeight, #sliderWeight").children('a').removeAttr("href");
            calcBMIInImperialOrMetric(true);

        }

        function fitCalcSetWeeklyChangeValue(value) {

            if (value <= -60) {
                weeklyChange = -1;
            }
            else if (value <= -20) {
                weeklyChange = -0.5;
            }
            else if (value <= 20) {
                weeklyChange = 0.0;
            }
            else if (value <= 60) {
                weeklyChange = 0.5;
            }
            else if (value <= 100) {
                weeklyChange = 1.0;
            }
        }

        function fitCalcActivityLabel(value) {

            var text = "";

            if (value <= 20)
                text = "Little or no exercise";
            else if (value <= 40 && value > 20)
                text = "Light exercise  (1-3 days per week)";
            else if (value <= 60 && value > 40)
                text = "Moderate exercise  (3-5 days per week)";
            else if (value <= 80 && value > 60)
                text = "Heavy exercise  (6-7 days per week)";
            else if (value <= 100 && value > 80)
                text = "Very heavy exercise  (twice daily, heavy workouts)";

            $("#amountActivity").html(text);
        }

        function fitSwitchToMetric() {
            isMetric = true;
            $("#metric").hide();
            $("#imperial").show();
            alterValuesToMetric();
        }

        function fitSwitchToImperial() {
            isMetric = false
            $("#imperial").hide();
            $("#metric").show();
            alterValuesToImperial();
        }

        function fitLoadGoals() {

            var loadHeight = $("#fit_height").val();
            var loadWeight = $("#fit_weight").val();
            var loadAge = $("#fit_age").val();
            var loadActivity = $("#fit_exerciselevel").val();
            var loadWeeklyChange = $("#fit_weeklychange").val();
            var loadMeasurement = $("#fit_measurementsystem").val();
            var loadGender = $("#fit_gender").val();

            if (loadMeasurement == 0) {
                fitSwitchToMetric();
            }
            else {
                fitSwitchToImperial();
            }

            if (loadGender == "M") {
                $("#fitcal_male").attr('checked', true);
            }
            else {
                $("#fitcal_female").attr('checked', true);
            }

            $("#sliderHeight").slider('value', loadHeight);
            height = loadHeight;
            showValuesOfSlider($("#sliderHeight"), loadHeight);

            $("#sliderWeight").slider('value', loadWeight);
            weight = loadWeight;
            showValuesOfSlider($("#sliderWeight"), loadWeight);

            // Set age
            $("#sliderAge").slider('value', loadAge);
            age = loadAge;
            $("#amountAge").html('Age  (' + getAge() + ' years old)');

            // Set activity
            $("#sliderActivity").slider('value', loadActivity);
            fitCalcActivityLabel(loadActivity);

            $("#sliderWeeklyChange").slider('value', (loadWeeklyChange - 100));
            fitCalcSetWeeklyChangeValue((loadWeeklyChange - 100));
            showValuesOfSlider($("#sliderWeeklyChange"), getWeeklyChange());
        }

        function fitSaveGoals() {

            // var API_PATH = "http://api.onesportevent.com/DevApi";
            var API_PATH = "http://192.168.0.50/result/public";

            
            var url = API_PATH + "/Stats/SetCalorieGoal?jsoncallback=?";
            var email = $("#fit_email").val();
            var sessionGuid = $("#fit_session").val();
            var BMR = getBMR();
            var calorieGoal = Math.round(getCaloryGoal(BMR));
            var height = getHeight();
            var weight = getWeight();
            var measurementSystem = useMetric() == true ? 0 : 1;
            var gender = isMale() == true ? "M" : "F";
            var age = getAge();
            var weeklyChange = $("#sliderWeeklyChange").slider('value') + 100;  // convert to byte for efficient storage
            var exerciseLevel = activity;

            $.getJSON(url, {
                email: email,
                SessionGuid: sessionGuid,
                calorieGoal: calorieGoal,
                height: height,
                weight: weight,
                gender: gender,
                measurementSystem: measurementSystem,
                age: age,
                bmr: BMR,
                weeklyChange: weeklyChange,
                exerciseLevel: exerciseLevel
            }, function (data) {

                if (data.MessageId != 0) {
                    alert(data.Message);
                }
                else {
                    $("#fit_goalssaved").show();
                }

            });
        }


        //change slider values once the selection is changed
        function alterValuesToMetric() {
            showValuesOfSlider($("#sliderHeight"), $("#sliderHeight").slider('value'));
            showValuesOfSlider($("#sliderWeight"), $("#sliderWeight").slider('value'));
            //showValuesOfSlider($("#sliderWeeklyChange"), $("#sliderWeeklyChange").slider('value'));
            showValuesOfSlider($("#sliderWeeklyChange"), getWeeklyChange());


            $("#sliderWeeklyChange").parent().children('.float-left').html('-1 kg');
            $("#sliderWeeklyChange").parent().children('.float-right').html('1 kg');

        }

        function alterValuesToImperial() {
            showValuesOfSlider($("#sliderHeight"), $("#sliderHeight").slider('value'));
            showValuesOfSlider($("#sliderWeight"), $("#sliderWeight").slider('value'));
            //showValuesOfSlider($("#sliderWeeklyChange"), $("#sliderWeeklyChange").slider('value'));
            showValuesOfSlider($("#sliderWeeklyChange"), getWeeklyChange());


            $("#sliderWeeklyChange").parent().children('.float-left').html('-2 lb');
            $("#sliderWeeklyChange").parent().children('.float-right').html('2 lb');
        }

        //change the values
        function showValuesOfSlider($slider, value) {

            if ($slider.attr('id') == 'sliderHeight') {
                if (useMetric()) {
                    $("#amountHeight").html('Height  (' + value + ' cm)');
                }
                else {
                    var height = getImperialHeight(value);
                    $("#amountHeight").html('Height  (' + height[0] + ' feet, ' + height[1] + ' inches)');
                }
            }
            else if ($slider.attr('id') == 'sliderWeight') {
                if (useMetric()) {
                    $("#amountWeight").html('Weight  (' + value + ' kg)');
                }
                else {
                    var lbs = getImperialWeight(value);
                    $("#amountWeight").html('Weight  (' + lbs + ' lbs)');
                }
            }
            else if ($slider.attr('id') == 'sliderWeeklyChange') {
                if (useMetric()) {
                    $("#amountWeeklyChange").html('Weekly Change  (' + value + ' kg)');
                }
                else {
                    var lbs = Math.round(getImperialWeight(value));
                    $("#amountWeeklyChange").html('Weekly Change  (' + lbs + ' lbs)');
                }
            }

            calcBMIInImperialOrMetric();
        }

        //accesors, only to be used with calculations

        function getImperialHeight(value) {
            var inches = Math.round(((value / 2.54) % 12) * 10) / 10;
            var feet = (value / 2.54) / 12;

            var feetInt = parseInt("" + feet);

            return [feetInt, inches];
        }

        function getImperialWeight(value) {
            var lbs = Math.round(value * 2.2 * 10) / 10;

            return lbs;
        }

        function useMetric() {
            return isMetric;
        }

        function getGender() {
            return $("[name*='sex']:checked").attr('value');
        }

        function getHeight() {
            return height;
        }

        function getWeight() {
            return weight;
        }

        function getActive() {
            var value = activity;

            if (value <= 20)
                return 1.2; 	// fix negative bug + no semicolin
            else if (value <= 40)
                return 1.375;
            else if (value <= 60)
                return 1.55;
            else if (value <= 80)
                return 1.725;
            else
                return 1.9;
        }

        function getAge() {
            return age;
        }

        function getWeeklyChange() {
            return weeklyChange;
        }


        function isMale() {
            if (getGender() == 1) {
                return true;
            }

            return false;
        }

        function isFemale() {
            if (getGender() == 2) {
                return true;
            }
            return false;
        }

        /*--------------------BMI Calculation Methods----------------------*/
        function getBMR() {
            var BMR = 0;

            if (isMale()) {
                BMR = 66.5 + (13.75 * getWeight()) + (5.003 * getHeight()) - (6.755 * getAge());
            }
            else {
                BMR = 655.1 + (9.563 * getWeight()) + (1.850 * getHeight()) - (4.676 * getAge());
            }

            return BMR;
        }

        function getCaloryGoal(BMR) {

            // calories that need to be consumed each day just to keep person at same weight for a given activity level
            var baseCaleroies = BMR * getActive();

            // Default is 555.5 calories per day equals half a kilo of change +/- per week
            var weightChange = 550.0;
            var targetCalories = baseCaleroies + ((getWeeklyChange() * 2) * weightChange);

            // If lb's then 500 calories equals 1lb of change per week
            if (!useMetric()) {
                weightChange = 500;
                targetCalories = baseCaleroies + ((getWeeklyChange() * 2) * weightChange);
            }

            //console.log('BMR:' + BMR + ' cal: ' + baseCaleroies + ' tgt:' + targetCalories)
            return targetCalories;
        }

        function getIcmSquared(iCM) {
            return (iCM * iCM) / 100;
        }


        //calculator class
        function calcBMI(iCM, dKG) {
            var iCMSquared = getIcmSquared(iCM);

            return (dKG / iCMSquared) * 100;
        }

        function getAcceptableBMIRangeString(iCM) {
            return (calcMinBmiRangeInKilo(iCM) / 100) + " to " + (calcMaxBmiRangeInKilo(iCM) / 100);
        }

        function calcMinBmiRangeInKilo(iCM) {
            var iCMSquared = getIcmSquared(iCM);

            return iCMSquared * 18.5;
        }

        function calcMaxBmiRangeInKilo(iCM) {
            var iCMSquared = getIcmSquared(iCM);

            return iCMSquared * 25;
        }

        function calcBMIInImperialOrMetric(moveCallout) {

            // Goal/stats info has changed
            $("#fit_goalssaved").hide();

            var calculatedBMI = calcBMI(getHeight(), getWeight());
            var idealMinBMI = calcMinBmiRangeInKilo(getHeight());
            var idealMaxBMI = calcMaxBmiRangeInKilo(getHeight());

            var weightFrom = idealMinBMI / 100;
            var weightTo = idealMaxBMI / 100;

            if (!useMetric()) {
                weightFrom = getImperialWeight(weightFrom);
                weightTo = getImperialWeight(weightTo);

                // To save space don't include decimal points in pounds  (pounds has more characters due to "lbs" vs "kg" plus length, e.g. 90kg == 130lbs)
                callOutText = "Your BMI is " + calculatedBMI.toFixed(1).replace(/[.,]0$/, '') + " and your ideal weight is between " + Math.round(weightFrom) + " and " + Math.round(weightTo);
            }
            else {
                callOutText = "Your BMI is " + calculatedBMI.toFixed(1).replace(/[.,]0$/, '') + " and your ideal weight is between " + weightFrom.toFixed(1).replace(/[.,]0$/, '') + " and " + weightTo.toFixed(1).replace(/[.,]0$/, '');
            }

            callOutText += useMetric() ? "kg" : "lbs";
            callOutText += "<br/><div style='margin-top: 6px; padding-top: 0px;'>Your daily calorie goal is " + Math.round(getCaloryGoal(getBMR())) + "</div>";

            try {

                if ($callout) {
                    $callout.children('div').html(callOutText);
                }

                if (moveCallout) {
                    fitMoveBMICallout(callOutText);
                }
            }
            catch (err) {
            }
        }

        function fitMoveBMICallout(callOutText) {

            //var pixelWidthPerUnit = 642 / 120.0;   (640)  
            //var pixelHeightPerUnit = 520 / 51.0;    (518)

            var pixelWidthPerUnit = 592 / 120.0;
            var pixelHeightPerUnit = 480 / 51.0;

            callOutX = ((getWeight() - $("#sliderWeight").slider("option", 'min')) * pixelWidthPerUnit);
            callOutY = ((getHeight() - $("#sliderHeight").slider("option", 'min')) * pixelHeightPerUnit);


            if (callOutX <= 296 && callOutY <= 240) {
                moveInFirstQuadrant(callOutX, callOutY, callOutText);
            }
            else if (callOutX > 296 && callOutY <= 240) {
                moveInSecondQuadrant(callOutX, callOutY, callOutText);
            }
            else if (callOutX <= 296 && callOutY > 240) {
                moveInThirdQuadrant(callOutX, callOutY, callOutText);
            }
            else if (callOutX > 296 && callOutY > 240) {
                moveInFourthQuadrant(callOutX, callOutY, callOutText);
            }

            /*if (callOutX <= 321 && callOutY <= 260) {
            moveInFirstQuadrant(callOutX, callOutY, callOutText);
            }
            else if (callOutX > 321 && callOutY <= 260) {
            moveInSecondQuadrant(callOutX, callOutY, callOutText);
            }
            else if (callOutX <= 321 && callOutY > 260) {
            moveInThirdQuadrant(callOutX, callOutY, callOutText);
            }
            else if (callOutX > 321 && callOutY > 260) {
            moveInFourthQuadrant(callOutX, callOutY, callOutText);
            }*/
        }

        /*----------------------Callout Move Functions------------------------*/

        function bmiGetCallout(callOutX, callOutY) {

            if (callOutX <= 296 && callOutY <= 240) {
                moveInFirstQuadrant(callOutX, callOutY, callOutText);
            }
            else if (callOutX > 296 && callOutY <= 240) {
                moveInSecondQuadrant(callOutX, callOutY, callOutText);
            }
            else if (callOutX <= 296 && callOutY > 240) {
                moveInThirdQuadrant(callOutX, callOutY, callOutText);
            }
            else if (callOutX > 296 && callOutY > 240) {
                moveInFourthQuadrant(callOutX, callOutY, callOutText);
            }
        }

        function moveInFirstQuadrant(x, y, text) {
            if (current != 1) {
                current = 1;
                $callout.hide();
                $callout = $("#cloudOne");
                $callout.show();
                $callout.css('left', x).css('bottom', y).children('div').html(text);
            }
            else {
                $callout.children('div').html(text);
                $callout.stop().animate({ 'left': x, 'bottom': y }, 500, "easeOutBack");
            }
        }

        function moveInSecondQuadrant(x, y, text) {
            if (current != 2) {
                current = 2;
                $callout.hide();
                $callout = $("#cloudTwo");
                $callout.show();
                $callout.css('left', x - 301).css('bottom', y).children('div').html(text);
            }
            else {
                $callout.children('div').html(text);
                $callout.stop().animate({ 'left': x - 301, 'bottom': y }, 500, "easeOutBack");
            }
        }

        function moveInThirdQuadrant(x, y, text) {
            if (current != 3) {
                current = 3;
                $callout.hide();
                $callout = $("#cloudThree");
                $callout.show();
                $callout.children('div').css('top', -105);
                $callout.children('div').css('top', parseFloat($callout.children('div').css('top').replace('px', '')) + 25)
                $callout.css('left', x).css('bottom', y - 126).children('div').html(text);
            }
            else {
                $callout.children('div').html(text);
                $callout.stop().animate({ 'left': x, 'bottom': y - 126 }, 500, "easeOutBack");
            }
        }

        function moveInFourthQuadrant(x, y, text) {
            if (current != 4) {
                current = 4;
                $callout.hide();
                $callout = $("#cloudFour");
                $callout.show();
                $callout.children('div').css('top', -105);
                $callout.children('div').css('top', parseFloat($callout.children('div').css('top').replace('px', '')) + 25);
                $callout.css('left', x - 301).css('bottom', y - 126).children('div').html(text);
            }
            else {
                $callout.children('div').html(text);
                $callout.stop().animate({ 'left': x - 301, 'bottom': y - 126 }, 500, "easeOutBack");
            }
        }

    }

    fitCore.fitCalorie = new fitCalorie();





}(window.fitCore = window.fitCore || {}, jQuery));




