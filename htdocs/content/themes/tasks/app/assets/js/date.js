(function($)
{
    /*****************************************************************/
    // Handle task date picker.
    /*****************************************************************/
    var picker = new Pikaday({
        field: $('button.due-date')[0],
        format: 'dddd L',
        onSelect: function() {
            var hidden = $('input.datekeeper'),
                dateContainer = $('div.task__date--set');

            // Clear date field.
            dateContainer.find('p').remove();

            // Update hidden field value.
            hidden.val(picker.toString());

            // Append date along the calendar.
            dateContainer.append($('<p class="has-date">'+ picker.toString() +'</p>'));
        }
    });

})(jQuery);