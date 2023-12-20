<?php
    $domyconn = true;
    $navbar_highlighted = "Benutzerverwaltung";
    require "../components/head.php";
    require "../components/navbar_intern.php";


    # Seite zum Bearbeiten von Benutzerberechtigungen
    if(!$isadmin):?>
        <div class="empty">
            <p class="empty-title" >
                <svg xmlns="http://www.w3.org/2000/svg" class="text-red " width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M18 6l-12 12"></path><path d="M6 6l12 12"></path></svg>
                Nicht genügend Rechte
            </p>
            <p class="empty-subtitle text-secondary">
                Sie verfügen nicht über genügend Rechte, um diese Aktion auszuführen.
            </p>
        </div>
    <?php require "../components/footer.php"; die(); else : ?>

        <?php 
            $sql = "SELECT id, username, email, `edit`, admin FROM users";
            $result = $myconn->query($sql);?>
            <?php if($result->num_rows == 0): ?>
                <div class="empty">
                    <p class="empty-title" >
                        <svg xmlns="http://www.w3.org/2000/svg" class="text-red " width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M18 6l-12 12"></path><path d="M6 6l12 12"></path></svg>
                        Aktion fehlgeschlagen
                    </p>
                    <p class="empty-subtitle text-secondary">
                        Es wurden keine Benutzer gefunden
                    </p>
                </div>
                <?php require "../components/footer.php"; die();?>
            <?php endif; ?>
            <div class="page-content">
                <div class="card mt-3">
                    <div class="card-header">
                        <h3 class="card-title">
                            Benutzer-Übersicht
                        </h3>
                    </div>
                    <div class="card-body">
                        <?php
                            echo "<table class='table table-vcenter card-table'>";
                            echo "<thead><tr><th>ID</th><th>Username</th><th>Email</th><th>Rolle</th><th class='w-1'></th></tr></thead><tbody>";
                            while($row = $result->fetch_assoc()) : 
                                $role = null;
                                if ($row["edit"]) {$role = "Editor";}
                                if ($row["admin"]){$role = "Administrator";}
                                if (!$row["admin"] && !$row["edit"]) {$role = "Ohne Rechte";} ?>

                                <tr>
                                    <td><?=$row["id"]?></td>
                                    <td><?=$row["username"]?></td>
                                    <td class='text-secondary'><?=$row["email"]?></td>
                                    <td class='text-secondary'><?=$role?></td>
                                    <td><a href="user.php?id=<?=$row["id"]?>" class="">Bearbeiten</a></td>
                                </tr>
                            <?php endwhile;
                            echo "</tbody></table>";
                        ?>
                    </div>
                </div>
            </div>

    <?php endif ?>

<?php
require "../components/footer.php";