<h2>Edit:</h2>

@if(isset($error))
    <p>{{ $error }}</p>
@endif

<p>
    <a href="{{ home_url('tasks') }}">Back to tasks list</a>
</p>

{{ Form::open() }}

<p>{{ Form::text('task', $task->post_title) }} - Due date ? - {{ Form::text('schedule', Meta::get($task->ID, 'task_due_date')) }}</p>

<p>{{ Form::submit('edit', 'Edit task') }}</p>

{{ Form::close() }}