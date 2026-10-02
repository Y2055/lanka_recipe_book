<?php
// Helper functions
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/db.php';

function clean($v) { return htmlspecialchars(trim($v), ENT_QUOTES, 'UTF-8'); }
function is_logged_in() { return isset($_SESSION['user_id']); }
function require_login($base = '') {
    if (!is_logged_in()) { header('Location: ' . $base . 'auth/login.php'); exit; }
}
function category_icon($c) {
    $icons = ['Rice' => '🍚', 'Curry' => '🍛', 'Snacks' => '🥥', 'Sweets' => '🍯', 'Drinks' => '🥤'];
    return $icons[$c] ?? '🍽️';
}
// Renders one recipe card (data-* attributes feed the modal in js/main.js)
function recipe_card($r) {
    $t = clean($r['title']); $c = clean($r['category']);
    echo '<div class="col-md-6 col-lg-4 recipe-item" data-title="' . strtolower($t) . '" data-category="' . $c . '">
      <div class="card recipe-card h-100"><div class="card-body d-flex flex-column">
        <div class="recipe-icon">' . category_icon($r['category']) . '</div>
        <h3 class="h5">' . $t . '</h3>
        <span class="badge badge-cat mb-3 align-self-start">' . $c . '</span>
        <button class="btn btn-outline-success mt-auto view-recipe" data-bs-toggle="modal" data-bs-target="#recipeModal"
          data-title="' . $t . '" data-ingredients="' . clean($r['ingredients']) . '" data-instructions="' . clean($r['instructions']) . '"
          title="See the full recipe">View recipe</button>
      </div></div></div>';
}
