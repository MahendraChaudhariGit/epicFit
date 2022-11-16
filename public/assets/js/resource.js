
var rowCreated = 0;
//var serviceCount=0;
//var classCount=0;

$(document).ready(function(){
	   initCustomValidator();
//clone one textbox to another text box.
    $("input[name=resName]").keyup(function(){
              update_text();
      });

    $('body').on('change', '.clone-textbox', function(e){
        e.preventDefault();
        $(this).data('clone','1');
      });
//clone and add the resource row..
$("#add-resource").click(function(e){   
      e.preventDefault();
      rowCreated++;
      //resItemLoc++;

      if($('input.clone-textbox:last').val()!="" && $('.resItemLocDd select:last').val()!=""){
      var self=$(".resource-clone-row:eq(0)");
      var resourceRow=self.clone();
      resourceRow.find('input[type="text"]').val("").attr('name','newResItem'+rowCreated);
      
      var ddGroup = resourceRow.find('.resItemLocDd');
      var dd = ddGroup.find('select.resItemLoc-select');
      ddGroup.empty();
      ddGroup.append(dd);
      ddGroup.find('select.resItemLoc-select').val("").attr('name','newResLoc'+rowCreated).selectpicker('refresh');
      resourceRow.find('input[type="hidden"]').remove();
      resourceRow.find('.col-md-2').remove();
      resourceRow.append(" <div class='col-md-2 col-xs-2'><a class='btn btn-xs btn-red remove-resource-row' href='#''><i class='fa fa-times fa fa-white'></i></a></div> ");
      //resourceRow.find('select').remove();
      //resourceRow.find('select').selectpicker('refresh');
          self.closest('fieldset').append(resourceRow);
          update_text();
       }//empty condition check end
    });
/* Start: Service Row Clone */
/*  $('.add-service-row').click(function(e){
      e.preventDefault();
      serviceCount++;
      //var cloneRow=$('.service-clone-class');
      //rowClone(cloneRow);
     rowClone('.service-clone-class',serviceCount);
  });
/* End: Service Row Clone */
/*Strat: Class row clone */
/*   $('.add-class-row').click(function(e){
      e.preventDefault();
      classCount++;
      //var cloneRow=$('.service-clone-class');
      //rowClone(cloneRow);
     rowClone('.class-clone-class',classCount);
    });
/* Strat: Class row clone */
/* Start: Select all loction onchange event of dropdown */
/* var existedService = $('#service-cls-form .service-clone-class:not(.hidden)');
  if(existedService.length){
    $.each(existedService, function(){
      selectChangeEvent($(this));
    });
  }
  var existedClass = $('#service-cls-form .class-clone-class:not(.hidden)');
  if(existedClass.length){
    $.each(existedClass, function(){
      selectChangeEvent($(this));
    });
  }

 $('select.service-cls-option').change(function(e){
     e.preventDefault();
      selectChangeEvent($(this));
      
 });
/* End: Select all loction onchange event of dropdown */
//delete added row......
$('body').on('click', '.remove-resource-row', function(e){
        e.preventDefault();
        $(this).closest('.row').remove();
    });  

//form submit codes for create and edit resource
 $(".submitresource").on( "click", function(e) {
	//alert("fss");
	e.preventDefault();   
	var formData = {};
	var form = $('#resourceForm');
	var isFormValid = form.valid();
    formData['resourceId'] = $('input[name="resourceId"]').val();

	if(isFormValid){
		$.each($(form).find(':input').serializeArray(), function(i, obj){
			formData[obj.name] = obj.value
		});
		if(!formData['resourceId']){
            $.post(public_url+'settings/business/resources', formData, function(response){
            	var data = JSON.parse(response);
                if(data.status == "added"){
                    saveSuccess(form, {action:'show', type:'store'});
                    window.location.href = public_url+"settings/business/resources"; //+data.discountid
                }
            });
        }
        else{
            $.ajax({
                url : public_url+'settings/business/resources/'+formData['resourceId'],
                type : 'PATCH',
                data : formData,
                success : function(response) {
                   var data = JSON.parse(response);
                   if(data.status == "updated"){
                    saveSuccess(form, {action:'show', type:'update'});
                    window.location.href = public_url+"settings/business/resources";
                    }
                },

            });
        }  

        scrollToTop(form);
    }//validate close
    return false
  });
});


//for clone value one textbox to onoter text box
    function update_text()
     {
        var index=$(".clone-textbox").length;
        var item1=$("input[name=resName]");
        var clone_textbox=$(".clone-textbox");
        var c=1;
        if(item1.val()!="")
        {
          for(var i=0;i<index;i++,c++)
          {
            //console.log(clone_textbox.eq(i).data('clone'));
             if(clone_textbox.eq(i).data('clone')==0)
                     {
                      clone_textbox.eq(i).val(item1.val()+" "+c);
                      setFieldNeutral(clone_textbox.eq(i));
                     } 
          }
        }         
       }
 /*      
      function rowClone(cloneCls,rowno){
      var name;
      var itemname;
      if(cloneCls=='.class-clone-class'){
            name='newClass'
            itemname='newClsItem'
      }
      else if(cloneCls=='.service-clone-class'){
            name='newService'
            itemname='newSrvItem'
      }
      var self=$(cloneCls+':eq(0)');
      var cloneRow=self.clone();
      var ddGroup = cloneRow.find('.resItemLocDd');
        var dd = ddGroup.find('select.service-cls-option');
        ddGroup.empty();
        ddGroup.append(dd);
        ddGroup.find('select.service-cls-option').attr('name',name+rowno).selectpicker('refresh');
        var ddGroup = cloneRow.find('.resItemLocDd2');
        var dd = ddGroup.find('select.service-cls-item-op');
        ddGroup.empty();
        dd.prepend("<option value=''>-- Select --</option>");
        ddGroup.append(dd);
        ddGroup.find('select.service-cls-item-op').attr('name',itemname+rowno).selectpicker('refresh');
        cloneRow.find('.col-md-2').remove();
        cloneRow.removeClass('hidden');
        cloneRow.removeClass(cloneCls);
        cloneRow.append(" <div class='col-md-2'><a class='btn btn-xs btn-red remove-resource-row' href='#''><i class='fa fa-times fa fa-white'></i></a></div> ");
        self.closest('fieldset').append(cloneRow);
        $('select.service-cls-option').change(function(e){
          alert('4');
             e.preventDefault();
              selectChangeEvent($(this));
         });
      } 

      function selectChangeEvent(elem){
        var c=$(elem).find("option:selected").data('locid');
          var item=$(elem).closest('.row').find('select.service-cls-item-op');
          item.find('option').remove().selectpicker('refresh');

          //var v=$('#option-value-fieldset select.resItemLoc-select[value="'+c+'"]' );
          var arr = $('#option-value-fieldset select.resItemLoc-select > option').map(function(){
               if(this.value==c)
                      return this.value
          }).get()
         
         //arr.length
         //console.log(item);

         var i;
          item.prepend("<option value=''>-- Select --</option>");
          for(i=1;i<=arr.length;i++){
              item.append("<option value='"+i+"'>"+i+"</option>");
          }

        newelem = elem.find('select.service-cls-option');
        //console.log(newelem);
          if(newelem.is('[data-itemqunt]')){
              item.val(newelem.data('itemqunt'));
              newelem.removeAttr('data-itemqunt');
          }

          item.selectpicker('refresh');        
      }
      */