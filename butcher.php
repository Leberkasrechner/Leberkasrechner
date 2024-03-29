

<?php 
        $leaflet = true;
        $opening_hours = true;
        $page_title = "Metzgerei";
        require "components/conn.php";
        require "components/butcher.php";
        require "components/gallery.php";
     
        global $conn;
        $sql = "SELECT id, lat, lon, tags FROM butchers WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $_GET["id"]);
        $stmt->execute();
        $result = $stmt->get_result();
        $res = $result->fetch_assoc();
        
        $noresults = false;
        if($result->num_rows !== 1) {
            $noresults = true;
        }
        $id = $lat = $lon = $tagsJson = $t = $butcher = $name = null;
        if(!$noresults) {
            $id = $res['id'];
            $lat = $res['lat'];
            $lon = $res['lon'];
            $tagsJson = $res['tags'];
            $t = json_decode(utf8_encode($tagsJson), true);
            $butcher = new Butcher($id, $lat, $lon, $tagsJson);
            $name = $t["name"];
            $page_title = $butcher->getName();
        }
        require "components/head.php";
        require "components/navbar.php";
        require "components/util.php";
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
    <div class="page">
        <?php if(!$noresults) : ?>
        <div class="page-header d-print-none">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <h2 class="page-title page-title-responsive">
                        <?=$name?>
                    </h2>
                </div>
            </div>
        </div>
        <?php endif ?>
     
        <div class="page-body">
            <?php if($noresults) : ?>
                <div class='empty'>
                    <div class='empty-img'><img src='static/svg/no_results.svg' height='128' alt=''>
                    </div>
                    <p class='empty-title'>Metzgerei nicht gefunden</p>
                    <div class='empty-action'>
                        <a href='/search_form.php' class='btn btn-primary'>
                            <svg xmlns='http://www.w3.org/2000/svg' class='icon icon-tabler icon-tabler-map-pin-search' width='24' height='24' viewBox='0 0 24 24' stroke-width='2' stroke='currentColor' fill='none' stroke-linecap='round' stroke-linejoin='round'><path stroke='none' d='M0 0h24v24H0z' fill='none'/><path d='M14.916 11.707a3 3 0 1 0 -2.916 2.293' /><path d='M11.991 21.485a1.994 1.994 0 0 1 -1.404 -.585l-4.244 -4.243a8 8 0 1 1 13.651 -5.351' /><path d='M18 18m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0' /><path d='M20.2 20.2l1.8 1.8' /></svg>
                            Metzgerei suchen
                        </a>
                    </div>
                </div>
            <?php die(); endif ?>
            
            <div class="row row-cards row-cols-1 row-cols-md-2 row-cols-lg-3">
                <div class="col">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                Infomationen
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="ms-3 me-3">
                                <?=$butcher->getInfoCard();?>
                            </div>
                        </div>
                    </div>
                    <?php if($butcher->diet_info_available) : ?>
                    <div class="card mt-2">
                        <div class="card-body">
                            <h4>Über das Angebot</h4>
                            <ul class="list-unstyled space-y-1">
                                <?=$butcher->getDietInfoHTML();?>
                            </ul>
                        </div>
                    </div>
                    <?php endif ?>
                    <?php if($butcher->getImageIDs($conn)) : ?>
                        <div class="card mt-3">
                            <div class="card-header">
                                <h3 class="card-title">Gallerie</h3>
                            </div>
                            <div class="card-body">
                            <?=gallery($butcher->getImageIDs($conn));?>
                            </div>
                        </div>
                    <?php else : ?>
                        <div class="col">
                            <div class="card mt-3">
                                <div class="card-header">
                                    <h3 class="card-title">Bilder hinzufügen</h3>
                                </div>
                                <div class="card-body">
                                    <p>
                                        Für diesen Metzger gibt es leider
                                        <strong>noch keine Bilder!</strong>
                                        <a href="contribute.php">Hilf mit</a> und lade
                                        ein Bild hoch!
                                    </p>
                                    <a class="btn btn-outline mt-2" target="_blank" href="/intern/img_upload.php">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-camera-plus" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 20h-7a2 2 0 0 1 -2 -2v-9a2 2 0 0 1 2 -2h1a2 2 0 0 0 2 -2a1 1 0 0 1 1 -1h6a1 1 0 0 1 1 1a2 2 0 0 0 2 2h1a2 2 0 0 1 2 2v3.5" /><path d="M16 19h6" /><path d="M19 16v6" /><path d="M9 13a3 3 0 1 0 6 0a3 3 0 0 0 -6 0" /></svg>
                                        Bild hochladen
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endif ?>
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
                            <div class="card-header">   
                                <h3 class="card-title">
                                    <span class="me-2">Öffnungszeiten</span>
                                    <span class="badge text-red">Beta</span>
                                </h3>
                            </div>
                            <div class="card-body">
                                <?=$butcher->getOpeningHoursHTML();?>
                                <div class="mt-2">
                                    <small><i>
                                        <span>Abweichungen insbesondere an Feiertagen und während der Ferien möglich.</span>
                                        <?php if(!empty($butcher->getOpeningHoursCheckDate())) : ?>
                                            <span>
                                                Zuletzt geprüft am
                                                <?=$butcher->getOpeningHoursCheckDate();?>.
                                            </span>
                                        <?php endif ?>
                                    </i></small>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php else : ?>
                    <div class="col">
                        <div class="card bg-warning-lt">
                            <div class="card-header">
                                <h3 class="card-title">Hier fehlen Informationen!</h3>
                            </div>
                            <div class="card-body">
                                <p>
                                    Zu diesem Metzger liegen
                                    <strong>leider keine Öffnungszeiten</strong>
                                    vor! <a href="contribute.php">Hilf mit</a>,
                                    die Daten zu verbessern und 
                                    bearbeite diesen Node auf OpenStreetMap.
                                </p>
                                <a class="btn btn-outline mt-2" target="_blank" href="https://osm.org/edit?node=<?=$_GET["id"]?>">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-map-pin-plus" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 11a3 3 0 1 0 6 0a3 3 0 0 0 -6 0" /><path d="M12.794 21.322a2 2 0 0 1 -2.207 -.422l-4.244 -4.243a8 8 0 1 1 13.59 -4.616" /><path d="M16 19h6" /><path d="M19 16v6" /></svg>
                                    Zu OpenStreetMap
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endif ?>
                <div class="col">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Kartenansicht</h3>
                        </div>
                        <div class="card-body">
                            <?=domap($lat, $lon, $name);?>
                        </div>
                        <div class="card-footer">
                            <ul class="list-group list-group-flush list-group-hoverable">
                                <li class="list-group-item">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-external-link" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 6h-6a2 2 0 0 0 -2 2v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-6" /><path d="M11 13l9 -9" /><path d="M15 4h5v5" /></svg>
                                    <a href="https://osm.org/?mlat=<?=$lat?>&mlon=<?=$lon?>" class="link link-secondary" target="_blank">
                                        OpenStreetMap
                                    </a>
                                </li>
                                <li class="list-group-item">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-external-link" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 6h-6a2 2 0 0 0 -2 2v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-6" /><path d="M11 13l9 -9" /><path d="M15 4h5v5" /></svg>
                                    <a href="https://maps.google.com/maps?q=<?=$lat?>,<?=$lon?>&ll=<?=$lat?>,<?=$lon?>&z=17" class="link link-secondary" target="_blank">
                                        Google Maps
                                    </a>
                                </li>
                            </ul>
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
                                Zum Beispiel, indem Du <a target="_blank" href="https://osm.org/edit?node=<?=$_GET["id"]?>">diesen Node auf OpenStreetMap bearbeitest</a>.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
     
     
     
     
     
     
    <?php 
    require "components/footer.php";

