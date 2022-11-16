    $(document).ready(function(){
		var public_url = $('meta[name="public_url"]').attr('content');
		$('.permission-section').hide();
		$('.errormsg').hide();
		$('.show-permission').click(function(e){
			var pclass = $(this).siblings('.permission-section');
			$('.permission-section').hide();
			$('.permission-section').not(pclass).data('display-status','');
			e.preventDefault();
			var displayVal = pclass.data('display-status');
			if((pclass.children('.assign-group').find('.permGroup').length)>0){
				pclass.children('.assign-group').show();
			} else {
				pclass.children('.assign-group').hide();
			}
			if((pclass.children('.unAssign-group').find('.permGroup').length)>0){
				pclass.children('.unAssign-group').show();
			} else {
				pclass.children('.unAssign-group').hide();
			}
			$(pclass).show();
			if(displayVal == ''){
				$(pclass).show();
				pclass.data('display-status','display');
			} else {
				$(pclass).hide();
				pclass.data('display-status','');
			}
		});
		/*$('body').unbind('change').on('change','.sorting_1 .unassined-perm',function(){
			var requestUrl;
			var checked = $(this).is(":checked");
			var allPermissionIdArr=[];
	        var PermissionArr=[];
			var userId = $(this).closest('.sorting_1').children('a').data('type-id');
			var  thisValue = $(this);
   	      var currentId=$(this).prop('id');
    	var PermissionArr=managePermissionId(currentId);
    	allPermissionIdArr.push(PermissionArr['allPerm']);
   

        //managePermissionAction(checked ,allPermissionIdArr ,userId);
			
		});*/
   
	$('#save-group').click(function(e){
		var groupName = $('#group-name').val();
		if(groupName == ''){
			$('.errormsg').show();
		} else {
			$.ajax({
				url: public_url+'add-group-type',
				method: "POST",
				data: {'group_name':groupName},
				success: function(data){
					var data = JSON.parse(data);
					if(data.status == "ok"){
						$('.errormsg').hide();
						$('#addGroupModal').modal('hide');
						location.reload();
						showNotific(data.msg)
					}  else {
						$('.errormsg').show();
						$('.errormsg').text(data.msg);  
				  }
				}
	    	});
		}
	});
	$('.delete-type').click(function(){
      var typeid = $(this).data('type-id');
	  var o= $(this);
      o.attr('disabled', true);
      swal({
              title: "Are you sure to delete this group type?",
              type: "warning",
              showCancelButton: true,
              confirmButtonColor: "#d43f3a",
              confirmButtonText: "Yes",
              closeOnConfirm: true,
              allowOutsideClick: false
      }, function(isConfirm){
         if(isConfirm){
			$.ajax({
			  url: public_url+'delete-group-type',
			  method: "POST",
			  data: {'type_id': typeid},
			  success: function(data){
					var data = JSON.parse(data);
					if(data.status == "ok"){
					  o.attr('disabled', false);
					  location.reload();
					}
	
				  }  
			  });
          }
          o.attr('disabled', false);
      });
     });
  });
  function showNotific(content){
	var group = $('#group-listing');
	group.children('.alert').remove();
	setTimeout(function(){ 
		group.prepend(content);
	}, 400);
}

    
 function managePermissionAction(checked ,allPermId ,userId){
 	var public_url = $('meta[name="public_url"]').attr('content');
    var requestUrl;
 	if(checked == true) {
				requestUrl = public_url+'manage-permission/create' ;
			} else {
				requestUrl = public_url+'manage-permission/delete' ;
			}
			$.ajax({
				url: requestUrl,
				method: "POST",
				data: {'user_type_id':userId,'perm_id':allPermId},
				success: function(data){
					var data = JSON.parse(data);
					if(data.status == "ok"){
						var html = data.html;
					
					}
				}
			});
 }

  function managePermissionId(allPermId){
  	//var allPermIdArr=[];

  	   if(allPermId){
    	 	var permId = allPermId.split('_');
    	 }
       return {'allPerm' :permId[1] ,'userId':permId[0]};
  	 }

//select all checkboxes 
$( document ).on( 'change', '.panel-heading input[type="checkbox"]', function(e) {
    var checked = $(this).is(":checked");
    var userId = $(this).closest('.sorting_1').children('a').data('type-id');
    var allPermissionIdArr=[];
	var PermissionArr=[];
  if(checked){
  	var checkBoxes= $(this).closest('.panel').find('.perm-boby input[type="checkbox"]').not(':checked');
  	 checkBoxes.prop('checked', true);


  }else{
  	var checkBoxes = $(this).closest('.panel').find('.perm-boby input[type="checkbox"]:checked');

  	 checkBoxes.prop('checked', false);

  	   

  }

  checkBoxes.each(function() {
    	var currentId=$(this).prop('id');
    	var PermissionArr=managePermissionId(currentId);
    	allPermissionIdArr.push(PermissionArr['allPerm']);
    	
       });
 if(allPermissionIdArr.length)

 managePermissionAction(checked ,allPermissionIdArr ,userId);




	/*var panelChkElement=$(this).closest('.panel').find('.perm-boby input[type="checkbox"]');
	
	var allPermissionIdArr=[];
	var PermissionArr=[];
	panelChkElement.prop('checked', checked);  //$(this).prop("checked")

    panelChkElement.each(function() {
    	var currentId=$(this).prop('id');
    	var PermissionArr=managePermissionId(currentId);
    	allPermissionIdArr.push(PermissionArr['allPerm']);
    	
       });

	var userId = $(this).closest('.sorting_1').children('a').data('type-id');
	managePermissionAction(checked ,allPermissionIdArr ,userId);*/

});

$( document ).on( 'change', '.perm-boby input[type="checkbox"]', function(e) {

   var permCls = $(this).prop('class');
   var permissionCls = permCls.split(' ');
   var checked = $(this).is(":checked");
   var totalChkCls = $(this).closest('.perm-boby').data('total-chkbox');
   var userId = $(this).closest('.sorting_1').children('a').data('type-id');
   var currentId=$(this).prop('id');
   var allPermissionIdArr=[];
   var PermissionArr=[];
//console.log(totalChkCls);
	 if(!checked){ 
        $(this).closest('.panel').find('.panel-heading input[type="checkbox"]').prop('checked', false);
        $(this).closest('.permission-section').find('[data-permission-class="'+permissionCls[1]+'"]').prop('checked', false);

    }else{

    var totalChkLength=$(this).closest('.perm-boby').find('input[type="checkbox"]:checked').length;

    if (totalChkLength == totalChkCls){
        $(this).closest('.panel').find('.panel-heading input[type="checkbox"]').prop('checked', true);
    }
    
    var totalChkActionLength=$(this).closest('.permission-section').find('input.'+permissionCls[1]+':checked').length;
    var totalChkboxActionLength=$(this).closest('.permission-section').find('input.'+permissionCls[1]).length;


   if (totalChkActionLength == totalChkboxActionLength)
    $(this).closest('.permission-section').find('[data-permission-class="'+permissionCls[1]+'"]').prop('checked', true);

 
}

        var PermissionArr=managePermissionId(currentId);
    	allPermissionIdArr.push(PermissionArr['allPerm']);
        managePermissionAction(checked ,allPermissionIdArr ,userId);
    
    

});	
$( document ).on( 'change', '.all_Checkbox', function() {
	var premThis=$(this);
    var permissionCls = premThis.data('permission-class');
    var checked = premThis.is(":checked");
    var userId = $(this).closest('.sorting_1').children('a').data('type-id');
    var allPermissionIdArr=[];
	var PermissionArr=[];
	
    if(checked){
  	var checkBoxes= premThis.closest('.permission-section').find('input[type="checkbox"].'+permissionCls).not(':checked');
  	 checkBoxes.prop('checked', true);


  }else{
  	var checkBoxes = premThis.closest('.permission-section').find('input[type="checkbox"].'+permissionCls+':checked');

  	 checkBoxes.prop('checked', false);

  	   

  }

  checkBoxes.each(function() {
  	if(checked){
  	var totalChkCls = $(this).closest('.panel').find('.perm-boby').data('total-chkbox');
    var totalChkLength=$(this).closest('.perm-boby').find('input[type="checkbox"]:checked').length;

    if (totalChkLength == totalChkCls){
        $(this).closest('.panel').find('.panel-heading input[type="checkbox"]').prop('checked', true);
    }
  		
  	}else{
  		$(this).closest('.panel').find('.panel-heading input[type="checkbox"]').prop('checked', false);
  	}
    	var currentId=$(this).prop('id');
    	var PermissionArr=managePermissionId(currentId);
    	allPermissionIdArr.push(PermissionArr['allPerm']);
    	
       });
 if(allPermissionIdArr.length)

 managePermissionAction(checked ,allPermissionIdArr ,userId);
	
});	


