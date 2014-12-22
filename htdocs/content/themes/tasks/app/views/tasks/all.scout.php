@extends('layouts.tasks')

@section('pre-document')
    {{ Form::open('', 'post', false, array('nonce' => APP_NONCE, 'nonce_action' => 'task_remove')) }}
@stop

@section('toolbar')
    <a href="{{ wp_nonce_url(home_url('tasks/create/'), 'create_task', 'action') }}" class="add-task">+ Add task</a>
    <!-- Remove completed tasks -->
    {{ Form::submit('remove', '- Remove', array('class' => 'remove-task')) }}
@overwrite

@section('messages')
    @if(isset($message) && $message)
        <p>{{ $message }}</p>
    @endif
@stop

@section('content')
    @if($query->have_posts())
        <ul>
            @while($query->have_posts())
            <?php
            $query->the_post();

            // Get the due date.
            $dueDate = Meta::get(Loop::id(), 'task_due_date')
            ?>
            <li>
                <p>{{ Form::checkbox('task_check[]', Loop::id()) }}{{ Loop::title() }} | <a href="{{ wp_nonce_url(home_url('tasks/'.Loop::id().'/edit/'), 'edit_task', 'action') }}">Edit</a> - <a href="{{ wp_nonce_url(home_url('tasks/'.Loop::id().'/delete/'), 'delete_task', 'action') }}">Delete</a>
                    @if(!empty($dueDate))
                    | <b>{{{ $dueDate }}}</b>
                    @endif
                </p>
            </li>
            @endwhile
        </ul>
    @endif
    <?php wp_reset_postdata(); ?>

    @if(!$query->have_posts())
        <p>Yeah! No tasks for today. <a href="{{ wp_nonce_url(home_url('tasks/create/'), 'create_task', 'action') }}">Add a task ?</a></p>
    @endif
@stop

@section('post-document')
    {{ Form::close() }}
@stop