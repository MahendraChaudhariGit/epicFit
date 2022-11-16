

function clearFormBusiness(form){
	$(".remove-hidden", form).remove();
	$('.upload-group img', form).removeAttr('src');
	$(form).find('.form-group.has-success').each(function(index, element){
		$(element).removeClass('has-success');
	});
}

<!-- start: Client Form Reference -->
/*$(document).ready(function(){
	$.get(public_url+'clients/all', function(data){
		$('#form-6').find('#clientList').typeahead({
            source:data,
            items:'all',
            afterSelect:function(selection){
                $('input[name="clientId"]').val(selection.id);
            }
        });
    },'json');
    $.get(public_url+'staffs/all', function(data){
    	$('#form-6').find('#staffList').typeahead({
            source:data,
            items:'all',
            afterSelect:function(selection){
                $('input[name="staffId"]').val(selection.id);
            }
        });
    },'json');
    $.get(public_url+'contacts/all', function(data){
    	$('#form-6').find('#proList').typeahead({
    		highlighter: function(item){
    			var data = item.split('|');
    			return data[0]+'<br><span>'+data[1];
    		},
            source:data,
            items:'all',
            afterSelect:function(selection){
                $('input[name="proId"]').val(selection.id);
            }
        });
    },'json');
    $('#form-6').find('input[name="referralNetwork"]').change(function(){
        toggleReference($(this).val());
    });
    toggleReference();
});
function toggleReference(val){
    var form6 = $('#form-6');
    var clientList = form6.find('#clientList');
    var staffList = form6.find('#staffList');
    var proList = form6.find('#proList');
    if(!val)
        val = form6.find('input[name="referralNetwork"]:checked').val()

    if(val == 'Client'){
        clientList.removeClass('hidden');
        staffList.addClass('hidden');
        proList.addClass('hidden');
    }
    else if(val == 'Staff'){
        staffList.removeClass('hidden');
        proList.addClass('hidden');
        clientList.addClass('hidden');
    }
    else if(val == 'Professional network'){
        proList.removeClass('hidden');
        clientList.addClass('hidden');
        staffList.addClass('hidden');
    }
    else{
        clientList.addClass('hidden');
        proList.addClass('hidden');
        staffList.addClass('hidden');
    }
}*/
<!-- end: Client Form Reference -->





