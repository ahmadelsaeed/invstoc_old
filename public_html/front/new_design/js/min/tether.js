!function(t,e){"function"==typeof define&&define.amd?define(e):"object"==typeof exports?module.exports=e(require,exports,module):t.Tether=e()}(this,function(t,e,o){"use strict";function i(t,e){if(!(t instanceof e))throw new TypeError("Cannot call a class as a function")}function n(t){var e=t.getBoundingClientRect(),o={};for(var i in e)o[i]=e[i];if(t.ownerDocument!==document){var r=t.ownerDocument.defaultView.frameElement;if(r){var s=n(r);o.top+=s.top,o.bottom+=s.top,o.left+=s.left,o.right+=s.left}}return o}function r(t){var e=(getComputedStyle(t)||{}).position,o=[];if("fixed"===e)return[t];for(var i=t;(i=i.parentNode)&&i&&1===i.nodeType;){var n=void 0;try{n=getComputedStyle(i)}catch(t){}if(void 0===n||null===n)return o.push(i),o;var r=n,s=r.overflow,a=r.overflowX,l=r.overflowY;/(auto|scroll|overlay)/.test(s+l+a)&&("absolute"!==e||["relative","absolute","fixed"].indexOf(n.position)>=0)&&o.push(i)}return o.push(t.ownerDocument.body),t.ownerDocument!==document&&o.push(t.ownerDocument.defaultView),o}function s(){E&&document.body.removeChild(E),E=null}function a(t){var e=void 0;t===document?(e=document,t=document.documentElement):e=t.ownerDocument;var o=e.documentElement,i=n(t),r=T();return i.top-=r.top,i.left-=r.left,void 0===i.width&&(i.width=document.body.scrollWidth-i.left-i.right),void 0===i.height&&(i.height=document.body.scrollHeight-i.top-i.bottom),i.top=i.top-o.clientTop,i.left=i.left-o.clientLeft,i.right=e.body.clientWidth-i.width-i.left,i.bottom=e.body.clientHeight-i.height-i.top,i}function l(t){return t.offsetParent||document.documentElement}function h(){if(S)return S;var t=document.createElement("div");t.style.width="100%",t.style.height="200px";var e=document.createElement("div");f(e.style,{position:"absolute",top:0,left:0,pointerEvents:"none",visibility:"hidden",width:"200px",height:"150px",overflow:"hidden"}),e.appendChild(t),document.body.appendChild(e);var o=t.offsetWidth;e.style.overflow="scroll";var i=t.offsetWidth;o===i&&(i=e.clientWidth),document.body.removeChild(e);var n=o-i;return S={width:n,height:n}}function f(){var t=arguments.length<=0||void 0===arguments[0]?{}:arguments[0],e=[];return Array.prototype.push.apply(e,arguments),e.slice(1).forEach(function(e){if(e)for(var o in e)({}).hasOwnProperty.call(e,o)&&(t[o]=e[o])}),t}function d(t,e){if(void 0!==t.classList)e.split(" ").forEach(function(e){e.trim()&&t.classList.remove(e)});else{var o=new RegExp("(^| )"+e.split(" ").join("|")+"( |$)","gi"),i=c(t).replace(o," ");g(t,i)}}function p(t,e){if(void 0!==t.classList)e.split(" ").forEach(function(e){e.trim()&&t.classList.add(e)});else{d(t,e);var o=c(t)+" "+e;g(t,o)}}function u(t,e){if(void 0!==t.classList)return t.classList.contains(e);var o=c(t);return new RegExp("(^| )"+e+"( |$)","gi").test(o)}function c(t){return t.className instanceof t.ownerDocument.defaultView.SVGAnimatedString?t.className.baseVal:t.className}function g(t,e){t.setAttribute("class",e)}function m(t,e,o){o.forEach(function(o){-1===e.indexOf(o)&&u(t,o)&&d(t,o)}),e.forEach(function(e){u(t,e)||p(t,e)})}function i(t,e){if(!(t instanceof e))throw new TypeError("Cannot call a class as a function")}function v(t,e){var o=arguments.length<=2||void 0===arguments[2]?1:arguments[2];return t+o>=e&&e>=t-o}function b(){return performance&&performance.now?performance.now():+new Date}function y(){for(var t={top:0,left:0},e=arguments.length,o=Array(e),i=0;i<e;i++)o[i]=arguments[i];return o.forEach(function(e){var o=e.top,i=e.left;"string"==typeof o&&(o=parseFloat(o,10)),"string"==typeof i&&(i=parseFloat(i,10)),t.top+=o,t.left+=i}),t}function w(t,e){return"string"==typeof t.left&&-1!==t.left.indexOf("%")&&(t.left=parseFloat(t.left,10)/100*e.width),"string"==typeof t.top&&-1!==t.top.indexOf("%")&&(t.top=parseFloat(t.top,10)/100*e.height),t}var C=function(){function t(t,e){for(var o=0;o<e.length;o++){var i=e[o];i.enumerable=i.enumerable||!1,i.configurable=!0,"value"in i&&(i.writable=!0),Object.defineProperty(t,i.key,i)}}return function(e,o,i){return o&&t(e.prototype,o),i&&t(e,i),e}}(),O=void 0;void 0===O&&(O={modules:[]});var E=null,x=function(){var t=0;return function(){return++t}}(),A={},T=function(){var t=E;t&&document.body.contains(t)||((t=document.createElement("div")).setAttribute("data-tether-id",x()),f(t.style,{top:0,left:0,position:"absolute"}),document.body.appendChild(t),E=t);var e=t.getAttribute("data-tether-id");return void 0===A[e]&&(A[e]=n(t),M(function(){delete A[e]})),A[e]},S=null,P=[],M=function(t){P.push(t)},W=function(){for(var t=void 0;t=P.pop();)t()},k=function(){function t(){i(this,t)}return C(t,[{key:"on",value:function(t,e,o){var i=!(arguments.length<=3||void 0===arguments[3])&&arguments[3];void 0===this.bindings&&(this.bindings={}),void 0===this.bindings[t]&&(this.bindings[t]=[]),this.bindings[t].push({handler:e,ctx:o,once:i})}},{key:"once",value:function(t,e,o){this.on(t,e,o,!0)}},{key:"off",value:function(t,e){if(void 0!==this.bindings&&void 0!==this.bindings[t])if(void 0===e)delete this.bindings[t];else for(var o=0;o<this.bindings[t].length;)this.bindings[t][o].handler===e?this.bindings[t].splice(o,1):++o}},{key:"trigger",value:function(t){if(void 0!==this.bindings&&this.bindings[t]){for(var e=0,o=arguments.length,i=Array(o>1?o-1:0),n=1;n<o;n++)i[n-1]=arguments[n];for(;e<this.bindings[t].length;){var r=this.bindings[t][e],s=r.handler,a=r.ctx,l=r.once,h=a;void 0===h&&(h=this),s.apply(h,i),l?this.bindings[t].splice(e,1):++e}}}}]),t}();O.Utils={getActualBoundingClientRect:n,getScrollParents:r,getBounds:a,getOffsetParent:l,extend:f,addClass:p,removeClass:d,hasClass:u,updateClasses:m,defer:M,flush:W,uniqueId:x,Evented:k,getScrollBarSize:h,removeUtilElements:s};var _=function(){return function(t,e){if(Array.isArray(t))return t;if(Symbol.iterator in Object(t))return function(t,e){var o=[],i=!0,n=!1,r=void 0;try{for(var s,a=t[Symbol.iterator]();!(i=(s=a.next()).done)&&(o.push(s.value),!e||o.length!==e);i=!0);}catch(t){n=!0,r=t}finally{try{!i&&a.return&&a.return()}finally{if(n)throw r}}return o}(t,e);throw new TypeError("Invalid attempt to destructure non-iterable instance")}}(),C=function(){function t(t,e){for(var o=0;o<e.length;o++){var i=e[o];i.enumerable=i.enumerable||!1,i.configurable=!0,"value"in i&&(i.writable=!0),Object.defineProperty(t,i.key,i)}}return function(e,o,i){return o&&t(e.prototype,o),i&&t(e,i),e}}(),B=function(t,e,o){for(var i=!0;i;){var n=t,r=e,s=o;i=!1,null===n&&(n=Function.prototype);var a=Object.getOwnPropertyDescriptor(n,r);if(void 0!==a){if("value"in a)return a.value;var l=a.get;if(void 0===l)return;return l.call(s)}var h=Object.getPrototypeOf(n);if(null===h)return;t=h,e=r,o=s,i=!0,a=h=void 0}};if(void 0===O)throw new Error("You must include the utils.js file before tether.js");var z=O.Utils,r=z.getScrollParents,a=z.getBounds,l=z.getOffsetParent,f=z.extend,p=z.addClass,d=z.removeClass,m=z.updateClasses,M=z.defer,W=z.flush,h=z.getScrollBarSize,s=z.removeUtilElements,j=function(){if("undefined"==typeof document)return"";for(var t=document.createElement("div"),e=["transform","WebkitTransform","OTransform","MozTransform","msTransform"],o=0;o<e.length;++o){var i=e[o];if(void 0!==t.style[i])return i}}(),Y=[],L=function(){Y.forEach(function(t){t.position(!1)}),W()};!function(){var t=null,e=null,o=null,i=function i(){if(void 0!==e&&e>16)return e=Math.min(e-16,250),void(o=setTimeout(i,250));void 0!==t&&b()-t<10||(null!=o&&(clearTimeout(o),o=null),t=b(),L(),e=b()-t)};"undefined"!=typeof window&&void 0!==window.addEventListener&&["resize","scroll","touchmove"].forEach(function(t){window.addEventListener(t,i)})}();var D={center:"center",left:"right",right:"left"},X={middle:"middle",top:"bottom",bottom:"top"},F={top:0,left:0,middle:"50%",center:"50%",bottom:"100%",right:"100%"},H=function(t){var e=t.left,o=t.top;return void 0!==F[t.left]&&(e=F[t.left]),void 0!==F[t.top]&&(o=F[t.top]),{left:e,top:o}},N=function(t){var e=t.split(" "),o=_(e,2);return{top:o[0],left:o[1]}},U=N,V=function(t){function e(t){var o=this;i(this,e),B(Object.getPrototypeOf(e.prototype),"constructor",this).call(this),this.position=this.position.bind(this),Y.push(this),this.history=[],this.setOptions(t,!1),O.modules.forEach(function(t){void 0!==t.initialize&&t.initialize.call(o)}),this.position()}return function(t,e){if("function"!=typeof e&&null!==e)throw new TypeError("Super expression must either be null or a function, not "+typeof e);t.prototype=Object.create(e&&e.prototype,{constructor:{value:t,enumerable:!1,writable:!0,configurable:!0}}),e&&(Object.setPrototypeOf?Object.setPrototypeOf(t,e):t.__proto__=e)}(e,k),C(e,[{key:"getClass",value:function(){var t=arguments.length<=0||void 0===arguments[0]?"":arguments[0],e=this.options.classes;return void 0!==e&&e[t]?this.options.classes[t]:this.options.classPrefix?this.options.classPrefix+"-"+t:t}},{key:"setOptions",value:function(t){var e=this,o=arguments.length<=1||void 0===arguments[1]||arguments[1];this.options=f({offset:"0 0",targetOffset:"0 0",targetAttachment:"auto auto",classPrefix:"tether"},t);var i=this.options,n=i.element,s=i.target,a=i.targetModifier;if(this.element=n,this.target=s,this.targetModifier=a,"viewport"===this.target?(this.target=document.body,this.targetModifier="visible"):"scroll-handle"===this.target&&(this.target=document.body,this.targetModifier="scroll-handle"),["element","target"].forEach(function(t){if(void 0===e[t])throw new Error("Tether Error: Both element and target must be defined");void 0!==e[t].jquery?e[t]=e[t][0]:"string"==typeof e[t]&&(e[t]=document.querySelector(e[t]))}),p(this.element,this.getClass("element")),!1!==this.options.addTargetClasses&&p(this.target,this.getClass("target")),!this.options.attachment)throw new Error("Tether Error: You must provide an attachment");this.targetAttachment=U(this.options.targetAttachment),this.attachment=U(this.options.attachment),this.offset=N(this.options.offset),this.targetOffset=N(this.options.targetOffset),void 0!==this.scrollParents&&this.disable(),"scroll-handle"===this.targetModifier?this.scrollParents=[this.target]:this.scrollParents=r(this.target),!1!==this.options.enabled&&this.enable(o)}},{key:"getTargetBounds",value:function(){if(void 0===this.targetModifier)return a(this.target);if("visible"===this.targetModifier){if(this.target===document.body)return{top:pageYOffset,left:pageXOffset,height:innerHeight,width:innerWidth};return(r={height:(t=a(this.target)).height,width:t.width,top:t.top,left:t.left}).height=Math.min(r.height,t.height-(pageYOffset-t.top)),r.height=Math.min(r.height,t.height-(t.top+t.height-(pageYOffset+innerHeight))),r.height=Math.min(innerHeight,r.height),r.height-=2,r.width=Math.min(r.width,t.width-(pageXOffset-t.left)),r.width=Math.min(r.width,t.width-(t.left+t.width-(pageXOffset+innerWidth))),r.width=Math.min(innerWidth,r.width),r.width-=2,r.top<pageYOffset&&(r.top=pageYOffset),r.left<pageXOffset&&(r.left=pageXOffset),r}if("scroll-handle"===this.targetModifier){var t=void 0,e=this.target;e===document.body?(e=document.documentElement,t={left:pageXOffset,top:pageYOffset,height:innerHeight,width:innerWidth}):t=a(e);var o=getComputedStyle(e),i=0;(e.scrollWidth>e.clientWidth||[o.overflow,o.overflowX].indexOf("scroll")>=0||this.target!==document.body)&&(i=15);var n=t.height-parseFloat(o.borderTopWidth)-parseFloat(o.borderBottomWidth)-i,r={width:15,height:.975*n*(n/e.scrollHeight),left:t.left+t.width-parseFloat(o.borderLeftWidth)-15},s=0;n<408&&this.target===document.body&&(s=-11e-5*Math.pow(n,2)-.00727*n+22.58),this.target!==document.body&&(r.height=Math.max(r.height,24));var l=this.target.scrollTop/(e.scrollHeight-n);return r.top=l*(n-r.height-s)+t.top+parseFloat(o.borderTopWidth),this.target===document.body&&(r.height=Math.max(r.height,24)),r}}},{key:"clearCache",value:function(){this._cache={}}},{key:"cache",value:function(t,e){return void 0===this._cache&&(this._cache={}),void 0===this._cache[t]&&(this._cache[t]=e.call(this)),this._cache[t]}},{key:"enable",value:function(){var t=this,e=arguments.length<=0||void 0===arguments[0]||arguments[0];!1!==this.options.addTargetClasses&&p(this.target,this.getClass("enabled")),p(this.element,this.getClass("enabled")),this.enabled=!0,this.scrollParents.forEach(function(e){e!==t.target.ownerDocument&&e.addEventListener("scroll",t.position)}),e&&this.position()}},{key:"disable",value:function(){var t=this;d(this.target,this.getClass("enabled")),d(this.element,this.getClass("enabled")),this.enabled=!1,void 0!==this.scrollParents&&this.scrollParents.forEach(function(e){e.removeEventListener("scroll",t.position)})}},{key:"destroy",value:function(){var t=this;this.disable(),Y.forEach(function(e,o){e===t&&Y.splice(o,1)}),0===Y.length&&s()}},{key:"updateAttachClasses",value:function(t,e){var o=this;t=t||this.attachment,e=e||this.targetAttachment;void 0!==this._addAttachClasses&&this._addAttachClasses.length&&this._addAttachClasses.splice(0,this._addAttachClasses.length),void 0===this._addAttachClasses&&(this._addAttachClasses=[]);var i=this._addAttachClasses;t.top&&i.push(this.getClass("element-attached")+"-"+t.top),t.left&&i.push(this.getClass("element-attached")+"-"+t.left),e.top&&i.push(this.getClass("target-attached")+"-"+e.top),e.left&&i.push(this.getClass("target-attached")+"-"+e.left);var n=[];["left","top","bottom","right","middle","center"].forEach(function(t){n.push(o.getClass("element-attached")+"-"+t),n.push(o.getClass("target-attached")+"-"+t)}),M(function(){void 0!==o._addAttachClasses&&(m(o.element,o._addAttachClasses,n),!1!==o.options.addTargetClasses&&m(o.target,o._addAttachClasses,n),delete o._addAttachClasses)})}},{key:"position",value:function(){var t=this,e=arguments.length<=0||void 0===arguments[0]||arguments[0];if(this.enabled){this.clearCache();var o=function(t,e){var o=t.left,i=t.top;return"auto"===o&&(o=D[e.left]),"auto"===i&&(i=X[e.top]),{left:o,top:i}}(this.targetAttachment,this.attachment);this.updateAttachClasses(this.attachment,o);var i=this.cache("element-bounds",function(){return a(t.element)}),n=i.width,r=i.height;if(0===n&&0===r&&void 0!==this.lastSize){var s=this.lastSize;n=s.width,r=s.height}else this.lastSize={width:n,height:r};var f=this.cache("target-bounds",function(){return t.getTargetBounds()}),d=f,p=w(H(this.attachment),{width:n,height:r}),u=w(H(o),d),c=w(this.offset,{width:n,height:r}),g=w(this.targetOffset,d);p=y(p,c),u=y(u,g);for(var m=f.left+u.left-p.left,v=f.top+u.top-p.top,b=0;b<O.modules.length;++b){var C=O.modules[b].position.call(this,{left:m,top:v,targetAttachment:o,targetPos:f,elementPos:i,offset:p,targetOffset:u,manualOffset:c,manualTargetOffset:g,scrollbarSize:T,attachment:this.attachment});if(!1===C)return!1;void 0!==C&&"object"==typeof C&&(v=C.top,m=C.left)}var E={page:{top:v,left:m},viewport:{top:v-pageYOffset,bottom:pageYOffset-v-r+innerHeight,left:m-pageXOffset,right:pageXOffset-m-n+innerWidth}},x=this.target.ownerDocument,A=x.defaultView,T=void 0;return A.innerHeight>x.documentElement.clientHeight&&(T=this.cache("scrollbar-size",h),E.viewport.bottom-=T.height),A.innerWidth>x.documentElement.clientWidth&&(T=this.cache("scrollbar-size",h),E.viewport.right-=T.width),-1!==["","static"].indexOf(x.body.style.position)&&-1!==["","static"].indexOf(x.body.parentElement.style.position)||(E.page.bottom=x.body.scrollHeight-v-r,E.page.right=x.body.scrollWidth-m-n),void 0!==this.options.optimizations&&!1!==this.options.optimizations.moveElement&&void 0===this.targetModifier&&function(){var e=t.cache("target-offsetparent",function(){return l(t.target)}),o=t.cache("target-offsetparent-bounds",function(){return a(e)}),i=getComputedStyle(e),n=o,r={};if(["Top","Left","Bottom","Right"].forEach(function(t){r[t.toLowerCase()]=parseFloat(i["border"+t+"Width"])}),o.right=x.body.scrollWidth-o.left-n.width+r.right,o.bottom=x.body.scrollHeight-o.top-n.height+r.bottom,E.page.top>=o.top+r.top&&E.page.bottom>=o.bottom&&E.page.left>=o.left+r.left&&E.page.right>=o.right){var s=e.scrollTop,h=e.scrollLeft;E.offset={top:E.page.top-o.top+s-r.top,left:E.page.left-o.left+h-r.left}}}(),this.move(E),this.history.unshift(E),this.history.length>3&&this.history.pop(),e&&W(),!0}}},{key:"move",value:function(t){var e=this;if(void 0!==this.element.parentNode){var o={};for(var i in t){o[i]={};for(var n in t[i]){for(var r=!1,s=0;s<this.history.length;++s){var a=this.history[s];if(void 0!==a[i]&&!v(a[i][n],t[i][n])){r=!0;break}}r||(o[i][n]=!0)}}var h={top:"",left:"",right:"",bottom:""},d=function(t,o){if(!1!==(void 0!==e.options.optimizations?e.options.optimizations.gpu:null)){var i=void 0,n=void 0;if(t.top?(h.top=0,i=o.top):(h.bottom=0,i=-o.bottom),t.left?(h.left=0,n=o.left):(h.right=0,n=-o.right),window.matchMedia){window.matchMedia("only screen and (min-resolution: 1.3dppx)").matches||window.matchMedia("only screen and (-webkit-min-device-pixel-ratio: 1.3)").matches||(n=Math.round(n),i=Math.round(i))}h[j]="translateX("+n+"px) translateY("+i+"px)","msTransform"!==j&&(h[j]+=" translateZ(0)")}else t.top?h.top=o.top+"px":h.bottom=o.bottom+"px",t.left?h.left=o.left+"px":h.right=o.right+"px"},p=!1;if((o.page.top||o.page.bottom)&&(o.page.left||o.page.right)?(h.position="absolute",d(o.page,t.page)):(o.viewport.top||o.viewport.bottom)&&(o.viewport.left||o.viewport.right)?(h.position="fixed",d(o.viewport,t.viewport)):void 0!==o.offset&&o.offset.top&&o.offset.left?function(){h.position="absolute";var i=e.cache("target-offsetparent",function(){return l(e.target)});l(e.element)!==i&&M(function(){e.element.parentNode.removeChild(e.element),i.appendChild(e.element)}),d(o.offset,t.offset),p=!0}():(h.position="absolute",d({top:!0,left:!0},t.page)),!p)if(this.options.bodyElement)this.element.parentNode!==this.options.bodyElement&&this.options.bodyElement.appendChild(this.element);else{for(var u=!0,c=this.element.parentNode;c&&1===c.nodeType&&"BODY"!==c.tagName;){if("static"!==getComputedStyle(c).position){u=!1;break}c=c.parentNode}u||(this.element.parentNode.removeChild(this.element),this.element.ownerDocument.body.appendChild(this.element))}var g={},m=!1;for(var n in h){var b=h[n];this.element.style[n]!==b&&(m=!0,g[n]=b)}m&&M(function(){f(e.element.style,g),e.trigger("repositioned")})}}}]),e}();V.modules=[],O.position=L;var R=f(V,O),_=function(){return function(t,e){if(Array.isArray(t))return t;if(Symbol.iterator in Object(t))return function(t,e){var o=[],i=!0,n=!1,r=void 0;try{for(var s,a=t[Symbol.iterator]();!(i=(s=a.next()).done)&&(o.push(s.value),!e||o.length!==e);i=!0);}catch(t){n=!0,r=t}finally{try{!i&&a.return&&a.return()}finally{if(n)throw r}}return o}(t,e);throw new TypeError("Invalid attempt to destructure non-iterable instance")}}(),a=(z=O.Utils).getBounds,f=z.extend,m=z.updateClasses,M=z.defer,q=["left","top","right","bottom"];O.modules.push({position:function(t){var e=this,o=t.top,i=t.left,n=t.targetAttachment;if(!this.options.constraints)return!0;var r=this.cache("element-bounds",function(){return a(e.element)}),s=r.height,l=r.width;if(0===l&&0===s&&void 0!==this.lastSize){var h=this.lastSize;l=h.width,s=h.height}var d=this.cache("target-bounds",function(){return e.getTargetBounds()}),p=d.height,u=d.width,c=[this.getClass("pinned"),this.getClass("out-of-bounds")];this.options.constraints.forEach(function(t){var e=t.outOfBoundsClass,o=t.pinnedClass;e&&c.push(e),o&&c.push(o)}),c.forEach(function(t){["left","top","right","bottom"].forEach(function(e){c.push(t+"-"+e)})});var g=[],v=f({},n),b=f({},this.attachment);return this.options.constraints.forEach(function(t){var r=t.to,h=t.attachment,f=t.pin;void 0===h&&(h="");var d=void 0,c=void 0;if(h.indexOf(" ")>=0){var m=h.split(" "),y=_(m,2);c=y[0],d=y[1]}else d=c=h;var w=function(t,e){return"scrollParent"===e?e=t.scrollParents[0]:"window"===e&&(e=[pageXOffset,pageYOffset,innerWidth+pageXOffset,innerHeight+pageYOffset]),e===document&&(e=e.documentElement),void 0!==e.nodeType&&function(){var t=e,o=a(e),i=o,n=getComputedStyle(e);if(e=[i.left,i.top,o.width+i.left,o.height+i.top],t.ownerDocument!==document){var r=t.ownerDocument.defaultView;e[0]+=r.pageXOffset,e[1]+=r.pageYOffset,e[2]+=r.pageXOffset,e[3]+=r.pageYOffset}q.forEach(function(t,o){"Top"===(t=t[0].toUpperCase()+t.substr(1))||"Left"===t?e[o]+=parseFloat(n["border"+t+"Width"]):e[o]-=parseFloat(n["border"+t+"Width"])})}(),e}(e,r);"target"!==c&&"both"!==c||(o<w[1]&&"top"===v.top&&(o+=p,v.top="bottom"),o+s>w[3]&&"bottom"===v.top&&(o-=p,v.top="top")),"together"===c&&("top"===v.top&&("bottom"===b.top&&o<w[1]?(o+=p,v.top="bottom",o+=s,b.top="top"):"top"===b.top&&o+s>w[3]&&o-(s-p)>=w[1]&&(o-=s-p,v.top="bottom",b.top="bottom")),"bottom"===v.top&&("top"===b.top&&o+s>w[3]?(o-=p,v.top="top",o-=s,b.top="bottom"):"bottom"===b.top&&o<w[1]&&o+(2*s-p)<=w[3]&&(o+=s-p,v.top="top",b.top="top")),"middle"===v.top&&(o+s>w[3]&&"top"===b.top?(o-=s,b.top="bottom"):o<w[1]&&"bottom"===b.top&&(o+=s,b.top="top"))),"target"!==d&&"both"!==d||(i<w[0]&&"left"===v.left&&(i+=u,v.left="right"),i+l>w[2]&&"right"===v.left&&(i-=u,v.left="left")),"together"===d&&(i<w[0]&&"left"===v.left?"right"===b.left?(i+=u,v.left="right",i+=l,b.left="left"):"left"===b.left&&(i+=u,v.left="right",i-=l,b.left="right"):i+l>w[2]&&"right"===v.left?"left"===b.left?(i-=u,v.left="left",i-=l,b.left="right"):"right"===b.left&&(i-=u,v.left="left",i+=l,b.left="left"):"center"===v.left&&(i+l>w[2]&&"left"===b.left?(i-=l,b.left="right"):i<w[0]&&"right"===b.left&&(i+=l,b.left="left"))),"element"!==c&&"both"!==c||(o<w[1]&&"bottom"===b.top&&(o+=s,b.top="top"),o+s>w[3]&&"top"===b.top&&(o-=s,b.top="bottom")),"element"!==d&&"both"!==d||(i<w[0]&&("right"===b.left?(i+=l,b.left="left"):"center"===b.left&&(i+=l/2,b.left="left")),i+l>w[2]&&("left"===b.left?(i-=l,b.left="right"):"center"===b.left&&(i-=l/2,b.left="right"))),"string"==typeof f?f=f.split(",").map(function(t){return t.trim()}):!0===f&&(f=["top","left","right","bottom"]),f=f||[];var C=[],O=[];o<w[1]&&(f.indexOf("top")>=0?(o=w[1],C.push("top")):O.push("top")),o+s>w[3]&&(f.indexOf("bottom")>=0?(o=w[3]-s,C.push("bottom")):O.push("bottom")),i<w[0]&&(f.indexOf("left")>=0?(i=w[0],C.push("left")):O.push("left")),i+l>w[2]&&(f.indexOf("right")>=0?(i=w[2]-l,C.push("right")):O.push("right")),C.length&&function(){var t=void 0;t=void 0!==e.options.pinnedClass?e.options.pinnedClass:e.getClass("pinned"),g.push(t),C.forEach(function(e){g.push(t+"-"+e)})}(),O.length&&function(){var t=void 0;t=void 0!==e.options.outOfBoundsClass?e.options.outOfBoundsClass:e.getClass("out-of-bounds"),g.push(t),O.forEach(function(e){g.push(t+"-"+e)})}(),(C.indexOf("left")>=0||C.indexOf("right")>=0)&&(b.left=v.left=!1),(C.indexOf("top")>=0||C.indexOf("bottom")>=0)&&(b.top=v.top=!1),v.top===n.top&&v.left===n.left&&b.top===e.attachment.top&&b.left===e.attachment.left||(e.updateAttachClasses(b,v),e.trigger("update",{attachment:b,targetAttachment:v}))}),M(function(){!1!==e.options.addTargetClasses&&m(e.target,g,c),m(e.element,g,c)}),{top:o,left:i}}});var a=(z=O.Utils).getBounds,m=z.updateClasses,M=z.defer;O.modules.push({position:function(t){var e=this,o=t.top,i=t.left,n=this.cache("element-bounds",function(){return a(e.element)}),r=n.height,s=n.width,l=this.getTargetBounds(),h=o+r,f=i+s,d=[];o<=l.bottom&&h>=l.top&&["left","right"].forEach(function(t){var e=l[t];e!==i&&e!==f||d.push(t)}),i<=l.right&&f>=l.left&&["top","bottom"].forEach(function(t){var e=l[t];e!==o&&e!==h||d.push(t)});var p=[],u=[];return p.push(this.getClass("abutted")),["left","top","right","bottom"].forEach(function(t){p.push(e.getClass("abutted")+"-"+t)}),d.length&&u.push(this.getClass("abutted")),d.forEach(function(t){u.push(e.getClass("abutted")+"-"+t)}),M(function(){!1!==e.options.addTargetClasses&&m(e.target,u,p),m(e.element,u,p)}),!0}});_=function(){return function(t,e){if(Array.isArray(t))return t;if(Symbol.iterator in Object(t))return function(t,e){var o=[],i=!0,n=!1,r=void 0;try{for(var s,a=t[Symbol.iterator]();!(i=(s=a.next()).done)&&(o.push(s.value),!e||o.length!==e);i=!0);}catch(t){n=!0,r=t}finally{try{!i&&a.return&&a.return()}finally{if(n)throw r}}return o}(t,e);throw new TypeError("Invalid attempt to destructure non-iterable instance")}}();return O.modules.push({position:function(t){var e=t.top,o=t.left;if(this.options.shift){var i=this.options.shift;"function"==typeof this.options.shift&&(i=this.options.shift.call(this,{top:e,left:o}));var n=void 0,r=void 0;if("string"==typeof i){(i=i.split(" "))[1]=i[1]||i[0];var s=_(i,2);n=s[0],r=s[1],n=parseFloat(n,10),r=parseFloat(r,10)}else n=i.top,r=i.left;return e+=n,o+=r,{top:e,left:o}}}}),R});