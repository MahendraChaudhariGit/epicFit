    CKEDITOR.replace('mealDesc', {
        height: 120
    });
    CKEDITOR.replace('ingredients', {
        height: 120
    });
    CKEDITOR.replace('methods', {
        height: 120
    });
    CKEDITOR.replace('tips', {
        height: 120
    });

    var timer = setInterval(updateTextArea, 1000);
    function updateTextArea(){
        $('#mealDesc').html(CKEDITOR.instances.mealDesc.getData());
        $('#ingredients').html(CKEDITOR.instances.ingredients.getData());
        $('#methods').html(CKEDITOR.instances.methods.getData());
        $('#tips').html(CKEDITOR.instances.tips.getData());
    }



    var productNames = new Array();
    var productIds = new Object();
    $.getJSON( public_url+'meal-planner/getFood', null,
        function ( jsonData )
        {
            $.each( jsonData, function ( index, product )
            {
                productNames.push( product.short_desc );
                productIds[product.short_desc] = product.food_id;
            } );
            $( '#food' ).typeahead( { 
                source:productNames,
                afterSelect:function(selection){                  
                    $('input[name="food_id"]').val(productIds[$( '#food' ).val()]);
                        getServingSize();
                    }
             } );
        } );

        function getServingSize(){
            var productName = new Array();
            var productIds = new Object();
            $.getJSON( public_url+'meal-planner/getServings', { foodId: $('input[name="food_id"]').val() },
                function ( jsonData )
                {
                    $.each( jsonData, function ( index, product )
                    {
                        productName.push( product.seq+' '+product.serving_desc );
                        productIds[product.serving_desc] = product.servingsize_id;
                    } );
                   // console.log(productName);
                    $( '#servingSize' ).typeahead( { 
                        source:productName,
                        afterSelect:function(selection){   
                        //alert(productIds[$( '#servingSize' ).val()]);               
                            $('input[name="servingSize_id"]').val(productIds[$( '#servingSize' ).val()]);
                            }
                     } );
                } );
        }
    


   jQuery("#add_meal").click(function() {
      // var selected_type = jQuery('input[name=type]:checked').val();
       var form = $('#form'),
            isFormValid = form.valid();
       
        if(isFormValid){
            var formdata = $('#form').serialize();
            jQuery.ajax({
                url: public_url+'meal-planner/meals/create',
                type: 'post',
                data: formdata,
                success: function (res) {
                    setTimeout(function() {
                    swal({
                        title: "Meal is successfully created",
                        text: "",
                        type: "success"
                    }, function() {
                        window.location = public_url+'meal-planner/meals';
                    });
                }, 1000);
                }
            });
        }
    });

     jQuery("#update_meal").click(function() {
       var form = $('#form'),
            isFormValid = form.valid();
       
        if(isFormValid){
            var formdata = $('#form').serialize();
            formdata['mealDesc'] = CKEDITOR.instances.mealDesc.getData();
            formdata['mealDesc'] = CKEDITOR.instances.mealDesc.getData();
            jQuery.ajax({
                url: public_url+'meal-planner/meals/updatemeal',
                type: 'post',
                data: formdata,
                success: function (res) {
                    setTimeout(function() {
                    swal({
                        title: "Meal is successfully updated",
                        text: "",
                        type: "success"
                    }, function() {
                        window.location = public_url+'meal-planner/meals';
                    });
                }, 1000);

                }
            });
        }
    });

    $('.delete-meal').click(function() {
        var processbarDiv = $(this).closest('tr');
        var mealId = processbarDiv.find('#meal-id').val();
        var entity = $(this).data('entity');
        var eventUrl ='meal-planner/meals/deletemeal';
        var actionType='popup';
        if(mealConfirmDelete(mealId,entity,eventUrl,actionType))
        {
           deleteGoal(mealId); 
           processbarDiv.remove();
        }
       
    });

    $('.delete-food').click(function() {
        var processbarDiv = $(this).closest('tr');
        var mealId = processbarDiv.find('#food-id').val();
        var entity = $(this).data('entity');
        var eventUrl ='meal-planner/food/deletefood';
        var actionType='popup';
        if(mealConfirmDelete(mealId,entity,eventUrl,actionType))
        {
           deleteGoal(mealId); 
           processbarDiv.remove();
        }
       
    });

    

    jQuery("#add_food").click(function() {
      // var selected_type = jQuery('input[name=type]:checked').val();
       var form = $('#form'),
            isFormValid = form.valid();
       
        if(isFormValid){
            var formdata = $('#form').serialize();
            jQuery.ajax({
                url: public_url+'meal-planner/food/create',
                type: 'post',
                data: formdata,
                success: function (res) {
                    setTimeout(function() {
                    swal({
                        title: "Food is successfully created",
                        text: "",
                        type: "success"
                    }, function() {
                        window.location = public_url+'meal-planner/food';
                    });
                }, 1000);
                }
            });
        }
    });

    jQuery("#update_food").click(function() {
       var form = $('#form'),
            isFormValid = form.valid();
       
        if(isFormValid){
            var formdata = $('#form').serialize();
            jQuery.ajax({
                url: public_url+'meal-planner/food/updatefood',
                type: 'post',
                data: formdata,
                success: function (res) {
                    setTimeout(function() {
                    swal({
                        title: "Food is successfully updated",
                        text: "",
                        type: "success"
                    }, function() {
                        window.location = public_url+'meal-planner/food';
                    });
                }, 1000);

                }
            });
        }
    });

    jQuery("#add_meal_category").click(function() {
      // var selected_type = jQuery('input[name=type]:checked').val();
       var form = $('#form'),
            isFormValid = form.valid();
       
        if(isFormValid){
            var formdata = $('#form').serialize();
            jQuery.ajax({
                url: public_url+'meal-planner/meal-categories/create',
                type: 'post',
                data: formdata,
                success: function (res) {
                    setTimeout(function() {
                    swal({
                        title: "Meal Category is successfully created",
                        text: "",
                        type: "success"
                    }, function() {
                        window.location = public_url+'meal-planner/meal-categories';
                    });
                }, 1000);
                }
            });
        }
    });

    jQuery("#update_mealcat").click(function() {
       var form = $('#form'),
            isFormValid = form.valid();
       
        if(isFormValid){
            var formdata = $('#form').serialize();
            jQuery.ajax({
                url: public_url+'meal-planner/meal-categories/updateMealCategory',
                type: 'post',
                data: formdata,
                success: function (res) {
                    setTimeout(function() {
                    swal({
                        title: "Meal Category is successfully updated",
                        text: "",
                        type: "success"
                    }, function() {
                        window.location = public_url+'meal-planner/meal-categories';
                    });
                }, 1000);

                }
            });
        }
    });

    $('.delete-mealcat').click(function() {
        var processbarDiv = $(this).closest('tr');
        var mealId = processbarDiv.find('#cat_id').val();
        var entity = $(this).data('entity');
        var eventUrl ='meal-planner/meal-categories/deleteMealCategory';
        var actionType='popup';
        if(mealConfirmDelete(mealId,entity,eventUrl,actionType))
        {
           deleteGoal(mealId); 
           processbarDiv.remove();
        }
       
    });


    jQuery("#add_servings").click(function() {
      // var selected_type = jQuery('input[name=type]:checked').val();
       var form = $('#form'),
            isFormValid = form.valid();
       
        if(isFormValid){
            var formdata = $('#form').serialize();
            jQuery.ajax({
                url: public_url+'meal-planner/serving-size/create',
                type: 'post',
                data: formdata,
                success: function (res) {
                    setTimeout(function() {
                    swal({
                        title: "Serving Size is successfully created",
                        text: "",
                        type: "success"
                    }, function() {
                        window.location = public_url+'meal-planner/serving-size';
                    });
                }, 1000);
                }
            });
        }
    });


    jQuery("#update_servings").click(function() {
       var form = $('#form'),
            isFormValid = form.valid();
       
        if(isFormValid){
            var formdata = $('#form').serialize();
            jQuery.ajax({
                url: public_url+'meal-planner/serving-size/updateServings',
                type: 'post',
                data: formdata,
                success: function (res) {
                    setTimeout(function() {
                    swal({
                        title: "Serving Size is successfully updated",
                        text: "",
                        type: "success"
                    }, function() {
                        window.location = public_url+'meal-planner/serving-size';
                    });
                }, 1000);

                }
            });
        }
    });

    $('.delete-servings').click(function() {
        var processbarDiv = $(this).closest('tr');
        var mealId = processbarDiv.find('#servings_id').val();
        var entity = $(this).data('entity');
        var eventUrl ='meal-planner/serving-size/deleteServings';
        var actionType='popup';
        if(mealConfirmDelete(mealId,entity,eventUrl,actionType))
        {
           deleteGoal(mealId); 
           processbarDiv.remove();
        }
       
    });


    jQuery("#add_shop_cat").click(function() {
      // var selected_type = jQuery('input[name=type]:checked').val();
       var form = $('#form'),
            isFormValid = form.valid();
       
        if(isFormValid){
            var formdata = $('#form').serialize();
            jQuery.ajax({
                url: public_url+'meal-planner/shopping-category/create',
                type: 'post',
                data: formdata,
                success: function (res) {
                    setTimeout(function() {
                    swal({
                        title: "Shopping Category is successfully created",
                        text: "",
                        type: "success"
                    }, function() {
                        window.location = public_url+'meal-planner/shopping-category';
                    });
                }, 1000);
                }
            });
        }
    });


    jQuery("#update_shopcat").click(function() {
       var form = $('#form'),
            isFormValid = form.valid();
       
        if(isFormValid){
            var formdata = $('#form').serialize();
            jQuery.ajax({
                url: public_url+'meal-planner/shopping-category/updateShoppingCat',
                type: 'post',
                data: formdata,
                success: function (res) {
                    setTimeout(function() {
                    swal({
                        title: "Shopping Size is successfully updated",
                        text: "",
                        type: "success"
                    }, function() {
                        window.location = public_url+'meal-planner/shopping-category';
                    });
                }, 1000);

                }
            });
        }
    });

    $('.delete-shoppingcat').click(function() {
        var processbarDiv = $(this).closest('tr');
        var catId = processbarDiv.find('#category_id').val();
        var entity = $(this).data('entity');
        var eventUrl ='meal-planner/shopping-category/deleteShoppingCat';
        var actionType='popup';
        if(mealConfirmDelete(catId,entity,eventUrl,actionType))
        {
           deleteGoal(catId); 
           processbarDiv.remove();
        }
       
    });


    function addRow(tableID) {

            var table = document.getElementById(tableID);

            var rowCount = table.rows.length;
            var row = table.insertRow(rowCount);

            var colCount = table.rows[0].cells.length;

            for(var i=0; i<colCount; i++) {

                var newcell = row.insertCell(i);

                newcell.innerHTML = table.rows[1].cells[i].innerHTML;
                //alert(newcell.childNodes);
                switch(newcell.childNodes[0].type) {
                    case "text":
                            newcell.childNodes[0].value = "";
                            break;
                    case "checkbox":
                            newcell.childNodes[0].checked = false;
                            break;
                    case "select-one":
                            newcell.childNodes[0].selectedIndex = 0;
                            break;
                }
            }


        }

        function deleteRow(tableID) {
            try {
            var table = document.getElementById(tableID);
            var rowCount = table.rows.length;

            for(var i=0; i<rowCount; i++) {
                var row = table.rows[i];
                var chkbox = row.cells[0].childNodes[0];
                if(null != chkbox && true == chkbox.checked) {
                    table.deleteRow(i);
                    rowCount--;
                    i--;
                }


            }
            }catch(e) {
                alert(e);
            }
        }



    /*jQuery(".edit-meallogs").click(function() {
      // var selected_type = jQuery('input[name=type]:checked').val();
       var form = $('#form'),
            isFormValid = form.valid();
       
        if(isFormValid){
            var formdata = $('#form').serialize();
            jQuery.ajax({
                url: public_url+'meal-planner/shopping-category/create',
                type: 'post',
                data: formdata,
                success: function (res) {
                    setTimeout(function() {
                    swal({
                        title: "Shopping Category is successfully created",
                        text: "",
                        type: "success"
                    }, function() {
                        window.location = public_url+'meal-planner/shopping-category';
                    });
                }, 1000);
                }
            });
        }
    });*/

    jQuery("#add_shop_item").click(function() {
      // var selected_type = jQuery('input[name=type]:checked').val();
       var form = $('#form'),
            isFormValid = form.valid();
       
        if(isFormValid){
            var formdata = $('#form').serialize();
            jQuery.ajax({
                url: public_url+'meal-planner/shopping-list/create',
                type: 'post',
                data: formdata,
                success: function (res) {
                    setTimeout(function() {
                    swal({
                        title: "Shopping Item is successfully created",
                        text: "",
                        type: "success"
                    }, function() {
                        window.location = public_url+'meal-planner/shopping-list';
                    });
                }, 1000);
                }
            });
        }
    });


    jQuery("#update_shopitem").click(function() {
       var form = $('#form'),
            isFormValid = form.valid();
       
        if(isFormValid){
            var formdata = $('#form').serialize();
            jQuery.ajax({
                url: public_url+'meal-planner/shopping-items/updateShoppingItem',
                type: 'post',
                data: formdata,
                success: function (res) {
                    setTimeout(function() {
                    swal({
                        title: "Shopping Item is successfully updated",
                        text: "",
                        type: "success"
                    }, function() {
                        window.location = public_url+'meal-planner/shopping-items';
                    });
                }, 1000);

                }
            });
        }
    });

    $('.delete-shoppingitems').click(function() {
        var processbarDiv = $(this).closest('tr');
        var catId = processbarDiv.find('#category_id').val();
        var entity = $(this).data('entity');
        var eventUrl ='meal-planner/shopping-items/deleteShoppingItem';
        var actionType='popup';
        if(mealConfirmDelete(catId,entity,eventUrl,actionType))
        {
           deleteGoal(catId); 
           processbarDiv.remove();
        }
       
    });


    function mealConfirmDelete(eventId,entity,eventUrl,actionType){
    var entity = entity;
    swal({
        title: "Are you sure to delete this "+entity+"?",
        text: (typeof warningText != 'undefined' && warningText)?warningText:'',
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d43f3a",
        confirmButtonText: "Yes, delete it!",
        allowOutsideClick: true,
        customClass: 'delete-alert'
    }, 
    function(){
        $(document).on( 'click', '.confirm', function(e) {
         $.ajax({
            url: public_url+eventUrl,
            type: 'POST',
            dataType:'json',
            data: { 'eventId':eventId},
            success: function(response) {
                if(response.status == 'true'){
                    $('.delete-'+entity+'-'+eventId).remove();
                        console.log(actionType);

                    if(actionType == 'calender')
                     $('.delete-'+entity+'-'+eventId).remove();
                    else if(actionType == 'popup')
                     location.reload();
                    
                      
              }  
            }
         });
        });
    });
}   


  
  function mpfileSelectHandler(elem) {
    // get selected file
    var oFile = elem.files[0];

    var ifCroppedImgSaved = false,
        public_url = $('meta[name="public_url"]').attr('content');

    var formGroup = $(elem).closest('.form-group')
    //var mainPhotoName = formGroup.find('input[name="mainPhotoName"]');
    //var PhotoName = formGroup.find('input[class="prePhotoName"]');
    var entityIdVal = formGroup.find('input[name="entityId"]').val();
    var photoHelperVal = formGroup.find('input[name="photoHelper"]').val();
    var previewPics = $('.'+photoHelperVal+'PreviewPics');
    var prePhotoName = $('.'+photoHelperVal+'PhotoName');

    //var previewPics1 = $('.'+photoHelperVal+'PreviewPics1');
    var cropSelector = formGroup.find('input[name="cropSelector"]').val();
    if(cropSelector)
        cropSelector = cropSelector.split(',');
    //console.log(cropSelector)
    //console.log(cropSelector.length)

    var picCropModel = $('.picCropModel');
    $oImage = picCropModel.find('img.preview');
    var photoName = picCropModel.find('input[name="photoName"]');
    var ui_w = picCropModel.find('input[name="ui-w"]');
    var ui_h = picCropModel.find('input[name="ui-h"]');
    var ui_x1 = picCropModel.find('input[name="ui-x1"]');
    var ui_y1 = picCropModel.find('input[name="ui-y1"]');
    var widthScale = picCropModel.find('input[name="widthScale"]');
    var heightScale = picCropModel.find('input[name="heightScale"]');
    photoName.val('');
    ui_w.val('');
    ui_h.val('');
    ui_x1.val('');
    ui_y1.val('');
    widthScale.val('');
    heightScale.val('');
    $oImage.src = '';
    
    var form_data = new FormData();                  
    form_data.append('fileToUpload', oFile);
    //photoName.val('/uploads/thumb_'+photoName);
   // picCropModel.modal('show');

    $.ajax({
        url: public_url+'photo/save',
        dataType: 'text',  
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,                         
        type: 'post',
        success: function(response){
            photoName.val(response);
            picCropModel.modal('show');
        }
     });
    
    picCropModel.find('button.save').unbind("click").click(function(){
        if(ui_w.val() != ''){
            //picCropModel.modal('hide');
            var form_data = new FormData();                  
            form_data.append('photoName', photoName.val());
            form_data.append('widthScale', widthScale.val());
            form_data.append('x1', ui_x1.val());
            form_data.append('w', ui_w.val());
            form_data.append('heightScale', heightScale.val());
            form_data.append('y1', ui_y1.val());
            form_data.append('h', ui_h.val());
            form_data.append('prePhotoName', prePhotoName.val());
            $.ajax({
                url: public_url+'photo/save',
                dataType: 'text',  
                cache: false,
                contentType: false,
                processData: false,
                data: form_data,                         
                type: 'post',
                success: function(response){
                    ifCroppedImgSaved = true;
                    picCropModel.modal('hide');

                    if(entityIdVal != null && entityIdVal != ''){
                        formData = {};
                        formData['id'] = entityIdVal;
                        formData['photoName'] = response;
                        $.ajax({
                            url: public_url+formGroup.find('input[name="saveUrl"]').val(),
                            data: formData,                         
                            method: 'POST'
                        });
                    }
                    else 
                        formGroup.find('input[name="'+photoHelperVal+'"]').val(response);
                    
                    

                    previewPics.prop('src', public_url+'uploads/thumb_'+response);
                    if(previewPics.hasClass('hidden'))
                        previewPics.removeClass('hidden');
console.log(JSON.stringify(prePhotoName));
                    prePhotoName.val(response);

                    setUploadFieldValid(formGroup);
                }
            });
            destroyJcrop();
        }
    });

    picCropModel.off('hide.bs.modal');
    picCropModel.on('hide.bs.modal', function(){
        if(!ifCroppedImgSaved){
            var formData = {};

            formData['photoName'] = photoName.val();
            $.post(public_url+'photos/delete', formData);   
        }
        destroyJcrop();
    });

    picCropModel.off('show.bs.modal');
    picCropModel.on('show.bs.modal', function(){
        toggleRatio.show();
        if(cropSelector.length == 1)
            toggleRatio.hide();
        else if(cropSelector.length > 1){
            toggleRatio.each(function(){
                var $this = $(this);
                if(jQuery.inArray($this.data('crop-selector'), cropSelector) < 0)
                    $this.hide();
            })
        }

        var oImage = $oImage[0];
        // prepare HTML5 FileReader
        var oReader = new FileReader();
            oReader.onload = function(e) {

            // e.target.result contains the DataURL which we can use as a source of the image
            oImage.src = e.target.result;
            //picCropModel.modal('show');
            oImage.onload = function () { // onload event handler
                var oImageNW = oImage.naturalWidth;
                var oImageNH = oImage.naturalHeight;
                // destroy Jcrop if it is existed
                    
                setTimeout(function(){
                    // initialize Jcrop
                    var oImageW = $oImage.width();
                    var oImageH = $oImage.height();
                    if(oImageNW > oImageW)
                        widthScale.val(oImageNW/oImageW);
                    if(oImageNH > oImageH)
                        heightScale.val(oImageNH/oImageH);
                
                    /*if (jcrop_api) {
                        jcrop_api.destroy();
                        jcrop_api = null;
                        $oImage.width(oImageNW);
                        $oImage.height(oImageNH);
                    }*/

                    $oImage.Jcrop({
                        minSize: [120, 120], // min crop size
                        //aspectRatio : 1, // keep aspect ratio 1:1
                        bgFade: true, // use fade effect
                        bgOpacity: .3, // fade opacity
                        onChange: updateInfo,
                        onSelect: updateInfo,
                        setSelect:   [ 0, 0, 120, 120 ],
                        onRelease: clearInfo,
                        allowSelect: false
                    }, function(){

                        // use the Jcrop API to get the real image size
                        var bounds = this.getBounds();
                        boundx = bounds[0];
                        boundy = bounds[1];

                        // Store the Jcrop API in the jcrop_api variable
                        jcrop_api = this;
                    });
                    if(cropSelector.length)
                        toggleRatio.filter("[data-crop-selector='"+cropSelector[0]+"']").trigger('click');
                    else
                        toggleRatio.first().trigger('click');
                } ,300);
            };
        };

        // read selected file as DataURL
        oReader.readAsDataURL(oFile);
    });
};


