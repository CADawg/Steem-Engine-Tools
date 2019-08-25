<?php
require "libs/time_string.php";

$loki = '$loki';

require_once "libs/steemengine/SteemEngine.php";
use SnaddyvitchDispenser\SteemEngine\SteemEngine;

$_STEEM_ENGINE = new SteemEngine();
?>
<!DOCTYPE HTML>
<html>
    <head lang="en">
        <?php include "additions/styles.php"; ?>
        <title>Steem Engine Delegations Viewer</title>
        <meta name="description" content="Easily view delegations of various Steem Engine tokens." >
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
            <h2><small>Welcome to the Steem Engine Delegation Viewer.</small></h2>
            <p>This tool was created to enable steem users to identify who they are delegating to, as well as who delegates to them, you can then use this information to change/remove delegations.</p>

            <h3><small>A few things to note:</small></h3>
            <p>There is a limit of 1000 records per query. Although, I don't believe this will be a probem for any queries at the moment. If you do need more than 1000 records, please contact me on Steem <a href="https://steemit.com/@cadawg">@cadawg</a> or on discord (<strong>cadawg#3984</strong>) and I'll see what I can do.<br/>Updated and created dates may not always be available, because at the start these fields were not added. If it shows created but not updated, that means that it hasn't been changed yet!</p>

            <h3><small>Many More Features Planned</small></h3>
            <p>Stay tuned to see many more options being added.</p>

            <?php include "ad.php" ?>

            <form class="form-inline">
                <label for="to" class="mr-sm-2 mb-2">To:</label>
                <input type="text" class="form-control mb-2 mr-sm-2" name="to" value="<?php echo isset($_GET["to"]) ? $_GET["to"]:""; ?>" id="to">
                <label for="from" class="mr-sm-2 mb-2">From:</label>
                <input type="text" class="form-control mb-2 mr-sm-2" name="from" value="<?php echo isset($_GET["from"]) ? $_GET["from"]:""; ?>" id="from">
                <label for="token" class="mr-sm-2 mb-2">Symbol:</label>
                <input type="text" class="form-control mb-2 mr-sm-2" name="symbol" value="<?php echo isset($_GET["symbol"]) ? $_GET["symbol"]:""; ?>" id="token">
                <div class="form-check mb-2 mr-sm-2">
                    <label class="form-check-label disabled">
                        <input name="ownsp" class="form-check-input" type="checkbox" <?php echo isset($_GET["ownsp"]) ? "checked=\"checked\"" : ""; ?>> Include To's own Stake
                    </label>
                </div>
                <button type="submit" class="btn btn-primary mb-2">Submit</button>
            </form>

            <?php
            /* Parameters */
            $to = isset($_GET["to"]) ? strtolower($_GET["to"]) : "";
            $own_sp = (isset($_GET["to"]) and isset($_GET["ownsp"]));
            $from = isset($_GET["from"]) ? strtolower($_GET["from"]) : "";
            $symbol = isset($_GET["symbol"]) ? $_GET["symbol"] : "";

            $query = ["to" => $to, "from" => $from, "symbol" =>$symbol];

            $delegations = $_STEEM_ENGINE->get_delegations($query);

            if ($own_sp and strlen($to) > 0 and strlen($symbol) > 0) {
                $balance = $_STEEM_ENGINE->get_user_balance_one($to, $symbol);
                if (isset($balance[0]->stake)) {
                    $self_power = $balance[0]->stake;
                } else {
                    $self_power = false;
                }
            } else {
                $self_power = false;
            }

            if ($delegations !== false) {
                ?>
                <div class="row">
                    <?php
                        $show = false;
                        if (!isset($_GET["to"]) and !isset($_GET["from"])) {
                            $show = true;
                        } if (isset($_GET["to"]) and $_GET["to"] == "" and isset($_GET["from"]) and $_GET["from"] == "") {
                            $show = true;
                        }
                    ?>
                    <?php if ((isset($_GET["to"]) and $_GET["to"] != "") or $show ) { ?><div id="delegatorsChart" class="col" style="height: 400px;"></div> <?php } ?>
                    <?php if ((isset($_GET["from"]) and $_GET["from"] != "") or $show ) { ?><div id="delegateesChart" class="col" style="height: 400px;"></div> <?php } ?>
                </div>

                <table id="delegatorTable" class="table table-striped table-bordered">
                    <thead>
                        <tr><th>From</th><th>To</th><th>Amount</th><th>Symbol</th><th>Created*</th><th>Updated*</th><th>Share</th></tr>
                    </thead>
                    <tbody>
                <?php
                $token_delegators = [];
                $total = 0.0;

                foreach ($delegations as $delegation) {
                    $total += (float)$delegation->quantity;
                }

                if ($self_power !== false) {
                    $total += $self_power;
                    print("<tr><td><strong>OWN POWER</strong></td><td>$to</td><td>" . (float)$self_power . "</td><td>" . strtoupper($symbol) . "</td><td data-order='1000000000000000'></td><td data-order='1000000000000000'></td><td>" . (string)round(((float)$self_power/(float)$total)*100,2) . "%</td></tr>");
                    $token_delegators["OWN POWER,own_power"] = [(float)$self_power, $symbol];
                }

                foreach ($delegations as $delegation) {
                    $updated_safe = isset($delegation->updated) ? $delegation->updated : "";
                    $created_safe = isset($delegation->created) ? $delegation->created : "";
                    if ((int)$updated_safe === (int)$created_safe) {
                        $updated = "";
                    } else {
                        $updated = epoch_to_time($delegation->updated);
                    }

                    if (!isset($delegation->created)) {
                        $delegation->created = "";
                    }

                    $token_delegators[$delegation->from . "," . $delegation->symbol . "," . $delegation->$loki] = [(float)$delegation->quantity, $delegation->symbol, $delegation->to];
                    print("<tr><td>$delegation->from</td><td>$delegation->to</td><td>" . (float)$delegation->quantity . "</td><td>$delegation->symbol</td><td data-order='$created_safe'><abbr title='" . epoch_to_time($created_safe, true, true) . "'>" . epoch_to_time($created_safe) . "</abbr></td><td data-order='$updated_safe'><abbr title='" . epoch_to_time($updated_safe, true, true) . "'>$updated</abbr></td><td>" . (string)round(((float)$delegation->quantity/(float)$total)*100,2) . "%</td></tr>");
                }

                ?>
                    </tbody>
                    <tfoot>
                    <tr><th>From</th><th>To</th><th>Amount</th><th>Symbol</th><th>Created*</th><th>Updated*</th><th>Share</th></tr>
                    </tfoot>
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
                          foreach ($token_delegators as $name=>$tokens) {
                              echo "['" . explode(",", $name)[0] . "', {v: $tokens[0], f: '$tokens[0] $tokens[1]'}],";
                          }
                          ?>]);

                        var options = {
                          title: 'Delegators by Tokens'
                        };

                        var chart = new google.visualization.PieChart(document.getElementById('delegatorsChart'));

                        chart.draw(data, options);
                      }

                      window.addEventListener('resize', drawChart);
                      window.addEventListener('orientationchange', drawChart);
                </script>
                <script>
                    google.charts.load('current', {'packages':['corechart']});
                    google.charts.setOnLoadCallback(drawChartDelegatees);

                    function drawChartDelegatees() {

                        var data = google.visualization.arrayToDataTable([
                            ['User', 'Tokens'],<?php

                            $amountt = array_column($token_delegators, 0);
                            array_multisort($amountt, SORT_DESC, SORT_NUMERIC, $token_delegators);
                            foreach ($token_delegators as $name=>$tokens) {
                                echo "['" . $tokens[2] . "', {v: $tokens[0], f: '$tokens[0] $tokens[1]'}],";
                            }
                            ?>]);

                        var options = {
                            title: 'Delegatees by Tokens'
                        };

                        var chart = new google.visualization.PieChart(document.getElementById('delegateesChart'));

                        chart.draw(data, options);
                    }

                    window.addEventListener('resize', drawChartDelegatees);
                    window.addEventListener('orientationchange', drawChartDelegatees);
                </script>
            <?php
            } else {
                ?>

                <table id="delegatorTable" class="table table-striped">
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
                    "order": [[ 4, "desc" ]]
                } );
            } );
        </script>
        <?php include "ad_endofpage.php"; ?>
    </body>
</html>