<?php
$opening_hours = true;
$navbar_highlighted = "Suche";
require "components/head.php";
require "components/navbar.php";
require "components/conn.php";
require "components/butcher.php";


if (isset($_GET['q'])) {
    //SQL-Abfrage
    $searchString = mysqli_real_escape_string($conn, $_GET['q']);
    $currentPage = 1;
    if(!empty($_GET["page"])) {$currentPage = $_GET["page"];}
    $sql = "SELECT * FROM butchers WHERE MATCH (tags) AGAINST ('" . $searchString . "')";
    $result = $conn->query($sql);

    # Keine Suchergebnisse
    if ($result->num_rows == 0) : ?>
        
        <div class='empty'>
            <div class='empty-img'><img src='static/svg/no_results.svg' height='128' alt=''>
            </div>
            <p class='empty-title'>Keine Ergebnisse gefunden</p>
            <p class='empty-subtitle text-secondary'>
                Suchen Sie ihre Metzgerei auf unserer Karte:
            </p>
            <div class='empty-action'>
            <a href='/' class='btn btn-primary'>
                <svg xmlns='http://www.w3.org/2000/svg' class='icon icon-tabler icon-tabler-map-pin-search' width='24' height='24' viewBox='0 0 24 24' stroke-width='2' stroke='currentColor' fill='none' stroke-linecap='round' stroke-linejoin='round'><path stroke='none' d='M0 0h24v24H0z' fill='none'/><path d='M14.916 11.707a3 3 0 1 0 -2.916 2.293' /><path d='M11.991 21.485a1.994 1.994 0 0 1 -1.404 -.585l-4.244 -4.243a8 8 0 1 1 13.651 -5.351' /><path d='M18 18m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0' /><path d='M20.2 20.2l1.8 1.8' /></svg>
                Karte deutscher Metzgereien
            </a>
            </div>
            <p class='empty-subtitle text-secondary empty-action'>
                Oder probieren Sie es mit anderen Suchbegriffen:
            </p>
            <div class='empty-action'>
                <form role='search' action='/search.php' method='get' autocomplete='off' novalidate=''>
                <div class='input-icon'>
                    <span class='input-icon-addon'>
                        <svg xmlns='http://www.w3.org/2000/svg' class='icon' width='24' height='24' viewBox='0 0 24 24' stroke-width='2' stroke='currentColor' fill='none' stroke-linecap='round' stroke-linejoin='round'><path stroke='none' d='M0 0h24v24H0z' fill='none'></path><path d='M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0'></path><path d='M21 21l-6 -6'></path></svg>
                    </span>
                    <input type='text' name='q' class='form-control' placeholder='Neuer Suchbegriff...' aria-label='Metzgereien suchen'>
                </div>
                </form>
            </div>
        </div>
        
    <?php endif;
    if ($result->num_rows > 0) {
        // Seitenaufteilung bei mehr als 10 Ergebnissen
        $resultsPerPage = 10;
        $totalResults = $result->num_rows;
        $totalPages = ceil($totalResults / $resultsPerPage);
        
        if (!isset($_GET['page'])) {
            $page = 1;
        } else {
            $page = $_GET['page'];
        }
        
        $offset = ($page - 1) * $resultsPerPage;
        
        $stmt = $conn->prepare("$sql LIMIT ?, ?");
        $stmt->bind_param("ii", $offset, $resultsPerPage);
        $stmt->execute();
        $pagedResult = $stmt->get_result();
        ?>
        
        <div class="alert alert-warning" role="alert">
            <div class="d-flex">
                <div>
                    <!-- Download SVG icon from http://tabler-icons.io/i/alert-triangle -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M12 9v4"></path><path d="M10.363 3.591l-8.106 13.534a1.914 1.914 0 0 0 1.636 2.871h16.214a1.914 1.914 0 0 0 1.636 -2.87l-8.106 -13.536a1.914 1.914 0 0 0 -3.274 0z"></path><path d="M12 16h.01"></path></svg>
                </div>
                <div>
                    <h4 class="alert-title">Suchfunktion in Entwicklung!</h4>
                <div class="text-secondary">Bitte haben Sie Verständnis für eventuell auftretende Unannehmlichkeiten.
                </div>
            </div>
        </div>
        </div>



        <div class="card mb-3">
            <div class="card-header">
                <h3 class="card-title">Suchergebnisse</h3>
            </div>
            <div class="card-body">
                <div class="list-group list-group-flush">

                    <?php // Fetch and display the paged search results
                    while ($row = $pagedResult->fetch_assoc()) {
                        $curButcher = new Butcher($row["id"], $row["lat"], $row["lon"], $row["tags"]);
                        $drawDash = (!empty($curButcher->getOpeningStateHTML() && !empty($curButcher->address)));
                        if($drawDash) {$drawDash=" - ";}
                        echo '
                        <div class="list-group-item">
                            <div class="row">
                                <div class="col-auto">
                                    <a href="butcher.php?id='.$curButcher->getId().'">
                                        <span class="avatar">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-meat" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M13.62 8.382l1.966 -1.967a2 2 0 1 1 3.414 -1.415a2 2 0 1 1 -1.413 3.414l-1.82 1.821" /><path d="M5.904 18.596c2.733 2.734 5.9 4 7.07 2.829c1.172 -1.172 -.094 -4.338 -2.828 -7.071c-2.733 -2.734 -5.9 -4 -7.07 -2.829c-1.172 1.172 .094 4.338 2.828 7.071z" /><path d="M7.5 16l1 1" /><path d="M12.975 21.425c3.905 -3.906 4.855 -9.288 2.121 -12.021c-2.733 -2.734 -8.115 -1.784 -12.02 2.121" /></svg>
                                        </span>
                                    </a>
                                </div>
                                <div class="col text-truncate">
                                    <a href="butcher.php?id='.$curButcher->getId().'" class="text-body d-block">'. $curButcher->getName() .'</a>
                                    <div class="text-secondary text-truncate mt-n1">'.$curButcher->getOpeningStateHTML(). $drawDash. $curButcher->address . '</div>
                                </div>
                            </div>
                        </div>
                        ';
                    }
                    ?> 
                </div>
            </div>
            <div class="card-footer">
                <div class="d-flex align-items-center">
                    <ul class="pagination">
                        <?php
                        # Determinate if first and last icons should be shown
                        $disableFirst = $disableLast = "";
                        if ($currentPage < 4) {$disableFirst="disabled";}
                        if ($currentPage + 3 < $totalPages) {$disableLast="disabled";}
                        # back
                        $backPage = $currentPage-1;
                        echo '
                            <li class="page-item'.$disableFirst.'">
                                <a class="page-link" href="search.php?q='.$searchString.'&page='.$backPage.'" tabindex="-1" aria-disabled="true">
                                    <!-- Download SVG icon from http://tabler-icons.io/i/chevron-left -->
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M15 6l-6 6l6 6"></path></svg>
                                </a>
                            </li>
                        ';
                        # In between
                        for ($i = max(1, $currentPage - 3); $i <= min($totalPages, $currentPage + 3); $i++) {
                            $active = "";
                            if ($i == $currentPage) {$active = "active";} 
                            echo '<li class="page-item '.$active.'">
                                <a class="page-link" href="search.php?q='.$searchString.'&page='.$i.'" tabindex="-1" aria-disabled="true">
                                    '.$i.'    
                                </a>
                            </li>';
                        }
                        # next
                        $nextPage = $currentPage+1;
                        echo '
                            <li class="page-item'.$disableLast.'">
                                <a class="page-link" href="search.php?q='.$searchString.'&page='.$nextPage.'" tabindex="-1" aria-disabled="true">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M9 6l6 6l-6 6"></path></svg>
                                </a>
                            </li>
                        ';
                        } ?>
                    </ul>
                </div>
            </div>
        </div>
<?php
} 
if(!isset($_GET['q'])) : ?>

    <div class="page-header d-print-none">
    <div class="row g-2 align-items-center">
        <div class="col">
            <h2 class="page-title">
                Erweiterte Metzgereisuche
            </h2>
        </div>
    </div>
    </div>
    <div class="page-body"> 
        <?php require "components/butcherSearchForm.php"; ?>
    </div>

<?php endif;

require "components/footer.php";
?>