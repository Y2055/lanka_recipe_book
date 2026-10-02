<?php
$page = 'recipes'; $title = 'Recipes - Lanka Recipe Book';
require 'includes/header.php';
$res = $conn->query("SELECT * FROM recipes ORDER BY title");
?>
<main class="container py-5">
  <h1 class="mb-4">Recipes</h1>
  <div class="row g-3 mb-4">
    <div class="col-md-8"><input id="searchBox" class="form-control" placeholder="Search recipes, e.g. sambol"></div>
    <div class="col-md-4">
      <select id="categoryFilter" class="form-select">
        <option value="">All categories</option>
        <option>Rice</option><option>Curry</option><option>Snacks</option><option>Sweets</option><option>Drinks</option>
      </select>
    </div>
  </div>
  <div class="row g-4" id="recipeGrid"><?php while ($r = $res->fetch_assoc()) recipe_card($r); ?></div>
  <p id="noResults" class="text-muted d-none mt-4">No recipes match your search. Try another word or category.</p>
</main>
<?php require 'includes/footer.php'; ?>
