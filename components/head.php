<!DOCTYPE html>
<?php
    # Load theme
    session_name("leberkasrechner_sessid");
    session_start();
    if(isset($_GET["theme"]) && $_GET["theme"] == "dark") {$_SESSION["l_theme"] = "dark";}
    if(isset($_GET["theme"]) && $_GET["theme"] == "light") {$_SESSION["l_theme"] = "light";}
    if(isset($_SESSION["l_theme"]) && $_SESSION["l_theme"]=="dark") : ?>
<html lang="de" data-bs-theme="dark">
    <?php else : ?>
<html lang="de">
    <?php endif ?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <?php if(isset($leaflet)) : ?>
        <!--Leaflet-->
        <script  src="/node_modules/leaflet/dist/leaflet.js"></script>
        <link rel="stylesheet" href="/node_modules/leaflet/dist/leaflet.css"/>
        <!--leaflet.contextmenu-->
        <script src="/node_modules/leaflet-contextmenu/dist/leaflet.contextmenu.min.js"></script>
        <link rel="stylesheet" href="/node_modules/leaflet-contextmenu/dist/leaflet.contextmenu.min.css"/>
        <!--leaflet-fullscreen-->
        <script src="/node_modules/leaflet-fullscreen/dist/Leaflet.fullscreen.min.js"></script>
        <link rel="stylesheet" href="/node_modules/leaflet-fullscreen/dist/leaflet.fullscreen.css"/>
        <!--leaflet-search-->
        <script src="/node_modules/leaflet-search/dist/leaflet-search.min.js"></script>
        <link rel="stylesheet" href="/node_modules/leaflet-search/dist/leaflet-search.min.css"/>
        <!--leaflet-markers-canvas-->
        <link rel="stylesheet" href="/static/leberkas-chapta.css">
         <script src="/static/leberkas-chapta.js"></script>

        <script src="/node_modules/rbush/rbush.js"></script>
        <script src="/node_modules/leaflet-markers-canvas/dist/leaflet-markers-canvas.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>

    <?php endif ?>
    <?php if(isset($lmap)) : ?>
        <!--Leberkasmap.js-->
        <script src="/static/leberkasmap.js"></script>
    <?php endif ?>
    <?php if(isset($opening_hours)) : ?>
        <!--OSM opening_hours evaluation tool-->
        <script src="/node_modules/opening_hours/build/opening_hours.js"></script>
    <?php endif ?>
    <?php if(isset($tom_select)) : ?>
        <!--OSM opening_hours evaluation tool-->
        <script src="/node_modules/tom-select/dist/js/tom-select.complete.js"></script>
        <link rel="stylesheet" href="/node_modules/tom-select/dist/css/tom-select.css"/>
    <?php endif ?>
    
    <!--Tabler-->
    <link rel="stylesheet" href="/static/tabler/tabler.css"/>
    <script src="/static/tabler/tabler.min.js"></script>
    <!--Custom CSS-->
    <link rel="stylesheet" href="/static/custom.css"/>
    <!--Leberkas-chatpta-->
    <link rel="stylesheet" href="/static/leberkas-chapta.css">
    <!--BaguetteBox-->
    <script src="/node_modules/baguettebox.js/dist/baguetteBox.min.js"></script>
    <link rel="stylesheet" href="/node_modules/baguettebox.js/dist/baguetteBox.min.css"/>
    <!--Inter-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Dosis:wght@200;300;400;500;600;700;800&family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet"> 
    <style>
        :root {
        --tblr-font-sans-serif: 'Inter';
        }
    </style>
    <style>
        .homepage_headline{
            font-weight: bold;
            font-size: 6rem;
            font-weight: 800;
            padding-top: 60px;
            text-align: center;
        }
        .homepage_text{
         text-align: center;
         padding-top: 4vh;
         font-size: 1.5em;
        }
    </style>
    <?php if (isset($page_title)) : ?>
        <title><?=$page_title?> | Leberkasrechner</title>
    <?php else: ?>
        <title>Leberkasrechner</title>
    <?php endif ?>
</head>
<?php 
    $layout = "layout-condensed";
    $divlayout ="container-xl";
    if(isset($fluid)) {
        $layout = "layout-fluid";
        $divlayout = "";
    } 
?>
<?php if(isset($_SESSION["l_theme"]) && $_SESSION["l_theme"]=="dark") : ?>
<body class="<?=$layout?> theme-dark" onload="LeberkasChapta()">
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
<div class="page">
        <div class="<?=$divlayout?>">
<?php else : ?>
<body class="<?=$layout?> theme-light">
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

    <div class="page">
        <div class="<?=$divlayout?>">
<?php endif ?>