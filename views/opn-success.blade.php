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
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#32a852">
                <path d="M0 0h24v24H0z" fill="none" />
                <path
                    d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z" />
            </svg>
            <h2>Payment Successful</h2>
            
           
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
            <p>Redirecting to merchant in <span id="counter">10</span></p>
            <a href="{{$attempt['redirect_uri']}}" class="button">Back to Merchant</a>
            <br>
            <div class="secured-by-bottom">
                <span>Secured by </span>
                <img src="https://www.opn.ooo/assets/svg/logo-opn-full.svg" class="secured-by-logo" height="20">
            </div>
        </div>
    </div>
</body>
<script>
    let timeToWait = 10
    let interval;

    window.addEventListener('load', (e) => {
       setTimeout(() => {
            window.location.href = "{{$attempt['redirect_uri']}}";
       }, timeToWait * 1000);

       interval = setInterval(() => {
        timeToWait--
        let ele = document.getElementById('counter')
        ele.innerHTML = timeToWait;
        if(timeToWait <= 0) {
            clearInterval(interval)
        }
       }, 1000);
    });
</script>
</html>
