<?php
    $navbar_highlighted = "Bilder";
    $tom_select = true;
    require "../components/head.php";
    require "../components/butcher.php";
    require "../components/navbar_intern.php";

    // Function to fetch image records from the database
    function getImages()
    {
        global $conn;
        $query = "SELECT id, name FROM image";
        $result = mysqli_query($conn, $query);
        $images = array();

        while ($row = mysqli_fetch_assoc($result)) {
            $images[] = $row;
        }

        return $images;
    }

    function getButchers()
    {
        global $conn;
        $query = "SELECT * FROM butchers";
        $result = mysqli_query($conn, $query);
        $butchers = array();

        while ($row = mysqli_fetch_assoc($result)) {
            $butchers[] = $row;
        }

        return $butchers;
    }

    function insertImageButcher($imageId, $butcherId)
    {
        global $myconn;
        $query = "INSERT INTO image_butcher (image, butcher) VALUES (?, ?)";
        $stmt = mysqli_prepare($myconn, $query);
        mysqli_stmt_bind_param($stmt, "ss", $imageId, $butcherId);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }

    function removeImageButcher($imageId, $butcherId) {
        global $conn;
        $query = "DELETE FROM image_butcher WHERE image = ? AND butcher = ?;";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "ss", $imageId, $butcherId);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }

    // Initialize variables for error messages
    $imageError = null;
    $butcherError = null;
    $successMessage = null;

    // Fetch images and Bahnübergang records from the database
    $images = getImages();
    $butchers = getButchers();

    // Check if the form is submitted
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        // Validate and sanitize the input values
        $selectedImage = $_POST["selectedImage"];
        $selectedButcher = $_POST["selectedButcher"];

        // Perform additional validation if needed

        // Check if both inputs are provided
        if (empty($selectedImage)) {
            $imageError = "Please select an image.";
        }

        if (empty($selectedBahnübergang)) {
            $butcherError = "Please select a Butcher.";
        }

        if (empty($imageError) && empty($bahnübergangError)) {
            // Check if the selected image exists in the database
            $imageExists = false;
            foreach ($images as $image) {
                if ($image['id'] == $selectedImage) {
                    $imageExists = true;
                    break;
                }
            }

            // Check if the selected Butcher exists in the database
            $butcherExists = false;
            foreach ($butchers as $butcher) {
                if ($butcher['id'] == $selectedButcher) {
                    $butcherExists = true;
                    break;
                }
            }

            if (!$imageExists) {
                $imageError = "Selected image does not exist.";
            } elseif (!$butcherExists) {
                $butcherError = "Selected butcher does not exist.";
            } else {
                // If the input is valid, insert the relationship into the database
                insertImageButcher($selectedImage, $selectedButcher);
                $successMessage = "Image and butcher connected successfully.";
            }
        }
    }
?>


    <div class="card mt-3"><div class="card-body">
        <h1>Bilder zu Metzger hinzufügen</h1>

        <form method="POST" action="">
            <div class="mb-3">
                <label class="form-label" for="selectedImage">Bild</label>
                <select class="form-select" name="selectedImage" id="selectedImage">
                    <option value="">-- Bild auswählen --</option> <!-- Empty option -->
                    <?php foreach ($images as $image): ?>
                        <?php
                            $selected = false;
                            if(isset($_GET['id']) && $_GET['id']==$image['id']) {
                                $selected = true;
                            }
                        ?>
                        <option value="<?php echo $image['id']; ?>"<?php if($selected){echo "selected";}?>><?php echo $image['name']; ?></option>
                    <?php endforeach; ?>
                </select>
                <?php if ($imageError): ?>
                    <div class="invalid-feedback"><?php echo $imageError; ?></div>
                <?php endif; ?>
            </div>
            <div class="mb-3">
                <label class="form-label" for="selectedButcher">Select Bahnübergang:</label>
                <select name="selectedButcher" id="selectedButcher" class="form-select">
                </select>
                <?php if ($butcherError): ?>
                    <div class="invalid-feedback"><?php echo $butcherError; ?></div>
                <?php endif; ?>
            </div>
            <div class="mb-3">
                <input type="submit" value="Connect" class="btn btn-primary">
            </div>
        </form>
        <?php if ($successMessage): ?>
            <div class="alert alert-success mt-3" role="alert"><?php echo $successMessage; ?></div>
        <?php endif; ?>
    </div></div>
<?php require "../components/footer.php"; ?>

<script>

    let options =[];

    async function getButchers() {
        try {
            let res = await fetch("../static/butchers.json");
            return await res.json();
        } catch (error) {
            console.log(error);
        }
    }

    async function renderButchers() {
        allButchers = await getButchers();
        result = Object.assign({}, allButchers);
        
        Object.values(result).forEach((butcher, index) => {
            options.push({
                id: butcher.id,
                title: butcher.tags.name
            });
        });

        
        new TomSelect('#selectedButcher',{
            valueField: 'id',
            labelField: 'title',
            searchField: 'title',
            sortField: 'title',
            options: options,
            create: false,
    		render:{
    			item: function(data,escape) {
    				return '<div><span class="dropdown-item-indicator">' + escape(data.title) + '</span></div>';
    			},
    		},
        });

    }

    renderButchers();


</script>
