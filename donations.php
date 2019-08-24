<!DOCTYPE HTML>
<html>
<head lang="en">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <title>Steem Engine Delegations Viewer</title>
    <meta name="description" content="Donate To Steem Engine Tools.">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="UTF-8">
    <meta name="author" content="@CADawg">
    <?php include "header.php"; ?>
</head>

<body>
<?php include "navagation.php"; ?>

<div class="container" style="margin-top:30px">
    <h2><small>Donate</small></h2>
    <p>These tools are provided free of charge, although they are most certainly not free to run. If you'd like to support me, here's how you can:</p>
    <ul>
        <li>STEEM</li>
        <ul>
            <li>Upvote <a href="https://steemit.com/@cadawg">@cadawg</a></li>
            <li>Donate to <a href="https://steemit.com/@cadawg">@cadawg</a></li>
            <form class="form-inline">
                <label for="to" class="mr-sm-2 mb-2">Your Account:</label>
                <input type="text" class="form-control mb-2 mr-sm-2" name="account" id="account_token">
                <label for="from" class="mr-sm-2 mb-2">Amount:</label>
                <input type="tel" class="form-control mb-2 mr-sm-2" name="amount" id="amount_token">
                <label for="token" class="mr-sm-2 mb-2">Token:</label>
                <select class="form-control mb-2 mr-sm-2" id="currency_select">
                    <option value="STEEM">STEEM</option>
                    <option value="SBD">SBD</option>
                </select>
                <button type="submit" id="keychain_send" class="btn btn-primary mb-2">SEND</button>
            </form>
            <li>Send Steem-Engine Tokens To <a href="https://steemit.com/@cadawg">@cadawg</a></li>
            <form class="form-inline">
                <label for="to" class="mr-sm-2 mb-2">Your Account:</label>
                <input type="text" class="form-control mb-2 mr-sm-2" name="account" id="account_se_token">
                <label for="from" class="mr-sm-2 mb-2">Amount:</label>
                <input type="tel" class="form-control mb-2 mr-sm-2" name="amount" id="amount_se_token">
                <label for="token" class="mr-sm-2 mb-2">Token:</label>
                <input type="text" class="form-control mb-2 mr-sm-2" style="width: 7em;" placeholder="PAL" name="token" id="token_se_token">
                <button type="submit" id="keychain_send_token" class="btn btn-primary mb-2">SEND</button>
            </form>
        </ul>
        <li>Cryptocurrency:</li>
        <ul>
            <li>Send <strong>Bitcoin</strong> to <code><strong>3QyYGjpdG3S46igUQztK1HZvKAX88ovbbA</strong></code></li>
            <li>Send <strong>Bitcoin Cash</strong> to <code><strong>qzeyn5n6ee7zq9usj2yeg4e8mn7zcvznjc3rswyms8</strong></code></li>
            <li>Send <strong>Litecoin</strong> to <code><strong>M9EECoKaWQDBbF8ewxAjd5szBezn4xFZoc</strong></code></li>
            <li>Send <strong>Dogecoin</strong> to <code><strong>D8azPUdR69UMZnayGKM6W6RdfqvBhCaFaN</strong></code></li>
            <li>Send <strong>DASH</strong> to <code><strong>XmiVsrdkY65n9V6Xzft5xwJjU9Zf21aPsk</strong></code></li>
            <li>Send <strong>Etherium</strong> to <code><strong>0x96248d59156789620805462B8786C4EB5a873adB</strong></code></li>
            <li>Send <strong>Etherium Classic</strong> to <code><strong>0xDe0739F22cc190982Df202ee51ab029A51212433</strong></code></li>
            <li>Wait <strong>For Me</strong> to <code><strong>Get A Ledger 🤣</strong></code></li>
        </ul>
    </ul>

    <h4>Thanks!</h4>

</div>

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<script>
    $(document).ready(function() {
        $("#keychain_send").on("click", function (e) {
            e.preventDefault();
            e.stopPropagation();
            if (window.steem_keychain) {
                steem_keychain.requestTransfer($("#account_token").val(), "cadawg", Number($("#amount_token").val()).toFixed(3), "Donation from https://steem.tools/steemengine.", $("#currency_select").val(), function (response) {
                    if (response["success"]) {
                        alert("Thank you for donating!");
                    } else {
                        alert("Your donation failed =(")
                    }
                }, false);
            } else {
                var url = "https://beta.steemconnect.com/sign/transfer?from=" + $("#account_token").val() + "&to=cadawg&amount=" + Number($("#amount_token").val()).toFixed(3) + "%20" + $("#currency_select").val() + "&memo=Donation%20From%20Steem%20Engine%20Tools";
                var win = window.open(url, '_blank');
                win.focus();
                alert("Thank you for donating!");
            }
        });

        $("#keychain_send_token").on("click", function (e) {
            e.preventDefault();
            e.stopPropagation();
            if (window.steem_keychain) {
                steem_keychain.requestCustomJson($("#account_se_token").val().toLowerCase(), "ssc-mainnet1", "Active", JSON.stringify({"contractName":"tokens","contractAction":"transfer","contractPayload":{"symbol": $("#token_se_token").val().toUpperCase(),"to":"cadawg","quantity":$("#amount_se_token").val(),"memo":"Donation For Steem Engine Tools"}}), "Send Tokens", function(response) {
                    if (response["success"]) {
                        alert("Thank you for donating!");
                    } else {
                        alert("Your donation failed =(")
                    }
                }, false);
            } else {
                alert("SteemConnect is only supported for STEEM/SBD Donations, sorry! Install Keychain for automated donations!");
            }
        });
    });
</script>
</body>
</html>