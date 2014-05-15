$(document).ready(function() {

	// Initialize tooltips and popovers
	$(".has-tooltip").tooltip();
	$(".has-popover").popover();

	// Submit from textarea if Ctrl+Enter is pressed
	$('body').on('keypress', 'textarea', function(e) {
		if(e.keyCode == 13 && (e.target.type != 'textarea' || (e.target.type == 'textarea' && e.ctrlKey))) {
			$(this).parents('form')[0].submit();
			e.preventDefault();
		}
	});

});
