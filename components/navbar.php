<?php
    
    $navitems = array(
        "/" => "Home",
        "https://about.leberkasrechner.de" => "Über"
    );

?>


</div>
<nav class="navbar navbar-expand-md d-print-none mb-3">
  <div class="container-xl">
      <a class="navbar-brand" href="/">leberkasrechner.de</a> 
      
        <a href=".">
          <h1 class="navbar-brand navbar-brand-autodark d-none-navbar-horizontal pe-0 pe-md-3"></h1>
        </a>
    
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
    <div class="col-2 d-none d-xxl-block">
                  <div class="my-2 my-md-0 flex-grow-1 flex-md-grow-0 order-first order-md-last">
                    <form action="./" method="get" autocomplete="off" novalidate="">
                      <div class="input-icon">
                        <span class="input-icon-addon">
                          <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0"></path><path d="M21 21l-6 -6"></path></svg>
                        </span>
                        <form 
                        <input type="text" value="" class="form-control" placeholder="Metzgerei suchen..." aria-label="Search in website">
                      </div>
                    </form>
                  </div>
                </div>
  </div>
</nav>
<div class="container-xl">