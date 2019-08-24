<!DOCTYPE HTML>
<html>
<head lang="en">
    <?php include "additions/styles.php"; ?>
    <title>Steem Engine Delegations Viewer</title>
    <meta name="description" content="Easily view delegations of various Steem Engine tokens.">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="UTF-8">
    <meta name="author" content="@CADawg">
    <?php include "header.php"; ?>
</head>

<body>
<?php include "navagation.php"; ?>

    <div class="container" style="margin-top:30px">
        <h2><small>Welcome to STEEM Engine Tools.</small></h2>
        <p>These tools were created to help users with many tasks on Steem Engine</p>

        <h3><small>Made By <a href="https://steemit.com/@cadawg">@CADawg</a></small></h3>

        <?php include "ad.php" ?>

        <h3><small>Features:</small></h3>
        <ul>
            <li>Delegation Viewer</li>
            <ul>
                <li>View Percentage shares of Token Power</li>
                <li>Include your own power in the percentages</li>
                <li>Search on To, From and Symbol</li>
                <li>Charts showing token distibution</li>
                <li>Search The Table Easily</li>
            </ul>
            <li>Undelegation Viewer</li>
            <ul>
                <li>View User, Amount, Symbol and Time Till Returned</li>
                <li>Search on Account and Symbol</li>
                <li>Search function on any field in table.</li>
            </ul>
            <li>Market Viewer</li>
            <ul>
                <li>Shows ALL Open Orders</li>
                <li>Flags Known Bots Up</li>
            </ul>
            <li>Market Calculator</li>
            <ul>
                <li>Calculate Instant-Selling Prices</li>
                <li>Calculate Instant-Buying Prices</li>
                <li>Calculate How Many Sells would have to happen to lower the market to x</li>
                <li>Calculate how many buys it would take to raise the market to x</li>
            </ul>
            <li>Donate</li>
            <ul>
                <li>Please Give Me Money LOL</li>
            </ul>
        </ul>
    </div>

<?php include "additions/scripts.php"; ?>
<?php include "ad_endofpage.php"; ?>
</body>
</html>