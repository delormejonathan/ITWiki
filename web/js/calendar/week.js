$(document).ready(function() {
	$('#calendar_day').fullCalendar({
		defaultView : 'agendaWeek',
		// lazyFetching : true,
		ignoreTimezone : true,
		minTime : 0,
		firstDay : 1,
		editable : true,
		columnFormat : {
			week : 'ddd d/M'
		},
		axisFormat : 'HH:mm',
		titleFormat : "MMMM d[ yyyy]{ '-'[ MMMM] d yyyy}",
		timeFormat : 'HH:mm',
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
				url: Routing.generate('intranet_calendar_fetch'),
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
		eventRender: function (event, element) {
			customEventRender(event,element, 'week');
		},
		dayClick: function(date, allDay, jsEvent, view) 
		{
			customDayClick(date, allDay , jsEvent, view);
		},
		eventClick: function(calEvent, jsEvent, view) 
		{ 
			customEventClick(calEvent, jsEvent, view);
		},
		eventDrop: function(event, dayDelta, minuteDelta, allDay, revertFunc, jsEvent, ui, view) 
		{
			view = "day";
			customEventDrop(event, dayDelta, minuteDelta, allDay, revertFunc, jsEvent, ui, view);
		},
		eventDragStart: function(event, dayDelta, minuteDelta, allDay, revertFunc, jsEvent, ui, view) 
		{
			$('.popover').hide();
		},
		eventResize: function(event, dayDelta, minuteDelta, allDay, revertFunc, jsEvent, ui, view) 
		{
			view = "day";
			customEventResize(event, dayDelta, minuteDelta, allDay, revertFunc, jsEvent, ui, view);
		}
	});
});