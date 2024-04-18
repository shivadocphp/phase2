(function($) {
    "use strict";
	
	$('#right').click(function() {
		Notify('Default notification');
	});

	$('#random').click(function() {
		Notify({
			content: 'Random color...',
			color: 'random'
		});
	});

	$('#left').click(function() {
		Notify({
			content: 'Left notification',
			position: 'left',
			color: 'red'
		});
	});

	$('#rounded').click(function() {
		Notify({
			content: 'Rounded notification',
			rounded: true,
			color: 'blue'
		});
	});

	$('#callback').click(function() {
		Notify({
			content: 'Callback',
			color: 'random',
			callback: function () {
				alert('This is a callback');
			}
		});
	});

})(jQuery);