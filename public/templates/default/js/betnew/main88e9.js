

// *** START MENU *** //


$(document).on("click", ".close-menu", function(){
	$(this).toggleClass('active');
	$('.topmenu').toggleClass('active');
	$('.overflow-topmenu').toggleClass('active');
	$('.block-content').toggleClass('menu-open');
	$('.footer').toggleClass('menu-open');
});


$(document).on("click", ".open-menu-mobile", function(){
	$(this).toggleClass('active');
	$('.topmenu').toggleClass('active');
	$('.overflow-topmenu').toggleClass('active');
	$('.block-content').toggleClass('menu-open');
	$('.footer').toggleClass('menu-open');
});


$(document).on("click", ".overflow-topmenu", function(){
	$(this).toggleClass('active');
	$('.open-menu-mobile').toggleClass('active');
	$('.topmenu').toggleClass('active');
	$('.block-content').toggleClass('menu-open');
	$('.footer').toggleClass('menu-open');
});


$(document).on("click", ".one-menu.open-menu .h-one-open-menu a", function(){
	$(this).parent().parent().toggleClass('active');
});


if ($(window).width() < 992) {
	$(document).on("click", ".topmenu .one-menu.open-menu a.a-to-open-d", function(){
		event.preventDefault();
		// $('header').find('.topmenu').addClass('to-open-d');
		$(this).parent().parent().addClass('active');
	});
}


$(".open-lang a").each(function(){
	var href = $(this).attr('href');
	$url_page = location.pathname;
	$new_url_lang = $url_page + href;
	$(this).attr('href', $new_url_lang);
});


$(document).on("click", ".btn-lang", function(){
	$(this).toggleClass('active');
	$('.open-lang').toggleClass('active');
});


// *** END MENU *** //


// *** START SPORT *** //


$url_sport = location.pathname;
if($url_sport == "https://betfury.development-casino.com/sport/"){
	$('.btn-swap a').removeClass('active');
	$('.btn-swap a:nth-child(2)').addClass('active');
}


// *** END SPORT *** //


// *** START ONLOAD *** //


var width_body = $('html body').width();
if(width_body > 991){
	$('.close-menu').trigger('click');
} else {
	$url_page = location.pathname;
	console.log($url_page);
	if($url_page == 'https://betfury.development-casino.com/'){
		console.log($url_page);
		// $('.open-menu-mobile').trigger('click');
	}
}


setTimeout(function() {
	$('.index-search input[name=search]').val('');
}, 1000);


// *** END ONLOAD *** //


// *** START MODAL *** //


$(document).on("click", ".modal-overflow", function(){
	close_small();
});

function close_small(){
	$(".modal-win").removeClass("active");
	$(".modal-overflow").removeClass("active");
	$(".modal-container").removeClass("active");
}

function modal_container(){
	$(".modal-container").addClass("active");
}

$(document).on("click", ".close-small", function(){
	close_small();
});


// *** END MODAL *** //


// *** START GAME SWIPER *** //


var swiper = new Swiper(".game-swiper", {
	slidesPerView: 2,
	spaceBetween: 16,
	freemode: false,
	// slidesPerGroup: 3,
	navigation: {
		nextEl: ".swiper-button-next",
		prevEl: ".swiper-button-prev",
	},
    breakpoints: {
		768: {
			slidesPerView: 6,
			spaceBetween: 16,
		},
		580: {
			slidesPerView: 4,
			spaceBetween: 16,
		},
	},
});


$(document).on("click", ".head-btn-slide.left-slide", function(){
	$(this).parent().parent().parent().find(".swiper-button-prev").trigger('click');
});


$(document).on("click", ".head-btn-slide.right-slide", function(){
	$(this).parent().parent().parent().find(".swiper-button-next").trigger('click');
});


// *** END GAME SWIPER *** //


// *** START TAB GAMERS *** //


$(document).on("click", ".one-tab-gamers-statistics", function(){
	$('.one-tab-gamers-statistics').removeClass('active');
	$(this).addClass('active');
});

$(document).on("click", "#open-tab-1", function(){
	$('.one-list-tab-gamers-statistics').removeClass('active');
	$('#tab-1').addClass('active');
});

$(document).on("click", "#open-tab-2", function(){
	$('.one-list-tab-gamers-statistics').removeClass('active');
	$('#tab-2').addClass('active');
});

$(document).on("click", "#open-tab-3", function(){
	$('.one-list-tab-gamers-statistics').removeClass('active');
	$('#tab-3').addClass('active');
});


// *** END TAB GAMERS *** //


// *** START FOOTER SWIPER *** //


var swiper = new Swiper(".footer-swiper", {
	loop: true,
	slidesPerView: 4,
	spaceBetween: 0,
	autoplay: {
		delay: 2500,
		disableOnInteraction: false,
	},
	pagination: {
		el: ".swiper-pagination",
		clickable: true,
	},
	navigation: {
		nextEl: ".swiper-button-next",
		prevEl: ".swiper-button-prev",
	},
	breakpoints: {
		991: {
			slidesPerView: 8,
			spaceBetween: 0,
		},
		768: {
			slidesPerView: 6,
			spaceBetween: 0,
		},
		640: {
			slidesPerView: 5,
			spaceBetween: 0,
		},
	},
});


// *** END FOOTER SWIPER *** //


// *** START FAVORITES *** //


$(".star.favorite.no_favorites").on("click", function(){
	$(this).addClass(".in_favorites");
	$(this).removeClass(".no_favorites");
});


// *** END FAVORITES *** //


// *** START PROVIDERS *** //


$(document).on("click", ".head-subcat-tags a", function(){
	$('.head-subcat-tags .subcat-tags').removeClass('active');
	$(this).addClass('active');
	var filter = $(this).data('num-filter');
	
	$(".list-game .game-slide").each(function(){
		var tag = $(this).find('.meta-game-slide').text();
		$(this).addClass('no-active');
		if(tag == filter){
			$(this).removeClass('no-active');
			$(this).addClass('active');
		}
		if(filter == 'all'){
			$(this).removeClass('no-active');
		}
	});
});


$(document).on("click", ".head-subcat-select", function(){
	$(this).find('.subcat-select').toggleClass('active');
	$(this).find('.subcat-select-drop').toggleClass('active');
});


const div = document.querySelector( '.head-subcat-select');
 
document.addEventListener( 'click', (e) => {
	const withinBoundaries = e.composedPath().includes(div);
	if ( ! withinBoundaries ) {
		$('.head-subcat-select .subcat-select').removeClass('active');
		$('.head-subcat-select .subcat-select-drop').removeClass('active');
	}
})


// *** END PROVIDERS *** //


// *** START GAME SEARCH *** //


$(".index-search input[name=search]").on("click", function(){
	close_small();
	modal_container();
	$(".modal-overflow").addClass("active");
    $(".modal-win#search").addClass("active");
	$(".modal-win#search .search-field input").focus();
});


$(".modal-win#search .search-field input").on("input", function(){
	var s_q = $(this).val();
	$.ajax({
		type: "GET",
		datatype: "JSON",
		url: "/engine/ajax/game_list.php?type=json&bao=1&q="+s_q,
		success: function(search){
			var search_one = '';
			$(this).each(function(){
			var search_two = '';
				const arr = search.replaceAll('}{', '};{').split(';').map(JSON.parse);
				for(const value of arr) {
					var id_game = value.g_id;
					var name_game = value.g_name;
					var title_game = value.g_title;
					var provider_game = value.g_path;
					var theme_name = $('body').find('.theme_url').text();
					theme_name = theme_name.replace('https://betfury.development-casino.com/templates/', '', theme_name);
					var img_game = "/templates/default/ico/"+name_game+".jpg"; //заменил чтобы грузились с общей папки
					var play_game = "/slots/"+provider_game+"/"+name_game+"?real";
					var demo_game = "/slots/"+provider_game+"/"+name_game;
					
					var search_three = "<div class = 'game-slide'><div class = 'img-game-slide' style = 'background-image: url("+img_game+");'></div><div class = 'hover-game-slide'><div class = 'h-game-slide'>"+title_game+"</div><a href = '"+play_game+"' class = 'play-game-slide'><svg focusable='false' aria-hidden='true'><use xlink:href='/templates/"+theme_name+"/img/betnew/svg-sprite.e1149d9.svg#icon-play'></use></svg></a><div class = 'provider-game-slide'><a href = '"+demo_game+"'>Demo</a></div><div class = 'provider-game-slide'>"+provider_game+"</div></div></div>";
					search_two = search_two + search_three;
				}
				search_one = search_two;
			});
			$(".modal-win#search .parent-search-list-game .list-game").html(search_one);
		},
		error: function() {
		}
	});
});


// *** END GAME SEARCH *** //





/*
var endDate = new Date("Mar 15, 2023 12:00:00").getTime();

var timer = setInterval(function() {
	
	
	let now = new Date().getTime(); 
let t = endDate - now;
	
	
	if (t >= 0) {
    let days = Math.floor(t / (1000 * 60 * 60 * 24));
    let hours = Math.floor((t % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
    let mins = Math.floor((t % (1000 * 60 * 60)) / (1000 * 60));
    let secs = Math.floor((t % (1000 * 60)) / 1000);
}
	
	document.getElementById("timer-days").innerHTML = days + 
"<span class='label'>DAY(S)</span>";
document.getElementById("timer-hours").innerHTML= ("0" + hours).slice(-2) +
"<span class='label'>HR(S)</span>";
document.getElementById("timer-mins").innerHTML= ("0" + mins).slice(-2) +
"<span class='label'>MIN(S)</span>";
document.getElementById("timer-secs").innerHTML= ("0" + secs).slice(-2) +
"<span class='label'>SEC(S)</span>";
}, 1000);
*/


























// *** START GAME *** //


$(".fullsc.block").on("click", function(){
    $(".game-modal").toggleClass("full-sc");
});


$(".modal-game-name .close").on("click", function(){
    $(this).parent().next("iframe").remove()
    $(this).parent().remove()
});


function toggleFullscreen(elem) {
  elem = elem || document.documentElement;
  if (!document.fullscreenElement && !document.mozFullScreenElement &&
    !document.webkitFullscreenElement && !document.msFullscreenElement) {
    if (elem.requestFullscreen) {
      elem.requestFullscreen();
    } else if (elem.msRequestFullscreen) {
      elem.msRequestFullscreen();
    } else if (elem.mozRequestFullScreen) {
      elem.mozRequestFullScreen();
    } else if (elem.webkitRequestFullscreen) {
      elem.webkitRequestFullscreen(Element.ALLOW_KEYBOARD_INPUT);
    }
  } else {
    if (document.exitFullscreen) {
      document.exitFullscreen();
    } else if (document.msExitFullscreen) {
      document.msExitFullscreen();
    } else if (document.mozCancelFullScreen) {
      document.mozCancelFullScreen();
    } else if (document.webkitExitFullscreen) {
      document.webkitExitFullscreen();
    }
  }
}


$("#btnFullscreen").on("click", function(){
	toggleFullscreen();
});


$(".screen .block").on("click", function(){
    $(this).addClass("active")
    $(this).prev(".block").removeClass("active")
    $(this).next(".block").removeClass("active")
    if($(".screen .block.active").hasClass("multi")){
        $(".game-modal").addClass("multi");
        $(".top-pannel").addClass("hide")
    }
    if($(".screen .block.active").hasClass("one")){
        $(".game-modal").removeClass("multi");
        $(".top-pannel").removeClass("hide")
    }
});


$(".game-modal .game .search input").on("click", function(){
	close_small();
	modal_container();
	$(".modal-overflow").addClass("active");
    $(".modal-win#search").addClass("active");
	var num = $(this).parent().parent().attr("data-num");
	$(".modal-win#search .search-field input").focus();
	$(".modal-win#search").addClass("active-game"+num);
	
	$(document).on("click", ".active-game"+num+" .parent-search-list-game .game-slide a", function(){
		event.preventDefault();
		var name_game = $(this).parent().parent().find(".h-game-slide").text();
		var href_link = $(this).attr("href");
		
		var iframe = $('.game.none-search#win'+num+' iframe');
		
		$.ajax({
			type: "POST",
			dataType : 'html',
			url: href_link,
			success: function(content_gp){
				var frame_gp = $(content_gp).find(".game-modal .game:nth-child(1) iframe").attr("src");
				$(iframe).attr('src', frame_gp);
			}
		});
		
		$(".game.none-search#win"+num+" .modal-game-name span").text(name_game);
		$(".game.none-search#win"+num).removeClass("none-search");
		$(".modal-win#search").removeClass("active-game"+num);
		$(".modal-win#search .search-field input").val('');
		$(".modal-win#search .close-small").trigger("click");
	});
});


$(".close-game").on("click", function(){
	$(this).parent().parent().addClass("none-search");
	$(this).parent().parent().find("iframe").attr("src", "");
});


var has_game = $('body').hasClass('game-page');
if(has_game == true){
	setTimeout(function() {
		$('.open-menu-mobile.active').trigger('click');
	}, 1000);
}


// *** GAME END *** //


// *** COOKIE START *** //


/*let cook = localStorage.getItem('cookie');
console.log(cook);
if(cook != "false"){
	$('.cookies-policy').addClass('active-cook');
}

$("#ok_cookie_button").on("click", function(){
    localStorage.setItem('cookie', 'false');
	let cook = localStorage.getItem('cookie');
	console.log(cook);
	if(cook = "false"){
		$('.cookies-policy').removeClass('active-cook');
	}
});*/


// *** COOKIE END *** //


// *** FAQ START *** //


$(".faq-title").on("click", function(){
    $(this).toggleClass("active")
    $(this).next(".faq-section").toggleClass("active")
});

$(".faq-item b").on("click", function(){
    if($(this).parent().hasClass("active")){
        $(this).parent().removeClass("active")       
    }else{
        $(this).parent().parent().find(".faq-item").removeClass("active")
        $(this).parent().addClass("active")    
    }
});


// *** FAQ END *** //


// *** START PAY *** //


$(".tab").on("click", function(){
    var num = $(this).index()
    $(".tab").removeClass("active")
    $(this).addClass("active")
    $(".tab-cont").removeClass("active")
    $(".tab-cont").eq(num).addClass("active")
});


// *** END PAY *** //


// *** START CF *** //


function CF(){
	var email = $(".form-cf input[name='email-cf'").val();
	var text = $(".form-cf textarea[name='text-cf'").val();
	if(email == "" || text == ""){
		$(".reply-contact-form").text("Заполните все поля");
	}
	if(email != "" && text != ""){
		$.ajax({
			type: "POST",
			url:  "/engine/ajax/send.php",
			data: {"email": email, "text": text},
			success: function(){
				$(".reply-contact-form").text("Сообщение отправлено");
				setTimeout(function(){
					$(".reply-contact-form").text("");
				}, 5000);
			}
		});
	}
}


// *** END CF *** //


// *** START PIN *** //


function Code(){
	var enter_pin = $(".enter-pin input#form-pin").val();
	console.log(enter_pin)
	if(enter_pin == ""){
		$(".enter-pin .reply-pin-block").text("Error");
	} else {
		$.ajax({
			type: "POST",
			url:  "/engine/ajax/pin.php",
			data: {"pin": enter_pin},
			success: function(text){
				result = $.parseJSON(text);
				$(".enter-pin .reply-pin-block").text(result.error);
				console.log(text);
			}
		});
	}
}


// *** END PIN *** //


// *** START AUTH *** //


$(document).on("click", ".open-login", function(){
	close_small();
	modal_container();
	$(".modal-win#login").addClass("active");
	$(".modal-overflow").addClass("active");
});


function Auth(){
  var login = $(".login-form input[name='email']").val();
  var pass = $(".login-form input[name='pass']").val();
  $.ajax({
    type: "POST",
    dataType : 'json',
    url:  "/engine/ajax/user.php?action=auth",
    data: {"email": login, "pass": pass},
    success: function(auth){
      if(auth.success == true){
        window.location.reload();
      }
      if(auth.success == false){
        $(".login-form .answer").text(auth.error);
      }
    }
  });
}


$(document).on("click", ".login-form button.submit", function(){
	event.preventDefault();
	Auth();
});


// *** END AUTH *** //


// *** START REMIND *** //


$(document).on("click", ".open-remind", function(){
	close_small();
	modal_container();
	$(".modal-win#remind").addClass("active");
	$(".modal-overflow").addClass("active");
});


function Remind(){
  var login = $(".remind-form input[name='email']").val();
  console.log(login);
  
  $.ajax({
    type: "POST",
    dataType : 'json',
    url:  "/engine/ajax/user.php?action=remind",
    data: {"email": login},
    success: function(remind){
      if(remind.success == true){
        $(".remind-form .answer").text(remind.success);
      }
      if(remind.success == false){
        $(".remind-form .answer").text(remind.error);
      }
    }
  });
}


$(document).on("click", ".remind-form button.submit", function(){
	event.preventDefault();
	Remind();
});


// *** END REMIND *** //


// *** START REG *** //


$(document).on("click", ".open-reg", function(){
	close_small();
	modal_container();
	$(".modal-win#reg").addClass("active");
	$(".modal-overflow").addClass("active");
});


$(document).on("click", ".pass-open.open-active", function(){
	$(this).removeClass('open-active');
	$(this).addClass('close-active');
	$(".one-form-modal-win input[name='pass']").toggleClass('active');
	$(".one-form-modal-win input[name='pass']").attr('type', 'text');
});


$(document).on("click", ".one-form-modal-win input[name='terms']", function(){
	if ($(this).prop("checked") === true) {
        $(".error-check").removeClass("active");
    } else {
		$(".error-check").addClass("active");
	}
});


$(document).on("click", ".pass-open.close-active", function(){
	$(this).removeClass('close-active');
	$(this).addClass('open-active');
	$(".one-form-modal-win input[name='pass']").removeClass('active');
	$(".one-form-modal-win input[name='pass']").attr('type', 'password');
});


$(document).on("click", ".reg-form .one-form-modal-win input[type='checkbox']", function(){
	$(this).toggleClass('active');
	$(this).parent().parent().find('button.submit').toggleClass('disabled');
	
});


function Reg(){
  var login = $(".reg-form input[name='email']").val();
  var pass = $(".reg-form input[name='pass']").val();
  // var country = $(".popup.reg .active-form .country").val();
  // var phone = $(".popup.reg .active-form .phone").val();
  // var valute = $(".popup.reg .active-form select[name='valute'] option:selected").val();
  // var gift = $(".popup.reg input[name='gft']:checked").val();
  console.log(login);
  console.log(pass);
  // console.log(phone);
  // console.log(valute);
  // console.log(gift);
  // var rules = $('#rules1').is(':checked');
  var rules = '1';
  var spam = '1';

  $.ajax({
    type: "POST",
    dataType : 'json',
    url:  "/engine/ajax/user.php?action=register",
    data: {"email": login, "pass": pass, "yes": true, "spam":true},
    success: function(reg){
      if(reg.success == true){
        window.location.reload();
        // console.log("OK")
      }
      if(reg.success == false){
        $(".reg-form .answer").text(reg.error);
      }
    }
  });
}


$(document).on("click", ".reg-form button.submit", function(){
	event.preventDefault();
	Reg();
});


// *** END REG *** //

