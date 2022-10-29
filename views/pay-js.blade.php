<!DOCTYPE html>
<html>
<head>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <title>Opn Payments</title>
    <link href='https://cdn.omise.co/fonts/circular.css' rel='stylesheet'>
</head>

<body>
    <script type='text/javascript' src='https://cdn.omise.co/omise.js'></script>
</body>
<script type='text/javascript'>
    const amount = '{{$amount}}';
    const currency = '{{$currency}}';
    const publicKey = '{{$publicKey}}';
    const otherPaymentMethods = {!! $paymentMethods !!};
    const backgroundColor = '{{$theme}}';
    const locale = '{{$locale}}';
    const style =  {!! $style !!};

    window.addEventListener('load', (e) => {
        e.preventDefault();
        initOmise();
        openOmiseJsFrame();
    });

    function openOmiseJsFrame() {
        OmiseCard.open({
            amount,
            currency,
            locale: locale,
            frameLabel: '',
            frameDescription: '',
            submitLabel: '',
            location: 'no'
        });
    }

    async function initOmise() {
        OmiseCard.configure({
            publicKey,
            submitAuto: 'no',
            image: '',
            otherPaymentMethods : otherPaymentMethods,
            style,
            onCreateTokenSuccess: function(token) {
                parent.postMessage({
                    message: 'charge',
                    payload: {
                        amount,
                        currency,
                        token
                    }
                }, '*');
                setTimeout(() => {
                    openOmiseJsFrame();
                }, 300);
            }
        });
    }
</script>

</html>
