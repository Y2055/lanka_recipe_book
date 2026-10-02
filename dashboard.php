<?php
$title = 'Chef Dashboard - Lanka Recipe Book';
require_once 'includes/functions.php';
require_login();

$uid = $_SESSION['user_id'];
$username = $_SESSION['username'] ?? 'Chef';
$msg = '';
$err = '';
$editingRecipe = null;

// Handle Edit Mode Request
if (isset($_GET['edit'])) {
    $editId = (int)$_GET['edit'];
    $editSt = $conn->prepare("SELECT * FROM recipes WHERE id=? AND user_id=?");
    $editSt->bind_param('ii', $editId, $uid);
    $editSt->execute();
    $editingRecipe = $editSt->get_result()->fetch_assoc();
}

// Handle Delete with CSRF
if (isset($_POST['action']) && $_POST['action'] === 'delete') {
    if (!verify_csrf($_POST['csrf_token'] ?? '')) {
        $err = 'Invalid security token. Please try again.';
    } else {
        $id = (int)($_POST['recipe_id'] ?? 0);
        $st = $conn->prepare("DELETE FROM recipes WHERE id=? AND user_id=?");
        $st->bind_param('ii', $id, $uid);
        $st->execute();
        header('Location: dashboard.php?deleted=1');
        exit;
    }
}

// Handle Add or Update Recipe
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_recipe'])) {
    if (!verify_csrf($_POST['csrf_token'] ?? '')) {
        $err = 'Invalid security token. Please refresh and try again.';
    } else {
        $recipeId = (int)($_POST['recipe_id'] ?? 0);
        $titleVal = trim($_POST['title'] ?? '');
        $catVal = trim($_POST['category'] ?? '');
        $descVal = trim($_POST['description'] ?? '');
        $prepVal = trim($_POST['prep_time'] ?? '15 mins');
        $cookVal = trim($_POST['cook_time'] ?? '25 mins');
        $servVal = trim($_POST['servings'] ?? '4 servings');
        $diffVal = trim($_POST['difficulty'] ?? 'Easy');
        $spiceVal = trim($_POST['spice_level'] ?? 'Medium');
        $imgVal = trim($_POST['image_url'] ?? '');
        $ingVal = trim($_POST['ingredients'] ?? '');
        $instVal = trim($_POST['instructions'] ?? '');

        if ($titleVal === '' || $catVal === '' || $ingVal === '' || $instVal === '') {
            $err = 'Please complete the title, category, ingredients, and cooking steps.';
        } else {
            if ($recipeId > 0) {
                // Update Existing Recipe
                $upSt = $conn->prepare("UPDATE recipes SET title=?, category=?, description=?, prep_time=?, cook_time=?, servings=?, difficulty=?, spice_level=?, image_url=?, ingredients=?, instructions=? WHERE id=? AND user_id=?");
                $upSt->bind_param('ssssssssssiii', $titleVal, $catVal, $descVal, $prepVal, $cookVal, $servVal, $diffVal, $spiceVal, $imgVal, $ingVal, $instVal, $recipeId, $uid);
                $upSt->execute();
                header('Location: dashboard.php?updated=1');
                exit;
            } else {
                // Insert New Recipe
                $inSt = $conn->prepare("INSERT INTO recipes (title, category, description, prep_time, cook_time, servings, difficulty, spice_level, image_url, ingredients, instructions, user_id) VALUES (?,?,?,?,?,?,?,?,?,?,?,?)");
                $inSt->bind_param('sssssssssssi', $titleVal, $catVal, $descVal, $prepVal, $cookVal, $servVal, $diffVal, $spiceVal, $imgVal, $ingVal, $instVal, $uid);
                $inSt->execute();
                header('Location: dashboard.php?added=1');
                exit;
            }
        }
    }
}

if (isset($_GET['added'])) $msg = 'Recipe created and published to the public collection!';
if (isset($_GET['updated'])) $msg = 'Recipe successfully updated!';
if (isset($_GET['deleted'])) $msg = 'Recipe has been deleted.';

// Fetch User's Recipes
$mineSt = $conn->prepare("SELECT * FROM recipes WHERE user_id=? ORDER BY created_at DESC");
$mineSt->bind_param('i', $uid);
$mineSt->execute();
$mine = $mineSt->get_result();

// Count total recipes in system
$totalQuery = $conn->query("SELECT COUNT(*) as count FROM recipes");
$totalCount = $totalQuery ? $totalQuery->fetch_assoc()['count'] : 0;

require 'includes/header.php';
?>

<main class="container py-5">
  <!-- Top Welcome Banner -->
  <div class="d-flex flex-column flex-md-row md-align-items-center justify-content-between gap-3 mb-4">
    <div>
      <span class="badge bg-emerald-subtle text-emerald px-3 py-1 mb-2">Chef Portal</span>
      <h1 class="h2 fw-bold mb-1">Welcome back, <?= clean($username) ?>!</h1>
      <p class="text-muted mb-0">Manage your shared Sri Lankan recipes, create new dishes, and inspire food lovers.</p>
    </div>
    <div class="d-flex gap-2">
      <a href="#addFormSection" class="btn btn-emerald">
        <i class="bi bi-plus-lg me-1"></i> Add Recipe
      </a>
      <a href="recipes.php" class="btn btn-outline-secondary">
        <i class="bi bi-eye me-1"></i> View Public Book
      </a>
    </div>
  </div>

  <!-- Status Banners -->
  <?php if ($msg): ?>
    <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2" role="alert">
      <i class="bi bi-check-circle-fill fs-5"></i>
      <div><?= clean($msg) ?></div>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  <?php endif; ?>

  <?php if ($err): ?>
    <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center gap-2" role="alert">
      <i class="bi bi-exclamation-triangle-fill fs-5"></i>
      <div><?= clean($err) ?></div>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  <?php endif; ?>

  <!-- Statistics Cards Grid -->
  <div class="row g-3 mb-5">
    <div class="col-sm-6 col-lg-3">
      <div class="dash-stat-card">
        <div class="dash-stat-icon bg-emerald-subtle text-emerald">
          <i class="bi bi-journal-check"></i>
        </div>
        <div>
          <div class="text-muted small">My Recipes</div>
          <div class="h3 fw-bold mb-0"><?= $mine->num_rows ?></div>
        </div>
      </div>
    </div>
    <div class="col-sm-6 col-lg-3">
      <div class="dash-stat-card">
        <div class="dash-stat-icon bg-warning-subtle text-warning-emphasis">
          <i class="bi bi-book"></i>
        </div>
        <div>
          <div class="text-muted small">Total in Book</div>
          <div class="h3 fw-bold mb-0"><?= $totalCount ?></div>
        </div>
      </div>
    </div>
    <div class="col-sm-6 col-lg-3">
      <div class="dash-stat-card">
        <div class="dash-stat-icon bg-danger-subtle text-danger">
          <i class="bi bi-heart-fill"></i>
        </div>
        <div>
          <div class="text-muted small">Saved Favorites</div>
          <div class="h3 fw-bold mb-0 fav-count-badge">0</div>
        </div>
      </div>
    </div>
    <div class="col-sm-6 col-lg-3">
      <div class="dash-stat-card">
        <div class="dash-stat-icon bg-info-subtle text-info-emphasis">
          <i class="bi bi-award"></i>
        </div>
        <div>
          <div class="text-muted small">Chef Tier</div>
          <div class="h5 fw-bold mb-0 text-truncate">Ceylon Artisan</div>
        </div>
      </div>
    </div>
  </div>

  <div class="row g-5">
    <!-- Left Column: Add / Edit Recipe Form -->
    <div class="col-lg-7" id="addFormSection">
      <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5">
        <div class="d-flex align-items-center justify-content-between mb-4">
          <h2 class="h4 fw-bold mb-0">
            <i class="bi bi-<?= $editingRecipe ? 'pencil-square' : 'plus-circle' ?> text-emerald me-2"></i>
            <?= $editingRecipe ? 'Edit Recipe: ' . clean($editingRecipe['title']) : 'Post a New Sri Lankan Recipe' ?>
          </h2>
          <?php if ($editingRecipe): ?>
            <a href="dashboard.php" class="btn btn-outline-secondary btn-sm rounded-pill">Cancel Edit</a>
          <?php endif; ?>
        </div>

        <form method="post" class="needs-validation" novalidate>
          <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
          <input type="hidden" name="recipe_id" value="<?= $editingRecipe ? (int)$editingRecipe['id'] : 0 ?>">

          <!-- Recipe Title -->
          <div class="mb-3">
            <label class="form-label fw-semibold">Recipe Title <span class="text-danger">*</span></label>
            <input type="text" name="title" id="formRecipeTitle" class="form-control form-control-lg" placeholder="e.g. Jaffna Crab Curry or Seeni Sambol" value="<?= clean($editingRecipe['title'] ?? '') ?>" required maxlength="120">
            <div class="invalid-feedback">Please provide a descriptive recipe title.</div>
          </div>

          <!-- Category & Difficulty Row -->
          <div class="row g-3 mb-3">
            <div class="col-md-6">
              <label class="form-label fw-semibold">Category <span class="text-danger">*</span></label>
              <select name="category" id="formRecipeCategory" class="form-select" required>
                <option value="">Select Category...</option>
                <?php 
                $categories = ['Rice', 'Curry', 'Snacks', 'Sweets', 'Drinks'];
                $currentCat = $editingRecipe['category'] ?? '';
                foreach ($categories as $cat) {
                    $selected = ($currentCat === $cat) ? 'selected' : '';
                    echo "<option value=\"$cat\" $selected>$cat</option>";
                }
                ?>
              </select>
              <div class="invalid-feedback">Select a category.</div>
            </div>

            <div class="col-md-6">
              <label class="form-label fw-semibold">Difficulty</label>
              <select name="difficulty" class="form-select">
                <?php 
                $diffs = ['Easy', 'Medium', 'Hard'];
                $currDiff = $editingRecipe['difficulty'] ?? 'Easy';
                foreach ($diffs as $d) {
                    $selected = (strcasecmp($currDiff, $d) === 0) ? 'selected' : '';
                    echo "<option value=\"$d\" $selected>$d</option>";
                }
                ?>
              </select>
            </div>
          </div>

          <!-- Short Description -->
          <div class="mb-3">
            <label class="form-label fw-semibold">Brief Summary</label>
            <input type="text" name="description" class="form-control" placeholder="A one-sentence intro highlighting flavours and occasion" value="<?= clean($editingRecipe['description'] ?? '') ?>" maxlength="255">
          </div>

          <!-- Prep Time, Cook Time, Servings, Spice -->
          <div class="row g-3 mb-3">
            <div class="col-sm-6 col-md-3">
              <label class="form-label fw-semibold small">Prep Time</label>
              <input type="text" name="prep_time" class="form-control" placeholder="15 mins" value="<?= clean($editingRecipe['prep_time'] ?? '15 mins') ?>">
            </div>
            <div class="col-sm-6 col-md-3">
              <label class="form-label fw-semibold small">Cook Time</label>
              <input type="text" name="cook_time" id="formRecipeCookTime" class="form-control" placeholder="25 mins" value="<?= clean($editingRecipe['cook_time'] ?? '25 mins') ?>">
            </div>
            <div class="col-sm-6 col-md-3">
              <label class="form-label fw-semibold small">Servings</label>
              <input type="text" name="servings" class="form-control" placeholder="4 servings" value="<?= clean($editingRecipe['servings'] ?? '4 servings') ?>">
            </div>
            <div class="col-sm-6 col-md-3">
              <label class="form-label fw-semibold small">Spice Level</label>
              <select name="spice_level" class="form-select">
                <?php 
                $spices = ['Mild', 'Medium', 'Spicy', 'Extra Spicy'];
                $currSpice = $editingRecipe['spice_level'] ?? 'Medium';
                foreach ($spices as $s) {
                    $selected = (strcasecmp($currSpice, $s) === 0) ? 'selected' : '';
                    echo "<option value=\"$s\" $selected>$s</option>";
                }
                ?>
              </select>
            </div>
          </div>

          <!-- Image Selection & Preset Gallery -->
          <div class="mb-3">
            <label class="form-label fw-semibold">Recipe Image</label>
            <div class="mb-2">
              <span class="small text-muted d-block mb-1">Pick a preset Sri Lankan dish photo:</span>
              <div class="image-preset-picker mb-2">
                <img src="images/kiribath.jpg" class="preset-img-thumb" data-src="images/kiribath.jpg" title="Kiribath" alt="Kiribath">
                <img src="images/chicken-curry.jpg" class="preset-img-thumb" data-src="images/chicken-curry.jpg" title="Chicken Curry" alt="Chicken Curry">
                <img src="images/parippu.jpg" class="preset-img-thumb" data-src="images/parippu.jpg" title="Dhal Parippu" alt="Dhal Parippu">
                <img src="images/pol-sambol.jpg" class="preset-img-thumb" data-src="images/pol-sambol.jpg" title="Pol Sambol" alt="Pol Sambol">
                <img src="images/kottu-roti.jpg" class="preset-img-thumb" data-src="images/kottu-roti.jpg" title="Kottu Roti" alt="Kottu Roti">
                <img src="images/watalappam.jpg" class="preset-img-thumb" data-src="images/watalappam.jpg" title="Watalappam" alt="Watalappam">
                <img src="images/kokis.jpg" class="preset-img-thumb" data-src="images/kokis.jpg" title="Kokis" alt="Kokis">
                <img src="images/woodapple.jpg" class="preset-img-thumb" data-src="images/woodapple.jpg" title="Woodapple" alt="Woodapple">
              </div>
            </div>
            <input type="text" name="image_url" id="recipeImageUrlInput" class="form-control" placeholder="Or enter an image path / URL (e.g. images/chicken-curry.jpg)" value="<?= clean($editingRecipe['image_url'] ?? '') ?>">
          </div>

          <!-- Ingredients (One per line) -->
          <div class="mb-3">
            <label class="form-label fw-semibold">Ingredients (one per line) <span class="text-danger">*</span></label>
            <textarea name="ingredients" rows="5" class="form-control" placeholder="2 cups raw rice&#10;3 cups thick coconut milk&#10;1 tsp salt" required><?= clean($editingRecipe['ingredients'] ?? '') ?></textarea>
            <div class="invalid-feedback">Please list at least one ingredient.</div>
          </div>

          <!-- Cooking Method (One step per line) -->
          <div class="mb-4">
            <label class="form-label fw-semibold">Cooking Method / Steps (one step per line) <span class="text-danger">*</span></label>
            <textarea name="instructions" rows="6" class="form-control" placeholder="1. Wash rice and simmer in water.&#10;2. Add coconut milk on low heat.&#10;3. Flatten on banana leaf and cut into diamonds." required><?= clean($editingRecipe['instructions'] ?? '') ?></textarea>
            <div class="invalid-feedback">Describe the cooking steps in detail.</div>
          </div>

          <button type="submit" name="save_recipe" class="btn btn-emerald btn-lg w-100 shadow-sm">
            <i class="bi bi-cloud-arrow-up me-2"></i>
            <?= $editingRecipe ? 'Update Recipe' : 'Publish Recipe to Book' ?>
          </button>
        </form>
      </div>
    </div>

    <!-- Right Column: My Published Recipes & Live Card Preview -->
    <div class="col-lg-5">
      <!-- Live Card Preview -->
      <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
        <h3 class="h6 fw-bold text-uppercase text-muted tracking-wider mb-3">
          <i class="bi bi-display me-1 text-emerald"></i> Live Card Preview
        </h3>
        <div class="card modern-recipe-card">
          <div class="card-media-wrap">
            <img src="<?= !empty($editingRecipe['image_url']) ? clean($editingRecipe['image_url']) : 'images/chicken-curry.jpg' ?>" id="previewCardImg" alt="Preview" class="card-media-img" onerror="this.src='images/hero.jpg'">
            <div class="card-category-tag" id="previewCardCategory"><?= clean($editingRecipe['category'] ?? 'Category') ?></div>
          </div>
          <div class="card-body p-3">
            <div class="d-flex align-items-center justify-content-between text-muted small mb-1">
              <span><i class="bi bi-clock me-1"></i><span id="previewCardTime"><?= clean($editingRecipe['cook_time'] ?? '25 mins') ?></span> cook</span>
              <span><i class="bi bi-person-circle me-1"></i><?= clean($username) ?></span>
            </div>
            <h4 class="h5 fw-bold mb-0" id="previewCardTitle"><?= clean($editingRecipe['title'] ?? 'Your Recipe Title') ?></h4>
          </div>
        </div>
      </div>

      <!-- User's Created Recipes List -->
      <div class="card border-0 shadow-sm rounded-4 p-4">
        <h3 class="h5 fw-bold mb-3 d-flex align-items-center justify-content-between">
          <span><i class="bi bi-collection text-emerald me-2"></i>My Recipes (<?= $mine->num_rows ?>)</span>
        </h3>

        <?php if ($mine->num_rows === 0): ?>
          <div class="text-center py-4">
            <div class="text-muted fs-1 mb-2">🥘</div>
            <p class="text-muted small mb-0">You have not created any recipes yet.<br>Use the form to post your first Sri Lankan recipe!</p>
          </div>
        <?php else: ?>
          <div class="list-group list-group-flush">
            <?php while ($r = $mine->fetch_assoc()): ?>
              <div class="list-group-item px-0 py-3 d-flex align-items-center justify-content-between gap-2">
                <div class="d-flex align-items-center gap-3 overflow-hidden">
                  <img src="<?= clean(!empty($r['image_url']) ? $r['image_url'] : 'images/hero.jpg') ?>" class="rounded-3" style="width: 48px; height: 48px; object-fit: cover;" onerror="this.src='images/hero.jpg'">
                  <div class="text-truncate">
                    <h4 class="h6 mb-0 text-truncate"><?= clean($r['title']) ?></h4>
                    <span class="badge bg-secondary-subtle text-secondary small"><?= clean($r['category']) ?></span>
                    <span class="text-muted small ms-1">&middot; <?= date('M j, Y', strtotime($r['created_at'])) ?></span>
                  </div>
                </div>

                <div class="d-flex gap-1 flex-shrink-0">
                  <a href="?edit=<?= $r['id'] ?>" class="btn btn-sm btn-outline-primary" title="Edit Recipe">
                    <i class="bi bi-pencil"></i>
                  </a>
                  <!-- Delete Form with CSRF -->
                  <form method="post" onsubmit="return confirm('Are you sure you want to delete \'<?= addslashes(clean($r['title'])) ?>\'?');" class="d-inline">
                    <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="recipe_id" value="<?= $r['id'] ?>">
                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete Recipe">
                      <i class="bi bi-trash"></i>
                    </button>
                  </form>
                </div>
              </div>
            <?php endwhile; ?>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</main>

<?php require 'includes/footer.php'; ?>
