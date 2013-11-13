$(document).ready(function() {
	$(".form_datetime").datetimepicker(
	{
		format: 'yyyy-mm-dd hh:ii',
		language: 'fr',
		autoclose: true
	});

	$(document).on("keyup.autocomplete", '#customer_search', function(){
		var input = $(this);
		$(this).autocomplete(
		{
			source: function(request, response) 
			{
				$.ajax({
					method: "POST",
					url: Routing.generate('intranet_ajax_customersearch'),
					data : { searchQuery : input.val() },
					// dataType: "xml",
					success: function( xmlResponse ) 
					{
						var data = $( "resultat", xmlResponse ).map(function() {
							return {
								id: $( "id", this ).text(),
								parent: $( "parent", this ).text(),
								left: $( "left", this ).text(),
								right: $( "right", this ).text(),
								value: $( "lastname", this ).text() + " " + $( "firstname", this ).text(),
								lastname: $( "lastname", this ).text(),
								firstname: $( "firstname", this ).text(),
								phone1: $( "phone1", this ).text(),
								phone2: $( "phone2", this ).text(),
								address: $( "address", this ).text(),
								zip: $( "zip", this ).text(),
								city: $( "city", this ).text(),
							};
						});
						response(data);
					}
				})   
			},
			minLength:  2,
			appendTo : input.parent().parent().parent(),
			select: function( event, ui ) 
			{
				if (ui.item.id != null)
				{
					ui.item.value = ui.item.lastname;
					// alert(input.parent().parent().parent().parent().parent().find("#intranet_event_add_customer_id").attr('name'));
					// alert(ui.item.id);
					// alert($("#interventions .add .search").val(ui.item.lastname));
					input.parent().parent().parent().parent().parent().parent().find("#intranet_event_add_customer_id").val(ui.item.id);
				}
			}
		});
	});

	// color picker
	$('#color-picker div').click(function()
	{
		$('#color-picker div').each(function ()
		{
			$(this).removeClass('selected');
		});
		$('#color-radio select').val($(this).attr('id'));

		$(this).addClass('selected');
		$('#color-radio input[value="' + $(this).attr('id') + '"]').attr('checked','checked');
	});

	$( "#trash" ).droppable({
		drop: function( event, ui ) {
			var noty_load = 
			noty({
				layout: 'bottomRight',
				animation: {
					open: {height: 'toggle'},
					close: {height: 'toggle'},
					easing: 'swing',
					speed: 500,
				},
				type: 'information',
				text: 'Suppression en cours'
			});
			$.ajax({
				type: 'POST',
				url: Routing.generate('intranet_calendar_delete' , { id : ui.draggable.find('.fc-event-inner').attr('id') }),
				success: function(data) {
					noty_load.close();
					$('#calendar_day').fullCalendar( 'refetchEvents' );
					$('#calendar_month').fullCalendar( 'refetchEvents' );
				},
			});
		}
	});
});
function customEventRender(event, element , viewType)
{
	if (event.intervention_id != null || event.type == 1)
	{
		element.find('.fc-event-time').prepend('<span class="glyphicon glyphicon-wrench"></span> ');
	}
	else if (event.type == 2)
	{
		element.find('.fc-event-time').prepend('<span class="glyphicon glyphicon-user"></span> ');
	}
	else if (event.type == 3)
	{
		element.find('.fc-event-time').prepend('<span class="glyphicon glyphicon-time"></span> ');
	}
	element.popover({
		title: event.title,
		html : true,
		trigger : 'hover',
		placement: 'bottom',
		container: 'body',
		content: function()
		{
			var content = '';
			if (event.allDay)
			{
				content += '<strong>Toute la journée</strong>' + '<br/>';
			}
			else
			{
				content += '<strong>Date de départ : </strong>' + $.fullCalendar.formatDate(new Date(event.start), 'yyyy-MM-dd HH:mm') + '<br/>';
				content += '<strong>Date de fin : </strong>' + $.fullCalendar.formatDate(new Date(event.end), 'yyyy-MM-dd HH:mm') + '<br/>';
			}
			content += '<strong>Description : </strong>' + event.description;

			return content;
		}
	});
}
function customDayClick(date, allDay , jsEvent, view)
{
	$('#modal_calendar_add').find('select').removeClass('input-sm');
	// on reset le formulaire du modal
	$('#modal_calendar_add form')[0].reset();
	// on initialise les input de début/fin avec le jour sélectionné
	if ( view == 'month' )
	{
		$('#modal_calendar_add .form_datetime #intranet_event_add_start').val($.fullCalendar.formatDate(new Date(date.getTime() + 3600000 * 8), 'yyyy-MM-dd HH:mm'));
		$('#modal_calendar_add .form_datetime #intranet_event_add_end').val($.fullCalendar.formatDate(new Date(date.getTime() + 3600000 * 9), 'yyyy-MM-dd HH:mm'));
	}
	else
	{	
		if ( ! allDay )
		{
			$('#modal_calendar_add .form_datetime #intranet_event_add_start').val($.fullCalendar.formatDate(date, 'yyyy-MM-dd HH:mm'));
			$('#modal_calendar_add .form_datetime #intranet_event_add_end').val($.fullCalendar.formatDate(new Date(date.getTime() + 3600000), 'yyyy-MM-dd HH:mm'));
		}
		else
		{
			$('#modal_calendar_add .form_datetime #intranet_event_add_start').val($.fullCalendar.formatDate(new Date(date.getTime() + 3600000 * 8), 'yyyy-MM-dd HH:mm'));
			$('#modal_calendar_add .form_datetime #intranet_event_add_end').val($.fullCalendar.formatDate(new Date(date.getTime() + 3600000 * 9), 'yyyy-MM-dd HH:mm'));
		}
		
	}
	// on signale au datetimepicker que le contenu du input a changé
	$('.form_datetime').datetimepicker('update');
	// on affiche le modal
	$('#modal_calendar_add').modal('show')

	// ajouter un évènement
	$('#modal_calendar_add .btn-success').one('click' , function()
	{
		var noty_load = 
			noty({
				layout: 'bottomRight',
				animation: {
					open: {height: 'toggle'},
					close: {height: 'toggle'},
					easing: 'swing',
					speed: 500,
				},
				type: 'information',
				text: 'Sauvegarde en cours',
				timeout: 1000
			});
		$.ajax({
			type: 'POST',
			url: Routing.generate('intranet_calendar_add'),
			data : $('#modal_calendar_add form').serialize(),
			dataType : 'json',
			success: function(data) {
				noty_load.close();
				$('#modal_calendar_add').modal('hide');
				$('#calendar_month').fullCalendar('renderEvent', data);
				$('#calendar_day').fullCalendar('renderEvent', data);
			},
		});
	});
}

function customEventClick (calEvent, jsEvent, view) 
{
	var noty_load = 
	noty({
		layout: 'bottomRight',
		animation: {
			open: {height: 'toggle'},
			close: {height: 'toggle'},
			easing: 'swing',
			speed: 500,
		},
		type: 'information',
		text: 'Chargement en cours',
		timeout: 1000
	});
	$.ajax({
		type: 'GET',
		url: Routing.generate('intranet_calendar_edit' , { id : calEvent.id}),
		success: function(html) {
			noty_load.close();
			$('#modal_calendar_edit .modal-body').html(html);
			$('#modal_calendar_edit').modal('show');
			$('#modal_calendar_edit').find('select').removeClass('input-sm');
			$("#modal_calendar_edit .form_datetime").datetimepicker(
			{
				format: 'yyyy-mm-dd hh:ii',
				language: 'fr',
				autoclose: true
			});
		},
	});


	// Editer un évènement
	$('#modal_calendar_edit .btn-success').one('click' , function()
	{
		var noty_load = 
		noty({
			layout: 'bottomRight',
			animation: {
				open: {height: 'toggle'},
				close: {height: 'toggle'},
				easing: 'swing',
				speed: 500,
			},
			type: 'information',
			text: 'Sauvegarde en cours',
			timeout: 1000
		});
		$.ajax({
			type: 'POST',
			url: Routing.generate('intranet_calendar_edit' , { id : calEvent.id}),
			data : $('#modal_calendar_edit form').serialize(),
			dataType : 'json',
			success: function(data) {
				noty_load.close();
				$('#modal_calendar_edit').modal('hide');

				calEvent.title = data.title;
				calEvent.start = data.start;
				calEvent.end = data.end;
				calEvent.allDay = data.allDay;
				calEvent.color = data.color;
				$('#calendar_month').fullCalendar( 'updateEvent', calEvent )
				$('#calendar_day').fullCalendar( 'updateEvent', calEvent )
			},
		});
	});
}
function customEventDrop (event, dayDelta, minuteDelta, allDay, revertFunc, jsEvent, ui, view)
{
	if (event.end == null)
	{
		event.end = event.start;
	}
	var noty_load = 
	noty({
		layout: 'bottomRight',
		easing: 'swing',
		speed: 500,
		type: 'information',
		text: 'Sauvegarde en cours',
		timeout: 1000,
		animation: {
			open: {height: 'toggle'},
			close: {height: 'toggle'},
		},	
		});

	$.ajax({
		type: 'GET',
		url: Routing.generate('intranet_calendar_dropped'),
		data :
		{
			event_id : event.id,
			start : $.fullCalendar.formatDate(event.start, 'yyyy-MM-dd HH:mm'),
			end : $.fullCalendar.formatDate(event.end, 'yyyy-MM-dd HH:mm'),
			allday : event.allDay
		},
		success: function(data) {
			noty_load.close();
			if ( view == "month" )
				$('#calendar_day').fullCalendar( 'refetchEvents' );
			else
				$('#calendar_month').fullCalendar( 'refetchEvents' );
		},

		error: function (jqXHR, textStatus, errorThrown) {
			alert("Echec de la sauvegarde");
			revertFunc();
		}
	});
}

function customEventResize (event, dayDelta, minuteDelta, allDay, revertFunc, jsEvent, ui, view) 
{
	if (event.end == null)
	{
		event.end = event.start;
	}
	var noty_load = 
	noty({
		layout: 'bottomRight',
		animation: {
			open: {height: 'toggle'},
			close: {height: 'toggle'},
			easing: 'swing',
			speed: 500,
		},
		type: 'information',
		text: 'Sauvegarde en cours',
		timeout: 1000
	});
	$.ajax({
		type: 'GET',
		url: Routing.generate('intranet_calendar_dropped'),
		data :
		{
			event_id : event.id,
			start : $.fullCalendar.formatDate(event.start, 'yyyy-MM-dd HH:mm'),
			end : $.fullCalendar.formatDate(event.end, 'yyyy-MM-dd HH:mm'),
			allday : event.allDay
		},
		success: function(data) {
			noty_load.close();
			if ( view == "month" )
				$('#calendar_day').fullCalendar( 'refetchEvents' );
			else
				$('#calendar_month').fullCalendar( 'refetchEvents' );
		},

		error: function (jqXHR, textStatus, errorThrown) {
			alert("Echec de la sauvegarde");
			revertFunc();
		}
	});
}