<?php
    $navbar_highlighted = "Bilder";
    $page_title = "Bilder verwalten";
    require "../components/head.php";
    require "../components/navbar_intern.php";
?>

<div class="card mt-3">
    <div class="card-header">
        <h3 class="card-title">Bildaktionen</h3>
    </div>
    <div class="list-group list-group-flush">
        <a href="img_upload.php" class="list-group-item list-group-item-action">Neues Bild hochladen</a>
        <a href="img_edit.php" class="list-group-item list-group-item-action">Bild bearbeiten oder löschen</a>
        <a href="img_connect.php" class="list-group-item list-group-item-action">Bild zu Einträgen hinzufügen/von Einträgen entfernen</a>
    </div>
</div>

<?php
    require "../components/footer.php";