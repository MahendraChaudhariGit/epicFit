$(document).ready(function(){

	/* Start: Modal for Add more */
		$('.add-more-subcat').click(function(e){
			e.preventDefault();

			var $this = $(this),
				href = $this.attr('href');

			$.get(href, function(data){
				data = JSON.parse(data);

				var modal = $('#addSubcat');
				//modal.find('.modal-title').text($this.data('modal-title')); //Setting title
				modal.find('.modal-title').text('Product Categories/Subcategories');

				modal.find('.addEditSubCat, .delLink').attr('href', href).data('extra', '') //Setting add/edit route

				var catType = $this.data('field');
				modal.find('input[name="fields"]').val(catType) //Field class to edit/update

				var tableBody = modal.find('tbody');
				tableBody.find('tr:not(.hidden)').remove(); //Emptying table
				
				addRowSubCatModal(data);
				modal.modal('show');
			});
		})

		$('body').on('click', '.addEditSubCat', function(e){	
			e.preventDefault();

			var $this = $(this),
				modal = $('#addMoreSubCat'),
				heading = modal.find('.modal-subtitle'),
				idField = modal.find('input[name="editId"]'),
				field = modal.find('input[name="text"]'),
				cat_subtitle = modal.find('input[name="sub_title"]'),
				slug = modal.find('input[name="slug"]'),
				old_slug = modal.find('input[name="old_slug"]'),
				select = modal.find('select[name="productCat-id"]'),
				prevPicField = modal.find('[name="prePhotoName"]'),
				newPicField = modal.find("[name='subCategoryImage']"),
				picPreview = modal.find('.previewImg');

			prevPicField.val('')
			newPicField.val('')
			
			var catData = $('input[name="pro-cat-val"]').val();
			if(catData){
				catData = JSON.parse(catData);
				select.empty();
				var dd = "<option value='0'> Is parent </option>";
				$.each(catData, function(index, value){
					
					dd += "<option value='"+index+"'>"+value+"</option>";
				})
				select.append(dd);
				select.selectpicker('refresh');
			}

			if(hasData($this, 'entity-id')){ //Edit Case
				heading.text('Edit');
				picPreview.removeClass('hidden');
				idField.val($this.data('entity-id'));

				cat_subtitle.val($this.closest('tr').data('subtitle'));
				slug.val($this.closest('tr').data('slug'));
				old_slug.val($this.closest('tr').data('slug'));

				var procatId = $this.closest('tr').data('procatid');
				select.val(procatId);
				select.selectpicker('refresh');

				if(procatId == 0){
					var value = $this.closest('tr').find('.cat-text').text();
					select.prop("disabled", true);
				}else{
					var value = $this.closest('tr').find('.subcat-text').text();
					select.prop("disabled", false);
				}
				field.val(value);

				var img = $this.closest('tr').find('.subCatImg-body img').attr('src');
				if(img){
					var n = img.lastIndexOf('/'),
						picName = img.substring(n + 1);
					prevPicField.val(picName);

					picPreview.attr('src', img);
				}
			}
			else{ //Add Case
				heading.text('Add');
				picPreview.attr('src','');
				picPreview.addClass('hidden');
				idField.val('');
				field.val('');
				cat_subtitle.val('');
				slug.val('');
				old_slug.val('');
				select.val('');
				select.prop("disabled", false);
				
			}
			select.selectpicker('refresh');
			modal.find('form').attr('action', $this.attr('href'));
			setFieldNeutral(field);
			setFieldNeutral(newPicField);

			modal.modal("show");
		});

		$('#addMoreSubCat .submit').click(function(){
			var form = $('#addMoreSubCat form'),
				isFormValid = form.valid(),
				formData = {},
				upImg = form.find('[name="subCategoryImage"]').val(),
				preImgn = form.find('[name="prePhotoName"]').val();

			if(upImg == '' && preImgn == ''){
				form.find('.upload-group').addClass('has-error');
				isFormValid = false;
			}

			if(isFormValid){
				if(upImg)
					formData.image = upImg;
				else
					formData.image = preImgn;

				formData.entityId = form.find('input[name="editId"]').val();
				formData.text = form.find('input[name="text"]').val();
				formData.sub_title = form.find('input[name="sub_title"]').val();
				formData.procat_id = form.find('select[name="productCat-id"]').val();
				formData.slug = form.find('input[name="slug"]').val();
				formData.old_slug = form.find('input[name="old_slug"]').val();
				if(formData.procat_id !=0)
					var procatname = form.find('select[name="productCat-id"] option:selected').text();
				

				$.post(form.attr('action'), formData, function(response){
					var data = JSON.parse(response);
					console.log(data);
					if(data.status == 'success'){
						var catId = data.catId;
							$row = $('#addMore-row-'+catId),
							field = $('select.productSubCat'),
							hiddenField = $('input[name="pro-cat-val"]'),
							catVal = JSON.parse(hiddenField.val()),
							$dd = '';

						if(catVal.length < 1)
							var catVal = {};

						if(!formData.entityId){ //Add Case
							if(formData.image != '')
								var addData = {id:catId, name:formData.text, image:formData.image, procatid:formData.procat_id, procatName:procatname, subtitle:formData.sub_title, slug:data.catSlug};

							addRowSubCatModal([addData])
							if(formData.procat_id != 0){
								$dd = '<option value="'+catId+'">'+procatname+'->'+formData.text+'</option>';
								field.append($dd);
							}
							else{
								catVal[catId] = formData.text;
							}
						}
						else{ //Edit Case

							if(formData.image != ''){
								$('#addMore-row-'+catId+' .subCatImg-body img').attr('src', public_url+'uploads/'+formData.image);
								//$('#addMoreSubCat').find('input[name="subCategoryImage"]').val(formData.image);
							}
							$row.data('subtitle',formData.sub_title);
							$row.data('slug',data.catSlug);

							if(formData.procat_id == 0){
								$row.find('.cat-text').text(formData.text);
								$row.find('.subcat-text').text(' ---- ');
								field.find('option[value="'+catId+'"]').text(formData.text);
								catVal[catId] = formData.text;
								$row.data('procatid',0);
							}
							else{
								$row.find('.cat-text').text(procatname);
								$row.find('.subcat-text').text(formData.text);
								field.find('option[value="'+catId+'"]').text(procatname+'->'+formData.text);
								var $cat = {};
								$.each(catVal, function(key, value){
									if(key != catId)
										$cat[key] = value;
								})
								catVal = $cat;
							}

						}

						console.log(catVal);
						console.log(JSON.stringify(catVal));
						field.selectpicker('refresh');
						hiddenField.val(JSON.stringify(catVal));
						$('#addMoreSubCat').modal("hide");
					}
				});
			}
		})
	/* End: Modal for Add more */

	/* Start: Hide/Show product color */
	$('#product_color_checkbox').change(function(){
		var $this = $(this),
			colorFIeld = $('#product_color_field');
		if($this.is(':checked'))
			colorFIeld.show();
		else
			colorFIeld.hide();
	})
	/* End: Hide/Show product color */
})

function addMoreSubCatDel(catId){
	if(catId != 'error'){
		var hiddenField = $('input[name="pro-cat-val"]'),
			catVal = JSON.parse(hiddenField.val()),
			$cat = {};
			$.each(catVal, function(key, value){
				if(key != catId)
					$cat[key] = value;
			})
			hiddenField.val(JSON.stringify($cat));	
		$('#addMore-row-'+catId).remove();

		var field =  $('select.productSubCat');
		field.find('option[value="'+catId+'"]').remove();
		field.selectpicker('refresh');
	}
}

function addRowSubCatModal(data){
		//Adding rows to table
		if(data.length){
			var tableBody = $('#addSubcat tbody'),
				dummyRow = tableBody.find('tr.hidden');
				
			$.each(data, function(key, value){

				var row = dummyRow.clone();
				row.removeClass('hidden').attr('id', 'addMore-row-'+value.id);
				row.data('procatid',value.procatid);
				row.data('subtitle',value.subtitle);
				row.data('slug',value.slug);

				if(value.procatid == 0){
					row.find('.cat-text').text(value.name);
					row.find('.subcat-text').text(' ---- ');
				}
				else{
					row.find('.cat-text').text(value.procatName);
					row.find('.subcat-text').text(value.name);
				}

				var editLink = row.find('.addEditSubCat');
				editLink.data('entity-id', value.id)

				var delLink = row.find('.delLink');
					delLinkHref = delLink.attr('href');
				delLink.attr('href', delLinkHref+'/'+value.id)

				row.find('.subCatImg-body').children().attr('src', public_url+'uploads/'+value.image);
			
				tableBody.append(row);
			});
		}
}

function imageUploadHandel($this){
	//var imgObj = document.getElementById('uploded-image');
	//var elem = $('#uploded-image');
	var elem = $($this),
		type = elem.data('type'),
		formGroup = elem.closest('.form-group'),
		inputElem = formGroup.find('input[name="inputElem"]').val(),
		imgSrcCls = formGroup.find('input[name="imgSrcCls"]').val(),
		saveUrl = formGroup.find('input[name="saveUrl"]').val(),
		entityId = formGroup.find('input[name="entityId"]').val(),
		preImg = formGroup.find('input[name="prePhotoName"]'),
		files = elem[0].files,
		formData = new FormData();

	formData.append('type', type);
	$.each(files, function(key, value){
        formData.append(key, value);
    });
	formData.append('preImg', preImg.val());

	$.ajax({
        url: public_url+'intervention-resizeImage?files',
        type: 'POST',
        data: formData,
        cache: false,
        dataType: 'json',
        processData: false, // Don't process the files
        contentType: false, // Set content type to false as jQuery will tell the server its a query string request
        success: function(response){
        	if(response.status == 'save'){
        		var imageName = response.imgname+'.'+response.imgex;

        		preImg.val(imageName);
        		formGroup.removeClass('has-error');
        		formGroup.find('.help-block').html(response.message).hide();

        		formGroup.find('input[name="'+inputElem+'"]').val(imageName);
        		formGroup.find('.'+imgSrcCls).attr('src', public_url+'uploads/'+imageName).removeClass('hidden');

        		if(typeof saveUrl != 'undefined' && typeof entityId != 'undefined' && saveUrl && entityId){
        			$.post(public_url+saveUrl, {id: entityId, logo: imageName}, function(response){/*console.log(response);*/});
        		}
        	}
        	else{
        		formGroup.find('.'+imgSrcCls).attr('src', '').addClass('hidden');
        		formGroup.addClass('has-error');
        		formGroup.find('.help-block').html(response.message).show();
        	}
        }
    });
	
}