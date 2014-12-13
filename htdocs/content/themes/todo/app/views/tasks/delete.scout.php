{{ Form::open() }}

{{ Form::submit('delete', 'Delete task '.$task->ID) }}

{{ Form::close() }}