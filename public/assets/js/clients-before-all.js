function realTimeUpdate(fieldType, val){
	if(fieldType == 'email'){
		$('[data-realtime="'+fieldType+'"]').each(function(){
			var $elem = $(this),
				elemType = $elem.prop('nodeName');

			if(elemType == 'A'){
				if($elem.hasClass('editFieldModal'))
					$elem.data('value', val);
				else{
					$elem.attr('href', 'mailto:'+val)
					$elem.text(val)
				}
			}
			else if(elemType == 'INPUT')
				$elem.val(val)
		})
	}
	else if(fieldType == 'phone'){
		$('[data-realtime="'+fieldType+'"]').each(function(){
			var $elem = $(this),
				elemType = $elem.prop('nodeName');

			if(elemType == 'A'){
				if($elem.hasClass('editFieldModal'))
					$elem.data('value', val);
				else{
					$elem.attr('href', 'tel:'+val)
					$elem.text(val)
				}
			}
			else if(elemType == 'INPUT')
				$elem.intlTelInput("setNumber", val.toString());
		})
	}
	else if($.inArray(fieldType, ['firstName', 'lastName', 'occupation']) !== -1){
		$('[data-realtime="'+fieldType+'"]').each(function(){
			var $elem = $(this),
				elemType = $elem.prop('nodeName');

			if(elemType == 'INPUT')
				$elem.val(val);
			else if(elemType == 'A' && $elem.hasClass('editFieldModal'))
				$elem.data('value', val)
			else
				$elem.text(val)
		})
	}
	else if(fieldType == 'accStatus'){
		var vals = val.split('|');
		if(vals[1] == 'active')
       		var html = '<span class="label label-info">Active</span>';
       	else
       		var html = '<span class="label label-warning">'+ucfirst(vals[1])+'</span>';

		$('[data-realtime="'+fieldType+'"]').each(function(){
			var $elem = $(this),
				elemType = $elem.prop('nodeName');

			if(elemType == 'A' && $elem.hasClass('editFieldModal'))
				$elem.data('value', vals[0]);
			else
				$elem.html(html);
		})
	}
	else if(fieldType == 'parqStatus'){
		if(val == 0)
			var html = '<span class="label label-info">Completed</span>';
		else if(val == 1)
			var html = '<span class="label label-warning">'+val+' Step Left</span>';
		else
			var html = '<span class="label label-warning">'+val+' Steps Left</span>';

		$('[data-realtime="'+fieldType+'"]').each(function(){
			var $elem = $(this),
				elemType = $elem.prop('nodeName');

			if(elemType == 'TD')
				$elem.html(html);
		})
	}
	else if(fieldType == 'referralNetwork'){
		var vals = val.split('|');
		if(vals[0] == 'Client')
			var html = '<a href="'+$('meta[name="public_url"]').attr('content')+'client/'+vals[1]+'">'+vals[2]+'</a> (Client)'
		else if(vals[0] == 'Staff')
			//var html = '<a href="'+$('meta[name="public_url"]').attr('content')+'staff/'+vals[1]+'">'+vals[2]+'</a> (Staff)'
			var html = '<a href="'+$('meta[name="public_url"]').attr('content')+'settings/business/staff/'+vals[1]+'">'+vals[2]+'</a> (Staff)'
		else if(vals[0] == 'Professional network')
			//var html = '<a href="'+$('meta[name="public_url"]').attr('content')+'contact/'+vals[1]+'">'+vals[2]+'</a> (Professional network)'
			var html = '<a href="'+$('meta[name="public_url"]').attr('content')+'settings/business/contacts/'+vals[1]+'">'+vals[2]+'</a> (Professional network)'

		$('[data-realtime="'+fieldType+'"]').each(function(){
			var $elem = $(this),
				elemType = $elem.prop('nodeName');

			if(elemType == 'TD')
				$elem.html(html);
		})
	}
	else if(fieldType == 'gender'){
		var ajaxSent = false;

		$('[data-realtime="'+fieldType+'"]').each(function(){
			var $elem = $(this),
				elemType = $elem.prop('nodeName');

			if(elemType == 'INPUT' && $elem.val() == val)
				$elem.prop('checked', true);
			else if(elemType == 'A' && $elem.hasClass('editFieldModal'))
				$elem.data('value', val)
			else if(elemType == 'IMG' && $elem.attr('src').lastIndexOf("public/profiles/") >= 0){
				if(!ajaxSent){
					$.ajax({
						url: $('meta[name="public_url"]').attr('content')+'noimage-src',
						method: "GET",
						data: {
							gender: val
						},
						success: function(data){
							updatePicSrc(data);
						}
					});
					ajaxSent = true;
				}
			}
			else
				$elem.text(val)
		})
	}
	else if(fieldType == 'goals'){
		var valArr = val.split(',');

		$('[data-realtime="'+fieldType+'"]').each(function(){
			var $elem = $(this),
				elemType = $elem.prop('nodeName');

			if(elemType == 'A' && $elem.hasClass('editFieldModal'))
				$elem.data('value', valArr)
			else if(elemType == 'SELECT'){
				selValmultiselect(valArr, $elem, true);
				$elem.selectpicker('refresh');
			}
			else
				$elem.html(val);
		})
	}
	else if(fieldType == 'dob'){
		var overviewDob = $.datepicker.formatDate('dd M, yy', new Date(val)),
			valArr = val.split('-');

		$('[data-realtime="'+fieldType+'"]').each(function(){
			var $elem = $(this),
				elemType = $elem.prop('nodeName');

			if(elemType == 'A' && $elem.hasClass('editFieldModal'))
				$elem.data('value', val)
			else if(elemType == 'SELECT'){
				if($elem.attr('name') == 'dd')
					$elem.find('option[value="'+valArr[2]+'"]').attr('selected', 'true');
				
				else if($elem.attr('name') == 'mm')
					$elem.find('option[value="'+valArr[1]+'"]').attr('selected', 'true');
				
				else if($elem.attr('name') == 'yyyy')
					$elem.find('option[value="'+valArr[0]+'"]').attr('selected', 'true');
				
				$elem.selectpicker('refresh');
			}
			else
				$elem.html(overviewDob);
		})
	}
}

function selValmultiselect(valArr, dd, resetDd){
	if(resetDd != null && resetDd)
		dd.find('option').removeAttr("selected");

	if(valArr.length){
		$.each(valArr, function(key, value){
			dd.find('option[value="'+value+'"]').attr('selected', 'true')
		});
	}
}

function ucfirst(string){
    return string.charAt(0).toUpperCase() + string.slice(1);
}

function updatePicSrc(src){
	$('img[data-realtime="gender"]').each(function(){
		$(this).attr('src', src)
	})
}