<?php
    $domyconn = true;
    require "../components/head.php";
    require "../components/navbar_intern.php";



    // Daten aus der Datenbank abrufen
    $id = $_GET["id"];
    $query = "SELECT * FROM users WHERE id = ?";
    $stmt = mysqli_prepare($myconn, $query);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if($result->num_rows == 0) {
        echo '<div class="mt-3">Keinen solchen Benutzer gefunden</div>';
        require "../components/footer.php";
        die();
    }
    $user = mysqli_fetch_assoc($result);
    
    // Validierung und Verarbeitung der Formulardaten
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $email = $_POST["email"];
        $edit = isset($_POST["edit"]) ? 1 : 0;
        $admin = isset($_POST["admin"]) ? 1 : 0;

        // Validierung der Eingaben
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            // SQL-Query in ein prepared statement umwandeln
            $id = $_GET["id"];
            $query = "UPDATE users SET email = ?, edit = ?, admin = ? WHERE id = ?";
            $stmt = mysqli_prepare($myconn, $query);
            mysqli_stmt_bind_param($stmt, "siii", $email, $edit, $admin, $id);
            mysqli_stmt_execute($stmt);
        } else {
            // Fehlerbehandlung, falls die Eingaben ungültig sind
        }
    }

    // Löschen des Accounts
    if (isset($_GET["delete"]) && isset($_GET["confirm"]) && $_GET["confirm"] == 1) {
        $id = $_GET["id"];
        $query = "DELETE FROM users WHERE id = ?";
        $stmt = mysqli_prepare($myconn, $query);
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        echo '<div class="modal modal-blur fade show" id="modal-success" tabindex="-1" role="dialog" style="display: block;" aria-modal="true"> <div class="modal-dialog modal-sm modal-dialog-centered" role="document"> <div class="modal-content"> <div class="modal-status bg-success"></div> <div class="modal-body text-center py-4"> <svg xmlns="http://www.w3.org/2000/svg" class="icon mb-2 text-green icon-lg" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0"></path><path d="M9 12l2 2l4 -4"></path></svg> <h3>Account gelöscht</h3> <div class="text-secondary">Dieser Account wurde endgültig gelöscht.</div> </div> <div class="modal-footer"> <div class="w-100"> <div class="row"> <div class="col"><a href="#" class="btn w-100" data-bs-dismiss="modal"> Zurück zur Benutzerübersicht </a></div> </div> </div> </div> </div> </div> </div>';  
    } 
    if (isset($_GET["delete"])) : ?>

        <div class="modal modal-blur fade show" id="modal-danger" tabindex="-1" role="dialog" style="display: block;"
            aria-modal="true">
            <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
                <div class="modal-content">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <div class="modal-status bg-danger"></div>
                    <div class="modal-body text-center py-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon mb-2 text-danger icon-lg" width="24" height="24"
                            viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                            <path d="M12 9v4"></path>
                            <path
                                d="M10.363 3.591l-8.106 13.534a1.914 1.914 0 0 0 1.636 2.871h16.214a1.914 1.914 0 0 0 1.636 -2.87l-8.106 -13.536a1.914 1.914 0 0 0 -3.274 0z">
                            </path>
                            <path d="M12 16h.01"></path>
                        </svg>
                        <h3>Sind Sie sicher?</h3>
                        <div class="text-secondary">Wollen Sie diesen Account wirklich löschen? Diese Aktion kann nicht rückgängig gemacht werden.
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="w-100">
                            <div class="row">
                                <div class="col">
                                    <a href="#" class="btn w-100" data-bs-dismiss="modal">
                                        Abbrechen
                                    </a>
                                </div>
                                <div class="col">
                                    <a href="user.php?id=<?=$_GET["id"]?>&delete=1&confirm=1" class="btn btn-danger w-100">
                                        Löschen
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif;

    
    ?>

    <!-- Formular zur Bearbeitung der Rechte und E-Mail-Adresse -->
    <div class="page-content">
        <div class="card mt-3">
            <div class="card-header">
                <h3 class="card-title">
                    Benutzerdaten bearbeiten
                </h3>
            </div>
            <div class="card-body">
                <form action="" method="post">
                    <div class="form-group mt-2">
                        <label for="email">E-Mail-Adresse</label>
                        <input type="text" class="form-control" id="email" name="email" value="<?= $user['email'] ?>">
                        <div class="valid-feedback">Valid.</div>
                        <div class="invalid-feedback">Bitte geben Sie eine gültige E-Mail-Adresse ein.</div>
                    </div>
                    <div class="form-check mt-2">
                        <input type="checkbox" class="form-check-input" id="edit" name="edit" value="1" <?= $user['edit'] ? "checked" : "" ?>>
                        <label class="form-check-label" for="edit">Edit-Rechte</label>
                    </div>
                    <div class="form-check mt-2">
                        <input type="checkbox" class="form-check-input" id="admin" name="admin" value="1" <?= $user['admin'] ? "checked" : "" ?>>
                        <label class="form-check-label" for="admin">Admin-Rechte</label>
                    </div>
                    <div class="row g-2 align-items-center">
                        <div class="col-6 col-sm-4 col-md-2 col-xl-auto py-3">
                            <button type="submit" class="btn btn-primary">Speichern</button>
                        </div>
                        <div class="col-6 col-sm-4 col-md-2 col-xl-auto py-3">
                            <a href="?id=<?= $id ?>&delete=1" class="btn btn-outline-danger">Account löschen</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
