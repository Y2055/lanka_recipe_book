<?php
$page = 'recipes';
$title = 'All Recipes - Lanka Recipe Book';
require 'includes/header.php';

// Initial query to fetch all recipes joined with user if present
$query = "SELECT r.*, u.username 
          FROM recipes r 
          LEFT JOIN users u ON r.user_id = u.id 
          ORDER BY r.title ASC";
$res = $conn->query($query);
$initialQuery = isset($_GET['q']) ? clean($_GET['q']) : '';
$initialCat = isset($_GET['cat']) ? clean($_GET['cat']) : '';
?>

<main class="container py-5">
  <!-- Page Header -->
  <div class="mb-4">
    <span class="badge bg-emerald-subtle text-emerald px-3 py-1 mb-2">Recipe Collection</span>
    <h1 class="display-6 fw-bold mb-2">Authentic Sri Lankan Dishes</h1>
    <p class="text-muted">Browse our traditional recipes, filter by category or difficulty, and save your favourites for later cooking.</p>
  </div>

  <!-- Modern Interactive Filter Card -->
  <div class="filter-card mb-4">
    <!-- Top Row: Search input & quick toggles -->
    <div class="row g-3 align-items-center mb-3">
      <div class="col-lg-6">
        <div class="input-group">
          <span class="input-group-text bg-transparent border-end-0 text-muted"><i class="bi bi-search"></i></span>
          <input type="text" id="searchBox" class="form-control border-start-0 ps-0" placeholder="Search by name, ingredient, or spice..." value="<?= $initialQuery ?>">
        </div>
      </div>
      <div class="col-sm-6 col-lg-3">
        <select id="difficultyFilter" class="form-select">
          <option value="">All Difficulties</option>
          <option value="easy">Easy (Beginner)</option>
          <option value="medium">Medium</option>
          <option value="hard">Advanced</option>
        </select>
      </div>
      <div class="col-sm-6 col-lg-3">
        <select id="sortSelect" class="form-select">
          <option value="default">Sort: Default</option>
          <option value="title-asc">Title: A to Z</option>
          <option value="title-desc">Title: Z to A</option>
          <option value="cook-asc">Cook Time: Fastest first</option>
        </select>
      </div>
    </div>

    <!-- Bottom Row: Category Pills & Favorites Filter Button -->
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 pt-2 border-top border-light-subtle">
      <div class="filter-category-pills">
        <button type="button" class="filter-pill-btn <?= empty($initialCat)?'active':'' ?>" data-category="">All</button>
        <button type="button" class="filter-pill-btn <?= strtolower($initialCat)==='rice'?'active':'' ?>" data-category="Rice">🍚 Rice & Kiribath</button>
        <button type="button" class="filter-pill-btn <?= strtolower($initialCat)==='curry'?'active':'' ?>" data-category="Curry">🍛 Curries</button>
        <button type="button" class="filter-pill-btn <?= strtolower($initialCat)==='snacks'?'active':'' ?>" data-category="Snacks">🥥 Snacks & Sambol</button>
        <button type="button" class="filter-pill-btn <?= strtolower($initialCat)==='sweets'?'active':'' ?>" data-category="Sweets">🍯 Sweets</button>
        <button type="button" class="filter-pill-btn <?= strtolower($initialCat)==='drinks'?'active':'' ?>" data-category="Drinks">🥤 Drinks</button>
      </div>

      <div class="d-flex align-items-center gap-3">
        <button type="button" class="btn btn-outline-danger btn-sm rounded-pill px-3" id="favFilterBtn">
          <i class="bi bi-heart-fill me-1"></i>
          <span>Favorites</span>
          <span class="badge bg-danger text-white rounded-pill ms-1 fav-count-badge">0</span>
        </button>
        <span class="text-muted small fw-semibold" id="recipeCount">Showing recipes</span>
      </div>
    </div>
  </div>

  <!-- Recipe Grid -->
  <div class="row g-4" id="recipeGrid">
    <?php 
    if ($res && $res->num_rows > 0) {
      while ($r = $res->fetch_assoc()) {
        recipe_card($r);
      }
    } else {
      echo '<div class="col-12"><p class="text-muted">No recipes found in database.</p></div>';
    }
    ?>
  </div>

  <!-- Empty State when filter yields 0 matches -->
  <div id="noResults" class="text-center py-5 d-none">
    <div class="rounded-circle bg-body-tertiary mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 72px; height: 72px; font-size: 2rem;">
      🔍
    </div>
    <h3 class="h5 fw-bold mb-2">No Matching Recipes Found</h3>
    <p class="text-muted small mb-3">Try adjusting your search terms or clearing your category filters.</p>
    <button type="button" class="btn btn-outline-success btn-sm rounded-pill px-4" onclick="document.getElementById('searchBox').value=''; document.querySelector('.filter-pill-btn[data-category=\'\']').click(); document.getElementById('difficultyFilter').value=''; document.getElementById('sortSelect').value='default';">
      Reset All Filters
    </button>
  </div>
</main>

<?php require 'includes/footer.php'; ?>
