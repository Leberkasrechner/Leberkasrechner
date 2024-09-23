<?php
require __DIR__ . '/components/conn.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $postid = $_POST['postid'];
    $author = htmlspecialchars(trim($_POST['author']));
    $email = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);
    $comment = htmlspecialchars(trim($_POST['comment']));
    $captcha = trim($_POST['captcha']);

    if (!$email) {
        echo "Ungültige E-Mail-Adresse.";
        exit;
    }

    if ($captcha !== '8') {
        echo "Falsche Antwort auf die Sicherheitsfrage.";
        exit;
    }

    if (!empty($author) && !empty($email) && !empty($comment)) {
        $stmt = $conn->prepare("INSERT INTO comments (blog_post_id, author, email, comment, is_approved) VALUES (?, ?, ?, ?, 0)");
        $stmt->bind_param("isss", $postid, $author, $email, $comment);
        if ($stmt->execute()) {
            // Erfolgsnachricht und automatische Weiterleitung
            echo "<p>Kommentar erfolgreich eingereicht. Er wird nach Überprüfung freigeschaltet.</p>";
            echo "<p>Sie werden in 3 Sekunden zum Blogpost weitergeleitet...</p>";
            echo "<script>
                    setTimeout(function() {
                        window.location.href = '/blog.php?post={$postid}';
                    }, 3000);
                  </script>";
        } else {
            echo "Fehler beim Speichern des Kommentars.";
        }
    } else {
        echo "Bitte alle Felder ausfüllen.";
    }
} else {
    header("Location: /blog.php");
    exit;
}
