var popup = {
	win:null, counter:0, template:null, sx:0, sy:0, dir:'', ox:0, oy:0, resizing:false, dragging:false, mxw:0, mxh:0, wins:new Array(), zi:0, maxWins:10, adw:0, adh:0, a:null, b:null, bo:'none', onresize:null, timer:null,
	initialize : function(e){
		var ua = navigator.userAgent;
		if(document.all && ua.indexOf('Opera') == -1){
			popup.mxw = document.documentElement.clientWidth;
			popup.mxh = document.documentElement.clientHeight;
		}
		else{
			popup.mxw = window.innerWidth;
			popup.mxh = window.innerHeight;
		}
		//if(this.mxw <= 480) this.mxw-=20;
		popup.adw = popup.adh = 0;
		window.onresize = popup.initialize;
	},

	show:function(content, config){
		if(!(this.mxw || this.mxh)) this.initialize();
		
		if(this.counter >= this.maxWins){
			alert("Error : Max popups limit exceeded");
			return;
		}
		else if(config && config.id && document.getElementById(config.id)){
			alert("Error : Duplicate popup id");
			return;
		}

		
		if(content.indexOf(' ') == -1 && content.indexOf('<') == -1 && content.indexOf('>') == -1){
			try{
				jQuery.get(content, function(data){
					popup.win.innerHTML = data;
					if(typeof(config.onload) == "function"){
						config.onload(popup.win);
					}
					popup.autofit();
					popup.timer = setInterval('popup.autofit()', 500);
				});
			}
			catch(e){
				alert('JQuery extension is required to load content from another file!'); return;
			}
			content = config && config.preloader?config.preloader:"<div style='padding:10px;'>loading...</div>";
		}
		
		if(config && config.modal){
			var lr1 = document.createElement("div");
			lr1.id = "popup_modal_layer";
			with(lr1.style){
				position = "absolute";
				left = top = "0px";
				width = "100%";
				height = (popup.mxh < document.body.scrollHeight ? document.body.scrollHeight : popup.mxh)+"px";
			}
			document.body.appendChild(lr1);
		}
		
		this.win = document.createElement('div');
		this.win.innerHTML = content;
		this.win.className = "builder-popup";
		if(config && config.className) this.win.className += " "+config.className;
		if(config && config.id) this.win.id = config.id;
		if(config && typeof(config.onclose) == "function") this.win.onclose = config.onclose;
		jQuery(jQuery('.pg_width')[1]).append(this.win);
		
		if(!(config && config.width)) config.width = this.win.offsetWidth;
		if(!(config && config.height)) config.height = this.win.offsetHeight;
		
		var scrollTop = window.pageYOffset;
		if(!scrollTop) scrollTop = document.documentElement.scrollTop;
		with(this.win.style){
			position = "absolute";
			left = (this.mxw - config.width) / 2 + "px";
			top = ((this.mxh - config.height) / 2 + scrollTop) + "px";
			zIndex = ++this.zi;
		}
		
		if(config && config.width) this.win.style.width = config.width + "px";
		if(config && config.height)	this.win.style.height = config.height + "px";
		
		this.counter++;
		this.win.onmousedown = function(e){
			if(document.all) e = event;
			popup.win = this;
			if((document.all?e.srcElement:e.target).className == "title_bar"){
				if(!this.getAttribute('s')){
					popup.dragging = true;
					popup.ox = parseInt(this.style.left);
					popup.oy = parseInt(this.style.top);
					if(isNaN(popup.ox) || isNaN(popup.oy)){
						popup.ox = this.offsetLeft;
						popup.oy = this.offsetTop;
					}
					popup.sx = e.clientX;
					popup.sy = e.clientY;
				}
	//			if (e.preventDefault) e.preventDefault();
			}
		}
		return this.win;
	},
	
	getWindow : function(obj){
		while(obj.tagName != "body"){
			if(obj.className.indexOf("popup")==0) return obj;
			else obj = obj.parentNode;
		}
	},
	
	close : function(obj){
		obj = obj?popup.getWindow(obj):popup.win;
		try{ obj.onclose(obj) }
		catch(e){}
		var x = obj.parentNode.removeChild(obj);
		popup.counter--;
		var ml = document.getElementById('popup_modal_layer');
		if(ml) document.body.removeChild(ml);
		clearInterval(popup.timer);
		return x;
	},
    autofit: function () {
		var win = jQuery(popup.win);
		var xh = popup.win.scrollHeight;
		if(win.attr('height') != xh){
	        var scrollTop = window.pageYOffset;
    	    if (!scrollTop) scrollTop = document.documentElement.scrollTop;
        	popup.win.style.overflow = 'hidden';
        	var xw = popup.win.scrollWidth;
			var tp = ((popup.mxh - xh) / 2 + scrollTop);
			if(tp < 0) tp=20;
        	with(popup.win.style) {
				height = xh + 'px';
				top =  tp+ "px";
        	}
        	
		    // var ml=$('#popup_modal_layer')[0];
        	// var ml = document.getElementById('popup_modal_layer');
		    var ml=jQuery('#popup_modal_layer')[0];
			if(xh > ml.offsetHeight)ml.style.height=(xh+30)+'px';
			win.attr('height', xh);
        }
    }
};
document.onmouseup = function(){ popup.dragging=false; popup.resizing = false; };
document.onmousemove = function(e){
	if(document.all) e = event;
	if(popup.dragging){
		popup.win.style.left = (popup.ox + e.clientX - popup.sx) + "px";
		popup.win.style.top = (popup.oy + e.clientY - popup.sy) + "px";
		return false;
	}
}