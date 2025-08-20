@php
  use Carbon\Carbon;
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation - Easy Multi Vendor Shop</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .order-details {
            background-color: #f9f9f9;
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 20px;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            color: #666;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        .total {
            font-weight: bold;
            background-color: #f0f0f0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Easy Multi Vendor Shop</h1>
            <p>Order Confirmation</p>
        </div>

        <div class="order-details">
            <h2>Order Details</h2>
            <p>Invoice No: {{ $data['invoice_no'] }}</p>
            <p>Date: {{ Carbon::now()->format('d F Y') }}</p>
            <p>Customer: {{ $data['name'] }}</p>
            <p>Email: {{ $data['email'] }}</p>
        </div>

        <div class="order-summary">
            <h2>Order Summary</h2>
            <table>
                <tr>
                    <th>Subtotal</th>
                    <td>${{ number_format($data['amount'], 2) }}</td>
                </tr>
                <tr class="total">
                    <th>Total Amount</th>
                    <td>${{ number_format($data['amount'], 2) }}</td>
                </tr>
            </table>
        </div>

        <div class="footer">
            <p>Thank you for shopping with us!</p>
            <p>Best regards,<br>
            Easy Multi Vendor Shop Team</p>
        </div>
    </div>
</body>
</html>