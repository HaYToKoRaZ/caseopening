$('body').on('click', '#openCase, #testSpin', function() {
  $('#openCase, #testSpin, #caseCount, #deleteCase').attr('disabled', 'true');
  var type = $(this).text();
  var slug = $(location).attr("href").split('/')[3];
  var count = $('#caseCount').val();
	$('body').addClass('busy');
  $.post('process/openCase.php', {slug: slug, type: type, count: count}, function(data) {
		$('body').removeClass('busy');
    var data = $.parseJSON(data);
    if(data.success) {
      $('#balance').html(data.balance);
      var percent = '-' + Math.floor((Math.random() * 9) + 891) + '%';
      $('#caseOpener .inner').css('margin-left', '0').html(data.slider);
      if(getCookie('mute') != 1) {
				$('#audio-roll')[0].volume = 1;
			} else {
				$('#audio-roll')[0].volume = 0;
			}
			$('#audio-roll')[0].play();
      $('#caseOpener .inner').animate({
        'marginLeft': percent
      }, 8500, 'easeOutCubic', function() {
				$('#openCase, #testSpin, #caseCount, #deleteCase').removeAttr('disabled');
				if(data.modal) {
          if(getCookie('mute') != 1) {
          	$('#audio-open')[0].volume = 0.1;
          } else {
						$('#audio-open')[0].volume = 0;
					}
					$('#audio-open')[0].play();
					$('#modal').html(data.modal);
					$("#winningsModal").modal('show');
				}
      });
    } else {
      $('#openCase, #testSpin, #caseCount, #deleteCase').removeAttr('disabled');
      notification({info: 'error', message: data.message});
    }
  });
});

$('#caseCount').change(function() {
  var count = $('#caseCount').val();
  $('#caseOpener .slider + .slider').remove();
  for(var i=1; i<count; i++) {
    $('#caseOpener .slider:first-child').after($('#caseOpener .slider:first-child').clone());
  }
});

$('#deleteCase').click(function() {
	if(confirm('Are you sure you want to delete this case? This action cannot be reversed.')) {
		var caseid = $(this).data('id');
		$('body').addClass('busy');
		$.post('process/deleteCase.php', {caseid: caseid}, function(data) {
			$('body').removeClass('busy');
			if(data == 1) {
				window.location.href = '/casebrowser/my';
			} else {
				notification({info: 'error', message: 'Failed to delete case'});
			}
		});
	}
});
