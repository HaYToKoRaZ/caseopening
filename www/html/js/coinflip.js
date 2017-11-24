function loadInventory() {
	$('#inventory').html('');
	$('body').addClass('busy');
	$.post('process/getItems.php', {'inventory':1}, function(data) {
		$('body').removeClass('busy');
		$('#inventory').html(data);
	});
}

$('.bottle svg').drawsvg({
	easing: 'linear',
}).drawsvg('animate');

$('#coinflips').on('click', 'button', function() {
	var cid = $(this).parent().parent().data('id');
	var svg = $('#watchFlip'+cid+' svg');
	var con = svg.html();
	svg.html('').html(con).drawsvg({
		easing: 'linear',
	}).drawsvg('animate');
});

$('#newFlip').click(function() {
  loadInventory();
	$('#cid').val('');
	$('#createFlip .modal-header p').html('Create New Coinflip');
	$('#createFlip .modal-footer .green').html('Create');
	$('#selectedValue').html('0.00');
	$('#selectedCount').html(0);
	$('#minValue').attr('data-value','2.00').html('2.00');
	$('#maxValue').attr('data-value','10000.00').html('10000.00');
});

$('#createFlip').on('click', '.item', function() {
	if(!$(this).hasClass('selected') && $('#createFlip .selected').length >= 12) return;
	$(this).toggleClass('selected');
	updateCreateItems();
});

$('#submitFlip').click(function() {
	if($('#createFlip .selected').length > 0) {
		var itemids = '';
		$('#createFlip .selected').each(function() {
			itemids += $(this).data('id') + ',';
		});
		$('body').addClass('busy');
		$.post('process/createFlip.php', {itemids: itemids}, function(data) {
			$('body').removeClass('busy');
			var data = $.parseJSON(data);
			if(data.success) {
				$("#createFlip").modal('hide');
				console.log(data);
			} else {
				notification({info: 'error', message: data.message});
			}
		});
	} else {
		notification({info: 'error', message: 'Failed to create flip [no items selected]'});
	}
});

function updateCreateItems() {
	var selectedCount = selectedValue = 0;
	var minValue = $('#minValue').data('value');
	var maxValue = $('#maxValue').data('value');
	$('#createFlip .selected').each(function() {
		selectedCount++;
		selectedValue += parseFloat($(this).data('price'));
	});
	$('#selectedValue').html(parseFloat(selectedValue).toFixed(2));
	$('#selectedCount').html(selectedCount);
	if(selectedCount > 0 && selectedCount <= 12 && selectedValue >= minValue && selectedValue <= maxValue) {
		$('#submitFlip').removeAttr('disabled');
	} else {
		$('#submitFlip').attr('disabled', 'true');
	}
}

$('#coinflips').on('click', '.btn', function() {
	$('#cid').val($(this).parent().parent().data('id'));
	var min = $(this).parent().parent().data('min');
	var max = $(this).parent().parent().data('max');
	$('#minValue').attr('data-value',min).html(min);
	$('#maxValue').attr('data-value',max).html(max);
});

$('.coinflip').on('click', '.join', function() {
  $('.modal').modal('hide');
	loadInventory();
	$('#createFlip .modal-header p').html('Join Coinflip');
	$('#createFlip .modal-footer .green').html('Join');
	$('#selectedValue').html('0.00');
	$('#selectedCount').html(0);
	$('#createFlip').modal('show');
});
