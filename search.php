<?php
require "components/head.php";
require "components/navbar.php";
require "components/conn.php";
require "components/butcher.php";


if (isset($_GET['q'])) {
    //SQL-Abfrage
    $searchString = $_GET['q'];
    $currentPage = 1;
    if(!empty($_GET["page"])) {$currentPage = $_GET["page"];}
    $sql = "SELECT * FROM butchers WHERE MATCH (tags) AGAINST ('" . mysqli_real_escape_string($conn, $searchString) . "')";
    $result = $conn->query($sql);

    if ($result->num_rows === 0) {
        echo "Keine Ergebnisse gefunden. Bitte mit anderem Suchbegriff erneut versuchen";
        //TODO: erweitern
    } else {
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



        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Suchergebnisse</h3>
            </div>
            <div class="card-body">
            <div class="list-group list-group-flush overflow-auto" style="max-height: 35rem">

        <?php // Fetch and display the paged search results
        while ($row = $pagedResult->fetch_assoc()) {
            $curButcher = new Butcher($row["id"], $row["lat"], $row["lon"], $row["tags"]);
            echo '
            <div class="list-group-item">
                <div class="row">
                    <div class="col-auto">
                        <a href="#">
                            <span class="avatar" style="background-image: url(./static/avatars/066m.jpg)"></span>
                        </a>
                    </div>
                    <div class="col text-truncate">
                        <a href="butcher.php?id='.$curButcher->getId().'" class="text-body d-block">'. $curButcher->getName() .'</a>
                        <div class="text-secondary text-truncate mt-n1">'. $curButcher->address . '</div>
                    </div>
                </div>
            </div>
            ';
        }
        ?> 
        </div>
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
            }
        echo "</ul></div>";
} else {
    // Handle the case when no search string is given
    echo "Error: No search string provided.";
}
?>