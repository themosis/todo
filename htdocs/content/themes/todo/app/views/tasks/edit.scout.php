<h2>Edit:</h2>

@if(isset($error))
    <p>{{ $error }}</p>
@endif

<p>
    <a href="{{ home_url('tasks') }}">Back to tasks list</a>
</p>

{{ Form::open() }}

{{ Form::text('task', $task->post_title) }}
{{ Form::submit('edit', 'Edit task') }}

{{ Form::close() }}