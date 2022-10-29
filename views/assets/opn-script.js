var eventMethod = window.addEventListener ? "addEventListener" : "attachEvent";
var eventListener = window[eventMethod];
var messageEvent = eventMethod === "attachEvent" ? "onmessage" : "message";

let redirectUri;
let interval = null;
let totalTry = 0;

eventListener(messageEvent, async function (e) {
    if (e.origin !== location.origin) return;
    let data = e.data;
    if (data.message == "charge") {
        await doCharge(data.payload.token);
    }
});

async function doCharge(token) {
    const rawResponse = await fetch("/opn-payments/charge/" + orderId, {
        method: "POST",
        headers: {
            Accept: "application/json",
            "Content-Type": "application/json",
        },
        body: JSON.stringify({ token }),
    });
    const content = await rawResponse.json();
    if (content && content.redirect_uri) {
        redirectUri = content.redirect_uri;
    }
    if (content && content.qrcode) {
        const element = document.getElementById("qr-container");
        const securedBy = document.getElementById("secured-by");
        element.style.display = "flex";
        securedBy.style.display = "none";
        let html = `<img src="${content.qrcode}" width="300">`;
        html += `<a href="${content.qrcode}" donwload class="download-qr">Download QR</a>`;
        html += `<a href="${redirectUri}" class="complete-payment">Complete Payment</a>`;
        element.innerHTML = html;
        listenForChargeStatus();
    } else {
        window.location.href = redirectUri;
    }
}

function listenForChargeStatus() {
    interval = setInterval(async () => {
        await getChargeStatus();
    }, 5000);
}

async function getChargeStatus() {
    totalTry++;
    const rawResponse = await fetch("/opn-payments/status/" + orderId);
    const result = await rawResponse.json();
    console.log(result.status);
    if (result.status) {
        clearInterval(interval);
        window.location.href = redirectUri;
    }
    if (totalTry >= 25) {
        clearInterval(interval);
    }
}
