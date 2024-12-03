{{-- {!! $data_email['isi'] !!} --}}
<!DOCTYPE html>
<html>
<head>
    <title>Event Notification</title>
</head>
<body>
    <h1>Event Notification</h1>
    <p>Event: {{ $data_email['title'] }}</p>
    <p>Nama: {{ $data_email['name']}}</p>
    <p>Nama Email: {{ $data_email['user_email'] }}</p>
    <p>Dimulai pada: {{ $data_email['start'] }}</p>
    <p>Berakhir pada: {{ $data_email['end'] }}</p>
    <p>Terimakasih telah menggunakan sistem kami!</p>
</body>
</html>