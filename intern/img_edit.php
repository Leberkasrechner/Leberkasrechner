<?php
$navbar_highlighted = "Bilder";
require "../components/head.php";
require "../components/navbar_intern.php";

// Function to retrieve image options for the dropdown
function getImageOptions()
{
    global $myconn;
    $query = "SELECT id, name FROM image";
    $result = $myconn->query($query);
    $options = "";
    while ($row = $result->fetch_assoc()) {
        $options .= "<option value='{$row['id']}'>{$row['name']}</option>";
    }
    return $options;
}

// yyyy-mm-dd-Validierung
function validateDate($date, $format)
{
    if($date === "") {return true;}
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) === $date;
}

// Function to retrieve image details based on ID
function getImageDetails($id)
{
    global $myconn;
    $query = "SELECT * FROM image WHERE id = ?";
    $stmt = $myconn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    }
    return null;
}
$showModal = false;
// Check if the image ID is provided in the URL
if (isset($_GET['id'])) {
    $imageId = $_GET['id'];
    $imageDetails = getImageDetails($imageId);
} else {
    $imageId = null;
    $imageDetails = null;
    $imageDetails['img_header'] = $imageDetails['description'] = $imageDetails['date'] = 
    $imageDetails['date'] = $imageDetails['date_sort'] = $imageDetails['license'] = "";
    die();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the form values
    $imgHeader = isset($_POST['img_header']) ? $_POST['img_header'] : null;
    $imgDescription = isset($_POST['img_description']) ? $_POST['img_description'] : null;
    $imgDate = isset($_POST['img_date']) ? $_POST['img_date'] : null;
    $imgDateSort = isset($_POST['img_date_sort']) ? $_POST['img_date_sort'] : null;
    $imgLicense = $_POST['img_license'];

    // Validate the form values
    $error = null;
    if (empty($imgHeader) || strlen($imgHeader) < 5) {
        $error = "Die Überschrift muss mindestens 5 Zeichen lang sein.";
    } elseif (!validateDate($imgDateSort, 'Y-m-d')) {
        $error = "Fehler bei Ihren Eingaben.";
    } elseif (empty($imgLicense)) {
        $error = "Bitte wählen Sie eine Lizenz aus.";
    }

    // If there are no validation errors, proceed with database update
    if (empty($error)) {
        if (empty($imgDateSort)) { $query = "UPDATE image SET name=?, description=?, date=?, date_sort=NULL, license=? WHERE id=?"; }
        else { $query = "UPDATE image SET name=?, description=?, date=?, date_sort=?, license=? WHERE id=?"; }
        $stmt = $myconn->prepare($query);
        if (empty($imgDateSort)) { $stmt->bind_param("ssssi", $imgHeader, $imgDescription, $imgDate, $imgLicense, $imageId); }
        else { $stmt->bind_param("sssssi", $imgHeader, $imgDescription, $imgDate, $imgDateSort, $imgLicense, $imageId); }
        
        if ($stmt->execute()) {
            $status = "Daten erfolgreich aktualisiert.";
            $imageDetails = getImageDetails($imageId); // Update image details after successful update
        } else {
            $error = "Fehler beim Aktualisieren der Daten: " . $stmt->error;
        }
    }
}
?>

<div class="container">
    <h1 class="mt-3">Bild bearbeiten</h1>

    <?php
    // Display error message if there is an error
    if (!empty($error)) {
        echo '<div class="alert alert-danger" role="alert">' . $error . '</div>';
    }

    // Display success message if there is a success status
    if (!empty($status)) {
        echo '<div class="alert alert-success" role="alert">' . $status . '</div>';
    }
    ?>

    <form method="POST" action="">
        <div class="mb-3">
            <label class="form-label required" for="img_header">Überschrift</label>
            <input type="text" class="form-control" id="img_header" name="img_header" value="<?php echo isset($imgHeader) ? htmlspecialchars($imgHeader) : htmlspecialchars($imageDetails['name']); ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label" for="img_description">Beschreibung</label>
            <textarea class="form-control" id="img_description" name="img_description"><?php echo isset($imgDescription) ? htmlspecialchars($imgDescription) : htmlspecialchars($imageDetails['description']); ?></textarea>
        </div>
        <div class="mb-3">
            <label class="form-label" for="img_date">Datum</label>
            <input type="text" class="form-control" id="img_date" name="img_date" value="<?php echo isset($imgDate) ? htmlspecialchars($imgDate) : htmlspecialchars($imageDetails['date']); ?>">
        </div>
        <div class="mb-3">
            <label class="form-label" for="img_date_sort">Sortierungsdatum</label>
            <input type="text" class="form-control" id="img_date_sort" name="img_date_sort" value="<?php echo isset($imgDateSort) ? htmlspecialchars($imgDateSort) : htmlspecialchars($imageDetails['date_sort']); ?>">
        </div>
        <div class="mb-3">
            <label class="form-label required" for="img_license">Lizenz</label>
            <select class="form-control" id="img_license" name="img_license" required>
                <option value="1">Copyright (Keine Weiternutzung erlaubt)</option>
            </select>
        </div>
        <?php if(!$showModal) {
            echo '
            <div class="row g-2 align-items-center">
                <div class="col-6 col-sm-4 col-md-2 col-xl py-3">
                    <button type="submit" class="btn btn-primary">Aktualisieren</button>
                </div>
                <div class="col-6 col-sm-4 col-md-2 col-xl py-3">
                    <a class="btn btn-secondary col-6 col-sm-4 col-md-2 col-xl-auto" href="/img_connect.php?id=' . $imageId . '">Bild zu Eintrag hinzufügen/von Eintrag entfernen</a>
                </div>
            </div>'
            ;
        } ?>
    </form>

    <?php if ($showModal) : ?>
        <div class="modal modal-blur fade show" id="modal-simple" tabindex="-1" style="display: block;" aria-modal="true" role="dialog">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Bild auswählen</h5>
                    </div>
                    <div class="modal-body">
                        <form method="GET" action="">
                            <div class="form-group">
                                <label class="form-label" for="image_id">Sie müssen ein Bild auswählen, um fortzufahren.</label>
                                <select class="form-control" id="image_id" name="id" required>
                                    <option value="">Bitte wählen</option>
                                    <?php echo getImageOptions(); ?>
                                </select>
                            </div>
                    </div>
                    <div class="modal-footer">
                        <a class="btn btn-ghost-secondary" href="./">Abbrechen</a>
                        <button type="submit" class="btn btn-primary">Auswählen</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php require "../components/footer.php"; ?>
