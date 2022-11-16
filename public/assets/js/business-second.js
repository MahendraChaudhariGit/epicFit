<!-- start: EPIC ACCORDIAN -->
$(document).ready(function() {
	var epicAccordianElem = $('.epic-accordion');
	epicAccordianElem.find('.panel-body').css({'display': 'none', 'padding-top': 0, 'padding-bottom': 0});
	
	epicAccordianElem.find('.panel-heading .fa-chevron-down', '.panel-heading .fa-chevron-up').on('click', function() {
		toggleSections(this);
	});
	toggleSections(epicAccordianElem.find('.panel-heading .fa-chevron-down')[openStep]);
});

function toggleSections(elem){
	var pclass = $(elem).attr('class');
	if( pclass == 'fa fa-chevron-up pull-right') {
		$(elem).attr('class', 'fa fa-chevron-down pull-right');
	} else if( pclass == 'fa fa-chevron-down pull-right' ) {
		$(elem).attr('class', 'fa fa-chevron-up pull-right');
	}
	var self = $(elem).closest('.panel-heading');
	self.closest('.panel').siblings().find('.panel-heading .fa-chevron-up').attr('class', 'fa fa-chevron-down pull-right');
	self.closest('.panel').siblings().find('.panel-body').removeClass('panel-top-90');
	self.closest('.panel').siblings().find('.panel-body').slideUp(600);
	self.closest('.panel').find('.panel-body').slideToggle(600);

	var step = self.attr('data-step');
	var top = (step * 35) + 90;

	if ( top == NaN ) {
		top = 224;
	}

	$('#wizard > ul.anchor').css({'top': top+'px'});
}
<!-- end: EPIC ACCORDIAN -->

<!-- start: EXTRA FORM -->
/*$(document).ready(function() {
	$('body').on('click', '.btn-add-new-form', function(){	
		var self = $(this),
			id = self.attr('data-target-form'),
			targetForm = $('#'+id),
			formHeight = targetForm.height();

		$('#form-container').css('min-height', formHeight+'px');

		targetForm.attr('data-current-input', id);
		targetForm.show("slide", { direction: "right" }, 500);
	});

	$('body').on('click', '.add-new-form-save', function(e){	
		var self = $(this);
		self.closest('form').hide("slide", { direction: "right" }, 500);

		var currentInput = self.closest('form').attr('data-current-input');
		targetInput = $('[data-target-form="'+currentInput+'"]');
		targetInput.closest('.form-group').find('select').append('<option value="hi">Newly added</option>');

		$('select').selectpicker('refresh');

		e.preventDefault();
	});

	$('body').on('click', '.add-new-form-cancel', function(e){
		$(this).closest('form').hide("slide", { direction: "right" }, 500);

		e.preventDefault();
	})
})*/
<!-- end: EXTRA FORM -->



<!-- start: SAVE DATA -->
/*jQuery(document).on('click','.next-step',function(form){
	form.preventDefault();
	var sform = $(this).closest('form');
	formData = {}
	jQuery.each(sform.serializeArray(), function(i, obj) {
		formData[obj.name] = obj.value
	});

	var venu = formData.venue_location;

	if(venu == 1) {
		var form = $('form.location');
		jQuery.each(formData, function(key, value){
			form.find('[name="'+key+'"]').val(value);
		})
	}
});*/
<!-- end: SAVE DATA -->

