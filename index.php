<?php
    $leaflet = true;
    $lmap = true;
    $navbar_highlighted = "Home";
    $customcss = true;
    require __DIR__ ."/components/head.php";
    require __DIR__ ."/components/navbar.php";
?>
<header class="hero">
    <div class="container">
        <h1 class="hero-title">
            Leberkasnot?
        </h1>
        <p class="hero-description mt-4">
            Finden Sie Ihren Fachhändler. Schnell. Einfach. Kostenlos.
        </p>
        <div class="row hero-actions justify-content-center">
            <div class="col-auto">
                <a href="#" class="btn hero-btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-search">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-search" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" /><path d="M21 21l-6 -6" /></svg>
                    Metzgerei suchen
                </a>
            </div>
            <div class="col-auto">
                <a href="contribute.php" class="btn hero-btn hero-btn-second">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-database-plus" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 6c0 1.657 3.582 3 8 3s8 -1.343 8 -3s-3.582 -3 -8 -3s-8 1.343 -8 3" /><path d="M4 6v6c0 1.657 3.582 3 8 3c1.075 0 2.1 -.08 3.037 -.224" /><path d="M20 12v-6" /><path d="M4 12v6c0 1.657 3.582 3 8 3c.166 0 .331 -.002 .495 -.006" /><path d="M16 19h6" /><path d="M19 16v6" /></svg>
                    Mitmachen
                </a>
            </div>
        </div>
    </div>
</header>
<div class="hr"></div>
<h1 class="hero-map-title">Oder doch lieber auf der Karte?</h1>
<div id="lmap"></div>
<div id="chapta-root-container" style="display: none;">
    <h1 class="chapta-headline">Kurze Sicherheitsüberprüfung</h1>
    <div id="description">
        Platzieren Sie die richtige Zutat auf der Leberkassemme (Ketchup oder Senf) um zu verifizieren, dass Sie kein Roboter sind.
    </div>
    <div class="root">
    <div class="chapta-container">
        <img class="chapta-img" id="livercheese" src="/static/livercheese.png" draggable="true">
        <img class="chapta-img" id="ketchup" src="/static/ketchup.jpg" draggable="true">
        <img class="chapta-img" id="senf" src="/static/mustard.jpg" draggable="true">
    </div>
</div>
</div>

<?php $modal = true;?>
<?php require __DIR__ . "/components/butcherSearchForm.php"; ?>

<?php
require "components/footer.php";