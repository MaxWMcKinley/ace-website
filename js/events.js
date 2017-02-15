$.getJSON("../php/get-events.php", function(result){
	var events = "";
  console.log("result: " + result["Coed Soccer Game"].date);

  var tuples = [];

  for (var key in result) tuples.push([key, result[key]]);

  tuples.sort(function(a, b) {
      a = a[1];
      b = b[1];
      console.log("a: " + a.date + " b: " + b.date + " diff: " + (Date.parse(a.date) > Date.parse(b.date)));
      return Date.parse(a.date) - Date.parse(b.date);
  });

  for (var i = 0; i < tuples.length; i++) {
    var key = tuples[i][0];
    var value = tuples[i][1];

    console.log("value: " + value.date);
    var date = moment(value.date, 'YYYY-MM-D').format('MMM Do');
    var type = value.type.charAt(0).toUpperCase() + value.type.substr(1);
      events += `
      <button type='button' class='list-group-item' data-toggle='modal' data-target='#eventModal' data-name='${key}' data-id='${value.id}' data-officername='${value.officer_name}' data-date='${date}' data-freeze='${value.freeze}' data-type='${type}' data-points='${value.points}'>
        <span class='badge'>${type}</span>
        <h2 class='list-group-item-heading'>${key}</h2>
          <p class='list-group-item-text'>${date}  |  ${value.points} points</p>
      </button>`;

      document.getElementById("events").innerHTML = events;
  }
});

function filterAll() {
  var events = document.getElementById("events").children;

  for (i = 0; i < events.length; i++) {
    events[i].style.display = '';
    console.log(events[i]);
  }
  return false;
}

function filterService() {
  var events = document.getElementById("events").children;

  for (i = 0; i < events.length; i++) {
    var type = events[i].getAttribute('data-type');

    if (type !== "Service") {
      events[i].style.display = 'none';
    }
    else {
      events[i].style.display = '';
    }
  }
  return false;
}

function filterFundraising() {
 var events = document.getElementById("events").children;

  for (i = 0; i < events.length; i++) {
    var type = events[i].getAttribute('data-type');

    if (type !== "Fundraising") {
      events[i].style.display = 'none';
    }
    else {
      events[i].style.display = '';
    }
  }
  return false;
}

function filterFlex() {
 var events = document.getElementById("events").children;

  for (i = 0; i < events.length; i++) {
    var type = events[i].getAttribute('data-type');

    if (type !== "Flex") {
      events[i].style.display = 'none';
    }
    else {
      events[i].style.display = '';
    }
  }
  return false;
}



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

        if (value.spots <= 0) {
      			shifts += `
    	 		<label class="checkbox-inline">
    	 			<input type="checkbox" disabled="disabled" value="${value.id}" name="shifts[]">${start} - ${end}
    	 		</label>`;
        }
        else {
            shifts += `
          <label class="checkbox-inline">
            <input type="checkbox" value="${value.id}" name="shifts[]">${start} - ${end}
          </label>`;
        }
		});

		document.getElementById("shifts").innerHTML = shifts;
  });

  modal.find('.modal-title').text(name + " - " + date)
  modal.find('.modal-body h4[id="officer"]').text("Officer: " + officer_name);
  modal.find('.modal-body p[id="points"]').text(points + " " + type + " points per shift");
});