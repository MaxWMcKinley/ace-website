$.getJSON("../php/get-events.php", function(result){
	var events = "";
  var tuples = [];

  for (var key in result) tuples.push([key, result[key]]);

  tuples.sort(function(a, b) {
      a = a[1];
      b = b[1];
      return Date.parse(a.date) - Date.parse(b.date);
  });

  for (var i = 0; i < tuples.length; i++) {
    var key = tuples[i][0];
    var value = tuples[i][1];

    var date = moment(value.date, 'YYYY-MM-D').format('MMM Do');
    var type = value.type.charAt(0).toUpperCase() + value.type.substr(1);
      events += `
      <button type='button' class='list-group-item' data-toggle='modal' data-target='#eventModal' data-name='${key}' data-id='${value.id}' data-officername='${value.officer_name}' data-date='${date}' data-freeze='${value.freeze}' data-type='${type}' data-points='${value.points}' data-start='${value.start}' data-end='${value.end}' data-description='${value.description}'>
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
  var button = $(event.relatedTarget); 
  var modal = $(this);

  // Extract info from data-* attributes
  var name = button.data('name'); 
  var id = button.data('id');
  var officer_name = button.data('officername');
  var date = button.data('date');
  var freeze = button.data('freeze'); 
  var type = button.data('type'); 
  var points = button.data('points'); 
  var start = button.data('start'); 
  var end = button.data('end'); 
  var description = button.data('description'); 
  
  var start = moment(start, 'HH:mm:ss').format('h:mma');
  var end = moment(end, 'HH:mm:ss').format('h:mma');

  setTimeout(function(){  document.getElementById("sign-up").eventid = id;}, 100)

  modal.find('.modal-title').text(name)
  modal.find('.modal-body h3[id="officer"]').text("Officer: " + officer_name);  
  modal.find('.modal-body h4[id="date"]').text("Date: " + date);  
  modal.find('.modal-body h4[id="time"]').text("Time: " + start + " - " + end);    
  modal.find('.modal-body h4[id="points"]').text(type + " Points: " + points);
  modal.find('.modal-body p[id="description"]').text("Description: " + description);  
});

$(document).ready(() => {
	$('#sign-up').click(event => {

		var data = {
			id: event.target.eventid
		};

		$.ajax('../php/sign-up.php', {
			data: data,
			method: 'POST'
		})
		.done(data => {
			if (data == "success") {
        $('#eventModal').modal('hide');        
        showAlert("alert-success", "Success!", "You have been signed up for your event.");        
      } else if (data == "nospots") {
        $('#eventModal').modal('hide');        
        showAlert("alert-warning", "Failure!", "There are no spots left for this event."); 
      } else {
        $('#eventModal').modal('hide');        
				showAlert("alert-danger", "Failure!", "Something went wrong with event sign-up. Talk to the webmaster.");
				console.log(data);
			}
		})
		.fail((jqXHR, status, error) => {
			alert(status);
		});
	});
});