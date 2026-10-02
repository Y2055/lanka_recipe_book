</div><!-- /.site-main-wrapper -->

<!-- Modern Site Footer -->
<footer class="site-footer mt-5 pt-5 pb-4">
  <div class="container">
    <div class="row g-4 justify-content-between mb-4">
      <div class="col-lg-4 col-md-6">
        <div class="footer-brand d-flex align-items-center gap-2 mb-3">
          <div class="brand-logo-badge sm">
            <i class="bi bi-fire"></i>
          </div>
          <span class="brand-name h5 mb-0 text-white">Lanka<span class="brand-highlight">Recipes</span></span>
        </div>
        <p class="footer-desc text-muted mb-3">
          Preserving and celebrating the deep culinary roots of Sri Lanka. From sun-kissed coastal seafood curries to highland spiced tea and traditional village clay-pot feasts.
        </p>
        <div class="footer-socials d-flex gap-2">
          <a href="#" class="social-link" title="Instagram" aria-label="Instagram"><i class="bi bi-instagram"></i></a>
          <a href="#" class="social-link" title="YouTube" aria-label="YouTube"><i class="bi bi-youtube"></i></a>
          <a href="#" class="social-link" title="Facebook" aria-label="Facebook"><i class="bi bi-facebook"></i></a>
          <a href="https://github.com/Y2055/lanka_recipe_book" target="_blank" rel="noopener" class="social-link" title="GitHub" aria-label="GitHub"><i class="bi bi-github"></i></a>
        </div>
      </div>

      <div class="col-lg-2 col-md-3 col-6">
        <h3 class="footer-heading h6 text-white text-uppercase tracking-wider mb-3">Explore</h3>
        <ul class="list-unstyled footer-links mb-0">
          <li><a href="<?= $base ?>index.php">Home</a></li>
          <li><a href="<?= $base ?>recipes.php">All Recipes</a></li>
          <li><a href="<?= $base ?>recipes.php?cat=Curry">Ceylon Curries</a></li>
          <li><a href="<?= $base ?>recipes.php?cat=Rice">Rice & Kiribath</a></li>
          <li><a href="<?= $base ?>recipes.php?cat=Sweets">Sweet Delicacies</a></li>
        </ul>
      </div>

      <div class="col-lg-2 col-md-3 col-6">
        <h3 class="footer-heading h6 text-white text-uppercase tracking-wider mb-3">Community</h3>
        <ul class="list-unstyled footer-links mb-0">
          <?php if (is_logged_in()): ?>
            <li><a href="<?= $base ?>dashboard.php">Chef Dashboard</a></li>
            <li><a href="<?= $base ?>dashboard.php#addFormSection">Post Recipe</a></li>
          <?php else: ?>
            <li><a href="<?= $base ?>auth/login.php">Sign In</a></li>
            <li><a href="<?= $base ?>auth/register.php">Create Free Account</a></li>
          <?php endif; ?>
          <li><a href="<?= $base ?>contact.php">Contact & Support</a></li>
          <li><a href="<?= $base ?>contact.php#faqSection">Cooking FAQs</a></li>
        </ul>
      </div>

      <div class="col-lg-3 col-md-6">
        <div class="footer-newsletter-card p-3 rounded-3">
          <h3 class="h6 text-white mb-2"><i class="bi bi-patch-check-fill text-warning me-1"></i> ICT 1209 Mini Project</h3>
          <p class="small text-muted mb-2">Designed with modern responsive HTML5, Vanilla CSS, JS, PHP & MySQL with zero external CSS frameworks beyond Bootstrap.</p>
          <div class="badge bg-emerald-subtle text-emerald px-2 py-1 small">v2.0 Modern Edition</div>
        </div>
      </div>
    </div>

    <div class="footer-bottom pt-4 border-top border-secondary-subtle d-flex flex-column flex-md-row align-items-center justify-content-between gap-2">
      <p class="small text-muted mb-0">
        &copy; <?= date('Y') ?> Lanka Recipe Book &middot; Crafted with passion for Sri Lankan gastronomy.
      </p>
      <div class="small text-muted">
        <span class="text-emerald fw-semibold">100% Homemade</span> &middot; Traditional Spice Formulations
      </div>
    </div>
  </div>
</footer>

<!-- Interactive Modern Recipe Detail Modal -->
<div class="modal fade" id="recipeModal" tabindex="-1" aria-labelledby="rmTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
    <div class="modal-content recipe-modal-content border-0 shadow-lg">
      
      <!-- Modal Header Banner -->
      <div class="modal-header-hero position-relative">
        <img id="rmImage" src="" alt="Recipe photo" class="modal-hero-img w-100">
        <div class="modal-hero-overlay"></div>
        <button type="button" class="btn-close btn-close-white modal-close-btn" data-bs-dismiss="modal" aria-label="Close"></button>
        
        <div class="modal-hero-meta">
          <span class="badge badge-cat-pill mb-2" id="rmCategory"></span>
          <h2 class="modal-title text-white h3 fw-bold mb-1" id="rmTitle"></h2>
          <p class="text-white-50 small mb-0" id="rmDescription"></p>
        </div>
      </div>

      <div class="modal-body p-4">
        <!-- Quick Info Ribbon -->
        <div class="recipe-ribbon d-flex flex-wrap gap-2 justify-content-between align-items-center p-3 mb-4 rounded-3">
          <div class="d-flex align-items-center gap-2">
            <div class="ribbon-icon"><i class="bi bi-clock-history"></i></div>
            <div>
              <div class="text-muted small">Prep Time</div>
              <div class="fw-bold small" id="rmPrep">15 mins</div>
            </div>
          </div>
          <div class="d-flex align-items-center gap-2">
            <div class="ribbon-icon"><i class="bi bi-fire"></i></div>
            <div>
              <div class="text-muted small">Cook Time</div>
              <div class="fw-bold small" id="rmCook">25 mins</div>
            </div>
          </div>
          <div class="d-flex align-items-center gap-2">
            <div class="ribbon-icon"><i class="bi bi-bar-chart"></i></div>
            <div>
              <div class="text-muted small">Difficulty</div>
              <div class="fw-bold small" id="rmDifficulty">Easy</div>
            </div>
          </div>
          <div class="d-flex align-items-center gap-2">
            <div class="ribbon-icon"><i class="bi bi-capslock"></i></div>
            <div>
              <div class="text-muted small">Spice Level</div>
              <div class="fw-bold small" id="rmSpice">Medium</div>
            </div>
          </div>
        </div>

        <div class="row g-4">
          <!-- Ingredients Column -->
          <div class="col-lg-5">
            <div class="d-flex align-items-center justify-content-between mb-3">
              <h3 class="h5 fw-bold mb-0 text-emerald">
                <i class="bi bi-basket me-2"></i>Ingredients
              </h3>
              <!-- Portion Scaler -->
              <div class="serving-scaler d-inline-flex align-items-center gap-1 border rounded-pill px-2 py-1">
                <button type="button" class="btn btn-sm btn-scaler" id="scalerMinus" title="Decrease Servings">-</button>
                <span class="small fw-semibold px-1" id="scalerValue">4</span>
                <span class="small text-muted me-1">srv</span>
                <button type="button" class="btn btn-sm btn-scaler" id="scalerPlus" title="Increase Servings">+</button>
              </div>
            </div>
            <p class="small text-muted mb-2"><i class="bi bi-info-circle me-1"></i>Tap to check off ingredients as you prepare.</p>
            <ul class="list-unstyled ingredients-checklist mb-4" id="rmIngredients"></ul>

            <!-- Kitchen Cooking Timer Widget -->
            <div class="cooking-timer-box p-3 rounded-3 mb-3">
              <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="small fw-bold text-emerald"><i class="bi bi-stopwatch me-1"></i>Kitchen Timer</span>
                <span class="timer-display fw-mono fw-bold text-emerald" id="timerDisplay">10:00</span>
              </div>
              <div class="d-flex gap-1 mb-2">
                <button type="button" class="btn btn-outline-secondary btn-sm timer-preset py-0 px-2" data-min="3">3m</button>
                <button type="button" class="btn btn-outline-secondary btn-sm timer-preset py-0 px-2" data-min="5">5m</button>
                <button type="button" class="btn btn-outline-secondary btn-sm timer-preset py-0 px-2 active" data-min="10">10m</button>
                <button type="button" class="btn btn-outline-secondary btn-sm timer-preset py-0 px-2" data-min="20">20m</button>
              </div>
              <div class="d-flex gap-2">
                <button type="button" class="btn btn-emerald btn-sm flex-grow-1" id="timerStartBtn">
                  <i class="bi bi-play-fill me-1"></i>Start Timer
                </button>
                <button type="button" class="btn btn-outline-secondary btn-sm" id="timerResetBtn">
                  <i class="bi bi-arrow-counterclockwise"></i>
                </button>
              </div>
            </div>
          </div>

          <!-- Instructions Column -->
          <div class="col-lg-7">
            <h3 class="h5 fw-bold mb-3 text-emerald">
              <i class="bi bi-journal-text me-2"></i>Cooking Method
            </h3>
            <p class="small text-muted mb-2"><i class="bi bi-check2-circle me-1"></i>Follow along and check completed steps.</p>
            <ol class="steps-interactive-list list-unstyled ps-0" id="rmSteps"></ol>

            <div class="chef-note-box p-3 rounded-3 mt-4">
              <h4 class="h6 fw-bold mb-1 text-emerald"><i class="bi bi-lightbulb me-1"></i>Ceylon Kitchen Tip</h4>
              <p class="small text-muted mb-0">For best flavour, use authentic clay pots (chattie) and freshly extracted coconut milk. Avoid high flames when adding coconut milk to prevent curdling.</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Modal Footer Actions -->
      <div class="modal-footer d-flex justify-content-between align-items-center bg-body-tertiary px-4 py-3">
        <div class="d-flex gap-2">
          <button type="button" class="btn btn-outline-secondary btn-sm" id="btnPrintRecipe">
            <i class="bi bi-printer me-1"></i> Print
          </button>
          <button type="button" class="btn btn-outline-secondary btn-sm" id="btnShareRecipe">
            <i class="bi bi-share me-1"></i> Share
          </button>
        </div>
        <button type="button" class="btn btn-emerald" data-bs-dismiss="modal">Done Cooking</button>
      </div>

    </div>
  </div>
</div>

<!-- Floating Toast Container for Alerts & Notifications -->
<div class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index: 1090">
  <div id="appToast" class="toast align-items-center text-bg-dark border-0 shadow-lg" role="alert" aria-live="assertive" aria-atomic="true">
    <div class="d-flex">
      <div class="toast-body d-flex align-items-center gap-2">
        <i class="bi bi-check-circle-fill text-success fs-5 toast-icon"></i>
        <span id="toastMessage">Action completed successfully.</span>
      </div>
      <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
    </div>
  </div>
</div>

<!-- Bootstrap 5.3.3 Bundle JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- Main Interactive Application JS -->
<script src="<?= $base ?>js/main.js"></script>
</body>
</html>
