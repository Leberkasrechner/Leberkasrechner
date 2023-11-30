<?php
$navbar_highlighted = "Suche";
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
    <form role="search" action="/search.php" method="get" autocomplete="off" novalidate="">
        <div class="input-icon">
            <span class="input-icon-addon">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0"></path><path d="M21 21l-6 -6"></path></svg>
            </span>
            <input type="text" name="q" value="<?=$searchBoxProp?>" class="form-control" placeholder="Metzgerei suchen..." aria-label="Metzgereien suchen">
        </div>
    </form>
</div>

<?php 
require "components/footer.php";