$.getJSON("../php/get-events.php", function(result){
	var events = "";
	$.each( result, function( key, value ) {
  		events += `
			<button type='button' class='list-group-item' data-toggle='modal' data-target='#eventModal' data-name='${key}' data-date='${value.date}' data-freeze='${value.freeze}' data-start='${value.start}' data-end='${value.end} data-type='${value.type}' data-points='${value.points}' data-shiftlength='${value.shift_length}' data-shiftamount='${value.shift_amount}'>
				<span class='badge'>${value.points} points</span>
				<h2 class='list-group-item-heading'>${key}</h2>
					<p class='list-group-item-text'>${value.date}  |  ${value.start}-${value.end}</p>
			</button>`;

  		document.getElementById("events").innerHTML = events;
	});
});

$('#eventModal').on('show.bs.modal', function (event) {
  var button = $(event.relatedTarget) // Button that triggered the modal
  var modal = $(this)

  var name = button.data('name') // Extract info from data-* attributes
  var date = button.data('date') // Extract info from data-* attributes
  var freeze = button.data('freeze') // Extract info from data-* attributes
  var start = button.data('start') // Extract info from data-* attributes
  var end = button.data('end') // Extract info from data-* attributes
  var type = button.data('type') // Extract info from data-* attributes
  var points = button.data('points') // Extract info from data-* attributes
  var shiftLength = button.data('shiftlength') // Extract info from data-* attributes
  var shiftAmount = button.data('shiftamount') // Extract info from data-* attributes

  start = parseInt(start);
  end = parseInt(end);

var shifts = "";
for (var i = start; i < end; i+=shiftLength) {
	shifts += `
		<label class="checkbox-inline">
				<input type="checkbox" value="1">${i}-${i+shiftLength}
		</label>`;
}

  document.getElementById("shifts").innerHTML = shifts;

  modal.find('.modal-title').text(name)
  modal.find('.modal-body p[id="points"]').text("Points per shift: " + points);
})