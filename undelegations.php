<?php
require "libs/time_string.php";
?>
<!DOCTYPE HTML>
<html>
<head lang="en">
    <?php include "additions/styles.php"; ?>
    <title>Steem Engine Delegations Viewer</title>
    <meta name="description" content="Easily view Undelegations of various Steem Engine tokens." >
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="UTF-8">
    <meta name="author" content="@CADawg">
    <?php include "header.php"; ?>
    <style>
        [id*=_wrapper].dataTables_wrapper {
            margin-bottom: 30px;
        }
    </style>
</head>

<body>
<?php include "navagation.php"; ?>

<div class="container" style="margin-top:30px">
    <h2><small>Welcome to the Steem Engine Undelegation Viewer.</small></h2>
    <p>This tool was created to enable steem users to identify when their undelegated tokens will be returned to them after delegating them out.</p>

    <h3><small>A few things to note:</small></h3>
    <p>There is a limit of 1000 records per query. Although, I don't believe this will be a probem for any queries at the moment. If you do need more than 1000 records, please contact me on Steem <a href="https://steemit.com/@cadawg">@cadawg</a> or on discord (<strong>cadawg#3984</strong>) and I'll see what I can do.</p>

    <?php include "ad.php" ?>

    <form class="form-inline" style="justify-content: center;">
        <label for="to" class="mr-sm-2 mb-2">Account:</label>
        <input type="text" class="form-control mb-2 mr-sm-2" name="account" value="<?php echo isset($_GET["account"]) ? $_GET["account"]:""; ?>" id="to">
        <label for="from" class="mr-sm-2 mb-2">Symbol:</label>
        <input type="text" class="form-control mb-2 mr-sm-2" name="symbol" value="<?php echo isset($_GET["symbol"]) ? $_GET["symbol"]:""; ?>" id="token">
        <button type="submit" class="btn btn-primary mb-2">Submit</button>
    </form>

    <?php
    /* Parameters */
    $account = isset($_GET["account"]) ? $_GET["account"] : "";
    $symbol = isset($_GET["symbol"]) ? $_GET["symbol"] : "";

    /* Get Delegations */
    require "undelegation_api.php";
    $contents = undelegation_api($account, $symbol);

    /* Decode */
    $json = json_decode($contents);
    if (isset($json[0]->result) and !isset($json->error)) {
        ?>
        <div class="row">
            <div id="undelegatingChart" class="col" style="height: 400px;"></div>
        </div>

        <table id="delegatorTable" class="table table-striped">
            <thead>
            <tr><th>Account</th><th>Amount</th><th>Symbol</th><th>Returned In</th></tr>
            </thead>
            <tbody>
            <?php
            $token_delegators = [];
            $total = 0.0;
            foreach ($json[0]->result as $delegation) {
                $total += (float)$delegation->quantity;
            }
            foreach ($json[0]->result as $delegation) {
                $returned_safe = isset($delegation->completeTimestamp) ? $delegation->completeTimestamp : "";

                $token_delegators[$delegation->txID] = [(float)$delegation->quantity, $delegation->symbol, $delegation->account];
                print("<tr><td>$delegation->account</td><td>" . (float)$delegation->quantity . "</td><td>" . $delegation->symbol . "</td><td data-order='$returned_safe'><abbr title='" . epoch_to_time($returned_safe, true, true) . "'>" . epoch_to_time($returned_safe,true,false) . "</abbr></td></tr>");
            }

            ?>
            </tbody>
        </table>

        <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
        <script>
            google.charts.load('current', {'packages':['corechart']});
            google.charts.setOnLoadCallback(drawChart);

            function drawChart() {

                var data = google.visualization.arrayToDataTable([
                    ['User', 'Tokens'],<?php

                    $amountt = array_column($token_delegators, 0);
                    array_multisort($amountt, SORT_DESC, SORT_NUMERIC, $token_delegators);
                    foreach ($token_delegators as $tokens) {
                        echo "['" . $tokens[2] . "', {v: $tokens[0], f: '$tokens[0] $tokens[1]'}],";
                    }
                    ?>]);

                var options = {
                    title: 'Undelegations by Tokens'
                };

                var chart = new google.visualization.PieChart(document.getElementById('undelegatingChart'));

                chart.draw(data, options);
            }

            window.addEventListener('resize', drawChart);
            window.addEventListener('orientationchange', drawChart);
        </script>
    <?php
    } else {
    ?>

        <table class="table table-striped">
            <thead>
            <tr><th>From</th><th>To</th><th>Amount</th><th>Symbol</th><th>Created*</th><th>Updated*</th></tr>
            </thead>
            <tbody>
            </tbody>
        </table>

        <?php
    } ?>
</div>

<?php include "additions/scripts.php"; ?>
<script>
    $(document).ready( function () {
        $('#delegatorTable').DataTable( {
            "order": [[ 3, "asc" ]]
        } );
    } );
</script>
<?php include "ad_endofpage.php"; ?>
</body>
</html>