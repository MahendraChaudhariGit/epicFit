jQuery(function ($) {
    var count = 0;
    var latLongIcon = '';
    Math.toRadians = function (degree) {
        var pi = Math.PI;
        var radians = ((eval(degree)) * (pi / 180));
        return radians;
    };

    Fit = {};
    const contentString =
    `<div class="div-tooltip" style="text-align:center"></div>Click to start <br/> mapping a route`;
    const infowindow = new google.maps.InfoWindow({
        content: contentString,
        disableAutoPan : true,
        pixelOffset: new google.maps.Size(10,-10)
    });
    Fit.Global = new function () {
        this.Map = null;
        this.Markers = [];
        this.DistanceMarkers = [];
        //this.RouteModes = [0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 1, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0]; // create an empty array
        this.RouteModes = [];
        this.Chart = null;
        this.MouseMarker = null;
        this.Polyline = [];
        this.Elevations = null;
        this.ElevationsForMarkers = [];

        this.GlobalPolylines = 0;
        this.IsAddingExistingPoint = false;

        this.GeocoderService = null;
        this.ElevationService = null;
        this.DirectionsService = null;
        this.IsNotToCache = false;

        this.CalcElevation = true;
        this.CurrentDistanceMeters = 0;
        this.CumulativeElevationMeters = 0;
        this.MaxElevationMeters = 0;

        this.CurrentCityID = null;
        this.CurrentRouteID = null;
        this.CurrentRouteMode = 1; /* direct OR drive */

        this.SAMPLES = 256;
        this.CurrentUnit = 'Meters';
        this.ConversionFactor = function () {
            return (Fit.Global.CurrentUnit == 'Meters') ? 0.001 : 0.000621371192;
        };
        this.CurrentLatLng = null;
        this.WrappedZoomLevel = 3;
        this.IsDistanceMarkerVisible = true;
        this.SearchAddress = '';

        this.DirectMode = 0;
        this.DriveMode = 1;

        this.ActivityOptions = [{ ID: 1, Name: 'Running', METS: 7.5, EstKmPerHour: 10 }, { ID: 2, Name: 'Swimming', METS: 7, EstKmPerHour: 4 }, { ID: 3, Name: 'Walking', METS: 3.5, EstKmPerHour: 5 }, { ID: 4, Name: 'Mtn Biking', METS: 7, EstKmPerHour: 15 }, { ID: 5, Name: 'Kayaking', METS: 7, EstKmPerHour: 10 }, { ID: 6, Name: 'Rowing', METS: 9, EstKmPerHour: 10 }, { ID: 7, Name: 'Orienteering', METS: 7, EstKmPerHour: 7 }, { ID: 8, Name: 'Cycling', METS: 7, EstKmPerHour: 25 }, { ID: 9, Name: 'Other', METS: 6, EstKmPerHour: 7 }];

        this.RunningOptions = [{ ID: 6, Name: 'Easy Run', METS: 8, EstKmPerHour: 10.91 }, { ID: 7, Name: 'Long Run', METS: 7, EstKmPerHour: 10 }, { ID: 1, Name: 'Hill', METS: 14, EstKmPerHour: 6.5 }, { ID: 5, Name: 'Speed', METS: 16, EstKmPerHour: 16 }, { ID: 8, Name: 'Tempo Run', METS: 12, EstKmPerHour: 12 }, { ID: 20, Name: 'Fartlek', METS: 10, EstKmPerHour: 12 }];
        this.CyclingOptions = [{ ID: 21, Name: 'Easy', METS: 6.5, EstKmPerHour: 23 }, { ID: 22, Name: 'Hill', METS: 10, EstKmPerHour: 15 }, { ID: 23, Name: 'Interval', METS: 9, EstKmPerHour: 25 }, { ID: 24, Name: 'Long', METS: 6, EstKmPerHour: 26 }, { ID: 25, Name: 'Tempo', METS: 9, EstKmPerHour: 28 }, { ID: 26, Name: 'Race', METS: 11.5, EstKmPerHour: 35 }];
        this.SwimmingOptions = [{ ID: 17, Name: 'Freestyle', METS: 7, EstKmPerHour: 5.56 }, { ID: 11, Name: 'Backstroke', METS: 7, EstKmPerHour: 4.77 }, { ID: 12, Name: 'Breaststroke', METS: 7, EstKmPerHour: 4.28 }, { ID: 13, Name: 'Butterfly', METS: 7, EstKmPerHour: 5.07 }, { ID: 18, Name: 'Sidestroke', METS: 7, EstKmPerHour: 4.91 }, { ID: 19, Name: 'Mixed', METS: 7, EstKmPerHour: 4.92 }];

        //this.BaseUrl = "http://api.onesportevent.com/DevApi";
        //this.BaseUrl = "http://192.168.225.50/result/public";
        this.BaseUrl =  $('meta[name="public_url"]').attr('content');

        this.AddRoute = false;
        this.CachedPath = [];
        this.PreviousCounter = 0;
        this.EndDragged = false;
        this.PolylinesDrawn = false;
        this.CachedElevations = [];
        this.IsCacheElevation = false;

        // Current user email address and other details
        this.UserEmail = null;
        this.UserBMR = null;

    };

    Fit.Calories = new function () {

        this.RoughCalculateCalories = function (seconds, estimatedMETS) {
            // If unknown METS or no exercise time then unknown / no calories
            if (estimatedMETS == null || seconds == 0)
                return 0;

            var BMR = 1500;
            var estCalories = ((BMR / 86400) * seconds) * estimatedMETS;
            return estCalories;
        };

        //  http://en.wikipedia.org/wiki/Basal_metabolic_rate
        this.CalculateCalories = function (seconds, estimatedMETS, BMR) {
            // If unknown METS or no exercise time then unknown / no calories
            if (estimatedMETS == null || seconds == 0)
                return 0;

            // If BMR not known
            if (BMR == null || BMR == 0)
                return RoughCalculateCalories(seconds, estimatedMETS);

            // http://www.ehow.com/how_8685883_convert-mets-kilocalories-per-minute.html
            var estCalories = ((BMR / 86400) * seconds) * estimatedMETS;
            return estCalories;
        };

        this.GetEstimatedMETS = function (activityTypeID, exerciseTypeID, duration, BMR) {

            // Get METS (if any) from each combo
            var detailedMETS = Fit.Calories.GetExerciseMets(activityTypeID, exerciseTypeID);
            var generalMETS = Fit.Calories.GetActivityMets(activityTypeID);

            // console.log('calc calories', activityTypeID, exerciseTypeID, duration, BMR, detailedMETS, generalMETS);

            // Use the detailed if it exists, otherwise the general
            var estimatedMETS = detailedMETS != null ? detailedMETS : generalMETS;

            // Calculate the total calories and display it
            var calories = Fit.Calories.CalculateCalories(duration, estimatedMETS, BMR);

            // Update label
            $("#map-calories").html(calories.toFixed() + " calories");
        };

        // Find activity METS value in array and return it
        this.GetActivityMets = function (activityTypeID) {
            return Fit.Calories.GetMets(activityTypeID, Fit.Global.ActivityOptions);
        };

        // Find activity METS value in array and return it
        this.GetMets = function (value, lookup) {
            //console.log('look', lookup, value);
            for (var i = 0; i < lookup.length; i++) {
                //console.log('check', i, lookup[i], lookup[i].ID);
                if (lookup[i].ID == value) {
                    return lookup[i].METS;
                }
            }
            return null;
        };

        // Find activity METS value in array and return it
        this.GetExerciseMets = function (activityTypeID, exerciseTypeID) {

            if (activityTypeID == 1) {
                return Fit.Calories.GetMets(exerciseTypeID, Fit.Global.RunningOptions);
            }
            else if (activityTypeID == 2) {
                return Fit.Calories.GetMets(exerciseTypeID, Fit.Global.SwimmingOptions);
            }
            else if (activityTypeID == 8) {
                return Fit.Calories.GetMets(exerciseTypeID, Fit.Global.CyclingOptions);
            }
            else {
                return null;
            }
        };

    };

    Fit.Ajax = new function () {

        this.GetCookie = function (name) {
            var nameEQ = name + "=";
            var ca = document.cookie.split(';');
            for (var i = 0; i < ca.length; i++) {
                var c = ca[i];
                while (c.charAt(0) == ' ') c = c.substring(1, c.length);
                if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
            }
            return null;
        };

        this.FindClosestCity = function (latlng) {

            jQuery.ajax({
                url: Fit.Global.BaseUrl + '/Cities/FindClosestCity?jsoncallback=?',
                type: 'GET',
                dataType: 'json',
                data: { latitude: latlng.lat(), longitude: latlng.lng() },
                contentType: 'application/json; charset=utf-8',
                success: function (data) {


                    if (data.MessageId == 0) {
                        Fit.Global.CurrentCityID = data.Area.CityID;
                    }
                    else {
                        //alert('city not found');
                    }

                },
                error: function (data) {
                    //alert("Error in ajax returned data.Please try again.");
                }
            });
        };


        // Save route
        this.SaveRoute = function (encodedRoute) {

            //notes: Uncomment below line to test SaveWorkout PLEASE REMOVE
            //Fit.Ajax.SaveWorkout(1);
            // Create/write a cookie and store it for 1 day
            //setCookie('myCookie', 'myValue', 1);

            // Get authenticated user
            //var fitUserEmail = Fit.Ajax.GetCookie('fitUserEmail');

            // Delete/erase my cookie
            //deleteCookie('myCookie');


            //	http://localhost:51827/Route/Save?email=&jsoncallback=jQuery16205664615085110135_1324428713474&RouteID=0&PersonID=0&RouteName=&RouteDesc=&Quantity=1&IsAvailableToPublic=true&IsAvailableToFriends=true&CityID=260&KeyWords=&Location=test&Levels=levels&Polyline=poly&ScenicRating=1&TimesUsed=1&ActivityIDFastestBike=null&ActivityIDFastestFoot=0&AltitudeGainMeters=0&MaxAltitudeMeters=0&RegionID=1&CountryID=1&ClubID=1&RouteDistanceId=1&RouteTypes=a&_=1324428755029

            var newRoute = new Object();


            //newRoute.PersonID = 0;

            newRoute.RouteID = 0;
            newRoute.CityName = jQuery("#fitSearchAddress").val();
            newRoute.RouteName = jQuery("#fitRouteTitle").val();
            newRoute.RouteDesc = jQuery("#fitRouteDesc").val();
            newRoute.map_id = jQuery("#map_id").val();
            newRoute.new_challenge = jQuery("#new_challenge").val();

            newRoute.Quantity = Fit.Global.CurrentDistanceMeters;
            newRoute.IsAvailableToPublic = true;
            newRoute.IsAvailableToFriends = true;
            newRoute.CityID = Fit.Global.CurrentCityID;

            newRoute.KeyWords = jQuery("#fitRouteKeywords").val();
            newRoute.Location = " ";

            var routeControl = "0";

            // First marker is always saved as Direct
            for (var i = 0; i < Fit.Global.RouteModes.length; i++) {
                if (Fit.Global.RouteModes[i] == 0) {
                    routeControl += "0";
                }
                else {
                    routeControl += "1";
                }
            }

            newRoute.Levels = routeControl;
            newRoute.Polyline = encodedRoute;
            newRoute.lat = Fit.Global.Markers[i].position.lat(); // Latitude;
            newRoute.lng = Fit.Global.Markers[i].position.lng(); //Longitude;
            console.log( newRoute.lat,newRoute.lng);
            newRoute.ScenicRating = 3; 		// jQuery("#fit_diary").val();

            newRoute.TimesUsed = 1;
            newRoute.ActivityIDFastestBike = null;
            newRoute.ActivityIDFastestFoot = null;
            newRoute.AltitudeGainMeters = Fit.Global.CumulativeElevationMeters;
            newRoute.MaxAltitudeMeters = Fit.Global.MaxElevationMeters;
            newRoute.Exercise = $("#selectedExercise option:selected").val();
            newRoute.Workout = $("#selectedWorkout option:selected").val();

            newRoute.Duration = $("#selectedDuration").val();
            newRoute.ActualDuration = $("#map-time").html();
            // If not supplied use automatic estimation
            // if (duration == null || duration == "" || duration == undefined) {
            //     duration = $("#map-time").html();
            // }
            // newRoute.Duration =  duration;
            //newRoute.RegionID = null;
            //newRoute.CountryID = null;
            newRoute.ClubID = null;
            //newRoute.RouteDistanceId = null;
            //newRoute.RouteTypes = "a";
            $.ajax({
				url: 	Fit.Global.BaseUrl + '/Route/SaveRoute?pointsCount=' + Fit.Global.Markers.length + '&email=' + Fit.Global.UserEmail + '&jsoncallback=?',
				type: 	'GET',
				data: 	newRoute,
				success: function(data){
                    console.log(data);
                    if(data.success){
                        swal({
                            type: 'success',
                            title: data.success,
                            allowOutsideClick: false,
                            showCancelButton: false,
                            confirmButtonText: 'Yes',
                            confirmButtonColor: '#ff4401',
                            cancelButtonText: "No"
                        }, 
                        function(isConfirm){
                            if(isConfirm){
                                if(data.challenge == 'challenge'){
                                    window.location.replace(public_url+'fitness-mapper/'+data.fit_map_id+'/create/challenge');
                                }else{
                                    window.location.reload();
                                }
                                
                            }
                        });
                    }
                }
            })

           
        };


        this.formatDateTime = function (date) {

            var leadingMin = ':';

            if (date.getMinutes() < 10)
                leadingMin = ':0';

            if (date.getHours() == 0 && date.getMinutes() == 0) {
                return $.datepicker.formatDate("dd-M-yy ", date);
            }
            else {
                return $.datepicker.formatDate("dd-M-yy ", date) + date.getHours() + leadingMin + date.getMinutes();
            }
        };

        // Save workout
        this.SaveWorkout = function (routeId) {

            var newWorkout = new Object();

            // Get authenticated user
            // var fitUserEmail = Fit.Ajax.GetCookie('fitUserEmail');

            newWorkout.ActivityTypeID = jQuery("#workoutSelector").val();
            newWorkout.Quantity = Fit.Global.CurrentDistanceMeters;

            // Calculate seconds
            var duration = $("#durationSelector").datetimepicker('getDate');

            if (duration == null) {
                newWorkout.Seconds = 0;
            }
            else {
                newWorkout.Seconds = (duration.getHours() * 3600) + (duration.getMinutes() * 60) + (duration.getSeconds());
            }


            var startDate = $.datepicker.parseDate('dd-M-yy', $("#dateSelector").val());

            var time = [0, 0];
            if ($("#dateSelector").val().split(" ")[1] != null) {
                var time = $("#dateSelector").val().split(" ")[1].split(":");
            }
            startDate.setHours(time[0], time[1], 0, 0);

            newWorkout.Notes = " ";
            newWorkout.ActivityStart = Fit.Ajax.formatDateTime(startDate);//jQuery("#dateSelector").datetimepicker("getDate"));
            newWorkout.ActivityEnded = null;
            newWorkout.AveragePulse = null;
            newWorkout.ShoeID = null;
            newWorkout.RouteID = routeId;
            newWorkout.UserGeneratedPlanID = null;

            newWorkout.IsComplete = true;
            newWorkout.IsWalkthrough = false;
            newWorkout.EstimatedCalories = null;
            newWorkout.EventID = null; // jQuery("#workoutSelector").val()

            if ($("#exerciseSelector").val() != null) {
                newWorkout.ExerciseTypeID = jQuery("#exerciseSelector").val();
            }
            else {
                newWorkout.ExerciseTypeID = null;
            }

            if (newWorkout.ExerciseTypeID == 0) {
                Fit.Mapper.DebugLog('No exercise choosen');
                newWorkout.ExerciseTypeID = null;
            }


            // Reassign text null if necessary
            if (newWorkout.EventID == 'null') {
                newWorkout.EventID = null;
            }

            newWorkout.WeatherCD = 'X';

            /*	fitPulse
            fitCalories	*/

            jQuery.ajax({
                url: Fit.Global.BaseUrl + '/Activity/Save?email=' + Fit.Global.UserEmail + '&jsoncallback=?',
                type: 'GET',
                dataType: 'json',
                data: newWorkout,
                contentType: 'application/json; charset=utf-8',
                success: function (data) {

                    if (data.MessageId == 0) {

                        // check settings, call MapperSaveClicked
                        /*if (oseSettings != null) {
                            if (oseSettings.callMapperSaveClicked === true) {

                                // if not authenticated
                                if (Fit.Global.UserEmail == null || Fit.Global.UserEmail == "") {
                                    //todo
                                    MapperSaveClicked(data.ActivityId, routeId, "False");
                                }
                                else {
                                    MapperSaveClicked(data.ActivityId, routeId, "True");
                                }
                            }
                        }*/

                        alert('Your route and workout has been successfully saved on your calendar');

                        // Call login/authentication here
                    }
                    else {

                        alert('error' + data.ActivityId);
                    }

                },
                error: function (data) {
                    alert("Error in ajax returned data.Please try again.");
                }
            });
        };
    };



    Fit.Mapper = new function () {

        this.DebugLog = function (value) {
            if (window.console && false)
                console.log(value);
        };

        this.Start = function (userEmail, BMR) {

            // Store session credentials
            Fit.Global.UserEmail = userEmail;
            Fit.Global.UserBMR = BMR == null ? 1500 : BMR;

            $("#fitSearchAddress").keypress(Fit.Handlers.SearchKeyPress);
            $("#fitMapBtnSearch").click(Fit.Search.DoSearch);
            $("#fitMapBtnClear").click(function () {  
                
                swal({
                    type: 'warning',
                    title: 'Are you sure you want to clear the map?',
                    showCancelButton: true,
                    allowOutsideClick: false,
                    showConfirmButton: true,
                    confirmButtonColor: '#ff4401',
                }, 
                function(isConfirm){
                    if(isConfirm){
                        google.maps.event.addListener(Fit.Global.Map,'mousemove',function(event){
                            infowindow.setPosition(event.latLng);
                            infowindow.open(Fit.Global.Map);
                        });
                        count = 0; Fit.Processor.Reset(); return false; 
                    }
                });
                
            });

            //$("#fitAltChart").mouseout(Fit.Mapper.ClearMouseMarker);
            $("#fitMapBtnUndo").click(function () { Fit.ActionPanel.MenuChoosen('Undo'); return false; });
            $("#fitMapBtnPlotBack").click(function () { Fit.ActionPanel.MenuChoosen('CloseLoop'); return false; });
            $("#fitMapBtnOutBack").click(function () { Fit.ActionPanel.MenuChoosen('OutAndBack'); return false; });

            $("#fitMapBtnSave").click(function () {

                // Grab manually entered duration
                var duration = $("#selectedDuration").val();

                // If not supplied use automatic estimation
                if (duration == null || duration == "" || duration == undefined) {
                    duration = $("#map-time").html();
                }

                $("#durationSelector").val(duration);

                Fit.ActionPanel.MenuChoosen('Save');
                return false;
            });

            $(".minimizeDiv").click(function (evt) {
                $("#accordion").slideToggle();
                return false;
            });

            $(".headerGradient").click(function (evt) {
                $(evt.target).next('div').slideToggle();
                return false;
            });
            $("#fitControlPanel").draggable();
            $("#toggleControl").click(function () {
                Fit.Global.IsDistanceMarkerVisible = !Fit.Global.IsDistanceMarkerVisible;
                Fit.DistanceMarkers.ToggleUpdateDistanceMarkers();
            });

            $("#toggleUnit").click(function () {
                Fit.Global.CurrentUnit = (Fit.Global.CurrentUnit == 'Meters') ? 'Miles' : 'Meters';
                Fit.Processor.UpdateDistance();
                Fit.DistanceMarkers.ToggleUpdateDistanceMarkers();

                $("#fitDistanceType").html((Fit.Global.CurrentUnit == 'Meters') ? 'Kms' : 'Miles');
            });

            $("#toggleRoad").click(function (evt) {
                var image = $(evt.target);
                if (Fit.Global.CurrentRouteMode == Fit.Global.DirectMode) {
                    Fit.Global.CurrentRouteMode = Fit.Global.DriveMode;
                    image.attr('src', image.attr('src').replace('straight', 'road'));
                    //Fit.Processor.CalculateRoute('driving');
                }
                else {
                    Fit.Global.CurrentRouteMode = Fit.Global.DirectMode;
                    image.attr('src', image.attr('src').replace('road', 'straight'));
                    
                    //Fit.Elevation.UpdateElevation();
                }
            });
			// Ankit
            $("#toggleControl").click(function (evt) {
                var image = $(evt.target);
                if (Fit.Global.CurrentRouteMode == Fit.Global.DirectMode) {
                    Fit.Global.CurrentRouteMode = Fit.Global.DriveMode;
                    image.attr('src', image.attr('src').replace('eye-1', 'eye-2'));
                    //Fit.Processor.CalculateRoute('driving');
                }
                else {
                    Fit.Global.CurrentRouteMode = Fit.Global.DirectMode;
                    image.attr('src', image.attr('src').replace('eye-2', 'eye-1'));
                    
                    //Fit.Elevation.UpdateElevation();
                }
            });
			// Ankit
            $("#toggleScreen").click(function (evt) {
                var image = $(evt.target);
                if (Fit.Global.CurrentRouteMode == Fit.Global.DirectMode) {
                    Fit.Global.CurrentRouteMode = Fit.Global.DriveMode;
                    image.attr('src', image.attr('src').replace('min', 'max'));
                    //Fit.Processor.CalculateRoute('driving');
                }
                else {
                    Fit.Global.CurrentRouteMode = Fit.Global.DirectMode;
                    image.attr('src', image.attr('src').replace('max', 'min'));
                    
                    //Fit.Elevation.UpdateElevation();
                }
            });

            $("#toggleScreen").click(function () {
                $("#fitMapMainContainer").fullScreen();
            });

            /*$("#chartToggle").click(function (evt) {
                var path = $(evt.target).attr("src");
                path = path.indexOf("up") > 0 ? path.replace("up", "down") : path.replace("down", "up");
                $(evt.target).attr("src", path);
                $("#fitChartDiv").slideToggle('slow');
            });*/

            $("input[data-wmt],textarea[data-wmt]").each(function (index, el) {
                //jQuery(el).watermark($(el).attr("data-wmt"));
            });
            $("#dateSelector").datetimepicker({
                dateFormat: 'dd-M-yy',
                beforeShow: function (textbox, instance) {

                    // Wrap with our special class if it is not already wrapped
                    var $widget = $(this).datepicker("widget");

                    if (!$widget.parent().hasClass("fit-ui"))
                        $(this).datepicker("widget").wrap("<div class='fit-ui'/>");
                },
                defaultDate: new Date()
            });

            $("#dateSelector").datepicker("setDate", new Date());

            $("#getStarted").click(function () {
                $("#fitStaticPanel").fadeOut();
                return false;
            });
            $("#selectedDuration,#durationSelector,.challengeDuration").click(function() {
                $(".ui_tpicker_millisec_label").hide();
                $(".ui_tpicker_microsec_label").hide();
                $(".ui_tpicker_millisec").hide();
                $(".ui_tpicker_microsec").hide();
            })
            
            $("#durationSelector").timepicker({
                pickDate: false,
                timeOnlyTitle: 'Choose Duration',
                timeText: 'Duration',
                showSecond: true,
                timeFormat: 'HH:mm:ss',
                beforeShow: function (textbox, instance) {

                    // Wrap with our special class if it is not already wrapped
                    var $widget = $(this).datepicker("widget");

                    if (!$widget.parent().hasClass("fit-ui"))
                        $(this).datepicker("widget").wrap("<div class='fit-ui'/>");
                }
            });
            $(".challengeDuration").timepicker({
                pickDate: false,
                timeOnlyTitle: 'Choose Duration',
                timeText: 'Duration',
                showSecond: true,
                timeFormat: 'HH:mm:ss',
                beforeShow: function (textbox, instance) {

                    // Wrap with our special class if it is not already wrapped
                    var $widget = $(this).datepicker("widget");

                    if (!$widget.parent().hasClass("fit-ui"))
                        $(this).datepicker("widget").wrap("<div class='fit-ui'/>");
                }
            });

            $("#selectedDuration").timepicker({
                pickDate: false,
                timeOnlyTitle: 'Choose Duration',
                timeText: 'Duration',
                showSecond: true,
                timeFormat: 'HH:mm:ss',
                beforeShow: function (textbox, instance) {

                    // Wrap with our special class if it is not already wrapped
                    var $widget = $(this).datepicker("widget");

                    if (!$widget.parent().hasClass("fit-ui"))
                        $(this).datepicker("widget").wrap("<div class='fit-ui'/>");
                }
            });

            // TODO IMPROVE: update calories on duration change
            // TODO FEATURE: allow override duration 
            $("#durationSelector").change(function () {
                $("#selectedDuration").val($("#durationSelector").val());
                var duration = $("#durationSelector").timepicker('getDate');
            });

            $("#selectedWorkout").change(function () {
                var selectedWorkout = $("#selectedWorkout option:selected").val();
                $("#workoutSelector").val(selectedWorkout);
                Fit.ScriptHandlers.RepopluateDropdowns(selectedWorkout);
                Fit.Processor.UpdateDistance();     // Recalculate calories and time for course based on new exercise
            });

            $("#workoutSelector").change(function () {
                var workoutSelected = $("#workoutSelector option:selected").val();
                $("#selectedWorkout").val(workoutSelected);

                Fit.ScriptHandlers.RepopluateDropdowns(workoutSelected);
                Fit.Processor.UpdateDistance();     // Recalculate calories and time for course based on new exercise
            });

            $("#selectedExercise").change(function () {
                $("#exerciseSelector").val($("#selectedExercise option:selected").val());
                Fit.Processor.UpdateDistance();     // Recalculate calories and time for course based on new exercise
            });

            $("#exerciseSelector").change(function () {
                $("#selectedExercise").val($("#exerciseSelector option:selected").val());
                Fit.Processor.UpdateDistance();     // Recalculate calories and time for course based on new exercise
            });

            $("body").click(function () {
                $("#fitStaticPanel").fadeOut();
            });
            $("#fitStaticPanel").click(function (e) {
                e.stopPropagation();
            });

            Fit.Mapper.Initialize();
        };
        
        // Add a marker and trigger recalculation of the path and elevation
        this.AddMarker = function (latlng, isAddElevation, url=null) {

            if(url == null){
                var url;
                count++;
                console.log(count, url);
                if(count == 1){
                    url = public_url+'css/fitness-mapper/css/images/map/start_marker.png';
                }else{
                    if(count > 2){

                        url = public_url+'css/fitness-mapper/css/images/map/route-icon.png';
                        console.log(latLongIcon);
                         var marker = new google.maps.Marker({
                             position: latLongIcon,
                             map: Fit.Global.Map,
                         })
                         marker.setIcon(false);
                         marker.setIcon(url);
                    }
                    url = public_url+'css/fitness-mapper/css/images/map/end_marker.png';
                }                  
                latLongIcon =latlng;
                // $('#isAddElevation').val(isAddElevation);
                // $('#latLon').val(latlng);
            }
            var pathname = window.location.pathname;
            if(pathname.split('/')[1] == 'details' || pathname.split('/')[1] == 'search'){
                var draggable_marker = false;
            }else{
                var draggable_marker = true;
            }

            if (Fit.Global.GlobalPolylines != 0 && Fit.Global.IsAddingExistingPoint == false) {
               
                Fit.Mapper.DebugLog("Expected Polylines" + Fit.Global.GlobalPolylines);
                Fit.Mapper.DebugLog("Actual Polylines" + Fit.Global.Polyline.length);
                if (Fit.Global.GlobalPolylines <= Fit.Global.Polyline.length) {
                    Fit.Mapper.DebugLog("Polylines Equal" + Fit.Global.GlobalPolylines);
                    //Add marker
                    var marker = new google.maps.Marker({
                        position: latlng,
                        map: Fit.Global.Map,
                        draggable: draggable_marker,
                        icon: url
                    })

                    google.maps.event.addListener(marker, 'dragend', function (e) {
                        //Fit.Elevation.AddElevation();
                        Fit.Global.EndDragged = true;
                        Fit.Mapper.RemovePolyline();
                        Fit.Elevation.UpdateElevation(true);
                    });

                    Fit.Global.Markers.push(marker);
                    if(Fit.Global.Markers.length > 2){
                        for(i=1;i < Fit.Global.Markers.length-1;i++){
                            Fit.Global.Markers[i].setMap(null)
                        }
                    }
                    if (isAddElevation) {
                        if (Fit.Global.Markers.length != 1) {
                            Fit.Global.RouteModes.push(Fit.Global.CurrentRouteMode);
                        }
                        Fit.Elevation.AddElevation();
                    }
                }
                else {
                    Fit.Mapper.DebugLog("Polylines Not Equal" + Fit.Global.GlobalPolylines + ">" + Fit.Global.Polyline.length);
                    Fit.Global.GlobalPolylines--;
                    Fit.Elevation.AddElevation();
                }
            }
            else {
            
                var marker = new google.maps.Marker({
                    position: latlng,
                    map: Fit.Global.Map,
                    draggable: draggable_marker,
                    icon: url
                })

                google.maps.event.addListener(marker, 'dragend', function (e) {
                    //Fit.Elevation.AddElevation();
                    Fit.Global.EndDragged = true;
                    Fit.Mapper.RemovePolyline();
                    Fit.Elevation.UpdateElevation(true);
                });

                Fit.Global.Markers.push(marker);

                if (isAddElevation) {
                    if (Fit.Global.Markers.length != 1) {
                        Fit.Global.RouteModes.push(Fit.Global.CurrentRouteMode);
                    }
                    Fit.Elevation.AddElevation();
                }
            }
        };

        //notes: Code to lessen main markers on zoom change
        this.RedrawMainMarkers = function () {
            var currentZoomLevel = Fit.Global.Map.getZoom();
            console.log(currentZoomLevel);
            // Zooming out 
            if (currentZoomLevel < 12) {
                Fit.Global.WrappedZoomLevel = 1;
            }
            else if (currentZoomLevel < 13) {
                Fit.Global.WrappedZoomLevel = 2;
            }
            else {
                Fit.Global.WrappedZoomLevel = 3;
            }

            for (var i = 0; i < Fit.Global.Markers.length; i++) {
                Fit.Global.Markers[i].setVisible(true);
            }

            switch (Fit.Global.WrappedZoomLevel) {
                case 1:
                    {
                        for (var i = 0; i < Fit.Global.Markers.length; i++) {
                            if (i % 3 != 0 && i != 0 && i != Fit.Global.Markers.length - 1) {
                                Fit.Global.Markers[i].setVisible(false);
                            }
                        }
                    }
                    break;
                case 2:
                    {
                        for (var i = 0; i < Fit.Global.Markers.length; i++) {
                            if (i % 2 != 0 && i != 0 && i != Fit.Global.Markers.length - 1) {
                                Fit.Global.Markers[i].setVisible(false);
                            }
                        }
                    }
                    break;

                case 3:
                    {
                    }
                    break;
            }
        };

        this.RemovePolyline = function () {
            for (var i = 0; i < Fit.Global.Polyline.length; i++) {
                Fit.Global.Polyline[i].setMap(null);
            }

            Fit.Global.Polyline = [];
        };

        // Remove the green rollover marker when the mouse leaves the chart
        this.ClearMouseMarker = function () {
            if (Fit.Global.MouseMarker != null) {
                Fit.Global.MouseMarker.setMap(null);
                Fit.Global.MouseMarker = null;
            }
        }

        this.Initialize = function () {
            // Load with no default route (polyline)
            var polyUnknown = null;
            //notes :  Comment out this line when using MyCoder, Replace example with wrongEncoding
            ///var example = "lucnEyidrBvEb[tCzRmJ{TkByW_EgUgGsFaj@cHcVm]tRgHnVtLzMgDiFwg@b[mlAbjAq_@zo@qhAzG_W~[yd@dRum@obEtrGgAbf@~Dfr@";
            //var wrongEncoding = "nucnEwidrB?tEb[?vCxR?mJyT?kByW?_EiU?gGsF?cj@aH?aVm]?tRgH@nVrL@zMeD@iFyg@@b[klA?bjAs_@?zo@ohA?xG_W?`\{d@?dRum@?qbEvrG?gAbf@?~Ddr@";
            ///var encode = "lucnEyidrB?vEb[?tCzR?mJ{T?kByW@_EgU@gGsF@aj@cH@cVm]?tRgH?nVtL?zMgD?iFwg@?b[mlA@bjAq_@@zo@qhA@zG_W@~[yd@?dRum@?obEtrG?gAbf@?~Dfr@";
            Fit.Mapper.Init(polyUnknown);
            var url = window.location.pathname;
            if(url.split('/')[1] != 'editRoute' && url.split('/')[1] != 'copyRoute'){
                $("#fitSearchAddress").val("Auckland");
            }
            
            Fit.Search.DoSearch();
            setTimeout(function(){
              $(".gm-style > div > div:nth-child(1)").addClass("customMap");
            }, 400);
            
        };


        this.Init = function (routePoly) {

            var canvasTest = document.getElementById("fitMapCanvas");
               
            if (canvasTest == null) {
                return;
            }

            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function (position) {
                    var lat = position.coords.latitude;
                    var lng = position.coords.longitude;
                    Fit.Global.CurrentLatLng = new google.maps.LatLng(lat, lng);
                    if (Fit.Global.Markers.length == 0) {
                        Fit.Global.Map.setCenter(Fit.Global.CurrentLatLng);
                        Fit.Global.GeocoderService.geocode({ 'latLng': Fit.Global.CurrentLatLng }, function (results, status) {
                            if (status == google.maps.GeocoderStatus.OK) {
                                if (results[1]) {
                                    Fit.Global.SearchAddress = results[1].formatted_address;
                                    console.log(Fit.Global.SearchAddress);
                                    var url = window.location.pathname;
                                    if(url.split('/')[1] != 'editRoute' && url.split('/')[1] != 'copyRoute'){
                                        $("#fitSearchAddress").val(Fit.Global.SearchAddress);
                                        $("#fitMapBtnSearch").trigger('click');
                                    }
                                }
                            }
                        })
                    }
                });
            }
            else {
                alert("Geolocation is not supported by this browser - please search for your address to start.");
            }

            //google.visualization will be defined here
            // Fit.Global.Chart = new google.visualization.ColumnChart(document.getElementById('fitAltChart'));
            //google.visualization.events.addListener(Fit.Global.Chart, 'ready', $("#chartToggle").trigger("click"));
            var myOptions = {
                zoom: 16,
                //center: new google.maps.LatLng(lat, lng),
                draggableCursor: 'crosshair',
                styles: [
                    { elementType: "geometry", stylers: [{ color: "#242f3e" }] },
                    { elementType: "labels.text.stroke", stylers: [{ color: "#242f3e" }] },
                    { elementType: "labels.text.fill", stylers: [{ color: "#746855" }] },
                    {
                      featureType: "administrative.locality",
                      elementType: "labels.text.fill",
                      stylers: [{ color: "#d59563" }],
                    },
                    {
                      featureType: "poi",
                      elementType: "labels.text.fill",
                      stylers: [{ color: "#d59563" }],
                    },
                    {
                      featureType: "poi.park",
                      elementType: "geometry",
                      stylers: [{ color: "#263c3f" }],
                    },
                    {
                      featureType: "poi.park",
                      elementType: "labels.text.fill",
                      stylers: [{ color: "#6b9a76" }],
                    },
                    {
                      featureType: "road",
                      elementType: "geometry",
                      stylers: [{ color: "#38414e" }],
                    },
                    {
                      featureType: "road",
                      elementType: "geometry.stroke",
                      stylers: [{ color: "#212a37" }],
                    },
                    {
                      featureType: "road",
                      elementType: "labels.text.fill",
                      stylers: [{ color: "#9ca5b3" }],
                    },
                    {
                      featureType: "road.highway",
                      elementType: "geometry",
                      stylers: [{ color: "#746855" }],
                    },
                    {
                      featureType: "road.highway",
                      elementType: "geometry.stroke",
                      stylers: [{ color: "#1f2835" }],
                    },
                    {
                      featureType: "road.highway",
                      elementType: "labels.text.fill",
                      stylers: [{ color: "#f3d19c" }],
                    },
                    {
                      featureType: "transit",
                      elementType: "geometry",
                      stylers: [{ color: "#2f3948" }],
                    },
                    {
                      featureType: "transit.station",
                      elementType: "labels.text.fill",
                      stylers: [{ color: "#d59563" }],
                    },
                    {
                      featureType: "water",
                      elementType: "geometry",
                      stylers: [{ color: "#17263c" }],
                    },
                    {
                      featureType: "water",
                      elementType: "labels.text.fill",
                      stylers: [{ color: "#515c6d" }],
                    },
                    {
                      featureType: "water",
                      elementType: "labels.text.stroke",
                      stylers: [{ color: "#17263c" }],
                    },
                  ],
            }

            Fit.Global.Map = new google.maps.Map(document.getElementById("fitMapCanvas"), myOptions);
            //chart = new google.visualization.ColumnChart(document.getElementById('fitAltChart'));

            Fit.Global.GeocoderService = new google.maps.Geocoder();
            Fit.Global.ElevationService = new google.maps.ElevationService();
            Fit.Global.DirectionsService = new google.maps.DirectionsService();
           
            google.maps.event.addListener(Fit.Global.Map,'mousemove',function(event){
                var url = window.location.pathname;
                if(url.split('/')[1] != 'details' && url.split('/')[1] != 'search'){
                    infowindow.setPosition(event.latLng);
                    infowindow.open(Fit.Global.Map);
                }
            });

            google.maps.event.addListener(Fit.Global.Map, 'click', function (event) {
                infowindow.close();
                google.maps.event.clearListeners(Fit.Global.Map, 'mousemove');

                // For the first point, find the closest city
                if (Fit.Global.Markers.length == 0) {
                    Fit.Ajax.FindClosestCity(event.latLng);
                }
                var url = window.location.pathname;
                if(url.split('/')[1] != 'details' && url.split('/')[1] != 'search'){
                    Fit.Mapper.AddMarker(event.latLng, true);
                    console.log(event.latLng);
                }
            });

            // google.maps.event.addListenerOnce(Fit.Global.Map, 'bounds_changed', function(event) {
            //     if (Fit.Global.Map.getZoom()){
            //         Fit.Global.Map.setZoom(16);
            //     }
            // });
            google.maps.event.addListener(Fit.Global.Map, 'zoom_changed', function () {
                console.log($("#route-address").val());
                if($("#route-address").val() != undefined){
                    if (Fit.Global.Map.getZoom() == 16){
                        Fit.Global.Map.setZoom(10);
                    }
                }
                else if (Fit.Global.Map.getZoom() == 10){
                    Fit.Global.Map.setZoom(16);
                }
                Fit.DistanceMarkers.RedrawMarkers();
                Fit.Mapper.RedrawMainMarkers();
            });

            google.maps.event.addListener(Fit.Global.Map, 'maptypeid_changed', function () {

                var mapTypeId = Fit.Global.Map.getMapTypeId();

                if (mapTypeId == google.maps.MapTypeId.HYBRID) {
                    $(".fitControlPanelMapInner").addClass('satelliteControlPanel');
                    $(".fitMap").addClass('mapOuterBorder');
                }
                else if (mapTypeId == google.maps.MapTypeId.ROADMAP) {
                    $(".fitControlPanelMapInner").removeClass('satelliteControlPanel');
                    $(".fitMap").removeClass('mapOuterBorder');
                }
            });

            /*google.visualization.events.addListener(Fit.Global.Chart, 'onmouseover', function (e) {
                if (Fit.Global.MouseMarker == null) {
                    Fit.Global.MouseMarker = new google.maps.Marker({
                        position: Fit.Global.CachedElevations[e.row].location,
                        map: Fit.Global.Map,
                        icon: "http://maps.google.com/mapfiles/ms/icons/green-dot.png"
                    });
                } else {
                    Fit.Global.MouseMarker.setPosition(Fit.Global.CachedElevations[e.row].location);
                }
            });*/

            // Hmm, some null checking woes
            if (routePoly !== null && routePoly != "null") {
                console.log(routePoly);
                Fit.Coder.Decode(routePoly);
            }
        };

    };


    Fit.ActionPanel = new function () {

        this.UndoLast = function () {
            if (Fit.Global.Markers.length > 1) {
                count--;
                console.log(count)
                // Remove last marker
                var marker = Fit.Global.Markers.pop();
                marker.setMap(null);
               
                console.log(Fit.Global.Markers.length);
                for (var i=0; i<Fit.Global.Markers.length;i++) {
                    // console.log(Fit.Global.Markers[i].position.lat(),Fit.Global.Markers[i].position.lng())
                    if (Fit.Global.Markers.length-1 == i && Fit.Global.Markers.length > 1) {
                        // console.log(Fit.Global.Markers[i].position.lat(),Fit.Global.Markers[i].position.lng())
                        var lat_lng = {
                            lat:Fit.Global.Markers[i].position.lat(),
                            lng:Fit.Global.Markers[i].position.lng()
                        }
                        // console.log(lat_lng);
                        var marker = new google.maps.Marker({
                            position: lat_lng,
                            map: Fit.Global.Map,
                            icon:public_url+'css/fitness-mapper/css/images/map/end_marker.png'
                        })
                       
                    }
                }

                Fit.Global.RouteModes.pop();
                if (Fit.Global.Polyline.length > 1) {
                    var lastPoly = Fit.Global.Polyline.pop();
                    if (lastPoly != undefined) {
                        lastPoly.setMap(null);
                    }

                    Fit.Global.CachedPath.pop();
                    Fit.Global.GlobalPolylines--;

                    for (i = 0; i < 256; i++) {
                        Fit.Global.CachedElevations.pop();
                    }

                    Fit.Processor.UpdateDistance();
                    Fit.Processor.UpdateCumulativeElevation();
                    if (Fit.Global.IsDistanceMarkerVisible) {
                        if (Fit.Global.EndDragged || Fit.Global.IsAddingExistingPoint) {
                            Fit.DistanceMarkers.OldDrawMarkers();
                            Fit.Global.IsAddingExistingPoint = false;
                        }
                        else {
                            Fit.DistanceMarkers.DrawMarkers();
                        }
                    }

                }
                else {
                    Fit.Mapper.RemovePolyline();
                    if (Fit.Global.Markers.length != 1) {
                        Fit.Global.CachedPath = [];
                    }
                    else {
                        Fit.Global.CachedPath.pop();
                    }

                    Fit.Global.GlobalPolylines = 0;
                    Fit.Global.CachedElevations = []
                    Fit.Elevation.UpdateElevation();
                }

                //Fit.Global.CachedPath.pop();
                if (Fit.Global.Markers.length == 1) {
                    $("#fitDistance").html('0.00');
                    $("#map-altitude").html('0m');
                    $("#map-calories").html('0 calories');
                    $("#map-time").html('00:00:00');
                    Fit.DistanceMarkers.RemoveAllDistanceMarkers();
                }
                /* else{
                    Fit.DistanceMarkers.DrawMarkers();
                }  */
            }
            else {
                count = 0;
                Fit.Processor.Reset();
            }
        };

        this.CloseLoop = function () {

            // Must have at least 2 points to warrent closing the loop
            if (Fit.Global.Markers.length >= 2) {

                // If we have closed already, do not close again
                if (Fit.Global.Markers[0].position.lat() == Fit.Global.Markers[Fit.Global.Markers.length - 1].position.lat() &&
                     Fit.Global.Markers[0].position.lng() == Fit.Global.Markers[Fit.Global.Markers.length - 1].position.lng()) {
                }
                else {
                    Fit.ActionPanel.AddExistingPoint(0);
                    Fit.Global.IsAddingExistingPoint = true;
                    Fit.Elevation.AddElevation();
                    Fit.Global.IsAddingExistingPoint = false;
                }
            }else{
                alert('Must have at least 2 points to warrent closing the loop');
            }
        };

        this.AddExistingPoint = function (beginPosition) {

            // Find existing point latlng details
            var pLat = Fit.Global.Markers[beginPosition].position.lat();
            var pLng = Fit.Global.Markers[beginPosition].position.lng();

            var initialLatLng = new google.maps.LatLng(pLat, pLng);

            // Add point again
            Fit.Global.RouteModes.push(Fit.Global.CurrentRouteMode);
            Fit.Mapper.AddMarker(initialLatLng, false);
        };

        this.OutAndBack = function () {

            // From the second to last point we have, back to the first point
            for (i = Fit.Global.Markers.length - 2; i <= 0; i--) {
                // Add the existing point again
                Fit.ActionPanel.AddExistingPoint(i);
            }
            Fit.Mapper.RemovePolyline();
            Fit.Global.GlobalPolylines = 0;
            Fit.Global.IsAddingExistingPoint = true;
            Fit.Global.IsNotToCache = (Fit.Global.CurrentRouteMode == Fit.Global.DriveMode) ? true : false;
            Fit.Elevation.UpdateElevation();
        };

        this.SaveDialog = function () {

            if (Fit.Global.Markers.length == 0) {
                alert('To get started, click on the map to draw a route, then you can save.');
                return;
            }

            $("#fitSaveDialog").dialog(
                 {
                     resizable: false,
                     modal: true,
                     minWidth: 600,
                     open: function (event, ui) {
                         $(event.target).parent().addClass("customPopup");
                         $('.ui-widget-overlay').wrap('<div class="fit-ui" />');
                     },
                     create: function (event, ui) {
                         $('.ui-dialog').wrap('<div class="fit-ui" />');
                     },
                     close: function (event, ui) {
                         $(".fit-ui").filter(function () {
                             if ($(this).text() == "") {
                                 return true;
                             }
                             return false;
                         }).remove();
                     },
                     buttons: {
                         Cancel: function () {
                             $(this).dialog("close");
                         },
                         Save: function () {

                             var title = jQuery('#fitRouteTitle').val();
                             var desc = jQuery('#fitRouteDesc').val();

                             if (title === null || title == "" || desc === null || desc == "") {
                                 alert('Please supply a title and description for your route.');
                                 return;
                             }

                             Fit.Ajax.SaveRoute(Fit.Coder.CreateEncodings(false));

                             $(this).dialog("close");
                         }
                     }
                 });
            return;
        };

        this.MenuChoosen = function (item) {
            switch (item) {
                case 'Undo': // Last Point': 
                    Fit.ActionPanel.UndoLast();
                    break;

                case 'RemoveAll':
                    deleteAllPoints();
                    break;

                case 'CloseLoop':
                    Fit.ActionPanel.CloseLoop();
                    break;

                case 'OutAndBack':
                    Fit.ActionPanel.OutAndBack();
                    break;

                case 'Save':
                    Fit.ActionPanel.SaveDialog();
                    break;

                case 'Milestones':
                    showMilestones();
                    break;

                case 'Search':
                    test();
                    showBrowseWindow();
                    break;

                case 'Hide Search Window':
                    showBrowseWindow();
                    break;

                case 'Map Settings':
                    break;

                case 'Search Routes':
                    break;

                case 'Routes':
                    break;

                case 'Route Points':
                    break;

                default:
                    alert('option not found');
            }
        };
    };

    Fit.Coder = new function () {

        // Encode a signed number in the encode format.
        this.EncodeSignedNumber = function (num) {
            var sgnNum = num << 1;

            if (num < 0) {
                sgnNum = ~(sgnNum);
            }

            return (Fit.Coder.EncodeNumber(sgnNum));
        }

        // Encode an unsigned number in the encode format.
        this.EncodeNumber = function (num) {
            var encodeString = "";

            while (num >= 0x20) {
                encodeString += (String.fromCharCode((0x20 | (num & 0x1f)) + 63));
                num >>= 5;
            }

            encodeString += (String.fromCharCode(num + 63));
            return encodeString;
        }

        this.EncodeRouteMode = function (routeModeVal) {
            return String.fromCharCode(routeModeVal + 63);
        };

        // Create the encoded polyline and level strings. If moveMap is true
        // move the map to the location of the first point in the polyline.
        this.CreateEncodings = function (moveMap) {
            var i = 0;

            var plat = 0;
            var plng = 0;

            var encodedPoints = "";
            var encodedLevels = "";
            for (i = 0; i < Fit.Global.Markers.length; ++i) {
                // var point = markers[i];
                var lat = Fit.Global.Markers[i].position.lat(); // Latitude;
                var lng = Fit.Global.Markers[i].position.lng(); //Longitude;
                //var pLevel = markers[iIndex].Level;
               
                //var level = point.Level;

                var late5 = Math.floor(lat * 1e5);
                var lnge5 = Math.floor(lng * 1e5);

                dlat = late5 - plat;
                dlng = lnge5 - plng;

                plat = late5;
                plng = lnge5;
                console.log(dlat,dlng);
                encodedPoints += Fit.Coder.EncodeSignedNumber(dlat) + Fit.Coder.EncodeSignedNumber(dlng);

                //notes : Comment out the line below to use the original enCoder given to us,
                /*if (i == Fit.Global.Markers.length - 1) {
                    encodedPoints += '';
                }
                else {
                    encodedPoints += Fit.Coder.EncodeRouteMode(Fit.Global.RouteModes[i]);
                }*/
                //encoded_levels += encodeNumber(level);
               
            }
            return encodedPoints;

            //$('#encodedLevels').val(encodedLevels);
            //$('#encodedPolyline').val(encodedPoints);
            //$('#totalDistance').val(Fit.Processor.GetTotalDistance('Meters'));

            //if (document.overlay) {
            //    Fit.Global.Map.removeOverlay(document.overlay);
            //}

            //if (points.length > 1) {
            //    document.overlay = GPolyline.fromEncoded({ color: "#0000FF",
            //        weight: 10,
            //        points: encoded_points,
            //        zoomFactor: 32,
            //        levels: encoded_levels,
            //        numLevels: 4
            //    });

            //    Fit.Global.Map.addOverlay(document.overlay);
            //}
        };


        // Decode the supplied encoded polyline and levels.
        this.Decode = function (encodedPoints) {
            //var encoded_points = document.getElementById('encodedPolyline').value;
            //var encoded_levels = document.getElementById('encodedLevels').value;
            if (encodedPoints === null || encodedPoints == "null" || encodedPoints.length == 0) {
                return;
            }

            Fit.Processor.Reset();

            var decodedPoints = Fit.Coder.DecodeLine(encodedPoints);
            console.log(decodedPoints);
            //notes: Uncomment the line below to use original decoder
            //Fit.Global.RouteModes = [0, 0, 0, 0, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 0, 0, 0, 0];
            count = decodedPoints.length;
            if (decodedPoints.length == 0) {
                return;
            }

            var bounds = new google.maps.LatLngBounds();

            for (var i = 0; i < decodedPoints.length; ++i) {
                //createPoint(enc_points[i][0], enc_points[i][1], enc_levels[i]);
                if(i == 0){
                    url = public_url+'css/fitness-mapper/css/images/map/start_marker.png';
                }else if(decodedPoints.length-1 == i){
                    url = public_url+'css/fitness-mapper/css/images/map/end_marker.png';
                }
                else{
                    url = public_url+'css/fitness-mapper/css/images/map/route-icon.png';
                }

                var latlng = new google.maps.LatLng(decodedPoints[i][0], decodedPoints[i][1]);
                Fit.Mapper.AddMarker(latlng, false, url);
                bounds.extend(latlng);
            }

            Fit.Global.Map.fitBounds(bounds);
            Fit.Elevation.UpdateElevation();

            //Fit.Global.RouteModes = [0, 0, 0, 0, 1, 1, 1, 1, 0, 0, 0, 0, 0, 1, 1, 1, 1, 0, 0, 0, 0];
            //var encodedString = Fit.Coder.CreateEncodings(false);
        };

        // Decode an encoded polyline into a list of lat/lng tuples.
        this.DecodeLine = function (encodedString) {
            var len = encodedString.length;
            var index = 0;
            var array = [];
            var lat = 0;
            var lng = 0;
            //var i = 0;
            //notes: Comment the line below to use original decoder
            //Fit.Global.RouteModes = [];

            while (index < len) {
                var b;
                var shift = 0;
                var result = 0;
                do {
                    b = encodedString.charCodeAt(index++) - 63;
                    result |= (b & 0x1f) << shift;
                    shift += 5;
                } while (b >= 0x20);
                var dlat = ((result & 1) ? ~(result >> 1) : (result >> 1));
                lat += dlat;

                shift = 0;

                result = 0;
                do {
                    b = encodedString.charCodeAt(index++) - 63;
                    result |= (b & 0x1f) << shift;
                    shift += 5;
                } while (b >= 0x20);
                var dlng = ((result & 1) ? ~(result >> 1) : (result >> 1));
                lng += dlng;

                array.push([lat * 1e-5, lng * 1e-5]);

                //notes: Comment the line below to use original decoder
                //Fit.Global.RouteModes[i++] = encodedString.charCodeAt(index++) - 63;
            }

            //notes: Comment the line below to use original decoder
            //Fit.Global.RouteModes.pop();
            return array;
        };

    };

    Fit.Processor = new function () {

        // Find estimated km per hour value in array and return it
        this.GetArrayEstKmPerHour = function (value, lookup) {
            for (var i = 0; i < lookup.length; i++) {
                if (lookup[i].ID == value) {
                    return lookup[i].EstKmPerHour;
                }
            }
            return null;
        };

        // Find activity METS value in array and return it
        this.GetEstKmPerHour = function (activityTypeID, exerciseTypeID) {

            if (activityTypeID == 1) {
                return Fit.Processor.GetArrayEstKmPerHour(exerciseTypeID, Fit.Global.RunningOptions);
            }
            else if (activityTypeID == 2) {
                return Fit.Processor.GetArrayEstKmPerHour(exerciseTypeID, Fit.Global.SwimmingOptions);
            }
            else if (activityTypeID == 8) {
                return Fit.Processor.GetArrayEstKmPerHour(exerciseTypeID, Fit.Global.CyclingOptions);
            }
            else {
                return Fit.Processor.GetArrayEstKmPerHour(activityTypeID, Fit.Global.ActivityOptions);
            }
        };

        this.GetEstimatedSeconds = function (activityTypeID, exerciseTypeID, distanceMeters) {
            var estimatedMetersPerSecond = Fit.Processor.GetEstKmPerHour(activityTypeID, exerciseTypeID) * 1000 / 3600;

            //console.log('estimatedMetersPerSecond', estimatedMetersPerSecond, distanceMeters, activityTypeID, exerciseTypeID);

            var totalSeconds = distanceMeters / estimatedMetersPerSecond;

            //console.log('totalSeconds--aaa', totalSeconds, distanceMeters, estimatedMetersPerSecond);

            return totalSeconds;
        };

        // TODO move to core
        this.formatTime = function (secs, showHoursIfZero) {
            var hours = Math.floor(secs / 60 / 60),
                minutes = Math.floor((secs - (hours * 60 * 60)) / 60),
                seconds = Math.round(secs - (hours * 60 * 60) - (minutes * 60));

            // if (hours == 0 && showHoursIfZero == false) {
            //     return ((minutes < 10) ? '0' + minutes : minutes) + ':' + ((seconds < 10) ? '0' + seconds : seconds);
            // } else {
                return ((hours < 10) ? '0' + hours : hours) + ':' + ((minutes < 10) ? '0' + minutes : minutes) + ':' + ((seconds < 10) ? '0' + seconds : seconds);
            // }

        };

        // Geocode an address and add a marker for the result
        this.AddAddress = function (isNewMarker, currentLocationAddress) {
            Fit.Global.SearchAddress = $('#fitSearchAddress').val();
            Fit.Global.GeocoderService.geocode({ 'address': Fit.Global.SearchAddress }, function (results, status) {
                if (status == google.maps.GeocoderStatus.OK) {

                    /* if(results.length > 1)
                    {
                        Fit.Processor.GetAddress();
                    }
                    else{ */
                    var latlng = results[0].geometry.location;
                    if (isNewMarker === true) {
                        Fit.Mapper.AddMarker(latlng, true);
                    }
                    //if(window.console)
                    //console.log('markers',Fit.Global.Markers);

                    if (Fit.Global.Markers.length > 1) {
                        var bounds = new google.maps.LatLngBounds();

                        for (var k = 0; k < Fit.Global.Markers.length; k++) {
                            bounds.extend(Fit.Global.Markers[k].getPosition());
                        }
                        /*for (var i in Fit.Global.Markers) {
                            bounds.extend(Fit.Global.Markers[i].getPosition());
                        }*/
                        bounds.extend(latlng);
                        Fit.Global.Map.fitBounds(bounds);
                    } else {
                        Fit.Global.Map.fitBounds(results[0].geometry.viewport);
                    }
                    /* } */
                } else if (status == google.maps.GeocoderStatus.ZERO_RESULTS) {
                    alert("Sorry, could not find that address, resolving based on current location");
                    Fit.Processor.GetAddress();
                } else {
                    alert("Address lookup failed");
                }
            });
        };

        this.GetAddress = function () {
            if (Fit.Global.CurrentLatLng == null) {
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(function (position) {
                        var lat = position.coords.latitude;
                        var lng = position.coords.longitude;
                        Fit.Global.CurrentLatLng = new google.maps.LatLng(lat, lng);
                    });
                }
                else {
                    alert("Geolocation is not supported by this browser.");
                }
            }

            Fit.Global.GeocoderService.geocode({ 'latLng': Fit.Global.CurrentLatLng }, function (results, status) {
                console.log('hi');
                if (status == google.maps.GeocoderStatus.OK) {
                    if (results[1]) {

                        //Fit.Processor.AddAddress(false, results[1].formatted_address);
                        Fit.Global.SearchAddress = results[1].formatted_address;
                        Fit.Global.GeocoderService.geocode({ 'address': Fit.Global.SearchAddress }, function (results, status) {
                            if (status == google.maps.GeocoderStatus.OK) {
                                var latlng = results[0].geometry.location;
                                if (isNewMarker === true) {
                                    Fit.Mapper.AddMarker(latlng, true);
                                }

                                if (Fit.Global.Markers.length > 1) {
                                    var bounds = new google.maps.LatLngBounds();
                                    for (var i in Fit.Global.Markers) {
                                        bounds.extend(Fit.Global.Markers[i].getPosition());
                                    }
                                    bounds.extend(latlng);
                                    Fit.Global.Map.fitBounds(bounds);
                                } else {
                                    Fit.Global.Map.fitBounds(results[0].geometry.viewport);
                                }
                            } else if (status == google.maps.GeocoderStatus.ZERO_RESULTS) {
                                alert("Sorry, could not find that address.");
                                //Fit.Processor.GetAddress();
                            } else {
                                alert("Address lookup failed");
                            }
                        });
                    }
                }
                else {
                    alert("Sorry, could not find that address.");
                }
            });
        };


        // Submit a directions request for the path between points and an
        // elevation request for the path once returned
        this.DrawDecodedRoute = function (currentPosition, path) {
            if (currentPosition == Fit.Global.Markers.length) {
                Fit.Global.CachedPath = path.slice(0);
                Fit.Processor.GetElevation(path,'draggable');
                return;
            }
            var currentMode = currentPosition > 0 ? Fit.Global.RouteModes[currentPosition - 1] : Fit.Global.DirectMode;
            if (currentPosition == 0) {
                path[currentPosition] = [Fit.Global.Markers[currentPosition].getPosition()];
                Fit.Processor.DrawDecodedRoute(currentPosition + 1, path);
            }
            if (currentPosition > 0) {
                if (currentMode == Fit.Global.DirectMode) {
                    path[currentPosition] = [Fit.Global.Markers[currentPosition].getPosition()];
                    Fit.Processor.DrawDecodedRoute(currentPosition + 1, path);
                }
                else {
                    var counter = 0;
                    var wayPoints = [];
                    for (var i = currentPosition + 1; i < Fit.Global.Markers.length ; i++) {
                        if (Fit.Global.RouteModes[i - 2] == Fit.Global.DriveMode) {
                            counter++;
                            wayPoints.push({ location: Fit.Global.Markers[i - 1].getPosition(), stopover: true });
                            if (counter == 8) {
                                break;
                            }
                        }
                        else {
                            break;
                        }

                    }
                    var request = {
                        origin: Fit.Global.Markers[currentPosition - 1].getPosition(),
                        destination: Fit.Global.Markers[currentPosition + counter].getPosition(),
                        travelMode: google.maps.DirectionsTravelMode.DRIVING,
                        waypoints: wayPoints
                    };

                    Fit.Global.DirectionsService.route(request, function (response, status) {
                        if (status == google.maps.DirectionsStatus.OK) {
                            path[currentPosition] = response.routes[0].overview_path;
                            Fit.Processor.DrawDecodedRoute(currentPosition + counter + 1, path);
                        } else if (status == google.maps.DirectionsStatus.ZERO_RESULTS) {
                            alert("Could not find a route between these points");
                        } else {
                            alert("Directions request failed: " + status);
                        }
                    });
                }
            }
        };

        this.DrawPolyline = function (currentPosition, path) {
            if (Fit.Global.CachedPath.length > 0) {
                Fit.Global.CachedPath[currentPosition] = path[currentPosition].slice(0);
                var pathToAdd = [];
                pathToAdd.push(Fit.Global.Markers[currentPosition - 1].getPosition());
                pathToAdd.push(Fit.Global.CachedPath[currentPosition]);
                Fit.Mapper.DebugLog("Cached Path" + Fit.Global.CachedPath);

                Fit.Mapper.DebugLog("Drawing from :" + Fit.Global.CachedPath[currentPosition - 1] + "To" + Fit.Global.CachedPath[currentPosition]);
                Fit.Processor.GetElevation(pathToAdd);
            }
            else {
                Fit.Global.CachedPath = path.slice(0);
                Fit.Mapper.DebugLog("Cached Path Val" + Fit.Global.CachedPath);

            }
        };

        this.DrawRoute = function (currentPosition, path) {

            Fit.Mapper.DebugLog("Current Pos" + currentPosition);
            if (currentPosition == Fit.Global.Markers.length) {
                // Lets do some refactoring here; If the currentPosition == CachedPath length, don't plot any line
                if (Fit.Global.CachedPath.length < currentPosition) {
                    Fit.Processor.DrawPolyline(currentPosition - 1, path);
                }

                return;
            }
            var currentMode = currentPosition > 0 ? Fit.Global.RouteModes[currentPosition - 1] : Fit.Global.DirectMode;
            if (currentPosition == 0) {
                path[currentPosition] = [Fit.Global.Markers[currentPosition].getPosition()];
                Fit.Processor.DrawRoute(currentPosition + 1, path);
            }
            if (currentPosition > 0) {
                if (currentMode == Fit.Global.DirectMode) {
                    path[currentPosition] = [Fit.Global.Markers[currentPosition].getPosition()];
                    Fit.Processor.DrawPolyline(currentPosition, path);
                    Fit.Processor.DrawRoute(currentPosition + 1, path);
                }
                else {
                    var counter = 0;
                    var wayPoints = [];
                    for (var i = currentPosition + 1; i < Fit.Global.Markers.length ; i++) {
                        if (Fit.Global.RouteModes[i - 2] == Fit.Global.DriveMode) {
                            counter++;
                            wayPoints.push({ location: Fit.Global.Markers[i - 1].getPosition(), stopover: true });
                            if (counter == 8) {
                                break;
                            }
                        }
                        else {
                            break;
                        }

                    }
                    var request = {
                        origin: Fit.Global.Markers[currentPosition - 1].getPosition(),
                        destination: Fit.Global.Markers[currentPosition + counter].getPosition(),
                        travelMode: google.maps.DirectionsTravelMode.DRIVING,
                        waypoints: wayPoints
                    };

                    Fit.Global.DirectionsService.route(request, function (response, status) {
                        if (status == google.maps.DirectionsStatus.OK) {
                            path[currentPosition] = response.routes[0].overview_path;
                            if (counter == 0) {
                                if (Fit.Global.Markers.length >= currentPosition + counter) {
                                    Fit.Mapper.DebugLog("Drawing road route at" + currentPosition);
                                    Fit.Processor.DrawPolyline(currentPosition + counter, path);
                                }
                                Fit.Processor.DrawRoute(currentPosition + 1, path);
                            }
                            else {
                                Fit.Processor.DrawRoute(currentPosition + counter, path);
                            }
                        } else if (status == google.maps.DirectionsStatus.ZERO_RESULTS) {
                            alert("Could not find a route between these points");
                        } else {
                            alert("Directions request failed: " + status);
                        }
                    });
                }
            }
        };

        this.GetElevation = function (path,draggable=null) {
            var pathtoSend = [];
            for (var i = 0 ; i < path.length; i++) {
                if (path[i] != null || path[i] != undefined) {
                    pathtoSend = pathtoSend.concat(path[i]);
                }
            }
            if(draggable == null){
                Fit.Global.GlobalPolylines++;
            }else{
                Fit.Global.GlobalPolylines = 1;
            }
            Fit.Mapper.DebugLog("GlobalPolyline Expected:" + Fit.Global.GlobalPolylines);

            Fit.Global.IsCacheElevation = ((path.length == 2) && (!Fit.Global.IsNotToCache)) ? true : false;
            Fit.Global.IsNotToCache = false;

            Fit.Global.ElevationService.getElevationAlongPath({
                path: pathtoSend,
                samples: Fit.Global.SAMPLES
            }, Fit.Elevation.PlotElevation);
        };

        this.UpdateDistance = function () {

            // Store current distance	
            Fit.Global.CurrentDistanceMeters = Fit.Processor.GetTotalDistance('Meters');

            // Update label
            console.log(Fit.Global.CurrentDistanceMeters,Fit.Global.ConversionFactor());
            $('#fitDistance').html((Fit.Global.CurrentDistanceMeters * Fit.Global.ConversionFactor()).toFixed(2));

            // Update calories
            var ex = $("#selectedExercise option:selected").val();
            var act = $("#selectedWorkout option:selected").val();

            // Estimate how long the distance takes to travel based on the exercise / activity types choosen
            var totalSeconds = Fit.Processor.GetEstimatedSeconds(act, ex, Fit.Global.CurrentDistanceMeters);

            // Display estimated time
            $("#map-time").html(Fit.Processor.formatTime(totalSeconds, false));
            $("#selectedDuration").val(Fit.Processor.formatTime(totalSeconds, false))
            // Calculate and display estimated calories
            Fit.Calories.GetEstimatedMETS(act, ex, totalSeconds, 1500);
        };

        this.UpdateCumulativeElevation = function () {

            var cumulativeElevation = 0;
            var lastElevation = null;
            var maxElevation = null;

            // Loop through cache of elevations and calculate
            for (var i = 0; i < Fit.Global.CachedElevations.length; i++) {

                // From the second elevation point onwards..
                if (lastElevation != null) {

                    // If we have travelled uphill, record the cumulative uphill travel
                    if (lastElevation < Fit.Global.CachedElevations[i].elevation) {
                        cumulativeElevation += (Fit.Global.CachedElevations[i].elevation - lastElevation);
                    }
                }

                // Record the highest point we got to
                if (maxElevation != null) {
                    if (Fit.Global.CachedElevations[i].elevation > maxElevation) {
                        maxElevation = Fit.Global.CachedElevations[i].elevation;
                    }
                }

                // Store elevation
                lastElevation = Fit.Global.CachedElevations[i].elevation;
            }

            // Now update global
            Fit.Global.CumulativeElevationMeters = cumulativeElevation;
            Fit.Global.MaxElevationMeters = maxElevation;

            // Update label
            $('#map-altitude').html(Fit.Global.CumulativeElevationMeters.toFixed() + "m");
        };

        this.GetTotalDistance = function (unit) {
            var distance = 0;
            var metricConversion = 1;

            // Miles in a single meter
            //if (unit == 'Miles') { metricConversion = 0.000621371192; }

            // Kilometers in a single meter 
            //if (unit == 'Kilometers') { metricConversion = 0.001; }

            // Get distance between points in meters

            for (var i = 1; i < Fit.Global.CachedElevations.length; i++) {
                distance += Fit.DistanceMarkers.CalculateDistance(Fit.Global.CachedElevations[i - 1].location, Fit.Global.CachedElevations[i].location);

            }
            distance = distance * 1000;


            /* for (var i = 1; i < Fit.Global.Markers.length; i++) {
                distance += google.maps.geometry.spherical.computeDistanceBetween(Fit.Global.Markers[i].position, Fit.Global.Markers[i - 1].position);
            } */

            // Convert to desired distance measurement system
            var totalDistance = (distance * metricConversion);
            Fit.Mapper.DebugLog("Total Distance:" + distance);
            return totalDistance;
        };

        // Clear all overlays, reset the array of points, and hide the chart
        this.Reset = function () {
            Fit.Mapper.RemovePolyline();

            for (var i in Fit.Global.Markers) {

                if (Fit.Global.Markers[i]) {
                    if (Fit.Global.Markers[i].setMap) {
                        Fit.Global.Markers[i].setMap(null);
                    }
                }
            }

            // Remove distance markers
            Fit.DistanceMarkers.RemoveAllDistanceMarkers();

            Fit.Global.Markers = [];
            Fit.Global.RouteModes = [];
            Fit.Global.CachedPath = [];
            Fit.Global.CachedElevations = [];
            Fit.Global.GlobalPolylines = 0;
            Fit.Mapper.DebugLog("Polylines Set to zero" + Fit.Global.GlobalPolylines);

            $('#fitAltChart').css('display', 'none');

            Fit.ScriptHandlers.ClearPanelValues();
            Fit.Processor.UpdateDistance();

            // Reset elevations
            Fit.Global.MaxElevationMeters = 0;
            Fit.Global.CumulativeElevationMeters = 0;
            Fit.Processor.UpdateCumulativeElevation();
        };
    };


    Fit.Thread = new function () {

        this.CallBackFun = null;
        this.Reset = function () {
            Fit.Thread.Counter = 0;
            Fit.Thread.CallBackFun = null;
        };

        this.Counter = 0;

        this.IndexDict = [];

        this.Init = function (callBackFun) {
            this.CallBackFun = callBackFun;
        };

        this.CheckAndExecute = function (obj) {
            if (Fit.Thread.Counter == 0) {
                Fit.Thread.CallBackFun(obj);
            }
        };
    };

    Fit.Search = new function () {

        this.DoSearch = function () {
            var isNewMarker = Fit.Global.Markers.length === 0;
            Fit.Processor.AddAddress(false, '');
        };
    };

    Fit.Elevation = new function () {

        this.AddElevation = function () {
            Fit.Processor.DrawRoute(Fit.Global.Markers.length - 1, []);
        };

        // Trigger the elevation query for point to point
        // or submit a directions request for the path between points
        this.UpdateElevation = function () {
            if (Fit.Global.CalcElevation === false)
                return;

            if (Fit.Global.Markers.length > 1) {
                Fit.Processor.DrawDecodedRoute(0, []);
            }
            else if (Fit.Global.Markers.length == 1) {
                Fit.Mapper.RemovePolyline();
            }
        };

        // This is for a single polyline segment.  
        // Takes an array of ElevationResult objects, draws the path on the map
        // and plots the elevation profile on a GViz ColumnChart.
        this.PlotElevation = function (results) {
            // Can't do much if this fails, which it does sometimes
            if (results == null)
                return;

            Fit.Global.Elevations = results.slice(0);

            // console.log(results.length, Fit.Global.Elevations.length, Fit.Global.CachedElevations.length, Fit.Global.IsCacheElevation);

            if (Fit.Global.Polyline.length < Fit.Global.Markers.length - 1) {
                Fit.Mapper.DebugLog("Drawing Polyline" + (Fit.Global.Polyline.length + 1));
                /* var polyline = Fit.Global.Polyline.pop();
                if(typeof polyline !== 'undefined') {
                    polyline.setMap(null);
                }
         */

                //debugger;
                if (Fit.Global.IsCacheElevation) {
                    Fit.Global.CachedElevations = Fit.Global.CachedElevations.concat(Fit.Global.Elevations);
                }
                else {
                    Fit.Global.CachedElevations = Fit.Global.Elevations.slice(0);
                }
                Fit.Mapper.DebugLog("Elevations" + Fit.Global.CachedElevations.length);

                //var cumulativeElevation = 0;
                //var lastElevation = Fit.Global.CumulativeElevationMeters;
                //var maxElevation = Fit.Global.MaxElevationMeters;

                var path = [];
                for (var i = 0; i < results.length; i++) {

                    // From the second elevation point onwards..
                    /*if (lastElevation != null) {
    
                        // If we have travelled uphill, record the cumulative uphill travel
                        if (lastElevation < Fit.Global.Elevations[i].elevation) {
                            cumulativeElevation += (Fit.Global.Elevations[i].elevation - lastElevation);
                        }
                    }
    
                    // Record the highest point we got to
                    if (maxElevation != null) {
                        if (Fit.Global.Elevations[i].elevation > maxElevation) {
                            maxElevation = Fit.Global.Elevations[i].elevation;
                        }
                    }*/

                    path.push(Fit.Global.Elevations[i].location);

                    // Store elevation
                    //lastElevation = Fit.Global.Elevations[i].elevation;
                }
               

                // Now update global
                //Fit.Global.CumulativeElevationMeters = cumulativeElevation;
                //Fit.Global.MaxElevationMeters = maxElevation;

                // Show total cumulative elevation
                //$("#map-altitude").html(cumulativeElevation.toFixed() + "m");

                Fit.Processor.UpdateCumulativeElevation();

                Fit.Mapper.DebugLog("Polyline drawn : " + Fit.Global.Elevations.length);

                var polyLine = new google.maps.Polyline({
                    path: path,
                    strokeColor: "#F64C1E",
                    strokeOpacity: "0.7",
                    strokeWeight: 7,
                    map: Fit.Global.Map
                });

                Fit.Global.Polyline.push(polyLine);

                /*var data = new google.visualization.DataTable();
                data.addColumn('string', 'Sample');
                data.addColumn('number', 'Elevation');
                for (var i = 0; i < Fit.Global.CachedElevations.length; i++) {
                    data.addRow(['', Fit.Global.CachedElevations[i].elevation]);
                }*/

                /*$('#fitAltChart').css('display', 'block');
                Fit.Global.Chart.draw(data, {
                    width: 800,
                    height: 200,
                    legend: 'none',
                    titleY: 'Elevation (m)',
                    focusBorderColor: '#00ff00'
                });*/

                Fit.Processor.UpdateDistance();
                if (Fit.Global.IsDistanceMarkerVisible) {
                    if (Fit.Global.EndDragged || Fit.Global.IsAddingExistingPoint) {
                        Fit.DistanceMarkers.OldDrawMarkers();
                        Fit.Global.IsAddingExistingPoint = false;
                    }
                    else {
                        Fit.DistanceMarkers.DrawMarkers();
                    }
                }
            }
        };
    };

    Fit.DistanceMarkers = new function () {

        this.AddDistanceMarker = function (latlng, index) {
            var marker = new google.maps.Marker({
                position: latlng,
                map: Fit.Global.Map,
                icon: public_url+'css/fitness-mapper/css/images/markers/' + (index + 1) + '.png'
            });
            Fit.Global.DistanceMarkers[index] = marker;
        };

        this.OldDrawMarkers = function () {
            var distance = 0;
            var j = 0;

            Fit.DistanceMarkers.RemoveAllDistanceMarkers();

            var distanceUnit = Math.ceil((Fit.Processor.GetTotalDistance('Metrers') * Fit.Global.ConversionFactor()) / 10);
            distanceUnit = (distanceUnit == 0) ? 1 : distanceUnit;

            for (var i = 0; i < Fit.Global.Elevations.length - 1; i++) {
                distance += Fit.DistanceMarkers.CalculateDistance(Fit.Global.Elevations[i].location, Fit.Global.Elevations[i + 1].location);
                if (distance > distanceUnit) {
                    Fit.DistanceMarkers.AddDistanceMarker(Fit.Global.Elevations[i].location, j);
                    //notes: I think the distance markers represent actual distance from the starting point.
                    // So, if the total distance is say 20 KM and the mile marker is 2KM apart, the first mile marker must show 2 (which represents that it is @ 2 KM from start)
                    // Give a try by uncommenting the line below.?
                    Fit.Global.DistanceMarkers[j].setIcon(public_url+'css/fitness-mapper/css/images/markers/' + distanceUnit * (j + 1) + '.png');
                    j++;
                    distance = 0;
                }
            }

            //		if (Fit.Global.IsDistanceMarkerVisible) {
            //            Fit.DistanceMarkers.ShowDistanceMarkers();
            //        }
            //        else {
            //            Fit.DistanceMarkers.HideDistanceMarkers();
            //        }
        };

        this.GetDistanceElevation = function (results) {
            var distance = 0;
            var j = 0;
            Fit.Global.ElevationsForMarkers = results;

            Fit.DistanceMarkers.RemoveAllDistanceMarkers();

            var distanceUnit = Math.ceil((Fit.Processor.GetTotalDistance('Metrers') * Fit.Global.ConversionFactor()) / 10);
            distanceUnit = (distanceUnit == 0) ? 1 : distanceUnit;

            if (Fit.Global.ElevationsForMarkers != null || Fit.Global.ElevationsForMarkers != undefined) {
                for (var i = 0; i < Fit.Global.ElevationsForMarkers.length - 1; i++) {
                    distance += Fit.DistanceMarkers.CalculateDistance(Fit.Global.ElevationsForMarkers[i].location, Fit.Global.ElevationsForMarkers[i + 1].location);
                    if (distance > distanceUnit) {
                        Fit.DistanceMarkers.AddDistanceMarker(Fit.Global.ElevationsForMarkers[i].location, j);
                        //notes: I think the distance markers represent actual distance from the starting point.
                        // So, if the total distance is say 20 KM and the mile marker is 2KM apart, the first mile marker must show 2 (which represents that it is @ 2 KM from start)
                        // Give a try by uncommenting the line below.?
                        Fit.Global.DistanceMarkers[j].setIcon(public_url+'css/fitness-mapper/css/images/markers/' + distanceUnit * (j + 1) + '.png');
                        j++;
                        distance = 0;
                    }
                }
            }
            else {
                // debugger;
            }
        };

        this.DrawMarkers = function (useElevation) {

            if (useElevation) {
                Fit.DistanceMarkers.OldDrawMarkers();
            }
            else {
                var distance = 0;
                var j = 0;
                //Fit.Global.ElevationsForMarkers = results;

                Fit.DistanceMarkers.RemoveAllDistanceMarkers();

                var distanceUnit = Math.ceil((Fit.Processor.GetTotalDistance('Metrers') * Fit.Global.ConversionFactor()) / 10);
                distanceUnit = (distanceUnit == 0) ? 1 : distanceUnit;

                Fit.Mapper.DebugLog("Drawing distance markers");
                for (var i = 0; i < Fit.Global.CachedElevations.length - 1; i++) {
                    distance += Fit.DistanceMarkers.CalculateDistance(Fit.Global.CachedElevations[i].location, Fit.Global.CachedElevations[i + 1].location);
                    if (distance > distanceUnit) {
                        Fit.Mapper.DebugLog("Adding Marker  " + distance + "  @" + i);
                        Fit.DistanceMarkers.AddDistanceMarker(Fit.Global.CachedElevations[i].location, j);
                        //notes: I think the distance markers represent actual distance from the starting point.
                        // So, if the total distance is say 20 KM and the mile marker is 2KM apart, the first mile marker must show 2 (which represents that it is @ 2 KM from start)
                        // Give a try by uncommenting the line below.?
                        Fit.Global.DistanceMarkers[j].setIcon(public_url+'css/fitness-mapper/css/images/markers/' + distanceUnit * (j + 1) + '.png');
                        j++;
                        distance = 0;
                    }

                }
                // Done to get the full elevation for the distance markers
                /* var pathtoSend = [];
                for (var i = 0 ; i < Fit.Global.CachedPath.length; i++) {
                    if (Fit.Global.CachedPath[i] != null || Fit.Global.CachedPath[i] != undefined) {
                        pathtoSend = pathtoSend.concat(Fit.Global.CachedPath[i]);
                    }
                }
    
                Fit.Global.ElevationService.getElevationAlongPath({
                    path: pathtoSend,
                    samples: Fit.Global.SAMPLES
                }, Fit.DistanceMarkers.GetDistanceElevation); */
            }
        };

        this.ToggleUpdateDistanceMarkers = function () {
            //notes: Also update the distance markers on toggle of unit if they are visible
            if (Fit.Global.IsDistanceMarkerVisible) {
                Fit.DistanceMarkers.ShowDistanceMarkers();
            }
            else {
                Fit.DistanceMarkers.HideDistanceMarkers();
            }
        };

        this.HideDistanceMarkers = function () {
            for (var index in Fit.Global.DistanceMarkers) {
                Fit.Global.DistanceMarkers[index].setVisible(false);
            }
        };

        this.ShowDistanceMarkers = function () {
            Fit.DistanceMarkers.DrawMarkers();
            Fit.DistanceMarkers.RedrawMarkers();
        };

        this.RemoveAllDistanceMarkers = function () {
            for (var i = 0; i < Fit.Global.DistanceMarkers.length; i++) {
                Fit.Global.DistanceMarkers[i].setMap(null);
            }
            Fit.Global.DistanceMarkers = [];
        };


        this.RedrawMarkers = function () {
            var currentZoomLevel = Fit.Global.Map.getZoom();
            // Zooming out 
            if (currentZoomLevel < 12) {
                Fit.Global.WrappedZoomLevel = 1;
            }
            else if (currentZoomLevel < 13) {
                Fit.Global.WrappedZoomLevel = 2;
            }
            else {
                Fit.Global.WrappedZoomLevel = 3;
            }

            for (var i = 0; i < Fit.Global.DistanceMarkers.length; i++) {
                Fit.Global.DistanceMarkers[i].setVisible(true);
            }

            switch (Fit.Global.WrappedZoomLevel) {
                case 1:
                    {
                        for (var i = 0; i < Fit.Global.DistanceMarkers.length; i++) {
                            if (i % 3 != 0) {
                                Fit.Global.DistanceMarkers[i].setVisible(false);
                            }
                        }
                    }
                    break;
                case 2:
                    {
                        for (var i = 0; i < Fit.Global.DistanceMarkers.length; i++) {
                            if (i % 2 != 0) {
                                Fit.Global.DistanceMarkers[i].setVisible(false);
                            }
                        }
                    }
                    break;

                case 3:
                    {
                    }
                    break;
            }
        };

        this.CalculateDistance = function (start, end) {
            var Radius = 6371;//radius of earth in Km         
            var lat1 = start.lat();
            var lat2 = end.lat();
            var lng1 = start.lng();
            var lng2 = end.lng();
            var dLat = Math.toRadians(lat2 - lat1);
            var dLng = Math.toRadians(lng2 - lng1);

            var a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
            Math.cos(Math.toRadians(lat1)) * Math.cos(Math.toRadians(lat2)) *
            Math.sin(dLng / 2) * Math.sin(dLng / 2);

            var c = 2 * Math.asin(Math.sqrt(a));

            return Radius * 1000 * c * Fit.Global.ConversionFactor();
        };

    };

    Fit.Handlers = new function () {

        // Trigger a geocode request when the Return key is
        // pressed in the address field
        this.SearchKeyPress = function (evt) {
            var code = evt.keyCode ? evt.keyCode : evt.which;
            if (code == 13) {
                Fit.Search.DoSearch();
                return false;
            }
            else {
                return true;
            }
        };
    };

    Fit.ScriptHandlers = new function () {

        this.RepopluateDropdowns = function (workoutSelection) {
            var options = null;
            var defaultExercise = 0;

            $("#selectedExercise").empty();
            $("#exerciseSelector").empty();

            $("#selectedExerciseDiv").show();
            $("#exerciseSelectorDiv").show();

            switch (workoutSelection) {
                case '1': options = Fit.Global.RunningOptions;
                    defaultExercise = 6;
                    break;
                case '8': options = Fit.Global.CyclingOptions;
                    defaultExercise = 21;
                    break;
                case '2': options = Fit.Global.SwimmingOptions;
                    defaultExercise = 17;
                    break;
                default:
                    $("#selectedExerciseDiv").hide();
                    $("#exerciseSelectorDiv").hide();
                    break;
            }

            if (options != null) {
                $.each(options, function (id, option) {
                    $('<option>').val(option.ID).text(option.Name).appendTo('#selectedExercise');
                    $('<option>').val(option.ID).text(option.Name).appendTo('#exerciseSelector');
                });
            }

            $("#selectedExercise option:selected").val(defaultExercise);
            $("#exerciseSelector option:selected").val(defaultExercise);
        };

        this.ClearPanelValues = function () {
            $("#selectedWorkout").val(1);
            $("#workoutSelector").val(1);
            Fit.ScriptHandlers.RepopluateDropdowns('1');

            $("#durationSelector").val('');
            $("#selectedDuration").val('');
            $("#selectedDate").val('');
        };
    };
});

$(document).on('click','.editMap',function(){
    var id = $(this).data('id');
    var action = $(this).data('action');
    var tab = $('.tabb').find('li.active');
    var text = tab.find('a').text();
    if(text == "My route"){
        tab.removeClass('active');
        $('#my-route').removeClass('active in');
     var createRouteTab =  $('.tabb').find('.createRoute');
     createRouteTab.addClass('active');
     $('#create-route').addClass('active in');
    }
    $.post(public_url+'edit/fitness-map', {id:id}, function(data){
        flightPlanCoordinates=  Fit.Coder.Decode(data.polyline);
        // const flightPath = new google.maps.Polyline({
        //     path: flightPlanCoordinates,
        //     geodesic: true,
        //     strokeColor: "#F64C1E",
        //     strokeOpacity: "0.7",
        //     strokeWeight: 7,
        // });
        // flightPath.setMap(Fit.Global.Map);

        if(action == 'edit'){
            $("#map_id").val(data.id);
        }else if(action == 'copy'){
            $("#map_id").val('');
        }
        Fit.ScriptHandlers.RepopluateDropdowns(''+data.workout+'');
        $('#selectedExercise [value='+data.exercise+']').attr('selected', 'true');
        $('#selectedWorkout [value='+data.workout+']').attr('selected', 'true');
        // $("#selectedExercise").val(data.exercise);
        // $("#selectedWorkout").val(data.workout);
        $("#selectedDuration").val(data.duration);
        $("#fitSearchAddress").val(data.city);
        $("#fitRouteTitle").val(data.name)
        $("#fitRouteDesc").val(data.description)
        $("#fitRouteKeywords").val(data.keywords)
        $('#exerciseSelector [value='+data.exercise+']').attr('selected', 'true');
        $('#workoutSelector [value='+data.workout+']').attr('selected', 'true');
        // $("#exerciseSelector").val(data.exercise);
        // $("#workoutSelector").val(data.workout);
        $("#durationSelector").val(data.duration);
        $('#fitDistance').html((data.km * 0.001).toFixed(2));
        
    });
})

$('.polyline').on('click',function(){
    Fit.Processor.Reset();
    var polyline = $(this).data('polyline');
    flightPlanCoordinates=  Fit.Coder.Decode(polyline);
    const flightPath = new google.maps.Polyline({
        path: flightPlanCoordinates,
        geodesic: true,
        strokeColor: "#F64C1E",
        strokeOpacity: "0.7",
        strokeWeight: 7,
      });
    
      flightPath.setMap(Fit.Global.Map);
})
$(document).on('click',".deleteMap",function() {
    var id = $(this).data('id');
    
    swal({
        type: 'warning',
        title: 'Are you sure to delete this map?',
        showCancelButton: true,
        allowOutsideClick: false,
        showConfirmButton: true,
        confirmButtonColor: '#ff4401',
    }, 
    function(isConfirm){
        if(isConfirm){
            $('.waitingShield').show();
            $.get(public_url+'delete/map/'+id, function(data){
                $('.waitingShield').hide();
                if(data.status == 'success'){
                    swal({
                        type: 'success',
                        title: data.message,
                        allowOutsideClick: false,
                        showCancelButton: false,
                        confirmButtonText: 'Yes',
                        confirmButtonColor: '#ff4401',
                        // cancelButtonText: "No"
                    }, 
                    function(isConfirm){
                        if(isConfirm){
                            $('#data-table').load(location.href + " #data-table");
                        }
                    });
                }
            });
        }
    });
})

$(document).on('click',".deleteChallenge",function() {
    var id = $(this).data('id');
    
    swal({
        type: 'warning',
        title: 'Are you sure to delete this Challenge?',
        showCancelButton: true,
        allowOutsideClick: false,
        showConfirmButton: true,
        confirmButtonColor: '#ff4401',
    }, 
    function(isConfirm){
        if(isConfirm){
            $('.waitingShield').show();
            $.get(public_url+'delete/challenge/'+id, function(data){
                $('.waitingShield').hide();
                if(data.status == 'success'){
                    swal({
                        type: 'success',
                        title: data.message,
                        allowOutsideClick: false,
                        showCancelButton: false,
                        confirmButtonText: 'Yes',
                        confirmButtonColor: '#ff4401',
                        // cancelButtonText: "No"
                    }, 
                    function(isConfirm){
                        if(isConfirm){
                            $('#data-table1').load(location.href + " #data-table1");
                        }
                    });
                }else{
                    swal({
                        type: 'warning',
                        title: data.message,
                        allowOutsideClick: false,
                        showCancelButton: false,
                        confirmButtonText: 'Yes',
                        confirmButtonColor: '#ff4401',
                        // cancelButtonText: "No"
                    }, 
                    function(isConfirm){
                        if(isConfirm){
                            $('#data-table1').load(location.href + " #data-table1");
                        }
                    });
                }
            });
        }
    });
})

$(document).on("click",".reset-filter",function() {
    $("#filter-map").val('null');
    $(".filter").trigger('click');
})


$('.changeMap').on('click',function(){
    Fit.Global.Map.setMapTypeId($(this).data('map'));
    
})



function polylineMapRoute(encodedString) {
var len = encodedString.length;
var index = 0;
var array = [];
var lat = 0;
var lng = 0;
//var i = 0;
//notes: Comment the line below to use original decoder
//Fit.Global.RouteModes = [];

while (index < len) {
    var b;
    var shift = 0;
    var result = 0;
    do {
        b = encodedString.charCodeAt(index++) - 63;
        result |= (b & 0x1f) << shift;
        shift += 5;
    } while (b >= 0x20);
    var dlat = ((result & 1) ? ~(result >> 1) : (result >> 1));
    lat += dlat;

    shift = 0;

    result = 0;
    do {
        b = encodedString.charCodeAt(index++) - 63;
        result |= (b & 0x1f) << shift;
        shift += 5;
    } while (b >= 0x20);
    var dlng = ((result & 1) ? ~(result >> 1) : (result >> 1));
    lng += dlng;

    array.push({lat: lat * 1e-5, lng: lng * 1e-5});

}

return array;
}


function plotElevationNew(elevations, status) {
    var path= polylineMapRoute($("#polyline").val());
    var elevationData = $('#elevationData').val();
    elevationData = JSON.parse(elevationData)

    var elevationLevelData = $('#elevationLevelData').val();
    elevationLevelData = JSON.parse(elevationLevelData)

var array_data = [];
var lable_data = [];
var startLat = '';
var startLon  = '';

// $.each(path,function(key,value){
//     if(key == 0){
//      startLat = value['lat'];
//      startLon = value['lng'];
//     //  lable_data.push(0);
//     }
//     // if(key != 0){
//     //     lable_data.push(((google.maps.geometry.spherical.computeDistanceBetween(new google.maps.LatLng(path[key]['lat'],path[key]['lng']), new google.maps.LatLng(path[key-1]['lat'],path[key-1]['lng'])))*0.000621371).toFixed(2));
//     // }
//     lable_data.push(((google.maps.geometry.spherical.computeDistanceBetween(new google.maps.LatLng(startLat,startLon), new google.maps.LatLng(value['lat'], value['lng'])))*0.000621371).toFixed(2));
// })

for (var i = 0; i < elevationData.length; i++) {
    array_data.push(elevationData[i]*3.281);
    // array_data.push(elevationData[i]);
}
for (var i = 0; i < elevationLevelData.length; i++) {
    lable_data.push(elevationLevelData[i]);
}
// console.log(lable_data);
// lable_data.sort(function(a, b) {
//     return a - b;
//  });
//   console.log(lable_data);
$('#minValue').text(parseInt(array_data[0]));
$('#maxValue').text(parseInt(Math.max(...array_data)));
$('#gain_value').text(parseInt(Math.max(...array_data)) - parseInt(array_data[0]));
$('#gain_feet').text(parseInt(Math.max(...array_data)) - parseInt(array_data[0])+ ' FT');

// console.log(array_data,,Math.max(...array_data));

let draw = Chart.controllers.line.prototype.draw;
	Chart.controllers.line = Chart.controllers.line.extend({
    draw: function() {
        draw.apply(this, arguments);
        let ctx = this.chart.chart.ctx;
        let _stroke = ctx.stroke;
        ctx.stroke = function() {
            ctx.save();
            ctx.shadowColor = '#D7D4D4';
            ctx.shadowBlur = 6;
			ctx.shadowOffsetX = 0;
			ctx.shadowOffsetY = 30;
            _stroke.apply(this, arguments)
            ctx.restore();
        }
    }
});

var ctx = document.getElementById('elevation_chart').getContext('2d');

var myChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: lable_data,
        datasets: [{
            label: 'Elevation(mi)',
            data: array_data,
            backgroundColor:'transparent',
            borderColor:'#FF0000',
            borderWidth: 2
        }]
    },
    options: {
        legend: {
            display: true,
            position: 'bottom',
        },
        tooltips: {
            mode: 'index',
        },
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero: false
                }
            }],
            xAxes: [{
                gridLines: { 
                    display: false 
                } ,
                tickes: {
                    autoskip: true,
                },
                gridLines: {
                  display: false,
                },
            }],
        }
    }
});

}


   
$(window).on('load', function() {
    var url = window.location.pathname;
    if(url.split('/')[1] == 'editRoute'){
        flightPlanCoordinates=  Fit.Coder.Decode($("#polyline").val());
    }
    if(url.split('/')[1] == 'copyRoute'){
        flightPlanCoordinates=  Fit.Coder.Decode($("#polyline").val());
    }
    if(url.split('/')[1] == 'details'){
        flightPlanCoordinates=  Fit.Coder.Decode($("#polyline").val());
        // const flightPath = new google.maps.Polyline({
        //     geodesic: true,
        //     strokeColor: "#F64C1E",
        //     strokeOpacity: "0.7",
        //     strokeWeight: 7,
        // });
        // flightPath.setMap(Fit.Global.Map);
        var elevator = new google.maps.ElevationService;
        var path= polylineMapRoute($("#polyline").val());

        var elevator = new google.maps.ElevationService;

        elevator.getElevationAlongPath({
          'path': path,
          'samples': path.length
        }, plotElevationNew);
         }
   });

   $('.tabb').find('.createRoute a').click(function() {
    // console.log(window.location.origin);
       window.location.replace(window.location.origin+'/epic/train-gain/fitness-mapper');
   })

   $(document).on('click','.saveTime',function() {
    var challenge_id = $(this).data('id');
    var challenge_from = $(this).data('challenge-from');
    var client_id = $(this).data('client-id');
    var time = $("#challengeDuration"+challenge_id).val();
    swal({
        type: 'warning',
        title: 'Are you sure you want to complete the challenge?',
        showCancelButton: true,
        allowOutsideClick: false,
        showConfirmButton: true,
        confirmButtonText: 'Ok',
        confirmButtonColor: '#ff4401',
    }, 
    function(isConfirm){
        if(isConfirm){
            $('.waitingShield').show();
            $.post(public_url+'challenge/completed',{challenge_id:challenge_id,challenge_from:challenge_from,client_id:client_id,time:time}, function(data){
                $('.waitingShield').hide();
                if(data.status == 'success'){
                    $(".challengeDuration"+challenge_id).val('');
                    $("#timer-open"+challenge_id).hide();
                    swal({
                        type: 'success',
                        title: data.message,
                        allowOutsideClick: false,
                        showCancelButton: false,
                        confirmButtonText: 'Ok',
                        confirmButtonColor: '#ff4401',
                        // cancelButtonText: "No"
                    }, 
                    function(isConfirm){
                        if(isConfirm){
                            $('#data-table1').load(location.href + " #data-table1");
                        }
                    });
                }
                if(data.status == 'error'){
                    $(".challengeDuration"+challenge_id).val('');
                    $("#timer-open"+challenge_id).hide();
                    swal({
                        type: 'warning',
                        title: data.message,
                        allowOutsideClick: false,
                        showCancelButton: false,
                        confirmButtonText: 'Ok',
                        confirmButtonColor: '#ff4401',
                        // cancelButtonText: "No"
                    }, 
                    function(isConfirm){
                        if(isConfirm){
                            $('#data-table1').load(location.href + " #data-table1");
                        }
                    });
                }
            });
        }
    });
   })