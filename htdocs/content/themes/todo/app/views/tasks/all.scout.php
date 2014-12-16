<h2>Tasks:</h2>

@if(isset($taskCreated) && $taskCreated)
    <p>Your task has been added.</p>
@endif

<p>
    <a href="{{ wp_nonce_url(home_url('tasks/create/'), 'create_task', 'action') }}">Create a task</a>
</p>

@if($query->have_posts())
    <ul>
        @while($query->have_posts())
            <?php $query->the_post(); ?>
            <li>{{ Loop::title() }} - <a href="{{ home_url('tasks/'.Loop::id().'/edit/') }}">Edit</a> - <a href="{{ home_url('tasks/'.Loop::id().'/delete/') }}">Delete</a></li>
        @endwhile
    </ul>
@endif
<?php wp_reset_postdata(); ?>

@if(!$query->have_posts())
    <p>Yeah! No tasks for today. <a href="{{ wp_nonce_url(home_url('tasks/create/'), 'create_task', 'action') }}">Add a task ?</a></p>
@endif