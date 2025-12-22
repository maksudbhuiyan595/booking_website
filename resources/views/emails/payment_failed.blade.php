<!DOCTYPE html>
<html>
<head>
    <title>Payment Failed - Booking Unsuccessful</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #ddd; border-radius: 5px; }
        .header { background: #dc3545; color: #fff; padding: 10px; text-align: center; border-radius: 5px 5px 0 0; }
        .details { background: #f9f9f9; padding: 15px; margin-top: 15px; border-radius: 5px; }
        .details ul { list-style: none; padding: 0; }
        .details li { margin-bottom: 8px; }
        .btn { display: inline-block; margin-top: 20px; padding: 10px 20px; background: #333; color: #fff; text-decoration: none; border-radius: 4px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Payment Failed</h2>
        </div>

        <p>Dear <strong>{{ $details['name'] }}</strong>,</p>
        <p>We are sorry, but we were unable to process your booking payment. Please check your card details or try a different payment method.</p>

        <div class="details">
            <h3>Attempt Details:</h3>
            <ul>
                <li><strong>Name:</strong> {{ $details['name'] }}</li>
                <li><strong>Email:</strong> {{ $details['email'] }}</li>
                <li><strong>Phone:</strong> {{ $details['phone'] }}</li>
                <li style="color: #dc3545;"><strong>Error Reason:</strong> {{ $details['error_message'] }}</li>
                <li><strong>Time:</strong> {{ $details['date'] }}</li>
            </ul>
        </div>

        <p>Your account has <strong>not</strong> been charged.</p>

        <center>
            <a href="{{ url('/') }}" class="btn">Try Booking Again</a>
        </center>
    </div>
</body>
</html>
