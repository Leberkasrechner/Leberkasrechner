<!DOCTYPE html>
<html lang="de">
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
        <link rel="stylesheet" href="/node_modules/tom-select/dist/css/tom-select.bootstrap5.min.css"/>
    <?php endif ?>
    
    <!--Tabler-->
    <link rel="stylesheet" href="/static/tabler/tabler.css"/>
    <script src="/static/tabler/tabler.min.js"></script>
    <!--Custom CSS-->
    <link rel="stylesheet" href="/static/custom.css"/>
    <!--Inter-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
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
<?php if(!isset($dobody)) {
    echo '
<body class="layout-condensed">
    <div class="page">
        <div class="container-xl">
    ';
}
