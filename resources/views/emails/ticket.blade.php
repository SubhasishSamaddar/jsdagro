Hello <strong>{{$first_name}} {{$last_name}}</strong>,
<p>You have successfully booked the ticket. Please find below details.</p>
<p>
    Event Name: {{$event_name}} <br/>
    Event On: {{ date('M d, Y',strtotime($event_on))}}<br/>
    Email: {{$user_email}}<br/>
    Mobile: {{$phone}}<br/>
    Ticket Code: {{$ticket_code}}<br/>
    Type of Ticket: {{$ticket_title}} @ ${{$ticket_amount}}<br/>
    Number of Booked Tickets: {{$ticket_count}}<br/>
    Amount Paid: ${{$paid_amount}}<br/>
    Transaction Id: {{$payment_id}}<br/>

</p>
