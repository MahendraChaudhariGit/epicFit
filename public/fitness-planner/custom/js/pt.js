var CB={
	API_PATH:'http://api.onesportevent.com/DevApi',
	platformWebKey:'',
	IMAGE_DIR_URL: 'http://http://fmdev.azurewebsites.net/pt/images/',
	NO_IMAGE: '',
	DifficultyLevels: ['beginner', 'intermediate', 'expert'],
	selectedProgram:null,
	ProgramExerciseMap: {'WeekIndex':1,'DayIndex':1,'Sets':1,'Priority':1,'RepOrSeconds':30,'Resistance':'','RestSeconds':60,'TempoDesc':'','TempoTiming':'','EstimatedTime':60},
	ExBodyParts: ['Shoulders','Chest','Anterior Arms','Core','Forearms','Anterior Thigh','Neck & Upper Neck','Posterior Arms','Lats & Middle Back','Lower Back','Hips & Gluts','Posterior Thigh','Lower Leg'],
	UI:{
		popupParams: {className:'pt-popup',modal:true,width:720},
		aexTo: null, isMobile: false, currentPage:1, searchScroll:null,
		start:function(){
			CB.UI.isMobile = jQuery(document).width() <= 480;
			
			var mplist = jQuery('.pt .my_pt');
			//current layout is for: My Programs
			if(mplist[0]){
				CB.FX.loadProgramsList();
				return;
			}
			
			var mdet = jQuery('.pt .manage_det');
			//current layout is for: My Programs
			if(mdet[0]){
				var acc = jQuery('#pt-accordion');
				acc.accordion({
					collapsible: true,
					heightStyle: 'fill'
				});
				acc.find('h3 a').click(CB.UI.addExercise);

				var p = jQuery.parseJSON(localStorage['CB.SelectedProgram']);
				if(p.id){
					CB.selectedProgram = p;
					mdet.find('label').html(p.name);
					jQuery(mdet.find('p')[0]).html(p.desc);
					CB.FX.loadProgram(p.id);
				}
			}
		},
		addProgramInList:function(p){
			jQuery(CB.UI.isMobile?'<a href="javascript:void(0)">':'<div>')
			.attr({'class':'row','data':jQuery.toJSON({id:p.FixedProgramId, name:p.ProgramName, desc:p.ProgramDesc})})
			.append('<div class="left program_title"><label>'+p.ProgramName+'</label></div>\
				<div class="left exercise_count mob_view"><label>'+p.Snippet+'</label></div>\
				<div class="right">\
					<a class="btn_white mob_view" href="javascript:void(0)" onclick="CB.UI.goTo(\'manage\',this)">manage &rsaquo;</a>\
					<span class="mob_view_btn">&rsaquo;</span>\
				</div>\
				<div class="clr"></div>')
			.click(function(){
				if(this.tagName.toLowerCase() == 'a')
					CB.UI.goTo('manage', jQuery(this).find('div')[0]);
			})
			.appendTo(jQuery('.pt .my_pt'));
		},
		//extract data from UI and ask FX to redirect
		goTo:function(to, obj){
			obj = jQuery(obj);
			switch(to){
				case 'manage':
					localStorage['CB.SelectedProgram'] = obj.parents('.row').attr('data');
					location.href = 'manage.html';
				break;
			}
		},
		createProgram: function(f){
			var pn = f.pname.value;
			if(pn.replace(/ /g, '').length > 0)
				CB.FX.createProgram(pn, function(response){
					if(response.MessageId == 0){
						localStorage['CB.SelectedProgram'] = jQuery.toJSON({id:response.Program.FixedProgramId, 'name':pn, 'desc':''});
						location.href = 'manage.html';
					}
				});
			else CB.showErrorDiv('.create_pt .error-msg');
		},
		//show edit program view
		showEPForm: function(){
			var md = jQuery('.manage_det');
			var p = CB.selectedProgram;
			md.find('input.txtbox').val(p.name);
			md.find('textarea').val(p.desc);
			jQuery(md.find('div')[0]).hide();
			md.find('form').fadeIn();
		},
		hideEPForm: function(){
			var md = jQuery('.manage_det');
			md.find('form').hide();
			jQuery(md.find('div')[0]).fadeIn();
		},
		addExercise:function(e){
			CB.UI.aexTo = jQuery(this).parents('h3');
			popup.show('add_exercise.html?r='+Math.random(), jQuery.extend({},CB.UI.popupParams, {onload:CB.UI.initExSearch}));
			e.stopPropagation();
		},
		updateProgram:function(f){
			var id = CB.selectedProgram.id;
			var pn = f.pname.value;
			if(pn.replace(/ /g, '').length > 0){
				var pd = f.pdesc.value;
				CB.FX.updateProgram(id, pn, pd, function(response){
					if(response.MessageId == 0){
						var md = jQuery('.manage_det');
						md.find('label').html(pn);
						jQuery(md.find('p')[0]).html(pd);
						CB.UI.hideEPForm();
						with(CB.selectedProgram){
							name=pn;
							desc=pd;
						}
					}
				});
			}
			else CB.showErrorDiv('.manage_det .error-msg');
		},
		addExerciseToProgram: function(ex){
			var pc = jQuery('#pt-accordion .'+ex.WorkOut.replace(' ', '-').toLowerCase()).next(' .cat_det');
			if(pc[0]){
				var iurl = CB.IMAGE_DIR_URL+(ex.Image?ex.Image.ResourceName:CB.NO_IMAGE);
				var d = jQuery('<li>')
				.attr({data:jQuery.toJSON({id:ex.FixedProgramExerciseID, sets:ex.Sets, reps:ex.RepOrSeconds, tempo:ex.TempoTiming, rest:ex.RestSeconds, duration: ex.EstimatedTime, resist: ex.Resistance, typeid:ex.ExerciseTypeID, workout:ex.WorkOut})})
				.append('<div class="details_list"><div class="left desc">\
						<div class="left pic"><img src="'+iurl+'" /></div>\
						<div class="left lh18">\
							<div class="ex-name">'+ex.ExerciseDesc+'</div>\
							<div class="font11">\
								sets: '+ex.Sets+', reps: '+ex.RepOrSeconds+', tempo: '+ex.TempoTiming+'\
							</div>\
						</div>\
						<div class="clr"></div>\
					</div>\
					<div class="right">\
						<a href="javascript:void(0)" rel="edit"><img src="images/icon/edit.png" width="18"></a> &nbsp; \
						<a href="javascript:void(0)"><img src="images/icon/delete.png" width="18"></a>\
					</div>\
					<div class="clr"></div></div>\
					<div class="details_edit">\
						<div class="left desc"><img src="'+iurl+'" width="79"></div>\
						<div class="right right_sec">\
							<div class="left fields">\
								<div>\
									<div class="left small"><span>Sets: </span><input type="text" class="txtbox_small sets" placeholder="sets" /></div>\
									<div class="left small"><span>Reps: </span><input type="text" class="txtbox_small reps" placeholder="reps" /></div>\
									<div class="left large"><span>Duration: </span><input type="text" class="txtbox_small duration" placeholder="duration" /></div>\
									<div class="clr"></div>\
								</div>\
								<div class="ptop5">\
									<div class="left small"><span>Tempo: </span><input type="text" class="txtbox_small tempo" placeholder="tempo" /></div>\
									<div class="left small"><span>Rest: </span><input type="text" class="txtbox_small rest" placeholder="rest" /></div>\
									<div class="left large"><span>Resistance: </span><input type="text" class="txtbox_small resist" placeholder="resistance" /></div>\
									<div class="clr"></div>\
								</div>\
							</div>\
							<div class="right">\
								<a href="javascript:void(0)" rel="save"><img src="images/icon/save.png" width="18"></a> &nbsp; \
								<a href="javascript:void(0)" rel="cancel"><img src="images/icon/delete.png" width="18"></a>\
							</div>\
							<div class="clr"></div>\
						</div>\
                        <div class="clr"></div></div>')
				.appendTo(pc);
				jQuery('#pt-build-instruction').hide();
				//add onclick events to manage exercise, such as edit, update & delete
				d.find('a').click(function(){
					var de = d.find('.details_edit');
					var dl = d.find('.details_list');
					switch(this.rel){
						case 'edit':
							var dt = jQuery.parseJSON(d.attr('data'));
							de.find('.sets').val(dt.sets);
							de.find('.reps').val(dt.reps);
							de.find('.rest').val(dt.rest);
							de.find('.duration').val(dt.duration);
							de.find('.tempo').val(dt.tempo);
							de.find('.resist').val(dt.resist);
							dl.hide();
							de.fadeIn();
						break;
						case 'save':
							var sts = de.find('.sets').val();
							var rps = de.find('.reps').val();
							var rst = de.find('.rest').val();
							var dur = de.find('.duration').val();
							if(isNaN(sts) || isNaN(rps) || isNaN(rst) || isNaN(dur)){
								alert('Sets, Reps, Rest & Duration fields must contain only numeric values.');
								return;
							}

							CB.FX.updateProgramEx({'FixedProgramExerciseID':ex.FixedProgramExerciseID,'FixedProgramID':CB.selectedProgram.id,'ExerciseTypeID':ex.ExerciseTypeID,'WorkOut':ex.WorkOut,'Sets':sts,'RepOrSeconds':rps,'Resistance':de.find('.resist').val(),'RestSeconds':rst,'TempoTiming':de.find('.tempo').val(),'EstimatedTime':dur}, function(response){
								if(response.MessageId == 0){
									var ex = response.UpdatedExercise;
									var obj = {id:ex.FixedProgramExerciseID, sets:ex.Sets, reps:ex.RepOrSeconds, tempo:ex.TempoTiming, rest:ex.RestSeconds, duration: ex.EstimatedTime, resist: ex.Resistance, typeid: ex.ExerciseTypeID, workout:ex.WorkOut};
									d.attr('data', jQuery.toJSON(obj));
									dl.find('.font11').html('sets: '+ex.Sets+', reps: '+ex.RepOrSeconds+', tempo: '+ex.TempoTiming);
									de.hide();
									dl.fadeIn();
									CB.UI.calculateAndUpdateProgramTime();
								}
								else{
									alert(response.Message);
									//restore text fields to original values
									var dt = jQuery.parseJSON(d.attr('data'));
									de.find('.sets').val(dt.sets);
									de.find('.reps').val(dt.reps);
									de.find('.resist').val(dt.resist);
									de.find('.rest').val(dt.rest);
									de.find('.tempo').val(dt.tempo);
									de.find('.duration').val(dt.duration);
								}
							});
						break;
						case 'cancel':
							de.hide();
							dl.fadeIn();
						break;
						default:
							if(confirm('Are you sure you wish to remove ' + d.find('.ex-name').text() + ' from your program?')){
								CB.FX.removeExercise(ex.FixedProgramExerciseID, function(response){
									if(response.MessageId == 0){
										var p = d.parent();
										d.remove();
										var otherLi = p.find('li');
										if(otherLi.length) CB.UI.updateExPriorities(false, jQuery(otherLi));
										else CB.UI.calculateAndUpdateProgramTime();
										jQuery('.cat_det').sortable('refresh');
									}
								});
							}
					}
				});
				CB.UI.updateExPriorities(false, d);
			}
		},
		initExSearch: function(){
			var sv = jQuery('.ex-search');
			var ticker = null;
			var keyfield = sv.find('.txtbox');
			keyfield.placeholder();
			keyfield.keyup(function(){
				clearTimeout(ticker);
				ticker = setTimeout('CB.UI.searchExercise()', 1000);
			});
			sv.find('select, #pt-favorite').change(CB.UI.searchExercise);
			CB.UI.searchScroll = new CB.InfiniteScroller(sv.find('.exercise_list'), CB.UI.searchExercise);
			CB.UI.searchExercise();
			
			var pc = sv.parent();
			pc.find('.tab').click(CB.UI.switchEDTab);
			pc.find('.act-btns a').click(function(){
				if(this.innerHTML.indexOf('add to') == 0){
					var ex = jQuery.parseJSON(jQuery(this).parents('.ex-detail').find('.tab.sel').attr('data'));
					var ca = CB.UI.aexTo.attr('class').split(' ');
					CB.FX.addExToProgram({FixedProgramID:CB.selectedProgram.id, Workout:ca[0].replace('-', ' '), ExerciseTypeID:ex.id}, function(response){
						if(response.MessageId == 0){
							var ne = response.NewExercise;
							ne.ExerciseDesc=ex.name;
							ne.Image = ex.Image;
							CB.UI.addExerciseToProgram(ne);
							sv.find('#'+ex.id+' img')[3].src = 'images/icon/added.png';
							CB.UI.backToSearch();
							jQuery('.cat_det').sortable('refresh');
						}
					});
				}
				else CB.UI.backToSearch();
			});
			sv.find('.bodies img').load(function(){
				var pos = jQuery(this).position();
				//not sure why but we've to change position by 3 pixels to appear body parts at their correct places
				pos.left-=3;
				pos.top-=3;
				sv.find('svg').css(pos);

				var paths = sv.find('path');
				paths.click(function(){
					paths.css('opacity',0);
					jQuery(this).css('opacity',0.6);
					var index = this.id.substr(5);
					jQuery('#pt-bodypart').val(CB.ExBodyParts[index]);
					CB.UI.searchExercise();
				});
				paths.hover(
					function(){
						var p = jQuery(this);
						if(p.css('opacity') < 0.6)
							p.css('opacity',0.5);
					},
					function(){
						var p = jQuery(this);
						if(p.css('opacity') < 0.6)
							p.css('opacity',0);
					}
				);
			});
		},
		//contnue indicates if search from first page or continue from last seen page
		searchExercise: function(contnue){
			var pagenum;
			var elc = jQuery('.ex-search .exercise_list');
			var iss = CB.UI.searchScroll;
			if(contnue == true){
				pagenum = ++CB.UI.currentPage;
				iss.enabled = false;
			}
			else{
				pagenum = CB.UI.currentPage = 1;
				iss.enabled = true;
				elc.find('.exercise').remove();
			}
			if(this.id == 'pt-bodypart'){
				var ep = elc.parent();
				ep.find('path').css('opacity',0);
				var si = this.selectedIndex;
				if(si) ep.find('#pt-bp'+(si-1)).css('opacity', 0.6);
			}
			var elmsg = elc.find('.result-msg');
			elmsg.html('Loading...').show();
			CB.FX.searchExercise({keyWords: jQuery('.ex-search').find('input').val(), equipment: jQuery('#pt-equipment').val(), ability: jQuery('#pt-ability').val(), bodypart: jQuery('#pt-bodypart').val(), myFavourites: jQuery('#pt-favorite')[0].checked, perPage:10, pageNumber:pagenum}, function(response){
				var es = response.Exercises;
				if(response.MessageId == 0 && es.length){
					var clrdiv = elc.find('.clr.last');
					for(var i=0; i<es.length; i++){
						var rn = es[i].Resources[0].ResourceName;
						jQuery('<div>')
						.attr({'class': 'left exercise', 'id': es[i].ExerciseTypeID})
						.append('<div class="left pic"><img src="'+CB.IMAGE_DIR_URL+(rn?rn:CB.NO_IMAGE)+'" /></div>\
			                <div class="left desc">'
								+'<div>'+es[i].ExerciseDesc+'</div>\
								<div>\
									<div class="left font11">\
										'+CB.UI.getDLHTML(es[i].DifficultyLevel)+'\
									</div>\
									<div class="right">\
										<div><img src="images/icon/'+(es[i].IsFav?'remove':'add')+'-fav.png" width="20" /></div>\
										<div><img src="images/icon/add.png" width="23" /></div>\
									</div>\
									<div class="clr"></div>\
								</div>\
							</div>\
							<div class="clr"></div>')
						.insertBefore(clrdiv)
						.click(CB.UI.showExDetail)
						.find('img').click(function(e){
							e.stopPropagation();
							var ed = jQuery(this).parents('.exercise');
							var id = ed.attr('id');
							var img = this;
							if(this.src.indexOf('-fav')>0){
								//if exercise is to be added as favorite
								if(this.src.indexOf('add')!=-1)
									CB.FX.manageFavoriteEx(id, 'Add', function(response){
										if(response.MessageId == 0) img.src = img.src.replace('add-', 'remove-');
									});
								//if exercise is to be removed from favorite
								else
									CB.FX.manageFavoriteEx(id, 'Remove', function(response){
										if(response.MessageId == 0) img.src = img.src.replace('remove-', 'add-');
									});
							}
							else if(this.src.indexOf('add.png')>0){
								var ca = CB.UI.aexTo.attr('class').split(' ');
								CB.FX.addExToProgram({FixedProgramID:CB.selectedProgram.id, WorkOut:ca[0].replace('-', ' '), ExerciseTypeID:id}, function(response){
									if(response.MessageId == 0){
										var ne = response.NewExercise;
										ne.ExerciseDesc=ed.find('.desc div')[0].innerHTML;
										var pic = ed.find('img')[0].src;
										pic = pic.substr(pic.lastIndexOf('/'));
										ne.Image = {'ResourceName':pic};
										CB.UI.addExerciseToProgram(ne);
										img.src = 'images/icon/added.png';
										jQuery('.cat_det').sortable('refresh');
									}
								});
							}
						});
					}
					iss.enabled = true;
					elmsg.hide();
				}
				else{
					elmsg.html('No '+(pagenum == 1?'':'more')+' exercise found.');
					iss.enabled = false;
				}
			});
			CB.UI.currentPage++;
		},
		showExDetail: function(){
			var id = this.id;
			CB.FX.getExercise(id, function(response){
				if(response.MessageId == 0){
					var edc = jQuery('.ex-detail');
					jQuery('.ex-search').hide();
					edc.show();
					
					var exs = response.Exercises;
					var es;
					//if exercises is not an array we need to put in array to let same code work for both
					if(exs.length) es = exs;
					else{
						exs.ExerciseTypeID = id;
						es = [exs];
					}
					var tcd = edc.find('.tabs .clr');
					var tabName = es.length > 1 ? 'P<span>rogression</span>-1' : '<span>Exercise </span>Detail';
					for(var i=0, c=1; i<es.length; i++){
						var selClass = es[i].ExerciseTypeID == id?' sel':'';
						jQuery('<div>')
						.addClass('left tab'+selClass)
						.html(tabName)
						.click(CB.UI.switchEDTab)
						.attr('data', jQuery.toJSON({'id':es[i].ExerciseTypeID,'name':es[i].ExerciseDesc,'Image':es[i].Resources[0]}))
						.insertBefore(tcd);
						
						CB.UI.renderExercise(es[i], c, selClass);
						tabName = 'P<span>rogression</span>-'+(++c);
					}
				}
			});
		},
		getDLHTML: function(level){
			var dlIcons=[];
			for(var j=0;j<=level;j++) dlIcons.push('<img src="images/icon/blue_dot.png" width="8" />');
			return dlIcons.join(' ')+'&nbsp; '+CB.DifficultyLevels[level];
		},
		renderExercise: function(ex, index, selected){
			var edc = jQuery('<div>')
			.addClass('details tab-'+index)
			.append('<div class="detail_content">\
					<div class="left video"><div id="video'+index+'"><img src="images/video.png" width="100%" /></div></div>\
					<div class="left pics"></div>\
					<div class="clr"></div>\
				</div>\
				<div class="desc">\
					<div class="short">\
						<div class="left latt">\
							<strong>Difficulty Level: </strong>&nbsp; <span>'+CB.UI.getDLHTML(ex.DifficultyLevel)+'</span><br />\
							<strong>Muscles involved: </strong>&nbsp; <span>'+CB.getOVOA(ex.MuscleAreas, 'MuscleAreaDescription').join(', ')+'</span>\
						</div>\
						<div class="left ratt">\
							<strong>Categories: </strong>&nbsp; <span>'+CB.getOVOA(ex.Categories, 'ExerciseCategoryDescription').join(', ')+'</span><br />\
							<strong>Equipments: </strong>&nbsp; <span>'+CB.getOVOA(ex.Equipment, 'EquipmentName').join(', ')+'</span>\
						</div>\
						<div class="clr"></div>\
					</div>\
					<div class="explanation">'+ex.Explanation+'</div>\
				</div>')
			.insertBefore(jQuery('.ex-detail .act-btns'));
			if(!selected)
				edc.css('display', 'none')
			
			var resc = ex.Resources;
			var pd = edc.find('.pics');
			var vd = edc.find('.video');
			for(var i=0; i < resc.length; i++){
				if(resc[i].ResourceTypeCD == 'I')
					jQuery('<div>')
					.addClass('left')
					.append('<img src="'+CB.IMAGE_DIR_URL+resc[i].ResourceName+'" />')
					.appendTo(pd);
				else if(resc[i].ResourceTypeCD == 'Y')
					jwplayer("video"+index).setup({
						flashplayer: "jwplayer/player.swf",
						file: ex.ResourceName,
						width: vd.width(),
						height: vd.height()
					});
			}
			jQuery('<div>').addClass('clr').appendTo(pd);
			popup.autofit();
		},
		switchEDTab: function(){
			var a = jQuery(this);
			var pc = a.parents('.popup_content');
			pc.find('.tab').removeClass('sel');
			a.addClass('sel');
			pc.find('.details').hide();
			var di = a.html().indexOf('-');
			pc.find('.tab-'+(di > 0 ? a.html().substr(di+1) : 1)).show();
			popup.autofit();
		},
		backToSearch: function(){
			var pc = jQuery('.popup_content');
			var ed = pc.find('.ex-detail');
			ed.find('.details').remove();
			ed.find('.tab').remove();
			ed.find('.pics').html('');
			ed.hide();
			pc.find('.ex-search').show();
			jQuery(popup.win).css('height',100);
			popup.autofit();
		},
		programsLoaded: function(){
			jQuery('.cat_det').sortable({ stop: CB.UI.updateExPriorities });
		},
		//can be called from sortable jquery UI, or pass evt as false & ui as any element in the li
		updateExPriorities: function(evt, ui){
			var items = [], obj;
			if(evt) obj = ui.item;
			else{
				obj = ui;
				CB.UI.calculateAndUpdateProgramTime();
			}
			var obj = evt ? ui.item : ui;
			obj.parents('ul').find('li').each(function(key,exd){
				items.push(jQuery.parseJSON(jQuery(exd).attr('data')).id);
			});
			if(items.length)
				CB.FX.updateExPriorities(items.join(','));
		},
		calculateAndUpdateProgramTime: function(){
			var secs = 0;
			jQuery('#pt-accordion li').each(function(k,d){
				secs += parseInt(jQuery.parseJSON(jQuery(d).attr('data')).duration);
			});
			jQuery('#pt-time-estimate span').html(Math.ceil(secs/60)+' minutes');
		}
	},
	FX:{
		start:function(webKey){
			//not sure why webKey is being used
			if (webKey == undefined || webKey == null)
	            webKey = jQuery("#fm-webkey").val();
			
			// HF Account
			if (webKey == undefined || webKey == null)
            	webKey = "www.testwebkey.com";

			// Set platform webkey
			CB.platformWebKey = webKey;
			
			//load current user session
			CB.FX.loadUserSession();
			
			//start CB module depending upon current page
			CB.UI.start();
		},
		loadUserSession: function (){
			//API not provided to add/get current user and so using fixed values as used in API sample
			localStorage['CB.userEmail'] = 't';
			localStorage['CB.sessionGUID'] = '590EC926-0942-481E-B11D-3666A9FBF157';
        },
		loadProgramsList: function(){
			var url = CB.API_PATH + '/CustomPlan/GetUsersPlans?jsoncallback=?';
			CB.APICallback(url, {}, function(response){
				var plans = response.Plans;
				for(var i=0;i<plans.length;i++){
					CB.UI.addProgramInList(plans[i]);
				}
			});
		},
		createProgram: function(name, callback){
			var url = CB.API_PATH + '/CustomPlan/CreateProgram?jsoncallback=?';
			var data = { name:name, description:'', snippet:'' };
			CB.APICallback(url, data, callback);
		},
		loadProgram: function(id, callback){
			var url = CB.API_PATH + '/CustomPlan/GetUsersPlanDetail?jsoncallback=?';
			var data = { fixedProgramId: id };
			CB.APICallback(url, data, function(response){
				var exs = response.Exercises;
				if(exs){
					for(var i=0; i<exs.length;i++){
						CB.UI.addExerciseToProgram(exs[i]);
					}
					CB.UI.programsLoaded();
				}
			});
		},
		updateProgram: function(id, name, desc, callback){
			var url = CB.API_PATH + '/CustomPlan/UpdateProgram?jsoncallback=?';
			var data = { name:name, description:desc, snippet:'', fixedProgramId: id };
			CB.APICallback(url, data, callback);
		},
		removeExercise: function(id, callback){
			var url = CB.API_PATH + '/CustomPlan/RemoveExerciseFromProgram?jsoncallback=?';
			CB.APICallback(url, { fixedProgramExerciseID: id }, callback);
		},
		searchExercise: function(data, callback){
			var url = CB.API_PATH + '/CustomPlan/SearchExercises?jsoncallback=?';
			CB.APICallback(url, data, callback);
		},
		getExercise: function(id, callback){
			var url = CB.API_PATH + '/CustomPlan/GetExerciseProgressionsForExercise?jsoncallback=?';
			CB.APICallback(url, { exerciseTypeId: id }, callback);
		},
		addExToProgram: function(exercise, callback){
			CB.MapObject(CB.ProgramExerciseMap, exercise);
			var url = CB.API_PATH + '/CustomPlan/AddExerciseToProgram?jsoncallback=?';
			CB.APICallback(url, {jsonExercise:jQuery.toJSON(exercise)}, callback);
		},
		updateProgramEx: function(exercise, callback){
			CB.MapObject(CB.ProgramExerciseMap, exercise);
			var url = CB.API_PATH + '/CustomPlan/UpdateExercise?jsoncallback=?';
			CB.APICallback(url, {jsonExercise:jQuery.toJSON(exercise)}, callback);
		},
		manageFavoriteEx: function(id, act, callback){
			var url = CB.API_PATH + '/CustomPlan/' + act + 'FavExercise?jsoncallback=?';
			CB.APICallback(url, { exerciseTypeId: id }, callback);
		},
		updateExPriorities: function(ids){
			var url = CB.API_PATH + '/CustomPlan/UpdateExercisePriority?jsoncallback=?';
			CB.APICallback(url, { fixedProgramExerciseIDsInOrder: ids }, function(){});
		}
	},
	APICallback: function (url, data, responseFunc) {
        var extraData = {
            email: localStorage['CB.userEmail'],
            SessionGuid: localStorage['CB.sessionGUID']
        };
        jQuery.extend(data, extraData);
        jQuery.getJSON(url, data, responseFunc);
    },
	showErrorDiv: function(selector){
		jQuery(selector).show();
		setTimeout("jQuery('"+selector+"').hide()", 2000);
	},
	getProgramIDFromURL: function(){
		var id=0;
		try{
			var qs = location.search.substr(1).split('&'), id;
			if(qs[0].indexOf('id=')==0) id = qs[0].substr(3);
		}
		catch(e){}
		return id;
	},
	getOVOA: function(arr, key){
		var output=[];
		for(var i=0; i<arr.length;i++){
			output.push(arr[i][key]);
		}
		return output;
	},
	MapObject: function(source, target){
		for(var i in source) if(!target[i]) target[i]=source[i];
		//return target;
	},
	InfiniteScroller: function(obj, callback){
		_self = this;
		this.obj = obj;
		this.callback = callback;
		this.ticker = setInterval('_self.test()', 100);
		this.height = 265;
		this.enabled = true;
		
		this.test = function(){
			if(!this.enabled) return;
			var ib = this.obj.scrollTop();
			var xs = (this.obj[0].scrollHeight - this.height);
			if(ib > 0 && ib >= xs) this.callback(true);
		}
	}
};

//jQuery(document).ready(function () {
//	CB.FX.start();
//});

//jQuery(window).bind('load',function(){
//	var wid = jQuery(document).width();
//	if(wid < 720){
//		jQuery( "<style> .pt-popup .popup_content { width:"+(wid-24)+"px; margin:5px 3px 3px 3px; }</style>" ).appendTo( "head" );
//		CB.UI.popupParams.width = wid - 20;
//	}
//});
