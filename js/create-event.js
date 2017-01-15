function addShift(){
	$("#shifts-div").append("<div class='row'><div class='col-xs-4'><div class='form-group'><label for='start'>Start Time: </label><input type='time' class='form-control' name='start[]' value='12:00:00'></div></div><div class='col-xs-4'><div class='form-group'><label for='end'>End Time: </label><input type='time' class='form-control' name='end[]' value='12:00:00'></div></div><div class='col-xs-4'><div class='form-group'><label for='spots'>Spots:</label><input type='number' class='form-control' name='spots[]' placeholder='Spots Available'></div></div></div>");
}


function removeShift() {
	$('#shifts-div').children().last().remove();
}