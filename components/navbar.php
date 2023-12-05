<?php
    
    $navitems = array(
        "/" => "Home",
        "/search_form.php" => "Suche",
        "/blog.php" => "Blog",
        "https://about.leberkasrechner.de" => "Über"
    );
    $searchBoxProp = "";
    if(!empty($_GET["q"])) {
      $searchBoxProp = $_GET["q"];
    }

    // Dark & Light theme links
    $url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    $url_theme_dark = $url_theme_light = $url;
    $url = preg_replace('/([&?]theme=[^&]*)|([&?])/', '$2', $url);
    if (strpos($url, 'theme=light') === false) {
        $url_theme_light = $url . (parse_url($url, PHP_URL_QUERY) ? '&' : '?') . 'theme=light';
    } 
    if(strpos($url, 'theme=dark') === false) {
        $url_theme_dark = $url . (parse_url($url, PHP_URL_QUERY) ? '&' : '?') . 'theme=dark';
    }

?>


</div>
<nav class="navbar navbar-expand-md d-print-none mb-3">
  <div class="container-xl">
      <a class="navbar-brand" href="/">leberkasrechner.de</a> 
      
        <a href=".">
          <h1 class="navbar-brand navbar-brand-autodark d-none-navbar-horizontal pe-0 pe-md-3"></h1>
        </a>
    
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-menu" aria-controls="navbar-menu" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div id="navbar-menu" class="collapse navbar-collapse">
      <div class="d-flex flex-column flex-md-row flex-fill align-items-stretch align-items-md-center">
        <ul class="navbar-nav">
          <?php
            if(!isset($navbar_highlighted)) {$navbar_highlighted = " ";}
            foreach($navitems as $url => $label) {
              $isActive = ($navbar_highlighted === $label) ? 'active' : '';
              echo "<li class=\"nav-item $isActive\"><a class=\"nav-link $isActive\" href=$url>$label</a></li>";
            }
          ?>
        </ul>
      </div>
    </div>
    <div class="me-3">
        <a href="<?=$url_theme_dark?>" class="nav-link px-0 hide-theme-dark" title="Enable dark mode" data-bs-toggle="tooltip"
            data-bs-placement="bottom">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 3c.132 0 .263 0 .393 0a7.5 7.5 0 0 0 7.92 12.446a9 9 0 1 1 -8.313 -12.454z" /></svg>
        </a>
        <a href="<?=$url_theme_light?>" class="nav-link px-0 hide-theme-light" title="Enable light mode" data-bs-toggle="tooltip"
            data-bs-placement="bottom">
            <!-- Download SVG icon from http://tabler-icons.io/i/sun -->
            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 12m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0" /><path d="M3 12h1m8 -9v1m8 8h1m-9 8v1m-6.4 -15.4l.7 .7m12.1 -.7l-.7 .7m0 11.4l.7 .7m-12.1 -.7l-.7 .7" /></svg>
        </a>
    </div>
    <div class="col-2 d-none d-xxl-block">
        <div class="my-2 my-md-0 flex-grow-1 flex-md-grow-0 order-first order-md-last">
            <form role="search" action="/search.php" method="get" autocomplete="off" novalidate="">
                <div class="input-icon">
                    <span class="input-icon-addon">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0"></path><path d="M21 21l-6 -6"></path></svg>
                    </span>
                    <input type="text" name="q" value="<?=$searchBoxProp?>" class="form-control" placeholder="Metzgerei suchen..." aria-label="Metzgereien suchen">
                </div>
            </form>
        </div>
    </div>
  </div>
</nav>
<div class="container-xl">
