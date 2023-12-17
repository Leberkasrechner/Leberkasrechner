<?php
$leaflet = true;
$lmap = true;
$navbar_highlighted = "Karte";
$fluid = true;
require __DIR__ . "/components/head.php";
require __DIR__ . "/components/navbar.php";
require __DIR__ . "/components/conn.php";
require __DIR__ . "/components/butcher.php";
?>

<style>
    #lmap {
        height: 750px !important;
        margin-bottom: 4rem !important;
        border-radius: 0.375rem !important;
        box-shadow: none !important;
    }
</style>
<div id="lmap"></div>

<?php
require __DIR__ . "/components/footer.php";