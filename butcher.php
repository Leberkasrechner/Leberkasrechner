<?php 
    $leaflet = true;
    $opening_hours = true;
    require "components/head.php";
    require "components/navbar.php";
    require "components/conn.php";
    require "components/util.php";
    global $conn;
    $sql = "SELECT id, lat, lon, tags FROM butchers WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $_GET["id"]);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {echo "Keine Ergebnisse gefunden";}
    
    $res = $result->fetch_assoc();
    $id = $res['id'];
    $lat = $res['lat'];
    $lon = $res['lon'];
    $tagsJson = $res['tags'];
    $t = json_decode(utf8_encode($tagsJson), true);
    $name = $t["name"];
    if (strpos($name, 'Metzgerei') === false) {
        $name = 'Metzgerei ' . $name;
    }
    

    ## INFO CARD ######################################
    $infocardcode = "";
    $svgs = array(
        "addr:city" => '<path stroke="none" d="M0 0h24v24H0z" fill="none" />
        <path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0" />
        <path d="M3.6 9h16.8" />
        <path d="M3.6 15h16.8" />
        <path d="M11.5 3a17 17 0 0 0 0 18" />
        <path d="M12.5 3a17 17 0 0 1 0 18" />'
    );
    $descriptions = array(
        "addr:city" => "Stadt",
        ""
    );
    
    foreach (array_keys($t) as $key) {
        $value = $t[$key];
    
        if (array_key_exists($key, $descriptions)) {
            $infocardcode .= '<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-world" width="24" height="24" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">';
            $infocardcode .= $svgs[$key] . "</svg>";
        }

    }
    

    # SEITENBEGINN
    ?>

    <div class="page-header d-print-none">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">
                    <?=$name?>
                </h2>
            </div>
        </div>
    </div>


    <div class="page-body">
        <div class="row row-cards">
            <div class="col-lg-8">
                <div class="card card-lg">
                    <div class="card-body">
                        <div class="card-title">
                            Informationen
                        </div> 
                        

                        <?php
                        echo "<table>";

                        foreach ($t as $key => $value) {
                            echo "<tr><td>$key</td><td>$value</td></tr>\n";
                        }


                        echo "</table>";
                        ?>

                        <?=$infocardcode?>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <?=domap($lat, $lon, $name);?>
                    </div>
                  <div class="card-footer">
                    Kartendaten &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>-Mitwirkende
                  </div>
                </div>
              </div>
            </div>
        </div>








<?php 
require "components/footer.php";