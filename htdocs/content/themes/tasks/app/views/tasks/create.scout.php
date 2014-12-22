<h2>Create task:</h2>

@if(isset($error))
    <p>{{{ $error }}}</p>
@endif

<p>
    <a href="{{ home_url('tasks') }}">Back to tasks list</a>
</p>

{{ Form::open('tasks', 'post', false, array('nonce' => APP_NONCE, 'nonce_action' => 'task_create')) }}

<p>{{ Form::text('task') }} - Due date ? - {{ Form::text('schedule') }}</p>

<p>{{ Form::submit('create', 'Create Task') }}</p>

{{ Form::close() }}