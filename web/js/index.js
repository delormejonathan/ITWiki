// index.js

'use strict';

$(document).ready(function()
{
	$('#homepage .bs-sidenav li').click(function()
	{
		var $this = $(this);
		$.ajax({
			type: 'POST',
			url: Routing.generate('wiki_homepage_articles_by_tag' , {'id' : $this.attr('data-tag-id')}),
			success: function(html) {
				$('#articles').html(html);
				$('#homepage .bs-sidenav li.active').removeClass('active');
				$this.addClass('active');
			},
		});
	})

	$('textarea[data-toggle="markdown"]').keydown(function(e) {
		if(e.keyCode === 9) { // tab was pressed
			// get caret position/selection
			var start = this.selectionStart;
			var end = this.selectionEnd;

			var $this = $(this);
			var value = $this.val();

			// set textarea value to: text before caret + tab + text after caret
			$this.val(value.substring(0, start)
				+ "\t"
				+ value.substring(end));

			// put caret at right position again (add one for the tab)
			this.selectionStart = this.selectionEnd = start + 1;

			// prevent the focus lose
			e.preventDefault();
		}
	});
	$('textarea[data-toggle="markdown"]').keyup(function()
	{
		var content = $(this).val();
		$.ajax({
			type: 'POST',
			url: Routing.generate('wiki_articles_preview'),
			data: { markdown : content },
			success: function(html) {
				$('.preview').html(html);
			},
		});
	})
	$('.tag-add').typeahead([
	{
		name: 'Tags',
		remote: Routing.generate('wiki_articles_tags_remote') + '?searchQuery=%QUERY',
	}
	])
	.on('typeahead:selected', function(event , datum)
	{
		$('.tags').append('<span class="label label-primary tag">' + datum.name + '<input name="tags_id[]" type="checkbox" value="' + datum.id + '" checked="checked" style="display:none"/><input name="tags_name[]" type="checkbox" value="' + datum.name + '" checked="checked" style="display:none"/><span class="tag-delete">x</span></span>')
		$('.tag-add').typeahead('setQuery', '');
	});
	$('body').on('click' , '.tags .tag-delete' , function()
	{
		$(this).parent().remove();
	})
});