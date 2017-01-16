function loadNexus() {
	var name = document.getElementById("name").value;
	data = "name=" + name;

	$.getJSON("../php/get-signups.php", data, function(result){
		var signups = "";
		$.each( result, function( key, value ) {
			var date = moment(value.date, 'YYYY-MM-D').format('MMM Do');
			var type = value.type.charAt(0).toUpperCase() + value.type.substr(1);
	  		signups += `
				<button type='button' class='list-group-item' data-toggle='modal' data-target='#eventModal' data-name='${key}' data-id='${value.id}' data-officername='${value.officer_name}' data-date='${date}' data-freeze='${value.freeze}' data-type='${type}' data-points='${value.points}'>
					<span class='badge'>${type}</span>
					<h2 class='list-group-item-heading'>${key}</h2>
						<p class='list-group-item-text'>${date}  |  ${value.points} points</p>
				</button>`;

	  		document.getElementById("signups").innerHTML = signups;	
	  	});
	});

	return false;
}