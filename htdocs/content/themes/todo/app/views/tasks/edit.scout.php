<h2>Edit:</h2>
{{ Form::open() }}

{{ Form::text('task', $task->post_title) }}
{{ Form::submit('edit', 'Edit task '.$task->ID) }}

{{ Form::close() }}