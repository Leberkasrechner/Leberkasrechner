<?php
    $leaflet = true;
    $lmap = true;
    $navbar_highlighted = "Home";
    require "components/head.php";
    require "components/navbar.php";
?>

<h1 class="homepage_headline">Leberkasnot?</h1>
<p class="homepage_text">Finden Sie Ihren Fachhändler. Schnell. Einfach. Kostenlos.</p>
<div id="meineKarte"></div>
<style>#meineKarte{height:500px; margin-bottom: 4rem; border-radius: 0.375rem;}</style>

<?php
require "components/footer.php";