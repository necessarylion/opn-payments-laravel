<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Opn Payment | {{$config['title']}}</title>
    <style>
        :root {
            --themeColor : {{ $config['theme']['color'] }};
            --primaryTextColor : {{ $config['theme']['primaryTextColor'] }};
            --secondaryTextColor : {{ $config['theme']["secondaryTextColor"] }};
        }
    </style>
    <link href='https://cdn.omise.co/fonts/circular.css' rel='stylesheet'>
    <link href='/opn-payments/opn-style.css' rel='stylesheet'>
</head>
<body>
    <div class="container">
        <div class="main">
            <div class="left">
                <div class="left-box">
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
            </div>
            <div class="right">
                <div id="qr-container" class="qr-container">
                    
                </div>
                <div id="secured-by" class="secured-by">
                    <div class="secured-by-bottom">
                        <span>Secured by </span>
                        <img src="https://www.opn.ooo/assets/svg/logo-opn-full.svg" class="secured-by-logo" height="20">
                    </div>
                </div>
                <iframe name="frame" width="400" src="/{{$prefix}}/methods/{{$orderId}}" frameborder="0" >
                </iframe>
            </div>
        </div>
    </div>
</body>
<script>
    const orderId = "{{$orderId}}";
    const route = "/{{$prefix}}"
</script>
<script src="/opn-payments/opn-script.js"></script>
</html>
