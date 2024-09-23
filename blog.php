<?php
$navbar_highlighted = "Blog";
$page_title = "Blog";
require __DIR__ . "/components/conn.php";
require __DIR__ . "/components/head.php";
require __DIR__ . "/components/navbar.php";
require __DIR__ . "/vendor/autoload.php";
$parsedown = new Parsedown();
if(!empty($_GET["post"])) {
    $postid = $_GET["post"];
}
$posts = null;
$pd = null;
?>

<?php if(empty($postid)) : ?>
    <?php 
        # Load all blogposts
        global $conn;
        $posts = $conn->query("SELECT * FROM blog_posts;");
        while($postdata = $posts->fetch_assoc()) {
            $postArray[] = $postdata;
        }
        for($i = count($postArray) - 1; $i >= 0; $i--) : 
            $postdata = $postArray[$i];
        ?>
            <?php 
                $created = "Blogpost vom " . (new DateTime($postdata["created"]))->format("d.m.Y");
                $content = explode("\n", $postdata["content"])[0]
            ?>
            <div class="row justify-content-center">
                <div class="col lg-10 col-xl-9">
                    <div class="card mb-3">
                        <div class="card-header">
                            <h3 class="card-title"><?=$postdata["header"]?>
                                <span class="card-subtitle"><i><?=$created?></i></span>
                            </h3>
                        </div>
                        <div class="card-body">
                            <?=$parsedown->text($content)?>
                        </div>
                        <div class="card-footer">
                            <a class="btn" href="/blog.php?post=<?=$postdata["id"]?>">
                                Mehr lesen...
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endfor ?>

<?php else : ?>
    
    <?php 
        ## BESTIMMTER BLOGPOST SOLL ANGEZEIGT WERDEN
        global $conn;
        $stmt = $conn->prepare("SELECT * FROM blog_posts WHERE id = ?");
        $stmt->bind_param("i", $_GET["post"]);
        $stmt->execute();
        $res = $stmt->get_result();
        $pd = $res->fetch_assoc();
        $created = "Blogpost vom " . (new DateTime($pd["created"]))->format("d.m.Y");
        if($res->num_rows == 0 ) :
            # WENN BLOGPOST NICHT GEFUNDEN
    ?>
    
    <div class='empty'>
        <div class='empty-img'><img src='static/svg/no_results.svg' height='128' alt=''>
        </div>
        <p class='empty-title'>Blogpost nicht gefunden</p>
        <div class='empty-action'>
            <a href='/blog.php' class='btn btn-primary'>
                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-list-details" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M13 5h8" /><path d="M13 9h5" /><path d="M13 15h8" /><path d="M13 19h5" /><path d="M3 4m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v4a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z" /><path d="M3 14m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v4a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z" /></svg>    
                Blog-Übersicht
            </a>
        </div>
    </div>
    <?php 
        require "components/footer.php";
        die();
        endif 
        # BESTIMMTEN BLOGPOST GEFUNDEN; WIRD JETZT ANGEZEIGT
    ?>
    
    <div class="card mb-3">
        <div class="card-header">
            <h3 class="card-title">
                <?=($pd["header"])?>
                <span class="card-subtitle">
                    <?=$created?>
                </span>
            </h3>
        </div>
        <div class="card-body">
            <?=$parsedown->text(($pd["content"]))?>
        </div>
    </div>


    <!-- Kommentarsektion -->
    <div class="comments-section mt-4">
        <h2>Kommentare</h4>
        <!-- Kommentare anzeigen -->
        <?php
        $stmt = $conn->prepare("SELECT * FROM comments WHERE blog_post_id = ? AND is_approved = 1 ORDER BY created_at DESC");
        $stmt->bind_param("i", $postid);
        $stmt->execute();
        $comments = $stmt->get_result();
        if ($comments->num_rows > 0) {
            while ($comment = $comments->fetch_assoc()) {
                echo "<div>";
                echo "<div class=\"card-title mb-0\">{$comment['author']} <span class=\"card-subtitle\">" . (new DateTime($comment['created_at']))->format("d.m.Y, H:i") . "</span></div>";
                echo "<p>{$comment['comment']}</p>";
                echo "</div><div class=\"hr\"></div>";
            }
        } else {
            echo "<p>Noch keine Kommentare.</p>";
        }
        ?>

        <!-- Formular für neuen Kommentar -->
        <form action="/submit_comment.php" method="POST" class="card mt-4 mb-3">
            <div class="card-header"><span class="card-title">Kommentar verfassen</span></div>
            <div class="card-body">
                <input type="hidden" name="postid" value="<?=$postid?>">
                <div class="mb-3">
                    <label for="author" class="form-label">Name</label>
                    <input type="text" class="form-control" id="author" name="author" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">E-Mail</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <div class="mb-3">
                    <label for="comment" class="form-label">Kommentar</label>
                    <textarea class="form-control" id="comment" name="comment" rows="4" required></textarea>
                </div>
                <div class="mb-3">
                    <label for="captcha" class="form-label">Sicherheitsfrage: 5 + 3 = ?</label>
                    <input type="text" class="form-control" id="captcha" name="captcha" required>
                </div>
            </div>
            <div class="card-footer text-end">
                <button type="submit" class="btn btn-primary">Kommentar abschicken</button>
            </div>
        </form>
    </div>

<?php endif ?>

<?php require "components/footer.php";