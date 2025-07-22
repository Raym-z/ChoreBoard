@component('mail::message')
# You're Invited to Join a Household!

You've been invited to join the household **{{ $household->name }}** on ChoreBoard.

@component('mail::button', ['url' => $inviteLink])
Join Household
@endcomponent

Or use this invite code: **{{ $household->invite_code }}**

If you did not expect this invitation, you can ignore this email.

Thanks,<br>
The ChoreBoard Team
@endcomponent
