<h2>Delete task:</h2>

@if(isset($error))
    <p>{{ $error }}</p>
@endif

<p>
    <a href="{{ home_url('tasks') }}">Back to tasks list</a>
</p>

<p>Are you sure you want to delete this task ?</p>
<ul>
    <li>{{ $task->post_title }}</li>
</ul>

{{ Form::open() }}

{{ Form::hidden('task', $task->ID) }}
{{ Form::submit('delete', 'Delete task') }}

{{ Form::close() }}