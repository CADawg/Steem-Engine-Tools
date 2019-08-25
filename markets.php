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
        <meta name="description" content="Easily view and calculate the price to buy various amounts of tokens from Steem Engine." >
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta charset="UTF-8">
        <meta name="author" content="@CADawg">
        <?php include "header.php"; ?>
    </head>

    <body>
        <?php include "navagation.php"; ?>

        <div class="container" style="margin-top:30px">
            <h2><small>Steem Engine Market</small></h2>
            <p>Looking for functions that were here? See <a href="market_calc.php">Market Calculator</a></p>

            <?php include "ad.php" ?>

            <form class="form-inline" style="justify-content: center;">
                <label for="to" class="mr-sm-2 mb-2">Account:</label>
                <input type="text" class="form-control mb-2 mr-sm-2" name="to" value="<?php echo isset($_GET["to"]) ? $_GET["to"]:""; ?>" id="to">
                <label for="token" class="mr-sm-2 mb-2">Symbol:</label>
                <input type="text" class="form-control mb-2 mr-sm-2" name="symbol" value="<?php echo isset($_GET["symbol"]) ? $_GET["symbol"]:""; ?>" id="token">
                <button type="submit" class="btn btn-primary mb-2">Submit</button>
            </form>

            <?php
            /* Parameters */
            $to = isset($_GET["to"]) ? strtolower($_GET["to"]) : "";
            $symbol = isset($_GET["symbol"]) ? strtoupper($_GET["symbol"]) : "";

            $_KNOWN_BOTS = ["ifoundthissong", "mancer-sm-alt"];

            /* Get Orders */
            $sells = $_STEEM_ENGINE->get_market_sells($to, $symbol);
            $buys = $_STEEM_ENGINE->get_market_buys($to, $symbol);

            if ($sells and $buys) {
            ?>

            <div class="row">

                <div class="col">
                    <h4>Market Buys</h4>
                    <table id="buysTable" class="table table-striped">
                        <thead>
                        <tr>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Token</th>
                            <th>Account</th>
                            <th>STEEM</th>
                            <th>Total STEEM</th>
                        </tr>
                        </thead>

                        <tbody>
                        <?php
                        $total = 0.0;

                        /**
                         * @var array $buys Object of buy orders
                         */
                        usort($buys, function($a, $b) {
                            return $a->price > $b->price ? -1 : 1;
                        });

                        foreach ($buys as $market_buy) {
                            $total += (float)$market_buy->tokensLocked;
                            $current_price = rtrim(sprintf("%.8f",(float)$market_buy->price), "0");
                            $bot = "";
                            if (in_array($market_buy->account, $_KNOWN_BOTS)) {
                                $bot = " <strong class='text-danger'>[BOT]</strong>";
                            }
                            print("<tr><td><strong>$current_price</strong></td><td>$market_buy->quantity</td><td>$market_buy->symbol</td><td>$market_buy->account$bot</td><td>$market_buy->tokensLocked</td><td>$total</td></tr>");
                        }

                        ?>
                        </tbody>

                    </table>

                </div>

                <div class="col">
                    <h4>Market Sells</h4>

                    <table id="sellsTable" class="table table-striped">
                        <thead>
                        <tr>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Token</th>
                            <th>Account</th>
                            <th>STEEM</th>
                            <th>Total STEEM</th>
                        </tr>
                        </thead>

                        <tbody>
                        <?php
                        $total = 0.0;

                        /**
                         * @var array $sells Object of sell orders
                         */
                        usort($sells, function($a, $b) {
                            return $a->price < $b->price ? -1 : 1;
                        });

                        foreach ($sells as $market_sell) {
                            $amt = round($market_sell->quantity * $market_sell->price, 8);
                            $current_price = rtrim(sprintf("%.8f",(float)$market_sell->price),"0");
                            $total += (float)$amt;
                            $bot = "";
                            if (in_array($market_sell->account, $_KNOWN_BOTS)) {
                                $bot = " <strong class='text-danger'>[BOT]</strong>";
                            }
                            print("<tr><td><strong>$current_price</strong></td><td>" . (float)$market_sell->quantity . "</td><td>$market_sell->symbol</td><td>$market_sell->account$bot</td><td>$amt</td><td>$total</td></tr>");
                        }

                        ?>
                        </tbody>

                    </table>

                </div>

            </div>

                <?php
            }
            ?>

        </div>

        <?php include "additions/scripts.php"; ?>
        <script>
            $(document).ready( function () {
                $('#sellsTable').DataTable( {
                    "order": [[ 5, "asc" ]]
                } );
                $('#buysTable').DataTable( {
                    "order": [[ 5, "asc" ]]
                } );
            } );
        </script>
    <script>
        $("#amount_sell").on("keyup",function(){
            var sellprice = getSellPrice($("#amount_sell").val());

            if (typeof(sellprice) ===  "string") {
                $("#amount_sell_steem").val(sellprice);
                $("#amount_sell_average").val(sellprice);
                $("#sell_price").val(sellprice);
            } else {
                $("#amount_sell_steem").val(sellprice[0]);
                $("#amount_sell_average").val(sellprice[2]);
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
                $("#amount_average").val(buyprice[2]);
                $("#buy_price").val(buyprice[1]);
            }
        });

        $("#lower_target_price").on("keyup",function(){
            var lowerprice = targetBuyPrice($("#lower_target_price").val());
            $("#lower_tokens_required").val(lowerprice[0]);
            $("#lower_sell_price").val(lowerprice[1]);
        });

        $("#raise_target_price").on("keyup",function(){
            var raiseprice = targetSellPrice($("#raise_target_price").val());
            $("#raise_steem_required").val(raiseprice[0]);
            $("#raise_buy_price").val(raiseprice[1]);
        });
    </script>
        <?php include "ad_endofpage.php"; ?>
    </body>
</html>