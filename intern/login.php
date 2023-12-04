<?php 
    $dobody = false;
    require "../components/head.php";
    require "../components/conn.php";
    $error = "";
    $emailErrorMsg = "";
    $passwordErrorMsg = "";
?>


<?php 
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $email = mysqli_real_escape_string($conn, $_POST["email"]);
        $passwordhash = mysqli_real_escape_string($conn, $_POST["password"]);
        $password = password_hash($password, PASSWORD_DEFAULT);

            if($email == ""){
                $emailErrorMsg = "Bitte E-Mail-Adresse angeben"; 
            }else if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
                $emailErrorMsg = "Bitte eine gültige E-Mail-Adresse angeben"; 
            }
            if($password == "" || !$password){
                $passwordErrorMsg = "Bitte Passwort eingeben";
        }

        if($emailErrorMsg == "" && $passwordErrorMsg == ""){
            $emailquery = "SELECT email, username, password FROM users WHERE email = ?";
            $emailstmt = $conn->prepare($emailquery);
            $emailstmt->bind_param("s", $email);
            $emailstmt->execute();
            $emailres = $emailstmt->get_result();
            $finduser = $emailres->fetch_assoc();
            if($finduser){
                // Benutzer existiert; Passwort eingeben
                $doesmatch = password_verify($passwordhash, $finduser["password"]);
                if($doesmatch) {
                    session_name("leberkasrechner_sessid");
                    session_start();
                    $_SESSION["email"] = $finduser["email"];
                    $_SESSION["username"] = $finduser["username"];
                    $_SESSION["loggedin"] = true;
                    header("location: ../intern?success=true");
                    exit();
                } else {
                    $error = "Passwort falsch. Bitte erneut versuchen.";
                }
            }else{
                $error = "Konnte Benutzer nicht finden - bitte versuchen Sie es erneut.";
            }
        }
    }
?>


<body class=" d-flex flex-column bg-white">
    <script src="./dist/js/demo-theme.min.js?1684106145"></script>
    <div class="row g-0 flex-fill">
      <div class="col-12 col-lg-6 col-xl-4 border-top-wide border-primary d-flex flex-column justify-content-center">
        <div class="container container-tight my-5 px-lg-5">
          <div class="text-center mb-4">
            <a href="." class="navbar-brand navbar-brand-autodark"><img src="./static/logo.svg" alt="" height="36"></a>
          </div>
          <h2 class="h3 text-center mb-3">
            Anmelden
          </h2>
            <form action="./login.php" method="POST" autocomplete="off">
                <?php // LOGOUT ERFOLGREICH; hier wieder anmelden
                if(isset($_GET["logout"]) && $_GET["logout"]==="1") {
                    echo '
                        <div class="alert alert-success alert-dismissible" role="alert"> 
                        <div class="d-flex">
                            <div> <svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"> <path stroke="none" d="M0 0h24v24H0z" fill="none"></path> <path d="M5 12l5 5l10 -10"></path> </svg> </div>
                            <div>
                                <h4 class="alert-title">Logout erfolgreich!</h4> 
                                <div class="text-muted">Sie können sich hier wieder anmelden.</div>
                            </div>
                            <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
                        </div></div>';
                } ?>

                <?php // REGISTRIERUNG ERFOLGREICH; hier anmelden
                if(isset($_GET["as"]) && $_GET["as"]==="1") {
                    echo '
                        <div class="alert alert-success alert-dismissible" role="alert"> 
                        <div class="d-flex">
                            <div> <svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"> <path stroke="none" d="M0 0h24v24H0z" fill="none"></path> <path d="M5 12l5 5l10 -10"></path> </svg> </div>
                            <div>
                                <h4 class="alert-title">Registrieren erfolgreich!</h4> 
                                <div class="text-muted">Sie können sich jetzt anmelden.</div>
                            </div>
                            <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
                        </div></div>';
                } ?>
                
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
                    <label class="form-label">E-Mail-Adresse</label>
                    <input type="email" name="email" class="form-control <?php if($emailErrorMsg != "")echo "is-invalid"?>" placeholder="max.mustermann@beispiel.de" autocomplete="off" tabindex="1">
                    <?php if($emailErrorMsg!==""){echo "<div class='invalid-feedback'>$emailErrorMsg</div>";}?>
                </div>
                <div class="mb-2">
                    <label class="form-label">
                        Passwort
                        <span class="form-label-description">
                            <a href="./forgot-password.html" tabindex="-1">Passwort vergessen?</a>
                        </span>
                    </label>
                <div class="input-group input-group-flat">
                    <input type="password" name="password" class="form-control<?php if($passwordErrorMsg != "")echo "is-invalid"?>" placeholder="Ihr Passwort" autocomplete="off" tabindex="2">
                    <?php if($passwordErrorMsg!==""){echo "<div class='invalid-feedback'>$passwordErrorMsg</div>";}?>
                </div>
            </div>
            <div class="form-footer">
                <button type="submit" class="btn btn-primary w-100" tabindex="3">Anmelden</button>
            </div>
    </form>
          <div class="text-center text-muted mt-3">
            Du hast noch kein Benutzerkonto? <a href="./register.php" tabindex="-1">Jetzt registrieren</a>
          </div>
        </div>
      </div>
      <div class="col-12 col-lg-6 col-xl-8 d-none d-lg-block">
        <!-- Photo -->
        <div class="bg-cover h-100 min-vh-100" style="background-image: url(/static/img/login_cover.jpg)"></div>
      </div>
    </div>
    <!-- Libs JS -->
    <!-- Tabler Core -->
    <script src="./dist/js/tabler.min.js?1684106145" defer=""></script>
    <script src="./dist/js/demo.min.js?1684106145" defer=""></script>
  
</body>

