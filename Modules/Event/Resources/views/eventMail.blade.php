<!DOCTYPE html>
<html>
<head>
    <title>Send mail</title>
</head>
<body>
    <h1>{{ $details['title'] }}</h1>
    <p>Time: {{ $details['event']->event_time }}</p>
    <p>Date: {{ $details['event']->start_date }}</p>
    <p>Location: {{ $details['event']->location }}</p>
    <p>Description: {{ $details['event']->description }}</p>
   
    <div class="card">
        <div class="card-header">
            <h4>QR Code Check In</h4>
        </div>
        <div class="card-body">
            {!! QrCode::format('svg')->generate('1') !!}
        </div>
    </div>
</body>
</html>