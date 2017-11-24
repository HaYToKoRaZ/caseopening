function loadCases(search) {
	$('body').addClass('busy');
	$.post('process/getCases.php', search, function(data) {
		$('body').removeClass('busy');
		$('#browseCases').html(data);
	});
}

var cat = 1;
$('.getCases button').click(function() {
	var text = $(this).text();
	if(text == 'Search') {
		searchCases();
	} else {
		$('.getCases button').addClass('green');
		$(this).removeClass('green');
		if(text == 'Most Popular') {
			loadCases();
			cat = 1;
			history.replaceState({}, null, '/casebrowser/popular');
		} else if(text == 'New Cases') {
			loadCases({new: 1});
			cat = 2;
			history.replaceState({}, null, '/casebrowser/new');
		} else if(text == 'Top 7 Days') {
			loadCases({top: 1});
			cat = 3;
			history.replaceState({}, null, '/casebrowser/top');
		} else if(text == 'My Cases') {
			loadCases({steamid: 1});
			cat = 4;
			history.replaceState({}, null, '/casebrowser/my');
		}
	}
});

$('#searchCaseName').keypress(function(e) {
  var keycode = (e.keyCode ? e.keyCode : e.which);
  if(keycode == '13') {
    searchCases();
  }
});

function searchCases() {
	var search = $('#searchCaseName').val();
	if(search != '') {
		if(cat == 1) {
			loadCases({search: search});
		} else if(cat == 2) {
			loadCases({search: search, new: 1});
		} else if(cat == 3) {
			loadCases({search: search, top: 1});
		} else if(cat == 4) {
			loadCases({search: search, steamid: 1});
		}
	}
}

$('#browseCases').on('click', '.scroll', function() {
	var id = $(this).attr('id');
	var large = $(this).siblings('.large-row');
	var items = large.children('.col-md-1').length;
	var curr = large.attr('style');
	var toMove = null;
	if(curr == undefined || curr == 'margin-left: 0%;') {
		if(id == 'scrollRight') {
			toMove = "-100%";
			$(this).siblings('#scrollLeft').fadeIn();
			if(items < 9) $(this).fadeOut();
		} else {
			toMove = "0%";
		}
	} else if(curr == 'margin-left: -100%;') {
		$(this).fadeOut();
		if(id == 'scrollLeft') {
			toMove = "0%";
			if(items < 9) $(this).siblings('#scrollRight').fadeIn();
		} else {
			toMove = "-200%";
		}
	} else if(curr == 'margin-left: -200%;') {
		if(id == 'scrollLeft') {
			toMove = "-100%";
			$(this).siblings('#scrollRight').fadeIn();
		} else {
			toMove = "-200%";
		}
	}
	if(toMove) {
		large.animate({
			marginLeft: toMove
		});
	}
});
