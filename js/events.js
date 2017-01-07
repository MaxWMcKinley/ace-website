$.getJSON("../php/get-events.php", function(result){
	var html = "";
	$.each( result, function( key, value ) {
  		html += `
			<button type='button' class='list-group-item' data-toggle='modal' data-target='#eventModal' data-name='${key}' data-date='${value.date}' data-freeze='${value.freeze}' data-start='${value.start}' data-end='${value.end} data-type='${value.type}' data-points='${value.points}' data-shiftLength='${value.shift_length}' data-shiftAmount='${value.shift_amount}''>
				<span class='badge'>${value.points} points</span>
				<h2 class='list-group-item-heading'>${key}</h2>
					<p class='list-group-item-text'>${value.date}  |  ${value.start}-${value.end}</p>
			</button>
  		`;
  		document.getElementById("events").innerHTML = html;
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
  var shiftLength = button.data('shiftLength') // Extract info from data-* attributes
  var shiftAmount = button.data('shiftAmount') // Extract info from data-* attributes

  modal.find('.modal-title').text(name)
  modal.find('.modal-body p[id="points"]').text("Points per shift: " + points);
})