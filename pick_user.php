<?php
if (isset($_GET["username"])) {
    if (substr($_GET["username"], 0, 1) == "@") {
        $username = substr($_GET["username"],1);
    } else {
        $username = $_GET["username"];
    }

    header("Location: ./user/@$username");
}

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
    <title>Steem Engine User Finder</title>
    <meta name="description" content="Search for a user." >
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="UTF-8">
    <meta name="author" content="@CADawg">
    <?php include "header.php"; ?>
</head>

<body>
<?php include "navagation.php"; ?>

<div class="container" style="margin-top:30px">
    <form action="pick_user.php">
        <label for="username" class="mr-sm-2 mb-2">Username (Without @):</label>
        <input type="text" class="form-control mb-2 mr-sm-2" name="username" id="username">
        <button type="submit" class="btn btn-primary mb-2">Submit</button>
    </form>
</div>

<?php include "additions/scripts.php"; ?>
<?php include "ad_endofpage.php"; ?>
</body>
</html>