<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>402 - Payment Required</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #2e3a57;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            text-align: center;
        }

        .container {
            max-width: 600px;
            padding: 20px;
            background-color: #c8c8c8;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #333;
        }

        p {
            color: #666;
        }

        .contact-info {
            margin-top: 20px;
        }

        .contact-info p {
            margin: 5px 0;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>402 - Payment Required</h1>
        @if (Request::route()->named('kitchen.index'))
            <p>This content is purchasable.<br> To purchase the kitchen module, please contact:</p>
        @elseif(Request::route()->named('waiter.index'))
            <p>This content is purchasable. <br>To purchase the waiter module, please contact:</p>
        @else
            <p>This content is purchasable. To purchase, please contact:</p>
        @endif
        <div class="contact-info">
            <p>Pavan Vattikala</p>
            <p>CEO and Founder of Edge Ease</p>
            <p>Phone: <a href="tel:+918341837776">+91 8341837776</a></p>
            <p>Email: <a href="mailto:pavanvattikala54@gmail.com">pavanvattikala54@gmail.com</a></p>
            <p>Email: <a href="mailto:edgeease@gmail.com">edgeease@gmail.com</a></p>
        </div>
    </div>
</body>

</html>
