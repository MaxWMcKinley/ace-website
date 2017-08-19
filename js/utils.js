function showAlert(type, strong, message) {
	$('#alert-strong').html(strong + ' ');
	$('#alert-message').html(message);
	$('#alert').addClass(type);

	$('#alert').fadeIn('slow');
}
