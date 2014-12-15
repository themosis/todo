<h2>Create task:</h2>

{{ Form::open('tasks') }}

{{ Form::text('task') }}
{{ Form::submit('create', 'Create Task') }}

{{ Form::close() }}