$('body').on('click', '#sellItem', function() {
	var $that = $(this);
	var itemids = $(this).data('id');
	var classes = $(this).parent().attr('class');
	$('body').addClass('busy');
	$.post('process/sellItem.php', {itemids: itemids}, function(data) {
		$('body').removeClass('busy');
		var data = $.parseJSON(data);
		if(data.success) {
			if(classes == 'col-sm-4 box') {
				$that.removeAttr('id').removeClass('green').addClass('orange').text('Sold');
			} else if(classes == 'col-sm-8') {
				$('.item').remove();
			} else if(classes == 'modal-footer') {
				$('#tradeinventory div[data-id="'+itemids+'"]').remove();
				$('#winningsModal .modal-body button').removeAttr('id').removeClass('green').addClass('orange').text('Sold');
			} else {
        $that.parent().remove();
      }
			$('#balance').html(data.balance);
			var sellAll = 0;
			$('.sellItem.green').each(function() {
    		sellAll += parseFloat($(this).data('price'));
  		});
			if(sellAll > 0) {
				$('.sellAll').html('Sell All For $'+parseFloat(sellAll).toFixed(2));
			} else {
				$('.sellAll').removeAttr('id').removeClass('green').addClass('orange').text('Sold');
			}
			notification({info: 'success', message: data.message});
		} else {
			notification({info: 'error', message: data.message});
		}
	});
});

$('body').on('click', '#withdrawItem', function() {
	var $that = $(this);
	var itemid = $(this).data('id');
	$('body').addClass('busy');
	$.post('process/withdrawItem.php', {itemid: itemid}, function(data) {
		$('body').removeClass('busy');
		var data = $.parseJSON(data);
		if(data.success) {
			$that.prev().remove();
      $that.parent().removeClass('item').addClass('withdraw');
			$that.removeAttr('id').removeClass('green').addClass('orange').text('Pending Withdraw...');
			notification({info: 'success', message: data.message});
		} else {
			notification({info: 'error', message: data.message});
		}
	});
});
