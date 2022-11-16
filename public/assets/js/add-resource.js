var rowCreated = 0;
//var resItemLoc=0;
$(document).ready(function(){
    $("input[name=resName]").keyup(function(){
	          update_text();
	  });

    $('body').on('change', '.clone-textbox', function(e){
		e.preventDefault();
	    $(this).data('clone','1');
	  });
    
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
   	  resourceRow.append(" <div class='col-md-2'><a class='btn btn-xs btn-red remove-resource-row' href='#''><i class='fa fa-times fa fa-white'></i></a></div> ");
   	  //resourceRow.find('select').remove();
   	  //resourceRow.find('select').selectpicker('refresh');
   	      self.closest('fieldset').append(resourceRow);
   	      update_text();
	   }//empty condition check end
	});
	
	//delete row of resource
	$('body').on('click', '.remove-resource-row', function(e){
		e.preventDefault();
		$(this).closest('.row').remove();
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
	 	  	console.log(clone_textbox.eq(i).data('clone'));
	         if(clone_textbox.eq(i).data('clone')==0)
	         	      clone_textbox.eq(i).val(item1.val()+c);
	 	  }
        }	 	  
 }