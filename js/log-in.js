$(document).ready(() => {
	$('#log-in').submit(event => {
		console.log('in event');
		event.preventDefault();
		var form = event.target;

		var data = {
			uin: form.uin.value,
			password: form.password.value,
		};

		$.ajax('../php/log-in.php', {
			data: data,
			method: 'POST'
		})
		.done(data => {
			console.log('in done');

			if(data == "success") {
				window.location.href = "nexus";
			} else if(data == "failure") {3
				showAlert("alert-info", "Failure!", "UIN and password were not correct. Please try again.");
			} else {
				showAlert("alert-danger", "Failure!", "Something went wrong while logging in. Talk to the webmaster.");
				console.log(data);
			}
		})
		.fail((jqXHR, status, error) => {
			alert(status);
		});
	});
});
