function addShift(){
	var shift = 
		`<div class="form-inline">
			<div class="form-group">
				<label for="start">Start Time: </label>
				<input type="time" class="form-control" name="start" value="12:00:00">
			</div>

			<div class="form-group">
				<label for="end">End Time: </label>
				<input type="time" class="form-control" name="end" value="12:00:00">
			</div>

			<div class="form-group">
				<label for="end">Spots Available: </label>
				<input type="number" class="form-control" name="spots">
			</div>
		</div>
		<br>`;

		document.getElementById("shifts-div").innerHTML += shift;
}


function removeShift() {
	$('#shifts-div').children().last().remove();
	$('#shifts-div').children().last().remove();

}