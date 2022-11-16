var lastJQueryTS = 0,
  timeoutReference = null; // this is a global variable.
var validationMsg = null;

$(document).ready(function () {
  loadFirstStep();
  $(".final-step-submit").hide();

  //testing mahendra
  //$("#goal_type").val('create_new_goal');

  $(document).on("click", "input[name='chooseGoal']", function (e) {
    $("#goal_type").val($(this).val());
  });
  $(document).on("click", 'input[name="template"]', function () {
    // var goalTempleteId = $('input[name="template"]:checked').data('id');
    var goalTempleteId = $(this).data("id");
    $(".backward").attr("data-goal-template-id", goalTempleteId);
    $(".nextData").attr("data-goal-template-id", goalTempleteId);
    var datafrom = $(this).data("from");
    if (datafrom == "popup") {
      $(".choose-immediate-priority").attr("disabled", false);
      $(".same-template").prop("disabled", true);
      // $(".same-template").attr('disabled',true);
    }

    if (goalTempleteId != "" && goalTempleteId != undefined) {
      $("#goal_template_id").val(goalTempleteId);
      setGoalTemplateDetails(goalTempleteId);
    }

    //Images change fro left hand side panel
    var div = $(this).closest("div"),
      img = div.find("img").attr("src");
    $(".content-left-wrapper").find("img").attr("src", img);
  });
  $(document).on("click", ".choose-immediate-priority", function () {
    $("input[name=goal]").siblings("span").html("");
    $("input[name=goal]").trigger("refresh");
    // alert($("input[name=goal]:checked").val());
    $("#temp-modal").hide();
    $("#temp-modal").removeClass("in show");
    $("#goal_type").val("choose_form_template");

    $('input[name="goal"][value="No"]').prop("checked", true);

    // var goalTempleteId = $('input[name="template"]:checked').data("id");
    // $("#choose_immediate_priority").val(goalTempleteId); //Flag to change 1st page tempalate id

    $(".backward").trigger("click");

    // $(".backward").trigger("click");
    // $(".backward").trigger("click");
  });
  $(document).on("click", ".choose-create-new-goal", function () {
    // $('input[name="goal"][value="No"]').prop('checked', false);
    window.location.href = "/goal-buddy/create";
  });

  //    $("body").on("click", ".goal-predifine-template", function () {
  //     console.log("mahendra...");

  //     console.log($(this).data("goal-template-id"));

  //     console.log("finished...");

  //     var bg_url = $("#bgUrl").css("background-image");
  //     var goalTempleteId = "1"; //$(this).data('goal-template-id');

  //     setGoalTemplateDetails(goalTempleteId);
  //     $("#goal-template").val(goalTempleteId);
  //     $("#rightimage").show();
  //     $("#rightimage").css("background-image", bg_url);
  //   });
});

function setGoalTemplateDetails(goalTempleteId) {
  var goal_id = $("#goal_buddy_id").val();

  $.ajax({
    url: public_url + "goal-buddy/template/" + goalTempleteId,
    type: "GET",
    async: false,
    data: { goal_id: goal_id },
    success: function (data) {
      var data = JSON.parse(data);
      console.log("template---", data);

      if (data.status == true) {
        $("#goal_type").val("choose_form_template");
        //Stored template data to Local storage
        sessionStorage.setItem("templateData", JSON.stringify(data));

        var templet_id = data.goal_template.id;

        if (templet_id) {
          switch (templet_id) {
            case 1:
              var image_path = BASE_URL + "/result/images/weightmanagement.jpg";
              $(".slide-img").attr("src", image_path);
              break;
            case 2:
              var image_path = BASE_URL + "/result/images/drop_a_size.jpg";
              $(".slide-img").attr("src", image_path);
              break;
            case 3:
              var image_path = BASE_URL + "/result/images/eat.jpg";
              $(".slide-img").attr("src", image_path);
              break;
            case 4:
              var image_path = BASE_URL + "/result/images/improve_h.jpg";
              $(".slide-img").attr("src", image_path);
              break;
            case 5:
              var image_path = BASE_URL + "/result/images/reduce_stress.jpg";
              $(".slide-img").attr("src", image_path);
              break;
            case 6:
              var image_path = BASE_URL + "/result/images/improve_my_sleep.jpg";
              $(".slide-img").attr("src", image_path);
              break;
            case 7:
              var image_path = BASE_URL + "/result/images/improve_health.jpg";
              $(".slide-img").attr("src", image_path);
              break;
            case 8:
              var image_path = BASE_URL + "/result/images/injury.jpg";
              $(".slide-img").attr("src", image_path);
              break;
            case 9:
              var image_path =
                BASE_URL + "/result/images/increase_activity.jpg";
              $(".slide-img").attr("src", image_path);
              break;
            case 10:
              var image_path = BASE_URL + "/result/images/balance.jpg";
              $(".slide-img").attr("src", image_path);
              break;
            case 11:
              var image_path = BASE_URL + "/result/images/health.jpg";
              $(".slide-img").attr("src", image_path);
              break;
            case 12:
              var image_path = BASE_URL + "/result/images/improve_posture.jpg";
              $(".slide-img").attr("src", image_path);
              break;
            case 13:
              var image_path = BASE_URL + "/result/images/time_man.jpg";
              $(".slide-img").attr("src", image_path);
              break;
            case 14:
              var image_path = BASE_URL + "/result/images/improve_per.jpg";
              $(".slide-img").attr("src", image_path);
              break;
            case 15:
              var image_path = BASE_URL + "/result/images/improve_c.jpg";
              $(".slide-img").attr("src", image_path);
              break;
            case 16:
              var image_path = BASE_URL + "/result/images/become_proactive.jpg";
              $(".slide-img").attr("src", image_path);
              break;
          }
        }
      }
    },
  });
}

function loadFirstStep() {
  console.log("load first step..");

  $.ajax({
    url: public_url + "goal-buddy/loadfirststep",
    type: "GET",
    dataType: "json",
    processData: false,
    contentType: false,
    success: function (data) {
      //console.log("load first step ajax..");

      if (data.edit_goal) {
        console.log("edit_goal data : ", data);
        $("#goal_notes").val(data.gb_goal_notes);
        $("#goal_due_date").val(data.gb_due_date);
        $("#goal_start_date").val(data.gb_start_date);

        if (data.template_id && data.goal_type == "choose_form_template") {
          //console.log("template = ", data.template_id);
          $("#goal_template_id").val(data.template_id);
          $("#goal_buddy_id").val(data.goal_buddy_id);

          if (data.empty_habit_no) {
            $("#current_habit_step").val(data.empty_habit_no);
          }
          if (data.empty_task_no) {
            $("#current_task_step").val(data.empty_task_no);
          }

          $("#edit_goal").val(1);
          setGoalTemplateDetails(data.template_id);
        } else if (data.goal_type == "create_new_goal") {
          $("#goal_buddy_id").val(data.goal_buddy_id);
          $("#edit_goal").val(1);
          $("#goal_type").val(data.goal_type);
        }

        //If any form no filled it will redirect to that form directly while edit only
        console.log("empty form no : ", data.empty_form_no);
        if (data.empty_form_no != 0) {
          editGoalDetails(data.empty_form_no, data.goal_type);

          if (data.goal_type == "create_new_goal") {
            if (data.empty_form_no > 1) {
              $(".question-section").css("display", "block");
            }
            if (data.empty_form_no > 9) {
              $(".question-step").text(data.empty_form_no);
            } else {
              $(".question-step").text("0" + data.empty_form_no);
            }
          } else {
            data.empty_form_no = data.empty_form_no - 1;

            if (data.empty_form_no >= 1) {
              $(".question-section").css("display", "block");
            }

            if (data.empty_form_no > 9) {
              $(".question-step").text(data.empty_form_no);
            } else {
              $(".question-step").text("0" + data.empty_form_no);
            }
          }

          console.log("data.empty_form_no : ", data.empty_form_no);

          if (data.empty_form_no <= 12) {
            $("#section_completed").val("1");
          }
          if (data.empty_form_no >= 13) {
            $("#section_completed").val("2");
          }
          if (data.empty_form_no >= 16) {
            if (data.empty_habit_no == undefined) {
              $("#section_completed").val("3");
            }
          }
          if (data.empty_form_no >= 18) {
            if (data.empty_task_no == undefined) {
              $("#section_completed").val("4");
            }
          }
          if (data.empty_form_no == 20 || data.empty_form_no == 0) {
            $("#section_completed").val("5");
          }

          //Section changes according to step no
          moveSectionEditInitial(data.empty_form_no);

          return false;
        }
        ///////
        $("#section_completed").val("5");
      }

      //console.log("load first step ajax..");

      $("form").html("");
      $("form").html(data.html);
      $(".backward").hide();
    },
  });
}

function showHidePrevNext(goal_type) {
  $(".backward").show();
  $(".nextData").show();
  $(".final-step-submit").hide();

  if ($(".step").data("step") == "1") {
    $(".backward").hide();
  }

  if ($(".step").data("step") == "20" && goal_type == "create_new_goal") {
    $(".nextData").hide();
    $(".final-step-submit").show();
  }
  if ($(".step").data("step") == "21" && goal_type == "choose_form_template") {
    $(".nextData").hide();
    $(".final-step-submit").show();
  }
}

//Next button click
$(document).on("click", ".nextData", function (e) {
  e.preventDefault();
  toastr.options = {
    closeButton: true,
    progressBar: true,
  };

  var current_step = $(".step").data("step");
  $("#add_new_task").val("0"); //Flag 0 for add new task
  $("#edit_task").val("0"); //Flag 0 for edit task

  if ($("#goal_type").val() == "" || $("#goal_type").val() == undefined)
    $("#goal_type").val($('input[name="chooseGoal"]:checked').val());
  var next_question_no;
  var goal_type = $("#goal_type").val();

  //Completed work for this flag then needs to 0 after edit first habit
  $("#last_form_edit_habit").val("0");
  //Completed work for this flag then needs to 0 after edit first task
  $("#last_form_edit_task").val("0");

  if (!checkAllValidations(current_step, goal_type)) {
    //check validations
    // toastr.error("Please fill all data.");
    // return false;
    if (validationMsg != null) toastr.error(validationMsg);
    else toastr.error("Please fill all data.");
    return false;
  }

  var form = $(".goal_form")[0];
  var formData = new FormData(form);

  if (goal_type == "create_new_goal") {
    next_question_no = parseInt(current_step) + 1;

    //Repeat custom habit for compulsory 3 habit should be filled by user
    var total_habit_steps = parseInt($("#total_habit_step").val());
    var current_habit_step = parseInt($("#current_habit_step").val());
    if (
      current_step == 16 &&
      current_habit_step &&
      current_habit_step < total_habit_steps
    ) {
      $("#current_habit_step").val(current_habit_step + 1);
      formData.append("current_habit_step", current_habit_step);

      formData.append("repeat_habit", true);

      $("#waitingShield").removeClass("hidden");
      next_question_no = 16;
    } else {
      $("#current_habit_step").val(1);
      formData.append("repeat_habit", false);
    }
    //End custom habit repetation code-------

    //Repeat custom task for compulsory 3 habit should be filled by user
    var total_task_steps = parseInt($("#total_task_step").val());
    var current_task_step = parseInt($("#current_task_step").val());
    if (
      current_step == 18 &&
      current_task_step &&
      current_task_step < total_task_steps
    ) {
      $("#current_task_step").val(current_task_step + 1);
      formData.append("current_task_step", current_task_step);
      formData.append("repeat_task", true);

      $("#waitingShield").removeClass("hidden");
      next_question_no = 18;
    } else {
      $("#current_task_step").val(1);
      formData.append("repeat_task", false);
    }
    //End custom task repetation code----------------
  } else if (goal_type == "choose_form_template") {
    if (current_step == 1) {
      next_question_no = 0;
    } else {
      var temp_current_question = $(".watermark1").attr("data-id");
      next_question_no = parseInt(temp_current_question);
    }

    //Repeat template habit for compulsory 3 habit should be filled by user
    var total_habit_steps = parseInt($("#total_habit_step").val());
    var current_habit_step = parseInt($("#current_habit_step").val());
    if (
      current_step == 17 &&
      current_habit_step &&
      current_habit_step < total_habit_steps
    ) {
      $("#current_habit_step").val(current_habit_step + 1);
      formData.append("current_habit_step", current_habit_step);

      formData.append("repeat_habit", true);
      $("#waitingShield").removeClass("hidden");

      next_question_no = 15;
    } else {
      $("#current_habit_step").val(1);
      formData.append("repeat_habit", false);
    }
    //End template habit repetation code-------

    //Repeat template task for compulsory 3 habit should be filled by user
    var total_task_steps = parseInt($("#total_task_step").val());
    var current_task_step = parseInt($("#current_task_step").val());
    if (
      current_step == 19 &&
      current_task_step &&
      current_task_step < total_task_steps
    ) {
      $("#current_task_step").val(current_task_step + 1);
      formData.append("current_task_step", current_task_step);

      formData.append("repeat_task", true);
      $("#waitingShield").removeClass("hidden");

      next_question_no = 17;
    } else {
      $("#current_task_step").val(1);
      //formData.append("current_task_step", 0);
      formData.append("repeat_task", false);
    }
    //End template task repetation code----------------
  }

  //Only for goal template
  var goalTempleteId = $("#goal_template_id").val();
  formData.append("template", goalTempleteId);
  //End of goal template

  var goal_notes = $("#goal_notes").val();

  //append extra data
  if (goal_type == "") goal_type = "create_new_goal"; //default for testing //todo - remove

  formData.append("goal_type", goal_type);
  formData.append("current_step", current_step);
  formData.append("move", "next");

  if (goal_notes != undefined && goal_notes != "")
    formData.append("goal_notes", goal_notes);

  //creation/updation Milstone step-13
  var allMilestonesNames = [];
  var allMilestonesNamesId = [];
  var allMilestonesDates = [];

  $("#wrapped .milestones-name").each(function () {
    var milestonesNames = $(this).val();
    var milestonesId = $(this).data("milestones-id");
    if (
      milestonesNames != undefined &&
      milestonesId != undefined &&
      milestonesNames != "" &&
      milestonesId != ""
    ) {
      allMilestonesNamesId.push(milestonesId + ":" + milestonesNames);
    }
  });
  $("#wrapped .edit-milestones-date").each(function () {
    if ($(this).val() != "") {
      var mileStonesDate = moment($(this).val()).format("YYYY-MM-DD");

      console.log(mileStonesDate);

      allMilestonesDates.push(mileStonesDate);
    }
  });
  if (allMilestonesNamesId.length > 0) {
    formData.append("milestones-names-id", allMilestonesNamesId);
  }
  if (allMilestonesDates.length > 0) {
    formData.append("milestones-dates", allMilestonesDates);
  }
  //end milstone step-13
  //Goal reminders for step -12
  var send_msg_type = $("input[name='goal-Send-mail']:checked").val();
  var Send_mail_time = "";
  if (send_msg_type == "daily") {
    formData.append(
      "gb_reminder_goal_time",
      $("#daily_time_goal option:selected").val()
    );
  } else if (send_msg_type == "weekly") {
    formData.append(
      "gb_reminder_goal_time",
      $("#weekly_day_goal option:selected").val()
    );
  } else if (send_msg_type == "monthly") {
    formData.append(
      "gb_reminder_goal_time",
      $("#month_date_goal option:selected").val()
    );
  }
  /////////////////
  //creation/updation milstone reminder step-15
  var gb_milestones_reminder = $(
    "input[name='milestones-Send-mail']:checked"
  ).val();

  if (gb_milestones_reminder == "daily") {
    formData.append(
      "Send_mail_milestones_time",
      $("#daily_time_milestones").val()
    );
  } else if (gb_milestones_reminder == "weekly") {
    formData.append(
      "Send_mail_milestones_time",
      $("#weekly_day_milestones").val()
    );
  } else if (gb_milestones_reminder == "monthly") {
    formData.append(
      "Send_mail_milestones_time",
      $("#month_date_milestones").val()
    );
  }
  //////
  //Task creation/updation step-18
  var task_reminders = $("input[name='creattask-send-mail']:checked").val();
  if (task_reminders == "daily") {
    formData.append("Send_mail_task_time", $("#daily_time_task").val());
  }
  if (task_reminders == "weekly") {
    formData.append("Send_mail_task_time", $("#weekly_day_task").val());
  }
  if (task_reminders == "monthly") {
    formData.append("Send_mail_task_time", $("#month_date_task").val());
  }

  //Habit creation/updation step-16
  var habit_reminders = $("input[name='habits-send-mail']:checked").val();
  if (habit_reminders == "daily") {
    formData.append("Send_mail_habits_time", $("#daily_time_habits").val());
  }
  if (habit_reminders == "weekly") {
    formData.append("Send_mail_habits_time", $("#weekly_day_habits").val());
  }
  if (habit_reminders == "monthly") {
    formData.append("Send_mail_habits_time", $("#month_date_habits").val());
  }
  console.log("NEXT JS form data request :", Object.fromEntries(formData));
  $.ajax({
    data: formData,
    url: public_url + "goal-buddy/savegoal-new",
    type: "POST",
    dataType: "json",
    processData: false,
    contentType: false,
    success: function (data) {
      $("form").html("");

      //Question no change according to step no
      nextQuestion(goal_type, next_question_no);

      //Section changes according to step no
      moveSectionNext(next_question_no);

      $("form").html(data.html);

      showHidePrevNext(goal_type);

      // Scroll Top for each step
      $("#scroll_top")[0].click();

      setTimeout(function () {
        $("#waitingShield").addClass("hidden");
      }, 2000);
    },
    error: function (data) {
      console.log("Error:", data);

      $(".textdanger").text("");

      var response = JSON.parse(data.responseText);
      $.each(response.errors, function (field_name, error) {
        $(document)
          .find("[name=" + field_name + "]")
          .after('<span class="text-strong textdanger">' + error + "</span>");
      });

      setTimeout(function () {
        $("#waitingShield").addClass("hidden");
      }, 2000);
    },
  });
});

function nextQuestion(goal_type, next_question_no) {
  if (goal_type == "create_new_goal") {
    if (next_question_no > 1) {
      $(".question-section").css("display", "block");
    }
  } else {
    next_question_no = next_question_no + 1;
    if (next_question_no >= 1) {
      $(".question-section").css("display", "block");
    }
  }

  if (next_question_no > 9) {
    $(".question-step").text(next_question_no);
  } else {
    $(".question-step").text("0" + next_question_no);
  }
}

//Prev button click
$(document).on("click", ".backward", function (e) {
  e.preventDefault();

  if ($("#goal_type").val() == "" || $("#goal_type").val() == undefined)
    $("#goal_type").val($('input[name="chooseGoal"]:checked').val());

  var goal_type = $("#goal_type").val();
  var current_step = $(".step").data("step");

  var prev_question_no;
  $("#add_new_task").val("0"); //Flag 0 for add new task
  $("#edit_task").val("0"); //Flag 0 for edit task

  var form = $(".goal_form")[0];
  var formData = new FormData(form);

  if (goal_type == "create_new_goal") {
    prev_question_no = parseInt(current_step) - 1;
    var last_form_edit_habit = $("#last_form_edit_habit").val();
    if (last_form_edit_habit == "1") {
      current_step = 17;
    } else {
      $("#last_form_edit_habit").val("0");
    }
    var last_form_edit_task = $("#last_form_edit_task").val();
    if (last_form_edit_task == "1") {
      $("#current_task_step").val("1");
      current_step = 19;
    } else {
      $("#last_form_edit_task").val("0");
    }

    if (current_step == 16 || current_step == 18) {
      $("#waitingShield").removeClass("hidden");
    }
  } else if (goal_type == "choose_form_template") {
    var last_form_edit_habit = $("#last_form_edit_habit").val();
    if (last_form_edit_habit == "1") {
      current_step = 18;
    } else {
      $("#last_form_edit_habit").val("0");
    }
    var last_form_edit_task = $("#last_form_edit_task").val();
    if (last_form_edit_task == "1") {
      $("#current_task_step").val("1");
      current_step = 20;
    } else {
      $("#last_form_edit_task").val("0");
    }
    if (current_step == 1) {
      prev_question_no = 1;
    } else {
      var temp_current_question = $(".watermark1").attr("data-id");
      prev_question_no = parseInt(temp_current_question) - 1;
    }
    //Only for goal template
    var goalTempleteId = $("#goal_template_id").val();
    formData.append("template", goalTempleteId);

    if (current_step == 17 || current_step == 19) {
      $("#waitingShield").removeClass("hidden");
    }
    //End of goal template
  }
  //creation/updation Milstone step-13
  var allMilestonesNamesId = [];
  var allMilestonesDates = [];
  $("#wrapped .milestones-name").each(function () {
    var milestonesNames = $(this).val();
    var milestonesId = $(this).data("milestones-id");
    if (
      milestonesNames != undefined &&
      milestonesId != undefined &&
      milestonesNames != "" &&
      milestonesId != ""
    ) {
      allMilestonesNamesId.push(milestonesId + ":" + milestonesNames);
    }
  });
  $("#wrapped .edit-milestones-date").each(function () {
    if ($(this).val() != "") {
      var mileStonesDate = moment($(this).val()).format("YYYY-MM-DD");

      console.log(mileStonesDate);

      allMilestonesDates.push(mileStonesDate);
    }
  });
  if (allMilestonesNamesId.length > 0) {
    formData.append("milestones-names-id", allMilestonesNamesId);
  }
  if (allMilestonesDates.length > 0) {
    formData.append("milestones-dates", allMilestonesDates);
  }

  formData.append("goal_type", goal_type);
  formData.append("current_step", current_step);
  formData.append("move", "back");

  console.log("PREV JS form data request :", Object.fromEntries(formData));

  $.ajax({
    data: formData,
    url: public_url + "goal-buddy/savegoal-new",
    type: "POST",
    dataType: "json",
    processData: false,
    contentType: false,
    success: function (data) {
      $("form").html("");

      if (goal_type == "create_new_goal") {
        if (prev_question_no <= 1) {
          $(".question-section").css("display", "none");
        }
      } else {
        //prev_question_no = prev_question_no - 1;
        if (prev_question_no < 1) {
          $(".question-section").css("display", "none");
        }
      }

      if (prev_question_no > 9) {
        $(".question-step").text(prev_question_no);
      } else {
        $(".question-step").text("0" + prev_question_no);
      }

      var last_form_habit_edit = $("#last_form_edit_habit").val();
      var last_form_edit_task = $("#last_form_edit_task").val();

      if (last_form_habit_edit == "1") {
        moveSectionEditInitial(16);
        $(".question-step").text(16);
      } else if (last_form_edit_task == "1") {
        moveSectionEditInitial(18);
        $(".question-step").text(18);
      } else {
        //Section changes according to step no
        moveSectionPrev(prev_question_no);
      }

      $("form").html(data.html);

      showHidePrevNext(goal_type);

      // Scroll Top for each step
      $("#scroll_top")[0].click();

      setTimeout(function () {
        $("#waitingShield").addClass("hidden");
      }, 2000);

      //Completed work for this flag then needs to 0 after edit first habit
      $("#last_form_edit_habit").val("0");
      //Completed work for this flag then needs to 0 after edit first task
      $("#last_form_edit_task").val("0");
    },
    error: function (data) {
      console.log("Error:", data);

      $(".textdanger").text("");

      var response = JSON.parse(data.responseText);
      $.each(response.errors, function (field_name, error) {
        $(document)
          .find("[name=" + field_name + "]")
          .after('<span class="text-strong textdanger">' + error + "</span>");
      });
      setTimeout(function () {
        $("#waitingShield").addClass("hidden");
      }, 2000);
    },
  });
});

//step validation logic - start
function checkAllValidations(current_step, goal_type) {
  console.log("current_step, goal_type", current_step, goal_type);
  var status = false;
  if (current_step == "20" || current_step == "21") {
    return true;
  }
  if (current_step == "1") {
    status = validateStep1();
    return status;
  }
  if (goal_type == "choose_form_template") {
    status = checkTemplateValidations(current_step);
    return status;
  }
  if (goal_type == "create_new_goal") {
    status = checkValidations(current_step);
    return status;
  }

  /*  status = validateStep16(); //todo delete this line
  return status; //todo delete this line*/
}

function checkValidations(current_step) {
  //custom goal validations
  var status = false;

  for (var i = 2; i <= 20; i++) {
    if (i == current_step) {
      status = eval("validateStep" + i)();
    }
  }
  return status;
}

function checkTemplateValidations(current_step) {
  //template goal validations
  var status = false;

  for (var i = 2; i <= 20; i++) {
    if (i == current_step) {
      status = eval("validateTemplateStep" + i)();
    }
  }
  return status;
}

function validateStep1() {
  if ($('input[name="chooseGoal"]:checked').length == 0) {
    return false;
  } else return true;
}

function validateStep2() {
  if ($("#name_goal").val() == "") {
    return false;
  } else return true;
}

function validateStep3() {
  if ($("#description").val() == "") {
    return false;
  } else return true;
}

function validateStep4() {
  if ($('input[name="goal"]:checked').length == 0) {
    return false;
  } else return true;
}

function validateStep5() {
  if ($('input[name="life-change[]"]:checked').length == 0) {
    return false;
  }

  if (
    $('input[name="life-change[]"]').length &&
    $('input[name="life-change[]"]:checked').length > 0
  ) {
    var flag = true;
    $('input[name="life-change[]"]:checked').each(function () {
      if ($(this).val() == "Other") {
        if ($("#gb_change_life_reason_other").val() == "") {
          flag = false;
          return false;
        }
      }
    });

    if (flag == false) {
      validationMsg = "Please fill other text";
      return false;
    }
  }

  validationMsg = null;
  return true;
}

function validateStep6() {
  if ($("#accomplish").val() == "") {
    return false;
  } else return true;
}

function validateStep7() {
  if ($("#fail-description").val() == "") {
    return false;
  } else return true;
}

function validateStep8() {
  if ($("#gb_relevant_goal").val() == "") {
    return false;
  } else return true;
}

function validateStep9() {
  if ($("#gb_relevant_goal_event").val() == "") {
    return false;
  } else return true;
}

function validateStep10() {
  if ($("#datepicker_SYG").val() == "") {
    validationMsg = "Please select due date";
    return false;
  }
  if ($("#start-datepicker").val() == "") {
    validationMsg = "Please select start date";
    return false;
  }

  validationMsg = null;
  return true;
}
function validateStep11() {
  if ($('input[name="goal_seen"]:checked').length == 0) {
    return false;
  }

  if ($('input[name="goal_seen"]:checked').val() == "Selected friends") {
    if ($("#goal_selective_friends").val() == "") {
      validationMsg = "Please select friends";
      return false;
    }
  }

  validationMsg = null;
  return true;
}

function validateStep12() {
  if (
    $('input[name="goal-Send-mail"]:checked').length == 0 ||
    $('input[name="goal-Send-epichq"]:checked').length == 0
  ) {
    return false;
  } else return true;
}

function validateStep13() {
  if ($(".mile_section:eq(0) .dd-list > .dd-item").length == 0) return false;
  else return true;
  /*  if (
      $("#Milestones").val() == "" ||
      $("#milestones-date-pickup").val() == ""||

    ) {
      return false;
    } else return true;*/
}

function validateStep14() {
  if ($('input[name="gb_milestones_seen"]:checked').length == 0) {
    return false;
  }

  if (
    $('input[name="gb_milestones_seen"]:checked').val() == "Selected friends"
  ) {
    if ($("#gb_milestones_selective_friends").val() == "") {
      validationMsg = "Please select friends";
      return false;
    }
  }

  validationMsg = null;
  return true;
}
function validateStep15() {
  //   if( $('input[name="milestones-Send-mail"]:checked').length == 0) {
  //     validationMsg = "Please select 'Send Email / Message reminders'";
  //     return false;
  // }
  //  if($('input[name="milestones-Send-epichq"]:checked').length == 0){
  //     validationMsg = "Please select 'Get Notifications Through'";
  //     return false;
  // }
  // validationMsg = null;
  // return true;
  if (
    $('input[name="milestones-Send-mail"]:checked').length == 0 ||
    $('input[name="milestones-Send-epichq"]:checked').length == 0
  ) {
    return false;
  } else return true;
}

function validateStep16() {
  if ($("#SYG_habits").val() == "") {
    validationMsg = "Please fill habit name";
    document
      .querySelector("#SYG_habits")
      .scrollIntoView({ behavior: "instant", block: "end", inline: "nearest" });
    return false;
  }

  if ($('input[name="SYG_habit_recurrence"]:checked').length == 0) {
    validationMsg = "Please fill habit recurrence";
    document
      .querySelector('input[name="SYG_habit_recurrence"]')
      .scrollIntoView();
    return false;
  }

  if (
    $('input[name="SYG_habit_recurrence"]:checked').length > 0 &&
    $('input[name="SYG_habit_recurrence"]:checked').val() == "weekly"
  ) {
    if ($('input[name="habitRecWeek[]"]:checked').length == 0) {
      validationMsg = "Please select day";
      document
        .querySelector('input[name="SYG_habit_recurrence"]')
        .scrollIntoView();
      return false;
    }
  }

  if ($("#SYG_notes").val() == "") {
    validationMsg = "Please fill habit importance";
    document.querySelector("#SYG_notes").scrollIntoView();
    return false;
  }

  if (
    $(".milestone_div_class option:selected").val() == "" ||
    $(".milestone_div_class option:selected").val() == undefined
  ) {
    validationMsg = "Please select milestone";
    document.querySelector(".milestone_div_class").scrollIntoView();
    return false;
  }

  if ($('input[name="syg2_see_habit"]:checked').length == 0) {
    validationMsg = "Please select who can view";
    document.querySelector('input[name="syg2_see_habit"]').scrollIntoView();
    return false;
  }

  if ($('input[name="syg2_see_habit"]:checked').val() == "Selected friends") {
    if ($("#syg2_selective_friends").val() == "") {
      validationMsg = "Please select friends";
      document.querySelector('input[name="syg2_see_habit"]').scrollIntoView();
      return false;
    }
  }

  if ($('input[name="habits-send-mail"]:checked').length == 0) {
    validationMsg = "Please select send email/message";
    document.querySelector('input[name="habits-send-mail"]').scrollIntoView();
    return false;
  }

  if ($('input[name="habits-send-epichq"]:checked').length == 0) {
    validationMsg = "Please select get notificaions through";
    document.querySelector('input[name="habits-send-epichq"]').scrollIntoView();
    return false;
  }

  validationMsg = null;
  return true;
}
function validateStep17() {
  // if ($('#Milestones').val() == '' && $('#milestones-date-pickup').val() == ''){
  //    return false;
  // }else
  return true;
}

function validateStep18() {
  console.log("taskhabit", $(".taskhabit_div_class").length);
  console.log(
    "taskhabit selected",
    $(".taskhabit_div_class option:selected").val()
  );
  if (
    $(".taskhabit_div_class").length &&
    ($(".taskhabit_div_class option:selected").val() == "" ||
      $(".taskhabit_div_class option:selected").val() == undefined)
  ) {
    console.log("hi");
    validationMsg = "Please select habit name";
    document
      .querySelector(".taskhabit_div_class")
      .scrollIntoView({ behavior: "instant", block: "end", inline: "nearest" });
  }
  if ($(".taskhabit_div_class option:selected").val() == "") {
    validationMsg = "Please select habit name";
    document
      .querySelector(".taskhabit_div_class")
      .scrollIntoView({ behavior: "instant", block: "end", inline: "nearest" });
    return false;
  }
  // if (
  //   $("#SYG3_task").val() == "" ||
  //   $(".taskhabit_div_class option:selected").val() == ""
  // ) {
  //   return false;
  // }

  if ($("#SYG3_task").val() == "") {
    validationMsg = "Please fill task name";
    document.querySelector("#SYG3_task").scrollIntoView();
    return false;
  }

  if ($("#SYG_task_note").val() == "") {
    validationMsg = "Please fill task notes";
    document.querySelector("#SYG_task_note").scrollIntoView();
    return false;
  }

  if ($('input[name="Priority"]:checked').length == 0) {
    validationMsg = "Please select priority";
    document.querySelector('input[name="Priority"]').scrollIntoView();
    return false;
  }

  if ($('input[name="SYG_task_recurrence"]:checked').length == 0) {
    validationMsg = "Please select task recurrence";
    document
      .querySelector('input[name="SYG_task_recurrence"]')
      .scrollIntoView();
    return false;
  }

  if (
    $('input[name="SYG_task_recurrence"]:checked').length > 0 &&
    $('input[name="SYG_task_recurrence"]:checked').val() == "weekly"
  ) {
    if ($('input[name="task_recurrence_week[]"]:checked').length == 0) {
      validationMsg = "Please select day";
      document
        .querySelector('input[name="SYG_task_recurrence"]')
        .scrollIntoView();
      return false;
    }
  }

  if ($('input[name="SYG3_see_task"]:checked').length == 0) {
    validationMsg = "Please select who can view";
    document.querySelector('input[name="SYG3_see_task"]').scrollIntoView();
    return false;
  }

  if ($('input[name="SYG3_see_task"]:checked').val() == "Selected friends") {
    if ($("#SYG3_selective_friends").val() == "") {
      validationMsg = "Please select friends";
      document.querySelector('input[name="SYG3_see_task"]').scrollIntoView();
      return false;
    }
  }

  if ($('input[name="creattask-send-mail"]:checked').length == 0) {
    validationMsg = "Please select send email/message";
    document
      .querySelector('input[name="creattask-send-mail"]')
      .scrollIntoView();
    return false;
  }

  if ($('input[name="creattask-send-epichq"]:checked').length == 0) {
    validationMsg = "Please select get notificaions through";
    document
      .querySelector('input[name="creattask-send-epichq"]')
      .scrollIntoView();
    return false;
  }

  validationMsg = null;
  return true;
}

function validateStep19() {
  // if ($('#Milestones').val() == '' && $('#milestones-date-pickup').val() == ''){
  //    return false;
  // }else
  return true;
}
function validateStep20() {
  /*if (
    $('input[name="Specific"]').is(":checked") == false ||
    $('input[name="Measurable"]').is(":checked") == false ||
    $('input[name="Attainable"]').is(":checked") == false ||
    $('input[name="Relevant"]').is(":checked") == false ||
    $('input[name="Time-Bound"]').is(":checked") == false
  ) {
    return false;
  } else*/ return true;
}

function validateTemplateStep2() {
  if ($('input[name="template"]:checked').length == 0) {
    return false;
  } else return true;
}

function validateTemplateStep3() {
  if ($('input[name="name_goal"]:checked').length == 0) {
    return false;
  }

  if (
    $('input[name="name_goal"]').length &&
    $('input[name="name_goal"]:checked').length > 0
  ) {
    if ($('input[name="name_goal"]:checked').val() == "Other") {
      if ($("#name_goal_other").val() == "") {
        validationMsg = "Please fill other text";
        return false;
      }
    }
  }

  validationMsg = null;
  return true;
}

function validateTemplateStep4() {
  if ($('input[name="describe_achieve"]:checked').length == 0) {
    return false;
  }

  if (
    $('input[name="describe_achieve"]').length &&
    $('input[name="describe_achieve"]:checked').length > 0
  ) {
    if ($('input[name="describe_achieve"]:checked').val() == "Other") {
      if ($("#describe_achieve_other").val() == "") {
        validationMsg = "Please fill other text";
        return false;
      }
    }
  }

  validationMsg = null;
  return true;
}

function validateTemplateStep5() {
  if ($('input[name="goal"]:checked').length == 0) {
    return false;
  } else return true;
}

function validateTemplateStep6() {
  if ($('input[name="life-change[]"]:checked').length == 0) {
    return false;
  }

  if (
    $('input[name="life-change[]"]').length &&
    $('input[name="life-change[]"]:checked').length > 0
  ) {
    var flag = true;

    var other_life_change_reason = $(".other_life_change_reason").prop(
      "checked"
    );

    if (other_life_change_reason == true) {
      if ($("#gb_change_life_reason_other").val() == "") {
        flag = false;
        return false;
      }
    }
    // $('input[name="life-change[]"]:checked').each(function () {
    //   if ($(this).val() == "Other") {

    //   }
    // });

    if (flag == false) {
      validationMsg = "Please fill other text";
      return false;
    }
  }

  validationMsg = null;
  return true;
}

function validateTemplateStep7() {
  if ($('input[name="accomplish[]"]:checked').length == 0) {
    return false;
  }

  if (
    $('input[name="accomplish[]"]').length &&
    $('input[name="accomplish[]"]:checked').length > 0
  ) {
    var flag = true;
    var accomplish_other = $(".accomplish_other").prop("checked");

    if (accomplish_other == true) {
      if ($("#accomplish_other").val() == "") {
        flag = false;
        return false;
      }
    }

    // $('input[name="accomplish[]"]:checked').each(function () {
    //   if ($(this).val() == "Other") {
    //     if ($("#accomplish_other").val() == "") {
    //       flag = false;
    //       return false;
    //     }
    //   }
    // });

    if (flag == false) {
      validationMsg = "Please fill other text";
      return false;
    }
  }

  validationMsg = null;
  return true;
}

function validateTemplateStep8() {
  if ($('input[name="fail-description[]"]:checked').length == 0) {
    return false;
  }

  if (
    $('input[name="fail-description[]"]').length &&
    $('input[name="fail-description[]"]:checked').length > 0
  ) {
    var flag = true;

    var fail_description_other = $(".fail_description_other").prop("checked");

    if (fail_description_other == true) {
      if ($("#fail_description_other").val() == "") {
        flag = false;
        return false;
      }
    }

    // $('input[name="fail-description[]"]:checked').each(function () {
    //   if ($(this).val() == "Other") {
    //     if ($("#fail_description_other").val() == "") {
    //       flag = false;
    //       false;
    //     }
    //   }
    // });

    if (flag == false) {
      validationMsg = "Please fill other text";
      return false;
    }
  }

  validationMsg = null;
  return true;
}

function validateTemplateStep9() {
  if ($('input[name="gb_relevant_goal[]"]:checked').length == 0) {
    return false;
  }

  if (
    $('input[name="gb_relevant_goal[]"]').length &&
    $('input[name="gb_relevant_goal[]"]:checked').length > 0
  ) {
    var flag = true;
    var gb_relevant_goal_other = $(".gb_relevant_goal_other").prop("checked");

    if (gb_relevant_goal_other == true) {
      if ($("#gb_relevant_goal_other").val() == "") {
        flag = false;
        return false;
      }
    }

    // $('input[name="gb_relevant_goal[]"]:checked').each(function () {
    //   if ($(this).val() == "Other") {
    //     if ($("#gb_relevant_goal_other").val() == "") {
    //       flag = false;
    //       return false;
    //     }
    //   }
    // });

    if (flag == false) {
      validationMsg = "Please fill other text";
      return false;
    }
  }

  validationMsg = null;
  return true;
}

function validateTemplateStep10() {
  if ($('input[name="gb_relevant_goal_event"]:checked').length == 0) {
    return false;
  }

  if (
    $('input[name="gb_relevant_goal_event[]"]').length &&
    $('input[name="gb_relevant_goal_event[]"]:checked').length > 0
  ) {
    var flag = true;
    $('input[name="gb_relevant_goal_event[]"]:checked').each(function () {
      if ($(this).val() == "Other") {
        if ($("#gb_relevant_goal_event_other").val() == "") {
          flag = false;
          return false;
        }
      }
    });

    if (flag == false) {
      validationMsg = "Please fill other text";
      return false;
    }
  }

  validationMsg = null;
  return true;
}

function validateTemplateStep11() {
  if ($("#datepicker_SYG").val() == "") {
    validationMsg = "Please select due date";
    return false;
  }
  if ($("#start-datepicker").val() == "") {
    validationMsg = "Please select start date";
    return false;
  }

  validationMsg = null;
  return true;
}

function validateTemplateStep12() {
  if ($('input[name="goal_seen"]:checked').length == 0) {
    return false;
  }

  if ($('input[name="goal_seen"]:checked').val() == "Selected friends") {
    if ($("#goal_selective_friends").val() == "") {
      validationMsg = "Please select friends";
      return false;
    }
  }

  validationMsg = null;
  return true;
}

function validateTemplateStep13() {
  if (
    $('input[name="goal-Send-mail"]:checked').length == 0 ||
    $('input[name="goal-Send-epichq"]:checked').length == 0
  ) {
    return false;
  } else return true;
}

function validateTemplateStep14() {
  if ($(".mile_section:eq(0) .dd-list > .dd-item").length == 0) return false;
  else return true;
}

function validateTemplateStep15() {
  if ($('input[name="gb_milestones_seen"]:checked').length == 0) {
    return false;
  }

  if (
    $('input[name="gb_milestones_seen"]:checked').val() == "Selected friends"
  ) {
    if ($("#gb_milestones_selective_friends").val() == "") {
      validationMsg = "Please select friends";
      return false;
    }
  }

  validationMsg = null;
  return true;
}

function validateTemplateStep16() {
  if (
    $('input[name="milestones-Send-mail"]:checked').length == 0 ||
    $('input[name="milestones-Send-epichq"]:checked').length == 0
  ) {
    return false;
  } else return true;
}

function validateTemplateStep17() {
  if ($("#SYG_habits").length && $("#SYG_habits").val() == "") {
    validationMsg = "Please fill habit name";
    document
      .querySelector("#SYG_habits")
      .scrollIntoView({ behavior: "instant", block: "end", inline: "nearest" });
    return false;
  }

  if ($('input[name="SYG_habit_recurrence"]:checked').length == 0) {
    validationMsg = "Please fill habit recurrence";
    document
      .querySelector('input[name="SYG_habit_recurrence"]')
      .scrollIntoView();
    return false;
  }

  if (
    $('input[name="SYG_habit_recurrence"]:checked').length > 0 &&
    $('input[name="SYG_habit_recurrence"]:checked').val() == "weekly"
  ) {
    if ($('input[name="habitRecWeek[]"]:checked').length == 0) {
      validationMsg = "Please select day";
      document
        .querySelector('input[name="SYG_habit_recurrence"]')
        .scrollIntoView();
      return false;
    }
  }

  if ($("#SYG_notes").length && $("#SYG_notes").val() == "") {
    validationMsg = "Please fill habit importance";
    document.querySelector("#SYG_notes").scrollIntoView();
    return false;
  }

  if (
    $('input[name="SYG_notes[]"]').length &&
    $('input[name="SYG_notes[]"]:checked').length == 0
  ) {
    validationMsg = "Please fill habit importance";
    document.querySelector('input[name="SYG_notes[]"]').scrollIntoView();
    return false;
  }

  if (
    $('input[name="SYG_notes[]"]').length &&
    $('input[name="SYG_notes[]"]:checked').length > 0
  ) {
    var flag = true;
    $('input[name="SYG_notes[]"]:checked').each(function () {
      if ($(this).val() == "Other") {
        if ($("#gb_habit_note_other").val() == "") {
          flag = false;
          return false;
        }
      }
    });

    if (flag == false) {
      validationMsg = "Please fill habit other text";
      document.querySelector('input[name="SYG_notes[]"]').scrollIntoView();
      return false;
    }
  }

  if (
    $(".milestone_div_class option:selected").val() == "" ||
    $(".milestone_div_class option:selected").val() == undefined
  ) {
    validationMsg = "Please select milestone";
    document.querySelector(".milestone_div_class").scrollIntoView();
    return false;
  }

  if ($('input[name="syg2_see_habit"]:checked').length == 0) {
    validationMsg = "Please select who can view";
    document.querySelector('input[name="syg2_see_habit"]').scrollIntoView();
    return false;
  }

  if ($('input[name="syg2_see_habit"]:checked').val() == "Selected friends") {
    if ($("#syg2_selective_friends").val() == "") {
      validationMsg = "Please select friends";
      document.querySelector('input[name="syg2_see_habit"]').scrollIntoView();
      return false;
    }
  }

  if ($('input[name="habits-send-mail"]:checked').length == 0) {
    validationMsg = "Please select send email/message";
    document.querySelector('input[name="habits-send-mail"]').scrollIntoView();
    return false;
  }

  if ($('input[name="habits-send-epichq"]:checked').length == 0) {
    validationMsg = "Please select get notificaions through";
    document.querySelector('input[name="habits-send-epichq"]').scrollIntoView();
    return false;
  }

  validationMsg = null;
  return true;
}

function validateTemplateStep18() {
  return true;
}

function validateTemplateStep19() {
  if (
    $(".taskhabit_div_class").length &&
    ($(".taskhabit_div_class option:selected").val() == "" ||
      $(".taskhabit_div_class option:selected").val() == undefined)
  ) {
    validationMsg = "Please select habit name";
    document
      .querySelector(".taskhabit_div_class")
      .scrollIntoView({ behavior: "instant", block: "end", inline: "nearest" });
    return false;
  }

  if ($("#SYG3_task").length && $("#SYG3_task").val() == "") {
    validationMsg = "Please fill task name";
    document.querySelector("#SYG3_task").scrollIntoView();
    return false;
  }

  // if ($(".notes").length && $(".notes").val() == "") {
  //   validationMsg = "Please fill task notes";
  //   document.querySelector(".notes").scrollIntoView();
  //   return false;
  // }

  if ($("#SYG_task_note").val() == "") {
    validationMsg = "Please fill task notes";
    document.querySelector("#SYG_task_note").scrollIntoView();
    return false;
  }

  if (
    $('input[name="SYG_task_note[]"]').length &&
    $('input[name="SYG_task_note[]"]:checked').length == 0
  ) {
    validationMsg = "Please fill task notes";
    document.querySelector('input[name="SYG_task_note[]"]').scrollIntoView();
    return false;
  }

  if (
    $('input[name="SYG_task_note[]"]').length &&
    $('input[name="SYG_task_note[]"]:checked').length > 0
  ) {
    var flag = true;
    $('input[name="SYG_task_note[]"]:checked').each(function () {
      if ($(this).val() == "Other") {
        if ($("#gb_task_note_other").val() == "") {
          flag = false;
          return false;
        }
      }
    });

    if (flag == false) {
      validationMsg = "Please fill task other text";
      document.querySelector('input[name="SYG_task_note[]"]').scrollIntoView();
      return false;
    }
  }

  //console.log("SYG task notes : ", $('input[name="SYG_task_note[]"]'));

  if ($('input[name="Priority"]:checked').length == 0) {
    validationMsg = "Please select priority";
    document.querySelector('input[name="Priority"]').scrollIntoView();
    return false;
  }

  if ($('input[name="SYG_task_recurrence"]:checked').length == 0) {
    validationMsg = "Please select task recurrence";
    document
      .querySelector('input[name="SYG_task_recurrence"]')
      .scrollIntoView();
    return false;
  }

  if (
    $('input[name="SYG_task_recurrence"]:checked').length > 0 &&
    $('input[name="SYG_task_recurrence"]:checked').val() == "weekly"
  ) {
    if ($('input[name="task_recurrence_week[]"]:checked').length == 0) {
      validationMsg = "Please select day";
      document
        .querySelector('input[name="SYG_task_recurrence"]')
        .scrollIntoView();
      return false;
    }
  }

  if ($('input[name="SYG3_see_task"]:checked').length == 0) {
    validationMsg = "Please select who can view";
    document.querySelector('input[name="SYG3_see_task"]').scrollIntoView();
    return false;
  }

  if ($('input[name="SYG3_see_task"]:checked').val() == "Selected friends") {
    if ($("#SYG3_selective_friends").val() == "") {
      validationMsg = "Please select friends";
      document.querySelector('input[name="SYG3_see_task"]').scrollIntoView();
      return false;
    }
  }

  if ($('input[name="creattask-send-mail"]:checked').length == 0) {
    validationMsg = "Please select send email/message";
    document
      .querySelector('input[name="creattask-send-mail"]')
      .scrollIntoView();
    return false;
  }

  if ($('input[name="creattask-send-epichq"]:checked').length == 0) {
    validationMsg = "Please select get notificaions through";
    document
      .querySelector('input[name="creattask-send-epichq"]')
      .scrollIntoView();
    return false;
  }

  validationMsg = null;
  return true;
}

function validateTemplateStep20() {
  return true;
}

function validateTemplateStep21() {
  return true;
}

function validateTemplateStep22() {
  return true;
}
//steps validation logic - end

$(document).on("click", ".habit-edit", function () {
  var processbarDiv = $(this).closest("tr");
  var habitId = $(this).attr("data-habit-id");
  var primary_habit = $(this).attr("data-habit-primary");

  var goal_type = $("#goal_type").val();
  var edit_habit = true;
  var last_form = $(this).attr("data-last-form");

  //Only for last form habit edit step - 20/21
  if (last_form == "true") {
    $("#last_form_edit_habit").val("1");
    $(".backward").trigger("click");
  } else {
    //Perticular habit edit from click on edit button step 19
    console.log("habit edit..");
    loadCustomabitStep(
      goal_type,
      edit_habit,
      habitId,
      processbarDiv,
      primary_habit
    );
  }
});

function loadCustomabitStep(
  goal_type,
  edit_habit = null,
  habitId = null,
  processbarDiv = null,
  primary_habit = null
) {
  console.log(edit_habit);

  $.ajax({
    url:
      public_url + "goal-buddy/load-custom-habit-step?goal_type=" + goal_type,
    type: "GET",
    dataType: "json",
    processData: false,
    contentType: false,
    async: false,
    success: function (data) {
      $("form").html("");
      $("form").html(data.html);

      //Goal task data should load after html load completion..
      if (edit_habit) {
        console.log("edit habit.");
        showHidePrevNext(goal_type);
        getHabit(habitId, processbarDiv, primary_habit);
      }
    },
  });
}
function primaryHabitTemplate(template_habit, habit_data) {
  var gb_habit_notes = template_habit[0].gb_habit_notes.split("\n");
  var html1 = "";
  var radio_val = "";
  var goal_other = null;
  var other1 = "";
  if (gb_habit_notes.length > 0) {
    gb_habit_notes.push("Other");

    for (var i = 0; i < gb_habit_notes.length; i++) {
      if (gb_habit_notes[i] != "") {
        gb_habit_notes[i] = gb_habit_notes[i].replace(/\./g, "");
        gb_habit_notes[i] = gb_habit_notes[i].replace(/[^\w\s]/g, "").trim();

        var habit_notes = habit_data.gb_habit_notes
          ? habit_data.gb_habit_notes
          : null;
        var gb_habit_note_other = habit_data.gb_habit_note_other
          ? habit_data.gb_habit_note_other
          : null;

        if (habit_notes != null) {
          habit_notes = habit_notes.split(",");
          radio_val = habit_notes.includes(gb_habit_notes[i]) ? "checked" : "";
          goal_other = gb_habit_note_other;
        }
        other1 = "gb_habit_note";

        if (gb_habit_notes[i] == "Other") {
          other1 = "gb_habit_note_other";
          if (goal_other != null) {
            html1 =
              '<textarea rows="7" class="form-control" id="gb_habit_note_other" name="gb_habit_note_other">' +
              goal_other +
              "</textarea>";
          } else {
            html1 =
              '<textarea rows="7" class="form-control hidden" id="gb_habit_note_other" name="gb_habit_note_other" placeholder="Input Your Specific Goal Here..."></textarea>';
          }
        }
        $(".habit_notes").append(
          '<div class="form-group">\
                            <label class="container_check version_2">' +
            gb_habit_notes[i] +
            '\
                            <input type="checkbox" class="' +
            other1 +
            '" name="SYG_notes[]" required value="' +
            gb_habit_notes[i] +
            '"' +
            radio_val +
            '>\
                            <span class="checkmark"></span>\
                            ' +
            html1 +
            "\
                            </label>\
                      </div>"
        );
      }
    }
  }
}

function getHabit(hid, processbarDiv, primary_habit) {
  $("#waitingShield").removeClass("hidden");
  $.ajax({
    url: public_url + "goal-buddy/showhabit",
    type: "POST",
    data: { habitId: hid },
    async: false,
    success: function (data) {
      var data = JSON.parse(data);
      if (data.status == "true") {
        console.log("habit data.....", data);

        var habit_data = data.goalBuddy;

        $("#habitid").val(habit_data.id); //Put habit id to update

        console.log("primary div : ", primary_habit);
        if (primary_habit == "true") {
          $(".habit_notes").html("");
          var data = JSON.parse(sessionStorage.getItem("templateData"));
          if (data.goal_template.goal_buddy_habit.length > 0) {
            var matched_habit = data.goal_template.goal_buddy_habit.filter(
              (habit) => habit.gb_habit_name == habit_data.gb_habit_name
            );
            console.log(matched_habit);
            primaryHabitTemplate(matched_habit, habit_data);
          }
          $(".SYG_habits").val(habit_data.gb_habit_name);
          $("#SYG_habits").attr("disabled", true);
        } else {
          $("#SYG_habits").val(habit_data.gb_habit_name);
        }

        if (habit_data.gb_milestones_id != undefined) {
          var milestoneHabitIds = habit_data.gb_milestones_id.split(",");
          $("select#milestone_div")
            .val(milestoneHabitIds)
            .selectpicker("refresh");
        }

        if (habit_data.gb_habit_recurrence_type != undefined) {
          $(
            "input[type=radio][name=SYG_habit_recurrence][value='" +
              habit_data.gb_habit_recurrence_type +
              "']"
          ).attr("checked", true);

          if (habit_data.gb_habit_recurrence_type == "weekly") {
            $(".showDayBox").show();
            var weekly = habit_data.gb_habit_recurrence_week.split(",");
            weekly.map((data) => {
              $("input[type=checkbox][value='" + data + "']").attr(
                "checked",
                true
              );
            });
          } else if (habit_data.gb_habit_recurrence_type == "daily") {
            $(".showDayBox").hide();
          }
        }
        if (habit_data.gb_habit_notes != undefined) {
          $("#SYG_notes").val(habit_data.gb_habit_notes);
        }
        if (habit_data.gb_habit_seen != undefined) {
          $("input[type=radio][value='" + habit_data.gb_habit_seen + "']").attr(
            "checked",
            true
          );
        }

        if (habit_data.gb_habit_reminder != undefined) {
          $(
            "input[type=radio][name=habits-send-mail][value='" +
              habit_data.gb_habit_reminder +
              "']"
          ).attr("checked", true);
          if (habit_data.gb_habit_reminder == "daily") {
            $(".showTimeBox").show();
            $("#daily_time_habits").val(habit_data.gb_habit_reminder_time);
          } else if (habit_data.gb_habit_reminder == "weekly") {
            $(".showDayBoxReminder").show();
            $("#weekly_day_habits").val(habit_data.gb_habit_reminder_time);
          } else if (habit_data.gb_habit_reminder == "monthly") {
            $(".showMonthBox").show();
            $("#month_date_habits").val(habit_data.gb_habit_reminder_time);
          }
        }
        if (habit_data.gb_habit_reminder_epichq != undefined) {
          $(
            "input[type=radio][name=habits-send-epichq][value='" +
              habit_data.gb_habit_reminder_epichq +
              "']"
          ).attr("checked", true);
        }
        if (habit_data.gb_habit_seen == "Selected friends") {
          if (habit_data.gb_habit_selective_friends) {
            console.log(habit_data.gb_habit_selective_friends);
            $("#syg2_selective_friends").val(
              habit_data.gb_habit_selective_friends
            );
            $("#syg2_selective_friends").parent().removeClass("hidden");
          }
        } else {
          $("#syg2_selective_friends").attr("value", "");
          $("#syg2_selective_friends").parent().addClass("hidden");
        }
      }
      setTimeout(function () {
        $("#waitingShield").addClass("hidden");
      }, 2000);
    },
  });
}

function showMIlestoneDd(defaults) {
  var milestones = $(".mile_section .dd-item");
  $(".milestone-div").show();
  var optionValue =
    '<select id="milestone_div" name="milestone_value" class="selectpicker form-control onchange-set-neutral milestone_div_class" multiple="" data-actions-box="true">';
  var url = window.location.href;
  var result = url.split("/");
  var Param = result[result.length - 3];
  var Param1 = result[result.length - 2];
  $.each(milestones, function (k, obj) {
    if (Param == "goal-buddy" && Param1 == "edit") {
      // var v = $(obj).find('.save-milestone-info').data('milestones-id'),
      var v = $(obj).find(".milestones-name").data("milestones-id"),
        t = $(obj).find(".milestones-name").val();
    } else {
      var v = $(obj).closest(".dd-item").data("milestones-id"),
        t = $(obj).find(".milestones-name").val();
    }
    console.log("alert=== ", obj, v, t);
    if (defaults && $.inArray(v + "", defaults) >= 0) {
      optionValue += '<option value="' + v + '" selected>' + t + "</option>";
    } else {
      optionValue += '<option value="' + v + '">' + t + "</option>";
    }
  });
  optionValue += "</select>";
  $(".milestone-dropdown").html($(optionValue));
  initSelectpicker($(".milestone-dropdown select"));

  $(".milestone_div_class").selectpicker("refresh");
}

function assignSelectedMile(defaults) {
  $("select[name=milestone_value]").val(defaults);
  $(".milestone_div_class").selectpicker("refresh");
}

//Back to goal list
$(".back_to_goal_list").click(function () {
  sessionStorage.removeItem("all_habits_associated_task"); //Clean habit data from session storage
  sessionStorage.removeItem("templateData"); //Clean template data from session storage

  window.location.href = public_url + "goals";
});

$(".final-step-submit").click(function (e) {
  e.preventDefault();
  $("#m-selected-st").val(5);
  var allVals = [];
  var formdata = {};
  isFormValid = true;
  $('input[type="checkbox"]:checked.goalsmart').each(function () {
    allVals.push($(this).val());
  });
  if (allVals.length > 0) {
    formdata["review"] = allVals;
  }

  if (
    $('input[value="specific"]').is(":checked") == false ||
    $('input[value="measurable"]').is(":checked") == false ||
    $('input[value="attainable"]').is(":checked") == false ||
    $('input[value="relevant"]').is(":checked") == false ||
    $('input[value="time_Bound"]').is(":checked") == false
  ) {
    isFormValid = false;
  }
  if (isFormValid) {
    formdata["form_no"] = 5;
    formdata["goal_notes"] = $("#goal_notes").val();
    formdata["last_insert_id"] = $("#last-insert-id").val();

    sessionStorage.removeItem("all_habits_associated_task"); //Clean habit data from session storage
    sessionStorage.removeItem("templateData"); //Clean template data from session storage

    //savegoal(formdata);
    $(".nextData").trigger("click");

    window.location.href = public_url + "goals";

    swal("Data has been saved successfully");
  } else {
    swal("Please select all S.M.A.R.T. goals");
  }
});

//Edit goal details from last page
$(document).on("click", "#edit_goal_details", function () {
  var goal_type = $("#goal_type").val();
  var step_no = 2; //Create a new goal step
  if (goal_type == "choose_form_template") {
    step_no = 3;
  }
  editGoalDetails(step_no, goal_type);
});
$(document).on("click", "#edit_goal_milestone", function () {
  var goal_type = $("#goal_type").val();
  var step_no = 13; //Create a new goal step
  if (goal_type == "choose_form_template") {
    step_no = 14;
  }
  editGoalDetails(step_no, goal_type);
});
$(document).on("click", "#edit_goal_habit", function () {
  var goal_type = $("#goal_type").val();
  var step_no = 16; //Create a new goal step
  if (goal_type == "choose_form_template") {
    step_no = 17;
  }
  editGoalDetails(step_no, goal_type);
});
$(document).on("click", "#edit_goal_task", function () {
  var goal_type = $("#goal_type").val();
  var step_no = 18; //Create a new goal step
  if (goal_type == "choose_form_template") {
    step_no = 19;
  }
  editGoalDetails(step_no, goal_type);
});

function editGoalDetails(step_no, goal_type) {
  $.ajax({
    url: public_url + "goal-buddy/editgoaldetails",
    type: "POST",
    data: { current_step: step_no, goal_type: goal_type },
    async: false,
    success: function (data) {
      console.log("loaded");
      $("form").html("");
      $("form").html(data.html);
      $(".nextData").show();
      $(".final-step-submit").hide();

      //Section changes according to step no
      moveSectionPrev(step_no);

      if (step_no > 9) {
        $(".question-step").text(step_no);
      } else {
        $(".question-step").text("0" + step_no);
      }

      showHidePrevNext(goal_type);
    },
  });
}

$(document).on("click", ".task-edit", function () {
  j = 1;
  isJump = "false";
  var processbarDiv = $(this).closest("tr");
  var primary_task = $(this).attr("data-task-primary");

  var step_no = $(this).attr("data-step-no");
  $("#current_task_step").val(step_no);
  var goal_type = $("#goal_type").val();
  var taskId = $(this).attr("data-task-id");
  var edit_task = true;
  var last_form = $(this).attr("data-last-form");
  //Only for last form habit edit step - 20/21
  if (last_form == "true") {
    $("#last_form_edit_task").val("1");
    $(".backward").trigger("click");
  } else {
    $("#edit_task").val("1");
    loadCustomTaskStep(
      goal_type,
      edit_task,
      taskId,
      processbarDiv,
      primary_task
    );
  }
});

function loadCustomTaskStep(
  goal_type = null,
  edit_task = null,
  taskId = null,
  processbarDiv = null,
  primary_task = null
) {
  $.ajax({
    url: public_url + "goal-buddy/load-custom-task-step?goal_type=" + goal_type,
    type: "GET",
    dataType: "json",
    processData: false,
    contentType: false,
    success: function (data) {
      $("form").html("");
      $("form").html(data.html);
      //Goal task data should load after html load completion..
      if (edit_task) {
        showHidePrevNext(goal_type);
        getTask(taskId, processbarDiv, primary_task);
      }
    },
  });
}
function primaryTaskTemplate(template_task, task_data) {
  var gb_task_note = template_task[0].gb_task_note.split("\n");
  var html1 = "";
  var radio_val = "";
  var goal_other = null;
  var other1 = "";
  var task_notes_class = "notes";
  if (gb_task_note.length > 0) {
    var current_task_step = $("#current_task_step").val();
    if (current_task_step >= "4" && current_task_step <= "9") {
      gb_task_note.push("Other");
      $(".task_notes")
        .removeClass("container_check")
        .removeClass("version_2")
        .removeAttr("style");
    }
    //gb_task_note.push("Other");

    for (var i = 0; i < gb_task_note.length; i++) {
      var task_notes = task_data.gb_task_note ? task_data.gb_task_note : null;
      var task_notes_other = task_data.gb_task_note_other
        ? task_data.gb_task_note_other
        : null;

      if (gb_task_note[i] != "") {
        gb_task_note[i] = gb_task_note[i].replace(/\./g, "");
        gb_task_note[i] = gb_task_note[i].replace(/[^\w\s]/g, "").trim();
        console.log("gb_task_note[i]", gb_task_note[i]);
        if (current_task_step >= "1" && current_task_step <= "3") {
          var prev_notes = $(".goal_task_notes").data("message1");
          $(".goal_task_notes").data(
            "message1",
            prev_notes + "<br/><br/><b> - " + gb_task_note[i] + "</b>"
          );
        }

        /* uncomment */
        if (current_task_step >= "4" && current_task_step <= "9") {
          $("#SYG_task_note").remove();
          if (task_notes != "") {
            task_notes = task_notes.split(",");
            radio_val = task_notes.includes(gb_task_note[i]) ? "checked" : "";
          }
          if (gb_task_note[i] == "Other") {
            task_notes_class = "gb_task_note_other";
            if (task_notes_other != null) {
              html1 =
                '<textarea rows="7" class="form-control" id="gb_task_note_other" name="gb_task_note_other">' +
                task_notes_other +
                "</textarea>";
            } else {
              html1 =
                '<textarea rows="7" class="form-control hidden" id="gb_task_note_other" name="gb_task_note_other" placeholder="Input Your Specific Goal Here..."></textarea>';
            }
          }
          $(".task_notes").append(
            '<div class="form-group">\
                          <label class="container_check version_2">' +
              gb_task_note[i] +
              '\
                          <input type="checkbox" class="' +
              task_notes_class +
              '" name="SYG_task_note[]" required value="' +
              gb_task_note[i] +
              '"' +
              radio_val +
              '>\
                          <span class="checkmark"></span>\
                          ' +
              html1 +
              "\
                          </label>\
                    </div>"
          );
        }

        /* uncomment */
      }
    }
  }
}
function getTask(tid, processbarDiv, primary_task) {
  $("#waitingShield").removeClass("hidden");
  $.ajax({
    url: public_url + "goal-buddy/showtask",
    type: "POST",
    data: { taskId: tid },
    success: function (data) {
      var data = JSON.parse(data);

      if (data.status == "true") {
        console.log("Task data edit : ", data);
        console.log("primary div : ", primary_task);

        var task_data = data.goalBuddy;
        var habits_data = data.habitTask;
        $("#task_id").val(task_data.id);
        if (primary_task == "true") {
          //$(".task_notes").html("");
          var data = JSON.parse(sessionStorage.getItem("templateData"));

          if (data.goal_template.goal_buddy_task.length > 0) {
            var matched_task = data.goal_template.goal_buddy_task.filter(
              (task) => task.gb_task_name == task_data.gb_task_name
            );
            primaryTaskTemplate(matched_task, task_data);
          }

          $("#SYG3_task").attr("disabled", true);
          $(".SYG3_task").val(task_data.gb_task_name);
          if (habits_data != undefined) {
            $(".task-habit-div").show();
            var inputBox = "";

            //console.log("total habits : ", habits_data);
            $.each(habits_data, function (key, value) {
              //console.log("hey : ", value.id, " : ", task_data.gb_habit_id);
              if (value.id == task_data.gb_habit_id) {
                $("#task_habit_value").val(value.id);
                inputBox =
                  '<input data-toggle="tooltip" name="habit_value" disabled="true" value="' +
                  value.gb_habit_name +
                  '" ng-model="habit_value" type="text" class="form-control" id="habit_value" ng-keypress="pressEnter($event)">';
              }
            });
            $(".task-habit-dropdown").html(inputBox);
          }
          $("#task_habit_value").val(task_data.gb_habit_id);
        } else {
          $("#SYG3_task").val(task_data.gb_task_name);

          if (data.habitTask != "") {
            $(".task-habit-div").show();
            var optionValue =
              '<select id="habit_div" name="habit_value" class="form-control  taskhabit_div_class" required=""><option value="">-- Select --</option>';

            $.each(data.habitTask, function (key, value) {
              var selected = "";
              if (value.id == task_data.gb_habit_id) {
                selected = "selected";

                console.log("selected : ", task_data.gb_habit_id);
              }
              optionValue +=
                '<option value="' +
                value.id +
                '" ' +
                selected +
                ">" +
                value.gb_habit_name +
                "</option>";
            });
            optionValue += "</select>";
            $(".task-habit-dropdown").html(optionValue);
            initSelectpicker($(".task-habit-dropdown select"));

            $(".taskhabit_div_class").selectpicker("refresh");
            optionValue = "";
            sessionStorage.setItem(
              "all_habits_associated_task",
              JSON.stringify(data.habitTask)
            );
          }
        }

        $("#SYG_task_note").val(task_data.gb_task_note);

        if (task_data.gb_task_priority != undefined) {
          $(
            "input[type=radio][value='" + task_data.gb_task_priority + "']"
          ).attr("checked", true);
        }

        if (task_data.gb_task_reminder_epichq != undefined) {
          $(
            "input[type=radio][value='" +
              task_data.gb_task_reminder_epichq +
              "']"
          ).attr("checked", true);
        }
        if (task_data.gb_task_reminder != undefined) {
          $(
            "input[type=radio][value='" + task_data.gb_task_reminder + "']"
          ).attr("checked", true);
          if (task_data.gb_task_reminder == "daily") {
            $(".showTimeBox").show();
            $("#daily_time_task").val(task_data.gb_task_reminder_time);
          } else if (task_data.gb_task_reminder == "weekly") {
            $(".showDayBox").show();
            $("#weekly_day_task").val(task_data.gb_task_reminder_time);
          } else if (task_data.gb_task_reminder == "monthly") {
            $(".showMonthBox").show();
            $("#month_date_task").val(task_data.gb_task_reminder_time);
          }
        }
        if (task_data.gb_task_seen != undefined) {
          $("input[type=radio][value='" + task_data.gb_task_seen + "']").attr(
            "checked",
            true
          );

          if (task_data.gb_task_seen == "Selected friends") {
            console.log("SYG3_see_task: ", task_data.gb_task_selective_friends);
            $("#SYG3_selective_friends").val(
              task_data.gb_task_selective_friends
            );
            $("#SYG3_selective_friends").parent().removeClass("hidden");
          } else {
            $("#SYG3_selective_friends").attr("value", "");
            $("#SYG3_selective_friends").parent().addClass("hidden");
          }
        }

        if (task_data.gb_task_recurrence_type != undefined) {
          $(
            "input[type=radio][name='SYG_task_recurrence'][value='" +
              task_data.gb_task_recurrence_type +
              "']"
          ).attr("checked", true);

          if (
            task_data.gb_task_recurrence_type == "weekly" &&
            task_data.gb_task_recurrence_week
          ) {
            var recurrence_week = task_data.gb_task_recurrence_week.split(",");
            $("#task_recurrence_week_div").show();
            $("#task_recurrence_month_div").hide();
            if (recurrence_week.length > 0) {
              recurrence_week.map((value) => {
                $("input[type=checkbox][value='" + value + "']").attr(
                  "checked",
                  true
                );
              });
            }
          } else if (
            task_data.gb_task_recurrence_type == "monthly" &&
            task_data.gb_task_recurrence_month
          ) {
            $("#task_recurrence_month_div").show();
            $("#task_recurrence_week_div").hide();
            $("#gb_task_recurrence_month").val(
              task_data.gb_task_recurrence_month
            );
          }
        }
        setTimeout(function () {
          $("#waitingShield").addClass("hidden");
        }, 2000);
      }
    },
  });
}
$(document).on("change", "#goal_notes", function (e) {
  var goal_notes = $("#goal_notes").val();
  console.log(goal_notes);
  goal_notes = goal_notes.replace(/\n/g, "<br>");
  $(".gb_goal_notes").html(goal_notes);
});

$(document).on("click", ".step-back", function () {
  scrollToGoalTop();

  var goal_type = $("#goal_type").val();

  var check = $(".step-back");
  var templateCheck =
    $("input[name='chooseGoal']:checked").val() == "choose_form_template"
      ? true
      : false;

  if (check.hasClass("formStepfirst")) {
    var step_no = 1; //Create a new goal step
    if (goal_type == "choose_form_template") {
      step_no = 1;
    }

    $(".question-section").css("display", "none");

    editGoalDetails(step_no, goal_type);

    removeClasses();
    $(".step-back").addClass("formStepfirst");
    $(".step-forward").addClass("formStepSecond");

    $(".prev-name").text("DEFINE YOUR GOAL");
    $(".current-section").text("DEFINE YOUR GOAL");
    $(".next-name").text("ESTABLISH YOUR MILE STONES");

    $(".section-step").text("01");
    $(".all-section-step").text("05");
  } else if (check.hasClass("formStepSecond")) {
    var step_no = 13; //Create a new goal step
    if (goal_type == "choose_form_template") {
      step_no = 14;
    }
    editGoalDetails(step_no, goal_type);

    removeClasses();
    $(".step-back").addClass("formStepfirst");
    $(".step-forward").addClass("formStepThird");

    $(".prev-name").text("DEFINE YOUR GOAL");
    $(".current-section").text("ESTABLISH YOUR MILE STONES");
    $(".next-name").text("ESTABLISH NEW HABIT");

    $(".section-step").text("02");
    $(".all-section-step").text("05");
  } else if (check.hasClass("formStepThird")) {
    var step_no = 17; //Create a new goal step
    if (goal_type == "choose_form_template") {
      step_no = 18;
    }
    editGoalDetails(step_no, goal_type);

    removeClasses();
    $(".step-back").addClass("formStepSecond");
    $(".step-forward").addClass("formStepFourth");

    $(".prev-name").text("ESTABLISH YOUR MILE STONES");
    $(".current-section").text("ESTABLISH NEW HABIT");
    $(".next-name").text("CREATE TASKS");

    $(".section-step").text("03");
    $(".all-section-step").text("05");
  } else if (check.hasClass("formStepFourth")) {
    var step_no = 19; //Create a new goal step
    if (goal_type == "choose_form_template") {
      step_no = 20;
    }
    editGoalDetails(step_no, goal_type);

    removeClasses();
    $(".step-back").addClass("formStepThird");
    $(".step-forward").addClass("formStepFive");

    $(".prev-name").text("ESTABLISH NEW HABIT");
    $(".current-section").text("CREATE TASKS");
    $(".next-name").text("SMART REVIEW");

    $(".section-step").text("04");
    $(".all-section-step").text("05");
  }
  var prev_question_no = parseInt($(".step").data("step"));
  if (goal_type == "create_new_goal") {
    $(".question-section").css("display", "block");
    if (prev_question_no > 9) {
      $(".question-step").text(prev_question_no);
    } else {
      $(".question-step").text("0" + prev_question_no);
    }
  } else {
    prev_question_no = prev_question_no - 1;

    if (prev_question_no >= 1) {
      $(".question-section").css("display", "block");
    }

    if (prev_question_no > 9) {
      $(".question-step").text(prev_question_no);
    } else {
      $(".question-step").text("0" + prev_question_no);
    }
  }
});

$(document).on("click", ".step-forward", function () {
  scrollToGoalTop();
  //Completed work for this flag then needs to 0 after edit first habit
  $("#last_form_edit_habit").val("0");
  //Completed work for this flag then needs to 0 after edit first task
  $("#last_form_edit_task").val("0");

  var goal_type = $("#goal_type").val();

  var section_completed = parseInt($("#section_completed").val());

  console.log("section_completed : ", section_completed);

  if (section_completed == 0) {
    console.log("there is no section completed.");
    return false;
  }

  var check = $(".step-forward");
  var templateCheck =
    $("input[name='chooseGoal']:checked").val() == "choose_form_template"
      ? true
      : false;

  if (check.hasClass("formStepSecond")) {
    //Checking section completion
    if (section_completed < 2) {
      console.log("Second section is not completed.");
      return false;
    }

    var goal_type = $("#goal_type").val();
    var step_no = 13; //Create a new goal step
    if (goal_type == "choose_form_template") {
      step_no = 14;
    }
    editGoalDetails(step_no, goal_type);

    removeClasses();
    $(".step-back").addClass("formStepfirst");
    $(".step-forward").addClass("formStepThird");

    $(".prev-name").text("DEFINE YOUR GOAL");
    $(".current-section").text("ESTABLISH YOUR MILE STONES");
    $(".next-name").text("ESTABLISH NEW HABIT");

    $(".section-step").text("02");
    $(".all-section-step").text("05");

    $(".question-step").text("13");
    $(".all-question-step").text("20");

    // return false;
  } else if (check.hasClass("formStepThird")) {
    //Checking section completion
    if (section_completed < 3) {
      console.log("Third section is not completed.");
      return false;
    }
    var goal_type = $("#goal_type").val();
    var step_no = 17; //Create a new goal step
    if (goal_type == "choose_form_template") {
      step_no = 18;
    }
    editGoalDetails(step_no, goal_type);

    removeClasses();
    $(".step-back").addClass("formStepSecond");
    $(".step-forward").addClass("formStepFourth");

    $(".prev-name").text("ESTABLISH YOUR MILE STONES");
    $(".current-section").text("ESTABLISH NEW HABIT");
    $(".next-name").text("CREATE TASKS");

    $(".section-step").text("03");
    $(".all-section-step").text("05");

    $(".question-step").text("17");
    $(".all-question-step").text("20");

    // return false;
  } else if (check.hasClass("formStepFourth")) {
    //Checking section completion
    if (section_completed < 4) {
      console.log("Fourth section is not completed.");
      return false;
    }
    var goal_type = $("#goal_type").val();
    var step_no = 19; //Create a new goal step
    if (goal_type == "choose_form_template") {
      step_no = 20;
    }
    editGoalDetails(step_no, goal_type);

    removeClasses();
    $(".step-back").addClass("formStepThird");
    $(".step-forward").addClass("formStepFive");

    $(".prev-name").text("ESTABLISH NEW HABIT");
    $(".current-section").text("CREATE TASKS");
    $(".next-name").text("SMART REVIEW");

    $(".section-step").text("04");
    $(".all-section-step").text("05");

    $(".question-step").text("19");
    $(".all-question-step").text("20");

    // return false;
  } else if (check.hasClass("formStepFive")) {
    //Checking section completion
    if (section_completed < 5) {
      console.log("Fifth section is not completed.");
      return false;
    }
    var goal_type = $("#goal_type").val();
    var step_no = 20; //Create a new goal step
    if (goal_type == "choose_form_template") {
      step_no = 21;
    }
    editGoalDetails(step_no, goal_type);

    removeClasses();
    $(".step-back").addClass("formStepFourth");
    $(".step-forward").addClass("formStepFive");

    $(".prev-name").text("CREATE TASKS");
    $(".current-section").text("SMART REVIEW");
    $(".next-name").text("SMART REVIEW");

    $(".section-step").text("05");
    $(".all-section-step").text("05");

    $(".question-step").text("20");
    $(".all-question-step").text("20");
    // return false;
  }
  var next_question_no = $(".step").data("step");

  if (goal_type == "create_new_goal") {
    $(".question-section").css("display", "block");
    if (next_question_no > 9) {
      $(".question-step").text(next_question_no);
    } else {
      $(".question-step").text("0" + next_question_no);
    }
  } else {
    next_question_no = next_question_no - 1;

    if (next_question_no >= 1) {
      $(".question-section").css("display", "block");
    }

    if (next_question_no > 9) {
      $(".question-step").text(next_question_no);
    } else {
      $(".question-step").text("0" + next_question_no);
    }
  }
});

function removeClasses() {
  $(".step-back").removeClass("formStepfirst");
  $(".step-back").removeClass("formStepSecond");
  $(".step-back").removeClass("formStepThird");
  $(".step-back").removeClass("formStepFourth");
  $(".step-back").removeClass("formStepFive");

  $(".step-forward").removeClass("formStepfirst");
  $(".step-forward").removeClass("formStepSecond");
  $(".step-forward").removeClass("formStepThird");
  $(".step-forward").removeClass("formStepFourth");
  $(".step-forward").removeClass("formStepFive");
}

function scrollToGoalTop() {
  var targetElm = document.querySelector("#wizard_container"); // reference to scroll
  targetElm.scrollIntoView();
}

function moveSectionNext(current_step) {
  //Completed work for this flag then needs to 0 after edit first habit
  $("#last_form_edit_habit").val("0");
  //Completed work for this flag then needs to 0 after edit first task
  $("#last_form_edit_task").val("0");

  var goal_type = $("#goal_type").val();

  if (goal_type == "create_new_goal") {
    current_step = current_step - 1;
  }
  if (current_step == 12) {
    removeClasses();
    $(".step-back").addClass("formStepfirst");
    $(".step-forward").addClass("formStepThird");

    $(".prev-name").text("DEFINE YOUR GOAL");
    $(".current-section").text("ESTABLISH YOUR MILE STONES");
    $(".next-name").text("ESTABLISH NEW HABIT");

    $(".section-step").text("02");
    $(".all-section-step").text("05");

    //2 Section completed
    if (
      $("#section_completed").val() != "5" &&
      $("#section_completed").val() != "4" &&
      $("#section_completed").val() != "3"
    ) {
      $("#section_completed").val("2");
    }
  } else if (current_step == 15) {
    removeClasses();
    $(".step-back").addClass("formStepSecond");
    $(".step-forward").addClass("formStepFourth");

    $(".prev-name").text("ESTABLISH YOUR MILE STONES");
    $(".current-section").text("ESTABLISH NEW HABIT");
    $(".next-name").text("CREATE TASKS");

    $(".section-step").text("03");
    $(".all-section-step").text("05");

    //3 Section completed
    if (goal_type == "create_new_goal") {
      if (
        $("#section_completed").val() != "5" &&
        $("#section_completed").val() != "4"
      ) {
        $("#section_completed").val("3");
      }
    }
  } else if (current_step == 17) {
    removeClasses();
    $(".step-back").addClass("formStepThird");
    $(".step-forward").addClass("formStepFive");

    $(".prev-name").text("ESTABLISH NEW HABIT");
    $(".current-section").text("CREATE TASKS");
    $(".next-name").text("SMART REVIEW");

    $(".section-step").text("04");
    $(".all-section-step").text("05");

    //4 Section completed
    if (goal_type == "create_new_goal") {
      if ($("#section_completed").val() != "5") {
        $("#section_completed").val("4");
      }
    }
  } else if (current_step == 19) {
    removeClasses();
    $(".step-back").addClass("formStepFourth");
    $(".step-forward").addClass("formStepFive");

    $(".prev-name").text("CREATE TASKS");
    $(".current-section").text("SMART REVIEW");
    $(".next-name").text("SMART REVIEW");

    $(".section-step").text("05");
    $(".all-section-step").text("05");

    $("#section_completed").val("5");
  }

  //Task or habit completion then we can complete the section (only for template goal)
  var current_habit_step = $("#current_habit_step").val();
  var current_task_step = $("#current_task_step").val();
  if (goal_type == "choose_form_template") {
    if (current_habit_step == "3") {
      //3 Section completed
      if ($("#section_completed").val() != "5") {
        $("#section_completed").val("3");
      }
    }
    if (current_task_step == "9") {
      //4 Section completed
      if ($("#section_completed").val() != "5") {
        $("#section_completed").val("4");
      }
    }
  }
}

function moveSectionPrev(current_step) {
  console.log("movesection prev : ", current_step);
  if (current_step < 13) {
    removeClasses();
    $(".step-back").addClass("formStepfirst");
    $(".step-forward").addClass("formStepSecond");

    $(".prev-name").text("DEFINE YOUR GOAL");
    $(".current-section").text("DEFINE YOUR GOAL");
    $(".next-name").text("ESTABLISH YOUR MILE STONES");

    $(".section-step").text("01");
    $(".all-section-step").text("05");
  } else if (current_step <= 15) {
    removeClasses();
    $(".step-back").addClass("formStepfirst");
    $(".step-forward").addClass("formStepThird");

    $(".prev-name").text("DEFINE YOUR GOAL");
    $(".current-section").text("ESTABLISH YOUR MILE STONES");
    $(".next-name").text("ESTABLISH NEW HABIT");

    $(".section-step").text("02");
    $(".all-section-step").text("05");
  } else if (current_step == 17) {
    removeClasses();
    $(".step-back").addClass("formStepSecond");
    $(".step-forward").addClass("formStepFourth");

    $(".prev-name").text("ESTABLISH YOUR MILE STONES");
    $(".current-section").text("ESTABLISH NEW HABIT");
    $(".next-name").text("CREATE TASKS");

    $(".section-step").text("03");
    $(".all-section-step").text("05");
  } else if (current_step == 19) {
    removeClasses();
    $(".step-back").addClass("formStepThird");
    $(".step-forward").addClass("formStepFive");

    $(".prev-name").text("ESTABLISH NEW HABIT");
    $(".current-section").text("CREATE TASKS");
    $(".next-name").text("SMART REVIEW");

    $(".section-step").text("04");
    $(".all-section-step").text("05");
  } else if (current_step == 20) {
    removeClasses();
    $(".step-back").addClass("formStepFourth");
    $(".step-forward").addClass("formStepFive");

    $(".prev-name").text("CREATE TASKS");
    $(".current-section").text("SMART REVIEW");
    $(".next-name").text("SMART REVIEW");

    $(".section-step").text("05");
    $(".all-section-step").text("05");
  }
}
function moveSectionEditInitial(current_step) {
  console.log("moveSectionEditInitial : ", current_step);
  if (current_step < 13) {
    removeClasses();
    $(".step-back").addClass("formStepfirst");
    $(".step-forward").addClass("formStepSecond");

    $(".prev-name").text("DEFINE YOUR GOAL");
    $(".current-section").text("DEFINE YOUR GOAL");
    $(".next-name").text("ESTABLISH YOUR MILE STONES");

    $(".section-step").text("01");
    $(".all-section-step").text("05");

    if ($("#section_completed").val() != "5") {
      $("#section_completed").val("1");
    }
  } else if (current_step >= 13 && current_step <= 15) {
    removeClasses();
    $(".step-back").addClass("formStepfirst");
    $(".step-forward").addClass("formStepThird");

    $(".prev-name").text("DEFINE YOUR GOAL");
    $(".current-section").text("ESTABLISH YOUR MILE STONES");
    $(".next-name").text("ESTABLISH NEW HABIT");

    $(".section-step").text("02");
    $(".all-section-step").text("05");

    if ($("#section_completed").val() != "5") {
      $("#section_completed").val("1");
    }
  } else if (current_step >= 16 && current_step <= 17) {
    removeClasses();
    $(".step-back").addClass("formStepSecond");
    $(".step-forward").addClass("formStepFourth");

    $(".prev-name").text("ESTABLISH YOUR MILE STONES");
    $(".current-section").text("ESTABLISH NEW HABIT");
    $(".next-name").text("CREATE TASKS");

    $(".section-step").text("03");
    $(".all-section-step").text("05");

    if ($("#section_completed").val() != "5") {
      $("#section_completed").val("2");
    }
  } else if (current_step >= 18 && current_step <= 19) {
    removeClasses();
    $(".step-back").addClass("formStepThird");
    $(".step-forward").addClass("formStepFive");

    $(".prev-name").text("ESTABLISH NEW HABIT");
    $(".current-section").text("CREATE TASKS");
    $(".next-name").text("SMART REVIEW");

    $(".section-step").text("04");
    $(".all-section-step").text("05");

    if ($("#section_completed").val() != "5") {
      $("#section_completed").val("3");
    }
  } else if (current_step == 20) {
    removeClasses();
    $(".step-back").addClass("formStepFourth");
    $(".step-forward").addClass("formStepFive");

    $(".prev-name").text("CREATE TASKS");
    $(".current-section").text("SMART REVIEW");
    $(".next-name").text("SMART REVIEW");

    $(".section-step").text("05");
    $(".all-section-step").text("05");

    $("#section_completed").val("4");
  }
}
