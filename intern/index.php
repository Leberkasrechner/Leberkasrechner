<?php 
    $navbar_highlighted = "Home";
    require "../components/head.php";
    require "../components/navbar_intern.php";
    

?>

<?php if(isset($_GET["success"])) : ?>
    <div class="alert alert-success alert-dismissible mt-3" role="alert">
        <div class="d-flex">
            <div>
            <svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M5 12l5 5l10 -10"></path></svg>
        </div>
        <div>
            Die Anmeldung war erfolgreich!
        </div>
    </div>
    <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
    </div>
<?php endif ?>
<h1 class="mt-3">Willkommen im internen Bereich!</h1>
<span class="mb-3">Hier können Sie die Datenbank hinter www.anrufschranke.de bearbeiten.</span>




<?php require "../components/footer.php" ?>