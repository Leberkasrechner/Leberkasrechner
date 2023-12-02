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
<style>
    .cards-responsive{
        display: flex;
    }
    .page-title-responsive{
        padding-left: 0rem;
    }
    @media only screen and (max-width: 942px) {
        .cards-responsive {
            display: block;
            margin-left: 1rem;
            margin-right: 1rem;
            margin-top: 2rem;
        }
        .page-title-responsive{
            margin-left: 1.5rem;
        }

    }
    </style>
    <div class="page-header d-print-none">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title page-title-responsive">
                    <?=$name?>
                </h2>
            </div>
        </div>
    </div>


    <div class="page-body">
        <div class="row row-cards cards-responsive">
            <div class="col">
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
            <?php if($butcher->getOpeningHoursHTML()) : ?>
            <div class="col">
                <div class="card">
                    <?php if($butcher->getOpeningState()) : ?>
                        <div class="ribbon bg-lime">Geöffnet</div>
                    <?php endif ?>
                    <?php if(!$butcher->getOpeningState()) : ?>
                        <div class="ribbon bg-red">Geschlossen</div>
                    <?php endif ?>
                    <div class="card-body">
                        <h3>
                            <span class="me-2">Öffnungszeiten</span>
                            <span class="badge text-red">Beta</span>
                        </h3>
                        <?=$butcher->getOpeningHoursHTML();?>
                        <div class="mt-2">
                            <small><i>
                                <span>Abweichungen insbesondere an Feiertagen und während der Ferien möglich.</span>
                                <?php if(!empty($butcher->getOpeningHoursCheckDate())) : ?>
                                    <br><span>
                                        Zuletzt geprüft am
                                        <?=$butcher->getOpeningHoursCheckDate();?>.
                                    </span>
                                <?php endif ?>
                            </i></small>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif ?>
            <div class="col">
                <div class="card">
                    <div class="card-body">
                        <?=domap($lat, $lon, $name);?>
                    </div>
                </div>
                <div class="card mt-3">
                    <div class="card-stamp">
                        <div class="card-stamp-icon bg-white text-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-database-edit" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 6c0 1.657 3.582 3 8 3s8 -1.343 8 -3s-3.582 -3 -8 -3s-8 1.343 -8 3" /><path d="M4 6v6c0 1.657 3.582 3 8 3c.478 0 .947 -.016 1.402 -.046" /><path d="M20 12v-6" /><path d="M4 12v6c0 1.526 3.04 2.786 6.972 2.975" /><path d="M18.42 15.61a2.1 2.1 0 0 1 2.97 2.97l-3.39 3.42h-3v-3l3.42 -3.39z" /></svg>
                        </div>
                    </div>
                    <div class="card-body">
                        <p>
                            Daten dieser Seite &copy; <a href="https://osm.org/copyright">OpenStreetMap</a>-Mitwirkende.
                        </p>
                        <p>
                            Auch Du kannst helfen, die Daten aktuell zu halten oder zu erweitern!
                            Zum Beispiel, indem Du <a href="https://osm.org/edit?node=<?=$_GET["id"]?>">diesen Node auf OpenStreetMap bearbeitest</a>.
                        </p>
                    </div>
                </div>
            </div>
        </div>
</div>








<?php 
require "components/footer.php";