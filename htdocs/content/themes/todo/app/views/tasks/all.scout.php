<h2>Tasks:</h2>

@if(isset($taskCreated) && $taskCreated)
    <p>New task added.</p>
@endif

<ul>
    @query(array('post_type' => 'tasks', 'posts_per_page' => 500, 'post_status' => 'publish'))
        <li>{{ Loop::title() }} - <a href="{{ home_url('tasks/'.Loop::id().'/edit/') }}">Edit</a> - <a href="{{ home_url('tasks/'.Loop::id().'/delete/') }}">Delete</a></li>
    @endquery
</ul>