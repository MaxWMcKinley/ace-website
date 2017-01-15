$.getJSON("../php/get-events.php", function(result){
	var events = "";
	$.each( result, function( key, value ) {
		var date = moment(value.date, 'YYYY-MM-D').format('MMM Do');
		var type = value.type.charAt(0).toUpperCase() + value.type.substr(1);
  		events += `
			<button type='button' class='list-group-item' data-toggle='modal' data-target='#eventModal' data-name='${key}' data-id='${value.id}' data-officername='${value.officer_name}' data-date='${date}' data-freeze='${value.freeze}' data-type='${type}' data-points='${value.points}'>
				<span class='badge'>${type}</span>
				<h2 class='list-group-item-heading'>${key}</h2>
					<p class='list-group-item-text'>${date}  |  ${value.points} points</p>
			</button>`;

  		document.getElementById("events").innerHTML = events;
	});
});

$('#eventModal').on('show.bs.modal', function (event) {
  var button = $(event.relatedTarget); // Button that triggered the modal
  var modal = $(this);

  // Extract info from data-* attributes
  var name = button.data('name'); 
  var id = button.data('id');
  var officer_name = button.data('officername');
  var date = button.data('date');
  var freeze = button.data('freeze'); 
  var type = button.data('type'); 
  var points = button.data('points'); 

  var data = "id=" + id;
  var shifts = "";
  $.getJSON("../php/get-shifts.php", data, function(result){
  		$.each( result, function( key, value ) {
  			var start = moment(value.start, 'HH:mm:ss').format('h:mma');
  			var end = moment(value.end, 'HH:mm:ss').format('h:mma');

  			console.log(value.id);
  			shifts += `
	 		<label class="checkbox-inline">
	 			<input type="checkbox" value="${value.id}" name="shifts[]">${start} - ${end}
	 		</label>`;
		});

		document.getElementById("shifts").innerHTML = shifts;
  });

  modal.find('.modal-title').text(name + " - " + date)
  modal.find('.modal-body h4[id="officer"]').text("Officer: " + officer_name);
  modal.find('.modal-body p[id="points"]').text(points + " " + type + " points per shift");
})