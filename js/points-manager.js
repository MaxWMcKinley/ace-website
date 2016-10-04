// Get array of the names of all ACE members
$.get("../php/get-names.php", function(response) {
	dictionary = JSON.parse(response);
});
