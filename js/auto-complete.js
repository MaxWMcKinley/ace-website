// Array that needs to be filled with values that should be autocompleted
var dictionary;

// Get array of the names of all ACE members
$.get("../php/get-names.php", function(response) {
	dictionary = JSON.parse(response);
});

// User types into name entry text box
function KeyPress (e) {
  var input = document.getElementById("name").value;		// Get what the user has typed so far
  if (input)
    Autocomplete(e.keyCode, input);
  else
    document.getElementById("result").innerHTML = "";
}

// Search through dictionary to match against user input
function filterMembers(input) {
  var reg = new RegExp(input.split('').join('\\w*').replace(/\W/, ""), 'i');
  return dictionary.filter(function(dictionary) {
	 if (dictionary.match(reg)) {
		return dictionary;
	 }
  });
}

function Autocomplete(key, input) {
  var autoCompleteResult = filterMembers(input);	// Filter dictionary against user input
  document.getElementById("result").innerHTML = autoCompleteResult;	// Display matches
  
  // Autocomplete entry if only one match
  // Also, only do this if key pressed was not delete or backspace
  if (autoCompleteResult.length == 1 && key != 8 && key != 46) {
	  document.getElementById("name").value = autoCompleteResult;
    document.getElementById("result").innerHTML = "";
  }
}
