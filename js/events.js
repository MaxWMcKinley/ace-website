$.getJSON("../php/get-events.php", function(result){
	var events = "";
	$.each( result, function( key, value ) {
  		events += `
			<button type='button' class='list-group-item' data-toggle='modal' data-target='#eventModal' data-name='${key}' data-id='${value.id}' data-officername='${value.officer_name}' data-date='${value.date}' data-freeze='${value.freeze}' data-type='${value.type}' data-points='${value.points}'>
				<span class='badge'>${value.type}</span>
				<h2 class='list-group-item-heading'>${key}</h2>
					<p class='list-group-item-text'>${value.date}  |  ${value.points} points</p>
			</button>`;

  		document.getElementById("events").innerHTML = events;
	});
});

$('#eventModal').on('show.bs.modal', function (event) {
  var button = $(event.relatedTarget); // Button that triggered the modal
  var modal = $(this);

  var name = button.data('name'); // Extract info from data-* attributes
  var id = button.data('id');
  var officer_name = button.data('officername');
  var date = button.data('date'); // Extract info from data-* attributes
  var freeze = button.data('freeze'); // Extract info from data-* attributes
  var type = button.data('type'); // Extract info from data-* attributes
  var points = button.data('points'); // Extract info from data-* attributes

  var data = "id=" + id;
  var shifts = "";
  $.getJSON("../php/get-shifts.php", data, function(result){
  		$.each( result, function( key, value ) {
  			console.log("start: " + value.start + " end: " + value.end);
  			shifts += `
	 		<label class="checkbox-inline">
	 			<input type="checkbox" value="1">${value.start}-${value.end}
	 		</label>`;
		});

		document.getElementById("shifts").innerHTML = shifts;
  });

  modal.find('.modal-title').text(name)
  modal.find('.modal-body h4[id="officer"]').text(officer_name);
  modal.find('.modal-body p[id="points"]').text("Points per shift: " + points);
})