<?php

$url = explode("/", $_SERVER["REQUEST_URI"]);

if (sizeof($url) > 3) {
    $start = str_repeat("../", sizeof($url) - 3);
} else {
    $start = "";
}

?>

<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-45168180-13"></script>
<script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'UA-45168180-13');
</script>

<style>
    .nav-link.text-white:hover {
        opacity: 0.7;
    }

    .nav-link:hover {
        opacity: 0.95;
    }
</style>

<nav class="navbar navbar-expand-sm bg-dark navbar-dark text-white">
    <a class="navbar-brand" href="index.php">Steem Engine Tools</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="collapsibleNavbar">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link text-white" href="<?php echo $start; ?>delegations.php">Delegation Viewer</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white" href="<?php echo $start; ?>undelegations.php">Undelegation Viewer</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white" href="<?php echo $start; ?>markets.php">Market Viewer</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white" href="<?php echo $start; ?>market_calc.php">Market Calculator</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-primary" href="<?php echo $start; ?>donations.php">Donate</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white" href="<?php echo $start; ?>privacy.php">Privacy</a>
            </li>
        </ul>
    </div>
</nav>