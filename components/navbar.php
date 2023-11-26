<?php
    
    $navitems = array(
        "/leberkasrechner/" => "Home",
        "https://about.leberkasrechner.de" => "Über"
    );

?>


</div>
<nav class="navbar navbar-expand-md d-print-none">
  <div class="container-xl">
      <a class="navbar-brand" href="/">leberkasrechner.de</a> 
      
        <a href=".">
          <h1 class="navbar-brand navbar-brand-autodark d-none-navbar-horizontal pe-0 pe-md-3">
        </a>
      </h1>
    
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarColor01" aria-controls="navbarColor01" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div id="navbar menu" class="collapse navbar-collapse">
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
  </div>
</nav>
<div class="container-xl">