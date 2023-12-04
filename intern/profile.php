<?php 
    $domyconn = true;
    require "../components/head.php";
    require "../components/navbar_intern.php";
?>
<h1 class="mt-3"><?php echo $_SESSION["username"]; ?></h1>
<p class="mt-3">Hier wird es in Kürze möglich sein, Aktionen mit Ihrem Profil auszuführen, das Profil zu löschen.</span>
<h2>Ihre Berechtigungen</h1>
<?php 
    
    
    if($canedit) {echo "Sie sind <b>berechtigt,</b> Seiten zu bearbeiten.";}
    else {echo "Sie sind <b>nicht berechtigt,</b> Seiten zu bearbeiten.";}
    if($isadmin) {echo "<br>Sie sind <b>Systemadministrator.</b>";}
    else {echo "<br>Sie sind <b>kein Systemadministrator.</b>";}


    /*$edit = mysqli_fetch_assoc($res)["edit"];
    $admin = mysqli_fetch_assoc($res)["admin"];
    echo $admin;
    if($edit) {echo "Sie sind <b>berechtigt,</b> Seiten zu bearbeiten.";}
    else {echo "Sie sind <b>nicht berechtigt,</b> Seiten zu bearbeiten.";}
    if($admin) {echo "<br>Sie sind <b>Admin.</b>";}
    else {echo "<br>Sie sind <b>kein Admin.</b>";}'*/
?>