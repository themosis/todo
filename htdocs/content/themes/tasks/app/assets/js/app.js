(function($)
{
    /*****************************************************************/
    // Checked tasks.
    /*****************************************************************/
    $('input.check').on('change', function()
    {
        var texts = $(this).closest('div.task__content').find('p'),
            removeBtn = $('button.remove-task'),
            taskTools = $(this).closest('div.task').find('div.task__tools');

        // If is checked, strike through texts
        // and hide its tools
        if (this.checked)
        {
            texts.addClass('done');
            taskTools.addClass('hide');
        }
        else
        {
            texts.removeClass('done');
            taskTools.removeClass('hide');
        }

        // Check if there are checked tasks.
        // If so, show the remove button.
        var checked = $('input.check:checked');

        if (checked.length)
        {
            removeBtn.addClass('show');
        }
        else
        {
            removeBtn.removeClass('show');
        }
    });
})(jQuery);