<?php
// Core helper functions for Lanka Recipe Book
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/db.php';

function clean($v) {
    return htmlspecialchars(trim((string)$v), ENT_QUOTES, 'UTF-8');
}

function is_logged_in() {
    return isset($_SESSION['user_id']);
}

function require_login($base = '') {
    if (!is_logged_in()) {
        header('Location: ' . $base . 'auth/login.php');
        exit;
    }
}

// CSRF Protection
function csrf_token() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verify_csrf($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], (string)$token);
}

// Category icons
function category_icon($c) {
    $c = strtolower(trim((string)$c));
    if (strpos($c, 'rice') !== false) return '🍚';
    if (strpos($c, 'curry') !== false) return '🍛';
    if (strpos($c, 'snack') !== false || strpos($c, 'sambol') !== false) return '🥥';
    if (strpos($c, 'sweet') !== false || strpos($c, 'dessert') !== false) return '🍯';
    if (strpos($c, 'drink') !== false || strpos($c, 'beverage') !== false) return '🥤';
    return '🍽️';
}

// Difficulty badge styling
function difficulty_badge($d) {
    $d = trim((string)$d);
    switch (strtolower($d)) {
        case 'easy':
            return '<span class="badge badge-diff diff-easy"><i class="bi bi-circle-fill me-1"></i>Easy</span>';
        case 'medium':
            return '<span class="badge badge-diff diff-medium"><i class="bi bi-circle-fill me-1"></i>Medium</span>';
        case 'hard':
        case 'expert':
            return '<span class="badge badge-diff diff-hard"><i class="bi bi-circle-fill me-1"></i>Advanced</span>';
        default:
            return '<span class="badge badge-diff diff-easy">Easy</span>';
    }
}

// Spice level badge
function spice_badge($s) {
    $s = strtolower(trim((string)$s));
    if ($s === 'mild') {
        return '<span class="badge badge-spice spice-mild" title="Mild Spice">🌶️ Mild</span>';
    } elseif ($s === 'medium') {
        return '<span class="badge badge-spice spice-medium" title="Medium Spice">🌶️🌶️ Medium</span>';
    } elseif ($s === 'spicy') {
        return '<span class="badge badge-spice spice-hot" title="Hot & Spicy">🌶️🌶️🌶️ Spicy</span>';
    } elseif ($s === 'extra spicy') {
        return '<span class="badge badge-spice spice-fire" title="Fiery Hot">🔥 Fiery</span>';
    }
    return '<span class="badge badge-spice spice-mild">🌶️ Mild</span>';
}

// Fallback image helper
function get_recipe_image($img, $category = '', $base = '') {
    if (!empty($img) && file_exists(__DIR__ . '/../' . ltrim($img, '/'))) {
        return $base . ltrim($img, '/');
    }
    // Return themed default by category
    $c = strtolower((string)$category);
    if (strpos($c, 'curry') !== false) return $base . 'images/chicken-curry.jpg';
    if (strpos($c, 'rice') !== false) return $base . 'images/kiribath.jpg';
    if (strpos($c, 'snack') !== false || strpos($c, 'sambol') !== false) return $base . 'images/pol-sambol.jpg';
    if (strpos($c, 'sweet') !== false) return $base . 'images/watalappam.jpg';
    if (strpos($c, 'drink') !== false) return $base . 'images/woodapple.jpg';
    return $base . 'images/hero.jpg';
}

// Render modern, interactive recipe card
function recipe_card($r, $base = '') {
    $id = (int)$r['id'];
    $title = clean($r['title']);
    $category = clean($r['category']);
    $desc = clean($r['description'] ?? '');
    $prep = clean($r['prep_time'] ?? '15 mins');
    $cook = clean($r['cook_time'] ?? '25 mins');
    $servings = clean($r['servings'] ?? '4 servings');
    $diff = clean($r['difficulty'] ?? 'Easy');
    $spice = clean($r['spice_level'] ?? 'Medium');
    $img = get_recipe_image($r['image_url'] ?? '', $category, $base);
    $ing = clean($r['ingredients']);
    $inst = clean($r['instructions']);
    $author = !empty($r['username']) ? clean($r['username']) : 'Ceylon Heritage';

    echo '
    <div class="col-md-6 col-lg-4 recipe-item" 
         data-id="' . $id . '"
         data-title="' . strtolower($title) . '" 
         data-category="' . $category . '"
         data-difficulty="' . strtolower($diff) . '"
         data-cooktime="' . (int)preg_replace('/[^0-9]/', '', $cook) . '">
      <div class="card modern-recipe-card h-100">
        <div class="card-media-wrap">
          <img src="' . $img . '" alt="' . $title . '" class="card-media-img" loading="lazy" onerror="this.src=\'' . $base . 'images/hero.jpg\'">
          <div class="card-category-tag">' . category_icon($category) . ' ' . $category . '</div>
          <button type="button" class="btn-fav-toggle" data-id="' . $id . '" title="Save to Favorites" aria-label="Save to Favorites">
            <svg class="heart-icon" viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
            </svg>
          </button>
          <div class="card-badge-row">
            ' . difficulty_badge($diff) . '
            ' . spice_badge($spice) . '
          </div>
        </div>
        
        <div class="card-body d-flex flex-column p-4">
          <div class="d-flex align-items-center justify-content-between text-muted small mb-2">
            <span><i class="bi bi-clock me-1"></i>' . $cook . ' cook</span>
            <span><i class="bi bi-people me-1"></i>' . $servings . '</span>
          </div>

          <h3 class="recipe-card-title h5 mb-2">' . $title . '</h3>
          ' . ($desc ? '<p class="recipe-card-desc text-muted small mb-3">' . $desc . '</p>' : '') . '

          <div class="recipe-card-footer mt-auto pt-3 border-top d-flex align-items-center justify-content-between">
            <span class="chef-tag text-muted small"><i class="bi bi-person-circle me-1 text-emerald"></i>' . $author . '</span>
            <button class="btn btn-emerald-sm view-recipe"
              data-bs-toggle="modal" data-bs-target="#recipeModal"
              data-id="' . $id . '"
              data-title="' . $title . '"
              data-category="' . $category . '"
              data-image="' . $img . '"
              data-description="' . $desc . '"
              data-prep="' . $prep . '"
              data-cook="' . $cook . '"
              data-servings="' . $servings . '"
              data-difficulty="' . $diff . '"
              data-spice="' . $spice . '"
              data-author="' . $author . '"
              data-ingredients="' . $ing . '"
              data-instructions="' . $inst . '">
              <span>View Recipe</span>
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"></polyline></svg>
            </button>
          </div>
        </div>
      </div>
    </div>';
}
