<?php
    require "components/conn.php";
    $image;
    $desiredHeight = 500;

    if (!isset($_GET['id'])) {
        echo "No image given!";
        exit();
    } else {
        $imgid = $_GET["id"];
        $imgpath = "img/" . getValue("image", "id", $imgid, "filename");

        if (!file_exists($imgpath)) {
            echo "This image does not exist!";
            exit();
        }

        // Determine the image type and create an image resource accordingly
        $imgInfo = getimagesize($imgpath);
        $imgType = $imgInfo[2]; // 1 = GIF, 2 = JPEG, 3 = PNG
        switch ($imgType) {
            case IMAGETYPE_JPEG:
                $image = imagecreatefromjpeg($imgpath);
                break;
            case IMAGETYPE_PNG:
                $image = imagecreatefrompng($imgpath);
                break;
            default:
                echo "Unsupported image type!";
                exit();
        }
    }

    if (isset($_GET['h'])) {
        $desiredHeight = $_GET['h'];
    } else {
        // Output the original image with its original format
        header("Content-type: image/" . image_type_to_extension($imgType, false));
        switch ($imgType) {
            case IMAGETYPE_JPEG:
                imagejpeg($image);
                break;
            case IMAGETYPE_PNG:
                imagepng($image);
                break;
        }
        exit();
    }

    // Get the original width and height
    $originalWidth = imagesx($image);
    $originalHeight = imagesy($image);

    // Calculate the width based on the desired height and original aspect ratio
    $desiredWidth = round(($desiredHeight / $originalHeight) * $originalWidth);

    // Create a new image with the desired dimensions
    $img = imagescale($image, $desiredWidth, $desiredHeight);

    // Output the resized image in PNG format
    header("Content-type: image/png");
    imagepng($img);

    // Free up memory by destroying the image resources
    imagedestroy($image);
    imagedestroy($img);
?>
