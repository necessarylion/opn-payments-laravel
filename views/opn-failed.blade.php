<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Opn Payment | {{ $config['title'] }}</title>
    <link href='https://cdn.omise.co/fonts/circular.css' rel='stylesheet'>
    <style>
        :root {
            --themeColor : {{ $config['theme']['color'] }};
            --primaryTextColor : {{ $config['theme']['primaryTextColor'] }};
            --secondaryTextColor : {{ $config['theme']["secondaryTextColor"] }};
        }
    </style>
    <link href='/opn-payments/opn-style.css' rel='stylesheet'>
</head>

<body class="info">
    <div class="container">
        <div class="content">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#ed5050">
                <path d="M0 0h24v24H0z" fill="none" />
                <path
                    d="M12 2C6.47 2 2 6.47 2 12s4.47 10 10 10 10-4.47 10-10S17.53 2 12 2zm5 13.59L15.59 17 12 13.41 8.41 17 7 15.59 10.59 12 7 8.41 8.41 7 12 10.59 15.59 7 17 8.41 13.41 12 17 15.59z" />
            </svg>
            <h2>Failed to make payment</h2>
            
           
            <table class="table">
                <tbody>
                    <tr>
                        <td style="width:100px">Order ID</td>
                        <td style="text-align: right">{{$attempt->order_id}}</td>
                    </tr>
                    <tr>
                        <td>Amount</td>
                        <td style="text-align: right">{{number_format($attempt->amount, 2)}} {{strtoupper($attempt->currency)}}</td>
                    </tr>
                </tbody>
            </table>
            <br>
            <a href="{{$attempt['redirect_uri']}}" class="button">Back to Merchant</a>
            <br>
            <div class="secured-by-bottom">
                <span>Secured by </span>
                <img src="https://www.opn.ooo/assets/svg/logo-opn-full.svg" class="secured-by-logo" height="20">
            </div>
        </div>
    </div>
</body>

</html>
