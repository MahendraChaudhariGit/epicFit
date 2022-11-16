var dirtyForm = {
	class: 'dirty',

	/**
	 * Initialize plugin over fields. It accept field or container of fields
	 * @param {Number} a 
	 * @param {Number} b
	 * @return {Number} sum
	 */
	init: function(elem){
		var field = dirtyForm.getField(elem);

		if(field.length){
			field.each(function(){
				var $this = $(this);
				dirtyForm.destroy($this);
				$this.bind('change.dirtyForm', function(){
					$(this).addClass(dirtyForm.class);
				})
			})
		}
	},

	destroy: function(elem){
		var field = dirtyForm.getField(elem);

		if(field.length){
			field.each(function(){
				$(this).unbind('change.dirtyForm').removeClass(dirtyForm.class);
			})
		}
	},	

	isDirty: function(elem){
		if(dirtyForm.isFieldInput(elem))
			return elem.hasClass(dirtyForm.class)

		return elem.find(':input.'+dirtyForm.class+':not(.skip-dirty)').length;	
	},


	getField: function(elem){
		if(dirtyForm.isFieldInput(elem))
			return elem;
		
		//return dirtyForm.getLyingField(elem)
		return elem.find(':input:not(.skip-dirty)');
	},

	/*getLyingField: function(elem){
		return elem.find(':input:not(.skip-dirty)');
	},*/

	isFieldInput: function(elem){
		var tag = elem.prop('tagName').toLowerCase(),
			type = elem.prop('type');

		if(tag == 'select' || tag == 'input' || tag == 'textarea')
			return true;
		return false;
	}
}