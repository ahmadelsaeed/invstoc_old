var calendar={init:function(){var t=new Date,a=(t.getFullYear(),t.getMonth(),t.getDate(),t.getMonth()+1);$(".month").text(function(t){return["January","February","March","April","May","June","July","August","September","October","November","December"][t-1]}(a)),$('tbody td[data-day="'+t.getDate()+'"]').addClass("current-day"),$("tbody td").on("click",function(t){$(this).hasClass("event")?($("tbody td").removeClass("active"),$(this).addClass("active")):$("tbody td").removeClass("active")}),$(".day-event").each(function(t){var a=$(this).data("month"),e=$(this).data("day");$('tbody tr td[data-month="'+a+'"][data-day="'+e+'"]').addClass("event")}),$("tbody td").on("click",function(t){$(".day-event").slideUp("fast");var a=$(this).data("month"),e=$(this).text();$('.day-event[data-month="'+a+'"][data-day="'+e+'"]').slideDown("fast")}),$(".close").on("click",function(t){$(this).parent().slideUp("fast")}),$(".save").click(function(){if(this.checked){$(this).next().text("Remove from personal list");var t=$(this).closest(".day-event").html(),a=$(this).closest(".day-event").data("month"),e=$(this).closest(".day-event").data("day"),d=$(this).closest(".day-event").data("number");$(".person-list").append('<div class="day" data-month="'+a+'" data-day="'+e+'" data-number="'+d+'" style="display:none;">'+t+"</div>"),$('.day[data-month="'+a+'"][data-day="'+e+'"]').slideDown("fast"),$(".day").find(".close").remove(),$(".day").find(".save").removeClass("save").addClass("remove"),$(".day").find(".remove").next().addClass("hidden-print"),$(".remove").click(function(){if(this.checked){$(this).next().text("Remove from personal list");var t=$(this).closest(".day").data("month"),a=$(this).closest(".day").data("day"),e=$(this).closest(".day").data("number");$('.day[data-month="'+t+'"][data-day="'+a+'"][data-number="'+e+'"]').slideUp("slow"),$('.day-event[data-month="'+t+'"][data-day="'+a+'"][data-number="'+e+'"]').find(".save").data("checked",!1),$('.day-event[data-month="'+t+'"][data-day="'+a+'"][data-number="'+e+'"]').find("span").text("Save to personal list"),setTimeout(function(){$('.day[data-month="'+t+'"][data-day="'+a+'"][data-number="'+e+'"]').remove()},1500)}}),function(){var t=$(".person-list");t.find(".day").sort(function(t,a){return+t.getAttribute("day")-+a.getAttribute("day")}).appendTo(t)}()}else{$(this).next().text("Save to personal list");var a=$(this).closest(".day-event").data("month"),e=$(this).closest(".day-event").data("day"),d=$(this).closest(".day-event").data("number");$('.day[data-month="'+a+'"][data-day="'+e+'"][data-number="'+d+'"]').slideUp("slow"),setTimeout(function(){$('.day[data-month="'+a+'"][data-day="'+e+'"][data-number="'+d+'"]').remove()},1500)}}),$(".print-btn").click(function(){window.print()})}};$(document).ready(function(){calendar.init()});