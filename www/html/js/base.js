$(document).ready(function() {
	$('.se-pre-con').fadeOut('slow');
});
var socket = io.connect('tuz1k.com:8443');
socket.on('livedrops', function(data) {
	var split = data.split('<li data-id="').reverse();
	for(var i=0; i<split.length-1; i++) {
		var openid = split[i].split('">')[0];
		var count = $('#livedrops li[data-id="'+openid+'"]').length;
		if(count == 0) {
			$('<li class="load" data-id="'+split[i]).prependTo($('#livedrops')).hide().fadeIn('fast').removeAttr('class');
		}
	}
	while($('#livedrops li').length > 15) {
		$('#livedrops li:last-child').remove();
	}
});
socket.on('onlinecount', function(data) {
	setCookie('online', data);
	$('#onlineUsers').html(data);
});
socket.on('notification', function(msg) {
  if(msg.delay) {
    setTimeout(function() { notification(msg); }, 15000);
  } else {
    notification(msg);
  }
});

function notification(msg) {
  noty({
    text: msg.message,
    type: msg.info,
    layout: "bottomRight",
		timeout: 3000,
		progressBar: true,
    animation: {
      open: {height: "toggle"},
      close: {height: "toggle"},
      easing: "swing",
      speed: 500,
    },
  });
}

function setCookie(key, value) {
  var expires = new Date();
  expires.setTime(expires.getTime() + (1 * 24 * 60 * 60 * 1000));
  document.cookie = key + '=' + value + ';path=/;expires=' + expires.toUTCString();
}

function getCookie(key) {
  var keyValue = document.cookie.match('(^|;) ?' + key + '=([^;]*)(;|$)');
  return keyValue ? keyValue[2] : null;
}

$('#btcDeposit').click(function() {
	$(this).hide();
	$('#btcStep2').fadeIn();
});

$('#btcCreate').click(function() {
	var value = $('#btcValue').val();
	$('body').addClass('busy');
	$.post('process/btcForm.php', {value: value}, function(data) {
		$('body').removeClass('busy');
		$('#modal').html(data);
		$('.modal').modal('hide');
		$('#btcModal').modal('show');
	});
});

$('#toggleMute').click(function() {
  var mute = getCookie('mute');
  var roll = $('#audio-roll')[0];
  var open = $('#audio-open')[0];
	var loading = $('#audio-loading')[0];
  if(mute == 1) {
    setCookie('mute', 0);
    if(roll != undefined) roll.volume = 1;
    if(open != undefined) open.volume = 0.1;
		if(loading != undefined) loading.volume = 0.1;
    $('#toggleMute i').removeClass('fa-volume-off').addClass('fa-volume-up');
  } else {
    setCookie('mute', 1);
    if(roll != undefined) roll.volume = 0;
    if(open != undefined) open.volume = 0;
		if(loading != undefined) loading.volume = 0;
    $('#toggleMute i').removeClass('fa-volume-up').addClass('fa-volume-off');
  }
});
