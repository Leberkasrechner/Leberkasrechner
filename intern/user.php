<?php
    $domyconn = true;
    require "../components/head.php";
    require "../components/navbar_intern.php";



    $id = $_GET["id"];
    $query = "SELECT id, username, email, `edit`, `admin` FROM users WHERE id = ?";
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
    
    // Verarbeitung
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $email = $_POST["email"];
        $edit = isset($_POST["edit"]) ? 1 : 0;
        $admin = isset($_POST["admin"]) ? 1 : 0;

        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            # Update user database
            $id = $_GET["id"];
            $query = "UPDATE users SET email = ?, edit = ?, admin = ? WHERE id = ?";
            $stmt = mysqli_prepare($myconn, $query);
            mysqli_stmt_bind_param($stmt, "siii", $email, $edit, $admin, $id);
            mysqli_stmt_execute($stmt);
            # Update user privileges
            $dbusername = "'lusr_" . getValue("users", "id", $_GET["id"], "username", true, $myconn) . "'@'localhost'";
            $privsql = "REVOKE ALL PRIVILEGES ON *.* FROM $dbusername;";
            if($admin) {$privsql .= "   
                                GRANT CREATE USER ON *.* TO $dbusername; ALTER USER $dbusername ; 
                                GRANT SELECT, INSERT, UPDATE, DELETE ON leberkasrechner.butchers TO $dbusername;
                                GRANT SELECT, INSERT, UPDATE, DELETE ON leberkasrechner.image_butcher TO $dbusername;
                                GRANT SELECT, INSERT, UPDATE, DELETE ON leberkasrechner.image_butcher TO $dbusername;
                                GRANT SELECT, INSERT, UPDATE, DELETE ON leberkasrechner.image TO $dbusername;
                                GRANT SELECT, INSERT, UPDATE, DELETE ON leberkasrechner.license TO $dbusername;
                                GRANT SELECT (id, username, email, edit, admin),
                                      UPDATE (id, username, email, edit, admin) ON leberkasrechner.users TO $dbusername ;";
            } elseif($user) { $privsql .= "
                GRANT SELECT, INSERT, UPDATE, DELETE ON leberkasrechner.butchers TO $dbusername;
                GRANT SELECT, INSERT, UPDATE, DELETE ON leberkasrechner.image_butcher TO $dbusername;
                GRANT SELECT, INSERT, UPDATE, DELETE ON leberkasrechner.image_butcher TO $dbusername;
                GRANT SELECT, INSERT, UPDATE, DELETE ON leberkasrechner.image TO $dbusername;
                GRANT SELECT, INSERT, UPDATE, DELETE ON leberkasrechner.license TO $dbusername; ";
            }
            echo $privsql;
        } else {
        }
    }

    // Löschen des Accounts
    if (isset($_GET["delete"]) && isset($_GET["confirm"]) && $_GET["confirm"] == 1) {
        $id = $_GET["id"];
        $query = "DELETE FROM users WHERE id = ?";
        $stmt = mysqli_prepare($myconn, $query);
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        header("Location: user_delete_success.php");
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
    
