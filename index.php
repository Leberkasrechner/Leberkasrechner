<?php
    $leaflet = true;
    $lmap = true;
    $navbar_highlighted = "Home";
    $customcss = true;
    require "components/head.php";
    require "components/navbar.php";
?>

<header class="hero">
    <div class="container">
        <h1 class="hero-title">
            Leberkasnot?
        </h1>
        <p class="hero-description mt-4">
            Finden Sie Ihren Fachhändler. Schnell. Einfach. Kostenlos.
        </p>
    </div>
</header>

<div id="meineKarte"></div>
<style></style>

<?php
require "components/footer.php";