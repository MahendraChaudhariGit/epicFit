$(document).ready(function(){
	$('.callEditContctSubview').click(function(e){
		e.preventDefault();

		var $this = $(this),
			id = $this.data('contact-id');
			/*subview = $('#subview');
			console.log(id);
			console.log(subview);*/
		if(id){
			openSubview($(this), id);
			
			/*var src = 'settings/business/contacts/'+id+'/edit';

			subview.find("iframe").attr("src", public_url+src);
			subview.show("slide", {direction:"right"}, 200);
			subviewOpen = true;*/
		}
	});
});