@extends('layouts.tasks')

@section('pre-document')
    {{ Form::open('', 'post', false, array('nonce' => APP_NONCE, 'nonce_action' => 'task_remove')) }}
@stop

@section('toolbar')
    <a href="{{ wp_nonce_url(home_url('tasks/create/'), 'create_task', 'action') }}" class="add-task">+ Add task</a>
    <!-- Remove completed tasks -->
    {{ Form::submit('remove', 'Remove', array('class' => 'remove-task')) }}
@overwrite

@section('messages')
    @if(!empty($message))
        <p>{{ $message }}</p>
    @endif
@stop

@section('content')
    @if($query->have_posts())
        <ul class="tasks-list">
            @while($query->have_posts())
            <?php
            $query->the_post();

            // Get the due date.
            $dueDate = Meta::get(Loop::id(), 'task_due_date')
            ?>
            <li>
                <div class="task">
                    <div class="task__content clearfix">
                        <div class="task__checked left">
                            {{ Form::checkbox('task_check', Loop::id(), '', array('class' => 'check')) }}
                        </div>
                        <div class="task__date left">
                            @if(!empty($dueDate))
                                <p>{{{ $dueDate }}}</p>
                            @else
                                <p>---</p>
                            @endif
                        </div>
                        <div class="task__name left">
                            <p>{{ Loop::title() }}</p>
                        </div>
                    </div>
                    <div class="task__tools">
                        <a class="edit-task" href="{{ wp_nonce_url(home_url('tasks/'.Loop::id().'/edit/'), 'edit_task', 'action') }}">Edit</a>
                        <a class="delete-task" href="{{ wp_nonce_url(home_url('tasks/'.Loop::id().'/delete/'), 'delete_task', 'action') }}">Delete</a>
                    </div>
                </div>
            </li>
            @endwhile
        </ul>
    @endif
    <?php wp_reset_postdata(); ?>

    @if(!$query->have_posts())
        <p class="no-tasks">Yeah! No tasks for today.</p>
    @endif
@stop

@section('post-document')
    {{ Form::close() }}
@stop