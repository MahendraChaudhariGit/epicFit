$("#datepicker1").datepicker({ 
    changeMonth: true,
    changeYear: true,
    yearRange: '1950:2050',
    dateFormat : 'yy-mm-dd',
});

var score = $("#total_score").val();

var prevSelect1 = '';
var scorePrev1 = $("#getup_score").find(':selected').attr('data-value');
$("#getup_score").change(function(){
    prevSelect1 = scorePrev1;
    scorePrev1 = $(this).find(':selected').attr('data-value');
    score = Number(score)-Number(prevSelect1);
    score = Number(score)+Number($(this).find(':selected').attr('data-value'));
    $("#total_score").val(score)
})

var prevSelect2 = '';
var scorePrev2 = $("#goto_bed_score").find(':selected').attr('data-value');
$("#goto_bed_score").change(function(){
    prevSelect2 = scorePrev2;
    scorePrev2 = $(this).find(':selected').attr('data-value');
    score = Number(score)-Number(prevSelect2);
    score = Number(score)+Number($(this).find(':selected').attr('data-value'));
    $("#total_score").val(score)
})

var prevSelect3 = '';
var scorePrev3 = $("#specific_time_getup_score").find(':selected').attr('data-value');
$("#specific_time_getup_score").change(function(){
    prevSelect3 = scorePrev3;
    scorePrev3 = $(this).find(':selected').attr('data-value');
    score = Number(score)-Number(prevSelect3);
    score = Number(score)+Number($(this).find(':selected').attr('data-value'));
    $("#total_score").val(score)
})

var prevSelect4 = '';
var scorePrev4 = $("#easy_to_getup_score").find(':selected').attr('data-value');
$("#easy_to_getup_score").change(function(){
    prevSelect4 = scorePrev4;
    scorePrev4 = $(this).find(':selected').attr('data-value');
    score = Number(score)-Number(prevSelect4);
    score = Number(score)+Number($(this).find(':selected').attr('data-value'));
    $("#total_score").val(score)
})

var prevSelect5 = '';
var scorePrev5 = $("#first_half_hour_score").find(':selected').attr('data-value');
$("#first_half_hour_score").change(function(){
    prevSelect5 = scorePrev5;
    scorePrev5 = $(this).find(':selected').attr('data-value');
    score = Number(score)-Number(prevSelect5);
    score = Number(score)+Number($(this).find(':selected').attr('data-value'));
    $("#total_score").val(score)
})

var prevSelect6 = '';
var scorePrev6 = $("#first_half_hour_hungry_score").find(':selected').attr('data-value');
$("#first_half_hour_hungry_score").change(function(){
    prevSelect6 = scorePrev6;
    scorePrev6 = $(this).find(':selected').attr('data-value');
    score = Number(score)-Number(prevSelect6);
    score = Number(score)+Number($(this).find(':selected').attr('data-value'));
    $("#total_score").val(score)
})

var prevSelect7 = '';
var scorePrev7 = $("#first_half_hour_tired_score").find(':selected').attr('data-value');
$("#first_half_hour_tired_score").change(function(){
    prevSelect7 = scorePrev7;
    scorePrev7 = $(this).find(':selected').attr('data-value');
    score = Number(score)-Number(prevSelect7);
    score = Number(score)+Number($(this).find(':selected').attr('data-value'));
    $("#total_score").val(score)
})

var prevSelect8 = '';
var scorePrev8 = $("#no_commitment_goto_bed_score").find(':selected').attr('data-value');
$("#no_commitment_goto_bed_score").change(function(){
    prevSelect8 = scorePrev8;
    scorePrev8 = $(this).find(':selected').attr('data-value');
    score = Number(score)-Number(prevSelect8);
    score = Number(score)+Number($(this).find(':selected').attr('data-value'));
    $("#total_score").val(score)
})

var prevSelect9 = '';
var scorePrev9 = $("#engage_in_physical_exercise_score").find(':selected').attr('data-value');
$("#engage_in_physical_exercise_score").change(function(){
    prevSelect9 = scorePrev9;
    scorePrev9 = $(this).find(':selected').attr('data-value');
    score = Number(score)-Number(prevSelect9);
    score = Number(score)+Number($(this).find(':selected').attr('data-value'));
    $("#total_score").val(score)
})

var prevSelect10 = '';
var scorePrev10 = $("#feel_tired_in_day_score").find(':selected').attr('data-value');
$("#feel_tired_in_day_score").change(function(){
    prevSelect10 = scorePrev10;
    scorePrev10 = $(this).find(':selected').attr('data-value');
    score = Number(score)-Number(prevSelect10);
    score = Number(score)+Number($(this).find(':selected').attr('data-value'));
    $("#total_score").val(score)
})

var prevSelect11 = '';
var scorePrev11 = $("#peak_performance_score").find(':selected').attr('data-value');
$("#peak_performance_score").change(function(){
    prevSelect11 = scorePrev11;
    scorePrev11 = $(this).find(':selected').attr('data-value');
    score = Number(score)-Number(prevSelect11);
    score = Number(score)+Number($(this).find(':selected').attr('data-value'));
    $("#total_score").val(score)
})

var prevSelect12 = '';
var scorePrev12 = $("#got_into_bed_tired_score").find(':selected').attr('data-value');
$("#got_into_bed_tired_score").change(function(){
    prevSelect12 = scorePrev12;
    scorePrev12 = $(this).find(':selected').attr('data-value');
    score = Number(score)-Number(prevSelect12);
    score = Number(score)+Number($(this).find(':selected').attr('data-value'));
    $("#total_score").val(score)
})

var prevSelect13 = '';
var scorePrev13 = $("#goto_several_hour_into_bed_score").find(':selected').attr('data-value');
$("#goto_several_hour_into_bed_score").change(function(){
    prevSelect13 = scorePrev13;
    scorePrev13 = $(this).find(':selected').attr('data-value');
    score = Number(score)-Number(prevSelect13);
    score = Number(score)+Number($(this).find(':selected').attr('data-value'));
    $("#total_score").val(score)
})

var prevSelect14 = '';
var scorePrev14 = $("#one_night_awake_score").find(':selected').attr('data-value');
$("#one_night_awake_score").change(function(){
    prevSelect14 = scorePrev14;
    scorePrev14 = $(this).find(':selected').attr('data-value');
    score = Number(score)-Number(prevSelect14);
    score = Number(score)+Number($(this).find(':selected').attr('data-value'));
    $("#total_score").val(score)
})

var prevSelect15 = '';
var scorePrev15 = $("#two_hour_hard_work_score").find(':selected').attr('data-value');
$("#two_hour_hard_work_score").change(function(){
    prevSelect15 = scorePrev15;
    scorePrev15 = $(this).find(':selected').attr('data-value');
    score = Number(score)-Number(prevSelect15);
    score = Number(score)+Number($(this).find(':selected').attr('data-value'));
    $("#total_score").val(score)
})

var prevSelect16 = '';
var scorePrev16 = $("#physical_exercise_score").find(':selected').attr('data-value');
$("#physical_exercise_score").change(function(){
    prevSelect16 = scorePrev16;
    scorePrev16 = $(this).find(':selected').attr('data-value');
    score = Number(score)-Number(prevSelect16);
    score = Number(score)+Number($(this).find(':selected').attr('data-value'));
    $("#total_score").val(score)
})

var prevSelect17 = '';
var scorePrev17 = $("#school_hours_score").find(':selected').attr('data-value');
$("#school_hours_score").change(function(){
    prevSelect17 = scorePrev17;
    scorePrev17 = $(this).find(':selected').attr('data-value');
    score = Number(score)-Number(prevSelect17);
    score = Number(score)+Number($(this).find(':selected').attr('data-value'));
    $("#total_score").val(score)
})

var prevSelect18 = '';
var scorePrev18 = $("#feeling_best_score").find(':selected').attr('data-value');
$("#feeling_best_score").change(function(){
    prevSelect18 = scorePrev18;
    scorePrev18 = $(this).find(':selected').attr('data-value');
    score = Number(score)-Number(prevSelect18);
    score = Number(score)+Number($(this).find(':selected').attr('data-value'));
    $("#total_score").val(score)
})

var prevSelect19 = '';
var scorePrev19 = $("#types_of_people_score").find(':selected').attr('data-value');
$("#types_of_people_score").change(function(){
    prevSelect19 = scorePrev19;
    scorePrev19 = $(this).find(':selected').attr('data-value');
    score = Number(score)-Number(prevSelect19);
    score = Number(score)+Number($(this).find(':selected').attr('data-value'));
    $("#total_score").val(score)
})