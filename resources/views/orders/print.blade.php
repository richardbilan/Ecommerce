<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Order #{{ sprintf('%06d', $order->id) }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .order-info {
            margin-bottom: 30px;
        }
        .section {
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }
        th {
            background-color: #f8f9fa;
        }
        .total {
            text-align: right;
            font-weight: bold;
            font-size: 1.1em;
            margin-top: 20px;
        }
        @media print {
            body {
                padding: 0;
                margin: 0;
            }
            @page {
                margin: 2cm;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Order Details</h1>
        <p>Order #{{ sprintf('%06d', $order->id) }}</p>
        <p>{{ $order->created_at->format('F d, Y h:i A') }}</p>
    </div>

    <div class="order-info">
        <div class="section">
            <h2>Customer Information</h2>
            <p><strong>Name:</strong> {{ $order->user_name }}</p>
            <p><strong>Delivery Location:</strong> {{ $order->location }}</p>
            <p><strong>Payment Method:</strong> {{ $order->payment_method }}</p>
        </div>

        <div class="section">
            <h2>Order Status</h2>
            <p><strong>Current Status:</strong> {{ ucfirst($order->status) }}</p>
            <p><strong>Last Updated:</strong> {{ $order->updated_at->format('F d, Y h:i A') }}</p>
        </div>
    </div>

    <div class="section">
        <h2>Order Items</h2>
        <table>
            <thead>
                <tr>
                    <th>Item</th>
                    <th style="text-align: center;">Quantity</th>
                    <th style="text-align: right;">Price</th>
                    <th style="text-align: right;">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @if($order->items)
                    @foreach($order->items as $item)
                    <tr>
                        <td>{{ $item['name'] }}</td>
                        <td style="text-align: center;">{{ $item['quantity'] }}</td>
                        <td style="text-align: right;">₱{{ number_format($item['price'], 2) }}</td>
                        <td style="text-align: right;">₱{{ number_format($item['price'] * $item['quantity'], 2) }}</td>
                    </tr>
                    @endforeach
                @else
                    <tr>
                        <td>{{ $order->name }}</td>
                        <td style="text-align: center;">{{ $order->quantity }}</td>
                        <td style="text-align: right;">₱{{ number_format($order->price, 2) }}</td>
                        <td style="text-align: right;">₱{{ number_format($order->price * $order->quantity, 2) }}</td>
                    </tr>
                @endif
            </tbody>
        </table>

        <div class="total">
            Total Amount: ₱{{ number_format($order->total_amount, 2) }}
        </div>
    </div>

    <script>
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html> 