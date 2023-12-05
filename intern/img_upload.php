<?php
    $navbar_highlighted = "Bilder";
    require "../components/head.php";
    require "../components/navbar_intern.php";
?>

<?php 

    function validateFileName($filename) {
        // Validate the file name using a regular expression or any other validation method
        // Return true if the filename is valid, otherwise return false
        // You can customize the validation logic based on your requirements
        // For example, allow only alphanumeric characters and specific symbols
        $pattern = "/^[a-zA-Z0-9_-]+(\.[a-zA-Z0-9_-]+)*$/";
        return preg_match($pattern, $filename);
    }

    function generateUniqueFileName($filename) {
        $fileExtension = pathinfo($filename, PATHINFO_EXTENSION);
        // Generate a unique filename using a secure method
        $uniqueFilename = bin2hex(random_bytes(8)) . '.' . $fileExtension;
        return $uniqueFilename;
    }

    function isImageFile($file) {
        $validExtensions = array('jpg', 'jpeg', 'png', 'gif');
        $imageInfo = getimagesize($file["tmp_name"]);
        if ($imageInfo !== false) {
            $extension = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));
            if (in_array($extension, $validExtensions)) {
                return true;
            }
        }
        return false;
    }


    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $error = null;
        $status = null;
        $targetDir = "../img/";
        $uploadedFile = $_FILES["imgToUpload"];


        // Check if a file was selected
        if (empty($uploadedFile["name"])) {
            $error = "Bitte wählen Sie eine Bilddatei aus.";
        } else {

            
            // Validate and sanitize the file name
            $originalFileName = $uploadedFile["name"];
            if (!validateFileName($originalFileName)) {
                $error = "Ungültiger Dateiname.";
            }

            // Generate a unique file name
            $uniqueFileName = generateUniqueFileName($originalFileName);
            $targetFile = $targetDir . $uniqueFileName;

            // Check if the uploaded file is a valid image
            if (!isImageFile($uploadedFile)) {
                $error = "Die hochgeladene Datei ist keine Bilddatei. Bitte überprüfen Sie Ihre Datei.";
            }

            // Check if the file already exists
            if (file_exists($targetFile)) {
                $error = "Fehler - ein Bild mit dem generierten Namen gibt es schon. Bitte nochmal probieren.";
            }

            // Check file size
            $maxFileSize = 40000000; // 40 MB
            if ($uploadedFile["size"] > $maxFileSize) {
                $error = "Die Datei ist größer als die erlaubten 40 MB. Wollen Sie die Datei trotzdem hochladen, kontaktieren Sie den " . 
                "<a href='../kontakt.php'>Webmaster</a>.";
            }

            // DATABASE-INSERT
            // Perform the MySQL query to store the data
            // Get the form values
            $imgHeader = $_POST['img_header'];
            $imgDescription = isset($_POST['img_description']) ? $_POST['img_description'] : null;
            $imgDate = isset($_POST['img_date']) ? $_POST['img_date'] : null;
            $imgDateSort = isset($_POST['img_date_sort']) ? $_POST['img_date_sort'] : null;
            $imgLicense = $_POST['img_license'];

            // Validate the form values
            if (empty($imgHeader) || strlen($imgHeader) < 5) {
                $error = "Die Überschrift muss mindestens 5 Zeichen lang sein.";
            } elseif (!empty($imgDateSort) && !validateDate($imgDateSort, 'Y-m-d')) {
                $error = "Fehler bei Ihren Eingaben.";
            } elseif (empty($imgLicense)) {
                $error = "Bitte wählen Sie eine Lizenz aus.";
            }

            // If there are no validation errors, proceed with database insertion
            if (empty($error)) {
                $query = "INSERT INTO image (name, description";
                $values = "VALUES (?, ?";
                $bindValues = array($imgHeader, $imgDescription);
                $types = "ss";

                if (!empty($imgDate)) {
                    $query .= ", date";
                    $values .= ", ?";
                    $bindValues[] = $imgDate;
                    $types .= "s";
                }

                if (!empty($imgDateSort)) {
                    $query .= ", date_sort";
                    $values .= ", ?";
                    $bindValues[] = $imgDateSort;
                    $types .= "s";
                }

                $query .= ", license, filename) ";
                $values .= ", ?, ?)";
                $bindValues[] = $imgLicense;
                $bindValues[] = $uniqueFileName;
                $types .= "ss";

                $finalQuery = $query . $values;

                $stmt = $myconn->prepare($finalQuery);
                $stmt->bind_param($types, ...$bindValues);

                if ($stmt->execute()) {
                    $status = "Daten erfolgreich gespeichert.";
                } else {
                    $error = "Fehler beim Speichern der Daten: " . $stmt->error;
                }
            }




            // Wenn jetzt keine Fehler mehr bestehen, kann die Datei hochgeladen werden
            if ($error === null) {
                if (move_uploaded_file($uploadedFile["tmp_name"], $targetFile)) {
                    $status = "Die <a href='$targetFile' class'alert-link'>Datei</a> wurde erfolgreich hochgeladen.";
                } else {
                    $error = "Fehler beim Hochladen der Datei.";
                }
            }
        }
    }

?>

<form class="card mt-3" action="./img_upload.php" method="POST" enctype="multipart/form-data">
    <div class="card-body">
        <h1>Bild-Upload</h1>
        <?php if(isset($error)) {echo '
                <div class="alert alert-danger alert-dismissible" role="alert">
                <div class="d-flex">
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"> <path stroke="none" d="M0 0h24v24H0z" fill="none"></path> <circle cx="12" cy="12" r="9"></circle> <line x1="12" y1="8" x2="12" y2="12"></line> <line x1="12" y1="16" x2="12.01" y2="16"></line> </svg>
                    </div>
                    <div>'. $error .'</div>
                </div>
                <a class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="close"></a>
                </div>';
            '';}
            
            // ERFOLGREICH? --> MELDUNG HIER
                if(isset($status) && $status !== "") {
                    echo '
                        <div class="alert alert-success alert-dismissible" role="alert"> 
                        <div class="d-flex">
                            <div> <svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"> <path stroke="none" d="M0 0h24v24H0z" fill="none"></path> <path d="M5 12l5 5l10 -10"></path> </svg> </div>
                            <div>
                                <h4 class="alert-title">Hochladen erfolgreich!</h4> 
                                <div class="text-muted">'.$status.'</div>
                            </div>
                            <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
                        </div></div>';
                } ?>
        <div class="mb-3">
            <div class="form-label">Bilddatei hochladen</div>
            <input type="file" name="imgToUpload" class="form-control <?php echo !empty($error) && empty($_FILES['imgToUpload']['name']) ? 'is-invalid' : ''; ?>">
            <?php if (!empty($error) && empty($_FILES['imgToUpload']['name'])) { echo '<div class="invalid-feedback">Bitte wählen Sie eine Bilddatei aus.</div>'; } ?>
        </div>
        <div class="mb-3">
            <div class="form-label required">Überschrift</div>
            <input type="text" class="form-control <?php echo !empty($error) && (empty($imgHeader) || strlen($imgHeader) < 5) ? 'is-invalid' : ''; ?>" name="img_header" value="<?php echo isset($_POST['img_header']) ? $_POST['img_header'] : ''; ?>">
            <?php if (!empty($error) && (empty($imgHeader) || strlen($imgHeader) < 5)) { echo '<div class="invalid-feedback">Die Überschrift muss mindestens 5 Zeichen lang sein.</div>'; } ?>
        </div>
        <div class="mb-3">
            <div class="form-label">Beschreibung</div>
            <textarea class="form-control" rows="4" name="img_description"><?php echo isset($_POST['img_description']) ? $_POST['img_description'] : ''; ?></textarea>
        </div>
        <div class="mb-3">
        <div class="form-label">Datum</div>
            <input type="text" class="form-control" name="img_date" value="<?php echo isset($_POST['img_date']) ? $_POST['img_date'] : ''; ?>">
        </div>
        <div class="mb-3">
            <div class="form-label">Datum (yyyy-mm-dd)</div>
            <input type="text" class="form-control <?php echo !empty($error) && (isset($imgDateSort) && !validateDate($imgDateSort, 'Y-m-d')) ? 'is-invalid' : ''; ?>" name="img_date_sort" value="<?php echo isset($_POST['img_date_sort']) ? $_POST['img_date_sort'] : ''; ?>">
            <?php if (!empty($error) && (isset($imgDateSort) && !validateDate($imgDateSort, 'Y-m-d'))) { echo '<div class="invalid-feedback">Das Datum muss das Format "YYYY-MM-DD" haben.</div>'; } ?>
        </div>
        <div class="mb-3">
            <div class="form-label required">Lizenz</div>
            <select class="form-control <?php echo !empty($error) && empty($imgLicense) ? 'is-invalid' : ''; ?>" name="img_license">
                <option value="1">Copyright (Keine Weiternutzung erlaubt)</option>
            </select>
            <?php if (!empty($error) && empty($imgLicense)) { echo '<div class="invalid-feedback">Bitte wählen Sie eine Lizenz aus.</div>'; } ?>
        </div>
        <div class="mb-3">
            <input class="btn btn-primary" type="submit" value="Bild hochladen" name="submit">
        </div>
    </div>
</form>

<?php
    

    require "../components/footer.php";