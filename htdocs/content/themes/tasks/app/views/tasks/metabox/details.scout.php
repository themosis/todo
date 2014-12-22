<?php
    $dueDate = Meta::get($__post->ID, 'task_due_date');
    $dueDate = !empty($dueDate) ? $dueDate : __('No due date.');
?>
<div>
    <h4>Due date:</h4>
    <p>{{{ $dueDate }}}</p>
</div>