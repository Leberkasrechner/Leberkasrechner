<?php 
    $dobody = false;
    require "../components/head.php";
    require "../components/conn.php";
    $error = "";
    $emailErrorMsg = "";
    $usernameErrorMsg = "";
    $passwordErrorMsg = "";
    $confirmPasswordErrorMsg = "";
?>


<?php
    $username = $email = $password = $confirmPassword = null;
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = mysqli_real_escape_string($conn, $_POST["username"]);
        $email = mysqli_real_escape_string($conn, $_POST["email"]);
        $password = mysqli_real_escape_string($conn, $_POST["password"]);
        $confirmPassword = mysqli_real_escape_string($conn, $_POST["confirm-password"]);
            if($username == "" || !isset($username)){
                $usernameErrorMsg = "Bitte geben Sie einen Benutzernamen ein";
            }
            if($username !== "" && strlen($username) < 4) {
                $usernameErrorMsg = "Ihr Benutzername muss aus mindestens vier Zeichen bestehen.";
            }
            if($email == ""){
                $emailErrorMsg = "Bitte geben Sie eine E-Mail-Adresse an."; 
            }else if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
                $emailErrorMsg = "Bitte geben Sie eine <b>gültige</b> E-Mail-Adresse an"; 
            }
            if($password == ""){
                $passwordErrorMsg = "Bitte geben Sie ein Passwort ein.";
            }
            if($confirmPassword == ""){
                $confirmPasswordErrorMsg = "Bitte bestätigen Sie Ihr Passwort.";
            }
            if($password !== "" && strlen($password) < 6){
                $passwordErrorMsg = "Bitte geben Sie ein Passwort ein, das mindestens 6 Zeichen lang ist.";
            }else if($password!=$confirmPassword){
                $confirmPasswordErrorMsg = "Passwort und Passwortbestätigung müssen gleich sein.";
            }

            # Für den Zugriff auf die Benutzerdatenbank ist ein eigenständiger Benutzeraccount zuständig. Hier in der Variable $grant_conn gespeichert.
            $env = parse_ini_file(__DIR__ . '/../.env');
            $grant_conn = new mysqli($env["DBSERVER"], $env["UC_DBUSER"], $env["UC_DBPASSWORD"], $env["DBNAME"], intval($env["DBPORT"]));

            // E-Mail-Adresse prüfen
            if(getEntity("users", "email", $email, true, $grant_conn)!==false) {$emailErrorMsg="E-Mail-Adresse bereits belegt";}
            
            // Benutzername prüfen
            if(getEntity("users", "username", $username, true, $grant_conn)!==false) {$emailErrorMsg="Benutzername bereits belegt";}
            
            if($error == "" && $usernameErrorMsg == "" && $emailErrorMsg == "" && $passwordErrorMsg == "" && $confirmPasswordErrorMsg == ""){
                $passwordhash = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $grant_conn->prepare("INSERT INTO users (username, email, password) VALUES(?, ?, ?)");
                $stmt->bind_param("sss", $username, $email, $passwordhash);
                $stmt->execute();

                $_SESSION['username'] = $username;
                $_SESSION['email'] = $email;

                $dbusername = "lusr_" . $username;
                $createUserSql = "CREATE USER '$dbusername'@'localhost' IDENTIFIED BY '$password'";
                if ($grant_conn->query($createUserSql) === TRUE) {
                    // Benutzer erfolgreich erstellt, jetzt Berechtigungen erteilen
                    // Hierfür gibt es einen eigenen Benutzer
                    $grantPermissionSql = "GRANT SELECT ON leberkasrechner.* TO '$dbusername'@'localhost'";
                    if ($grant_conn->query($grantPermissionSql) === TRUE) {
                        echo "Neuer Benutzer wurde erfolgreich erstellt und Berechtigungen erteilt";
                    } else {
                        echo "Fehler beim Erteilen von Berechtigungen: " . $grant_conn->error;
                    }
                } else {
                    echo "Fehler beim Erstellen des Benutzers: " . $grant_conn->error;
                }
                global $userconn;
                $env = parse_ini_file(__DIR__ . '/../.env');
                $userconn = new mysqli($env["DBSERVER"], $dbusername, $password, $env["DBNAME"], intval($env["DBPORT"]));
                $_SESSION["userconn"] = $userconn;
                
                #header("location: ./login.php?as=1");
            }
        }
?>


<body  class=" d-flex flex-column">
    <div class="page page-center">
      <div class="container container-tight py-4">
        <div class="text-center mb-4">
          <a href="." class="navbar-brand navbar-brand-autodark"><img src="./static/logo.svg" height="36" alt=""></a>
        </div>
        <form class="card card-md" action="./register.php" method="POST" autocomplete="off" novalidate>
          <div class="card-body">
            <h2 class="card-title text-center mb-4">Neuen Account erstellen</h2>

            <?php //UNGÜLTIGE ZUGANGSDATEN ($error)
                    if ($error !== "") {
                        echo '
                        <div class="alert alert-important alert-danger alert-dismissible" role="alert">
                            <div class="d-flex">
                                <div>
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"> <path stroke="none" d="M0 0h24v24H0z" fill="none"></path> <circle cx="12" cy="12" r="9"></circle> <line x1="12" y1="8" x2="12" y2="12"></line> <line x1="12" y1="16" x2="12.01" y2="16"></line> </svg>
                                </div>
                                <div>'. $error .'</div>
                            </div>
                            <a class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="close"></a>
                            </div>
                        ';
                    } ?>

            <div class="mb-3">
              <label class="form-label">Benutzername</label> <!--Benutzername-->
              <input type="text" name="username" class="form-control<?php if ($usernameErrorMsg !== "") echo " is-invalid" ?>" placeholder="Benutzername eingeben" value="<?php echo $username; ?>">
              <?php if ($usernameErrorMsg !== "") echo "<div class='invalid-feedback'>$usernameErrorMsg</div>" ?>
            </div>
            <div class="mb-3">
              <label class="form-label">E-Mail-Adresse</label>
              <input type="email" name="email" class="form-control<?php if ($emailErrorMsg !== "") echo " is-invalid" ?>" placeholder="E-Mail-Adresse eingeben" value="<?php echo $email; ?>">
              <?php if ($emailErrorMsg !== "") echo "<div class='invalid-feedback'>$emailErrorMsg</div>" ?>
            </div>
            <div class="mb-3">
              <!-- Passwort  -->
            <label class="form-label">Passwort</label>
                <input type="password" name="password" class="form-control<?php if ($passwordErrorMsg !== "") echo " is-invalid" ?>"  placeholder="Passwort"  autocomplete="off" >
              <?php if ($passwordErrorMsg !== "") echo "<div class='invalid-feedback'>$passwordErrorMsg</div>" ?>
            </div>
            <div class="mb-3">
              <?php //PASSWORT WIEDERHOLEN ?>
              <label class="form-label">Passwort wiederholen</label>
              <input type="password" name="confirm-password" class="form-control<?php if ($confirmPasswordErrorMsg !== "") echo " is-invalid" ?>"  placeholder="Passwort"  autocomplete="off">
              <?php if ($confirmPasswordErrorMsg !== "") echo "<div class='invalid-feedback'>$confirmPasswordErrorMsg</div>" ?>
            </div>
            <div class="mb-3">
              <div>Indem Sie auf "Neuen Account erstellen" klicken, akzeptieren Sie die <a href="/nutzungsbedingungen.php">Nutzungsbedingungen</a>.</div>
            </div>
            <div class="form-footer">
              <button type="submit" class="btn btn-primary w-100">Neuen Account erstellen</button>
            </div>
          </div>
        </form>
        <div class="text-center text-muted mt-3">
          Du hast schon einen Account? <a href="./login.php" tabindex="-1">Hier anmelden</a>
        </div>
      </div>
    </div>
  </body>