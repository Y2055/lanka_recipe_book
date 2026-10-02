<?php
require_once __DIR__ . '/functions.php';
$base = $base ?? '';
$page = $page ?? '';
$currentUser = is_logged_in() ? clean($_SESSION['username'] ?? 'User') : null;
?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= $title ?? 'Lanka Recipe Book - Authentic Sri Lankan Flavours' ?></title>
  <meta name="description" content="Discover, cook, and share authentic Sri Lankan culinary treasures. From fragrant Kiribath and Pol Sambol to fiery Ceylon Chicken Curry.">

  <!-- Favicon icon -->
  <link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>🍛</text></svg>">

  <!-- Google Fonts: Plus Jakarta Sans & Playfair Display -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;0,800;1,600&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

  <!-- Bootstrap 5.3.3 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  
  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

  <!-- Custom Theme Styles -->
  <link href="<?= $base ?>css/style.css" rel="stylesheet">

  <!-- Prevent Theme Flash -->
  <script>
    (function() {
      const savedTheme = localStorage.getItem('lrb_theme') || (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
      document.documentElement.setAttribute('data-bs-theme', savedTheme);
    })();
  </script>
</head>
<body class="site-body">

<!-- Modern Floating / Sticky Top Navigation -->
<nav class="navbar navbar-expand-lg site-navbar sticky-top">
  <div class="container">
    <a class="navbar-brand d-flex align-items-center gap-2" href="<?= $base ?>index.php">
      <div class="brand-logo-badge">
        <i class="bi bi-fire"></i>
      </div>
      <div class="brand-text">
        <span class="brand-name">Lanka<span class="brand-highlight">Recipes</span></span>
        <span class="brand-tagline">Ceylon Kitchen Heritage</span>
      </div>
    </a>

    <div class="d-flex align-items-center gap-2 d-lg-none">
      <!-- Mobile Theme Toggle -->
      <button class="btn btn-icon theme-toggle-btn" aria-label="Toggle theme">
        <i class="bi bi-moon-stars theme-icon-moon"></i>
        <i class="bi bi-sun theme-icon-sun d-none"></i>
      </button>

      <!-- Mobile Hamburger Button -->
      <button class="navbar-toggler custom-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#siteNavbar" aria-controls="siteNavbar" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
    </div>

    <div class="collapse navbar-collapse" id="siteNavbar">
      <ul class="navbar-nav mx-auto mb-2 mb-lg-0 nav-pills-custom">
        <li class="nav-item">
          <a class="nav-link <?= $page==='home'?'active':'' ?>" href="<?= $base ?>index.php">
            <i class="bi bi-house-door me-1"></i> Home
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?= $page==='recipes'?'active':'' ?>" href="<?= $base ?>recipes.php">
            <i class="bi bi-journal-bookmark me-1"></i> Recipes
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?= $page==='contact'?'active':'' ?>" href="<?= $base ?>contact.php">
            <i class="bi bi-envelope-paper me-1"></i> Contact
          </a>
        </li>
      </ul>

      <div class="d-flex align-items-center gap-2 pt-3 pt-lg-0">
        <!-- Desktop Theme Toggle -->
        <button class="btn btn-icon theme-toggle-btn d-none d-lg-inline-flex" title="Toggle Light/Dark Theme" aria-label="Toggle theme">
          <i class="bi bi-moon-stars theme-icon-moon"></i>
          <i class="bi bi-sun theme-icon-sun d-none"></i>
        </button>

        <?php if (is_logged_in()): ?>
          <!-- Logged In User Dropdown -->
          <div class="dropdown">
            <button class="btn btn-user-profile dropdown-toggle d-flex align-items-center gap-2" type="button" data-bs-toggle="dropdown" aria-expanded="false">
              <span class="user-avatar-initial"><?= strtoupper(substr($currentUser, 0, 1)) ?></span>
              <span class="user-name-text d-none d-sm-inline"><?= $currentUser ?></span>
            </button>
            <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 user-dropdown-menu">
              <li class="px-3 py-2 border-bottom">
                <p class="mb-0 small text-muted">Signed in as</p>
                <p class="mb-0 fw-bold"><?= $currentUser ?></p>
              </li>
              <li>
                <a class="dropdown-item py-2" href="<?= $base ?>dashboard.php">
                  <i class="bi bi-speedometer2 me-2 text-emerald"></i> Chef Dashboard
                </a>
              </li>
              <li>
                <a class="dropdown-item py-2" href="<?= $base ?>dashboard.php#addFormSection">
                  <i class="bi bi-plus-circle me-2 text-emerald"></i> Add New Recipe
                </a>
              </li>
              <li><hr class="dropdown-divider"></li>
              <li>
                <a class="dropdown-item py-2 text-danger" href="<?= $base ?>auth/logout.php">
                  <i class="bi bi-box-arrow-right me-2"></i> Log out
                </a>
              </li>
            </ul>
          </div>
        <?php else: ?>
          <!-- Guest Navigation Actions -->
          <a class="btn btn-ghost-nav" href="<?= $base ?>auth/login.php">
            <i class="bi bi-box-arrow-in-right me-1"></i> Log in
          </a>
          <a class="btn btn-saffron-pill shadow-sm" href="<?= $base ?>auth/register.php">
            <span>Sign up free</span>
            <i class="bi bi-arrow-right-short ms-1"></i>
          </a>
        <?php endif; ?>
      </div>
    </div>
  </div>
</nav>

<div class="site-main-wrapper">
