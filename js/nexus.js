function loadNexus() {
	var name = document.getElementById("name").value;
	var data = "name=" + name;

	$.getJSON("../php/get-signups.php", data, function(result){
		var signups = "";
		$.each( result, function( key, value ) {
			var date = moment(value.date, 'YYYY-MM-D').format('MMM Do');
			var type = value.type.charAt(0).toUpperCase() + value.type.substr(1);
	  		signups += `
				<button type='button' class='list-group-item' data-toggle='modal' data-target='#mySignUpsModal' data-name='${key}' data-id='${value.id}' data-uin='${value.uin}' data-officername='${value.officer_name}' data-date='${date}' data-freeze='${value.freeze}' data-type='${type}' data-points='${value.points}'>
					<span class='badge'>${type}</span>
					<h2 class='list-group-item-heading'>${key}</h2>
						<p class='list-group-item-text'>${date}  |  ${value.points} points</p>
				</button>`;

	  		document.getElementById("signups").innerHTML = signups;	
	  	});
	});

	$.getJSON("../php/get-createdevents.php", data, function(result){
		var myevents = "";
		$.each( result, function( key, value ) {
			var date = moment(value.date, 'YYYY-MM-D').format('MMM Do');
			var type = value.type.charAt(0).toUpperCase() + value.type.substr(1);
	  		myevents += `
				<button type='button' class='list-group-item' data-toggle='modal' data-target='#myEventsModal' data-name='${key}' data-id='${value.id}' data-officername='${value.officer_name}' data-date='${date}' data-freeze='${value.freeze}' data-type='${type}' data-points='${value.points}'>
					<span class='badge'>${type}</span>
					<h2 class='list-group-item-heading'>${key}</h2>
						<p class='list-group-item-text'>${date}  |  ${value.points} points</p>
				</button>`;

	  		document.getElementById("myevents").innerHTML = myevents;	
	  	});
	});

	return false;
}

function removeAttendee() { 

	var uin = document.getElementById("mySignUpsModal").uin;
	var eventId = document.getElementById("mySignUpsModal").eventId;

   	$.ajax({
            type: "POST",
            url: "../php/removeAttendee.php",
            data: {uins: uin, eventId: eventId},
            success: function(response) {
              alert( response );
              $('#mySignUpsModal').modal('hide');
              loadNexus();
                }
          });
}


// function submitEvent() { 

// 	var uins = new Array();
// 	var eventId = document.getElementById("attendeesDiv").eventId;

//    	$("input:checkbox:checked").each(function() {
//    		uins.push($(this).val());

//    		$(this).remove();
//    		$('label[for="' + this.id + '"]').remove();
//   	});

//    	$.ajax({
//             type: "POST",
//             url: "../php/removeAttendees.php",
//             data: {uins: JSON.stringify(uins), eventId: eventId},
//             success: function(response) {
//               alert( response );
//                 }
//           });

// 	return false;
// }


$('#mySignUpsModal').on('show.bs.modal', function (event) {
  var button = $(event.relatedTarget); // Button that triggered the modal
  var modal = $(this);

  // Extract info from data-* attributes
  var name = button.data('name'); 
  var eventId = button.data('id');
  var uin = button.data('uin');
  var date = button.data('date');

  document.getElementById("mySignUpsModal").uin = uin;
  document.getElementById("mySignUpsModal").eventId = eventId;

  modal.find('.modal-title').text(name + " - " + date);
})


$('#myEventsModal').on('show.bs.modal', function (event) {
  var button = $(event.relatedTarget); // Button that triggered the modal
  var modal = $(this);

  // Extract info from data-* attributes
  var name = button.data('name'); 
  var eventId = button.data('id');
  var date = button.data('date');

  var data = "id=" + eventId;
  var attendees = "";
  $.getJSON("../php/get-attendees.php", data, function(result){
  		var id = 0;
  		$.each( result, function( key, value ) {
  			attendees += `
	 		<label for="${id}" class="checkbox">
	 			<input type="checkbox" name"attendees[]" id="${id}" eventId="${eventId}" value="${key}">${value}
	 		</label>`;
	 		id++;
		});

  		document.getElementById("attendeesDiv").eventId = eventId;
		document.getElementById("attendeesDiv").innerHTML = attendees;
  });

  modal.find('.modal-title').text(name + " - " + date)
})