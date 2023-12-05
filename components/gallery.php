<?php


    function gallery($images) {
        $g_id = bin2hex(random_bytes(6)); //Eindeutige ID für die Gallerie
        $ret = "<div class='gallery-$g_id d-flex'>";
        foreach ($images as $iid) {
            $img = getEntity("image", "id", $iid);
            $img_filename = $img["filename"];
            $img_description = $img["description"];
            $ret .= "<a href='/img/$img_filename' title='$img_description' style='margin: 0.5rem;'>";
            $ret .= "<img src='img.php?id=$iid&h=350' height='150'>";
            $ret .= "</a>";

        }
        $ret .= "</div>";
        // Lightbox-Script
        $ret .= "<script>window.onload = function() {
            baguetteBox.run('.gallery-$g_id')}</script>";
        return $ret;
    }
    
    
    ?>