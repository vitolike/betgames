
$(document).on("click", ".payment__item.payitem", function(){
	$('.payment__item.payitem').removeClass('active');
	$(this).addClass('active');
});

$(document).on("click", ".rating__info img", function(){
	$(this).parent().toggleClass('active');
});

$(document).on("click", ".open_tab__action", function(){
	$('.tab__action.head_action').addClass('active');
});

$(document).on("click", ".close_tab__action", function(){
	$('.tab__action.head_action').removeClass('active');
});

$(document).on("click", ".tab__action.head_action.active", function(){
	$('.tab__action.head_action').removeClass('active');
});

width_mob();

function width_mob() {
	var w = window.innerWidth;
	if (w < 768) {
		var width_history_table = window.innerWidth;
		width_history_table = Number(width_history_table) - 36;
		// console.log(width_history_table);
		$('.scroll-table-history').css('width', width_history_table);
	}
}

$(document).on("click", ".profile_submit", function(){
	SaveUser();
});

function SaveUser(){
	var first_name = $(".profile__main input#profileform-firstname").val();
	var last_name = $(".profile__main input#profileform-lastname").val();
	var login = $(".profile__main input#profileform-login").val();
	var birthday = $(".profile__main input#profileform-birthday").val();
	var email = $(".profile__main input#profileform-email").val();
	var phone = $(".profile__main input#profileform-phone").val();
	$.ajax({
		type: "POST",
		url:  "/engine/ajax/user.php?action=edit",
		data: {"ProfileForm[firstname]": first_name, "ProfileForm[lastname]": last_name, "ProfileForm[login]": login, "ProfileForm[birthday]": birthday, "ProfileForm[email]": email, "ProfileForm[phone]": phone},
		success: function(edit_user){
			
			var success = $('.save_success_text').text();
			$(".profile__main .modal__error").addClass('active');
			$(".profile__main .modal__error .modal__note_important").text(success);
			
			setTimeout(function () {
				$(".profile__main .modal__error").removeClass('active');
				$(".profile__main .modal__error .modal__note_important").text('');
			}, 3000);
		}
	});
}



$(document).on("click", ".active-phone", function(){
	var phone = $(".profile__main input#profileform-phone").val();
	$.ajax({
		type: "POST",
		url:  "/engine/ajax/activate.php",
		data: {
			val: phone,
			type: 'phone',
		},
		dataType: "json",
		success: function(data){
			console.log(data);
			xhr = "";
			$(".loading").remove();
			if (!data.success) {
				$("#profile")
					.find(".modal__error .modal__note_important")
					.html(data.error);
				$("#profile").find(".modal__error").show();
			} else {
				var timeinterval = setInterval(function () {
					var $time = getTimeRemaining(data.time);
					if ($time.seconds <= 0 && $time.minutes <= 0) {
						$(".clock-timer__counter").text("0:00");
						clearInterval(timeinterval);
					} else {
						$(".clock-timer__counter").text($time.minutes + ":" + $time.seconds);
					}
				}, 100);
				$(".popup_phoneVerification").addClass('index99997');
			}
		}
	});
});

$(document).on("click", ".active-email", function(){
	var phone = $(".profile__main input#profileform-email").val();
	$.ajax({
		type: "POST",
		url:  "/engine/ajax/activate.php",
		data: {
			val: phone,
			type: 'email',
		},
		dataType: "json",
		success: function(data){
			console.log(data);
			xhr = "";
			$(".loading").remove();
			if (!data.success) {
				$("#profile")
					.find(".modal__error .modal__note_important")
					.html(data.error);
				$("#profile").find(".modal__error").show();
			} else {
				$(".popup_emailVerification").addClass('index99997');
			}
		}
	});
});







/**Cookie**/
(function (factory) {
  if (typeof define === 'function' && define.amd) {
    // AMD (Register as an anonymous module)
    define(['jquery'], factory);
  } else if (typeof exports === 'object') {
    // Node/CommonJS
    module.exports = factory(require('jquery'));
  } else {
    // Browser globals
    factory(jQuery);
  }
}(function ($) {
  var pluses = /\+/g;
  function encode(s) {
    return config.raw ? s : encodeURIComponent(s);
  }
  function decode(s) {
    return config.raw ? s : decodeURIComponent(s);
  }
  function stringifyCookieValue(value) {
    return encode(config.json ? JSON.stringify(value) : String(value));
  }
  function parseCookieValue(s) {
    if (s.indexOf('"') === 0) {
      // This is a quoted cookie as according to RFC2068, unescape...
      s = s.slice(1, -1).replace(/\\"/g, '"').replace(/\\\\/g, '\\');
    }
    try {
      // Replace server-side written pluses with spaces.
      // If we can't decode the cookie, ignore it, it's unusable.
      // If we can't parse the cookie, ignore it, it's unusable.
      s = decodeURIComponent(s.replace(pluses, ' '));
      return config.json ? JSON.parse(s) : s;
    } catch(e) {}
  }
  function read(s, converter) {
    var value = config.raw ? s : parseCookieValue(s);
    return $.isFunction(converter) ? converter(value) : value;
  }
  var config = $.cookie = function (key, value, options) {
    // Write
    if (arguments.length > 1 && !$.isFunction(value)) {
      options = $.extend({}, config.defaults, options);
      if (typeof options.expires === 'number') {
        var days = options.expires, t = options.expires = new Date();
        t.setMilliseconds(t.getMilliseconds() + days * 864e+5);
      }
      return (document.cookie = [
        encode(key), '=', stringifyCookieValue(value),
        options.expires ? '; expires=' + options.expires.toUTCString() : '', // use expires attribute, max-age is not supported by IE
        options.path    ? '; path=' + options.path : '',
        options.domain  ? '; domain=' + options.domain : '',
        options.secure  ? '; secure' : ''
      ].join(''));
    }
    // Read
    var result = key ? undefined : {},
      // To prevent the for loop in the first place assign an empty array
      // in case there are no cookies at all. Also prevents odd result when
      // calling $.cookie().
      cookies = document.cookie ? document.cookie.split('; ') : [],
      i = 0,
      l = cookies.length;
    for (; i < l; i++) {
      var parts = cookies[i].split('='),
        name = decode(parts.shift()),
        cookie = parts.join('=');
      if (key === name) {
        // If second argument (value) is a function it's a converter...
        result = read(cookie, value);
        break;
      }
      // Prevent storing a cookie that we couldn't decode.
      if (!key && (cookie = read(cookie)) !== undefined) {
        result[name] = cookie;
      }
    }
    return result;
  };
  config.defaults = {};
  $.removeCookie = function (key, options) {
    // Must not alter options, thus extending a fresh object...
    $.cookie(key, '', $.extend({}, options, { expires: -1 }));
    return !$.cookie(key);
  };
}));
/**Cookie**/
var preloader = "<div class='loading'><div class='loader'></div></div>";
window.vulcanNamespace = {};
var xhr = "";
$(function () {
  var array = $("[data-piwik-event]");  
  array.each(function (index) {
    var item = $(this),
      eventName = item.attr("data-piwik-event").split(",");
    item.bind("click", function () {
      _paq.push(["trackEvent", eventName[0], eventName[1], eventName[2]]);
      console.log(
        "Group: " +
          eventName[0] +
          ", " +
          "Event Name: " +
          eventName[2] +
          ", " +
          "Event Action: " +
          eventName[1]
      );
    });
  });
  $(".levels-table__item").on("touchend click", function (e) {
    $(".levels-table__item").removeClass("levels-table__item_active");
    $(this).addClass("levels-table__item_active");
    $(".levels-table__arrow").removeClass("levels-table__arrow_active");
    $(this).find(".levels-table__arrow").addClass("levels-table__arrow_active");
  });
  $(".js-close-popup, [data-toggle='tab']").on("click", function (e) {    
    $(".payment__tooltip").removeClass("payment__tooltip_open");
  });
  $(".payitem").on("click", function (e) {
    console.log("click");
    var $index = $(this).index(),
      content = $(".payment__tooltip_open .pay-tooltip");
    $('.pay-tooltip__summ input[type="radio"]').on("click", function (i) {
      var volume_value = $(this).val();
      $(this).parent().parent().find(".l_num").val(volume_value);
      $(this)
        .parent()
        .parent()
        .find(".input_summ_val")
        .val(volume_value)
        .focus();
    });
    $(".js-input__inner").val("");
    $(".pay-tooltip__note").hide();
    $(".l_num").click().val("1000");
    $(".input_summ_val").val("1000");
    $(".input_summ_val").on("change, input", function () {
      var volume_value = $(this).val();
      $(this).parent().parent().find(".l_num").val(volume_value).click();
    });
    $(".payment__tooltip").removeClass("payment__tooltip_open");
    $(this)
      .parent()
      .parent()
      .parent()
      .find(".payment__tooltip")
      .toggleClass("payment__tooltip_open");
    var paysys = $(this).data("paysys");
    if (paysys)
      $(this)
        .parents(".payment-form")
        .attr("action", "/engine/dir/pay/" + paysys + "/" + paysys + ".php");
    if (
      $(this).find("input").val() === "qiwi_rub" ||
      $(this).find("input").val() === "mts_rub" ||
      $(this).find("input").val() === "beeline_rub" ||
      $(this).find("input").val() === "megafon_rub" ||
      $(this).find("input").val() === "tele2_rub"
    ) {
      $(".pay-tooltip__phone").show();
      $(".pay-tooltip").addClass("pay-tooltip_withphone");
      $(".pay-tooltip__phone_inner").attr("required", true);
    } else {
      $(".pay-tooltip__summ").show();
      $(".pay-tooltip__phone").hide();
      $(".pay-tooltip").removeClass("pay-tooltip_withphone");
      $(".pay-tooltip__phone_inner").attr("required", false);
    }
    if (
      $(this).find("input").val() === "qiwi_rub" &&
      $(this).find("input").hasClass("payout")
    ) {
      $(".pay-tooltip__number").removeClass("pay-tooltip__number_withr");
      $(".pay-tooltip__number").addClass("pay-tooltip__number_withplus");
      $(".pay-tooltip__number_inner").removeClass(
        "pay-tooltip__number_inner-noprefix"
      );
      $(".pay-tooltip__number_inner").attr({
        required: true,
        name: "account",
        type: "tel",
        placeholder: "70000000000",
        maxlength: "14",
      });
    } else if ($(this).find("input").val() === "webmoney") {
      $(".pay-tooltip__number").removeClass("pay-tooltip__number_withplus");
      $(".pay-tooltip__number").addClass("pay-tooltip__number_withr");
      $(".pay-tooltip__number_inner").removeClass(
        "pay-tooltip__number_inner-noprefix"
      );
      $(".pay-tooltip__number_inner").attr({
        required: true,
        name: "account",
        type: "text",
        placeholder: "000000000000",
        maxlength: "20",
      });
    } else if ($(this).find("input").val() === "pin") {
      $(".pay-tooltip__summ").hide();
      $(".pay-tooltip__pin_inner").attr({
        required: true,
        name: "pin",
        type: "text",
        placeholder: "0000000000",
        maxlength: "10",
      });
      $(".pay-tooltip__pin").show();
    } else {
      $('input[value="card_rub"]').closest("form").addClass("card_rub--form");
      $(".card_rub--form .pay-tooltip__number .pay-tooltip__caption").text(
        "Номер карты:"
      );
      var duplicateInputHTML =
        '<input type="number" name="account" placeholder="хххх-хххх-хххх-хххх" required="required" class="pay-tooltip__number_inner input__inner js-input__inner js-account-duplicate pay-tooltip__number_inner-noprefix">';
      var hintHTML =
        '<div class="tooltip__hint"><div class="arrow"></div>Введите номер банковской карты</div>';
      if ($(".card_rub--form .tooltip__hint").length < 1) {
        $(duplicateInputHTML).appendTo(
          ".card_rub--form .pay-tooltip__number .pay-tooltip__input"
        );
        $(hintHTML).appendTo(
          ".card_rub--form .pay-tooltip__number .pay-tooltip__input"
        );
      }
      $(".pay-tooltip__summ").show();
      $(".pay-tooltip__number").removeClass("pay-tooltip__number_withplus");
      $(".pay-tooltip__number").removeClass("pay-tooltip__number_withr");
      $(".pay-tooltip__number_inner").addClass(
        "pay-tooltip__number_inner-noprefix"
      );
      $(".pay-tooltip__number_inner").attr({
        required: true,
        name: "account",
        type: "tel",
        placeholder: "000000000000000",
        //maxlength: '22'
      });
      $(".card_rub--form .pay-tooltip__number_inner").attr({
        required: true,
        name: "account",
        type: "tel",
        placeholder: "хххх-хххх-хххх-хххх",
        //maxlength: '22'
      });
      $(".card_rub--form .js-account-duplicate").mask("?9999 9999 9999 9999");
      $(document).on(
        "keyup",
        ".card_rub--form .js-account-duplicate",
        function () {
/*$(this).closest('.pay-tooltip__number').find('.js-account-main').val($(this).val().replace(/\s+/g,'').split("_").join(""));if($(this).closest('.pay-tooltip__number').find('.js-account-main').val().length<16){$(this).closest('.pay-tooltip__number').find('.js-account-main').val(null)}*/
        }
      );
    }
    $(this).find(".l_num").click();
    $(".js-input__inner").on("keyup, input", function (e) {
      if (this.value.match(/[^0-9]/g)) {
        this.value = this.value.replace(/[^0-9]/g, "");
      }
    });
    if ($index == 0) {
      content.addClass("left").removeClass("right");
    } else if ($index == 1) {
      content.removeClass("left").removeClass("right");
    } else if ($index == 2) {
      content.removeClass("left").addClass("right");
    }
  });

$(".payment__tooltip_close").on("click", function(){
    $('.payment__item.payitem').removeClass('active');
    $('.payment__tooltip').removeClass('payment__tooltip_open');
});

/*$('.slider_small').slick({infinite:true,slidesToShow:8,slidesToScroll:1,swipe:true,draggable:true,touchMove:true,dots:false,responsive:[{breakpoint:1440,settings:{slidesToShow:7,slidesToScroll:1,dots:false}},{breakpoint:1240,settings:{slidesToShow:5,slidesToScroll:5,dots:false}},{breakpoint:1000,settings:{slidesToShow:3,slidesToScroll:3,dots:false}},{breakpoint:768,settings:{slidesToShow:3,slidesToScroll:2,dots:false,swipe:true,draggable:true}}]});*/
  $('.slider_info').slick({
    infinite: true,
    slidesToShow: 5,
    slidesToScroll: 1,
    dots: false,
    autoplay: true,
    autoplaySpeed: 1500
  });

  $('.slider_tournament').slick({
    infinite: true,
    autoplay: false,
    autoplaySpeed: 1500,
    slidesToShow: 9,
    slidesToScroll: 1,
    dots: false,
    responsive: [
      {
        breakpoint: 1240,
        settings: {
          slidesToShow: 7,
          slidesToScroll: 1
        }
      },
      {
        breakpoint: 1000,
        settings: {
          slidesToShow: 6,
          slidesToScroll: 1
        }
      },
      {
        breakpoint: 768,
        settings: {
          slidesToShow: 3,
          slidesToScroll: 2,
          dots: false,
          swipe: true,
          draggable: true
        }
      }
    ]
  });
  $('.slider_hero').slick({
    //lazyLoad: 'ondemand',
    dots: true,
    arrows: false,
    infinite: false,
    autoplay: true,
    autoplaySpeed: 4000,
    speed: 500,
    fade: true,
    draggable: true,
    cssEase: 'linear',
    responsive: [
      {
        breakpoint: 767,
        settings: {
          dots: false
        }
      }
    ]
  });
  $('.winsline').slick({
    infinite: true,
    slidesToShow: 5,
    slidesToScroll: 1,
    swipe: true,
    dots: false,
    arrows: false,
    autoplay: true,
    autoplaySpeed: 1500,
    pauseOnFocus: true,
    responsive: [
      {
        breakpoint: 1240,
        settings: {
          slidesToShow: 4,
          slidesToScroll: 1,
          pauseOnFocus: true
        }
      },
      {
        breakpoint: 999,
        settings: {
          slidesToShow: 3,
          slidesToScroll: 1,
          pauseOnFocus: true
        }
      },
      {
        breakpoint: 767,
        settings: {
          slidesToShow: 1,
          slidesToScroll: 1,
          pauseOnFocus: true
        }
      }
    ]
  });
  (function ($) {
    if ($(window).width() < 768) {
      $('.leaderboard__slider').slick({
        dots: true,
        infinite: false,
        arrows: false
      });
      $('.lottery-details__tickets').slick({
        dots: true,
        infinite: false,
        arrows: false
      });
    }
  }) (jQuery);
  (function ($) {
    if ($(window).width() < 768) {
      $(".main-nav").not(".ps-container").perfectScrollbar({
        theme: "hidden",
        suppressScrollY: true,
        scrollXMarginOffset: 5,
      });
    }
  })(jQuery);
  (function ($) {
    $(".header__wrap_scroll").not(".ps-container").perfectScrollbar({
      theme: "hidden",
      suppressScrollY: true,
      scrollXMarginOffset: 5,
    });
  })(jQuery);
  (function ($) {
    if ($(window).width() > 768) {
      $(".modal_open .popup2")
        .not(".ps-container")
        .not(".popup_tabs")
        .perfectScrollbar({
          theme: "hidden",
        });
    }
  })(jQuery);
  $(".filter__select").chosen({
    disable_search: true,
  });
/*$('input.datepicker_start').Zebra_DatePicker({offset:[-150,40],default_position:'below',show_icon:false,direction:true,format:'m/d/Y',show_clear_date:false,show_select_today:false,pair:$('input.datepicker_end')});$('input.datepicker_end').Zebra_DatePicker({offset:[-150,40],default_position:'below',show_icon:false,format:'m/d/Y',show_clear_date:false,show_select_today:false,direction:1});$('input.datepicker_birth').Zebra_DatePicker({offset:[-280,40],days:['Вс.','Пн.','Вт.','Ср.','Чт.','Пт.','Сб.'],months:['Январь','Февраль','Март','Апрель','Май','Июнь','Июль','Август','Сентябрь','Октябрь','Ноябрь','Декабрь'],default_position:'below',show_icon:false,format:'Y-m-d',show_clear_date:false,show_select_today:false});*/
  $("input.range__input").ionRangeSlider({
    type: "double",
    grid: true,
    grid_snap: true,
    min: 1,
    max: 7,
    from: 1,
    step: 1,
    to: 2,
    from_fixed: true,
    prefix: "x",
    hide_from_to: true,
    hide_min_max: true,
    onStart: function (data) {
      $(".irs-grid-text").each(function (index, element) {
        $(this).removeClass("irs-grid-text_active");
        if (index === data.to - 1) {
          $(this).addClass("irs-grid-text_active");
        }
      });
    },
    onChange: function (data) {
      var toValue = data.to;
      $(".irs-grid-text").each(function (index, element) {
        $(this).removeClass("irs-grid-text_active");
        if (index === data.to - 1) {
          $(this).addClass("irs-grid-text_active");
        }
      });
    },
  });
/*if(768<=$(window).width()){let a=$(".hero__wrap").outerHeight(),e=$(".main-nav__subnav").outerHeight();$(".main-nav__item_subnav").hover(function(){$(this).hasClass("main-nav__item_active")||$(".hero__nav").hasClass("hero__nav_sticky")||($(".main-nav").outerHeight(),$(".hero__wrap").animate({height:e+a+"px"},{queue:!1,duration:100}))},function(){$(this).hasClass("main-nav__item_active")||($(".main-nav").outerHeight()<=50?($(".hero__wrap").animate({height:a+"px"},{queue:!1,duration:100}),$(".main-nav").attr("style","overflow: hidden !important")):$(".hero__wrap").animate({height:a+"px"},{queue:!1,duration:100}))}),function(a){var e,t;a(".main-nav").attr("style","overflow: auto !important"),a(".main-nav__item_subnav").hasClass("main-nav__item_active")&&(e=a(".hero__wrap").outerHeight(),t=a(".main-nav__subnav").outerHeight(),a(".hero__wrap").css("height",e+t))}(jQuery)}*/
  if ($(window).width() >= 768) {
    let heroHeight = $(".hero__wrap").height();
    let subnavHeight = $(".main-nav__subnav").height();
    $(".main-nav__item_subnav")
      .children(".main-nav__subnav.subnav")
      .attr("style", "visibility: visible; opacity: 1");
    $(".main-nav__item_subnav:gt(0)")
      .children(".main-nav__subnav.subnav")
      .attr("style", "visibility: hidden; opacity: 0");
    $(".hero__wrap").height(subnavHeight + heroHeight);
    $(".main-nav").attr("style", "overflow: auto !important");
  }
  $(function () {
    var alertPanel = $(".alert-panel").height();
    var header = $(".header").height();
    var hero = $(".hero").height();
    var heightSum;
    if (alertPanel !== undefined) {
      heightSum = alertPanel + header + hero;
    } else {
      heightSum = header + hero;
    }
    var window_width = $(window).width();
    if ($(".main-nav li").width() >= window_width) {
      $(".hero__nav").addClass("hero__nav_scroll");
    }
    $(window).scroll(function () {
      var scroll = getCurrentScroll();
      if ($(window).width() >= 768) {
        if (scroll >= heightSum - 78) {
          $(".hero__nav").addClass("hero__nav_sticky");
        } else {
          $(".hero__nav").removeClass("hero__nav_sticky");
        }
        if (
          scroll >= 1 &&
          scroll < heightSum - (78 + 43) &&
          $(".alert-panel").is(":visible")
        ) {
          $("header").css({
            "margin-top": alertPanel,
          });
          $(".alert-panel").addClass("hero__nav_sticky");
        } else {
          $("header").css({
            "margin-top": 0,
          });
          $(".alert-panel").removeClass("hero__nav_sticky");
        }
      }
    });
    function getCurrentScroll() {
      return window.pageYOffset || document.documentElement.scrollTop;
    }
  });
  $(function () {
    if (window.location.hash != undefined) {
      if (window.location.hash == "#registration") {
        $("#registration-modal").show();
        var link = window.location.href;
        link = link.replace(window.location.hash, "");
        history.pushState({}, "", link);
      }
      if (window.location.hash == "#login") {
        $("#login-modal").show();
        var link = window.location.href;
        link = link.replace(window.location.hash, "");
        history.pushState({}, "", link);
      }
    }
  });
  $(document).on("click", '[data-toggle="modal"]', function (e) {
    e.preventDefault();
    if ($(this).data("tab") == "#cashier") {
      $(window).scrollTop(0);
    }
    $(".modal,.popup2").hide();
    var $id = $(this).attr("href");
    if ($id == undefined) {
      $id = $(this).data("target");
    }
    $($id).show();
    $("html").addClass("modal_open");
    if ($(window).width() < 768) {
      $(".modal_open .popup2")
        .not(".ps-container")
        .not(".popup_tabs")
        .perfectScrollbar({
          theme: "hidden",
        });
    }
    if ($(this).data("tab") != undefined) {
      $($(this).data("tab")).parent().find(">div").removeClass("active");
      $($(this).data("tab")).addClass("active");
      $('.tab__item[href="' + $(this).data("tab") + '"]')
        .parent()
        .find(".tab__item")
        .removeClass("tab__item_active");
      $('.tab__item[href="' + $(this).data("tab") + '"]').addClass(
        "tab__item_active"
      );
    }
  });
  window.getTimeRemaining = function (endtime) {
    var today = new Date().toUTCString();
    endtime = new Date(endtime * 1000).toUTCString();
    var t = Date.parse(endtime) - Date.parse(today);
    var seconds = Math.floor((t / 1000) % 60);
    var minutes = Math.floor((t / 1000 / 60) % 60);
    var hours = Math.floor((t / (1000 * 60 * 60)) % 24);
    var days = Math.floor(t / (1000 * 60 * 60 * 24));
    return {
      total: t,
      days: days,
      hours: hours,
      minutes: minutes,
      seconds: seconds,
    };
  };
  function initializeClock(id, endtime, letter_format) {
    var clock = document.getElementById(id);
    if (!clock) {
      return false;
    }
    var timeinterval = setInterval(function () {
      var t = getTimeRemaining(endtime);
      if (t.days < 0) { t.days = 0; }
      if (t.hours < 0) { t.hours = 0; }
      if (t.minutes < 0) { t.minutes = 0; }
      if (t.seconds < 0) { t.seconds = 0; }
      if (t.hours < 10 && t.hours >= 0) { t.hours = "0" + t.hours; }
      if (t.minutes < 10 && t.minutes >= 0) { t.minutes = "0" + t.minutes; }
      if (t.seconds < 10 && t.seconds >= 0) { t.seconds = "0" + t.seconds; }
      if (letter_format) {
        clock.innerHTML =
          ' <div class="timer__cell timer__cell-days">' +
          t.days +
          "</div> " +
          '<div class="timer__cell timer__cell_empty"></div> ' +
          '<div class="timer__cell timer__cell-value">' +
          t.hours +
          "</div>" +
          ' <div class="timer__cell">ч</div> ' +
          '<div class="timer__cell timer__cell-value">' +
          t.minutes +
          "</div> " +
          '<div class="timer__cell">мин</div> ' +
          '<div class="timer__cell timer__cell-value">' +
          t.seconds +
          "</div> " +
          '<div class="timer__cell">сек</div> ';
      } else {
        clock.innerHTML =
          ' <div class="timer__cell timer__cell-days">' +
          t.days +
          "</div> " +
          '<div class="timer__cell timer__cell_empty"></div> ' +
          '<div class="timer__cell">' +
          t.hours +
          "</div>" +
          ' <div class="timer__cell">:</div> ' +
          '<div class="timer__cell">' +
          t.minutes +
          "</div> " +
          '<div class="timer__cell">:</div> ' +
          '<div class="timer__cell">' +
          t.seconds +
          "</div> ";
      }
      if (t.total <= 0) { clearInterval(timeinterval); }
    }, 1000);
  }
  function initializeJackpot(id, jackpot) {
    var jack = document.getElementById(id);
    var numbers = (jackpot + "").split("", -4);
    var $j = numbers.reverse();
    var timeinterval = setInterval(function () {
      var date = new Date();
      if (date.getHours() > 20 || date.getHours() < 3)
        $j[0] = parseInt($j[0]) + randomInteger(1, 10);
      else $j[0] = parseInt($j[0]) + randomInteger(1, 5);
      if ($j[0] >= 10) {
        $j[0] = parseInt($j[0]) - 10;
        $j[1] = parseInt($j[1]) + 1;
        if ($j[1] >= 10) {
          $j[1] = 10 - $j[1];
          $j[2] = parseInt($j[2]) + 1;
          if ($j[2] >= 10) {
            $j[2] = 10 - $j[2];
            $j[3] = parseInt($j[3]) + 1;
            if ($j[3] == 10) {
              $j[3] = 0;
              $j[4] = parseInt($j[4]) + 1;
              if ($j[4] == 10) {
                $j[4] = 0;
              }
            }
          }
        }
      }
      if ($j[0] < 0) {
        $j[0] = 0;
        $j[1] = parseInt($j[1]) - 1;
        if ($j[1] < 0) {
          $j[1] = 0;
          $j[2] = parseInt($j[2]) - 1;
          if ($j[2] < 0) {
            $j[2] = 0;
            $j[3] = parseInt($j[3]) - 1;
            if ($j[3] == 10) $j[3] = 0;
          }
        }
      }
      $(jack).find(".js-countdown__item:last").html($j[0]);
      $(jack)
        .find(".js-countdown__item")
        .eq(parseInt($(jack).find(".js-countdown__item").length) - 2)
        .html($j[1]);
      $(jack)
        .find(".js-countdown__item")
        .eq(parseInt($(jack).find(".js-countdown__item").length) - 3)
        .html($j[2]);
      $(jack)
        .find(".js-countdown__item")
        .eq(parseInt($(jack).find(".js-countdown__item").length) - 4)
        .html($j[3]);
      $(jack)
        .find(".js-countdown__item")
        .eq(parseInt($(jack).find(".js-countdown__item").length) - 5)
        .html($j[4]);
    }, 2000);
  }
  function randomInteger(min, max) {
    var rand = min + Math.random() * (max - min);
    rand = Math.round(rand);
    return rand;
  }
  $(function () {
    function randomInteger(min, max) {
      var rand = min + Math.random() * (max - min);
      rand = Math.round(rand);
      return rand;
    }
    $(".js-close-popup").on("click", function (e) {
      // e.preventDefault();
	  console.log('click');
      var popup2 = $(this).parents(".popup2");
      if (popup2.length != 0) {
        var Class = popup2.attr("class").split(" ");
        var ClassName = Class[1];
      } else {
        var ClassName = "modal";
      }
      $("." + ClassName).hide();
      $(".modal").hide();
      $("html").removeClass("modal_open");
      $(".popup2").removeClass("index99997");
    });
    $(".alert-panel__icon .icon_cross").on("click", function (e) {
      e.preventDefault();
      $(".alert-panel").hide();
    });
    $(".notify-panel__icon .icon_cross").on("click", function (e) {
      e.preventDefault();
      $(".notify-panel").hide();
    });
    $.each($('[data-toggle="timer"]'), function () {
      initializeClock($(this).attr("id"), $(this).data("time"));
    });
    $.each($('[data-toggle="jackpot"]'), function () {
      initializeJackpot($(this).attr("id"), $(this).data("jack"));
    });
    initializeClock(
      "notifyAntiblockTimer",
      new Date().getTime() / 1000 + 3601,
      true
    );
  });
  function showRegistrationPopup() {
    $(".modal,.popup2").hide();
    $("#registration-confirm").show();
    $("html").addClass("modal_open");
    if ($(window).width() < 768) {
      $(".modal_open .popup2").not(".ps-container").perfectScrollbar({
        theme: "hidden",
      });
    }
    $("#registration-confirm #bonus").val(
      $('#registration-modal [name="bonus"]:checked').val()
    );
    var label = $('#registration-modal [name="bonus"]:checked').prev("label");
    if (!label.hasClass("bonus_no_img")) {
      $("#registration-confirm .registration__image img").attr(
        "src",
        label.find("img").attr("src")
      );
      $("#registration-confirm .registration__image img").show();
      $(".registration-set-bonus").addClass("control-hidden");
      $(".registration-change-bonus:first").removeClass("control-hidden");
    } else {
      $(".registration-change-bonus:first").addClass("control-hidden");
      $("#registration-confirm .registration__image img").hide();
      $(".registration-set-bonus").removeClass("control-hidden");
    }
  }
  if (!$('#registration-modal [name="bonus"]').data("binding"))
    $('#registration-modal [name="bonus"]').on("change", function (e) {
      e.preventDefault();
      showRegistrationPopup();
      $("html").addClass("modal_open");
    });
  $(document).on("submit", 'form[data-type="ajax"]', function (e) {
    e.preventDefault();
    var $type = $(this).attr("method");
    var $action = $(this).attr("action");
    var $data = $(this).serialize();
    var $answer = $(this).data("answer");
    var $form = $(this);
    var is_reg = $(this).hasClass("registration__form");
    $.ajax({
      type: $type,
      url: $action,
      data: $data,
      dataType: "json",
      beforeSend: function () {
        $form.find(".modal__error").hide();
        $form.find(".pay-tooltip__note").hide();
        $form.closest(".modal,.popup2").append(preloader);
      },
      success: function (data) {
        console.log(data);
        $(".loading").remove();
        if (!data.success) {
          //Если запрос не удачен
          $('[type="password"]').val("");
          if ($.type(data.error) == "object") {
            $form
              .find(
                ".modal__error .modal__note_important,.pay-tooltip__note .error__info"
              )
              .html("");
            $.each(data.error, function ($key, $value) {
              $form
                .find(
                  ".modal__error .modal__note_important,.pay-tooltip__note .error__info"
                )
                .append($value + "<br/>");
            });
          } else {
            $form
              .find(
                ".modal__error .modal__note_important,.pay-tooltip__note .error__info"
              )
              .html(data.error);
          }
          $form.find(".modal__error").show();
          $form.find(".pay-tooltip__note").show();
        } else {
          //Если запрос удачно выполнился
          if(data.phone){
            self['location']['replace'](self.location['href'])
            location.reload();
          }
          if (data.uid != undefined && _ggcounter != undefined) {
            _ggcounter.push({
              event: "login",
              uid: data.uid,
              callback: function () {},
            });
          }
          if (data.form != undefined) {
            $("body").append(data.form);
            $("#" + data.form_id).submit();
          } else {
            if ($answer != undefined) {
              $(".modal,.popup2").hide();
              $($answer).show();
            } else {
              if (is_reg) {
              }
              window.location.reload();
            }
          }
        }
      },
    });
  });
  $(document).on("submit", "form.payment-form", function (e) {
    e.preventDefault();
    var $type = $(this).attr("method");
    var $action = $(this).attr("action");
    var $data = $(this).serialize();
    var $answer = $(this).data("answer");
    var $form = $(this);
    $.ajax({
      type: $type,
      url: $action,
      data: $data,
      dataType: "json",
      beforeSend: function () {
        $form.find(".pay-tooltip__note").hide();
        $form.closest(".modal,.popup2").append(preloader);
      },
      success: function (data) {
        $(".loading").remove();
        if (data.result != "ok") {
          if ($.type(data.message) == "object") {
            $form.find(".pay-tooltip__note .error__info").html("");
            $.each(data.message, function ($key, $value) {
              $form
                .find(".pay-tooltip__note .error__info")
                .append($value + "<br/>");
            });
          } else {
            $form.find(".pay-tooltip__note .error__info").html(data.message);
          }
          $form.find(".pay-tooltip__note").show();
        } else {
          if (data.form != undefined) {
            $("body").append(data.form);
            $("#" + data.form_id).submit();
          } else {
            if ($answer != undefined) {
              $(".modal,.popup2").hide();
              $($answer).show();
            } else {
              window.location.reload();
            }
          }
        }
      },
    });
  });
  $(function () {
    $(".activate-bonus").on("click", function (e) {
      e.preventDefault();
      if (xhr != "") {
        xhr.abort();
      }
      var id = $(this).data("id");
      xhr = $.post(
        "engine/ajax/activate_bonus.html",
        { id: id, },
        function (data) {
          xhr = "";
          if (data.status && data.is_deposit) {
            $("#bonus-img").attr("src", data.image);
            $("#bonus-deposit-sum").html(data.deposit);
            console.log($(".min"));
            $(".min").html(data.deposit);
            $(".deposit-campaign-id").val(data.campaign_id);
            $("#deposit-for-bonus-modal .aside__promo-table .table__body").html("");
            $("#deposit-for-bonus-modal input[name=bonus_id]").val(id);
            $.each(data.winners, function ($key, $item) {
              var $row =
                "<tr class='table__row'><td class='table__cell'>" +
                ($key + 1) +
                "</td><td class='table__cell'>" +
                $item.login +
                "</td><td class='table__cell'>" +
                Math.round($item.win) +
                "</td></tr>";
              $(
                "#deposit-for-bonus-modal .aside__promo-table .table__body"
              ).append($row);
            });
            $("#cabinet-modal").hide();
            $("#deposit-for-bonus-modal").show();
            $("html").addClass("modal_open");
          } else {
            if (!data.status) {
              $("#cabinet-modal").hide();
              $("#have_active_bonus .popup__content .popup__title").html(
                data.error
              );
              $("#have_active_bonus").show();
            } else {
              window.location.reload();
            }
          }
          $(window).scrollTop(0);
        },
        "json"
      );
    });
  });
  $(function () {
    $(".deactivate-bonus").on("click", function (e) {
      e.preventDefault();
      if (xhr != "") {
        xhr.abort();
      }
      var id = $(this).data("id");
      xhr = $.post(
        "engine/ajax/deactivate_bonus.html",
        { id: id, },
        function (data) {
          xhr = "";
          if (data.status && data.is_deposit) {
            $("#bonus-img").attr("src", data.image);
            $("#bonus-deposit-sum").html(data.deposit);
            console.log($(".min"));
            $(".min").html(data.deposit);
            $(".deposit-campaign-id").val(data.campaign_id);
            $("#deposit-for-bonus-modal .aside__promo-table .table__body").html("");
            $("#deposit-for-bonus-modal input[name=bonus_id]").val(id);
            $("#cabinet-modal").hide();
            $("#deposit-for-bonus-modal").show();
            $("html").addClass("modal_open");
          } else {
            if (!data.status) {
              $("#cabinet-modal").hide();
              $("#have_active_bonus .popup__content .popup__title").html(
                data.error
              );
              $("#have_active_bonus").show();
            } else {
              window.location.reload();
            }
          }
          $(window).scrollTop(0);
        },
        "json"
      );
    });
  });
  $(document).on("click", 'button[data-type="ajax"]', function (e) {
    e.preventDefault();
    var $success = $(this).data("success");
    var $fail = $(this).data("fail");
    console.log($(this).data("target"));
    $.post(
      $(this).data("target"),
      {},
      function (data) {
        if (data.success) {
          $($success).show();
        } else {
          $($fail).show();
        }
      },
      "json"
    );
  });
  (function () {
    $(".popup_favoritesAdded .js-close-popup").on("click", function () {
      $('button[data-success =".popup_favoritesAdded"]').hide();
    });
    $(".notify-popup .js-close-popup").on("click", function () {
      $(this).closest(".notify-popup").fadeOut();
    });
    $("#popup-antiblock .js-close-popup").on("click", function () {
      var date = new Date();
      date.setTime(date.getTime() + 5 * 60 * 1000);
      $.cookie("hide_antiblock_popup", true, {
        path: "/",
        expires: date,
      });
    });
  })();
  $(document).on("submit", "#search-form", function (e) {
    e.preventDefault();
    $.ajax({
      type: "GET",
      data: {
        page: $("#page").val(),
        group: $("#gamegroup").val(),
        type: "html",
        q: $("#search-form input").val(),
      },
      url: "/engine/ajax/game_list.php",
      success: function (data) {
        if ($("#search-form input").val() != "") {
          history.pushState(
            { q: $("#search-form input").val(), },
            "",
            window.location.pathname + "/?q=" + $("#search-form input").val()
          );
        } else {
          history.pushState({}, "", window.location.pathname);
        }
        $(".main_gallery").html(data);
      },
    });
  });
  $(document).on("keyup", "#search-form input", function (e) {
    e.preventDefault();
    if (xhr != "") xhr.abort();
    ajaxSearch("#search-form");
  });
  $(document).on("keyup", "#search-form-mob input", function (e) {
    e.preventDefault();
    if (xhr != "") xhr.abort();
    ajaxSearch("#search-form-mob");
  });
  function ajaxSearch(form) {
    xhr = $.ajax({
      type: "GET",
      data: {
        page: $("#page").val(),
        group: $("#gamegroup").val(),
        type: "html",
        q: $(form).find("input").val(),
      },
      url: "/engine/ajax/game_list.php",
      success: function (data) {
        xhr = "";
        if ($(form).find("input").val() != "") {
          $(".main_gallery_search").html(data);
          $(".main_gallery_search").addClass("active");
          $(".all_games_list").removeClass("active");
          history.pushState(
            { q: $(form).find("input").val(), },
            "",
            window.location.pathname.replace(new RegExp("[/]+$", "g"), "") +
              "/?q=" +
              $(form).find("input").val()
          );
        } else {
          history.pushState({}, "", window.location.pathname);
          $(".main_gallery_search").removeClass("active");
          $(".all_games_list").addClass("active");
        }
      },
    });
  }
  (function ($) {
    $.each(["show", "hide"], function (i, ev) {
      var el = $.fn[ev];
      $.fn[ev] = function () {
        this.trigger(ev);
        return el.apply(this, arguments);
      };
    });
  })(jQuery);
  $("#registration-modal").on("show", function () {
    $('[name="bonus"]').prop("checked", false);
  });
  $("#soc_registration-modal").on("show", function () {
    $('[name="bonus"]').prop("checked", false);
  });
  $(".modal,.popup2").on("show", function () {
    $(".modal__error").hide();
    $(".pay-tooltip__note").hide();
    if ($(".tab-profile__form").length >= 1) {
      $(".tab-profile__form")[0].reset();
      $(".js-input__inner_tel").on("change keyup input click", function () {
        if (this.value.match(/[^0-9]/g)) {
          this.value = this.value.replace(/[^0-9]/g, "");
        }
      });
    }
  });
  
  /*$(document).on("click", "[data-verification]", function (e) {
    e.preventDefault();
    var $type = $(this).data("verification");
    if (xhr != "") {
      xhr.abort();
    }
    xhr = $.ajax({
      type: "POST",
      data: {
        val: $("#profileform-" + $type).val(),
        type: $type,
      },
      url: "/engine/ajax/activate.php",
      dataType: "json",
      success: function (data) {
        xhr = "";
        $(".loading").remove();
        if (!data.success) {
          $("#profile")
            .find(".modal__error .modal__note_important")
            .html(data.error);
          $("#profile").find(".modal__error").show();
          console.log(2090);
        } else {
          if ($type == "phone") {
            var timeinterval = setInterval(function () {
              var $time = getTimeRemaining(data.time);
              if ($time.seconds <= 0 && $time.minutes <= 0) {
                $(".clock-timer__counter").text("0:00");
                clearInterval(timeinterval);
              } else {
                $(".clock-timer__counter").text(
                  $time.minutes + ":" + $time.seconds
                );
              }
            }, 100);
            $(".popup_phoneVerification").show(); //открытие окошка
          }
          if ($type == "email") {
            $(".popup_emailVerification").show();
          }
        }
      },
    });
  });*/
  
  $(document).on("click", ".disabled", function (e) {
    e.preventDefault();
    if ($(this).data("target") != undefined) {
      $(".modal,.popup2").hide();
      $($(this).data("target")).show();
    }
    $(this).removeClass("disabled");
  });
});
$(function () {
  $(".vipclub__row .vipclub__item").on("click", function () {
    var infoBlock = $($(this).data("target"));
    var padding_for = infoBlock.height() + 76;
    $(".vipclub__row").not(infoBlock.parent()).css("padding-bottom", "0");
    $(".vipclub__info").not(infoBlock).removeClass("vipclub__info_open");
    if (infoBlock.hasClass("vipclub__info_open")) {
      infoBlock.removeClass("vipclub__info_open");
      infoBlock.parent().css("padding-bottom", 0);
    } else {
      infoBlock.addClass("vipclub__info_open");
      infoBlock.parent().css("padding-bottom", padding_for);
      if ($(this).is(":first-child")) {
        infoBlock.find(".vipclub__arrow").removeClass("vipclub__arrow_right");
        infoBlock.find(".vipclub__arrow").addClass("vipclub__arrow_left");
      } else if ($(this).is(":nth-last-child(2)")) {
        infoBlock.find(".vipclub__arrow").removeClass("vipclub__arrow_left");
        infoBlock.find(".vipclub__arrow").addClass("vipclub__arrow_right");
      } else {
        infoBlock
          .find(".vipclub__arrow")
          .removeClass("vipclub__arrow_left vipclub__arrow_right");
      }
    }
  });
});
$(function () {
  $('[data-toggle="tab"]').on("click tap swipe", function (e) {
    e.preventDefault();
    var $id = $(this).attr("href"),
      $viewport = $("html, body");

    if ($id == undefined) {
      $id = $(this).data("target");
    }
    if ($(this).hasClass("levels-table__item") && $(window).width() > 768) {
      $viewport.stop().animate(
        {
          scrollTop: $(".levels-table").offset().top,
        },
        "slow",
        function () {
          $viewport.off(
            "scroll mousedown wheel DOMMouseScroll mousewheel keyup touchmove"
          );
        }
      );
      $viewport.bind(
        "scroll mousedown DOMMouseScroll mousewheel keyup",
        function (e) {
          if (
            e.which > 0 ||
            e.type === "mousedown" ||
            e.type === "mousewheel"
          ) {
            $viewport
              .stop()
              .unbind("scroll mousedown DOMMouseScroll mousewheel keyup");
          }
        }
      );
    }
    $($id).parent().find(">div").removeClass("active");
    $($id).addClass("active");
    $(".modal__error").hide();
    if ($(this).data("remote") != undefined && $(this).data("remote") != "") {
      var $content = $(this).data("content");
      $.post($(this).data("remote"), {}, function (data) {
        var $response = $(data).find($content);
        var $html = $response.html();
        $($id).html($html);
      });
    }
    if (!$(this).hasClass("levels-table__item")) {
      if ($(this).hasClass("lottery__tabitem")) {
        $(this)
          .parent()
          .find(".lottery__tabitem")
          .removeClass("lottery__tabitem_active");
        $(this).addClass("lottery__tabitem_active");
      } else {
        $(this).parent().find(".tab__item").removeClass("tab__item_active");
        $(this).addClass("tab__item_active");
		$('.tab__action.head_action').removeClass('active');
      }
    }
  });
});
$(function () {
  $(document).off("click", "[data-toggle]");
  $(document).on("click", "[data-toggle]", function (e) {
    e.preventDefault();
    var $el = $(this);
    if ($el.attr("data-toggle") == "add-fav") {
      $.get(
        "engine/ajax/add_to_favorites.html",
        { id: $(this).data("id"), },
        function (data) {
          if (data.success) {
            $el.addClass("in_favorites");
            $el.attr("data-toggle", "remove-fav");
            $el.attr("title", "Удалить из избранного");
          } else {
            $(".popup_favoritesAddedFail").css("position", "fixed").show();
            $("html").addClass("modal_open");
            $el.removeClass("in_favorites");
          }
        },
        "json"
      );
    } else if ($el.attr("data-toggle") == "remove-fav") {
      $.get(
        "engine/ajax/remove_favorites.html",
		{ id: $el.data("id"), },
        function (data) {
          $el.removeClass("in_favorites");
          $el.attr("data-toggle", "add-fav");
          $el.attr("title", "Добавить в избранное");
        },
        "json"
      );
    }
  });
});
function user_ajax(form, action) {
  data = $(form).serialize();
  $(form).closest(".modal,.popup2").append(preloader);
  error_box = $(form).find(".modal__error .modal__note_important").empty();
  $.post(
    "engine/ajax/user.html",
    data + "&action=" + action,
    function (res) {
      $(".loading").remove();
      if (res.success == true) {
        if (res.txt) {
          $(form).find(".modal__error .modal__note_important").html("");
          $(form).find(".modal__error").show();
        }
        window.location.reload();
      } else {
        if (res.error) {
          error_box.html(res.error);
          $(form).find(".modal__error").show();
        }
      }
    },
    "json"
  );
  return false;
}
function decimalAdjust(type, value, exp) {
  if (typeof exp === "undefined" || +exp === 0) {
    return Math[type](value);
  }
  value = +value;
  exp = +exp;
  if (isNaN(value) || !(typeof exp === "number" && exp % 1 === 0)) {
    return NaN;
  }
  value = value.toString().split("e");
  value = Math[type](+(value[0] + "e" + (value[1] ? +value[1] - exp : -exp)));
  value = value.toString().split("e");
  return +(value[0] + "e" + (value[1] ? +value[1] + exp : exp));
}
if (!Math.round10) {
  Math.round10 = function (value, exp) {
    return decimalAdjust("round", value, exp);
  };
}
$(document).on("change keyup input click", "#exchange-input", function () {
  if (this.value.match(/[^0-9]/g)) {
    this.value = this.value.replace(/[^0-9]/g, "");
  }
  var $value = $(this).val() * $(this).data("cours");
  $("#exchange-output").val(Math.round10($value, -2));
  $("#exchange-input").val($(this).val() * 1);
});
$(document).on("change keyup input click", "#exchange-output", function () {
  this.value = this.value.replace(/[^\d\.]/g, "");
  var $value = $(this).val() / $(this).data("cours");
  $("#exchange-input").val(Math.round10($value, -2));
  $("#exchange-output").val($(this).val() * 1);
});
$(document).on(
  "change keyup input click",
  "#exchange-input-token",
  function () {
    if (this.value.match(/[^0-9]/g)) {
      this.value = this.value.replace(/[^0-9]/g, "");
    }
    var $value = $(this).val() * $(this).data("cours");
    $("#exchange-input-token").val($(this).val() * 1);
    $("#exchange-output-token").val(parseInt($value).toFixed(0));
  }
);
$(document).on(
  "change keyup input click",
  "#exchange-output-token",
  function () {
    this.value = this.value.replace(/[^\d\.]/g, "");
    var $value = $(this).val() / $(this).data("cours");
    $("#exchange-input-token").val(parseInt($value).toFixed(0));
    $("#exchange-output-token").val($(this).val() * 1);
  }
);
$(document).ready(function () {
  /*if (window.location.hostname.indexOf("casino") == -1 && window.location.hostname.indexOf("vulkan") == -1 && window.location.hostname.indexOf("vulcan") == -1) {
    var popup_404 = $('.warning-wrapper').clone();
    $('body').html(popup_404);
    show404modal();
}*/
  //console.log('Ready');
  $(".toggler[data-target]").on("click", function () {
    var target = $(this).data("target");
    var target_elem = $(this)
      .parent()
      .find('.toggle-data[data-value="' + target + '"]');
    $('.toggle-data[data-value!="' + target + '"]').hide();
    target_elem.slideToggle();
  });
  $(".toggle-opera").on("click", function (e) {
    $('.toggle-data[data-value!="opera"]').hide();
    $('.toggle-data[data-value="opera"]').show();
  });
  if (
    $.cookie("hide_antiblock_popup") != "true" &&
    window.location.href.indexOf("antiblock") == -1
  ) {
    $("#popup-antiblock").removeClass("hidden");
  }
  var calculateSize = function ($el) {
    var width = $el.width();
    var height = $el.height();
    var maxWidth = $el.parent().parent().width();
    var maxHeight = $el.parent().parent().height();
    var proportions = 3 / 4;
    if (maxHeight / maxWidth < proportions) {
      height = Math.floor(maxHeight);
      width = Math.floor(height / proportions);
    } else {
      width = Math.floor(maxWidth);
      height = Math.floor(width * proportions);
    }
    $el.css({
      width: width + "px",
      height: height + "px",
      display: "block",
    });
    return {
      width: width,
      height: height,
    };
  };
  setTimeout(function () {
    calculateSize($(".gameplay__canvas_inner object"));
  }, 100);
  $("form button.validate").click(function (e) {
    e.preventDefault();
    var $form = $(this).closest("form");
    var hasErrors = false;
    $form.find(".modal__error").hide();
    $form.find(".modal__note_important").html("");
    $(this)
      .closest("form")
      .find('[required][type="checkbox"]')
      .each(function (index, el) {
        if (!$(el)[0].checked) {
          hasErrors = true;
          $form.find(".modal__error").show();
          $form
            .find(".modal__note_important")
            .append($(el).attr("data-error-message"));
        }
      });
    if (!hasErrors) {
      $form.submit();
    }
  });
  $(window).resize(function () {
    calculateSize($(".gameplay__canvas_inner object"));
  });
  $(window).scroll(function () {
    if ($(this).scrollTop() > 0) {
      $(".scroller").fadeIn();
    } else {
      $(".scroller").fadeOut();
    }
  });
  $(".popup_tournamentGames").scroll(function () {
    if ($(this).scrollTop() > 0) {
      $(".scroller").fadeIn();
    } else {
      $(".scroller").fadeOut();
    }
  });
  $(".scroller").click(function () {
    $("body,html").animate(
      {
        scrollTop: 0,
      },
      400
    );
    if ($(".popup_tournamentGames").css("display") == "block") {
      $(".popup_tournamentGames").animate(
        {
          scrollTop: 0,
        },
        400
      );
    }
    return false;
  });
});

function searchGame(text) {
  if (text == "") {
    $(".popup__gallery .main__item.preview").show();
    return true;
  }
  var search = text.toLowerCase();
  $.each($(".popup__gallery .main__item.preview"), function () {
    var $title = $(this).find(".preview__title").html().toLowerCase();
    $(".popup__gallery").perfectScrollbar("update");
    if ($title.indexOf(search) < 0) {
      $(this).hide();
    } else {
      $(this).show();
    }
  });
}

$(function () {
  $(".js-userpanel-button").on("click", function () {
    $(this).toggleClass("user-toppanel__button_close");
    $(this).toggleClass("js-userpanel-button-close");
    $(".header__panel").toggleClass("open");
    $(".header").toggleClass("header_panel-open");
    $(".header__wrap").toggleClass("header__wrap_scroll");
    $(".header__wrap_scroll").not(".ps-container").perfectScrollbar({
      theme: "hidden",
      suppressScrollY: true,
      scrollXMarginOffset: 5,
    });
    $(".header__toppanel").toggleClass("open");
    $("body, html").toggleClass("hidden");
  });
});

$(function () {
  $(".js-toppanel-button").on("click", function () {
    $(this).toggleClass("toppanel__button_close");
    $(this).toggleClass("js-toppanel-button-close");
    $(".js-mobilenav-dropdown").toggleClass("open");
    $(".header__toppanel").toggleClass("open");
    $("body, html").toggleClass("hidden");
  });
});

$(function () {
  $(".js-promo-details-button").on("click", function () {
    $(".promo-details__dropdown").slideToggle("fast").toggleClass("active");
  });
});

$(function () {
  if ($(".finecountdown").length > 0) {
    $(".finecountdown").each(function () {
      var suma = $(this).data("sum").toString();
      var i = (k = 0);
      $(this).empty();
      for (i = suma.length - 1; i >= 0; --i) {
        if ($(this).hasClass("countdown") == true) {
          $(this).prepend(
            '<span class="countdown__item js-countdown__item">' +
              suma[i] +
              "</span>"
          );
          if (++k % 3 == 0 && k > 0 && k <= suma.length - 1)
            $(this).prepend('<span class="countdown__divider"></span>');
        } else {
          $(this).prepend(
            '<span class="js-countdown__item">' + suma[i] + "</span>"
          );
          if (++k % 3 == 0 && k > 0 && k <= suma.length - 1)
            $(this).prepend(" , ");
        }
      }
    });
  }
});

function get_cookie(cookie_name) {
  var results = document.cookie.match(
    "(^|;) ?" + cookie_name + "=([^;]*)(;|$)"
  );
  if (results) return unescape(results[2]);
  else return null;
}

function delete_cookie(cookie_name) {
  var cookie_date = new Date();
  cookie_date.setTime(cookie_date.getTime() - 1);
  document.cookie = cookie_name += "=; expires=" + cookie_date.toGMTString();
}

function set_cookie(name, value, exp_y, exp_m, exp_d, path, domain, secure) {
  var cookie_string = name + "=" + escape(value);
  if (exp_y) {
    var expires = new Date(exp_y, exp_m, exp_d);
    cookie_string += "; expires=" + expires.toGMTString();
  }
  if (path) cookie_string += "; path=" + escape(path);
  if (domain) cookie_string += "; domain=" + escape(domain);
  if (secure) cookie_string += "; secure";
  document.cookie = cookie_string;
}
function countDownToMidnight(elem) {
  function countDownToMidnightTick(elem) {
    var toDate = new Date();
    var tomorrow = new Date();
    tomorrow.setHours(24, 0, 0, 0);
    var diffMS = tomorrow.getTime() / 1000 - toDate.getTime() / 1000;
    var diffHr = Math.floor(diffMS / 3600);
    diffMS = diffMS - diffHr * 3600;
    var diffMi = Math.floor(diffMS / 60);
    diffMS = diffMS - diffMi * 60;
    var diffS = Math.floor(diffMS);
    var result = diffHr < 10 ? "0" + diffHr : diffHr;
    result += ":" + (diffMi < 10 ? "0" + diffMi : diffMi);
    result += ":" + (diffS < 10 ? "0" + diffS : diffS);
    $(elem).text(result);
  }
  countDownToMidnightTick(elem);
  setInterval(function () {
    countDownToMidnightTick(elem);
  }, 1000);
}

function hideTgTooltip() {
  $(this).closest("#header-tooltip").remove();
  let date = new Date();
  date.setTime(date.getTime() + 12 * 60 * 60 * 1000);
  $.cookie("hide_tg_tooltip", true, {
    path: "/",
    expires: date,
  });
  $.removeCookie("start_tg_tooltip");
}
function tgTimer(element) {
  if ($.cookie("hide_tg_tooltip") === "true") {
    return;
  }
  let state = {
    left: 0,
    start: 0,
    timeout: 2 * 60 * 60 * 1000,
  };
  if ($.cookie("start_tg_tooltip") === undefined) {
    $.cookie("start_tg_tooltip", Date.now());
  }
  state.start = parseInt($.cookie("start_tg_tooltip"));
  let render = () => {
    let pad = (number) => {
      return number < 10 ? "0" + number : number;
    };
    var hour, minute, seconds;
    seconds = Math.floor(state.left / 1000);
    minute = Math.floor(seconds / 60);
    hour = Math.floor(minute / 60);
    seconds = pad(seconds % 60);
    minute = pad(minute % 60);
    hour = pad(hour % 24);
    $(element).text(hour + ":" + minute + ":" + seconds);
  };
  let count = () => {
    state.left = state.timeout - (Date.now() - state.start);

    if (state.left < 0) {
      hideTgTooltip();
    }
  };
  count();
  setInterval(() => {
    count();
    render();
  }, 1000);
}
tgTimer("#tg-timer");

$(".confirm-email__close").on("click", function () {
  $(this).closest("#header-tooltip").remove();
  let date = new Date();
  date.setTime(date.getTime() + 12 * 60 * 60 * 1000);
  $.cookie("hide_tg_tooltip", true, {
    path: "/",
    expires: date,
  });
  $.removeCookie("start_tg_tooltip");
});

if ($.cookie("hide_tg_tooltip") == "true") {
  $(".tg-tooltip").remove();
}
$(".main-nav__item_subnav a").on("click", function (e) {
  if ($(window).width() < 768) {
    $(".hero__submenu_mobile_nav").toggleClass("active");
    e.preventDefault();
  }
});
// countDownToMidnight('#tg-timer');
/*(function(factory){if(typeof define==="function"&&define.amd){}else{}})(function(datepicker){datepicker.regional.ru={closeText:"Закрыть",prevText:"&#x3C;Пред",nextText:"След&#x3E;",currentText:"Сегодня",monthNames:["Январь","Февраль","Март","Апрель","Май","Июнь","Июль","Август","Сентябрь","Октябрь","Ноябрь","Декабрь"],monthNamesShort:["Янв","Фев","Мар","Апр","Май","Июн","Июл","Авг","Сен","Окт","Ноя","Дек",],dayNames:["воскресенье","понедельник","вторник","среда","четверг","пятница","суббота",],dayNamesShort:["вск","пнд","втр","срд","чтв","птн","сбт"],dayNamesMin:["Вс","Пн","Вт","Ср","Чт","Пт","Сб"],weekHeader:"Нед",dateFormat:"dd.mm.yy",firstDay:1,isRTL:false,showMonthAfterYear:false,yearSuffix:"",};datepicker.setDefaults(datepicker.regional.ru);return datepicker.regional.ru});
*/
function numberWithSpaces(x) {
  return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, " ");
}
function show404modal() {
  $(".warning-wrapper").fadeIn();
}
function hide404modal() {
  $(".warning-wrapper").fadeOut();
}

$(function() {
    var $q = function(q, res){
          if (document.querySelectorAll) {
            res = document.querySelectorAll(q);
          } else {
            var d=document
              , a=d.styleSheets[0] || d.createStyleSheet();
            a.addRule(q,'f:b');
            for(var l=d.all,b=0,c=[],f=l.length;b<f;b++)
              l[b].currentStyle.f && c.push(l[b]);

            a.removeRule(0);
            res = c;
          }
          return res;
        }
      , addEventListener = function(evt, fn){
          window.addEventListener
            ? this.addEventListener(evt, fn, false)
            : (window.attachEvent)
              ? this.attachEvent('on' + evt, fn)
              : this['on' + evt] = fn;
        }
      , _has = function(obj, key) {
          return Object.prototype.hasOwnProperty.call(obj, key);
        }
      ;
    function loadImage (el, fn) {
      var img = new Image()
        , src = el.getAttribute('data-src');
        img.onload = function() {
          if (!! el.parent)
            el.parent.replaceChild(img, el)
          else
            el.src = src;
          fn? fn() : null;
        }
      if(typeof src !== "undefined"){    
        img.src = src;
      }      
    }
    function elementInViewport(el) {
      var rect = el.getBoundingClientRect()
      return (
         rect.top    >= 100
      && rect.left   >= 0
      && rect.top <= (window.innerHeight || document.documentElement.clientHeight)
      )
    }
      var images = new Array()
        , query = $q('img.lazy')
        , processScroll = function(){
            for (var i = 0; i < images.length; i++) {
              if (elementInViewport(images[i])) {
                loadImage(images[i], function () {
                  images.splice(i, i);
                });
              }
            };
          }
        ;
      for (var i = 0; i < query.length; i++) {
        images.push(query[i]);
      };
      processScroll();
      addEventListener('scroll',processScroll, {passive: true}); //
    });

$(function() {
    var $q = function(q, res){
          if (document.querySelectorAll) {
            res = document.querySelectorAll(q);
          } else {
            var d=document
              , a=d.styleSheets[0] || d.createStyleSheet();
            a.addRule(q,'f:b');
            for(var l=d.all,b=0,c=[],f=l.length;b<f;b++)
              l[b].currentStyle.f && c.push(l[b]);

            a.removeRule(0);
            res = c;
          }
          return res;
        }
      , addEventListener = function(evt, fn){
          window.addEventListener
            ? this.addEventListener(evt, fn, false)
            : (window.attachEvent)
              ? this.attachEvent('on' + evt, fn)
              : this['on' + evt] = fn;
        }
      , _has = function(obj, key) {
          return Object.prototype.hasOwnProperty.call(obj, key);
        }
      ;
    function loadImage (el, fn) {
      var img = new Image()
        , src = el.getAttribute('data-src');
      img.onload = function() {
        if (!! el.parent)
          el.parent.replaceChild(img, el)
        else
          el.src = src;
        fn? fn() : null;
      }
      img.src = src;
    }
    function elementInViewport(el) {
      var rect = el.getBoundingClientRect()
      return (
         rect.top    >= 100
      && rect.left   >= 0
      && rect.top <= (window.innerHeight || document.documentElement.clientHeight)
      )
    }
      var images = new Array()
        , query = $q('img.lazy')
        , processScroll = function(){
            for (var i = 0; i < images.length; i++) {
              if (elementInViewport(images[i])) {
                loadImage(images[i], function () {
                  images.splice(i, i);
                });
              }
            };
          }
        ;
      for (var i = 0; i < query.length; i++) {
        images.push(query[i]);
      };
      processScroll();
      addEventListener('scroll',processScroll, {passive: true}); //
    });
  $('.slider_small').not('.slick-initialized').slick({
    infinite: true,
    //autoplay: false,
    //autoplaySpeed: 1500,
    slidesToShow: 9,
    slidesToScroll: 1,
    dots: false
  });
$('.slider_gameplay').not('.slick-initialized').slick({
    infinite: true,
    autoplay: true,
    autoplaySpeed: 4000,
    pauseOnFocus: true,
    slidesToShow: 7,
    slidesToScroll: 1,
    dots: false,
    //fade:true,
    focusOnSelect:true,
    draggable:true,
  });
  $('.show__nav').on('click', function () {
    $('.gameplay-nav-small').slideToggle();
  });
  $('.gameplay-nav__item').on('click', function () {
    $('.gameplay-nav').find('li').not($(this)).removeClass('gameplay-nav__item_active');
    $('.gameplay__slider').not($($(this).data('target'))).removeClass('gameplay__slider_open').css('display', 'none');
    if ($(this).hasClass('gameplay-nav__item_active')) {
      $(this).removeClass('gameplay-nav__item_active');
      $($(this).data('target')).removeClass('gameplay__slider_open').css('display', 'none');
    } else {
      $('.slider_gameplay').slick('slickPrev');
      $(this).addClass('gameplay-nav__item_active');
      $($(this).data('target')).addClass('gameplay__slider_open').css('display', 'block');
    }
  });