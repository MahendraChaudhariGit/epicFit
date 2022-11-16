$('#UpdateShoppingList').click(function(){
    var formData ={};
    formData['startDate'] = $('input[name="startDate"]').val();
    formData['endDate'] = $('input[name="endDate"]').val();
    formData['clientId'] = $('input[name="clientId"]').val();
    var shoppingList = [];
    shopValue = {};
    $('.shoppingValue').each(function(){

       if($(this).prop('checked') == true){
       var  name =$(this).val(),
         quantity = $(this).data('quantity'),
         shopping_id = $(this).data('id');

         shopValue ={
             name: name,
             quantity:quantity,
             shopping_id:shopping_id
         };
        shoppingList.push(shopValue);
       }

    });
    formData['shoppingData'] = shoppingList;
    console.log(' formData',  formData['shoppingData']);
if( formData['shoppingData'].length > 0){
    $.post(public_url+'meal-planner/update-shopping-list', formData, function(data){
        swal({
            title: data,
            allowOutsideClick: true,
            showCancelButton: false,
            confirmButtonText: 'Okay',
            confirmButtonColor: '#ff4401'
        }, 
        function(isConfirm){
            if(isConfirm){
        window.location.reload();
            }
        })
        
    });

}
   

    
})


$('.viewRecipe').on('click',function(){
    var recipe = $(this).data('recipe-name');
     if(Object.keys(recipe).length > 0){
         var html='';
		$.each(recipe, function(key, value){
            
             html += ` <li>
             <div class="fc-event-container">
                     <table>
                     <tr>
                     <td>
                         <span class="eventTimeRange">
                           <h5>Recipe Name</h5>  ${value.recName}
                         </span></td>
                         
                     <br>
                            <td style="
                        "> <h5>Quantity</h5> ${value.quantity }
                                 </br>
                                 </td>
                                 </tr>
                                 </table>
                             </label>
                     </div>
                
             </div></li>`;
            
        });
      
        $('#recipe-modal').find('.showRecipe').empty().append(html);
    }
    $('#recipe-modal').modal('show');
})