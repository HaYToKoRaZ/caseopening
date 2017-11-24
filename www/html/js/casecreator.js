$('.casecreator form').on('keyup', '#itempercent', function() {
  var casePercent = 0;
  $('.percent').each(function() {
    casePercent += parseFloat($(this).val());
  });
  $('#casepercent').html(casePercent + '%');
});

$('#caseName').keyup(function() {
	$('#confirmName').html($('#caseName').val().replace(/(<([^>]+)>)/ig, ""));
});

var typingTimer;
var doneTypingInterval = 250;
$('#searchItems').on('input', function() {
  window.clearTimeout(typingTimer);
  typingTimer = window.setTimeout(doneTyping, doneTypingInterval);
});

function doneTyping() {
	var search = $('#searchItems').val();
  $.post('process/getItems.php', {search: search}, function(data) {
		$('#viewItems').html(data);
		$('#caseItems > div').each(function() {
			$('.item[data-id="'+$(this).data('id')+'"]').addClass('selected');
		});
	});
}

$('#viewItems').on('click', '.item', function() {
	var name = $(this).data('name');
	var id = $(this).data('id');
	var price = $(this).data('price');
	if(!$(this).hasClass('selected')) {
		$(this).addClass('selected');
		$('#caseItems').append('<div data-id="'+id+'" class="row"><div class="col-sm-6"><h4>'+name+'</h4></div><div class="col-sm-3"><h4>$'+price+'</h4></div><div class="col-sm-3"><input type="number" name="'+id+'" class="percent" data-price="'+price+'" step=".1" max="30" min="0" placeholder="0%" /><span class="deleteItem" title="Delete Item"><i class="fa fa-trash" ria-hidden="true"></i></span></div></div>');
		$('#caseItems div[data-id="'+id+'"]').hide().fadeIn();
	} else {
		$(this).removeClass('selected');
		$('#caseItems div[data-id="'+id+'"]').fadeOut(400, function(){$(this).remove();});
	}
});

$('#caseName').keyup(function() {
	$('#caseNameSize').html(32 - $('#caseName').val().length);
});

$('#caseItems').keyup(function() {
	var casePercent = 0;
	var casePrice = 0;
	var itemCount = 0;
	$('#caseItems .percent').each(function() {
		var itemPercent = parseFloat($(this).val());
		var itemPrice = parseFloat($(this).data('price'));
		if(itemPercent) {
			itemCount += 1;
			casePercent += itemPercent;
			casePrice += (itemPrice * (itemPercent/100) * 1.2);
		}
	});
  casePercent = parseFloat(casePercent).toFixed(3);
  casePrice = parseFloat(casePrice).toFixed(2);
	$('#casePercent').html(casePercent);
	if(casePercent == 100 && itemCount >= 2 && itemCount <= 20) {
		$('#confirmPrice').html('$'+casePrice);
		$('#casePrice').html('$'+casePrice);
	} else {
		$('#confirmPrice').html('N/A');
		$('#casePrice').html('N/A');
	}
});

$('#caseItems').on('click', '.deleteItem', function() {
	var id = $(this).parent().parent().data('id');
	$('.item[data-id="'+id+'"]').removeClass('selected');
	$('#caseItems div[data-id="'+id+'"]').fadeOut(400, function(){$(this).remove();});
});

$('#caseCreate').click(function() {
	var items = $('#caseItems').serializeArray();
	var name = $('#caseName').val();
	var image = $("input[name=caseImage]:checked").val();
	$('body').addClass('busy');
	$.post('process/createCase.php', {name: name, image: image, items: items}, function(data) {
		$('body').removeClass('busy');
		var data = $.parseJSON(data);
		if(data.success) {
			$('.step').removeClass('active').addClass('done');
			window.location.href = '/'+data.slug;
		} else {
			notification({info: 'error', message: data.message});
		}
	});
});

$('.caseStep1, .caseStep2, .caseStep3, .caseStep4').click(function() {
	$('.step').removeClass('active').removeClass('done');
	var prev = $(this).text() == 'Previous Step';
	if(prev) {
		if($(this).hasClass('caseStep1')) {
			$('.step1').addClass('active');
			$('#step2, #step3, #step4').hide();
			$('#step1').fadeIn();
		} else if($(this).hasClass('caseStep2')) {
			$('.step1').addClass('done');
			$('.step2').addClass('active');
			$('#step1, #step3, #step4').hide();
			$('#step2').fadeIn();
		} else if($(this).hasClass('caseStep3')) {
			$('.step1, .step2').addClass('done');
			$('.step3').addClass('active');
			$('#step1, #step2, #step4').hide();
			$('#step3').fadeIn();
		}
	} else {
		if($(this).hasClass('caseStep2')) {
			$('.step1').addClass('active');
			var caseName = $('#caseName').val();
			if(caseName != '') {
				if(caseName.length <= 32) {
					$('body').addClass('busy');
					$.post('process/validCase.php', {caseName: caseName}, function(data) {
						$('body').removeClass('busy');
						if(data == 1) {
							$('.step1').removeClass('active').addClass('done');
							$('.step2').addClass('active');
							$('#step1, #step3, #step4').hide();
							$('#step2').fadeIn();
						} else {
							notification({info: 'error', message: 'Case name has already been taken'});
						}
					});
				} else {
					notification({info: 'error', message: 'Case name cannot exceed 32 characters'});
				}
			} else {
				notification({info: 'error', message: 'Case name cannot be empty'});
			}
		} else if($(this).hasClass('caseStep3')) {
			$('.step1').addClass('done');
			$('.step2').addClass('active');
			var caseImage = parseFloat($("input[name=caseImage]:checked").val());
			if(caseImage > 0 && caseImage < 13) {
				$('body').addClass('busy');
				$.post('process/getItems.php', function(data) {
					$('body').removeClass('busy');
					$('#viewItems').html(data);
					$('.step2').removeClass('active').addClass('done');
					$('.step3').addClass('active');
					$('#step1, #step2, #step4').hide();
					$('#step3').fadeIn();
				});
			} else {
				notification({info: 'error', message: 'Selected case image is invalid'});
			}
		} else if($(this).hasClass('caseStep4')) {
			$('.step1, .step2').addClass('done');
			$('.step3').addClass('active');
			var casePercent = 0;
			var itemCount = 0;
			var overThirty = false;
			$('#caseItems .percent').each(function() {
				var itemPercent = parseFloat(parseFloat($(this).val()).toFixed(3));
				var itemPrice = parseFloat($(this).data('price'));
				if(itemPercent <= 0 || itemPercent > 50) {
					overThirty = true;
				}
				itemCount += 1;
				casePercent += itemPercent;
				casePrice += (itemPrice * (itemPercent/100) * 1.2);
			});
			if(!overThirty && casePercent == 100 && itemCount >= 2 && itemCount <= 20) {
				$('.step3').removeClass('active').addClass('done');
				$('.step4').addClass('active');
				$('#step1, #step2, #step3').hide();
				$('#step4').fadeIn();
			} else {
				if(overThirty) {
					notification({info : 'error', message: 'The odds percentage for one item should be between 0.001% and 50%'});
				} else if(casePercent != 100) {
					notification({info : 'error', message: 'The total odds should be 100%'});
				} else {
					if(itemCount < 2) {
						notification({info : 'error', message: 'You must have at least 2 items to create a case'});
					} else {
						notification({info : 'error', message: 'You cannot have more than 20 items in a case'});
					}
				}
			}
		}
	}
});
