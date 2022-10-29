<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Opn Payment</title>
    <link href='https://cdn.omise.co/fonts/circular.css' rel='stylesheet'>
<style>
    :root {
        --themeColor : {{ $config['theme']['color'] }};
        --primaryTextColor : {{ $config['theme']['primaryTextColor'] }};
        --secondaryTextColor : {{ $config['theme']["secondaryTextColor"] }};
    }
    body {
        margin: 0;
        background: #0909091a;
        font-family: Circular, Arial, sans-serif;
    }
    .container {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 100vh;
    }
    .main {
        display: flex;
        flex-direction: row;
        justify-content: center;
    }
    .left {
        width: 350px;
        background: var(--themeColor);
        border-radius: 5px 0px 0px 5px;
        padding: 30px 25px 20px 25px;
    }
    .right {
        border-radius: 0px 5px 5px 0px;
        background: #FFFFFF;
    }
    iframe {
        height: 600px;
        background: #FFFFFF;
        border-radius: 0px 5px 5px 0px;
    }
    .back-container {
        display: flex;
        flex-direction: row;
        align-items: center;
        margin-bottom: 15px;
    }
    .shop-info {
        display: flex;
        flex-direction: row;
        align-items: flex-start;
        justify-content: space-between;
    }
    .logo {
        margin-top: -42px;
    }
    .primary {
        color: var(--primaryTextColor);
        margin-bottom: 1px;
        margin-top: 5px;
        font-size: 22px;
        font-weight: bold;
    }
    .back {
        margin-right: 5px;
        height: 26px;
    }
    .back-text {
        color: var(--primaryTextColor);
        margin-bottom: 1px;
        margin-top: 0px;
    }
    .secondary {
        margin-top: 5px;
        color: var(--secondaryTextColor);
        font-weight: normal;
        font-size: 14px;
    }
</style>
</head>
<body>
    <div class="container">
        <div class="main">
            <div class="left">
                <div class="back-container">
                    <a href="{{$backUrl}}" class="back">
                        <svg class="back" xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24"
                            width="24px" fill="{{ $config['theme']['primaryTextColor'] }}">
                            <path d="M0 0h24v24H0z" fill="none" />
                            <path d="M20 11H7.83l5.59-5.59L12 4l-8 8 8 8 1.41-1.41L7.83 13H20v-2z" />
                        </svg>
                    </a>
                    <p class="back-text">Cancel</p>
                </div>
                <div class="shop-info">
                    <div>
                        <p class="primary">{{$config['title']}}</p>
                        <p class="secondary">{{$config['description']}}</p>
                    </div>
                    <img class="logo" src="{{$config['logo']}}" width="{{$config['logo_width']}}" alt="">
                </div>
            </div>
            <div class="right">
                <iframe width="400" src="/opn-payments/methods/{{$orderId}}" frameborder="0" >
                </iframe>
            </div>
        </div>
    </div>
</body>

<script>
    var eventMethod = window.addEventListener ? "addEventListener" : "attachEvent";
    var eventListener = window[eventMethod];
    var messageEvent = eventMethod === "attachEvent" ? "onmessage" : "message";
    eventListener(messageEvent, async function(e) {
        if (e.origin !== location.origin) return;
        let data = e.data
        if (data.message == 'charge') {
            await doCharge(data.payload.token);
        }
    });

    async function doCharge(token) {
        const orderId = '{{$orderId}}';
        const rawResponse = await fetch('/opn-payments/charge/'+ orderId, {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({token})
        });
        const content = await rawResponse.json();
        if(content && content.redirect_uri) {
            window.location.href = content.redirect_uri
        }
    }
</script>

</html>
