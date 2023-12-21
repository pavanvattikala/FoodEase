<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        /* Add your custom styles here */
        *{
            margin-top: 0px; margin-bottom: 0px; 
            margin-left:5px; margin-right:5px;
        }
        td{
            padding: 2px;
        }
        .center{
            text-align: center;
        }
        .left{
            text-align: left;
            margin-left: 100px; /* Adjust this margin if needed */
        }
    </style>
</head>
<body>
    <div class="name">
        <h1 class="center">{{ $resName }}</h1>
    </div>

    <div>
        <h3 class="center">Bill ID:{{ $billDetails['id'] }}</h3>
        <p>Table: {{ $billDetails['table_no'] }} ---------  {{ date('d M, Y h:i a') }}</p>
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
            @foreach($orderDetails as $name => $details)
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
            <p >Total: Rs {{ $billDetails['grand_total'] }}</p>
            
            <p >Discount: Rs {{ $billDetails['discount'] }}</p>
            <p>------------------------------------</p>
            <p>Grand Total: Rs {{ $billDetails['grand_total'] }}</p>

            <p>------------------------------------</p>
       </div>

    </div>

    <div class="center">
        <p>{{ $address }}</p>
        <p>{{ $phone }}</p>
    </div>

</body>
</html>
