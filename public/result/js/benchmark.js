
$(document).ready(function(){ 
	 // initCustomValidator();
	$('#showBenchmarkForm').click(function(e){
		//console.log('check');
		//$('input[name=benchmarkEditId]').val('');
	    benckmarkReset();

		$(this).addClass('hidden');
		$('#createBenchmark').removeClass('hidden');
		$('.starting-screen').removeClass('hidden');
		$('#benchmark-list').addClass('hidden');
		$('#hideBenchmarkForm').removeClass('hidden');
	})

	$('#hideBenchmarkForm').click(function(e){
		$('input[name=benchmarkEditId]').val('');
		if($(this).hasClass('reloadBtn')){
		     reloadPageWithTab("#benchmarks");
		     $(this).removeClass('reloadBtn');  
		 }
		 else{
            $(this).addClass('hidden');
			$('#createBenchmark').addClass('hidden');
			$('.starting-screen').addClass('hidden');
			$('#benchmark-list').removeClass('hidden');
			$('#showBenchmarkForm').removeClass('hidden');
			$('#benchmarke-details-field').addClass('hidden');
		 }
	})

	//show details of benchmarkes with id
	$('.benchmark-view-edit').click(function(e){
       e.preventDefault();

       //ajex for feching data in benchmarkes table....
       var benchmarkId=$(this).data('benchmarkid');
       var btn_type=$(this).data("btntype");
       //console.log(benchmarkId);

       $.ajax({
			url: public_url+'benchmark/'+benchmarkId,
			method: "get",
			data: {},
			success: function(data) {
				var myObj=JSON.parse(data);
				if(btn_type=='view-list'){
					displayBenchmarkData(myObj);
			    }
			    else{
			    	$('#benchmarkWizard').smartWizard("goToStep", 1);
			    	if(myObj.nps_automatic_time=='1'){
			    		$('.bm_time_selectable').find('.automatic').addClass('ui-selected');
			    		$('.bm_time_selectable').find('.manual').removeClass('ui-selected');
			    		var min=myObj.nps_time_min;
			    		if(min>0 && min % 5 != 0){
			    			minmod=min % 5;
			    			min-=minmod;
			    			if(min<10)
			    				min='0'+min;
			    		}
			    		$('select#time_min').val(min).selectpicker('refresh');
			    	}
			    	else{
			    		$('.bm_time_selectable').find('.manual').addClass('ui-selected');
			    		$('.bm_time_selectable').find('.automatic').removeClass('ui-selected');
			    		$('select#time_min').val(myObj.nps_time_min).selectpicker('refresh');
			    	}
			    	toggleBmTimeFields($('.bm_time_selectable'));
			    	$('input[name=bm_time_day]').val(dbDateToDate(myObj.nps_day));
			    	$('select#time_hour').val(myObj.nps_time_hour).selectpicker('refresh');
			    	$('.stress').val(myObj.stress);
			    	$('.sleep').val(myObj.sleep);
			    	$('.nutrition').val(myObj.nutrition);
			    	$('.hydration').val(myObj.hydration);
			    	$('.humidity').val(myObj.humidity);
			    	$('.rating-tooltip').trigger('change');
			    	if(myObj.stress=="")
			    	    $('.label-success').hide();
			    	$('#temperatureEdit').val(myObj.benchmarkTemperature).selectpicker('refresh');
			    	$('input[name=bm_waist]').val(myObj.waist);
			    	$('input[name=bm_hips]').val(myObj.hips);
			    	$('input[name=bm_height]').val(myObj.height);
			    	$('input[name=bm_weight]').val(myObj.weight);
			    	$('input[name=bm_pressups]').val(myObj.pressups);
			    	if(myObj.plank != ''){
			    		var newPlank = myObj.plank,
			    			newPlankArray = newPlank.split(":");
			    		$('#bm_plank_min').val(newPlankArray[0]);
			    		$('#bm_plank_sec').val(newPlankArray[1]);
			    	}
			    	$('input[name=bm_plank]').val(myObj.plank);
			    	if(myObj.timetrial3k != ''){
			    		var newTimetrial3k = myObj.timetrial3k,
			    			newTimetrial3kArray = newTimetrial3k.split(":");
			    		$('#bm_timetrial3k_min').val(newTimetrial3kArray[0]);
			    		$('#bm_timetrial3k_sec').val(newTimetrial3kArray[1]);
			    	}
			    	$('input[name=bm_timetrial3k]').val(myObj.timetrial3k);
			    	$('input[name=bm_bpm1]').val(myObj.cardiobpm1);
			    	$('input[name=bm_bpm2]').val(myObj.cardiobpm2);
			    	$('input[name=bm_bpm3]').val(myObj.cardiobpm3);
			    	$('input[name=bm_bpm4]').val(myObj.cardiobpm4);
			    	$('input[name=bm_bpm5]').val(myObj.cardiobpm5);
			    	$('input[name=bm_bpm6]').val(myObj.cardiobpm6);
			    	$('input[name=benchmarkEditId]').val(benchmarkId);
			    	//for edit code............
			    }
	         }
       });
       if(btn_type=='view-list'){ 
          $("#createBenchmark").addClass('hidden');       
	      $('#benchmarke-details-field').removeClass('hidden'); 
	   }
	   else{
	   	  $('#benchmarke-details-field').addClass('hidden'); 
     	  $("#createBenchmark").removeClass('hidden');
	   }
	   $('#benchmark-list').addClass('hidden');
	   $('#hideBenchmarkForm').removeClass('hidden');
	   $('#showBenchmarkForm').addClass('hidden');
	});
    
    /*start: Edit benchmark */
	/*$('#benchmark-edit').click(function(e){
        e.preventDefault();
       

       $("#createBenchmark").removeClass('hidden');
       $("#benchmark-list").addClass('hidden'); 
       $('#hideBenchmarkForm').removeClass('hidden');
       $('#showBenchmarkForm').addClass('hidden');
	});
	/*end: Edit benchmark */

	$('.rating-symbol').click(function(e){
          e.preventDefault();
	});

});
  
   function benckmarkReset()
   {
   	    $('.stress').val(0);
    	$('.sleep').val(0);
    	$('.nutrition').val(0);
    	$('.hydration').val(0);
    	$('.humidity').val(0);
    	//$('.label-success').css("visibility","hidden");
    	$('.rating-tooltip').trigger('change');
    	$('.label-success').hide();
    	$('input[name=bm_temp]').val('35').selectpicker('refresh');
   	    clearForm($('#form-1'));
		clearForm($('#form-2'));
		clearForm($('#form-3'));
		clearForm($('#form-4'));
		$('#benchmarkWizard').find('select').selectpicker('refresh');
		$('#benchmarkWizard').smartWizard("goToStep", 1);
		$('.bm_time_selectable').find('.manual').addClass('ui-selected');
		$('.bm_time_selectable').find('.automatic').removeClass('ui-selected');
		toggleBmTimeFields($('.bm_time_selectable'));
   }
   