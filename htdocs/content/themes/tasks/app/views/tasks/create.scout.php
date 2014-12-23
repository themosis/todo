@extends('layouts.tasks')

@section('toolbar')
    <a class="back-to-tasks" href="{{ home_url('tasks') }}">< Back</a>
@stop

@section('toolbar_title')
    <h3>Add new task</h3>
@stop

@section('messages')
    @if(isset($error))
        <p class="error">{{{ $error }}}</p>
    @endif
@stop

@section('content')
    {{ Form::open('tasks', 'post', false, array('nonce' => APP_NONCE, 'nonce_action' => 'task_create')) }}
        <div class="task">
            <div class="clearfix">
                <div class="task__date--set left">
                    {{ Form::hidden('schedule', '', array('class' => 'datekeeper')) }}
                    {{ Form::button('toggleDate', 'Pick a date', array('class' => 'due-date')) }}
                </div>
                <div class="task__name--set left">
                    {{ Form::text('task', '', array('placeholder' => 'Type your task here...', 'autocomplete' => 'off')) }}
                </div>
            </div>
        </div>
        <p class="submit">{{ Form::submit('create', '+ Add task', array('class' => 'create-task')) }}</p>
    {{ Form::close() }}
@stop