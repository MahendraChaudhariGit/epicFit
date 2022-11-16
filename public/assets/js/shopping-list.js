$(document).ready(function(){
	/* Select/Deselect shopping list */
	$('#all').change(function(){
		var $this = $(this),
			table = $this.closest('table');
		if(this.checked){
			table.find('.shop-checkbox').prop('checked', true);
		}
		else{
			table.find('.shop-checkbox').prop('checked', false);
		}
	})

	/* Create div like checkbox */
	$('.checkbox-div').click(function(e){
		e.stopPropagation();
		var $this = $(this),
			checkBox = $this.find('input.checkbox-input'),
			table = $this.closest('table');

		if(checkBox.is(':checked')){
			if(checkBox.attr('name') == 'all'){
				checkBox.prop('checked', false);
				table.find('.shop-checkbox').prop('checked', false);
			}
			else{
				checkBox.prop('checked', false);
			}
		}
		else{
			if(checkBox.attr('name') == 'all'){
				checkBox.prop('checked', true);
				table.find('.shop-checkbox').prop('checked', true);
			}
			else{
				checkBox.prop('checked', true);
			}
		}
	})
})