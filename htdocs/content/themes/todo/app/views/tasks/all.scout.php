<h2>Tasks:</h2>

@if(isset($taskCreated) && $taskCreated)
    <p>New task added.</p>
@endif

<p>
    <a href="{{ wp_nonce_url(home_url('tasks/create/'), 'create_task', 'action') }}">Create a task</a>
</p>

<ul>
    @query($query)
        <li>{{ Loop::title() }} - <a href="{{ home_url('tasks/'.Loop::id().'/edit/') }}">Edit</a> - <a href="{{ home_url('tasks/'.Loop::id().'/delete/') }}">Delete</a></li>
    @endquery
</ul>