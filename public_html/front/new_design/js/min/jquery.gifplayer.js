!function(e){"object"==typeof module&&"object"==typeof module.exports?module.exports=e(require("jquery")):e(jQuery)}(function(e){function t(e,t){this.previewElement=e,this.options=t,this.animationLoaded=!1}return t.scopes=new Array,t.prototype={supportedFormats:["gif","jpeg","jpg","png"],activate:function(){var e=this;0===this.previewElement.width()?setTimeout(function(){e.activate()},100):(e.mode=e.getOption("mode"),e.wrap(),e.addSpinner(),e.addControl(),e.addEvents())},wrap:function(){this.previewElement.addClass("gifplayer-ready"),this.wrapper=this.previewElement.wrap("<div class='gifplayer-wrapper'></div>").parent(),this.wrapper.css("width",this.previewElement.width()),this.wrapper.css("height",this.previewElement.height()),this.previewElement.css("cursor","pointer")},addSpinner:function(){this.spinnerElement=e("<div class = 'spinner'></div>"),this.wrapper.append(this.spinnerElement),this.spinnerElement.hide()},getOption:function(e){var t=this.previewElement.data(e.toLowerCase());return void 0!=t&&""!=t?t:this.options[e]},addControl:function(){var t=this.getOption("label");this.playElement=e("<ins class='play-gif'>"+t+"</ins>"),this.wrapper.append(this.playElement),this.playElement.css("top",this.previewElement.height()/2-this.playElement.height()/2),this.playElement.css("left",this.previewElement.width()/2-this.playElement.width()/2)},addEvents:function(){var e=this,t=this.getOption("playOn");switch(t){case"click":e.playElement.on("click",function(t){e.previewElement.trigger("click")}),e.previewElement.on("click",function(t){e.loadAnimation(),t.preventDefault(),t.stopPropagation()});break;case"hover":e.previewElement.on("click mouseover",function(t){e.loadAnimation(),t.preventDefault(),t.stopPropagation()});break;case"auto":console.log("auto not implemented yet");break;default:console.log(t+" is not accepted as playOn value.")}},processScope:function(){scope=this.getOption("scope"),scope&&(t.scopes[scope]&&t.scopes[scope].stopGif(),t.scopes[scope]=this)},loadAnimation:function(){this.processScope(),this.spinnerElement.show(),"gif"==this.mode?this.loadGif():"video"==this.mode&&(this.videoLoaded?this.playVideo():this.loadVideo()),this.getOption("onPlay").call(this.previewElement)},stopGif:function(){this.gifElement.hide(),this.previewElement.show(),this.playElement.show(),this.resetEvents(),this.getOption("onStop").call(this.previewElement)},getFile:function(e){var t=this.getOption(e);if(void 0!=t&&""!=t)return t;for(replaceString=this.previewElement.attr("src"),i=0;i<this.supportedFormats.length;i++)pattrn=new RegExp(this.supportedFormats[i]+"$","i"),replaceString=replaceString.replace(pattrn,e);return replaceString},loadGif:function(){var t=this;t.playElement.hide(),this.animationLoaded||this.enableAbort();var i=this.getFile("gif"),n=this.previewElement.width(),o=this.previewElement.height();this.gifElement=e("<img class='gp-gif-element' width='"+n+"' height=' "+o+" '/>");this.getOption("wait")?this.gifElement.load(function(){t.animationLoaded=!0,t.resetEvents(),t.previewElement.hide(),t.wrapper.append(t.gifElement),t.spinnerElement.hide(),t.getOption("onLoadComplete").call(t.previewElement)}):(t.animationLoaded=!0,t.resetEvents(),t.previewElement.hide(),t.wrapper.append(t.gifElement),t.spinnerElement.hide()),this.gifElement.css("cursor","pointer"),this.gifElement.css("position","absolute"),this.gifElement.css("top","0"),this.gifElement.css("left","0"),this.gifElement.attr("src",i),this.gifElement.click(function(i){e(this).remove(),t.stopGif(),i.preventDefault(),i.stopPropagation()}),t.getOption("onLoad").call(t.previewElement)},loadVideo:function(){this.videoLoaded=!0;var t=this.getFile("mp4"),i=this.getFile("webm"),n=this.previewElement.width(),o=this.previewElement.height();this.videoElement=e('<video class="gp-video-element" width="'+n+'px" height="'+o+'" style="margin:0 auto;width:'+n+"px;height:"+o+'px;" autoplay="autoplay" loop="loop" muted="muted" poster="'+this.previewElement.attr("src")+'"><source type="video/mp4" src="'+t+'"><source type="video/webm" src="'+i+'"></video>');var s=this,p=function(){4===s.videoElement[0].readyState?(s.playVideo(),s.animationLoaded=!0):setTimeout(p,100)};this.getOption("wait")?p():this.playVideo(),this.videoElement.on("click",function(){s.videoPaused?s.resumeVideo():s.pauseVideo()})},playVideo:function(){this.spinnerElement.hide(),this.previewElement.hide(),this.playElement.hide(),this.gifLoaded=!0,this.previewElement.hide(),this.wrapper.append(this.videoElement),this.videoPaused=!1,this.videoElement[0].play(),this.getOption("onPlay").call(this.previewElement)},pauseVideo:function(){this.videoPaused=!0,this.videoElement[0].pause(),this.playElement.show(),this.mouseoverEnabled=!1,this.getOption("onStop").call(this.previewElement)},resumeVideo:function(){this.videoPaused=!1,this.videoElement[0].play(),this.playElement.hide(),this.getOption("onPlay").call(this.previewElement)},enableAbort:function(){var e=this;this.previewElement.click(function(t){e.abortLoading(t)}),this.spinnerElement.click(function(t){e.abortLoading(t)})},abortLoading:function(e){this.spinnerElement.hide(),this.playElement.show(),e.preventDefault(),e.stopPropagation(),this.gifElement.off("load").on("load",function(e){e.preventDefault(),e.stopPropagation()}),this.resetEvents(),this.getOption("onStop").call(this.previewElement)},resetEvents:function(){this.previewElement.off("click"),this.previewElement.off("mouseover"),this.playElement.off("click"),this.spinnerElement.off("click"),this.addEvents()}},e.fn.gifplayer=function(i){return/^(play|stop)$/i.test(i)?this.each(function(){if(i=i.toLowerCase(),e(this).hasClass("gifplayer-ready")){var n=new t(e(this),null);switch(n.options={},n.options=e.extend({},e.fn.gifplayer.defaults,n.options),n.wrapper=e(this).parent(),n.spinnerElement=n.wrapper.find(".spinner"),n.playElement=n.wrapper.find(".play-gif"),n.gifElement=n.wrapper.find(".gp-gif-element"),n.videoElement=n.wrapper.find(".gp-video-element"),n.mode=n.getOption("mode"),i){case"play":n.playElement.trigger("click");break;case"stop":n.playElement.is(":visible")||("gif"==n.mode?n.stopGif():"video"==n.mode&&n.videoElement.trigger("click"))}}}):this.each(function(){i=e.extend({},e.fn.gifplayer.defaults,i);new t(e(this),i).activate()})},e.fn.gifplayer.defaults={label:"GIF",playOn:"click",mode:"gif",gif:"",mp4:"",webm:"",wait:!1,scope:!1,onPlay:function(){},onStop:function(){},onLoad:function(){},onLoadComplete:function(){}},t});