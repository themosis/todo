<h2>Create task:</h2>

@if(isset($error))
    <p>{{{ $error }}}</p>
@endif

<p>
    <a href="{{ home_url('tasks') }}">Back to tasks list</a>
</p>

{{ Form::open('tasks') }}

{{ Form::text('task') }}
{{ Form::submit('create', 'Create Task') }}

{{ Form::close() }}