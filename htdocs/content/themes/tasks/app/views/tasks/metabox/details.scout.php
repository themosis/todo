<?php
    $dueDate = Meta::get(Input::get('post'), 'task_due_date');
    $dueDate = !empty($dueDate) ? $dueDate : __('No due date.');
?>
<div>
    <h4>Due date:</h4>
    <p>{{{ $dueDate }}}</p>
</div>