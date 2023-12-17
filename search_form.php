<?php
$navbar_highlighted = "Suche";
$page_title = "Suche";
require "components/head.php";
require "components/navbar.php";
?>
<div class="page-header d-print-none">
    <div class="row g-2 align-items-center">
        <div class="col">
            <h2 class="page-title">
                Erweiterte Metzgereisuche
            </h2>
        </div>
    </div>
</div>
<div class="page-body"> 
    <?php require "components/butcherSearchForm.php"; ?>
</div>

<?php 
require "components/footer.php";