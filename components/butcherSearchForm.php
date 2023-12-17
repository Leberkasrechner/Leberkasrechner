<form role="search" action="/search.php" method="get" autocomplete="off" novalidate="">
    <div class="row mt-3 row-cols-1 row-cols-sm-1 row-cols-md-1 row-cols-lg-2 row-cols-xl-2">
        <div class="col mt-2">
            <div class="mt-3">
                <div class="form-label">Einfache Suche</div>
                <div class="input-icon">
                    <div class="input-group">
                        <input type="text" class="form-control" name="q" placeholder="Suchbegriff...">
                        <button class="btn" type="submit">Suche</button>
                    </div>
                    <span class="input-icon-addon">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0"></path><path d="M21 21l-6 -6"></path></svg>
                    </span>
                </div>
                <div class="hr-text">ODER</div>
                <div class="form-label mb-0">Erweiterte Suche</div>
                <div class="mb-3">
                    <small class="text-secondary">
                        Nur ausgefüllte Felder werden verarbeitet.
                    </small>
                </div>
                <div class="input-group">
                    <span class="input-group-text">
                        <strong>Adresse</strong>&nbsp;enthält
                    </span>
                    <input type="text" class="form-control" aria-label="Text input with dropdown button">
                </div>
                <div class="input-group mt-3">
                    <span class="input-group-text">
                        <strong>Name</strong>&nbsp;enthält &nbsp;&nbsp;&nbsp;
                    </span>
                    <input type="text" class="form-control" aria-label="Text input with dropdown button">
                </div>
            </div>
        </div>
        <div class="col mt-2">
            <div class="mt-3">
                <div class="form-label">Anzuzeigende Daten</div>
                <label class="form-check">
                    <input class="form-check-input" type="checkbox" checked="">
                    <span class="form-check-label">
                        Fehlende Daten anzeigen
                    </span>
                    <span class="form-check-description">
                        Zeige mir, wenn Daten fehlen (OpenStreetMap oder  leberkasrechner.de-Bilddatenbank), 
                        um die Datenqualität dieses Projektes und der OpenStreetMap zu verbessern.
                    </span>
                </label>
                <label class="form-check">
                    <input class="form-check-input" type="checkbox" checked="">
                    <span class="form-check-label">
                        Detailliertes Angebot anzeigen
                    </span>
                    <span class="form-check-description">
                        Wenn aktiviert, werden (falls verfügbar) genauere Daten über das Angebot dieses
                        Metzgers gezeigt, z.B. ob vegetarische/vegane oder halale Lebensmittel angeboten werden,
                        aber auch ob die Metzgerei Catering- oder Partyservice anbietet.
                    </span>
                </label>
            </div>
        </div>
    </div>
</form>