<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        /* Add your custom styles here */
        * {
            margin-top: 5px;
            margin-bottom: 0px;
            margin-left: 5px;
            margin-right: 5px;
        }

        td {
            padding: 2px;
        }

        .center {
            text-align: center;
        }

        .left {
            text-align: left;
            margin-left: 80px;
            /* Adjust this margin if needed */
        }

        .last {
            margin-top: 60px;
        }
    </style>
</head>

<body>
    <div class="name">
        <h2 class="center">{{ $restaurant['name'] }}</h2>
        <p class="center">{{ $restaurant['address'] }}</p>
        <p class="center">{{ $restaurant['phone'] }}</p>
        <p class="center">Cash/Bill</p>
    </div>

    <div>
        <h4 class="center">Bill ID:{{ $billDetails['id'] }}</h4>
        <p>Table: {{ $billDetails['table_no'] }} &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            {{ $billDetails['date'] }}</p>
    </div>

    <div>
        <table>
            <thead>
                <tr>
                    <th>Sno</th>
                    <th>Item</th>
                    <th>Qty</th>
                    <th>Price</th>
                    <th>Amt</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($orderDetails as $name => $details)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $name }}</td>
                        <td>{{ $details['quantity'] }}</td>
                        <td>{{ $details['price'] }}</td>
                        <td>{{ $details['price'] * $details['quantity'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="left">
            <p>------------------------------------</p>
            <p>Total: Rs {{ $billDetails['grand_total'] }}</p>

            <p>Discount: Rs {{ $billDetails['discount'] }}</p>
            <p>------------------------------------</p>
            <p>Grand Total: Rs {{ $billDetails['grand_total'] }}</p>

            <p>------------------------------------</p>
        </div>

    </div>

    <div class="center last">
        <p>**{{ $restaurant['tagline'] }}**</p>
        <p>**Thank You For Dining With Us**</p>
    </div>

</body>

</html>
