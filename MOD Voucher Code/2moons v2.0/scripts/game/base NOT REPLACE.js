function NotifyBox(text, duration = 500) {
	tip = $('#tooltip')
	tip.html(text).addClass('notify').css({
		left: (($(window).width() - $('#leftmenu').width()) / 2 - tip.outerWidth() / 2) + $('#leftmenu').width(),
	}).show();
	window.setTimeout(function () { tip.fadeOut(1000, function () { tip.removeClass('notify') }) }, duration);
}