<?php
$page = 'home';
$title = 'Lanka Recipe Book - Authentic Sri Lankan Flavours & Heritage';
require 'includes/header.php';

// Fetch newest 6 recipes
$featured = $conn->query("SELECT * FROM recipes ORDER BY created_at DESC LIMIT 6");
?>

<!-- Modern Hero Banner Section -->
<section class="hero-wrapper">
  <div class="container position-relative" style="z-index: 2;">
    <div class="row align-items-center">
      <div class="col-lg-8 col-xl-7">
        <div class="hero-pill-badge">
          <i class="bi bi-stars"></i>
          <span>Authentic Sri Lankan Culinary Heritage</span>
        </div>
        <h1 class="hero-title">
          Rice, Coconut <br>& A Spark of <span class="brand-highlight">Island Fire.</span>
        </h1>
        <p class="hero-lead">
          Explore time-honoured island recipes passed down through generations. From diamond-cut Kiribath to midnight Kottu and rich Ceylon coconut curries.
        </p>

        <!-- Fast Search Bar -->
        <form action="recipes.php" method="get" class="hero-search-box mb-4">
          <i class="bi bi-search text-muted fs-5"></i>
          <input type="text" name="q" class="hero-search-input" placeholder="Search recipes (e.g. Kiribath, Dhal, Kottu, Sambol)..." autocomplete="off">
          <button type="submit" class="btn btn-saffron-pill py-2 px-4">
            <span>Explore</span>
          </button>
        </form>

        <!-- Category Shortcuts -->
        <div class="d-flex flex-wrap align-items-center gap-2 small">
          <span class="text-white-50 fw-semibold">Popular:</span>
          <a href="recipes.php?cat=Rice" class="badge bg-white bg-opacity-10 text-white text-decoration-none px-3 py-2 rounded-pill hover-lift">🍚 Rice</a>
          <a href="recipes.php?cat=Curry" class="badge bg-white bg-opacity-10 text-white text-decoration-none px-3 py-2 rounded-pill hover-lift">🍛 Curry</a>
          <a href="recipes.php?cat=Snacks" class="badge bg-white bg-opacity-10 text-white text-decoration-none px-3 py-2 rounded-pill hover-lift">🥥 Sambols</a>
          <a href="recipes.php?cat=Sweets" class="badge bg-white bg-opacity-10 text-white text-decoration-none px-3 py-2 rounded-pill hover-lift">🍯 Sweets</a>
        </div>

        <!-- Quick Stats Ribbon -->
        <div class="hero-stats-ribbon">
          <div>
            <div class="stat-item-num">100%</div>
            <div class="stat-item-label">Traditional Taste</div>
          </div>
          <div>
            <div class="stat-item-num">8+</div>
            <div class="stat-item-label">Curated Dishes</div>
          </div>
          <div>
            <div class="stat-item-num">4.9★</div>
            <div class="stat-item-label">Cook Satisfaction</div>
          </div>
          <div>
            <div class="stat-item-num">Free</div>
            <div class="stat-item-label">Open Access</div>
          </div>
        </div>

      </div>
    </div>
  </div>
</section>

<!-- Featured Recipes Grid -->
<section id="featured" class="container py-5">
  <div class="d-flex flex-column flex-md-row md-align-items-end justify-content-between mb-4 pb-2">
    <div>
      <span class="badge bg-emerald-subtle text-emerald px-3 py-1 mb-2">Editor's Picks</span>
      <h2 class="h2 fw-bold mb-1">Featured Sri Lankan Dishes</h2>
      <p class="text-muted mb-0">Our most cherished staples, prepared with authentic ingredients and simple steps.</p>
    </div>
    <div class="mt-3 mt-md-0">
      <a href="recipes.php" class="btn btn-outline-success rounded-pill px-4">
        <span>View all recipes</span>
        <i class="bi bi-arrow-right ms-1"></i>
      </a>
    </div>
  </div>

  <div class="row g-4">
    <?php 
    if ($featured && $featured->num_rows > 0) {
      while ($r = $featured->fetch_assoc()) {
        recipe_card($r);
      }
    } else {
      echo '<div class="col-12 text-muted">No recipes found yet.</div>';
    }
    ?>
  </div>
</section>

<!-- Why Lanka Recipe Book / Heritage Features -->
<section class="bg-body-tertiary py-5 border-top border-bottom border-light-subtle">
  <div class="container py-3">
    <div class="text-center max-w-xl mx-auto mb-5" style="max-width: 680px;">
      <span class="badge bg-warning-subtle text-warning-emphasis px-3 py-1 mb-2">The Ceylon Difference</span>
      <h2 class="h2 fw-bold mb-3">Why Cook With Lanka Recipes?</h2>
      <p class="text-muted">Sri Lankan food is an art of layering flavours: creamy fresh coconut milk, fiery roasted chilli, aromatic cinnamon, goraka, and tempered mustard seeds.</p>
    </div>

    <div class="row g-4">
      <div class="col-md-4">
        <div class="card h-100 p-4 border-0 shadow-sm rounded-4 text-center">
          <div class="rounded-circle bg-emerald-subtle text-emerald mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 64px; height: 64px; font-size: 1.8rem;">
            <i class="bi bi-fire"></i>
          </div>
          <h3 class="h5 fw-bold mb-2">Authentic Clay Pot Method</h3>
          <p class="text-muted small mb-0">Each recipe preserves original cooking techniques, from slow clay pot (chattie) simmering to rapid tava street-kottu chop.</p>
        </div>
      </div>

      <div class="col-md-4">
        <div class="card h-100 p-4 border-0 shadow-sm rounded-4 text-center">
          <div class="rounded-circle bg-warning-subtle text-warning-emphasis mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 64px; height: 64px; font-size: 1.8rem;">
            <i class="bi bi-stopwatch"></i>
          </div>
          <h3 class="h5 fw-bold mb-2">Live Kitchen Timer & Scaler</h3>
          <p class="text-muted small mb-0">Adjust serving sizes on the fly and use our built-in audio cooking timer to ensure your dhal and curries are cooked to perfection.</p>
        </div>
      </div>

      <div class="col-md-4">
        <div class="card h-100 p-4 border-0 shadow-sm rounded-4 text-center">
          <div class="rounded-circle bg-danger-subtle text-danger mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 64px; height: 64px; font-size: 1.8rem;">
            <i class="bi bi-bookmark-heart"></i>
          </div>
          <h3 class="h5 fw-bold mb-2">Save & Share Family Secrets</h3>
          <p class="text-muted small mb-0">Bookmark your favourite dishes to offline storage, print recipe cards, or sign in to contribute your own family heritage recipes.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Call to action community block -->
<section class="container py-5 my-3">
  <div class="card border-0 rounded-4 overflow-hidden shadow-lg" style="background: linear-gradient(135deg, #0f4c3a 0%, #15803d 100%); color: #fff;">
    <div class="card-body p-5">
      <div class="row align-items-center">
        <div class="col-lg-8">
          <h2 class="display-6 fw-bold text-white mb-3">Got a Treasured Family Recipe?</h2>
          <p class="lead text-white-50 mb-4 mb-lg-0">
            Share your grandma's secret Jaffna crab curry, holiday kokis, or coconut rotti with food enthusiasts across Sri Lanka and the world.
          </p>
        </div>
        <div class="col-lg-4 text-lg-end">
          <?php if (is_logged_in()): ?>
            <a href="dashboard.php" class="btn btn-saffron-pill btn-lg px-4 py-3 shadow">
              <i class="bi bi-plus-circle me-1"></i> Post a Recipe Now
            </a>
          <?php else: ?>
            <a href="auth/register.php" class="btn btn-saffron-pill btn-lg px-4 py-3 shadow">
              <span>Join As a Chef</span>
              <i class="bi bi-arrow-right ms-2"></i>
            </a>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</section>

<?php require 'includes/footer.php'; ?>
