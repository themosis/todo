@extends('layouts.tasks')

@section('toolbar')
    <a class="back-to-tasks" href="{{ home_url('tasks') }}">< Back</a>
@stop

@section('toolbar_title')
    <h3>Edit task</h3>
@stop

@section('messages')
    @if(isset($error))
        <p class="error">{{{ $error }}}</p>
    @endif
@stop

@section('content')
    {{ Form::open() }}
        <div class="task">
            <div class="clearfix">
                <div class="task__date--set left">
                    <?php
                        $date = Meta::get($task->ID, 'task_due_date');
                    ?>
                    {{ Form::hidden('schedule', $date, array('class' => 'datekeeper')) }}
                    {{ Form::button('toggleDate', 'Pick a date', array('class' => 'due-date')) }}
                    @if(!empty($date))
                        <p class="has-date">{{{ $date }}}</p>
                    @endif
                </div>
                <div class="task__name--set left">
                    {{ Form::text('task', $task->post_title, array('placeholder' => 'Type your task here...', 'autocomplete' => 'off')) }}
                </div>
            </div>
        </div>
        <p class="submit">{{ Form::submit('edit', 'Edit task', array('class' => 'create-task')) }}</p>
    {{ Form::close() }}
@stop