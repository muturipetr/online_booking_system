<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt - Booking #{{ booking.id }}</title>
</head>
<body class="body">

<div class="receipt-container">
    <div class="header">
        <h1>Booking Receipt</h1>
        <p>Booking ID: #{{ booking.id }}</p>
        <p>Date: {{ "now"|date("Y-m-d") }}</p>
    </div>

    <div class="details">
        <h2>Customer Details</h2>
        <p><span>Name:</span> {{ app.user.username}}</p>
        <p><span>Email:</span> {{ booking.email }}</p>
    </div>

    <div class="summary">
        <h2>Booking Summary</h2>
        <table>
            <tr>
                <th>Service</th>
                <td>{{ booking.service.name }}</td>
            </tr>
            <tr>
                <th>Date</th>
                <td>{{ booking.date ? booking.date|date('Y-m-d') : 'N/A' }}</td>
            </tr>
            <tr>
                <th>Time</th>
                <td>{{ booking.time ? booking.time|date('H:i') : 'N/A' }}</td>
            </tr>
            <tr>
                <th>Duration</th>
                <td>{{ booking.duration }} hours</td>
            </tr>
            <tr>
                <th>Total Price</th>
                <td><strong>Kshs</strong>{{ booking.TotalPrice }}</td>
            </tr>
        </table>
    </div>

    <div class="total">
        <h3>Total Amount Due: <strong>Kshs</strong>{{ booking.TotalPrice }}</h3>
    </div>

    <div class="footer">
        <p>Thank you for your booking!</p>
        <p>If you have any questions, feel free to contact us at josestudio@gmail.com or call us at (+254) 752210975.</p>
    </div>
</div>

</body>
</html>
