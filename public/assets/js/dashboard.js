
/*console.log($('#salesbar').outerHeight());
$('.now-style').height($('#salesbar').outerHeight() - 200);*/
/* Start: ---- Sales stackbar ---- */ 
/*var sales_bar_width=$('#salesbar').width();
console.log(sales_bar_width);*/
/* Start: Create Days for sales bar */
//var steps_offset=max_value/9;
$(document).ready(function(){
    //var productivity_bar_width=$('#productivitybar').width();
    
    /* Start: Chart-Setting */
    $('#chartSetting').on('shown.bs.modal', function(e) {
        var elem=(e.relatedTarget);
        var modal=$(this);
        var chart_type=$(elem).data('type');
        if(chart_type=='clientsChart'){
            modal.find('#clientsChart').removeClass('hidden');
            modal.find('#salesProChart').addClass('hidden');
        }
        else if(chart_type=='salesProChart'){
            modal.find('#clientsChart').addClass('hidden');
            modal.find('#salesProChart').removeClass('hidden');
        }
        $('[name="chart_type"]').val(chart_type);       
    });
    /* End: Chart-Setting */

    /* Start: chart setting ajax */
    $('#chart-setting-save').click(function(){
        var formData={};
        //var form=$('#chart-setting-form');
        var chartType=$('[name="chart_type"]').val();
        var form=$('#'+chartType);
        formData['chart_type']=chartType;

        form.find(':input').each(function(){
            //formData[$(this).attr('name')] = $(this).val()
            var type = this.type,
                $this = $(this);

            if(type != 'checkbox' || (type == 'checkbox' && $this.is(':checked')))
                formData[$this.attr('name')] = $this.val();
        });

        $.ajax({
                url: public_url+'dashboard/chart-setting',
                method: "POST",
                data: formData,
                success: function(data){
                    var data = JSON.parse(data);
                    if(data.status == "updated"){
                         $('#chartSetting').modal('hide');
                         location.reload();
                    }
                }
            });

       //console.log(formData);

    });
    /* End: chart setting ajax */
    /* Start: Custom tooltip for bar chart on TODAY  area */

    /* End: Custom tooltip for bar chart on TODAY  area */
    var new_offset=maxOffsetCal(max_value);
    var new_maxval=new_offset*9;
    var startdate = new moment().subtract(7, "days");    
    var daysforlabel=[];
       for(var i=1;i<15;i++){
            daysforlabel.push(startdate.add(1, "days").format('ddd, DD MMM'));
       }
/* End: Create Days for sales bar */

    var salesData = {
            labels:daysforlabel /*["9 june", "10 june", "11 june", "12 june", "13 june", "14 june", "15 june","16 june","17 june","18 june","19 june","20 june","21 june"]*/,
            datasets: [{
                label: 'Pencilled-in appointments',
                backgroundColor:'#7E8085',
                data: total_pencilledin
            }, {
                label: 'Confirmed appointments',
                backgroundColor:'#ff4401' ,
                data: total_confirmed
            }]
           
        }

        var options = {
            maintainAspectRatio: false,
          
              /*animation:false,
              scaleOverride:true,
              scaleSteps:9,
              scaleStartValue:0,
              scaleStepWidth:100,*/
              //stepSize:9,
            // Sets the chart to be responsive
            responsive: true,

            //Boolean - Whether the scale should start at zero, or an order of magnitude down from the lowest value
            //scaleBeginAtZero: true,

            //Boolean - Whether grid lines are shown across the chart
            scaleShowGridLines: true,

            //String - Colour of the grid lines
            scaleGridLineColor: "rgba(0,0,0,.05)",

            //Number - Width of the grid lines
            scaleGridLineWidth: 1,

            //Boolean - If there is a stroke on each bar
            barShowStroke: true,

            //Number - Pixel width of the bar stroke
            barStrokeWidth: 2,

            //Number - Spacing between each of the X value sets
            barValueSpacing: 5,
            
            //Number - Spacing between data sets within X values
            scales: {
                        xAxes: [{
                            stacked: true,
                            barPercentage: 0.5,
                        }],
                        yAxes: [{
                            ticks: {
                                max: new_maxval/*105*/,
                                min: 0,
                                stepSize:new_offset/*15*/,
                                userCallback: function(value, index, values) 
                                     { return '$'+value; }  
                            },
                            stacked: true      
                        }]
                    },
              tooltips: {
                enabled: true,
                mode: 'single',
                callbacks: {
                    label: function(tooltipItems, data) {
                        /*if(tooltipItems.index==6){

                        } */
                        return data.datasets[tooltipItems.datasetIndex].label +': '+' $'+tooltipItems.yLabel;
                    }
                }
            },       
            //barThickness:100,
            //String - A legend template
           // legendTemplate: '<ul class="tc-chart-js-legend"><% for (var i=0; i<datasets.length; i++){%><li class="pull-left"><span style="background-color:<%=datasets[i].fillColor%>"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>',
            legendCallback: function(chart) {
                var text = [];
                text.push('<ul class="tc-chart-js-legend">');
                for (var i=0; i<chart.data.datasets.length; i++) {
                    text.push('<li>');
                    if (chart.data.datasets[i].label) {
                        text.push('<span style="background-color:' + chart.data.datasets[i].backgroundColor + '"></span>'+chart.data.datasets[i].label);
                    }
                    text.push('</li>');
                }
                text.push('</ul>');
                return text.join("");
            },

            legend: {
            display: false
            }
        }

            var ctx = document.getElementById("salesbar").getContext("2d");
            window.salesbar  = new Chart(ctx, {
                type: 'bar',
                data: salesData,
                options: options
            });
              
            //generate the legend
        var legend = salesbar.generateLegend();
        //and append it to your page somewhere
        $('#sales-legend').append(legend);
  /*   End: ---- Stackbar for SALES ---- */       
        
  /* Start: ---- Stack bar for  --------*/
   var pen_data=[],
       conf_data=[],
       attended_data=[],
       notshow_data=[],
       busy_data=[],
       cls_data=[],
       available_time=[];
       total_ocupied_time=0,
       last7_available_hour=0,
       next7_available_hour=0,
       last7_booked_time=0,
       next7_booked_time=0;
       
   for (var i = 0; i < 14; i++) {
            pen_data[i]=getTimeFromMins(total_pen_time[i]);
            conf_data[i]=getTimeFromMins(total_conf_time[i]);
            notshow_data[i]=getTimeFromMins(total_notshow_time[i]);
            attended_data[i]=getTimeFromMins(total_attended_time[i]);
            busy_data[i]=getTimeFromMins(total_busy_time[i]);
            cls_data[i]=getTimeFromMins(total_cls_time[i]);
            total_ocupied_time=(total_pen_time[i]+total_conf_time[i]+total_attended_time[i]+total_busy_time[i]);
            var d1=(total_working_time[i]-total_ocupied_time);
            available_time[i]=getTimeFromMins(d1);
            //console.log(d1);
            //console.log(total_pen_time[i]);
            if(i<7){
                last7_available_hour+=total_working_time[i];
                last7_booked_time+=(total_pen_time[i]+total_conf_time[i]+total_attended_time[i]);
            }
            else{
                next7_available_hour+=total_working_time[i];
                next7_booked_time+=(total_pen_time[i]+total_conf_time[i]+total_attended_time[i]);
            }

    } 
  


    /* Strat: calculation for total booked percentege */ 
    var last7_booked_per=0,
        next7_booked_per=0;
     last7_booked_per= Math.round((last7_booked_time*100)/(last7_available_hour/60)); 
     next7_booked_per= Math.round((next7_booked_time*100)/(next7_available_hour/60));
     if(isNaN(last7_booked_per))
        last7_booked_per = 0
    if(isNaN(next7_booked_per))
        next7_booked_per = 0
     $('#last7-per-data').text(last7_booked_per);
     $('#next7-per-data').text(next7_booked_per);
    var timeOffset=maxOffsetCal(max_time);
    var newmax_time=timeOffset*9;
   /* End: calculation for total booked percentege */
     var productivityData = {
            labels:daysforlabel,
            datasets: [{
                label: 'Available hours',
                backgroundColor:'#fff' ,
                borderColor:'#ccc',
                borderWidth: 1,
                data: available_time
            }, {
                label: 'Pencilled-in appointments',
                backgroundColor:'#7E8085',
                borderColor:'#7E8085',
                borderWidth: 1,
                data: pen_data
            }, {
                label: 'Confirmed appointments',
                backgroundColor:'#ff4401',
                borderColor:'#ff4401',
                borderWidth: 1,
                data: conf_data
            },{
                label: 'Attended appointments',
                backgroundColor:'#4286f4',
                borderColor:'#4286f4',
                borderWidth: 1,
                data: attended_data
            },{
                label: 'Did not show appointments',
                backgroundColor:'#09026b' ,
                borderColor:'#09026b',
                borderWidth: 1,
                data: notshow_data
            },{
                label: 'Breaks and busy times',
                backgroundColor:'#ffbf00',
                borderColor:'#ffbf00',
                borderWidth: 1,
                data: busy_data
            },{
                label: 'Class times',
                backgroundColor:'#0a0',
                borderColor:'#0a0',
                borderWidth: 1,
                data: cls_data
            }]
           
        }

        var options = {
            maintainAspectRatio: false,
          
              /*animation:false,
              scaleOverride:true,
              scaleSteps:9,
              scaleStartValue:0,
              scaleStepWidth:100,*/
              stepSize:9,
            // Sets the chart to be responsive
            responsive: true,

            //Boolean - Whether the scale should start at zero, or an order of magnitude down from the lowest value
            //scaleBeginAtZero: true,

            //Boolean - Whether grid lines are shown across the chart
            scaleShowGridLines: true,

            //String - Colour of the grid lines
            scaleGridLineColor: "rgba(0,0,0,.05)",

            //Number - Width of the grid lines
            scaleGridLineWidth: 1,

            //Boolean - If there is a stroke on each bar
            barShowStroke: true,

            //Number - Pixel width of the bar stroke
            barStrokeWidth: 2,

            //Number - Spacing between each of the X value sets
            barValueSpacing: 5,

            //Number - Spacing between data sets within X values
            scales: {
                        xAxes: [{
                            stacked: true,
                            barPercentage: 0.4,
                        }],
                        yAxes: [{
                            ticks: {
                                max: newmax_time,
                                min: 0,
                                stepSize: timeOffset,
                                userCallback: function(value, index, values) { return value+' hours'; }
                            },
                            stacked: true
                        }]
                    },
              tooltips: {
                enabled: true,
                mode: 'single',
                callbacks: {
                    label: function(tooltipItems, data) { 
                        var time = tooltipItems.yLabel;

                        time = time.toString().split(".");
                        
                        var tooltip = time[0]+' hrs ';
                        if(time.length > 1){
                            var min = time[1];
                            if(min.length == 1)
                                min += '0';
                            tooltip += min+' min';
                        }
                        return data.datasets[tooltipItems.datasetIndex].label +': '+tooltip/*time+'hrs'*/;
                    }
                }
            },       
               legendCallback: function(chart) {
                var text = [];
                text.push('<ul class="tc-chart-js-legend chart-legend-custom">');
                for (var i=0; i<chart.data.datasets.length; i++) {
                    text.push('<li>');
                    if (chart.data.datasets[i].label) {
                        text.push('<span style="background-color:' + chart.data.datasets[i].backgroundColor + '"></span>'+chart.data.datasets[i].label);
                    }
                    text.push('</li>');
                }
                text.push('</ul>');
                return text.join("");
            },

            legend: {
            display: false
            }
        }

            var ctx = document.getElementById("productivitybar").getContext("2d");
            window.productivitybar  = new Chart(ctx, {
                type: 'bar',
                data: productivityData,
                options: options
            });
         //generate the legend
        var legend = productivitybar.generateLegend();
        //and append it to your page somewhere
        $('#productivity-legend').append(legend);
  /* End:   -----Stack br for   --------*/
 });
 
 /*function getTimeFromMins(mins) {
    mins=parseInt(mins);
    return (mins / 60).toFixed(2);
}*/
function getTimeFromMins(mins) {
    if(mins<60){
        //return "0."+moment.utc().minutes(mins).format("mm"); 
        return "0."+mins;   
    }
    var h = Math.floor(mins / 60) ,
        m = mins % 60
        //mPer = Math.floor((m/60)*100);
    if(m < 10)
        m = '0'+m;
    return h+'.'+m;
    //return moment.utc().hours(h).minutes(m).format("hh.mm");
}

function maxOffsetCal(value){
    var maxoffset=Math.ceil(value/8);
    var offset_mod= maxoffset%10;
    var newoffset=maxoffset+10-offset_mod;
   return newoffset; 
}