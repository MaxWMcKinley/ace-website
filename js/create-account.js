$(document).ready(() => {
	$('#create-account').submit(event => {
		event.preventDefault();
		var form = event.target;

		var data = {
			uin: form.uin.value,
			name: form.name.value,
			major: form.major.value,
			family: form.family.value,
			phone: form.phone.value,
			email: form.email.value,
			password: form.password.value,
			access: form.access.value
		};

		$.ajax('../php/create-account.php', {
			data: data,
			method: 'POST'
		})
		.done(data => {
			if(data == "success") {
				window.location.href = "?action=nexus";
			} else if(data == "wrongAccess") {
				showAlert("alert-info", "Failure!", "Access code did not match. Please try again.");
			} else {
				showAlert("alert-danger", "Failure!", "Something went wrong with creating your account. Talk to the webmaster.");
				console.log(data);
			}
		})
		.fail((jqXHR, status, error) => {
			alert(status);
		});
	});
});
