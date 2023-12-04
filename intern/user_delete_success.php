<?php require "../components/head.php"; ?>


<div class="modal modal-blur fade show" id="modal-success" tabindex="-1" role="dialog" style="display: block;" aria-modal="true">
      <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-status bg-success"></div>
          <div class="modal-body text-center py-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon mb-2 text-green icon-lg" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0"></path><path d="M9 12l2 2l4 -4"></path></svg>
            <h3>Account erfolgreich gelöscht</h3>
            <div class="text-secondary">Der Account wurde erfolgreich gelöscht. Diese Aktion kann nicht rückgängig gemacht werden.</div>
          </div>
          <div class="modal-footer">
            <div class="w-100">
              <div class="row">
                <div class="col"><a href="users.php" class="btn btn-success w-100">
                    Zurück zur Benutzerübersicht
                  </a></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>