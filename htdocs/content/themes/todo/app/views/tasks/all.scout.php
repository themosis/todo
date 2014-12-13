<h2>Tasks:</h2>

<ul>
    @loop
        <li>{{ Loop::title() }} - <a href="{{ home_url('tasks/'.Loop::id().'/edit/') }}">Edit</a></li>
    @endloop
</ul>