$(document).ready(function() {

	var editable = false;
	if (typeof(customDayClick) == "function")
		editable = true;

	var id = 0;
	if (typeof($('#calendar_month').attr('data-id')) != "undefined")
	{
		id = $('#calendar_month').attr('data-id');
	}
	$('#calendar_month').fullCalendar({
		defaultView : 'month',
		editable : editable,
		monthNames:['Janvier','Février','Mars','Avril','Mai','Juin','Juillet','Août','Septembre','Octobre','Novembre','Décembre'],
		monthNamesShort:['janv.','févr.','mars','avr.','mai','juin','juil.','août','sept.','oct.','nov.','déc.'],
		dayNames: ['Dimanche','Lundi','Mardi','Mercredi','Jeudi','Vendredi','Samedi'],
		dayNamesShort: ['Dim', 'Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam'],
		buttonText: {
			today:    'Aujourd\'hui',
			month:    'Mois',
			week:     'Semaine',
			day:      'Jour'
		},
		events : function(start, end, callback) 
		{
			$.ajax({
				url: Routing.generate('intranet_calendar_fetch', { id: id }),
				dataType: 'json',
				data: {
					// our hypothetical feed requires UNIX timestamps
					start: Math.round(start.getTime() / 1000),
					end: Math.round(end.getTime() / 1000),
					user_id: $('#calendar_month').attr('data-user-id')
				},
				success: function(data) {
					var events = [];
					$(data).each(function() {
						events.push({
							id: $(this).attr('id'),
							title: $(this).attr('title'),
							start: $(this).attr('start'),
							end: $(this).attr('end'),
							allDay: $(this).attr('allDay'),
							color: $(this).attr('color'),
							description: $(this).attr('description'),
							intervention_id: $(this).attr('intervention_id'),
							type: $(this).attr('type'),
						});
					});
					callback(events);
				}
			});
		},
		firstDay : 1,
		timeFormat : "HH'h'",
		// lazyFetching : true,
		eventRender: function (event, element) {
			if (typeof(customEventRender) == "function")
				customEventRender(event,element,'month');
		},
		dayClick: function(date, allDay, jsEvent, view) 
		{
			if (typeof(customDayClick) == "function")
				customDayClick(date, allDay , jsEvent, view);
		},
		eventClick: function(calEvent, jsEvent, view) 
		{ 
			if (typeof(customEventClick) == "function")
				customEventClick(calEvent, jsEvent, view);
		},
		eventDrop: function(event, dayDelta, minuteDelta, allDay, revertFunc, jsEvent, ui, view) 
		{
			view = "month";
			if (typeof(customEventDrop) == "function")
				customEventDrop(event, dayDelta, minuteDelta, allDay, revertFunc, jsEvent, ui, view);
		},
		eventResize: function(event, dayDelta, minuteDelta, allDay, revertFunc, jsEvent, ui, view) 
		{
			view = "month";
			if (typeof(customEventResize) == "function")
				customEventResize(event, dayDelta, minuteDelta, allDay, revertFunc, jsEvent, ui, view);
		},
		eventDragStart : function( event, jsEvent, ui, view )
		{
			ui.helper.find('.fc-event-inner').attr('id', event.id);
			$('#trash').show('slow');
			$('.popover').hide();
		},
		eventDragStop : function( event, jsEvent, ui, view )
		{
			ui.helper.find('.fc-event-inner').attr('id', event.id);
			$('#trash').hide('slow');
		}
	});
});