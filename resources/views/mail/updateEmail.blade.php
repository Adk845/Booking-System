{{-- {!! $data_email['isi'] !!} --}}
<!DOCTYPE html>
<html>
<head>
<title>Booking Room Notification</title>
</head>
<body>
    <h1>Update Booking Room Schedule</h1>
    <p>Room: {{ $data_email['title'] }}</p>
    <p>Name: {{ $data_email['name'] }}</p>
    <p>Email: {{ $data_email['user_email'] }}</p>
    <p>Starts at: {{ $data_email['start'] }}</p>
    <p>Ends at: {{ $data_email['end'] }}</p>
    <p>Thank you for using our system!</p>
</body>

</html>