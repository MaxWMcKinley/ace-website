function showAlert(type, strong, message) {
	$('#alert-strong').html(strong + ' ');
	$('#alert-message').html(message);

	$('#alert').removeClass('alert-success');
	$('#alert').removeClass('alert-info');
	$('#alert').removeClass('alert-warning');	
	$('#alert').removeClass('alert-danger');
	$('#alert').addClass(type);

	$('#alert').fadeIn('slow');
}

$(document).on("click", "[data-hide]", function() {
	$(this).closest("." + $(this).attr("data-hide")).hide();	
});