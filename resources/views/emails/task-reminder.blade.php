<h2>{{ __('messages.task_reminder_subject') }}</h2>
<p>{{ __('messages.hello') }}, {{ $task->user->first_name }}.</p>
<p>{{ __('messages.task_due_tomorrow') }}: <strong>{{ $task->title }}</strong></p>
