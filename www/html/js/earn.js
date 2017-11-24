$('.getOffers button').click(function() {
	if($(this).hasClass('green')) {
		var text = $(this).data('offers');
		$('.getOffers button').addClass('green');
		$(this).removeClass('green');
		if(text == '1') {
			$('#adgate, #offertoro, #superrewards').hide();
			$('#adscend:hidden').fadeIn();
		} else if(text == '2') {
			var iframe = $("#adgate iframe");
			if(iframe.attr('src') == undefined) {
				$('.offers .se-pre-con').show();
				iframe.attr("src", iframe.data("src"));
			}
			$('#adscend, #offertoro, #superrewards').hide();
			$('#adgate:hidden').fadeIn();
		} else if(text == '3') {
			var iframe = $("#offertoro iframe");
    		if(iframe.attr('src') == undefined) {
    			$('.offers .se-pre-con').show();
    			iframe.attr("src", iframe.data("src"));
    		}
			$('#adgate, #adscend, #superrewards').hide();
			$('#offertoro:hidden').fadeIn();
		} else if(text == '4') {
			var iframe = $("#superrewards iframe");
    		if(iframe.attr('src') == undefined) {
    			$('.offers .se-pre-con').show();
    			iframe.attr("src", iframe.data("src"));
    		}
			$('#adgate, #offertoro, #adscend, #superrewards').hide();
			$('#superrewards:hidden').fadeIn();
		}
	}
});

$('.offers iframe').load(function() {
	$('.offers .se-pre-con').hide();
});
