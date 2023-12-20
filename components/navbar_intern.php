<?php
    require "../components/conn.php";
    if(session_status() !== PHP_SESSION_ACTIVE) {
        session_name("leberkasrechner_sessid");
        session_start();
    }
    // Check if user is logged in
    if($_SESSION["loggedin"] == true) {
      // Authentifikation erfolgreich; Nichts passiert
    } else {
      header("location: ./login.php");
    }
    $myconn = false;
    $canedit = $isadmin = null;
    # Datenbankverbindung aufbauen
    $env = parse_ini_file(__DIR__ . '/../.env');
    $myconn = new mysqli($env["DBSERVER"], $_SESSION["dbusername"], $_SESSION["dbpassword"], $env["DBNAME"], intval($env["DBPORT"]));
    if (!$myconn) {
        $myconn = false;
        die("Connection error");
    }
    $canedit = getValue("users", "username", $_SESSION["username"], "edit", false, $myconn);
    $canedit = getValue("users", "username", "admin", "edit", false, $myconn);
    $isadmin = getValue("users", "username", $_SESSION["username"], "admin", false, $myconn);
    
    $navitems = array(
        "/intern/" => "Home",
        "/intern/img_menu.php" => "Bilder",
        "/intern/users.php" => "Benutzerverwaltung",
    );


    // Dark & Light theme links
    $url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    $url_theme_dark = $url_theme_light = $url;
    $url = preg_replace('/([&?]theme=[^&]*)|([&?])/', '$2', $url);
    if (strpos($url, 'theme=light') === false) {
        $url_theme_light = $url . (parse_url($url, PHP_URL_QUERY) ? '&' : '?') . 'theme=light';
    } 
    if(strpos($url, 'theme=dark') === false) {
        $url_theme_dark = $url . (parse_url($url, PHP_URL_QUERY) ? '&' : '?') . 'theme=dark';
    }

?>
</div>
<nav class="navbar navbar-expand-md d-print-none">
  <div class="container-xl">
      <a class="navbar-brand" style="color:#800010;" href="/intern"> leberkasrechner.de <span style="font-weight: 400;">intern</span></a> 
        <a href=".">
          <h1 class="navbar-brand navbar-brand-autodark d-none-navbar-horizontal pe-0 pe-md-3">
        </a>
      </h1>
    
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarColor01" aria-controls="navbarColor01" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div id="navbar-menu" class="collapse navbar-collapse">
      <div class="d-flex flex-column flex-md-row flex-fill align-items-stretch align-items-md-center">
        <ul class="navbar-nav">
          <?php
            if(!isset($navbar_highlighted)) {$navbar_highlighted = " ";}
            foreach($navitems as $url => $label) {
              $isActive = ($navbar_highlighted === $label) ? 'active' : '';
              echo "<li class=\"nav-item $isActive\"><a class=\"nav-link $isActive\" href=$url>$label</a></li>";
            }
          ?>
        </ul>
      </div>
    </div>

    <?php // Hier wird der aktuell angemeldete Benutzer angezeigt und Optionen angezeigt ?>

    <div class="me-3">
        <a href="<?=$url_theme_dark?>" class="nav-link px-0 hide-theme-dark" title="Enable dark mode" data-bs-toggle="tooltip"
            data-bs-placement="bottom">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 3c.132 0 .263 0 .393 0a7.5 7.5 0 0 0 7.92 12.446a9 9 0 1 1 -8.313 -12.454z" /></svg>
        </a>
        <a href="<?=$url_theme_light?>" class="nav-link px-0 hide-theme-light" title="Enable light mode" data-bs-toggle="tooltip"
            data-bs-placement="bottom">
            <!-- Download SVG icon from http://tabler-icons.io/i/sun -->
            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 12m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0" /><path d="M3 12h1m8 -9v1m8 8h1m-9 8v1m-6.4 -15.4l.7 .7m12.1 -.7l-.7 .7m0 11.4l.7 .7m-12.1 -.7l-.7 .7" /></svg>
        </a>
    </div>
    <div class="nav-item dropdown">
      <a href="#" class="nav-link d-flex lh-1 text-reset p-0" data-bs-toggle="dropdown" aria-label="Benutzermenü öffnen" aria-expanded="false">
        <span class="avatar avatar-sm" style="background-image: url(/static/img/user.png)"></span>
        <div class="d-none d-xl-block ps-2">
          <div class="mt-1 small text-secondary">Angemeldet als</div>
          <div><?php echo $_SESSION["username"]; ?></div>
        </div>
      </a>
      <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
      <a href="/intern/profile.php" class="dropdown-item">Profil</a>
        <a href="logout.php" class="dropdown-item">
          <svg xmlns="http://www.w3.org/2000/svg" class="icon dropdown-item-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M14 8v-2a2 2 0 0 0 -2 -2h-7a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h7a2 2 0 0 0 2 -2v-2"></path><path d="M9 12h12l-3 -3"></path><path d="M18 15l3 -3"></path></svg>  
          Abmelden
        </a>
      </div>
    </div>

  </div>
</nav>
<div class="container-xl">