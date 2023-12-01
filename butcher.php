<?php 
    $leaflet = true;
    $opening_hours = true;
    require "components/head.php";
    require "components/navbar.php";
    require "components/conn.php";
    require "components/butcher.php";
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

    $butcher = new Butcher($id, $lat, $lon, $tagsJson);

    $name = $t["name"];
    
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
                        # Table with all OSM tags for debugging purposes
                        /*
                        echo "<table>";

                        foreach ($t as $key => $value) {
                            echo "<tr><td>$key</td><td>$value</td></tr>\n";
                        }


                        echo "</table>";
                        */?>
                        <div class="mt-3">
                            <?=$butcher->getInfoCard();?>
                        </div>
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
            <div class="col-lg-4">
                <div class="card">
                    <?php if($butcher->getOpeningState()) : ?>
                        <div class="ribbon bg-lime">Geöffnet</div>
                    <?php endif ?>
                    <?php if(!$butcher->getOpeningState()) : ?>
                        <div class="ribbon bg-red">Geschlossen</div>
                    <?php endif ?>
                    <h3 class="card-header">
                        Öffnungszeiten
                    </h3>
                    <div class="card-body">
                        <?=$butcher->getOpeningHoursHTML();?>
                    </div>
                </div>
              </div>
            </div>
        </div>








<?php 
require "components/footer.php";