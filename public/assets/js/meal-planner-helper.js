var productNames = new Array();
var productIds = new Object();
var servingIds = new Object();
function foodsTypeaHead(self) {
	var queryText = self.val();
    $.getJSON( public_url+'meal-planner/getFoodList', {query: queryText}, function (jsonData){
        $.each(jsonData, function (index, product ){
        	if(Object.values(productIds).indexOf(product.id) == -1){
	            productNames.push( product.name );
	            productIds[product.name] = product.id;
	            servingIds[product.name] = product.serving_size;
	        }
        });

     	self.typeahead({ 
            source : productNames,
            afterSelect:function(selection){ 
                self.closest('.form-group').find("input.food_id").val(productIds[selection]);
                self.closest('.form-group').find("#pre_serv_id").val(servingIds[selection]);
            }
        });
    });
}
