!function(){"use strict";angular.module("angularjs-autogrow",[]).directive("autogrow",["$window",function(t){return{link:function(o,e,r){function n(t,e){t!==e&&o.autogrowFn()}o.attrs={rows:1,maxLines:999};for(var a in o.attrs)r[a]&&(o.attrs[a]=parseInt(r[a]));o.getOffset=function(){for(var o=t.getComputedStyle(e[0],null),r=["paddingTop","paddingBottom"],n=0,a=0;a<r.length;a++)n+=parseInt(o[r[a]]);return n},o.autogrowFn=function(){var t=0,r=!1;return e[0].scrollHeight-o.offset>o.maxAllowedHeight?(e[0].style.overflowY="scroll",t=o.maxAllowedHeight):(e[0].style.overflowY="hidden",e[0].style.height="auto",t=e[0].scrollHeight-o.offset,r=!0),e[0].style.height=t+"px",r},o.offset=o.getOffset(),o.lineHeight=e[0].scrollHeight/o.attrs.rows-o.offset/o.attrs.rows,o.maxAllowedHeight=o.lineHeight*o.attrs.maxLines-o.offset,o.$watch(r.ngModel,o.autogrowFn);var l=r.autogrow?r.autogrow.split(","):[];angular.forEach(l,function(t){o.$watch(function(){return e.css(t)},n)}),""!=e[0].value&&o.autogrowFn()}}}])}();