<?php
    $leaflet = true;
    $lmap = true;
    $navbar_highlighted = "Home";
    require "components/head.php";
    require "components/navbar.php";
?>

<h1 class="mt-3">Sie haben Leberkasnot? Wir helfen Ihnen!</h1>
<h2>Auf folgender Karte sehen Sie alle deutschen Metzgereien</h2>

<div id="meineKarte"></div>
<style>#meineKarte{height:500px;}</style>

<?php
require "components/footer.php";