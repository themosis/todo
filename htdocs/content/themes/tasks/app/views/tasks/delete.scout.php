@extends('layouts.tasks')

@section('toolbar')
    <a class="back-to-tasks" href="{{ home_url('tasks') }}">< Back</a>
@stop

@section('toolbar_title')
    <h3>Delete task</h3>
@stop

@section('messages')
    @if(isset($error))
        <p class="error">{{{ $error }}}</p>
    @endif
@stop

@section('content')
    <div class="task">
        <div class="clearfix">
            <div class="task__date--set left">
                <?php
                    $date = Meta::get($task->ID, 'task_due_date')
                ?>
                @if(empty($date))
                    <p>---</p>
                @else
                    <p>{{ $date }}</p>
                @endif
            </div>
            <div class="task__name--set left">
                <p>{{ $task->post_title }}</p>
            </div>
        </div>
    </div>
    {{ Form::open() }}
        {{ Form::hidden('task', $task->ID) }}
        <p class="ays">Are you sure you want to delete this task ?</p>
        <p class="submit">{{ Form::submit('delete', 'Delete task', array('class' => 'delete-task')) }}</p>
    {{ Form::close() }}
@stop