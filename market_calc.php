<?php
require_once "libs/steemengine/SteemEngine.php";
use SnaddyvitchDispenser\SteemEngine\SteemEngine;

$_STEEM_ENGINE = new SteemEngine();
?>
<!DOCTYPE HTML>
<html>
<head lang="en">
    <?php include "additions/styles.php"; ?>
    <title>Steem Engine Market Viewer</title>
    <meta name="description" content="Calculate the price to buy various amounts tokens from Steem Engine. Also find out how much it will take to move the market." >
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="UTF-8">
    <meta name="author" content="@CADawg">
    <style>
        [id*=_wrapper].dataTables_wrapper {
            margin: 20px 0;
        }
    </style>
</head>

<body>
<?php include "navagation.php"; ?>

<div class="container" style="margin-top:30px">
    <h2><small>Steem Engine Market Calculator</small></h2>
    <p>Please Choose A Token by symbol to begin!</p>

    <form class="form-inline" style="justify-content: center;">
        <label for="token" class="mr-sm-2 mb-2">Symbol:</label>
        <input type="text" class="form-control mb-2 mr-sm-2" name="symbol" value="<?php echo isset($_GET["symbol"]) ? $_GET["symbol"]:""; ?>" id="token">
        <button type="submit" class="btn btn-primary mb-2">Submit</button>
    </form>

    <?php include "ad.php" ?>

    <?php
    /* Parameters */
    $symbol = isset($_GET["symbol"]) ? strtoupper($_GET["symbol"]) : "";

    /* Get Orders */
    $sells = $_STEEM_ENGINE->get_market_sells("", $symbol);
    $buys = $_STEEM_ENGINE->get_market_buys("", $symbol);

    if ($symbol != "") {
        ?>

        <div class="row mt-8 mb-8">
            <div class="col card">
                <form class="form" style="justify-content: center;">
                    <label for="amount" class="mr-sm-2">Selling</label>
                    <div class="input-group mb-3 mr-2">
                        <input type="text" id="amount_sell" class="form-control" placeholder="1.000" aria-label="Amount of token">
                        <div class="input-group-append">
                            <span class="input-group-text"><?php echo $symbol; ?></span>
                        </div>
                    </div>
                    <label for="amount_steem" class="mr-sm-2">Will Earn You</label>
                    <div class="input-group mb-3 mr-2">
                        <input type="text" disabled="disabled" id="amount_sell_steem" class="form-control" placeholder="0.250" aria-label="Amount of STEEM">
                        <div class="input-group-append">
                            <span class="input-group-text">STEEM</span>
                        </div>
                    </div>
                    <label for="amount_average" class="mr-sm-2">Average Price</label>
                    <div class="input-group mb-3 mr-2">
                        <input type="text" disabled="disabled" id="amount_sell_average" class="form-control" placeholder="0.250" aria-label="Average price in STEEM">
                        <div class="input-group-append">
                            <span class="input-group-text">STEEM</span>
                        </div>
                    </div>
                    <label for="buy_price" class="mr-sm-2">Steem Engine Sell Price*</label>
                    <div class="input-group mb-3 mr-2">
                        <input type="text" id="sell_price" class="form-control" placeholder="0.250" aria-label="Highest Buy Price">
                        <div class="input-group-append">
                            <span class="input-group-text">STEEM</span>
                        </div>
                    </div>
                </form>
                <p><strong>*</strong> Price you need to enter in Steem-Engine to do this in one go. Steem-Engine automatically selects best offer first, so this is the price of the cheapest order of all.</p>
            </div>

            <div class="col-1"></div>

            <div class="col card">
                <form class="form" style="justify-content: center;">
                    <label for="amount" class="mr-sm-2">Buying</label>
                    <div class="input-group mb-3 mr-2">
                        <input type="text" id="amount_buy" class="form-control" placeholder="1.000" aria-label="Amount of token">
                        <div class="input-group-append">
                            <span class="input-group-text"><?php echo $symbol; ?></span>
                        </div>
                    </div>
                    <label for="amount_steem" class="mr-sm-2">Will Cost</label>
                    <div class="input-group mb-3 mr-2">
                        <input type="text" disabled="disabled" id="amount_steem" class="form-control" placeholder="0.250" aria-label="Amount of STEEM">
                        <div class="input-group-append">
                            <span class="input-group-text">STEEM</span>
                        </div>
                    </div>
                    <label for="amount_average" class="mr-sm-2">Average Price</label>
                    <div class="input-group mb-3 mr-2">
                        <input type="text" disabled="disabled" id="amount_average" class="form-control" placeholder="0.250" aria-label="Average price in STEEM">
                        <div class="input-group-append">
                            <span class="input-group-text">STEEM</span>
                        </div>
                    </div>
                    <label for="buy_price" class="mr-sm-2">Steem Engine Buy Price*</label>
                    <div class="input-group mb-3 mr-2">
                        <input type="text" id="buy_price" class="form-control" placeholder="0.250" aria-label="Highest Buy Price">
                        <div class="input-group-append">
                            <span class="input-group-text">STEEM</span>
                        </div>
                    </div>
                </form>
                <p><strong>*</strong> Price you need to enter in Steem-Engine to do this in one go. Steem-Engine automatically selects cheapest first, so this is the price of the most expensive order of all.</p>
            </div>
        </div>

        <div class="row mt-4 mb-4">
            <div class="col card">
                <form class="form" style="justify-content: center;">
                    <label for="amount" class="mr-sm-2">To Lower The Buy Price To (Remove Buys Above Price)</label>
                    <div class="input-group mb-3 mr-2">
                        <input type="text" id="lower_target_price" class="form-control" placeholder="1.000" aria-label="Price Target">
                        <div class="input-group-append">
                            <span class="input-group-text">STEEM/<?php echo $symbol; ?></span>
                        </div>
                    </div>
                    <label for="amount_steem" class="mr-sm-2">Will Take</label>
                    <div class="input-group mb-3 mr-2">
                        <input type="text" disabled="disabled" id="lower_tokens_required" class="form-control" placeholder="1" aria-label="Amount of TOKENs">
                        <div class="input-group-append">
                            <span class="input-group-text"><?php echo $symbol; ?></span>
                        </div>
                    </div>
                    <label for="buy_price" class="mr-sm-2">Steem Engine Sell Price*</label>
                    <div class="input-group mb-3 mr-2">
                        <input type="text" id="lower_sell_price" class="form-control" placeholder="0.250" aria-label="Lowest Sell Price">
                        <div class="input-group-append">
                            <span class="input-group-text">STEEM</span>
                        </div>
                    </div>
                </form>
                <p><strong>*</strong> Price you need to sell quantity of tokens for in Steem-Engine to remove all orders above your set price in one go. Steem-Engine automatically selects best offer first, so this is the price of the cheapest order of all.</p>
            </div>

            <div class="col-1"></div>

            <div class="col card">
                <form class="form" style="justify-content: center;">
                    <label for="amount" class="mr-sm-2">To Raise The Sell Price To (Remove Sells Below Price)</label>
                    <div class="input-group mb-3 mr-2">
                        <input type="text" id="raise_target_price" class="form-control" placeholder="1.000" aria-label="Price Target">
                        <div class="input-group-append">
                            <span class="input-group-text">STEEM/<?php echo $symbol; ?></span>
                        </div>
                    </div>
                    <label for="amount_steem" class="mr-sm-2">Requires you to purchase</label>
                    <div class="input-group mb-3 mr-2">
                        <input type="text" disabled="disabled" id="raise_steem_required" class="form-control" placeholder="1" aria-label="Amount of STEEM">
                        <div class="input-group-append">
                            <span class="input-group-text"><?php echo $symbol; ?></span>
                        </div>
                    </div>
                    <label for="buy_price" class="mr-sm-2">Steem Engine Buy Price*</label>
                    <div class="input-group mb-3 mr-2">
                        <input type="text" id="raise_buy_price" class="form-control" placeholder="0.250" aria-label="Lowest Sell Price">
                        <div class="input-group-append">
                            <span class="input-group-text">STEEM</span>
                        </div>
                    </div>
                </form>
                <p><strong>*</strong> Price you need to buy quantity of tokens for in Steem-Engine to remove all orders below your set price in one go. Steem-Engine automatically selects best offer first, so this is the price of the most expensive order of all.</p>
            </div>
        </div>

        <script>
            function getSellPrice(amt) {
                var steemAmt = 0.0;
                var oldAmt = amt;
                for (var i = 0, len = window.buys.length; i < len; i++) {
                    if (amt <= window.buys[i][0]) {
                        steemAmt += (amt * window.buys[i][1]);
                        return [Number(steemAmt.toFixed(4)).toString(), (+window.buys[i][1]).toFixed(8).replace(/([0-9]+(\.[0-9]+[1-9])?)(\.?0+$)/,'$1'), Number((steemAmt/oldAmt).toFixed(4)).toString()];
                    } else {
                        steemAmt += (window.buys[i][0] * window.buys[i][1]);
                        console.log(steemAmt);
                        amt -= window.buys[i][0];
                    }
                }
                return "Not Enough orders to fill!";
            }

            function getBuyPrice(amt) {
                var steemAmt = 0.0;
                var oldAmt = amt;
                for (var i = 0, len = window.sells.length; i < len; i++) {
                    if (amt <= window.sells[i][0]) {
                        steemAmt += (amt * window.sells[i][1]);
                        return [Number(steemAmt.toFixed(4)).toString(), (+window.sells[i][1]).toFixed(8).replace(/([0-9]+(\.[0-9]+[1-9])?)(\.?0+$)/,'$1'), Number((steemAmt/oldAmt).toFixed(4)).toString()];
                    }
                    else {
                        steemAmt += (window.sells[i][0] * window.sells[i][1]);
                        amt -= window.sells[i][0];
                    }
                }
                return "Not Enough orders to fill!";
            }

            function targetBuyPrice(target) {
                var tokensNeeded = 0.0;
                var lowPrice = 0.0;
                for (var i = 0, len = window.buys.length; i < len; i++) {
                    if (window.buys[i][1] > target) {
                        tokensNeeded += window.buys[i][0];
                        lowPrice = window.buys[i][1];
                    } else {
                        return [tokensNeeded, lowPrice];
                    }
                }
                if (target != 0) {
                    return ["Target Too High!", "Target Too High!"];
                } else {
                    return [tokensNeeded, lowPrice];
                }
            }

            function targetSellPrice(target) {
                var steemNeeded = 0.0;
                var highPrice = 0.0;
                for (var i = 0, len = window.sells.length; i < len; i++) {
                    if (target > window.sells[i][1]) {
                        steemNeeded += window.sells[i][0];
                        highPrice = window.sells[i][1];
                    }
                    else {
                        return [steemNeeded, highPrice];
                    }
                }
                if (target < highPrice) {
                    return ["Target Too Low!", "Target Too Low!"];
                } else {
                    return [steemNeeded, highPrice];
                }
            }
        </script>

        <?php
    }

    if ($buys and $sells) {
        usort($buys, function($a, $b) {
            return $a->price > $b->price ? -1 : 1;
        });

        $buys_json = [];

        foreach ($buys as $market_buy) {
            $buys_json[] = [(float)$market_buy->quantity, (float)$market_buy->price];
        }

        usort($sells, function($a, $b) {
            return $a->price < $b->price ? -1 : 1;
        });

        $sells_json = [];

        foreach ($sells as $market_sell) {
            $sells_json[] = [(float)$market_sell->quantity, (float)$market_sell->price];
        }

        ?>


        <script>
            window.buys = <?php echo json_encode($buys_json); ?>;
            window.sells = <?php echo json_encode($sells_json); ?>;
        </script>

        <?php
    }
    ?>

</div>

<?php include "additions/scripts.php"; ?>
<script>
    $("#amount_sell").on("keyup",function(){
        var sellprice = getSellPrice($("#amount_sell").val());

        if (typeof(sellprice) ===  "string") {
            $("#amount_sell_steem").val(sellprice);
            $("#amount_sell_average").val(sellprice);
            $("#sell_price").val(sellprice);
        } else {
            $("#amount_sell_steem").val(sellprice[0]);
            if (isNaN(sellprice[2])) {
                $("#amount_sell_average").val(0);
            } else {
                $("#amount_sell_average").val(sellprice[2]);
            }
            $("#sell_price").val(sellprice[1]);
        }
    });

    $("#amount_buy").on("keyup",function(){
        var buyprice = getBuyPrice($("#amount_buy").val());

        if (typeof(buyprice) ===  "string") {
            $("#amount_steem").val(buyprice);
            $("#amount_average").val(buyprice);
            $("#buy_price").val(buyprice);
        } else {
            $("#amount_steem").val(buyprice[0]);
            if (isNaN(buyprice[2])) {
                $("#amount_average").val(0);
            } else {
                $("#amount_average").val(buyprice[2]);
            }
            $("#buy_price").val(buyprice[1]);
        }
    });

    $("#lower_target_price").on("keyup",function(){
        var lowerprice = targetBuyPrice($("#lower_target_price").val());
        $("#lower_tokens_required").val(lowerprice[0]);
        $("#amount_sell").val(lowerprice[0]);
        $("#amount_sell").trigger("keyup");
        $("#lower_sell_price").val(lowerprice[1]);
    });

    $("#raise_target_price").on("keyup",function(){
        var raiseprice = targetSellPrice($("#raise_target_price").val());
        $("#raise_steem_required").val(raiseprice[0]);
        $("#amount_buy").val(raiseprice[0]);
        $("#amount_buy").trigger("keyup");
        $("#raise_buy_price").val(raiseprice[1]);
    });
</script>
<?php include "ad_endofpage.php"; ?>
</body>
</html>