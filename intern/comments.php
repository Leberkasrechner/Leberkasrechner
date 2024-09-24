<?php
    $domyconn = true;
    $navbar_highlighted = "Kommentare verwalten";
    require "../components/head.php";
    require "../components/navbar_intern.php";

    # Seite zur Moderation von Kommentaren
    if(!$isadmin): ?>
        <div class="empty">
            <p class="empty-title">
                <svg xmlns="http://www.w3.org/2000/svg" class="text-red " width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M18 6l-12 12"></path><path d="M6 6l12 12"></path></svg>
                Nicht genügend Rechte
            </p>
            <p class="empty-subtitle text-secondary">
                Sie verfügen nicht über genügend Rechte, um diese Aktion auszuführen.
            </p>
        </div>
    <?php 
    require "../components/footer.php"; 
    die();
    else : 
        # SQL-Abfrage für unmoderierte Kommentare
        $sql = "SELECT c.id, c.author, c.comment, c.created_at, p.header 
                FROM blog_comments c 
                JOIN blog_posts p ON c.blog_post_id = p.id 
                WHERE c.is_approved = 0";
        $result = $myconn->query($sql);
    ?>

        <?php if($result->num_rows == 0): ?>
            <div class="empty">
                <p class="empty-title">
                    <svg xmlns="http://www.w3.org/2000/svg" class="text-red " width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M18 6l-12 12"></path><path d="M6 6l12 12"></path></svg>
                    Keine unmoderierten Kommentare
                </p>
                <p class="empty-subtitle text-secondary">
                    Es gibt derzeit keine Kommentare, die moderiert werden müssen.
                </p>
            </div>
            <?php 
            require "../components/footer.php"; 
            die();
        endif; ?>
        
        <!-- Tabelle mit Kommentaren -->
        <div class="page-content">
            <div class="card mt-3">
                <div class="card-header">
                    <h3 class="card-title">
                        Unmoderierte Kommentare
                    </h3>
                </div>
                <div class="card-body">
                    <?php
                        echo "<table class='table table-vcenter card-table'>";
                        echo "<thead><tr><th>Autor</th><th>Kommentar</th><th>Blogpost</th><th>Datum</th><th class='w-1'></th><th class='w-1'></th></tr></thead><tbody>";
                        
                        while($row = $result->fetch_assoc()) : ?>
                            <tr>
                                <td><?=$row["author"]?></td>
                                <td class='text-secondary'><?=$row["comment"]?></td>
                                <td class='text-secondary'><?=$row["header"]?></td>
                                <td class='text-secondary'><?= (new DateTime($row["created_at"]))->format("d.m.Y H:i") ?></td>
                                <td><a href="approve_comment.php?id=<?=$row["id"]?>" class="btn btn-success btn-sm">Annehmen</a></td>
                                <td><a href="delete_comment.php?id=<?=$row["id"]?>" class="btn btn-danger btn-sm">Ablehnen</a></td>
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
