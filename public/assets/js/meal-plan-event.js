var timeinterval = optionData = clickedBtn = openPopover = null;
var catData = JSON.parse($('input[name="category-data"]').val());
var optionData = "<select class='form-control' name='catName' id='catName'><option value=''> -- Select -- </option>";
if(Object.keys(catData).length > 0){
    $.each(catData, function(val, text){ 
        optionData += "<option value='"+val+"'>"+text+"</option>";
    });
}
optionData +="</select>"; 
var catPopoverOpt = {
    html: true,
    content: "<div class='row popoverContent'><dic class='col-md-12'>"+optionData+"</div></div>",
    container: $('#mealplanmodal'),
    title: "Add to",
    placement: 'right',
    trigger: 'manual'
}; 

var catPopoverOpt2 = {
    html: true,
    content: "<div class='row popoverContent'><dic class='col-md-12'>"+optionData+"</div></div>",
    container: $('#detatilModal'),
    title: "Add to",
    placement: 'right',
    trigger: 'manual'
}; 

/* Get Current Tab name */
function getCurrentTab(){
    var currentTab = mealplanmodal.find('#classTabs li.active').find('a').attr('href');
    return currentTab.substring(1, currentTab.length);
}

/* Set active tab */
function resetTab(type){
    if(type == 'Meal'){
        mealplanmodal.find('#classTabs').find('a[href="#mealDetail"]').trigger("click");
        mealplanmodal.find('#classTabs').find('a[href="#foodDetail"]').closest('li').addClass('hidden');
        mealplanmodal.find('#foodDetail').addClass('hidden');
    }
    else{
        mealplanmodal.find('#classTabs').find('a[href="#foodDetail"]').trigger("click");
        mealplanmodal.find('#classTabs').find('a[href="#mealDetail"]').closest('li').addClass('hidden');
        mealplanmodal.find('#mealDetail').addClass('hidden');
    }
}

/* Diaplay list with detail */
function displayList(type, data, response=null,category=null){
    // console.log('data==', response);
    if(response.html){
        $('.include-filter-popup').html(response.html); 
    }
    if(response.mob_html){
        $('.include-filter-popup-mob').html(response.mob_html); 
    }
    if(response.tag){
        $('.filter-data').html(response.tag); 
    } else {
        $('.filter-data').html(''); 
    }
    console.log('category',category);
    $('#category-name').attr('value', category);
    var html = "",
        appendArea = mealplanmodal.find("#list-area-"+type);

    appendArea.empty();
    //  var total_record = data.length;
     $('.total-count').html(data.length);
    if(data.length > 0){
        $.each(data, function(i, value){
            if((value.name).length > 19)
                var name = (value.name).substring(0, 19)+'...';
            else
                var name = (value.name);

            if((value.description).length > 90)
                var description = (value.description).substring(0, 90)+'...';
            else
                var description = (value.description);

            if(value.img == '')
                var imgPath = public_url+'profiles/noimage.gif';
            else
                var imgPath = public_url+'uploads/thumb_'+value.img;

            html += '<li>\
              <div class="recipe-data data-btn detailBtn" data-id="'+value.id+'" data-type="'+type+'" data-title="'+value.name+'">\
                    <div class="imgbox">\
                      <img src="'+imgPath+'">\
                    </div>\
                     <div class="col-md-12 col-xs-12 h-114">\
                        <h5 class="recipe-title" >'+name+'</h5>\
                        <div class="recipe-description">\
                            '+description+'\
                        </div>\
                    </div>\
                    <div class="rating collectable__rating"> <span>(1)</span> <div aria-hidden="true" class="rating__star rating__star--checked"> <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 25 23.8"> <path fill="currentColor" d="M12.5 0l2.9 9.1H25l-7.7 5.6 2.9 9.1-7.7-5.7-7.7 5.7 2.9-9.1L0 9.1h9.6z"> </path> </svg> </div> <div aria-hidden="true" class="rating__star"> <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 25 23.8"> <path fill="currentColor" d="M12.5 0l2.9 9.1H25l-7.7 5.6 2.9 9.1-7.7-5.7-7.7 5.7 2.9-9.1L0 9.1h9.6z"> </path> </svg> </div> <div aria-hidden="true" class="rating__star"> <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 25 23.8"> <path fill="currentColor" d="M12.5 0l2.9 9.1H25l-7.7 5.6 2.9 9.1-7.7-5.7-7.7 5.7 2.9-9.1L0 9.1h9.6z"> </path> </svg> </div> <div aria-hidden="true" class="rating__star"> <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 25 23.8"> <path fill="currentColor" d="M12.5 0l2.9 9.1H25l-7.7 5.6 2.9 9.1-7.7-5.7-7.7 5.7 2.9-9.1L0 9.1h9.6z"> </path> </svg> </div> <div aria-hidden="true" class="rating__star"> <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 25 23.8"> <path fill="currentColor" d="M12.5 0l2.9 9.1H25l-7.7 5.6 2.9 9.1-7.7-5.7-7.7 5.7 2.9-9.1L0 9.1h9.6z"> </path> </svg> </div> </div>\
                  <a href="#" class="btn btn-xs btn-primary m-l-5 addBtn addBtn'+type+'"><i class="fa fa-plus"></i></a>\
                </div>\
              </li>';
        });
    } else {
        html += '<p> No records found !! </p> '
    }
    appendArea.append(html);
    // appendArea.find('.addBtn').popover(catPopoverOpt);

    // Remove section
    // <small><b>'+value.cat+'</b></small>

}   

/* Get all meal and food data */
function getListData(tab,category=null){
    toggleWaitShield('show');
    if(typeof tab == 'undefined'){
        tab = getCurrentTab();
    }
    data = {};
    if(tab == 'mealDetail'){
        var type = 'Meal';
        var url = public_url+'meal-planner/calendar/meallist';
        if(category != null){
            data = {category_type:category};
        }
    }
    else{
        var type = 'Food';
        var url = public_url+'meal-planner/calendar/foodlist';
    }

    $.getJSON(url,data, function(response){
    
        // console.log('response', response);
        if(response.status == 'success'){
            // setTimeout(function() {   
                displayList(type, response.data, response, category);
            //  }, 500);
           
           
        }

        toggleWaitShield('hide');
    });
}

/* Display details on details modal */
function displayDeatils(type, data){
    var modal = $('#detatilModal');
        if(type == 'Meal'){
        if(data.is_custom == 2)
        {
            modal = $('.custom_meals_details');
            modal.find('#recipe_name').text(data.name);
            if(data.img != '' && data.img != undefined){
                modal.find('.custom_food_img').show();
                modal.find('.extra-data').show();
                modal.find('.custom_food_img img').attr('src',public_url+"uploads/"+data.img);
            }else{
                modal.find('.custom_food_img').hide();
                modal.find('.extra-data').hide();
            }
            if(data.time != "" && data.time != undefined){
                modal.find('#nutritionTIme').text(moment(data.time, 'HH:mm a').format('hh:mm A'));
            }
            if(data.hunger_rate != '' && data.hunger_rate != undefined){
                var ratingHtml = '';
                var i = 1
                for(i;i<=10;i++){
                    if(i <= data.hunger_rate){
                        ratingHtml += '<i class="fa fa-star" aria-hidden="true"></i>'; 
                    }else{
                        ratingHtml += '<i class="fa fa-star-o" aria-hidden="true"></i>';
                    }
                }
                modal.find('.rating_icon').empty();
                modal.find('.rating_icon').append(ratingHtml); 
            }
            if(data.activity_label != "" && data.activity_label != undefined){
                modal.find('#activityLabel').text(data.activity_label);
            }
            if(data.general_notes != '' && data.general_notes != undefined){
                modal.find('#generalNotes').text(data.general_notes);
            }
            if(data.meal_rating != '' && data.meal_rating != undefined){
                modal.find('#mealRating').text(data.meal_rating);
            }
            if(data.enjoyed_meal != '' && data.enjoyed_meal != undefined){
                modal.find('#mealPortion').text(data.enjoyed_meal);
            }
            var html = '';
            $.each(data.ingrediantData,function(key,value){
                html += '<p>'+value.ingrediant+' : '+value.quantity+'</p>'
            });
            modal.find('#ingredients').empty();
            modal.find('#ingredients').append(html);
            modal.find('.quant').hide();
            modal.find('#serving_size').text(data.serves);
            modal.find('#type').text(data.catName);
            $('#detatilModal').find('#deleteEvent').data('id',data.id);
            $('#detatilModal').find('#deleteEvent').data('type',data.type);
            $('.custom_meals_details').show();
            $('.meals_details').hide();
            $('.foodModal').hide();
        }else if(data.is_custom == 1){
            modal.find('.quant').show();
            modal = $('.custom_meals_details');
            modal.find('#recipe_name').text(data.name);
            modal.find('#ingredients').text(data.ingredients);
            modal.find('#quantity').text(data.quantity);
            modal.find('#serving_size').text(data.serves);
            modal.find('#type').text(data.catName);
            $('#detatilModal').find('#deleteEvent').data('id',data.id);
            $('#detatilModal').find('#deleteEvent').data('type',data.type);
            $('.custom_meals_details').show();
            $('.meals_details').hide();
            $('.foodModal').hide();
        }
        else{
            $('.custom_meals_details').hide();
            modal.find('#recipeTitle').text(data.name);
            modal.find('#mealImage').attr('src',public_url+'uploads/thumb_'+data.img);
            modal.find('#nutritional_information').html(nuratInfoHtml(data.nutrInfo));
            modal.find('.description_data').html(data.description);
            /*  */
            modal.find('.bottom_data').html(data.html);
            /*  */
            modal.find('#preparationData').html(data.method);
            modal.find('#tipsData').html(data.tips);
            modal.find('#ingredientPara').html(data.ingredients);
            if(data.total_hrs > 0){
                modal.find('#preprationTimeHrs').text(data.total_hrs);
                $('.time-hrs').css('display','inline-block');
            }
            if(data.total_mins > 0){
                modal.find('#preprationTime').text(data.total_mins);
                $('.time-min').css('display','inline-block');
            }
            // modal.find('#preprationTime').text(data.time);
            modal.find('#servingSize').text(data.serves);
            modal.find('#deleteEvent').data('id',data.id);
            modal.find('#deleteEvent').data('type',data.type);
            modal.find('.addFromDetail').data('id',data.id);
            modal.find('.addFromDetail').data('type',data.type);
            var nutritionInfo = data.nutrInfo; 
            if(data.serves == undefined || isNaN(data.serves) || data.serves == '')
            {
                data.serves = 1;
            }
            console.log('here');
            $('#calories').text((nutritionInfo.energ_kcal/data.serves).toFixed(2));
            $('#nutriData').html('<table class="bottom-table"><tr><th><span>Fat</span>:</th><th><span>Saturated Fat</span>:</th><th><span>Sugar</span>:</th><th><span>Carbohydrate</span>:</th><th><span>Sodium</span>:</th><th><span>Fiber</span>:</th><th><span>Protein</span>:</th><th><span>Cholesterol</span>:</td></tr><tr><td>  <span>'+(nutritionInfo.fat/data.serves).toFixed(2)+'</span> g</td><td>  <span>'+(nutritionInfo.fa_sat/data.serves).toFixed(2)+'</span> g</td><td>  <span>'+(nutritionInfo.sugar/data.serves).toFixed(2)+'</span> g</td><td>  <span>'+(nutritionInfo.carbohydrate/data.serves).toFixed(2)+'</span> g</td><td>  <span>'+(nutritionInfo.sodium/data.serves).toFixed(2)+'</span> mg</td><td>  <span>'+(nutritionInfo.sugar/data.serves).toFixed(2)+'</span> g</td><td>  <span>'+(nutritionInfo.protein/data.serves).toFixed(2)+'</span> g</td><td>  <span>'+(nutritionInfo.cholesterol/data.serves).toFixed(2)+'</span> mg</td></tr></table>');
            $('.meals_details').show();
            $('.foodModal').hide();
       }
    }  
    else{
        /* image display */
        modal.find('#img-area').empty();
        modal.find('#img-area').append('<img src="'+public_url+'uploads/thumb_'+data.img+'" width="100%" />');

        /* display tag button */
        var tagsBtn = '';
        if((data.tags).length > 0){
            $.each(data.tags, function(i, name){
                tagsBtn += '<button class="btn btn-default m-r-5">'+name+'</button>';
            });
        }
        modal.find('#tags-area').empty();
        modal.find('#tags-area').append(tagsBtn);

        modal.find('#nutritional_information').html(nuratInfoHtml(data.nutrInfo));
        modal.find('#description').html(data.description);
        modal.find('#ingredients').html("<pre>"+data.ingredients+"</pre>");
        modal.find('#method').html(data.method);
        modal.find('#tips').html(data.tips);
        modal.find('#deleteEvent').data('id',data.id);
        modal.find('#deleteEvent').data('type',data.type);
        $('.meals_details').hide();
        $('.foodModal').show();
    }

}

function nuratInfoHtml(data){
    var html = '';
    if(Object.keys(data).length > 0){
        html += '<div class="row">'
        $.each(data, function(key, value){  
            html += '<div class="col-md-6 col-xs-6"><p><b>'+textLabel(key)+'</b>&nbsp;:&nbsp;'+value+'</p></div>';
        })
        html += '</div>';
    }
    return html;
}

/* String format for label */
function textLabel(str){
    var string = str.split('_');
    var name = '';
    $.each(string, function(i, value){
        name += value.charAt(0).toUpperCase() + value.substr(1) + ' '; 
    })
    return name;
} 

$(document).ready(function(){
    // $('#detatilModal').find('.addFromDetail').popover(catPopoverOpt2);

    /* click on out side popover its close */
    $('body').on('click', function (e) {
        var open = ($('#detatilModal').data('bs.modal') || {}).isShown;
        if(!(open == true)){
            $('.popover').each(function () {
                if (!$(this).is(e.target) && $(this).has(e.target).length === 0 && $('.popover').has(e.target).length === 0)
                    $(this).popover('hide');
            });
        }
    });

    /* open popover for category */
    $('body').on('click', '.addBtn', function(e){
        e.preventDefault();
        e.stopPropagation();
        
        $('body').find('.popover').popover('hide');
        if($(this).find('.fa-plus').length > 0)
            $(this).popover('show');

        var $this = $(this),
            id = $this.closest('.data-btn').data('id'),
            type = $this.closest('.data-btn').data('type');

        clickedBtn = $this;
        openPopover = $('#'+$this.attr('aria-describedby'));
        openPopover.css({"z-index":"10000","width":"150px"}); 
          
    });

    /* Open popver on details page */
    $('body').on('click','.addFromDetail', function(e){
        e.preventDefault();
        
        // if($(this).find('.fa-plus').length > 0)
        //     $(this).popover('show'); 

        // var $this = $(this);

        // clickedBtn = $this;
        // openPopover = $('#'+$this.attr('aria-describedby'));
        // openPopover.css({"z-index":"1000000","width":"150px"});
        var $this = $(this),
            formData = {};

            formData.cat = mealplanmodal.find('input[name="eventCat"]').val();
            formData.snackType = mealplanmodal.find('input[name="eventSnackType"]').val();
            formData.id = $this.data('id');
            formData.type = $this.data('type');
            formData.date = mealplanmodal.find('input[name="eventDate"]').val();
        if(formData.id && formData.type != '' && formData.date != ''){
            $.ajax({
                url : public_url+"meal-planner/calendar/store",
                type : 'POST',
                data : formData,
                success : function(response){
                    var data = JSON.parse(response);
                    if(data.status == 'success'){
                        swal({
                            title: 'Success',
                            text: 'Meal added successfully',
                            type: "success",
                            allowOutsideClick: false,
                        },function(isConfirm){
                            if(isConfirm){
                                $('#detatilModal').modal('hide');
                                getDbEvent();
                                getWeekEvent();
                                getDayEvent();
                            }
                        });
                    }else{
                        swal({
                            title: 'Error',
                            text: 'Something went wrong',
                            type: "error",
                            allowOutsideClick: true,
                        });
                    }
                }
            })
        }
    })

    /* Add meal/ food */
     $('body').on('change', 'select#catName', function(){
        var $this = $(this),
            popoverContent = $this.closest('.popoverContent'),
            formData = {};

            formData.cat = $this.val();
            formData.id = clickedBtn.closest('.data-btn').data('id');
            formData.type = clickedBtn.closest('.data-btn').data('type');
            formData.date = mealplanmodal.find('input[name="eventDate"]').val();

        if(formData.id && formData.type != '' && formData.date != ''){
            $.ajax({
                url : public_url+"meal-planner/calendar/store",
                type : 'POST',
                data : formData,
                success : function(response){
                    var data = JSON.parse(response);
                    if(data.status == 'success'){
                        clickedBtn.find('i').removeClass('fa-plus');
                        clickedBtn.find('i').addClass('fa-check');
                        clickedBtn.popover('hide');
                        getDbEvent();
                    }
                }
            })
        }
    });

    /* Add Meal using Add Btn */
    $('body').on('click', '.addBtnMeal', function(){
        var $this = $(this),
            formData = {};
            formData.cat = mealplanmodal.find('input[name="eventCat"]').val();
            formData.snackType = mealplanmodal.find('input[name="eventSnackType"]').val();
            formData.id = clickedBtn.closest('.data-btn').data('id');
            formData.type = clickedBtn.closest('.data-btn').data('type');
            formData.date = mealplanmodal.find('input[name="eventDate"]').val();
            formData.title = clickedBtn.closest('.data-btn').data('title');
        if(formData.id && formData.type != '' && formData.date != ''){
            $.ajax({
                url : public_url+"meal-planner/calendar/store",
                type : 'POST',
                data : formData,
                success : function(response){
                    var data = JSON.parse(response);
                    if(data.status == 'success'){
                        clickedBtn.find('i').removeClass('fa-plus');
                        clickedBtn.find('i').addClass('fa-check');
                        clickedBtn.popover('hide');
                        getDbEvent();
                        getWeekEvent();
                        getDayEvent();
                    }
                }
            })
        }
    });

    /* Type and filter of meal*/
    $('input[name="meal_name"]').keyup(function(e){
        var text = $(this).val(),
            url = public_url+'meal-planner/calendar/meallist';

        clearTimeout(timeinterval);
        timeinterval =  setTimeout(function(){
            toggleWaitShield('show');
            $.getJSON(url, {text:text}, function(response){
                if(response.status == 'success'){
                    displayList('Meal', response.data);
                }
                else{
                    displayList('Meal', []);
                }
                toggleWaitShield('hide');
            }); 
        }, 1000);
    });

    /* Type and filter of food*/
    $('input[name="food_name"]').keyup(function(e){
        var text = $(this).val(),
            url = public_url+'meal-planner/calendar/foodlist';

        clearTimeout(timeinterval);
        timeinterval =  setTimeout(function(){
            toggleWaitShield('show');
            $.getJSON(url, {text:text}, function(response){
                if(response.status == 'success'){
                    displayList('Food', response.data);
                }
                else{
                    displayList('Food', []);
                }
                toggleWaitShield('hide');
            }); 
        }, 1000);
    });

    /* Open Details modal */
    $('body').on('click', '.detailBtn', function(e){
        e.preventDefault();
        //toggleWaitShield('show');

        var $this = $(this),
            modal = $('#detatilModal'),
            id = $this.data('id'),
            type = $this.data('type');

        $(this).popover(catPopoverOpt);

        if(type == 'Meal'){
            modal.find('.modal-title').text("Meal Details");
            var url = public_url+'meal-planner/calendar/meal/';
        }
        else{
            modal.find('.modal-title').text("Food Details");
            var url = public_url+'meal-planner/calendar/food/';
        }

        modal.find('.data-btn').data('id', id);
        modal.find('.data-btn').data('type', type);

        $.getJSON(url+id, function(response){
            if(response.status == 'success'){
                displayDeatils(type, response);
                modal.find('.addFromDetail').show();
                modal.find('.back-btn').show();
                modal.find('.done-btn').hide();
                $('#detatilModal').modal("show");
            }
            toggleWaitShield('hide');
        });
    })

});