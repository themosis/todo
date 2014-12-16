<h2>Tasks:</h2>

@if(isset($message) && $message)
    <p>{{ $message }}</p>
@endif

<p>
    <a href="{{ wp_nonce_url(home_url('tasks/create/'), 'create_task', 'action') }}">Create a task</a>
</p>

@if($query->have_posts())
    <ul>
        @while($query->have_posts())
            <?php $query->the_post(); ?>
            <li>{{ Loop::title() }} - <a href="{{ wp_nonce_url(home_url('tasks/'.Loop::id().'/edit/'), 'edit_task', 'action') }}">Edit</a> - <a href="{{ wp_nonce_url(home_url('tasks/'.Loop::id().'/delete/'), 'delete_task', 'action') }}">Delete</a></li>
        @endwhile
    </ul>
@endif
<?php wp_reset_postdata(); ?>

@if(!$query->have_posts())
    <p>Yeah! No tasks for today. <a href="{{ wp_nonce_url(home_url('tasks/create/'), 'create_task', 'action') }}">Add a task ?</a></p>
@endif