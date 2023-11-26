<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <?php if(isset($leaflet)) : ?>
        <!--leaflet-->
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
        <!--Leberkasmap.js-->
        <script src="/static/leaflet/leberkasmap.js"></script>

    <?php endif ?>
    <!--Tabler-->
    <link rel="stylesheet" href="/static/tabler/tabler.css"/>
    
    <title>Leberkasrechner</title>
</head>
<?php if(!isset($dobody)) {
    echo '
    <body class="layout-condensed">
        <div class="page">
            <div class="container-xl">
    ';
}