CRUMINA.IsotopeSort=function(){$(".sorting-container").each(function(){var t=$(this),i=t.data("layout").length?t.data("layout"):"masonry";t.isotope({itemSelector:".sorting-item",layoutMode:i,percentPosition:!0}),t.imagesLoaded().progress(function(){t.isotope("layout")});t.siblings(".sorting-menu").find("li").on("click",function(){if($(this).hasClass("active"))return!1;$(this).parent().find(".active").removeClass("active"),$(this).addClass("active");var i=$(this).data("filter");return void 0!==i?(t.isotope({filter:i}),!1):void 0})})},$(document).ready(function(){CRUMINA.IsotopeSort()});