@component('mail::message')
# Event Approved: {{ $event->event_title }}

Hello,

We are pleased to inform you that the event **{{ $event->event_title }}** has been approved and is now ready for your participation.

**Event Details:**
- **Date:** {{ $event->start_date }} until {{ $event->end_date }}
- **Time:** {{ $event->start_time }} to {{ $event->end_time }}
- **Platform:** {{ $event->platform }}
- **Objective:** {{ $event->objective }}

We look forward to your participation!

@component('mail::button', ['url' => route('login')])
View Event Details
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
