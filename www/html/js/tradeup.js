$('#tradeup .items').on('click', '.item', function() {
	var id = $(this).attr('data-id');
	if($(this).children('h6').text() != '') {
		if($('#tradeItems .item').length < 10) {
			$('#tradeinventory div[data-id="'+id+'"]').addClass('added').hide();
		} else {
			notification({info: 'error', message: 'Cannot have more than 10 items in trade up contract'});
		}
	} else {
		$('#tradeinventory div[data-id="'+id+'"]').removeClass('added').fadeIn();
	}
	updateTradeItems();
});

function updateTradeItems() {
	var cur = 1;
	$('#tradeItems > div').each(function() {
		$(this).removeClass('item').addClass('noitem').removeAttr('data-id');
		$(this).children('h4').children('img').removeAttr('style');
		$(this).children('h5').html('<br />Slot #'+cur).removeAttr('style');
		cur++;
	});
	var tradeCount = 0;
	var tradeValue = 0;
	var tradeMin = 0;
	var tradeMax = 0;
	$('#tradeinventory .added').each(function() {
		var id = $(this).attr('data-id');
		var img = $(this).children('h4').children('img').clone();
		var h5text = $(this).children('h5').html();
		var h5style = $(this).children('h5').attr('style');
		var toAdd = $('#tradeItems .noitem').first();
		toAdd.children('h4').html(img);
		toAdd.children('h5').html(h5text).attr('style', h5style);
		toAdd.removeClass('noitem').addClass('item').attr('data-id', id);
		tradeCount++;
		tradeValue += parseFloat($(this).data('price'));
		tradeMin += parseFloat($(this).data('price')) * 0.6;
		tradeMax += parseFloat($(this).data('price')) * 5;
	});
	$('#tradeValue').html('$'+parseFloat(tradeValue).toFixed(2));
	$('#tradeMin').html('$'+parseFloat(tradeMin).toFixed(2));
	$('#tradeMax').html('$'+parseFloat(tradeMax).toFixed(2));
	$('#tradeCount').html(tradeCount);
	if(tradeCount >= 3 && tradeValue <= 500) {
		$('.btn.tradeUp').removeAttr('disabled');
	} else {
		$('.btn.tradeUp').attr('disabled', 'true');
	}
}

$('.btn.tradeUp').click(function() {
	var itemids = '';
	$('#tradeinventory .added').each(function() {
		itemids += $(this).data('id') + ',';
	});
	$('body').addClass('busy');
	$.post('process/tradeUp.php', {itemids: itemids}, function(data) {
		var data = $.parseJSON(data);
		if(data.success) {
			$('.btn.tradeUp').parent().hide();
			$('.progress').addClass('loading');
			if(getCookie('mute') != 1) {
				$('#audio-loading')[0].volume = 0.1;
			} else {
				$('#audio-loading')[0].volume = 0;
			}
			$('#audio-loading')[0].play();
			setTimeout(function() {
				if(getCookie('mute') != 1) {
					$('#audio-open')[0].volume = 0.1;
				} else {
					$('#audio-open')[0].volume = 0;
				}
				$('#audio-open')[0].play();
				$('body').removeClass('busy');
				$('.btn.tradeUp').parent().show();
				$('.progress').removeClass('loading');
				$('#tradeinventory .added').remove();
				updateTradeItems();
				$('#tradeinventory .items').prepend(data.item);
				$('#tradeinventory .items .items > div').unwrap();
				$('#modal').html(data.modal);
				$("#tradeUpModal").modal('show');
			}, 3000);
		} else {
			$('body').removeClass('busy');
			notification({info: 'error', message: data.message});
		}
	});
});
