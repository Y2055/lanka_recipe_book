<footer class="site-footer text-center py-4 mt-5">
  <div class="container">&copy; <?= date('Y') ?> Lanka Recipe Book &middot; ICT 1209 Mini Project</div>
</footer>
<!-- Recipe details modal -->
<div class="modal fade" id="recipeModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable"><div class="modal-content">
    <div class="modal-header"><h2 class="modal-title h4" id="rmTitle"></h2><button class="btn-close" data-bs-dismiss="modal"></button></div>
    <div class="modal-body">
      <h3 class="h6">Ingredients</h3><ul id="rmIngredients"></ul>
      <h3 class="h6">Method</h3><ol id="rmSteps"></ol>
    </div>
  </div></div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?= $base ?>js/main.js"></script>
</body></html>
